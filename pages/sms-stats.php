<?php
$page_name = 'SMS Statistic';
include_once('../cores/definition.php'); 
require_once('../cores/db.php'); 
include_once('../cores/session.php');

$self = $_SERVER['PHP_SELF'];
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

$sms_folder = strtolower(urldecode(get_var('folder',''))); 
    // inbox, sent, all. all will display SMS trafic (sent & received) by date range.
    // inbox will display SMS by date range, filtered by keyword.
    // sent will display SMS by date range, filtered by status ('error' or 'success').
$sms_kategori = strtolower(urldecode(get_var('kategori','')));
$sms_keyword = strtolower(urldecode(get_var('keyword','')));

include_once('../gammu/gammu-fetch-sms.php');
error_reporting(E_ALL ^ E_NOTICE);

/**
 * If this page is is being loaded using Ajax call,
 * fetch the requested data and skip the rest of the page:
 */
// generate series of hour between two days
function generate_time_series($date1, $date2, $kind)
{
    $times = array();
    $start = strtotime($date1);
    $end = strtotime($date2);
    if ($kind=='h')
    {
        for ($i = $start; $i <= $end; $i += 24 * 3600)
        {
            for ($j = 0; $j <= 23; $j+=4)
            {
                $times []= date("Y-m-d ".str_pad($j,2,'0', STR_PAD_LEFT), $i);
            }
        }
    }
    else 
    if ($kind=='d')
    {
        $d = date('d',$start);
        
        for ($i = $start; $i <= $end+(24*3600); $i += 24*3600)
        {            
            $times []= date("Y-m-", $i).str_pad($d, 2,'0', STR_PAD_LEFT);
            $d++;
        }
    }
    return $times;
}

function generate_dates($frequency, $date1, $date2)
{
    $result = array($date1, $date2);
    $d = $date2;
    switch ($frequency)
    {
        case 'd':            
            break;
        case 'w':
            $d = date_create($date1);
            date_add($d, date_interval_create_from_date_string('7 days'));
            $d = date_format($d, 'Y-m-d');
            $result[1] = $d;
            break;
        default : //monthly
            $d = date_create($date1);
            date_add($d, date_interval_create_from_date_string('1 month'));
            $d = date_format($d, 'Y-m-d');
            $result[1] = $d;
    }
    return $result;
}


