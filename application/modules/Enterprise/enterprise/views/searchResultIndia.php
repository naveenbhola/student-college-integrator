<?php if($clientAccess === false && $_POST['ldb_viewed_flag'] != 1) { ?>
<div style="border: 1px solid #000;display: inline-block;margin-left: 10px;  position:relative;padding: 10px 10px 10px 40px" class="OrgangeFont"><img src="/public/images/exclamation-circle_yellow.png" style=" left: 5px;position: absolute;top: 3px;width: 30px;" /><?php if(!empty($displayArray['ldbAccessMsg'])) { echo $displayArray['ldbAccessMsg']; } else { echo "Manual download of leads is not available any more. Please click <b>'Create a Genie'</b> to proceed further."; } ?></div>
<div class="lineSpace_10">&nbsp;</div>
<?php } ?>
<div style="width:100%">
	<div class="cmsSResult_pagingBg">
		<div style="margin:0 10px">
			<div style="line-height:6px">&nbsp;</div>
			<div style="width:100%">
				<div class="float_L" style="width:41%">
					<div style="width:100%">
						<div style="height:22px">
							<span><span class="pagingID" id="paginationPlace1"></span></span>
						</div>
					</div>
				</div>
				<div class="float_L" style="width:33%"></div>
				<!-- <div class="float_L" style="width:25%">
					<div style="width:100%">
						<div style="height:22px" class="txt_align_r">
							<span class="normaltxt_11p_blk bld pd_Right_6p">View:
								<select class="selectTxt" name="countOffset" id="countOffset_DD1" onChange= "updateCountOffset(this,'startOffSetSearch','countOffsetSearch');">
									<option value="10">10</option>
									<option value="15">15</option>
									<option value="20">20</option>
									<option value="25">25</option>
									<option value="50">50</option>
									<option value="100">100</option>
								</select>
							</span>
						</div>
					</div>
				</div> -->
				<div class="cmsClear">&nbsp;</div>
			</div>
		</div>
	</div>
