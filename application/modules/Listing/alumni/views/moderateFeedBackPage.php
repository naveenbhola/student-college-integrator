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
    <!-- code for overlay -->
    <div id="courseListForm" class="w102"  style="display:none; width:500px;">
    <div id="courseListFormError"></div>
	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk bld fontSize_12p" id="formTitle">Select the Courses on which the feedback should be applied.</div>
	</div>
		<div class="lineSpace_10">&nbsp;</div>
	<form name="courseListForm" action="#" method="post">
	<div style="overflow-y:auto; height:240px;width:530px;">
       <?php
	if(isset($courses))
	{
	  foreach ($courses as $course){
	  ?>
	    <div class="row">
	    <INPUT NAME="courseoptions" TYPE="CHECKBOX" VALUE="<?php echo $course['courseID']; ?>" ID="<?php echo $course['courseID']; ?>" >
	    <?php echo $course['courseName']; ?><BR>
	    </div>
	    <div class="lineSpace_5">&nbsp;</div>
	  <?php
	  }
	}
	?>
	</div>
	<input type="hidden" id="institute_id_overlay" value=""></input>
	<input type="hidden" id="email_overlay" value=""></input>
	<input type="hidden" id="criteria_id_overlay" value=""></input>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="txt_align_c"><input type="button" value="OK" class="homeShik_SubmitBtn" onClick="updateCourseList(); return false;"/></div>
	<div class="lineSpace_5">&nbsp;</div>
	</form>
    </div>
    <!-- code for overlay -->

	<div id="dataLoaderPanel" style="position:absolute;display:none">
		<img src="/public/images/loader.gif"/>
	</div>
	<div class="mar_full_10p" style="height:100%">
        <div style="margin-left:1px">
            <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;"><a href="/alumni/AlumniSpeakFeedBack/showAlumFeedBacks">Alumni Speak DashBoard</a> > Institute Reviews </div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="bld" style="padding-left:10px;">Institute Id - <label class="OrgangeFont"><?php echo $instituteId; ?></label></div>
            <div class="bld" style="padding-left:10px;">Institute Name - <label class="OrgangeFont" id="instituteNamePlaceHolder"></label></div>
	    <?php if($selectedCourseName != "") {?>
	    <div class="bld" style="padding-left:10px;">Course Name - <label class="OrgangeFont"><?php echo $selectedCourseName; ?></label></div>
	    <?php }	?>
	    
            <div class="bld" style="padding-left:10px;">Total no. of reviews received - <label class="OrgangeFont" id="totalReviews"></label></div>
            <div class="bld" style="padding-left:10px;">Total no. of reviews received awaiting moderation - <label class="OrgangeFont" id="totalUnpublishedReviews"></label></div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="grayLine"></div>
            <div class="lineSpace_20">&nbsp;</div>
            <center>
            <div class="lineSpace_20">&nbsp;</div>
            <?php

                    $sortUpImg='<img src="/public/images/arrow_up.png" align="absmiddle" height="16" width="16" border="0" alt="Descending"/>';
                    $sortDownImg='<img src="/public/images/arrow_down.png" align="absmiddle" height="16" width="16" border="0" alt="Asccending"/>';
                    $emailSortImage = '';
                    $emailSortOrder = 'up';
                    if($sort == 'email') {
                        $emailSortImage = $sortOrder == 'desc' ? $sortUpImg : $sortDownImg;
                        $emailSortOrder = $sortOrder == 'asc' ? 'up' : 'down';
                    }
                    $dateSortImage = '';
                    $dateSortOrder = 'up';
                    if($sort == 'feedbackTime') {
                        $dateSortImage = $sortOrder == 'desc' ? $sortUpImg : $sortDownImg;
                        $dateSortOrder = $sortOrder == 'asc' ? 'up' : 'down';
                    }
            ?>
            <div>
                <div>
                     <img src="/public/images/img_Live.gif" align="absmiddle" /> Publish &nbsp; 
                     <img src="/public/images/img_Block.gif" align="absmiddle" /> Unpublish &nbsp; 
                     <img src="/public/images/img_Delete.gif" align="absmiddle" /> Discard &nbsp;
                     <img src="/public/images/img_Web.gif" align="absmiddle" width="20" height="20" /> Preview 
                     <img src="/public/images/img_Course.jpg" align="absmiddle" width="20" height="20" /> Course Listing 

                </div>
                <div class="lineSpace_20">&nbsp;</div>
                <table border="0" width="99%">
                    <tr>
                        <td colspan="4" width="100%"> 
                            <table width="100%" cellspacing="2px" cellpadding="5px">
                                <tr>
                                    <td width="10%" align="center" bgcolor="#e2e2e2"><a href="/alumni/AlumniSpeakFeedBack/getInstituteFeedBacks/<?php echo $instituteId; ?>?sortBy=feedbackTime&sortOrder=<?php echo $dateSortOrder; ?>">Date Of Review</a><?php echo $dateSortImage; ?></td>
                                    <td  width="20%"align="center" bgcolor="#e2e2e2"><a href="/alumni/AlumniSpeakFeedBack/getInstituteFeedBacks/<?php echo $instituteId; ?>?sortBy=email&sortOrder=<?php echo $emailSortOrder; ?>">Email</a><?php echo $emailSortImage; ?></td>
                                    <td width="20%" bgcolor="#e2e2e2">Name</td>
                                    <td width="20%" bgcolor="#e2e2e2">Course &amp; Completion Year</td>
                                    <td width="30%" bgcolor="#e2e2e2">Designation &amp; Organisation</td>
                                </tr>
                            </table>     
                         </td>   
                    </tr>
                    <tr>
                        <td width="10%" bgcolor="#FFFFCC" align="center">Feedback Criteria</td>
                        <td width="10%" bgcolor="#FFFFCC" align="center">Criteria Rating</td>
                        <td width="70%" bgcolor="#FFFFCC">Criteria Review</td>
                        <td width="10%" bgcolor="#FFFFCC" align="center">Status</td>
                    </tr>
                    <tr><td colspan="4" height="5px">&nbsp;</td>
                    </tr>
            <?php
		//_p($courses); die;
                $email = '';
                $countUnpublish = 0;
                $count = -1;
                foreach($feedbacks as $feedback) {
                    $count++;
                    $description  = $feedback['criteria_desc'] != '' ? $feedback['criteria_desc'] : 'No review received';
                    if($feedback['status'] == 'published') {
                        $statusImg = '/public/images/img_Block.gif';
                        $status = 'Unpublish';
                    } else {
                        $statusImg = '/public/images/img_Live.gif';
                        $status = 'Publish';
                        $countUnpublish++;
                    }
                    $rating = $feedback['criteria_rating'] != 0 && $feedback['criteria_rating'] != '' ? $feedback['criteria_rating'] : 'Not received';
                    $instituteName = $feedback['institute_name'];
                    $organisation = $feedback['organisation'] != '' ? ' at '. $feedback['organisation'] : '';
                    if($email != $feedback['email']) {
                        $email = $feedback['email'];
			
			if($feedback['course_id'] != -1) {
				foreach($courses as $key => $courseArray) {
					if($courseArray['courseID'] == $feedback['course_id']) {
						$feedback['course_name'] = $courseArray['courseName'];
						break;
					}
				}
			}
            ?>
                <tr>
                    <td colspan="4"> 
                        <table width="100%" cellspacing="2px" cellpadding="5px">
                            <tr>
                                <td width="10%" bgcolor="#e2e2e2"><?php echo $feedback['feedbackTime'];?></td>
                                <td width="20%" bgcolor="#e2e2e2"><?php echo $feedback['email']; ?></td>
                                <td width="20%" bgcolor="#e2e2e2"><?php echo $feedback['name']; ?></td>
                                <td width="20%" bgcolor="#e2e2e2"><?php echo $feedback['course_name'] .' : batch '. $feedback['course_comp_year'] ; ?></td>
                                <td width="30%" bgcolor="#e2e2e2"><?php echo $feedback['designation'] .' '. $organisation ; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php
                    }
                ?>
            <tr>
                <td width="10%" bgcolor="#FFFFCC"><?php echo $feedback['criteria_name']; ?></td>
                <td width="10%" bgcolor="#FFFFCC" align="center"><?php echo $rating; ?></td>
                <td width="70%" bgcolor="#FFFFCC"><i><?php echo $description; ?></i></td>
                <td width="10%" bgcolor="#FFFFCC" align="center"><a title="<?php echo $status; ?>"><img src="<?php echo $statusImg; ?>" alt="<?php echo $status; ?>" border="0" onclick="<?php echo $feedback['criteria_desc'] == '' ? 'return false;' : ''; ?>updateStatus(this.alt, this,'<?php echo $feedback['institute_id']; ?>','<?php echo $feedback['criteria_id']; ?>','<?php echo $email; ?>');return false;" style="<?php echo $feedback['criteria_desc'] == '' ? '' : 'cursor:pointer;'; ?>"/></a> &nbsp; <a title="Discard"><img src="/public/images/img_Delete.gif" border="0" onclick="updateStatus('Discard', this,'<?php echo $feedback['institute_id']; ?>','<?php echo $feedback['criteria_id']; ?>','<?php echo $email; ?>'); return false;" alt="Discard" style="cursor:pointer;"/></a>&nbsp;<a href="/getListingDetail/<?php echo $instituteId; ?>/institute" title="Preview" target="_blank"><img src="/public/images/img_Web.gif" align="absmiddle" alt="Preview" style="cursor:pointer;" border="0" width="20" height="20" /></a>&nbsp;<a title="CourseListing" ><img src="/public/images/img_Course.jpg" onClick="showCourseList(this.alt, this,'<?php echo $feedback['institute_id']; ?>','<?php echo $feedback['criteria_id']; ?>','<?php echo $email; ?>');return false;" align="absmiddle" alt="Course Listing" style="cursor:pointer;" border="0" width="20" height="20" id="courseList_<?php echo $feedback['email']; ?>_<?php echo $feedback['criteria_id']; ?>"/></a>
                </td>
            </tr>
            <?php
                }
            ?>
		</table>
	</div>
            <div class="lineSpace_20">&nbsp;</div>
                <div>
                     <img src="/public/images/img_Live.gif" align="absmiddle" /> Publish &nbsp; 
                     <img src="/public/images/img_Block.gif" align="absmiddle" /> Unpublish &nbsp; 
                     <img src="/public/images/img_Delete.gif" align="absmiddle" /> Discard &nbsp;
                     <img src="/public/images/img_Web.gif" align="absmiddle" width="20" height="20" /> Preview 
                     <img src="/public/images/img_Course.jpg" align="absmiddle" width="20" height="20" /> Course Listing 
                </div>
        </center>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>