if ($ajax)
{
    $stat_freq      = post_var('statfreq', 'd');
    $stat_date1     = post_var('statdate1', date('d/m/Y'));  
    $stat_date2     = post_var('statdate2', date('d/m/Y'));  // default offset of first item to fetch.
    $stat_folder    = post_var('statfolder', 'all'); // inbox, sent, all.
    // if $stat_folder is 'inbox', $stat_keyword will be sms keyword
    // if $stat_folder is 'sent', $stat_keyword will be 'error' or 'success';
    $stat_kategori   = post_var('statkategori', 'all'); // default SMS with this category to display.
    $stat_keyword   = post_var('statkeyword', 'all'); // default SMS with this keyword to display.
    
    /**
     * Here we generate morris compatible JSON for charting:
     */    
    // build dates: DD/MM/YYYY
    $dt1 = substr($stat_date1,6,4).'-'.substr($stat_date1,3,2).'-'.substr($stat_date1,0,2);
    $dt2 = substr($stat_date2,6,4).'-'.substr($stat_date2,3,2).'-'.substr($stat_date2,0,2);
    
    switch ($sms_folder)
    {
        case 'inbox':
            $dates = generate_dates($stat_freq, $dt1, $dt2);
            $dt1 = $dates[0];
            $dt2 = $dates[1];
            unset($dates);
            if ($stat_kategori=='all')
            {
                // keyword tidak diproses
                $ibx_sql = "select  concat(date(v.waktu_terima),' ', HOUR(v.waktu_terima),':00') as period, count(*) as jumlah
                    from sms_valid v
                    where (v.waktu_terima >= concat('$dt1',' 00:00:00')) and (v.waktu_terima <= concat('$dt2',' 23:59:59'))
                    group by concat(date(v.waktu_terima),' ', HOUR(v.waktu_terima),':00')";    
            }
            else
            {
                if ($stat_keyword=='all')
                {
                    $ibx_sql = "select  concat(date(v.waktu_terima),' ', HOUR(v.waktu_terima),':00') as period, count(*) as jumlah
                        from sms_valid v
                        inner join sms_keywords k on upper(k.keyword) = upper(v.jenis)
                        where 
                        upper(k.kategori) = upper('$stat_kategori') and
                        (v.waktu_terima >= concat('$dt1',' 00:00:00')) and (v.waktu_terima <= concat('$dt2',' 23:59:59'))
                        group by concat(date(v.waktu_terima),' ', HOUR(v.waktu_terima),':00')";   
                }
                else
                {
                    $ibx_sql = "select  concat(date(v.waktu_terima),' ', HOUR(v.waktu_terima),':00') as period, count(*) as jumlah
                        from sms_valid v
                        inner join sms_keywords k on upper(k.keyword) = upper(v.jenis)
                        where 
                        upper(k.kategori) = upper('$stat_kategori') and
                        upper(k.keyword) = upper('$stat_keyword') and
                        (v.waktu_terima >= concat('$dt1',' 00:00:00')) and (v.waktu_terima <= concat('$dt2',' 23:59:59'))
                        group by concat(date(v.waktu_terima),' ', HOUR(v.waktu_terima),':00')";       
                }
            }
            $data = array();
            $q_count = fetch_query($ibx_sql);
            
            // refill with real values:
            foreach($q_count as $i=>$vals)
            {
                $data[] =  array('period'=>$vals['period'], 'jumlah'=>$vals['jumlah']);                        
            }
            unset($q_count);
            
            $json = array(
                'data' => $data,
                // metadata:
                'xkey'      => 'period',
                'ykeys'     => array('jumlah'),
                'labels'    => array('Jumlah'),
                'pointSize' => 2,
                'lineColors'=> array('#62d8b6'),
                'hideHover' => 'auto',
                'resize'    => true,
                'xLabels'   => 'Time Period'
            );                
            echo json_encode($json); 
            break;
        case 'sent':
            // kategori not processed
            // keyword not processed
            $dates = generate_dates($stat_freq, $dt1, $dt2);
            $dt1 = $dates[0];
            $dt2 = $dates[1];
            unset($dates);
            $data = array();
            $q_count = fetch_query(
                "select 
                	concat(date(s.UpdatedInDB),' ', HOUR(s.UpdatedInDB),':00') as period, 
                	(
                		select count(*) from sentitems sc where concat(date(sc.UpdatedInDB),' ', HOUR(sc.UpdatedInDB),':00') = concat(date(s.UpdatedInDB),' ', HOUR(s.UpdatedInDB),':00')
                		and (POSITION(lower('error') IN lower(sc.`Status`))=0) 
                	) as success
                	,
                	(
                		select count(*) from sentitems sc where concat(date(sc.UpdatedInDB),' ', HOUR(sc.UpdatedInDB),':00') = concat(date(s.UpdatedInDB),' ', HOUR(s.UpdatedInDB),':00')
                		and (POSITION(lower('error') IN lower(sc.`Status`))>0) 
                	) as error
                from sentitems s 
                where ((date(s.UpdatedInDB) >= '$dt1') and (date(s.UpdatedInDB) <= '$dt2'))
                group by concat(date(s.UpdatedInDB),' ', HOUR(s.UpdatedInDB),':00')
            ");
            
            // refill with real values:
            foreach($q_count as $i=>$vals)
            {
                $data[] =  array('period'=>$vals['period'], 'success'=>$vals['success'],'error'=>$vals['error']);                        
            }
            unset($q_count);
            
            $json = array(
                'data' => $data,
                // metadata:
                'xkey'      => 'period',
                'ykeys'     => array('success', 'error'),
                'labels'    => array('Success', 'Error'),
                'pointSize' => 2,
                'lineColors'=> array('#62d8b6','#ff9eb6'),
                'hideHover' => 'auto',
                'resize'    => true,
                'xLabels'   => 'Time Period'
            );                
            echo json_encode($json); 
            break;
        default: // assumed as 'all'
            // kategori not processed
            // keyword not processed
            $dates = generate_dates($stat_freq, $dt1, $dt2);
            $dt1 = $dates[0];
            $dt2 = $dates[1];
            unset($dates);
            $data = array();
            $q_count = fetch_query(
                "select 
                	periods.period,
                	(select count(*) from sms_valid v where concat(date(v.waktu_terima),' ', HOUR(v.waktu_terima),':00') = periods.period) as received,
                	(select count(*) from sentitems s where concat(date(UpdatedInDB),' ', HOUR(UpdatedInDB),':00') = periods.period) as sent                	
                from (
                	select concat(date(UpdatedInDB),' ', HOUR(UpdatedInDB),':00') as period from sentitems
                	union
                	select concat(date(waktu_terima),' ', HOUR(waktu_terima),':00') as period from sms_valid) periods
                where (date(period)>='$dt1') and (date(period)<='$dt2');");
            // refill with real values:
            foreach($q_count as $i=>$vals)
            {
                $data[] =  array('period'=>$vals['period'], 'received'=>$vals['received'],'sent'=>$vals['sent']);                        
            }
            unset($q_count);
            
            $json = array(
                'data' => $data,
                // metadata:
                'xkey'      => 'period',
                'ykeys'     => array('sent', 'received'),
                'labels'    => array('SMS Sent', 'SMS Received'),
                'pointSize' => 2,
                'lineColors'=> array('#ff9eb6','#62d8b6'),
                'hideHover' => 'auto',
                'resize'    => true,
                'xLabels'   => 'Time Period'
            );                
            echo json_encode($json); 
            break;
    }    
    // Skip the rest of the page:
    exit(); 
}

require_login();
$skip_morris = false;

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
                        <small>SMS Statistics </small>
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-database fa-fw"></i> SMS Statistic
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="<?php echo $self; ?>?folder=all&kategori=all&keyword=all" class="btn btn-default btn-xs first">
                                        <i class="fa fa-envelope-o"></i> All SMS                                       
                                    </a>
                                    <a href="<?php echo $self; ?>?folder=inbox&kategori=all&keyword=all" class="btn btn-default btn-xs">
                                        <i class="fa fa-inbox"></i> SMS Inbox                                        
                                    </a>
                                    <a href="<?php echo $self; ?>?folder=sent&kategori=all&keyword=all" class="btn btn-default btn-xs">
                                        <i class="fa fa-send"></i> SMS Sent
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                
                                    <?php
                                    if ($sms_folder=='all')
                                    {
                                        echo 'SMS statistic will display all received and sent SMSes.';
                                    }
                                    else
                                    if ($sms_folder=='inbox')
                                    {
                                        $kats = keyword_fetch_kategori();       
                                        $kats_kosong = count($kats)==0;                          
                                        if ($kats_kosong) {
                                            echo 'You have no keyword. SMS statistic will display all received SMSes.';
                                        } 
                                        else 
                                        {                                         
                                            echo '<div class="btn-group pull-right">';
                                                     
                                            echo '<a href="#" class="btn disabled btn-success btn-sm"><strong>Keywords:</strong></a>';
                                            echo '<a href="'.$self.'?folder='.urlencode($sms_folder).'&kategori=all&keyword=all" class="btn btn-default btn-sm">All Inbox</a>';
                                            $kat_idx = 0;
                                            foreach ($kats as $kat)
                                            {
                                                $kat_idx++;
                                                echo '<div class="btn-group">';
                                                echo '<a href="'.$self.'?folder='.urlencode($sms_folder).'&kategori='.urlencode(strtolower($kat)).'&keyword=all" class="btn btn-default btn-sm">'.ucfirst(strtolower($kat)).'</a>';
                                                echo '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>';
                                                echo '<ul class="dropdown-menu pull-right" role="menu">';
                                                $menu_keys = keyword_fetch_all($kat);                                                                
                                                foreach($menu_keys as $i => $menu_key)
                                                {
                                                    echo '<li>';
                                                    echo '<a href="'.$self.'?folder='.urlencode($sms_folder).'&kategori='.urlencode(strtolower($menu_key['kategori'])).'&keyword='.urlencode(strtolower($menu_key['keyword'])).'">';
                                                    echo ucfirst(strtolower($menu_key['keyword'])).'</a>';
                                                    echo '</li>';
                                                }
                                                
                                                echo '</ul>';
                                                echo '</div>';
                                            }
                                            echo '</div>';                                          
                                        }                                 
                                    }
                                    else
                                    if ($sms_folder=='sent')
                                    {
                                        echo 'SMS statistic will display all sent SMSes.';
                                    }
                                    ?>
                                </div>                                                            
                            </div>    
                            <div class="row">
                                <div class="col-lg-12">
                                    <hr />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <!-- Morris Chart Here -->
                                    
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            SMS Frequency Graph
                                        </div>
                                        <div class="panel-body">
                                            <div id="sms-stat-area-chart"></div>
                                        </div>                                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <div class="row">
                            <div class="col-sm-2">
                                <label>Frequency</label>
                                <select id="stat-frequency" class="form-control input-sm" style="width: 100%;">
                                    <option value="d">Daily</option>
                                    <option value="w" selected="">Weekly</option>
                                    <option value="m">Monthly</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Start<small> - dd/mm/yyyy</small></label>
                                <div class="form-group input-group">                        
                                    <input type="text" class="form-control input-sm date" id="stat-start-date" value="<?php echo date('d/m/Y'); ?>" />
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar-o"></i></span>                                              
                                </div>
                            </div>
                            <div class="col-sm-2 hide until-date">
                                <label>Until<small> - dd/mm/yyyy</small></label>
                                <div class="form-group input-group">                        
                                    <input type="text" class="form-control input-sm date" id="stat-until-date" value="<?php echo date('d/m/Y'); ?>" />
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar-o"></i></span>                                              
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-sm btn-primary" id="stat-reload">
                                        <i class="fa fa-send-o"></i> Go
                                    </button>
                                </div>
                            </div>
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
    var stat_freq       = getCookie('stat-freq') || 'w'; 
    var stat_date1      = getCookie('stat-start-date') || '<?php echo date('d/m/Y'); ?>';   
    var stat_date2      = getCookie('stat-until-date') ||'<?php echo date('d/m/Y'); ?>';
    var stat_folder     = '<?php echo $sms_folder; ?>';
    var stat_kategori   = '<?php echo $sms_kategori; ?>';
    var stat_keyword    = '<?php echo $sms_keyword ?>';         
    var ajax            = true;    
    var url             = '<?php $self; ?>';
    
    var timer;
    var morrisChart;
    
    var validDate = function(strDate) { var rx = /^(0[1-9]|1\d|2\d|3[01])\/(0[1-9]|1[0-2])\/(19|20)\d{2}$/; return rx.test(strDate);};
    var initMetaData = function()
    {
        if (assigned(morrisChart)) { return ; }
        var postData = {
            type: 'meta', //'meta' to get parameters, 'data' to get data.
            statfolder: stat_folder,
            statkategori: stat_kategori,
            statkeyword: stat_keyword,
            statfreq: stat_freq,
            statdate1: stat_date1,
            statdate2: stat_date2,
            ajax : ajax,
            r: Math.random()    
        };  
        $.post(url, postData, function (respData){
            // console.log(respData);
            var o = JSON.parse(respData);
            morrisChart = Morris.Area({ // Bedakan 'null' dengan null, kalo gak bakal error.
                element: 'sms-stat-area-chart',
                data: null,                
                xkey: o.xkey,
                ykeys: o.ykeys,
                labels: o.labels,
                pointSize: o.pointSize,
                hideHover: o.hideHover,
                resize: o.resize,
                xLabels:o.xLabels,
                lineColors: o.lineColors
            });
        });   
    };
    var reloadData = function(folder, kategori, keyword, freq, date1, date2)
    {
        
        var postData = {
            type: 'data', //'meta' to get parameters, 'data' to get data.
            statfolder: folder,
            statkategori: kategori,
            statkeyword: keyword,
            statfreq: freq,
            statdate1: date1,
            statdate2: date2,
            ajax : ajax,
            r: Math.random()    
        };       
        $.post(url, postData, function (respData){
            var o = JSON.parse(respData);
            // console.log(o);
            morrisChart.setData(o.data);
        });          
        clearTimeout(timer);  
        // return;     
        timer = setTimeout(function(){
            reloadData(stat_folder, stat_kategori, stat_keyword, stat_freq, stat_date1, stat_date2);
        }, 20000); // refresh data after 20 seconds               
    };    

    $('#stat-start-date').val(stat_date1);
    $('#stat-until-date').val(stat_date2);
    
    $('#stat-frequency').change(function()
    {
       var freq = $(this).val();
       if (freq == 'd')
       {
            $('.until-date').removeClass('hide');
       }        
       else
       {
            $('.until-date').addClass('hide');
       }
    }).val(stat_freq).change();

    /**
     * Reload stats:
     */
    initMetaData();
    $('#stat-reload').click(function(e){
        e.prevenDefault;
        var sDate = $('#stat-start-date').val();
        var uDate = $('#stat-until-date').val();
        var freq  = $('#stat-frequency').val();
        if (!validDate(sDate)){msgBox('Error','Start Date is invalid.'); return false}
        if (!validDate(uDate)){msgBox('Error','Until Date is invalid.'); return false}
        setCookie('stat-freq', freq, 365);
        setCookie('stat-start-date', sDate, 365);
        setCookie('stat-until-date', uDate, 365);
        stat_freq       = freq; 
        stat_date1      = sDate;   
        stat_date2      = uDate;
        reloadData(stat_folder, stat_kategori, stat_keyword, stat_freq, stat_date1, stat_date2);
        return false;
    }).click();
    
});
</script>   
<?php include '_footscripts.php'; ?>
<!--
<script src="../js/morris-test.js"></script>
-->
<?php include '_foot.php'; ?>