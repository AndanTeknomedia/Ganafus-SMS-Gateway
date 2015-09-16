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
$ajax = post_var('ajax');
if($ajax=='ajax')
{
    $sx = post_var('smscount');
    if (set_system_config('sms_to_process_per_minute', $sx))
    {
        echo 'OK';
    }
    else
    {
        echo 'ER';
    }
    die('ER');
}

require_login(); 
require_admin('index.php','Pooling SMS Settings', 'Anda tidak memiliki hak akses ke halaman ini.', 'Dashboard');

$sms_count_per_min = get_system_config('sms_to_process_per_minute', 20);

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
                        <div class="row">   
                            <div class="col-lg-12">
                                <div class="list-group" id="list-control">
                                    <?php /* ?>
                                    <a href="#" class="list-group-item text-info">
                                        <i class="fa fa-clock-o fa-fw"></i> Proses SMS setiap 
                                        <span class="pull-right text-muted small">
                                            <select id="sms-daemon-timer" class="form-control input-sm">                                        
                                                <option value="1" selected="">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                            </select> menit.
                                        </span>
                                    </a>
                                    <?php */ ?>
                                    <a href="#" class="list-group-item text-info">
                                        <button class="btn btn-primary btn-sm" id="run-sms-daemon"> Jalankan SMS Daemon <i class="fa fa-chevron-right"></i></button>
                                    </a>
                                </div>
                                <div class="list-group" id="list-progress">
                                    <a href="#" class="list-group-item text-info" id="progress-0">
                                        <i class="fa fa-gear fa-fw"></i> Initializing 
                                        <span class="pull-right text-muted small"><em>Wait...</em></span>
                                    </a>
                                    <!-- Windows Task Scheduler -->
                                    <a href="#" class="list-group-item text-info" id="progress-1" ajax-uri="uninstallprocessor">
                                        <i class="fa fa-gear fa-fw"></i> Menghapus tugas otomatis 
                                        <span class="pull-right text-muted small"><em>Wait...</em></span>
                                    </a>
                                    <a href="#" class="list-group-item text-info" id="progress-2" ajax-uri="installprocessor">
                                        <i class="fa fa-gear fa-fw"></i> Membuat entri Windows Task Scheduler 
                                        <span class="pull-right text-muted small"><em>Wait...</em></span>
                                    </a>
                                    <a href="#" class="list-group-item text-success" id="progress-3" ajax-uri="queryprocessor">
                                        <i class="fa fa-gear fa-fw"></i> Memeriksa status tugas 
                                        <span class="pull-right text-muted small"><em>Wait...</em></span>
                                    </a>
                                </div>     
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <span class="form-group input-group" >
                                    <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
                                    <!--
                                    <input type="text" class="form-control" id="sms-count" placeholder="Process Count" value="<?php echo $sms_count_per_min; ?>">
                                    -->
                                    <?php 
                                    $sc_values = array(10,20,50,100,200);
                                    echo '<select class="form-control" id="sms-count">';
                                    foreach($sc_values as $v)
                                    {
                                        echo '<option value="'.$v.'" '.($sms_count_per_min == $v ? 'selected=""' : ''). '>'.$v.'</option>';
                                    }                                    
                                    echo '</select>';
                                    unset($sc_values);
                                    ?>
                                    <span class="input-group-addon">SMS/min</span>
                                </span>
                            </div>
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
            // var countTester= /^[a-z0-9]+$/i;
            $('#run-sms-daemon').click(function(e){    
                e.preventDefault;
                var currentItem = 0;
                var allOK = true;
                var smsCount = $('#sms-count').val();
                $.post('<?php echo $_SERVER['PHP_SELF']; ?>',{ajax:'ajax',r: Math.random(), smscount: smsCount}, function(data){
                    if (data.substr(0,2).toUpperCase()=='ER')
                    {
                        
                    }
                });
                for (var i =0; i<4; i++)
                {
                    $('#progress-'+(i).toString()).find('i')
                        .removeClass('fa-spin')
                        .removeClass('fa-check')
                        .removeClass('fa-warning')
                        .addClass('fa-gear');    
                }
                var executeAndNext = function(i, success, data){                
                    if (i>3) { return; }                
                    var el = $('#progress-'+(i+1).toString());
                    $('#progress-'+(i).toString()).find('i').removeClass('fa-spin');
                    $(el).find('i').addClass('fa-spin');      
                    var command = $(el).attr('ajax-uri');
                    allOK = allOK & success;
                    if (!success) {        
                        $('#progress-'+i+' i').removeClass('fa-gear').addClass('fa-warning');
                        $('#progress-'+i+' em').text(data);
                        executeAndNext(i+1, false, 'Dibatalkan.');
                    }
                    else
                    {
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
                return false;
            });
        });
    </script>    

<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>