<div class="lineSpace_10">&nbsp;</div>
<div>
<div class="float_L" style="width:35%;line-height:18px;">
<div class="txt_align_r" style="padding-right:5px">Std. XII Stream<b class="redcolor">*</b>:</div>
</div>
<div style="width:63%;float:right;text-align:left;">
<div><input name="science_stream" blurmethod="xii_stream_check();" onclick="xii_stream_check();" <?php if ($Name
== 'science') { echo "checked"; } ?> id="science_stream" type="radio" value="science_stream" />Science&nbsp;<input
blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream"
<?php if ($Name == 'arts') { echo "checked"; } ?> id="science_arts" type="radio" value="science_arts"
/>Arts&nbsp;<input blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream" <?php if ($Name
== 'commerce') { echo "checked"; } ?> id="science_commerce" type="radio" value="science_commerce"  />Commerce</div>
<div class="errorPlace" style=""><div id= "science_commerce_error" class="errorMsg"></div></div>
<div class="errorPlace" style=""><div id= "science_arts_error" class="errorMsg"></div></div>
<div class="errorPlace" style=""><div id= "science_stream_error" class="errorMsg"></div></div>
</div>
</div>
<div class="clear_L withClear">&nbsp;</div>

<div id="completion_date_block_ls" class="lineSpace_10">&nbsp;</div>
<div>
<div class="float_L" style="width:35%;line-height:18px;">
<div class="txt_align_r" style="padding-right:5px">Std. XII Details<b class="redcolor">*</b>:</div>
</div>
<div style="width:63%;float:right;text-align:left;">
<div>
<select  validate = "validateSelect" required="true" caption = " XII completion year" id="10_com_year_year"
id="10_com_year_year" name="10_com_year_year">
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
<select required="true" caption="XII marks" style="font-size:11px;width:100px" name =
"10_ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="10_ug_detials_courses_marks"><option
value="">Marks</option>
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
	    <div class="errorMsg" id= "10_com_year_year_error"  style="*margin-left:4px"></div>
	    <div class="errorMsg" id= "10_ug_detials_courses_marks_error" style="*margin-left:4px"></div>
	</div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>