<?php
	if(!isset($userComparedCourses)){
		if(!isset($this->compareCourseLib)){
			$this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
		}
		$userComparedCourses = $this->compareCourseLib->getUserComparedCourses();
	}
?>
<?php
    foreach($shortlistData as $university){
        foreach($university->getCourses() as $course){
            $dataArray = array(
              'courseObj'         => array($course),
              'universityObject'  => $university,
              'identifier'        => 'ShortListTuple',
              'pageType'          => 'shortlistPage',
			  'compareTupleTrackingSource' => '605',
			  'userComparedCourses'	=> $userComparedCourses
            );
            $this->load->view("categoryPage/widgets/categoryPageTuple",$dataArray);
        }
    }
?>
<?php if(count($userShortlistedCourses) > RMC_TAB_TUPLE_COUNT) { ?>
        <div id="shortlistPageLoadMoreButton" class="load-more">
            <a id="shortlistPageLoadMoreButtonText" href="javascript:void(0);" onclick="shortlistPageLoadMore();" style="margin-bottom: 20px;">Load More</a>
        </div>
<?php } ?>