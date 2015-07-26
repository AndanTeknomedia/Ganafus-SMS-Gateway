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
$php_data   = post_var('phpdata');
$php_tipe   = strtolower(post_var('tipe'));

if (!$ajax)
{
    die('ERError: Unspeficifed parameters.');
}

define ('NO_SYNTAX_ERROR', 'No syntax errors');

if ($php_tipe=='file')
{
    $php_file = $php_data;
}
else
if ($php_tipe == 'data')
{
    $php_file = dirname(__FILE__).'\php-lint-'.md5(session_id()).'.php';
    if (file_exists($php_file)) {unlink($php_file);}
    $f = fopen($php_file, 'w');
    fputs($f, $php_data);
    fclose($f);
}
$res = fetch_query('select * from modem_gateway order by id desc limit 0,1');
$modem = $res[0];
$php = $modem['php_path'].'\php.exe';
$php_ini_cli = dirname(__FILE__).'\php-cli.php';
unset ($res);

$ret = 0;
$res = array();

$command = '"'.$php.'" -l ""'.$php_ini_cli.'" "'.$php_file.'""';
$command = ''.$php.' -l "'/* .$php_ini_cli.'" "'*/ .$php_file.'"';        
// die('OK'.$command);
$hasil = exec($command, $res, $ret);
$pesan = implode(' ', $res);
$ok = strpos(strtoupper($pesan),strtoupper(NO_SYNTAX_ERROR))>=0;        
echo ($ok?'OK':'ER').$pesan;
unlink($php_file);
?>