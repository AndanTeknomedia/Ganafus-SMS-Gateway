<?php
define(KW_TEST, 'TEST');
define(KW_INFO, 'INFO');
define(KW_PINJAM, 'PINJAM');
define(KW_MONITOR, 'MONITOR');
define(KW_KEMBALI, 'KEMBALI');

define('FORMAT_SMS_PINJAM',sms_create_format(KW_PINJAM, array(
    'NAMA_BAYI',
    'TGL_LAHIR',
    'TGL_PULANG_RS',    
    'CM_PJGLAHIR',  
    'KG_BERATLAHIR',      
    sms_create_options(array('SEHAT','SAKIT')),
    'NAMA_RS',
    'NM_DOKTER/BIDAN',
    'NO_KK',
    'ALAMAT',
    'NAMA_IBU',
    'NAMA_AYAH'
)));
define('FORMAT_SMS_KEMBALI',sms_create_format(KW_KEMBALI, array('KODEPINJAM','CM_PANJANGBAYI','KG_BERATBAYI',sms_create_options(array('SEHAT','SAKIT')))));
define('FORMAT_SMS_MONITOR',sms_create_format(KW_MONITOR, array('KODEPINJAM','CM_PANJANGBAYI','KG_BERATBAYI',sms_create_options(array('SEHAT','SAKIT')))));
define('FORMAT_SMS_INFO',sms_create_format(KW_INFO,array(sms_create_options(array(KW_PINJAM, KW_KEMBALI, KW_MONITOR)))));
// CONTOH SMS
define('CONTOH_SMS_PINJAM',sms_create_format(KW_PINJAM, array(
    'DIAN KHAMSAWARNI',
    '21/09/2015',
    '23/09/2015',
    '28',  
    '3,2',          
    'SEHAT',
    'RSU Wahidin',
    'Dr. Marhamah, Sp.OG',
    '9288299288',
    'BTN Hamzy E8/A',
    'RINA MAWARNI',
    'ARIFIN ADINEGORO'
)));
define('CONTOH_SMS_KEMBALI',sms_create_format(KW_KEMBALI, array('F8F4902B','31','3,5','SEHAT')));
define('CONTOH_SMS_MONITOR',sms_create_format(KW_MONITOR, array('F8F4902B','31','3,5','SAKIT')));
// define PINJAM as sample SMS for keyword INFO:
define('CONTOH_SMS_INFO',sms_create_format(KW_INFO,array(KW_PINJAM)));
?>