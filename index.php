<?php

include_once ('cores/session.php');
if(!file_exists(realpath('dbconfig.php')))
{
    header('location:install/');
    die();
}
if (user_logged_in()){
    // echo 'ok';    
    header('location:pages/');
}
else
{
    // fall-back message:
    header('location:front-end/');
}
?>