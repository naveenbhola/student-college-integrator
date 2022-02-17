<div class="shortlist-title">
<?php 
    if(isset($instituteName) && $instituteName != '') { 
        echo "Colleges similar to <strong>$instituteName</strong>";
    } else {
        echo "Shortlist from Recommended Colleges";
    }
?>
</div>
<div class="recommendationSlider">
    <ul class="slides">
<?php foreach($courseObject as $course) {
    if( isset ( $recommendedCourseWidgetKey ) ){
        $this->load->view('mobile_myShortlist5/myShortlistRecommendationCourseInfo',array('courseDetails'=>$course,'naukriData'=>$naukriData, 'recommendedCourseWidgetKey'=>$recommendedCourseWidgetKey));
    } else {
        $this->load->view('mobile_myShortlist5/myShortlistRecommendationCourseInfo',array('courseDetails'=>$course,'naukriData'=>$naukriData));
    }
}
?>
    </ul>    
</div>