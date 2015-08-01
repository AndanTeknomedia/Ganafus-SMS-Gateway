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
$ajax       = post_var('ajax');
$command    = strtolower(post_var('command'));

$sms_processor = dirname(__FILE__).'\sms-processor-daemon.php';

if (!$ajax)
{
    die('Error: Unspeficifed parameters.');
}

$res = fetch_query('select * from modem_gateway order by id desc limit 0,1');
$modem = $res[0];
$php = $modem['php_path'].'\php-win.exe';
$php_ini_cli = dirname(__FILE__).'\php-cli.php';
unset ($res);

$ret = 0;
$res = array();
switch ($command)
{
    /**
     * Window service section:
     */
    case 'install':
        $command = $modem['gammu_path'].'\gammu-smsd.exe -c "'.$modem['gammu_config_file'].'" -i -n '.$modem['service_name'];
        if (!exec($command, $res, $ret))
        {
            die('Gagal.');
        }
        echo 'OK'; 
        break;
    case 'uninstall':
        $command = $modem['gammu_path'].'\gammu-smsd.exe -c "'.$modem['gammu_config_file'].'" -u -n '.$modem['service_name'];
        if (!exec($command, $res, $ret))
        {
            die('Gagal.');
        }
        echo 'OK';
        break; 
    case 'start':
        $command = $modem['gammu_path'].'\gammu-smsd.exe -c "'.$modem['gammu_config_file'].'" -s -n '.$modem['service_name'];
        
        if (!exec($command, $res, $ret))
        {
            die('Gagal.');
        }
                
        echo 'OK';
        break;
    case 'stop':
        $command = $modem['gammu_path'].'\gammu-smsd.exe -c "'.$modem['gammu_config_file'].'" -k -n '.$modem['service_name'];
        if (!exec($command, $res, $ret))
        {
            die('Gagal.' );
        }
        echo 'OK';
        break;
    case 'check':
        $command = 'sc.exe query '.$modem['service_name'];
        $hasil = exec($command, $res, $ret);
        $found = false;
        $data = 'ERService tidak terpasang.';
        foreach ($res as $i=>$pesan)
        {
            $found = strpos(strtoupper($pesan),'STATE');
            if ($found){
                $data = explode(' ', $pesan);
                if (count($data)>0) {
                    $data = ucfirst(strtolower($data[count($data)-1]));
                    $data = 'OK. '.$data;    
                }                
                break;
            }
        }
        echo $data;
        break;
    /**
     * Windows Task Scheduler section:
     */
    case 'installprocessor':
        $command = 'schtasks.exe /create /F /SC MINUTE /RU SYSTEM /TN "'.$modem['service_name'].'" /TR ""'.$php.'" -c "'.$php_ini_cli.'" ""'.$sms_processor.'"""';        
        $hasil = exec($command, $res, $ret);
        $pesan = implode(',', $res);
        $found = strpos(strtoupper($pesan),'SUCCESS');        
        if ($found===false) {
            echo 'OKGagal membuat tugas otomatis.';
        }
        else
        {
             echo 'OKSukses membuat tugas otomatis.';
        }             
        break;
    case 'uninstallprocessor':
        $command = 'schtasks.exe /delete /F /TN "'.$modem['service_name'].'"';        
        $hasil = exec($command, $res, $ret);
        $pesan = implode(',', $res);
        $found = strpos(strtoupper($pesan),'SUCCESS');        
        if ($found===false) {
            echo 'OKGagal. Mungkin tugas belum dibuat?';
        }
        else
        {
            echo 'OKSukses menghapus tugas otomatis.'; 
        }
        break;
    case 'queryprocessor':
        $command = 'schtasks.exe /query /TN "'.$modem['service_name'].'"';    
        $hasil = exec($command, $res, $ret);
        $pesan = implode(',', $res);
        $found = strpos(strtoupper($pesan),'READY') || strpos(strtoupper($pesan),'RUNNING');        
        if ($found===false) {
            echo 'OKTugas otomatis belum siap.';
        }
        else
        {
            echo 'OKTugas otomatis telah siap.'; 
        }  
        break;
    default:
        echo 'ERUnknown parameters.';
}
// echo $command;

/*C:\xeroxl\UniServerZ\core\php54>schtasks /create /F /SC MINUTE /RU SYSTEM /TN "SMS Parser daemon" /TR "C:\xeroxl\UniServerZ\core\php54
\php.exe ""C:\xeroxl\UniServerZ\vhosts\inkubator-local\gammu\win32-service\sms-processor-daemon.php"""
*/

/*
schtasks /query /TN "SMS Parser daemon"
*/

/*
schtasks /delete /F /TN "SMS Parser daemon"
*/
?>