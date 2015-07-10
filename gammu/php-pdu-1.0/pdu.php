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

class Pdu {

    // Last error code
    private $lastErrorCode;

    // Set last error code
    private function setErrorCode( $errorcode ) {
        $this->lastErrorCode = $errorcode;
    }

    // Get last error code
    public function getErrorCode() {
        return $this->lastErrorCode;
    }

    // Get last error string
    public function getErrorString() {
        if($this->getErrorCode()==1) {
            return 'Error #1: Telephone/gsm number not valid.';
        }
        if($this->getErrorCode()==2) {
            return 'Error #2: Message too short.';
        }
        if($this->getErrorCode()==3) {
            return 'Error #3: Message too long.';
        }
        return 'No error.';
    }

    // Generate PDU string
    public function generatePDU($receiverNumber,$message) {

        // Receiver number must be 10 characters long. Because we use numberType 81
        if( strlen($receiverNumber) != 10 ) {
            // Error, not 10 numbers long (Code 1)
            $this->setErrorCode(1);
            return false;
        }

        // Message must be 2 characters long at least
        if( strlen($message) < 2 ) {
            // Error, message too short (Code 2)
            $this->setErrorCode(2);
            return false;
        }

        // Message can't be longer than 160 characters. Only 'short' SMS is supported.
        if( strlen($message) > 160 ) {
            // Error, message too long (Code 3)
            $this->setErrorCode(3);
            return false;
        }

        // Length of servicecenter number (normally automatically fixed by phone)
        $serviceCenterNumberLength = '00';

        // SMS-? : 04=sms-deliver(recieve), 11=sms-submit, 01 = dont know but it works
        // You can try to change this if your phone does not work with 01 command try to use 11 command
        $smsType = '01';

        // TP Message Reference: (placeholder), let the phone set the message reference number itself
        $messageRef = '00';

        // Number length
        $numberLength = '0A';

        // Type of phone adress: (81=Unknown=10dec, 91=InternationalFormat, 92=National?)
        $numberType = '81';

        // Get the PDU version of the number
        $number = $this->getNumberAsPDU( $receiverNumber );

        // TP-PID (Protocol Identifier)
        $protocolId = '00';

        // TP-DCS (Data coding scheme)
        $dataCodingScheme = '00';

        // TP-Validity-Period (timestamp), AA=4days expiry, disabled for SonyEricsson support.
        $validityPeriod = '';
        // $validityPeriod = 'AA'; // Add this if the PDU command fails

        // Data length of message (in hex format)
        $dataLength = $this->strToHexLen($message);

        // Convert message, string > 7bits > 8bits > hex
        $hexMessage = $this->bit7tohex( $this->strto7bit( $message ) );


        // Create the complete PDU string
        $pdu = $serviceCenterNumberLength . $smsType . $messageRef . $numberLength .
                $numberType . $number . $protocolId . $dataCodingScheme .
                $validityPeriod . $dataLength . $hexMessage;

        /*
         * Generate the length of var $pdu (pdu/2 minus 1) as pdu format requests
         * The -1 is because we don't count the first two characters '00', needed for this command: 'cmgs=24'
         */
        $cmgslen = strlen($pdu)/2-1;

        // Build data array to return with required information
        $data = array();
        $data['pdu'] = $pdu;
        $data['cmgslen'] = $cmgslen;

        // Return the data array with PDU information
        return $data;
    }


    // Generate PDU formatted cellphone number
    private function getNumberAsPDU($number) {

        // Length of number divided by 2 handle two characters each time
        $length = strlen( $number )/2;
        // Set counter to 1 for strlen
        $i = 1;
        $pduNumber = '';

        // Loop to handle every 2 characters of the phone number. 06 12 34 56 78
        while ($i <= $length) {
            // Get 2 characters of the complete string depending on the number of the current loop.
            // Then reverse these 2 characters and put them in var $pduNumber (06 = 60)
            $pduNumber .= strrev( substr( $number,$i*2-2,2) );
            // Counter + 1
            $i++;
        }

        // Return the generated number
        return $pduNumber;
    }


