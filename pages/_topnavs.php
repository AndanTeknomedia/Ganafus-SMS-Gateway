            <ul class="nav navbar-top-links navbar-right">
                <?php if (USE_GAMMU) { ?>
                <li>
                    <a href="#" class="checksvc" id="statussvc" data-toggle="dropdown" href="#" title="SMS Gateway Service">
                        <i class="fa fa-play fa-fw"></i> <small>Gammu</small> <span class="fa fa-caret-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-gammu-smsd">                        
                        <li><a href="#" class="control control-svc start"><i class="fa fa-play fa-fw"></i> Start </a></li>                        
                        <li><a href="#" class="control control-svc stop"><i class="fa fa-stop fa-fw"></i> Stop </a></li>
                        <li class="divider"></li>
                        <li><a href="run-gammu.php"><i class="fa fa-refresh fa-fw"></i> Reinstall </a></li>
                        <li><a href="setup-gammu.php"><i class="fa fa-gear fa-fw"></i> Setting </a></li>                        
                    </ul>
                </li>
                <li>
                    <a href="#" class="checksvc" id="statusproc" data-toggle="dropdown" href="#" title="SMS Processor Service">
                        <i class="fa fa-play fa-fw"></i> <small>SMSProc</small> <span class="fa fa-caret-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-sms-processor">                        
                        <li><a href="#" class="control control-proc start"><i class="fa fa-check fa-fw"></i> Enable </a></li>                        
                        <li><a href="#" class="control control-proc stop"><i class="fa fa-times fa-fw"></i> Disable </a></li>
                        <li class="divider"></li>
                        <li><a href="run-task-scheduler.php"><i class="fa fa-tasks fa-fw"></i> Restart SMS Processor</a></li>                        
                        <li class="disabled"><a href="#"><i class="fa fa-gear fa-fw"></i> Setting </a></li>                            
                    </ul>
                </li>
                <?php } ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="SMS Manager">
                        <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-sms">
                        <li><a href="sms-inbox.php"><i class="fa fa-envelope-o fa-fw"></i> SMS Masuk</a></li>
                        <li><a href="sms-outbox.php"><i class="fa fa-cloud-upload fa-fw"></i> SMS Sedang Dikirim</a></li>
                        <li><a href="sms-sent.php"><i class="fa fa-send-o fa-fw"></i> SMS Terkirim</a></li>
                        <?php if (USE_GAMMU) { ?>
                        <li class="divider"></li>
                        <li><a href="#" class="send-recv-sms" jenis="kirim" id="btn-send-sms"><i class="fa fa-upload fa-fw"></i> Kirim SMS</a></li>
                        <li><a href="#" class="send-recv-sms" jenis="terima" id="btn-recv-sms"><i class="fa fa-download fa-fw"></i> Simulasi Terima SMS</a></li>
                        <li class="divider"></li>
                        <li><a href="sms-pooling-setup.php"><i class="fa fa-th-list fa-fw"></i> Setup Pooling SMS</a></li>
                        <?php } ?>
                    </ul>
                </li>               
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="user-profile.php?id=<?php echo $user_data['user_id']; ?>"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
                        <!--
                        <li><a href="user-settings.php?id=<?php echo $user_data['user_id']; ?>"><i class="fa fa-gear fa-fw"></i> Settings</a></li>
                        -->
                        <li class="divider"></li>
                        <li><a href="../cores/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->