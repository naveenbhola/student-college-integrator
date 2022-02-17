
<?php
    $this->load->view('listing/abroad/widget/courseTitle');
    $this->load->view('listing/abroad/widget/courseFeatures');
    //download brochure at belly    
    $this->load->view('listing/abroad/widget/courseCTAs');
    
?>
<div id="left-col">
    <?php // if(!empty($consultantData)){ $this->load->view('listing/abroad/widget/consultantWidgetInline'); }?>
    <div id="mediaForStudyAbroadPage"></div>
    <div id="userAdmittedCards"></div>


<?php
    
    //Commented as of now as it is static
    //$this->load->view('listing/abroad/widget/courseAdmissionProcess');
    
    //download brochure at the bottom
    $this->load->view('listing/abroad/widget/downloadBrochureSingleSignup');
?>
    
    <div id="alsoViewedCoursesWidget">
        <?php if(!empty($alsoViewedRecommendationResponse)) echo $alsoViewedRecommendationResponse['recommendationHTML']; ?>
    </div>
    <div id="similarInstituteWidget"></div>
    <div class="clearfix"></div>
	<?php // echo modules::run('abroadContentOrg/AbroadContentOrgPages/getContentOrgWidget');?>
    <div id="coursePageArticleWidget">
	<?php
	    $articleCount = 9;
	    $data = array($courseObj, $courseCategoryId, $courseSubCategoryId, $articleCount);
	    echo Modules::run('studyAbroadArticleWidget/articleAbroadWidgets/getArticlesForCoursePage', $data);
	?>
    </div>

    <?php if($compareData && $compareData['recommendedCompareCourseData'] && count($compareData['recommendedCompareCourseData'])>=1){ ?>
        <?php if(count($compareData['recommendedCompareCourseData'])==1){ ?>
            <div class="widget-wrap clearwidth" id="compareCoursesWidget">
        <?php } if(count($compareData['recommendedCompareCourseData'])>1){ ?>
            <div class="widget-wrap clearwidth">
        <?php }
        $this->load->view('listing/abroad/widget/compareCoursesWidget'); ?>
        </div>
    <?php }?>
	
</div>
