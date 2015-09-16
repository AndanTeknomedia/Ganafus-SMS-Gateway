<?php
$base_data = 
'<?xml version="1.0" encoding="UTF-16"?> '."\n".
'<Task version="1.2" xmlns="http://schemas.microsoft.com/windows/2004/02/mit/task">'."\n".
'  <RegistrationInfo>'."\n".
'    <Date>%REGDATE%T%REGTIME%</Date>'."\n".
'    <Author>Toshiba</Author>'."\n".
'  </RegistrationInfo>'."\n".
'  <Triggers>'."\n".
'    <TimeTrigger>'."\n".
'      <Repetition>'."\n".
'        <Interval>PT1M</Interval>'."\n".
'        <StopAtDurationEnd>false</StopAtDurationEnd>'."\n".
'      </Repetition>'."\n".
'      <StartBoundary>%STARTDATE%T%STARTTIME%</StartBoundary>'."\n".
'      <Enabled>true</Enabled>'."\n".
'    </TimeTrigger>'."\n".
'  </Triggers>'."\n".
'  <Principals>'."\n".
'    <Principal id="Author">'."\n".
'      <UserId>S-1-5-18</UserId>'."\n".
'      <RunLevel>LeastPrivilege</RunLevel>'."\n".
'    </Principal>'."\n".
'  </Principals>'."\n".
'  <Settings>'."\n".
'    <MultipleInstancesPolicy>IgnoreNew</MultipleInstancesPolicy>'."\n".
'    <DisallowStartIfOnBatteries>false</DisallowStartIfOnBatteries>'."\n".
'    <StopIfGoingOnBatteries>true</StopIfGoingOnBatteries>'."\n".
'    <AllowHardTerminate>true</AllowHardTerminate>'."\n".
'    <StartWhenAvailable>false</StartWhenAvailable>'."\n".
'    <RunOnlyIfNetworkAvailable>false</RunOnlyIfNetworkAvailable>'."\n".
'    <IdleSettings>'."\n".
'      <StopOnIdleEnd>true</StopOnIdleEnd>'."\n".
'      <RestartOnIdle>false</RestartOnIdle>'."\n".
'    </IdleSettings>'."\n".
'    <AllowStartOnDemand>true</AllowStartOnDemand>'."\n".
'    <Enabled>true</Enabled>'."\n".
'    <Hidden>false</Hidden>'."\n".
'    <RunOnlyIfIdle>false</RunOnlyIfIdle>'."\n".
'    <WakeToRun>false</WakeToRun>'."\n".
'    <ExecutionTimeLimit>P3D</ExecutionTimeLimit>'."\n".
'    <Priority>7</Priority>'."\n".
'  </Settings>'."\n".
'  <Actions Context="Author">'."\n".
'    <Exec>'."\n".
'      <Command>%PHPPATH%</Command>'."\n".
'      <Arguments>%ARGUMENTS%</Arguments>'."\n".
'    </Exec>'."\n".
'  </Actions>'."\n".
'</Task>';
// baca file:
$fdata = $base_data;
// tulis file:
$fdata = str_replace('%REGDATE%T%REGTIME%', date('Y-m-d').'T'.date('h:i:s'), $fdata);
$maju1menit = strtotime(date("H:i:s")." +1 minutes");
$fdata = str_replace('%STARTDATE%T%STARTTIME%', date('Y-m-d').'T'.date('h:i:s', $maju1menit), $fdata);
/*
$fdata = str_replace('%PHPPATH%', $php, $fdata);
$fdata = str_replace('%ARGUMENTS%', '-c "'.$php_ini_cli.'" ""'.$sms_processor.'""', $fdata);
*/
$taskdefs = 'C:\xeroxl\UniServerZ\vhosts\inkubator-local\gammu\task-defs.xml';
$ftask = fopen($taskdefs, 'w');
fputs($ftask,$fdata);        
fclose($ftask);

?>