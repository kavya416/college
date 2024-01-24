<?php if ($siteinfos->note == 1) { ?>
    <div class="callout callout-danger">
        <p><b>Note:</b> Create exam, class, section & subject before add mark</p>
    </div>
<?php } ?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?= $this->lang->line('panel_title') ?></h3>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li><a href="<?= base_url("mark/index") ?>"><?= $this->lang->line('menu_mark') ?></a></li>
            <li class="active"><?= $this->lang->line('menu_add') ?> <?= $this->lang->line('menu_mark') ?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <form method="POST">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label for="classesID" class="control-label">
                                            <?= $this->lang->line('mark_classes') ?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                        $array = array("0" => $this->lang->line("mark_select_classes"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2 classesID'");
                                        ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="<?php echo form_error('examID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label for="examID" class="control-label">
                                            <?= $this->lang->line('mark_exam') ?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                        $array = array("0" => $this->lang->line("mark_select_exam"));
                                        foreach ($exams as $exam) {
                                            $array[$exam->examID] = $exam->exam;
                                        }
                                        echo form_dropdown("examID", $array, set_value("examID"), "id='examID' class='form-control select2 examID'");
                                        ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label class="control-label"><?= $this->lang->line('mark_section') ?> <span class="text-red">*</span></label>
                                        <?php
                                        $arraysection = array('0' => $this->lang->line("mark_select_section"));
                                        if (customCompute($sections)) {
                                            foreach ($sections as $section) {
                                                $arraysection[$section->sectionID] = $section->section;
                                            }
                                        }
                                        echo form_dropdown("sectionID", $arraysection, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3" style="display:none;">
                                    <div class="<?php echo form_error('subjectID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label for="subjectID" class="control-label">
                                            <?= $this->lang->line('mark_subject') ?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                        // $subjectArray = array("0" => $this->lang->line("mark_select_subject"));
                                        if (customCompute($subjects)) {
                                            foreach ($subjects as $subject) {
                                                $subjectArray[$subject->subjectID] = $subject->subject;
                                            }
                                        }
                                        echo form_dropdown("subjectID", $subjectArray, set_value("subjectID"), "id='subjectID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success col-md-12 col-xs-12 mark_btn" style="margin-top: 20px;"><?= $this->lang->line('add_mark') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php if (customCompute($students)) { ?>
                    <!-- <form enctype="multipart/form-data" style="" action="<?= base_url('mark/mark_bulkimport'); ?>" class="form-horizontal" role="form" method="post">
                        <input  type="hidden"name="classId" value="" class="classId" />
                        <input  type="hidden"name="sectionId" value="" class="sectionId" />
                        <input  type="hidden"name="subjectId" value="" class="subjectId" />
                        <input  type="hidden" name="examId" value="" class="examId" />
                        <div class="form-group">
                            <label for="csvMark" class="col-sm-2 control-label col-xs-8 col-md-2">
                                <?= 'Add Mark Sheet' ?>
                                &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download sample mark shhet and add marks and upload"></i>
                            </label>
                            <div class="col-sm-3 col-xs-1 col-md-2">
                                <input class="form-control markImport" id="uploadFile" placeholder="Choose File" disabled />
                            </div>

                            <div class="col-sm-2 col-xs-1 col-md-1">
                                <div class="fileUpload btn btn-success form-control">
                                    <span class="fa fa-repeat"></span>
                                    <span><?= "Upload" ?></span>
                                    <input id="uploadBtn" type="file" class="upload markUpload" name="csvMark" />
                                </div>
                            </div>

                            <div class="col-md-1 rep-mar">
                                <input type="submit" class="btn btn-success" value="Import">
                            </div>
                        </div>
                    </form>
                    <form enctype="multipart/form-data" style="" action="<?= base_url('mark/add'); ?>" class="form-horizontal" role="form" method="post">
                        <input type="hidden" name="classesID" value="" class="classId" />
                        <input  type="hidden"name="sectionID" value="" class="sectionId" />
                        <input  type="hidden"name="subjectID" value="" class="subjectId" />
                        <input  type="hidden"name="examID" value="" class="examId" />
                        <input  type="hidden" name="downloadFile" value="1" id="" />
                        <div class="form-group">
                            <div class="col-md-1 rep-mar">
                                <input type="submit" class="btn btn-success" value="Download Sample File">
                            </div>
                        </div>
                    </form> -->

                <?php }  ?>


                <?php if (customCompute($sendExam) && customCompute($sendClasses) && customCompute($sendSection) && customCompute($sendSubject)) { ?>
                    <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                        <?php
                        echo '<h5><center>' . $this->lang->line('mark_details') . '</center></h5>';
                        echo '<h5><center>' . $this->lang->line('mark_exam') . ' : ' . $sendExam->exam . '</center></h5>';
                        echo '<h5><center>' . $this->lang->line('mark_classes') . ' : ' . $sendClasses->classes . '</center></h5>';
                        echo '<h5><center>' . $this->lang->line('mark_section') . ' : ' . $sendSection->section . '</center></h5>';
                        // echo '<h5><center>' . $this->lang->line('mark_subject') . ' : ' . $sendSubject->subject . '</center></h5>';
                        ?>
                    </div>
                <?php } ?>


            </div>
            <div class="col-sm-12">
                <?php if (customCompute($students)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th><?= $this->lang->line('slno') ?></th>
                                    <th><?= $this->lang->line('mark_photo') ?></th>
                                    <th><?= $this->lang->line('mark_name') ?>(<?= $this->lang->line('mark_roll') ?>)</th>

                                    
                                    <!-- code for getting dynamic subjects start -->
                                    <?php
                                    //if (customCompute($subjects)) {
                                           // foreach ($subjects as $subject) {?>
                                                <!-- $subjectArray[$subject->subjectID] = $subject->subject; -->
                                                <!-- <th> <?php //echo $subject->subject?></th> -->
                                           <?php //}
                                        //}
                                    ?>
                                    
                                    <?php
                                    $out_of = 0;
                                    foreach ($subjects as $subject) { 
                                            
                                          //echo "<span class='dyn_sub'>".$subject->subject."</span>";
                                        // foreach ($markpercentages as $data) {
                                             
                                        //     echo "<th>$data->markpercentagetype ($data->percentage)</th>";
                                        // }
                                             echo "<th>$subject->subject (".$subject->max_mark.") </th>";

                                             $out_of += $subject->max_mark;

                                    }
                                    ?>
                                   <th> Total (Out of <?php echo $out_of;?>)</th>
                                   <th> Grade </th>
                                   <th> Send SMS <input type="checkbox" class="" id="checkAll" name="send_sms_marks"> </th>



                                </tr>
                            </thead>
                            <tbody>
                                <?php if (customCompute($students)) {
                                    $i = 1;
                                    //  echo "<pre>"; print_r($students);
                                    foreach ($students as $student) {
                                        // foreach ($marks as $mark) {
                                            // if ($student->studentID == $mark->studentID) {   ?>
                                                <tr>
                                                    <td data-title="<?= $this->lang->line('slno') ?>">
                                                        <?php echo $i; ?>
                                                    </td>
                                                    <td data-title="<?= $this->lang->line('mark_photo') ?>">
                                                        <?= profileproimage($student->photo) ?>
                                                    </td>
                                                    <td data-title="<?= $this->lang->line('mark_name') ?>">
                                                        <?php echo $student->name; ?> ( <?php echo $student->roll; ?>)
                                                    </td>
                                                    <!-- <td data-title="<?= $this->lang->line('mark_roll') ?>">
                                                        <?php echo $student->roll; ?>
                                                    </td> -->
                                                    <?php
                                                        // echo "<pre>";print_r($markwr);die;
                                                        // foreach ($subjects as $data) {
                                                        //     echo "<td data-title='$data1->subject'>";
                                                        //     echo "<input class='form-control mark input_mark' type='number' name='mark1-" . $markwr[$student->studentID][$data1->markpercentageID] . "' id='" . $data1->markpercentageID . "' value='" . $markRelations[$student->studentID][$data1->markpercentageID] . "' min='0' max='" . $data1->percentage . "' />";
                                                        //     echo "</td>";
                                                        // }
                                                      
                                                        //    echo "<pre>"; print_r($marks);
                                                            
                                                        foreach ($subjects as $subject) { 

                                                    foreach ($markpercentages as $data) {


                                                         
                                                       


                                                        // echo "<td data-title='$data->markpercentagetype'>";
                                                        // echo "<input subj_id = '".$subject->subjectID."'  class='form-control mark input_mark' type='number' name='".$subject->subjectID."mark-" . $markwr[$student->studentID][$data->markpercentageID] . "' id='" . $data->markpercentageID . "' value='" . $mrk . "' min='0' max='" . $data->percentage . "' />";
                                                        // echo "</td>";
                                                        //  $zero_mark = 'no';
                                                        // $zero_mark = 0;
                                                          
                                                        foreach ($marks as $mark) {
                                                            // $my_template = "aa";
                                                            // $tot = 0;
                                                            if($subject->subjectID == $mark->subjectID && $student->studentID == $mark->studentID ){

                                                                $sql = "SELECT mark FROM `mark` LEFT JOIN `markrelation` ON `markrelation`.`markID` = `mark`.`markID` WHERE `mark`.`schoolyearID` = ".$mark->schoolyearID." AND `mark`.`examID` = ".$mark->examID." AND `mark`.`classesID` = ".$mark->classesID." and studentId= ".$student->studentID." and markpercentageID =".$data->markpercentageID." and subjectID=".$subject->subjectID;
                                                                //  echo "<br/>";       
                                                               $mrk =  $this->db->query($sql)->row()->mark;
                                                                if($mrk == 0 || $mrk == '0' ){
                                                                    
                                                                    // $zero_mark = 'yes'; 
                                                                    ++$zero_mark; 
                                                                }
                                                                $tot += $mrk; 
                                                                


                                                        echo "<td data-title='$data->markpercentagetype'>";
                                                        echo "<input subj_id = '".$subject->subjectID."'  class='form-control mark input_mark' type='' style='width: 100px !important;' name='".$subject->subjectID."mark-" . $mark->markID . "' id='" . $data->markpercentageID . "' value='" . $mrk . "' min='0' max='" . $subject->max_mark . "' />";
                                                        echo "</td>";
                                                        
                                                        $my_template .= $subject->subject."=".$mrk."/".$subject->max_mark.",";
                                                        }
                                                        }
                                                    }
                                                }
                                                // echo '@@@@@@@@@'.$tot;
                                                    echo "<td>".$tot."<td/>";

                                                  
                                                    $percent_cal = ($tot / $out_of) * 100;
                                                    if($percent_cal >= 95 && $zero_mark == 0){
                                                        $grade = "A+";
                                                    }else if($percent_cal >= 90 && $percent_cal < 95 && $zero_mark == 0){
                                                        $grade = "A";
                                                    }else if($percent_cal >= 80 && $percent_cal < 90 && $zero_mark == 0){
                                                        $grade = "B+";
                                                    }else if($percent_cal >= 70 && $percent_cal < 80 && $zero_mark == 0){
                                                        $grade = "B";
                                                    }else if($percent_cal >= 60 && $percent_cal < 70 && $zero_mark == 0){
                                                        $grade =  "C+";
                                                    }else if($percent_cal >= 50 && $percent_cal < 60 && $zero_mark == 0){
                                                        $grade = "C";
                                                    }else{
                                                        $grade = "D";
                                                    }
                                                    echo  $grade;
                                                    ?>


                                                    <!-- //CONSTRUCT SEND MARKS SMS -->
                                                    <td><input type="checkbox" st_ids="<?php echo $student->studentID;?>" st_names="<?php echo $student->name;?>" mobile_no="<?php echo $student->phone;?>" exam_name ="<?php echo $mark->exam;?>" total_marks ="<?php echo $tot."/". $out_of;?>"  marks_template ="<?php echo $my_template;?>" name="send_sms_marks" id="send_sms_marks" class="checkbox"></td>
                                                </tr>
                                <?php 
                                $my_template = "";
                                $tot = 0;
                                $zero_mark = 0;
                                $i++;
                                            // }
                                        // }
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <input type="button" class="btn btn-success col-sm-2 col-xs-12" id="add_mark" name="add_mark" value="<?= $this->lang->line("add_sub_mark") ?>" />

                    <button class="btn btn-default sendSms" id="send_sms_marks_btn"><span class="fa fa-send"></span> Send SMS</button>

                    <script type="text/javascript">
                        window.addEventListener('load', function() {
                            setTimeout(lazyLoad, 1000);
                        });

                        function lazyLoad() {
                            var card_images = document.querySelectorAll('.card-image');
                            card_images.forEach(function(card_image) {
                                var image_url = card_image.getAttribute('data-image-full');
                                var content_image = card_image.querySelector('img');
                                content_image.src = image_url;
                                content_image.addEventListener('load', function() {
                                    card_image.style.backgroundImage = 'url(' + image_url + ')';
                                    card_image.className = card_image.className + ' is-loaded';
                                });
                            });
                        }

                        $(document).on("keyup", ".mark", function() {
                            if (parseInt($(this).val())) {
                                var val = parseInt($(this).val());
                                var minMark = parseInt($(this).attr('min'));
                                var maxMark = parseInt($(this).attr('max'));
                                if (minMark > val || val > maxMark) {
                                    $(this).val('');
                                }
                            } else {
                                if ($(this).val() == '0') {} else {
                                    $(this).val('');
                                }
                            }
                        });

                        // $("#add_mark").click(function() {
                            $(document).on('keyup','.input_mark',function() {
                                var subj_id = $(this).attr('subj_id');
                               
                            var inputs = "";
                            var inputs_value = "";
                            var mark = $('input[name^='+subj_id+'mark]').map(function() {
                                return {
                                    markpercentageid: this.id,
                                    mark: this.name,
                                    value: this.value
                                };
                            }).get();

                            $.ajax({
                                type: 'POST',
                                url: "<?= base_url('mark/mark_send') ?>",
                                data: {
                                    "examID": "<?= $set_exam ?>",
                                    "classesID": "<?= $set_classes ?>", 
                                    "subjectID":subj_id ,
                                    "inputs": mark
                                },
                                dataType: "html",
                                success: function(data) {
                                    var response = jQuery.parseJSON(data);
                                    if (response.status) {
                                        toastr["success"](response.message)
                                        toastr.options = {
                                            "closeButton": true,
                                            "debug": false,
                                            "newestOnTop": false,
                                            "progressBar": false,
                                            "positionClass": "toast-top-right",
                                            "preventDuplicates": false,
                                            "onclick": null,
                                            "showDuration": "500",
                                            "hideDuration": "500",
                                            "timeOut": "5000",
                                            "extendedTimeOut": "1000",
                                            "showEasing": "swing",
                                            "hideEasing": "linear",
                                            "showMethod": "fadeIn",
                                            "hideMethod": "fadeOut"
                                        }
                                    } else {
                                        if (response.inputs) {
                                            toastr["error"](response.inputs)
                                            toastr.options = {
                                                "closeButton": true,
                                                "debug": false,
                                                "newestOnTop": false,
                                                "progressBar": false,
                                                "positionClass": "toast-top-right",
                                                "preventDuplicates": false,
                                                "onclick": null,
                                                "showDuration": "500",
                                                "hideDuration": "500",
                                                "timeOut": "5000",
                                                "extendedTimeOut": "1000",
                                                "showEasing": "swing",
                                                "hideEasing": "linear",
                                                "showMethod": "fadeIn",
                                                "hideMethod": "fadeOut"
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    </script>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();
    $("#classesID").change(function() {
        var classesID = $(this).val();
        if (parseInt(classesID)) {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('mark/examcall') ?>",
                data: {
                    "classesID": classesID
                },
                dataType: "html",
                success: function(data) {
                    $('#examID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?= base_url('mark/subjectcall') ?>",
                data: {
                    "id": classesID
                },
                dataType: "html",
                success: function(data) {
                    $('#subjectID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?= base_url('mark/sectioncall') ?>",
                data: {
                    "id": classesID
                },
                dataType: "html",
                success: function(data) {
                    $('#sectionID').html(data);
                }
            });
        }
    });


    $('.markUpload').on('change', function() {
        $('.markImport').val($(this).val());
    });

    $(document).ready(function() {

        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        

        
        
        $(document).on("click","#send_sms_marks_btn",function(){

            var st_ids = [];
            st_names =[];
            mobile_no = [];
            exam_name = [];
            total_marks = [] ;
            marks_template = []; 
            i=j=k=l=m=n=0;

            $('.checkbox:checked').each(function(){        
                // var values = $(this).val();
                // var sids = $(this).attr("st_ids");
                
                st_ids[i++] = $(this).attr("st_ids");
                st_names[j++] = $(this).attr("st_names");
                mobile_no[k++] = $(this).attr("mobile_no");
                exam_name[l++] = $(this).attr("exam_name");
                total_marks[m++] = $(this).attr("total_marks");
                marks_template[n++] = $(this).attr("marks_template");
            });

            $.ajax({
                            
                type: "POST",
                url: "<?php echo site_url('progresscardreport/send_marks_to_sms'); ?>",
                // dataType: "json",
                data: {"st_ids":st_ids,"st_names":st_names,"mobile_no":mobile_no,"exam_name":exam_name,"total_marks":total_marks,"marks_template":marks_template},
                success: function(result)
                {
                    
                }
            })
        });
            

        // if( sessionStorage.getItem("click") == 'yes')){}else{
        //     sessionStorage.setItem("click", "no");
        // }
        // if(sessionStorage.getItem("click") == 'no'){
        //     $(".mark_btn").click();
        //     sessionStorage.setItem("click", "yes");
        // }
        // $(".mark_btn").click();

        var cID = $('#classesID').val();
        var sID = $('#sectionID').val();
        var eID = $('#examID').val();
        var subID = $('#subjectID').val();

        $('.classId').val(cID);
        $('.sectionId').val(sID);
        $('.examId').val(eID);
        $('.subjectId').val(subID);
    });
$(document).on("click","#add_mark",function(){
    location.reload();
});

</script>