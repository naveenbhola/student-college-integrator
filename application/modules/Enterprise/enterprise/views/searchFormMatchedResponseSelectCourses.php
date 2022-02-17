<div style="width:100%">
	<div style="line-height:6px">&nbsp;</div>
	<div>
		<div class="cmsSearch_RowLeft">
            <div style="width:100%">
                <div class="txt_align_r" style="padding-right:5px">Select Courses*:&nbsp;</div>
            </div>
        </div>
		<div class="cmsSearch_RowRight" style="padding-top: 3px;">
			<div style="width:100%">
				<div style="position: relative;">
					<div style="width:40%; float: left;">
						<p style="margin-bottom:5px;">Universities and Colleges</p>
						<div id="all_institutes_holder" style="float:left;width:313px;border:1px solid #CCC;padding:5px 0;height:125px;overflow:auto">
							<div style="display:block;padding-left:5px">
								<input style="float: left; margin:0;" type="checkbox" onClick="checkUncheckChilds1(this, 'all_institutes_holder'); displayMappedCourses('-1','all_institutes','all_institutes_holder');" id="all_institutes" name="institute_id[]" value="-1"/>
								<label for="all_institutes" style="display:block; margin:8px 0 0 20px">All</label>
							</div>
							<div id="institutes_holder">
								<?php foreach($instituteList as $instituteId => $instituteCourseData){ 
										if($instituteCourseData['instituteData']['listing_type'] == 'university') {	?>
								<div style="display:block;padding-left:5px">
									<input style="float: left; margin:0;" id="<?php echo $instituteId; ?>_institute" type="checkbox" name="institute_id[]" value="<?php echo $instituteId; ?>" onClick="uncheckElement1(this,'all_institutes','institutes_holder'); displayMappedCourses('<?php echo $instituteId; ?>','all_institutes','all_institutes_holder');">
									<label for="<?php echo $instituteId; ?>_institute" style="display:block; margin:8px 0 0 20px"><?php echo $instituteCourseData['instituteData']['listing_title']; ?> </label>
								</div>
								<?php } 
									} ?>
								<?php foreach($instituteList as $instituteId => $instituteCourseData){ 
										if($instituteCourseData['instituteData']['listing_type'] == 'institute') {	?>
								<div style="display:block;padding-left:5px">
									<input style="float: left; margin:0;" id="<?php echo $instituteId; ?>_institute" type="checkbox" name="institute_id[]" value="<?php echo $instituteId; ?>" onClick="uncheckElement1(this,'all_institutes','institutes_holder'); displayMappedCourses('<?php echo $instituteId; ?>','all_institutes','all_institutes_holder');">
									<label for="<?php echo $instituteId; ?>_institute" style="display:block; margin:8px 0 0 20px"><?php echo $instituteCourseData['instituteData']['listing_title']; ?> </label>
								</div>
								<?php } 
									} ?>
							</div>
						</div>
					</div>
					<div><i class="double-arrw-icon"></i></div>
					<div style="margin-left:310px;">
						<p style="margin-left:60px; margin-bottom:5px;">Courses Offered</p>
						<div id="all_courses_holder" style="float:left;width:313px;margin-left:60px;border:1px solid #CCC;padding:5px 0;height:125px;overflow:auto">
							<div id="all_holder" style="display:none;padding-left:5px">
								<input style="float: left; margin:0;" type="checkbox" onClick="checkUncheckChilds1(this, 'all_courses_holder')" id="all_courses" name="course_id[]" value="-1"/>
								<label for="all_courses" style="display:block; margin:8px 0 0 20px;">All Courses </label>
							</div>
							<div id="institute_courses_holder">
								<?php foreach($instituteList as $instituteId => $instituteCourseData){
										if($instituteCourseData['instituteData']['listing_type'] == 'university') {	?>
									<div id="<?php echo $instituteId; ?>_institute_holder" style="display:none;padding-left:5px">
										<input style="float: left; margin:0;" id="<?php echo $instituteId; ?>_all_courses" type="checkbox" name="institute_id[]" value="<?php echo $instituteId; ?>" onClick="checkUncheckChilds1(this, '<?php echo $instituteId; ?>_institute_holder'); uncheckElement1(this, 'all_courses', 'institute_courses_holder');">
										<label for="<?php echo $instituteId; ?>_all_courses" style="display:block; margin:8px 0 0 20px;" ><?php echo $instituteCourseData['instituteData']['listing_title']; ?> </label>
											<div id="<?php echo $instituteId; ?>_courses_holder" style="display:block;padding-left:18px">
											<?php foreach ($instituteCourseData['courseList'] as $index => $courseDetails) { ?>
												<div>
													<input id="<?php echo $courseDetails['id']; ?>" style="float: left; margin:0;" type="checkbox" name="course_id[]" onClick="uncheckElement1(this, '<?php echo $instituteId; ?>_all_courses', '<?php echo $instituteId; ?>_courses_holder'); uncheckElement1(this, 'all_courses', 'institute_courses_holder');" value="<?php echo $courseDetails['id'].'|'.$courseDetails['name'].'|'.$instituteCourseData['instituteData']['listing_title']; ?>" />
													<label for="<?php echo $courseDetails['id']; ?>" style="display:block; margin:8px 0 0 20px"><?php echo $courseDetails['name']; ?> </label>
												</div>
											<?php } ?>
											</div>
									</div>
									<?php } 
									} ?>

									<?php foreach($instituteList as $instituteId => $instituteCourseData){
										if($instituteCourseData['instituteData']['listing_type'] == 'institute') {	?>
									<div id="<?php echo $instituteId; ?>_institute_holder" style="display:none;padding-left:5px">
										<input style="float: left; margin:0;" id="<?php echo $instituteId; ?>_all_courses" type="checkbox" name="institute_id[]" value="<?php echo $instituteId; ?>" onClick="checkUncheckChilds1(this, '<?php echo $instituteId; ?>_institute_holder'); uncheckElement1(this, 'all_courses', 'institute_courses_holder');">
										<label for="<?php echo $instituteId; ?>_all_courses" style="display:block; margin:8px 0 0 20px;" ><?php echo $instituteCourseData['instituteData']['listing_title']; ?> </label>
											<div id="<?php echo $instituteId; ?>_courses_holder" style="display:block;padding-left:18px">
											<?php foreach ($instituteCourseData['courseList'] as $index => $courseDetails) { ?>
												<div>
													<input id="<?php echo $courseDetails['id']; ?>" style="float: left; margin:0;" type="checkbox" name="course_id[]" onClick="uncheckElement1(this, '<?php echo $instituteId; ?>_all_courses', '<?php echo $instituteId; ?>_courses_holder'); uncheckElement1(this, 'all_courses', 'institute_courses_holder');" value="<?php echo $courseDetails['id'].'|'.$courseDetails['name'].'|'.$instituteCourseData['instituteData']['listing_title']; ?>" />
													<label for="<?php echo $courseDetails['id']; ?>" style="display:block; margin:8px 0 0 20px"><?php echo $courseDetails['name']; ?> </label>
												</div>
											<?php } ?>
											</div>
									</div>
									<?php } 
									} ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
	</div>                
