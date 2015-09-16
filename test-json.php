<?php

if (!preg_match('/^[0-9]{1,2}+([\,\.][0-9]{1,2})?$/', "1.989"))
{ 
  echo "invalid";
}
else
{
    echo 'OK';
}

die();

$page_name = 'SMS Statistic';
include_once('cores/definition.php'); 
require_once('cores/db.php'); 
include_once('cores/session.php');


error_reporting(E_ALL ^ E_NOTICE);

$sms_count = get_system_config('sms_to_process_per_minute', 20);

echo $sms_count;

die();



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
                $times []= date("Y-m-d ".str_pad($j,2,'0', STR_PAD_LEFT), $i).':00';
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



$stat_freq      = 'd';
$stat_date1     = date('01/08/2015');  
$stat_date2     = date('d/m/Y');

// build dates: DD/MM/YYYY
$dt1 = substr($stat_date1,6,4).'-'.substr($stat_date1,3,2).'-'.substr($stat_date1,0,2);
$dt2 = substr($stat_date2,6,4).'-'.substr($stat_date2,3,2).'-'.substr($stat_date2,0,2);
$dt2 = date_create($dt1);
date_add($dt2, date_interval_create_from_date_string('2 months 3 days'));
$dt2 = date_format($dt2, 'Y-m-d');
echo $dt1;
echo $dt2;
die();
$ret = array();
$daily_hours = generate_time_series($dt1, $dt2, 'h');    
foreach ($daily_hours as $i=>$daily_hour)
{
    //$ret[] = array('period'=>$daily_hour, 'success'=>'0', 'error'=>'0');
    
    $ret[$daily_hour]['success']="0";
    $ret[$daily_hour]['error']="0";
    
}    
$q_success = fetch_query("select concat(date(s.UpdatedInDB),' ', HOUR(s.UpdatedInDB),':00') as sms_time, count(*) as sms_count
                from sentitems s 
                where ((date(s.UpdatedInDB) >= '$dt1') and (date(s.UpdatedInDB) <= '$dt2'))
                and (POSITION(lower('error') IN lower(s.`Status`))=0) 
                group by concat(date(s.UpdatedInDB),' ', HOUR(s.UpdatedInDB),':00')");
$q_error   = fetch_query("select concat(date(s.UpdatedInDB),' ', HOUR(s.UpdatedInDB),':00') as sms_time, count(*) as sms_count
                from sentitems s 
                where ((date(s.UpdatedInDB) >= '$dt1') and (date(s.UpdatedInDB) <= '$dt2'))
                and (POSITION(lower('error') IN lower(s.`Status`))>0) 
                group by concat(date(s.UpdatedInDB),' ', HOUR(s.UpdatedInDB),':00')");
foreach($q_success as $hour=>$smscount)
{
    $ret[$smscount['sms_time']]['success']=$smscount['sms_count'];        
    
} 
foreach($q_error as $hour=>$smscount)
{
    $ret[$smscount['sms_time']]['error']=$smscount['sms_count'];    
    
} 
$data = array();
foreach($ret as $period=>$vals)
{
    $data[] = array('period'=>$period, 'success'=>$vals['success'],'error'=>$vals['error']);
}

$json = array(
    'data' => $data,
    // metadata:
    'xkey'      => 'period',
    'ykeys'     => array('iphone', 'ipad', 'itouch'),
    'labels'    => array('iPhone', 'iPad', 'iPod Touch'),
    'pointSize' => 2,
    'lineColors'=> array('#ff0000','#ff00ff','#0000ff'),
    'hideHover' => 'auto',
    'resize'    => true,
    'xLabels'   => 'day'
);                
// echo json_encode($json); 
echo json_encode($json);

?>