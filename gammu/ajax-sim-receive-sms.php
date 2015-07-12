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
$pengirim = stripslashes( post_var('nopengirim') );
$pno       = '+62';
if 
(
    (substr($pengirim,0,3)!='+62') &&
    (substr($pengirim,0,2)!='08')
)
{
    die ('ERNomor tidak valid.<br>Nomor harus diawali dengan +628xx... atau 08xx...');
}

if (substr($pengirim,0,2)=='08')
{
    $pengirim = $pno . substr($pengirim,1);
}

$pesan      = addslashes(stripslashes( post_var('pesan') )); 
$multipart  =  strlen($pesan)>160;     

$data       = 'ERSMS gagal diterima.';

$res        = fetch_query('select * from modem_gateway order by id desc limit 0,1');
$modem      = $res[0];
$nama_modem = $modem['nama_modem'];
$smsc       = $modem['smsc'];
$udh_prefix = '050003'.str_pad(dechex(rand(1,255)), 2, '0', STR_PAD_LEFT);
unset($modem);
unset($res);
/*
echo 'OK';
echo $udh_prefix.str_pad(dechex(3), 2, '0', STR_PAD_LEFT).str_pad(dechex(1), 2, '0', STR_PAD_LEFT).'<br>';
echo $udh_prefix.str_pad(dechex(3), 2, '0', STR_PAD_LEFT).str_pad(dechex(2), 2, '0', STR_PAD_LEFT).'<br>';
echo $udh_prefix.str_pad(dechex(3), 2, '0', STR_PAD_LEFT).str_pad(dechex(3), 2, '0', STR_PAD_LEFT).'<br>';
*/
if (!$multipart) {
    $sms_part = $pesan;
    $hex_part = gammu_hex_16bit_safe($sms_part);
    $sql = "insert into inbox(            
            UpdatedInDB,
            Text,
            SenderNumber,
            UDH,
            SMSCNumber,
            TextDecoded,
            RecipientID
            ) values (
            CURRENT_TIMESTAMP(),
            '$hex_part',
            '$pengirim', 
            '$udh', 
            '$smsc',
            '$sms_part',
            '$nama_modem' 
            )";  
    if (!exec_query($sql))
    {
        echo $data.'<br>';
    }
    else
    {   
        $data = 'OKSMS diterima dengan ID: '.$_mysqli->insert_id;
        echo $data;
    }
}
else
{ 
    $parts = str_split($pesan, 153); 
    $jml_part = count($parts);    
    $ernum = 0; 
    $udh_prefix.=str_pad(dechex($jml_part), 2, '0', STR_PAD_LEFT);
    $_mysqli->autocommit(false);
    $sql = "insert into inbox(            
            UpdatedInDB,
            Text,
            SenderNumber,
            UDH,
            SMSCNumber,
            TextDecoded,
            RecipientID
            ) values ";
    for ($i = 0; $i<$jml_part; $i++)
    {
        $sms_part = $parts[$i];
        $hex_part = gammu_hex_16bit_safe($sms_part);
        $sql.=  "(
                CURRENT_TIMESTAMP(),
                '$hex_part',
                '$pengirim', 
                '".strtoupper($udh_prefix.str_pad(dechex(1+$i), 2, '0', STR_PAD_LEFT))."', 
                '$smsc',
                '$sms_part',
                '$nama_modem' 
                ),";  
    }
    $sql = substr($sql, 0, strlen($sql)-1) .';';
    // echo  'OK'.$sql.'<br>';
    
    if (exec_query($sql))
    {
        $_mysqli->commit();
        echo 'OKSimulasi Pesan Masuk selesai.';
    }     
    else
    {
        $_mysqli->rollback();        
        echo 'ERSimulasi pesan masuk gagal';
    }
    
    $_mysqli->autocommit(true);                                   
}
?>