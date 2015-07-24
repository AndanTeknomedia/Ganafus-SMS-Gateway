<?php
/*
exec("mode com11: BAUD=921600 PARITY=n DATA=8 STOP=1 to=off dtr=off rts=off");
$fp =fopen("com11", "r+b");
//$fp = fopen('/dev/ttyUSB0','r+'); //use this for Linux
fwrite($fp, "at\r"); //write string to serial
$data = "";
while ($c = fread($fp,1))
{
    $data .= $c;
}
echo $data;
fclose($fp);

die();
*/

require("php_serial.class.php");
$serial = new phpSerial();
$serial->deviceSet("com11");
$serial->confBaudRate(921600); //Baud rate: 9600
$serial->confParity("none"); //Parity (this is the "N" in "8-N-1")
$serial->confCharacterLength(8); //Character length
$serial->confStopBits(1); //Stop bits (this is the "1" in "8-N-1")
$serial->confFlowControl("none");
$serial->deviceOpen("r+");
$serial->sendMessage("ate1\r");
$respond = $serial->readPort();
$serial->deviceClose();
echo $respond;
?>