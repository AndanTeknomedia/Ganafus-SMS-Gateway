<?php
/* 
** 
** Built-in session for internal ajax. Do not mix it with UI session!
*/
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
session_start();
/* Required files */
include_once('definition.php'); 
include_once('db.php');

$uid = (isset($_POST['username']) ? $_POST['username'] : '');
$pwd = (isset($_POST['password']) ? $_POST['password'] : '');
$str = '';

if (empty($uid)) { $str.= 'Username kosong. ';}
if (empty($pwd)) { $str.= 'Password kosong. ';}
if (!empty($str)) 
{
	echo $str;
}
else
{
	$users = fetch_query(
        /*"select 
        	u.id, 
        	u.group_id, 
        	g.group_name, 
        	u.user_name, 
        	u.email, 
        	u.first_name, 
        	u.last_name, 
        	u.image_path,
        	(case when u.group_id in (1,2) then
        		(select count(*) from skpd)
        	else
        		(select count(*) from map_user_skpd m
        			inner join skpd s on s.id = m.skpd_id
        			inner join users us on us.id = m.user_id
        			where us.id = u.id)
        	end) as jumlah_akses_skpd        	
        from users u inner join user_groups g on g.id = u.group_id
        where u.user_name = '$uid' and u.user_password = md5('$pwd')"
        */
        "select * from vw_user_login where user_name = '$uid' and user_password = md5('$pwd')"
        );
        
	if (count($users)>0)
	{
		//set session vars:
		$_SESSION['user_sess'] = 1;
        $_SESSION['user_id'] = $users[0]['id'];
		$_SESSION['user_name'] = $users[0]['user_name'];
		$_SESSION['user_email'] = $users[0]['email'];
		$_SESSION['user_first_name'] = $users[0]['first_name'];
		$_SESSION['user_last_name'] = $users[0]['last_name'];
        $_SESSION['user_group_id'] = $users[0]['group_id'];
        $_SESSION['user_group_name'] = $users[0]['group_name'];
        $_SESSION['user_image_path'] = 'img/user-avatar/'.$users[0]['image_path'];
		echo 'OK';
	}
	else
	{
		echo 'Kombinasi Username dan/atau Password tidak cocok.';
	}
	unset ($users);
}
//since this application was intended to be used within
//local server, what about an idea to make it more ajaxified by ajaxifying the progress ?
// cheers :)
// sleep(2 /* seconds */);
?>