<?php
$page_name = 'Pooling SMS Manager';
include_once('../cores/definition.php'); 
$ajax = post_var('ajax');
$req_type = post_var('reqtype');
if (!$ajax) {
    if (USE_GAMMU){
        require_once('../gammu/gammu-cores.php');
        if (!is_gammu_ok())
        {
            header('location:setup-gammu.php');
            
        }
    }
}
require_once('../cores/db.php'); 
include_once('../cores/session.php');
include_once('../gammu/gammu-fetch-sms.php');
// require_login('post.php?show/newest');

/**
 * If this page is is being loaded using Ajax call,
 * fetch the requested data and skip the rest of the page:
 */

if ($ajax && ($req_type!=NULL))
{    
    switch (strtolower($req_type))
    {
        case 'gettemplate':
            $req_kw = post_var('reqkw');
            $req_desc = post_var('reqdesc');
            $req_format = post_var('reqformat');
            $req_sample = post_var('reqsample');
            $req_reply = strtolower(post_var('reqaction')) == 'true';
            if ($req_kw==NULL)
            {
                echo 'ERInvalid Parameter.';
            }
            else
            {                
                $tpl_file = realpath('../gammu/sms-keyword-hook-templates.txt');
                if (!file_exists($tpl_file))
                {
                    echo 'ERTemplate file does not exist. You have to reinstall '.SP_APP_NAME_SHORT;
                }
                else
                {
                    $tpl = file($tpl_file);
                    echo 'OK';
                    $tpl_str = implode("",$tpl);
                    unset($tpl);
                    $tpl_str = str_replace(
                        array(
                            '%KEYWORD%_description_str',
                            '%KEYWORD%_format_str',
                            '%KEYWORD%_sample_str',
                            '%keyword%_action',
                            '%keyword%', 
                            '%KEYWORD%'
                        ),
                        array(
                            $req_desc, 
                            $req_format, 
                            $req_sample, 
                            ($req_reply ? "return sms_send(".'$params'."['sender'], 'Your SMS has been processed.', ".'$nama_modem'.")": "return true"),
                            strtolower($req_kw), 
                            strtoupper($req_kw)
                        ),
                        $tpl_str
                    );
                    echo $tpl_str;
                }
            }
            break;
        case 'savetemplate':
            break;
        default:
            echo 'ERInvalid Parameter.';
    }
    exit();
}

require_login();

