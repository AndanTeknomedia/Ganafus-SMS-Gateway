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

$return_uri = ((!isset($_GET['return_uri'])) || empty($_GET['return_uri']) ? 'index.php' : urldecode($_GET['return_uri']));
$post_id = ((!isset($_GET['post_id'])) || empty($_GET['post_id']) ? 0 : urldecode($_GET['post_id']));
$post_status = ((!isset($_GET['post_status'])) || empty($_GET['post_status']) ? '' : ucfirst(strtolower($_GET['post_status'])));
$msg = '';
if ($post_id == 0) {$msg.='Parameter [post_id] tidak valid.<br />'; }
if (empty($post_status)) {$msg.='Parameter [post_status] tidak valid.<br />'; }
if (!empty($msg))
{
	$_SESSION['error_message'] = $msg;
	header('location:'.$return_uri);	
}
if (!exec_query("update posts set post_status = '$post_status' where id = $post_id"))
{
	$_SESSION['error_message'] = 'Update gagal. Pastikan [post_id] valid dan [post_status] sudah benar.';	
}
header('location:'.$return_uri);

?>