<?php

include_once(str_replace("\\", "/", dirname(dirname(__FILE__))."/cores/db.php"));
include_once(str_replace("\\", "/", dirname(dirname(__FILE__))."/gammu/gammu-cores.php"));
include_once(str_replace("\\", "/", dirname(dirname(__FILE__))."/cores/definition.php"));

/**
 * SMS Parser constants. Sesuaikan dengan trigger inbox_after_insert pada tabel inbox di database.
 */
define('DELIMITER', '*');	
define('KW_TEST', 'TEST');
define('KW_INFO', 'INFO');
define('KW_PINJAM', 'PINJAM');
define('KW_KEMBALI', 'KEMBALI');
define('KW_MONITOR', 'MONITOR');
define('KW_UNKNOWN', 'UNKNOWN');

$app_name       = SP_APP_NAME_SHORT;
$app_version    = SP_APP_VERSION;
$gammu_id       = GAMMU_CREATOR_ID;

$last_config_name       = 'last_processed_valid_sms_id';
$data_count_to_process  = 10; // execute 10 data every minute - as this task run
$nama_modem             = fetch_one_value("select coalesce((select nama_modem from modem_gateway order by id desc limit 0,1),'')");

/**
 * SMS processor utilities:
 */

/**
 * Split sms by DELIMITER and 
 *  return array if sms is valid
 *  return false if sms is invalid/empty 
 */
/*
$options = array('Item1','Item2');
echo 'Here is the array: ';
print_r($options); 
echo '<hr>';
echo 'And then joined:';
$opts = implode('|', $options); 
echo $opts;
die();
*/

function sms_create_options($options = array())
{    
    if (!is_array($options)) { return ''; } else { return '<'.implode('|', $options).'>'; }    
}

function sms_create_format($keyword, $params = array())
{
    $params = array_merge(array(strtoupper($keyword)), $params);
    return implode(DELIMITER, $params);
}
// FORMAT SMS
define('FORMAT_SMS_PINJAM',sms_create_format(KW_KEMBALI, array(
    'NAMA_BAYI',
    'TGL_LAHIR',
    'TGL_PULANG_RS',    
    'CM_PJGLAHIR',  
    'KG_BERATLAHIR',      
    sms_create_options(array('SEHAT','SAKIT')),
    'NAMA_RS',
    'NM_DOKTER/BIDAN',
    'NO_KK',
    'NAMA_IBU',
    'NAMA_AYAH'
)));
define('FORMAT_SMS_KEMBALI',sms_create_format(KW_KEMBALI, array('KODEPINJAM','CM_PANJANGBAYI','KG_BERATBAYI',sms_create_options(array('SEHAT','SAKIT')))));
define('FORMAT_SMS_MONITOR',sms_create_format(KW_MONITOR, array('KODEPINJAM','CM_PANJANGBAYI','KG_BERATBAYI',sms_create_options(array('SEHAT','SAKIT')))));
define('FORMAT_SMS_INFO',sms_create_format(KW_INFO,array(sms_create_options(array(KW_PINJAM, KW_KEMBALI, KW_MONITOR)))));
// CONTOH SMS
define('CONTOH_SMS_PINJAM',sms_create_format(KW_KEMBALI, array(
    'DIAN KHAMSAWARNI',
    '21/09/2015',
    '23/09/2015',
    '28',  
    '3,2',          
    'SEHAT',
    'RSU Wahidin',
    'Dr. Marhamah, Sp.OG',
    '9288299288',
    'RINA MAWARNI',
    'ARIFIN ADINEGORO'
)));
define('CONTOH_SMS_KEMBALI',sms_create_format(KW_KEMBALI, array('F8F4902B','31','3,5','SEHAT','SAKIT')));
define('CONTOH_SMS_MONITOR',sms_create_format(KW_MONITOR, array('F8F4902B','31','3,5','SEHAT','SAKIT')));
define('CONTOH_SMS_INFO',sms_create_format(KW_INFO,array(KW_PINJAM)));

/**
 * echo CONTOH_SMS_PINJAM.'<hr>';
 * echo CONTOH_SMS_KEMBALI.'<hr>';
 * echo CONTOH_SMS_MONITOR.'<hr>';
 * die();
 */

function sms_get_parameter($pesan)
{
    if (strlen($pesan)==0) { return false; }
    $res = explode(DELIMITER,$pesan);
    if (count($res)<1)
    {
        return false;
    }
    else
    {
        $res[0] = strtoupper($res[0]); //keyword in uppercase.
        return $res;
    }
}

