<style>.disabled {pointer-events: none;cursor: default;}</style>
<section style="padding:0; display: none;" class="clearfix content-section" id="getMentorForm">  
<article class="clearfix naukri-inner-wrap" style="border-bottom:1px solid #ccc; padding-bottom:10px;">
        <h2 style="text-transform:capitalize;" class="rank-predictor-title">Enroll to Get a Mentor</h2>
</article>
<div class="mentor-form">
	<p class="mentor-form-title">Your Preferences</p>
	<ul>
	<li style="position:relative;">
		<label> Engineering exams you have taken / planned to take <span style="color: rgb(255, 0, 0);">*</span></label>
		<div onclick="showHideExam('show');" class="ui-select">
			<div class="ui-btn ui-shadow ui-btn-corner-all ui-btn-icon-right ui-btn-up-c" data-theme="c" data-iconpos="right" data-icon="arrow-d" data-wrapperels="span" data-iconshadow="true" data-shadow="true" data-corners="true"><span class="ui-btn-inner"><span class="ui-btn-text"><span style="font-size: .9em;" id="examListH">Selected entrance exam</span></span><span class="ui-icon ui-icon-arrow-d ui-icon-shadow">&nbsp;</span></span>
		</div>
		</div>
	    
	    
	    <div data-enhance="false" class="exam-layer" id="examlist" style="display: none;">
                <ul style="height:100px; overflow:auto;" id="menteeExamList">
                <?php $i=0;foreach($exam_list as $exam_list){?>
		<li>
					<label refId="<?php echo $i;?>" for="<?php echo $i;?>" onclick="selectExamList($(this).attr('refId'));">
					<input type="checkbox" value="<?php echo $exam_list;?>" id="<?php echo $i;?>" name="exam[]"> <?php echo $exam_list;?></label>
                        <div class="exam-layer-details-box" id="input_<?php echo $i;?>_layer">
				<div>
                                        <input type="text" id="input_<?php echo $i;?>" placeholder="Enter Score" class="flRt mentee-exam-field" maxlength="5" minlength="1" onkeyup="validateExamScore();">
					<div>
                                            <div class="regErrorMsg" id="error_<?php echo $i;?>"></div>
                                        </div>
				</div>
			</div>
		</li>
		<?php $i++;}?>
		<li>
			<label refId="other" onclick="selectExamList($(this).attr('refId'));"><input type="checkbox" id="other" value="other" name="exam[]"> Other</label>
                        <div id="input_other_layer" class="exam-layer-details-box">
				<div style="margin-bottom: 5px">
                                    <input style="width:70%;" type="text" id="inputExam_other" placeholder="Enter Exam" class="flRt mentee-exam-field" maxlength="50" onkeyup="$('#other').val($(this).val());validateExamScore();" mandatory="1" style="color: rgb(153, 153, 153);">
                                </div>
                                <div style="padding: 24px 0 0;">
					<input style="width:70%;" type="text" id="input_other" placeholder="Enter Score" class="flRt mentee-exam-field" maxlength="5" minlength="1" onkeyup="validateExamScore();">
                                </div>
                        </div>
        </li>
	
	<li>
		<label refId="nothave" onclick="selectExamList($(this).attr('refId'));">
			<input type="checkbox" id="nothave" value="Haven\'t taken any exam" name="exam[]">  Haven't taken any exam</label>
		<input type="hidden" id="input_nothave" placeholder="Enter Score" class="flRt mentee-exam-field" style="display: none;" maxlength="5" minlength="1">
        </li>
			
	</ul>

    <div class="btn-row2">
	<a onclick="showHideExam('hide');" href="javascript:void(0);" class="button blue small">OK</a>
    </div>
