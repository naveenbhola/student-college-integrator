<!DOCTYPE html>
<!--
    A InfoEdge Limited Property
    ---------------------------
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Fresh Responses</title>
        <?php 
            $headerComponents = array(
                'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style', 'offlineResponses', 'registration', 'studyAbroadCommon'),
                'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','ajax-api','ana_common'),//pragya
                'displayname'=> (isset($displayname)?$displayname:""),
                'tabName'   =>  '',
                'taburl' => site_url('offlineOps/index'),
                'metaKeywords'  =>''
                );
            $this->load->view('enterprise/headerCMS', $headerComponents);
        ?>
    </head>
    <body>
        <div id="content-child-wrap">
            <div id="smart-content">
                <h2>Welcome <strong><?php echo trim($displayname); ?> </strong></h2>
            </div><br>
            <br>

        
            <div id="searchFormSubContents" style="display:none">
                <input type="hidden" id="startOffSetSearch" name="startOffSetSearch" value="<?php echo isset($startOffset)?$startOffset:'0';?>"/>
                <input type="hidden" id="countOffsetSearch" name="countOffsetSearch" value="<?php echo isset($countOffset)?$countOffset:'10';?>"/>
                <input type="hidden" id="methodName" name="methodName" value="getResponsesForCriteria"/>
                <input type="hidden" id="searchCriteria" name="searchCriteria" value="<?php echo isset($searchCriteria)?$searchCriteria:'both';?>"/>
            </div>