</div>
<!--End_NavigationBar-->
<!--Start_SendMail_SendSMS-->
<div style="width:100%;background:#fffbff;height:35px">
	<div style="margin:0 10px">
		<div style="line-height:6px">&nbsp;</div>
		<div style="width:100%">
			<div class="float_L" style="width:50%">
				<div style="width:100%">
					<div style="height:23px">

						<?php if($clientAccess === true || $_POST['ldb_viewed_flag'] == 1) { ?>

						<input type="checkbox" id="checkAllUsers_1" />
						<input type="button" value="Send Mail" class="cmsSResult_sendMailBtn" />&nbsp;&nbsp;
						<input type="button" value="Send SMS" class="cmsSResult_sendSMSBtn" />&nbsp;&nbsp;
						<input type="button" value="&nbsp;" class="cmsSResult_downLoadCSV" />

						<?php } ?>

					</div>
				</div>
			</div>
			<div class="float_L" style="width:49%" id="cvsRightLink">
				<div style="float:right; height:23px;line-height:23px;<?php if($clientAccess === false) { echo "margin-right:130px;"; }?>">
					<div style='float:left; margin-right:5px;'>Show Students: </div>
					<?php if(empty($actual_course_id)) { ?>
					<div style="float:left; margin-right:3px;" >
					<?php } else { ?>
					<div style="float:left; margin-right:3px;">
					<?php } ?>

					<?php if($clientAccess === true) { ?>

						<a class="" href="javascript:void(0);" id="switchLDBSearchFilter_all" >All<?php if($underViewedLimitFlagSet && !isset($DontShowViewed)){ ?> (Under Limit) <?php } ?></a> |

					<?php }  else { ?>
					
						<a class="" style="display:none;" href="javascript:void(0);" id="switchLDBSearchFilter_all">&nbsp;</a>

					<?php } ?>

						<div style='position: relative; display:none;' id='layerForAll'>
							<div style="position: absolute; width: 100px; height: 50px; border: 1px solid #ddd; left: 0px;">
								<ul style='margin:0; padding:0'>
									<li style="margin:0; padding:1px 1px 1px 5px; border-bottom:1px solid #ddd;"><a  href='javascript:void(0);' style='display:block;'>All</a></li>
									<li style="margin:0; padding:1px 1px 1px 5px;"><a onclick="return switchLDBSearchFilter('all:UnderViewLimit');" href='javascript:void(0);' style='display:block;'>Under view limit</a></li>
								</ul>								
							</div>
						</div>
					</div>
					<?php if(empty($actual_course_id)) { ?>
					<div style="float:left; margin-right:3px;" >
					<?php } else { ?>
					<div style="float:left; margin-right:3px;">
					<?php } ?>
						<a href="javascript:void(0);" id="switchLDBSearchFilter_unviewed">Unviewed<?php if($underViewedLimitFlagSet && isset($DontShowViewed)){ ?> (Under Limit) <?php } ?></a> |
						<div style='position: relative; display:none;' id='layerForUnviewed'>
							<div style="position: absolute; width: 100px; height: 50px; border: 1px solid #ddd; left: 0px;">
								<ul style='margin:0; padding:0'>
									<li style="margin:0; padding:1px 1px 1px 5px; border-bottom:1px solid #ddd;"><a  href='javascript:void(0);' style='display:block;'>All</a></li>
									<li style="margin:0; padding:1px 1px 1px 5px;"><a onclick="return switchLDBSearchFilter('unviewed:UnderViewLimit');" href='javascript:void(0);' style='display:block;'>Under view limit</a></li>
								</ul>								
							</div>
						</div>
					</div>
					<div style='float:left; margin-right:3px;'>							
						<a id="switchLDBSearchFilter_viewed" href="javascript:void(0);" class="">Viewed</a> <?php echo '|'; ?>
					</div>

					<?php //if($clientAccess === true) { ?>

					<div style='float:left; margin-right:3px;'>							
						<a id="switchLDBSearchFilter_emailed" href="javascript:void(0);" class="">Emailed</a> |
					</div>
					<div style='float:left;'>							
						<a id="switchLDBSearchFilter_smsed" href="javascript:void(0);" class="">SMSed</a>
					</div>		
					
					<?php //}  else { ?>


					<!-- <div style='float:left; margin-right:3px;display:none'>							
						<a id="switchLDBSearchFilter_emailed" href="javascript:void(0);" class="">Emailed</a> |
					</div>
					<div style='float:left;display:none'>							
						<a id="switchLDBSearchFilter_smsed" href="javascript:void(0);" class="">SMSed</a>
					</div> -->

					<?php //} ?>

				</div>
			</div>
			<div class="cmsClear">&nbsp;</div>
		</div>
	</div>
