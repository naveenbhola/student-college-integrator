<?php
	$months = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
	$this->load->view('common/calendardiv');
?>

<input type="hidden" id="totalAgents" value="<?php echo $search_agents_all_array['totalRows']; ?>"/>

<div id="manageSADiv" class="">
    	<div style="margin-top:10px">
			<div class="featured-article-tab">
				<ul>
				    <li onclick="clickMyTabs('activatedTab')" <?php if($genieType == 'activated') echo 'class="active"'; ?> ><a id="activatedTab" href="/searchAgents/searchAgents/openUpdateSearchAgent/0/10">Activated Genie</a></li>
				    <li onclick="clickMyTabs('deactiveTab')" <?php if($genieType == 'deactive') echo 'class="active"'; ?>><a id="deactiveTab" href="/searchAgents/searchAgents/openUpdateSearchAgent/0/10/normal/deactive">Deactivated Genie</a></li>
				    <li onclick="clickMyTabs('deletedTab')" <?php if($genieType == 'deleted') echo 'class="active"'; ?>><a id="deletedTab" href="/searchAgents/searchAgents/openUpdateSearchAgent/0/10/normal/deleted">Deleted Genie</a></li>
				</ul>
			</div>
            <!--Start_Repeating_Data-->
            
			<div style="width:100%; float:left;">
				<?php if($search_agents_all_array['totalRows'] > 1){ ?>
				<div style=" margin: 0 5px 15px 0;float: left;">
	            	Sort By :
	            	<select id="sortByCriteria" class="auto-dwnload-select" style="padding:3px 2px; width:auto;">
	            		<option value="created_on" <?php if($searchCriteria == 'created_on'){echo 'selected'; }?>>Genie creation date </option>
	            		<option value="updated_on" <?php if($searchCriteria == 'updated_on'){echo 'selected'; }?> >Genie Modification date </option>
	            		<option value="leads" <?php if($searchCriteria == 'leads'){echo 'selected'; }?> >No. of Leads </option>
	            	</select>
	            	 <input class="sa_goBtn" type="button" onclick="sortSearchAgentGenies($('sortByCriteria').value);" style="position:relative; top:2px;">
        		</div>
        		<?php } ?>	

				<div id="pagingIDc" style="padding:3px 0 3px 3px; float:right;">
						<!--Pagination Related hidden fields Starts-->
						<input type="hidden" id="startOffset" value="<?php echo $start; ?>"/>
						<input type="hidden" id="countOffset" value="<?php echo $count; ?>"/>
						<input type="hidden" id="methodName" value="getPaginatedAgents"/>
						<!--Pagination Related hidden fields Ends  -->
						<span><span class="pagingID" id="paginataionPlace1"><?php echo $paginationHTML; ?></span></span>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php $i=1+$start; 
				if($search_agents_all_array['totalRows']==0){
				echo "<div class='showMessages'>You do not have any ".$genieType." Genie. </div>";
				}
				foreach($search_agents_all_array as $tempArray){
				if(is_array($tempArray)){ 
			?>
    			<div class="genie-section">
    			<div class="genie-inner-section genie-label">
    				<ul> 
    					<li>  
    						<span style="float:left; width:25px; margin-right:3px;"><?php echo $i; ?>. </span>
		    				<div class="genie-col">
		    					<p class="genie-name" style="width:280px; word-wrap:break-word" title="<?php echo $tempArray['name']; ?>"><?php echo formatArticleTitle($tempArray['name'],22); ?></p>
		    					<ul class="genie-detail-list">
		    						
		    							<!-- <label>Searched criteria:</label> -->

		    							<?php foreach($search_agents_all_display_array as $tempDisplay){ 
											if($tempDisplay[0]['searchagentid']==$tempArray['sa_id']){ 
													$TemplateData = json_decode(base64_decode($tempDisplay[0]['displaydata']));
													
													if(isset($TemplateData->streamData) && !empty($TemplateData->streamData)){ ?>
														<?php 
															$stream = "";
															foreach($TemplateData->streamData as $row=>$col){
																$stream[] = $col;
															}
														?>
														<li>
															<label>Stream: </label>
															
																 <div class="disInln" id='stream_<?php echo $tempArray['sa_id']; ?>'><?php echo implode(',',$stream); ?> </div> 
															
														</li>
													<?php }
													if ($search_agents_all_array[$tempDisplay[0]['searchagentid']]["type"] != "response"){
													if(isset($TemplateData->isActiveUser)){ ?>
														<li>
															<label>Active User Included: </label> 
															<?php 
															if ($TemplateData->isActiveUser == 'on')
															{
																echo "Yes";
															}
															else{ 
																if ($activeUserMapping[$tempDisplay[0]['searchagentid']] == 'yes')
																{
																	echo "Yes";
																}
																else{
																	echo "No";
																}
															}
															?>

														</li>
													<?php } else {
														?>
														<li>
															<label>Active User Included: </label> 
															<?php 
															
															if ($activeUserMapping[$tempDisplay[0]['searchagentid']] == 'yes')
															{
																echo "Yes";
															}
															else{
																echo "No";
															}
													
															?>

														</li>
													<?php }}

													if(isset($TemplateData->DesiredCourseName) && !empty($TemplateData->DesiredCourseName)){ ?>
														
														<?php $DesiredCourseName = "";
																foreach($TemplateData->DesiredCourseName as $row=>$col){
																	$DesiredCourseName[$col] = $col;
																}
															?>
														<li>
															<label>Course: </label>
															 <?php echo implode(',',$DesiredCourseName); ?>  
														</li>
													<?php }

													if(isset($TemplateData->courseData) && !empty($TemplateData->courseData)){ ?>
														<?php 
															$courses = "";
															foreach($TemplateData->courseData as $row=>$col){
																$courses[] = $col;
															}
															$coursesList = implode(',', $courses);
														?>
														<li>
															<label>Course Name: </label>

																<?php if(strlen($coursesList) > 150) { ?>
																	<div class="disInln" id='courseNameShorten_<?php echo $tempArray['sa_id']; ?>'> 
																		<?php echo formatArticleTitle($coursesList, 150); ?> 
																		<a href='javascript:showMoreInfo("courseName","<?php echo $tempArray['sa_id']; ?>");' >Show More</a> 
																	</div> 
																	<div class="disInln" id='courseName_<?php echo $tempArray['sa_id']; ?>' style="display:none;"> <?php echo $coursesList; ?> </div> 

																<?php }else{ ?>
																 <div class="disInln" id='courseName_<?php echo $tempArray['sa_id']; ?>'> <?php echo $coursesList; ?> </div> 
															<?php } ?>
															
														</li>
													<?php }

													

													/*check for matched Response later - ajay*/
													if(isset($TemplateData->matchedCourses) && !empty($TemplateData->matchedCourses)){ 
															$matchedForCourses = array();
															foreach($TemplateData->matchedCourses as $row=>$col){
																$matchedForCourses[] = $col;
															}
														?>
														<li>
															<label>Matched Courses: </label>
															<?php $matchedCoursesData =  implode(", ",$matchedForCourses); ?>
															<?php if(strlen($matchedCoursesData) > 150) { ?>
																	<div id='matchedCourseShorten_<?php echo $tempArray['sa_id']; ?>'> 
																		<?php echo formatArticleTitle($matchedCoursesData, 150); ?> 
																		<a href='javascript:showMoreInfo("matchedCourse","<?php echo $tempArray['sa_id']; ?>");' >Show More</a> 
																	</div> 
																	<div id='matchedCourse_<?php echo $tempArray['sa_id']; ?>' style="display:none;"> <?php echo $matchedCoursesData; ?> </div> 

																<?php }else{ ?>
																 <div id='matchedCourse_<?php echo $tempArray['sa_id']; ?>'> <?php echo $matchedCoursesData; ?> </div> 
															<?php } ?>
														</li>
													<?php }
													/*~~~~check for matched Response later - ajay   - ENDS~~~*/


													if(isset($TemplateData->subStreamData) && !empty($TemplateData->subStreamData)){ ?>
														
														<?php 
															$subStream = "";
															foreach($TemplateData->subStreamData as $row=>$col){
																$subStream[] = $col;
															}
														?>
														<li>
															<label>Sub Stream: </label>
															<?php echo implode(',',$subStream); ?>  
														</li>
													<?php }

													/*kept here for study abroad -if not used, then remove*/
													/*Specialization For study abroad - STARTS*/
													/*if(isset($TemplateData->Specialization) && !empty($TemplateData->Specialization)){ ?>
														<li>
															<label>Specialization: </label>
															<?php //echo formatArticleTitle($TemplateData->Specialization,150); ?>
															<?php if(strlen($TemplateData->Specialization) > 150) { ?>
																	<div id='specializationShorten_<?php echo $tempArray['sa_id']; ?>'> 
																		<?php echo formatArticleTitle($TemplateData->Specialization, 150); ?> 
																		<a href='javascript:showMoreInfo("specialization","<?php echo $tempArray['sa_id']; ?>");' >Show More</a> 
																	</div> 
																	<div id='specialization_<?php echo $tempArray['sa_id']; ?>' style="display:none;"> <?php echo $TemplateData->Specialization; ?> </div> 

																<?php }else{ ?>
																 <div id='specialization_<?php echo $tempArray['sa_id']; ?>'> <?php echo $TemplateData->Specialization; ?> </div> 
															<?php } ?>
														</li>
													<?php }*/
													/*Specialization For study abroad - ENDS*/

													if(isset($TemplateData->specializationData) && !empty($TemplateData->specializationData)){ ?>
														<?php 
															$specializations = array();
															foreach($TemplateData->specializationData as $row=>$col){
																$specializations[] = $col;
															}
														?>
														<li>
															<label>Specialization: </label>
															<?php $specList = implode(', ',$specializations); 
																if(strlen($specList) > 150) { ?>
																	<div id='specializationShorten_<?php echo $tempArray['sa_id']; ?>'> 
																		<?php echo formatArticleTitle($specList, 150); ?> 
																		<a href='javascript:showMoreInfo("specialization","<?php echo $tempArray['sa_id']; ?>");' >Show More</a> 
																	</div> 
																	<div id='specialization_<?php echo $tempArray['sa_id']; ?>' style="display:none;"> <?php echo $specList; ?> </div> 

																<?php }else{ ?>
																 <div class="disInln" id='specialization_<?php echo $tempArray['sa_id']; ?>'> <?php echo $specList; ?> </div> 
															<?php } ?>
														</li>
													<?php }

													if(isset($TemplateData->modeDataDisplay) && !empty($TemplateData->modeDataDisplay)){ ?>
														
														<?php 
														   	$modeLevels = $TemplateData->modeDataDisplay;
														?>
														<li>
															<label>Mode: </label>
															<?php echo $modeLevels; ?>
														</li>
													<?php }
													
													/*Specialization For study abroad - STARTS*/
													if(isset($TemplateData->abroadSpecializations) && !empty($TemplateData->abroadSpecializations)){ ?>
														<li>
															<label>Specialization: </label>
															<?php $abroadSpecializations  = implode(',', $TemplateData->abroadSpecializations);
																  if(strlen($abroadSpecializations) > 150) { ?>
																	<div id='abroadSpecializationsShorten_<?php echo $tempArray['sa_id']; ?>'> 
																		<?php echo formatArticleTitle($abroadSpecializations, 150); ?> 
																		<a href='javascript:showMoreInfo("abroadSpecializations","<?php echo $tempArray['sa_id']; ?>");' >Show More</a> 
																	</div> 
																	<div id='abroadSpecializations_<?php echo $tempArray['sa_id']; ?>' style="display:none;"> <?php echo $abroadSpecializations; ?> </div> 

																<?php }else{ ?>
																 <div id='abroadSpecializations_<?php echo $tempArray['sa_id']; ?>'> <?php echo $abroadSpecializations; ?> </div> 
															<?php } ?>
														</li>
													<?php }
													/*Specialization For study abroad - ENDS*/

													// check for study abroad if they use it
													if(isset($TemplateData->DesiredCourseLevels) && !empty($TemplateData->DesiredCourseLevels)){ ?>
														
														<?php $desiredCourseLevels = array();
																foreach($TemplateData->DesiredCourseLevels as $row=>$col){
																	if($col == 'UG'){
																		$desiredCourseLevels[]= " Bachelors";
																	}else if($col == 'PG'){
																		$desiredCourseLevels[] = " Masters";
																	}else{
																		$desiredCourseLevels[] = " ".$col;
																	}
																}

															?>
														<li>
															<label>Desired Course Levels: </label>
															<?php echo implode(',',$desiredCourseLevels); ?>
														</li>
													<?php }
													//ABOVE CODE  -  check for study abroad if they use it


													if(isset($TemplateData->mode) && !empty($TemplateData->mode)){ ?>
														
														<?php $modeLevels = array();
																foreach($TemplateData->mode as $row=>$col){
																		$modeLevels[] = " ".$col;
																}
															?>
														<li>
															<label>MODE: </label>
															<?php echo implode(',',$modeLevels); ?>
														</li>
													<?php }


													if(isset($TemplateData->prefLocation) && !empty($TemplateData->prefLocation)){ ?>
														<li>
															<label>Preferred Location: </label>
															<?php if(strlen($TemplateData->Specialization) > 150) { ?>
																	<div id='prefLocationShorten_<?php echo $tempArray['sa_id']; ?>'> 
																		<?php echo formatArticleTitle($TemplateData->prefLocation, 150); ?> 
																		<a href='javascript:showMoreInfo("prefLocation","<?php echo $tempArray['sa_id']; ?>");' >Show More</a> 
																	</div> 
																	<div id='prefLocation_<?php echo $tempArray['sa_id']; ?>' style="display:none;"> <?php echo $TemplateData->prefLocation; ?> </div> 

																<?php }else{ ?>
																 <div id='prefLocation_<?php echo $tempArray['sa_id']; ?>'> <?php echo $TemplateData->prefLocation; ?> </div> 
															<?php } ?>
														</li>
													<?php }

													if(isset($TemplateData->currentLocation) && !empty($TemplateData->currentLocation)){ ?>
														<li>
															<label>Location: </label>
															<?php //echo formatArticleTitle($TemplateData->currentLocation, 150); ?>
															<?php if(strlen($TemplateData->currentLocation) > 150) { ?>
																	<div id='currentLocationShorten_<?php echo $tempArray['sa_id']; ?>'> 
																		<?php echo formatArticleTitle($TemplateData->currentLocation, 150); ?> 
																		<a href='javascript:showMoreInfo("currentLocation","<?php echo $tempArray['sa_id']; ?>");' >Show More</a> 
																	</div> 
																	<div id='currentLocation_<?php echo $tempArray['sa_id']; ?>' style="display:none;"> <?php echo $TemplateData->currentLocation; ?> </div> 

																<?php }else{ ?>
																 <div id='currentLocation_<?php echo $tempArray['sa_id']; ?>'> <?php echo $TemplateData->currentLocation; ?> </div> 
															<?php } ?>
														</li>
													<?php }

if(isset($TemplateData->MRLocation) && !empty($TemplateData->MRLocation)){ ?>
<li>
<label>Preferred Location: </label>
<?php if(strlen($TemplateData->MRLocation) > 150) { ?>
	<div id='mrLocationShorten_<?php echo $tempArray['sa_id']; ?>'>
		<?php echo formatArticleTitle($TemplateData->MRLocation, 150); ?> 
		<a href='javascript:showMoreInfo("mrLocation","<?php echo $tempArray['sa_id']; ?>");' >Show More</a> 
	</div> 
	<div id='mrLocation_<?php echo $tempArray['sa_id']; ?>' style="display:none;">
		<?php echo $TemplateData->MRLocation; ?>
	</div> 
	<?php } else { ?>
	 <div id='mrLocation_<?php echo $tempArray['sa_id']; ?>'>
		<?php echo $TemplateData->MRLocation; ?>
	</div> 
	<?php } ?>
</li>
<?php }
													if(isset($TemplateData->cityLocalityDisplay) && ($TemplateData->cityLocalityDisplay != '')){ ?>
														<?php 
															$currentCities = "";
														?>
														<li>
															<label>Current Location: </label>
															<?php $currentCities = $TemplateData->cityLocalityDisplay;  ?>
															<?php if(strlen($currentCities) > 150) { ?>
																	<div class="disInln" id='CurrentCityShorten_<?php echo $tempArray['sa_id']; ?>'> 
																		<?php echo formatArticleTitle($currentCities, 150); ?> 
																		<a href='javascript:showMoreInfo("CurrentCity","<?php echo $tempArray['sa_id']; ?>");' >Show More</a> 
																	</div> 
																	<div class="disInln" id='CurrentCity_<?php echo $tempArray['sa_id']; ?>' style="display:none;"> <?php echo $currentCities; ?> </div> 

																<?php }else{ ?>
																 <div class="disInln" id='CurrentCity_<?php echo $tempArray['sa_id']; ?>'> <?php echo $currentCities; ?> </div> 
															<?php } ?>
														</li>
													<?php }

													if(isset($TemplateData->planToStart) && !empty($TemplateData->planToStart)){ ?>
														<li>
															<label>Plan to Start: </label>
															 <?php echo implode(", ",$TemplateData->planToStart); ?> 
															
														</li>
													<?php }

													if(isset($TemplateData->passport) && !empty($TemplateData->passport)){ ?>
														<li>
															<label>Passport: </label>
															<?php echo $TemplateData->passport; ?>
														</li>
													<?php }

													/*below Exams is for abroad*/
													if(isset($TemplateData->examTaken) && !empty($TemplateData->examTaken)){ ?>
														<li>
															<label>Exam: </label>
															<?php echo $TemplateData->examTaken; ?>
														</li>
													<?php }

													/*below Exams is for national*/
													if(isset($TemplateData->exams) && !empty($TemplateData->exams)){ ?>
														<li>
															<label>Exam: </label>
															<?php echo implode(',',$TemplateData->exams); ?>
														</li>
													<?php }

													if(isset($TemplateData->graduationDate) && !empty($TemplateData->graduationDate)){ ?>
														<li>
															<label>Graduation Date: </label>
															<?php echo $TemplateData->graduationDate; ?>
														</li>
													<?php }

													if(isset($TemplateData->XIIGraduationDate) && !empty($TemplateData->XIIGraduationDate)){ ?>
														<li>
															<label>XII Date: </label>
															<?php echo $TemplateData->XIIGraduationDate; ?>
														</li>
													<?php }

													if(isset($TemplateData->workex) && !empty($TemplateData->workex)){ ?>
														<li>
															<label>Work Exp: </label>
															<?php echo $TemplateData->workex; ?>
														</li>
													<?php }


												?>
											<?php }
										} ?>
		    					</ul>	

		    				</div>
		    				<div class="genie-col">
		    					<p class="genie-name" style="font-size:12px; padding-top:10px;">Auto Download <?php if(!empty($genieType) && $genieType != 'deleted'){ ?> [ <a href="#" onClick="callSAformOverlay('<?php echo $tempArray['sa_id']; ?>',<?php echo $search_agents_all_array[$tempArray['sa_id']]['groupid']['groupid'] ?>);" >Edit</a> ] <?php } ?> </p>
		    					<ul class="genie-detail-list">
		    						<li>
		    							<?php $activatePauseAutoDownload="activatePauseAutoDownload".$tempArray['sa_id'];?>
		    							<span id="<?php echo $activatePauseAutoDownload; ?>">
										<span><strong>Status: </strong><span style="margin-left:80px;"><?php if($tempArray['auto_download']['is_active']=='live'){ ?> <strong class="fcGrn">Activated</strong> &nbsp; &nbsp; <?php if(!empty($genieType) && $genieType != 'deleted'){ ?><a href="javascript:editActivatePaused('<?php echo $tempArray['sa_id']; ?>','flag_auto_download','live','<?php echo $activatePauseAutoDownload; ?>');">Deactivate</a> <?php } }else if($tempArray['auto_download']['is_active']=='history'){ ?> <strong style="color:#d12902; ">Deactivated</strong> &nbsp; &nbsp; <?php if(!empty($genieType) && $genieType != 'deleted') { ?><a href="javascript:editActivatePaused('<?php echo $tempArray['sa_id']; ?>','flag_auto_download','history','<?php echo $activatePauseAutoDownload; ?>');">Activate</a> <?php } }else if($tempArray['auto_download']['is_active']==NULL){ ?><strong style="color:#d12902">Deactivated</strong> &nbsp; &nbsp; <?php if(!empty($genieType) && $genieType != 'deleted'){ ?> <a href="#" onClick="callSAformOverlay('<?php echo $tempArray['sa_id']; ?>',<?php echo $search_agents_all_array[$tempArray['sa_id']]['groupid']['groupid'] ?>);" >Activate</a> <?php  } }?></span></span>
									</span>
		    						</li>
		    						<li>
		    							<?php if($tempArray['type'] == 'lead') { ?>
		    							<label class="label-width">No. of Leads:</label>
		    							<?php echo $tempArray['auto_download']['detail']['leads_daily_limit']; ?><?php if($tempArray['auto_download']['detail']['leads_daily_limit']==NULL){?>0<?php } ?> per day  &nbsp; &nbsp; 
		    							<?php }else{ ?>
		    								<label class="label-width">No. of Matched Responses:</label>
		    								<?php echo $tempArray['auto_download']['detail']['leads_daily_limit']; ?><?php if($tempArray['auto_download']['detail']['leads_daily_limit']==NULL){?>0<?php } ?> per day  &nbsp; &nbsp;
	    								<?php } ?>
		    						</li>
		    						<li>
		    							

		    							<?php if($tempArray['auto_download']['detail']['email_freq']=='asap' || $tempArray['auto_download']['detail']['email_freq']=='everyhour'){ ?><label class="label-width">Email Frequency:</label><?php $emailFreqDivId="emailFreqDivId".$tempArray['sa_id']; ?> <select name="email_freq" <?php if($genieType == 'deleted'){?> disabled="true" <?php } ?>  class="auto-dwnload-select" onChange="showSaveButton('<?php echo $emailFreqDivId; ?>');"><option value="asap" <?php echo $tempArray['auto_download']['detail']['email_freq'] == "asap" ? 'selected' :'';?>>Instantaneously</option><option value="everyhour" <?php echo $tempArray['auto_download']['detail']['email_freq'] == "everyhour" ? 'selected' :'';?>>Every 1 hour</option></select> &nbsp;<span id="<?php echo $emailFreqDivId; ?>" style="display:none"><input type="button" onClick="javascript:editActivatePaused('<?php echo $tempArray['sa_id']; ?>','email_freq','<?php echo $tempArray['auto_download']['detail']['email_freq']; ?>','<?php echo $emailFreqDivId; ?>');" value="&nbsp;" class="spirtCMSBtn sCMSBtn" /></span><?php } ?>
		    						</li>
		    						<li>
		    							<label class="label-width">Email Ids:</label>
		    							<?php echo $tempArray['contact_details']['email'][0]; ?><?php if(!empty($tempArray['contact_details']['email'][1])){?>, <?php echo $tempArray['contact_details']['email'][1]; } ?><?php if(!empty($tempArray['contact_details']['email'][2])){?>, <?php echo $tempArray['contact_details']['email'][2]; } ?>
		    						</li>
		    						<li>
		    							<label class="label-width">Mobile Number:</label>
		    							<?php echo $tempArray['contact_details']['mobile'][0]; ?><?php if(!empty($tempArray['contact_details']['mobile'][1])){?>, <?php echo $tempArray['contact_details']['mobile'][1]; } ?>
		    						</li>
		    						<li>
		
		    							<?php if($tempArray['auto_download']['detail']['sms_freq']=='asap' || $tempArray['auto_download']['detail']['sms_freq']=='everyhour'){ ?><label class="label-width">SMS Frequency:</label><?php $smsFreqDivId="smsFreqDivId".$tempArray['sa_id']; ?> <select class="auto-dwnload-select" <?php if($genieType == 'deleted'){?> disabled="true" <?php } ?> name="sms_freq" onChange="showSaveButton('<?php echo $smsFreqDivId; ?>');"><option value="asap" <?php echo $tempArray['auto_download']['detail']['sms_freq'] == "asap" ? 'selected' :'';?>>Instantaneously</option><option value="everyhour" <?php echo $tempArray['auto_download']['detail']['sms_freq'] == "everyhour" ? 'selected' :'';?>>Every 1 hour</option></select> &nbsp;<span id="<?php echo $smsFreqDivId; ?>" style="display:none"><input type="button" onClick="javascript:editActivatePaused('<?php echo $tempArray['sa_id']; ?>','sms_freq','<?php echo $tempArray['auto_download']['detail']['sms_freq']; ?>','<?php echo $smsFreqDivId; ?>');" value="&nbsp;" class="spirtCMSBtn sCMSBtn" /><?php } ?>
		    						</li>
		    					</ul>	
		    				</div>
		    				<div class="genie-col last">
		    					<p class="genie-name" style="font-size:12px;padding-top:10px;">Auto Responder <?php if(!empty($genieType) && $genieType != 'deleted'){ ?> [ <a href="#" onClick="callSAformOverlay('<?php echo $tempArray['sa_id']; ?>',<?php echo $search_agents_all_array[$tempArray['sa_id']]['groupid']['groupid'] ?>);" >Edit</a> ] <?php } ?>  </p>
		   		    					<ul class="genie-detail-list">
		    						<li>
		    							<?php $activatePauseAutoResponderEmail="activatePauseAutoResponderEmail".$tempArray['sa_id']; ?>
		    							<span id="<?php echo $activatePauseAutoResponderEmail; ?>">
		    								<label class="label-width">Email Responder:</label>
											<?php if($tempArray['auto_responder']['email']['is_active']=='live'){?> <strong class="fcGrn">Activated</strong> &nbsp; &nbsp; <?php if(!empty($genieType) && $genieType != 'deleted'){ ?> <a href="javascript:editActivatePaused('<?php echo $tempArray['sa_id']; ?>','flag_auto_responder_email','live','<?php echo $activatePauseAutoResponderEmail; ?>');">Deactivate</a> &nbsp; &nbsp; <?php } }else if($tempArray['auto_responder']['email']['is_active']=='history' || $tempArray['auto_responder']['email']['is_active']==NULL){ ?>  <strong style="color:#d12902">Deactivated</strong> &nbsp; &nbsp; <?php if($tempArray['auto_responder']['email']['is_active']=='history' && !empty($genieType) && $genieType != 'deleted'){ ?><a href="javascript:editActivatePaused('<?php echo $tempArray['sa_id']; ?>','flag_auto_responder_email','history','<?php echo $activatePauseAutoResponderEmail; ?>');">Activate</a> <?php }else if($tempArray['auto_responder']['email']['is_active']==NULL && !empty($genieType) && $genieType != 'deleted'){ ?><a href="#" onClick="callSAformOverlay('<?php echo $tempArray['sa_id']; ?>',<?php echo $search_agents_all_array[$tempArray['sa_id']]['groupid']['groupid'] ?>);">Activate</a> <?php } ?>&nbsp; &nbsp; <?php } ?></span><?php if($tempArray['auto_responder']['email']['subject']!=NULL || $tempArray['auto_responder']['email']['msg']!=NULL){?><span style="display: block; margin-left: 120px;"> [ <a href="javascript:void(0);" onClick="viewEmail('<?php echo base64_encode($tempArray['auto_responder']['email']['subject']); ?>','<?php echo base64_encode($tempArray['auto_responder']['email']['msg']); ?>','<?php echo base64_encode($tempArray['auto_responder']['email']['from_emailid']);?>');">View Email</a> ]</span><?php } ?>
										</span?
		    						</li>
		    						<li> 
		    							<?php if($tempArray['type'] == 'lead') { ?>
										<label class="label-width">No. of Leads(Email):</label><?php echo $tempArray['auto_responder']['email']['daily_limit']; ?><?php if($tempArray['auto_responder']['email']['daily_limit']==NULL){?>0<?php } ?> per day  &nbsp; &nbsp;
									 <?php } else { ?>
										<label class="label-width">No. of Matched Responses(Email): </label><?php echo $tempArray['auto_responder']['email']['daily_limit']; ?><?php if($tempArray['auto_responder']['email']['daily_limit']==NULL){?>0<?php } ?> per day  &nbsp; &nbsp;
									<?php } ?>
		    						</li>
		    						<li>
		    							<?php $activatePauseAutoResponderSms="activatePauseAutoResponderSms".$tempArray['sa_id']; ?>
		    							<span id="<?php echo $activatePauseAutoResponderSms; ?>">
		    								<label class="label-width">SMS Responder:</label>
		    								<!-- <a href="#" class="active-color" style="display:inline"><strong>Activated</strong></a><a href="#" style="display:inline;margin-left:10px"><strong>Deactivated</strong></a> -->
									<?php if($tempArray['auto_responder']['sms']['is_active']=='live'){?> <strong class="fcGrn">Activated</strong>  &nbsp; &nbsp; <?php if(!empty($genieType) && $genieType != 'deleted'){ ?> <a href="javascript:editActivatePaused('<?php echo $tempArray['sa_id']; ?>','flag_auto_responder_sms','live','<?php echo $activatePauseAutoResponderSms; ?>');">Deactivate</a> &nbsp; &nbsp; <?php } }else if($tempArray['auto_responder']['sms']['is_active']=='history' || $tempArray['auto_responder']['sms']['is_active']==NULL){ ?> <strong style="color:#d12902">Deactivated</strong> &nbsp; &nbsp; <?php if($tempArray['auto_responder']['sms']['is_active']=='history' && !empty($genieType) && $genieType != 'deleted'){ ?><a href="javascript:editActivatePaused('<?php echo $tempArray['sa_id']; ?>','flag_auto_responder_sms','history','<?php echo $activatePauseAutoResponderSms; ?>');">Activate</a><?php }else if($tempArray['auto_responder']['sms']['is_active']==NULL && !empty($genieType) && $genieType != 'deleted'){ ?><a href="#" onClick="callSAformOverlay('<?php echo $tempArray['sa_id']; ?>',<?php echo $search_agents_all_array[$tempArray['sa_id']]['groupid']['groupid'] ?>);">Activate</a> <?php } ?> &nbsp; &nbsp;<?php } ?></span><?php if($tempArray['auto_responder']['sms']['msg']!=NULL){?> <span style="display: block; margin-left: 120px;"> [ <a href="javascript:void(0);" onClick="viewSMS('<?php echo base64_encode($tempArray['auto_responder']['sms']['msg']); ?>');">View SMS</a> ]</span> <?php } ?></span>

		    							</span>
		    						</li>
		    						<li>

		    							
		    							<?php if($tempArray['type'] == 'lead') { ?>
									<label class="label-width">No. of Leads(SMS):</label> <?php if($tempArray['auto_responder']['sms']['daily_limit']==''){ echo '0'; }else{ echo $tempArray['auto_responder']['sms']['daily_limit']; }?> per day  &nbsp; &nbsp;
									<?php } else { ?>
									<label class="label-width">No. of Matched Responses(SMS): </label> <?php if($tempArray['auto_responder']['sms']['daily_limit']==''){ echo '0'; }else{ echo $tempArray['auto_responder']['sms']['daily_limit']; }?> per day  &nbsp; &nbsp;
									<?php } ?>
		    						</li>
		    					</ul>
		    				</div>
		    				<div class="clearFix"></div>
	    				</li>
    				</ul>

    				<ul style="border: none">

    				Backlog: <span id='<?php echo $tempArray['sa_id']; ?>_leftover' style="padding: 3px 16px;background: #f8f8f8;border: 1px solid #ccc;min-width: 110px;"><?php echo $leftOver[$tempArray['sa_id']]; ?></span> 
    				<a href="javascript:resetLeftOverStatus('<?php echo $tempArray['sa_id']; ?>');" class="orange-button">Reset</a>
    					Click on Reset button to set backlog counter value to zero(0)

    				</ul>
    				<p class="backlog--txt"><strong style="
						">Backlog</strong>  Counter for each genie is equal to shortfall between daily limit of genie and lead/MR delivered to you fo each preceding day aggregated till date. It is calculated from the day genie was created or backlog counter was set to Zero(0), whichever date is greater of two.</p>
    				<div style="width: 100%; float: left; font-size: 11px; color: rgb(102, 102, 102);">
    					<div class="flLt">
    					<p style="font-weight:bold;"><?php if($tempArray['type'] == 'lead') { ?> No. of Leads Allocated: <?php }else{ ?> No. of MR Allocated:<?php }?><?php if(isset($allocatedLeadsCountForGenie[$tempArray['sa_id']])){ echo $allocatedLeadsCountForGenie[$tempArray['sa_id']]; }else{ echo '0'; }; ?> </p>
    					<?php 
    						$createdOn = date_parse($tempArray['created_on']);
    						$updatedOn = date_parse($tempArray['updated_on']);
    					?>
    					<p>Created on: <?php echo $createdOn['day']." ".$months[$createdOn['month']]." ".$createdOn['year']; ?> | Modified on: <?php echo $updatedOn['day']." ".$months[$updatedOn['month']]." ".$updatedOn['year']; ?></p>
						</div>    					
    					<div class="flRt button-wrap">

    						<form  style="display:inline; <?php if(!empty($genieType) && $genieType == 'deleted'){ echo 'float:right;'; }?> " method="post" action="/MIS/SADownloadleads/index" id="formsubmit_<?php echo $tempArray['sa_id']; ?>">
    							<input type="hidden" id="SearchagentId" name="SearchagentId" value="<?php echo $tempArray['sa_id']; ?>">
    							<input type="hidden" id="SearchagentStatus" name="SearchagentStatus" value="<?php echo $genieType; ?>">
	    						<label>From : </label>
	                            <input type="text" id="timerangeFrom_<?php echo $tempArray['sa_id']; ?>" onclick="timerange('From','<?php echo $tempArray['sa_id']; ?>',this);" name="timerangeFrom_<?php echo $tempArray['sa_id']; ?>" readonly="readonly" style="width:90px; font-size:12px;border: 1px solid #ccc;" value="" placeholder='dd/mm/yyyy' />
	                            <img id="timerangeFromImg_<?php echo $tempArray['sa_id']; ?>" onclick="timerange('From','<?php echo $tempArray['sa_id']; ?>',this);" src="/public/images/calendar.jpg" width="15" height="15" alt="calendar" style="position:relative; top:3px; cursor: pointer;" class="calendar-image" />

	                            <label style="width:40px; margin-left: 5px;">To : </label>
	                            <input type="text" id="timerangeTill_<?php echo $tempArray['sa_id']; ?>" onclick="timerange('Till','<?php echo $tempArray['sa_id']; ?>',this);" name="timerangeTill_<?php echo $tempArray['sa_id']; ?>" readonly="readonly" style="width:90px;font-size:12px;border: 1px solid #ccc;" value="" placeholder='dd/mm/yyyy' />
	                            <img id="timerangeTillImg_<?php echo $tempArray['sa_id']; ?>" onclick="timerange('Till','<?php echo $tempArray['sa_id']; ?>',this);" src="/public/images/calendar.jpg" width="15" height="15" alt="calendar" style="position:relative; top:3px; cursor: pointer;" class="calendar-image" />

	    						<a href="javascript:void(0);" onclick="dowloadLeads(<?php echo $tempArray['sa_id']; ?>); return false;" class="orange-button" style="margin-left: 5px;">Download</a>
	    					</form>

    						<?php if(!empty($genieType) && $genieType != 'deleted'){ ?>
	    						<a href="javascript:void(0);" onclick="return runsearchAgent('<?php echo $tempArray['sa_id']; ?>');" class="orange-button">Run</a>
	    						<a href="javascript:editActivatePaused('<?php echo $tempArray['sa_id']; ?>','is_active','live','<?php echo $activatePauseSearchAgent; ?>');" class="orange-button">Delete</a>
    						<?php } ?>
    					</div>
    				</div>
    			</div>
    		</div>
			<?php $i++; }}?>
            <!--End_Repeating_Data-->
		</div>
		<div id="showingEventsList2"></div>
		<div class="txt_align_r mt10">
			<div id="pagingIDc" style="padding:3px">
				<span>
					<span class="pagingID" id="paginataionPlace2"><?php echo $paginationHTML;?></span>
				</span>
			</div>
		</div>
</div>
<?php if($hasPortingAgents && !$Search_Agent_Update_flag) { ?>
	<div style="padding:10px;"><b><a href='/searchAgents/searchAgents/openUpdateSearchAgent/0/10/porting' id="viewPortingGenieLink">View porting genie</a></b></div>
<?php } ?>			
<div class="lineSpace_35">&nbsp;</div>	
</div>
</div>
</div>
