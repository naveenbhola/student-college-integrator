<div id="right-col">
<?php
	switch($listingType) {
		case 'university':
            $this->load->view('listing/abroad/widget/universityInfo');
            $this->load->view('listing/abroad/widget/applyHomeLinkingWidget',array('gaParams'=>'UNIVERSITY_PAGE,applyPageLinkingWidget'));
			if(!empty($studentGuide)){ $this->load->view('listing/abroad/widget/studentGuideRightWidget');}
            $this->load->view('listing/abroad/widget/facebookWidget');
			break;
			
		case 'department':
            $this->load->view('listing/abroad/widget/universityInfo');
			$this->load->view('listing/abroad/widget/facebookWidget');
			break;

		case 'course':
						if($courseObj->getScholarshipLinkCourseLevel()!='' || $courseObj->getScholarshipLinkDeptLevel()!='' || $courseObj->getScholarshipLinkUniversityLevel()!=''){
							$this->load->view('listing/abroad/widget/courseScholarshipWidget.php');
						}
						if($isMoreInfoTabFlag)
						{
							$this->load->view('listing/abroad/widget/courseMoreInfo.php');	
						}
						if(count($otherCoursesArr['courses'])>0)
						{
							$this->load->view('listing/abroad/widget/otherCourses.php');	
						}
                        $this->load->view('listing/abroad/widget/facebookWidget');
			break;
	}
?>
</div>
