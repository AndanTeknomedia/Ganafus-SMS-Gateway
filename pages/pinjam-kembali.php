<?php
$page_name = 'Pengembalian Inkubator';
include_once('../cores/definition.php'); 
require_once('../cores/db.php'); 
include_once('../cores/session.php');

$ajax = post_var('ajax');

if (!$ajax) {
    if (USE_GAMMU){
        require_once('../gammu/gammu-cores.php');
        if (!is_gammu_ok())
        {
            header('location:setup-gammu.php');
            
        }
    }
}

$p_data_state = urldecode(get_var('status','semua'));

include_once('../gammu/gammu-fetch-sms.php');
error_reporting(E_ALL);

/**
 * If this page is is being loaded using Ajax call,
 * fetch the requested data and skip the rest of the page:
 */
if ($ajax)
{
    switch ($ajax)
    {
        case 'terima':
            $p_kode = post_var('pid',0);
            $p_ket  = 'Pengembalian inkubator dengan KODE PINJAM : '.$p_kode .' telah disetujui. Terimakasih.';            
            if ($p_kode==0)
            {
                echo 'ERParameter tidak valid.';
            }            
            else
            {
                $i_sql = "update inkubator_kembali set status_kembali = 'Diterima',
                    tgl_update_status_kembali = CURRENT_TIMESTAMP(),
                    keterangan_status_kembali = '$p_ket' where kode_pinjam = '$p_kode'";            
                if (exec_query($i_sql))
                {
                    // pre($i_sql);
                    $p_hp   = fetch_one_value("select concat(hp_ibu,'|',hp_ayah) from inkubator_pinjam where upper(kode_pinjam) = upper('$p_kode')");
                    if (!empty($p_hp)){
                        $hp = explode('|', $p_hp);
                        foreach($hp as $no)
                        {
                            // echo $no.'<br>';
                            sms_send($no, $p_ket);
                        }
                    }
                    echo 'OKData pengembalian dengan kode pinjam <strong>'.$p_kode.'</strong> telah disetujui.';
                }
                else
                {
                    echo 'ERPenerimaan peminjaman gagal.';
                }
            }
            break;
        case 'tolak':
            $p_kode = post_var('pid',0);
            $p_ket  = post_var('pket','Peminjaman ditolak karena tidak memenuhi persyaratan');
            $p_hp_ibu = post_var('phpi','');
            $p_hp_ayah = post_var('phpa','');
            if ($p_kode==0)
            {
                echo 'ERParameter tidak valid.';
            }
            else
            {
                $i_sql = "update inkubator_kembali set status_kembali = 'Ditolak', 
                                tgl_update_status_kembali = CURRENT_TIMESTAMP(),
                                keterangan_status_kembali = '$p_ket' where kode_pinjam = '$p_kode'";
                // pre($i_sql);
                // $i_sql = "";
                if (exec_query($i_sql))
                {
                    if (!empty($p_hp_ibu)){
                        sms_send($p_hp_ibu, $p_ket);
                    }
                    echo 'OKData pengembalian dengan kode <strong>'.$p_kode.'</strong> telah ditolak.';
                }
                else
                {
                    echo 'ERPenolakan pengembalian gagal.';
                }
            }
            break;
        case 'delete':
            $p_kode = post_var('pid',0);
            if ($p_kode==0)
            {
                echo 'ERParameter tidak valid.';
            }
            else
            {
                $_mysqli->autocommit(false);
                if (exec_query("delete from inkubator_monitoring where kode_pinjam = '$p_kode' and lower(keterangan) like 'status akhir%'")
                /* 
                &&
                exec_query("delete from inkubator_pinjam where kode_pinjam = '$p_kode'")
                */
                &&
                exec_query("delete from inkubator_kembali where kode_pinjam = '$p_kode'"))
                {
                    $_mysqli->commit();
                    echo 'OKData pengembalian dengan kode <strong>'.$p_kode.'</strong> telah dihapus.';
                }
                else
                {
                    $_mysqli->rollback();
                    echo 'ERPenghapusan data pengembalian gagal.';
                }
                $_mysqli->autocommit(true);
            }
            break;
        case 'list':    // show list
            $p_offset     = post_var('p_offset', 0);
            $p_state      = post_var('pstate', '');  // default status peminjaman.
            $p_limit      = post_var('plimit', 10); // default item count per page.
            $p_sort_order = post_var('psortorder','desc'); // default SMS will be sorted ascending way. Only SMS timestamp supported.
            $p_count_sql  = "select count(k.id)                              
                            from inkubator_kembali k
                            inner join inkubator_pinjam p on k.kode_pinjam = p.kode_pinjam
                            inner join inkubator_master i on i.id = p.id_inkubator                 
                            where (lower(p.status_pinjam) = lower('Disetujui')) " 
                            . ((!empty($p_state))? " and (lower(k.status_kembali) = lower('$p_state') )" :"" ) ;
            $p_count      = fetch_one_value($p_count_sql);
            // pre( $p_count_sql);
            // $p_count = 0;
            if ($p_offset==-1) {
                $p_offset = $p_count-$p_limit;
                if ($p_offset<0) { $p_offset = 0; }
            }
             
            $sql = "select 
                	p.id,
                	i.nama as nama_inkubator,
                	p.kode_pinjam,
                	p.id_inkubator,
                	p.tgl_pinjam,
                	p.nama_bayi,
                	p.kembar,
                	p.tgl_lahir,
                	p.berat_lahir,
                	p.panjang_lahir,
                	p.kondisi,
                	p.rumah_sakit,
                	p.nama_dokter,
                	p.tgl_pulang,
                	p.no_kk,
                	p.alamat,
                	p.nama_ibu,
                	p.hp_ibu,
                	p.email_ibu,
                	p.nama_ayah,
                	p.hp_ayah,
                	p.email_ayah,
                	p.jumlah_pinjam,
                	p.status_pinjam,
                	p.tgl_update_status_pinjam,
                	p.keterangan_status_pinjam,
                	p.konfirmasi,
                	k.tgl_kembali,
                	k.berat_kembali,
                	k.panjang_kembali,
                	k.kondisi_kembali,
                	k.jumlah_kembali,
                	k.status_kembali,
                	k.tgl_update_status_kembali,
                	k.keterangan_status_kembali
                	
                from inkubator_kembali k
                inner join inkubator_pinjam p on k.kode_pinjam = p.kode_pinjam
                inner join inkubator_master i on i.id = p.id_inkubator                 
                where (lower(p.status_pinjam) = lower('Disetujui')) " 
                . ((!empty($p_state))? " and (lower(k.status_kembali) = lower('$p_state') )" :"" ) .
                " order by p.tgl_pinjam $p_sort_order, p.id $p_sort_order limit $p_offset,$p_limit";
            // pre($sql);
            if ($p_count == 0)
            {
            ?>
                <thead>
                    <tr>                                                    
                        <th><span class="fa fa-spinner fa-spin"></span> Menunggu Data</th>                                                    
                    </tr>
                </thead>
                <tbody>
                    <tr class="warning"  style="font-weight: bold;">
                        <td>Belum ada data yang tersedia...</td>                                                    
                    </tr>
                </tbody>    
            <?php
            }
            else
            {
                ?>
                <thead>
                    <tr>             
                        <th>Tanggal Pengembalian</th>                                       
                        <th>Kode Pinjam</th>
                        <th>Bayi</th>
                        <th>Kondisi Bayi</th>
                        <th>Jenis Inkubator</th>
                        <th>Detail</th>                                                       
                    </tr>
                </thead>
                <tbody>
                <?php
                
                $p_data = fetch_query($sql);
                $i=0;
                 // pre($p_data);
                foreach($p_data as $data)
                {   
                    $i++;
                    // if (($i % 2) == 0) {$rclas='info';}else{$rclas='';}
                    
                    $rclas = ($data['status_kembali']=='Ditunda')
                                ?'warning'
                                : (($data['status_kembali']=='Ditolak') 
                                    ? 'danger'
                                    :'success'
                                );
                ?>      
                    <tr class="text-<?php echo $rclas; ?>" style="font-weight: bold;">
                        <td><?php echo date('d/m/Y', strtotime($data['tgl_kembali']));?></td>
                        <td>
                            <a class="label label-<?php echo $rclas; ?>" onclick="javascript:msgBox('Kode Pinjam','<strong><?php echo $data['kode_pinjam'];?></strong>');">
                                <strong><?php echo $data['kode_pinjam'];?></strong>
                            </a>
                        </td>
                        <td><?php echo $data['nama_bayi'] /* .', ' .date('d/m/Y', strtotime($data['tgl_lahir'])) */ ;?></td>
                        <td><?php echo $data['berat_kembali'].'kg/'.$data['panjang_kembali'].'cm/'.ucfirst(strtolower($data['kondisi_kembali']));?></td>
                        <td><?php echo $data['nama_inkubator'];?></td>                    
                        <td>
                            <div class="btn-group">
                            <a href="#" onclick="javascript:window.terimaKembali(<?php echo $data['id'];?>,'<?php echo $data['kode_pinjam'];?>'); return false;" class="pinjam-detail btn btn-xs btn-success" data-id="<?php echo $data['id'];?>"><i class="fa fa-thumbs-o-up"></i></a>
                            <a href="#" onclick="javascript:window.tolakKembali(<?php echo $data['id'];?>,'<?php echo $data['kode_pinjam'];?>'); return false;" class="pinjam-detail btn btn-xs btn-warning" data-id="<?php echo $data['id'];?>"><i class="fa fa-thumbs-o-down"></i></a>
                            <a href="#" onclick="javascript:window.deleteKembali(<?php echo $data['id'];?>,'<?php echo $data['kode_pinjam'];?>'); return false;" class="pinjam-delete btn btn-xs btn-danger" data-id="<?php echo $data['id'];?>"><i class="fa fa-scissors"></i></a>
                            </div>
                        </td>
                    </tr>
                     
                <?php
                }
                echo '</tbody> ';
                unset($p_data);
            }
            // state the data count for last page:
            echo '<input type="hidden" value="'.$p_count.'" id="ajax-data-count" />';
            // ----------------- END OF CASE 'list';
            
            break;
        default:
            die();    
    }
    
    // Skip the rest of the page:
    exit(); 
}

