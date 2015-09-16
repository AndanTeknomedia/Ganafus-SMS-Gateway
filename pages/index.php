<?php
$page_name = 'Dashboard';
include_once('../cores/definition.php'); 
if (USE_GAMMU){
    require_once('../gammu/gammu-cores.php');
    if (!is_gammu_ok())
    {
        header('location:setup-gammu.php');
        
    }
}

include_once('../cores/session.php');
// require_login('post.php?show/newest');
require_login();

$skip_morris = true;

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
                    <h1 class="page-header"><?php echo $page_name; ?>
                   </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <!-- Fetch Data count: -->
            <?php
            $qry = fetch_query("select 
        	(select sum(stok_inkubator) from vw_inkubator_tersedia) as inkubator_count,        	
            (select count(*) from vw_inkubator_pinjam) as bayi_count,
        	(select count(*) from vw_inkubator_pinjam where coalesce(status_kembali,'Ditunda') = 'Ditunda' and status_pinjam = 'Disetujui') as pinjam_count,        	
        	(select count(*) from vw_inkubator_perkembangan where perkembangan = 'Positif') as bayi_sehat_count", false);
            
            
            $badge_data = $qry[0];
            ?>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-home fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $badge_data['inkubator_count']; ?></div>
                                    <div>Inkubator Tersedia</div>
                                </div>
                            </div>
                        </div>
                        <a href="inkubator-master.php">
                            <div class="panel-footer">
                                <span class="pull-left">Lihat Detail</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $badge_data['bayi_count']; ?></div>
                                    <div>Dipinjam</div>
                                </div>
                            </div>
                        </div>
                        <a href="pinjam-data.php">
                            <div class="panel-footer">
                                <span class="pull-left">Lihat Detail</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-list fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $badge_data['pinjam_count']; ?></div>
                                    <div>Sedang Inkubasi</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Lihat Detail</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text-o fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $badge_data['bayi_sehat_count']; ?></div>
                                    <div>Berkembang Sehat</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Lihat Detail</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            unset($badge_data);
            unset($qry);
            ?>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle fa-fw"></i> Tentang Inkubator Bayi Gratis
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <i class="fa fa-book fa-fw"></i> Panduan Peminjaman                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <i class="fa fa-lightbulb-o fa-fw"></i> Syarat &amp; Ketentuan
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-4">
                    <!-- Inbox Messages -->
                    <?php /*
                    <div class="chat-panel panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments fa-fw"></i> Testimoni                          
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <ul class="chat">
                                <li class="left clearfix">
                                    <span class="chat-img pull-left">
                                        <img src="img/user-avatar/01.jpg" alt="User Avatar" class="img-circle" />
                                    </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <strong class="primary-font">Jack Sparrow</strong>
                                            <small class="pull-right text-muted">
                                                <i class="fa fa-clock-o fa-fw"></i> 12 mins ago
                                            </small>
                                        </div>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                                        </p>
                                    </div>
                                </li>
                                <li class="right clearfix">
                                    <span class="chat-img pull-right">
                                        <img src="img/user-avatar/02.jpg" alt="User Avatar" class="img-circle" />
                                    </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <small class=" text-muted">
                                                <i class="fa fa-clock-o fa-fw"></i> 13 mins ago</small>
                                            <strong class="pull-right primary-font">Bhaumik Patel</strong>
                                        </div>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                                        </p>
                                    </div>
                                </li>
                                <li class="left clearfix">
                                    <span class="chat-img pull-left">
                                        <img src="img/user-avatar/03.jpg" alt="User Avatar" class="img-circle" />
                                    </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <strong class="primary-font">Jack Sparrow</strong>
                                            <small class="pull-right text-muted">
                                                <i class="fa fa-clock-o fa-fw"></i> 14 mins ago</small>
                                        </div>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                                        </p>
                                    </div>
                                </li>
                                <li class="right clearfix">
                                    <span class="chat-img pull-right">
                                        <img src="img/user-avatar/04.jpg" alt="User Avatar" class="img-circle" />
                                    </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <small class=" text-muted">
                                                <i class="fa fa-clock-o fa-fw"></i> 15 mins ago</small>
                                            <strong class="pull-right primary-font">Bhaumik Patel</strong>
                                        </div>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <span>&nbsp;</span>                                    
                        </div>
                        <!-- /.panel-footer -->
                    </div>
                    <!-- /.panel .chat-panel -->
                    */ ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Pooling SMS
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <ul class="chat" id="last-sms-container">
                            <!-- Will be filled by Ajax call. -->
                            <!-- Temporary display: -->                            
                                <li class="left clearfix">
                                    <span class="chat-img pull-left">
                                        <img id="img-ajax" src="img/ajax-loaders/ajax-loader-fan.gif" title="img/ajax-loaders/ajax-loader-fan.gif" />
                                    </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <strong class="primary-font">Mempersiapkan data</strong>
                                        </div>
                                        <p>
                                            Data sedang diproses oleh server...
                                        </p>
                                    </div>
                                </li>
                            </ul>                            
                            <!-- /.chat -->
                            <a href="sms-inbox.php" class="btn btn-default btn-block">Lihat Semua</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->                    
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<script>
$(document).ready(function(){
    /**
     * Fetch last N sms(es).
     */
    var lastSmsTimer;
    var reloadData = function(sort_type, fetch_count)
    {
        $('#last-sms-container').load('../gammu/ajax-last-sms.php', {
            sort_type : sort_type,
            fetch_count : fetch_count, 
            ajax : 'ajax',
            r: Math.random()    
        });  
        clearTimeout(lastSmsTimer);
        lastSmsTimer = setTimeout(function(){
            reloadData(sort_type, fetch_count);
        }, 20000); // refresh data after 20 seconds       
    };
    
    reloadData('desc', 5);
    /*
    $('a.tulis-pesan').click(function(e){
        e.preventDefault();
        var dlgSendMsg = BootstrapDialog.show({
            size    : BootstrapDialog.SIZE_LARGE,
            title   : 'Kirim Pesan',
            closable: true,
            draggable: true,
            message : $('<p id="p-form-send"></p>').load('../cores/ajax-form-send-message.php', {tipe: 'user', touser:'0'} )
        });      
        window.dlgSendMsg = dlgSendMsg;
        dlgSendMsg.open();
        return false; 
    });
    */
});
</script>   
<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>