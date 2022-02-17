<?php
		$breadcrumbText = '';
		if($formName == ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING){
				$formAction = ENT_SA_CMS_CONSULTANT_PATH.'saveConsultantUniversityMappingFormData/'.$formName;
				$breadcrumbText = "Add New ";
		}
		else{
				$formAction = ENT_SA_CMS_CONSULTANT_PATH.'updateConsultantUniversityMappingFormData/'.$formName;
				$breadcrumbText = "Edit ";
		}
?>
<script>
		var formName = '<?=$formName?>';
		var cloneVar;
		var cloneCount = 0;
		var universityCount = 0;
		<?php if (empty($consultantUniversities)) { ?>
		var consultantUniversities =  [];
		<?php }else{ ?>
		var consultantUniversities = JSON.parse('<?=$consultantUniversities?>');
		<?php } ?>
</script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">
		<div class="abroad-breadcrumb">
				<a class="abroad-breadcrumb-link" href="<?=SHIKSHA_STUDYABROAD_HOME?>/consultantPosting/ConsultantPosting/viewConsultantUniversityMapping">Map Consultants &amp; Universities</a>
				<span>›</span>
				<?=$breadcrumbText?>Mapping
		</div>
		<div class="abroad-cms-head">
				<h1 class="abroad-title"><?=$breadcrumbText?> University &amp; Consultant Mapping</h1>
				<div class="last-uploaded-detail">
						<?php if ($formName == ENT_SA_FORM_EDIT_CONSULTANT_UNIVERSITY_MAPPING) { ?>
								<p><span>Last modified: </span><?=$lastModifierData['lastModifiedAt']?> by <?=htmlentities($lastModifierData['lastModifiedBy'])?><br>
						<?php } ?>		
						*Mandatory</p>
				</div>
		</div>
		<div class="cms-form-wrapper clear-width">
				<div class="clear-width">
						<h3 style="cursor:pointer;" class="section-title">
								<i class="abroad-cms-sprite minus-icon"></i>
								<?php if ($formName == ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING) { ?>
										Map a University<?php if(!empty($consultantId)){ ?> to <?=htmlentities($consultantName)?> <?php }else{ ?> to Consultant <?php } ?>
								<?php }else if ($formName == ENT_SA_FORM_EDIT_CONSULTANT_UNIVERSITY_MAPPING) { ?>
										Edit University Mapping For <?=htmlentities($consultantName)?>
								<?php } ?>
						</h3>
						<div style="margin-bottom:0;" class="cms-form-wrap">
								<form id="form_<?=$formName?>" name="<?=$formName?>" action="<?=$formAction?>" method="POST" enctype="multipart/form-data">
										<ul id="formSubsectionCountList">
												<li>
														<label>Consultant Name* : </label>
														<div class="cms-fields">
																<?php if(empty($consultantId)){ ?>
																		<select name="consultantId" id="consultantId_<?=$formName?>" caption="Consultant Name" tooltip="cons_name" class="universal-select cms-field" onchange="showErrorMessage(this,'<?=$formName?>');getConsultantUniversities(this.value)" onblur="showErrorMessage(this,'<?=$formName?>');" validationType="select" required="true">                         
																				<option value = "">Select Consultant</option>
																				<?php foreach($consultantList as $consultantId => $consultantName) { ?>
																								<option value="<?=$consultantId?>"><?=htmlentities($consultantName)?></option>
																				<?php } ?>
																		</select>
																<?php }else{ ?>
																		<input type="hidden" name="consultantId" value="<?=$consultantId?>"/>
																		<select id="consultantId_<?=$formName?>" caption="Consultant Name" class="universal-select cms-field" tooltip="cons_name" validationType="select" required="true" disabled="true">
																				<option value="<?=$consultantId?>"><?=htmlentities($consultantName)?></option>
																		</select>
																<?php } ?>
																<div id="consultantId_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
														</div>
												</li>
												<?php if(!empty($mappingData)){
														$this->load->view("addEditConsultantUniversityMappingFormPrefilled");
												}
												?>
												
										</ul>
								</form>
								<ul>
										<?php if($formName == ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING){ ?>
										<li>
												<a style="width:200px;" class="add-more-link" href="javascript:void(0);" onclick="addAnotherUniversity();">[+] Add another university</a>
										</li>
										<?php }?>
										<li>
												<div id="form_<?=$formName?>_error" style="margin:0 0 0 253px;display: none;" class="errorMsg"></div>
										</li>
								</ul>
						</div>
				</div>
		</div>
		<div class="button-wrap">
				<a class="orange-btn" href="javascript:void(0);" onclick="submitConsultantUniversityMappingForm();">Save &amp; Publish</a>
				<a class="cancel-btn" href="javascript:void(0);" onclick="confirmRedirection();">Cancel</a>
		</div>
		<?php if($consultantUniversityTableData){ ?>
		<div id="bottomTable" class="mapped-univ-table">
				<h4 style="margin-left:0; margin-top:10px;" class="ranking-head">
						<?php if($consultantUniversityTableDataCount > 1) { ?>
						All <?=$consultantUniversityTableDataCount?> Universities Mapped to <?=htmlentities($consultantName)?>
						<?php }
						if($consultantUniversityTableDataCount == 1) { ?>
						1 University mapped to <?=htmlentities($consultantName)?>
						<?php } ?>
				</h4>
				<table cellspacing="0" cellpadding="0" border="1" style="margin:15px 0 0;" class="cms-table-structure">
						<tbody>
								<tr>
										<th width="5%" align="center">S.No.</th>
										<th width="50%">
												<span class="flLt">Mapped University Name</span>
										</th>
										<th width="25%">
												<span class="flLt">Consultant Proof</span>
										</th>
										<th width="20%">
												<span class="flLt">Valid Till</span>
										</th>
								</tr>
								<?php
								$counter = 1;
								foreach($consultantUniversityTableData as $mapping){
								?>
										<tr>
												<td align="center"><?=$counter?>.</td>
												<td>
														<p><?=htmlentities($mapping['universityName'])?></p>
														<div class="edit-del-sec">
																<a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_EDIT_CONSULTANT_UNIVERSITY_MAPPING?>/?consutId=<?=$consultantId?>&univId=<?=$mapping['id']?>">Edit</a>&nbsp;&nbsp;
																<?php if($usergroup == 'saAdmin' || $usergroup == 'saCMSLead'){ ?><a href="javascript:void(0);" onclick="conformMappingDeletion(<?=$consultantId?>,<?=$mapping['id']?>);">Delete</a> <?php } ?>
														</div>
												</td>
												<td>
														<p><?=$mapping['proofType']?></p>
												</td>
								
												<td>
														<p><?=$mapping['validTill']?></p>
												</td>
										</tr>
								<?php $counter++;
								} ?>
						</tbody>
				</table>
		</div>
		<?php } ?>
