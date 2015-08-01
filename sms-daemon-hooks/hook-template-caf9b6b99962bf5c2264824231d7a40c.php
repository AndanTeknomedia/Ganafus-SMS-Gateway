<?php
/**
 * Hook for keyword INFO
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
 *    ["text"]=>string(4) "INFO"
 *    ["keyword"]=>string(4) "INFO"
 *    ["status"]=>NULL
 *    ["params"]=>array(1) 
 *    {
 *      [0]=>string(4) "INFO"
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
$my_info_kategori = 'Inkubator bayi';
$my_info_keyword = 'INFO';
$my_info_description = 'Informasi peminjaman inkubator';
$my_info_sms_format = 'INFO*KATAKUNCI';
$my_info_sms_sample = 'INFO*STOK';
 
/**
 * Define your hook for specific SMS keyword. 
 * Return true to mark SMS as processed and 
 * will be passed on next processing.
 * Return false will cause the SMS to be 
 * reprocessed infinitely until you return true.
 */
function my_hook_info_function($keyword, $params)
{
    global $app_name, $app_version, $nama_modem;
    global $my_info_kategori, $my_info_keyword;
    // Sometime, you don't need to reply SMS from non-user number,
    // such as SMS from Service Center, message center, 
    // or promotional SMS:
    $param = $params['params'];
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
        $c = count($param); 
        $sql_kunci = "select k.keyword from sms_keywords k where (not (upper(k.keyword) in ('UNKNOWN',upper('$my_info_keyword')) ))
                     and (k.kategori in (select s.kategori from sms_keywords s where s.keyword = '$my_info_keyword')) order by k.keyword asc";
        if ($c!=2)
        {
            $pesan1 = 'Terimakasih. Ketik '.strtoupper($my_info_keyword).DELIMITER.'KATAKUNCI untuk bantuan.';
            $kunci  = fetch_query($sql_kunci);
            $pesan2 = '';            
            foreach($kunci as $i=>$item){
                $pesan2.=','.$item['keyword'];
            }
            $pesan2 = 'Kata kunci tersedia: '.substr($pesan2, 1);
            unset($kunci);
        }
        else
        {
            $info_kw = strtoupper($param[1]);
            $kirim = keyword_fetch_by_keyword($info_kw);            
            // $kirim = fetch_query("select format_sms, contoh_sms from sms_keywords where upper(keyword) = upper('$info_kw')" );
            if (count($kirim)==0)
            {
                $pesan1 = 'Kata kunci '.strtoupper($info_kw).' tidak ditemukan. Ketik '.strtoupper($my_info_keyword).' untuk bantuan.';
                $kunci  = fetch_query($sql_kunci);
                $pesan2 = '';            
                foreach($kunci as $i=>$item){
                    $pesan2.=','.$item['keyword'];
                }
                $pesan2 = 'Kata kunci tersedia: '.substr($pesan2, 1);
                unset($kunci);    
            }       
            else
            {
                $pesan1 = 'Format SMS: '.$kirim['sms_format'];
                $pesan2 = 'Contoh SMS: '.$kirim['sms_sample'];
            }
            unset($kirim);
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
 * Callback for register event:
 *  - Keyword: your keyword.
 */
function my_hook_info_register_callback_function($keyword)
{
    // create your table here, etc., and...
    return true;    
}

/**
 * Callback for unregister event:
 *  - Keyword: your keyword.
 */
function my_hook_info_unregister_callback_function($keyword)
{
    // drop your table here, etc., and...
    return true;    
}

/**
 * Callback for activation event:
 *  - Keyword: your keyword.
 */
function my_hook_info_activation_callback_function($keyword)
{
    // create your table here, etc., and...
    // exec_query('create table if not exists `unknown_sms_data`(id int(10) not null auto_increment, primary key(id)) engine=MyISAM');
    return true;    
}

/**
 * Callback for deactivation event:
 *  - Keyword: your keyword.
 */
function my_hook_info_deactivation_callback_function($keyword)
{
    // drop your table here, etc., and...
    // exec_query('drop table if exists `unknown_sms_data`');
    // you can leave your database entries for next time your hook reactivated.
    return true;    
}


/**
 * Register your keyword info and its hook function to database. 
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
    $my_info_keyword, 
    'my_hook_info_function', // hook function name.
    __FILE__, // current file.
    $my_info_description, 
    $my_info_sms_format, 
    $my_info_sms_sample,
    $my_info_kategori
);
*/
/**
 * keyword_hook_unregister(
 *     $my_info_keyword, 
 *     'my_hook_info_function'
 * );
 */
?>