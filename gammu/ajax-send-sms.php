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

require_once('gammu-hex.php');
$no_tujuan = stripslashes( post_var('notujuan') );
$pno       = '+62';
if 
(
    (substr($no_tujuan,0,3)!='+62') &&
    (substr($no_tujuan,0,2)!='08')
)
{
    die ('ERNomor tidak valid.<br>Nomor harus diawali dengan +628xx... atau 08xx...');
}

if (substr($no_tujuan,0,2)=='08')
{
    $no_tujuan = $pno . substr($no_tujuan,1);
}

$pesan     = substr(stripslashes( post_var('pesan') ), 0, 400); 
// $pesan     = post_var('pesan');
// $tipe      = strlen($pesan)>160 ? 'EMS' : 'TEXT';
$tipe      = 'TEXT';
$param     =  strlen($pesan)>160 ? ' -len 400' : '';    
$use       = strtoupper(post_var('use')); 

$data = 'ERSMS Gagal Dikirim.';

$res = fetch_query('select * from modem_gateway order by id desc limit 0,1');
$modem = $res[0];
$nama_modem = $modem['nama_modem'];
// $nama_modem = '';
unset($res);

if ($use=='CMD')
{    
    $command = str_replace('\\','/',$modem['gammu_path']).'/gammu-smsd-inject.exe -c "'.str_replace('\\','/',$modem['gammu_config_file']).'" '.$tipe.' "'.$no_tujuan.'"'.$param.' -text "'.$pesan.'"';
    //  $command = 'C:/Gammu-1.32.0-Windows/bin/gammu-smsd-inject.exe -c "C:/Gammu-1.32.0-Windows/bin/gammu-config.cfg" TEXT "+6282345798006" -text "tested agaiinn..sds."';
    // die($command);
    
    $ret = 0;
    $res = array();
    $hasil = exec($command, $res, $ret);
    
    $found = false;
    
    // echo $command.'<br>'.$res;
    foreach ($res as $i=>$pesan)
    {
        // $found = strpos(strtolower($pesan),'written message with id'); --> not safe! some message can be written without ID returned!
        $found = strpos(strtolower($pesan),'written message with'); // preferred way.
        if ($found){
            $data = explode(' ', $pesan);
            if (count($data)>0) {
                $data = ucfirst(strtolower($data[count($data)-1]));
                $data = 'OKSMS Terkirim.';    
            }                
            break;
        }
    }
    echo $data;
}
else
if ($use=='SQL')
{
    global $_mysqli;
    $sql = "insert into outbox_tmp(            
                DestinationNumber,
                TextDecoded,
                SenderID,
                CreatorID
                ) values (
                '$no_tujuan', 
                '$pesan', 
                '$nama_modem', 
                '".GAMMU_CREATOR_ID."'
                )";  
    if (!exec_query($sql))
    {
        echo $data;
    }
    else
    {   
        $data = 'OKSMS Terkirim dengan ID: '.$_mysqli->insert_id;
        echo $data;
    }
}
else
{
    echo 'ERNot supported';  
}
?>