</div>

<div style="display:none;">
		<li id="formSectionHolder" class="clonable">
				<div class="add-more-sec clear-width" style="display:none;">
						<input type="hidden" value="%id%" name="universityIndexes[]"/>
						<ul>
								
								<li>
										<label>University country* : </label>
										<div class="cms-fields">
												<select name="countryId[]" id="%id%_country_<?=$formName?>" caption="Country" tooltip="univ_country" class="universal-select cms-field" onchange="showErrorMessage(this,'<?=$formName?>');populateAbroadUniversitiesForConsultant('<?=$formName?>','%id%');" onblur="showErrorMessage(this,'<?=$formName?>');" validationType="select" required="true">
														<option value="">Select a Country</option>
														<?php foreach($countryList as $country) {?>
																<option value="<?=$country->getId()?>"><?=$country->getName()?></option>
														<?php } ?>
												</select>
												<div id="%id%_country_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
										</div>
								</li>
								<li>
										<label>University Name* : </label>
										<div class="cms-fields">
												<select name="universityId[]" id="%id%_university_<?=$formName?>" caption="University" tooltip="univ_name" class="universal-select cms-field" onchange="showErrorMessage(this,'<?=$formName?>');populateAbroadCoursesForUniversity(this.value,'%id%');" onblur="showErrorMessage(this,'<?=$formName?>');" required="true" validationType="select">
														<option value="">Select a University</option>
												</select>
												<div id="%id%_university_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
										</div>
								</li>
								<li>
										<label>Select course you want to exclude: </label>
										<div class="cms-fields">
												<div style="width:290px;" class="assign-city-box">
														<div class="overview">
																<div class="viewport">
																		<ul id="%id%_disabledCoursesList" style="width:280px;" class="assign-city-list">
																				
																		</ul>
																</div>
														</div> 
												</div>
												<p>Excluded Courses: <span id="%id%_excludedCourseCounter">0</span></p>
												<div id="%id%_disabledCoursesList_error" style="display: none;" class="errorMsg"></div>
										</div>
								</li>
								<li>
										<label>Description of excluded courses : </label>
										<div class="cms-fields">
												<textarea id="%id%_disabledCourseComments" name="disabledCourseComments[]" class="cms-textarea" maxlength="5000"></textarea>
												<div id = "%id%_disabledCourseComments_error" style="display:none;" class="errorMsg"></div>
										</div>
								</li>
								<li>
										<label>Official representatives* : </label>
										<div style="margin-top:6px;" class="cms-fields">
												<input type="radio" value="yes" tooltip="univ_repre" name="%id%_representative_<?=$formName?>" onclick="showProofSection('%id%');" required="true" validationType="radio"> Yes
												<input type="radio" value="no" tooltip="univ_repre" name="%id%_representative_<?=$formName?>" onclick="hideProofSection('%id%');" required="true" validationType="radio" checked="checked"> No
										</div>
								   </li>
								<span id="%id%_officialRepresentativeProofSection" style="display:none;">
										<li>
												<label>Valid From* : </label>
												<div class="cms-fields">
														<div class="clear-width" style="margin-bottom:0">
																<input id="%id%_startDate_<?=$formName?>" type="text" tooltip="valid_from" readonly="" class="universal-txt-field cms-text-field flLt" style="width:150px !important;margin-right:5px;" value="DD/MM/YYYY" onchange="validateStartDate('%id%');" onblur="validateStartDate('%id%');" name="startDate[]">
																<i class="abroad-cms-sprite calendar-icon" onclick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('%id%_startDate_<?=$formName?>'),'%id%_startDate_img','DD/MM/YYYY'); return false;" style="top:3px;" id="%id%_startDate_img" name="%id%_startDate_img" ></i>
														</div>
														<div id="%id%_startDate_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
												</div>
										</li>
										<li>
												<label>Valid Till* : </label>
												<div class="cms-fields">
														<div class="clear-width" style="margin-bottom:0">
																<input id="%id%_endDate_<?=$formName?>" type="text" tooltip="valid_till" readonly="" class="universal-txt-field cms-text-field flLt" style="width:150px !important;margin-right:5px;" value="DD/MM/YYYY" onchange="validateEndDate('%id%');" onblur="validateEndDate('%id%');" name="endDate[]">
																<i class="abroad-cms-sprite calendar-icon" onclick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('%id%_endDate_<?=$formName?>'),'%id%_endDate_img','DD/MM/YYYY'); return false;" style="top:3px;" id="%id%_endDate_img" name="%id%_endDate_img" ></i>
														</div>
														<div id="%id%_endDate_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
												</div>
										</li>
										<li>
												<label>Type of proof* : </label>
												<div class="cms-fields">
														<select id="%id%_proofType_<?=$formName?>" name="proofType[]" caption="type of proof" tooltip="univ_proof_type" validationType="select" onchange="showErrorMessage(this,'<?=$formName?>');showProofType('%id%',this.value);" onblur="showErrorMessage(this,'select');showProofType('%id%',this.value);" class="universal-select cms-field">
																<option value="">Select Type Of Proof</option>
																<option value='name'>Name on University site</option>
																<option value='email'>Email from University admissions office</option>
														</select>
														<div id="%id%_proofType_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
												</div>
										</li>
										<span id="%id%_nameProofSection" style="display:none">
												<li>
														<label>Webpage link on university site* : </label>
														<div class="cms-fields">
																<input id="%id%_proofWebsiteLink_<?=$formName?>" name="proofWebsiteLink[]" caption="Website Link" tooltip="link_on_univ_site" maxlength="500" validationType="link" onchange="showErrorMessage(this,'<?=$formName?>');" onblur="showErrorMessage(this,'<?=$formName?>');" type="text" class="universal-txt-field cms-text-field">
																<div id="%id%_proofWebsiteLink_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
														</div>
												</li>
										</span>
										<span id="%id%_emailProofSection" style="display:none">
												<li>
														<label>University Person name* : </label>
														<div class="cms-fields">
																<input id="%id%_proofPersonName_<?=$formName?>" name="proofPersonName[]" caption="University Person Name" tooltip="univ_personName" validationType="name" maxlength="100" onchange="showErrorMessage(this,'<?=$formName?>');" onblur="showErrorMessage(this,'<?=$formName?>');" type="text" class="universal-txt-field cms-text-field" style="width:150px !important;margin-right:5px;">
																<div id="%id%_proofPersonName_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
														</div>
												</li>
												<li>
														<label>University Person Email and Designation* : </label>
														<div class="cms-fields">
																<input id="%id%_proofPersonDetails_<?=$formName?>" name="proofPersonDetails[]" caption="University Person Email and Designation" tooltip="univ_personEmailDes" validationType="str" maxlength="200" onchange="showErrorMessage(this,'<?=$formName?>');" onblur="showErrorMessage(this,'<?=$formName?>');" type="text" class="universal-txt-field cms-text-field" style="width:150px !important;margin-right:5px;">
																<div id="%id%_proofPersonDetails_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
														</div>
												</li>
												<li>
														<label>Email from Sales* : </label>
														<div class="cms-fields" style="width:500px; overflow:hidden;">
																<input id="%id%_proofEmailDocument_<?=$formName?>" name="proofEmailDocument[]" caption="Email From Sales" tooltip="emailApproval" validationType="emailFile" onchange="showErrorMessage(this,'<?=$formName?>');" onblur="showErrorMessage(this,'<?=$formName?>');" type="file">
																<div id="%id%_proofEmailDocument_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
														</div>
												</li>
										</span>
								</span>
								<li>
										<label>Name of Sales Person* : </label>
										<div class="cms-fields">
												<select id="%id%_salesPerson_<?=$formName?>" name="salesPerson[]" caption="Name of Sales Person" tooltip="salesPerson" validationType="select" required="true" onchange="showErrorMessage(this,'<?=$formName?>');" onblur="showErrorMessage(this,'<?=$formName?>');" class="universal-select cms-field">
														<option value="">Select Sales Person</option>
														<?php foreach($consultantSalesPersons as $consultantSalesPersonId => $consultantSalesPersonData){?>
																<option value="<?=$consultantSalesPersonId?>"><?=$consultantSalesPersonData['name']?></option>
														<?php } ?>
												</select>
												<div id="%id%_salesPerson_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
										   </div>
								</li>
						</ul>
						<a style="margin-right:5px;" href="javascript:void(0);" onclick="removeUniversity(this);" class="remove-link flRt"><i class="abroad-cms-sprite remove-icon"></i>Remove University</a>
				</div>
		</li>
