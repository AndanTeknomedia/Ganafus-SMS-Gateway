<?php

/* Required files */
include_once('definition.php');
include_once('db.php');


$r = post_var('r');
if($r==NULL)
{
    die('ERInvalid parameters.');
}

$ok = true;
$ok &= drop_user_tables();

$ok &= clean_tables();
if ($ok)
{
    echo 'OKTable(s) cleaned and ready.';
}
else
{
    echo 'ERTable(s) cleaning failed.';
}

?>