require_login();

$skip_morris = true;
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
                        <small><?php 
                        $subt = ucfirst(strtolower($p_data_state));
                        echo 'Tampilkan '.($subt=='Semua'?'Semuanya':'Pengembalian '.$subt); 
                        ?>
                        </small>
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-database fa-fw"></i> Daftar Data Pengembalian Inkubator
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button"  onclick="javascript:location.reload();" class="btn btn-default btn-xs">
                                        <i class="fa fa-refresh"></i> Refresh                                        
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">   
                                    <label>Legenda Kode Pinjam:</label>                                    
                                    <span class="label label-danger">Ditolak</span>
                                    <span class="label label-warning">Ditunda</span>
                                    <span class="label label-success">Diterima</span>
                                    <!--
                                    <span class="label label-default">Dikembalikan</span>
                                    -->
                                </div>
                                <div class="col-lg-6">
                                    <?php
                                    $states = fetch_one_value(" SELECT  replace(replace(replace(replace(COLUMN_TYPE,'''',''),'enum(',''),')',''),', ','')
                                        from   information_schema.`COLUMNS` where table_schema = '".DB_DATABASE."' and table_name = 'inkubator_kembali'
                                        and COLUMN_name = 'status_kembali' ;");       
                                    $states = explode(',', $states);                         
                                    ?>                                      
                                    <div class="col-lg-12">
                                        <div class="btn-group pull-right">
                                        <?php             
                                            echo '<a href="#" class="btn disabled btn-default btn-sm"><strong>Status Pengembalian:</strong></a>';
                                            echo '<a href="'.$_SERVER['PHP_SELF'].'" class="btn btn-default btn-sm">Semua</a>';
                                            foreach($states as $state)
                                            {
                                                echo '<a href="'.$_SERVER['PHP_SELF'].'?status='.urlencode($state).'" class="btn btn-default btn-sm">'.ucfirst($state).'</a>';    
                                            }                                        
                                        ?>                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <hr />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table 
                                            class="table small table-hover table-striped table-bordered" 
                                            id="data-container">
                                            <thead>
                                                <tr>                                                    
                                                    <th><span class="fa fa-spinner fa-spin"></span> Menunggu Data</th>                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="warning">
                                                    <td>Belum ada data yang tersedia...</td>                                                    
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <span class="pull-left">
                                <button id="btn-nav-first"   class="btn btn-circle btn-default btn-xs data-nav"><i class="fa fa-step-backward"></i></button>
                                <button id="btn-nav-prior"   class="btn btn-circle btn-default btn-xs data-nav"><i class="fa fa-chevron-left"></i></button>
                                <button id="btn-nav-refresh" class="btn btn-circle btn-default btn-xs data-nav"><i class="fa fa-repeat"></i></button>
                                <button id="btn-nav-next"    class="btn btn-circle btn-default btn-xs data-nav"><i class="fa fa-chevron-right"></i></button>
                                <button id="btn-nav-last"    class="btn btn-circle btn-default btn-xs data-nav"><i class="fa fa-step-forward"></i></button>
                                <span>&nbsp;&nbsp;</span>
                                <button id="btn-nav-sort"    class="btn btn-circle btn-default btn-xs data-nav"><i class="fa fa-sort-amount-asc"></i></button>
                                <span>&nbsp;&nbsp;</span>
                                <div class="pull-right">
                                    <select id="data-count" class="form-control input-sm pull-left" style="width: 75px;">
                                        <option value="5">5</option>
                                        <option value="10" selected="">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    &nbsp;&nbsp;                
                                </div>               
                            </span>
                            <div class="clearfix"></div>
                        </div>
                        <!-- /.panel-footer -->
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
    <?php     
    echo "var dataState = '".(empty($p_data_state) || ($p_data_state=='semua')?'':$p_data_state)."';";
    ?>
    var p_offset      = 0;    
    var p_limit       = parseInt(getCookie('p_limit') || "10",10);
    var p_state       = dataState;
    var p_sortorder   = getCookie('p_sortorder') || 'desc';
    // alert (p_offset+"\n"+p_limit+"\n"+p_state+"\n"+p_sortorder);
    $('#btn-nav-sort i')
        .removeClass('fa-sort-amount-asc')
        .removeClass('fa-sort-amount-desc')
        .addClass('fa-sort-amount-'+p_sortorder);
    $('#data-count')
        .val(p_limit.toString())
        .change(function(){
            p_limit = $(this).val();
            setCookie('p_limit',p_limit,365);
            reloadData(p_offset, p_limit, p_state, p_sortorder); 
        });
    var url             = '<?php $_SERVER['PHP_SELF']; ?>';
    var timer;
    var reloadData = function(offset, limit, state, sortorder)
    {
        // return;
        $('#data-container').load(url, {
            poffset : offset,
            plimit : limit, 
            pstate : state,
            psortorder : sortorder,
            ajax : 'list',
            r: Math.random()    
        });          
        clearTimeout(timer);
        if (window.timerDisabled==true){ return; }
        timer = setTimeout(function(){
            reloadData(p_offset, p_limit, p_state, p_sortorder);
        }, 20000); // refresh data after 20 seconds       
    };
    
    reloadData(p_offset, p_limit, p_state, p_sortorder);
    
    $('.data-nav').click(function(e){        
        e.preventDefault();
        var dc = $('#ajax-data-count').val();
        var id = $(this).prop('id').substr(8).toLowerCase();        
        switch (id)
        {
            
            case 'first':
                p_offset = 0;                
                break;                
            case 'prior':
                p_offset -= p_limit;
                if (p_offset<0) {p_offset=0;}
                break;  
            case 'next': 
                p_offset += p_limit;
                if (p_offset>=dc) {p_offset=dc-p_limit;}
                break; 
            case 'last': 
                p_offset = dc-p_limit;
                break;
            case 'sort': 
                p_sortorder = (p_sortorder=='asc'?'desc':'asc');
                $('#'+$(this).prop('id')+' i')
                    .removeClass('fa-sort-amount-asc')
                    .removeClass('fa-sort-amount-desc')
                    .addClass('fa-sort-amount-'+p_sortorder);                
                break;
            case 'refresh':                
                break;
        }  
        
        $('#btn-nav-first').prop('disabled', p_offset == 0);
        $('#btn-nav-prior').prop('disabled', p_offset == 0);
        $('#btn-nav-next').prop('disabled', p_offset >= (dc-p_limit));
        $('#btn-nav-last').prop('disabled', p_offset >= (dc-p_limit));
        
        setCookie('p_offset',p_offset, 360);
        setCookie('p_limit',p_limit, 360);
        // setCookie('p_state',p_state, 360);
        setCookie('p_sortorder',p_sortorder, 360);
        reloadData(p_offset, p_limit, p_state, p_sortorder);     
        return false;
    });
    
    window.tolakKembali = function(idPinjam, kodePinjam)
    {
        window.timerDisabled = true;   
        msgInput('Keterangan','Masukkan keterangan penolakan',
        'Pengembalian ditolak karena tidak memenuhi persyaratan.', 'OK','Batal', function(e, v){
            if (e)
            {
                $.post(url, {
                    ajax : 'tolak',
                    pid  : kodePinjam,
                    pket : v,                                    
                    r: Math.random()    
                }, function(data){
                    msgBox('Penolakan Data Peminjaman', data.substr(2));
                    window.timerDisabled = false;
                    reloadData(p_offset, p_limit, p_state, p_sortorder); 
                });    
            }            
        });   
    };
    
    window.terimaKembali = function(idPinjam, kodePinjam)
    {
        window.timerDisabled = true;
        $.post(url, {
            ajax : 'terima',
            pid  : kodePinjam,
            r: Math.random()    
        }, function(data){
            msgBox('Pemrosesan Data Pengembalian', data.substr(2));
            window.timerDisabled = false;
            reloadData(p_offset, p_limit, p_state, p_sortorder); 
        }); 
    };
    
    window.deleteKembali = function(idPinjam, kodePinjam)
    {
        window.timerDisabled = true;
        msgConfirm('Hapus Data Peminjaman',
            'Lanjutkan menghapus data pengembalian dengan kode <strong>'+kodePinjam+'</strong>?',
            'Hapus',
            'Batal',
            function(ya){
                if (ya)
                {
                    $.post(url, {
                        ajax : 'delete',
                        pid  : kodePinjam,   
                        r: Math.random()    
                    }, function(data){
                        msgBox('Hapus Data Pengembalian', data.substr(2));
                        window.timerDisabled = false;
                        reloadData(p_offset, p_limit, p_state, p_sortorder); 
                    }); 
                }    
            }
        );
    };
    
});
</script>
<!--   
<script src="../bower_components/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
-->
<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>