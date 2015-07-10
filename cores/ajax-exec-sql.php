<?php
include_once('session.php');
include_once('definition.php');
include_once('functions.php'); 
include_once('db.php');
require_once('modules/slowaescrypt.php');
if (!user_logged_in())
{
    die('ERROR. Anda belum login.');
}

/**
 * Query MUST be prefixed with 'SQL:' and be sent as user_name encryped string;
 */
$sql = isset($_POST['sql']) ? $_POST['sql'] : null;
$prefix = isset($_POST['prefix']) ? $_POST['prefix'] : '';

if ($sql == null)
{
    echo 'ERROR';
}
else
{
	$un = $_SESSION['user_name'];
    $query = decrypt($sql, $un);
    // echo $query;
    if (!(substr($query,0,strlen($prefix))==$prefix))
    {
        echo 'ERROR. Wrong encryption key.';
    }    
    else
    {
        $query = substr($query,strlen($prefix));
        
        switch (substr(strtolower($query),0,4))
        {
            case 'sele':
            case 'show':
            case 'desc':
                $r = fetch_query($query, true);
                $ra = json_decode($r);
                if (count($ra)==0){
                    echo 'ERROR';
                }
                else
                {
                    header('Content-Type: application/json');
                    echo $r;
                }
                unset($ra);
                unset($r);
                break;
            default:
                if (exec_query($query)){ 
                    echo 'OK';
                }
                else
                {
                    echo 'ERROR';
                }      
            
        }        
    }
}
?>