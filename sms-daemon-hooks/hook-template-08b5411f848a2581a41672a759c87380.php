<?php
/**
 * Hook for keyword MONITOR
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
 *    ["text"]=>string(4) "MONITOR"
 *    ["keyword"]=>string(4) "MONITOR"
 *    ["status"]=>NULL
 *    ["params"]=>array(1) 
 *    {
 *      [0]=>string(4) "MONITOR"
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
$my_monitor_kategori = 'Inkubator bayi';
$my_monitor_keyword = 'MONITOR';
$my_monitor_description = 'SMS Input Data Monitoring Perkembangan Bayi';
$my_monitor_sms_format = 'MONITOR*KODEPINJAM*CM_PANJANGBAYI*CM_BERATBAYI*<SEHAT/SAKIT>';
$my_monitor_sms_sample = 'MONITOR*323431-363135-38*29.2*3.10*SEHAT';
 
/**
 * Define your hook for specific SMS keyword. 
 * Return true to mark SMS as processed and 
 * will be passed on next processing.
 * Return false will cause the SMS to be 
 * reprocessed infinitely until you return true.
 * 
 * AVOID HEAVY LONG PROCESS HERE. Database initializations are better be done on activation callback.
 */
function my_hook_monitor_function($keyword, $params)
{
    global $app_name, $app_version, $nama_modem;
    global $my_kembali_sms_format, $my_kembali_sms_sample;
    // Sometime, you don't need to reply SMS from non-user number,
    // such as SMS from Service Center, message center, 
    // or promotional SMS:
    $valid_param_count = 5;
    // pre( $params);
    // return true;
    if (strlen($params['sender'])<=6) {
        return true;
    }
    else
    {
        if (count($params['params'])!=$valid_param_count){
            sms_send($params['sender'], '1/2. SMS tidak valid. Jumlah parameter data harus '.$valid_param_count.'.', $nama_modem);
            sms_send($params['sender'], '2/2. Format SMS: '.$my_monitor_sms_format, $nama_modem);
            sms_send($params['sender'], '3/2. Contoh SMS: '.$my_monitor_sms_sample, $nama_modem);
        }
        else
        {
            $kode_pinjam = strtoupper($params['params'][1]);
            // cek kode pinjam, jika ID = 0, berarti kode pinjam tidak valid:
            $id_pinjam   = fetch_one_value("select coalesce( (
                    select UUID_SHORT() id from inkubator_pinjam p where upper(p.kode_pinjam) = '$kode_pinjam'
                    and p.status_pinjam  = 'Disetujui' 
                ),0) as id");
            if ($id_pinjam == 0)
            {
                sms_send($params['sender'], 'Kode Pinjam tidak ditemukan: '.$kode_pinjam.'.', $nama_modem);    
            }   
            else
            {         
                // proses SMS dan insert ke table `inkubator_kembali`:
                // Sample: KEMBALI*323431-353131-35*30*3.60*SEHAT;
                $p_pjg              = trim($params['params'][ 2]);
                $p_berat            = trim($params['params'][ 3]);
                $p_kondisi          = strtoupper(trim($params['params'][ 4]));                
                // cek tangal, panjang dan berat apakah formatnya sesuai atau tidak.
                // $p_validate_tgl     = '/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/'; // dd/mm/yyyy
                $p_validate_pjg     = '/^[0-9]{1,2}+([\,\.][0-9]{1,2})?$/'; // max2digits[.,]max2digits
                if (!preg_match($p_validate_pjg, $p_pjg))
                {
                    sms_send($params['sender'], 'Maaf. Panjang bayi saat kembali salah. Contoh panjang bayi: 31.5', $nama_modem);
                }
                else
                if (!preg_match($p_validate_pjg, $p_berat))
                {
                    sms_send($params['sender'], 'Maaf. Berat bayi saat kembali salah. Contoh berat bayi: 3,12', $nama_modem);
                }
                else
                if (($p_kondisi!='SEHAT') && ($p_kondisi!='SAKIT'))
                {
                    sms_send($params['sender'], 'Maaf. Kondisi bayi salah. Harus SEHAT atau SAKIT.', $nama_modem);
                }
                else
                {
                    // process tgl, berat & panjang:
                    // xx/yy/xxxx
                    $p_skor = ($p_kondisi=='SEHAT'?1:0);
                    $p_berat = str_replace(',','.', $p_berat);
                    $p_pjg = str_replace(',','.', $p_pjg);
                    // all set! save it to database.
                    $sub_mon_sql = "insert into inkubator_monitoring 
                		(id, kode_pinjam, tgl_input, panjang_bayi, berat_bayi, kondisi, skor, keterangan)
                	   values
                		(UUID_SHORT(), '$kode_pinjam', CURRENT_TIMESTAMP(), $p_pjg, $p_berat, '$p_kondisi', $p_skor,
                		concat('Data monitoring ', (select p.nama_bayi from inkubator_pinjam p where p.kode_pinjam = '$kode_pinjam'))
                	   );";
                    /*
                    $f = fopen('d:/test-.txt','w');
                    fputs($f, $save_sql);
                    fputs($f, $sub_mon_sql);
                    fclose($f);
                    */                    
                    if (exec_query($sub_mon_sql))
                    {
                        sms_send($params['sender'], 'Data monitoring telah diterima.', $nama_modem);
                    }
                    else
                    {
                        sms_send($params['sender'], 'Maaf, server sedang sibuk. Cobalah beberapa saat lagi.', $nama_modem);
                    }                    
                }                    
            }
        }
        return true;
    }     
}

/**
 * Callback for register event:
 *  - Keyword: your keyword.
 */
function my_hook_monitor_register_callback_function($keyword)
{
    // create your table here, etc., and...
    return true;    
}

/**
 * Callback for unregister event:
 *  - Keyword: your keyword.
 */
function my_hook_monitor_unregister_callback_function($keyword)
{
    // drop your table here, etc., and...
    return true;    
}

/**
 * Callback for activation event:
 *  - Keyword: your keyword.
 */
function my_hook_monitor_activation_callback_function($keyword)
{
    // create your table here, etc., and...
    // exec_query('create table if not exists `unknown_sms_data`(id int(10) not null auto_increment, primary key(id)) engine=MyISAM');
    return true;    
}

/**
 * Callback for deactivation event:
 *  - Keyword: your keyword.
 */
function my_hook_monitor_deactivation_callback_function($keyword)
{
    // drop your table here, etc., and...
    // exec_query('drop table if exists `unknown_sms_data`');
    // you can leave your database entries for next time your hook reactivated.
    return true;    
}


/**
 * Register your keyword monitor and its hook function to database. 
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
    $my_monitor_keyword, 
    'my_hook_monitor_function', // hook function name.
    __FILE__, // current file.
    $my_monitor_description, 
    $my_monitor_sms_format, 
    $my_monitor_sms_sample,
    $my_monitor_kategori
);
*/
/**
 * keyword_hook_unregister(
 *     $my_monitor_keyword, 
 *     'my_hook_monitor_function'
 * );
 */
?>