<script>
document.getElementById('instituteNamePlaceHolder').innerHTML = '<?php echo str_replace("'","\'",$instituteName); ?>';
document.getElementById('totalReviews').innerHTML = '<?php echo count($feedbacks); ?>';
document.getElementById('totalUnpublishedReviews').innerHTML = '<?php echo $countUnpublish; ?>';
function updateStatus(statusToChange, statusObj, instituteId,criteriaId, email) {
    var confirmOp = confirm('You are going to '+ statusObj.alt + ' the review. Do you want to Proceed?');
    if(confirmOp === false) {
        return false;
    }
    var url = "/alumni/AlumniSpeakFeedBack/updateReviewStatus";
    var data = "instituteId="+ instituteId +'&status='+statusToChange.toLowerCase()+'ed&criteriaId='+criteriaId+'&email='+email;
    new Ajax.Request (url,{ method:'post', parameters: (data), onSuccess:function (xmlHttp) {
            if(statusToChange != 'Discard') {
                statusObj.src = statusToChange == 'Publish' ? '/public/images/img_Block.gif' : '/public/images/img_Live.gif';
                statusObj.alt = statusToChange == 'Publish' ? 'Unpublish' : 'Publish';
                statusObj.parentNode.title = statusToChange == 'Publish' ? 'Unpublish' : 'Publish';
                var addVar = statusToChange == 'Publish' ? -1 : 1;

                document.getElementById('totalUnpublishedReviews').innerHTML =parseInt(document.getElementById('totalUnpublishedReviews').innerHTML ) + addVar ;
                //alert("This status has been updated successfully");
            } else {
                //alert("The review is discarded");
                var  a  = statusObj.parentNode.parentNode.parentNode.parentNode;
                a.removeChild(statusObj.parentNode.parentNode.parentNode);
                document.getElementById('totalReviews').innerHTML = parseInt(document.getElementById('totalReviews').innerHTML) - 1;
                if(parseInt(document.getElementById('totalUnpublishedReviews').innerHTML ) > 0) {
                    document.getElementById('totalUnpublishedReviews').innerHTML =parseInt(document.getElementById('totalUnpublishedReviews').innerHTML ) - 1 ;
                }
                var aChildren = a.childNodes, aChildCount =0;
                for(var aChild = a.firstChild; aChild = aChild.nextSibling; ) {
                    if(aChild.nodeName.toLowerCase() == 'div' && aChild.getAttribute('feedback') != null) {
                        aChildCount++;
                    }
                }
                if(aChildCount == 0) {
                    a.innerHTML += '<i>All the reviews are discarded.</i>';
                }
            }
            }});
}

