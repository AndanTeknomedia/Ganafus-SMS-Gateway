<?php 
// HEADER PARTS



$user_name = (isset($_SESSION['user_name'])?$_SESSION['user_name']:'');
$error_msg = (isset($_SESSION['error_message']) && (!empty($_SESSION['error_message'])) ? $_SESSION['error_message'] : '');
$_SESSION['error_message'] = '';
require_once('../cores/db.php'); 
require_once('../cores/functions.php'); 

/* UI */ 
require_once('../cores/ui/ui-helper.php'); 



 
/**
 * Query Strings:
 */

$query_string = get_query_string();
// header('Content-type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <!--
		
        <?php echo SP_APP_NAME_SHORT .' v'. SP_APP_VERSION . PHP_EOL; ?>
        <?php echo SP_APP_NAME_LONG .' v'. SP_APP_VERSION . PHP_EOL; ?>
        Copyright <?php echo date('Y'); ?> Joko Rivai
        http://kppdi.ga
        fb.com/joko.rivai 
        
        # [Start Bootstrap](http://startbootstrap.com/) - [SB Admin 2](http://startbootstrap.com/template-overviews/sb-admin-2/)

        [SB Admin 2](http://startbootstrap.com/template-overviews/sb-admin-2/) is an open source, admin dashboard template for [Bootstrap](http://getbootstrap.com/) created by [Start Bootstrap](http://startbootstrap.com/).
        
        
        ## Creator
        
        Start Bootstrap was created by and is maintained by **David Miller**, Managing Parter at [Iron Summit Media Strategies](http://www.ironsummitmedia.com/).
        
        * https://twitter.com/davidmillerskt
        * https://github.com/davidtmiller
        
        Start Bootstrap is based on the [Bootstrap](http://getbootstrap.com/) framework created by [Mark Otto](https://twitter.com/mdo) and [Jacob Thorton](https://twitter.com/fat).
        
        ## Copyright and License
        
        Copyright 2013-2015 Iron Summit Media Strategies, LLC. Code released under the [Apache 2.0](https://github.com/IronSummitMedia/startbootstrap-sb-admin-2/blob/gh-pages/LICENSE) license.
		
	-->    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo SP_APP_NAME_SHORT ,' v', SP_APP_VERSION ;?>">
    <meta name="author" content="<?php echo SP_AUTHOR ;?>">

    <title><?php 
    if (isset($page_name) && (!empty($page_name)))
    {
        echo $page_name;
    }
    else
    { 
        echo SP_APP_NAME_SHORT ,' v', SP_APP_VERSION;
    } 
    ?></title>
    
    <link rel="icon" type="image/png" href="../favicon.png" />

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="../dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../bower_components/morrisjs/morris.css" rel="stylesheet">
    
    <!-- DatePicker by Stefan Petre -->
    <link href="../non_bower_components/datepicker/css/datepicker.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../bower_components/bootstrap3-dialog/dist/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
    
    <!-- DataTables CSS -->
    <link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <link href="../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <!-- SlowAES Encyption -->
    <script src="../dist/js/aes.js"></script>
    <script src="../dist/js/cryptoHelpers.js"></script>
    <script src="../dist/js/jsHash.js"></script>
    <script src="../dist/js/slowaescrypt.js"></script>        
    
    
    <script src="../dist/js/ajax-overlay.js"></script>
    
    <script src="../dist/js/head-utils.js"></script>

</head>