<?php $arr = array('First','Second','Third','Forth','Fifth');?>
<?php //$stringToArr = explode(',',$finalValue);?>
<?php //$key = array_search($countOfSection, $stringToArr);?>
<?php $key = $arr[$countOfSection-1];?>
<?php error_log("key==".$key." & finalValue==".$finalValue.' & countOfSection= '.$countOfSection);?>
<li id="<?php echo $region;?>Section_<?php echo $countOfSection;?>">
	<div class="add-course-block">
	    <label><span>Heading:</span><br />
	    <span class="label-level-2">Prestigious Institutes:</span></label>
	    <div class="career-fields add-course-box">
		<input minlength="1" maxlength="250" caption="heading" validate="validateStr" type="text" class="universal-txt-field" style="width:345px" id="<?php echo $region;?>Heading_<?php echo $countOfSection;?>"/>&nbsp;<a href="javascript:void(0)" onClick="careerObj.removeSection('<?php echo $region;?>','<?php echo $countOfSection;?>');">Remove</a>
		<div style="display:none"><div class="errorMsg"  id="<?php echo $region;?>Heading_<?php echo $countOfSection;?>_error"></div></div>
		<div class="clearFix spacer15"></div>
		<div class="flLt"><input type="text" class="universal-txt-field" style="width:150px; color:#000; font-size:12px" value="Enter Text" id="<?php echo $region;?>CourseText_<?php echo $key;?>_1" onblur="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Text" validate="validateStr" minlength="1" maxlength="100"/>
		<div style="display:none"><div class="errorMsg"  id="<?php echo $region;?>CourseText_<?php echo $key;?>_1_error"></div></div></div> 
		<span id="<?php echo $region;?>OR_<?php echo $key;?>_1" class="or-txt">OR</span>
		<div class="flLt"><input type="text" class="universal-txt-field" style="width:150px; color:#000; font-size:12px" value="Enter Course Id" id="<?php echo $region;?>CourseId_<?php echo $key;?>_1" onblur="checkTextElementOnTransition(this,'blur');careerObj.validationCourseIdInWhereToStudySection(this.value,'<?php echo $region;?>CourseId_<?php echo $key;?>_1')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Course Id"/>
		<div style="display:none"><div class="errorMsg"  id="<?php echo $region;?>CourseId_<?php echo $key;?>_1_error"></div></div></div>  
		<div class="clearFix spacer10" id="spacer_<?php echo $region;?>_<?php echo $key;?>_1"></div>
		<div class="flLt"><input type="text" class="universal-txt-field" style="width:150px; color:#000; font-size:12px" value="Enter Text" id="<?php echo $region;?>CourseText_<?php echo $key;?>_2" onblur="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Text" validate="validateStr" minlength="1" maxlength="100" />
		<div style="display:none"><div class="errorMsg"  id="<?php echo $region;?>CourseText_<?php echo $key;?>_2_error"></div></div></div>
		<span id="<?php echo $region;?>OR_<?php echo $key;?>_2" class="or-txt">OR</span>          
		<div class="flLt"><input type="text" class="universal-txt-field" style="width:150px; color:#000; font-size:12px" value="Enter Course Id" id="<?php echo $region;?>CourseId_<?php echo $key;?>_2" onblur="checkTextElementOnTransition(this,'blur');careerObj.validationCourseIdInWhereToStudySection(this.value,'<?php echo $region;?>CourseId_<?php echo $key;?>_2')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Course Id"/>
		<div style="display:none"><div class="errorMsg"  id="<?php echo $region;?>CourseId_<?php echo $key;?>_2_error"></div></div></div>  
		<div class="clearFix spacer10" id="spacer_<?php echo $region;?>_<?php echo $key;?>_2"></div>
     		 <a href="javascript:void(0);" onClick="careerObj.addCourseIdForSection('<?php echo $region;?>','<?php echo $countOfSection;?>','<?php echo $key;?>');">+ Add More</a>
	    </div>
	</div>
</li>
