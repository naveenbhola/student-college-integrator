<?php $this->load->view('CareerProductEnterprise/subtabsCareers');?>

<script>
//var catSubcatCourseList = <?php echo json_encode($catSubcatCourseList); ?>;
careerObj.currentDiv = 0;
careerObj.careerId ='<?php echo $careerId;?>';
</script>
 <script>var floatingRegistrationSource = 'CAREER_FLOATINGWIDGETREGISTRATION';</script>
<?php
foreach($careerData as $key=>$value){
	$$key = $value;
}

if(!isset($skillRequiredCount) || $skillRequiredCount==''){ 
	$skillRequiredCount =  '';
}
if(!isset($jobProfileCount) || $jobProfileCount==''){ 
	$jobProfileCount =  '1';
}
if(!isset($howDoIGetThereCount) || $howDoIGetThereCount==''){ 
	$howDoIGetThereCount =  '1';
}
if(!isset($employmentOpportunitiesCount) || $employmentOpportunitiesCount==''){ 
	$employmentOpportunitiesCount =  '1';
}
if(!isset($whereToStudyCount) || $whereToStudyCount==''){ 
	$whereToStudyCount =  '1';
}
if(!isset($indiawhereToStudyCount) || $indiawhereToStudyCount==''){ 
	$indiawhereToStudyCount =  '1';
}
if(!isset($abroadwhereToStudyCount) || $abroadwhereToStudyCount==''){ 
	$abroadwhereToStudyCount =  '1';
}
if(!isset($indiawhereToStudyCourseIdCountForFirstSection) || $indiawhereToStudyCourseIdCountForFirstSection==''){ 
	$indiawhereToStudyCourseIdCountForFirstSection =  '1,2';
}
if(!isset($abroadwhereToStudyCourseIdCountForFirstSection) || $abroadwhereToStudyCourseIdCountForFirstSection==''){ 
	$abroadwhereToStudyCourseIdCountForFirstSection =  '1,2';
}
if(!isset($indiawhereToStudyCourseIdCountForSecondSection) || $indiawhereToStudyCourseIdCountForSecondSection==''){ 
	$indiawhereToStudyCourseIdCountForSecondSection =  '1,2';
}
if(!isset($abroadwhereToStudyCourseIdCountForSecondSection) || $abroadwhereToStudyCourseIdCountForSecondSection==''){ 
	$abroadwhereToStudyCourseIdCountForSecondSection =  '1,2';
}
if(!isset($indiawhereToStudyCourseIdCountForThirdSection) || $indiawhereToStudyCourseIdCountForThirdSection==''){ 
	$indiawhereToStudyCourseIdCountForThirdSection =  '1,2';
}
if(!isset($abroadwhereToStudyCourseIdCountForThirdSection) || $abroadwhereToStudyCourseIdCountForThirdSection==''){ 
	$abroadwhereToStudyCourseIdCountForThirdSection =  '1,2';
}
if(!isset($indiawhereToStudyCourseIdCountForForthSection) || $indiawhereToStudyCourseIdCountForForthSection==''){ 
	$indiawhereToStudyCourseIdCountForForthSection =  '1,2';
}
if(!isset($abroadwhereToStudyCourseIdCountForForthSection) || $abroadwhereToStudyCourseIdCountForForthSection==''){ 
	$abroadwhereToStudyCourseIdCountForForthSection =  '1,2';
}
if(!isset($indiawhereToStudyCourseIdCountForFifthSection) || $indiawhereToStudyCourseIdCountForFifthSection==''){ 
	$indiawhereToStudyCourseIdCountForFifthSection =  '1,2';
}
if(!isset($abroadwhereToStudyCourseIdCountForFifthSection) || $abroadwhereToStudyCourseIdCountForFifthSection==''){ 
	$abroadwhereToStudyCourseIdCountForFifthSection =  '1,2';
}


if(!isset($totalSkillRequiredCount) || $totalSkillRequiredCount==''){ 
	$totalSkillRequiredCount =  '0';
}
if(!isset($totaljobProfileCustomFieldCount) || $totaljobProfileCustomFieldCount==''){ 
	$totaljobProfileCustomFieldCount =  '1';
}
if(!isset($totalhowDoIGetThereCustomFieldCount) || $totalhowDoIGetThereCustomFieldCount==''){ 
	$totalhowDoIGetThereCustomFieldCount =  '1';
}
if(!isset($totalemploymentOpportunitiesCustomFieldCount) || $totalemploymentOpportunitiesCustomFieldCount==''){ 
	$totalemploymentOpportunitiesCustomFieldCount =  '1';
}
if(!isset($totalwhereToStudyCustomFieldCount) || $totalwhereToStudyCustomFieldCount==''){ 
	$totalwhereToStudyCustomFieldCount =  '1';
}
if(!isset($totalindiawhereToStudySectionFieldCount) || $totalindiawhereToStudySectionFieldCount==''){ 
	$totalindiawhereToStudySectionFieldCount =  '1';
}
if(!isset($totalabroadwhereToStudySectionFieldCount) || $totalabroadwhereToStudySectionFieldCount==''){ 
	$totalabroadwhereToStudySectionFieldCount =  '1';
}
if(!isset($totalabroadwhereToStudyCustomFieldCount) || $totalabroadwhereToStudyCustomFieldCount==''){ 
	$totalabroadwhereToStudyCustomFieldCount =  '1';
}
if(!isset($totalabroadwhereToStudyCourseIdCountForFirstSection) || $totalabroadwhereToStudyCourseIdCountForFirstSection==''){ 
	$totalabroadwhereToStudyCourseIdCountForFirstSection =  '2';
}
if(!isset($totalindiawhereToStudyCourseIdCountForFirstSection) || $totalindiawhereToStudyCourseIdCountForFirstSection==''){ 
	$totalindiawhereToStudyCourseIdCountForFirstSection =  '2';
}
if(!isset($totalabroadwhereToStudyCourseIdCountForSecondSection) || $totalabroadwhereToStudyCourseIdCountForSecondSection==''){ 
	$totalabroadwhereToStudyCourseIdCountForSecondSection =  '2';
}
if(!isset($totalindiawhereToStudyCourseIdCountForSecondSection) || $totalindiawhereToStudyCourseIdCountForSecondSection==''){ 
	$totalindiawhereToStudyCourseIdCountForSecondSection =  '2';
}
if(!isset($totalabroadwhereToStudyCourseIdCountForThirdSection) || $totalabroadwhereToStudyCourseIdCountForThirdSection==''){ 
	$totalabroadwhereToStudyCourseIdCountForThirdSection =  '2';
}
if(!isset($totalindiawhereToStudyCourseIdCountForThirdSection) || $totalindiawhereToStudyCourseIdCountForThirdSection==''){ 
	$totalindiawhereToStudyCourseIdCountForThirdSection =  '2';
}
if(!isset($totalabroadwhereToStudyCourseIdCountForForthSection) || $totalabroadwhereToStudyCourseIdCountForForthSection==''){ 
	$totalabroadwhereToStudyCourseIdCountForForthSection =  '2';
}
if(!isset($totalindiawhereToStudyCourseIdCountForForthSection) || $totalindiawhereToStudyCourseIdCountForForthSection==''){ 
	$totalindiawhereToStudyCourseIdCountForForthSection =  '2';
}
if(!isset($totalabroadwhereToStudyCourseIdCountForFifthSection) || $totalabroadwhereToStudyCourseIdCountForFifthSection==''){ 
	$totalabroadwhereToStudyCourseIdCountForFifthSection =  '2';
}
if(!isset($totalindiawhereToStudyCourseIdCountForFifthSection) || $totalindiawhereToStudyCourseIdCountForFifthSection==''){ 
	$totalindiawhereToStudyCourseIdCountForFifthSection =  '2';
}
?>
<script>
	careerObj.counter['totalCountOfSkillRequired'] = '<?php echo $totalSkillRequiredCount;?>';
	careerObj.addCustomFieldsCount['jobProfile'] = '<?php echo $totaljobProfileCustomFieldCount;?>';
	careerObj.addCustomFieldsCount['howDoIGetThere'] = '<?php echo $totalhowDoIGetThereCustomFieldCount;?>';
	careerObj.addCustomFieldsCount['employmentOpportunities'] = '<?php echo $totalemploymentOpportunitiesCustomFieldCount;?>';
	careerObj.addCustomFieldsCount['whereToStudy'] = '<?php echo $totalwhereToStudyCustomFieldCount;?>';
	careerObj.addSectionCourseIdCount['abroadwhereToStudyFirstSection'] = '<?php echo $totalabroadwhereToStudyCourseIdCountForFirstSection;?>';
	careerObj.addSectionCourseIdCount['indiawhereToStudyFirstSection'] = '<?php echo $totalindiawhereToStudyCourseIdCountForFirstSection;?>';
	careerObj.addSectionCourseIdCount['abroadwhereToStudySecondSection'] = '<?php echo $totalabroadwhereToStudyCourseIdCountForSecondSection;?>';
	careerObj.addSectionCourseIdCount['indiawhereToStudySecondSection'] = '<?php echo $totalindiawhereToStudyCourseIdCountForSecondSection;?>';
	careerObj.addSectionCourseIdCount['abroadwhereToStudyThirdSection'] = '<?php echo $totalabroadwhereToStudyCourseIdCountForThirdSection;?>';
	careerObj.addSectionCourseIdCount['indiawhereToStudyThirdSection'] = '<?php echo $totalindiawhereToStudyCourseIdCountForThirdSection;?>';
	careerObj.addSectionCourseIdCount['abroadwhereToStudyForthSection'] = '<?php echo $totalabroadwhereToStudyCourseIdCountForForthSection;?>';
	careerObj.addSectionCourseIdCount['indiawhereToStudyForthSection'] = '<?php echo $totalindiawhereToStudyCourseIdCountForForthSection;?>';
	careerObj.addSectionCourseIdCount['abroadwhereToStudyFifthSection'] = '<?php echo $totalabroadwhereToStudyCourseIdCountForFifthSection;?>';
	careerObj.addSectionCourseIdCount['indiawhereToStudyFifthSection'] = '<?php echo $totalindiawhereToStudyCourseIdCountForFifthSection;?>';
	careerObj.addSectionCount['indiawhereToStudy'] = '<?php echo $totalindiawhereToStudySectionFieldCount;?>';
	careerObj.addSectionCount['abroadwhereToStudy'] = '<?php echo $totalabroadwhereToStudySectionFieldCount;?>';


