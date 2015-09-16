<?php
include_once('definition.php'); 
include_once('session.php'); 
require_once('db.php'); 
require_once('functions.php'); 
/* expected parameter :
** post_id: int, post id to be updated
** post_status: new post status which the post wil be update as
** return uri: redirect target to redirect to after successful update
*/
$ajax = post_var('ajax',0);
$tipe = post_var('tipe','');
// die('ER'.$ajax.':'.$tipe);
if (($ajax==0) || ($tipe==''))
{
    die('ERInvalid Parameter.');
}
$sql = "delete from `$tipe` where id = '$ajax'";
if (exec_query($sql))
{
	echo 'OKSMS telah dihapus.';	
}
else
{
    echo 'Gagal menghapus SMS.';    
}

?>