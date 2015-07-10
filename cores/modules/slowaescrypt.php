<?php
require( 'aes_fast.php');
require( 'cryptoHelpers.php');

/*
 * Encrypts and decrypts plaintext with a given key.  Written to be compatible
 * with it's counterpart in js.  Uses AES.  Uses the slowAES encryption lib,
 * which is more than fast enough for our purposes; using it here because it
 * has several parallel versions in different languages (mainly php and js).
 *
 * Usage: Encrypt takes any string as the plaintext and any string as the key.
 *      Decrypt takes the output of encrypt and the same key used to encrypt.
 *
 * Details you might care about:
 *      The encryption output is really 3 space-seperated strings: 
 *      - The length of the original plaintext string as an integer
 *      - The Initialization Vector (iv).  This is just a random string that
 *        will be different each encryption, and can be sent in the clear
 *        with the ciphertext.  This is a hex string.
 *      - The ciphertext itself, as a hex string.
 *
 * Crypto details you won't care about unless you're setting up another set of
 * methods to match these ones:
 *      - AES (Rijndael, or very close, I think)
 *      - 256 bit key
 *      - 128 bit IV
 *      - CBC mode
 *
 **/
function encrypt( $plaintext, $key ){

    // Set up encryption parameters.
    $plaintext_utf8 = utf8_encode($plaintext);
    $inputData = cryptoHelpers::convertStringToByteArray($plaintext);
    $keyAsNumbers = cryptoHelpers::toNumbers(bin2hex($key));
    $keyLength = count($keyAsNumbers);
    $iv = cryptoHelpers::generateSharedKey(16);

    $encrypted = AES::encrypt(
        $inputData,
        AES::modeOfOperation_CBC,
        $keyAsNumbers,
        $keyLength,
        $iv
    );

    // Set up output format (space delimeted "plaintextsize iv cipher")
    $retVal = $encrypted['originalsize'] . " "
        . cryptoHelpers::toHex($iv) . " "
        . cryptoHelpers::toHex($encrypted['cipher']);

    return $retVal;
}

function decrypt( $input, $key ){

    // Split the input into its parts
    $cipherSplit = explode( " ", $input);
    $originalSize = intval($cipherSplit[0]);
    $iv = cryptoHelpers::toNumbers($cipherSplit[1]);
    $cipherText = $cipherSplit[2];

    // Set up encryption parameters
    $cipherIn = cryptoHelpers::toNumbers($cipherText);
    $keyAsNumbers = cryptoHelpers::toNumbers(bin2hex($key));
    $keyLength = count($keyAsNumbers);

    $decrypted = AES::decrypt(
        $cipherIn,
        $originalSize,
        AES::modeOfOperation_CBC,
        $keyAsNumbers,
        $keyLength,
        $iv
    );

    // Byte-array to text.
    $hexDecrypted = cryptoHelpers::toHex($decrypted);
    $retVal = pack("H*" , $hexDecrypted);

    return $retVal;
}

/**
 * Some simple testing code
 **/

/**
$plaintext = "Testing the php encryption/decryption. Unicode LOD: ?_?!";
$key = "multipass!";
$cipherText = encrypt($plaintext,$key);
$result = decrypt($cipherText,$key);
echo $plaintext.'<br>';
echo $cipherText.'<br>';
echo $result.'<br>';
*/
?>