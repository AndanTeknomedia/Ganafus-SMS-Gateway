<?php
$page_name = 'Master Data Inkubator';
include_once('../cores/definition.php'); 
require_once('../cores/db.php'); 
if (USE_GAMMU){
    require_once('../gammu/gammu-cores.php');
    if (!is_gammu_ok())
    {
        header('location:setup-gammu.php');
        
    }
}

include_once('../cores/session.php');
// require_login('post.php?show/newest');

$ajax = post_var('ajax','');
if ($ajax == 'add-inkubator')
{
    $nama       = post_var('nama','');
    $tipe       = post_var('tipe','');
    $panjang    = post_var('panjang',0);
    $lebar      = post_var('lebar',0);
    $tinggi     = post_var('tinggi',0);
    $berat      = post_var('berat',0.00);
    $jumlah     = post_var('jumlah',0);
    $path       = post_var('path','img/front-end/tentang-inkubator-gratis.jpg');
    $er = '';
    if ($nama=='')
    {
        $er .= 'Nama Inkubator tidak boleh kosong.<br>';
    }
    if ($panjang==0)
    {
        $er .= 'Panjang Inkubator tidak boleh kosong.<br>';
    }
    if ($lebar==0)
    {
        $er .= 'Lebar Inkubator tidak boleh kosong.<br>';
    }
    if ($tinggi==0)
    {
        $er .= 'Tinggi Inkubator tidak boleh kosong.<br>';
    }
    if ($berat==0)
    {
        $er .= 'Berat Inkubator tidak boleh kosong.<br>';
    }
    /*
    if ($jumlah==0)
    {
        $er .= 'Panjang Inkubator tidak boleh kosong.<br>';
    }
    */
    if ($er!='')
    {
        die('ER'.$er);
    }
    $sql =  "insert into inkubator_master ( id, nama, jumlah, panjang, lebar, tinggi, berat, tipe, img_path) 
            values ( 
                UUID_SHORT(),
                '$nama',
                '$jumlah',
                '$panjang',
                '$lebar',
                '$tinggi',
                '$berat',
                '$tipe',
                '$path'
            )"; 
    // echo $sql;
    if (exec_query($sql))
    {
        echo 'OKData Inkubator telah disimpan.';
    }
    else
    {
        echo 'ERData Inkubator gagal disimpan.';
    }
    die();       
}
else
if ($ajax == 'del-inkubator')
{
    $id  = post_var('id',0);
    if ($id ==0)
    {
        echo 'ERID inkubator tidak valid.';
    }
    else
    {
        if (exec_query("delete from inkubator_master where id = '$id'"))
        {
            echo 'OKInkubator terhapus.';
        }   
        else
        {
            echo 'ERInkubator gagal hapus.';
        }  
    }
    die();
}
else
if ($ajax == 'sto-inkubator')
{
    $id  = post_var('id',0);
    $jumlah  = post_var('jumlah',0);
    if ($id ==0)
    {
        echo 'ERID inkubator tidak valid.';
    }
    else
    if ($jumlah ==0)
    {
        echo 'ERJumlah tidak valid.';
    }
    else
    {
        // die("update inkubator_master set jumlah = '$jumlah' where id = '$id'");
        if (exec_query("update inkubator_master set jumlah = '$jumlah' where id = '$id'"))
        {
            echo 'OKInkubator telah diupdate.';
        }   
        else
        {
            echo 'ERGagal mengupdate stok inkubator.';
        }  
    }
    die();
}

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
                            <div class="panel panel-green">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col col-md-12">
                                            Klik salah satu inkubator untuk melihat data peminjam.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group">
                            <?php
                            $inkubators = fetch_query(
                                "select it.*,
                                i.img_path,
                                count(p.id) jumlah_dipinjam,
                                (select count(*) from inkubator_pinjam where id_inkubator = i.id) as jumlah_pernah_pinjam
                                from vw_inkubator_tersedia it 
                                inner join inkubator_master i on i.id = it.id
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
                                <div  class="list-group-item" id="inkubator-<?php echo $inkubator['id'];?>" style="height: 120px;">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <a href="inkubator-data-peminjam.php?id=<?php echo $inkubator['id'];?>">
                                                <img src="<?php echo $inkubator['img_path'];?>" class="img-thumbnail" width="100" />
                                            </a>
                                        </div>
                                        <div class="col-lg-6">                                            
                                            <a href="inkubator-data-peminjam.php?id=<?php echo $inkubator['id'];?>">
                                                <strong><?php echo $inkubator['nama'];?></strong>
                                            </a>
                                            <button class="btn btn-danger btn-xs btn-link delete-inkubator" id="<?php echo $inkubator['id'];?>" data-jumlah="<?php echo $inkubator['jumlah_pernah_pinjam'];?>">
                                                <i class="fa fa-trash-o fa-fw"></i>
                                            </button>
                                            <button class="btn btn-danger btn-xs btn-link edit-stok-inkubator" id="<?php echo $inkubator['id'];?>" data-jumlah="<?php echo $inkubator['stok_inkubator'];?>">
                                                <i class="fa fa-pencil fa-fw"></i>
                                            </button>
                                            <?php if (!empty($inkubator['tipe'])) 
                                            {
                                                echo '<br /><span class="text-info small">'.$inkubator['tipe'].'</span>';                                        
                                            }
                                            ?>
                                        </div>
                                        <div class="col-lg-4">
                                            <span class="pull-right text-muted small">
                                                <em><?php echo $spec;?></em></span><br />
                                            <span class="pull-right">
                                                <span class="label label-warning"><?php echo $inkubator['stok_inkubator'];?> buah</span> 
                                                <span class="label label-success"><?php echo $inkubator['jumlah_dipinjam'];?> dipinjam</span> 
                                                <span class="label label-info"><?php echo ($inkubator['stok_inkubator'] - $inkubator['jumlah_dipinjam']);?> tersedia</span>
                                            </span>
                                        </div>
                                    </div> 
                                </div>                            
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
    var selfURL = '<?php echo $_SERVER['PHP_SELF']; ?>';
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
    
    $('.delete-inkubator').click(function(e){
        e.preventDefault();
        var id = $(this).prop('id');
        var jumlah = parseInt($(this).attr('data-jumlah'));
        if (jumlah!=0) 
        {
            msgBox('Inkubator ini telah digunakan. Tidak dapat dihapus.');
        }
        else
        {
            $.post(selfURL,
        	{
        		ajax: 'del-inkubator',
                id: id,
                r: Math.random()
        	},
        	function(data)
        	{
        		
                var result = data.substr(0,2).toUpperCase();
                if (result == 'OK')
                {
                    location.reload();
                }
                else
                {
                    msgBox('Error',data.substr(2));
                }
        	});
         }
        return false;
        
    });
    
    $('.edit-stok-inkubator').click(function(e){
        e.preventDefault();
        var id = $(this).prop('id');
        var jumlah = parseInt($(this).attr('data-jumlah'));
        jumlah = prompt('Masukkan jumlah inkubator:', jumlah, 'Edit Stok');        
        if (jumlah<=0) 
        {
            msgBox('Error','Jumlah tidak boleh 0');
        }
        else
        {
            $.post(selfURL,
        	{
        		ajax: 'sto-inkubator',
                jumlah: jumlah,
                id: id,
                r: Math.random()
        	},
        	function(data)
        	{
        		
                var result = data.substr(0,2).toUpperCase();
                if (result == 'OK')
                {
                    location.reload();
                }
                else
                {
                    msgBox('Error',data.substr(2));
                }
        	});
         }
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
        var ipath   = $('#ipath').val();
        
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
        
        
        
        $.post(selfURL,
    	{
    		ajax: 'add-inkubator',
            nama: inama,
            tipe: itipe,
            panjang: ipanjang,
            lebar: ilebar,
            tinggi: itinggi,
            berat: iberat,
            jumlah: ijumlah,
            path: ipath,
            r: Math.random()
    	},
    	function(data)
    	{
    		
            var result = data.substr(0,2).toUpperCase();
            if (result == 'OK')
            {
                // msgBox('Sukses','Inkubator telah ditambahkan.');
                location.reload();
            }
            else
            {
                msgBox('Error',data.substr(2));
                $('#inama').focus();
            }
    	});

        return false;
    });     
});
</script>   
<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>