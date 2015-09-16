<?php

include_once('../cores/definition.php'); 
include_once('../cores/session.php');


// HEADER PARTS
$user_name = (isset($_SESSION['user_name'])?$_SESSION['user_name']:'');
$error_msg = (isset($_SESSION['error_message']) && (!empty($_SESSION['error_message'])) ? $_SESSION['error_message'] : 'Terjadi error.');
$_SESSION['error_message'] = '';
require_once('../cores/db.php'); 
require_once('../cores/functions.php'); 

$skip_morris = true;

/* UI */

$r = urldecode(get_var('r','index.php'));
$title = urldecode(get_var('title','Error!'));
$msg = urldecode(get_var('msg','An error has occurred.'));
$btn = urldecode(get_var('btn','Dashboard'));

?>
<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">

<head>
    <!--
		
        <?php echo SP_APP_NAME_SHORT .' v'. SP_APP_VERSION . PHP_EOL; ?>
        <?php echo SP_APP_NAME_LONG .' v'. SP_APP_VERSION . PHP_EOL; ?>
        Copyright <?php echo date('Y'); ?> Joko Rivai | Syahrir Nasser
        http://cenadep.org | http://syahrir.com
        fb.com/joko.rivai | fb.com/syahrir.nasser
        
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
    

    <title><?php echo SP_APP_NAME_SHORT ,' v', SP_APP_VERSION ;?></title>
    
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

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../bower_components/bootstrap3-dialog/dist/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong><?php echo SP_APP_NAME_SHORT .' v'. SP_APP_VERSION . PHP_EOL; ?></strong> - Error!</h3>
                    </div>
                    <div class="panel-body">
                        <div id="error-box" class="alert alert-danger">
                            <label><?php echo $title; ?></label>
                            <div><?php echo $msg; ?></div>
                            <!-- <a href="#" class="alert-link">Alert Link</a>. -->
                        </div>
                        <div>
                                <!-- Change this to a button or input when using this as a form -->
                                <a id="login-submit" href="<?php echo $r; ?>" class="btn btn-success">
                                    <i class="fa fa-chevron-left" style="width:12px;"></i> <?php echo $btn; ?>
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  

<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>
