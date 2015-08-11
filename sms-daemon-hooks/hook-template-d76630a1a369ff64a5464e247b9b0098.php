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
 
/**
 * 
 * WARNING!
 * DO NOT CHANGE SYSTEM GENERATED VARIABLE & FUNCTION NAMES!
 * 
 */
$my_pinjam_kategori = 'Inkubator bayi';
$my_pinjam_keyword = 'PINJAM';
$my_pinjam_description = 'SMS Peminjaman Inkubator';
$my_pinjam_sms_format = 'PINJAM*NAMA_BAYI*TGL_LAHIR*TGL_PULANG_RS*CM_PJGLAHIR*KG_BERATLAHIR*<SEHAT/SAKIT>*NAMA_RS*NM_DOKTER/BIDAN*NO_KK*ALAMAT*NAMA_IBU*NAMA_AYAH';
$my_pinjam_sms_sample = 'PINJAM*DIAN KHAMSAWARNI*21/09/2015*23/09/2015*28*3,2*SEHAT*RSU Wahidin*Dr. Marhamah, Sp.OG*9288299288*BTN Hamzy E8/A*RINA MAWARNI*ARIFIN ADINEGORO';

/**
 * Let's declare database initialization:
 *  my_pinjam_init_db
 *      is_installing: boolean, true for installing and false for removing.
 */
