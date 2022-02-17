<!-- Load Country Home Header -->

<?php $this->load->view('countryHomeRevamped/countryHomeHeader'); ?>

<!-- Country Home Bread Crumb File -->
<?php $this->load->view('countryHomeBreadcrumb');?>

    <div class="content-wrap clearfix">  
        <div class="clearwidth ">
            
            <!-- Load title and change country layer  -->
            <?php $this->load->view('countryHomeRevamped/countryHomeTitle'); ?>
            <div class="CountryStudyContainer-detail">
                <?php $this->load->view('countryHomeRevamped/countryHomeNavigation'); ?>
                <?php $this->load->view('countryHomeRevamped/countryHomeContent'); ?>
                <?php
                if($showGutterHelpText) {
                ?>	
                <div id="countryHomeGutterHelpText" class="navigate-info navigate-info2">
                    <i class="common-sprite info-pointer"></i>
                    <p>Use this sidebar to change course</p>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php 
    foreach(array_keys($coursesData) as $mainCourseId){
        $countryRecommendations = $coursesData[$mainCourseId]['countryRecommendations'];
        if (count($countryRecommendations) > 0) {
            $this->load->view('widgets/countryHomeRevamped/countryHomeSimilarDestinations',array('mainCourseId'=>$mainCourseId));
        }
    }
    $this->load->view('widgets/countryHomeRevamped/countryHomeOverviewContent');
    // Country Home Footer File
    $this->load->view('countryHomeRevamped/countryHomeFooter');
?>