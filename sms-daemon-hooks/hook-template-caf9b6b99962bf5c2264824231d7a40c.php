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
$my_info_kategori = 'Inkubator';
$my_info_keyword = 'INFO';
$my_info_description = 'Meminta info data inkubator';
$my_info_sms_format = 'INFO*KATAKUNCI';
$my_info_sms_sample = 'INFO*PINJAM';
 
/**
 * Define your hook for specific SMS keyword. 
 * Return true to mark SMS as processed and 
 * will be passed on next processing.
 * Return false will cause the SMS to be 
 * reprocessed infinitely until you return true.
 */
function my_hook_info_function($keyword, $params)
{
    global  $nama_modem, $my_info_keyword;
    // Sometime, you don't need to reply SMS from non-user number,
    // such as SMS from Service Center, message center, or promotional SMS:
    $param = $params['params'];
    if (strlen($params['sender'])<=6) {
        return true;
    }
    else
    {
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
            $kirim = fetch_query("select format_sms, contoh_sms from sms_keywords where upper(keyword) = upper('$info_kw')" );
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
                $pesan1 = 'Format SMS: '.$kirim[0]['format_sms'];
                $pesan2 = 'Contoh SMS: '.$kirim[0]['contoh_sms'];
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
 * Init function:
 *  single param: keyword
 */
/*
function keyword_info_init($keyword)
{
    echo "Initializing of keyword ".$keyword."\n";
    // return false;
    exec_query("create table if not exists sms_inbox_keyword_info(
        id int(8) not null auto_increment, 
        sms_time timestamp not null default current_timestamp,
        sms_text text null,
       	primary key (id)
        ) engine=MyISAM");
}
*/

/**
 * Register your keyword info and its hook function to database. 
 * Database registration is not required by SMS daemon, 
 * but is required - by SMS parser in database 
 * - to identify and classify each arriving SMS.  
 */
keyword_hook_register(
    $my_info_keyword, 
    'my_hook_info_function', // hook function name.
    __FILE__, // current file.
    $my_info_description, 
    $my_info_sms_format, 
    $my_info_sms_sample,
    $my_info_kategori
);

/**
 * keyword_hook_unregister(
 *     $my_info_keyword, 
 *     'my_hook_info_function'
 * );
 */
?>