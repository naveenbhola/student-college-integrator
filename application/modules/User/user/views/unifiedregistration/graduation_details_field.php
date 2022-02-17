<div id="graduationDetailsHolder_unifiedregistration" style="display:none">
<div>
    <div class="float_L" style="width:165px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Status<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:163px">
        <div>
            <input onclick="ShikshaUnifiedRegistarion.ug_status();" blurMethod="ShikshaUnifiedRegistarion.ug_status();" name="Completed" id="ug_Completed_unifiedregistration" type="radio" value="completed" caption="Completed" <?php echo $userarray['UGcompleted']?> checked />Completed &nbsp;
            <input onclick="ShikshaUnifiedRegistarion.ug_status();" blurMethod="ShikshaUnifiedRegistarion.ug_status();" name="Completed" id="ug_Pursuing_unifiedregistration" type="radio" value="Pursuing" caption="Pursuing" <?php echo $userarray['UGongoing']?>/>Pursuing
        </div>
        <div>
            <div class="errorMsg" id="ug_status_unifiedregistration_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:165px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:163px">
        <div>
            <select style="font-size:11px;width:120px" name = "ug_detials_courses" onchange="trackEventByGA('CourseDropDownClick','course drop down clicked');" validate = "validateSelect" caption = "your graduation course" id="ug_detials_courses_unifiedregistration">
            <option value="">Course</option>
            <?php echo $course_lists; ?>
            </select>
            <select  blurMethod="ShikshaUnifiedRegistarion.ug_detials_courses_marks();" style="font-size:11px;" name = "ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="ug_detials_courses_marks_unifiedregistration"><option value="">Marks</option>
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
            <div class="errorMsg" id="ug_detials_courses_unifiedregistration_error" style="padding-left:4px;*padding-left:4px"></div>
        </div>
        <div>
        <div class="errorMsg" id="ug_detials_courses_marks_unifiedregistration_error" style="padding-left:4px;*padding-left:4px"></div>
        </div>
            <?php
            if ($userarray['UGongoing'] == 'checked') {
            ?>
            <script>
                document.getElementById('ug_detials_courses_marks_unifiedregistration').style.display = "none";
                document.getElementById('ug_detials_courses_marks_unifiedregistration_error').style.display = "none";
            </script>
            <?php
            }
            ?>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div id="completion_date_block">
    <div class="float_L" style="width:165px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Completion Date<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:163px">
    <div>
    <?php
        if (isset($CourseCompletionDate)) {
            list($y_c, $m_c, $d_c) = explode('-', $CourseCompletionDate);
        }
        $str ='<select validate = "validateSelect" caption = "expected date of course completion" id="com_year_month_unifiedregistration" name="com_year_month" >';
        $str.='<option value="">Month</option>';
        $this_month = $m_c;
        $months=range(1,12);
        foreach($months as $month){
        $month = sprintf("%02d",$month);
        $str.=' <option value="'.$month.'"';
        if($month==$this_month) $str.=' selected="selected"';
        $str.='>'.date('M',mktime(0,0,0,$month,1,2006)).'</option>'."\n";
        }
        $str.='</select>'."\n";
        echo $str;
    ?>
    </select>
    <select  blurMethod="ShikshaUnifiedRegistarion.validate_Completion_Date();" validate = "validateSelect" caption = "year" id="com_year_year_unifiedregistration" name="com_year_year">
    <option value="">Year</option>
    <?php
        for($i= date("Y")+5; $i>= 1950; $i--) {
            if ($y_c == $i) {
                echo "<option selected value='$i'>".$i."</option>";
            } else {
                echo "<option  value='$i'>".$i."</option>";
            }
        }
    ?>
    </select>
    </div>
        <div>
            <div class="errorMsg" id="com_year_month_unifiedregistration_error" style="padding-left:4px;*padding-left:4px"></div>
        </div>
        <div>
            <div class="errorMsg" id="com_year_year_unifiedregistration_error" style="padding-left:4px;*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div id="completion_date_block_ls" class="lineSpace_10">&nbsp;</div>
</div>
