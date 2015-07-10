<?php

/*
$telephone = '447941222222'; //International format
$message = 'test message';
$expiry_sec = 60 * 60 * 24 * 7;
$sms_pdu_encode = new sms_pdu_encode();
echo $sms_pdu_encode->encode($telephone, $message, $expiry_sec);
*/

class sms_pdu_encode {

	private function first_octet() {

		//http://web.archive.org/web/20120714162835/http://dreamfabric.com/sms/submit_fo.html

		$tp_rp = '0'; //Reply Path
		$tp_udhi = '0'; //User Data Header Indicator
		$tp_srr = '0'; //Status Request Report
		$tp_vpf = '10'; //Validity Period Format
		$tp_rj = '0'; //Reject Duplicates
		$tp_mti = '01'; //Message Type Indicator (SMS-SUBMIT)

		$first_octet_dec = bindec($tp_rp . $tp_udhi . $tp_srr . $tp_vpf . $tp_rj . $tp_mti);
		$first_octet = $this->dec2hex($first_octet_dec);

		return $first_octet;

	}

	private function destination_address($telephone) {

		//Address Length (Phone number length)
		$telephone_len = strlen($telephone);
		$address_length = $this->dec2hex($telephone_len);

		$type_of_address = '91'; //Type of Address (Type of phone number - International)

		//Phone Number
		$phone_number = '';
		$telephone_chunks = str_split($telephone, 2);
		foreach ($telephone_chunks as $chunk) {
			$chunk_rev = strrev($chunk);
			$phone_number .= str_pad($chunk_rev, 2, 'F', STR_PAD_LEFT);
		}

		$destination_address = $address_length . $type_of_address . $phone_number;

		return $destination_address;

	}

	private function data_coding_scheme() {

		//http://web.archive.org/web/20120714175243/http://dreamfabric.com/sms/dcs.html

		$coding_group = '00';
		$compression = '0';
		$class_meaning = '1';
		$alphabet = '01'; //8 Bit Data
		$class = '01'; //Class 1 - ME Specific

		$data_coding_scheme_dec = bindec($coding_group . $compression . $class_meaning . $alphabet . $class);
		$data_coding_scheme = $this->dec2hex($data_coding_scheme_dec);

		return $data_coding_scheme;

	}

	private function validity_period($expiry_sec=false) {

		//http://web.archive.org/web/20120714172416/http://dreamfabric.com/sms/vp.html

		//Minimum expiry period (i.e. rounds up for periods that can not be convered exactly)

		//Default 7 days
		if ($expiry_sec === false) {
			$expiry_sec = 60 * 60 * 24 * 7;
		}

		if ($expiry_sec < (60 * 5)) {
			throw new Exception("Expiry must be at least 300 seconds (5 minutes)");
		}

		if ($expiry_sec <= 60 * 60 * 12) { //5 min - 12hr [5 min]
			$validity_period_dec = ($expiry_sec / (60 * 5)) - 1;
		} else if ($expiry_sec <= 60 * 60 * 24) { //12+hr - 24hr [30 min]
			$validity_period_dec = ( ($expiry_sec - (60 * 60 * 12)) / (60 * 30) ) + 143;
		} else if ($expiry_sec <= 60 * 60 * 24 * 30) { //24+hr - 30 day [1 day]
			$validity_period_dec = ($expiry_sec / (60 * 60 * 24) ) + 166;
		} else if ($expiry_sec <= 60 * 60 * 24 * 7 * 63) { //30+day - 63 week [1 week]
			$validity_period_dec = ($expiry_sec / (60 * 60 * 24 * 7) ) + 192;
		} else {
			throw new Exception("Expiry must be 38102400 seconds (63 weeks) or less");
		}

		$validity_period_dec = ceil($validity_period_dec);

		$tp_vp = $this->dec2hex($validity_period_dec);

		return $tp_vp;

	}

	private function user_data_length($message) {

		$message_length = strlen($message);
		$tp_udl = $this->dec2hex($message_length);

		return $tp_udl;

	}

	private function user_data($message) {

		$user_data = '';
		$message_chars = str_split($message, 1);
		foreach ($message_chars as $char) {
			$decimal_char = ord($char);
			$user_data .= $this->dec2hex($decimal_char);
		}

		return $user_data;

	}

	public function encode($telephone, $message, $expiry_sec=false) {

		//http://web.archive.org/web/20121115163620/http://dreamfabric.com/sms/
		//http://archive.sierrawireless.com/resources/AirPrime/GT47-GT48/Application_Notes/Gx4x%20App%20note%20Construction%20of%20SMS%20PDUs.pdf
		//http://www.neurophys.wisc.edu/comp/docs/ascii/

		if (!preg_match("/^\d{2,15}$/", $telephone)) {
			throw new Exception('Telephone not valid');
		}

		if (!$message) {
			throw new Exception('Message not specified');
		}

		if (strlen($message) > 140) {
			throw new Exception('8-bit GSM alphabet supports a maximum of 140 characters');
		}

		$sca = '00'; //Service Center Address
		$first_octet = $this->first_octet(); //First Octet
		$tp_mr = '00'; //Message Reference (Phone Specified)
		$tp_da = $this->destination_address($telephone); //Destination Address
		$tp_pid = '00'; //Protocol Identifier (SMS)
		$tp_dcs = $this->data_coding_scheme();
		$tp_vp = $this->validity_period($expiry_sec);
		$tp_udl = $this->user_data_length($message);
		$tp_ud = $this->user_data($message);

		$pdu = $sca . $first_octet . $tp_mr . $tp_da . $tp_pid . $tp_dcs . $tp_vp . $tp_udl . $tp_ud;

		return $pdu;

	}


	private function dec2hex($decimal) {

		$hex = dechex($decimal);
		$hex = str_pad($hex, 2, 0, STR_PAD_LEFT);
		$hex = strtoupper($hex);

		return $hex;

	}

}

?>