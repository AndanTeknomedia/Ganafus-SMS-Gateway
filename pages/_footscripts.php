<?php

/**
 * @author Toshiba
 * @copyright 2015
 */



?>
    <!-- Make this available on all pages -->
    <script>
    $(document).ready(function(){
        /**
         * Set this to show the number being SMSed:  
         * window.oldSMSNumber = '081...';
         * 
         * Set this to show the SMS being replied:  
         * window.oldSMSText = 'SMS being replied appear here...';
         */        
        window.sendSMS = function(targetNumber, smsText, callback /* (Boolean isError; String data)*/ )
        {
            var cb = callback;
            $.post('../gammu/ajax-send-sms.php',
			{
				notujuan: targetNumber,
				pesan: smsText,
                // use: 'CMD',
                use: 'SQL',
                r: Math.random()
			},
			function(data)
			{
				var result = data.substr(0,2);
                if (cb)
                {
                    cb((result.toUpperCase()=='ER'), data.substr(2));
                }				                    
			});
        };
        
        /**
         * Simulate Received SMS:
         */
        window.receiveSMS = function(senderNumber, smsText, callback /* (Boolean isError; String data)*/ )
        {
            var cb = callback;
            $.post('../gammu/ajax-sim-receive-sms.php',
			{
				nopengirim: senderNumber,
				pesan: smsText,
                r: Math.random()
			},
			function(data)
			{
				var result = data.substr(0,2);
                if (cb)
                {
                    cb((result.toUpperCase()=='ER'), data.substr(2));
                }				                    
			});
        };
        
        window.execBatalSMS = function(isSending)
        {
            window.dlgSendSMS.close(); return false;    
        };
        window.execKirimSMS = function(isSending)
        {
            var no = $('#send-sms-no-tujuan').val();
            var pesan = $('#send-sms-pesan').val();
            // alert(no+':'+pesan);
            window.dlgSendSMS.message = $(sendSMSWait);
            // $('#p-form-send-sms').html(sendSMSWait);
            // var uri = '<?php echo dirname(dirname(__FILE__)).'gammu/' ?>';
            if (isSending){
                window.sendSMS(no, pesan, function(er, data){
                    window.dlgSendSMS.close();
                    msgBox((er?'Error':'Sukses'), data);
                });	
            }
            else
            {
                window.receiveSMS(no, pesan, function(er, data){
                    // window.dlgSendSMS.close(); // for debug sake... :-(
                    msgBox((er?'Error':'Sukses'), data);
                });    
            }
            return false;
        };
        window.sendSMSWait = 
                    '<p style="text-align:center;">'+
                    ' <img src="img/ajax-loaders/ajax-loader-7.gif" title="img/ajax-loaders/ajax-loader-7.gif">'+
                    '<br>Sedang memproses...'+
                    '</p>';
        window.sendSMSForm = function(isSending /*true: send, false: receive */) {
                    return '<p id="p-form-send-sms">'+
                    '<div class="row"><div class="col-md-12">'+
                    '<label>'+(isSending?'No. Tujuan':'No. Pengirim')+' (Indonesian Only) :</label>'+
                    '<input class="form-control" placeholder="08xx..." id="send-sms-no-tujuan" name="send-sms-no-tujuan" type="text" '+
                    (
                        isSending && window.oldSMSNumber && (window.oldSMSNumber!='') ?
                        ' value="'+window.oldSMSNumber+'" '
                        : 
                        ''
                    )+
                    ' autofocus="">'+
                    '<br></div></div>'+
                    '<div class="row"><div class="col-md-12">'+
                    '<label>Pesan:</label>'+
                    '<textarea id="send-sms-pesan" name="send-sms-pesan" class="form-control" rows="3" maxlength="400"></textarea>'+
                    '<br></div></div>'+
                    (
                        isSending && window.oldSMSText && (window.oldSMSText != '') ?
                        '<div class="row"><div class="col-md-12">'+
                        '<div id="old-sms-box" class="alert alert-danger alert-dismissable">'+
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
                        '<span><strong>Balasan SMS untuk:</strong><br>'+window.oldSMSText+'</span></div></div></div>'
                        :
                        '' 
                    )+
                    '<div class="row"><div class="col-md-12"><br>'+
                    '<span class="pull-left"><a href="#" id="send-sms-kirim" onclick="javascript:window.execKirimSMS('+isSending+');" class="btn btn-sm btn-success btn-block">'+
                    (isSending ? 'Kirim':'Terima')+
                    '</a></span>'+
                    '<span class="pull-right"><a href="#" id="send-sms-close" onclick="javascript:window.execBatalSMS('+isSending+');" class="btn btn-sm btn-warning btn-block">Tutup</a></span>'+
                    '</div></div>'+
                    +'</p>';
        };
        $('.send-recv-sms').click(function(e){
            e.preventDefault();
            var tipe = $(this).attr('jenis');
            var dlgSendSMS = BootstrapDialog.show({
                size    : BootstrapDialog.SIZE_SMALL,
                title   : (tipe=='kirim'?'Kirim SMS':'SIMULASI: Terima SMS'),
                closable: true,
                draggable: true,
                message : $(sendSMSForm(tipe=='kirim'))
            });      
            window.dlgSendSMS = dlgSendSMS;
            dlgSendSMS.open();
            return false;
        });           
    });
    </script>
    
    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Bootstrap Dialog -->
    <script src="../bower_components/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <!-- Datepicker by Stefan Petre -->
    <script src="../non_bower_components/datepicker/js/bootstrap-datepicker.js"></script>

    <?php if (!$skip_morris) { ?>
    <!-- Morris Charts JavaScript -->
    <script src="../bower_components/raphael/raphael-min.js"></script>
    <script src="../bower_components/morrisjs/morris.min.js"></script>
    <script src="../js/morris-data.js"></script>
    <?php }?>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>