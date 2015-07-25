<?php
/**
 * Hook for keyword PINJAM
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
 *    ["text"]=>string(4) "PINJAM"
 *    ["keyword"]=>string(4) "PINJAM"
 *    ["status"]=>NULL
 *    ["params"]=>array(1) 
 *    {
 *      [0]=>string(4) "PINJAM"
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
$my_pinjam_kategori = 'Inkubator';
$my_pinjam_keyword = 'PINJAM';
$my_pinjam_description = 'SMS Peminjaman Inkubator';
$my_pinjam_sms_format = 'PINJAM*NAMA_BAYI*TGL_LAHIR*TGL_PULANG_RS*CM_PJGLAHIR*KG_BERATLAHIR*<SEHAT/SAKIT>*NAMA_RS*NM_DOKTER/BIDAN*NO_KK*ALAMAT*NAMA_IBU*NAMA_AYAH';
$my_pinjam_sms_sample = 'PINJAM*DIAN KHAMSAWARNI*21/09/2015*23/09/2015*28*3,2*SEHAT*RSU Wahidin*Dr. Marhamah, Sp.OG*9288299288*BTN Hamzy E8/A*RINA MAWARNI*ARIFIN ADINEGORO';
 
/**
 * Define your hook for specific SMS keyword. 
 * Return true to mark SMS as processed and 
 * will be passed on next processing.
 * Return false will cause the SMS to be 
 * reprocessed infinitely until you return true.
 */
function my_hook_pinjam_function($keyword, $params)
{
    global  $nama_modem;
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
        return sms_send($params['sender'], 'Your SMS has been processed.', $nama_modem);
    }    
}

/**
 * Register your keyword pinjam and its hook function to database. 
 * Database registration is not required by SMS daemon, 
 * but is required - by SMS parser in database 
 * - to identify and classify each arriving SMS.  
 */
keyword_hook_register(
    $my_pinjam_keyword, 
    'my_hook_pinjam_function', // hook function name.
    __FILE__, // current file.
    $my_pinjam_description, 
    $my_pinjam_sms_format, 
    $my_pinjam_sms_sample,
    $my_pinjam_kategori
);

/**
 * keyword_hook_unregister(
 *     $my_pinjam_keyword, 
 *     'my_hook_pinjam_function'
 * );
 */
?>