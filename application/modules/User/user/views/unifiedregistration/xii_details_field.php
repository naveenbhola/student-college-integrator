<div id="xiiDetailsHolder_unifiedregistration" style="display:none">
<?php
if ((count($userCompleteDetails) > 0) && (isset($validateuser[0]['firstname']))) {
    $age = $userCompleteDetails[0]['age'];
    $gender = $userCompleteDetails[0]['gender'];
    foreach ($userCompleteDetails[0]['EducationData'] as $xii_data) {
	if ( $xii_data['Level'] == '12' ) {
	    $Marks = $xii_data['Marks'];
	    $MarksType = $xii_data['MarksType'];
	    $Name = $xii_data['Name'];
	    $date = $xii_data['CourseCompletionDate'];
	    list($y_c, $m_c, $d_c) = explode('-', $date);
	    $date = $y_c;
	}
    }
    $otherdetails = $userCompleteDetails[0]['PrefData'][0]['UserDetail'];
}
?>
<div>
    <div class="float_L" style="width:165px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Std. XII Stream<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:163px">
        <div>
            <input name="science_stream" blurmethod="ShikshaUnifiedRegistarion.xii_stream_check();" onclick="ShikshaUnifiedRegistarion.xii_stream_check();" <?php if ($Name == 'science') { echo "checked"; } ?> id="science_stream_unifiedregistration" type="radio" value="science" />Science&nbsp;
            <input blurmethod="ShikshaUnifiedRegistarion.xii_stream_check();" onclick="ShikshaUnifiedRegistarion.xii_stream_check();" name="science_stream" <?php if ($Name == 'arts') { echo "checked"; } ?> id="science_arts_unifiedregistration" type="radio" value="arts"  />Arts&nbsp;
            <input blurmethod="ShikshaUnifiedRegistarion.xii_stream_check();" onclick="ShikshaUnifiedRegistarion.xii_stream_check();" name="science_stream" <?php if ($Name == 'commerce') { echo "checked"; } ?> id="science_commerce_unifiedregistration" type="radio" value="commerce"  />Commerce
        </div>
		<div class="errorPlace" style=""><div id= "science_commerce_unifiedregistration_error" class="errorMsg" style="padding-left:8px"></div></div>
		<div class="errorPlace" style=""><div id= "science_arts_unifiedregistration_error" class="errorMsg" style="padding-left:8px"></div></div>
		<div class="errorPlace" style=""><div id= "science_stream_unifiedregistration_error" class="errorMsg" style="padding-left:8px"></div></div>
	</div>
<div class="clear_L withClear">&nbsp;</div>
</div>
<div id="completion_date_block_ls" class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:165px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Std. XII Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:163px">
        <div>
	<select  validate = "validateSelect" caption = " XII completion year" id="10_com_year_year_unifiedregistration" name="10_com_year_year">
	<option value="">Year</option>
	<?php
	for($i= (date("Y")); $i >= 1990; $i--) {
	    if ($i == $date) {
		echo "<option  selected value='$i'>".$i."</option>";
	    } else {
		echo "<option value='$i'>".$i."</option>";
	    }                                    
	}
	?>
	</select>
	<select caption="XII marks" style="font-size:11px;width:100px" name = "10_ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="10_ug_detials_courses_marks_unifiedregistration"><option value="">Marks</option>
	<?php
	for ($i = 100; $i >= 33; $i--) {
	    if ($Marks == $i) {
		echo "<option selected value='".$i."'>" . $i . "%</option>";
	    } else {
		echo "<option value='".$i."'>" . $i . "%</option>";
	    }                                    
	}
	?>
	</select>
	</div>
        <div>
	    <div class="errorMsg" id= "10_com_year_year_unifiedregistration_error"  style="padding-left:4px;*margin-left:4px"></div>
	    <div class="errorMsg" id= "10_ug_detials_courses_marks_unifiedregistration_error" style="padding-left:4px;*margin-left:4px"></div>
	</div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div  class="lineSpace_10">&nbsp;</div>
</div>
