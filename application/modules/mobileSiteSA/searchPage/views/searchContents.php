<div id="course_result_container">
<?php
	if(!isset($userComparedCourses)){
		if(!isset($this->compareCourseLib)){
			$this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
		}
		$userComparedCourses = $this->compareCourseLib->getUserComparedCourses();
	}
?>
<?php
if(count($courseIdsBySimilarCourse)>0){
    foreach($courseIdsBySimilarCourse as $courseIds)
    {
        $courseObjArr = array();
        foreach($courseIds as $id){
            $courseObjArr[] = $courseObj[$id];
        }
		
		if(!is_object($courseObj[$id]) || $courseObj[$id] instanceof Course || $courseObj[$id]->getId() == "") {
						continue;
		}
		
        $dataArray = array(
                  'universityObject' => $uniObj[$courseObj[$id]->getUniversityId()],     
                  'courseObj' => $courseObjArr,
                  'identifier' => 'SearchListTuple',
                  'pageType' => 'searchPage_mob',
				  'compareTupleTrackingSource' => '608',
				  'userComparedCourses'	=> $userComparedCourses
                );
        $this->load->view("categoryPage/widgets/categoryPageTuple",$dataArray);
    }
}else{
    $this->load->view("noResults");
}

$nextPageStart = $course_result_offset + count($courseList);
?>
</div>
<?php if($sa_course_group_count >20){
   $remainingCount =  (($sa_course_group_count- 20)>20)?20:($sa_course_group_count- 20);
   $courseSearchText = ($remainingCount >1) ? $remainingCount." more Courses": " 1 more Course"
    ?>
<div id="course_loadmore_cont" class="load-more">
    <a onclick="loadMoreTracking(<?=$nextPageStart?>,'course');loadCourseSearchResults('<?= ($keywordEncoded)?>','20')" style="margin-bottom: 20px;">Load <?= $courseSearchText;?></a>
</div>
<?php } ?>