<?php

/**
 * insert page definitions here:
 */
 
$page_name = 'Blank Page';

$skip_morris = true;

include_once('../cores/definition.php'); 

include_once('../cores/session.php');
// require_login('post.php?show/newest');
require_login();

?>

<?php
include "_head.php";
?>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            
            <?php include '_logoarea.php'; ?>

            <?php include '_topnavs.php' ?>

            <?php include '_leftnavs.php' ?>
            
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        <?php echo $page_name; ?>
                    </h1>
                </div>   
            </div> 
            <div class="row">
            </div>                    
        </div>
    </div>
    <!-- /#wrapper -->

    
<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>