</div>
<div style="line-height:6px">&nbsp;</div>

<script>
	var instituteList = <?php echo json_encode($instituteList); ?>;	
	
	function checkUncheckChilds1(obj, checkBoxesHolder)
	{
		var checkBoxes = document.getElementById(checkBoxesHolder).getElementsByTagName("input");
		for(var i=0;i<checkBoxes.length;i++)
		{
			if(checkBoxes[i].checked!=obj.checked)
			{
				checkBoxes[i].checked = obj.checked;
			}
		}
	}
	
	function uncheckElement1(obj ,id, holderId)
	{
		var allChecked = false;
		if(!obj.checked) {
			if(document.getElementById(id).checked) {
				document.getElementById(id).checked = false;
			}
		}
		var checks =  document.getElementById(holderId).getElementsByTagName("input");
		var boxLength = checks.length;
		var chkAll = document.getElementById(id);
		for ( i=0; i < boxLength; i++ )
		{
			if (checks[i].parentNode.style.display == 'none' || checks[i].parentNode.parentNode.style.display == 'none' || checks[i].parentNode.parentNode.parentNode.style.display == 'none') {
				continue;
			}
			else {
				if ( checks[i].checked == true ) {
					allChecked = true;
					continue;
				}
				else {
					allChecked = false;
					break;
				}
			}
		}
		if ( allChecked == true )
			chkAll.checked = true;
		else
			chkAll.checked = false;
	}
	
	function displayMappedCourses(instituteId, id, holderId)
	{
		var displayAll = false;
		var allCoursesElement = document.getElementById('all_holder');
		var allInstitutesElement = document.getElementById('all_institutes');
		var inputChks =  document.getElementById(holderId).getElementsByTagName("input");
		var boxLength = inputChks.length;
			
		if (instituteId == '-1') {
			var allDivs = document.getElementById("institute_courses_holder").getElementsByTagName("div");
			var NoOfDivs = allDivs.length;
			var allInputs = document.getElementById("institute_courses_holder").getElementsByTagName("input");
			var NoOfInputs = allInputs.length;
			
			for ( i=0; i < NoOfInputs; i++ ) {
				allInputs[i].checked = false;
			}
			if (allInstitutesElement.checked == true) {
				document.getElementById("all_courses").checked = false;
				for ( i=0; i < NoOfDivs; i++ ) {
					allDivs[i].style.display = 'block';
				}
				allCoursesElement.style.display = 'block';
			}
			else{
				for ( i=0; i < NoOfDivs; i++ ) {
					allDivs[i].style.display = 'none';
				}
				allCoursesElement.style.display = 'none';
			}
		}
		else {
			var instituteElement = document.getElementById(instituteId+'_institute');
			var holderElement = document.getElementById(instituteId+'_institute_holder');
			var allInputElements = holderElement.getElementsByTagName("input");
			var inputNumber = allInputElements.length;
			var allDivElements = holderElement.getElementsByTagName("div");
			var divNumber = allDivElements.length;
			
			for ( i=0; i < inputNumber; i++ ) {
				allInputElements[i].checked = false;
			}
				
			if (instituteElement.checked == true) {
				document.getElementById("all_courses").checked = false;
				holderElement.style.display = 'block';
				for ( i=0; i < divNumber; i++ ) {
					allDivElements[i].style.display = 'block';
				}
			}
			else{
				holderElement.style.display = 'none';
				for ( i=0; i < divNumber; i++ ) {
					allDivElements[i].style.display = 'none';
				}
			}
			for ( i=0; i < boxLength; i++ ) {
				if ( inputChks[i].checked == true ) {
					displayAll = true;
					break;
				}
				else {
					displayAll = false;
					continue;
				}
			}
			if (displayAll == true) {
				allCoursesElement.style.display = 'block';
				uncheckElement1(instituteId+'_all_courses', 'all_courses', 'institute_courses_holder');
			}
			else {
				allCoursesElement.style.display = 'none';
			}
		}
	}
</script>