function my_pinjam_init_db($is_installing)
{
    $tables     = array();    
    $triggers   = array(); // DO NOT FORGET TO CHANGE MYSQL COMMAND DELIMITER before executing triggers creation!
    /**
     Check triggers existence before creating them:
     SELECT TRIGGER_NAME
            FROM information_schema.triggers
            WHERE TRIGGER_SCHEMA = 'db_name' AND TRIGGER_NAME = 'trigger_name';
     or
     
     SELECT TRIGGER_NAME
            FROM information_schema.triggers
            WHERE TRIGGER_SCHEMA = 'db_name' AND (TRIGGER_NAME in ('trigger_name_1','trigger_name_2','trigger_name_n'));
     
     */    
    $views      = array();
    $drops      = array();
    
    //master data inkubator
    $tables['inkubator_master'] = "CREATE TABLE if not exists `inkubator_master` 
            (
        	`id` BIGINT(20) NOT NULL,
        	`nama` VARCHAR(50) NOT NULL,
        	`jumlah` INT(2) NOT NULL DEFAULT '0',
        	`panjang` INT(2) NOT NULL DEFAULT '0' COMMENT 'cm',
        	`lebar` INT(2) NOT NULL DEFAULT '0' COMMENT 'cm',
        	`tinggi` INT(2) NOT NULL DEFAULT '0' COMMENT 'cm',
        	`berat` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT 'kg',
        	`tipe` TEXT NULL,
        	PRIMARY KEY (`id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;";

    // peminjaman
    $tables['inkubator_pinjam'] = "CREATE TABLE if not exists `inkubator_pinjam` 
        (
        	`id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', /* UUID_SHORT() */
        	`kode_pinjam` VARCHAR(20) NOT NULL DEFAULT '',
        	`id_inkubator` BIGINT(20) NOT NULL DEFAULT '0'  COMMENT 'Diisi saat verifikasi peminjaman.', /* UUID_SHORT() */
        	`tgl_pinjam` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        	`nama_bayi` VARCHAR(75) NOT NULL DEFAULT '',
        	`kembar` ENUM('Y','N') NOT NULL DEFAULT 'N',
        	`tgl_lahir` DATE NULL DEFAULT NULL,
        	`berat_lahir` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
        	`panjang_lahir` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
        	`kondisi` ENUM('SEHAT','SAKIT') NOT NULL DEFAULT 'SEHAT',
        	`rumah_sakit` VARCHAR(50) NOT NULL DEFAULT '',
        	`nama_dokter` VARCHAR(75) NOT NULL DEFAULT '',
        	`tgl_pulang` DATE NULL DEFAULT NULL,
        	`no_kk` VARCHAR(50) NOT NULL DEFAULT '',
        	`alamat` TEXT NULL,
        	`nama_ibu` VARCHAR(50) NOT NULL DEFAULT '',
        	`hp_ibu` VARCHAR(20) NOT NULL DEFAULT '',
        	`email_ibu` VARCHAR(50) NOT NULL DEFAULT '',
        	`nama_ayah` VARCHAR(50) NOT NULL DEFAULT '',
        	`hp_ayah` VARCHAR(20) NOT NULL DEFAULT '',
        	`email_ayah` VARCHAR(50) NOT NULL DEFAULT '',
        	`jumlah_pinjam` INT(2) NOT NULL DEFAULT '1',
        	`status_pinjam` ENUM('Ditunda','Disetujui','Ditolak') NOT NULL DEFAULT 'Ditunda',
        	`tgl_update_status_pinjam` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        	`keterangan_status_pinjam` VARCHAR(200) NOT NULL DEFAULT '',
        	`konfirmasi` ENUM('Y','N') NOT NULL DEFAULT 'N',
        	PRIMARY KEY (`id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;";
    $triggers['inkubator_pinjam_after_delete'] = "CREATE  TRIGGER `inkubator_pinjam_after_delete` AFTER DELETE ON `inkubator_pinjam` FOR EACH ROW 
        BEGIN
        	delete from inkubator_monitoring  where kode_pinjam = OLD.kode_pinjam;
        	delete from inkubator_kembali  where kode_pinjam = OLD.kode_pinjam;
        END";
    
    // pengembalian
    $tables['inkubator_kembali'] = "CREATE TABLE if not exists `inkubator_kembali` 
        (
        	`id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
        	`kode_pinjam` VARCHAR(20) NOT NULL DEFAULT '',
        	`id_inkubator` BIGINT(20) NOT NULL DEFAULT '0',
        	`tgl_kembali` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        	`jumlah_kembali` INT(2) NOT NULL DEFAULT '1',
        	`status_kembali` ENUM('Ditunda','Diterima','Ditolak') NOT NULL DEFAULT 'Ditunda',
        	`tgl_update_status_kembali` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        	`keterangan_status_kembali` VARCHAR(200) NOT NULL DEFAULT '',
        	`berat_kembali` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
        	`panjang_kembali` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
        	`kondisi_kembali` ENUM('SEHAT','SAKIT') NOT NULL DEFAULT 'SEHAT',
        	PRIMARY KEY (`id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;"; 
    $triggers['inkubator_kembali_after_insert'] = "CREATE TRIGGER `inkubator_kembali_after_insert` AFTER INSERT ON `inkubator_kembali` FOR EACH ROW 
        BEGIN
        	declare bayi varchar(100);
        	set bayi = (select p.nama_bayi from inkubator_pinjam p where p.kode_pinjam = NEW.kode_pinjam);
        	insert into inkubator_monitoring 
        		(kode_pinjam, tgl_input, panjang_bayi, berat_bayi, kondisi, keterangan)
        	values
        		(NEW.kode_pinjam, curent_timestamp, NEW.panjang_kembali, NEW.berat_kembali, NEW.kondisi_kembali, concat('Status akhir ', bayi));
        END";
    
    // monitoring
    $tables['inkubator_monitoring'] = "CREATE TABLE if not exists `inkubator_monitoring` 
         (
        	`id` INT(20) NOT NULL,
        	`kode_pinjam` VARCHAR(20) NOT NULL DEFAULT '',
        	`tgl_input` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        	`panjang_bayi` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
        	`berat_bayi` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
        	`kondisi` ENUM('SEHAT','SAKIT') NOT NULL DEFAULT 'SEHAT',
        	`skor` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
        	`keterangan` TEXT NULL,
        	PRIMARY KEY (`id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;";
    $triggers['inkubator_monitoring_before_insert']="CREATE TRIGGER `inkubator_monitoring_before_insert` BEFORE INSERT ON `inkubator_monitoring` FOR EACH ROW 
        BEGIN
        	set NEW.id = UUID_SHORT();
        	case when NEW.kondisi = 'SEHAT' then
        		set NEW.skor = 1;
        	else
        		set NEW.skor = 0;
        	end case;
        END";
    // Test-----------------
    $triggers['inkubator_monitoring_after_insert']="CREATE TRIGGER `inkubator_monitoring_after_insert` AFTER INSERT ON `inkubator_monitoring` FOR EACH ROW 
        BEGIN
        	set @xid = UUID_SHORT();        	
        END";
    // ./Test-----------------
    $triggers['inkubator_monitoring_before_update'] = "CREATE TRIGGER `inkubator_monitoring_before_update` BEFORE UPDATE ON `inkubator_monitoring` FOR EACH ROW BEGIN
        	set NEW.id = UUID_SHORT();
        	case when NEW.kondisi = 'SEHAT' then
        		set NEW.skor = 1;
        	else
        		set NEW.skor = 0;
        	end case;
        END";
    
    // Views:
    $views['vw_inkubator_perkembangan'] = "create or replace view `vw_inkubator_perkembangan` as select 
        	p.id,
        	i.nama,
        	p.kode_pinjam,
        	p.id_inkubator,
        	p.tgl_pinjam,
        	p.nama_bayi,
        	p.kembar,
        	p.tgl_lahir,
        	p.berat_lahir,
        	p.panjang_lahir,
        	p.kondisi,
        	p.rumah_sakit,
        	p.nama_dokter,
        	p.tgl_pulang,
        	p.no_kk,
        	p.alamat,
        	p.nama_ibu,
        	p.hp_ibu,
        	p.email_ibu,
        	p.nama_ayah,
        	p.hp_ayah,
        	p.email_ayah,
        	p.jumlah_pinjam,
        	p.status_pinjam,
        	p.tgl_update_status_pinjam,
        	p.keterangan_status_pinjam,
        	p.konfirmasi,
        	coalesce(count(m.id),0) as jumlah_data_monitor,
        	coalesce(sum(coalesce(m.skor,0)),0) as jumlah_skor_monitor,
        	(case 
        		when (coalesce(sum(coalesce(m.skor,0)),0) - coalesce(count(m.id),0) ) >= 0 then 
        			'Positif'
        		else 
        			'Negatif'	
        	end) as perkembangan
        from inkubator_pinjam p
        inner join inkubator_master i on i.id = p.id_inkubator
        left join inkubator_monitoring m on m.kode_pinjam = p.kode_pinjam 
        group by p.kode_pinjam "; 
    $views['vw_inkubator_pinjam'] = "create or replace view `vw_inkubator_pinjam` as select 
        	p.id,
        	i.nama,
        	p.kode_pinjam,
        	p.id_inkubator,
        	p.tgl_pinjam,
        	p.nama_bayi,
        	p.kembar,
        	p.tgl_lahir,
        	p.berat_lahir,
        	p.panjang_lahir,
        	p.kondisi,
        	p.rumah_sakit,
        	p.nama_dokter,
        	p.tgl_pulang,
        	p.no_kk,
        	p.alamat,
        	p.nama_ibu,
        	p.hp_ibu,
        	p.email_ibu,
        	p.nama_ayah,
        	p.hp_ayah,
        	p.email_ayah,
        	p.jumlah_pinjam,
        	p.status_pinjam,
        	p.tgl_update_status_pinjam,
        	p.keterangan_status_pinjam,
        	p.konfirmasi,
        	k.tgl_kembali,
        	k.berat_kembali,
        	k.panjang_kembali,
        	k.kondisi_kembali,
        	k.jumlah_kembali,
        	k.status_kembali,
        	k.tgl_update_status_kembali,
        	k.keterangan_status_kembali
        	
        from inkubator_pinjam p
        inner join inkubator_master i on i.id = p.id_inkubator
        left join inkubator_kembali k on k.kode_pinjam = p.kode_pinjam ";
    $views['vw_inkubator_tersedia'] =  "create or replace view `vw_inkubator_tersedia` as select 
        	i.id,
        	i.nama,
        	i.panjang,
        	i.lebar,
        	i.tinggi,
        	i.berat,
        	i.tipe,
        	(i.jumlah - sum(coalesce(p.jumlah_pinjam,0)) + sum(coalesce(k.jumlah_kembali,0)) ) as stok_inkubator
        from inkubator_master i
        left join inkubator_pinjam p on p.id_inkubator = i.id and p.status_pinjam = 'Disetujui'
        left join inkubator_kembali k on k.id_inkubator = i.id and k.status_kembali = 'Diterima'
        group by i.id ";
        
    /**
     * Do the job:
     */
    $multi_query = "";
    $check_triggers = "";
    $trg_query = "";
    if ($is_installing)
    {
        // create database items:
        foreach($views as $view=>$query)
        {
            $multi_query .= "drop view if exists `$view`;\n";
        }
        
        // no need to drop exisiting tables. You decide this.
        // dropping tables also drop their triggers.
        foreach($tables as $table=>$query)
        {
            $multi_query .= $query .(substr($query, strlen($query)-1)!=";"?";":"")."\n";
        }
        // Execute table and views first:
        foreach($views as $view=>$query)
        {
            $multi_query .= $query .(substr($query, strlen($query)-1)!=";"?";":"")."\n";
        }
        
        exec_queries($multi_query);
        
        //
        //
        //
        //
        //
        // Execute triggers:  
        foreach($triggers as $trigger=>$query)
        {
            $check_triggers .= ",lower('$trigger')";
        }
        
        $check_triggers = "SELECT lower(TRIGGER_NAME) as trgn FROM information_schema.triggers WHERE TRIGGER_SCHEMA = '".DB_DATABASE."' 
                AND (lower(TRIGGER_NAME) in (".substr($check_triggers,1)."))";
        $old_triggers= fetch_query($check_triggers);
        $check_triggers = array();   
        foreach ($old_triggers as $idx=>$col)
        {
            $check_triggers[] = $old_triggers[$idx]['trgn'];  
        }
        
        foreach($triggers as $trigger=>$query)
        {
            if (!in_array(strtolower($trigger), $check_triggers))
            {                
                exec_query($query .(substr($query, strlen($query)-1)!=";"?";":""));
            }
        }
        unset($old_triggers);
        // indicate sucess:  
        return true;      
    }
    else
    {
        // remove database items:
        
        return true;
    }
}

/**
 * Define your hook for specific SMS keyword. 
 * Return true to mark SMS as processed and 
 * will be passed on next processing.
 * Return false will cause the SMS to be 
 * reprocessed infinitely until you return true.
 */
function my_hook_pinjam_function($keyword, $params)
{
    global $app_name, $app_version, $nama_modem;
    global $my_pinjam_sms_format, $my_pinjam_sms_sample;
    // Sometime, you don't need to reply SMS from non-user number,
    // such as SMS from Service Center, message center, 
    // or promotional SMS:
    $valid_param_count = 13;
    if (strlen($params['sender'])<=6) {
        return true;
    }
    else
    {
        if (count($params['param']!=$valid_param_count)){
        /*    sms_send($params['sender'], '1/2. SMS tidak valid. Jumlah parameter data harus 13.', $nama_modem);
            sms_send($params['sender'], '2/2. Contoh SMS: '.$my_pinjam_sms_sample, $nama_modem);
        }
        else
        {
        */    // dapatkan ID dan KODE peminjaman:
            $sql_pinjam = "select (@idx:=UUID_SHORT()) id, /*hex(@idx) kode, */ concat(left(hex(@idx),6),'-',substr(hex(@idx),7,6),'-',right(hex(@idx),2)) kode limit 0,1";
            $meta_pinjam = fetch_query($sql_pinjam);
            $id_pinjam = $meta_pinjam[0]['id'];
            $kode_pinjam = $meta_pinjam[0]['kode'];
            // proses SMS dan insert ke table `pinjam`:
            
            // reply SMS:
            sms_send($params['sender'], 'Peminjaman sedang diproses. Kode Pinjam: '.$kode_pinjam, $nama_modem);
            
        }
        return true;
    }    
}

/**
 * Callback for register event:
 *  - Keyword: your keyword.
 */
function my_hook_pinjam_register_callback_function($keyword)
{
    // create your table here, etc., and...
    return true;    
}

/**
 * Callback for unregister event:
 *  - Keyword: your keyword.
 */
function my_hook_pinjam_unregister_callback_function($keyword)
{
    // drop your table here, etc., and...
    return true;    
}

/**
 * Callback for activation event:
 *  - Keyword: your keyword.
 */
function my_hook_pinjam_activation_callback_function($keyword)
{
    // create your table here, etc., and...
    // exec_query('create table if not exists `unknown_sms_data`(id int(10) not null auto_increment, primary key(id)) engine=MyISAM');
    my_pinjam_init_db(true);
    return true;    
}

/**
 * Callback for deactivation event:
 *  - Keyword: your keyword.
 */
function my_hook_pinjam_deactivation_callback_function($keyword)
{
    // drop your table here, etc., and...
    // exec_query('drop table if exists `unknown_sms_data`');
    // you can leave your database entries for next time your hook reactivated.
    my_pinjam_init_db(false);
    return true;    
}


/**
 * Register your keyword pinjam and its hook function to database. 
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
    $my_pinjam_keyword, 
    'my_hook_pinjam_function', // hook function name.
    __FILE__, // current file.
    $my_pinjam_description, 
    $my_pinjam_sms_format, 
    $my_pinjam_sms_sample,
    $my_pinjam_kategori
);
*/
/**
 * keyword_hook_unregister(
 *     $my_pinjam_keyword, 
 *     'my_hook_pinjam_function'
 * );
 */
?>