<?php
$page_name = 'Plugin Manager';
include_once('../cores/definition.php'); 
$ajax = post_var('ajax');
$req_type = post_var('reqtype');
/*
if (!$ajax) {
    if (USE_GAMMU){
        require_once('../gammu/gammu-cores.php');
        if (!is_gammu_ok())
        {
            header('location:setup-gammu.php');
            
        }
    }
}
*/
require_once('../cores/db.php'); 
include_once('../cores/session.php');
include_once('../gammu/gammu-fetch-sms.php');
// require_login('post.php?show/newest');

/**
 * If this page is is being loaded using Ajax call,
 * fetch the requested data and skip the rest of the page:
 */

$default_list = '<li class="left clearfix">'.
                '<span class="chat-img pull-left">'.
                '    <img id="img-ajax" src="img/front-end/plugin-icon.png" class="img-circle" />'.
                '</span>'.
                '<div class="chat-body clearfix">'.
                '    <div class="header">'.
                '        <strong class="primary-font">Keyword Pooling SMS belum diatur.</strong>'.                                            
                '    </div>'.
                '    <p>'.
                '        Klik tombol Buat Baru untuk membuat keyword baru.'.
                '    </p>'.
                '</div>'.
                '</li>';
                
$process_next = false;
if ($ajax && ($req_type!=NULL))
{    
    switch (strtolower($req_type))
    {
        case 'gettemplate':
            $req_kw = post_var('reqkw');
            if (keyword_hook_registered($req_kw)!==false)
            {
                echo 'ERKeyword telah digunakan sebelumnya.<br>Silahkan gunakan keyword lain.';
            }
            else
            {
                $req_desc = post_var('reqdesc');
                $req_format = post_var('reqformat');
                $req_sample = post_var('reqsample');
                $req_reply = strtolower(post_var('reqaction')) == 'true';
                $req_kategori = ucfirst(strtolower(post_var('reqkategori','')));
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
                                '%KEYWORD%_kategori_str',
                                '%KEYWORD%_description_str',
                                '%KEYWORD%_format_str',
                                '%KEYWORD%_sample_str',
                                '%keyword%_action',
                                '%keyword%', 
                                '%KEYWORD%'
                            ),
                            array(
                                $req_kategori,
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
                        // echo 'OKss';
                    }
                }
            }
            break;
        case 'savetemplate':
            $req_file = post_var('reqfile','');
            $req_kw = post_var('reqkw');
            /*
            if (keyword_hook_registered($req_kw)!==false)
            {
                echo 'ERKeyword telah digunakan sebelumnya.<br>Silahkan gunakan keyword lain.';
            }
            else
            {
            */
                if (empty($req_file))
                {
                    echo 'ERHook Template kosong.';
                } 
                else
                {
                    $tpl_file = str_replace("\\","/", realpath('../sms-daemon-hooks')).'/hook-template-'.md5($req_kw).'.php';  
    				//echo $tpl_file;    				
                    try {
    					$tpf = fopen($tpl_file, 'w');
    					fputs($tpf, $req_file);
    					fclose($tpf);
    					echo 'OKFile hook template sudah dibuat.';
    				}
    				catch (Exception $e)
    				{
    					echo 'ERGagal membuat file hook template: '.$e->getMessage();
    				}
                    
                }
            /*
            }
            */
            break;
        case 'checkkeyword':
            $req_kw = post_var('reqkw');
            if (keyword_hook_registered($req_kw)!==false)
            {
                echo 'ERKeyword telah digunakan sebelumnya.<br>Silahkan gunakan keyword lain.';
            }
            else
            {
                echo 'OKKeyword dapat digunakan';
            }
            break;
        case 'changestate':
            $req_id = post_var('reqid');
            $req_state = post_var('reqstate');
            try
            {
                exec_query("update sms_keywords set active = (case when active='Y' then 'N' else 'Y' end) where id = '$req_id';");
                $state = fetch_one_value("select upper(active) from sms_keywords where id = '$req_id'");
                echo 'OK'.($state=='Y'?'active':'disabled');    
            }
            catch (Exception $e)
            {
               echo 'ER'.$req_state; 
            }
            break;
        case 'dropkeyword':
            $req_id = post_var('reqid');
            try
            {
                $kw_data = fetch_query("select keyword, function_name, file_name from sms_keywords where id = '$req_id'");
                /*
                $kw_file = fetch_one_value("select file_name from sms_keywords where id = '$req_id'");
                $kw_keyword = fetch_one_value("select keyword from sms_keywords where id = '$req_id'");
                */
                $kw_keyword = $kw_data[0]['keyword'];
                $kw_function = $kw_data[0]['function_name'];
                $kw_file = $kw_data[0]['file_name'];                
                $kw_file = str_replace("\\","/", realpath('../sms-daemon-hooks')).'/'.basename($kw_file);
                unset($kw_data);                
                if (file_exists($kw_file))
                {
                    unlink($kw_file);
                }
                // panassa'i / ensure / pastikan:
                $res = keyword_hook_unregister($kw_keyword, $kw_function); 
                if (
                    /* exec_query("delete from sms_keywords where id = '$req_id'") */
                    ($res == ERROR_KEYWORD_NOT_REGISTERED) || ($res == ERROR_KEYWORD_SUCCESS)                
                ) 
                {
                    echo 'OKKeyword telah dihapus';
                }
                else
                {
                    echo 'ERGagal menghapus keyword';
                }    
            }
            catch (Exception $e)
            {
               echo 'ERGagal menghapus keyword: '.$e->getMessage(); 
            }
            break;
        case 'fetch':        
            
            $kategori_keyword = post_var('currkat', '');
            // pre($kategori_keyword);
            $keywords = keyword_fetch_all($kategori_keyword);
            /*
            $fetch_kw_sql = "select * from sms_keywords ".(empty($kategori_keyword)?"":" where upper(kategori) = upper('$kategori_keyword') ")." order by id asc";
            $keywords = fetch_query($fetch_kw_sql);
            echo $fetch_kw_sql ;
            */
            $c = count($keywords);
            if ($c==0)
            {
                echo $default_list;    
            }
            else
            {
                foreach($keywords as $i=>$key) 
                {
            ?>                    
            <li class="left clearfix keyword" id="<?php echo $key['id']; ?>">
                <span class="chat-img pull-left">
                    <img id="img-ajax" src="img/front-end/plugin-icon.png" class="img-circle" />
                </span>
                <div class="chat-body clearfix">
                    <div class="header">
                        <strong class="primary-font"><?php echo $key['keyword']; ?></strong>
                        <small class="pull-right text-muted">
                            <a href="#" class="label label-success button-test" data-id="<?php echo $key['id']; ?>" data-sms="<?php echo $key['contoh_sms']; ?>"><i class="fa fa-chevron-right fa-fw"></i> Test SMS</a>
                            <a href="#" class="label label-<?php echo ($key['active']=='Y'?'success':'warning') ?> button-edit-keyword-state" data-id="<?php echo $key['id']; ?>" data-state="<?php echo ($key['active']=='Y'?'active':'disabled') ?>"><i class="fa fa-<?php echo ($key['active']=='Y'?'check':'times') ?> fa-fw" id="fa-state-<?php echo $key['id']; ?>"></i> <?php echo ($key['active']=='Y'?'Active':'Disabled') ?></a>
							<a href="#" class="label label-danger button-drop-keyword" data-id="<?php echo $key['id']; ?>"><i class="fa fa-trash-o fa-fw"></i> Drop</a>
                        </small>
                    </div>
                    <p><small><strong class="label label-primary">KATEGORI:</strong> <?php echo htmlentities($key['kategori']); ?></small></p>
                    <p><small><strong class="label label-success">KETERANGAN:</strong> <?php echo htmlentities($key['description']); ?></small></p>
                    <p><small><strong class="label label-info">FORMAT SMS:</strong> <?php echo htmlentities($key['sms_format']); ?></small></p>
                    <p><small><strong class="label label-info">CONTOH SMS:</strong> <?php echo htmlentities($key['sms_sample']); ?></small></p>
                </div>
            </li>            
            <?php 
                }
            }
            ?>
            <script type="text/javascript">
            /**
             * ********* Need to be laded by ajax *************
             */
             
            /**
             * Change keyword state:
             */             
            $('.button-edit-keyword-state').click(function(e){
                e.preventDefault();
                var el = $(this);
                var elId = $(this).attr('data-id');
                var elState = $(el).attr('data-state');
                
                $.post(uri, {
                    ajax:true, 
                    r: Math.random(), 
                    reqtype: 'changestate',
                    reqid: $(el).attr('data-id'),
                    reqstate: elState
                },
                function(data){
                    if (data.substr(0,2).toUpperCase()=='OK')
                    {
                        var newState = data.substr(2).toLowerCase();
                        // alert(newState); 
                        $(el)
                            .removeClass('label-warning')
                            .removeClass('label-success')
                            .addClass(newState=='active'?'label-success':'label-warning')
                            .attr('data-state',newState)
                            .html('<i class="fa fa-'+(newState=='active'?'check':'times')+' fa-fw" id="fa-state-'+elId+'"></i>'+newState.substr(0,1).toUpperCase()+newState.substr(1));
                    }
                    else
                    {
                        msgBox('Error','Gagal Mengupdate status keyword.');
                    }     
                });    
                return false;
            });
            
            /**
             * Drop keyword:
             */
            $('.button-drop-keyword').click(function(e){
                e.preventDefault();
                var el = $(this);
                var elId = $(this).attr('data-id');
                msgConfirm('Hapus Keyword','Anda yakin akan menghapus keyword ini?', 'Ya, lanjut','Batalkan', function(jawab){
                    if (jawab)
                    {
                        $.post(uri, {
                            ajax:true, 
                            r: Math.random(), 
                            reqtype: 'dropkeyword',
                            reqid: $(el).attr('data-id')
                        },
                        function(data){
                            if (data.substr(0,2).toUpperCase()=='OK')
                            {
                                location.reload();                        
                            }
                            else
                            {
                                msgBox('Error','Gagal menghapus keyword.');
                            }
                        });     
                    }
                });    
                return false;
            }); 
             
            /**
             * Test SMS
             */
            $('.button-test').click(function(e){
                e.preventDefault();        
                window.oldSMSNumber = '';
                window.oldSMSText = '';
                window.defaultSMSText = $(this).attr('data-sms');
                var dlgSendSMS = BootstrapDialog.show({
                    size    : BootstrapDialog.SIZE_SMALL,
                    title   : ('Test SMS'),
                    closable: true,
                    draggable: true,
                    message : $(sendSMSForm(false))
                });      
                window.dlgSendSMS = dlgSendSMS;
                dlgSendSMS.open();
                return false;
            }); 
            /**
             * ********************************************************************
             */
            </script>
            <?php
            unset($keywords);
            break;
        default:
            echo 'ERInvalid Parameter.';
    }
    exit();
}

