<?php


foreach ($argv as $arg) {
    $e=explode("=",$arg);
    if(count($e)==2)
        $_GET[$e[0]]=$e[1];
    else   
        $_GET[$e[0]]=0;
}

$f = fopen('c:/php_log.txt', 'w');
foreach ($_GET as $key => $value)
{
    fputs($f, "$key=$value\n");
}
fclose($f);

?>