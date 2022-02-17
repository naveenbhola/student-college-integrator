<?php $locationList = json_decode($locationList);
$cityList = $locationList->cityList;
$stateList = $locationList->stateList;
?>
<style>.disabled {pointer-events: none;cursor: default;}</style>
<div class="mentorship-widget enroll-form-widget" id="getMentorForm" style="display: none;position: relative;">
	<div style="position:relative">
	<i class="mentorship-sprite left-border"></i>
		<h2>ENROLL TO GET A MENTOR</h2>
	<i class="mentorship-sprite right-border"></i>
    </div>
    <div class="enroll-details" id="_regForm">
	<?php if(isset($userId) && $userId>0 && !$isMentee){?>
		<!---existMentee form--->
		<form action="" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
                <?php $this->load->view('mentorship/existMenteeForm');?>
		</form>
                <!--end--->   
	<?php }?>
    </div>
    
    <div class="enroll-details" id="_yrPref" <?php if(isset($userId) && $userId>0 && !$isMentee){?> style="display: block;" <?php }else{?> style="display: none;" <?php }?>>
	<p class="detail-title">Your Preferences  </p>
	<ul>
		<li>
		<div style="width:35%" class="enroll-detail-col-2">
			<label class="dafault-pointer"> Engineering exams you have taken / planned to take <span style="color:#ff0000;">*</span></label>
			<div id="examList_m" class="enroll-dropdown customInputs" onclick="openDropDown('examList',$j(this));" onmouseleave="hideDropDown('examList','scrollbar1',$j(this));">
				<i class="mentorship-sprite ic-downarw easeall3s"></i>
				<span id="examListH">Select entrance exams</span>
				
				<div id="examList" class="year-field-layer" style="left:-1px; display: none; height:173px">
				<div class="scrollbar1" style="display: none;">
					<div class="scrollbar" style="height: 170px;">
						<div class="track">
							<div id="thumbbranch" class="thumb"></div>
						    </div>
					</div>
					<div class="viewport" style="height: 170px; width: 60%;">  
						<div class="overview">
							<ul style="display: block; border:0; width:242px" id="menteeExamList">
								<?php $i=0;foreach($exam_list as $exam_list){?>
								<li><a  refId="<?php echo $i;?>">
								
								<input type="checkbox" value="<?php echo $exam_list;?>" id="<?php echo $i;?>" name="exam[]">
								<label class="mentee-exam-width flLt" for="<?php echo $i;?>">
								<span class="common-sprite" style="position:relative; top:6px; margin-right:3px;"></span><?php echo $exam_list;?>
								</label>
								</a>
								<input type="text" id="input_<?php echo $i;?>" placeholder="Enter Score" class="flRt mentee-exam-field" style="display: none;" maxlength="5" minlength="1" onkeyup="validateExamScore();"><br/>
								<div class="menterror clear-width" id="error_<?php echo $i;?>"></div>
								</li>
								<?php $i++;}?>
								<li>
									<a href="javascript:void(0);" refId="other">
										<input type="checkbox" id="other" value="other" name=exam[]>
										<label class="mentee-exam-width flLt" for="other">
										<span class="common-sprite" style="position:relative; top:6px; margin-right:3px;"></span>Other</label>
									</a>
										<input type="text" id="inputExam_other" placeholder="Enter Exam" class="flRt mentee-exam-field" style="display: none;" maxlength="50" onkeyup="$j('#other').val($j(this).val());validateExamScore();"><br/>
										<input type="text" id="input_other" placeholder="Enter Score" class="flRt mentee-exam-field" style="display: none;" maxlength="5" minlength="1" onkeyup="validateExamScore();">
								</li>
								<li>
									<a href="javascript:void(0);" refId="nothave">
										<input type="checkbox" id="nothave" value="Haven\'t taken any exam" name=exam[]>
										<label class="mentee-exam-width flLt" for="nothave" style="width: 150px !important;">
										<span class="common-sprite" style="position:relative; top:6px; margin-right:3px;"></span>Haven't taken any exam</label>
								</a>
										<input type="hidden" id="input_nothave" placeholder="Enter Score" class="flRt mentee-exam-field" style="display: none;" maxlength="5" minlength="1">
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
				
			</div>
			<div class="menterror clear-width"><div id="examList_error" style="display: none;">Please select at least 1 exam</div></div>
			<div class="menterror clear-width"><div id="examList_score_error" style="display: none;"></div></div>
		
		</div>
		
		<div class="enroll-detail-col-2">
			<label class="dafault-pointer">Preferred college location  <span style="color:#9f9f9f">(Select atleast 1)</span></label>
			
			<div class="preference-dropdwn flLt">
			<div id="prefCL1_m" class="enroll-dropdown customInputs" onclick="openDropDown('prefCL1',$j(this));" onmouseleave="hideDropDown('prefCL1','scrollbar1',$j(this));" id="prefCL1Title" style="width:185px !important;">
			<i class="mentorship-sprite ic-downarw easeall3s"></i>
			<span id="prefCL1H">Preference 1</span>
			
			<div id="prefCL1" class="year-field-layer" style="width:163px; left:-1px; top:24px !important; display: none; height:190px;">
					<div class="mentor-search-field"><input type="text" id="key1" class="mentor-search-area" onkeyup="searchLocation('key1','locationList1','p','state1','city1','prefCL1');"></div>					
					<div class="scrollbar1" style="display: none;">
					<div class="scrollbar" style="height: 160px;">
						<div class="track">
							<div id="thumbbranch" class="thumb"></div>
						    </div>
					</div>
					<div class="viewport" style="height: 160px; width: 90%;">
						<div class="overview">
							<ul style="display: block; width:158px; border:0 none">
								<li>
									<div class="city-state-list locationList1" id="state1">
										<strong id="state1_H">State</strong>
									<?php if(count($stateList->stateList)>0) {foreach($stateList->stateList as $state){?>
										<p onclick="selectCity('prefCL1','<?php echo $state->stateName;?>');"><?php echo $state->stateName;?></p>
									<?php }}?>
									</div>
									<div class="city-state-list locationList1" id="city1">
										<strong id="city1_H">City</strong>
									    <?php if(count($cityList->cityList)>0) {foreach($cityList->cityList as $city){?>
										<p onclick="selectCity('prefCL1','<?php echo $city->cityName;?>');"><?php echo $city->cityName;?></p>
									<?php }}?>
									</div>
								</li>
								<input type="hidden" id="prefCL1_value" class="_mntHV">
							</ul>
						</div>
					</div>
				</div>
			</div>
			</div>
			<div class="menterror clear-width"><div id="prefCL1_error" style="display: none;">Please select preference 1</div></div>
		    </div>
		    <div class="preference-dropdwn flLt">
		    <div id="prefCL2_m" class="enroll-dropdown preference-dropdwn customInputs" onclick="openDropDown('prefCL2',$j(this));" onmouseleave="hideDropDown('prefCL2','scrollbar1',$j(this));" style="width:185px !important">
			<i class="mentorship-sprite ic-downarw easeall3s"></i>
			<span id="prefCL2H">Preference 2</span>
			<div id="prefCL2" class="year-field-layer" style="width:163px; left:-1px; top:24px !important; display: none;height:190px;">
					<div class="mentor-search-field"><input type="text" id="key2" class="mentor-search-area" onkeyup="searchLocation('key2','locationList2','p','state2','city2','prefCL2');"></div>					
					<div class="scrollbar1" style="display: none;">
					<div class="scrollbar" style="height: 160px;">
						<div class="track">
							<div id="thumbbranch" class="thumb"></div>
						    </div>
					</div>
					<div class="viewport" style="height: 160px; width: 90%;">
						<div class="overview">
							<ul style="display: block; width:158px; border:0 none">
								<li>
									<div class="city-state-list locationList2" id="state2">
										<strong id="state2_H">State</strong>
									<?php if(count($stateList->stateList)>0) {foreach($stateList->stateList as $state){?>
										<p onclick="selectCity('prefCL2','<?php echo $state->stateName;?>');"><?php echo $state->stateName;?></p>
									<?php }}?>
									</div>
									<div class="city-state-list locationList2" id="city2">
										<strong id="city2_H">City</strong>
									    <?php if(count($cityList->cityList)>0) {foreach($cityList->cityList as $city){?>
										<p onclick="selectCity('prefCL2','<?php echo $city->cityName;?>');"><?php echo $city->cityName;?></p>
									<?php }}?>
									</div>
								</li>
								<input type="hidden" id="prefCL2_value" class="_mntHV">
							</ul>
						</div>
					</div>
				</div>
			</div>
			
		    </div>
		    </div>
		    <div class="preference-dropdown flLt">
		    <div id="prefCL3_m" style="margin:0; width:185px !important;" class="enroll-dropdown preference-dropdwn customInputs" onclick="openDropDown('prefCL3',$j(this));" onmouseleave="hideDropDown('prefCL3','scrollbar1',$j(this));">
			<i class="mentorship-sprite ic-downarw easeall3s"></i>
			<span id="prefCL3H">Preference 3</span>
			
			<div id="prefCL3" class="year-field-layer" style="width:163px; left:-1px; top:24px !important; display: none;height:190px;">
					<div class="mentor-search-field"><input type="text" id="key3" class="mentor-search-area" onkeyup="searchLocation('key3','locationList3','p','state3','city3','prefCL3');"></div>					
					<div class="scrollbar1" style="display: none;">
					<div class="scrollbar" style="height: 160px;">
						<div class="track">
							<div id="thumbbranch" class="thumb"></div>
						    </div>
					</div>
					<div class="viewport" style="height: 160px; width: 90%;">
						<div class="overview">
							<ul style="display: block; width:158px; border:0 none">
								<li>
									<div class="city-state-list locationList3" id="state3">
										<strong id="state3_H">State</strong>
									<?php if(count($stateList->stateList)>0) {foreach($stateList->stateList as $state){?>
										<p onclick="selectCity('prefCL3','<?php echo $state->stateName;?>');"><?php echo $state->stateName;?></p>
									<?php }}?>
									</div>
									<div class="city-state-list locationList3" id="city3">
										<strong id="city3_H">City</strong>
									    <?php if(count($cityList->cityList)>0) {foreach($cityList->cityList as $city){?>
										<p onclick="selectCity('prefCL3','<?php echo $city->cityName;?>');"><?php echo $city->cityName;?></p>
									<?php }}?>
									</div>
								</li>
								<input type="hidden" id="prefCL3_value" class="_mntHV">
							</ul>
						</div>
					</div>
				</div>
			</div>
			
		    </div>
		</div>
		
	    </li>
	    <li>
		<div style="width:35%;z-index:9;" class="enroll-detail-col-2" id="end_degree">
			<label class="dafault-pointer"> When do you plan to start your engineering degree? <span style="color:#ff0000;">*</span></label>
			<div class="enroll-dropdown customInputs" onclick="openDropDown('menteeExamYr',$j(this));" onmouseleave="hideDropDown('menteeExamYr','scrollbar1',$j(this));" id="menteeExamYr_m">
				<i class="mentorship-sprite ic-downarw easeall3s"></i>
				<span id="menteeExamYrH">Select Year</span>
			
			<div id="menteeExamYr" class="year-field-layer" style="left:-1px; display: none;">
				<div class="scrollbar1" style="display: none;">
					<div class="scrollbar" style="height: 95px; display: none">
						<div class="track">
							<div id="thumbbranch" class="thumb"></div>
						</div>
					</div>
					<div class="viewport" style="height: 95px; width: 90%;">  
						<div class="overview">
							<ul style="display: block; border:0; width:250px" heading="menteeExamYr" class="examYr">
								<?php for($i = date('Y') + 3;$i>= date('Y') ;$i--){?>
								<li><a href="javascript:void(0);"><?php echo $i;?></a></li>
								<?php }?>
								<input type="hidden" id="menteeExamYr_value" class="_mntHV">
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
			<div class="menterror clear-width"><div id="menteeExamYr_error" style="display: none;">Please select starting year of engineering</div></div>
		</div>	
		<div class="enroll-detail-col-2" style="position: relative">
			<label class="dafault-pointer">Preferred engineering branches <span style="color:#9f9f9f">(Select atleast 1)</span></label>
			<div class="preference-dropdwn flLt">
			<div class="enroll-dropdown customInputs" onclick="openDropDown('eng_branch_pref1',$j(this));" onmouseleave="hideDropDown('eng_branch_pref1','scrollbar1',$j(this));" id="eng_branch_pref1_m" style=" width:185px !important">
			<i class="mentorship-sprite ic-downarw easeall3s"></i>
			<span id="eng_branch_pref1H">Preference 1</span>
			
			<div id="eng_branch_pref1" class="year-field-layer" style="width:163px; left:-1px; top:24px !important; display: none;">
				<div class="scrollbar1" style="display: none;">
					<div class="scrollbar" style="height: 95px;">
						<div class="track">
							<div id="thumbbranch" class="thumb"></div>
						    </div>
					</div>
					<div class="viewport" style="height: 95px; width: 90%;">
						<div class="overview">
							<ul style="display: block; width:158px; border:0 none" class="branchlst">
								<?php if(count($branchList)>0){for($k=0;$k<count($branchList);$k++){?>
								<li><a href="javascript:void(0);"><span heading="eng_branch_pref1" style="width: 100%;"><?php echo $branchList[$k];?></span></a></li>
								<?php }}?>
								<li><a href="javascript:void(0);">
								<span class="flLt" style="width:40px;" heading="eng_branch_pref1">Other</span>
								<input type="text" style="width:90px;" refId="eng_branch_pref1" class="mentee-exam-field" onkeyup="typeText($j(this),'eng_branch_pref1');" maxlength="50"></a>
								</li>
								<input type="hidden" id="eng_branch_pref1_value" class="_mntHV">
							</ul>
						</div>
					</div>
				</div>
			</div>
		    </div>
			<div class="menterror clear-width"><div id="eng_branch_pref1_error" style="display: none;">Please select preference 1</div></div><div class="menterror clear-width"><div id="otherB_error" style="display: none;"></div></div>
			</div>
			
			
			<div class="preference-dropdwn flLt">
			
		    <div class="enroll-dropdown customInputs" onclick="openDropDown('eng_branch_pref2',$j(this));" onmouseleave="hideDropDown('eng_branch_pref2','scrollbar1',$j(this));" id="eng_branch_pref2_m" style=" width:185px !important">
			<i class="mentorship-sprite ic-downarw easeall3s"></i>
			<span id="eng_branch_pref2H">Preference 2</span>
			<div id="eng_branch_pref2" class="year-field-layer" style="width:163px; left:-1px; top:24px !important; display: none;">
				<div class="scrollbar1" style="display: none;">
					<div class="scrollbar" style="height: 95px;">
						<div class="track">
							<div id="thumbbranch" class="thumb"></div>
						    </div>
					</div>
					<div class="viewport" style="height: 95px; width: 90%;">
						<div class="overview">
							<ul style="display: block; width:158px; border:0 none" class="branchlst">
								<?php if(count($branchList)>0){for($k=0;$k<count($branchList);$k++){?>
								<li><a href="javascript:void(0);"><span heading="eng_branch_pref2" style="width: 100%;"><?php echo $branchList[$k];?></span></a></li>
								<?php }}?>
								<li><a href="javascript:void(0);">
								<span class="flLt" style="width:40px;" heading="eng_branch_pref2">Other</span><input type="text" style="width:90px;" class="mentee-exam-field" refId="eng_branch_pref2" onkeyup="typeText($j(this),'eng_branch_pref2');" maxlength="50"></a></li>
								<input type="hidden" id="eng_branch_pref2_value" class="_mntHV">
							</ul>
						</div>
					</div>
				</div>
			</div>
		    </div>
		    
		    </div>
			
			<div class="preference-dropdwn flLt">
		    <div style="margin:0; width:185px !important" class="enroll-dropdown customInputs" onclick="openDropDown('eng_branch_pref3',$j(this));" onmouseleave="hideDropDown('eng_branch_pref3','scrollbar1',$j(this));" id="eng_branch_pref3_m">
			<i class="mentorship-sprite ic-downarw easeall3s"></i>
			<span id="eng_branch_pref3H">Preference 3</span>
			<div id="eng_branch_pref3" class="year-field-layer" style="width:163px; left:-1px; top:24px !important; display: none;">
				<div class="scrollbar1" style="display: none;">
					<div class="scrollbar" style="height: 95px;">
						<div class="track">
							<div id="thumbbranch" class="thumb"></div>
						    </div>
					</div>
					<div class="viewport" style="height: 95px; width: 90%;">
						<div class="overview">
							<ul style="display: block; width:158px; border:0 none" class="branchlst">
								
								<?php if(count($branchList)>0){for($k=0;$k<count($branchList);$k++){?>
								<li><a href="javascript:void(0);"><span heading="eng_branch_pref3" style="width: 100%;"><?php echo $branchList[$k];?></span></a></li>
								<?php }}?>
								<li><a href="javascript:void(0);">
								<span class="flLt" style="width:40px;" heading="eng_branch_pref3">Other</span>
								<input type="text" style="width:90px;" class="mentee-exam-field" refId="eng_branch_pref3" maxlength="50" onkeyup="typeText($j(this),'eng_branch_pref3');"></a></li><input type="hidden" id="eng_branch_pref3_value" class="_mntHV">
							</ul>
						</div>
					</div>
				</div>
			</div>
		    </div>
		    </div>
		</div>
		
	    </li> 
	    <li>
		<div style="width:35%" class="enroll-detail-col-2">
			<label class="dafault-pointer">List colleges you are targeting:</label>
			<input type="text" class="enroll-detailField _mntHV" id="clgTarget" placeholder="Enter colleges separated by commas" onkeyup="validateClgTarget('clgTarget');" maxlength="200"><br/>
			<div class="menterror clear-width"><div id="clgTarget_error" style="display: none;">Please enter colleges separated by commas</div></div>
		</div>
	    </li>
	</ul>
	<div class="clearfix tac">
		
		<a style="margin:0; padding:5px 50px;" href="javascript:void(0);" class="get-mentor-btn" id="registrationSubmit_<?php echo $regFormId; ?>" onclick="addPreferences('<?php echo $regFormId; ?>');">Submit</a>
		
	    <p class="agree-details">By clicking submit button, I agree to the <a onclick="return popitup('<?php echo SHIKSHA_HOME;?>/shikshaHelp/ShikshaHelp/termCondition');" href="javascript:void(0);">Terms and Conditions</a></p>
	</div>
    </div>
	<div id="mentee-thanku-layer" style="display: none;"></div>	
 </div>