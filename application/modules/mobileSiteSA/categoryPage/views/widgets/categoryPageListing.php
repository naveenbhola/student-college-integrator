<?php
$scholarshipWidgetShown = false;
if(!$isZeroResultPage){
    //initialize a couple of variables for shortlisting here
    $pageType = "categoryPageListing_mob";
    $identifier = "CategoryPageTuple";
    $count = 0;
    $univCount = count($resultantUniversityObjects);
    $imageCount =1;
    foreach ($resultantUniversityObjects as $universityObject){

        $courseObj = array();
        $courseObjList = array();
        $courseList = $universityObject->getCourses();
        foreach($courseList as $deptCourse){
            $courseObjList[$deptCourse->getId()] = $deptCourse;
        }
        $courseObj = $courseObjList;

        $sortOrderOfCourses = $universityObject->getSortOrderForSimilarCourses();
        if(!empty($sortOrderOfCourses)) {
            $updatedCourseObjList = array();
            foreach($sortOrderOfCourses as $courseIdWithOrder){
                $updatedCourseObjList[] = $courseObjList[$courseIdWithOrder];
            }
            $courseObj = $updatedCourseObjList;
        }

        if($sortingCriteria['sortBy'] == 'none' || empty($sortingCriteria['sortBy'])) {
            $paidCourse = array();
            $freeCourse = array();
            foreach($courseObj as $course) {
                if($course->isPaid()) {
                    $paidCourse[] = $course;
                } else {
                    $freeCourse[] = $course;
                }
            }

            if(!empty($paidCourse) && !empty($freeCourse)) {
                $courseObj = array_merge($paidCourse, $freeCourse);
            } elseif(!empty($paidCourse)) {
                $courseObj = $paidCourse;
            } else {
                $courseObj = $freeCourse;
            }
        }

        if(!isset($userComparedCourses)){
            if(!isset($this->compareCourseLib)){
                $this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
            }
            $userComparedCourses = $this->compareCourseLib->getUserComparedCourses();
        }
        if($imageCount <= $loadImagesWithoutLazyCount){
            $dontLoadImageWithoutLazy = false;
        }else{
            $dontLoadImageWithoutLazy = true;
        }
        $categoryPageTupleData = array('universityObject'           => $universityObject,
            'courseObj'                 => $courseObj,
            'pageType'                  => $pageType,
            'identifier'                => $identifier,
            'compareTupleTrackingSource'=> 603,
            'userComparedCourses'       => $userComparedCourses,
            'dontLoadImageWithoutLazy'      => $dontLoadImageWithoutLazy
        );
        $this->load->view('widgets/categoryPageTuple',$categoryPageTupleData);
        $imageCount++;
        $count++;
        if($count == 4)
        {
            //echo '<section id="bsbCP" class="cate-tupple clearfix" data-enhance="false"></section>';
            $this->load->view('categoryPage/widgets/scholarshipInterlinkingCards');
            $scholarshipWidgetShown = true;
        }
        if(!($categoryPageRequest->isExamCategoryPage())){
            if($count==5 && !$this->input->is_ajax_request()){
                $this->load->view('categoryPage/widgets/categoryPageCountryRecommendations');
            }elseif($univCount < 5 && $count == $univCount && !$this->input->is_ajax_request()){
                $this->load->view('categoryPage/widgets/categoryPageCountryRecommendations');
            }
            if(($count==8 || $univCount < 8 && $count == $univCount) && !$this->input->is_ajax_request()){
                //$this->load->view('categoryPage/widgets/scholarshipInterlinkingCards');
                echo '<section id="bsbCP" class="cate-tupple clearfix" data-enhance="false"></section>';
            }
        }


    }
    if($scholarshipWidgetShown !== true)
    {
        //echo '<section id="bsbCP" class="cate-tupple clearfix" data-enhance="false"></section>';
        $this->load->view('categoryPage/widgets/scholarshipInterlinkingCards');
    }
    if($totalTuplesOnPage > ($categoryPageRequest->getPageNumberForPagination()*$resultsPerPage)){
        ?>
        <div id="load-more-category-page" class="load-more">
            <a onclick="loadMoreCategoryPage(this,'<?=$categoryPageRequest->getURL(($categoryPageRequest->getPageNumberForPagination())+1)?>')" >Load More</a>
        </div>
        <?php
    }
}else{?>
    <div class="noResult-message">
        <strong class="sorry-text">Sorry !</strong>
        <p>No Search Results were found.</p>
        <p>Please Try Again</p>
    </div>
<?php }
?>
<noscript>
    <?php if($totalTuplesOnPage > ($categoryPageRequest->getPageNumberForPagination()*$resultsPerPage)){ ?>
        <a href="<?=$categoryPageRequest->getURL(($categoryPageRequest->getPageNumberForPagination())+1)?>">Next</a>
    <?php } ?>
    <?php if($categoryPageRequest->getPageNumberForPagination() != 1){ ?>
        <a href="<?=$categoryPageRequest->getURL(($categoryPageRequest->getPageNumberForPagination())-1)?>">Previous</a>
    <?php } ?>
</noscript>