</div>
<input type="hidden" name="actual_course_id" id="actual_course_id" value="<?php echo $actual_course_id;?>" />
<input type="hidden" name="clientAccess" id="clientAccess" value="<?php echo $clientAccess;?>" />
<!--End_SendMail_SendSMS-->
<div class="lineSpace_10">&nbsp;</div>
	<!--Start_MainDateRowContainer------------------------------------------>
	<div style="width:100%">
		<!--Start_DateRow_1------------------------------------------>
		<?php if(isset($resultResponse['numrows'])) { ?>
		<?php $rowCount=0; ?>
		<?php foreach($resultResponse['result'] as $row) {
			if(!$row['userId']){
				continue;
			}
			$flag = $resultResponse['userIdMap'][$row['userId']];			
			$showMessage =false;
			if($flag == 'LDB_MR' || $flag == 'SA_MR'){
				$showMessage =true;
			}?>
		<?php $isResponse = in_array($row['userid'],$responses); ?>
		<div style="width:100%">
			<!--Start_PersonSay-->
			<div style="width:100%">
				<div style="margin:0 10px">
					<div style="width:100%">
						<div style="height:23px">

							<?php if($clientAccess === true || $_POST['ldb_viewed_flag'] == 1) { ?>

							<input type="hidden" id="viewCredit_<?php echo $row['userid']; ?>" value="<?php echo ($row['ViewCredit']) ? base64_encode($row['ViewCredit']) : '0'; ?>" />
							<input type="hidden" id="emailCredit_<?php echo $row['userid']; ?>" value="<?php echo ($row['EmailCredit']) ? base64_encode($row['EmailCredit']) : '0'; ?>" />
							<input type="hidden" id="smsCredit_<?php echo $row['userid']; ?>" value="<?php echo ($row['SmsCredit']) ? base64_encode($row['SmsCredit']) : '0'; ?>" />

							<input class="checkInput" type="checkbox" id="rowName_<?php echo $rowCount?>" name="userCheckList[]" value="<?php echo $row['userid']; ?>"/>

							<?php } else { ?>

							<input style="display:none" type="checkbox" id="rowName_<?php echo $rowCount?>" name="userCheckList[]" value="<?php echo $row['userid']; ?>"/>

							<?php } ?>

							 <b class="fontSize_14p" <?php if($clientAccess === false && $_POST['ldb_viewed_flag'] != 1) { echo 'style="float:left;margin-left:20px;"'; } ?>><?php echo $row['firstname'].' '.$row['lastname']; ?></b> <?php if(empty($actual_course_id)) { echo $row['gender']!=""? ", ".$row['gender']:""; ?> <?php echo ($row['age'] != "0" && $row['age'] !="")?", ".$row['age']." years":""; } ?>
						</div>
					</div>
				</div>
			</div>

			<!--Start_PersonInformation-->
			<?php $prefCount=0;?>
						<div class="lineSpace_20">&nbsp;</div>
							<div style="width:100%">
								<!--Start_Margin40-->
								<div style="margin:0 40px">
									<!--Start_100%Width--->
									<div style="width:100%">
										<div class="float_L" style="width:33%">
											<div style="width:100%">
												<div style="padding-right:15px">
													<div style="width:100%">
														<!-- -->
													
													<!-- -->
													<?php if(empty($actual_course_id)) { ?>
														
															<div class="cmsSResult_pdBtm7"><span class="darkgray">Stream:</span> <b><?php echo $row['streamData']; ?></b></div>

																<?php 
																	$hyphen = '';
																	global $noSpecName;
																	if(isset($row['subStreamData']) && isset($row['specializationData'])){
																		$hyphen = '-';
																	}
																	if(!isset($row['subStreamData']) && !isset($row['specializationData'])){
																		$hyphen = 'N.A.';
																	}
																	if(isset($row['subStreamData']) && !isset($row['specializationData'])){
																		$hyphen = '-';
																		$row['specializationData'] = $noSpecName['noSpecMapping_data'];
																	}
																?>
															
																<div class="cmsSResult_pdBtm7"><span class="darkgray">Specialization:</span> <?php echo $row['subStreamData'].$hyphen.$row['specializationData']; ?></div>

																<div class="cmsSResult_pdBtm7"><span class="darkgray">Course:</span><b> <?php echo $row['courseData']; ?></b></div>
																
																<div class="cmsSResult_pdBtm7"><span class="darkgray"> Mode:</span>
																	<?php
																		foreach ($row['modeData'] as $key => $value) {
																			echo '<b>'.$key.'</b>';
																			if($value != '')
																				echo ' - '.$value;
																			echo '<br/>';
																		}
																	?>
																</div>
															
														
													<?php } else { ?>
														<?php if(count($matchedResponseData[$row['userid']]) > 0) {
															$displayMatchedCourses = array(); ?>
																<?php foreach($matchedResponseData[$row['userid']]['matchedCourses'] as $matchedCourseId=>$matchedCourse) {
																	/*$displayMatchedCourses[] = $matchedCourse['CourseName'].', '.$matchedCourse['InstituteName'];*/
																	$name = $matchedCourse['CourseName'];
																	if($matchedCourse['InstituteName']){
																		$name .= ','.$matchedCourse['InstituteName'];
																	}

																	$displayMatchedCourses[] =$name;
																} ?>
																<div class="cmsSResult_pdBtm7">
																	<span class="darkgray">Matched Response for:<br/></span>
																		<b><?php echo implode(';<br/>',array_values($displayMatchedCourses)); ?></b>
																</div>
														<?php 	} ?>
													<?php } ?>
													</div>
												</div>
											</div>
										</div>
										<div class="float_L" style="width:33%">
											<div style="width:100%">
												<div class="cmsSResult_dottedLineVertical">
													<div style="width:100%">

														<?php 
															$workex = '';
															if(isset($row['workex'])){
																$value = $row['workex'];
																if($value == -1){
																	$workex = 'No Experience';
																}else if($value == 10){
																	$workex = '> 10 Years';
																} else if($value == 0){
																	$workex = $value.'-'.(intval($value+1)).' Year';
																} else {
																	$workex = $value.'-'.(intval($value+1)).' Years';
																}
															}
															else {
																$workex = 'N.A.';
															}
														?>
														
														<div class="cmsSResult_pdBtm7"><span class="darkgray"><?php echo 'Work Experience:'; ?> </span><b><?php echo $workex; ?></b></div>

														<?php
															$competitiveExam = "";
															if(!empty($row['EducationData'])) {
																$competitiveExamArray = array();
																foreach($row['EducationData'] as $value){
																	if($value['Level'] == "Competitive exam"){	
																		$examObj = \registration\builders\RegistrationBuilder::getCompetitiveExam($value['Name'],$value);
																		/*if($competitiveExam) {
																			$competitiveExam .= ', ';
																		}*/
																		//$competitiveExam .= $examObj->displayExamName(TRUE);
																		$competitiveExamArray[] = $examObj->displayExamName(TRUE);

																	}
																}
																sort($competitiveExamArray);
																$competitiveExam = implode(', ', $competitiveExamArray);
															}
														?>

														<div class="cmsSResult_pdBtm7"><span class="darkgray">Exams Taken:</span> <?php echo ($competitiveExam ? $competitiveExam : '<b>N.A.</b>'); ?></div>								
														
														<div class="cmsSResult_pdBtm7">
														<?php
														if($row['CurrentCity']) {
															$citylocation = '<span class="darkgray">City:</span> <b>'.$row['CurrentCity'].'</b>';
														} else {
															$citylocation = '<span class="darkgray">Current Location:</span> <b> N.A.</b>';
														}
														echo $citylocation;
														?>
														</div>
														
														<?php if(!empty($actual_course_id)) { ?>
														<div class="cmsSResult_pdBtm7"><span class="darkgray">Preferred Locations:</span> <b> <?php if($row['ResponseLocations']) { echo implode(", ", $row['ResponseLocations']); } else { echo 'N.A.'; } ?></b></div>
														<?php } ?>
															
														<div class="cmsSResult_pdBtm7">
															<span class="darkgray">Locality:</span> 
															<b> <?php echo ($row['localityName'] ? $row['localityName'] :'N.A.'); ?></b>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="float_L" style="width:33%">
											<div style="width:100%">
												<div class="cmsSResult_dottedLineVertical">
													<div style="width:100%">
														<?php
															$prefLocationArray= array();
															foreach($prefDetails['LocationPref'] as $value) {
																if($value['CityId'] != 0 && $value['CityName'] !=""){
																	array_push($prefLocationArray,$value['CityName']);
																}
																else{
																	if($value['StateId'] != 0 && $value['StateName'] != ""){
																		array_push($prefLocationArray,"Anywhere in ".$value['StateName']);
																	}
																	else{
																		if($value['CountryId'] != 0 && $value['CountryName'] !=""){
																			array_push($prefLocationArray,"Anywhere in ".$value['CountryName']);
																		}
																	}
																}
															}
														?>
														
														<?php if(empty($actual_course_id)) { ?>
																<div class="cmsSResult_pdBtm7"><span class="darkgray">Creation Date:</span> <?php echo date('d M Y', strtotime($row['CreationDate'])); ?></div>
																<div class="cmsSResult_pdBtm7"><span class="darkgray">Last Active:</span> <?php echo date('d M Y', strtotime($row['LastLoginDate'])); ?></div>
														<?php } else { ?>
																<div class="cmsSResult_pdBtm7"><span class="darkgray">Response Date:</span> <b><?php echo (strtotime($matchedResponseData[$row['userid']]['date']) ? date('d M Y',strtotime($matchedResponseData[$row['userid']]['date'])) : 'N.A.'); ?></b></div>
														<?php } ?>
																<div class="cmsSResult_pdBtm7"><span class="darkgray">NDNC Status:</span> <b><?php 
																if($row['isNDNC'] == 'YES'){
																	echo 'Do not call';
																} else if($row['isNDNC'] == 'NO'){
																	echo 'Can call';
																} else{
																	echo 'N.A.';
																}?>
																</b></div>
																<?php
																	if($prefCount == 0 ) {
																		if($row['ContactData']['view'] || $isResponse) {
																		if($isResponse) {	
																?>
																	<div class="cmsSResult_pdBtm7"><span class="redcolor"><img align="absbottom" src="/public/images/cmsSResult_Checked.gif"/> <span class="darkgray">Has earlier made response on your listing</span></span></div>
																	<?php } else { ?>
																	<div class="cmsSResult_pdBtm7"><span class="redcolor"><img align="absbottom" src="/public/images/cmsSResult_Checked.gif"/> <span class="darkgray">Contact viewed on</span> <?php echo $row['ContactData']['view'][0]; ?> </span></div>
																	<?php } ?>
																	<div id="userContactDetailDiv_<?php echo $row['userid']; ?>">
																		<div class="cmsSResult_pdBtm7">Mobile: <?php echo $row['mobile']; ?></div>
																		<?php if($row['landline'] != "") { ?>
																			<div class="cmsSResult_pdBtm7">Landline: <?php echo $row['landline']; ?></div>
																		<?php } ?>
																		<div class="cmsSResult_pdBtm7" title="<?php echo $row['email'];?>" style="word-wrap: break-word;">Email: <?php echo $row['email']; ?></div>
																		<?php if($row['mobileverified'] != 0 && $row['emailverified'] != 0) { ?>
																			<!-- <span style="color:#185100;font-size:16px"><b>V</b></span>erified -->
																		<?php } ?>
																	</div>
																<?php } else { ?>
																	<div class="cmsSResult_pdBtm7" id="userContactDetailDiv_<?php echo $row['userid']; ?>">
																		<?php if($clientAccess === true) { ?>

																		<input class="showContactDetails" user="<?php echo $row['userid']; ?>" type="image" id="view_ldb_contact_detail_<?php echo $row['userid']; ?>" style="cursor:pointer" src="/public/images/vContactDtl.jpg" /> &nbsp;

																		<?php } ?>

																		<?php if($row['mobileverified'] != 0 && $row['emailverified'] != 0) { ?>
																			<!--<span style="color:#185100;font-size:16px"><b>V</b></span>erified-->
																		<?php } ?>
																	</div>
															<?php } 
																} ?>

															<?php if ($prefCount == 0 && !$isResponse) { ?>
															
																<?php if($clientAccess === true) { 
																	if(empty($row['ContactData']['view'])) { ?>

																		<div id="creditRequired_<?php echo $row['userid']; ?>" style=""><span class="darkgray"><b>Credit Required:</b><br /></span> <b>View: <?php echo $row['ViewCredit']; ?> | SMS: <?php echo $row['SmsCredit']; ?> | Email: <?php echo $row['EmailCredit']; ?></b></div>
																	<?php }  ?>

																		<div id="creditConsumed_<?php echo $row['userid']; ?>" <?php if(isset($row['CreditConsumed']) && !empty($row['ContactData']['view'])) { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> ><span class="darkgray"><b>Credit Consumed: <?php echo $row['CreditConsumed']; ?></b></span><br />You can now Email/SMS this student for free.</div>
																		<?php if($showMessage){?>
																		<b>User viewed as Matched Response</b>
																		<?php } ?>

																	<?php } ?>

																<div class="darkgray mt10">
																<?php
																	if(!$row['ContactData']['view'] ){
																		if (!empty($row['ViewCountArray'])) {				

																		$ViewCount = $row['ViewCountArray']['ViewCount'];
																		if(!$ViewCount){
																			$ViewCount =0;
																		}
																		
																		$str = "No. of views: <b><span id='viewed_times_".$row['userid']."'>" . $ViewCount . "</span></b>";
																		echo $str;
																		} else {
																			echo "No. of views: <b><span id='viewed_times_".$row['userid']."'>0</span></b>";
																		}
																	}
																}
																?>
															</div>													

															</div>
														</div>
													</div>
												</div>
												<div class="cmsClear">&nbsp;</div>

											</div>
											<!--End_100%Width--->
										</div>
										<!--End_Margin40-->
<!-- -->

										<?php if($clientAccess === true || $_POST['ldb_viewed_flag'] == 1) { ?>

										<div>
										<?php if(isset($row['ContactData']['email'])) { ?>
											<span class="redcolor"><img align="absbottom" src="/public/images/cmsSResult_mailCheck.gif"/> Emailed on <?php echo $row['ContactData']['email'][0]; ?></span>
										<?php } else { ?>
										<a href="javascript:void(0);" class="sendEmailSingle" row="rowName_<?php echo $rowCount; ?>" >Send Email</a>
										<?php } if(isset($row['ContactData']['sms'])) { ?>
											<span class="redcolor"><img align="absbottom" src="/public/images/cmsSResult_mobile.gif"/> SMSed on <?php echo $row['ContactData']['sms'][0]; ?></span>
										<?php /* } else if($row['isNDNC']=='NO'){ */ } else { ?>
										<a href="javascript:void(0);" class="sendSmsSingle" row="rowName_<?php echo $rowCount; ?>" >Send SMS</a>
										<?php } ?>
										</div>

										<?php } ?>

									</div>
								
						<!--End_PersonInformation-->
					</div>
					<div class="cmsSearch_SepratorLine" style="margin:15px 10px">&nbsp;</div>
				<?php
				$rowCount++;
				} ?>
				<?php } else {
					if(isset($resultResponse['error'])) {
						echo '<div class="fontSize_18p" style="padding-bottom:7px; padding-left: 20px;">'.$resultResponse['error'].'</div>';
					}
					else {
						echo '<div class="fontSize_18p" style="padding-bottom:7px; padding-left: 20px;">There are no matching students as per your criteria.</div>';
					}
				}
				?>
				<!--End_DateRow_1------------------------------------------>
			</div>
			<!--End_MainDateRowContainer------------------------------------------>
			<div class="lineSpace_10">&nbsp;</div>
			<!--Start_SendMail_SendSMS-->
			<div style="width:100%;background:#fffbff;height:35px">
				<div style="margin:0 10px">
					<div style="line-height:6px">&nbsp;</div>
					<div style="width:100%">
						<div class="float_L" style="width:50%">
							<div style="width:100%">
								<div style="height:23px">

									<?php if($clientAccess === true || $_POST['ldb_viewed_flag'] == 1) { ?>

									<input type="checkbox" id="checkAllUsers_2" />
									<input type="button" value="Send Mail" class="cmsSResult_sendMailBtn" />&nbsp;&nbsp;
									<input type="button" value="Send SMS" class="cmsSResult_sendSMSBtn" />&nbsp;&nbsp;
									<input type="button" value="&nbsp;" class="cmsSResult_downLoadCSV" />

									<?php } ?>

								</div>
							</div>
						</div>
						<div class="float_L" style="width:49%">
							<div style="width:100%"><div style="height:23px;font-size:11px" class="txt_align_r">&nbsp;</div></div>
						</div>
						<div class="cmsClear">&nbsp;</div>
					</div>
				</div>
			</div>
			<!--End_SendMail_SendSMS-->
			<!--Start_NavigationBar-->
			<div style="width:100%">
				<div class="cmsSResult_pagingBg">
					<div style="margin:0 10px">
						<div style="line-height:6px">&nbsp;</div>
						<div style="width:100%">
							<div class="float_L" style="width:41%">
								<div style="width:100%"><div style="height:22px">
								<span>
								<span class="pagingID" id="paginationPlace2"></span>
								</span>
								</div></div>
							</div>
						<!--<div class="float_L" style="width:40%">
						<img src="/public/images/cvsIst.jpg" align="absmiddle" />
						<img src="/public/images/cvsPre.jpg" align="absmiddle" />
						<span>1 of 1</span>
						<img src="/public/images/cvsNext.jpg" align="absmiddle" />
						<img src="/public/images/cvsLast.jpg" align="absmiddle" />
						&nbsp; | &nbsp; <input type="text" size="2" />
						<input type="button" value="&nbsp;" class="cvs_Go" />
						</div>-->
							<div class="float_L" style="width:33%">
								
							</div>
							<!-- <div class="float_L" style="width:25%">
								<div style="width:100%">
									<div style="height:22px" class="txt_align_r">
									<span style=""> &nbsp; View:
									<select class="selectTxt" name="countOffset" id="countOffset_DD2" onChange= "updateCountOffset(this,'startOffSetSearch','countOffsetSearch');">
										<option value="10">10</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="100">100</option>
									</select>
									</span>
									</div>
								</div>
							</div> -->
							<div class="cmsClear">&nbsp;</div>
						</div>
					</div>
				</div>
			</div>
