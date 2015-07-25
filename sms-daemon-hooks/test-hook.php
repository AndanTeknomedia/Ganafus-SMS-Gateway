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
 * You can return false to cancel process,
 * or process it  - such as database operation, log, etc. and return true 
 * to the daemon to indicate that the SMS has been processed.
 */
$my_test_kategori = 'Inkubator';
$my_test_keyword = 'TEST';
$my_test_description = 'Ini adalah keyword test';
$my_info_sms_format = 'TEST';
$my_info_sms_sample = 'TEST';
 
/**
 * Define your hook for specific SMS keyword. 
 * Return true to mark SMS as processed and will be passed on next processing.
 * Return false will cause the SMS to be processed infinitely until you return true.
 */
function my_hook_test_function($keyword, $params)
{
    global $app_name, $app_version, $nama_modem;
    // hanya balas SMS dari pengririm real.
    // SMS dari service center gausah dibalas:
    if (strlen($params['sender'])<=6) {
        return true;
    }
    else
    {
        return sms_send($params['sender'], "Test OK. $app_name v.$app_version siap.", $nama_modem);
    }
}

keyword_hook_register($my_test_keyword, 
    'my_hook_test_function', 
    __FILE__, 
    $my_test_description, 
    $my_info_sms_format, 
    $my_info_sms_sample, 
    $my_test_kategori);
// keyword_hook_unregister($my_test_keyword, 'my_hook_test_function');

?>