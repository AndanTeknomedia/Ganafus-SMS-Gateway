<?php
$page_name = 'Database Utility';
include_once('../cores/definition.php');
require_once('../cores/db.php'); 
include_once('../cores/session.php');
require_login();
$myself = user_data();
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
                        <small>Clean User Data</small>    
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-database fa-fw"></i> <strong class="text-danger">Warning!</strong> Ini Akan Menghapus Data SMS dan Keyword.                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" id="table-names-container">
                            <?php                            
                            if (!is_admin($myself['user_group_id']))
                            {
                            ?>
                            <div class="list-group">
                                <a href="#" class="list-group-item text-info">
                                    <i class="fa fa-lightbulb-o fa-2x fa-fw text-warning"></i><strong>Anda tidak memiliki akses ke fitur ini.</strong>
                                    <em class="small">Silahkan hubungi Administrator.</em>                                    
                                </a>
                            </div>
                            <?php    
                            }
                            else
                            {
                            ?>
                            <div class="list-group" id="table-names">
                                <?php
                                $table_count = 0;
                                foreach($_CLEANABLE_TABLES as $table=>$data)
                                {
                                    $table_count++;
                                ?>
                                <a href="#" class="list-group-item text-info" id="clean-table-<?php echo $table_count; ?>">
                                    <i class="fa fa-trash fa-fw"></i>
                                    Tabel <strong><?php echo $table; ?></strong>
                                    <em class="small"><?php echo $data[2]; ?></em>
                                    
                                </a>
                                <?php } ?>   
                            </div>                            
                            <div class="row">
                                <div class="col-lg-12 small text-danger" id="clean-db-status">
                                    <strong>Anda tidak dapat mengembalikan data yang telah dihapus</strong>. Pastikan Anda telah melakukan backup database.
                                </div>
                            </div>                             
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <a href="#" class="btn btn-danger btn-sm" id="clean-db-execute"><i class="fa fa-scissors" id="clean-db-indicator"></i> Eksekusi</a>
                            <a href="index.php" class="btn btn-success btn-sm hide" id="clean-db-finish"> Finish</a>
                            
                            <div class="clearfix"></div>
                        </div>
                        <!-- /.panel-footer -->
                        <?php } ?>
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

$(document).ready(function(){    
    $('#clean-db-execute').click(function(e)
    {
        e.preventDefault();
        $('#clean-db-indicator')
            .removeClass('fa-scissors')
            .addClass('fa-gear')
            .addClass('fa-spin');
        $.post('../cores/db-clean-tables.php',
		{
			r: Math.random()
		},
		function(data)
		{
            // msgBox('Test',data);
            if (data.substr(0,2) == 'OK')
			{	
                $('a.list-group-item i').each(function(i){
                    $(this).removeClass('fa-trash').addClass('fa-check');
				});
                $('#clean-db-finish').removeClass('hide');
                $('#clean-db-execute').addClass('hide');	
                $('#clean-db-status')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .html('<strong>Data telah dihapus.</strong> <em><a href="index.php">Kembali ke Dashboard</a>.</em>');
			}
			else
			{
				$('#clean-db-indicator')
                    .removeClass('fa-spin')
                    .removeClass('fa-gear')
                    .addClass('fa-scissors');
                $('#clean-db-status')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .html('<strong>Data gagal dihapus.</strong>');
			}            
		});	
        return false;
    }); 
});
</script>   
<?php include '_footscripts.php'; ?>
<!-- DataTables JavaScript -->
<script src="../bower_components/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<?php include '_foot.php'; ?>