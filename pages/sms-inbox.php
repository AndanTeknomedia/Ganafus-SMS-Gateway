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
    $sms_keyword    = post_var('sms_keyword', 'PINJAM'); // default SMS with this keyword to display.
    $sms_sort_order = post_var('sms_sortorder','asc'); // default SMS will be sorted ascending way. Only SMS timestamp supported.
    $sms_count      = fetch_one_value("select count(id) from sms_valid");
    // $sms_count = 0;
    if ($sms_offset==-1) {
        $sms_offset = $sms_count-$sms_limit;
        if ($sms_offset<0) { $sms_offset = 0; }
    }
    
    // $leftover_sql   = "select max(id) from sms_valid sv order by sv.waktu_terima $sms_sort_order, sv.id $sms_sort_order limit $sms_offset,$sms_limit";
    
    $sql = "select sv.id, sv.udh, sv.waktu_terima, sv.pengirim, sv.sms, sv.jenis, sv.param_count, sv.diproses
        from sms_valid sv order by sv.waktu_terima $sms_sort_order, sv.id $sms_sort_order limit $sms_offset,$sms_limit";
    // echo $sql;
    if ($sms_count == 0)
    {
    ?>
        <li class="left clearfix">
            <span class="chat-img pull-left">
                <img  class="img-circle" src="img/front-end/sms-data-sent.jpg" width="50" height="50" />                
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
                    <!--
                    <i class="fa fa-envelope-o fa-fw fa-2x"></i>
                    -->
                    <!--
                    <span style="position: absolute; width: 50px; height: 50x; border-radius: 50%;background-image: url(img/front-end/sms-data.jpg);"><?php echo $i; ?></span>
                    -->
                    
                    <img  class="img-circle" src="img/front-end/sms-data.jpg" width="50" height="50" />
                    
                </span>
                <div class="chat-body clearfix">
                    <div class="header">
                        <i class="fa fa-phone-square"></i> <strong class="primary-font" id="pengirim-<?php echo $sms['id']; ?>"><?php echo $sms['pengirim'];?></strong>
                        <small class="pull-right text-muted small">
                            <i class="fa fa-clock-o fa-fw"></i> <?php echo $sms['waktu_terima'];?>
                        </small>
                    </div>
                    <p>
                        <i class="fa fa-comment-o"></i> <?php echo substr($sms['sms'],0,100).'...';?>
                        <a href="#" class="full-sms"><i class="fa fa-hand-o-right "></i>
                            <span class="hide" id="full-sms-<?php echo $sms['id']; ?>"><?php echo $sms['sms'];?></span>
                        </a>
                    </p>
                    <br />
                    <p class="text-muted small">                     
                        <a href="#" class="label label-default balas-sms" onclick="javascript:window.balasSMS('<?php echo $sms['id']; ?>');"><i class="fa fa-reply"></i> Balas</a>
                        <span 
                            class="label label-<?php
                            switch ($sms['diproses'])
                            {
                                case 'Diproses' : echo 'warning'; break;
                                case 'Dibalas'  : echo 'success'; break;
                                default         : echo 'danger';    
                            }                
                            ?>"><i class="fa fa-check"></i> <?php echo $sms['diproses'];?></span>                    
                        <span class="label label-info"><i class="fa fa-tag"></i> <?php echo $sms['jenis'];?></span>    
                        
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
                        <small>Pesan Masuk (Inbox)</small>
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-database fa-fw"></i> Daftar SMS Pada Kotak Masuk
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
                                                <i class="fa fa-clock-o fa-fw"></i> Tunggu...
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
    var sms_offset      = parseInt(getCookie('sms_offset') || "0",10);    
    var sms_limit       = parseInt(getCookie('sms_limit') || "10",10);
    var sms_keyword     = getCookie('sms_keyword') || 'PINJAM';
    var sms_sortorder   = getCookie('sms_sortorder') || 'asc';
    // alert (sms_offset+"\n"+sms_limit+"\n"+sms_keyword+"\n"+sms_sortorder);
    $('#btn-nav-sort i')
        .removeClass('fa-sort-amount-asc')
        .removeClass('fa-sort-amount-desc')
        .addClass('fa-sort-amount-'+sms_sortorder);
    $('#data-count')
        // .change(function(){return false;})
        .val(sms_limit.toString())
        .change(function(){
            sms_limit = $(this).val();
            setCookie('sms_limit',sms_limit,365);
            reloadData(sms_offset, sms_limit, sms_keyword, sms_sortorder); 
        });
    var ajax            = true;    
    var url             = '<?php $_SERVER['PHP_SELF']; ?>';
    /**
     * karena darameter dikirim sebagai objek {...,...}, 
     * maka otomatis method yang digunakan oleh jQuery adalah POST.
     */
    var timer;
    var reloadData = function(offset, limit, keyword, sortorder)
    {
        $('#data-container').load(url, {
            sms_offset : offset,
            sms_limit : limit, 
            sms_keyword : keyword,
            sms_sortorder : sortorder,
            ajax : ajax,
            r: Math.random()    
        });  
        clearTimeout(timer);
        timer = setTimeout(function(){
            reloadData(sms_offset, sms_limit, sms_keyword, sms_sortorder);
        }, 20000); // refresh data after 20 seconds       
    };
    
    reloadData(sms_offset, sms_limit, sms_keyword, sms_sortorder);
    
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
        setCookie('sms_offset',sms_offset, 360);
        setCookie('sms_limit',sms_limit, 360);
        setCookie('sms_keyword',sms_keyword, 360);
        setCookie('sms_sortorder',sms_sortorder, 360);
        reloadData(sms_offset, sms_limit, sms_keyword, sms_sortorder);     
        return false;
    });
    
    // balas SMS:
    window.balasSMS = function(id){
        var oln = $('#pengirim-'+id).text();
        var olt = $('#full-sms-'+id).text();
        window.oldSMSNumber = oln;
        window.oldSMSText = olt;
        var dlgSendSMS = BootstrapDialog.show({
            size    : BootstrapDialog.SIZE_SMALL,
            title   : ('Balas SMS'),
            closable: true,
            draggable: true,
            message : $(sendSMSForm(true))
        });      
        window.dlgSendSMS = dlgSendSMS;
        dlgSendSMS.open();
        return false;
    };
});
</script>   
<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>