</script>

<?php 


?>
<?php 
$arrLocation = array('india','abroad');
foreach($arrLocation as $key=>$value){
	for($i=1;$i<=5;$i++){
	?>
		<script> 
			careerObj.counter['courseIdCountFor<?php echo $value;?>Section<?php echo $i;?>'] = "<?php echo ${'courseIdCountForSection'.$i.$value};?>";
		</script>
	<?php 
	}
}
?>

<form enctype="multipart/form-data" novalidate="novalidate" id="careerForm" method="post" onsubmit="careerObj.clearErrorMsg();if(validateFields(this) != true){careerObj.validateCareerDetail()!=true;return false;}if(careerObj.validateCareerDetail()==true){AIM.submit(this,{'onStart': startCallback, 'onComplete': careerObj.setInformation});} else {return false;} " action="/CareerProductEnterprise/CareerEnterprise/populate" autocomplete="off">
<div class="cms-section">

		<input type="hidden" id="skillRequiredCount" name="skillRequiredCount" value="<?php echo $skillRequiredCount;?>"/>
		<input type="hidden" id="totalSkillRequiredCount" name="totalSkillRequiredCount" value="<?php echo $totalSkillRequiredCount;?>"/>

		<input type="hidden" id="jobProfileCount" name="jobProfileCount" value="<?php echo $jobProfileCount;?>"/>
		<input type="hidden" id="totaljobProfileCustomFieldCount" name="totaljobProfileCustomFieldCount" value="<?php echo $totaljobProfileCustomFieldCount;?>"/>

		<input type="hidden" id="howDoIGetThereCount" name="howDoIGetThereCount" value="<?php echo $howDoIGetThereCount;?>"/>
		<input type="hidden" id="totalhowDoIGetThereCustomFieldCount" name="totalhowDoIGetThereCustomFieldCount" value="<?php echo $totalhowDoIGetThereCustomFieldCount;?>"/>

		<input type="hidden" id="employmentOpportunitiesCount" name="employmentOpportunitiesCount" value="<?php echo $employmentOpportunitiesCount;?>"/>
		<input type="hidden" id="totalemploymentOpportunitiesCustomFieldCount" name="totalemploymentOpportunitiesCustomFieldCount" value="<?php echo $totalemploymentOpportunitiesCustomFieldCount;?>"/>

		<input type="hidden" id="whereToStudyCount" name="whereToStudyCount" value="<?php echo $whereToStudyCount;?>"/>
		<input type="hidden" id="totalwhereToStudyCustomFieldCount" name="totalwhereToStudyCustomFieldCount" value="<?php echo $totalwhereToStudyCustomFieldCount;?>"/>
	
		<input type="hidden" id="indiawhereToStudyCount" name="indiawhereToStudyCount" value="<?php echo $indiawhereToStudyCount;?>"/>
		<input type="hidden" id="totalindiawhereToStudySectionFieldCount" name="totalindiawhereToStudySectionFieldCount" value="<?php echo $totalindiawhereToStudySectionFieldCount;?>"/>

		<input type="hidden" id="abroadwhereToStudyCount" name="abroadwhereToStudyCount" value="<?php echo $abroadwhereToStudyCount;?>"/>
		<input type="hidden" id="totalabroadwhereToStudySectionFieldCount" name="totalabroadwhereToStudySectionFieldCount" value="<?php echo $totalabroadwhereToStudySectionFieldCount;?>"/>

		<input type="hidden" id="abroadwhereToStudyCourseIdCountForFirstSection" name="abroadwhereToStudyCourseIdCountForFirstSection" value="<?php echo $abroadwhereToStudyCourseIdCountForFirstSection;?>"/>
		<input type="hidden" id="totalabroadwhereToStudyCourseIdCountForFirstSection" name="totalabroadwhereToStudyCourseIdCountForFirstSection" value="<?php echo $totalabroadwhereToStudyCourseIdCountForFirstSection;?>"/>

		<input type="hidden" id="indiawhereToStudyCourseIdCountForFirstSection" name="indiawhereToStudyCourseIdCountForFirstSection" value="<?php echo $indiawhereToStudyCourseIdCountForFirstSection;?>"/>
		<input type="hidden" id="totalindiawhereToStudyCourseIdCountForFirstSection" name="totalindiawhereToStudyCourseIdCountForFirstSection" value="<?php echo $totalindiawhereToStudyCourseIdCountForFirstSection;?>"/>

		<input type="hidden" id="abroadwhereToStudyCourseIdCountForSecondSection" name="abroadwhereToStudyCourseIdCountForSecondSection" value="<?php echo $abroadwhereToStudyCourseIdCountForSecondSection;?>"/>
		<input type="hidden" id="totalabroadwhereToStudyCourseIdCountForSecondSection" name="totalabroadwhereToStudyCourseIdCountForSecondSection" value="<?php echo $totalabroadwhereToStudyCourseIdCountForSecondSection;?>"/>

		<input type="hidden" id="indiawhereToStudyCourseIdCountForSecondSection" name="indiawhereToStudyCourseIdCountForSecondSection" value="<?php echo $indiawhereToStudyCourseIdCountForSecondSection;?>"/>
		<input type="hidden" id="totalindiawhereToStudyCourseIdCountForSecondSection" name="totalindiawhereToStudyCourseIdCountForSecondSection" value="<?php echo $totalindiawhereToStudyCourseIdCountForSecondSection;?>"/>

		<input type="hidden" id="abroadwhereToStudyCourseIdCountForThirdSection" name="abroadwhereToStudyCourseIdCountForThirdSection" value="<?php echo $abroadwhereToStudyCourseIdCountForThirdSection;?>"/>
		<input type="hidden" id="totalabroadwhereToStudyCourseIdCountForThirdSection" name="totalabroadwhereToStudyCourseIdCountForThirdSection" value="<?php echo $totalabroadwhereToStudyCourseIdCountForThirdSection;?>"/>

		<input type="hidden" id="indiawhereToStudyCourseIdCountForThirdSection" name="indiawhereToStudyCourseIdCountForThirdSection" value="<?php echo $indiawhereToStudyCourseIdCountForThirdSection;?>"/>
		<input type="hidden" id="totalindiawhereToStudyCourseIdCountForThirdSection" name="totalindiawhereToStudyCourseIdCountForThirdSection" value="<?php echo $totalindiawhereToStudyCourseIdCountForThirdSection;?>"/>

		<input type="hidden" id="abroadwhereToStudyCourseIdCountForForthSection" name="abroadwhereToStudyCourseIdCountForForthSection" value="<?php echo $abroadwhereToStudyCourseIdCountForForthSection;?>"/>
		<input type="hidden" id="totalabroadwhereToStudyCourseIdCountForForthSection" name="totalabroadwhereToStudyCourseIdCountForForthSection" value="<?php echo $totalabroadwhereToStudyCourseIdCountForForthSection;?>"/>

		<input type="hidden" id="indiawhereToStudyCourseIdCountForForthSection" name="indiawhereToStudyCourseIdCountForForthSection" value="<?php echo $indiawhereToStudyCourseIdCountForForthSection;?>"/>
		<input type="hidden" id="totalindiawhereToStudyCourseIdCountForForthSection" name="totalindiawhereToStudyCourseIdCountForForthSection" value="<?php echo $totalindiawhereToStudyCourseIdCountForForthSection;?>"/>

		<input type="hidden" id="abroadwhereToStudyCourseIdCountForFifthSection" name="abroadwhereToStudyCourseIdCountForFifthSection" value="<?php echo $abroadwhereToStudyCourseIdCountForFifthSection;?>"/>
		<input type="hidden" id="totalabroadwhereToStudyCourseIdCountForFifthSection" name="totalabroadwhereToStudyCourseIdCountForFifthSection" value="<?php echo $totalabroadwhereToStudyCourseIdCountForFifthSection;?>"/>

		<input type="hidden" id="indiawhereToStudyCourseIdCountForFifthSection" name="indiawhereToStudyCourseIdCountForFifthSection" value="<?php echo $indiawhereToStudyCourseIdCountForFifthSection;?>"/>
		<input type="hidden" id="totalindiawhereToStudyCourseIdCountForFifthSection" name="totalindiawhereToStudyCourseIdCountForFifthSection" value="<?php echo $totalindiawhereToStudyCourseIdCountForFifthSection;?>"/>


            	<ul>
                	<li>
                    	<label>Careers:</label>
                        <div class="career-fields">
                        	<select id="careerId" name="careerId" onChange="careerObj.redirectToCareerPage(this.value,'<?php echo $careerType;?>','careerdetail');" class="universal-select" style="width:330px">
					<option value=0>Select</option>
					<?php for($i=0;$i<count($careerList);$i++):?>
					<option value="<?php echo $careerList[$i]['careerId'];?>" <?php if($careerId==$careerList[$i]['careerId']){echo "selected";}?>><?php echo $careerList[$i]['name'];?></option>
					<?php endfor;?>
				</select>
			<div id="careers_error" class="errorMsg" style="display:none;">&nbsp;</div>
                        </div>
                    </li>
		</ul>
		
		<?php
		/*$displayLimit = count($ldbMappedCourses)?count($ldbMappedCourses):1;
		for($i=1 ; $i<=5 ; $i++){
	?>	   <ul style="display:<?php if($i>$displayLimit) {echo 'none';}else{echo '';}?>" id="courseMapDiv_<?php echo $i?>">
		   <!--<input type="hidden" name="ldbToCourseMappingId_<?=$i;?>" id="ldbToCourseMappingId_<?=$i;?>" value="<?php  echo $ldbMappedCourses[$i-1]['ldbToCourseMappingId'];?>"/>-->
                    <li>
                    	<label>Category:</label>
                        <div class="career-fields">
                        	<select onchange="careerObj.getSubCatForCategory(this.value,<?php echo $i;?>)" id="catDiv_<?php echo $i;?>" class="universal-select" style="width:330px">
	<?php
			echo "<option value=0>Select</option>";
			foreach($catSubcatCourseList as $key=>$parent){
				echo "<option value=".$key;
				if($key == $ldbMappedCourses[$i-1]['parentId'])
					echo ' selected';
				echo ">".$parent['name']."</option>";
			}
	?>
			</select>
                        </div>
                    </li>
                    
                    <li>
                    	<label>Sub-category:</label>
                        <div class="career-fields" id="wrapper_subCatDiv_<?php echo $i;?>">
                        	<select id="subCatDiv_<?php echo $i;?>" onchange="careerObj.getCourseForSubCategory(this.value,<?php echo $i;?>)" class="universal-select" style="width:330px">
					<option value=0>Select</option>
				</select>
                        </div>
                    </li>
                    
                    <li>
                    	<label>LDB course:</label>
                        <div class="career-fields" id="wrapper_courseDiv_<?php echo $i;?>">
                        	<select id="courseDiv_<?php echo $i;?>" name="courseMap_<?php echo $i; ?>"  class="universal-select" style="width:330px">
					<option value=0>Select</option>
				</select>
                        </div>
			<?php		
			if($ldbMappedCourses[$i-1]['parentId']){
			?>
			<script>
				careerObj.getSubCatForCategory('<?=$ldbMappedCourses[$i-1]['parentId'];?>','<?=$i;?>','<?=$ldbMappedCourses[$i-1]['categoryID'];?>');
				careerObj.getCourseForSubCategory('<?=$ldbMappedCourses[$i-1]['categoryID'];?>','<?=$i;?>','<?=$ldbMappedCourses[$i-1]['LDBCourseID'];?>');
			</script>
			<?php
			} ?>
                    </li>
		    </ul>
			<?php
			}*/ ?>
                
            </div>
	    <!--<div class="spacer5 clearFix"></div>
            <div id="courseMapAdd"><a href="javascript:void(0)" onclick="careerObj.showCourseMapDiv()">+ Add more</a></div>-->
	    <script>
	    careerObj.currentDiv = '<?=($displayLimit)?>';
	    careerObj.showCourseMapDiv();
	    </script>
	    <!--<div class="spacer5 clearFix"></div>-->
            <div class="cms-section">
            	<div class="sectoion-title">
            		<h2>Introduction</h2>
                </div>
            	<ul>
                	<li>
                    	<label>Introduction*:</label>
                        <div class="career-fields">
                        	<textarea rows="3" cols="10" class="universal-select" style="width:500px; height:80px; vertical-align:text-top"  minlength="1" maxlength="1000"  required="true" caption="Short Description" id="shortIntro" name="shortIntro" validate="validateStr" ><?php echo $shortDescription;?></textarea>
			<div style="display:none"><div class="errorMsg" id="shortIntro_error"></div></div>
                        </div>
                    </li>
                    <li>
                    	<label>Small Image*:</label>
                        <div class="career-fields">
                        	<input type="file" size="30" name="smallImageIntro[]"/>
				<div class="errorMsg" id="smallImageIntro_error" style="display:none"></div>
                        </div>
                    </li>
                    
                    <li>
                    	<label>Large Image*:</label>
                        <div class="career-fields">
                        	<input type="file" size="30" name="largeImageIntro[]"/>
				<div class="errorMsg" id="largeImageIntro_error" style="display:none"></div>
                        </div>
                    </li>
                    
                    <li>
                    	<label>Thumbnail Image*:</label>
                        <div class="career-fields">
                        	<input type="file" size="30" name="thumbnailImageIntro[]"/>
				<div class="errorMsg" id="thumbnailImageIntro_error" style="display:none"></div>
                        </div>
                    </li>
		    
                    <li>
                    	<label>Meta Tag Description:</label>
                        <div class="career-fields">
                        	<textarea rows="3" cols="10" minlength="1" maxlength="200"  validate="validateStr" caption="Description" class="universal-select" style="width:500px; height:80px; vertical-align:text-top" name="metaTagsDescription" id="metaTagsDescription"><?php echo $metaTagsDescription;?></textarea>
			<div style="display:none"><div class="errorMsg" id="metaTagsDescription_error"></div></div>
                        </div>
                    </li>
		    
		    <li>
                    	<label>Meta Tag Keywords:</label>
                        <div class="career-fields">
                        	<textarea rows="3" cols="10" minlength="1" maxlength="200"  validate="validateStr" caption="Keywords" class="universal-select" style="width:500px; height:80px; vertical-align:text-top" name="metaTagsKeywords" id="metaTagsKeywords"><?php echo $metaTagsKeywords;?></textarea>
			<div style="display:none"><div class="errorMsg" id="metaTagsKeywords_error"></div></div>
                        </div>
                    </li>
                </ul>
            </div>
            
            <div class="cms-section">
            	<div class="sectoion-title">
		<?php if(empty($jobProfileCheckStatus)){ $jobProfileCheckStatus = 'false';}?>
		<input type="hidden" value="<?php echo $jobProfileCheckStatus;?>" id="jobProfileCheckStatus" name="jobProfileCheckStatus"/>
		<h2><input type="checkbox" id="jobProfileCheck"  <?php if($jobProfileCheckStatus=='true'){ echo 'checked';}?> onclick="setValueForSection('jobProfileCheck')"/>&nbsp; &nbsp; Job Profile</h2>
                </div>
            	<ul>
                	<li>
                    	<label>Description:</label>
                        <div class="career-fields">
                        	<textarea profanity="true" class='mceEditor' minlength="1" maxlength="10000" caption="Description" name="wikkicontent_jobProfile_description" id="wikkicontent_jobProfile_description" style="width:350px;height:100px;" ><?php echo $wikkicontent_jobProfile_description;?></textarea>
			    <div><div id="wikkicontent_jobProfile_description_error" class="errorMsg">&nbsp;</div></div>
                            <div class="spacer15 clearFix"></div>
                            <a href="javascript:void(0);" id="skillsRequired" onclick="careerObj.addTextBoxForSkillRequired();">+ Skills required</a>
			    <div id="skillRequiredText">
			<?php if($skillRequiredCount!='') {$skillRequiredCountArr = explode(',',$skillRequiredCount);}else{$skillRequiredCountArr=array();}?>
			<?php $i=1;?>
			<?php foreach($skillRequiredCountArr as $key=>$value):?>
			<div id="skillRequiredId<?php echo $value;?>">
			<TABLE width="100%">
				<TR>
				    <TD  style="width:330px;" >
					<input  style="width:330px;" class="universal-txt-field" type="text" minlength="1" maxlength="250"  validate="validateStr" caption="Skill" id="skillRequired<?php echo $i;?>" value="<?php echo htmlspecialchars(${'skillRequired_'.$value});?>" >
				     </TD>
				    <TD>
					<a href="javascript:void(0);" onclick="careerObj.removeTextBoxForSkillRequired('<?php echo $value;?>','<?php echo base64_encode(${'skillRequired_'.$value});?>');" value="removeButton" class="removeButton">Remove</a>
				   </TD>
				</TR>
				<tr><td><div><div style="display:none"><div class="errorMsg" id="skillRequired<?php echo $i;?>_error"></div></div></div></td></tr>
			</TABLE>
		 </div>             
			<script>
				careerObj.counter['skillRequired']= '<?php echo $value;?>';
			</script>
			<?php $i++;?>
			<?php endforeach;?>
				 
			    </div>
                        </div>
                    </li>
		    
                    <li>
                    	<label>A typical Day:</label>
                        <div class="career-fields">
                        	<input type="text" class="universal-txt-field" style="width:330px; color:#000; font-size:12px" default="Enter YouTube URL" value="<?php if($jobProfileATypicalDay!=''){ echo $jobProfileATypicalDay;}else{ echo 'Enter YouTube URL';}?>" id="jobProfileATypicalDay" name="jobProfileATypicalDay" blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')"/><?php if($jobProfileATypicalDay!=''){ ?> &nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','jobProfileATypicalDay','Enter YouTube URL');">Clear</a><?php } ?><div><div id="typicalDay_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                        </div>

                    </li>
                    
                   	<li>
                    	<label>Clock Work:</label>
                        <div class="career-fields">
                        	<textarea profanity="true" class='mceEditor'  minlength="1" maxlength="10000"  caption="Clock Work"  name="wikkicontent_jobProfile_clockwork" id="wikkicontent_jobProfile_clockwork" style="width:350px;height:100px;" ><?php echo $wikkicontent_jobProfile_clockwork;?></textarea>
				<div><div id="wikkicontent_jobProfile_clockwork_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                        </div>
                    </li>
                </ul>
                
                <div class="clearFix"></div>
		<div id="jobProfileMainId">
		<?php //for($i=0;$i<$customFieldForJobProfileCount;$i++):?>
		<div id="jobProfileParentId"></div>
		<?php if($jobProfileCount!='') {$jobProfileCountArr = explode(',',$jobProfileCount);}else{$jobProfileCountArr=array();}?>
		<?php $i=1;?>
		<?php foreach($jobProfileCountArr as $key=>$value):?>
		<?php if(${'wikkicontent_title_jobProfile_'.$value}!=''){$titleJP = 1;}else{$titleJP='';}?>
		<?php if(${'wikkicontent_detail_jobProfile_'.$value}!=''){$descriptionJP = 1;}else{$descriptionJP='';}?>
                <div class="steps-main-block" style="padding-left:130px" id="jobProfileCustomField_<?php echo $value;?>">
                    <div class="steps-block" style="width:375px">
                    	<p class="custom-title">- Create Custom Field</p>
                        <ul>
                            <li>
                                <div class="career-fields">
					<input minlength="1" maxlength="250" caption="title" validate="validateStr" type="text" class="universal-txt-field" style="width:82%; font-size:12px;color:#000;" id="wikkicontent_title_jobProfile_<?php echo $i;?>" value="<?php if(base64_encode(${'wikkicontent_title_jobProfile_'.$value})!=''){ echo htmlspecialchars(${'wikkicontent_title_jobProfile_'.$value});}else{ echo 'Enter Title';}?>" blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Title"/> &nbsp;<a href="javascript:void(0)" onClick="careerObj.removeCustomFields('jobProfile','<?php echo $value;?>','<?php echo $titleJP;?>','<?php echo $descriptionJP;?>');">Remove</a>
				<div style="display:none"><div class="errorMsg"  id="wikkicontent_title_jobProfile_<?php echo $i;?>_error"></div></div>
                                </div>
                            </li>
                            
                            <li>
                                <div class="career-fields">
                                    <textarea minlength="1" maxlength="10000"  caption="Job Wiki Detail" class='mceEditor' caption="Description" id="wikkicontent_detail_jobProfile_<?php echo $i;?>" style="width:370px;height:100px;" ><?php echo ${'wikkicontent_detail_jobProfile_'.$value};?></textarea>
				<div><div id="wikkicontent_detail_jobProfile_<?php echo $i;?>_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                                </div>
                            </li>
                           
                        </ul>	
                        <div class="clearFix"></div>
                </div>
	 </div>
		<script>
			careerObj.addCustomFieldsActualCount['jobProfile']= '<?php echo $value;?>';
		</script>
		<?php $i++;?>
		<?php endforeach;?>
		 <div <?php if(count($jobProfileCountArr)==5){?>style="display:none"<?php } ?>class="career-fields" style="padding-left:130px;" id="jobProfileAddMore" >
                 	<div ><a href="javascript:void(0);" onclick="careerObj.addCustomFields('jobProfile')">+ Add more</a></div>
                 </div>
                 </div>
	</div>                
           
	    <?php //for($i=0;$i<2;$i++):?>
            <script>
		//careerObj.addCustomField('jobProfileCustomFieldAddMore','wikkicontent_detail_jobProfile_','jobProfileCustomField0','JobProfile','jobProfileCustomField');
	    </script>
	    <?php //endif;?>
            <div class="cms-section">
            	<div class="sectoion-title">
		<?php if(empty($employmentOpportunitiesCheckStatus)){ $employmentOpportunitiesCheckStatus = 'false';}?>
		<input type="hidden" value="<?php echo $employmentOpportunitiesCheckStatus;?>" id="employmentOpportunitiesCheckStatus" name="employmentOpportunitiesCheckStatus"/>
		<h2><input type="checkbox" id="employmentOpportunitiesCheck" <?php if($employmentOpportunitiesCheckStatus=='true'){ echo 'checked';}?> onclick="setValueForSection('employmentOpportunitiesCheck')"/>&nbsp; &nbsp; Employment Opportunities</h2>
                </div>
            	<ul>
                	<li>
                    	<label>Salary Range:</label>
                        <div class="career-fields">
                        	<div class="sal-range">From: <select class="universal-select" style="width:120px" name="minimumSalaryInLacs">
				<option >Lakh</option>
				<?php for($i=1;$i<100;$i++):?>
				<option <?php if($minimumSalaryInLacs==$i && !is_null($minimumSalaryInLacs)){echo "selected='selected'";}?> value="<?=$i;?>"><?=$i;?></option>
				<?php endfor;?>
			    </select> &nbsp;
                            <select class="universal-select" style="width:120px" name="minimumSalaryInThousand">
				<option>Thousand</option>
				<?php for($i=1;$i<100;$i++):?>
				<option <?php if($minimumSalaryInThousand==$i && !is_null($minimumSalaryInThousand)){echo "selected='selected'";}?> value="<?=$i;?>"><?=$i;?></option>
				<?php endfor;?>
			    </select>
                            </div>
                            <div class="sal-range">
                            To: <select class="universal-select" style="width:120px" name="maximumSalaryInLacs">
				<option>Lakh</option>
				<?php for($i=1;$i<100;$i++):?>
				<option <?php if($maximumSalaryInLacs==$i && !is_null($maximumSalaryInLacs)){echo "selected='selected'";}?> value="<?=$i;?>"><?=$i;?></option>
				<?php endfor;?>
			   </select> &nbsp;
                            <select class="universal-select" style="width:120px" name="maximumSalaryInThousand"> 
				<option>Thousand</option>
				<?php for($i=1;$i<100;$i++):?>
				<option <?php if($maximumSalaryInThousand==$i && !is_null($maximumSalaryInThousand)){echo "selected='selected'";}?> value="<?=$i;?>"><?=$i;?></option>
				<?php endfor;?>
			    </select>
                            </div>
                            
                        </div>
                    </li>

                    <li>
                    	<label>Description:</label>
                        <div class="career-fields">
                        	<textarea profanity="true" class='mceEditor' minlength="1" maxlength="10000" caption="Employment Description"  name="wikkicontent_employment_description" id="wikkicontent_employment_description" style="width:370px;height:100px;" ><?php echo $wikkicontent_employment_description;?></textarea>
  			    <div><div id="wikkicontent_employment_description_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                            <div class="spacer20 clearFix"></div>
                            <a href="javascript:void(0);" onClick="careerObj.hideshowEarningCustomFiled('employmentEarningCustomField0');" id="earningAnchor"> <?php if($wikkicontent_detail_employment_earning_0==''){?>+ Earnings <?php }else{?>- Earnings <?php }?></a>
			     <div class="clearFix"></div>
                <div class="steps-main-block" id="employmentEarningCustomField0" <?php if($wikkicontent_detail_employment_earning_0==''){?>style="display:none;" <?php } ?>>
                    <div class="steps-block" style="width:375px">
                    	<p class="custom-title"></p>
                        <ul>
                            <li>
                                <div class="career-fields">
                                    <textarea profanity="true" class='mceEditor' minlength="1" maxlength="10000" caption="Earning Description " id="wikkicontent_detail_employment_earning_0" style="width:370px;height:100px;" name="wikkicontent_detail_employment_earning_0"><?php echo $wikkicontent_detail_employment_earning_0;?></textarea>
				   <div><div id="wikkicontent_detail_employment_earning_0_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                                </div>
                            </li>
                        </ul>	
                        <div class="clearFix"></div>
                    </div>
                </div>
                
                       <div class="spacer5 clearFix"></div>
                        </div>
                    </li>
                    <li>
                    	<label>Recruiting Companies:</label>
                        <div class="career-fields">
                            <input name="firstCompanyName" id="firstCompanyName" minlength="1" maxlength="100" caption="name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($firstCompanyName)!=''){ echo htmlspecialchars($firstCompanyName);}else{ echo 'Enter Company Name';}?>" blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Company Name"/>&nbsp;<?php if(base64_encode($firstCompanyName)!=''){ ?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','firstCompanyName','Enter Company Name')">Clear</a><?php } ?>	
                            <input type="file" size="30" name="firstCompanyImage[]"/><?php if(!empty($firstCompanyImage)){ ?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','firstCompanyImage','Image')">Clear</a><?php } ?>	
				<div class="clearFix"></div>
			   <div style="float:left; width:220px;"><div id="firstCompanyName_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
			    <div class="errorMsg" id="firstCompanyImage_error" style=" float:left;display:none;">&nbsp;</div>
                            <div class="spacer15 clearFix"></div>
                            <input name="secondCompanyName"  id="secondCompanyName" minlength="1" maxlength="100" caption="name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($secondCompanyName)!=''){ echo htmlspecialchars($secondCompanyName);}else{ echo 'Enter Company Name';}?>"  blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Company Name"/>&nbsp;<?php if(base64_encode($secondCompanyName)!=''){?> &nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','secondCompanyName','Enter Company Name')">Clear</a><?php } ?>	
                            <input type="file" size="30" name="secondCompanyImage[]"/><?php if(!empty($secondCompanyImage)){ ?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','secondCompanyImage','Image')">Clear</a><?php } ?>	
			    <div class="clearFix"></div>
                            <div style="float:left; width:220px;"><div id="secondCompanyName_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
			     <div><div id="secondCompanyImage_error" class="errorMsg" style="float:left;display:none;">&nbsp;</div></div>
                            <div class="spacer15 clearFix"></div>
                            <input name="thirdCompanyName" id="thirdCompanyName" minlength="1" maxlength="100" caption="name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($thirdCompanyName)!=''){ echo htmlspecialchars($thirdCompanyName);}else{ echo 'Enter Company Name';}?>" blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Company Name"/>&nbsp;<?php if(base64_encode($thirdCompanyName)!=''){ ?> &nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','thirdCompanyName','Enter Company Name')">Clear</a><?php } ?>	
                            <input type="file" size="30" name="thirdCompanyImage[]"/><?php if(!empty($thirdCompanyImage)){ ?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','thirdCompanyImage','Image')">Clear</a>	<?php } ?>
			    <div class="clearFix"></div>
			    <div style="float:left; width:220px;"><div id="thirdCompanyName_error" class="errorMsg" style="display:none;">&nbsp;</div></div>  
			    <div id="thirdCompanyImage_error" class="errorMsg" style="float:left;display:none;">&nbsp;</div>                          
                            <div class="spacer15 clearFix"></div>
                            <input name="forthCompanyName" id="forthCompanyName" minlength="1" maxlength="100" caption="name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($forthCompanyName)!=''){ echo htmlspecialchars($forthCompanyName);}else{ echo 'Enter Company Name';}?>" blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Company Name"/>&nbsp;<?php if(base64_encode($forthCompanyName)!=''){ ?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','forthCompanyName','Enter Company Name')">Clear</a><?php } ?>	
                            <input type="file" size="30" name="forthCompanyImage[]"/><?php if(!empty($forthCompanyImage)){ ?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','forthCompanyImage','Image')">Clear</a><?php } ?>
			    <div class="clearFix"></div>
			    <div style="float:left; width:220px;"><div id="forthCompanyName_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
			    <div id="forthCompanyImage_error" class="errorMsg" style="float:left;display:none;">&nbsp;</div>
                        </div>
                    </li>
		    
		    <li>
                    	<label>Company Name:</label>
                        <div class="career-fields">
                        	<textarea minlength="1" maxlength="10000"  profanity="true" class='mceEditor' caption="Description" name="wikkicontent_employmentOpportunities_recruitingcompany" id="wikkicontent_employmentOpportunities_recruitingcompany" style="width:370px;height:100px;"><?php echo $wikkicontent_employmentOpportunities_recruitingcompany;?></textarea>    			<div><div id="wikkicontent_employmentOpportunities_recruitingcompany_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
						</div>
                    </li>
                    
                   	
                </ul>
                
                <div class="clearFix"></div>
		<div id="employmentOpportunitiesMainId">
		<div id="employmentOpportunitiesParentId"></div>
		<?php if($employmentOpportunitiesCount!='') {$employmentOpportunitiesCountArr = explode(',',$employmentOpportunitiesCount);}else{$employmentOpportunitiesCountArr=array();}?>
		<?php $i=1;?>
		<?php foreach($employmentOpportunitiesCountArr as $key=>$value):?>
		<?php if(${'wikkicontent_title_employmentOpportunities_'.$value}!=''){$titleEO = '1';}else{$titleEO = '';}?>
		<?php if(${'wikkicontent_detail_employmentOpportunities_'.$value}!=''){$descriptionEO = '1';}else{$descriptionEO = '';}?>
                <div class="steps-main-block" style="padding-left:130px" id="employmentOpportunitiesCustomField_<?php echo $value;?>">
                    <div class="steps-block" style="width:375px">
                    	<p class="custom-title">- Create Custom Field</p>
                        <ul>
                            <li>
                                <div class="career-fields">
					<input minlength="1" maxlength="250" caption="title" validate="validateStr" type="text" class="universal-txt-field" style="width:82%; font-size:12px;color:#000;" id="wikkicontent_title_employmentOpportunities_<?php echo $i;?>" value="<?php if(base64_encode(${'wikkicontent_title_employmentOpportunities_'.$value})!=''){ echo htmlspecialchars(${'wikkicontent_title_employmentOpportunities_'.$value});}else{ echo 'Enter Title';}?>" blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Title"/> &nbsp;<a href="javascript:void(0)" onClick="careerObj.removeCustomFields('employmentOpportunities','<?php echo $value;?>','<?php echo $titleEO;?>','<?php echo $descriptionEO;?>');" >Remove</a>
				<div style="display:none"><div class="errorMsg"  id="wikkicontent_title_employmentOpportunities_<?php echo $i;?>_error"></div></div>
                                </div>
                            </li>
                            
                            <li>
                                <div class="career-fields">
                                    <textarea minlength="1" maxlength="10000" class='mceEditor' caption="Description" id="wikkicontent_detail_employmentOpportunities_<?php echo $i;?>" style="width:370px;height:100px;" ><?php echo ${'wikkicontent_detail_employmentOpportunities_'.$value};?></textarea>
				<div><div id="wikkicontent_detail_employmentOpportunities_<?php echo $i;?>_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                                </div>
                            </li>
                           
                        </ul>	
                        <div class="clearFix"></div>
                </div>
	 </div>
		<script>
			careerObj.addCustomFieldsActualCount['employmentOpportunities']= '<?php echo $value;?>';
		</script>
		<?php $i++;?>
		<?php endforeach;?>
		 <div <?php if(count($employmentOpportunitiesCountArr)==5){?>style="display:none"<?php } ?>class="career-fields" style="padding-left:130px;" id="employmentOpportunitiesAddMore" >
                 	<div ><a href="javascript:void(0);" onclick="careerObj.addCustomFields('employmentOpportunities')">+ Add more</a></div>
                 </div>
                 </div>
	</div>  

            <div class="cms-section">
            	<div class="sectoion-title">
		<?php if(empty($howDoIGetThereCheckStatus)){ $howDoIGetThereCheckStatus = 'false';}?>
		<input type="hidden" value="<?php echo $howDoIGetThereCheckStatus;?>" id="howDoIGetThereCheckStatus" name="howDoIGetThereCheckStatus"/>
		<h2><input type="checkbox" <?php if($howDoIGetThereCheckStatus=='true'){ echo 'checked';}?> id="howDoIGetThereCheck" onclick="setValueForSection('howDoIGetThereCheck')"/>&nbsp; &nbsp; How do I get there?</h2>
                </div>
            	<ul>
                	<!-- <li>
                    	<label>Image:</label>
                        <div class="career-fields">
                        	<input type="file" size="30" name="pathImage[]"/>< ?php if(!empty($pathImage)){ ?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('< ?php echo $careerId;?>','pathImage','Image')">Clear</a>< ?php } ?>
				<div class="errorMsg" id="pathImage_error" style="display:none"></div>
                        </div>
                    </li> -->
		    <li>
                    	<label>Description:</label>
                        <div class="career-fields">
                        	<textarea minlength="1" maxlength="10000"  profanity="true" class='mceEditor' caption="Description" name="wikkicontent_hdigt_detail" id="wikkicontent_hdigt_detail" style="width:370px;height:100px;"><?php echo $wikkicontent_hdigt_detail;?></textarea>    			<div><div id="wikkicontent_hdigt_detail_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
						</div>
                    </li>
                </ul>
                
                <div class="clearFix"></div>
		<div id="howDoIGetThereMainId">
		<div id="howDoIGetThereParentId"></div>
		<?php if($howDoIGetThereCount!='') {$howDoIGetThereCountArr = explode(',',$howDoIGetThereCount);}else{$howDoIGetThereCountArr=array();}?>
		<?php $i=1;?>
		<?php foreach($howDoIGetThereCountArr as $key=>$value):?>
		<?php if(${'wikkicontent_title_howDoIGetThere_'.$value}!=''){$titleHDIGT = '1';}else{$titleHDIGT = '';}?>
		<?php if(${'wikkicontent_detail_howDoIGetThere_'.$value}!=''){$descriptionHDIGT = '1';}else{$descriptionHDIGT = '';}?>
                <div class="steps-main-block" style="padding-left:130px" id="howDoIGetThereCustomField_<?php echo $value;?>">
                    <div class="steps-block" style="width:375px">
                    	<p class="custom-title">- Create Custom Field</p>
                        <ul>
                            <li>
                                <div class="career-fields">
					<input minlength="1" maxlength="250" caption="title" validate="validateStr" type="text" class="universal-txt-field" style="width:82%; font-size:12px;color:#000;" id="wikkicontent_title_howDoIGetThere_<?php echo $i;?>" value="<?php if(base64_encode(${'wikkicontent_title_howDoIGetThere_'.$value})!=''){echo htmlspecialchars(${'wikkicontent_title_howDoIGetThere_'.$value});}else{ echo 'Enter Title';}?>" blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Title"/> &nbsp;<a href="javascript:void(0)" onClick="careerObj.removeCustomFields('howDoIGetThere','<?php echo $value;?>','<?php echo $titleHDIGT;?>','<?php echo $descriptionHDIGT;?>');" >Remove</a>
				<div style="display:none"><div class="errorMsg"  id="wikkicontent_title_howDoIGetThere_<?php echo $i;?>_error"></div></div>
                                </div>
                            </li>
                            
                            <li>
                                <div class="career-fields">
                                    <textarea minlength="1" maxlength="10000" class='mceEditor' caption="Description" id="wikkicontent_detail_howDoIGetThere_<?php echo $i;?>" style="width:370px;height:100px;" ><?php echo ${'wikkicontent_detail_howDoIGetThere_'.$value};?></textarea>
				<div><div id="wikkicontent_detail_howDoIGetThere_<?php echo $i;?>_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                                </div>
                            </li>
                           
                        </ul>	
                        <div class="clearFix"></div>
                </div>
	 </div>
		<script>
			careerObj.addCustomFieldsActualCount['howDoIGetThere']= '<?php echo $value;?>';
		</script>
		<?php $i++;?>
		<?php endforeach;?>
		 <div <?php if(count($howDoIGetThereCountArr)==5){?>style="display:none"<?php } ?>class="career-fields" style="padding-left:130px;" id="howDoIGetThereAddMore" >
                 	<div ><a href="javascript:void(0);" onclick="careerObj.addCustomFields('howDoIGetThere')">+ Add more</a></div>
                 </div>
                 </div>
	</div>
            
            <div class="cms-section">
            	<div class="sectoion-title">
		<?php if(empty($whereToStudyCheckStatus)){ $whereToStudyCheckStatus = 'false';}?>
		<input type="hidden" value="<?php echo $whereToStudyCheckStatus;?>" id="whereToStudyCheckStatus" name="whereToStudyCheckStatus"/>
		<h2><input type="checkbox" id="whereToStudyCheck"  <?php if($whereToStudyCheckStatus=='true'){ echo 'checked';}?> onclick="setValueForSection('whereToStudyCheck')"/>&nbsp; &nbsp; Where to Study</h2>
                </div>
                <h3>India</h3>
            	<ul>
                	<li>
                    	<label>Logo:</label>
                        <div class="career-fields">
                            <input name="instituteNameIndia1" id="instituteNameIndia1" minlength="1" maxlength="100" caption="institute name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($instituteNameIndia1)!=''){ echo htmlspecialchars($instituteNameIndia1);}else{ echo 'Enter Institute Name';}?>" blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Institute Name"/>&nbsp;<?php if(base64_encode($instituteNameIndia1)!=''){ ?> &nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','instituteNameIndia1','Enter Institute Name')">Clear</a><?php } ?>	
                            <input type="file" size="30" name="logoImageIndia1[]"/><?php if(!empty($logoImageIndia1)){ ?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','logoImageIndia1','Image')">Clear</a><?php } ?>
			    <div class="clearFix"></div>
			    <div style="float:left; width:220px;"><div id="instituteNameIndia1_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
			    <div class="errorMsg" id="logoImageIndia1_error" style=" float:left;display:none;">&nbsp;</div>

                            <div class="spacer15 clearFix"></div>
                            <input name="instituteNameIndia2" id="instituteNameIndia2" minlength="1" maxlength="100" caption="institute name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($instituteNameIndia2)!=''){ echo htmlspecialchars($instituteNameIndia2);}else{ echo 'Enter Institute Name';}?>"  blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Institute Name"/>&nbsp;<?php if(base64_encode($instituteNameIndia2)!=''){ ?> &nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','instituteNameIndia2','Enter Institute Name')">Clear</a><?php } ?>	
                            <input type="file" size="30" name="logoImageIndia2[]"/><?php if(!empty($logoImageIndia2)){ ?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','logoImageIndia2','Image')">Clear</a><?php } ?>
			    <div class="clearFix"></div>
			    <div style="float:left; width:220px;"><div id="instituteNameIndia2_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
			    <div class="errorMsg" id="logoImageIndia2_error" style=" float:left;display:none;">&nbsp;</div>

                            <div class="spacer15 clearFix"></div>
                            <input name="instituteNameIndia3" id="instituteNameIndia3" minlength="1" maxlength="100" caption="institute name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($instituteNameIndia3)!=''){ echo htmlspecialchars($instituteNameIndia3);}else{ echo 'Enter Institute Name';}?>"  blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Institute Name"/>&nbsp;<?php if(base64_encode($instituteNameIndia3)!=''){ ?> &nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','instituteNameIndia3','Enter Institute Name')">Clear</a><?php } ?>	
                            <input type="file" size="30" name="logoImageIndia3[]"/><?php if(!empty($logoImageIndia3)){ ?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','logoImageIndia3','Image')">Clear</a><?php } ?>
			    <div class="clearFix"></div>
			    <div style="float:left; width:220px;"><div id="instituteNameIndia3_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
			    <div class="errorMsg" id="logoImageIndia3_error" style=" float:left;display:none;">&nbsp;</div>

                            <div class="spacer15 clearFix"></div>
                            <input name="instituteNameIndia4" id="instituteNameIndia4" minlength="1" maxlength="100" caption="institute name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($instituteNameIndia4)!=''){ echo htmlspecialchars($instituteNameIndia4);}else{ echo 'Enter Institute Name';}?>"  blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Institute Name"/>&nbsp;<?php if(base64_encode($instituteNameIndia4)!=''){ ?> &nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','instituteNameIndia4','Enter Institute Name')">Clear</a><?php } ?>	
                            <input type="file" size="30" name="logoImageIndia4[]"/><?php if(!empty($logoImageIndia4)){ ?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','logoImageIndia4','Image')">Clear</a><?php } ?>
			    <div class="clearFix"></div>
			    <div style="float:left; width:220px;"><div id="instituteNameIndia4_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
			    <div class="errorMsg" id="logoImageIndia4_error" style=" float:left;display:none;">&nbsp;</div>
                        </div>
                    </li>
		<?php if($indiawhereToStudyCount!='') {$indiawhereToStudyCountArr = explode(',',$indiawhereToStudyCount);}else{$indiawhereToStudyCountArr=array();}?>
		<?php $i=1;?>
		<?php $arrFunctionIndia = array('addCourseIdForFirstSection','addCourseIdForSecondSection','addCourseIdForThirdSection','addCourseIdForForthSection','addCourseIdForFifthSection');?>
		<?php $arrSectionIndia = array('','First','Second','Third','Forth','Fifth')?>
		<?php foreach($indiawhereToStudyCountArr as $key=>$value):?>
		<li id="indiaSection_<?php echo $value;?>">
                    	<div class="add-course-block">
                            <label><span>Heading:</span><br />
                            <span class="label-level-2">Prestigious Institutes:</span></label>
                            <div class="career-fields add-course-box">
                                <input minlength="1" maxlength="250" caption="heading" validate="validateStr" type="text" class="universal-txt-field" style="width:345px" id="indiaHeading_<?php echo $value;?>" value="<?php echo htmlspecialchars(${'indiaHeading_'.$value});?>"/>&nbsp;<?php if($i>1){?><a href="javascript:void(0)" onClick="careerObj.removeSection('india','<?php echo $value;?>','<?php echo $arrSectionIndia[$value];?>');">Remove</a><?php } ?>&nbsp;<?php if($i==1 && base64_encode(${'indiaHeading_'.$value})!=''){?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','indiaHeading_<?php echo $value;?>')">Clear</a><?php } ?>
			<div style="display:none"><div class="errorMsg"  id="indiaHeading_<?php echo $value;?>_error"></div></div>
                                <div class="clearFix spacer15"></div>
                                <?php $j=1;?>
				<?php if(${'indiawhereToStudyCourseIdCountFor'.$arrSectionIndia[$value].'Section'}!='') {${'indiawhereToStudyCourseIdCountFor'.$arrSectionIndia[$value].'SectionArr'} = explode(',',${'indiawhereToStudyCourseIdCountFor'.$arrSectionIndia[$value].'Section'});}else{${'indiawhereToStudyCourseIdCountFor'.$arrSectionIndia[$value].'SectionArr'}=explode(',','1,2');}?>
				<?php foreach(${'indiawhereToStudyCourseIdCountFor'.$arrSectionIndia[$value].'SectionArr'} as $k=>$v):?>
				
                                <div class="flLt" id="indiaCourseText_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>_Div" >
<input type="text" class="universal-txt-field" style="width:150px; color:#000; font-size:12px" id="indiaCourseText_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>"   value="<?php if(base64_encode(${'indiaCourseText_'.$arrSectionIndia[$value].'_'.$v})!=''){ echo htmlspecialchars(${'indiaCourseText_'.$arrSectionIndia[$value].'_'.$v});}else{ echo 'Enter Text';}?>" blurmethod="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" default="Enter Text" caption="Enter Text" validate="validateStr" minlength="1" maxlength="100" />
				<div style="display:none"><div class="errorMsg"  id="indiaCourseText_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>_error"></div></div></div>
				<span id="indiaOR_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>" class="or-txt">OR</span>
				<div class="flLt" id="indiaCourseId_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>_Div">
<input type="text" class="universal-txt-field" style="width:150px; color:#000; font-size:12px" id="indiaCourseId_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>" value="<?php if(${'indiaCourseId_'.$arrSectionIndia[$value].'_'.$v}!=''){ echo ${'indiaCourseId_'.$arrSectionIndia[$value].'_'.$v};}else{ echo 'Enter Course Id';}?>" blurmethod="checkTextElementOnTransition(this,'blur');careerObj.validationCourseIdInWhereToStudySection(this.value,'indiaCourseId_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>')" onfocus="checkTextElementOnTransition(this,'focus');" default="Enter Course Id" minlength="1" maxlength="10" caption="Course Id"/>&nbsp;<?php if($j<3 && (${'indiaCourseId_'.$arrSectionIndia[$value].'_'.$v}!='' || ${'indiaCourseText_'.$arrSectionIndia[$value].'_'.$v}!='')){?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearTextForPrestigiousInstitute('<?php echo $careerId;?>','indiaCourseId_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>','indiaCourseText_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>')">Clear</a><?php } ?>
				<?php if($j>2){?><a href="javascript:void(0)" onClick="careerObj.removeCourseIdForSection('india','<?php echo $value;?>','<?php echo ${'indiaCourseId_'.$arrSectionIndia[$value].'_'.$v}?>','<?php echo $v;?>','<?php echo $arrSectionIndia[$value]?>');" id="remove_india_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>">Remove</a><?php } ?>
				
				<div style="display:none"><div class="errorMsg"  id="indiaCourseId_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>_error"></div></div></div>

                                <div class="clearFix spacer10" id="spacer_india_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>"></div>
				
				<?php $j++;?>   
				
                                <?php endforeach;?>
				<script>careerObj.addSectionCourseIdActualCount['indiawhereToStudy<?php echo $arrSectionIndia[$value];?>Section'] = '<?php echo $v;?>';</script>
                                <a href="javascript:void(0);" onClick="careerObj.addCourseIdForSection('india','<?php echo $value;?>','<?php echo $arrSectionIndia[$value];?>');">+ Add More</a>
                            </div>
                        </div>
                    </li>
			<script>
				careerObj.addSectionActualCount['indiawhereToStudy']= '<?php echo $value;?>';
			</script>
		<?php $i++;?>
		<?php endforeach;?>
                    <li id="indiaAddMore" <?php if(count($indiawhereToStudyCountArr)>4){?> style="display:none;"<?php } ?>><label>&nbsp;</label><div class="career-fields"><a href="javascript:void(0);" onClick="careerObj.addSection('india');">+ Add Section</a></div></li> 
                </ul>
                
                <div class="spacer10 clearFix"></div>
                <h3>Abroad</h3>
                <ul>
                	<li>
                    	<label>Logo:</label>
                        <div class="career-fields">
                            <input name="instituteNameAbroad1" id="instituteNameAbroad1" minlength="1" maxlength="100" caption="institute name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($instituteNameAbroad1)!=''){echo htmlspecialchars($instituteNameAbroad1);}else{ echo 'Enter Institute Name';}?>"  blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Institute Name"/>&nbsp;<?php if(base64_encode($instituteNameAbroad1)!=''){ ?> &nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','instituteNameAbroad1','Enter Institute Name')">Clear</a><?php } ?>	
                            <input type="file" size="30" name="logoImageAbroad1[]"/><?php if(!empty($logoImageAbroad1)){?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','logoImageAbroad1','Image')">Clear</a><?php } ?>	
			    <div class="clearFix"></div>
			    <div style="float:left; width:220px;"><div id="instituteNameAbroad1_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
			    <div class="errorMsg" id="logoImageAbroad1_error" style=" float:left;display:none;">&nbsp;</div>
			    
                            <div class="spacer15 clearFix"></div>
                            <input name="instituteNameAbroad2" id="instituteNameAbroad2" minlength="1" maxlength="100" caption="institute name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($instituteNameAbroad2)!=''){echo htmlspecialchars($instituteNameAbroad2);}else{ echo 'Enter Institute Name';}?>"  blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Institute Name"/>&nbsp;<?php if(base64_encode($instituteNameAbroad2)!=''){ ?> &nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','instituteNameAbroad2','Enter Institute Name')">Clear</a><?php } ?>
                            <input type="file" size="30" name="logoImageAbroad2[]"/><?php if(!empty($logoImageAbroad2)){?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','logoImageAbroad2','Image')">Clear</a><?php } ?>	
			    <div class="clearFix"></div>
			    <div style="float:left; width:220px;"><div id="instituteNameAbroad2_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
			    <div class="errorMsg" id="logoImageAbroad2_error" style=" float:left;display:none;">&nbsp;</div>

                            <div class="spacer15 clearFix"></div>
                            <input name="instituteNameAbroad3" id="instituteNameAbroad3" minlength="1" maxlength="100" caption="institute name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($instituteNameAbroad3)!=''){echo htmlspecialchars($instituteNameAbroad3);}else{ echo 'Enter Institute Name';}?>"  blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Institute Name"/>&nbsp;<?php if(base64_encode($instituteNameAbroad3)!=''){ ?> &nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','instituteNameAbroad3','Enter Institute Name')">Clear</a><?php } ?>	
                            <input type="file" size="30" name="logoImageAbroad3[]"/><?php if(!empty($logoImageAbroad3)){?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','logoImageAbroad3','Image')">Clear</a><?php } ?>	
			    <div class="clearFix"></div>
			    <div style="float:left; width:220px;"><div id="instituteNameAbroad3_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
			    <div class="errorMsg" id="logoImageAbroad3_error" style=" float:left;display:none;">&nbsp;</div>
                            <div class="spacer15 clearFix"></div>
                            <input name="instituteNameAbroad4" id="instituteNameAbroad4" minlength="1" maxlength="100" caption="institute name" validate="validateStr" type="text" class="universal-txt-field" style="width:200px; color:#000; font-size:12px" value="<?php if(base64_encode($instituteNameAbroad4)!=''){echo htmlspecialchars($instituteNameAbroad4);}else{ echo 'Enter Institute Name';}?>"  blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Institute Name"/>&nbsp;<?php if(base64_encode($instituteNameAbroad4)!=''){ ?> &nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','instituteNameAbroad4','Enter Institute Name')">Clear</a><?php } ?>	
                            <input type="file" size="30" name="logoImageAbroad4[]"/><?php if(!empty($logoImageAbroad4)){?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','logoImageAbroad4','Image')">Clear</a><?php } ?>	
			    <div class="clearFix"></div>
			    <div style="float:left; width:220px;"><div id="instituteNameAbroad4_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
			    <div class="errorMsg" id="logoImageAbroad4_error" style=" float:left;display:none;">&nbsp;</div>
                        </div>
                    </li>
                <?php if($abroadwhereToStudyCount!='') {$abroadwhereToStudyCountArr = explode(',',$abroadwhereToStudyCount);}else{$abroadwhereToStudyCountArr=array();}?>
		<?php $i=1;?>
		<?php $arrFunctionAbroad = array('addCourseIdForFirstSection','addCourseIdForSecondSection','addCourseIdForThirdSection','addCourseIdForForthSection','addCourseIdForFifthSection');?>
		<?php $arrSectionAbroad = array('','First','Second','Third','Forth','Fifth');?>
		<?php foreach($abroadwhereToStudyCountArr as $key=>$value):?>
		<?php ?>
		<li id="abroadSection_<?php echo $value;?>">
                    	<div class="add-course-block">
                            <label><span>Heading:</span><br />
                            <span class="label-level-2">Prestigious Institutes:</span></label>
                            <div class="career-fields add-course-box">
                                <input minlength="1" maxlength="250" caption="heading" validate="validateStr" type="text" class="universal-txt-field" style="width:345px" id="abroadHeading_<?php echo $value;?>" value="<?php echo htmlspecialchars(${'abroadHeading_'.$value});?>"/>&nbsp;<?php if($i>1){?><a href="javascript:void(0)" onClick="careerObj.removeSection('abroad','<?php echo $value;?>','<?php echo $arrSectionAbroad[$value];?>');">Remove</a><?php } ?>&nbsp;<?php if($i==1 && base64_encode(${'abroadHeading_'.$value})!=''){?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearText('<?php echo $careerId;?>','abroadHeading_<?php echo $value;?>')">Clear</a><?php } ?>
				<div style="display:none"><div class="errorMsg"  id="abroadHeading_<?php echo $value;?>_error"></div></div>
                                <div class="clearFix spacer15"></div>
				<?php $j=1;?>
				<?php if(${'abroadwhereToStudyCourseIdCountFor'.$arrSectionAbroad[$value].'Section'}!='') {${'abroadwhereToStudyCourseIdCountFor'.$arrSectionAbroad[$value].'SectionArr'} = explode(',',${'abroadwhereToStudyCourseIdCountFor'.$arrSectionAbroad[$value].'Section'});}else{${'abroadwhereToStudyCourseIdCountFor'.$arrSectionAbroad[$value].'SectionArr'}=array();}?>
				<?php foreach(${'abroadwhereToStudyCourseIdCountFor'.$arrSectionAbroad[$value].'SectionArr'} as $k=>$v):?>
				<div class="flLt" id="abroadCourseText_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>_Div">
<input type="text" class="universal-txt-field" style="width:150px; color:#000; font-size:12px" id="abroadCourseText_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>"  value="<?php if(base64_encode(${'abroadCourseText_'.$arrSectionAbroad[$value].'_'.$v})!=''){ echo htmlspecialchars(${'abroadCourseText_'.$arrSectionAbroad[$value].'_'.$v});}else{ echo 'Enter Text';}?>" blurmethod="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" default="Enter Text" caption="Enter Text" validate="validateStr" minlength="1" maxlength="100"/>
				<div style="display:none"><div class="errorMsg"  id="abroadCourseText_<?php echo $arrSectionAbroad[$value];?>_<?php echo $v;?>_error"></div></div></div>
				<span id="abroadOR_<?php echo $arrSectionIndia[$value];?>_<?php echo $v;?>" class="or-txt">OR</span>
                                <div class="flLt" id="abroadCourseId_<?php echo $arrSectionAbroad[$value];?>_<?php echo $v;?>_Div">
<input type="text" class="universal-txt-field" style="width:150px; color:#000; font-size:12px" id="abroadCourseId_<?php echo $arrSectionAbroad[$value];?>_<?php echo $v;?>" value="<?php if(${'abroadCourseId_'.$arrSectionAbroad[$value].'_'.$v}!=''){ echo ${'abroadCourseId_'.$arrSectionAbroad[$value].'_'.$v};}else{ echo 'Enter Course Id';}?>" blurmethod="checkTextElementOnTransition(this,'blur');careerObj.validationCourseIdInWhereToStudySection(this.value,'abroadCourseId_<?php echo $arrSectionAbroad[$value];?>_<?php echo $v;?>')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Course Id"/>&nbsp;<?php if($j<3 && ( ${'abroadCourseId_'.$arrSectionAbroad[$value].'_'.$v}!='' || ${'abroadCourseText_'.$arrSectionAbroad[$value].'_'.$v}!='')){?>&nbsp;<a href="javascript:void(0);" onclick="careerObj.clearTextForPrestigiousInstitute('<?php echo $careerId;?>','abroadCourseId_<?php echo $arrSectionAbroad[$value];?>_<?php echo $v;?>','abroadCourseText_<?php echo $arrSectionAbroad[$value];?>_<?php echo $v;?>')">Clear</a><?php } ?>
				<?php if($j>2){?><a href="javascript:void(0)" onClick="careerObj.removeCourseIdForSection('abroad','<?php echo $value;?>','<?php echo ${'abroadCourseId_'.$arrSectionAbroad[$value].'_'.$v}?>','<?php echo $v;?>','<?php echo $arrSectionAbroad[$value]?>');" id="remove_abroad_<?php echo $arrSectionAbroad[$value];?>_<?php echo $v;?>">Remove</a><?php } ?>
				<div style="display:none"><div class="errorMsg"  id="abroadCourseId_<?php echo $arrSectionAbroad[$value];?>_<?php echo $v;?>_error"></div></div></div>

                                <div class="clearFix spacer10" id="spacer_abroad_<?php echo $arrSectionAbroad[$value];?>_<?php echo $v;?>"></div>
				
				<?php $j++;?>   
				
                                <?php endforeach;?>
				<script>careerObj.addSectionCourseIdActualCount['abroadwhereToStudy<?php echo $arrSectionAbroad[$value];?>Section'] = '<?php echo $v;?>';</script>  
                                <a href="javascript:void(0);" onClick="careerObj.addCourseIdForSection('abroad','<?php echo $value;?>','<?php echo $arrSectionAbroad[$value];?>');">+ Add More</a>
                            </div>
                        </div>
                    </li>
			<script>
				careerObj.addSectionActualCount['abroadwhereToStudy']= '<?php echo $value;?>';
				//careerObj.addSectionUpdatedCount['abroadwhereToStudy'] = '<?php echo $i;?>';
			</script>
		<?php $i++;?>
		<?php endforeach;?>
                    <li id="abroadAddMore" <?php if(count($abroadwhereToStudyCountArr)>4){?> style="display:none;"<?php } ?>><label>&nbsp;</label><div class="career-fields"><a href="javascript:void(0);" onClick="careerObj.addSection('abroad');">+ Add Section</a></div></li>
                </ul>
                <div class="clearFix"></div>
		<div id="whereToStudyMainId">
		<?php //for($i=0;$i<$customFieldForwhereToStudyCount;$i++):?>
		<div id="whereToStudyParentId"></div>
		<?php if($whereToStudyCount!='') {$whereToStudyCountArr = explode(',',$whereToStudyCount);}else{$whereToStudyCountArr=array();}?>
		<?php $i=1;?>
		<?php foreach($whereToStudyCountArr as $key=>$value):?>
                <div class="steps-main-block" style="padding-left:130px" id="whereToStudyCustomField_<?php echo $value;?>">
                    <div class="steps-block" style="width:375px">
                    	<p class="custom-title">- Create Custom Field</p>
                        <ul>
                            <li>
                                <div class="career-fields">
					<input minlength="1" maxlength="250" caption="title" validate="validateStr" type="text" class="universal-txt-field" style="width:82%; font-size:12px;color:#000;" id="wikkicontent_title_whereToStudy_<?php echo $i;?>" value="<?php if(base64_encode(${'wikkicontent_title_whereToStudy_'.$value})!=''){ echo htmlspecialchars(${'wikkicontent_title_whereToStudy_'.$value});}else{ echo 'Enter Title';}?>" blurmethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Title" /> &nbsp;<a href="javascript:void(0)" onClick="careerObj.removeCustomFields('whereToStudy','<?php echo $value;?>','<?php echo base64_encode(${'wikkicontent_title_whereToStudy_'.$value});?>','<?php echo base64_encode(${'wikkicontent_detail_whereToStudy_'.$value});?>');" >Remove</a>
				<div style="display:none"><div class="errorMsg"  id="wikkicontent_title_whereToStudy_<?php echo $i;?>_error"></div></div>
                                </div>
                            </li>
                            
                            <li>
                                <div class="career-fields">
                                    <textarea minlength="1" maxlength="10000"  class='mceEditor' caption="Description" id="wikkicontent_detail_whereToStudy_<?php echo $i;?>" style="width:370px;height:100px;" ><?php echo ${'wikkicontent_detail_whereToStudy_'.$value};?></textarea>
				<div><div id="wikkicontent_detail_whereToStudy_<?php echo $i;?>_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                                </div>
                            </li>
                           
                        </ul>	
                        <div class="clearFix"></div>
                </div>
	 </div>
		<script>
			careerObj.addCustomFieldsActualCount['whereToStudy']= '<?php echo $value;?>';
		</script>
		<?php $i++;?>
		<?php endforeach;?>
		 <div <?php if(count($whereToStudyCountArr)==5){?>style="display:none"<?php } ?>class="career-fields" style="padding-left:130px;" id="whereToStudyAddMore" >
                 	<div ><a href="javascript:void(0);" onclick="careerObj.addCustomFields('whereToStudy')">+ Add more</a></div>
                 </div>
                 </div>
	</div>   
            <div class="btn-cont">
                <div id="successMessage" class="errorMsg">&nbsp;</div>
		<div id="correct_above_error" class="errorMsg">&nbsp;</div>
            	<input type="submit" value="Save" class="orange-button" id="submitButton"/>&nbsp;&nbsp;
		<?php //if($totalRows>0):?>
                <!--<input type="button" value="Publish" class="orange-button" onClick="careerObj.publishCareerData('<?php //echo $careerId;?>');"/>&nbsp;&nbsp;-->
		<?php //endif;?>
                <strong style="font-size:16px; font-family:Arial, Helvetica, sans-serif"><a href="#">Cancel</a></strong>
            </div>
</form>
            <div class="clearFix spacer5"></div>

<script>
function stripHtml(s) {
	        return s.replace(/<\S[^><]*>/g, '');
}
	
	function isProfaneTinymce(str) {
	        var profaneWordsBag = eval(base64_decode('WyJzdWNrIiwiZnVjayIsImRpY2siLCJwZW5pcyIsImN1bnQiLCJwdXNzeSIsImhvcm55Iiwib3JnYXNtIiwidmFnaW5hIiwiYmFiZSIsImJpdGNoIiwic2x1dCIsIndob3JlIiwid2hvcmlzaCIsInNsdXR0aXNoIiwibmFrZWQiLCJpbnRlcmNvdXJzZSIsInByb3N0aXR1dGUiLCJzZXgiLCJzZXh3b3JrZXIiLCJzZXgtd29ya2VyIiwiYnJlYXN0IiwiYnJlYXN0cyIsImJvb2IiLCJib29icyIsImJ1dHQiLCJoaXAiLCJoaXBzIiwibmlwcGxlIiwibmlwcGxlcyIsImVyb3RpYyIsImVyb3Rpc20iLCJlcm90aWNpc20iLCJsdW5kIiwiY2hvb3QiLCJjaHV0IiwibG9yYSIsImxvZGEiLCJyYW5kIiwicmFuZGkiLCJ0aGFyYWsiLCJ0aGFyYWtpIiwidGhhcmtpIiwiY2hvZCIsImNob2RuYSIsImNodXRpeWEiLCJjaG9vdGl5YSIsImdhYW5kIiwiZ2FuZCIsImdhbmR1IiwiZ2FhbmR1IiwiaGFyYWFtaSIsImhhcmFtaSIsImNodWRhaSIsImNodWRuYSIsImNodWR0aSIsImJhZGFuIiwiY2hvb2NoaSIsInN0YW4iLCJuYW5naSIsIm5hbmdhIiwibmFuZ2UiLCJwaHVkZGkiLCJmdWRkaSIsImxpZmVrbm90cyIsIjA5ODEwMTEyOTU0IiwiYWJpZGphbiIsInNpZXJyYS1MZW9uZSIsInNlbmVnYWwiLCJzaWVycmEgbGVvbmUiLCJsdWNreSBtYW4iLCJzaXJhIiwibWFkaGFyY2hvZCIsInRoYWJvIiwiZnVja2VkIiwiZnVja2luZyIsInB1YmxpYyBzaXRlIiwiRGFrdSIsInByaXZhdGUgbWFpbCIsInByaXZhdGUgbWFpbGJveCIsInNleHkiLCJqb2JzIHZhY2FuY2llcyIsIm9tbmkgY2l0eSIsImJhc3R1cmQiLCJqZWhhZCIsInRlbmRlcm5lc3MgY2FyZSJd'));
	        var words = str.split(" ");
	        for(var wordsCount = 0; wordsCount < words.length; wordsCount++) {
	                for(var profaneWordsCount = 0; profaneWordsCount < profaneWordsBag.length; profaneWordsCount++) {
	                        if(words[wordsCount] ==  profaneWordsBag[profaneWordsCount] ) {
	                                return profaneWordsBag[profaneWordsCount];
	                        }
	                }
	        }
	        return false;
	}
window.onload = function() {initTMCEEditor();addOnBlurValidate(document.getElementById('careerForm'));};
function myCustomInitInstance(ed) {
    if (tinymce.isIE || !tinymce.isGecko ) {
        tinymce.dom.Event.add(ed.getWin(), 'focus', function(e) {
        try {
                if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                        tinyMCE.activeEditor.setContent('');
                        }
            } catch (ex) {
                // do it later
            }
        });
        tinymce.dom.Event.add(ed.getWin(), 'blur', function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                    tinyMCE.activeEditor.setContent('');
                    }
        });
    } else {
        tinymce.dom.Event.add(ed.getDoc(), 'focus', function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                    tinyMCE.activeEditor.setContent('');
                    }
        });
        tinymce.dom.Event.add(ed.getDoc(), 'blur', function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                    tinyMCE.activeEditor.setContent('');
                    }
        });
    }
}