function showCourseList(statusToChange, statusObj, instituteId,criteriaId, email)
{
    if(!document.courseListForm.courseoptions)
    {
      alert("There are no Courses available for this Institute.");
      return false;
    }
    var url = "/alumni/AlumniSpeakFeedBack/getExcludedCourses";
    var data = "instituteId="+ instituteId +'&status='+statusToChange.toLowerCase()+'ed&criteriaId='+criteriaId+'&email='+email;
    new Ajax.Request (url,{ method:'post', parameters: (data), onSuccess:function (xmlHttp) {
	    var courseList = xmlHttp.responseText;
	    showCourseListOverlay(courseList, instituteId,criteriaId, email);
            }});
}

function showCourseListOverlay(courseList, instituteId,criteriaId, email)
{
	var overlayWidth = 550;
	var overlayHeight = window.screen.height/2;
	var overlayTitle = 'Course List';
	var overLayForm = $('courseListForm').innerHTML;
	$('courseListForm').innerHTML = '';
	overlayContent = overLayForm;
	overlayParent = $('courseListForm'); // Global variable For all the parent overlay contents;
	showOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent); 
	if(document.courseListForm.courseoptions)
	  CheckAll(document.courseListForm.courseoptions);
	var courseArray = courseList.split(",");
	for(var i=0;i<courseArray.length;i++)
	{
	    if(courseArray[i]!="")
	      document.getElementById(courseArray[i]).checked = false;
	}
	document.getElementById('institute_id_overlay').value = instituteId;
	document.getElementById('email_overlay').value = email;
	document.getElementById('criteria_id_overlay').value = criteriaId;
}

