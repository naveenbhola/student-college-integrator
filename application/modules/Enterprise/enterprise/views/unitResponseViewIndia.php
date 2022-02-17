<?php
$userId = $response['userId'];
$row = $resultResponse['userIdDetails'][$userId];
$work_ex = $row['experience'];
$work_ex = getExperienceText($work_ex);	   
$previousEducationLevel = 0;
$highestEducation = 'N.A.';
foreach($row['EducationData'] as $education) {
    $educationLevels = array(
        1 => '10',
        2 => '12',
        3 => 'UG',
        4 => 'PG'
    ); 
    $currentEducationLevel = array_search($education['Level'], $educationLevels);
    if($previousEducationLevel < $currentEducationLevel) {
        $highestEducation = $education['Name'];
        $previousEducationLevel = $currentEducationLevel;
    }
}

$educationInterest=$row["PrefData"][0]["SpecializationPref"][0]["CategoryName"];

$spec = array();
$courseLevel = "";
$prefDetails = $row["PrefData"][0];

$datediff=datediff($prefDetails['TimeOfStart'],$prefDetails['SubmitDate']);

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

$SpecializationName = implode(", ",$specializationArray);

$prefLocationArray= array();
$localityArray = array();
foreach($prefDetails['LocationPref'] as $value) {
    if($value['CityId'] != 0 && $value['CityName'] !=""){
            $localityArray[$value['CityName']];
            foreach ($prefDetails['LocationPref'] as $value1 ) {
                if ($value1['CityId'] == $value['CityId']) {
                    if ($value['LocalityName'] != '') {
                        $localityArray[$value['CityName']][] = $value['LocalityName'];
            break;
                    } else {
                        $localityArray[$value['CityName']][] = '-1';
            break;
                    }
                }
            }
            if(!in_array($value['CityName'], $prefLocationArray))
                array_push($prefLocationArray,$value['CityName']);
    }
    else{
            if($value['StateId'] != 0 && $value['StateName'] != ""){
                    array_push($prefLocationArray,$value['StateName']);
            }
            else{
                    if($value['CountryId'] != 0 && $value['CountryName'] !=""){
                            array_push($prefLocationArray,$value['CountryName']);
                    }
            }
    }
}
$str = '';
$m = 1;
$len = count($localityArray);
foreach ($localityArray as $key=>$value) {
    if ($value[0] != '-1') {
        $str .= $key .' : ' . implode(",&nbsp;<wbr/>",$value);
        $str = str_replace(',&nbsp;<wbr/>-1', '', $str);
    }
    if ($value[0] != '-1' && $m < $len) {
        $str .= ', ';
    }
    $m++;
}

$pursuingEducation = array();
$completedEducation = array();
$competitiveExam = "";
foreach($row['EducationData'] as $value){
    $divRow= array();
    
    /*if($value['Level'] == 12){
        $divRow['Title'] = "<span class='darkgray'>XII Std: </span>";
        $divRow['ValueName'] = $value['Name'];
        $divRow['Value'] = ($value['Marks']!=0)?", ".$value['Marks']." ".$value['MarksType']:'';       
        $divRow['Value'] .= (($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
        $divRow['title'] = "<span class='darkgray'>XII Year:</span>";
        $divRow['completionYear'] = (($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"": date('Y', strtotime($value['Course_CompletionDate'])));
    }

    if($value['Level'] == "UG"){
        if($value['OngoingCompletedFlag'] == 1) {
                $divRow['Title'] = "<span class='darkgray'>Pursuing: </span>";
                $divRow['ValueName'] = $value['Name'];
                $divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
        }
        else{
                $divRow['Title'] = "<span class='darkgray'>UG Details: </span>";
                $divRow['ValueName'] = $value['Name'];
                $divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
                $divRow['Value'].=($value['Marks']!=0)?", ".$value['Marks']." ".$value['MarksType']:'';
        }
        $divRow['title'] = "<span class='darkgray'>Graduation Year:</span>";
	    $divRow['completionYear'] = (($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"": date('Y', strtotime($value['Course_CompletionDate'])));
    }*/
    
    if($value['Level'] == "Competitive exam"){
        //$examObj = \registration\builders\RegistrationBuilder::getCompetitiveExam($value['Name'],$value);
        if($competitiveExam) {
            $competitiveExam .= ', ';
        }
        //$competitiveExam .= $examObj->displayExam(TRUE);
        $competitiveExam.=$value['Name'];
    }

    if($value['OngoingCompletedFlag'] == 1){
        array_push($pursuingEducation, $divRow);
    }
    else{
        array_push($completedEducation , $divRow);
    }
}

