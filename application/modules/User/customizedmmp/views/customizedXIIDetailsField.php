<div id="xiiDetailsHolder" style="display:none">
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
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Std. XII Stream<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div style="float:left;">
            <input name="science_stream" style="margin-left:0px;height:auto;" blurmethod="xii_stream_check();" onclick="xii_stream_check();" <?php //if ($Name == 'science') { echo "checked"; } ?> id="science_stream" type="radio" value="science" />
		</div>
		<div style="float:left;margin-top:3px;">
			Science&nbsp;
		</div>
		<div style="float:left;">
			<input blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream" <?php //if ($Name == 'arts') { echo "checked"; } ?> id="science_arts" type="radio" value="arts"  />
		</div>
		<div style="float:left;margin-top:3px;">
			Arts&nbsp;
		</div>
		<div style="float:left;">
			<input blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream" <?php //if ($Name == 'commerce') { echo "checked"; } ?> id="science_commerce" type="radio" value="commerce"  />
		</div>
		<div style="float:left;margin-top:3px;">
			Commerce
		</div>
		<div style="clear:both;"></div>
		<div class="errorPlace" style="float:left;"><div id= "science_commerce_error" class="errorMsg"></div></div>
		<div class="errorPlace" style="float:left;"><div id= "science_arts_error" class="errorMsg"></div></div>
		<div class="errorPlace" style="float:left;"><div id= "science_stream_error" class="errorMsg"></div></div>
	</div>
<div class="clear_L withClear">&nbsp;</div>
</div>
<div style="clear:both"></div>
<div id="completion_date_block_ls" class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Std. XII Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="float:right;width:63%;text-align:left;">
        <div>
	<select style="width:50%;" validate = "validateSelect" caption = " XII completion year" id="10_com_year_year" id="10_com_year_year" name="10_com_year_year">
		<option value="">Year</option>
		<?php
		for($i= (date("Y")); $i >= 1990; $i--) {
			if ($i == $date) {
			//echo "<option  selected value='$i'>".$i."</option>";
			echo "<option  value='$i'>".$i."</option>";
			} else {
			echo "<option value='$i'>".$i."</option>";
			}                                    
		}
		?>
	</select>
	<select caption="XII marks" style="font-size:11px;width:45%;" name = "10_ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="10_ug_detials_courses_marks"><option value="">Marks</option>
	<?php
	for ($i = 100; $i >= 33; $i--) {
	    if ($Marks == $i) {
		//echo "<option selected value='".$i."'>" . $i . "%</option>";
		echo "<option value='".$i."'>" . $i . "%</option>";
	    } else {
		echo "<option value='".$i."'>" . $i . "%</option>";
	    }                                    
	}
	?>
	</select>
	</div>
        <div>
			<div class="errorMsg" id= "10_com_year_year_error"  style="*margin-left:4px"></div>
		</div>
		<div>
			<div class="errorMsg" id= "10_ug_detials_courses_marks_error" style="*margin-left:4px"></div>
		</div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div  class="lineSpace_10">&nbsp;</div>
<div  style="clear:both;"></div>
</div>
