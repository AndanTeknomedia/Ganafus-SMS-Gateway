<?php
/*
** 
** Built-in session for internal ajax. Do not mix it with UI session!
*/

ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
session_start();
/* Required files */

include_once('../cores/definition.php'); 

$ajax           = post_var('ajax','');
$jenis_sms      = 'inbox'; // post_var('jenis_sms');
$sort_type      = post_var('sort_type','');
$fetch_count    = post_var('fetch_count','');

if (empty($ajax) || empty($sort_type) || empty($fetch_count))
{
    die('Invalid arguments.');
}
include_once('../cores/db.php');
include_once('gammu-cores.php');

$sql = "select 
    (@lapse := timediff(CURRENT_TIMESTAMP(),sv.waktu_terima)) as time_lapse,
    /* 
    EXTRACT(hour FROM @lapse) as h_lapse,
    EXTRACT(minute FROM @lapse) as m_lapse,
    EXTRACT(second FROM @lapse) as s_lapse,
    */
    hour(@lapse) as h_lapse,
    minute(@lapse) as m_lapse,
    second(@lapse) as s_lapse,
    sv.id, sv.udh, sv.waktu_terima, sv.pengirim, sv.sms, sv.jenis, sv.diproses
    from sms_valid sv order by id $sort_type limit 0,$fetch_count";
// echo  $sql;
$smses = fetch_query($sql);
foreach ($smses as $i => $sms)
{
?>
    <li class="left clearfix" id="sms-<?php echo $sms['id']; ?>">
        <div class="clearfix">
            <div class="header">
                <i class="fa fa-envelope-o "></i> <strong class="small"><?php echo $sms['pengirim'];?></strong>
                <small class="pull-right text-muted small">                    
                    <?php echo 
                        ($sms['h_lapse']!='0'?$sms['h_lapse'].'jam ':'').
                        ($sms['m_lapse']!='0'?$sms['m_lapse'].'min ':'').
                        $sms['s_lapse'].'dtk '.
                        'lalu';?> 
                    <i class="fa fa-clock-o fa-fw"></i> 
                </small>
            </div>
            <p class="small">
                <?php echo substr($sms['sms'],0,45).'...';?>
            </p>
        </div>
    </li> 
<?php
}

unset($smses);

?>