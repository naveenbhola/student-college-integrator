<!--Start_Education_Qualification_Filter-->            
<div class="cmsSearch_contentBoxTitle"><a href="javascript:void(0);" class="cmsSearch_plusImg" onClick="hideDivElement(this,document.getElementById('educationalQualificationParent'));return false;" style="text-decoration:none;color:#000;font-size:14px"><b>Educational Qualification Filter</b></a></div>
<div style="line-height:15px">&nbsp;</div>
<div id="educationalQualificationParent" style="display:none">
<?php
    // only completed by is added in degree
    if ($course_name =='IT Courses' || $course_name =='IT Degrees') {
	    $this->load->view('enterprise/searchFormEducationalQualificationUGCourse');
	    if ($course_name =='IT Courses') { 
		$this->load->view('enterprise/searchFormITPageUGCompletionMarks');
	    }
	    if ($course_name =='IT Degrees') { 
		$this->load->view('enterprise/searchFormEducationalQualificationUGCompletionDate');
	    }
	    $this->load->view('enterprise/searchFormEducationalQualification12Details');
    } else {
	$this->load->view('enterprise/searchFormEducationalQualificationUGCourse');
	if($course_name!='Distance/Correspondence MBA' && $course_name!='Study Abroad')
	$this->load->view('enterprise/searchFormEducationalQualificationUGCollege');
	$this->load->view('enterprise/searchFormEducationalQualificationUGCompletionDate');
	$this->load->view('enterprise/searchFormEducationalQualification12Details');
    }
?>
</div>
<div class="cmsSearch_SepratorLine">&nbsp;</div>            
<!--End_Education_Qualification_Filter-->            
