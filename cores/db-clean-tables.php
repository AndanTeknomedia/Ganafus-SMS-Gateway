<?php

/* Required files */
include_once('definition.php');
include_once('db.php');


$r = post_var('r');
$also_drop = post_var('also-drop');
if($r==NULL)
{
    die('ERInvalid parameters.');
}

$ok = true;
$ok &= clean_tables();
if ($also_drop!=NULL) {
    $ok &= drop_user_tables();
}

if ($ok)
{
    echo 'OKTable(s) cleaned and ready.';
}
else
{
    echo 'ERTable(s) cleaning failed.';
}

?>