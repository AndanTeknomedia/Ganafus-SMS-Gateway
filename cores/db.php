<?php

include_once (
    dirname(dirname(__FILE__)).'/dbconfig.php'
);

$_mysqli = new mysqli(
	DB_HOST,
    DB_USER,
    DB_PASSWORD,
	DB_DATABASE
);

if ($_mysqli->connect_errno)
{
    die("DATABASE CONNECTION IS INVALID!<br>
    <a href=\"/install/\">Klik di sini</a> untuk mengatur koneksi.
    ");
    //die();
}

function get_user_by_username($username)
{
	global $_mysqli;
	$result = array();
	$q = $_mysqli->query("SELECT id, first_name, last_name, user_group_id FROM users  WHERE user_name = '$username'");	
	if ($q)
	{
		$row = $q->fetch_assoc();
		$result = array(
				'id' => $row['id'],
				'first_name' => $row['first_name'],
				'last_name' => $row['last_name'],
				'group_id' => $row['user_group_id']
			);
		$q->free();
	}
	
	return $result;	
}

/*
** WARNING!!!!! ALL RESULTSETS ARE ARRAYS. YOU MUST FETCH THEIR ELEMENT[s] TO USE THEM!!!
*/

function fetch_query($sql, $as_json = false)
{
	global $_mysqli;	
	
	$result = array();
	$q = $_mysqli->query($sql);	
	if ($q)
	{
		if (!$as_json){
            $j=0;
    		while ($row = $q->fetch_row())
    		{				
    			for ($i=0; $i<$q->field_count; $i++)
    			{	
    				$f = $q->fetch_field_direct($i); 
    				$result[$j][$f->name] = $row[$i];
    			}						
    			$j++;
    		}
      }
      else
      {            
            while($r = $q->fetch_assoc()) {
                $result[] = $r;
            }
            $result = json_encode($result);
      }
		$q->free();
	}	
	return $result;	
}

function fetch_one_value($sql)
{
    global $_mysqli;
	
	$result = array();
    $q = $_mysqli->query($sql);	
	if ($q)
	{		           
        $r = $q->fetch_row();     
        $result = $r[0];
        $q->free();
	}	
	return $result;	
}

function exec_query($sql)
{
	global $_mysqli;	
	if ($_mysqli->query($sql) === TRUE)
	{
		return true;
	}
	else
	{
		return false;
	}
}

?>