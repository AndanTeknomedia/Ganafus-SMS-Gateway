<?php 
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
session_start();
$user_data = user_data();


function is_admin($group_id)
{
    if (isset($group_id) && (($group_id==='adm') || ($group_id==='dev')))
    {
        return true;
    }
    else
    {
        return false;
    }
}


function user_logged_in() 
{
    if(!isset ($_SESSION['user_sess']) || !$_SESSION['user_sess'] ) 
	{
        return FALSE;
        exit;
	}
	else
	{
		if (isset($_SESSION['user_name']) && (!empty($_SESSION['user_name'])) )
		{
        	return TRUE; 
        	// exit;
		}
		else
		{
			return FALSE;
		}
	}
}

function user_name()
{
	if (!user_logged_in())
	{
		return '';
	}
	else
	{
		return $_SESSION['user_name'];
	}
}

function user_data(){     
    $userdata['user_sess'] = $_SESSION['user_sess'];
    $userdata['user_id'] = $_SESSION['user_id'];
	$userdata['user_name'] = $_SESSION['user_name'];
	$userdata['user_email'] = $_SESSION['user_email'];
	$userdata['user_first_name'] = $_SESSION['user_first_name'];
	$userdata['user_last_name'] = $_SESSION['user_last_name'];
    $userdata['user_group_id'] = $_SESSION['user_group_id'];
    $userdata['user_group_name'] = $_SESSION['user_group_name'];
    $userdata['user_image_path'] = $_SESSION['user_image_path'];
    // $userdata['user_skpd_count'] = $_SESSION['user_skpd_count'];
    return $userdata;
}

function user_id()
{
	if (!user_logged_in())
	{
		return 0;
	}
	else
	{
		return $_SESSION['user_id'];
	}
}

/*
if (!user_logged_in())
{
	$src = strtolower(basename($_SERVER['PHP_SELF']));
	if ( !in_array($src, array('login.php')))
	{
		header('Location:login.php');	
	}
}
*/
function require_login($target_page = 'login.php')
{
	if (!user_logged_in())
	{
		$src = strtolower(basename($_SERVER['PHP_SELF']));
		if ( !in_array($src, array('login.php')))
		{
			header('Location:'.$target_page);	
		}
	}
}

function require_admin($fallback_url = 'index.php', $title='Error', $msg='An error has occurred.', $btn_title='Dashboard'){
    $sessid = session_id();
    if (empty($sessid)) {
        session_start();
    }
    if (!is_admin($_SESSION['user_group_id']))
    {
        $_SESSION['error_message'] = 'Anda tidak dapat mengakses halaman ini.';
        
        header ('location:error.php?r='.urlencode($fallback_url).'&title='.urlencode($title).'&msg='.urlencode($msg).'&btn='.urlencode($btn_title));
        // die('hello');
    }
}
?>