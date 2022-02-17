<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Status<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
            <input onclick="ug_status();" blurMethod="ug_status();" name="Completed" id="ug_Completed" type="radio"
value="completed" caption="Completed" <?php echo $userarray['UGcompleted']?> />Completed &nbsp;<input
onclick="ug_status();" blurMethod="ug_status();" name="Completed" id="ug_Pursuing" type="radio" value="Pursuing"
caption="Pursuing" <?php echo $userarray['UGongoing']?>/>Pursuing
        </div>
        <div>
            <div class="errorMsg" id="ug_status_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
            <select style="font-size:11px;width:150px"
	    onchange="trackEventByGA('CourseDropDownClick','course drop down clicked');" name = "ug_detials_courses"
validate = "validateSelect" required = "true" caption = "graduation course"
id="ug_detials_courses">
            <option value="">Select</option>
            <?php echo $course_lists; ?>
            </select>
            <select tip="<?php echo (!empty($userarray['UGongoing'])?"ug_passed":"ug_completed");?>"
blurMethod="ug_detials_courses_marks();" style="font-size:11px;" name = "ug_detials_courses_marks" validate =
"validateSelect" required = "true" caption = "marks" id="ug_detials_courses_marks"><option value="">Marks</option>
            <?php
                for ($i = 100; $i >= 33; $i--) {
                    if (isset($ug_marks)) {
                        if ($ug_marks == $i) {
                            $flag_marks = "selected";
                        } else {
                            $flag_marks = "";
                        }
                    }
                    echo "<option ".$flag_marks." value='".$i."'>" . $i . "%</option>";
                }
            ?>
            </select>
        </div>
        <div>
            <div class="errorMsg" id="ug_detials_courses_error" style="*padding-left:4px"></div>
        </div>
        <div>
        <div class="errorMsg" id="ug_detials_courses_marks_error" style="*padding-left:4px"></div>
        </div>
            <?php
            if ($userarray['UGongoing'] == 'checked') {
            ?>
            <script>
                document.getElementById('ug_detials_courses_marks').style.display = "none";
                document.getElementById('ug_detials_courses_marks_error').style.display = "none";
            </script>
            <?php
            }
            ?>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>