</div>

<script>
		function showProofSection(elemId){
				$j("#"+elemId+"_officialRepresentativeProofSection").css("display","block");
				$j("#"+elemId+"_officialRepresentativeProofSection").find("#"+elemId+"_startDate_<?=$formName?>").attr("required","true");
				$j("#"+elemId+"_officialRepresentativeProofSection").find("#"+elemId+"_endDate_<?=$formName?>").attr("required","true");
				$j("#"+elemId+"_officialRepresentativeProofSection").find("#"+elemId+"_proofType_<?=$formName?>").attr("required","true");
		}
		
		function hideProofSection(elemId){
				$j("#"+elemId+"_officialRepresentativeProofSection").css("display","none");
				$j("#"+elemId+"_officialRepresentativeProofSection").find("#"+elemId+"_startDate_<?=$formName?>").removeAttr("required");
				$j("#"+elemId+"_officialRepresentativeProofSection").find("#"+elemId+"_endDate_<?=$formName?>").removeAttr("required");
				$j("#"+elemId+"_officialRepresentativeProofSection").find("#"+elemId+"_proofType_<?=$formName?>").removeAttr("required");
				$j("#"+elemId+"_proofType_<?=$formName?>").val("").trigger("change");
		}
		
		function showProofType(elemId,val){
				if (val == 'name') {
						$j("#"+elemId+"_nameProofSection").css("display","block");
						$j("#"+elemId+"_nameProofSection").find(".cms-fields").children(":not(div)").each(function(){ $j(this).attr("required","true")});
						$j("#"+elemId+"_emailProofSection").css("display","none");
						$j("#"+elemId+"_emailProofSection").find(".cms-fields").children(":not(div)").each(function(){ $j(this).removeAttr("required")});
				}else if(val == 'email'){
						$j("#"+elemId+"_nameProofSection").css("display","none");
						$j("#"+elemId+"_nameProofSection").find(".cms-fields").children(":not(div)").each(function(){ $j(this).removeAttr("required")});
						$j("#"+elemId+"_emailProofSection").css("display","block");
						$j("#"+elemId+"_emailProofSection").find(".cms-fields").children(":not(div)").each(function(){ $j(this).attr("required","true")});
				}else{
						$j("#"+elemId+"_nameProofSection").css("display","none");
						$j("#"+elemId+"_nameProofSection").find(".cms-fields").children(":not(div)").each(function(){ $j(this).removeAttr("required")});
						$j("#"+elemId+"_emailProofSection").css("display","none");
						$j("#"+elemId+"_emailProofSection").find(".cms-fields").children(":not(div)").each(function(){ $j(this).removeAttr("required")});
				}
		}
		
		function addAnotherUniversity(){
				if (universityCount < 5) {
						cloneCount++;
						var temp = cloneVar.clone();
						$j(temp).find(".add-more-sec :first").removeClass("add-more-sec").addClass("add-more-sec2");
						temp = temp.html().replace(new RegExp("%id%",'g'),cloneCount);
						$j("#formSubsectionCountList").append('<li>'+temp+'</li>');
						universityCount++;
				}
				if (universityCount == 5) {
						$j(".add-more-link").hide();
				}
		}
		
		function removeUniversity(elem){
				$j(elem).parent().parent().fadeOut(600,
						function(){
								$j(elem).parent().parent().remove();
								universityCount--;
								$j(".add-more-link").show();
						}
				);
		}
		
		function populateAbroadUniversitiesForConsultant (formName,elemId){
				var selectedCountryId = $j('#'+elemId+'_country_' +formName).val();
				if(typeof selectedCountryId != "undefined" && selectedCountryId != ""){
						$j('#'+elemId+'_university_' +formName).find('option').remove().end().append('<option selected value="">Select University</option>');
						var params = {};
						params['countryId'] = selectedCountryId;
						params['freeOnly'] = 1;
						params['removeRmcCounsellorUniversity'] = 1;
						var customParams = {};
						customParams['formName']  = formName;
						customParams['elemId'] = elemId;
						studyAbroadCMSAjaxCall('getUniversitiesForCountry', params, 'fillUniversityList', customParams);
				} else {
						var universityDomId = '#university_' +  formName;
						$j(universityDomId).find('option').remove().end().append('<option selected value="">Select University</option>');
				}
		}
		function populateAbroadCoursesForUniversity(universityId,elemId){
				$j.ajax({
						url:"/consultantPosting/ConsultantPosting/getExcludedCoursesForUniversity",
						type: "POST",
						data : {'data':JSON.stringify({"universityId":universityId,'consultantId':$j('#consultantId_'+formName).val()})},
						async:false,
						success : function(result){
								var courses = JSON.parse(result);
								var courseCount = courses.length;
								var htmlToAppend = '';
								for(var i = 0; i< courseCount; i++){
										var course = courses[i];
										var checked = "";
										if (course['disabled'] == "true") {
												checked = "checked='checked'";
										}
										var temp = '<li style="width:98% !important;">';
										temp +='<input onclick="recalculateExcludedCourses(this,\''+elemId+'\');" '+checked+' name="disabledCourses['+elemId+'][]" value="'+course['courseId']+'" type="checkbox">'+course['courseName'];
										temp+='</li>';
										htmlToAppend+=temp;
								}
								$j("#"+elemId+"_disabledCoursesList").html(htmlToAppend);
						}
				});
		}
		function fillUniversityList(response, callBackCustomParams){
				if(typeof response != "undefined"){
						var dictKeys = ShikshaHelper.getDictionaryKeys(response);
						var existingUniversities = consultantUniversities;
						$j("[name='universityId[]']").each(function(){existingUniversities.push($j(this).val());});
						if(dictKeys.length > 0){
								for(index in dictKeys){
										if (existingUniversities.indexOf(dictKeys[index]) == -1) {
												$j('#'+callBackCustomParams['elemId']+'_university_'+callBackCustomParams['formName']).append("<option value='"+dictKeys[index]+"'>"+response[dictKeys[index]]+"</option>");
										}
										
								}
						}
				}
		}
		
		function recalculateExcludedCourses(elem,elemId){
				var count = $j(elem).parent().parent().find("input:checked").length;
				$j("#"+elemId+"_excludedCourseCounter").html(count);
		}
		
		function validateStartDate(elemId){
				var parts = $j("#"+elemId+"_startDate_<?=$formName?>").val().split('/');
				if (parts[0] == 'DD' || parts[0] == '') {
						$j("#"+elemId+"_startDate_<?=$formName?>_error").html("Please select Valid From").show();
						return false;
				}//if this condition fails, then something must've been selected
				var flag;
				var today = new Date();
				var startDate = new Date(parseInt(parts[2]),parseInt(parts[1])-1,parseInt(parts[0]),0,0,0);
				if (startDate > today) {
						$j("#"+elemId+"_startDate_<?=$formName?>_error").html("Valid From must be lesser than today's date").show();
						return false;
				}
				$j("#"+elemId+"_startDate_<?=$formName?>_error").hide();
				return validateEndDate(elemId,true);
		}
		
		function validateEndDate(elemId,referred){
				var parts = $j("#"+elemId+"_startDate_<?=$formName?>").val().split('/');
				if (parts[0] == 'DD' || parts[0] == '') {
						$j("#"+elemId+"_endDate_<?=$formName?>_error").html("Valid Till must be greater than Valid From").show();
						return false;
				}
				var startDate = new Date(parseInt(parts[2]),parseInt(parts[1])-1,parseInt(parts[0]));
				parts = $j("#"+elemId+"_endDate_<?=$formName?>").val().split('/');
				if (parts[0] == 'DD' || parts[0] == '') {
						$j("#"+elemId+"_endDate_<?=$formName?>_error").html("Please select Valid Till").show();
						return false;
				}
				var endDate = new Date(parseInt(parts[2]),parseInt(parts[1])-1,parseInt(parts[0]));
				var today = new Date();
				if (endDate <= today) {
						$j("#"+elemId+"_endDate_<?=$formName?>_error").html("Valid Till must be greater than Today's Date").show();
						return false;
				}
				if (endDate <= startDate) {
						$j("#"+elemId+"_endDate_<?=$formName?>_error").html("Valid Till must be greater than Valid From").show();
						return false;
				}
				$j("#"+elemId+"_endDate_<?=$formName?>_error").hide();
				return true;
		}
		
		function checkCustomValidations(){
				var dateErrorFlag = true;
				var uniqueUniversityErrorFlag = true;
				var allCoursesSelectedErrorFlag = true;
				var selectedUnivIds = [];
				//Validate the dates
				$j("[name='universityIndexes[]']").each(
						function(){
								if ($j(this).attr("value") !="%id%") {
										if($j("#"+$j(this).attr("value")+"_startDate_"+formName).attr("required") == "required"){
												dateErrorFlag = dateErrorFlag && validateStartDate($j(this).attr("value"));
										}
								}
						}
				);
				//Validate that no university has all of it's courses excluded
				$j("[name='universityIndexes[]']").each(
						function(){
								if ($j(this).attr("value") !="%id%") {
										if($j("#"+$j(this).attr("value")+"_disabledCoursesList").children().length <= $j("#"+$j(this).attr("value")+"_disabledCoursesList").find("input:checked").length){
												allCoursesSelectedErrorFlag = false;
												$j("#"+$j(this).attr("value")+"_disabledCoursesList_error").html("You cannot Disable All Courses!").show();
										}
										else{
												$j("#"+$j(this).attr("value")+"_disabledCoursesList_error").html("").hide();
										}
								}
						}
				);
				
				// Clean leading and trailing whitespaces in comments.
				$j("[name='universityIndexes[]']").each(
						function(){
								if ($j(this).attr("value") !="%id%") {
										var tempComment = $j("#"+$j(this).attr("value")+"_disabledCourseComments").val();
										tempComment = tempComment.replace(/^\s+|\s+$/g,'');
										$j("#"+$j(this).attr("value")+"_disabledCourseComments").val(tempComment);
								}
						}
				);
				
				//Validate duplicacy in universities
				$j("[name='universityId[]']").each(
						function(){
								if (!isNaN(parseInt($j(this).val()))) {
										selectedUnivIds.push(parseInt($j(this).val()));
								}
								
						}
				);
				selectedUnivIds.sort(function(a,b){
						return parseInt(a)-parseInt(b);
						});
				var univLen = selectedUnivIds.length;
				for(i=0;i<univLen-1;i++){
						if (selectedUnivIds[i] == selectedUnivIds[i+1] && selectedUnivIds[i]!="") {
								uniqueUniversityErrorFlag = false;
								$j("#form_<?=$formName?>_error").html("Same University has been selected twice, please verify the data").show();
								blockPosting = false;
								return false;
						}
				}
				
				return dateErrorFlag && allCoursesSelectedErrorFlag && uniqueUniversityErrorFlag;
		}
		
		var blockPosting = false;
		function submitConsultantUniversityMappingForm(){
				if (blockPosting) {
						return;
				}
				blockPosting = true;
				var errorFlag = showErrorMessage(this, formname, true);
				var customChecks = checkCustomValidations();
				
				if (!errorFlag && customChecks) {
						$j("#form_<?=$formName?>_error").html("").hide();
						$j("#form_"+formName).submit();
				}else{
						$j("#form_<?=$formName?>_error").html("Please scroll up & correct the fields shown with error message.").show();
						blockPosting = false;
						return false;
				}
				return true;
		}
		
		function getConsultantUniversities(consultantId){
				//document.getElementById("form_"+formname).reset(); // if all goes to hell uncomment this line.
				$j.ajax({
						url: "/consultantPosting/ConsultantPosting/getConsultantMappedUniversities/"+consultantId,
						success: function(res){
								consultantUniversities=JSON.parse(res);
						}
				});
		}
		
		window.onbeforeunload = confirmExit; 
		var preventOnUnload = false;
		var saveInitiated = false;
		function confirmExit(){//alert(saveInitiated);
				if(preventOnUnload == false){
					return 'Any unsaved change will be lost.';
				}
		}
		
        if(document.all) {
            document.body.onload = initFormPosting;
        } else {
            initFormPosting();
        }
        
        function initFormPosting() {
            AIM.submit(document.getElementById('form_<?=$formName?>'), {'onStart' : startCallback, 'onComplete' : completeCallback});
        }
        
        function startCallback(){
            // perform logics if required before submit form data
            return true;
        }
        
        function completeCallback(response){
				if (response != "success") {
						alert(response);
						blockPosting = false;
				}else{
						alert("Mapping has been saved successfully.");
						preventOnUnload = true;
				        window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_UNIVERSITY_MAPPING_TABLE?>";
				}
                
        }
        
        function confirmRedirection(){   
            var choice = confirm("Are you sure you want to cancel? All data changes will be lost.");
            if (choice) {
                preventOnUnload = true;
                //window.onbeforeunload = null;
                window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_UNIVERSITY_MAPPING_TABLE?>";
            }
            else{
                preventOnUnload = true;
            }
        }        
		
		var deletionControlVar = false;
		function conformMappingDeletion(consultantId, universityId){
				if (deletionControlVar) {
						return;
				}
				deletionControlVar = true;
				var choice = confirm("Are you sure you want to delete this mapping? This action cannot be undone!");
				if (choice) {
						$j.ajax({
								url: "<?=ENT_SA_CMS_CONSULTANT_PATH?>"+"deleteConsultantUniversityMapping/"+consultantId+"/"+universityId,
								method: "GET",
								success : function (response){
										if (response == "success") {
												alert("Mapping has been Successfully Deleted!");
												preventOnUnload = true;
												//window.location['href'] == "<?=SHIKSHA_STUDYABROAD_HOME.ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING."/?consutId=".$consultantId."#bottomTable"?>"
												if(formName == '<?=ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING?>'){
														window.location.reload();
												}else{
														window.location.href = "<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING."/?consutId=".$consultantId."#bottomTable"?>";
												}
										}else{
												alert(response);
												deletionControlVar = false;
										}
								},
								error : function(){
										alert("Something went wrong, please try again later");
										preventOnUnload = true;
										window.location.reload();
								}
						});
				}else{
						deletionControlVar = false;
				}
				return true;
		}
</script>