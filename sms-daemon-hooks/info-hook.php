<?php
/**
 * Define your SMS processor function here:
 * - $keyword   : keyword of SMS being processed
 * - $param     : SMS parameter, returned by PHP.gammu-fetch-sms.function sms_fetch_item($sms_id);
 * 
 * Here is $param structure:
 *  array(8) {
 *    ["id"]=>string(17) "24106959724609540"
 *    ["time_stamp"]=>string(19) "2015-07-15 23:15:57"
 *    ["udh"]=>string(0) ""
 *    ["sender"]=>string(14) "+6282345798006"
 *    ["text"]=>string(4) "TEST"
 *    ["keyword"]=>string(4) "TEST"
 *    ["status"]=>NULL
 *    ["params"]=>array(1) 
 *    {
 *      [0]=>string(4) "TEST"
 *    }
 *  }
 * 
 * You can process the SMS here.
 * You can return false to cancel the process,
 * or process it  - such as database operation, log, etc. and return true 
 * to the daemon to indicate that the SMS has been processed.
 */
$my_info_keyword = KW_INFO;
$my_info_description = 'Ini adalah hook untuk SMS dengan keyword INFO';
$my_info_sms_format = FORMAT_SMS_INFO;
$my_info_sms_sample = CONTOH_SMS_INFO;
 
/**
 * Define your hook for specific SMS keyword. 
 * Return true to mark SMS as processed and will be passed on next processing.
 * Return false will cause the SMS to be reprocessed infinitely until you return true.
 */
function my_hook_info_function($keyword, $params)
{
    global  $nama_modem;
    // Sometime, you don't need to reply SMS from non-user number,
    // such as SMS from Service Center, message center, or promotional SMS:
    $param = $params['params'];
    if (strlen($params['sender'])<=6) {
        return true;
    }
    else
    {
        $c = count($param); 
        $dp = 'Terimakasih. Ketik '.FORMAT_SMS_INFO.' untuk bantuan.';
        if ($c!=2)
        {
            $pesan1 = $dp;
            $pesan2 = '';
        }
        else
        {
            switch(strtoupper($param[1]))
            {
                case KW_PINJAM:
                    $pesan1 = FORMAT_SMS_PINJAM;
                    $pesan2 = CONTOH_SMS_PINJAM;
                    break;
                case KW_KEMBALI:
                    $pesan1 = FORMAT_SMS_KEMBALI;
                    $pesan2 = CONTOH_SMS_KEMBALI;
                    break;
                case KW_MONITOR:
                    $pesan1 = FORMAT_SMS_MONITOR;
                    $pesan2 = CONTOH_SMS_MONITOR;
                    break;
                default:
                    $pesan1 = $dp;      
                    $pesan2 = '';          
            }        
        }
        $ok1 = sms_send($params['sender'], $pesan1, $nama_modem);
        $ok2 = true;
        if (!empty($pesan2))
        {
            $ok2 = sms_send($params['sender'], $pesan2, $nama_modem);
        }
        return ($ok1 && $ok2);
    }    
}

/**
 * Register your keyword and its hook function to database . This is not required by SMS daemon, 
 * but is required - by SMS parser in database - to identify and classify each arriving SMS.  
 */
keyword_hook_register($my_info_keyword, 'my_hook_info_function', __FILE__, $my_info_description, $my_info_sms_format, $my_info_sms_sample);
// keyword_hook_unregister($my_info_keyword, 'my_hook_info_function');


?>