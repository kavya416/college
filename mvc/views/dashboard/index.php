<style>
    .blue{ background: #97085d !important}
    .violet{ background: #a60404 !important}
    .orange{ background: #811a84 !important}
    .yellow{ background: #ffa600 !important} 
    .pink{background: #b91776 !important}
    .green{background: #005c48 !important}
    .darkblue{background: #04488d !important}
</style>

<div class="row">
    <?php if(config_item('demo')) { ?>
        <div class="col-sm-12" id="resetDummyData">
            <div class="callout callout-danger">
                <h4>Reminder!</h4>
                <p>Dummy data will be reset in every <code>30</code> minutes</p>
            </div>
        </div>

        <script type="text/javascript"> 
            $(document).ready(function() {
                var count = 7;
                var countdown = setInterval(function(){
                    $("p.countdown").html(count + " seconds remaining!");
                    if (count == 0) {
                        clearInterval(countdown);
                        $('#resetDummyData').hide();
                    }
                    count--;
                }, 1000);
            });
        </script>
    <?php } ?>

    <?php //if((config_item('demo') === FALSE) && ($siteinfos->auto_update_notification == 1) && ($versionChecking != 'none')) { ?>
        <?php //if($this->session->userdata('updatestatus') === null) { ?>
            <!-- <div class="col-sm-12" id="updatenotify">
                <div class="callout callout-success">
                    <h4>Dear Admin</h4>
                    <p>INIlabs school management system has released a new update.</p>
                    <p>Do you want to update it now <?=config_item('ini_version')?> to <?=$versionChecking?> ?</p>
                    <a href="<?=base_url('dashboard/remind')?>" class="btn btn-danger">Remind me</a>
                    <a href="<?=base_url('dashboard/update')?>" class="btn btn-success">Update</a>
                </div>
            </div> -->
        <?php //} ?> 
    <?php //} ?>

    <?php
        // $arrayColor = array(
        //     'bg-orange-dark',
        //     'bg-teal-light',
        //     'bg-pink-light',
        //     'bg-purple-light'
        // );

        $arrayColor = array(
            'blue',
            'violet',
            'yellow',
             'orange',
        );

        function allModuleArray($usertypeID='1', $dashboardWidget) {
          $userAllModuleArray = array(
            $usertypeID => array(
                'student'   => $dashboardWidget['students'],
                'classes'   => $dashboardWidget['classes'],
                'teacher'   => $dashboardWidget['teachers'],
                'user'   => $dashboardWidget['users'],
                //'parents'   => $dashboardWidget['parents'],
                'subject'   => $dashboardWidget['subjects'],
                'book'     => $dashboardWidget['books'],
                'feetypes'  => $dashboardWidget['feetypes'],
                'lmember'   => $dashboardWidget['lmembers'],
                'event'     => $dashboardWidget['events'],
                'issue'     => $dashboardWidget['issues'],
                'holiday'   => $dashboardWidget['holidays'],
                'invoice'   => $dashboardWidget['invoices'],
            )
          );
          return $userAllModuleArray;
        }

        $userArray = array(
            '1' => array(
                'student'   => $dashboardWidget['students'],
                'teacher'   => $dashboardWidget['teachers'],
                'user'   => $dashboardWidget['users'],
                //'parents'   => $dashboardWidget['parents'],
                'subject'   => $dashboardWidget['subjects']
            ),
            '2' => array(
                'student'   => $dashboardWidget['students'],
                'teacher'   => $dashboardWidget['teachers'],
                'classes'   => $dashboardWidget['classes'],
                'subject'   => $dashboardWidget['subjects'],
            ),
            '3' => array(
                'teacher'   => $dashboardWidget['teachers'],
                'subject'   => $dashboardWidget['subjects'],
                'issue'     => $dashboardWidget['issues'],
                'invoice'   => $dashboardWidget['invoices'],
            ),
            '4' => array(
                'teacher'   => $dashboardWidget['teachers'],
                'book'     => $dashboardWidget['books'],
                'event'     => $dashboardWidget['events'],
                'holiday'   => $dashboardWidget['holidays'],
            ),
            '5' => array(
                'student'   => $dashboardWidget['students'],
                'teacher'   => $dashboardWidget['teachers'],
                // 'parents'   => $dashboardWidget['parents'],
               
                'feetypes'  => $dashboardWidget['feetypes'],
                'invoice'   => $dashboardWidget['invoices'],
            ),
            '6' => array(
                'teacher'   => $dashboardWidget['teachers'],
                'lmember'   => $dashboardWidget['lmembers'],
                'book'      => $dashboardWidget['books'],
                'issue'     => $dashboardWidget['issues'],
            ),
            '7' => array(
                'teacher'       => $dashboardWidget['teachers'],
                'event'         => $dashboardWidget['events'],
                'holiday'       => $dashboardWidget['holidays'],
                'visitorinfo'  => $dashboardWidget['visitors'],
            ),
        );

        $generateBoxArray = array();
        $counter = 0;
        $getActiveUserID = $this->session->userdata('usertypeID');
        $getAllSessionDatas = $this->session->userdata('master_permission_set');
        foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
            if($getAllSessionData == 'yes') {
                if(isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
 

                    if($counter == 4) {
                      break;
                    }
                    $generateBoxArray[$getAllSessionDataKey] = array(
                        'icon' => $dashboardWidget['allmenu'][$getAllSessionDataKey],
                        'color' => $arrayColor[$counter],
                        'link' => $getAllSessionDataKey,
                        'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
                        'menu' => $dashboardWidget['allmenulang'][$getAllSessionDataKey],
                    );
                    $counter++;
                }

            }
        }

        $icon = '';
        $menu = '';
        if($counter < 2) {
            $userArray = allModuleArray($getActiveUserID, $dashboardWidget);
            foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
                if($getAllSessionData == 'yes') {
                    if(isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
                        if($counter == 2) {
                            break;
                        }

                        if(!isset($generateBoxArray[$getAllSessionDataKey])) {
                            $generateBoxArray[$getAllSessionDataKey] = array(
                                'icon' => $dashboardWidget['allmenu'][$getAllSessionDataKey],
                                'color' => $arrayColor[$counter],
                                'link' => $getAllSessionDataKey,
                                'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
                                'menu' => $dashboardWidget['allmenulang'][$getAllSessionDataKey]
                            );
                            $counter++;
                        }
                    }
                }
            }
        }

        // echo "<pre>";print_r($generateBoxArray);die;
        if(customCompute($generateBoxArray)) { foreach ($generateBoxArray as $generateBoxArrayKey => $generateBoxValue) { ?>
            <div class="col-lg-2 col-xs-4">
                <div class="small-box ">
                    <a class="small-box-footer <?=$generateBoxValue['color']?>" href="<?=base_url($generateBoxValue['link'])?>">
                        <div class="icon <?=$generateBoxValue['color']?>" style="padding: 9.5px 18px 6px 18px;">
                            <!-- <i class="fa <?=$generateBoxValue['icon']?>"></i> -->
                        </div>
                        <div class="inner ">
                            <h3 class="text-white">
                                <?=$generateBoxValue['count']?>
                            </h3 class="text-white">
                            <p class="text-white">
                                <?=$this->lang->line('menu_'.$generateBoxValue['menu'])?>
                            </p>
                        </div>
                    </a>
                </div>
            </div>
    <?php } } ?>

 

            <?php 
            $uids = array(1,2,5,7,8,11,12); 
                if(in_array( $this->session->userdata('usertypeID'),$uids )){
                ?>
            <div class="col-lg-2 col-xs-4">
                <div class="small-box ">
                    <a class="small-box-footer pink " href="#">
                        <div class="icon pink" style="padding: 9.5px 18px 6px 18px;">
                            
                        </div>
                        <div class="inner ">
                            <h3 class="text-white">
                                 
                                <?=$whatsapp_count?>
                            </h3 class="text-white">
                            <p class="text-white">
                            
                                Whatsapp Count
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-2 col-xs-4">
                <div class="small-box ">
                    <a class="small-box-footer green" href="#">
                        <div class="icon green" style="padding: 9.5px 18px 6px 18px;">
                             
                        </div>
                        <div class="inner ">
                            <h3 class="text-white">
                                <?php echo $sms_count;?>
                            </h3 class="text-white">
                            <p class="text-white">
                                SMS Count
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-2 col-xs-4">
                <div class="small-box ">
                    <a class="small-box-footer darkblue" href="#">
                        <div class="icon darkblue" style="padding: 9.5px 18px 6px 18px;">
                            
                        </div>
                        <div class="inner ">
                            <h3 class="text-white">
                                <?php echo $voice_count;?>
                            </h3 class="text-white">
                            <p class="text-white">
                                Voice Count
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            <?php } ?>
</div>

<?php if($getActiveUserID == 1 || $getActiveUserID == 5) { ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-body" style="padding: 0px;">
                    <div id="earningGraph"></div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if($getActiveUserID == 1 || $getActiveUserID == 5) { ?>
    <div class="row">
        <div class="col-sm-4">
            <?php $this->load->view('dashboard/ProfileBox'); ?>
        </div>
        <div class="col-sm-8">
            <div class="box">
                <div class="box-body" style="padding: 0px;">
                    <div id="attendanceGraph"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if(permissionChecker('notice')) { ?>
        <div class="col-sm-6">
            <?php $this->load->view('dashboard/NoticeBoard', array('val' => 5, 'length' => 15, 'maxlength' => 45)); ?>
        </div>
        <?php } ?>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-body" style="padding: 0px;">
                    <div id="visitor"></div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="col-sm-4">
            <?php $this->load->view('dashboard/ProfileBox'); ?>
        </div>
        <?php if(permissionChecker('notice')) { ?>
        <div class="col-sm-8">
            <div class="box">
                <div class="box-body" style="padding: 0px;height: 320px">
                    <?php $this->load->view('dashboard/NoticeBoard', array('val' => 5, 'length' => 20, 'maxlength' => 70)); ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
<?php } ?>

<div class="row">
  <div class="col-sm-12">
      <div class="box">
          <div class="box-body">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
          </div>
      </div>
  </div>
</div><!-- /.row -->

<?php
    if($attendanceSystem != 'subject') {
        $this->load->view("dashboard/AttendanceHighChartJavascript");
    } else {
        $this->load->view("dashboard/SubjectWiseAttendanceHighChartJavascript");
    }
?>
<?php $this->load->view("dashboard/EarningHighChartJavascript.php"); ?>
<?php $this->load->view("dashboard/CalenderJavascript"); ?>
<?php $this->load->view("dashboard/VisitorHighChartJavascript"); ?>
