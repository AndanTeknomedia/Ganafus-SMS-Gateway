<?php
$page_name = 'Detail Inkubator';
include_once('../cores/definition.php'); 
if (USE_GAMMU){
    require_once('../gammu/gammu-cores.php');
    if (!is_gammu_ok())
    {
        header('location:setup-gammu.php');
        
    }
}
require_once('../cores/db.php'); 
include_once('../cores/session.php');
// require_login('post.php?show/newest');
require_login();

$skip_morris = true;

$ink_id = get_var('id');
$c = fetch_query("select it.*,
                count(p.id) jumlah_dipinjam
                from vw_inkubator_tersedia it 
                left join vw_inkubator_pinjam p on p.id_inkubator = it.id  and coalesce(p.status_kembali,'Ditunda') = 'Ditunda'
                where it.id = '$ink_id'
                group by (it.id)
                order by it.id asc");
$count = count($c);
if (empty($ink_id) || empty($count) || ($count==0))
{
    force_404('../404.php');
}

$inkubator = $c[0];
unset($c);
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
                        <small><?php echo $inkubator['nama'];?></small>
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-database fa-fw"></i> Data Stok Inkubator
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" id="btn-tambah" class="btn btn-default btn-xs first">
                                        <i class="fa fa-plus"></i> Tambah                                        
                                    </button>
                                    <button type="button" id="btn-pinjam" class="btn btn-default btn-xs">
                                        <i class="fa fa-shopping-cart"></i> Pinjam                                        
                                    </button>
                                    <button type="button"  onclick="javascript:location.reload();" class="btn btn-default btn-xs dropdown-toggle">
                                        <i class="fa fa-refresh"></i> Refresh                                        
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                            <?php
                            $inkubators = fetch_query(
                                "select it.*,
                                count(p.id) jumlah_dipinjam
                                from vw_inkubator_tersedia it 
                                left join vw_inkubator_pinjam p on p.id_inkubator = it.id  and coalesce(p.status_kembali,'Ditunda') = 'Ditunda'
                                group by (it.id)
                                order by it.id asc");
                            foreach($inkubators as $inkubator)
                            {           
                                $spec = $inkubator['panjang'].'cm x '.
                                        $inkubator['lebar'].'cm x '.
                                        $inkubator['tinggi'].'cm x '.
                                        $inkubator['berat'].'kg.';
                            ?>                                
                                <a href="inkubator-detail.php?<?php echo $inkubator['id'];?>" class="list-group-item" id="inkubator-<?php echo $inkubator['id'];?>" style="height: 60px;">
                                    <i class="fa fa-tasks fa-fw"></i> <strong><?php echo $inkubator['nama'];?></strong>
                                    <?php if (!empty($inkubator['tipe'])) {
                                        echo '<span class="text-info small">'.$inkubator['tipe'].'</span>';                                        
                                    }
                                    ?>
                                    <span class="pull-right text-muted small">
                                        <em><?php echo $spec;?></em></span><br />
                                    <span class="pull-right">
                                        <span class="label label-warning"><?php echo $inkubator['stok_inkubator'];?> buah</span> 
                                        <span class="label label-success"><?php echo $inkubator['jumlah_dipinjam'];?> dipinjam</span> 
                                        <span class="label label-info"><?php echo ($inkubator['stok_inkubator'] - $inkubator['jumlah_dipinjam']);?> tersedia</span>
                                    </span>
                                    
                                </a>                            
                            <?php
                            }
                            unset($inkubators);
                            ?>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<script>
$(document).ready(function(){
         
});
</script>   
<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>