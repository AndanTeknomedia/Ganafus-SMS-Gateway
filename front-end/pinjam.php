<?php

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
                        <strong>Form Peminjaman Inkubator</strong>                        
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h3 class="panel-title"><strong>Isikan Data-Data Berikut Untuk Melakukan Peminjaman</strong></h3>
                                    Data-data ini diperlukan untuk memonitor perkembangan bayi Anda.<br>
                                    Data ini sekaligus menjadi tolok ukur tingkat manfaat program penyediaan inkubator bayi gratis ini.
                                    <br>  
                                    <strong>Data yang ditandai <span  class="text-danger">berwarna merah</span> wajib diisi.</strong>                             
                                </div>                      
                            </div>
                        </div>    
                        <!-- Nama Bayi -->
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-danger">Nama Bayi:</label>
                            </div>
                            <div class="col-md-8 form-group required">
                                <input class="form-control" placeholder="e.g.: Lusi Andalusia" id="nama_bayi" name="nama_bayi" type="text">                                
                                                           
                            </div>
                        </div>
                        <!-- Tgl Lahir Bayi -->
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-danger">Tanggal Lahir:</label>
                            </div>
                            <div class="col-md-8">                                
                                <div class="required form-group input-group date" data-date="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" id="tgl_lahir">                        
                                    <input disabled="" type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo date('d/m/Y'); ?>">
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar-o"></i></span>                                              
                                </div>                                                            
                            </div>
                        </div> 
                        <!-- Tgl pulang Bayi -->
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-danger">Tanggal Pulang:</label>
                            </div>
                            <div class="col-md-8 required">                                
                                <div class="form-group input-group date" data-date="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" id="tgl_pulang">                        
                                    <input disabled="" type="text" class="form-control" id="tanggal_pulang" name="tanggal_pulang" value="<?php echo date('d/m/Y'); ?>">
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar-o"></i></span>                                              
                                </div>                                                            
                            </div>
                        </div> 
                        
                        <!-- Berat bayi -->
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-danger">Berat Waktu Lahir:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group input-group required">                        
                                    <input type="text" class="form-control" id="berat_lahir" name="berat_lahir" placeholder="e.g.: 2,5">
                                    <span class="input-group-addon add-on"><i class=""></i> Kg</span>                                              
                                </div>             
                            </div>
                        </div>
                        <!-- Panjang bayi -->
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-danger">Panjang Waktu Lahir:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group input-group required">                        
                                    <input type="text" class="form-control" id="panjang_lahir" name="panjang_lahir" placeholder="e.g.: 31">
                                    <span class="input-group-addon add-on"><i class=""></i> Cm</span>                                              
                                </div>             
                            </div>
                        </div>
                        <!-- Kondisi -->
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-danger">Kondisi Waktu Lahir:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group required">                        
                                    <select name="kondisi_lahir" id="kondisi_lahir" class="form-control">
                                        <option value="SEHAT" selected="">Sehat</option>
                                        <option value="SAKIT" >Sakit</option>
                                    </select>                                              
                                </div>             
                            </div>
                        </div>
                        <hr>
                        
                        <!-- RUmah sakt -->
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-normal">Nama Rumah Sakit/Klinik:</label>
                            </div>
                            <div class="col-md-8 form-group">                                
                                <input class="form-control" placeholder="e.g.: RSIA Sitti Khadijah" id="nama_rs" name="nama_rs" type="text">                                                                 
                            </div>
                        </div>
                        
                        <!-- Nama Dokter -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nama Dokter/Bidan:</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input class="form-control" placeholder="e.g.: dr. Imamunnisaa, Sp.OG." id="nama_dokter" name="nama_dokter" type="text">                                                           
                            </div>
                        </div>
                        <hr>
                        
                        <!-- No KK -->
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-danger">No. Kartu Keluarga:</label>
                            </div>
                            <div class="col-md-8 form-group required">
                                <input class="form-control" placeholder="Nomor KK" id="no_kk" name="no_kk" type="text">                                                           
                            </div>
                        </div>
                        <!-- Ibu -->
                        <div class="row">
                            <div class="col-md-4 form-group required">
                                <label><span class="text-danger">Nama Ibu, HP</span>, Email:</label>                            
                                <input class="form-control" id="nama_ibu" name="nama_ibu" type="text">                                                           
                            </div>
                            <div class="col-md-4 form-group">
                                <label>&nbsp;</label>                                                            
                                <input class="form-control" placeholder="08xx...    " id="hp_ibu" name="hp_ibu" type="text">                                                           
                            </div>
                            <div class="col-md-4 form-group">
                                <label>&nbsp;</label>                                                            
                                <input class="form-control" placeholder="ibu@gmail.com" id="email_ibu" name="email_ibu" type="text">                                                           
                            </div>
                        </div>
                        <!-- Ayah -->
                        <div class="row">
                            <div class="col-md-4 form-group required">
                                <label><span class="text-danger">Nama Ayah</span>, HP, Email:</label>                            
                                <input class="form-control" id="nama_ayah" name="nama_ayah" type="text">                                                           
                            </div>
                            <div class="col-md-4 form-group">
                                <label>&nbsp;</label>                                                            
                                <input class="form-control" placeholder="08xx...    " id="hp_ayah" name="hp_ayah" type="text">                                                           
                            </div>
                            <div class="col-md-4 form-group">
                                <label>&nbsp;</label>                                                            
                                <input class="form-control" placeholder="ayah@gmail.com" id="email_ayah" name="email_ayah" type="text">                                                           
                            </div>
                        </div>  
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                Kode pinjam akan dikirim lewat SMS ke HP Ibu. 
                                <strong>Balas SMS tersebut dengan mengetik <span class="text-danger">YES</span></strong> untuk melakukan konfirmasi.
                                Peminjaman tanpa konfirmasi akan diabaikan.
                                <br>
                                <span class="text-success">Konfirmasi ini tidak diperlukan jika Anda melakukan peminjaman lewat SMS.</span>
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
       	    // $('.tgl').datepicker();
            $('div.date').datepicker();   
            $('#btn-batal').click(function(e){
                e.preventDefault();
                msgConfirm('Konfirmasi','Anda yakin membatalkan peminjaman?','Ya','Tidak',function(ret){
                    if (ret) {
                        location.href = 'index.php';
                    }
                });
                return false;
            });  
            $('.form-control').blur(function(e){
                var t = $(this).parent();
                // alert($(this).tagName);
                return false;
            });
            $('#btn-save').click(function(e){
                e.preventDefault();
                var nama_bayi		= $('#nama_bayi').val();
                var tanggal_lahir   = $('#tanggal_lahir').val();
                var tanggal_pulang  = $('#tanggal_pulang').val();
                var berat_lahir     = $('#berat_lahir').val();
                var panjang_lahir   = $('#panjang_lahir').val();
                var kondisi_lahir   = $('#kondisi_lahir').val();
                var nama_rs         = $('#nama_rs').val();
                var nama_dokter     = $('#nama_dokter').val();
                var no_kk           = $('#no_kk').val();
                var nama_ibu        = $('#nama_ibu').val();
                var hp_ibu          = $('#hp_ibu').val();
                var email_ibu       = $('#email_ibu').val();
                var nama_ayah       = $('#nama_ayah').val();
                var hp_ayah         = $('#hp_ayah').val();
                var email_ayah      = $('#email_ayah').val();
                var str = '';
                if (nama_bayi=='') { str += 'Nama bayi kosong'; }
                if (tanggal_lahir=='') { str += 'Tanggal lahir kosong'; }
                if (tanggal_pulang=='') { str += 'Tanggal pulang kosong'; }
                if (berat_lahir=='') { str += 'Berat lahir kosong'; }
                if (panjang_lahir=='') { str += 'Panjang lahir kosong'; }
                
                alert (
                    nama_bayi+"\n"+
                    tanggal_lahir+"\n"+
                    tanggal_pulang+"\n"+
                    berat_lahir+"\n"+
                    panjang_lahir+"\n"+
                    kondisi_lahir+"\n"+
                    nama_rs+"\n"+
                    nama_dokter+"\n"+
                    no_kk+"\n"+
                    nama_ibu+"\n"+
                    hp_ibu+"\n"+
                    email_ibu+"\n"+
                    nama_ayah+"\n"+
                    hp_ayah+"\n"+
                    email_ayah
                );
                return false;
            });           
        });
    </script>    

<?php include '../pages/_footscripts.php'; ?>
<?php include '../pages/_foot.php'; ?>
