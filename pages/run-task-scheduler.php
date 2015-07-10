<?php
$skip_morris = true;

include_once('../cores/definition.php');

if (USE_GAMMU){
    require_once('../gammu/gammu-cores.php');
    if (!is_gammu_ok())
    {
        header('location:setup-gammu.php');
        
    }
}
include_once('../gammu/gammu-cores.php');
include_once('../cores/db.php');
include_once('../cores/session.php');


require_login(); 


?>

<?php
include "_head.php";
function waktu() { echo date('H:i:s'); }
?>


<body>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default" style="margin-top: 50px;">
                    <div class="panel-heading">                        
                        <h3 class="panel-title"><i class="fa fa-send-o fa-fw"></i> <strong>Setup SMS Gateway</strong></h3>                        
                    </div>
                    <div class="panel-body">
                        <div class="list-group" id="list-progress">
                            <a href="#" class="list-group-item text-info" id="progress-0">
                                <i class="fa fa-gear fa-fw"></i> Initializing <img src="img/ajax-loaders/ajax-loader-1.gif">
                                <span class="pull-right text-muted small"><em>Wait...</em></span>
                            </a>
                            <!-- Windows Task Scheduler -->
                            <a href="#" class="list-group-item text-info" id="progress-1" ajax-uri="uninstallprocessor">
                                <i class="fa fa-gear fa-fw"></i> Menghapus tugas otomatis <img src="img/ajax-loaders/ajax-loader-1.gif">
                                <span class="pull-right text-muted small"><em>Wait...</em></span>
                            </a>
                            <a href="#" class="list-group-item text-info" id="progress-2" ajax-uri="installprocessor">
                                <i class="fa fa-gear fa-fw"></i> Membuat entri Windows Task Scheduler <img src="img/ajax-loaders/ajax-loader-1.gif">
                                <span class="pull-right text-muted small"><em>Wait...</em></span>
                            </a>
                            <a href="#" class="list-group-item text-success" id="progress-3" ajax-uri="queryprocessor">
                                <i class="fa fa-gear fa-fw"></i> Memeriksa status tugas <img src="img/ajax-loaders/ajax-loader-1.gif">
                                <span class="pull-right text-muted small"><em>Wait...</em></span>
                            </a>
                        </div>                        
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">                                
                                <span class="pull-left">
                                    <a href="#" id="btn-retry" class="btn btn-sm btn-success btn-block" style="display: none;">Coba Lagi</a>
                                    <a href="index.php" id="btn-next" class="btn btn-sm btn-info btn-block" style="display: none;">Finish</a>
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
            /*
            // DEBUG ONLY:
            $.post('../gammu/ajax-gammu-service.php',{rnd: Math.random(),command:'installprocessor', ajax:'ajax'},
    	        function(data)
                {	    
                    msgBox('Data', data);
                }                                
    	    );
            return false;
            */            
            var currentItem = 0;
            var allOK = true;
            var executeAndNext = function(i, success, data){                
                if (i>3) { return; }                
                var command = $('#progress-'+(i+1).toString()).attr('ajax-uri');
                // var uri = 'gammu-'+$('#progress-'+(i+1).toString()).attr('ajax-uri')+'.php';                
                // alert((i+1)+'->>'+command);
                allOK = allOK & success;
                if (!success) {                    
                    $('#progress-'+i+' img').hide();
                    $('#progress-'+i+' i').removeClass('fa-gear').addClass('fa-warning');
                    $('#progress-'+i+' em').text(data);
                    /*
                    $('#btn-retry').show();
                    $('#btn-next').hide();
                    */
                    executeAndNext(i+1, false, 'Dibatalkan.');
                }
                else
                {
                    // alert(i.toString() +':'+data);
                    $('#progress-'+i+' img').hide();
                    $('#progress-'+i+' i').removeClass('fa-gear').addClass('fa-check');
                    $('#progress-'+i+' em').text(data);     
                    
                    if (i<=3){               
                        $.post('../gammu/ajax-gammu-service.php',{rnd: Math.random(),command:command, ajax:'ajax'},
         			        function(data)
                            {	    
                                setTimeout(function(){
                                    p = data.substr(2);                                                                        
                                    if (data.substr(0,2) == 'OK')
                                    {
                                        executeAndNext(i+1, true, p);
                                    }
                				    else 
                                    {
                                        executeAndNext(i+1, false, p);
                                    }
                                },1000);
                            }                                
          			    );
                    }
                }
            };
            // executing tasks:
            $('#btn-retry').hide();
            $('#btn-next').hide();
            executeAndNext(currentItem, true, 'Done.');            
            
            if (allOK)
            {
                $('#btn-next').show();    
            }
            else
            {
                $('#btn-retry').show();     
            }
        });
    </script>    

<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>