require_login();
require_admin('index.php','Pooling SMS Settings', 'Anda tidak memiliki hak akses ke halaman ini.', 'Dashboard');

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
                            <i class="fa fa-sliders fa-fw"></i> Daftar Plugin
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="new-keyword btn btn-default btn-xs first">
                                        <i class="fa fa-file-text"></i> Pasang Plugin...                                        
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Warnings -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">                                
                                        <div class="col-lg-12">
                                            <div class="alert alert-warning">
                                                Sebelum mengedit file-file Hooking Template, pastikan Anda telah mematikan keywordnya terlebih dulu.
                                                <small><span class="label label-success"><i class="fa fa-check fa-fw"></i> Active</a></small>
                                                menjadi <small><span class="label label-warning"><i class="fa fa-times fa-fw"></i> Disabled</a></small>
                                                <br />
                                                <span class="text-danger">Jika tidak, Anda dapat <strong>merusak sistem!</strong></span>
                                                <br />
                                                SMS dengan keyword <small><span class="label label-warning"><i class="fa fa-times fa-fw"></i> Disabled</a></small> tidak akan diproses,
                                                tapi ditandai dengan status <strong>Dibalas</strong>.
                                            </div>         
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Keyword List: -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <!-- Kategori -->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="btn-group pull-left">
                                                <?php 
                                                $kats = keyword_fetch_kategori();       
                                                $kats_kosong = count($kats)==0;
                                                if ($kats_kosong) { ?>
                                                <a href="#" class="btn btn-sm btn-primary new-keyword"><span class="fa fa-pencil"></span> Keyword Baru</a>
                                                <?php
                                                }
                                                else
                                                {         
                                                    echo '<a href="#" class="btn disabled btn-success btn-sm"><strong>Kategori</strong></a>';
                                                    echo '<div class="btn-group">';
                                                    echo '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>';                                                    
                                                    echo '<ul class="dropdown-menu pull-left" role="menu">';                                                
                                                    foreach ($kats as $kat)
                                                    {
                                                        echo '<li>';
                                                        // echo '<a href="'.$_SERVER['PHP_SELF'].'?kwkat='.urlencode(strtolower($kat)).'">';
                                                        echo '<a href="#" data-category="'.$kat.'" class="filter-by-kategori">';
                                                        echo ucfirst(strtolower($kat)).'</a>';
                                                        echo '</li>';
                                                    }                                                    
                                                    echo '</ul>';
                                                    echo '</div>';                                                                                       
                                                }
                                                ?>                                    
                                            </div>                                                                                  
                                        </div>
                                    </div>
                                    <!-- List -->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <hr />
                                            <ul class="chat" id="data-container">
                                                <?php                                        
                                                echo $default_list;                                        
                                                ?>
                                            </ul>                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Keyword Editor -->
                            <div class="row hide" id="keyword-editor">
                                <div class="col-lg-12">
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
                                                    <div class="col-sm-6 small">
                                                        <strong>HURUF dan ANGKA SAJA</strong> tanpa spasi dan tanda baca.
                                                    </div>
                                                </div>
                                                <!-- Kategori -->
                                                <div class="row" style="padding-bottom: 6px;" id="row-kw-kategori">
                                                    <div class="col-sm-2">Kategori</div>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-folder-o fa-fw"></i></span>
                                                            <input class="form-control input-sm" placeholder="Kategori" id="kw-kategori" type="text" value="<?php echo SP_APP_NAME_SHORT; ?>" maxlength="50">
                                                        </div>    
                                                    </div>
                                                    <div class="col-sm-6 small">
                                                        <strong>HURUF dan ANGKA SAJA</strong> tanpa spasi dan tanda baca.
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
                                                <div class="row" style="padding-bottom: 6px;" id="">
                                                    <div class="col-sm-12 small">                                                        
                                                        <div class="pull-left">
                                                            You&apos;d better copy this code to your PHP editor, edit it, and paste it back here.
                                                            <strong class="label label-warning">Warning!</strong> Any error here can break the whole system!
                                                        </div>
                                                        <div class="pull-right">
                                                            <a href="#"  class="btn btn-default btn-xs" id="kw-check-syntax"><i class="fa fa-check "></i> Check Syntax</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="padding-bottom: 6px;" id="row-hook-template">
                                                    <div class="col-sm-12 small"> 
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
                                                    <button type="button" class="btn btn-danger btn-sm" id="cancel-keyword">Close</button>
                    								<span class="small">Jika file sudah ada, menekan Save akan menimpa file lama.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->                        
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
var currentKeyword  = '';
var currentKategori = '';
function keywordValid(keyword)
{
    return (keyword!='') && kwTester.test(keyword);
}
function getKwParams()
{
    return {
        kwKeyword: $('#kw-keyword').val(), 
        kwDesc: $('#kw-description').val(), 
        kwFormat: $('#kw-format').val(), 
        kwSample: $('#kw-sample').val(),
        kwDefaultAction: $('#kw-reply-sms').is(':checked'),
        kwKategori: $('#kw-kategori').val()
    };
}
$(document).ready(function(){
    
    var timerRefetchData;
    var refetchData = function(currKat)
    {
        // alert(currKat);
        /*
        if ($('#data-container').hasClass('hide'))
        {
            return false;
        }
        */
        $('#data-container').load(uri, {
            ajax:true, 
            r: Math.random(), 
            reqtype: 'fetch',
            // reqkw: currKeyword
            currkat: currKat                
        });  
        clearTimeout(timerRefetchData);
        timerRefetchData = setTimeout(function(){
            refetchData(currentKategori);
        }, 10000); // refresh keyword after 10 seconds       
    };
    refetchData(currentKategori);
    
    /**
     * Filter by kategori:
     */
    $('.filter-by-kategori').click(function(e){
        e.preventDefault();
        var filterKat = $(this).attr('data-category');
        currentKategori = filterKat;        
        refetchData(currentKategori);  
    });
    
    // var editMode = $('#keyword-edit-mode').val();
    $('.new-keyword').click(function(e){
        $('#keyword-editor').removeClass('hide');
        $('#data-container').addClass('hide');
    });
    $('#cancel-keyword').click(function(e){
        $('#keyword-editor').addClass('hide');
        $('#data-container').removeClass('hide')
        $('#kw-keyword').val('');
        // $('#kw-kategori').val('<?php echo SP_APP_NAME_SHORT;  ?>');  
        $('#kw-description').val(''); 
        $('#kw-format').val('');
        $('#kw-sample').val('');
        $('#kw-reply-sms').prop('checked','checked');
        $('#kw-hook-template').addClass('hide').val('');
    });
    $('#kw-keyword').change(function(){
        var kwParam = getKwParams();
        if (!keywordValid(kwParam.kwKeyword) )
        {
            $('#row-kw-keyword').addClass('has-error');            
        }
        else
        {
            $.post(uri, {
                ajax:true, 
                r: Math.random(), 
                reqtype: 'checkkeyword',
                reqkw: kwParam.kwKeyword.toLowerCase()
            },
            function(data){
                if (data.substr(0,2).toUpperCase()=='OK')
                {
                    $('#row-kw-keyword').removeClass('has-error').addClass('has-success');
                }
                else
                {
                    $('#row-kw-keyword').addClass('has-error'); 
                }     
            });
        }
        
    });
    $('#generate-hook-template').click(function(e){
        e.preventDefault();
        // $('#row-kw-keyword').removeClass('has-error');
        var kwParam = getKwParams();
        if (!keywordValid(kwParam.kwKeyword) )
        {
            msgBox('Error','Keyword tidak boleh kosong, dan hanya boleh berisi huruf dan angka.');     
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
                reqaction: kwParam.kwDefaultAction,
                reqkategori: kwParam.kwKategori
            },
            function(data){
                if (data.substr(0,2).toUpperCase()=='OK')
                {
                    var $hookTpl = $('#kw-hook-template');
                    $('#row-hook-template').removeClass('hide');
                    var jml = data.substr(2).split("\n").length;
                    var lht = String($hookTpl.css('line-height')).replace('px','');
                    $hookTpl.val(data.substr(2))
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
    
    $('#kw-check-syntax').click(function(e){
        e.preventDefault();
        $.post('../gammu/ajax-lint.php', {
            ajax:true, 
            r: Math.random(), 
            phpdata: $('#kw-hook-template').val(),
            tipe: 'data' // or 'file' if you send PHP file as [phpdata] parameter
        },
        function(data){            
            if (data.substr(0,2).toUpperCase()=='OK')
            {
                $('#kw-check-syntax')
                    .removeClass('btn-default')
                    .removeClass('btn-danger')
                    .addClass('btn-success');                
            }
            else
            {
                $('#kw-check-syntax')
                    .removeClass('btn-default')
                    .removeClass('btn-success')
                    .addClass('btn-danger');
            }     
            msgBox('Syntax Check', data.substr(2), true, true);
        });  
        return false;
    });
    
    $('#save-keyword').click(function(e){
        e.preventDefault();
        var kwParam = getKwParams();
        if (!keywordValid(kwParam.kwKeyword) )
        {
            msgBox('Error','Keyword tidak boleh kosong, dan hanya boleh berisi huruf dan angka.');     
        }
        else
        {
            msgConfirm('Save Hook Template','Anda sudah pastikan kode PHP Anda benar?', 'Ya, lanjut','Cek lagi', function(jawab){
                if (jawab)
                {
                    $.post(uri, {
                        ajax:true, 
                        r: Math.random(), 
                        reqtype: 'savetemplate',
                        reqfile: $('#kw-hook-template').val(),
                        reqkw: kwParam.kwKeyword.toLowerCase()
                    },
                    function(data){
                        if (data.substr(0,2).toUpperCase()=='OK')
                        {
                            msgBox('Sukses','Hook template telah disimpan<br>Hook akan aktif beberapa saat lagi.');
                            // $('#cancel-keyword').click();
                            // location.reload();
                        }
                        else
                        {
                            msgBox('Error','Gagal menyimpan hook template:<br>'+data.substr(2));
                        }     
                    });    
                }    
            }); 
        }
        return false;
    });  
});
</script>   
<?php include '_footscripts.php'; ?>
<?php include '_foot.php'; ?>