<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMS Control Page</title>
<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle'),
	'js'	=> array('common','enterprise','listing'),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=>	'Alum Speak',
	'taburl' => site_url('alumni/AlumniSpeakFeedBack/showAlumFeedBacks'),
	'metaKeywords'	=>''
);
    $this->load->view('enterprise/headerCMS', $headerComponents);
    $this->load->view('enterprise/cmsTabs',$cmsUserInfo);
?>
</head>
<body>
	<div id="dataLoaderPanel" style="position:absolute;display:none">
		<img src="/public/images/loader.gif"/>
	</div>
	<div class="mar_full_10p" style="height:100%">
        <div style="margin-left:1px">
            <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Alumni Speak DashBoard</div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="grayLine"></div>
            <div class="lineSpace_20">&nbsp;</div>
            <center>
            <div>
                <form novalidate action="/alumni/AlumniSpeakFeedBack/showAlumFeedBacks" onsubmit="return validateData();">
                <input type="text" name="criteriaValue" id="criteriaValue" value="<?php echo $criteriaValue; ?>"/ maxlength="100">&nbsp;		
                <select name="criteriaName" id="criteriaName">
                    <option value="institute_id" <?php echo $criteriaName == 'institute_id' ? 'selected' : '';?>>Institute Id</option>
                    <option value="institute_name" <?php echo $criteriaName == 'institute_name' ? 'selected' : '';?>>Institute Name</option>
                </select>&nbsp;<?php		
		// Only show the course drop down when Institute Id search is done..
		if(isset($instituteCourses) && count($instituteCourses)) {	?>
		<input type="hidden" name="insId" id="insId" value="<?=$criteriaValue?>" >
		<input type="hidden" name="isCourseSelected" id="isCourseSelected" value="0" >
		<select style="width:180px" id="institute_courses" name="institute_courses">
			<option value=''>Select Course</option>	
		    <?php
		    foreach($instituteCourses as $courseId => $courseName) { ?>
			    <option value="<?php echo $courseId; ?>" <?php if($courseId == $selectedCourseId) { echo 'selected'; }?>><?php echo $courseName; ?></option>
		    <?php }
		    ?><option value="-1" <?php if($selectedCourseId == -1) { echo 'selected'; }?>>Others</option>
		</select><?php 	} ?>
                <input type="submit" value="Search"/>
					
                </form>
            </div>
            <div class="lineSpace_20">&nbsp;</div>
            <div class="lineSpace_20">&nbsp;</div>
            <div class="fontSize_14p bld">
                <?php 
                switch(count($feedbacks)) {
                    case '1' :
                        echo '1 institute found';
                        break;
                    case '0':
                            // echo 'No institute found';
			    echo 'No feedback found for the searched criteria.<br/><br/><br/><br/>';
                            break;
                    default:
                                echo count($feedbacks) .' institutes found';
                }
                ?>
            </div>
            <div id="unPublishedCounter" class="fontSize_14p bld"></div>
            <div class="lineSpace_20">&nbsp;</div>
                <?php
                    $sortUpImg='<img src="/public/images/arrow_up.png" align="absmiddle" height="16" width="16" border="0" alt="Descending"/>';
                    $sortDownImg='<img src="/public/images/arrow_down.png" align="absmiddle" height="16" width="16" border="0" alt="Asccending"/>';
                    $insituteIdSortImage = '';
                    $insituteIdSortOrder = 'up';
                    if($sort == 'institute_id') {
                        $insituteIdSortImage = $sortOrder == 'desc' ? $sortUpImg : $sortDownImg;
                        $insituteIdSortOrder = $sortOrder == 'asc' ? 'up' : 'down';
                    }
                    $insituteNameSortImage = '';
                    $insituteNameSortOrder = 'up';
                    if($sort == 'institute_name') {
                        $insituteNameSortImage = $sortOrder == 'desc' ? $sortUpImg : $sortDownImg;
                        $insituteNameSortOrder = $sortOrder == 'asc' ? 'up' : 'down';
                    }
                    $totalSortImage = '';
                    $totalSortOrder = 'up';
                    if($sort == 'total') {
                        $totalSortImage = $sortOrder == 'desc' ? $sortUpImg : $sortDownImg;
                        $totalSortOrder = $sortOrder == 'asc' ? 'up': 'down';
                    }
                    $unPublishedSortImage = '';
                    $unPublishedSortOrder = 'up';
                    if($sort == 'unpublished') {
                        $unPublishedSortImage = $sortOrder == 'desc' ? $sortUpImg : $sortDownImg;
                        $unPublishedSortOrder = $sortOrder == 'asc' ? 'up' : 'down';
                    }
                    $lastRecievedSortImage = '';
                    $lastRecievedSortOrder = 'up';
                    if($sort == 'lastRecieved') {
                        $lastRecievedSortImage = $sortOrder == 'desc' ? $sortUpImg : $sortDownImg;
                        $lastRecievedSortOrder = $sortOrder == 'asc' ? 'up' : 'down';
                    }
                    $sortUrl = "pageNum=$pageNum&numRecords=$numRecords&criteriaName=$criteriaName&criteriaValue=$criteriaValue";
                ?>
            <div style="display:<?php echo count($feedbacks) > 0 ? '' : 'none'; ?>">
                <div class="float_L brd <?php echo $sort == 'institute_id' ? 'bld' :'' ; ?>" style="width:100px;height:50px;text-align:center;padding-top:5px;"><a href="/alumni/AlumniSpeakFeedBack/showAlumFeedBacks?sortBy=institute_id&sortOrder=<?php echo $insituteIdSortOrder; ?>&<?php echo $sortUrl; ?>">Institute Id</a><?php echo $insituteIdSortImage; ?></div>
                <div class="float_L brd" style="width:470px;height:50px;text-align:center;padding-top:5px;"><a href="/alumni/AlumniSpeakFeedBack/showAlumFeedBacks?sortBy=institute_name&sortOrder=<?php echo $insituteNameSortOrder; ?>&<?php echo $sortUrl; ?>">Institute Name</a><?php echo $insituteNameSortImage; ?></div>
                <div class="float_L brd" style="width:100px;height:50px;text-align:center;padding-top:5px;"><a href="/alumni/AlumniSpeakFeedBack/showAlumFeedBacks?sortBy=total&sortOrder=<?php echo $totalSortOrder; ?>&<?php echo $sortUrl; ?>">Total Reviews</a><?php echo $totalSortImage; ?></div>
                <div class="float_L brd" style="width:145px;height:50px;text-align:center;padding-top:5px;"><a href="/alumni/AlumniSpeakFeedBack/showAlumFeedBacks?sortBy=unpublished&sortOrder=<?php echo $unPublishedSortOrder; ?>&<?php echo $sortUrl; ?>">Total Reviews Awaiting Moderation</a><?php echo $unPublishedSortImage; ?></div>
                <div class="float_L brd" style="width:145px;height:50px;text-align:center;padding-top:5px;"><a href="/alumni/AlumniSpeakFeedBack/showAlumFeedBacks?sortBy=lastRecieved&sortOrder=<?php echo $lastRecievedSortOrder; ?>&<?php echo $sortUrl; ?>">Date Of Last Review Recieved</a><?php echo $lastRecievedSortImage; ?></div>
            </div>
            <br clear="left"/>
            <?php
                $totalUnpublishedCount = 0;
                foreach($feedbacks as $feedback) {
                    $insituteId = $feedback['institute_id'];
                    $insituteName = $feedback['institute_name'];
                    $totalFeedbacks = $feedback['total'];
                    $totalUnpublished = $feedback['unpublished'];
                    $lastRecieved = $feedback['lastRecieved'];
                    $totalUnpublishedCount += $totalUnpublished;
            ?>
            <div>
                <div class="float_L brd" style="width:100px;text-align:center;height:20px;padding-top:5px;"><?php echo $insituteId; ?></div>
                <div class="float_L brd" style="width:470px;text-align:left;height:20px;padding-top:5px;">&nbsp; <?php echo $insituteName; ?></div>
                <div class="float_L brd" style="width:100px;text-align:center;height:20px;padding-top:5px;"><a href="/alumni/AlumniSpeakFeedBack/getInstituteFeedBacks/<?php echo $insituteId; echo ($selectedCourseId == "" ? "" : "/".$selectedCourseId); ?>"><?php echo $totalFeedbacks; ?></a></div>
                <div class="float_L brd" style="width:145px;text-align:center;height:20px;padding-top:5px;"><a href="/alumni/AlumniSpeakFeedBack/getInstituteFeedBacks/<?php echo $insituteId; echo ($selectedCourseId == "" ? "" : "/".$selectedCourseId); ?>"><?php echo $totalUnpublished; ?></a></div>
                <div class="float_L brd" style="width:145px;text-align:center;height:20px;padding-top:5px;"><?php echo $lastRecieved; ?></div>
            </div>
            <br clear="left"/>
            <?php
                }
            ?>
		</div>
	</div>
        </center>
        <script>
            <?php
		$postMsgTxt = "";
                switch($totalUnpublishedCount) {
                    case '1': $totalUnpublishedCount = '1 Review is '; break;
                    case '0': $totalUnpublishedCount = 'No Reviews are '; $postMsgTxt = ' for the searched criteria';
			break;
                    default: $totalUnpublishedCount = $totalUnpublishedCount .' Reviews are ';
                }
            ?>
            document.getElementById('unPublishedCounter').innerHTML = '<?php  echo $totalUnpublishedCount; ?> awaiting moderation<?php echo $postMsgTxt; ?>.';
        </script>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>

<script>
	function validateData(){
		if(trim($('criteriaValue').value) == '') {
			alert('What should I search for??');
			return false;
		}
		
		if ($("criteriaName").selectedIndex == 0 && isNaN($("criteriaValue").value)) {
			alert("Please enter the numeric value for the Institute Id.");
			$("criteriaValue").focus();
			return false;
		}
		
		// If Institute ID is the selected criteria and some Course is selected, then check if the Institute ID is not updated / manipulated by the user..
		if ($("institute_courses") != undefined && $("criteriaName").selectedIndex == 0 && $("institute_courses").selectedIndex != 0) {
				if($("insId").value != $("criteriaValue").value) {
					if(confirm("The courses do not belong to the Institue Id : "+$("criteriaValue").value+", Are you sure you want to continue and search for this Institute's reviews?")) {
						$("isCourseSelected").value = 0;
						return true;
					} else {
						return false;
					}
				}
				$("isCourseSelected").value = 1;
		}
		// alert("isCourseSelected = "+$("isCourseSelected").value);
		return true;
	}
</script>