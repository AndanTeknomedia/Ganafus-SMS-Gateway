<?php
$page_name = 'SMS Manager';
include_once('../cores/definition.php'); 
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
require_once('../cores/db.php'); 
include_once('../cores/session.php');
// require_login('post.php?show/newest');

/**
 * If this page is is being loaded using Ajax call,
 * fetch the requested data and skip the rest of the page:
 */

if ($ajax)
{
    
    $sms_offset     = post_var('sms_offset', 0);  // default offset of first item to fetch.
    $sms_limit      = post_var('sms_limit', 10); // default item count per page.
    $sms_sort_order = post_var('sms_sortorder','asc'); // default SMS will be sorted ascending way. Only SMS timestamp supported.
    $sms_count      = fetch_one_value("select count(id) from outbox");
    
    if ($sms_offset==-1) {
        $sms_offset = $sms_count-$sms_limit;
        if ($sms_offset<0) { $sms_offset = 0; }
    }
    
    // $leftover_sql   = "select max(id) from sms_valid sv order by sv.waktu_terima $sms_sort_order, sv.id $sms_sort_order limit $sms_offset,$sms_limit";
    
    $sql = "select ss.ID id, ss.UDH udah, ss.SendingDateTime send_time, ss.DestinationNumber no_tujuan, ss.TextDecoded sms
            from outbox ss order by ss.SendingDateTime $sms_sort_order, ss.id $sms_sort_order limit $sms_offset,$sms_limit";
    // echo $sql;
    if ($sms_count==0)
    {
    ?>
        <li class="left clearfix">
            <span class="chat-img pull-left">
                <img  class="img-circle" src="img/front-end/sms-data-outbox.jpg" width="50" height="50" />                
            </span>
            <div class="chat-body clearfix">
                <div class="header">
                    <i class="fa fa-lightbulb-o fa-2x"></i> <strong class="primary-font">Sementara tidak ada data untuk ditampilkan.</strong>                    
                </div>
                <p class="text-muted small">                     
                    <span class="label label-info"><i class="fa fa-refresh fa-spin"></i> Menunggu data...</span>                    
                </p>
            </div>
        </li>    
    <?php
    }    
    else
    {
        $smses = fetch_query($sql);
    
        $i=0;
        foreach($smses as $sms)
        {   
            $i++;
        ?>                              
            
            <li class="left clearfix" id="sms-<?php echo $sms['id']; ?>">
                <span class="chat-img pull-left">
                    <img  class="img-circle" src="img/front-end/sms-data-outbox.jpg" width="50" height="50" />                
                </span>
                <div class="chat-body clearfix">
                    <div class="header">
                        <i class="fa fa-phone-square"></i> <strong class="primary-font" id="pengirim-<?php echo $sms['id']; ?>"><?php echo $sms['no_tujuan'];?></strong>
                        <small class="pull-right text-muted small">
                            <i class="fa fa-clock-o fa-fw"></i> <?php echo $sms['send_time'];?>
                        </small>
                    </div>
                    <p>
                        <i class="fa fa-comment-o"></i> <?php echo implode('<br>',str_split(htmlentities($sms['sms']), 100));?>
                    </p>
                    <br />
                    <p class="text-muted small">    
                        <a href="#" class="label label-danger hapus-sms" onclick="javascript:window.hapusSMS('outbox','<?php echo $sms['id']; ?>');"><i class="fa fa-scissors"></i> Hapus</a>                 
                        <span class="label label-info"><i class="fa fa-spinner fa-spin"></i> Sedang mengantri...</span>                    
                    </p>
                </div>
            </li>    
                                   
        <?php
        }
        unset($smses);
    }
    // state the data count for last page:
    echo '<input type="hidden" value="'.$sms_count.'" id="ajax-data-count" />';
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
                        <small>Pesan Sedang Dikirim (Outbox)</small>
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-database fa-fw"></i> Daftar SMS Sedang Mengantri Untuk Dikirim
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" id="" jenis="kirim" class="send-recv-sms btn btn-default btn-xs first">
                                        <i class="fa fa-plus"></i> Kirim SMS                                        
                                    </button>
                                    <button type="button"  onclick="javascript:location.reload();" class="btn btn-default btn-xs">
                                        <i class="fa fa-refresh"></i> Refresh                                        
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <ul class="chat" id="data-container">
                            <!-- Will be filled by Ajax call. -->
                            <!-- Just load this page using $.post({..., ajax: true})-->
                            <!-- Temporary display: -->                            
                                <li class="left clearfix">
                                    <span class="chat-img pull-left">
                                        <img id="img-ajax" src="img/ajax-loaders/ajax-loader-fan.gif" title="img/ajax-loaders/ajax-loader-fan.gif" />
                                    </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <strong class="primary-font">Mempersiapkan data</strong>
                                            <small class="pull-right text-muted">
                                                <i class="fa fa-clock-o fa-fw"></i> Tunggu..
                                            </small>
                                        </div>
                                        <p>
                                            Data sedang diproses oleh server...
                                        </p>
                                    </div>
                                </li>
                            </ul>
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
                                <span class="pull-right">
                                    <select id="data-count" class="form-control input-sm" style="width: 75px;">
                                        <option value="5">5</option>
                                        <option value="10" selected="">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>                
                                </span>                
                            </span>
                            <span class="pull-right">&nbsp;&nbsp;Beberapa SMS mungkin tidak tampil karena telah terkirim sebelum data diperbarui.</span>
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
    /* ajax params */
    var sms_offset      = parseInt(getCookie('sms_offset_outbox') || "0",10);    
    var sms_limit       = parseInt(getCookie('sms_limit_outbox') || "10",10);
    var sms_sortorder   = getCookie('sms_sortorder_outbox') || 'desc';
    $('#btn-nav-sort i')
        .removeClass('fa-sort-amount-asc')
        .removeClass('fa-sort-amount-desc')
        .addClass('fa-sort-amount-'+sms_sortorder);
    $('#data-count')
        // .change(function(){return false;})
        .val(sms_limit.toString())
        .change(function(){
            sms_limit = $(this).val();
            setCookie('sms_limit_outbox',sms_limit,365);
            reloadData(sms_offset, sms_limit, sms_sortorder); 
        });
    var ajax            = true;    
    var url             = '<?php $_SERVER['PHP_SELF']; ?>';
    /**
     * karena darameter dikirim sebagai objek {...,...}, 
     * maka otomatis method yang digunakan oleh jQuery adalah POST.
     */
    var timerSent;
    var reloadData = function(offset, limit, sortorder)
    {
        $('#data-container').load(url, {
            sms_offset : offset,
            sms_limit : limit, 
            sms_sortorder : sortorder,
            ajax : ajax,
            r: Math.random()    
        });  
        clearTimeout(timerSent);
        timerSent = setTimeout(function(){
            reloadData(sms_offset, sms_limit, sms_sortorder);
        }, 20000); // refresh data after 20 seconds       
    };
    
    reloadData(sms_offset, sms_limit, sms_sortorder);
    
    $('.data-nav').click(function(e){        
        e.preventDefault();
        var dc = $('#ajax-data-count').val();
        var id = $(this).prop('id').substr(8).toLowerCase();        
        switch (id)
        {
            
            case 'first':
                sms_offset = 0;                
                break;                
            case 'prior':
                sms_offset -= sms_limit;
                if (sms_offset<0) {sms_offset=0;}
                break;  
            case 'next': 
                sms_offset += sms_limit;
                if (sms_offset>=dc) {sms_offset=dc-sms_limit;}
                break; 
            case 'last': 
                sms_offset = dc-sms_limit;
                break;
            case 'sort': 
                sms_sortorder = (sms_sortorder=='asc'?'desc':'asc');
                $('#'+$(this).prop('id')+' i')
                    .removeClass('fa-sort-amount-asc')
                    .removeClass('fa-sort-amount-desc')
                    .addClass('fa-sort-amount-'+sms_sortorder);                
                break;
            case 'refresh':                
                break;
        }  
        
        $('#btn-nav-first').prop('disabled', sms_offset == 0);
        $('#btn-nav-prior').prop('disabled', sms_offset == 0);
        $('#btn-nav-next').prop('disabled', sms_offset >= (dc-sms_limit));
        $('#btn-nav-last').prop('disabled', sms_offset >= (dc-sms_limit));
        // alert (sms_offset+"\n"+sms_limit+"\n"+sms_keyword+"\n"+sms_sortorder);
        setCookie('sms_offset_outbox',sms_offset, 360);
        setCookie('sms_limit_outbox',sms_limit, 360);
        setCookie('sms_sortorder_outbox',sms_sortorder, 360);
        reloadData(sms_offset, sms_limit, sms_sortorder);     
        return false;
    });
});
</script>   
<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>