function initTMCEEditor(){
tinyMCE.init({ 
	mode : "textareas",
	theme : "simple",
//    plugins : "jbimages,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",
    plugins : "jbimages,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",
    //theme_advanced_buttons1 : "bold,italic,underline,|,search,replace,|,bullist,numlist,|,undo,redo,|,link,unlink,image|,preview",
    //theme_advanced_buttons2 : "jbimages,tablecontrols,|,sub,sup,|,charmap",
    //theme_advanced_toolbar_location : "top",
	force_p_newlines : false,init_instance_callback: "myCustomInitInstance", force_br_newlines : true,forced_root_block : '',editor_selector : "mceEditor", editor_deselector : "mceNoEditor",  setup : function(ed) {
		ed.onKeyUp.add(function(ed, e) { 
            var text_limit = document.getElementById(tinyMCE.activeEditor.id).getAttribute('maxLength');
            var strip = (tinyMCE.activeEditor.getContent()).replace(/(<([^>]+)>)/ig,"");
	    if(document.all && (strip == "&nbsp;" || strip == "<p>&nbsp;</p>")) {
                strip = "";
            } 
            /*
            var text = strip.split(' ').length + " Words, " +  strip.length + " Characters. You have " +(text_limit-strip.length)+" Chracter remaining.";
            */
            var text = "<b>"+strip.length + "</b> out of <b>"+ text_limit + "</b> characters.";
            if (text_limit != null) {
                if (strip.length > text_limit) {
		     document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'inline';
                    document.getElementById(tinyMCE.activeEditor.id +'_error').style.display = 'inline';
                    document.getElementById(tinyMCE.activeEditor.id+'_error').innerHTML = "You have used <b>"+ strip.length + "</b> Characters. Please use a maximum of "+ text_limit +" characters.";
                    tinyMCE.execCommand('mceFocus', false, tinyMCE.activeEditor.id);
                    return false;
                } else {
                    document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'none';
                    document.getElementById(tinyMCE.activeEditor.id+'_error').innerHTML = '';
                }
            }
            var textBoxContent = trim(tinyMCE.activeEditor.getContent());
            textBoxContent = textBoxContent.replace(/[(\n)\r\t\"\']/g,' ');
            textBoxContent = textBoxContent.replace(/[^\x20-\x7E]/g,'');//alert(textBoxContent+"===="+tinyMCE.activeEditor.id);
	    document.getElementById(tinyMCE.activeEditor.id).value = textBoxContent;

            /*var profaneResponseWikki = isProfaneTinymce(stripHtml(textBoxContent));
            if(profaneResponseWikki !== false) {
                document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'inline';
                document.getElementById(tinyMCE.activeEditor.id +'_error').innerHTML = 'Please do not use objected words ('+ profaneResponseWikki +') for the Description';
                return false;
            }*/
		});
		}});
}
function setValueForSection(id){
	var state = $j('#'+id).is(':checked');
	$j('#'+id+'Status').val(state);
}
</script>
