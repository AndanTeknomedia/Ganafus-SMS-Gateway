<?php
$page_name = 'Data Penggunan Inkubator';
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
$id_inkubator = get_var('id',0);

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
                    <h1 class="page-header"><?php echo $page_name; ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-database fa-fw"></i> Peminjaman Inkubator
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button"  onclick="javascript:location.reload();" class="btn btn-default btn-xs dropdown-toggle">
                                        <i class="fa fa-refresh"></i> Refresh                                        
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table 
                                    class="table small table-hover table-striped table-bordered" 
                                    id="data-container">
                                    <thead>
                                        <tr>                                                    
                                            <th colspan="3">Legenda Kode Pinjam:
                                                <span class="label label-warning">Ditunda</span>
                                                <span class="label label-danger">Ditolak</span>
                                                <span class="label label-success">Disetujui</span>
                                            </th>
                                            <th colspan="3">Legenda Tanggal Kembali:
                                                <span class="label label-warning">Ditunda</span>
                                                <span class="label label-danger">Ditolak</span>
                                                <span class="label label-success">Diterima</span>
                                            </th>
                                        </tr>
                                        <tr>                                                    
                                            <th>Kode</th>
                                            <th>Nama Peminjam</th>                                                    
                                            <th>Nama Bayi</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Tindakan</th>
                                        </tr>                                        
                                    </thead>
                                    <tbody>
                                        <?php
                                        $pinjams = fetch_query
                                        (
                                            "select p.*, k.tgl_kembali, k.status_kembali from inkubator_pinjam p
                                            left join inkubator_kembali k on p.kode_pinjam = k.kode_pinjam ".
                                            (($id_inkubator==0 ? "" : " where p.id_inkubator = '".$id_inkubator."'")).                                            
                                            " order by p.tgl_pinjam asc, p.id asc"
                                        );
                                        $c = count($pinjams);
                                        if ($c==0)
                                        {
                                        ?>
                                            <tr>                                                    
                                                <td colspan="6" class="warning">Belum ada data peminjaman.</th>
                                            </tr> 
                                        <?php    
                                        }
                                        else
                                        {
                                            foreach($pinjams as $pinjam)
                                            { 
                                                $pclass = ($pinjam['status_pinjam']=='Ditunda')
                                                            ?'warning'
                                                            : (($pinjam['status_pinjam']=='Ditolak') 
                                                                ? 'danger'
                                                                :'success'
                                                            );
                                                $kclass = ($pinjam['status_kembali']=='Ditunda')
                                                            ?'warning'
                                                            : (($pinjam['status_kembali']=='Ditolak') 
                                                                ? 'danger'
                                                                :'success'
                                                            );
                                            ?>          
                                            <tr id="kp-<?php echo $pinjam['kode_pinjam']; ?>">
                                                <td><span class="label label-<?php echo $pclass ?>"><?php echo $pinjam['kode_pinjam']; ?></span></td>
                                                <td>
                                                    <i class="fa fa-female fa-fw"></i> <?php echo $pinjam['nama_ibu'];?>
                                                    <br>
                                                    <i class="fa fa-male fa-fw"></i> <?php echo $pinjam['nama_ayah']; ?>
                                                </td>
                                                <td><i class="fa fa-child fa-fw"></i> <?php echo $pinjam['nama_bayi']; ?></td>
                                                <td><?php echo $pinjam['tgl_pinjam']; ?></td>
                                                <td><span class="label label-<?php echo $kclass ?>"><?php echo $pinjam['tgl_kembali']; ?></span></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="monitor-bayi.php?id=<?php echo $pinjam['id']; ?>" class="btn btn-xs btn-default btn-detail"><i class="fa fa-bar-chart-o fa-fw"></i></a>
                                                        <a class="btn btn-xs btn-default btn-hapus"><i class="fa fa-trash-o fa-fw"></i></a>
                                                    </div>
                                                </td>
                                            </tr>                                                                   
                                            <?php
                                            }
                                        }
                                        unset($pinjams);
                                        ?>
                                    </tbody>
                                </table>
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
    // Perkembangan bayi.
    /*
    $('.btn-detail').click(function(e){
        e.preventDefault();
        var tr = $(this).closest('tr');
        var kodePinjam = $(tr).prop('id').substr(3);
        alert(kodePinjam);
        return false;
    });
    */
    // Hapus.
    $('.btn-hapus').click(function(e){
        e.preventDefault();
        var tr = $(this).closest('tr');
        var kodePinjam = $(tr).prop('id').substr(3);
        msgConfirm('Hapus Data','Anda yakin menghapus data peminjaman dengan kode <span class="label label-default">'+kodePinjam+'</span>?','Hapus','Batal',function(ya){
            if (ya)
            {
                $.post('pinjam-data.php', {
                    ajax: 'delete',
                    pid: kodePinjam,
                    r: Math.random()
                },
                function(data){
                    if (data.substr(0,2)=='OK')
                    {
                        msgBox('Sukses','Data telah dihapus.');    
                        location.reload();                    
                    }
                    else
                    {
                        msgBox('Error','Gagal menghapus data.');
                    }
                });
            }
        });
        return false;
    });
});
</script>   
<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>