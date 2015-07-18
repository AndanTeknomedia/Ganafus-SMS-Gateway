<?php
$page_name = 'Pooling SMS Manager';
include_once('../cores/definition.php'); 
$ajax = post_var('ajax');
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
// require_login('post.php?show/newest');

/**
 * If this page is is being loaded using Ajax call,
 * fetch the requested data and skip the rest of the page:
 */

if ($ajax)
{
    
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
                                <li class="left clearfix" id="keyword-editor">
                                    <div class="panel panel-default" style="border: 0;">
                                        <div class="panel-heading">
                                            <strong>Tambahkan Keyword Baru</strong>                                            
                                        </div> 
                                        <!-- /.panel-heading -->
                                        <div class="panel-body" id="input-container">                                            
                                            <div class="col-lg-12" id="kw-inputs">
                                                <!-- Keyword -->
                                                <div class="row" style="padding-bottom: 6px;" id="row-keyword">
                                                    <div class="col-sm-2">Keyword</div>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">&nbsp;#&nbsp;</span>
                                                            <input class="form-control input-sm" placeholder="Keyword" id="kw-keyword" type="text" autofocus="" maxlength="30">
                                                        </div>    
                                                    </div>
                                                </div>
                                                <!-- Description -->
                                                <div class="row" style="padding-bottom: 6px;" id="row-description">
                                                    <div class="col-sm-2">Keterangan</div>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">&nbsp;#&nbsp;</span>
                                                            <textarea class="form-control input-sm" placeholder="Keterangan" id="kw-description" ></textarea>
                                                        </div>     
                                                    </div>
                                                </div>
                                                <!-- Fields start here -->
                                                <!-- Fields placeholder -->
                                                <div class="row" style="padding-bottom: 6px;" id="row-description">
                                                    <div class="col-sm-2">Field-Field SMS</div>
                                                    <div class="col-sm-4">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover" id="dataTables-example">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Nama Kolom</th>
                                                                        <th>Format</th>
                                                                        <th>Contoh</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>Mark</td>
                                                                        <td>Otto</td>
                                                                        <td>@mdo</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>2</td>
                                                                        <td>Jacob</td>
                                                                        <td>Thornton</td>
                                                                        <td>@fat</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>3</td>
                                                                        <td>Larry</td>
                                                                        <td>the Bird</td>
                                                                        <td>@twitter</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>     
                                                    </div>
                                                </div>                                                
                                            </div>
                                        </div>
                                        <!-- /.panel-body -->
                                        <!-- Add fields -->
                                        <div class="panel-footer">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <button type="button" class="new-field btn btn-default btn-sm"><i class="fa fa-tag"></i> Add field</button>
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
                                            <strong class="primary-font">Keyword Poolong SMS belum diatur.</strong>                                            
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
                                                <a href="#" class="label label-warning button-edit" data-id="<?php echo $key['id']; ?>"><i class="fa fa-pencil fa-fw"></i> Edit</a>
                                                <a href="#" class="label label-danger button-drop" data-id="<?php echo $key['id']; ?>"><i class="fa fa-trash-o fa-fw"></i> Drop</a>
                                            </small>
                                        </div>
                                        <p><small><strong class="label label-success">KETERANGAN:</strong> <?php echo $key['description']; ?></small></p>
                                        <p><small><strong class="label label-info">FORMAT SMS:</strong> <?php echo $key['description']; ?></small></p>
                                        <p><small><strong class="label label-info">CONTOH SMS:</strong> <?php echo $key['description']; ?></small></p>
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
var kwColCount = 0;
function delField(el){
    kwColCount--;
    $('#'+el).remove();
    return false;
};

function changeFieldNum(fieldElement, newNum)
{
    var el = $('#'+fieldElement);
    $(el).prop('id', 'row-field-'+newNum);
    // var el = $('#'+'row-field-'+newNum);
    alert($(el).html());
    $(el).
}
$(document).ready(function(){    
    $('#dataTables-example').DataTable({
        responsive: true
    });  
    
    $('button.new-field').click(function(e){
        e.preventDefault();
        var el = $("#kw-inputs");
        kwColCount++;
        $(el).append(
            '<div class="row" style="padding-bottom: 6px; cursor: move;" id="row-field-'+kwColCount+'"><hr>'+
            '<div class="col-sm-2 nama-field">Field #'+kwColCount+'</div>'+
            '<div class="col-sm-2">'+
            '<div class="input-group">'+  
            '<span class="input-group-addon">'+('00'+kwColCount.toString()).slice(-2)+'</span>'+
            '<input class="form-control input-sm" placeholder="Nama Field" id="field-'+kwColCount+'-title" type="text" maxlength="30">'+
            '</div></div>'+
            '<div class="col-sm-2" style="padding-left: 0;">'+                                                        
            '<input class="form-control input-sm" placeholder="Format" id="field-'+kwColCount+'-format" type="text" maxlength="30">'+    
            '</div><div class="col-sm-2" style="padding-left: 0;">'+                                                        
            '<input class="form-control input-sm" placeholder="Contoh" id="field-'+kwColCount+'-sample" type="text" maxlength="30">'+
            '</div><div class="col-sm-2" style="padding-left: 0;">'+
            '<div class="btn-group" id="btn-field-'+kwColCount+'">'+
            '   <button type="button" class="up-field btn btn-default btn-sm first" onclick="javascript:upField('+kwColCount+');"><i class="fa fa-chevron-up"></i> </button>'+
            '   <button type="button" class="down-field btn btn-default btn-sm" onclick="javascript:downField('+kwColCount+');"><i class="fa fa-chevron-down"></i> </button>'+
            "   <button type=\"button\" class=\"del-field btn btn-default btn-sm\" onclick=\"javascript:delField('row-field-"+kwColCount+"');\"><i class=\"fa fa-trash\"></i> </button>"+
            '</div>'+
            '</div></div>'); 
        return false;
    });    
});
</script>   
<?php include '_footscripts.php'; ?>
<!-- DataTables JavaScript -->
<script src="../bower_components/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<?php include '_foot.php'; ?>