<div class="find-field-row" id="xiiDetailsHolder" style="display:none;">
	<label>Std. XII Stream:</label>
	<div class="formCont">
	<input name="science_stream" blurmethod="xii_stream_check();" onclick="xii_stream_check();" <?php if ($Name == 'science') { echo "checked"; } ?> id="science_stream_abroad" type="radio" value="science" /> Science&nbsp; 
            <input blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream" <?php if ($Name == 'arts') { echo "checked"; } ?> id="science_arts_abroad" type="radio" value="arts"  /> Arts&nbsp; 
            <input blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream" <?php if ($Name == 'commerce') { echo "checked"; } ?> id="science_commerce_abroad" type="radio" value="commerce"  /> Commerce
		<div class="clearFix"></div>
		<div class="errorPlace"><div id= "science_commerce_abroad_error" class="errorMsg"></div></div>
		<div><div id= "science_arts_abroad_error" class="errorMsg"></div></div>
		<div><div id= "science_stream_abroad_error" class="errorMsg"></div></div>
	</div>
	<div class="clearFix spacer10"></div>
	<!--<div id="completion_date_block_ls" class="lineSpace_10">&nbsp;</div>-->
	<label>Std. XII Details:</label>
    <div class="formCont">
	<select  validate = "validateSelect" caption = " XII completion year" id="10_com_year_year_abroad" name="10_com_year_year" style="width:80px !important">
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
	</select> &nbsp; 
	<select style="width:80px !important" name = "10_ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="10_ug_detials_courses_marks_abroad"><option value="">Marks</option>
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
	<div class="clearFix"></div>
	    <div><div class="errorMsg" id= "10_com_year_year_abroad_error"></div></div>
	    <div><div class="errorMsg" id= "10_ug_detials_courses_marks_abroad_error"></div></div>
	</div>
    </div>

