<?php

include_once('../cores/definition.php'); 
include_once('../cores/session.php');
$on_login_page = true;
include_once('../cores/session.php');

if (user_logged_in())
{
	header('location:index.php');	
}

?>

<?php
$skip_morris = true;
include "_head.php";
?>


<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <a class="btn btn-info btn-xs" href="../front-end">
                            <i class="fa fa-fw fa-home"></i>
                        </a>
                        <span class="panel-title"><strong><?php echo SP_APP_NAME_SHORT .' v'. SP_APP_VERSION . PHP_EOL; ?></strong> - Sign In</span>
                    </div>
                    <div class="panel-body">
                        <div id="error-box" class="alert alert-danger alert-dismissable"  style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                &times;
                            </button>
                            <span></span>
                            <!-- <a href="#" class="alert-link">Alert Link</a>. -->
                        </div>
                        <form role="form">
                            <fieldset  style="padding-left: 20px; padding-right: 20px;">
                                <div class="clearfix">&nbsp;</div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-user" style="width:12px;"></i></span>
                                    <input class="form-control" placeholder="Username" id="username" name="username" type="text" autofocus>
                                </div>
                                
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock" style="width:12px;"></i></span>
                                    <input class="form-control" placeholder="Password" id="password" name="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="remember" name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <!-- Change this to a button or input when using this as a form -->
                                <a id="login-submit" href="#" class="btn btn-lg btn-success btn-block">Login</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function(e) {
            $('input.form-control').keypress(function(e){
                if (e.which == 13)
                {
                    e.preventDefault();
                    $("#login-submit").click();
                }  
                         
            });
            // setCookie('inkubatorlogin', '{user:'+uid+', kunci:'+pwd+', ingat:'+$('#remember').prop('checked'));
            var c = getCookie('inkubatorlogin');
            if (c!='')
            {
                data = JSON.parse(c);
                $('#username').val(data.user);
				$('#password').val(data.kunci);
                $('#remember').prop('checked', data.ingat);
                
            }
        	$("#login-submit").click(function(e) {
				e.preventDefault();
                
                var dialog = BootstrapDialog.show({
                    size: BootstrapDialog.SIZE_SMALL,
                    title: 'Tunggu sebentar...',
                    message: '<p style="text-align:center;">'+
                	         ' <img src="img/ajax-loaders/ajax-loader-7.gif" title="img/ajax-loaders/ajax-loader-7.gif">'+
                             '<br>Sedang menghubungi server...'+
				             '</p>',
                    // closable: false,
                    draggable: true
                });
                dialog.open();
                /*
                setTimeout(function(){
                    dialog.close();
                }, 5000);
                */
				var uid = String($('#username').val());
				var pwd = String($('#password').val());
                
                if ($('#remember').prop('checked'))
                {
                    setCookie('inkubatorlogin', JSON.stringify({user:uid, kunci:pwd, ingat:$('#remember').prop('checked')}), 100);
                }
                else
                {
                    setCookie('inkubatorlogin', '', 100);
                }
				$.post('../cores/ajax-login.php',
				{
					username: uid,
					password: pwd
				},
				function(data)
				{
					dialog.close();
                    if (data == 'OK')
					{
						$('#username').val('');
						$('#password').val('');
						// redirect	
						document.location = 'index.php';
					}
					else
					{
						$("#error-box span").html(data);
						$("#error-box").show('slow');
					}
                    
				});													
			});
        });
    </script>    

<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>
