<?php
// error_reporting(E_ALL);
// error_reporting(E_ERROR | E_WARNING & ~E_NOTICE);
// error_reporting(E_ALL ^ E_NOTICE);
/**
 * This function retrieves CLI arguments or server $_GETs
 * $_GET's key and value are merged, so
 * CLI argv -DATA:TEST is equivalent to $_GET's &-DATA=:TEST 
 * First item of result always be the current file path.
 */

function get_command_args()
{
    global  $argv;
    $ar = array();
    if (PHP_SAPI === 'cli') {

        foreach($argv as $item)
        {
            $ar[] = $item;
        }
    }
    else 
    {
        $ar[] = __FILE__;
        foreach($_GET as $key=>$item)
        {
            $ar[] = $key.''.urldecode($item);
        }
    }
    return $ar;
}
//  var_dump(get_command_args());

include_once(str_replace("\\", "/", dirname(dirname(__FILE__))."/cores/db.php"));
include_once(str_replace("\\", "/", dirname(dirname(__FILE__))."/cores/definition.php"));
include_once(str_replace("\\", "/", dirname(dirname(__FILE__))."/gammu/gammu-cores.php"));
include_once(str_replace("\\", "/", dirname(dirname(__FILE__))."/gammu/gammu-fetch-sms.php"));

error_reporting(E_ALL ^ E_NOTICE);

if (!USE_GAMMU) {die('Not supported on this server. Gammu is not used.'); }

$app_name       = SP_APP_NAME_SHORT;
$app_version    = SP_APP_VERSION;
$gammu_id       = GAMMU_CREATOR_ID;

/**
 * This daemon will look for hook files in this directory and load them to processor engine: 
 */
$hook_dir = str_replace("\\", "/", dirname(dirname(__FILE__)))."/sms-daemon-hooks";
$hd = opendir($hook_dir);
if (!($hd===false))
{
    // Include definitions first (if any exists):
    rewinddir($hd);
    while ($hook = readdir($hd))
    {
        $hf = $hook_dir."/$hook";
        if (is_file($hf) && (substr($hook, strlen($hook)-8)=='.inc.php'))
        {
            // echo $hf .'<hr>';
            include_once($hf);
        }
    }  
    // Include hooks:
    rewinddir($hd);
    while ($hook = readdir($hd))
    {
        $hf = $hook_dir."/$hook";
        if (is_file($hf) && (substr($hook, strlen($hook)-4)=='.php') && (substr($hook, strlen($hook)-8)!='.inc.php'))
        {
            // echo $hf .'<hr>';
            include_once($hf);
        }
    }   
    closedir($hd);
}

$data_count_to_process  = 10; // execute 10 data every minute - as this task run
$nama_modem             = fetch_one_value("select coalesce((select nama_modem from modem_gateway order by id desc limit 0,1),'')");
$last_id                = fetch_one_value("select coalesce((select config_value from configs where config_name = '".LAST_ID_CONFIG_NAME."'),0)");
/*
$sms_query  = 
    "select sv.id, sv.udh, sv.waktu_terima, sv.pengirim, sv.sms, sv.jenis, sv.param_count, sv.diproses
    from sms_valid sv where sv.id > $last_id and sv.diproses = 'Ditunda'
    order by sv.waktu_terima asc, sv.id asc limit 0, $data_count_to_process";
*/
$sms_query  = 
    "select sv.id from sms_valid sv where sv.id > $last_id and sv.diproses = 'Ditunda' 
    order by sv.waktu_terima asc, sv.id asc limit 0, $data_count_to_process";
$smses      = fetch_query($sms_query);

$last_processed_id = $last_id;
$all_ok = true;

if (count($smses)==0)
{
    // No new message to process
    exit('No new message to process');
}
else
{
    foreach ($smses as $sms)
    {
        $last_processed_id = $sms['id'];
        $sms_item          = sms_fetch_item($last_processed_id);        
        $sms_keyword       = strtoupper($sms_item['params'][0]);
        foreach ($_SMS_PROCESSOR_DAEMON_HOOKS as $keyword => $hook_function)
        {
            if ($sms_keyword == strtoupper($keyword))
            {
                // echo $last_processed_id.'- '.$keyword.'<br>';
                $result = call_user_func($_SMS_PROCESSOR_DAEMON_HOOKS[$keyword], $keyword, $sms_item);
                /**
                 * If keyword hook returned true,
                 * mark it's status as 'Dibalas'
                 * and set it's ID as last processed SMS ID.
                 */
                if ($result)
                {
                    sms_status_dibalas($last_processed_id);
                    sms_set_last_processed_id($last_processed_id);
                    
                }
            }
        }
             
    }
}
unset($smses);

?>