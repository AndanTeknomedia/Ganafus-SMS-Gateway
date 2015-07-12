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

$_CLEANABLE_TABLES =  array(
    // 'tablename' => 'condition'
    'sentitems'             => "",
    'outbox'                => "",
    'outbox_multipart'      => "",
    'outbox_tmp'            => "",
    'inbox'                 => "",
    'sms_valid'             => "",          
    'inkubator_pinjam'      => "",
    'inkubator_monitoring'  => "",
    'inkubator_kembali'     => "",
    'configs'               => "where config_name = 'last_processed_valid_sms_id'",
    'sms_keywords'          => "",
    'sms_keyword_columns'   => ""
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

function drop_user_tables($exceptions = array())
{
    $tables = fetch_query('show tables');
    $tabs = array_values($tables);
    $ok = true;
    foreach($tabs as $table)
    {
        $v = array_values($table);
        $table = $v[0];
        if (substr($table, 0, strlen(USER_TABLE_PREFIX))== USER_TABLE_PREFIX)
        {   
            // $av[] = $table;
            $ok &= exec_query("drop table if exists `".$v[0]."`;");
        } 
    }
    // var_dump($av);
    return $ok;
}

function clean_tables()
{
    global $_CLEANABLE_TABLES;
    $ok = true;
    foreach ($_CLEANABLE_TABLES as $table=>$cond)
    {
        $ok &= exec_query("delete from `$table`".(empty($cond)?"":(" ".$cond)));
        // $ok .= "delete from `".$table."`".(empty($cond)?"":(" ".$cond)).";<br>";
    }
    /*
    var_dump($_CLEANABLE_TABLES);  
    var_dump( $ok );
    return true;
    */
    return $ok;
}
?>