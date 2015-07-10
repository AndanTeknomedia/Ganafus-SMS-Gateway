<?php
/* 
** 
** Built-in session for internal ajax. Do not mix it with UI session!
*/
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
session_start();
/* Required files */
/**
 * include_once('definition.php'); 
 * include_once('db.php');
 */

$str = '';
if (!isset($_POST['sessionData'])) { $str.= 'Session data kosong'; }
if (!empty($str)) 
{
	echo $str;
}
else
{
    foreach ($_POST['sessionData'] as $key=>$value){
        $_SESSION[$key] = $value;
    }
	
    echo 'OK';
}
//since this application was intended to be used within
//local server, what about an idea to make it more ajaxified by ajaxifying the progress ?
// cheers :)
// var_dump($_POST);
?>