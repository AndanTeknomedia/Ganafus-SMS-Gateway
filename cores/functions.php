<?php 
/**
	Generate date and time from mysql timestamp: yyyy-MM-dd hh:mm:ss
**/
$_NAMA_BULAN = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
$_NAMA_HARI  = array('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu');

function tgl_indo($mysql_timestamp, $use_nama_bulan = false, $use_nama_hari = false)
{
	global $_NAMA_BULAN, $_NAMA_HARI;
	if (strlen($mysql_timestamp)!=19)
	{
		return 'Invalid Timestamp';
	}
	else
	{
		$ts = strtotime($mysql_timestamp);
		$hari = $_NAMA_HARI[date('N', $ts)-1];		
		$bulan = $_NAMA_BULAN[date('m', $ts)-1];
		return "$hari, ".date('j',$ts)." $bulan ".date('Y',$ts);
	}
}

function get_query_string($include_space=true)
{
	$query_string = $_SERVER['QUERY_STRING'];
	return $query_string;
	/*
	$gal_urix = strtolower( urldecode( $query_string ));
	$gal_uri = '';
	for ($i = 0; $i<strlen($gal_urix); $i++)
	{
		$c = substr($gal_urix,$i,1);
		if ( (($c >='a') && ($c <='z')) || 
            ($c == '-') ||  ($c=='.') ||  ($c==',') || 
            ( ($c >= '0') && ($c<='9') )
			|| ($include_space && $c==' '))
		{
			$gal_uri .= $c;
		}
		else
		{
			break;	
		}
	}	
	return $gal_uri;
	*/
}

/**
 * Encrypt and decrypt
 */
 
 function m_encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}

/**
 * Returns decrypted original string
 */
function m_decrypt($encrypted_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return $decrypted_string;
}

/**
 * UI Notification state by percentage:
 *  $negative       : inverted as 100-$percentage
 */
 
function percentage_state($percentage, $negative = false)
{
    $p = $percentage;
    $state = "danger";
    if ($p<0) {$p=0;} else if ($p>100){$p=100;}    
    if ($negative) {$p=100-$p;}
    if ($p<25)
    {
        $state = 'danger';
    }
    else if (($p>=25) && ($p<50))
    {
        $state = 'warning';
    }
    else if (($p>=50) && ($p<75))
    {
        $state = 'info';
    }
    else
    {
        $state = 'success';
    }
    return $state;
}

?>