<div style="width:100%">
    <div style="font-size:18px">Shiksha Operator Completed Leads Viewer</div>
    <div class="lineSpace_10">&nbsp;</div>
    <div style="width:100%">
        <?php if(isset($numrows)) {
                $studentCount = 'Only 1 lead';
                if($numrows > 1) {
                    $studentCount = 'Total <span class="OrgangeFont">'. $numrows .'</span> leads';
                }
                if($numrows == 0) {
                    $studentCount = 'No lead';
                }
        ?>
            <div>
                <div style="width:70%">
                    <div style="width:100%">
                        <div class="fontSize_1xi68p" style="padding-bottom:7px"><span id="resultCount" style="font-size:18px"><?php echo $studentCount; ?> found</span></div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    
    <div style="width:100%;<?php if(isset($numrows) && $numrows <1) { echo 'display:none';} ?>">
                    
                    <div class="cmsSResult_pagingBg">
                	<div style="margin:0 10px">

                            <div style="line-height:6px">&nbsp;</div>
                            <div style="width:100%">
                        	<div class="float_L">
                                    <div style="width:100%">
                                        <div style="height:22px">
                                            <span>
                                                <span class="pagingID" id="paginationPlace1"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="float_R" style="width:25%">
                                    <div style="width:100%">
                                        <div style="height:22px" class="txt_align_r">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="cmsClear">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lineSpace_10">&nbsp;</div>
                
    <!--Start_DateRow_1------------------------------------------>
<?php if($numrows != 0){  ?>
<?php $rowCount=0; ?>
<?php foreach($resultResponse['result'] as $row) { ?>
    <div style="width:100%">
        <!--Start_PersonSay-->
        <div style="width:100%">
            <div style="margin:0 10px">
                <div style="width:100%">
                    <div style="height:23px">
                        <b class="fontSize_14p"><?php echo $row['firstname'].' '.$row['lastname']; ?></b> <?php echo $row['gender']!=""? ", ".$row['gender']:""; ?> <?php echo ($row['age'] != "0" && $row['age'] !="")?", ".$row['age']." years":""; ?>
                    </div>
                </div>
            </div>
        </div>
        <!--Start_PersonInformation-->
        <?php foreach($row['PrefData']  as $prefDetails) { ?>
        
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
                                            $courseNameArray = array();
                                            $specializationArray = array();
                                            foreach($prefDetails['SpecializationPref'] as $value){
                                                if (isset($value['blogTitle'])) {
                                                    array_push($courseNameArray,$value['blogTitle']);
                                                } else {
                                                    if(!in_array($value['CourseName'], $courseNameArray)){
                                                        array_push($courseNameArray,$value['CourseName']);
                                                    }
                                                    if(!in_array($value['SpecializationName'],$specializationArray)){
                                                        array_push($specializationArray,$value['SpecializationName']);
                                                    }
                                                }
                                            }
                                        ?>
                                        <?php if(count($courseNameArray) > 0) { ?>
                                            <div class="cmsSResult_pdBtm7"><span class="darkgray">Course:</span> <b><?php echo implode(",&nbsp;<wbr/>",$courseNameArray); ?></b></div>
                                        <?php } ?>
                                        <?php if ($course_name != 'IT Courses') { ?>
                                            <?php if(count($specializationArray)>0) { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Specialization:</span> <?php echo implode(",&nbsp;<wbr/>",$specializationArray); ?></div>
                                            <?php } ?>
                                        <?php } ?>
                                        </div>
                                        <?php
                                            $modeArray = array();
                                            if($prefDetails['ModeOfEducationFullTime'] == 'yes')
                                                array_push($modeArray, "Full Time");
                                            if($prefDetails['ModeOfEducationPartTime'] == 'yes')
                                                array_push($modeArray, "Part Time");
                                            if($prefDetails['ModeOfEducationDistance'] == 'yes')
                                                array_push($modeArray, "Distance");
                                        ?>
                                        <?php if(count($modeArray) > 0) { ?>
                                            <div class="cmsSResult_pdBtm7"><span class="darkgray">Mode:</span> <?php echo implode(",&nbsp;<wbr/>",$modeArray); ?></div>
                                        <?php } ?>
                                        <?php
                                            $degreePrefArray = array();
                                            if($prefDetails['DegreePrefAny'] == 'yes')
                                                    array_push($degreePrefArray, "Any");
                                            if($prefDetails['DegreePrefUGC'] == 'yes')
                                                    array_push($degreePrefArray, "UGC approved");
                                            if($prefDetails['DegreePrefAICTE'] == 'yes')
                                                    array_push($degreePrefArray, "AICTE approved");
                                            if($prefDetails['DegreePrefInternational'] == 'yes')
                                                    array_push($degreePrefArray, "Internatonal");
                                        ?>
                                        <?php if(count($degreePrefArray) > 0) { ?>
                                            <div class="cmsSResult_pdBtm7"><span class="darkgray">Degree Preference:</span> <?php echo implode(",&nbsp;<wbr/>",$degreePrefArray); ?></div>
                                        <?php } ?>
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
                                            <div class="cmsSResult_pdBtm7"><span class="darkgray">Plan to Start:</span> <?php echo ($prefDetails['YearOfStart']!='0000')?(($datediff!=0)?"Within ".$datediff:"Immediately"):"Not Sure"; ?><?php echo " (as on ".date('d-M-Y',strtotime($prefDetails['SubmitDate']))." )"?></div>
                                        <?php } ?>
                                        <?php if ($course_name != 'IT Courses') { ?>
                                            <?php if($displayArray['DesiredCourse']!='Distance/Correspondence MBA' || true) { ?>
                                                <?php if($row['experience'] >=1) { ?>
                                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Work Experience:</span> <b><?php echo $row['experience']; ?> Years</b></div>
                                                <?php }
                                                else if($row['experience']==="0"){ ?>
                                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Work Experience:</span> <b>Less than 1 Years</b></div>
                                                <?php }
                                                else if($row['experience']==-1){?>
                                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Work Experience:</span> <b>No Experience</b></div>
                                                <?php } else { ?>
                                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Work Experience:</span> <b>N.A.</b></div>
                                                <?php }
                                                }
                                            } ?>
                                        <?php
                                            $pursuingEducation = array();
                                            $completedEducation = array();
                                            $competitiveExam = "";
                                            foreach($row['EducationData'] as $value){
                                                $divRow= array();
                                                if($value['Level'] == 10){
                                                    $divRow['Title'] = "<span class='darkgray'>X Std</span>";
                                                    $divRow['Value'] = $value['Name']. " - ".$value['Marks']." ".$value['MarksType'];
                                                }
                                                if($value['Level'] == 12){
                                                    $divRow['Title'] = "<span class='darkgray'>XII Std</span>";
                                                    $divRow['ValueName'] = $value['Name'];
                                                    $divRow['Value'] = ($value['Marks']!=0)?$value['Marks']." ".$value['MarksType']:'';
                                                    $completiondate = $value['Course_CompletionDate'];
                                                    $completiondate = explode(" ", $completiondate);
                                                    $divRow['Value'] .= (($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$completiondate[1].") ");
                                                }
                                                if($value['Level'] == "UG"){
                                                    if($value['OngoingCompletedFlag'] == 1) {
                                                        $divRow['Title'] = "<span class='darkgray'>Pursuing</span>";
                                                        $divRow['ValueName'] = $value['Name'];
                                                        $divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
                                                    }
                                                    else{
                                                        $divRow['Title'] = "<span class='darkgray'>UG Details</span>";
                                                        $divRow['ValueName'] = $value['Name'];
                                                        $divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
                                                        $divRow['Value'].=($value['Marks']!=0)?" - ".$value['Marks']." ".$value['MarksType']:'';
                                                    }
                                                }
                                                if($value['Level'] == "PG"){
                                                    if($value['OngoingCompletedFlag'] == 1) {
                                                        $divRow['Title'] = "<span class='darkgray'>Pursuing</span>";
                                                        $divRow['ValueName'] = $value['Name'];
                                                        $divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['CourseCompletionDate'].") ");
                                                    }
                                                    else {
                                                        $divRow['Title'] = "<span class='darkgray'>PG Details</span>";
                                                        $divRow['ValueName'] = $value['Name'];
                                                        $divRow['Value'] = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
                                                        $divRow['Value'].=($value['Marks']!=0)?" - ".$value['Marks']." ".$value['MarksType']:'';
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
                                        ?>
                                        <?php
                                            foreach(array_merge($pursuingEducation,$completedEducation) as $value) {
                                                if($value['Title']!="") { ?>
                                                    <div class="cmsSResult_pdBtm7"><?php echo $value['Title']; ?> <b><?php echo $value['ValueName']; ?></b><?php echo $value['Value'] != '' ? ': '.$value['Value'] : ''; ?></div>
                                                <?php }
                                            } ?>
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
                                            <?php if($row['CurrentCity'] != "") { ?>
                                                <div class="cmsSResult_pdBtm7"><span class="darkgray">Current Location:</span> <b> <?php echo $row['CurrentCity']; ?></b></div>
                                            <?php } ?>
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
                                            <?php
                                            // echo "<pre>";print_r($prefDetails['LocationPref']);echo "</pre>";
                                            if ($course_name == 'IT Courses' || $course_name == 'Distance/Correspondence MBA' || $course_name == 'Online MBA' || $course_name == 'Part-time MBA' || $course_name == 'Certifications'){
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
                                                $str = '';
                                                $m = 1;
                                                $len = count($localityArray);
                                                foreach ($localityArray as $key=>$value) {
                                                if ($value[0] == '-1') {
                                                $str .= ' Anywhere in '.$key.' ';
                                                } else {
                                                $str .= $key .' : ' . implode(",&nbsp;<wbr/>",$value);
                                                $str = str_replace(',&nbsp;<wbr/>-1', '', $str);
                                                }
                                                if ($m < $len) {
                                                $str .= ' ,';
                                                }
                                                $m++;
                                                }
    //echo "<pre>";print_r($localityArray);echo "</pre>";
    if (strlen($str) > 0) {
    ?>
    <div class="cmsSResult_pdBtm7"><span class="darkgray">Preferred Localities:</span> <?php echo $str; ?></div>
    <?php
                                        }
                                            } else {
if(count($prefLocationArray) > 0) { ?>
<div class="cmsSResult_pdBtm7"><span class="darkgray">Preferred Locations:</span> <?php echo implode(",&nbsp;<wbr/>",$prefLocationArray); ?></div>
<?php } } ?>
                                                                                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Registered on:</span> <?php echo $row['CreationDate']; ?></div>
                                                                                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Last active on:</span> <?php echo $row['LastLoginDate']; ?></div>
                                                                                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Is in NDNC list:</span> <b><?php echo $row['isNDNC']; ?></b></div>
                                                                                                    <?php
                                                                                                            if($prefCount == 0 ) {

                                                                                                                     ?>
                                                                                                            <div id="userContactDetailDiv_<?php echo $row['userid']; ?>">
                                                                                                                    <div class="cmsSResult_pdBtm7">Mobile: <?php echo $row['mobile']; ?></div>
                                                                                                                    <?php if($row['landline'] != "") { ?>
                                                                                                                            <div class="cmsSResult_pdBtm7">Landline: <?php echo $row['landline']; ?></div>
                                                                                                                    <?php } ?>
                                                                                                                    <div class="cmsSResult_pdBtm7" title="<?php echo $row['email'];?>">Email: <?php echo strlen($row['email'])>25?substr($row['email'],0,22)."...":$row['email']; ?></div>
                                                                                                                    <?php if($row['mobileverified'] != 0 && $row['emailverified'] != 0) { ?>
                                                                                                                            <!--<span style="color:#185100;font-size:16px"><b>V</b></span>erified-->
                                                                                                                    <?php } ?>
                                                                                                            </div>
                                                                                                    <?php  } ?>

</div>													</div>
                                                                                    </div>
                                                                            </div>
                                                                    </div>
                                                                    <div class="cmsClear">&nbsp;</div>

                                                            </div>
                                                            <!--End_100%Width--->
                                                    </div>
                                                    <!--End_Margin40-->

                                            </div>
                                    <?php
                                    }
                                    ?>
                                    
                    
                    <!--End_PersonInformation-->
            
            <div class="cmsSearch_SepratorLine" style="margin:15px 10px">&nbsp;</div>
    <?php
    $rowCount++;
    } ?>
<?php }else{ 
    echo '<h1>No leads found</h1>'; 
}
?>
    <!--End_DateRow_1------------------------------------------>
</div>
            
            <!--Start_NavigationBar-->
                <div style="width:100%">
                    <div class="cmsSResult_pagingBg">
                	<div style="margin:0 10px">
                            <div style="line-height:6px">&nbsp;</div>
                            <div style="width:100%">
                        	<div class="float_L" style="width:41%">
                                    <div style="width:100%">
                                        <div style="height:22px">
                                            <span>
                                                <span class="pagingID" id="paginationPlace2"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="cmsClear">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
                        <div class="lineSpace_10">&nbsp;</div>
                <!--End_NavigationBar-->
        
        <?php
            $this->load->view('enterprise/footer');
        ?>
        
        </div>
        <script>
            function getResponsesForCriteria() {
                var startOffset = document.getElementById('startOffSetSearch').value;
                var countOffset = document.getElementById('countOffsetSearch').value;
                var url = '/offlineOps/index/myleads/';
                var timeInterval = 7;//document.getElementById('changeRegdateFilter_DD1').value;
                url +=  'both/'+timeInterval +'/'+ startOffset +'/'+ countOffset;
                location.replace(url);
            }
    
            doPagination('<?php echo $numrows; ?>','startOffSetSearch','countOffsetSearch','paginationPlace1','paginationPlace2','methodName',4);
        </script>
    </body>
</html>