?>

<div style="width:100%">
                    <!--Start_PersonSay-->
                    <div style="width:100%">
                        <div style="margin:0 10px">
                            <div style="width:100%">
                                <div style="height:23px"><input allocationId= "<?php echo $response['id'];?>" class="allo_check" type="checkbox" id="rowName_<?php echo $rowCount?>" name="userCheckList[]" value="<?php echo $row['userid']; ?>"/> <b class="fontSize_14p"><?php echo $row['firstname'].' '.$row['lastname']; ?></b> <?php echo $row['gender']!=""? ", ".$row['gender']:""; ?> <?php echo ($row['age'] != "0" && $row['age'] !="")?", ".$row['age']." years":""; ?>
                                </div>
                                </div>                    
                        </div>
                    </div>
                                
                    <!--Start_PersonInformation-->
                    <?php $prefCount=0; ?>
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
                        <div style="margin:0 40px">
                            <div style="width:100%">
                                <div class="float_L" style="width:33%">
                                    <div style="width:100%">
                                        <div style="padding-right:15px">
                                            <div style="width:100%">
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Email:</span> <b style="word-wrap:break-word;"> <?php echo $row['email']; ?></b></div>
                                                <?php if(empty($row['mobile'])) {
                                                        $row['mobile'] = 'N.A.';
                                                    } ?>
                                                <div class="cmsSResult_pdBtm7">
                                                    <span class="darkgray">Mobile:</span><?php if(isset($row['isdCode'])) { echo '+' . $row['isdCode'] . '-'; } ?> <?php echo ($row['mobileverified'] === '1') ? $row['mobile'] .' <i style="color:#ff0000">Verified </i>' : $row['mobile']; ?>
                                                </div>
                                                <div class="cmsSResult_pdBtm7">
                                                    <span class="darkgray">Is in NDNC List:</span> <i style="color:black"><b><?php echo ($row['isNDNC'] ? $row['isNDNC'] : 'N.A.');?></b> </i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="float_L" style="width:33%">
                                    <div style="width:100%">
                                        <div class="cmsSResult_dottedLineVertical">
                                            <div style="width:100%">
                                                <div class="cmsSResult_pdBtm7">
                                                    <span class="darkgray">Response To:</span> 
                                                    <?php 
                                                    global $IVR_Action_Types;
                                                    if(in_array($response['action'], $IVR_Action_Types)){
                                                        $viewedAction = true;
                                                        echo Inst_Viewed_Action_Course;
                                                    } else { ?>
                                                    <?php 
                                                        $viewedAction = false;
                                                        echo ($response['listing_title'] ? $response['listing_title'] : 'N.A.'); ?>
                                                    <?php } ?>
                                                </div>
                                                <hr/><?php if($viewedAction){ ?><span style="color:#286028;font-style:italic;"><strong>Help-</strong> Student has shown interest in your college/university and yet to make course specific action</span><?php } ?>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
				
                                <div class="float_L" style="width:33%">
                                    <div style="width:100%">
                                        <div class="cmsSResult_dottedLineVertical">
                                            <div style="width:100%">
                                                <?php
                                                    global $responseActionViewMapping;
                                                    if(stripos($response['action'], 'client')) {
                                                            $source = 'Mailer Alert';
                                                    } else {        
                                                            $source = $responseActionViewMapping[$response['action']] ? $responseActionViewMapping[$response['action']] : $response['action'];
                                                    }
                                                ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Date: </span> <b><?php echo ($response['submit_date'] ? $response['submit_date'] : 'N.A.'); ?></b></div>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Source: </span> <?php echo ($source ? $source : 'N.A.'); ?></div>
                                                <hr/><br/>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Current Location: </span><?php echo ($row['CurrentCity'] ? $row['CurrentCity'] : 'N.A.'); ?></div>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Current Locality: </span><?php echo ($row['localityName'] ? $row['localityName'] : 'N.A.'); ?></div>
					    <?php foreach(array_merge($pursuingEducation,$completedEducation) as $value) {
                                                if($value['title']!="") { ?>
                                                 <div class="cmsSResult_pdBtm7"><?php echo $value['title']; ?> <b><?php echo ($value['completionYear'] ? $value['completionYear'] : 'N.A.'); ?></b></div>
                                            <?php }
                                            } ?>
                                            <div class="cmsSResult_pdBtm7"><span class="darkgray">Exams Taken:</span> <?php echo ($competitiveExam ? $competitiveExam : 'N.A.'); ?></div>
                                            <div class="cmsSResult_pdBtm7"><span class="darkgray">Work Experience:</span> <?php echo ($work_ex ? $work_ex : 'N.A.'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
				<div style="clear:both"></div>
				<?php   if($response['action'] == "Offline CRM Response") {?>
				<div class="float_L" id="feedback_href_<?php echo $userId; ?>" style="display:block;">
                                <a  href="javascript:void(0);" onclick= "javascript:showlayer('<?php echo "feedback_submit_". $userId ; ?>')"> Give Feedback </a>
				</div>
			    <div style="clear:both"></div>
				<div id="feedback_submit_<?php echo $userId; ?>" style="display:none;"> 
				<form method="post" onsubmit="crm_form(<?php echo $userId; ?>); return false;" id="formsubmit" >
					<input id="crm_clientid_<?php echo $userId; ?>" type="hidden" name="crm_clientid" value= "<?php echo $clientuserid; ?>" />
					<input id="crm_counslarid_<?php echo $userId; ?>" type="hidden" name="crm_counslarid" value= "<?php echo $response['CounsellorId']; ?>" />
					<input id="crm_lead_id_<?php echo $userId; ?>" type="hidden" name="crm_lead_id" value= "<?php echo $userId; ?>" />
					<textarea id="comments_<?php echo $userId; ?>" name="comments" >
					</textarea>
					<br>
						<select id="score" name="month" >
							<option value="Good">Good</option>
							<option value="Poor">Poor</option>
							<option value="Average">Average</option>
						</select>
					<input type="submit" value="Save"/>
					<input type="button" onclick= "javascript:showlayer('<?php echo "feedback_submit_". $userId ; ?>')" value="Cancel"/>
				</form>
				</div>
			<div id="success_msg_<?php echo $userId; ?>" style="display:none">Your feedback has been saved.</div>
				
			<?php } ?>	
				
				<div class="cmsClear">&nbsp;</div>
                            </div>
                           <?php
                             if($source =='Asked_Question_On_Listing' || $source =='Asked_Question_On_Listing_MOB' || $source == 'D_MS_Ask' || $source == 'Asked_Question_On_CCHome' || $source == 'Asked_Question_On_CCHome_MOB'){
                               foreach($qnaInfoForListing as $userIdOfQuestn=>$questnVal){if($userId == $userIdOfQuestn){?> 
                                  <?php foreach($questnVal as $threadId=>$ansVal) {
                                    $ansVal=(array)$ansVal;
                                    
                                    for($i=0;$i<=count($ansVal['answers']);$i++){
                                        if($ansVal['answers'][$i]['AuserId']==$validateuser[0]['userid']){
                                            $ansVal['AuserId']=$ansVal['answers'][$i]['AuserId'];
                                            $ansVal['AmsgTxt']=$ansVal['answers'][$i]['AmsgTxt'];
                                            $ansVal['AmsgId']=$ansVal['answers'][$i]['AmsgId'];
                                        }
                                    }
                                    if(($ansVal['qcourseId'])== $response['listing_type_id'] && (strcmp($response['submit_date'],$ansVal['qcreationDate'])==0)){?>
                                    
                                        <div class="quest"></div>
                                         <p style="margin-left: 10px;float: left;"><?php echo $ansVal['qmsgTxt'];?></p>
                                         
                                                 <div class="clearFix spacer3"></div>
                                                 <?php if($validateuser[0]['userid']!= $userId){ ?>
                                                    <div class="ans-block" id="inlineAnswerFormCntainer<?php echo $threadId; ?>">
                                                        <?php $url = site_url("messageBoard/MsgBoard/replyMsg"); ?>
                                                        <?php if ($ansVal['AmsgTxt']!='' && ($ansVal['qstatus']=='live'||$ansVal['qstatus']=='closed') && (($validateuser[0]['userid']== $ansVal['AuserId']))) {
                                                            $aMsgId=$ansVal['AmsgId']; ?>
                                                        <label>Your Answer</label>
                                                             <div class="ans-details-block">
                                                                                    <div id="answerContainer<?php echo $aMsgId; ?>">
                                                                                             
                                                                                             <div style="background:#e2e2e2;padding:10px">
                                                                                                     <div class="txt_align_r"><a href="javascript:void(0);" onClick="javascript:showEditAnswerForm(<?php echo $aMsgId; ?>);return false;" class="bld">Edit</a></div>
                                                                                                     <div id="msgTxtContent<?php echo $aMsgId; ?>">
                                                                                                             <?php echo $ansVal['AmsgTxt']; ?>
                                                                                                     </div>
                                                                                             </div>
                                                                                     </div>
                                                                 </div>
                                                
                                                         <div style="width:100%;float:left">
						             <div style="display:none;" class="formReplyBrder" id="replyForm<?php echo $aMsgId;?>">
											<?php
											echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$aMsgId,'before' => 'if(validateFields(this) != true){return false;} else {disableReplyFormButton('.$aMsgId.')}','success' => 'javascript:addSubComment('.$aMsgId.',request.responseText,1,\'showAnswerForCMS('.$aMsgId.')\');'));
											?>
											<div>
												<div class="bld" style="padding-bottom:5px" id="messageForReply<?php echo $aMsgId; ?>"><span class="OrgangeFont">Reply to</span> <?php echo $temp['displayname']; ?></div>
												<div style="padding-bottom:5px;display:none;" id="messageForEdit<?php echo $aMsgId; ?>"><span class="fontSize_12p"></span></div>
											</div>
											<div>
												<textarea name="replyText<?php echo $aMsgId; ?>" onkeyup="textKey(this)" class="textboxBorder mar_left_10p" id="replyText<?php echo $aMsgId; ?>" validate="validateStr" caption="Answer" maxlength="2500" minlength="2" required="true" rows="5" style="width:98%;"></textarea>
											</div>
											<div>
												<table width="100%" cellpadding="0" border="0">
												<tr>
												<td><span id="replyText<?php echo $aMsgId; ?>_counter">0</span> out of 2500 character</td>
												<td><div align='right'><span align="right">Make sure your reply follows <a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline');">Community Guidelines</a>&nbsp;</span></div></td>
												</tr>
                                                                                                </table>
											</div>
											<div class="errorPlace" style="display:block"><div class="errorMsg" id="replyText<?php echo $aMsgId; ?>_error"></div></div>
											<div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo $aMsgId; ?>_error"></div></div>
											<input type="hidden" name="threadid<?php echo $aMsgId; ?>" value="<?php echo $aMsgId; ?>" />
											<input type="hidden" name="fromOthers<?php echo $aMsgId; ?>" value="user" />
											<input type="hidden" name="mainAnsId<?php echo $aMsgId; ?>" value="-1" />
											<input type="hidden" name="actionPerformed<?php echo $aMsgId; ?>" id="actionPerformed<?php echo $aMsgId; ?>" value="editAnswer" />
											<div style="padding-top:10px"><input type="Submit" value="Submit" class="submitGlobal" id="submitButton<?php echo $aMsgId; ?>" /> &nbsp; <input type="button" value="Cancel" class="cancelGlobal" onClick="hidereply_form('<?php echo $aMsgId; ?>','ForHiding');showAnswerForCMS('<?php echo $aMsgId; ?>')" /></div>
											</form>
										</div>									
								</div>
                                           
                                                <div class="clearFix"></div>
                                          
                                           <?php }elseif($ansVal['qstatus']=='closed'){
										echo '<span class="normaltxt_11p_blk">This question has been closed for answering.</span>';
									}
                                                elseif($ansVal['qstatus']=='abused'){
										echo '<span class="normaltxt_11p_blk">This question has been closed on account of report abused.</span>';
									}
                                                elseif($ansVal['qstatus']=='deleted'){
										echo '<span class="normaltxt_11p_blk">This question has been deleted by the user.</span>';
									}else{
										$dataArray = array('userGroup' =>'enterprise','threadId' =>$threadId,'callBackFunction' => 'try{ answerPostedSuccessfully('.$threadId.',request.responseText);
                                                                                            var body = document.body,
                                                                                            html = document.documentElement;
                                                                                            var height = Math.max( body.scrollHeight, body.offsetHeight,html.clientHeight, html.scrollHeight, html.offsetHeight );
                                                                                            $(\'dim_bg\').style.height = height + \'px\';
                                                                                             } catch (e) {}','enterpriseView' => 'true');
										$this->load->view('messageBoard/InlineForm',$dataArray);
									}
								?>	
                                 </div>
                                 <?php } ?>
                                 <div class="clearFix spacer15"></div>
                                
                            <?php 
                            }}}} } ?>
                         
                         
                            <div>
                                <?php 
                                    $smsContactedDate = $emailContactedDate = '';
                                    if(isset($resultResponse['contactHistory'][$userId])) {
                                        $contactData = $resultResponse['contactHistory'][$userId];
                                        if(isset($contactData['sms'])) {
                                            $smsContactedDate = $contactData['sms']['contactDate'];
                                        }
                                        if(isset($contactData['email'])) {
                                            $emailContactedDate = $contactData['email']['contactDate'];
                                        }
                                    }
                                ?>
                                <?php
                                    if($emailContactedDate !== '') {
                                ?>
                                <span class="redcolor" id="emailUser_<?php echo $rowCount; ?>"><img align="absbottom" src="/public/images/cmsSResult_mailCheck.gif"/> Emailed on <?php echo $emailContactedDate; ?></span>
                                <?php
                                    } else {
                                ?>
                                <span id="emailUser_<?php echo $rowCount; ?>"><a href="javascript:void(0);" onclick="communicateIndividualUser($('rowName_<?php echo $rowCount; ?>'),'Email');">Send Email</a></span>
                                <?php
                                    }
                                ?>|
                                <?php
                                    if($smsContactedDate !== '') {
                                ?>
                                <span class="redcolor" id="smsUser_<?php echo $rowCount; ?>"><img align="absbottom" src="/public/images/cmsSResult_mobile.gif"/> SMSed on <?php echo $smsContactedDate; ?></span>
                                <?php
                                    } else {
                                ?>
                                <span id="smsUser_<?php echo $rowCount; ?>"><a href="javascript:void(0);" onclick="communicateIndividualUser($('rowName_<?php echo $rowCount; ?>'),'Sms');">Send Sms</a></span>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!--End_PersonInformation-->
                </div>
                <div class="cmsSearch_SepratorLine" style="margin:15px 10px">&nbsp;</div>