function sms_generate_send_query($orig_sender, $sms_text)
{
    global $nama_modem;
    return "insert into outbox_tmp(            
        DestinationNumber,
        TextDecoded,
        SenderID,
        CreatorID
        ) values (
        '$orig_sender', 
        '$sms_text', 
        '$nama_modem', 
        '".GAMMU_CREATOR_ID."'
        )";        
}

function sms_status_dibalas($sms_id)
{
    return exec_query("update sms_valid set diproses = 'Dibalas' where id = '$sms_id'");
}
/**
 * Process keywords:
 */
function sms_process_keyword_test($orig_sender, $sms_params)
{
    $insert = sms_generate_send_query($orig_sender, SP_APP_NAME_SHORT." v.".SP_APP_VERSION." siap melayani Anda. Ketik ".KW_INFO." utk bantuan."); 
    if (exec_query($insert)) { return true; } else { return false; }         
}

function sms_process_keyword_unknown($orig_sender, $sms_params)
{
    $insert = sms_generate_send_query($orig_sender, 'SMS Anda tidak dikenali. Ketik '.KW_INFO.' utk bantuan.');
    if (exec_query($insert)) { return true; } else { return false; }     
}

function sms_process_improper_format($orig_sender, $sms_params)
{
    $insert = sms_generate_send_query($orig_sender, 'Format SMS salah. Ketik '.KW_INFO.DELIMITER.$sms_params[0].' utk bantuan.');
    if (exec_query($insert)) { return true; } else { return false; }     
}

function sms_process_keyword_info($orig_sender, $sms_params)
{
    $c = count($sms_params); 
    $dp = 'Terimakasih. Ketik '.FORMAT_SMS_INFO.' untuk bantuan.';
    if ($c!=2)
    {
        $pesan1 = $dp;
        $pesan2 = '';
    }
    else
    {
        switch(strtoupper($sms_params[1]))
        {
            case KW_PINJAM:
                $pesan = FORMAT_SMS_PINJAM;
                $pesan2 = CONTOH_SMS_PINJAM;
                break;
            case KW_KEMBALI:
                $pesan = FORMAT_SMS_KEMBALI;
                break;
            case KW_MONITOR:
                $pesan = FORMAT_SMS_MONITOR;
                break;
            default:
                $pesan = $dp;                
        }        
    }
    $insert = sms_generate_send_query($orig_sender, $pesan);
    if (exec_query($insert)) { return true; } else { return false; }     
}

$last_id    = fetch_one_value("select coalesce((select config_value from configs where config_name = '$last_config_name'),0)");
$sms_query  = 
    "select sv.id, sv.udh, sv.waktu_terima, sv.pengirim, sv.sms, sv.jenis, sv.param_count, sv.diproses
    from sms_valid sv where sv.id > $last_id and sv.diproses = 'Ditunda'
    order by sv.waktu_terima asc, sv.id asc limit 0,$data_count_to_process";
$smses      = fetch_query($sms_query);

$last_processed_id = $last_id;

if (count($smses)==0)
{
    //No new message to process
    exit('No new message to process');
}
else
{
    foreach ($smses as $sms)
    {
        // update $last_processed_id to every sms being processed - regardless any occurring error.
        $last_processed_id = $sms['id'];
        try {
            $keyword    = strtoupper($sms['jenis']);
            $params     = sms_get_parameter($sms['sms']);
            switch ($keyword)
            {
                case KW_TEST:
                    if (sms_process_keyword_test($sms['pengirim'], $params)) { sms_status_dibalas($sms['id']); }
                    break;
                case KW_INFO:
                    if (sms_process_keyword_info($sms['pengirim'], $params)) { sms_status_dibalas($sms['id']); }
                    break;
                case KW_PINJAM:
                    echo 'Pinjam keyword';
                    break;
                case KW_MONITOR:
                    echo 'Monitor keyword';
                    break;
                case KW_KEMBALI:
                    echo 'Kembali keyword';
                    break;
                default:
                    if (sms_process_keyword_unknown($sms['pengirim'], $params)) { sms_status_dibalas($sms['id']); }
            }    
            // echo '<hr>';    
            exec_query("insert into configs (config_name, config_value) values ('$last_config_name', '$last_processed_id')
                       on duplicate key update config_value = values(config_value)");
        }
        catch (Exception $e)
        {
            
        }       
    }
}
unset($smses);

?>