function CheckAll(chk)
{
	if(chk.length){
	  for (i = 0; i < chk.length; i++)
	  chk[i].checked = true ;
	}
	else
	  chk.checked = true;
}

function updateCourseList()
{
    var url = "/alumni/AlumniSpeakFeedBack/setExcludedCourses";
    if(document.courseListForm.courseoptions){
      var checkObj = document.courseListForm.courseoptions;
      var excludedCourseList = '';
      if(checkObj.length){
	for (i = 0; i < checkObj.length; i++)
	{
	    if(checkObj[i].checked === false)
	      excludedCourseList += checkObj[i].value + ",";
	}
      }
      else{
	    if(checkObj.checked === false)
	      excludedCourseList += checkObj.value + ",";
      }
      var data = "instituteId="+ document.getElementById('institute_id_overlay').value +'&criteriaId='+document.getElementById('criteria_id_overlay').value+'&email='+document.getElementById('email_overlay').value+'&courses='+excludedCourseList;
      new Ajax.Request (url,{ method:'post', parameters: (data), onSuccess:function (xmlHttp) {
	      if(xmlHttp.responseText == 1)
	      {
		  hideOverlay();
		  //commonShowConfirmMessage("The course list has been added for this feedback.");
	      }
	      }});
    }
}
</script>
