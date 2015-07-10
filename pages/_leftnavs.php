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
                        
                        <!-- Dashboard -->
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        
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
                        */
                        ?>
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
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->