$skip_morris = true;
include "_head.php";
?>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <?php include '_logoarea.php'; ?>

            <?php include '_topnavs.php' ?>

            <?php include '_leftnavs.php' ?>
            
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $page_name; ?> 
                        
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-database fa-fw"></i> Daftar Keyword Pooling SMS
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="new-keyword btn btn-default btn-xs first">
                                        <i class="fa fa-tag"></i> Buat Keyword Baru                                        
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <ul class="chat" id="data-container"> 
                                <li class="left clearfix hide" id="keyword-editor">
                                    <div class="panel panel-default" style="border: 0;">
                                        <div class="panel-heading">
                                            <strong>Tambahkan Keyword Baru</strong>                                            
                                        </div> 
                                        <!-- /.panel-heading -->
                                        <div class="panel-body" id="input-container">                                            
                                            <div class="col-lg-12" id="kw-inputs">
                                                <!-- Keyword -->
                                                <div class="row" style="padding-bottom: 6px;" id="row-kw-keyword">
                                                    <div class="col-sm-2">Keyword</div>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-tag fa-fw"></i></span>
                                                            <input class="form-control input-sm" placeholder="MYKEYWORD" id="kw-keyword" type="text" autofocus="" maxlength="30">
                                                        </div>    
                                                    </div>
                                                </div>
                                                <!-- Description -->
                                                <div class="row" style="padding-bottom: 6px;"  >
                                                    <div class="col-sm-2">Keterangan</div>
                                                    <div class="col-sm-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-lightbulb-o fa-fw"></i></span>
                                                            <textarea style="height: 40px;" class="form-control input-sm" placeholder="Keterangan" id="kw-description" ></textarea>
                                                        </div>     
                                                    </div>
                                                </div>
                                                <!-- Format -->
                                                <div class="row" style="padding-bottom: 6px;" >
                                                    <div class="col-sm-2">Format SMS</div>
                                                    <div class="col-sm-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-code fa-fw"></i></span>
                                                            <textarea style="height: 40px;" class="form-control input-sm" placeholder="Contoh: MYKEYWORD<?php echo DELIMITER; ?>YOURNAME<?php echo DELIMITER; ?>BIRTHDAY" id="kw-format" ></textarea>
                                                        </div>     
                                                    </div>
                                                    <div class="col-sm-4 small">
                                                        Delimiter SMS saat ini: <span class="label label-info"><strong><?php echo DELIMITER; ?></strong></span>
                                                    </div>
                                                </div>    
                                                <!-- Sample -->
                                                <div class="row" style="padding-bottom: 6px;" >
                                                    <div class="col-sm-2">Contoh SMS</div>
                                                    <div class="col-sm-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                            <textarea style="height: 40px;" class="form-control input-sm" placeholder="Contoh: MYKEYWORD<?php echo DELIMITER; ?>JohnDoe<?php echo DELIMITER; ?>31/12/1999" id="kw-sample" ></textarea>
                                                        </div>     
                                                    </div>
                                                    <div class="col-sm-4 small">
                                                        Delimiter SMS saat ini: <span class="label label-info"><strong><?php echo DELIMITER; ?></strong></span>
                                                    </div>
                                                </div>    
                                                <!-- Default Action -->   
                                                <div class="row" style="padding-bottom: 6px;" >
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-6">
                                                        <label class="checkbox-inline"><input type="checkbox" id="kw-reply-sms" checked=""> Otomatis Balas SMS</label>                                                             
                                                    </div>                                                    
                                                </div>
                                                
                                                <div class="row" style="padding-bottom: 6px;" >
                                                    <div class="col-sm-12 small">
                                                        <hr>
                                                        Klik Generate untuk membuat <strong>hook template</strong> baru bagi keyword ini - berupa file PHP. Semua SMS dengan keyword di
                                                        ini akan diarahkan untuk diproses oleh file tersebut.
                                                    </div>
                                                </div>                                                                                      
                                                <!-- Generate Hook Template -->
                                                <div class="row" style="padding-bottom: 6px;" >                                                    
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-success btn-sm" id="generate-hook-template">Generate <i class="fa fa-chevron-right"></i></button>    
                                                    </div>
                                                </div>
                                                <div class="row" style="padding-bottom: 6px;" id="row-hook-template">
                                                    <div class="col-sm-12 small">                                                        
                                                        You&apos;d better copy this code to your PHP editor, edit it, and paste it back here.
                                                        <strong class="label label-warning">Warning!</strong> Any error here can break the whole system!
                                                        <hr> 
                                                        <textarea style="height: auto;" class="form-control input-sm" placeholder="Generated" id="kw-hook-template" ></textarea>                                                         
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <!-- /.panel-body -->
                                        <!-- Add fields -->
                                        <div class="panel-footer">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <input type="hidden" value="new" id="keyword-edit-mode">
                                                    <button type="button" class="btn btn-primary btn-sm" id="save-keyword"><i class="fa fa-save"></i> Save</button>
                                                    <button type="button" class="btn btn-danger btn-sm" id="cancel-keyword">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php
                                $keywords = fetch_query("select * from sms_keywords order by id asc");
                                $c = count($keywords);
                                if ($c==0)
                                {
                                ?>
                                <li class="left clearfix">
                                    <span class="chat-img pull-left">
                                        <img id="img-ajax" src="img/front-end/sms-keyword.jpg" class="img-circle" />
                                    </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <strong class="primary-font">Keyword Pooling SMS belum diatur.</strong>                                            
                                        </div>
                                        <p>
                                            Klik tombol Buat Baru untuk membuat keyword baru.
                                        </p>
                                    </div>
                                </li>
                                <?php
                                }
                                else
                                {
                                    foreach($keywords as $i=>$key) {
                                ?>                    
                                <li class="left clearfix keyword" id="<?php echo $key['id']; ?>">
                                    <span class="chat-img pull-left">
                                        <img id="img-ajax" src="img/front-end/sms-keyword.jpg" class="img-circle" />
                                    </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <strong class="primary-font"><?php echo $key['keyword']; ?></strong>
                                            <small class="pull-right text-muted">
                                                <a href="#" class="label label-success button-test" data-id="<?php echo $key['id']; ?>"><i class="fa fa-chevron-right fa-fw"></i> Test SMS</a>
                                                <!--
                                                <a href="#" class="label label-warning button-edit" data-id="<?php echo $key['id']; ?>"><i class="fa fa-pencil fa-fw"></i> Edit</a>
                                                -->
                                                <a href="#" class="label label-danger button-drop" data-id="<?php echo $key['id']; ?>"><i class="fa fa-trash-o fa-fw"></i> Drop</a>
                                            </small>
                                        </div>
                                        <p><small><strong class="label label-success">KETERANGAN:</strong> <?php echo htmlentities($key['description']); ?></small></p>
                                        <p><small><strong class="label label-info">FORMAT SMS:</strong> <?php echo htmlentities($key['format_sms']); ?></small></p>
                                        <p><small><strong class="label label-info">CONTOH SMS:</strong> <?php echo htmlentities($key['contoh_sms']); ?></small></p>
                                    </div>
                                </li>
                                <?php 
                                    }
                                }
                                unset($keywords);
                                ?>
                            </ul>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            &nbsp;
                            <div class="clearfix"></div>
                        </div>
                        <!-- /.panel-footer -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<script>
