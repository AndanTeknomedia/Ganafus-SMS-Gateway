<?php
/*
** 
** Built-in session for internal ajax. Do not mix it with UI session!
*/

ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
session_start();
/* Required files */
include_once('../cores/definition.php'); 
include_once('../cores/db.php');
include_once('gammu-cores.php');

$nama_modem = post_var('nama_modem');
$nama_port =  strtolower(post_var('nama_port'));
$mode = post_var('mode');
$baudrate = post_var('baudrate');
$path = post_var('path');
$smsc = post_var('smsc');
$php = post_var('php');
$uselog = post_var('use_log') == 'true';
$service_name= GAMMU_SERVICE_NAME;

$str = '';
$str .= (empty($nama_port) ? 'Nama Port kosong.<br>' : '' );
$str .= (empty($mode) ? 'Mode Koneksi kosong.<br>' : '' );
$str .= (empty($baudrate) ? 'Baudrate kosong.<br>' : '' );
$str .= (empty($path) ? 'Path Gammu.exe kosong.<br>' : '' );
$str .= (empty($smsc) ? 'Nomor SMSC kosong.<br>' : '' );
$gammu = $path . (substr($path, strlen($path)-1)=="\\" ? "Gammu.exe" : "\\Gammu.exe");
$path = dirname($gammu);  
$str .= (!file_exists($gammu) ? 'Direktori Gammu.exe tidak valid.<br>' : '' );

$ph = $php . (substr($php, strlen($php)-1)=="\\" ? "php.exe" : "\\php.exe");
$php = dirname($ph);  
$str .= (!file_exists($ph) ? 'Direktori Php.exe tidak valid.<br>' : '' );

/*
echo 
    $nama_modem . '<br>'.
    $nama_port . '<br>'.
    $mode . '<br>'.
    $baudrate . '<br>'.
    $path . '<br>'.
    $gammu. '<br>';
*/

if (!empty($str)) 
{
	echo $str;
}
else
{
	$nama_modem= (empty($nama_modem) ? 'Modem '.$nama_port.'@'.$baurate : $nama_modem );
    // Delete old modems:
    if (!exec_query('delete from modem_gateway'))
    {
        die ('ERROR: Gagal mengupdate SMS Gateway.');
    } 
    $log = $path.'\gammu-log.log';
    $smsdlog=$path.'\smsd-log.log';
    $cfg = $path.'\gammu-config.cfg';
    unlink($log);
    unlink($smsdlog);
    unlink($cfg);
    $sql =  "insert into modem_gateway (
        nama_modem,
        nama_port,
        mode,
        baudrate,
        parity,
        stopbits,
        flowcontrol,
        gammu_path,
        gammu_log_file,
        smsd_log_file,
        gammu_config_file,
        service_name,
        smsc, 
        use_log,
        php_path)
        values (
        '$nama_modem',  
        '$nama_port',
        '$mode',
        '$baudrate',
        'none',
        '1',
        'XON/XOFF',
        '".addslashes($path)."',
        '".addslashes($log)."',
        '".addslashes($smsdlog)."',
        '".addslashes($cfg)."',
        '$service_name',
        '$smsc','".
        ($uselog ? 'Y' : 'N').                  
        "',
        '".addslashes($php)."'
        )";
    if (!exec_query($sql))
    {
        die ('ERROR: Gagal mengupdate SMS Gateway.');
    }
    // setup config files:
    $cfgs = "[gammu]\n".
        "Device=$nama_port\n".
        "Connection=$mode$baudrate\n".    
        "SMSC=$smsc\n".    
        ($uselog ? "" : ";").
        "LogFile=".($log)."\n".        
        "LogFormat=textalldate\n".        
        "[smsd]\n".
        "Service=SQL\n".
        "Driver=native_mysql\n".
        "SMSC=$smsc\n".
        "CheckSecurity=0\n".
        "MaxRetries=2\n".
        "PhoneID=$nama_modem\n".
        ($uselog ? "" : ";").
        "LogFile=".($smsdlog)."\n".        
        ";RunOnReceive=".str_replace("\\","/", PHP_BINARY)." ".str_replace("\\","/",dirname(__FILE__))."/run-on-receive.php". "\n".
        ";RunOnFailure=\n".        
        "User=".DB_USER."\n".
        "Password=".DB_PASSWORD."\n".
        "Host=".DB_HOST."\n".
        "Database=".DB_DATABASE."\n".      
        "DebugLevel=1\n".        
        "[sql]\n";
    unlink($path.'\gammu-config.cfg');
    $f = fopen($path.'\gammu-config.cfg', 'w');
    fputs($f, $cfgs);
    fclose($f);
    if (!file_exists($path.'\gammu-config.cfg'))
    {
        die ('ERROR: Gagal menonfigurasi SMS Gateway.');
    }    
       
    // mark gammu as already setup:
    // $go = realpath('gammu-ok.php');
    if (!set_gammu_ok(true))
    {
        die('Gagal mengupdate gammu-cores.');
    }
    echo 'OK';
}
?>