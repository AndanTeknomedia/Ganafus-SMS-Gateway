<?php
include_once('../gammu/gammu-fetch-sms.php');
?>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <!-- Logo Aplikasi -->
                        
                        <li class="sidebar-search">
                            <?php
                            echo    '<img src="'.SP_APP_LOGO.
                                    '"  style="width:64px; height:64px; vertical-align: middle;" >';
                            ?>
                                    
                        </li>
                        
                        <!-- Sample Blank Page -->
                        <li>
                            <a href="blank.php"><i class="fa fa-laptop fa-fw"></i> Sample Blank Page</a>
                        </li>
                        <li class="sidebar-divider"></li>
                        
                        <!-- Dashboard -->
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li class="sidebar-divider"></li>
                        
                        <!-- Master Data -->
                        <li>
                            <a href="#"><i class="fa fa-th-list fa-fw"></i> Master Data Inkubator<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="inkubator-master.php">Data Inkubator</a>
                                </li>                                
                                <li>
                                    <a href="inkubator-report.php">Laporan Data Inkubator</a>
                                </li>
                                <li>
                                    <a href="inkubator-data-peminjam.php">Data Peminjam</a>
                                </li>
                                <li>
                                    <a href="inkubator-data-bayi.php">Data Bayi</a>
                                </li>
                            </ul>
                            
                        </li>
                        
                        <!-- Peminjaman -->
                        <li>
                            <a href="#"><i class="fa fa-file-text fa-fw"></i> Data Peminjaman<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="pinjam-data.php">Data Peminjaman</a>
                                </li>
                                <li>
                                    <a href="pinjam-add.php">Peminjaman Baru</a>
                                </li>    
                                <li>
                                    <a href="pinjam-return.php">Pengembalian</a>
                                </li>
                            </ul>
                            
                        </li>
                        
                        <!-- Monitoring -->
                        <li>
                            <a href="#"><i class="fa fa-image fa-fw"></i> Data Monitoring<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="monitor-bayi.php">Perkembangan Bayi</a>
                                </li>
                                <li>
                                    <a href="monitor-edit.php">Edit Data</a>
                                </li>    
                                <li>
                                    <a href="monitor-laporan.php">Laporan Monitoring</a>
                                </li>
                            </ul>
                            
                        </li>
                        <li class="sidebar-divider"></li>
                                              
                        <!-- SMS Manager -->
                        <li class="success">
                            <a href="#"><i class="fa fa-envelope-o fa-fw"></i> SMS Manager<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="sms-inbox.php">SMS Masuk</a></li>                                                             
                                <li><a href="sms-outbox.php">SMS Sedang Dikirim</a></li>    
                                <li><a href="sms-sent.php">SMS Terkirim</a></li>
                                <li class="warning"><a href="sms-stats.php"><i class="fa fa-bar-chart-o fa-fw"></i> Statistik </a></li>
                                <?php if (USE_GAMMU) { ?>
                                <li class="divider sidebar-divider"></li>
                                <li><a href="#" class="send-recv-sms" jenis="kirim" id="btn-send-sms"><i class="fa fa-upload fa-fw"></i> Kirim SMS</a></li>
                                <li><a href="#" class="send-recv-sms" jenis="terima" id="btn-recv-sms"><i class="fa fa-download fa-fw"></i> Simulasi Terima SMS</a></li>
                                <?php } ?>
                            </ul>                            
                        </li>
                        <li class="sidebar-divider"></li>
                        <?php if (USE_GAMMU) { ?>
                        <!-- Konfigurasi SMS Gateway -->
                        <li class="warning">
                            <a href="#"><i class="fa fa-gears fa-fw"></i> SMS Gateway<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="run-gammu.php">Restart SMS Gateway</a></li>                        
                                <li><a href="run-task-scheduler.php">Restart SMS Processor</a></li>
                                <li><a href="setup-gammu.php">Setting SMS Gateway</a></li>
                                <li><a href="sms-pooling-setup.php">Setup Pooling SMS</a></li>                                
                            </ul>                            
                        </li>
                        <?php } ?>
                        <!-- User profile -->
                        <li>
                            <a href="#"><i class="fa fa-user fa-fw"></i> User Profile<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="user-profile.php?id=<?php echo $user_data['user_id']; ?>"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
                                <!--
                                <li><a href="user-settings.php?id=<?php echo $user_data['user_id']; ?>"><i class="fa fa-gear fa-fw"></i> Settings</a></li>
                                -->
                                <li><a href="../cores/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>                              
                            </ul>                            
                        </li>
                        <!-- Plugins -->
                        <li>
                            <a href="#"><i class="fa fa-sliders fa-fw"></i> Plugins<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="plugin-manager.php"><i class="fa fa-magic fa-fw"></i> Manage Plugins</a></li>                              
                            </ul>                            
                        </li>
                        <!-- Utils -->
                        <li>
                            <a href="#"><i class="fa fa-database fa-fw"></i> Utilites<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="util-clean-db.php"><i class="fa fa-scissors fa-fw"></i> Clean User Data</a></li>                              
                            </ul>                            
                        </li>
                        <?php
                        /*
                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Grafik<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="flot.html">Flot Charts</a>
                                </li>
                                <li>
                                    <a href="morris.html">Morris.js Charts</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>                        
                        <li>
                            <a href="tables.html"><i class="fa fa-table fa-fw"></i> Tables</a>
                        </li>
                        <li>
                            <a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="panels-wells.html">Panels and Wells</a>
                                </li>
                                <li>
                                    <a href="buttons.html">Buttons</a>
                                </li>
                                <li>
                                    <a href="notifications.html">Notifications</a>
                                </li>
                                <li>
                                    <a href="typography.html">Typography</a>
                                </li>
                                <li>
                                    <a href="icons.html"> Icons</a>
                                </li>
                                <li>
                                    <a href="grid.html">Grid</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="blank.html">Blank Page</a>
                                </li>
                                <li>
                                    <a href="login.html">Login Page</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        */
                        ?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->