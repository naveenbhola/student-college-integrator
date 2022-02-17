<?php
	if(!isset($userComparedCourses)){
		if(!isset($this->compareCourseLib)){
			$this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
		}
		$userComparedCourses = $this->compareCourseLib->getUserComparedCourses();
	}
?>
<?php foreach($RMCCourseAndUnivObjs['courses'] as $course)
    {
            $universityId = $course->getUniversityId();
            $dataArray = array(
                                  'courseObj'           => array($course),
                                  'universityObject'    =>  $RMCCourseAndUnivObjs['universities'][$universityId],
                                  'identifier'          => 'rateMyChanceTuple',
                                  'pageType'            => 'rateMyChancePage', 
								  'compareTupleTrackingSource' => '606',
								  'userComparedCourses'	=> $userComparedCourses
                              );
          $this->load->view("categoryPage/widgets/categoryPageTuple",$dataArray);}
?>
<?php if($rmcCourses > RMC_TAB_TUPLE_COUNT) { ?>
        <div id="rmcLoadMoreButton" class="load-more">
            <a id="rmcLoadMoreButtonButtonText" href="javascript:void(0);" onclick="rmcLoadMore();" style="margin-bottom: 20px;">Load More</a>
        </div>
<?php } ?>