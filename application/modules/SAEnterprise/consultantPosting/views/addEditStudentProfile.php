<script type="text/javascript">
var categoryDetails = eval(<?php echo json_encode($abroadCategories); ?>);
var consultantUniversityObj;
var formName = '<?=$formName?>';
var getConsultantUniversityUrl = '<?= ENT_SA_CMS_CONSULTANT_PATH ?>getUniversityForConsultant';
var urlAfterSaveDone =  "<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_STUDENT_PROFILE?>";
var urlAfterDeleteDone = "<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_STUDENT_PROFILE?>";
var deletionUrl = '<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_DELETE_STUDENT_PROFILE ?>';
var templateCount =5;
var universityId = '<?php echo $universityId;?>';

function validateExamScore()
{
	var error = false;
	var elements  = $j('#form_'+formName+' [name="saExam[]"]');
	var examArray = [];
	$j(elements).each(function(){
		var getCounter = $j(this).attr('id').replace(/saExam_<?= $formName ?>/g,'');
		if ($j(this).val() !='' && $j.inArray($j(this).val(), examArray ) > -1) {
		   error = true;
		   $j('#saExam_'+formName+getCounter+'_error').html('One of the exam name is selected more than one time.').show();
		}
		$j('#saExamScore_'+formName+getCounter).removeAttr('disabled');
		$j('#saExamScore_'+formName+getCounter+'_error').html('').hide();
		if ($j(this).val() !='' && $j.trim($j('#saExamScore_'+formName+getCounter).val())=='' && $j(this).val()!='9999' && $j(this).val()!='9998') {
			
			error = true;
			$j('#saExamScore_'+formName+getCounter+'_error').html('Please enter exam Score').show();
		}
		if ($j(this).val() !='') {
		   examArray.push($j(this).val());
		}
		
		if ($j(this).val()=='9999' || $j(this).val()=='9998') {
			$j('#saExamScore_'+formName+getCounter).val()=='';
			$j('#saExamScore_'+formName+getCounter).attr('disabled','disabled');
		}
		
		});
	return error;
}

function validateGPAOrPercent(){
	/*var error = false; */
	var elements  = $j('#form_'+formName+' [name="graduationGPA[]"]');
	$j(elements).each(function(){
		var getCounter = $j(this).attr('id').replace(/graduationGPA_<?= $formName ?>/g,'');
		/*
		if ($j(this).val() =='' && $j('#graduationPercentage_'+formName+getCounter).val()=='') {
			
			error = true;
			$j('#graduationPercentage_'+formName+getCounter+'_error').html('Please enter GPA OR % Marks').show();
		}else{
		*/
			$j('#graduationPercentage_'+formName+getCounter+'_error').html('').hide();
		
		});
	/*return error;*/
}



function validateStudentCourseDetail(){
	var error = false;
	var elements  = $j('#form_'+formName+' [name="desiredCourse[]"]');
	$j(elements).each(function(){
		var getCounter = $j(this).attr('id').replace(/desiredCourse_<?= $formName ?>/g,'');
		$j('#courseDetail_'+formName+getCounter+'_error').html('').hide();
		if($j(this).val() =='' && typeof($j('[name="courseType_'+getCounter+'"]:checked').val())=='undefined') 
		{
			error = true;
			$j('#courseDetail_'+formName+getCounter+'_error').html('Please select course type').show();
		}else if($j(this).val() =='' && $j('#childCat_'+formName+getCounter).val()==''){
			error = true;
			$j('#courseDetail_'+formName+getCounter+'_error').html('Please select a category').show();
		}
		
		});
	return error;
}

function validateJobYear(){
	var error = false;
	var elements  = $j('#form_'+formName+' [name="jobStart[]"]');
	$j(elements).each(function(){
		var getCounter = $j(this).attr('id').replace(/jobStart_<?= $formName ?>/g,'');
		$j('#jobYear_'+formName+getCounter+'_error').html('').hide();
		if($j(this).val() =='' && $j('#jobEnd_'+formName+getCounter).val()!=''){
			error = true;
			$j('#jobYear_'+formName+getCounter+'_error').html('Please select start year first').show();
		}else if($j(this).val() > $j('#jobEnd_'+formName+getCounter).val()){
			error = true;
			$j('#jobYear_'+formName+getCounter+'_error').html('Job end year must be greater than or equal start year').show();
		}
		
		});
	return error;
	
}


function changeCourseType(type,formName,obj)
{
	var getCounter = '';
	
	switch (type) {
		case "desiredCourse":
			getCounter = $j(obj).attr('id').replace(/desiredCourse_<?= $formName ?>/g,'');
			
			$j('[name="courseType_'+getCounter+'"]').each(function(){
				
				$j(this).attr('checked',false);
				});
			$j('#parentCat_'+formName+getCounter).val('');
			$j('#childCat_'+formName+getCounter).html('<option value="">Select a Category </option>');
			$j('#childCat_'+formName+getCounter).val('');

		break;
		case "courseType":
			getCounter = $j(obj).attr('name').replace(/courseType_/g,'');
			$j('#desiredCourse_'+formName+getCounter).val('');
		break;
	
		case "parentCategory":
			getCounter = $j(obj).attr('id').replace(/parentCat_<?= $formName ?>/g,'');
			$j('#desiredCourse_'+formName+getCounter).val('');
		break;
	
		case "childCategory":
			getCounter = $j(obj).attr('id').replace(/childCat_<?= $formName ?>/g,'');
			$j('#desiredCourse_'+formName+getCounter).val('');
		break;	
	}
	validateStudentCourseDetail();
}