var smsDelimiter    = '<?php echo DELIMITER; ?>';
var uri             = '<?php echo $_SERVER['PHP_SELF'];?>';
var kwTester        = /^[a-z0-9]+$/i /* /^[a-z0-9\-\s]+$/i */;
function getKwParams()
{
    return {
        kwKeyword: $('#kw-keyword').val(), 
        kwDesc: $('#kw-description').val(), 
        kwFormat: $('#kw-format').val(), 
        kwSample: $('#kw-sample').val(),
        kwDefaultAction: $('#kw-reply-sms').is(':checked')
    };
}
$(document).ready(function(){    
    var editMode = $('#keyword-edit-mode').val();
    $('.new-keyword').click(function(e){
        $('#keyword-editor').removeClass('hide');
    });
    $('#cancel-keyword').click(function(e){
        $('#keyword-editor').addClass('hide');
    });
    $('#generate-hook-template').click(function(e){
        e.preventDefault();
        // $('#row-kw-keyword').removeClass('has-error');
        var kwParam = getKwParams();
        if (kwParam.kwKeyword=='')
        {
            // $('#row-kw-keyword').addClass('has-error');
            msgBox('Error','Keyword tidak boleh kosong.');                
        }
        else
        if (!kwTester.test(kwParam.kwKeyword))
        {
            msgBox('Error','Keyword hanya boleh berisi huruf dan angka.');     
        }
        else
        {
            $.post(uri, {
                ajax:true, 
                r: Math.random(), 
                reqtype: 'gettemplate',
                reqkw: kwParam.kwKeyword.toLowerCase(),
                reqdesc: kwParam.kwDesc,
                reqformat: kwParam.kwFormat,
                reqsample: kwParam.kwSample,
                reqaction: kwParam.kwDefaultAction 
            },
            function(data){
                if (data.substr(0,2).toUpperCase()=='OK')
                {
                    var $hookTpl = $('#kw-hook-template');
                    $('#row-hook-template').removeClass('hide');
                    var jml = data.substr(2).split("\n").length;
                    var lht = String($hookTpl.css('line-height')).replace('px','');
                    $hookTpl.text(data.substr(2))
                        .css('overflow','hidden')
                        .height(0)
                        .height(lht*jml);
                }
                else
                {
                    msgBox('Error','Gagal membuat hook template:<br>'+data.substr(2));
                }     
            });
        }
        return false;
    });
    
    $('#save-keyword').click(function(e){
        e.preventDefault();
        alert(editMode); 
        return false;
    });    
});
</script>   
<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>