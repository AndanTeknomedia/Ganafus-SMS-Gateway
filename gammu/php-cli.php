<?php
echo 'not for public access.';
/*
[PHP]
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
; PHP PHP 5.4.39 CLI  php-cli.ini    ;
; Uniform Server PHP CLI php-cli.ini ;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

extension=php_curl.dll
extension=php_mysql.dll
extension=php_mysqli.dll
extension=php_openssl.dll
extension=php_gd2.dll
extension=php_pdo_mysql.dll
extension=php_mbstring.dll

extension_dir = "./extensions"
; error_reporting = E_ALL | E_STRICT
error_reporting = E_ALL & ~E_NOTICE
; display_errors = On
date.timezone = "Europe/London"

sendmail_path = "${US_ROOTF}/core/msmtp/msmtp.exe --file=${US_ROOTF}/core/msmtp/msmtprc.ini  -t"

[COM_DOT_NET]
extension=php_com_dotnet.dll
*/
?>