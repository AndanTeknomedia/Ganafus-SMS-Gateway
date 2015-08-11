<?php

/**
 * @author Toshiba
 * @copyright 2015
 */



?>
    <!-- Make this available on all pages -->
    <script src="../dist/js/foot-utils.js"></script>
    
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
    <!--
    <script src="../js/morris-data.js"></script>
    -->
    <?php }?>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>