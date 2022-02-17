<div class="header-unfixed">
    <h1 class="CntryHomePage-Head">
        Study in <?php echo $countryObj->getName(); ?>
    </h1>
    <?php
//    $this->load->view('commonModule/countryPage/desiredCourseTabs');
    $this->load->view('widgets/CountryHomeDesiredCourseNavigation');
    ?>
    <div class="country-container">
        <?php foreach($coursesOnPage as $course){ ?>
            <div class="country-Innercontainer courseSection<?php echo $course?>">
                <?php
                $this->load->view('widgets/mostPopularUniversityWidget',array('mainCourseId' => $course));
                if(count($coursesData[$course]['widgetData']['countryHomeFeeAffordability'])>0)
                {
                    $this->load->view('widgets/feesAndAffordability',array('mainCourseId' => $course));
                }
                $this->load->view('widgets/eligibilityAndExam', array('mainCourseId' => $course));
                ?>
            </div>
        <?php } ?>
    </div>    
        <?php
            foreach($coursesOnPage as $course){
                $countryRecommendations = $coursesData[$course]['countryRecommendations'];
                if(count($countryRecommendations)>0){
                    $this->load->view("widgets/browseSimilarStudyDestinations",array('mainCourseId' => $course));
                }    
            }
        ?>
    <div class="country-container padTop">
        <?php if($countryOverview['visa']!='' || $countryOverview['work']!='' || $countryOverview['economy']!=''){?>
        <div class="country-Innercontainer">
            <?php
            $this->load->view('widgets/countryAdditionalInfo');
            ?>
        </div>
        <?php } ?>
    </div>
</div>
<div class="clearFix">&nbsp;</div>