<SCRIPT type="text/javascript">
	$j('#searchFormSub').attr('action','/enterprise/shikshaDB/searchResults');
</SCRIPT>


<?php 
if($clientAccess === false && $_POST['ldb_viewed_flag'] != 1) { ?>
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
							<div class="float_L" style="width:33%">
								
							</div>
							<div class="float_L" style="width:25%">
								<div style="width:100%">
									<div style="height:22px" class="txt_align_r">
										<span style=""> &nbsp;Students:
										<select class="selectTxt" name="countOffset" id="countOffset_DD1" onChange= "updateCountOffset(this,'startOffSetSearch','countOffsetSearch');">
										<option value="10">10</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="100">100</option>
										</select>
                                         &nbsp; per page
										</span>
									</div>
								</div>
							</div>
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

									<input type="checkbox" id="checkAllUsers_1" onClick="checkAllUsers(this);"/> <input type="button" value="Send Mail" class="cmsSResult_sendMailBtn" onClick="sendMail();"/>&nbsp;&nbsp;<input type="button" value="Send SMS" class="cmsSResult_sendSMSBtn" onClick="sendSms();"/>&nbsp;&nbsp;<input type="button" value="&nbsp;" class="cmsSResult_downLoadCSV" onClick="downloadCSV();"/>

									<?php } ?>

								</div>
							</div>
						</div>
						
						<div class="float_L" style="width:49%" id="cvsRightLink">
							<div style="float:right; height:23px;line-height:23px;<?php if($clientAccess === false) { echo "margin-right:130px;"; }?>">
								<div style='float:left; margin-right:5px;'>		
									Show Students:
								</div>
								
								<div style="float:left; margin-right:3px;" onmouseover="$('layerForAll').style.display = 'block';" onmouseout="$('layerForAll').style.display = 'none';">
	
									<?php if($clientAccess === true) { ?>

    								<a class="" href="javascript:void(0);" id="switchLDBSearchFilter_all" onclick="return switchLDBSearchFilter('all');">All<?php if($underViewedLimitFlagSet && !isset($DontShowViewed)){ ?> (Under Limit) <?php } ?></a> |

									<?php }  else { ?>

									<a class="" style="display:none;" href="javascript:void(0);" id="switchLDBSearchFilter_all">&nbsp;</a>

									<?php } ?>

								    <div style='position: relative; display:none;' id='layerForAll'>
								    <div style="position: absolute; width: 100px; height: 50px; border: 1px solid #ddd; left: 0px;">
								    <ul style='margin:0; padding:0'>
								    <li style="margin:0; padding:1px 1px 1px 5px; border-bottom:1px solid #ddd;"><a onclick="return switchLDBSearchFilter('all');" href='javascript:void(0);' style='display:block;'>All</a></li>
								    <li style="margin:0; padding:1px 1px 1px 5px;"><a onclick="return switchLDBSearchFilter('all:UnderViewLimit');" href='javascript:void(0);' style='display:block;'>Under view limit</a></li>
								    </ul>								
								    </div>
								    </div>
								</div>
								
								<div style="float:left; margin-right:3px;" onmouseover="$('layerForUnviewed').style.display = 'block';" onmouseout="$('layerForUnviewed').style.display = 'none';">				
								    <a class="change" onclick="return switchLDBSearchFilter('unviewed');" href="javascript:void(0);" id="switchLDBSearchFilter_unviewed">Unviewed<?php if($underViewedLimitFlagSet && isset($DontShowViewed)){ ?> (Under Limit) <?php } ?></a> |
								    
								    <div style='position: relative; display:none;' id='layerForUnviewed'>
								    <div style="position: absolute; width: 100px; height: 50px; border: 1px solid #ddd; left: 0px;">
								    <ul style='margin:0; padding:0'>
								    <li style="margin:0; padding:1px 1px 1px 5px; border-bottom:1px solid #ddd;"><a onclick="return switchLDBSearchFilter('unviewed');" href='javascript:void(0);' style='display:block;'>All</a></li>
								    <li style="margin:0; padding:1px 1px 1px 5px;"><a onclick="return switchLDBSearchFilter('unviewed:UnderViewLimit');" href='javascript:void(0);' style='display:block;'>Under view limit</a></li>
								    </ul>								
								    </div>
								    </div>
								</div>
							
								<div style='float:left; margin-right:3px;'><a id="switchLDBSearchFilter_viewed" onclick="return switchLDBSearchFilter('viewed');" href="javascript:void(0);" class="">Viewed</a><?php echo '|'; ?>
								</div>
								
								<div style='float:left; margin-right:3px;'><a id="switchLDBSearchFilter_emailed" onclick="return switchLDBSearchFilter('emailed');" href="javascript:void(0);" class="">Emailed</a> |
								</div>
								
								<div style='float:left;'><a id="switchLDBSearchFilter_smsed" onclick="return switchLDBSearchFilter('smsed');" href="javascript:void(0);" class="">SMSed</a>
								</div>
								
							</div>
						</div>
							
						<div class="cmsClear">&nbsp;</div>
					</div>
				</div>
			</div>
			<!--End_SendMail_SendSMS-->
			<div class="lineSpace_10">&nbsp;</div>
			
			<input type="hidden" name="clientAccess" id="clientAccess" value="<?php echo $clientAccess;?>" />
			
			<!--Start_MainDateRowContainer------------------------------------------>
			<div style="width:100%">
				<!--Start_DateRow_1------------------------------------------>
				<?php if(isset($resultResponse['numrows'])) { ?>
				<?php $rowCount=0; ?>
				<?php
                foreach($resultResponse['result'] as $row) {
                ?>
				
				<?php $isResponse = in_array($row['userid'],$responses); ?>
				
					<div style="width:100%">
						<!--Start_PersonSay-->
						<div style="width:100%">
							<div style="margin:0 10px">
								<div style="width:100%">
									<div style="height:23px">

										<?php if($clientAccess === true || $_POST['ldb_viewed_flag'] == 1) { ?>

										<input onClick="checkAllInput()" type="checkbox" id="rowName_<?php echo $rowCount?>" name="userCheckList[]" value="<?php echo $row['userid']; ?>"/> 

										<?php } else { ?>

										<input style="display:none" type="checkbox" id="rowName_<?php echo $rowCount?>" name="userCheckList[]" value="<?php echo $row['userid']; ?>"/>

										<?php } ?>

										<b class="fontSize_14p" <?php if($clientAccess === false && $_POST['ldb_viewed_flag'] != 1) { echo 'style="float:left;margin-left:20px;"'; } ?>><?php echo $row['firstname'].' '.$row['lastname']; ?></b> <?php echo $row['gender']!=""? ", ".$row['gender']:""; ?> <?php echo ($row['age'] != "0" && $row['age'] !="")?", ".$row['age']." years":""; ?>
									</div>
								</div>
							</div>
						</div>
						<!--Start_PersonInformation-->
						<?php $prefCount=0; ?>
						<?php foreach($row['PrefData']  as $prefDetails) {
							if($prefCount==1) {
								echo '<a class="cmsSearch_plusImg" href="#" onclick="showInterestHistory(this,\''.$row["userid"].'\');return false;">View interest history</a>';
								echo '<div style="width:100%;display:none" id="parentDiv_'.$row['userid'].'">'; } ?>
									<?php if($prefDetails['UserDetail']!="") { ?>
										<div style="width:100%;">
											<div style="margin:0 35px 0 20px">
												<div style="width:100%">
													<div style="position:relative;top:1px"><img src="/public/images/cmsSResult_sayArrow.gif" /></div>
													<div>
														<div class="cmsSResult_L"><div class="cmsSResult_R">&nbsp;</div></div>
														<div style="margin-left:1px">
															<div style="border-left:1px solid #e7e7e7;border-right:1px solid #e7e7e7">
																<div style="width:100%">
																	<div style="margin:0 20px">
																		<?php echo $prefDetails['UserDetail']; ?>
																	</div>
																</div>
															</div>
														</div>
														<div class="cmsSResult_BL"><div class="cmsSResult_BR">&nbsp;</div></div>
													</div>
												</div>
											</div>
										</div>
									<?php } ?>
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
                                                            <?php
                                                                $prefLocationArray= array();
                                                                foreach($prefDetails['LocationPref'] as $value) {
                                                                    if($value['CountryId'] != 0 && $value['CountryName'] !=""){
                                                                        array_push($prefLocationArray,$value['CountryName']);
                                                                    }
                                                                }
                                                                    if(!empty($prefLocationArray)) {
                                                            ?>
																	<div class="cmsSResult_pdBtm7"><span class="darkgray">Preferred Country:</span> <b> <?php echo implode(', ',$prefLocationArray); ?></b></div>
                                                                    <?php
                                                                        }
                                                                        foreach($prefDetails['SpecializationPref'] as $value) {
                                                                            $categoryName = $value['CategoryName'];
                                                                            $courseLevel = $value['CourseLevel1'];
                                                                            if($courseLevel == 'UG') {
                                                                                $courseLevel = 'Bachelors';
                                                                            }
                                                                            if($courseLevel == 'PG') {
                                                                                $courseLevel = 'Masters';
                                                                            }
                                                                            $courseNameArray = array();
                                                                            $specializationArray = array();
                                                                            foreach($prefDetails['SpecializationPref'] as $value){
                                                                                if (isset($value['blogTitle'])) {
                                                                                    array_push($courseNameArray,$value['blogTitle']);
                                                                                } 
                                                                                else {
                                                                                    if(!in_array($value['CourseName'], $courseNameArray)){
                                                                                    array_push($courseNameArray,$value['CourseName']);
                                                                                    }
                                                                                    if(!in_array($value['SpecializationName'],$specializationArray)){
                                                                                    array_push($specializationArray,$value['SpecializationName']);
                                                                                    }
                                                                                }
                                                                            }

                                                                            if(!empty($categoryName) && strtolower(trim($categoryName)) != "all"){
                                                                                echo '<div class="cmsSResult_pdBtm7"><span class="darkgray">Field of Interest:</span> <b>'.$categoryName.'</b></div>';
                                                                            }elseif(implode(",&nbsp;<wbr/>",$courseNameArray) == 'MBA' || implode(",&nbsp;<wbr/>",$courseNameArray) == 'BE/Btech' || implode(",&nbsp;<wbr/>",$courseNameArray) == 'MS'){
                                                                                echo '<div class="cmsSResult_pdBtm7"><span class="darkgray">Desired Course: </span><b>'.implode(",&nbsp;<wbr/>",$courseNameArray).'</b></div>';
                                                                            }
                                                                            /*if(!empty($categoryName)) {
                                                                    ?>
																	<div class="cmsSResult_pdBtm7"><span class="darkgray">Field of Interest:</span> <b><?php echo $categoryName; ?></b></div>
                                                                    <?php
                                                                            }*/
                                                                            if(!empty($courseLevel)) {
                                                                    ?>
																	<div class="cmsSResult_pdBtm7"><span class="darkgray">Desired Course Level:</span> <b><?php echo $courseLevel; ?></b></div>
                                                                    <?php
                                                                            }
                                                                    }
                                                                    ?>
                                                                      <!--  < ?php if(isset($row['PrefData'][0]['program_budget'])){
                                                                            $budget = (int)$row['PrefData'][0]['program_budget'];
                                                                            global $budgetToTextArray;
                                                                            if(key_exists($budget, $budgetToTextArray)) {
                                                                                    $budget = $budgetToTextArray[$budget];
                                                                            }
                                                                            echo '<div class="cmsSResult_pdBtm7"><span class="darkgray">Budget: </span><b>'.$budget.'</b></div>';
                                                                        } ?> -->

                                                                        <?php if(isset($row['passport'])){
                                                                            echo '<div class="cmsSResult_pdBtm7"><span class="darkgray">Passport: </span><b>'.ucfirst($row['passport']).'</b></div>';
                                                                        } ?>
                                                                        <?php if($row['CurrentCity'] != "") { ?>
                                                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Current Location:</span> <b> <?php echo $row['CurrentCity']; ?></b></div>
                                                                        <?php } ?>
                                                                        <?php if($prefDetails['UserFundsOwn'] == "yes" || $prefDetails['UserFundsNone'] == "yes" || $prefDetails['UserFundsBank'] == "yes") {
                                                                        $sources = array();
                                                                        $sources[] = $prefDetails['UserFundsOwn'] == "yes" ? 'Own ' : '';
                                                                        $sources[] = $prefDetails['UserFundsBank'] == "yes" ? 'Bank Loan ' : '';
                                                                        $sources[] = $prefDetails['UserFundsNone'] == "yes" ? 'Others ' : '';
                                                                        $sources = trim(implode(', ', $sources),', ');
                                                                ?>
																	<div class="cmsSResult_pdBtm7"><span class="darkgray">Source(s) of Funding:</span> <b> <?php echo $sources; ?></b></div>
																<?php } ?>
															</div>
														</div>
													</div>
												</div>
												<div class="float_L" style="width:33%">
													<div style="width:100%">
														<div class="cmsSResult_dottedLineVertical">
															<div style="width:100%">
																<?php if($prefDetails['TimeOfStart'] != "" ) {
																	$datediff=datediff($prefDetails['TimeOfStart'],$prefDetails['SubmitDate']);
																?>
<!--																	<div class="cmsSResult_pdBtm7"><span class="darkgray">Plan to Start:</span> <?php echo ($prefDetails['YearOfStart']!='0000')?(($datediff!=0)?"Within ".$datediff:"Immediately"):"Not Sure"; ?><?php echo " (as on ".$row['CreationDate']." )"?></div>-->
																	<div class="cmsSResult_pdBtm7">
                                                                        <span class="darkgray">Plan to Start:</span>
                                                                        <?php //$datediff=datediff($prefDetails['TimeOfStart'],$prefDetails['SubmitDate']);
                                                                            //echo ($prefDetails['YearOfStart']!='0000')?(($datediff==0)?"Current Year":(($datediff==1)?"Next Year":"Not Sure")):"Not Sure"; ?>

                                                                        <?php 
                                                                       		if($prefDetails['YearOfStart'] == date('Y'))
															    			    echo 'Current Year';
															    		    else if($prefDetails['YearOfStart'] == date('Y')+1)
															    			    echo 'Next Year';
															    		    else
															    			    echo 'Not Sure';
                                                                        ?>
                                                                    </div>
																<?php } ?>
																<?php
																	$pursuingEducation = array();
																	$completedEducation = array();
																	$competitiveExam = "";
																	foreach($row['EducationData'] as $value){
                                                                        $marksType = $value['MarksType'] == 'percentage' ? '%' : ' '. $value['MarksType'];
																		$divRow= array();
																		if($value['Level'] == 10){
																			$divRow['Title'] = "<span class='darkgray'>X Std</span>";
																			$divRow['Value'] = $value['Name']. " - ".$value['Marks']. $marksType;
																		}
																		if($value['Level'] == 12){
																			$divRow['Title'] = "<span class='darkgray'>XII Std</span>";
																			$divRow['ValueName'] = $value['Name'];
																			$divRow['Value'] = ($value['Marks']!=0)?$value['Marks'] . $marksType . (($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (". current( explode('-', $value['CourseCompletionDate'])) .") "):'';
																		}
																		if($value['Level'] == "UG"){
																			if($value['OngoingCompletedFlag'] == 1) {
																				$divRow['Title'] = "<span class='darkgray'>Pursuing</span>";
																				$divRow['ValueName'] = $value['Name'];
																				$divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (". current(explode('-', $value['CourseCompletionDate'])).") ");
																			}
																			else{
																				$divRow['Title'] = "<span class='darkgray'>UG Details</span>";
																				$divRow['ValueName'] = $value['Name'];
																				$divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (". current(explode('-', $value['CourseCompletionDate'])) .") ");
																				$divRow['Value'].=($value['Marks']!=0)?" - ".$value['Marks'] . $marksType :'';
																			}
																		}
																		if($value['Level'] == "PG"){
																			if($value['OngoingCompletedFlag'] == 1) {
																				$divRow['Title'] = "<span class='darkgray'>Pursuing</span>";
																				$divRow['ValueName'] = $value['Name'];
																				$divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (". current(explode('-', $value['CourseCompletionDate'])).") ");
																			}
																			else {
																				$divRow['Title'] = "<span class='darkgray'>PG Details</span>";
																				$divRow['ValueName'] = $value['Name'];
																				$divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (". current(explode('-', $value['CourseCompletionDate'])).") ");
																				$divRow['Value'].=($value['Marks']!=0)?" - ".$value['Marks']. $marksType :'';
																			}
																		}
																		if($value['Level'] == "Competitive exam"){
																			if($value['Name'] != ""){
																				/*if($competitiveExam == ""){
																					$competitiveExam = "<b>".$value['Name']."</b>".(($value['Marks']!="" && $value['Marks']!=0)?" ( ".$value['Marks']. $marksType ." )":"");
																				}
																				else{
																					$competitiveExam .= ", <b>".$value['Name']."</b>".(($value['Marks']!="" && $value['Marks']!=0)?" ( ".$value['Marks']. $marksType ." )":"");
																				}*/
                                                                                                                                                                
                                                                                                                                                                $examObj = \registration\builders\RegistrationBuilder::getCompetitiveExam($value['Name'],$value);
                                                                                                                                                                if($competitiveExam) {
                                                                                                                                                                        $competitiveExam .= ', ';
                                                                                                                                                                }
                                                                                                                                                                $competitiveExam .= $examObj->displayExam();

																			}
																		}
																		if($value['OngoingCompletedFlag'] == 1){
																			array_push($pursuingEducation, $divRow);
																		}
																		else{
																			array_push($completedEducation , $divRow);
																		}
																	}
																?>
																<?php
																	foreach(array_merge($pursuingEducation,$completedEducation) as $value) {
																		if($value['Title']!="") { ?>
																			<div class="cmsSResult_pdBtm7"><?php echo $value['Title']; ?> <b><?php echo $value['ValueName']; ?></b><?php echo $value['Value'] != '' ? ': '.$value['Value'] : ''; ?></div>
																		<?php }
																	}
																?>
																<?php if($competitiveExam != "") { ?>
																	<div class="cmsSResult_pdBtm7"><span class="darkgray">Exams Taken:</span> <?php echo $competitiveExam; ?></div>
																<?php } ?>

															</div>
														</div>
													</div>
												</div>
												<div class="float_L" style="width:33%">
													<div style="width:100%">
														<div class="cmsSResult_dottedLineVertical">
															<div style="width:100%">
																<div class="cmsSResult_pdBtm7"><span class="darkgray">Lead processed date:</span> <?php 
																echo date("jS M Y",strtotime($row['PrefData'][0]['SubmitDate']));
																 ?></div>
																<div class="cmsSResult_pdBtm7"><span class="darkgray">Last active on:</span> <?php echo $row['LastLoginDate']; ?></div>
                                                                <?php /*
                                                                    if(is_numeric($prefDetails['suitableCallPref'])) {
                                                                        $preferenceCallTimeArray = array('0'=>'Do not call','1'=>'Anytime','2'=>'Morning', '3'=>'Evening')
                                                                ?>
																<div class="cmsSResult_pdBtm7"><span class="darkgray">Preferred time of Call:</span> <?php echo $preferenceCallTimeArray[$prefDetails['suitableCallPref']]; ?></div>
																<?php
                                                                } */ ?>
																<div class="cmsSResult_pdBtm7"><span class="darkgray">Is in NDNC list:</span> <b><?php echo $row['isNDNC']; ?></b></div>
																<?php	if($prefCount == 0 ) {
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
																			<!--<span style="color:#185100;font-size:16px"><b>V</b></span>erified-->
																		<?php } ?>
																	</div>
																<?php } else { ?>
																	<div class="cmsSResult_pdBtm7" id="userContactDetailDiv_<?php echo $row['userid']; ?>">
				 														<?php if($clientAccess === true) { ?>

				 														<input type="image" id="view_ldb_contact_detail_<?php echo $row['userid']; ?>" style="cursor:pointer" onClick="return showUserContactDetails('<?php echo $row['userid']; ?>');" src="/public/images/vContactDtl.jpg" /> &nbsp;	

				 														<?php } ?>

				 														<?php if($row['mobileverified'] != 0 && $row['emailverified'] != 0) { ?>
																			<!--<span style="color:#185100;font-size:16px"><b>V</b></span>erified-->
																		<?php } ?>
																	</div>
																<?php } } ?>

																<?php if ($prefCount == 0 && !$isResponse) { ?>

																	<?php if($clientAccess === true) { ?>

																	<div id="creditRequired_<?php echo $row['userid']; ?>" style=""><p align="center"><img src="/public/images/ldb_ajax-loader.gif" border="0" /></p></div>

																	<div id="creditConsumed_<?php echo $row['userid']; ?>" style=""><p align="center"><img src="/public/images/ldb_ajax-loader.gif" border="0" /></p></div>

																	<?php } ?>

																	<div class="darkgray mt10">
																	<?php
																	// TODO:-
																	if (is_array($row['ViewCountArray'])) {
																		$ViewCount = 0;
																		foreach($row['ViewCountArray'] as $ViewCountArray) {
																			if(($prefDetails['ExtraFlag'] == 'testprep') && ($prefDetails['DesiredCourse'] == 0) && ($ViewCountArray['Flag'] == 'testprep')) {
																				if($prefDetails['SpecializationPref'][0]['testPrepCourseId'] == $ViewCountArray['DesiredCourse']) {
																					$ViewCount = $ViewCountArray['ViewCount'];
																					break;
																				}
																			} else if(($prefDetails['ExtraFlag'] != 'testprep') && ($prefDetails['DesiredCourse'] != 0) && ($ViewCountArray['Flag'] != 'testprep')) {
																				if($prefDetails['DesiredCourse'] == $ViewCountArray['DesiredCourse']) {
																					$ViewCount = $ViewCountArray['ViewCount'];
																					break;
																				}
																			}
																		}
																		$str = "Viewed: <b><span id='viewed_times_".$row['userid']."'>" . $ViewCount . "</span></b>";
																		if ($ViewCount <= 1) {
																			$str .=  " Time";
																		} else {
																			$str .=  " Times";
																		}
																		echo $str;
																	} else {
																		echo "Viewed: <b><span id='viewed_times_".$row['userid']."'>0</span></b> Time";
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
										<a href="javascript:void(0);" onclick="singlecheckboxEMAIL('rowName_<?php echo $rowCount?>');" >Send Email</a>
										<?php } if(isset($row['ContactData']['sms'])) { ?>
											<span class="redcolor"><img align="absbottom" src="/public/images/cmsSResult_mobile.gif"/> SMSed on <?php echo $row['ContactData']['sms'][0]; ?></span>
										<?php /* } else if($row['isNDNC']=='NO'){ */ } else { ?>
										| <a href="javascript:void(0);" onclick="singlecheckboxSMS('rowName_<?php echo $rowCount?>');" >Send SMS</a>
										<?php } ?>
										</div>

										<?php } ?>

									</div>
								<?php
								$prefCount++;
								}
								if($prefCount>1){
								echo '</div>';
						}?>
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
						echo '<div style="clear:both;"></div><div class="fontSize_18p" style="padding-bottom:7px; padding-left: 20px;">There are no matching students as per your criteria.</div>';
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

								<input type="checkbox" id="checkAllUsers_2" onClick="checkAllUsers(this)"/> <input type="button" value="Send Mail" class="cmsSResult_sendMailBtn" onClick ="sendMail();"/>&nbsp;&nbsp;<input type="button" value="Send SMS" class="cmsSResult_sendSMSBtn" onClick="sendSms();"/>&nbsp;&nbsp;<input type="button" value="&nbsp;" class="cmsSResult_downLoadCSV" onClick="downloadCSV();"/>
								
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
							<div class="float_L" style="width:33%">
								
							</div>
							<div class="float_L" style="width:25%">
								<div style="width:100%">
									<div style="height:22px" class="txt_align_r">
									<span style=""> &nbsp;Students:
									<select class="selectTxt" name="countOffset" id="countOffset_DD2" onChange= "updateCountOffset(this,'startOffSetSearch','countOffsetSearch');">
									<option value="10">10</option>
									<option value="15">15</option>
									<option value="20">20</option>
									<option value="25">25</option>
									<option value="50">50</option>
									<option value="100">100</option>
									</select>
                                    &nbsp; per page
									</span>
									</div>
								</div>
							</div>
							<div class="cmsClear">&nbsp;</div>
						</div>
					</div>
				</div>
			</div>
