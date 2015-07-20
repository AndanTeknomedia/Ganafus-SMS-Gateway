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
$my_default_keyword = 'UNKNOWN';
$my_default_description = 'Ini adalah default hook untuk keyword unknown';
 
/**
 * Define your hook for specific SMS keyword. 
 * Return true to mark SMS as processed and will be passed on next processing.
 * Return false will cause the SMS to be reprocessed infinitely until you return true.
 */
function my_hook_default_function($keyword, $params)
{
    global  $nama_modem;
    // Sometime, you don't need to reply SMS from non-user number,
    // such as SMS from Service Center, message center, or promotional SMS:
    if (strlen($params['sender'])<=6) {
        return true;
    }
    else
    {
        // If the SMS requires reply, do it as follows:
        return sms_send($params['sender'], 'SMS Anda tidak dikenali.', $nama_modem);
        // or simply return true without replying it:
        // return true;
    }    
}

/**
 * Register your keyword and its hook function to database . This is not required by SMS daemon, 
 * but is required - by SMS parser in database - to identify and classify each arriving SMS.  
 */
keyword_hook_register($my_default_keyword, 'my_hook_default_function', __FILE__, $my_default_description);
// keyword_hook_unregister($my_default_keyword, 'my_hook_default_function');

?>