function profileAppendChildCategories(parentCat,obj){
	 var subcategoryList = categoryDetails[parentCat]['subcategory'];
	 var getCounter = $j(obj).attr('id').replace(/parentCat_<?= $formName ?>/g,'');
	 
	 var dropDownHtml = '<option value="">Select a Category </option>';
		$j.each(subcategoryList,function(key,value){
			dropDownHtml += '<option value="'+value.id+'">'+value.name+'</option>';			
		     });
		$j('#childCat_'+formName+getCounter).attr('disabled',false);
		$j('#childCat_'+formName+getCounter).html(dropDownHtml); 
}
</script>
<?php  
$ldbCourseDropDownHtml = '<option value="" >Select a Desired Course</option>';
foreach($abroadMainLDBCourses as $key => $mainLDBCourse){
    if(!empty($formData['desiredCourse']) && $formData['desiredCourse'] == $mainLDBCourse['SpecializationId'] ){		
		$ldbCourseDropDownHtml .=  '<option selected="selected" value="'.$mainLDBCourse['SpecializationId'].'">'.$mainLDBCourse['CourseName'].'</option>';
    } else {
		$ldbCourseDropDownHtml .=  '<option value="'.$mainLDBCourse['SpecializationId'].'">'.$mainLDBCourse['CourseName'].'</option>';
    }
}
?>
<div id="templateDiv" style="display:none;">
	<div id="universityDetailTemplate">
	    <div class="#htmlTemplate# universityBlock add-more-sec clear-width" style="background:#f8f8f8; padding-top:10px;">	
	    <ul>
	        <li>
		    <label>University country* : </label>
		    <div class="cms-fields">
			<select id="universityCountry_<?= $formName ?>#counter#" name="universityCountry[]" class="universityCountry universal-select cms-field" disabled="disabled" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="populateUniversityDropdown(this.value,'universityId_<?= $formName ?>#counter#');showErrorMessage(this, '<?=$formName?>');" required=true caption=" Country " tooltip="univ_country" validationType="select">
			<option value="">Select a country name</option>
		       </select>
		       <div style="display: none; margin-bottom: 5px" class="errorMsg" id="universityCountry_<?= $formName ?>#counter#_error">error</div>
		    </div>
		 </li>
		 <li>
		    <label>University admitted to* : </label>
		    <div class="cms-fields">
			<select id="universityId_<?= $formName ?>#counter#" name="universityId[]" class="universityName universal-select cms-field" disabled="disabled" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" required=true caption=" university name " tooltip="univ_admittedTo"  validationType="select">
			<option value="">Select a university name</option>	
		       </select>
		       <div style="display: none; margin-bottom: 5px" class="errorMsg" id="universityId_<?= $formName ?>#counter#_error">error</div>
		    </div>
		 </li>
		  <li>
		    <label>Course exact name* : </label>
		    <div class="cms-fields">
			<input id="courseName_<?= $formName ?>#counter#" name="courseName[]" type="text" class="universal-txt-field cms-text-field" caption=" exact name" tooltip="course_name" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" validationType="str" minlength=1 maxlength="100" required=true>
		    <div style="display: none; margin-bottom: 5px" class="errorMsg" id="courseName_<?= $formName ?>#counter#_error">error</div>
		    </div>  
		 </li>
		 <li>
		<label>Desired Course : </label>
		<div class="cms-fields">
		    <select id="desiredCourse_<?=$formName?>#counter#" name="desiredCourse[]" class="universal-select cms-field" tooltip="course_desired" onchange="changeCourseType('desiredCourse','<?=$formName?>',this);">                         
		      <?=$ldbCourseDropDownHtml?>
		   </select>
		   <p style="margin-top:10px;">OR</p>
		</div>
	     </li>
	     <li style="margin-bottom:0;">
		<div style="background:#f1f1f1;" class="add-more-sec2 clear-width">
		<ul>
			<li>
			<label>Course Type : </label>
			<div class="cms-fields" style="margin-top:6px;">
			    <?php foreach($couresTypes as $couresType) {?>
			    <label style="width: auto;"><input name="courseType_#counter#" onchange="changeCourseType('courseType','<?=$formName?>',this);" type="radio" value="<?=$couresType['CourseName']?>" /><?=$couresType['CourseName']?></label>
			    <?php }?>                  
			</div>
			<input type="hidden" name="courseTypeName[]" value="courseType_#counter#" />
			</li>
		    <li>
			<label>Parent Category* : </label>
			<div class="cms-fields">
			    <select id="parentCat_<?=$formName?>#counter#" name="parentCategory[]" caption="parent category" tooltip="course_category" validationType="select" onchange="changeCourseType('parentCategory','<?=$formName?>',this); profileAppendChildCategories(this.value,this);" class="universal-select cms-field">                         
				    <option value="">Select a Category</option>
				    <?php foreach($abroadCategories as $parentCategoryId => $parentCategoryDetails){ ?>
					<option <?php if(!empty($formData['parentCategory']) && $formData['parentCategory'] == $parentCategoryId) echo "selected"; ?> value="<?php echo $parentCategoryId;?>"><?php echo $parentCategoryDetails['name'];?></option>
				    <?php } ?>
			    </select>
			</div>
		    </li>
		    <li>
			<label>Child category* : </label>
			<div class="cms-fields">
			    <select onchange="changeCourseType('childCategory','<?=$formName?>',this);" id="childCat_<?=$formName?>#counter#" name="childCategory[]" caption="child category" tooltip="course_subCategory" class="universal-select cms-field">                         
				    <option value="">Select a Category</option>
			    </select>                         
			</div>
		    </li>
		    <li>
		    <div class="cms-fields">
			<div style="display: none; margin-bottom: 5px" class="errorMsg" id="courseDetail_<?=$formName?>#counter#_error"></div>
		    </div>
		   </li>
		    </ul>
		 </div>
		 </li>
		 <li>
		    <label>Scholarship Received?* : </label>
		    <div style="margin-top:6px;" class="cms-fields">
			<input type="radio" name="scholarship_#counter#" tooltip="scholarship_received"value="yes"> Yes
			<input type="radio" name="scholarship_#counter#" tooltip="scholarship_received" checked="checked" value="no"> No                  
		    </div>
		    <input type="hidden" name="scholarship_Name[]" value="scholarship_#counter#" />
		</li>
		<li>
		    <label>Scholarship Details : </label>
		    <div class="cms-fields">
			<textarea  name="scholarshipDetail[]"  id="scholarshipDetail_<?=$formName?>#counter#" class="cms-textarea checkIfEmpty" caption="scholarship Detail" tooltip="scholarship_details" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" validationType="html"></textarea>
			<div style="display: none; margin-bottom: 5px" class="errorMsg" id="scholarshipDetail_<?=$formName?>#counter#_error"></div>
		    </div>
		 </li>
		<li>
			<a onclick="removeDynamicAddTemplate('#htmlTemplate#','universityDetailTemplate');" href="javascript:void(0);" class="remove-link-2" style="margin-left:253px;">
			   <i class="abroad-cms-sprite remove-icon"></i>Remove this University Details
			</a>	
		</li>
		</ul>
	       </div>
	</div>
	
	<div id="saExamTemplate">	
		<li class="#htmlTemplate# examBlock">
		    <div class="cms-fields">
			<select style="width:140px;" id="saExam_<?=$formName?>#counter#" name="saExam[]" class="universal-select cms-field flLt" onblur="showErrorMessage(this, '<?=$formName?>');validateExamScore();" onchange="showErrorMessage(this, '<?=$formName?>');validateExamScore();" required=true caption="Exam Name" tooltip="study_abroadExam" validationType="select">                         
			<option value="">Select a exam</option>
			<?php foreach($abroadExamsMasterList as $exam)
			{ ?>
				<option value="<?=$exam['examId']?>"><?=$exam['exam']?></option>
			<?php } ?>
			<option value="9999">No Exam Given</option>
			<option value="9998">Exam info not available</option>
		       </select>
		       <input id="saExamScore_<?=$formName?>#counter#" name="saExamScore[]" type="text" class="universal-txt-field" tooltip="study_abroadExamScore" style="width:130px; margin-left:10px;" placeholder="Exam score"  onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" validationType="str" caption="exam score"  minlength=1 maxlength="4">
			<a onclick="removeDynamicAddTemplate('#htmlTemplate#','saExamTemplate');" href="javascript:void(0);" class="remove-link-2">
			   <i class="abroad-cms-sprite remove-icon"></i>Remove Exam
			</a>
		       <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="saExam_<?=$formName?>#counter#_error">error</div>
		       <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="saExamScore_<?=$formName?>#counter#_error">error</div>   
		    </div>
		 </li>
	</div>
	
	<div id="graduationTemplate">
		<ul class="#htmlTemplate# graduationBlock">
		<li>
		    <label>Graduation university : </label>
		    <div class="cms-fields">
			<input id="graduationUniversity_<?=$formName?>#counter#" name="graduationUniversity[]" type="text" class="universal-txt-field cms-text-field" caption=" graduation university " tooltip="graduation_univ" validationType="str" minlength=1 maxlength="500">
			
		    </div>
		 </li>
		 <li>
		    <label>Graduation college : </label>
		    <div class="cms-fields">
			<input id="graduationCollege_<?=$formName?>#counter#" name="graduationCollege[]" type="text" class="universal-txt-field cms-text-field" caption=" graduation college " tooltip="graduation_college" validationType="str" minlength=1 maxlength="500">
		    </div>
		 </li>
		 <li>
		    <label>Graudation university location : </label>
		    <div class="cms-fields">
				 <select id="graduationlocation_<?=$formName?>#counter#" name="graduationlocation[]" class="universal-select cms-field" tooltip="graduation_collegeLoc">
					<option value="">Select a City</option>
					<?php foreach($cityList as $cityObj){?>
					<option value="<?= $cityObj->getId();?>" ><?= $cityObj->getName();?></option>
					<?php }?>
			       </select>
		    </div>
		 </li>
		 <li>
		    <label>Graduation GPA : </label>
		    <div class="cms-fields">
			 <select id="graduationGPA_<?=$formName?>#counter#" name="graduationGPA[]" tooltip="graduation_gpa" class="universal-select cms-field" onchange="validateGPAOrPercent();">                         
				<option value="">Select GPA</option>
				<?php for($x=1;$x< 10;$x+=.1){
					?>
					<option value="<?= number_format($x,1);?>"><?= number_format($x,1);?></option>
				<?php }?>
		       </select>
		    </div>
		 </li>
		  <li>
		    <label>Graduation %age : </label>
		    <div class="cms-fields">
			 <select id="graduationPercentage_<?=$formName?>#counter#" name="graduationPercentage[]" class="universal-select cms-field" tooltip="graduation_percent"  onchange="validateGPAOrPercent();">                         
				<option value="">Select Percentage Marks</option>
				<?php for($x=40;$x<= 100;$x++){?>
				<option value="<?= $x;?>"><?= $x;?>%</option>
				<?php }?>
		       </select>
		     
		    </div>
		 </li>
		 <li>
		    <label>Graduation passing year : </label>
		    <div class="cms-fields">
			 <select class="universal-select cms-field" id="graduationPassing_<?=$formName?>#counter#" name="graduationPassing[]" caption="graduation passing year" tooltip="graduation_passingYear"  validationType="select">                         
				<option value="">Select a Year</option>
				<?php for($x=1970;$x<= date('Y');$x++){?>
				<option value="<?= $x;?>"><?= $x;?></option>
				<?php }?>
		       </select>
			
		    </div>
		 </li>
		 <li>
		    <label>Graduation description : </label>
		    <div class="cms-fields">
			 <textarea class="cms-textarea" id="graduationDesc_<?=$formName?>#counter#" name="graduationDesc[]" caption=" graduation details " tooltip="graduation_desc" minlength=1 maxlength="500"></textarea>
			
		    </div>
		 </li>
		 <li>
			<a onclick="removeDynamicAddTemplate('#htmlTemplate#','graduationTemplate');" href="javascript:void(0);" class="remove-link-2" style="margin-left:253px;">
			    <i class="abroad-cms-sprite remove-icon"></i>Remove This Graduation Details
		       </a>
		     </li>
		</ul>
	</div>
		
	
	<div id="workexTemplate">
		<ul class="#htmlTemplate# workexBlock">
			 <li>
			    <label>Company worked in : </label>
			    <div class="cms-fields">
				<input id="companyName_<?=$formName?>#counter#" name="companyName[]" type="text" class="universal-txt-field cms-text-field" tooltip="work_exp" maxlength="500">
			    </div>
			 </li>
			 <li>
			    <label>Company domain : </label>
			    <div class="cms-fields">
				 <select id="companyDomain_<?=$formName?>#counter#" name="companyDomain[]" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" caption="work experience domain" tooltip="work_exp_domain" validationType="select">                         
					<option value="">Select a Company Domain</option>
					<option>IT</option>
					<option>HR</option>
					<option>Customer Service</option>
					<option>E-Commerce</option>
					<option>Sales/Marketing</option>
					<option>Admin</option>
					<option>Government</option>
					<option>Operations</option>
					<option>Accounts/Finance</option>
					<option>Hospitality/Tourism</option>
					<option>Media</option>
					<option>Others</option>
			       </select>
			       <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="companyDomain_<?=$formName?>#counter#_error">error</div> 
			    </div>
			 </li>
			 <li>
			    <label>Job start year : </label>
			    <div class="cms-fields">
				<select id="jobStart_<?=$formName?>#counter#" name="jobStart[]" class="universal-select cms-field" tooltip="job_start" >                         
					<option value="">Select a Year</option>
					<?php for($x=1970;$x<= date('Y');$x++){?>
					<option value="<?= $x;?>"><?= $x;?></option>
					<?php }?>
			       </select>
			    </div>
			 </li>
			 <li>
			    <label>Job end year : </label>
			    <div class="cms-fields">
				 <select id="jobEnd_<?=$formName?>#counter#" name="jobEnd[]" class="universal-select cms-field" tooltip="job_end">                         
					<option value="">Select a Year</option>
					<?php for($x=1970;$x<= date('Y');$x++){?>
					<option value="<?= $x;?>"><?= $x;?></option>
					<?php }?>
			       </select>
			       <div style="display: none; margin-bottom: 5px;" class="errorMsg" id="jobYear_<?=$formName?>#counter#_error">error</div> 
			    </div>
			 </li>
			 <li>
				<a onclick="removeDynamicAddTemplate('#htmlTemplate#','workexTemplate');" href="javascript:void(0);" class="remove-link-2" style="margin-left:253px;">
				    <i class="abroad-cms-sprite remove-icon"></i>Remove This Company Section
			       </a>
			  </li>
		     </ul>
	</div>	
	
	<div id="documentTemplate">
	<div class="#htmlTemplate# fileBlock cms-fields">
	   <input style="width: 400px;overflow: hidden;" validationType="file" type="file" id="proofImg_<?=$formName?>#counter#" name="proofImg[]" caption=" proof " tooltip="doc_proof"/>
	   <a onclick="removeDynamicAddTemplate('#htmlTemplate#','documentTemplate');" href="javascript:void(0);" class="remove-link-2">
		<i class="abroad-cms-sprite remove-icon"></i>Remove
	   </a>
	   <div style="display: none; margin-bottom: 5px" class="errorMsg fileUploadError" id="proofImg_<?=$formName?>#counter#_error"></div>
	</div>
	</div>
	<div id="documentTemplateEdit">
	<div class="#htmlTemplate# fileBlock cms-fields">
	   <input type="hidden" id="proofImgHidden_<?=$formName?>#counter#" name="proofImgHidden[]" />
	   <a id="proofImgLink_<?=$formName?>#counter#" href="#" name="proofImgLink[]" target="_blank" style="width:200px; overflow: hidden;">View File</a>
	   <a onclick="changeDocument('#htmlTemplate#','documentTemplateEdit');" href="javascript:void(0);" class="remove-link-2">
		<i class="abroad-cms-sprite remove-icon"></i>Change 
	   </a>
	</div>
