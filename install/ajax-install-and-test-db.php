<?php
require_once('../cores/definition.php'); 

$host       = post_var('host');
$user       = post_var('user');
$pass       = post_var('pass');
$db         = post_var('db');
$use_gammu  = post_var('usegammu'); 

$er = '';
if (empty($host)) {$er .= 'Host jangan kosong.<br>'; }
if (empty($user)) {$er .= 'Username jangan kosong.<br>'; }
if (empty($pass)) {$er .= 'Password jangan kosong.<br>'; }
if (empty($db  )) {$er .= 'Database jangan kosong.<br>'; }
if (!empty($er))
{
    die($er);
}

$_my = new mysqli(
	$host,
    $user,
    $pass,
	$db
);

if ($_my->connect_errno)
{
    die('Koneksi database gagal. Silahkan direvisi lagi...');
}
else
{
    $dbc = str_replace("\\","/",realpath('..')).'/dbconfig.php';
    // die ('ERROR.'.$dbc);
    if (file_exists($dbc))
    {
        unlink($dbc);
    }
    $f = fopen($dbc,'w');
    fputs($f,
        "<?php
        define('DB_HOST','$host');
        define('DB_USER','$user');
        define('DB_PASSWORD','$pass');
        define('DB_DATABASE','$db');
        define('USE_GAMMU',$use_gammu);   
        define('GAMMU_CREATOR_ID', 'Gammu 1.32.0');
        define('USER_TABLE_PREFIX','usr_tables_');
        ?>"
    );
    fclose($f);
    if (file_exists($dbc)) {
        echo 'OK';
    }
    else
    {
        echo 'Gagal mengupdate file konfigurasi database.';
    }
    $_my->close();
}
// echo $host.'<br>'.$user.'<br>'.$pass.'<br>'.$db.'<br>'.$use_gammu;

?>