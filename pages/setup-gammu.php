<?php


$skip_morris = true;

include_once('../cores/definition.php');

if (!USE_GAMMU)
{
    header('location:index.php');
    
} 
include_once('../gammu/gammu-cores.php');
include_once('../cores/db.php');
include_once('../cores/session.php');


require_login(); 


?>

<?php
include "_head.php";

/**
 * Fetch curent modem setup if available:
 */
 $modems = fetch_query("select * from modem_gateway order by id desc limit 0,1");
 // var_dump($modems);
 if (count($modems)>0)
 {
    $modem = $modems[0];
 }
 else
 {
    $modem = false;
 }
 
 unset($modems);
 
 function modem_value($val_name)
 {
    global $modem;
    if (!$modem) { return ''; }
    if (!$modem[$val_name]) { return ''; }
    return $modem[$val_name];
 }
 // print($modem);
?>


<body>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default" style="margin-top: 50px;">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Setup SMS Gateway</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    Anda belum mengatur konfigurasi SMS Gateway.<br>
                                    Jika pengaturan ini tidak Anda pahami, hubungi Administrator.
                                    <br />
                                    <br />                                      
                                    <a href="gammu-setup-help.php" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-info"></i> Bantuan</a>                                                                     
                                </div>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Nama Modem:</label>
                                <input value="<?php echo modem_value('nama_modem'); ?>" class="form-control" placeholder="e.g.: Modem ZTE Cosmote MF636" id="nama_modem" name="nama_modem" type="text" autofocus>
                                <br>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Nama Port Modem:</label>
                                <input value="<?php echo strtoupper(modem_value('nama_port')); ?>" class="form-control" placeholder="e.g.: COM1" id="nama_port" name="nama_port" type="text">
                                <br>                                
                            </div>
                        </div>                        
                        <div class="row">
                            <div class="col-md-12">
                                <label>Jenis Koneksi (default AT):</label>                                
                                <select class="form-control" id="mode" name="mode">
                                    <?php 
                                    $modes = fetch_query('select * from modem_modes order by mode asc');
                                    $m = modem_value('mode');
                                    foreach ($modes as $i=>$mode) {                                        
                                        if ($m=='') {
                                            echo '<option value="'.$modes[$i]['mode'].'" '.($modes[$i]['default']=='Y' ? 'selected=""':'').'>'.$modes[$i]['mode'].'</option>';
                                        }
                                        else
                                        {
                                            echo '<option value="'.$modes[$i]['mode'].'" '.($modes[$i]['mode']==$m ? 'selected=""':'').'>'.$modes[$i]['mode'].'</option>';
                                        }
                                    }
                                    unset($modes);
                                    ?>                                    
                                </select>     
                                <br>                           
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Baudrate (Speed) Modem:</label>                                
                                <select class="form-control" id="baudrate" name="baudrate">
                                    <?php 
                                    $modes = fetch_query('select * from modem_baudrates order by baudrate asc');
                                    $m = modem_value('baudrate');
                                    foreach ($modes as $i=>$mode) {                                        
                                        if ($m=='') {
                                            echo '<option value="'.$modes[$i]['baudrate'].'" '.($modes[$i]['default']=='Y' ? 'selected=""':'').'>'.$modes[$i]['baudrate'].'</option>';
                                        }
                                        else
                                        {
                                            echo '<option value="'.$modes[$i]['baudrate'].'" '.($modes[$i]['baudrate']==$m ? 'selected=""':'').'>'.$modes[$i]['baudrate'].'</option>';
                                        }
                                    }
                                    unset($modes);
                                    ?>                                    
                                </select>    
                                <br>                            
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-12">
                                <label>Path Lokasi Gammu.exe (copy &amp; paste):</label>                                                                 
                                <div class="form-group input-group">                                    
                                    <input value="<?php echo modem_value('gammu_path'); ?>" type="text" class="form-control" placeholder="e.g.: C:\Gammu\Bin" id="path-temp" name="path-temp">
                                    <span class="input-group-addon" id="btn-browse"><i id="icon-browse" class="fa fa-file"></i></span>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-12">
                                <label>Path Lokasi Php.exe (copy &amp; paste):</label>                                                                 
                                <div class="form-group input-group">                                    
                                    <input value="<?php echo modem_value('php_path'); ?>" type="text" class="form-control" placeholder="e.g.: C:\PHP\Bin" id="php-temp" name="php-temp">
                                    <span class="input-group-addon" id="btn-browse"><i id="icon-browse-php" class="fa fa-file"></i></span>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-12">
                                <label>Nomor SMSC (Tanyakan pada administrator):</label>                                                                 
                                <input value="<?php echo modem_value('smsc'); ?>" type="text" class="form-control" placeholder="e.g.: +6281100000" id="smsc" name="smsc">
                                <br>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-12">
                                <label>File .LOG</label>                                                                 
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="" id="uselog" name="uselog"  <?php echo (modem_value('use_log')=='Y'? 'checked=""' : ''); ?>"> Ya, buat catatan LOG
                                </label>
                                
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">                                
                                <span class="pull-left">
                                    <a href="#" id="btn-save" class="btn btn-sm btn-success btn-block">Save</a>
                                    <a href="run-gammu.php" id="btn-next" class="btn btn-sm btn-info btn-block" style="display: none;">Start SMS Gateway</a>
                                    <!--
                                    <img id="setup-progress" src="img/ajax-loaders/ajax-loader-7.gif" title="img/ajax-loaders/ajax-loader-7.gif">
                                    -->
                                </span>                                                             
                                <span class="pull-right">
                                    <a href="../cores/logout.php" class="btn btn-sm btn-warning btn-block">Logout</a>
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
            $('#path-temp').change(function(){
                var dir = String($('#path-temp').val());                 
                setTimeout(function(){
                    if (dir==''){
                        $('#icon-browse').removeClass('fa-check').addClass('fa-file');
                    }
                    else
                    {
                        $('#icon-browse').removeClass('fa-file').addClass('fa-check');
                    }
                }, 500);                
            });
            $('#php-temp').change(function(){
                var dir = String($('#php-temp').val());                 
                setTimeout(function(){
                    if (dir==''){
                        $('#icon-browse-php').removeClass('fa-check').addClass('fa-file');
                    }
                    else
                    {
                        $('#icon-browse-php').removeClass('fa-file').addClass('fa-check');
                    }
                }, 500);                
            });
       	    $('#btn-save').click(function(e){
       	        e.defaultPrevented = true;  
                e.preventDefault();        
               
                var nama_modem = $('#nama_modem').val();
                var nama_port = $('#nama_port').val();
                /*
                if (nama_port == '')
                {
                    msgBox('Error','Nama Port harus diisi');
                    return false;
                }
                */
                var mode = $('#mode').val();
                var baudrate = $('#baudrate').val();
                var pathx = $('#path-temp').val();
                var path = pathx /* + (pathx.charAt(pathx.length-1)=='\\' ? 'Gammu.exe' : '\\Gammu.exe') */;
                var php = $('#php-temp').val();
                var smsc = $('#smsc').val();
                var uselog = $('#uselog').is(':checked');               
                
                var divLoader = $(
                                '<div style="padding:10px;" class="row"><center>'+
                                '<img src="img/ajax-loaders/ajax-loader-7.gif" title="img/ajax-loaders/ajax-loader-7.gif">'+
                                '</center></div>'); 
                var dialogSKPD = BootstrapDialog.show({
                    size: BootstrapDialog.SIZE_SMALL,
                    closable: false,
                    title: 'Mengonfigurasi SMS Gateway',
                    message: divLoader,
                    draggable: true,
                    onshown: function(dlg)
                    {
                       
                        $.post('../gammu/ajax-setup-gammu.php',
                		{
                			nama_modem: nama_modem,
                            nama_port: nama_port,
                            mode: mode,
                            baudrate: baudrate,
                            path: path,
                            smsc: smsc,
                            use_log: uselog,
                            php: php
                		},
        				function(data)
        				{
        				    dlg.close();
                            if (data == 'OK')
        					{   
                                $('#btn-save').hide();
                                $('#btn-next').show();
                                msgBox('Informasi',
                                    '<div class="alert alert-info">'+
                                    'Konfigurasi SMS Gateway selesai. Klik Start SMS Gateway untuk menjalan server Gammu.'+
                                    '</div>'
                                );
                                
        					}
        					else
        					{
        						msgBox('Astaga!', '<div class="alert alert-warning">' + data + '</div>', false);
        					}
                            
        				});
                    } 
                });
                dialogSKPD.open();                        
                return false;              
       	    });
            // END OF SAVE DATA
        });
    </script>    

<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>
