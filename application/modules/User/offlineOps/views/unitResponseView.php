<?php
//_p($response);exit;
/* 
 *     A InfoEdge Limited Property
 *     --------------------------- * 
 */
?>

<?php
$userId = $response['userId'];
$isAssigned = isset($response['assigned'])?$response['assigned']:false;
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

    <div style="width:100%;<?php if($isAssigned){echo 'background-color: gainsboro;';}?>">
        <!--Start_PersonSay-->
        <div style="width:100%">
            <div style="margin:0 10px">
                <div style="width:100%">
                    <div style="height:23px"> <b class="fontSize_14p"><?php echo $row['firstname'].' '.$row['lastname']; ?></b> <?php echo $row['gender']!=""? ", ".$row['gender']:""; ?> <?php echo ($row['age'] != "0" && $row['age'] !="")?", ".$row['age']." years":""; ?>
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
                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Email:</span> <b> <?php echo $response['email']; ?></b></div>
                                    <?php if(!empty($response['contact_cell'])) {
                                            if($isAssigned){
                                    ?>
                                                <div id="viewMobileDiv_<?php echo $userId; ?>" class="cmsSResult_pdBtm7"><span class="darkgray">Mobile:</span> <?php echo $response['contact_cell'] .' <i style="color:#ff0000">Verified </i>'; ?>
                                                </div>
                                    <?php
                                            }else{
                                    ?>
                                                <div id="viewMobileDiv_<?php echo $userId; ?>" class="cmsSResult_pdBtm7"><span class="darkgray">Mobile:</span> <a id="viewMobile_<?php echo $userId; ?>" href="javascript:void(0);" class="addDetail-btn" onclick="viewContact(this.id);"> View Contact Details </a><?php //echo $response['contact_cell'] .' <i style="color:#ff0000">Verified </i>'; ?>
                                                </div>
                                    <?php            
                                            }
                                    ?>
                                    
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
                                                
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Student Budget:</span> <i style="color:black"><b><?php echo $budget;?></b> </i>
                                        </div>
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
                                    if(!empty($response['institute_name'])) {
                                        if(isset($response['universityName']) && !empty($response['universityName'])){
                                ?>
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">University Name:</span>  <?php echo $response['universityName']; ?></div>
                                        <?php
                                            if(!empty($response['city_name'])) {
                                        ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">University Location:</span>  <?php echo $response['city_name']; ?></div>
                                        <?php } ?>
                                <?php
                                        }else{
                                ?>
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Institute Name:</span>  <?php echo $response['institute_name']; ?></div>
                                        <?php
                                            if(!empty($response['city_name'])) {
                                        ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Institute Location:</span>  <?php echo $response['city_name']; ?></div>
                                        <?php } ?>
                                <?php   }
                                    } 
                                ?>
                                <?php
                                    if(!empty($response['categoryName'])) {
                                ?>
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Category Name:</span>  <?php echo $response['categoryName']; ?></div>
                                <?php } ?>
                                
                                <?php 
                                     
                                ?>
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
                                    $source = $responseActionViewMapping[$response['action']] ? $responseActionViewMapping[$response['action']] : $response['action'];
                                ?>
                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Date: </span> <b><?php echo $response['submit_date']; ?></b></div>
                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Source: </span> <?php echo $source; ?></div>
                                    <hr/><br/>
                                <?php /*if($row['CurrentCity'] != "") { ?>
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Current Location: </span><?php echo $row['CurrentCity']; ?></div>
                                <?php } ?>
                                <?php
                                    foreach(array_merge($pursuingEducation,$completedEducation) as $value) {
                                        if($value['Title']!="") { ?>
                                            <div class="cmsSResult_pdBtm7"><?php echo $value['Title']; ?> <b><?php echo $value['ValueName']; ?></b><?php echo $value['Value'] != '' ? $value['Value'] : ''; ?></div>
                                    <?php }
                                    } 
                                ?>
                                <?php if($competitiveExam != "") { ?>
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Exams Taken:</span> <?php echo $competitiveExam; ?></div>
                                <?php } */?>     
                                                
                                    <div class="seprtr"></div>
                                    <?php if($searchCriteria != 'abroad'){?>
                                    <a id="addDetails_<?php echo $userId; ?>" href="javascript:void(0);" onclick="addDetails(this.id)" class="addDetail-btn">Add Details</a>
                                    <?php } ?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                
				
                    <div class="cmsClear">&nbsp;</div>
                </div>
                
                         
                        </div>
                    </div>
                    <!--End_PersonInformation-->
                </div>
                <div class="cmsSearch_SepratorLine" style="margin:15px 10px">&nbsp;</div>