</div>
	    
	    
	    
	<div class="menterror"><div id="examList_error" style="display: none;">Please select at least 1 exam</div></div>
	<div class="menterror clear-width"><div id="examList_score_error" style="display: none;"></div></div>
	    
	</li>
	<li style="margin-bottom:2px;">
		<label style="margin:0;"> Preferred college location <span style="color:#9f9f9f;">(Select atleast 1)</span></label>
		<div class="menterror"><div id="prefCL1_value_error" style="display: none;">Please select preference 1</div></div>
	</li>
	<li>
		
	<a data-enhance="false" onclick="$('#setLocation').val('prefCL1');getoTopLocation();" href="#locationDiv" data-inline="true" data-rel="dialog" data-transition="slide" class="flLt pref-btn" style="margin-bottom:0;"><span id="prefCL1">Preference 1</span><i class="flRt sprite pref-right-mark"></i></a>
	    <input type="hidden" class="pcl" id="prefCL1_value"><input type="hidden" id="setLocation">
	</li>
	
	<li>
	   <a data-enhance="false" onclick="$('#setLocation').val('prefCL2');getoTopLocation();" href="#locationDiv" data-inline="true" data-rel="dialog" data-transition="slide" class="flLt pref-btn" style="margin-bottom:0;"><span id="prefCL2">Preference 2</span><i class="flRt sprite pref-right-mark"></i></a>
	   <input type="hidden" class="pcl" id="prefCL2_value">
	</li>
	
	<li>
	    <a data-enhance="false" onclick="$('#setLocation').val('prefCL3');getoTopLocation();" href="#locationDiv" data-inline="true" data-rel="dialog" data-transition="slide" class="flLt pref-btn" style="margin-bottom:0;"><span id="prefCL3">Preference 3</span><i class="flRt sprite pref-right-mark"></i></a>
	    <input type="hidden" class="pcl" id="prefCL3_value">
	</li>
	
	<li>
		<label>When do you plan to start your engineering degree? <span style="color: rgb(255, 0, 0);">*</span></label>
	    <select class="mentor-select-field" id="menteeExamYr" onchange="validateOnClick('menteeExamYr');">
		<option value="">Select year</option>
		<?php for($i = date('Y') + 3;$i>= date('Y') ;$i--){?>
			<option value="<?php echo $i;?>"><?php echo $i;?></option>
		<?php }?>
	    </select>
	    <div class="menterror"><div id="menteeExamYr_error" style="display: none;">Please select starting year of engineering</div></div>
	</li>
	 <li style="margin-bottom:2px;">
		<label style="margin:0;">Preferred engineering branches <span style="color:#9f9f9f;">(Select atleast 1)</span></label>
		<div class="menterror"><div id="eng_branch_pref1_error" style="display: none;">Please select preference 1</div></div>
		<div class="menterror clear-width"><div id="otherB_error" style="display: none;"></div></div>
	 </li>
	 <li>
		
	    <select class="mentor-select-field" id="eng_branch_pref1" onchange="selectBranch('eng_branch_pref1',$(this).val());">
		<option value="">Preference 1</option>
		<?php if(count($branchList)>0){for($k=0;$k<count($branchList);$k++){?>
			<option value="<?php echo $branchList[$k];?>"><?php echo $branchList[$k];?></option>
		<?php }}?>
		<option value="Other">Other</option>
	    </select>
	    <input type="hidden" id="eng_branch_pref1_mvalue">
	</li>
	<li class="branchlst" id="eng_branch_pref1_open" style="display: none;">
	    <input data-enhance="false" type="text" class="mentor-text-field" id="eng_branch_pref1_value" refId="eng_branch_pref1" onkeyup="typeText($(this),'eng_branch_pref1');" maxlength="50">
	</li>
	<li>
	    <select class="mentor-select-field" id="eng_branch_pref2" onchange="selectBranch('eng_branch_pref2',$(this).val());">
		<option value="">Preference 2</option>
		<?php if(count($branchList)>0){for($k=0;$k<count($branchList);$k++){?>
			<option value="<?php echo $branchList[$k];?>"><?php echo $branchList[$k];?></option>
		<?php }}?>
		<option value="Other">Other</option>
	    </select>
	    <input type="hidden" id="eng_branch_pref2_mvalue">
	</li>
	<li class="branchlst" id="eng_branch_pref2_open" style="display: none;">
	    <input data-enhance="false"  type="text" class="mentor-text-field" id="eng_branch_pref2_value" refId="eng_branch_pref2" onkeyup="typeText($(this),'eng_branch_pref2');" maxlength="50">
	</li>
	<li>
	    <select class="mentor-select-field" id="eng_branch_pref3" onchange="selectBranch('eng_branch_pref3',$(this).val());">
		<option value="">Preference 3</option>
		<?php if(count($branchList)>0){for($k=0;$k<count($branchList);$k++){?>
			<option value="<?php echo $branchList[$k];?>"><?php echo $branchList[$k];?></option>
		<?php }}?>
		<option value="Other">Other</option>
	    </select>
	    <input type="hidden" id="eng_branch_pref3_mvalue">
	</li>
	<li class="branchlst" id="eng_branch_pref3_open" style="display: none;">
	    <input data-enhance="false"  type="text" class="mentor-text-field" id="eng_branch_pref3_value" refId="eng_branch_pref3" onkeyup="typeText($(this),'eng_branch_pref3');" maxlength="50">
	</li>
	<li>
		<label>List colleges you are targeting:</label>
	    <input data-enhance="false" type="text" placeholder="Enter colleges separated by commas" class="mentor-text-field" id="clgTarget" onkeyup="validateClgTarget('clgTarget');" maxlength="200">
		<div class="menterror"><div id="clgTarget_error" style="display: none;">Please enter colleges separated by commas</div></div>
	</li>
    </ul>
    <div class="clearfix"></div>
    <a class="mentor-submit-btn" href="javascript:void(0);" id="registrationSubmit_btn" onclick="addPreferences();">Submit</a>
    <p class="term-condition-txt">By clicking submit button, I agree to the <a href="/mcommon5/MobileSiteStatic/terms" target="_blank">Terms and Conditions</a></p>
</div>

<form type='hidden' method="post" action="<?=SHIKSHA_HOME?>/muser5/MobileUser/register" id="menteeUserForm">
            
                        <input type="hidden" name="category" id="category" value="2"/>
                        <input type="hidden" name="subcategory" id="subcategory" value="52" />
                        <input type="hidden" name="yearOfPassing" id="yearOfPassing" value="<?php echo date('Y');?>" />
                        <input type="hidden" name="fromMenteePage" id="fromMenteePage" value="Yes" />
			<input type="hidden" name="from_where" id="from_where" value="mobMenteeUser">
			<input type="hidden" name="tracking_keyid" id="tracking_keyid" value="<?php echo $trackingPageKeyId;?>">
                        
</form>
</section>