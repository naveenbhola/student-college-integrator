<li>
	<label>Graduation Completion Year:</label>
	<div class="flLt">
		<!--<p class="flLt">Course: &nbsp;</p>
		<div class="course-box" id="ug_course_holder">
			<ol>
				<li><input type="checkbox" onClick="checkUncheckChilds1(this, 'ug_course_holder')" id="all_ug_course" /> All</li>
				< ?php
					global $ug_course_array;
					foreach($ug_course_array as $value) {
						echo '<li><input type="checkbox" name="ug_course[]" onClick="uncheckElement1(this,\'all_ug_course\',\'ug_course_holder\');" value="'.$value.'"/>'.$value.'</li>';
					}
				?>
			</ol>
		</div>-->
		<!--<div class="clearFix spacer15"></div>-->
		<div>
			From: <select id="gradStartYear" name="gradStartYear" onchange="validateYearRange('grad');">
				<option value="">Year</option>
				<?php
				for($i=date("Y",time())+3;$i>=1990;$i--)
                                {
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
				?>
			</select>
			&nbsp;
			To: <select id="gradEndYear" name="gradEndYear" onchange="validateYearRange('grad');">
				<option value="">Year</option>
				<?php
				for($i=date("Y",time())+3;$i>=1990;$i--)
                                {
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
				?>
			</select>
		</div>
		<!--<div class="clearFix spacer15"></div>-->
		<!--<div>
			Marks: <select id="ug_marks" name="ug_marks" onChange="EnableDisableCheckBox(this)">
					<option value="">Select</option>
					< ?php
						for($i=30;$i<100;$i+=5)
						{
							echo '<option value="'.$i.'">&gt;'.$i.'%</option>';
						}
					?>
				</select> &nbsp;
				<input type="checkbox" id="gradResultAwaited" name="gradResultAwaited" value="result_awaited" disabled="true" /> includes students with result awaited
		</div>-->
	</div>
</li>
<li>
	<label>XII Completion Year:</label>
	<div class="flLt">
		<!--<p class="flLt">Stream: </p>-->
		<!--<input type="checkbox" id="science_stream" name="12_stream[]" value="science"/> Science &nbsp;&nbsp;-->
		<!--<input type="checkbox" id="arts_stream" name="12_stream[]" value="arts"/> Arts &nbsp;&nbsp;-->
		<!--<input type="checkbox" id="commerce_stream" name="12_stream[]" value="commerce"/> Commerce &nbsp;&nbsp;-->
		<!--<div class="spacer15 clearFix"></div>-->
		<div>
			From: <select id="XIIStartYear" name="XIIStartYear" onchange="validateYearRange('XII');">
				<option value="">Year</option>
				<?php
				for($i=date("Y",time())+3;$i>=1990;$i--)
                                {
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
				?>
			</select>
			&nbsp;
			To: <select id="XIIEndYear" name="XIIEndYear" onchange="validateYearRange('XII');">
				<option value="">Year</option>
				<?php
				for($i=date("Y",time())+3;$i>=1990;$i--)
                                {
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
				?>
			</select>
		</div>
		<!--<div>-->
		<!--	Marks: &nbsp;-->
		<!--		<select id="marks_twelve" name="marks_twelve">-->
		<!--			<option value="">Select</option>-->
		<!--			< ?php-->
		<!--				for($i=30;$i<100;$i+=5)-->
		<!--				{-->
		<!--					echo '<option value="'.$i.'">&gt;'.$i.'%</option>';-->
		<!--				}-->
		<!--			?>-->
		<!--		</select>-->
		<!--</div>-->
	</div>
</li>

<script type="text/javascript">
function validateYearRange(level)
{
	var formobj = $('formForaddNewUserset');
	if (formobj[level +'StartYear'].value == "" || formobj[level +'EndYear'].value == "") {
		return;
	}
	else {
		if(parseInt(formobj[level +'EndYear'].value) >= parseInt(formobj[level +'StartYear'].value)) {
			return;
		}
		else {
			alert("Please select an end year value greater than or equal to start year");
			formobj[level +'EndYear'].focus();
			selectComboBox(formobj[level +'EndYear'],"");
			return;
		}
	}   
}
</script>