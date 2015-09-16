<?php
// echo realpath('../dbconfig.php');
if(!file_exists(realpath('../dbconfig.php')))
{
    header('location:../install/');
    die();
}

$skip_morris = true;

include_once('../cores/definition.php');

include_once('../gammu/gammu-cores.php');
include_once('../cores/session.php');


?>

<?php
include "../pages/_head.php";
?>


<body>

    <div class="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" >
            <?php include '../pages/_logoarea.php'; ?>
            <ul class="nav navbar-top-links navbar-right" >                
                <li class="dropdown">                                                            
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <?php if (!user_logged_in()) { ?>
                        <li><a href="../pages/login.php"><i class="fa fa-sign-in fa-fw"></i> Login</a></li>
                        <?php } else { ?>
                        <li><a href="../pages/"><i class="fa fa-user fa-fw"></i> Dashboard</a></li>                        
                        <li class="divider"></li>
                        <li><a href="../cores/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                        <?php }?>
                    </ul>             
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
            
        </nav>
        <div class="row" >
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default" style="margin-top: 50px;">
                    <div class="panel-heading">
                        <strong>Selamat Datang</strong>
                        <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                        Menu
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <!--
                                        <li><a href="pinjam.php">Peminjaman</a></li>
                                        <li><a href="monitor.php">Input Data Monitoring</a></li>
                                        <li><a href="kembali.php">Pengembalian</a></li>
                                        <li class="divider"></li>
                                        <li><a href="testimoni.php">Pendapat Anda</a></li>
                                        -->
                                        <?php if (!user_logged_in()) { ?>
                                        <li><a href="../pages/login.php">Login</a></li>
                                        <?php } else { ?>
                                        <li><a href="../pages/">Dashboard</a></li>                        
                                        <li class="divider"></li>
                                        <li><a href="../cores/logout.php">Logout</a></li>
                                        <?php }?>
                                    </ul>
                                </div>
                            </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-10">
                            <?php 
                            $p = fetch_query("select id, title, excerpt from frontend_posts where flag = 'about'");                            
                            $post = $p[0];
                            unset($p);
                            ?>
                                <div class="alert alert-info">
                                    <h3 class="panel-title"><strong><?php echo $post['title']; ?></strong></h3>
                                    <?php echo $post['excerpt']; ?>
                                    <a class="pull-right" href="posts.php?id=<?php echo $post['id']; ?>" title="<?php echo $post['title']; ?>"><i class="fa fa-chevron-right"></i></a>                                    
                                </div>
                            </div>   
                            <div class="col-md-2" style="padding-top: 6px;">
                                <img class="img-circle" src="../pages/img/front-end/tentang-inkubator-gratis.jpg" width="100%" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2" style="padding-top: 6px;">
                                <img class="img-circle" src="../pages/img/front-end/cara-pinjam-inkubator-gratis.jpg" width="100%" />
                            </div>
                            <div class="col-md-10">
                            <?php 
                            $p = fetch_query("select id, title, excerpt from frontend_posts where flag = 'howto'");                            
                            $post = $p[0];
                            unset($p);
                            ?>
                                <div class="alert alert-success">
                                    <h3 class="panel-title"><strong><?php echo $post['title']; ?></strong></h3>
                                    <?php echo $post['excerpt']; ?>
                                    <a class="pull-right" href="posts.php?id=<?php echo $post['id']; ?>" title="<?php echo $post['title']; ?>"><i class="fa fa-chevron-right"></i></a>                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                            <?php 
                            $p = fetch_query("select id, title, excerpt from frontend_posts where flag = 'tos'");                            
                            $post = $p[0];
                            unset($p);
                            ?>
                                <div class="alert alert-warning">
                                    <h3 class="panel-title"><strong><?php echo $post['title']; ?></strong></h3>
                                    <?php echo $post['excerpt']; ?>
                                    <a class="pull-right" href="posts.php?id=<?php echo $post['id']; ?>" title="<?php echo $post['title']; ?>"><i class="fa fa-chevron-right"></i></a>                                    
                                </div>                           
                            </div>
                            <div class="col-md-2" style="padding-top: 6px;">
                                <img class="img-circle" src="../pages/img/front-end/syarat-dan-ketentuan-peminjaman-inkubator-gratis.jpg" width="100%" />
                            </div>
                        </div>                        
                    </div>
                        
                    
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">                                
                                <span class="">
                                    <!--
                                    <a href="pinjam.php" id="btn-save" class="btn btn-sm btn-info btn-block">Peminjaman</a>
                                    <a href="monitor.php" id="btn-save" class="btn btn-sm btn-success btn-block">Input Data Monitoring</a>
                                    <a href="kembali.php" class="btn btn-sm btn-warning btn-block">Pengembalian</a>
                                    <a href="testimoni.php" class="btn btn-sm btn-danger btn-block">Tuliskan Pendapat Anda</a>
                                    -->
                                    <?php if (!user_logged_in()) { ?>
                                    <a href="../pages/login.php" id="btn-save" class="btn btn-sm btn-info btn-block">Login</a>
                                    <?php } else { ?>
                                    <a href="../pages/" id="btn-save" class="btn btn-sm btn-info btn-block">Dashboard</a>
                                    <a href="../cores/logout.php" id="btn-save" class="btn btn-sm btn-danger btn-block">Logout</a>                                    
                                    <?php }?>
                                </span>
                            </div>
                        </div>                    
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() { 
       	    
        });
    </script>    

<?php include '../pages/_footscripts.php'; ?>
<?php include '../pages/_foot.php'; ?>
