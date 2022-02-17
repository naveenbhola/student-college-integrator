<div class="bg-color">
    <?php 
    if($isMultilocation){
        echo modules::run('nationalInstitute/InstituteDetailPage/getMultiLocationLayerForCourse',$courseObj,$currentLocationObj);
    }

    if(!empty($showImportantViewMore)){
        $this->load->view('nationalCourse/CoursePage/CourseImportantDatesLayer');
    }

    if (!empty($admissions)){
        $this->load->view('CoursePage/CourseAdmissionLayer');
    }

    if(!empty($predictorData) && count($predictorData) > 1)
        $this->load->view('CoursePage/CoursePredictorLayer');
    ?>
    

  	<div class="new-container new-breadcomb">
        <?=$breadcrumbHTML?>		
 	</div>	

    <div class="container-fluid top-header h-shadow" id="fixed-card">
        <div class="new-container new-header">
            <div class="n-col clear">
                <div class="new-row" id="fixed-cta">
                    <div class="col-md-8">
                        <p class="head-1" style="font-weight:600"><?=$courseName?></p>
                    </div>
                    <div class="col-md-4 right-text">
                        <a tracking-id="<?=COMPARE_DESKTOP_CTA_STICKY;?>" href="javascript:void(0);" class="btn-secondary addToCompare" ga-track="COMPARE_STICKY_COURSEDETAIL_DESKTOP" style="margin-right:10px;">Add to Compare</a>
                        <?php $this->load->view('CoursePage/CourseDownloadBrochure',array('isSticky'=>true)); ?>
                    </div>
                </div>
            </div>
            <div class="new sticky-nav" id="fixed-card-bottom" style="display:none;">
                <?php $this->load->view('CoursePage/CourseTabSection'); ?>
            </div>
        </div>
    </div>
    <div class="allContentLoader" id="loader-image" style="display: none">
            <div class="loader-image">
                <img src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif">
            </div>
    </div> 

    <div class="container-fluid">
        <div class="new-container">
            <!--Basic Info-->
            <div class="new-row">
                <?php $this->load->view('CoursePage/CourseDetailTopWidget'); ?>
                <div class="new pad10 sticky-nav" id="TabSection">
                   <?php $this->load->view('CoursePage/CourseTabSection'); ?>
                </div>
            </div>

            <?php
            $it = 0;
            foreach ($navigationSection as $key => $section) {
                switch ($section) {
                    case 'Eligibility':
                        if(!empty($eligibility) && $eligibility['showEligibilityWidget'])
                            $this->load->view('CoursePage/CourseEligibilityWidget');
                            $it++;
                    break;
                    case 'Reviews':
                         if(!empty($reviewWidget['html'])){
                             echo $reviewWidget['html'];
                             $it++;
                         }
                     break;
                    case 'Fees':
                        if(!empty($fees))
                        {
                            $this->load->view('CoursePage/CourseFeesWidget');
                            $a=1;
                        }
                        if($reviewAfter == 'Fees'){
                             echo $reviewWidget['html'];
                             $b=1;
                        }
                        if($a==1 ||$b==1)
                        {
                            $it++;
                        }
                    break;
                    case 'Highlights':
                        if(!empty($highlights)) {
                            $this->load->view('CoursePage/CourseHighlightsWidget');
                            $it++;
                        }
                        if($dfpData['client'] == 1)
                        {
                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1_client','bannerType'=>"content"));    
                        }
                    break;
                    case 'Gallery':
                        $this->load->view('CoursePage/CourseGalleryWidget');
                        $it++;
                    break;
                    case 'Structure':
                        if(!empty($courseStructure))
                        {
                         $this->load->view('CoursePage/CourseStructureWidget');
                         $it++;
                        }
                    break;
                    case 'Admissions':
                        if(!empty($admissions) || !empty($importantDatesData['importantDates'])) {
                            $this->load->view('CoursePage/CourseAdmissionWidget');
                            $it++;
                        }
                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1','bannerType'=>"content"));
                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1_2','bannerType'=>"content"));
                        $this->load->view('CoursePage/CourseCtaWidget');

                    break;
                    case 'Placements':
                        if(!empty($placements) || !empty($placementsCompanies) || !empty($internships) && $internships->getReportUrl()){
                            $this->load->view('CoursePage/CoursePlacementWidget');
                            $it++;
                        }
                    break;
                    case 'Seats':
                        if(!empty($seats))
                        {
                            $this->load->view('CoursePage/CourseSeatWidget');
                            $it++;
                        }
                    break;
                    case 'chpWidget':
                            echo '<div class="new-row">';
                              $this->load->view('common/chpInterLinking');
                            echo '</div>';
                    break;  
					case 'AnA':
                            $this->load->view('CoursePage/CourseANAWidget');
                            $it++;
                    break;
                    case 'Contact':
                        echo $schemaContact;
                        echo $contactWidget;
                        $it++;
                    break;
                    case 'Reco1':
                        $this->benchmark->mark('also_viewed_recommendation_start');
                        echo modules::run('nationalInstitute/InstituteDetailPage/getRecommendedListingWidget',$courseId,'course', 'alsoViewed');
                        $this->benchmark->mark('also_viewed_recommendation_end');
                        $it++;
                    break;
                    case 'Reco2':
                        $this->benchmark->mark('similar_recommendation_start');
                        echo modules::run('nationalInstitute/InstituteDetailPage/getRecommendedListingWidget',$courseId,'course', 'similar');
                        $this->benchmark->mark('similar_recommendation_end');
                        $it++;
                    break;
		            case 'naukriSalary':
                        //$this->load->view('CoursePage/CourseNaurkiSalaryWidget');
                        //$it++;
                    break;
                    case 'Partner':
                        if($partners)
                        {
                            $this->load->view('CoursePage/CoursePartnerWidget');
                            $it++;
                        }
                    break;
                    case 'CategoryPageLinks':
                        if(!empty($interLinkingLinks)){
                            $this->load->view('CoursePage/CourseCategoryPageLinkingWidget');
                            $it++;
                        }
                    default:
                    break;                        
                }
                if($it==1)
                {
                    $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C2','bannerType'=>"content"));
                    $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C2_2','bannerType'=>"content"));
                    $it=5;
                }
            }
            ?>

            <?php //$this->load->view('CoursePage/CourseAlsoViewedWidget'); ?>

           
            <!--Alumini Employment Stats-->            
            <?php //$this->load->view('CoursePage/CourseAluminiEmploymentWidget'); ?>

            <!--Student Exchange-->
            <?php //$this->load->view('CoursePage/CourseStudenetExchangeWidget'); ?>
            
         
            
            <!--college Reviews-->
            <?php //$this->load->view('CoursePage/CourseReviewWidget'); ?>
           

            <!--Ana Reviews-->
            <?php //$this->load->view('CoursePage/CourseANAWidget'); ?>

        </div>
    </div>

    <a href="javascript:void(0);" class="scrollToTop"></a>

</div>
   <div class="gallry-slider photonLayer" id="gallery-slider" style="display:none"></div>
