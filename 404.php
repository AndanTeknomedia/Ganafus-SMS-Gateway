<?php
error_reporting(E_ALL ^ E_NOTICE);
$ref = $_SERVER['HTTP_REFERER'];
$ref = empty($ref) ? '/index.php' : $ref;
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="Joko Rivai" />

	<title>404 - Not Found!</title>
    <style>
    body{
        text-align: center;
        padding-top: 40px;
    }
    </style>
</head>

<body>

<!--
<a href="/index.php">
-->
<a href="<?php echo $ref; ?>">
    <img src="/404.png" />
</a>

</body>
</html>