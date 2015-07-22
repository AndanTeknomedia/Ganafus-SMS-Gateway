<?php

error_reporting(E_ALL ^ E_NOTICE);

define ('SP_APP_NAME_SHORT', 'Inkubator Bayi');
define ('SP_APP_NAME_LONG','Sistem Monitoring Peminjaman Inkubator Gratis');
define ('SP_APP_VERSION','1.0.0');
define ('SP_APP_URL','http://inkubator-bayi.co.nf');
define ('SP_AUTHOR', 'Joko Rivai,jokorb@yahoo.co.uk,https://facebook.com/jokorivai');
define ('SP_ORG_NAME','AndanTeknomedia');
define ('SP_UNDEFINED_SLUG','00000.00-new');

define ('SP_DEV_MODE', true	);

define ('SP_POSTFIX_URL','inkubator');
define ('SP_APP_LOGO','/pages/img/app-logo.png');

if (!function_exists('base_url')){
	function base_url(){
	  return sprintf(
		"%s://%s/".SP_POSTFIX_URL,
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		$_SERVER['HTTP_HOST']
	  );
	}
}

function create_guid()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function server_var($var_name, $default = NULL) {
    return (isset($_SERVER[$var_name]) ? $_SERVER[$var_name] : $default);    
}


function post_var($var_name, $default = NULL)
{
    return (isset($_POST[$var_name]) ? $_POST[$var_name] : $default);
}

function get_var($var_name, $default = NULL)
{
    return (isset($_GET[$var_name]) ? $_GET[$var_name] : $default);
}

function common_var($var_name, $default = NULL)
{
    return 
        (isset($_POST[$var_name]) 
        ? $_POST[$var_name] 
        :   (isset($_GET[$var_name]) 
            ? $_GET[$var_name] 
            : $default)
        );
}

function dump_variable($the_var)
{
    echo '<pre>';
    var_dump($the_var);
    echo '</pre>';
}
/**
 * Time Lapse: fungsi yang menghitung waktu sebuah kejadian dari saat ini.
 * Contoh: 30 detik lalu, 25 menit lalu
 * selisih di atas 60 menit langsung ditulis waktunya.
 */
function time_lapse($occuring_time, $current_time = 0)
{
    $kini = ($current_time == 0 ? time() : $current_time);
    $selisih = $kini - $occuring_time;
    // if (5 \ 2)
    
}

function force_404($page_404)
{
    header("HTTP/1.0 404 Not Found");
    include($page_404);
    die();
}
?>