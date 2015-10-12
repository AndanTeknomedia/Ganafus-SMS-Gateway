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

$nama = addslashes(strip_tags(post_var('nama')));
$email = addslashes(strip_tags(post_var('email')));
$url = addslashes(strip_tags(post_var('url')));
$komentar = addslashes(strip_tags(post_var('komentar')));
$postid = post_var('postid',0);
$table = post_var('table',0);
// die($postid.'<br>'.$table);

$data = 'ERKomentar gagal kirim';

if(!exec_query("insert into frontend_comments values (UUID_SHORT(), CURRENT_TIMESTAMP(), '$nama', '$email', '$url', '$komentar','$table','$postid')"))
{
    echo $data;    
}
else
{
    echo 'OKKomentar terkirim';  
}
?>