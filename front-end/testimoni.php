<?php

$skip_morris = true;

include_once('../cores/definition.php');

include_once('../gammu/gammu-cores.php');
include_once('../cores/session.php');
if (!empty($_SESSION['AWT']))
{
    
}

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
                        <strong>Pendapat Anda</strong>                        
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="../pages/img/front-end/tulis-pendapat-pesan-kesan.jpg" class="img-circle" width="100%" />
                            </div>
                            <div class="col-md-10">
                                <div class="alert alert-info">
                                    <h3 class="panel-title">Tuliskan pendapat &amp; kesan Anda.</h3>
                                    Pendapat, pesan &amp; kesan Anda akan menjadi masukan bagi kami demi 
                                    meningkatkan pelayanan kami untuk Anda.                               
                                </div>                      
                            </div>
                        </div>    
                        <!-- Nama Anda -->
                        <div class="row">
                            <div class="col-md-2">
                                <label class="text-danger">Nama Anda:</label>
                            </div>
                            <div class="col-md-10 form-group required">
                                <input class="form-control" placeholder="e.g.: Lusi Andalusia" id="nama_anda" name="nama_anda" type="text" autofocus="">                                
                                                           
                            </div>
                        </div>                        
                        <!-- Email -->
                        <div class="row">
                            <div class="col-md-2">
                                <label class="text-danger">Email:</label>
                            </div>
                            <div class="col-md-10 form-group required">
                                <input class="form-control" placeholder="e.g.: anda@gmail.com" id="email" name="email" type="text">                                
                                                           
                            </div>
                        </div>
                        <!-- Pendapat Anda -->
                        <div class="row">
                            <div class="col-md-2">
                                <label class="text-danger">Pendapat</label>
                            </div>
                            <div class="col-md-10 form-group required">
                                <textarea class="form-control" id="pendapat" name="pendapat" placeholder="Pesan dan kesan Anda"></textarea>                
                            </div>
                        </div>     
                        
                        <!-- Errors -->
                        <div class="row hide" id="div-error">
                            <div class="col-md-2">
                                
                            </div>
                            <div class="col-md-10">
                                <div class="alert alert-danger" id="div-error-msg">
                                                                   
                                </div>                
                            </div>
                        </div>               
                    </div>
                        
                    
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">                                
                                <span class="pull-left">
                                    <a href="#" id="btn-save" class="btn btn-sm btn-info btn-block">Save</a>
                                </span>
                                <span class="pull-right">    
                                    <a href="#" id="btn-batal" class="btn btn-sm btn-warning btn-block">Batal</a>
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
       	    $('#btn-batal').click(function(e){
                e.preventDefault();
                location.href = 'index.php';
                return false;
            }); 
            $('.form-control').focus(function(){
                $('#div-error').removeClass('hide').addClass('hide');
            });
            $('#btn-save').click(function(e){
                e.preventDefault();
                var nama		= $('#nama_anda').val();
                var email        = $('#email').val();
                var pendapat          = $('#pendapat').val();
                var str = '';
                if (nama=='') { str += 'Nama Anda kosong.<br>'; }
                if (email=='') { str += 'Email Anda kosong.<br>'; }
                if (pendapat=='') { str += 'Pendapat Anda kosong.<br>'; }
                if (str!='')
                {
                    $('#div-error-msg').html(str);
                    $('#div-error').removeClass('hide');
                    return false;
                }
                alert (
                    nama+"\n"+
                    email+"\n"+
                    pendapat+"\n"
                );
                return false;
            });           
        });
    </script>    

<?php include '../pages/_footscripts.php'; ?>
<?php include '../pages/_foot.php'; ?>
