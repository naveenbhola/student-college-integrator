<?php
		$mappingData = reset($mappingData);
?>
<li>
		<div class="add-more-sec clear-width">
				<input type="hidden" value="<?=$mappingData['id']?>" name="mappingId">
				<input type="hidden" value="<?=$mappingData['createdAt']?>" name="createdAt" />
				<input type="hidden" value="<?=$mappingData['createdBy']?>" name="createdBy" />
				<input type="hidden" value="1" name="universityIndexes[]"/>
				<ul>
						<li>
								<label>University country* : </label>
								<div class="cms-fields">
										<input type="hidden" name="countryId[]" value="<?=$mappingData['countryId']?>" />
										<select caption="Country" tooltip="univ_country" class="universal-select cms-field" disabled="true">
												<option value="1" selected="true"><?=$mappingData['countryName']?></option>
										</select>
										<div id="1_country_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
								</div>
						</li>
						<li>
								<label>University Name* : </label>
								<div class="cms-fields">
										<input type="hidden" name="universityId[]" value="<?=$mappingData['university_id']?>"/>
										<select caption="University" tooltip="univ_name" class="universal-select cms-field" disabled="true">
												<option value="1"><?=$mappingData['UniversityName']?></option>
										</select>
										<div id="1_university_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
								</div>
						</li>
						<li>
								<label>Select course you want to exclude: </label>
								<div class="cms-fields">
										<?php $disableCounter = 0; ?>
										<div style="width:290px;" class="assign-city-box">
												<div class="overview">
														<div class="viewport">
																<ul id="1_disabledCoursesList" style="width:280px;" class="assign-city-list">
																		<?php
																		foreach($disabledCourses as $course){
																				if($course['disabled'] == "true"){
																						$checkFlag = "checked='checked'";
																						$disableCounter++;
																				}
																				else{
																						$checkFlag = "";
																				}
																		?>
																				<li style="width:98% !important;">
																						<input onclick="recalculateExcludedCourses(this,'1');" <?=$checkFlag?> name="disabledCourses[1][]" value="<?=$course['courseId']?>" type="checkbox"> <?=$course['courseName']?>
																				</li>
																		<?php } ?>
																</ul>
																
														</div>
												</div> 
										</div>
										<p>Excluded Courses: <span id="1_excludedCourseCounter"><?=$disableCounter?></span></p>
										<div id="1_disabledCoursesList_error" style="display: none;" class="errorMsg"></div>
								</div>
						</li>
						<li>
								<label>Description of excluded courses : </label>
								<div class="cms-fields">
										<textarea id="1_disabledCourseComments" name="disabledCourseComments[]" class="cms-textarea" maxlength="5000"><?=htmlentities($mappingData['excludedCourseComments'])?></textarea>
										<div id = "1_disabledCourseComments_error" style="display:none;" class="errorMsg"></div>
								</div>
						</li>
						<li>
								<label>Official representatives* : </label>
								<div style="margin-top:6px;" class="cms-fields">
										<input type="radio" value="yes" tooltip="univ_repre" name="1_representative_<?=$formName?>" onclick="showProofSection('1');" required="true" validationType="radio" <?=$mappingData['isOfficialRepresentative']=='yes'?"checked='checked'":""?>> Yes
										<input type="radio" value="no" tooltip="univ_repre" name="1_representative_<?=$formName?>" onclick="hideProofSection('1');" required="true" validationType="radio" <?=$mappingData['isOfficialRepresentative']=='no'?"checked='checked'":""?>> No
								</div>
						</li>
						
						<span id="1_officialRepresentativeProofSection" style="display:<?=$mappingData['isOfficialRepresentative']=='no'?"none":"block"?>;">
								<li>
										<label>Valid From* : </label>
										<div class="cms-fields">
												<div class="clear-width" style="margin-bottom:0">
														<input id="1_startDate_<?=$formName?>" type="text" tooltip="valid_from" readonly="" class="universal-txt-field cms-text-field flLt" style="width:150px !important;margin-right:5px;" value="<?=$mappingData['representativeValidFrom']?>" onchange="validateStartDate('1');" onblur="validateStartDate('1');" name="startDate[]" <?=$mappingData['isOfficialRepresentative']=='yes'?'required="required"':""?>>
														<i class="abroad-cms-sprite calendar-icon" onclick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('1_startDate_<?=$formName?>'),'1_startDate_img','DD/MM/YYYY'); return false;" style="top:3px;" id="1_startDate_img" name="1_startDate_img" ></i>
												</div>
												<div id="1_startDate_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
										</div>
								</li>
								<li>
										<label>Valid Till* : </label>
										<div class="cms-fields">
												<div class="clear-width" style="margin-bottom:0">
														<input id="1_endDate_<?=$formName?>" type="text" tooltip="valid_till" readonly="" class="universal-txt-field cms-text-field flLt" style="width:150px !important;margin-right:5px;" value="<?=$mappingData['representativeValidTo']?>" onchange="validateEndDate('1');" onblur="validateEndDate('1');" name="endDate[]" <?=$mappingData['isOfficialRepresentative']=='yes'?'required="required"':""?>>
														<i class="abroad-cms-sprite calendar-icon" onclick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('1_endDate_<?=$formName?>'),'1_endDate_img','DD/MM/YYYY'); return false;" style="top:3px;" id="1_endDate_img" name="1_endDate_img" ></i>
												</div>
												<div id="1_endDate_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
										</div>
								</li>
								<li>
										<label>Type of proof* : </label>
										<div class="cms-fields">
												<select id="1_proofType_<?=$formName?>" name="proofType[]" caption="type of proof" tooltip="univ_proof_type" validationType="select" onchange="showErrorMessage(this,'<?=$formName?>');showProofType('1',this.value);" onblur="showErrorMessage(this,'select');showProofType('1',this.value);" class="universal-select cms-field" <?=$mappingData['isOfficialRepresentative']=='yes'?'required="required"':""?>>
														<option value="">Select Type Of Proof</option>
														<option value='name' <?=($mappingData['proofType']=='name')?'selected="true"':''?> >Name on University site</option>
														<option value='email' <?=($mappingData['proofType']=='email')?'selected="true"':''?>>Email from University admissions office</option>
												</select>
												<div id="1_proofType_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
										</div>
								</li>
								<span id="1_nameProofSection" style="display:<?=($mappingData['proofType']=='name')?'block':'none'?>">
										<li>
												<label>Webpage link on university site* : </label>
												<div class="cms-fields">
														<input id="1_proofWebsiteLink_<?=$formName?>" name="proofWebsiteLink[]" caption="Website Link" tooltip="link_on_univ_site" maxlength="500" validationType="link" <?=($mappingData['proofType']=='name')?'required="required"':''?> onchange="showErrorMessage(this,'<?=$formName?>');" onblur="showErrorMessage(this,'<?=$formName?>');" type="text" class="universal-txt-field cms-text-field" value="<?=$mappingData['proofWebsiteLink']?>">
														<div id="1_proofWebsiteLink_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
												</div>
										</li>
								</span>
								<span id="1_emailProofSection" style="display:<?=($mappingData['proofType']=='email')?'block':'none'?>">
										<li>
												<label>University Person name* : </label>
												<div class="cms-fields">
														<input id="1_proofPersonName_<?=$formName?>" name="proofPersonName[]" caption="University Person Name" tooltip="univ_personName" validationType="name" maxlength="100" onchange="showErrorMessage(this,'<?=$formName?>');" onblur="showErrorMessage(this,'<?=$formName?>');" type="text" class="universal-txt-field cms-text-field" style="width:150px !important;margin-right:5px;" <?=($mappingData['proofType']=='email')?'required="required"':''?> value="<?=htmlentities($mappingData['proofPersonName'])?>">
														<div id="1_proofPersonName_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
												</div>
										</li>
										<li>
												<label>University Person Email and Designation* : </label>
												<div class="cms-fields">
														<input id="1_proofPersonDetails_<?=$formName?>" name="proofPersonDetails[]" caption="University Person Email and Designation" tooltip="univ_personEmailDes" validationType="str" maxlength="200" onchange="showErrorMessage(this,'<?=$formName?>');" onblur="showErrorMessage(this,'<?=$formName?>');" type="text" class="universal-txt-field cms-text-field" style="width:150px !important;margin-right:5px;" <?=($mappingData['proofType']=='email')?'required="required"':''?> value="<?=htmlentities($mappingData['proofPersonDetails'])?>">
														<div id="1_proofPersonDetails_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
												</div>
										</li>
										<li>
												<label>Email from Sales* : </label>
												<?php if($mappingData['proofType']=='email'){ ?>
												<div class="cms-fields" style="font-size:small;padding-top:6px;padding-bottom:5px;">
														<input type="hidden" name="proofEmailDocument[]" value="<?=$mappingData['proofEmailDocumentUrl']?>">
														<a href="<?=$mappingData['proofEmailDocumentUrl']?>" target="_blank"><?=$mappingData['proofEmailDocumentUrl']?></a>
														| <a href="javascript:void(0);" onclick="editFileUploadShow(this);">Remove File</a>
												</div>
												<div class="cms-fields" style="width:500px; overflow:hidden;display:none" id="editFormFile">
														<input id="1_proofEmailDocument_<?=$formName?>" caption="Email From Sales" tooltip="emailApproval" onchange="showErrorMessage(this,'<?=$formName?>');" onblur="showErrorMessage(this,'<?=$formName?>');" type="file">
														<div id="1_proofEmailDocument_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
												</div>
												<?php }else{ ?>
												<div class="cms-fields" style="width:500px; overflow:hidden;">
														<input id="1_proofEmailDocument_<?=$formName?>" name="proofEmailDocument[]" caption="Email From Sales" tooltip="emailApproval" validationType="emailFile" onchange="showErrorMessage(this,'<?=$formName?>');" onblur="showErrorMessage(this,'<?=$formName?>');" type="file" <?=($mappingData['proofType']=='email')?'required="required"':''?>>
														<div id="1_proofEmailDocument_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
												</div>
												<?php } ?>
										</li>
								</span>
						</span>
						<li>
								<label>Name of Sales Person* : </label>
								<div class="cms-fields">
										<select id="1_salesPerson_<?=$formName?>" name="salesPerson[]" caption="Name of Sales Person" tooltip="salesPerson" validationType="select" required="true" onchange="showErrorMessage(this,'<?=$formName?>');" onblur="showErrorMessage(this,'<?=$formName?>');" class="universal-select cms-field">
												<option value="">Select Sales Person</option>
												<?php foreach($consultantSalesPersons as $consultantSalesPersonId => $consultantSalesPersonData){?>
														<option value="<?=$consultantSalesPersonId?>" <?=($mappingData['salesPerson'] == $consultantSalesPersonId)?'selected="true"':'' ?>><?=$consultantSalesPersonData['name']?></option>
												<?php } ?>
										</select>
										<div id="1_salesPerson_<?=$formName?>_error" style="display: none;" class="errorMsg"></div>
								   </div>
						</li>
				</ul>
		</div>
</li>
<script>
		cloneCount = 1;
		universityCount = 1;
		function editFileUploadShow(ele){
				$j(ele).parent().remove();
				$j("#editFormFile").css("display","block").children(":first").attr("name","proofEmailDocument[]").attr("required","required").attr("validationType","emailFile");
		}
		
</script>
