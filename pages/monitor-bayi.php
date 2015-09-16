<?php
$page_name = 'Data Perkembangan Bayi';
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
$id_pinjam = get_var('id',0);
if ($id_pinjam==0)
{
    header('location:inkubator-data-peminjam.php');
}

/**
 * Tampil semua ataukah berdasarkan kode pinjam.
 */

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
                            <i class="fa fa-database fa-fw"></i> Kronologi Perkembangan Bayi
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
                                <?php
                                
                                $sql = "select p.*, k.tgl_kembali, k.berat_kembali, k.panjang_kembali, k.kondisi_kembali, k.status_kembali from inkubator_pinjam p
                                            left join inkubator_kembali k on p.kode_pinjam = k.kode_pinjam 
                                            where p.id = '$id_pinjam'
                                            order by p.tgl_pinjam asc, p.id asc";
                                $data_pinjam = fetch_query($sql);
                                if (count($data_pinjam)==0)
                                {
                                ?>
                                <div class="alert alert-warning">
                                    <div>
                                        <label>Data Tidak Tersedia.</label><br />
                                        ID Peminjaman tidak ditemukan.
                                        <br />
                                        <br />
                                        <a href="inkubator-data-peminjam.php" class="btn btn-info"><i class="fa fa-chevron-left fa-fw"></i> Daftar Peminjam</a>
                                    </div>
                                </div>
                                <?php
                                }
                                else
                                {
                                    $pinjam = $data_pinjam[0];
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
                                <table 
                                class="table small table-hover table-striped table-bordered" 
                                id="data-container">
                                <thead>
                                    <tr>                                                    
                                        <th colspan="1">Kode Pinjam:
                                        </th>
                                        <th colspan="5">
                                            <span class="label label-<?php echo $pclass; ?>"><?php echo $pinjam['kode_pinjam']; ?></span>
                                            <span class="pull-right"><a data-kode-pinjam="<?php echo $pinjam['kode_pinjam']; ?>" id="btn-hapus-data-peminjaman" href="" title="Hapus Data Peminjaman Ini" class="btn btn-danger btn-xs"><i class="fa fa-trash-o fa-fw"></i></a></span>
                                        </th>
                                    </tr>
                                    <tr>                                                    
                                        <th colspan="1">Nama Bayi:
                                        </th>
                                        <th colspan="5">
                                            <?php echo $pinjam['nama_bayi']; ?>
                                        </th>
                                    </tr>
                                    <tr>                                                    
                                        <th colspan="1">Nama Ibu/Ayah:
                                        </th>
                                        <th colspan="5">
                                            <?php echo $pinjam['nama_ibu']; ?> &mdash; <?php echo $pinjam['nama_ayah']; ?>
                                        </th>
                                    </tr>
                                    <tr>                                                    
                                        <th colspan="6" class="muted" style="text-align: center;">KRONOLOGI</th>
                                    </tr>
                                    <tr>                                                    
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>                                                    
                                        <th>Berat Bayi</th>
                                        <th>Panjang Bayi</th>
                                        <th>Kondisi</th>
                                        <th>Tindakan</th>
                                    </tr>                                        
                                </thead>
                                <tbody>                                     
                                    <!-- Data Monitoring di sini -->
                                    <?php 
                                    $kd_pinjam = $pinjam['kode_pinjam'];
                                    $sql_mon = "select * from inkubator_monitoring where kode_pinjam  = '$kd_pinjam' 
                                                order by tgl_input asc, id asc";
                                    $data_mon = fetch_query($sql_mon);
                                    foreach ($data_mon as $mon)
                                    { 
                                        $kon = $mon['kondisi']; 
                                        $kon_data = '<span class="label label-'.(strtolower($kon)=='sehat' ? 'success':'warning').'">'.$kon.'</span>';
                                        $st = substr(strtolower($mon['keterangan']),0,12);
                                        $awal_dan_akhir = ($st=='status awal ')||($st=='status akhir');
                                        ?>
                                        <tr id="mon-id-<?php echo $mon['id']; ?>" data-tanggal="<?php echo date('d/m/Y', strtotime($mon['tgl_input'])); ?>">
                                            <td><?php echo date('d/m/Y', strtotime($mon['tgl_input'])); ?></td>
                                            <td><?php echo $mon['keterangan']; ?></td>
                                            <td><?php echo $mon['berat_bayi']; ?></td>
                                            <td><?php echo $mon['panjang_bayi']; ?></td>
                                            <td><?php echo $kon_data; ?></td>
                                            <td>
                                                <div class="btn-group pull-right">
                                                    <a class="btn btn-xs <?php echo ($awal_dan_akhir?' btn-default disabled ':' btn-danger '); ?> btn-hapus">
                                                        <i class="fa fa-trash-o fa-fw"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>                                    
                                    <?php
                                    }
                                    unset($data_mon);
                                    ?>
                                </tbody>
                            </table>
                                <?php
                                }
                                unset($data_pinjam);
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
    
    // Hapus data monitoring.
    $('.btn-hapus').click(function(e){
        e.preventDefault();
        var tr  = $(this).closest('tr');
        var tgl = $(tr).attr('data-tanggal');
        var pid = $(tr).prop('id').substr(7); 
        msgConfirm('Hapus Data','Hapus data monitoring pada tanggal <span class="label label-default">'+tgl+' (ID: '+pid+')</span>?','Hapus','Batal',function(ya){
            if (ya)
            {
                $.post('pinjam-data.php', {
                    ajax: 'delmon',
                    pid: pid,
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
    // Hapus data peminjaman
    $('#btn-hapus-data-peminjaman').click(function(e){
        e.preventDefault();
        var kodePinjam = $(this).attr('data-kode-pinjam');
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