</div>


</div>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('consultantStudentProfile'); ?>"></script>
<div class="abroad-cms-rt-box">
    <?php
    //_p($formData);
      $breadCrumbText = "Student Profile";
      $displayData["pageTitle"]  	= "Add New Student Profile";
      if($stateOfForm != 'add' && $formName == ENT_SA_FORM_EDIT_STUDENT_PROFILE)
      {
	    $breadCrumbText = "Edit Student Profile ";
	    $displayData["pageTitle"]  	= "Edit Student Profile ";
	    $displayData["pageTitle"]  .= "<label style='color:red;'>".($formData['status']=="draft"?" (Draft state)":" (Published version)")."</label>";
      }
    $displayData["breadCrumb"] 	= array(array("text" => "All Student Profiles", "url" => ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_STUDENT_PROFILE ),
					array("text" => $breadCrumbText, "url" => "") );
    
    
    if($formName == ENT_SA_FORM_EDIT_STUDENT_PROFILE)
    {
    $displayData["lastUpdatedInfo"] = array("date"     => date("d/m/Y",strtotime($formData['modifiedAt'])),
					    "username" => $formData['modifiedByName']);
    }
    // load the title section
    $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
    ?>
    <form action ="/consultantPosting/ConsultantPosting/saveStudentProfile" name ="form_<?= $formName ?>" id="form_<?= $formName ?>" enctype="multipart/form-data" method="post">
	<div class="cms-form-wrapper clear-width">
	    <div class="clear-width">
		<h3 style="cursor:pointer;" class="section-title"><i class="abroad-cms-sprite minus-icon"></i>Student Profile Details</h3>
		<div style="margin-bottom:0;" class="cms-form-wrap">
		    <ul>
			<li>
			    <label>Consultant Name* : </label>
			    <div class="cms-fields">
				<select name="consultantId" id="consultantId_<?= $formName ?>" class="universal-select cms-field" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "getUniversityByConsultant(this.value);showErrorMessage(this, '<?=$formName?>');" required = true caption = "Consultant" tooltip="cons_name" validationType = "select">                         
				<option value="">Select a consultant</option>
				<?php foreach($consultantList as $consultantData){?>
					<option value="<?= $consultantData['consultantId']?>"><?=htmlentities($consultantData['name'])?></option>
				<?php } ?>	
			       </select>
			     <div style="display: none; margin-bottom: 5px" class="errorMsg" id="consultantId_<?= $formName ?>_error">error</div>
			    </div>
			 </li>
		     </ul>
		     <div style="background:#f8f8f8; padding-top:10px;" class="add-more-sec clear-width">
		     <ul>
			 <li>
			    <label>University country* : </label>
			    <div class="cms-fields">
				<select id="universityCountry_<?= $formName ?>1" name="universityCountry[]" class="universityCountry universal-select cms-field" tooltip="univ_country" disabled="disabled" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="populateUniversityDropdown(this.value,'universityId_<?= $formName ?>1');showErrorMessage(this, '<?=$formName?>');" required=true caption=" Country "  validationType="select">
				<option value="">Select a country name</option>
			       </select>
			       <div style="display: none; margin-bottom: 5px" class="errorMsg" id="universityCountry_<?= $formName ?>1_error">error</div>
			    </div>
			 </li>
			 <li>
			    <label>University admitted to* : </label>
			    <div class="cms-fields">
				<select id="universityId_<?= $formName ?>1" name="universityId[]" class="universityName universal-select cms-field" tooltip="univ_admittedTo" disabled="disabled" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" required=true caption=" university name "  validationType="select">
				<option value="">Select a university name</option>	
			       </select>
			       <div style="display: none; margin-bottom: 5px" class="errorMsg" id="universityId_<?= $formName ?>1_error">error</div>
			    </div>
			 </li>
			  <li>
			    <label>Course exact name* : </label>
			    <div class="cms-fields">
				<input id="courseName_<?= $formName ?>1" name="courseName[]" type="text" class="universal-txt-field cms-text-field" caption=" exact name" tooltip="course_name" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" validationType="str" minlength=1 maxlength="100" required=true>
			    <div style="display: none; margin-bottom: 5px" class="errorMsg" id="courseName_<?= $formName ?>1_error">error</div>
			    </div>   
			 </li>
			 <li>
			    <label>Desired Course : </label>
			    <div class="cms-fields">
				<select id="desiredCourse_<?=$formName?>1" name="desiredCourse[]" class="universal-select cms-field" tooltip="course_desired" onchange="changeCourseType('desiredCourse','<?=$formName?>',this);">                         
				  <?=$ldbCourseDropDownHtml?>
			       </select>
			       <p style="margin-top:10px;">OR</p>
			    </div>
			 </li>
			 <li style="margin-bottom:0;">
			    <div style="background:#f1f1f1;" class="add-more-sec2 clear-width">
			    <ul>
				    <li>
				    <label>Course Type : </label>
				    <div class="cms-fields" style="margin-top:6px;">
					<?php foreach($couresTypes as $couresType) {?>
                                        <label style="width: auto;"><input name="courseType_1" onchange="changeCourseType('courseType','<?=$formName?>',this);" type="radio" value="<?=$couresType['CourseName']?>" /><?=$couresType['CourseName']?></label>
                                        <?php }?>                  
				    </div>
				    <input type="hidden" name="courseTypeName[]" value="courseType_1" />
				    </li>
				<li>
				    <label>Parent Category* : </label>
				    <div class="cms-fields">
					<select id="parentCat_<?=$formName?>1" name="parentCategory[]" caption="parent category" tooltip="course_category" validationType="select" onchange="changeCourseType('parentCategory','<?=$formName?>',this); profileAppendChildCategories(this.value,this);" class="universal-select cms-field">                         
						<option value="">Select a Category</option>
						<?php foreach($abroadCategories as $parentCategoryId => $parentCategoryDetails){ ?>
						    <option <?php if(!empty($formData['parentCategory']) && $formData['parentCategory'] == $parentCategoryId) echo "selected"; ?> value="<?php echo $parentCategoryId;?>"><?php echo $parentCategoryDetails['name'];?></option>
						<?php } ?>
					</select>
				    </div>
				</li>
				<li>
				    <label>Child category* : </label>
				    <div class="cms-fields">
					<select onchange="changeCourseType('childCategory','<?=$formName?>',this);" id="childCat_<?=$formName?>1" name="childCategory[]" caption="child category" tooltip="course_subCategory" class="universal-select cms-field">                         
						<option value="">Select a Category</option>
					</select>                         
				    </div>
				</li>
				 <li>
					<div class="cms-fields">
					    <div style="display: none; margin-bottom: 5px" class="errorMsg" id="courseDetail_<?=$formName?>1_error"></div>
					</div>
				     </li>
			    </ul>
			 </div>
			 </li>
			 <li>
			    <label>Scholarship Received?* : </label>
			    <div style="margin-top:6px;" class="cms-fields">
				<input type="radio" name="scholarship_1" tooltip="scholarship_received" value="yes"> Yes
				<input type="radio" name="scholarship_1" tooltip="scholarship_received" checked="checked" value="no"> No                  
			    </div>
			    <input type="hidden" name="scholarship_Name[]" value="scholarship_1" />
			</li>
			<li>
			    <label>Scholarship Details : </label>
			    <div class="cms-fields">
				<textarea  name="scholarshipDetail[]"  id="scholarshipDetail_<?=$formName?>1" class="cms-textarea tinymce-textarea draft checkIfEmpty" caption="scholarship Detail" tooltip="scholarship_details" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" validationType="html" minlength="1" maxlength="500"></textarea>
				<div style="display: none; margin-bottom: 5px" class="errorMsg" id="scholarshipDetail_<?=$formName?>1_error"></div>
			    </div>
			 </li>
			</ul>
			</div>
			<a id="universityDetailAddMoreLink" class="add-more-link" style="margin-top:8px;margin-bottom:8px; width: 175px;" href="javascript:void(0);" onclick="addNewTemplateElement('universityDetailTemplate','universityDetailAddMoreLink');">[+] Add another university</a>
			<div class="cms-fields">
			<div style="display: none; margin-bottom: 5px" class="errorMsg" id="universityCourseUniqueError">error</div>
			</div>
			<ul>
			<li>
			    <label>Year of admission* : </label>
			    <div class="cms-fields">
				<select name="admissionMonth" id="admissionMonth_<?=$formName?>" style="width:140px;" class="universal-select cms-field flLt" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" required=true caption="Admission Month" tooltip="year_admission" validationType="select">                
					<option value="">Select a Month</option>
					<?php for($x=1;$x<13;$x++){?>
					<option value="<?= $x?>" <?php if($x==$formData['admissionMonth']){ echo "selected";}?>><?php echo date('M',strtotime("1970-".$x."-01"))?></option>
					<?php } ?>
			       </select>
			       
			       <select name="admissionYear" id="admissionYear_<?=$formName?>" style="width:140px; margin-left:10px;" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" required=true caption="Admission Year" tooltip="year_admission"   validationType="select">                         
					<option value="">Select a Year</option>
					<?php for($x=2010;$x<= date('Y');$x++){?>
					<option value="<?= $x;?>" <?php if($x==$formData['admissionYear']){ echo "selected";}?>><?= $x;?></option>
					<?php }?>
			       </select>
			       <div style="display: none; margin-bottom: 5px" class="errorMsg" id="admissionMonth_<?= $formName ?>_error">error</div>
			       <div style="display: none; margin-bottom: 5px" class="errorMsg" id="admissionYear_<?= $formName ?>_error">error</div>
			    </div>
			 </li>
			 <li>
			    <label>Study abroad exam* : </label>
			    <div class="cms-fields">
				<select style="width:140px;" id="saExam_<?=$formName?>1" name="saExam[]" class="universal-select cms-field flLt" onblur="showErrorMessage(this, '<?=$formName?>');validateExamScore();" onchange="showErrorMessage(this, '<?=$formName?>');validateExamScore();" required=true caption="Exam Name" tooltip="study_abroadExam" validationType="select">                         
				<option value="">Select a exam</option>
				<?php foreach($abroadExamsMasterList as $exam)
				{ ?>
					<option value="<?=$exam['examId']?>"><?=$exam['exam']?></option>
				<?php } ?>
				<option value="9999">No Exam Given</option>
				<option value="9998">Exam info not available</option>
			       </select>
			       <input id="saExamScore_<?=$formName?>1" name="saExamScore[]" type="text" class="universal-txt-field" style="width:130px; margin-left:10px;" placeholder="Exam score" tooltip="study_abroadExamScore" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" validationType="str" caption="exam score" minlength=1 maxlength="4">
			       <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="saExam_<?=$formName?>1_error">error</div>
			       <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="saExamScore_<?=$formName?>1_error">error</div>   
			    </div>
			 </li>
			 <li id="saExamAddMoreLink"><a style="margin-top:8px; width: 145px;" class="add-more-link" href="javascript:void(0);" onclick="addNewTemplateElement('saExamTemplate','saExamAddMoreLink');">[+] Add another exam</a></li>

			 <li>
			    <label>Student Name* : </label>
			    <div style="margin-top:6px;" class="cms-fields">
				<input id="studentName_<?=$formName?>" name="studentName" type="text" class="universal-txt-field cms-text-field" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" required=true caption="Student Name" tooltip="student_name" validationType="str" minlength=1 maxlength="100" value="<?= htmlentities($formData['studentName']);?>">               
				<div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="studentName_<?=$formName?>_error">error</div>
			    </div>
			</li>
			 <li>
			    <label>Residence City* : </label>
			    <div class="cms-fields">
				 <select id="studentCity_<?=$formName?>" name="studentCity" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" required=true caption=" Residence City " tooltip="residence_city" validationType="select">                         
					<option value="">Select a City</option>
					<?php foreach($cityList as $cityObj){?>
					<option value="<?= $cityObj->getId();?>" <?php if($cityObj->getId()==$formData['residenceCityId']){ echo "selected";}?> ><?= $cityObj->getName();?></option>
					<?php }?>
			       </select>
			       <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="studentCity_<?=$formName?>_error">error</div>
			    </div>
			 </li>
			 <li>
			    <label>Class 10th %age : </label>
			    <div class="cms-fields">
				 <select id="marks10_<?=$formName?>" name="marks10" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" caption="Class 10th marks" tooltip="10_percent" validationType="select">                         
					<option value="">Select Percentage Marks</option>
					<?php for($x=40;$x<= 100;$x++){?>
					<option value="<?= $x;?>" <?php if($x==$formData['classXPercentage']){ echo "selected";}?> ><?= $x;?>%</option>
					<?php }?>
			       </select>
			       <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="marks10_<?=$formName?>_error">error</div>
			    </div>
			 </li>
			 <li>
			    <label>Class 10th passing year : </label>
			    <div class="cms-fields">
				 <select id="year10Passing_<?=$formName?>" name="year10Passing" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" caption="Class 10th passing year" tooltip="10_passingYear" validationType="select">                         
					<option value="">Select a Year</option>
					<?php for($x=1970;$x<= date('Y');$x++){?>
					<option value="<?= $x;?>" <?php if($x==$formData['classXYear']){ echo "selected";}?>><?= $x;?></option>
					<?php }?>
			       </select>
			       <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="year10Passing_<?=$formName?>_error">error</div>
			    </div>
			 </li>
			 <li>
			    <label>Class 12th %age : </label>
			    <div class="cms-fields">
				 <select id="marks12_<?=$formName?>" name="marks12" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" caption="Class 12th marks" tooltip="12_percent" validationType="select">                         
					<option value="">Select Percentage Marks</option>
					<?php for($x=40;$x<= 100;$x++){?>
					<option value="<?= $x;?>" <?php if($x==$formData['classXIIPercentage']){ echo "selected";}?>><?= $x;?>%</option>
					<?php }?>
			       </select>
				<div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="marks12_<?=$formName?>_error">error</div> 
			    </div>
			 </li>
			 <li>
			    <label>Class 12th passing year : </label>
			    <div class="cms-fields">
				 <select id="year12Passing_<?=$formName?>" name="year12Passing" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" caption="Class 12th passing year" tooltip="12_passingYear" validationType="select">                         
					<option value="">Select a Year</option>
					<?php for($x=1970;$x<= date('Y');$x++){?>
					<option value="<?= $x;?>" <?php if($x==$formData['classXIIYear']){ echo "selected";}?>><?= $x;?></option>
					<?php }?>
			       </select>
				<div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="year12Passing_<?=$formName?>_error">error</div> 
			    </div>
			 </li>
			 
		       </ul>
		</div>
	    </div>
	</div>
	<div class="cms-form-wrapper clear-width">
	     <div style="margin-bottom:0;" class="cms-form-wrap">
		    <ul>
			<li>
			    <label>Graduation university : </label>
			    <div class="cms-fields">
				<input id="graduationUniversity_<?=$formName?>1" name="graduationUniversity[]" type="text" class="universal-txt-field cms-text-field" caption=" graduation university " tooltip="graduation_univ" validationType="str" minlength=1 maxlength="500">
				
			    </div>
			 </li>
			 <li>
			    <label>Graduation college : </label>
			    <div class="cms-fields">
				<input id="graduationCollege_<?=$formName?>1" name="graduationCollege[]" type="text" class="universal-txt-field cms-text-field" caption=" graduation college " tooltip="graduation_college" validationType="str" minlength=1 maxlength="500">
				
			    </div>
			 </li>
			 <li>
			    <label>Graudation university location : </label>
			    <div class="cms-fields">
				 <select id="graduationlocation_<?=$formName?>1" name="graduationlocation[]" class="universal-select cms-field" tooltip="graduation_collegeLoc">                         
					<option value="">Select a City</option>
					<?php foreach($cityList as $cityObj){?>
					<option value="<?= $cityObj->getId();?>" ><?= $cityObj->getName();?></option>
					<?php }?>
			       </select>
			    </div>
			 </li>
			 <li>
			    <label>Graduation GPA : </label>
			    <div class="cms-fields">
				 <select id="graduationGPA_<?=$formName?>1" name="graduationGPA[]" tooltip="graduation_gpa" class="universal-select cms-field">                         
					<option value="">Select GPA</option>
					<?php for($x=1;$x< 10;$x+=.1){
						?>
						<option value="<?= number_format($x,1);?>"><?= number_format($x,1);?></option>
					<?php }?>
			       </select>
			    </div>
			 </li>
			  <li>
			    <label>Graduation %age : </label>
			    <div class="cms-fields">
				 <select id="graduationPercentage_<?=$formName?>1" name="graduationPercentage[]" class="universal-select cms-field" tooltip="graduation_percent">                         
					<option value="">Select Percentage Marks</option>
					<?php for($x=40;$x<= 100;$x++){?>
					<option value="<?= $x;?>"><?= $x;?>%</option>
					<?php }?>
			       </select>
			    </div>
			 </li>
			 <li>
			    <label>Graduation passing year: </label>
			    <div class="cms-fields">
				 <select class="universal-select cms-field" id="graduationPassing_<?=$formName?>1" name="graduationPassing[]" caption="graduation passing year" tooltip="graduation_passingYear" validationType="select">                         
					<option value="">Select a Year</option>
					<?php for($x=1970;$x<= date('Y');$x++){?>
					<option value="<?= $x;?>"><?= $x;?></option>
					<?php }?>
			       </select>
				
			    </div>
			 </li>
			 <li>
			    <label>Graduation description : </label>
			    <div class="cms-fields">
				 <textarea class="cms-textarea" id="graduationDesc_<?=$formName?>1" name="graduationDesc[]" caption=" graduation details " tooltip="graduation_desc" minlength=1 maxlength="500"></textarea>
				 
			    </div>
			 </li>
		     </ul>
		      <a id="graduationAddMoreLink" style="margin-top:8px;width: 170px;" class="add-more-link" href="javascript:void(0);" onclick="addNewTemplateElement('graduationTemplate','graduationAddMoreLink');">[+] Add another graduation</a>
	</div>
    </div>
	<div class="cms-form-wrapper clear-width">
	     <div style="margin-bottom:0;" class="cms-form-wrap">
		    <ul>
			<li>
			    <label>Total work experience : </label>
			    <div class="cms-fields">
				 <select id="workex_<?=$formName?>" name="workex" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" caption="work experience " tooltip="work_exp" validationType="select">
					<option value="">Select experience</option>
					<option value="1 Month" <?php if($formData['totalWorkExperienceMonths']=="1 Month"){ echo "selected";}?>>1 Month</option>
					<?php for($x=2;$x<=60;$x++){?>
					<option value="<?= $x;?> Months" <?php if($formData['totalWorkExperienceMonths']==$x." Months"){ echo "selected";}?>><?= $x;?> Months</option>
					<?php }?>
					<option value="5-8 years" <?php if($formData['totalWorkExperienceMonths']=="5-8 years"){ echo "selected";}?>>5-8 years</option>
					<option value="8+ years"  <?php if($formData['totalWorkExperienceMonths']=="8+ years"){ echo "selected";}?>>8+ years</option>
			       </select>
			       <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="workex_<?=$formName?>_error">error</div> 
			    </div>
			 </li>
			 
		     </ul>
	</div>
    </div>
	<div class="cms-form-wrapper clear-width">
	     <div style="margin-bottom:0;" class="cms-form-wrap">
		    <ul>
			 <li>
			    <label>Company worked in : </label>
			    <div class="cms-fields">
				<input id="companyName_<?=$formName?>1" name="companyName[]" type="text" class="universal-txt-field cms-text-field" tooltip="last_comp" maxlength="500">
			    </div>
			 </li>
			 <li>
			    <label>Company domain : </label>
			    <div class="cms-fields">
				 <select id="companyDomain_<?=$formName?>1" name="companyDomain[]" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" caption="work experience domain" tooltip="work_exp_domain" validationType="select">                         
					<option value="">Select a Company Domain</option>
					<option>IT</option>
					<option>HR</option>
					<option>Customer Service</option>
					<option>E-Commerce</option>
					<option>Sales/Marketing</option>
					<option>Admin</option>
					<option>Government</option>
					<option>Operations</option>
					<option>Accounts/Finance</option>
					<option>Hospitality/Tourism</option>
					<option>Media</option>
					<option>Others</option>
			       </select>
			       <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="companyDomain_<?=$formName?>1_error">error</div> 
			    </div>
			 </li>
			 <li>
			    <label>Job start year : </label>
			    <div class="cms-fields">
				<select id="jobStart_<?=$formName?>1" name="jobStart[]" class="universal-select cms-field" tooltip="job_start">                         
					<option value="">Select a Year</option>
					<?php for($x=1970;$x<= date('Y');$x++){?>
					<option value="<?= $x;?>"><?= $x;?></option>
					<?php }?>
			       </select>
			    </div>
			 </li>
			 <li>
			    <label>Job end year : </label>
			    <div class="cms-fields">
				 <select id="jobEnd_<?=$formName?>1" name="jobEnd[]" class="universal-select cms-field" tooltip="job_end">                         
					<option value="">Select a Year</option>
					<?php for($x=1970;$x<= date('Y');$x++){?>
					<option value="<?= $x;?>"><?= $x;?></option>
					<?php }?>
			       </select>
			       <div style="display: none; margin-bottom: 5px;" class="errorMsg" id="jobYear_<?=$formName?>1_error">error</div> 
			    </div>
			 </li>
		     </ul>
		<a id="workexAddMoreLink" style="margin-top:8px;width: 205px;" class="add-more-link" href="javascript:void(0);" onclick="addNewTemplateElement('workexTemplate','workexAddMoreLink');">[+] Add another company</a>
	</div>
    </div>
	<div class="cms-form-wrapper clear-width">
	     <div style="margin-bottom:0;" class="cms-form-wrap">
		    <ul>
			<li>
			    <label>Extra curricular activities : </label>
			    <div class="cms-fields">
				<textarea id="curricularAct_<?=$formName?>" name="curricularAct" class="cms-textarea" tooltip="extra_curriculars" maxlength="1000"><?= htmlentities($formData['extraCurricularActivities']);?></textarea>
			    </div>
			 </li>
			 <li>
			    <label>Student Linkedin profile link* : </label>
			    <div class="cms-fields">
				<input id="linkedin_page_url_<?=$formName?>" name="linkedLink" type="text" class="universal-txt-field cms-text-field" onblur = "showErrorMessage(this, '<?=$formName?>');validateStudentContactDetail();" onchange = "showErrorMessage(this, '<?=$formName?>');validateStudentContactDetail();" caption=" linkedin url " tooltip="linkedIn_link" validationType="link" minlength="1" maxlength="1000" value="<?= htmlentities($formData['linkedInLink']);?>">
				<div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="linkedin_page_url_<?=$formName?>_error">error</div>
			    </div>
			 </li>
			  <li>
			    <label>Student Facebook profile link* : </label>
			    <div class="cms-fields">
				<input id="fb_page_url_<?=$formName?>" name="facebookLink" type="text" class="universal-txt-field cms-text-field" onblur = "showErrorMessage(this, '<?=$formName?>');validateStudentContactDetail();" onchange = "showErrorMessage(this, '<?=$formName?>');validateStudentContactDetail();" caption=" facebook url " tooltip="fb_link" validationType="link" minlength="1" maxlength="1000" value="<?= htmlentities($formData['facebookLink']);?>">
				<div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="fb_page_url_<?=$formName?>_error">error</div>
			    </div>
			 </li>
			 <li>
			    <label>Student phone number* : </label>
			    <div class="cms-fields">
				 <input id="phoneNo_<?=$formName?>" name="phoneNo"  type="text" class="universal-txt-field cms-text-field" onblur = "showErrorMessage(this, '<?=$formName?>');validateStudentContactDetail();" onchange = "showErrorMessage(this, '<?=$formName?>');validateStudentContactDetail();" caption=" mobile no " tooltip="student_number" validationType="str" minlength=8 maxlength=20 value="<?= htmlentities($formData['studentPhone']);?>">
				 <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="phoneNo_<?=$formName?>_error">error</div>
			    </div>
			 </li>
			 <li>
			    <label>Document Proof* : </label>
			    <div class="cms-fields fileBlock fileBlockdefault" style="<?php if($formName == ENT_SA_FORM_EDIT_STUDENT_PROFILE){echo "display:none;";}?>">
				<input style="width: 400px;overflow: hidden;" validationType="file" type="file" id="proofImg_<?=$formName?>1" name="proofImg[]" caption="Proof File" tooltip="doc_proof"  />
				<div style="display: none; margin-bottom: 5px" class="errorMsg fileUploadError" id="proofImg_<?=$formName?>1_error"></div>	
			    </div>
			     <div class="cms-fields">
				<div style="display: none; margin-bottom: 5px" class="errorMsg" id="document_error"></div>	
			    </div>
			    <a id="documentAddMoreLink" style="margin-top:8px; width: 210px;" class="add-more-link" href="javascript:void(0)" onclick="addNewTemplateElement('documentTemplate','documentAddMoreLink');">[+] Add another document proof</a>
			 </li>
			 <li>
			     <label>Student Email* : </label>
			    <div class="cms-fields">
				 <input id="studentEmail_<?=$formName?>" name="studentEmail"  type="text" class="universal-txt-field cms-text-field" onblur = "showErrorMessage(this, '<?=$formName?>');validateStudentContactDetail();" onchange = "showErrorMessage(this, '<?=$formName?>');validateStudentContactDetail();" caption=" student email " tooltip="student_email" validationType="email" value="<?= htmlentities($formData['studentEmail']);?>">
				 <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="studentEmail_<?=$formName?>_error">error</div>
			    </div>
			 </li>
			 <li>
			    <div class="cms-fields">
			     <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="studentdetail_<?=$formName?>_error">error</div>
			    </div>
			 </li>
			 <li>
			     <div style="display: none; margin-bottom: 5px;clear: both;" class="errorMsg" id="overAll_error">error</div>
			 </li>
		     </ul>
	</div>
	<input type="hidden" name="studentSaveMode" id="studentSaveMode" value="">
	<input type="hidden" name="studentActionType" id="studentActionType" value="<?=$formName?>">
	<input type="hidden" name="studentCreatedBy" id="studentCreatedBy" value="<?= $formData['createdBy'];?>">
	<input type="hidden" name="studentCreatedAt" id="studentCreatedAt" value="<?= $formData['createdAt'];?>">
	<input type="hidden" name="studentSaveModeOld" id="studentSaveModeOld" value="<?= $formData['status'];?>">
	<input type="hidden" name="studentId" id="studentId" value="<?= $formData['profileId'];?>">
	     
	<div class="button-wrap">
	    <a class="gray-btn" href="javascript:void(0);" onclick="saveStudentProfileForm(this,'<?=$formName?>','draft')">Save as Draft</a>
	    <a class="orange-btn" href="javascript:void(0);" onclick="saveStudentProfileForm(this,'<?=$formName?>','live')">Save &amp; Publish</a>
	    <a class="cancel-btn" href="javascript:void(0);" onclick="confirmRedirection();">Cancel</a>
	</div>
    </div>
    <div class="clearFix"></div>
    </form>
    <?php if(count($bottomTableData) >0){?>
    <div class="mapped-univ-table" id="bottomTable">
	    <h4 style="margin-left:0; margin-top:10px;" class="ranking-head">
		<?php
			if(count($bottomTableData)==1){
			$tableHeading = " 1 Student Profile By ".$consultantName;	
			}else{
			$tableHeading = "All ".count($bottomTableData)." Student Profiles By ".$consultantName;	
			}
			echo htmlentities($tableHeading);
	        ?>
		
	    </h4>
	    <table cellspacing="0" cellpadding="0" border="1" style="margin:15px 0 0;" class="cms-table-structure">
		    <tbody><tr>
		    <th width="5%" align="center">S.No.</th>
		    <th width="28%">
			<span class="flLt">Student Name</span>
		    </th>
		    <th width="15%">
			<span class="flLt">Course Name</span>
		    </th>
		    <th width="22%">
		    <span class="flLt">University</span>
		     </th>
		     <th width="18%">
		    <span class="flLt">Country</span>
		     </th>
		     <th width="12%">
		    <span class="flLt">Created/Updated on</span>
		     </th>
		</tr>
		<?php
		$count=1;
		foreach($bottomTableData as $tablerow){?>
				<tr>
				    <td align="center"><?= $count;?></td>
				    <td>
				       <p><?= stripcslashes(htmlspecialchars($tablerow['studentName']));?></p>
					<div class="edit-del-sec">
					    <a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_EDIT_STUDENT_PROFILE ?>?consultantId=<?= $consultantId?>&profileId=<?= $tablerow['profileId'];?>" >Edit</a>&nbsp;&nbsp;
					    <?php if($usergroup == 'saAdmin' || $usergroup == 'saCMSLead'){ ?>    
					    <a onclick="confirmDeletion('<?= $tablerow['profileId'];?>','<?= $tablerow['universityId']?>','<?= $consultantId?>');" href="javaScript:void(0);">Delete</a>
					    <?php }?>
					</div>
				    </td>
				    <td>
					    <p><?= stripcslashes(htmlspecialchars($tablerow['courseName']));?></p>
				    </td>
				    
				    <td>
					    <p><?= stripcslashes(htmlspecialchars($tablerow['universityName']));?></p>
				    </td>
				    <td>
					    <p><?= stripcslashes(htmlspecialchars($tablerow['countryName']));?></p>
				    </td>
				    <td>
					<p class="cms-table-date"><?= date('d M Y',strtotime($tablerow['modifiedAt']));?></p>
					<p class="<?= ($tablerow['status']=='live')?"publish":"draft" ?>-clr"><?= ($tablerow['status']=='live')?"Published":"Draft";?></p>
				    </td>
				</tr>
		<?php $count++;}?>		
	    </tbody></table>
    </div>
    <?php }?>
</div>

<script>
 var preventOnUnload = false;	
if(document.all) {
    document.body.onload = initFormPosting;
} else {
    initFormPosting();
}

var companyJSON = <?php echo json_encode($formData['companyMappingResult'],JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);?>;
var examJSON = <?php echo json_encode($formData['examMappingResult'],JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);?>;
var graduationJSON = <?php echo json_encode($formData['graduationMappingResult'],JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);?>;
var universityJSON = <?php echo json_encode($formData['universityMappingResult'],JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);?>;
var documentJSON = <?php echo json_encode($formData['documentMappingResult'],JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);?>;

</script>
