
	<div class="find-field-row" id="graduationDetailsHolder" style="display:none;">
	<label>Graduation Status: </label>
	<div class="mformCont">
		<input onclick="ug_status_abroad();" blurMethod="ug_status_abroad();" name="Completed" id="ug_Completed_abroad" type="radio" value="completed" caption="Completed" <?php echo $userarray['UGcompleted']?> checked /> Completed &nbsp;
        <input onclick="ug_status_abroad();" blurMethod="ug_status_abroad();" name="Completed" id="ug_Pursuing_abroad" type="radio" value="Pursuing" caption="Pursuing" <?php echo $userarray['UGongoing']?>/> Pursuing
    </div>
    <div class="clearFix"></div>
    <div class="errorMsg" id="ug_status_error"></div>
	
    <div class="clearFix spacer10"></div>
    <!--  label>Graduation Details :</label-->
    <div class="formCont">
    	<select name = "ug_detials_courses" onchange="trackEventByGA('CourseDropDownClick','course drop down clicked');" validate = "validateSelect" caption = "UG course" id="ug_detials_courses_abroad">
            <option value="">Graduation Course</option>
            <?php echo $course_lists; ?>
            </select>
            <div>
            	<div class="errorMsg" id="ug_detials_courses_abroad_error"></div>
            </div>
        
            <div class="clearFix spacer10"></div>
            
            
            
            <div>
            <select  blurMethod="ug_detials_courses_marks();" name = "ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="ug_detials_courses_marks_abroad"><option value="">Graduation Marks</option>
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
            <div>
        <div class="errorMsg" id="ug_detials_courses_marks_abroad_error"></div>
        </div>
        <div class="clearFix spacer10"></div>
        </div>
        
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
		<div id="completion_date_block">
        <!-- label>Completion Date :</label-->
        
		<div class="formCont">
    <?php
        if (isset($CourseCompletionDate)) {
            list($y_c, $m_c, $d_c) = explode('-', $CourseCompletionDate);
        }
        $str ='<select validate = "validateSelect" caption = "month of course completion" id="com_year_month_abroad" name="com_year_month" >';
        $str.='<option value="">Graduation Month</option>';
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
    <div>
    <div class="errorMsg" id="com_year_month_abroad_error"></div>
    </div>

     <div class="clearFix spacer10"></div>
    <select  blurMethod="validate_Completion_Date();" caption = "year of course completion" id="com_year_year_abroad" name="com_year_year">
    <option value="">Graduation Year</option>
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
    <div>
    <div class="errorMsg" id="com_year_year_abroad_error"></div>
    </div>
    </div>
    
    
	</div>
    </div>


