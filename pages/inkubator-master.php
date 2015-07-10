<?php
$page_name = 'Master Data Inkubator';
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
                    <h1 class="page-header"><?php echo $page_name; ?></h1>
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
                                    <button type="button"  onclick="javascript:location.reload();" class="btn btn-default btn-xs dropdown-toggle">
                                        <i class="fa fa-refresh"></i> Refresh                                        
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="panel panel-green hide" id="form-add-inkubator">
                                <div class="panel-heading"><strong>Tambah Data Inkubator</strong></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="row">
                                                <div class="col-lg-12 form-group">
                                                    <input id="inama" class="form-control input-sm" placeholder="Nama Inkubator" autofocus="" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 form-group">
                                                    <input id="itipe" class="form-control input-sm" placeholder="Keterangan. Contoh: Pakai roda, dua lampu." />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 form-group">
                                                    <div class="input-group">
                                                        <input value="120" id="ipanjang" class="form-control input-sm" placeholder="Panjang" />
                                                        <span class="input-group-addon add-on"><i class=""></i> cm</span>
                                                    </div>
                                                </div>                                            
                                                <div class="col-lg-3 form-group">
                                                    <div class="input-group">
                                                        <input value="90" id="ilebar" class="form-control input-sm" placeholder="Lebar" />
                                                        <span class="input-group-addon add-on"><i class=""></i> cm</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 form-group">
                                                    <div class="input-group">
                                                        <input value="140" id="itinggi" class="form-control input-sm" placeholder="Tinggi" />
                                                        <span class="input-group-addon add-on"><i class=""></i> cm</span>
                                                    </div>
                                                </div>                                            
                                                <div class="col-lg-3 form-group">
                                                    <div class="input-group">
                                                        <input value="15" id="iberat" class="form-control input-sm" placeholder="Berat" />
                                                        <span class="input-group-addon add-on"><i class=""></i> Kg</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 form-group">
                                                    <div class="input-group">
                                                        <input value="1" id="ijumlah" class="form-control input-sm" placeholder="Jumlah" />
                                                        <span class="input-group-addon add-on"><i class=""></i> Bh</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5 text-center">
                                            <img class="img-circle" src="img/front-end/tambah-data.jpg" width="150" style="margin:0 auto;" />
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="row">
                                        <div class="col-md-11">
                                            <a href="#" id="btn-exec-add" class="btn btn-sm btn-default">Tambah</a>
                                            <a href="#" id="btn-exec-cancel" class="btn btn-sm btn-default">Batalkan</a>    
                                        </div>
                                        <div class="col-md-1">
                                            <img id="img-ajax" style="display: none;" src="img/ajax-loaders/ajax-loader-fan.gif" title="img/ajax-loaders/ajax-loader-fan.gif" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /form-add-inkubator -->
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
                                <a href="inkubator-detail.php?id=<?php echo $inkubator['id'];?>" class="list-group-item" id="inkubator-<?php echo $inkubator['id'];?>" style="height: 60px;">
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
    $('#btn-tambah').click(function(e){
        e.preventDefault();
        $('#form-add-inkubator').removeClass('hide');
         $('#img-ajax').hide();
        return false;
    });
    $('#btn-exec-cancel').click(function(e){
        e.preventDefault();
        $('#form-add-inkubator').addClass('hide');
        return false;
    });
    $('#btn-exec-add').click(function(e){
        e.preventDefault();        
               
        var inama = $('#inama').val();
        var itipe = $('#itipe').val();
        var ipanjang = $('#ipanjang').val();
        var ilebar = $('#ilebar').val();
        var itinggi = $('#itinggi').val();
        var iberat = $('#iberat').val();
        var ijumlah = $('#ijumlah').val();
        
        var er = '';
        if (inama==''){er += 'Nama jangan kosong.<br>'; }
        if (ipanjang==''){er += 'Panjang jangan kosong.<br>'; }
        if (ilebar==''){er += 'Lebar jangan kosong.<br>'; }
        if (itinggi==''){er += 'Tinggi jangan kosong.<br>'; }
        if (iberat==''){er += 'Berat jangan kosong.<br>'; }
        if (ijumlah==''){er += 'Jumlah jangan kosong.<br>'; }
        
        if (er!='')
        {
            msgBox('Error',er);
            return false;
        }
        $('#img-ajax').show();
        return false;
        /*
        $.post('../cores/ajax-add-inkubator.php',
    	{
    		notujuan: no,
    		pesan: pesan,
            // use: 'CMD',
            use: 'SQL',
            r: Math.random()
    	},
    	function(data)
    	{
    		var result = data.substr(0,2);
            location.reload();                    
    	});
        */
    });     
});
</script>   
<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>