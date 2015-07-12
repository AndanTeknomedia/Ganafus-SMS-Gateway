<?php 
// HEADER PARTS

require_once('../cores/definition.php'); 
require_once('../cores/functions.php'); 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo SP_APP_NAME_SHORT ,' v', SP_APP_VERSION ;?>">
    <meta name="author" content="<?php echo SP_AUTHOR ;?>">

    <title><?php 
    echo SP_APP_NAME_SHORT ,' v', SP_APP_VERSION;
    ?></title>
    
    <link rel="icon" type="image/png" href="../favicon.png" />

    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
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
    
    <script>
    
    var stages = ['Konfigurasi Database','Pengecekan Gammu'];
    var currentStageIndex = 0;
    
    function msgBox(caption, text, autoClose){
        BootstrapDialog.show({
            size    : BootstrapDialog.SIZE_SMALL,
            draggable: true,
            title   : caption || 'Informasi',
            message : text || 'Ada pesan baru.',
            onshown: function(d){
                if (autoClose || true) {
                    setTimeout(function(){d.close();},7000);
                }
            }
        });    
    }
    
    function msgConfirm(caption, text, btnYes, btnNo, callBack)
    {
        var res;
        BootstrapDialog.show({
            size: BootstrapDialog.SIZE_SMALL,
            draggable: true,
            title: caption || 'Konfirmasi',
            message: text || 'Anda yakin untuk melanjutkan?',
            buttons: 
            [
                {
                    label: btnYes,
                    cssClass: 'btn-primary',
                    icon: 'fa fa-check',
                    action: function(dlg){                    
                        dlg.close();
                        callBack(true);
                    }
                },
                {
                    label: btnNo,
                    cssClass: 'btn-warning',
                    icon: 'fa fa-ban',
                    action: function(dlg){
                        dlg.close();
                        callBack(false);
                    }
                }
            ]
        });
    }    
    
    function assigned(obj)
    {
        return (!(typeof obj === 'undefined'));
    }
    
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }
    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
        return "";
    }  
    </script>

</head>

<body>

    <div class="wrapper">
        
        <div class="row" >
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default" style="margin-top: 50px;">
                    <div class="panel-heading">
                        <strong>Instalasi <?php echo SP_APP_NAME_SHORT.' v'.SP_APP_VERSION; ?></strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h3 class="panel-title"><strong>Pengaturan Koneksi Database</strong></h3>
                                    Isikan data-data berikut untuk mengatur koneksi database. <br />
                                    Jika Anda tidak yakin, tanyakan pada administrator.   
                                    <br />
                                    1. Create a user on MySQL server.<br />
                                    2. Create a database on MySQL server.<br />
                                    3. Assign the database to the user.<br />
                                    4. Restore file <strong>database.sql</strong> in folder <strong>install</strong> into the database.<br />
                                    5. Complete tasks below.                                
                                </div>   
                                <div class="alert alert-danger">
                                    User harus memilik akses penuh terhadap database, karena diperlukan untuk
                                    membuat tabel, mengupdate field-field dan mengatur trigger.       
                                </div>                          
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="text-danger">Database Host</label>
                                        <input class="form-control" placeholder="e.g.: localhost" id="dbhost" name="dbhost" type="text" autofocus="">                               
                                    </div>
                                </div>     
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="text-danger">Username</label>
                                        <input class="form-control" placeholder="e.g.: root" id="dbuser" name="dbuser" type="text">                               
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="text-danger">Password</label>
                                        <input class="form-control" id="dbpassword" name="dbpassword" type="password">                               
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="text-danger">Database</label>
                                        <input class="form-control" placeholder="e.g.: mydatabase" id="dbdatabase" name="dbdatabase" type="text">                               
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"><br />
                                        <label class="checkbox-inline">
                                            <input type="checkbox" class="" id="usegammu" name="usegammu"> Pasang Server Gammu.
                                        </label>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-md-6 col-lg-offset-3" style="margin-top: 40px;">
                                        <img class="img-circle" src="install-01.jpg" width="100%">                               
                                    </div>
                                </div> 
                            </div>
                        </div>                                                             
                    </div>
                        
                    
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">                                
                                <span class="pull-right">
                                    <a href="#" id="btn-lanjut" class="btn btn-sm btn-info btn-block">Lanjut</a>
                                </span>
                            </div>
                        </div>                    
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <script>
    $(document).ready(function(){
        $('#btn-lanjut').click(function(e){
            e.preventDefault();
            var host = $('#dbhost').val();
            var user = $('#dbuser').val();
            var pass = $('#dbpassword').val();
            var db   = $('#dbdatabase').val();
            var usegammu = $('#usegammu').is(':checked'); 
            var er = '';
            if (host == '') {
                er += 'Host jangan kosong.<br>';
            }
            if (user == '') {
                er += 'Username jangan kosong.<br>';
            }
            if (db == '') {
                er += 'Database jangan kosong.<br>';
            }
            if (er!='')
            {
                msgBox('Error',er);
                return false;
            }
            var divLoader = $(
                                '<div style="padding:10px;" class="row"><center>'+
                                '<img src="../pages/img/ajax-loaders/ajax-loader-7.gif" title="img/ajax-loaders/ajax-loader-7.gif">'+
                                '</center></div>'); 
            var dialogDB = BootstrapDialog.show({
                size: BootstrapDialog.SIZE_SMALL,
                closable: false,
                title: 'Mengonfigurasi Koneksi Database',
                message: divLoader,
                draggable: true,
                onshown: function(dlg)
                {
                    $('#btn-lanjut').hide();
                    $.post('ajax-install-and-test-db.php',
            		{
            			host: host,
                        user: user,
                        pass: pass,
                        db: db,
                        usegammu: usegammu
            		},
    				function(data)
    				{
    				    dlg.close();
                        $('#btn-lanjut').show();
                        if (data == 'OK')
    					{   
                            $.post('../cores/db-clean-tables.php',{r: Math.random()}, function(data){
                                if (data.substr(0,2).toUpperCase()=='OK')
                                {
                                    location.href = '../pages/';
                                }
                                else
                                {
                                    msgBox('Masalah!', '<div class="alert alert-warning">' + data.substr(2) + '</div>', false); 
                                }    
                            });
    					}
    					else
    					{
    						msgBox('Astaga!', '<div class="alert alert-warning">' + data + '</div>', false);
    					}
                        
    				});
                } 
            });
            dialogDB.open();                        
            return false; 
        });                 
    });
    </script>
    
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../bower_components/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js"></script>
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <script src="../dist/js/sb-admin-2.js"></script></body>

</html>