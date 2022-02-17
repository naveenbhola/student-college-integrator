<?php
$userId = $response['userId'];
$row = $resultResponse['userIdDetails'][$userId];
//To check highest education -- A crappy logic which will suck big time.
$previousEducationLevel = 0;
$highestEducation = 'N.A.';
foreach($row['EducationData'] as $education) {
    $educationLevels = array(
        // 0 for Crap (levels we're not catering)
        1 => '10',
        2 => '12',
        3 => 'UG',
        4 => 'PG'
    ); // Product don't want to cater anything apart from these above.
    $currentEducationLevel = array_search($education['Level'], $educationLevels);
    if($previousEducationLevel < $currentEducationLevel) {
        $highestEducation = $education['Name'];
        $previousEducationLevel = $currentEducationLevel;
    }
}

//echo "<pre>".print_r($row,true)."</pre>";
//echo $instituteLocation;

$educationInterest=$row["PrefData"][0]["SpecializationPref"][0]["CategoryName"];

$spec = array();
$courseLevel = "";
$prefDetails = $row["PrefData"][0];

$datediff=datediff($prefDetails['TimeOfStart'],$prefDetails['SubmitDate']);

foreach($prefDetails["SpecializationPref"] as $spec_details){
    $spec[] = $spec_details["SpecializationName"];
    $courseLevel = $spec_details['CourseLevel1'];
    if($courseLevel == 'UG') {
        $courseLevel = 'Bachelors';
    }
    if($courseLevel == 'PG') {
        $courseLevel = 'Masters';
    }
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

$SpecializationName = implode(", ",$specializationArray);


$modeArray = array();
if($prefDetails['ModeOfEducationFullTime'] == 'yes')
    array_push($modeArray, "Full Time");
if($prefDetails['ModeOfEducationPartTime'] == 'yes')
    array_push($modeArray, "Part Time");
if($prefDetails['ModeOfEducationDistance'] == 'yes')
    array_push($modeArray, "Distance");
$mode = implode(", ",$modeArray);

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

$degreePrefArray = array();
if($prefDetails['DegreePrefAny'] == 'yes')
    array_push($degreePrefArray, "Any");
if($prefDetails['DegreePrefUGC'] == 'yes')
    array_push($degreePrefArray, "UGC approved");
if($prefDetails['DegreePrefAICTE'] == 'yes')
    array_push($degreePrefArray, "AICTE approved");
if($prefDetails['DegreePrefInternational'] == 'yes')
    array_push($degreePrefArray, "Internatonal");


$pursuingEducation = array();
$completedEducation = array();
$competitiveExam = "";
foreach($row['EducationData'] as $value){
    $divRow= array();

    if($value['Level'] == 10){
        $divRow['Title'] = "<span class='darkgray'>X Std: </span>";
        $divRow['Value'] = $value['Name']. ", ".$value['Marks']." ".$value['MarksType'];
    }

    if($value['Level'] == 12){
        $divRow['Title'] = "<span class='darkgray'>XII Std: </span>";
        $divRow['ValueName'] = $value['Name'];
        $divRow['Value'] = ($value['Marks']!=0)?", ".$value['Marks']." ".$value['MarksType']:'';
        $completiondate = $value['Course_CompletionDate'];
        $completiondate = explode(" ", $completiondate);
        $divRow['Value'] .= (($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$completiondate[1].") ");
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
    }

    if($value['Level'] == "PG"){
            if($value['OngoingCompletedFlag'] == 1) {
                    $divRow['Title'] = "<span class='darkgray'>Pursuing: </span>";
                    $divRow['ValueName'] = $value['Name'];
                    $divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
            }
            else {
                    $divRow['Title'] = "<span class='darkgray'>PG Details: </span>";
                    $divRow['ValueName'] = $value['Name'];
                    $divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
                    $divRow['Value'].=($value['Marks']!=0)?", ".$value['Marks']." ".$value['MarksType']:'';
            }
    }

    if($value['Level'] == "Competitive exam"){
        $examObj = \registration\builders\RegistrationBuilder::getCompetitiveExam($value['Name'],$value);
        if($competitiveExam) {
            $competitiveExam .= ', ';
        }
        $competitiveExam .= $examObj->displayExam(TRUE);
    }

    if($value['OngoingCompletedFlag'] == 1){
        array_push($pursuingEducation, $divRow);
    }
    else{
        array_push($completedEducation , $divRow);
    }
}

$prefCountryArray= array();
foreach($prefDetails['LocationPref'] as $value) {
    if($value['CountryId'] != 0 && $value['CountryName'] !=""){
        if(!in_array($value['CountryName'],$prefCountryArray))
            array_push($prefCountryArray,$value['CountryName']);
    }
}

$sourcesFund = "";
if($prefDetails['UserFundsOwn'] == "yes" || $prefDetails['UserFundsNone'] == "yes" || $prefDetails['UserFundsBank'] == "yes") {
    $sources = array();
    $sources[] = $prefDetails['UserFundsOwn'] == "yes" ? 'Own ' : '';
    $sources[] = $prefDetails['UserFundsBank'] == "yes" ? 'Bank Loan ' : '';
    $sources[] = $prefDetails['UserFundsNone'] == "yes" ? 'Others ' : '';
    $sourcesFund = trim(implode(', ', $sources),', ');
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
                                                <?php if(!empty($row['mobile'])) { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Mobile:</span><?php if(isset($row['isdCode'])) { echo '+' . $row['isdCode'] . '-'; } ?> <?php echo ($row['mobileverified'] === '1') ? $row['mobile'] .' <i style="color:#ff0000">Verified </i>' : $row['mobile']; ?>
                                                </div>
                                                <?php } ?>
                                                <?php if(!empty($row['isNDNC'])) { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Is in NDNC List:</span> <i style="color:black"><b><?php echo $row['isNDNC'];?></b> </i>
                                                </div>
                                                <?php } ?>
                                                
                                                <?php if(!empty($prefDetails['program_budget'])) {
                                                    $budget = (int)$prefDetails['program_budget'];
                                                    global $budgetToTextArray;
                                                    if(key_exists($budget, $budgetToTextArray)) {
                                                            $budget = $budgetToTextArray[$budget];
                                                    } ?>
                                                
                                                <!--<div class="cmsSResult_pdBtm7"><span class="darkgray">Student Budget:</span> <i style="color:black"><b>< ?php echo $budget;?></b> </i>-->
                                                <!--</div>-->
                                                <?php } ?>
                                                
                                                <?php if(!empty($row['passport'])) { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Student Passport:</span> <i style="color:black"><b><?php echo ucfirst($row['passport']);?></b> </i>
                                                </div>
                                                <?php } ?>
                                                
                                                <?php if(!empty($row['landline'])) { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Landline:</span> <?php echo $row['landline']; ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
				
                                <div class="float_L" style="width:33%">
                                    <div style="width:100%">
                                        <div class="cmsSResult_dottedLineVertical">
                                            <div style="width:100%">
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Response To:</span> <?php echo $response['listing_title']; ?></div>
                                                <hr/><br/>
                                                <?php
                                                    if(!empty($highestEducation)) {
                                                ?>
                                              <!--  <div class="cmsSResult_pdBtm7"><span class="darkgray">Highest Education:</span> <b> <?php echo $highestEducation; ?></b></div> -->
                                                <?php } ?>
                                                <?php 
                                                    if($instituteLocation == "india"){
                                                ?>
                                                
                                                <?php if(!empty($educationInterest)) { ?>
                                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Field of Interest: </span><?php echo $educationInterest; ?></div>
                                                <?php } ?>
                                              
                                              <?php if(count($courseNameArray) > 0) { ?>
                                              <div class="cmsSResult_pdBtm7"><span class="darkgray">Desired Course Level:</span> <b><?php echo implode(",&nbsp;<wbr/>",$courseNameArray); ?></b></div>
                                              <?php } ?>
                                              
                                              <?php
                                                    if(!empty($SpecializationName) && strtolower(trim($SpecializationName)) != "all") {
                                                ?>
                                              <div class="cmsSResult_pdBtm7"><span class="darkgray">Specialization: </span><?php echo $SpecializationName; ?></div>
                                                <?php } ?>  
                                              
                                              <?php
                                                    if(!empty($mode)) {
                                                ?>
                                              <div class="cmsSResult_pdBtm7"><span class="darkgray">Mode: </span><?php echo $mode; ?></div>
                                              <?php } ?>  
                                              
                                              <?php if(empty($str) && count($prefLocationArray) > 0) { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Preferred Locations: </span> <?php echo implode(",&nbsp;<wbr/>",$prefLocationArray); ?></div>
                                              <?php } ?>
                                              
                                              <?php if(!empty($str)) { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Preferred Localities: </span> <?php echo $str; ?></div>
                                              <?php } ?>
                                                
                                              <?php if(count($degreePrefArray) > 0) { ?>
																<div class="cmsSResult_pdBtm7"><span class="darkgray">Degree Preference: </span> <?php echo implode(",&nbsp;<wbr/>",$degreePrefArray); ?></div>
                                                <?php } ?>  
                                                <?php if($prefDetails['TimeOfStart'] != "" ) { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Plan to Start:</span> <?php /*echo ($prefDetails['YearOfStart']!='0000')?$prefDetails['YearOfStart']:"Not Sure"; */?> <?php  echo ($prefDetails['YearOfStart']!='0000')?(($datediff!=0)?"Within ".$datediff:"Immediately"):"Not Sure"; ?><?php echo " (as on ".$row['CreationDate']." )" ?></div>                           
                                                <?php } ?>                                                       
                                              <?php
                                                    }else{
                                                        if(!empty($prefCountryArray)) {
                                              ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Preferred Country: </span><?php echo implode(', ',$prefCountryArray); ?></div>
                                                <?php } ?>
                                                         
                                                 <?php
                                                    if(!empty($educationInterest)) {
                                                        if(strtolower(trim($educationInterest)) != "all"){
                                                            echo '<div class="cmsSResult_pdBtm7"><span class="darkgray">Field Of Interest: </span>'.$educationInterest.'</div>';
                                                        }elseif(implode(",&nbsp;<wbr/>",$courseNameArray) == 'MBA' || implode(",&nbsp;<wbr/>",$courseNameArray) == 'BE/Btech' || implode(",&nbsp;<wbr/>",$courseNameArray) == 'MS'){
                                                            echo '<div class="cmsSResult_pdBtm7"><span class="darkgray">Field Of Interest: </span>'.implode(",&nbsp;<wbr/>",$courseNameArray).'</div>';
                                                        }
                                                    } ?>
                                                
                                                <?php if(!empty($courseLevel)) { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Desired Course Level: </span><?php echo $courseLevel; ?></div>
                                                <?php } ?>
                                                <?php if($prefDetails['TimeOfStart'] != "" ) { ?>
                                              <div class="cmsSResult_pdBtm7"><span class="darkgray">Plan to Start:</span> <?php echo ($prefDetails['YearOfStart']!='0000')?(($datediff==0)?"Current Year":(($datediff==1)?"Next Year":"Not Sure")):"Not Sure"; ?><?php //echo " (as on ".$row['CreationDate']." )"?></div>                                              
                                              <?php } ?>
                                            <?php if(!empty($sourcesFund)){ ?>
                                            <div class="cmsSResult_pdBtm7"><span class="darkgray">Source(s) of Funding:</span> <?php echo $sourcesFund; ?></div>
                                            <?php } ?>
                                            <?php /*
                                                if(is_numeric($prefDetails['suitableCallPref'])) {
                                                    $preferenceCallTimeArray = array('0'=>'Do not call','1'=>'Anytime','2'=>'Morning', '3'=>'Evening');
                                            ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Preferred time of Call:</span> <?php echo $preferenceCallTimeArray[$prefDetails['suitableCallPref']]; ?></div>
                                                <?php } */ ?>
                                              
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
													global $responseActionViewMapping;
													if(stripos($response['action'], 'client')) {
														$source = 'Mailer Alert';
													} else {	
														$source = $responseActionViewMapping[$response['action']] ? $responseActionViewMapping[$response['action']] : $response['action'];
												    }
                                                ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Date: </span> <b><?php echo $response['submit_date']; ?></b></div>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Source: </span> <?php echo $source; ?></div>
                                                <hr/><br/>
                                                <?php if($row['CurrentCity'] != "") { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Current Location: </span><?php echo $row['CurrentCity']; ?></div>
						<?php } ?>
                                                
                                                <?php /* if($row['experience'] > 1) { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Work Experience: </span><?php echo $row['experience']; ?> Years</div>
                                                <?php } else if($row['experience'] == 1) { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Work Experience: </span><?php echo $row['experience']; ?> Year</div>
                                                <?php } else if($row['experience']==="0"){ ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Work Experience: </span>Less than 1 Year</div>
                                                <?php } else if($row['experience']==-1){?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Work Experience: </span> No Experience</div>
                                                <?php } else { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Work Experience: </span>N.A.</div>
                                                <?php } */ ?>
                                                
                                                
                                            <?php
                                            foreach(array_merge($pursuingEducation,$completedEducation) as $value) {
                                                if($value['Title']!="") { ?>
                                                    <div class="cmsSResult_pdBtm7"><?php echo $value['Title']; ?> <b><?php echo $value['ValueName']; ?></b><?php echo $value['Value'] != '' ? $value['Value'] : ''; ?></div>
                                            <?php }} ?>
                                            <?php if($competitiveExam != "") { ?>
						<div class="cmsSResult_pdBtm7"><span class="darkgray">Exams Taken:</span> <?php echo $competitiveExam; ?></div>
                                            <?php } ?>        
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
                           <!----by prGYa--> 
                           <?php
                             if($source =='Asked_Question_On_Listing' || $source == 'Asked_Question_On_Listing_MOB' || $source == 'D_MS_Ask' || $source == 'Asked_Question_On_CCHome' || $source == 'Asked_Question_On_CCHome_MOB'){
                               foreach($qnaInfoForListing as $userIdOfQuestn=>$questnVal){if($userId == $userIdOfQuestn){?> 
                                  <?php foreach($questnVal as $threadId=>$ansVal) {
                                    $ansVal=(array)$ansVal;  //_p($ansVal);echo 'cc'.count($ansVal['answers']);
                                    
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
