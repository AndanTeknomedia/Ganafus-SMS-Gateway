



sc create PHP binPath= "service.exe \"F:\xampp\php\php-cgi.exe -b 127.0.0.1:9000 -c F:\xampp\php\php.ini\"" type= own start= auto error= ignore DisplayName= PHP


sc start PHP	// to start PHP service.

sc delete PHP	// to delete PHP service

sc stop PHP 	// to stop the service





Note: please change Path as per your PHP installation Directory.