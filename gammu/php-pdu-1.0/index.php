<?php
/**
 * This class converts a SMS and cellphone number to a PDU representation.
 * You can use this PDU string to send AT commands from PHP to your cellphone
 * or every other purpose where you need a PDU formatted string for SMS purpose.
 *
 * This work is licensed under the
 * Creative Commons Attribution-Noncommercial-Share Alike 3.0 Netherlands License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/nl/
 * or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
 *
 * If you need this code under another license please contact me.
 *
 * @author: Vincent Heet <vincentheet@gmail.com>
 * @link: http://adevelopersblog.com
 * @version: 1.0
 */

include_once('pdu.php');

$no = '0612345678';
$msg = 'This is a text message.';
$pduGenerator = new Pdu();
$pduMessage = $pduGenerator->generatePDU($no, $msg);

if($pduMessage===false){
    echo $pduGenerator->getErrorString();
}else{
    echo 'NO: ' .$no. '<br>';
    echo 'NO: ' .$msg. '<br>'; 
	echo 'PDU: '. $pduMessage['pdu'] . '<br>'; // The PDU string
	echo 'CMGS Length: ' . $pduMessage['cmgslen']; // CMGS Length for AT command
}
?>