    /* Function to convert ascii character to 8 bits
     * Much more efficient than holding a complete ASCII table
     * Thanks to Mattijs F.
     */
    private function asc2bin($input, $length=8) {

        $bin_out = '';
        // Loop through every character in the string
        for($charCount=0; $charCount < strlen($input); $charCount++) {
            $charAscii = ord($input{$charCount}); // ascii value of character
            $charBinary = decbin($charAscii); // decimal to binary
            $charBinary = str_pad($charBinary, $length, '0', STR_PAD_LEFT);
            $bin_out .= $charBinary;
        }

        // Return complete generated string
        return $bin_out;
    }


    // String to 7 bits array
    private function strto7bit($message) {
        $message = trim($message);
        $length = strlen( $message );
        $i = 1;
        $bitArray = array();

        // Loop through every character in the string
        while ($i <= $length) {
            // Convert this character to a 7 bits value and insert it into the array
            $bitArray[] = $this->asc2bin( substr( $message ,$i-1,1) ,7);
            $i++;
        }

        // Return array containing 7 bits values
        return $bitArray;
    }


    // Convert 8 bits binary string to hex values (like F2)
    private function bit8tohex($bin, $padding=false, $uppercase=true) {
        $hex = '';
        // Last item for counter (for-loop)
        $last = strlen($bin)-1;
        // Loop for every item
        for($i=0; $i<=$last; $i++) {
            $hex += $bin[$last-$i] * pow(2,$i);
        }

        // Convert from decimal to hexadecimal
        $hex = dechex($hex);
        // Add a 0 (zero) if there is only 1 value returned, like 'F'
        if($padding && strlen($hex) < 2 ) {
            $hex = '0'.$hex;
        }

        // If we want the output returned as UPPERCASE do this
        if($uppercase) {
            $hex = strtoupper($hex);
        }

        // Return the hexadecimal value
        return $hex;
    }


    // Convert 7 bits binary to hex, 7 bits > 8 bits > hex
    private function bit7tohex($bits) {

        $i = 0;
        $hexOutput = '';
        $running = true;

        // For every 7 bits character array item
        while($running) {

            if(count($bits)==$i+1) {
                $running = false;
            }

            $value = $bits[$i];

            if($value=='') {
                $i++;
                continue;
            }

            // Convert the 7 bits value to the 8 bits value
            // Merge a part of the next array element and a part of the current one

            // Default: new value is current value
            $new = $value;

            if(key_exists(($i+1), $bits)) {
                // There is a next array item so make it 8 bits
                $neededChar = 8 - strlen($value);
                // Get the char;s from the next array item
                $charFromNext = substr($bits[$i+1], -$neededChar);
                // Remove used bit's from next array item
                $bits[$i+1] = substr($bits[$i+1], 0, strlen($bits[$i+1])-$neededChar );
                // New value is characters from next value and current value
                $new = $charFromNext.$value;
            }

            if($new!='') {
                // Always make 8 bits
                $new = str_pad($new, 8, '0', STR_PAD_LEFT);
                // The 8 bits to hex conversion
                $hexOutput .= $this->bit8tohex($new, true);
            }

            $i++;
        }

        // Return the 7bits->8bits->hexadecimal generated value
        return $hexOutput;
    }

    // String to length in Hex, String > StringLength > Hex
    private function strToHexLen($message) {

        // Length of the string (message)
        $length = strlen( $message );
        // Hex value of this string length
        $hex = dechex($length);

        // Length of the hex value
        $hexLength = strlen($hex);
        // If the hex strng length is lower dan 2
        if($hexLength < 2) {
            // Add a 0 (zero) before it
            $hex = '0'.$hex;
        }

        // Return the hex value in UPPERCASE characters
        return strtoupper($hex);
    }

}
?>