<?php
/**
 * Hook for keyword TEST
 */

/**
 * Define your SMS processor function here:
 * - $keyword   : keyword of SMS being processed
 * - $param     : SMS parameter, returned by 
 *                PHP.gammu-fetch-sms.function sms_fetch_item($sms_id);
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
 *      [1]=>string(4) "Other SMS Part..."
 *    }
 *  }
 * 
 * You can process the SMS here.
 * You can return false to cancel the process,
 * or process it  - such as database operation, 
 * log, etc. and return true 
 * to the daemon to indicate that the SMS
 * has been processed.
 */
 
/**
 * 
 * WARNING!
 * DO NOT CHANGE SYSTEM GENERATED VARIABLE & FUNCTION NAMES!
 * 
 */
$my_test_kategori = 'Inkubator bayi';
$my_test_keyword = 'TEST';
$my_test_description = 'System Test';
$my_test_sms_format = 'TEST';
$my_test_sms_sample = 'TEST';
 
/**
 * Define your hook for specific SMS keyword. 
 * Return true to mark SMS as processed and 
 * will be passed on next processing.
 * Return false will cause the SMS to be 
 * reprocessed infinitely until you return true.
 */
function my_hook_test_function($keyword, $params)
{
    global $app_name, $app_version, $nama_modem;
    // Sometime, you don't need to reply SMS from non-user number,
    // such as SMS from Service Center, message center, 
    // or promotional SMS:
    if (strlen($params['sender'])<=6) {
        return true;
    }
    else
    {
        // If the SMS requires reply, do it as follows:
        /*
         * return sms_send($params['sender'], 
         *                   'Thank your for texting us.', 
         *                   $nama_modem);
         */
        // or simply return true without replying it:
        /*
         * return true;
         */
        return sms_send($params['sender'], "Test OK. $app_name v.$app_version siap.", $nama_modem);
    }    
}

/**
 * Callback for register event:
 *  - Keyword: your keyword.
 */
function my_hook_test_register_callback_function($keyword)
{
    // create your table here, etc., and...
    return true;    
}

/**
 * Callback for unregister event:
 *  - Keyword: your keyword.
 */
function my_hook_test_unregister_callback_function($keyword)
{
    // drop your table here, etc., and...
    return true;    
}

/**
 * Callback for activation event:
 *  - Keyword: your keyword.
 */
function my_hook_test_activation_callback_function($keyword)
{
    // create your table here, etc., and...
    // exec_query('create table if not exists `unknown_sms_data`(id int(10) not null auto_increment, primary key(id)) engine=MyISAM');
    return true;    
}

/**
 * Callback for deactivation event:
 *  - Keyword: your keyword.
 */
function my_hook_test_deactivation_callback_function($keyword)
{
    // drop your table here, etc., and...
    // exec_query('drop table if exists `unknown_sms_data`');
    // you can leave your database entries for next time your hook reactivated.
    return true;    
}


/**
 * Register your keyword test and its hook function to database. 
 * Database registration is not required by SMS daemon, 
 * but is required - by SMS parser in database 
 * - to identify and classify each arriving SMS.  
 */
/**
 * You are not needed to execute two following functions.
 * System will do it for you!
 */
/*
keyword_hook_register(
    $my_test_keyword, 
    'my_hook_test_function', // hook function name.
    __FILE__, // current file.
    $my_test_description, 
    $my_test_sms_format, 
    $my_test_sms_sample,
    $my_test_kategori
);
*/
/**
 * keyword_hook_unregister(
 *     $my_test_keyword, 
 *     'my_hook_test_function'
 * );
 */
?>