<div class="bg-color">
<?php 
  if($isMultilocation){
    echo modules::run('nationalInstitute/InstituteDetailPage/getMultiLocationLayer',$listing_id,$listing_type,$instituteObj,$currentLocationObj,$courseIdsMapping);
  }
  $this->load->view('InstitutePage/courseLayer');

  $locationName = $currentLocationObj->getCityName();
  if($currentLocationObj->getLocalityName())
    $locationName = $currentLocationObj->getLocalityName().", ".$locationName;

  $GA_Tap_On_Sticky_Compare = 'COMPARE_STICKY';
  $GA_Tap_On_Sticky_Db = 'DBROCHURE_STICKY';

  if($listing_type == 'university')
  {
    $compareStickyTrackingPageKeyId = 1002;
    $dBrochureStickyTrackingPageKeyId = 972;
    $dBrochureRecoLayer = 1005;
    $compareRecoLayer = 1001;
    $applyNowRecoLayer = 1006;
    $shortlistRecoLayer = 1009;
  }
  else if($listing_type == 'institute')
  {
    $compareStickyTrackingPageKeyId = 994;
    $dBrochureStickyTrackingPageKeyId = 932;

    $dBrochureRecoLayer = 995;
    $compareRecoLayer = 996;
    $applyNowRecoLayer = 998;
    $shortlistRecoLayer = 997;
  }
?>
<div class="layer-common" id="detailLayer" style=""></div>
<div class="layer-common" id="allInstituteLayer"></div>
<div class="layer-common" id="affiliationLayer"></div>
    <div class="container-fluid top-header h-shadow" id="fixed-card">
      <div class="new-container new-header">
       <div class="n-col clear" >
        <div class="new-row"  id="fixed-cta">
          <div class="col-md-8">
             <p class="head-1" style="font-weight:600;"><?php echo htmlentities($instituteName);?><span class="location-of-clg"><?php echo ($locationName ? ", ".$locationName : $locationName);?><span></p>
          </div>
        <div class="col-md-4 right-text">
                    <a class="button button--secondary btn-medium cmp-btn" style="margin-right:20px;" ga-attr="<?=$GA_Tap_On_Sticky_Compare?>" onclick="showCourseLayer('compare', {'instId':<?php echo $instituteObj->getId();?>,'instType':'<?php echo $instituteObj->getListingType();?>'}, 'Add Course to Compare','<?php echo $compareStickyTrackingPageKeyId;?>');">Add to Compare</a>
                    <!-- <a href="javascript:void(0);" class="btn-primary btn-medium">Shortlist</a> -->
                    <a class="button button--orange btn-pm" ga-attr="<?=$GA_Tap_On_Sticky_Db?>" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" onclick="ajaxDownloadEBrochure(this,<?php echo $listing_id;?>,'<?php echo $listing_type;?>','<?php echo stripslashes(htmlentities($instituteName));?>','instituteDetailPage','<?php echo $dBrochureStickyTrackingPageKeyId;?>','<?php echo $dBrochureRecoLayer;?>','<?php echo $compareRecoLayer;?>','<?php echo $applyNowRecoLayer;?>','<?php echo $shortlistRecoLayer;?>')">Apply Now</a>
                 </div>
       </div> 
       </div>
       <div class="new sticky-nav" id="fixed-card-bottom" style="display:none;">
           <?php $this->load->view('InstitutePage/InstituteTabSection'); ?>
       </div>
      </div>     
    </div>

    <div class="new-container new-breadcomb">
      <div class="breadcrumb3">
             <span class="home"><a href="<?=SHIKSHA_HOME;?>"><span>Home</span></a></span>
             <span class="breadcrumb-arrow">&#8250;</span>
             <span><?php echo ($listing_type == 'institute')?'Colleges':'Universities'?></span>
             <span class="breadcrumb-arrow">&#8250;</span>
             <span ><?=htmlentities($listingString['instituteString']);?> </span>
                            
      </div>
      <?php $this->load->view('InstitutePage/InstituteDetailTopWidget'); 
      ?>
      <div class="new pad10 sticky-nav" id="TabSection">
          <?php $this->load->view('InstitutePage/InstituteTabSection'); ?>
      </div>  

    </div>      
 </div>
   <!--container fluid ends-->
   
   <div class="container-fluid">
    <!---->
     <div class="new-container">
      <?php 
          $this->load->view('InstitutePage/PopularAndFeaturedCourses'); 
          if($dfpData['client'] == 1){
              $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1_client','bannerType'=>"content"));
          }

          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C2','bannerType'=>"content"));
          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C2_2','bannerType'=>"content"));
          echo $reviewWidget['html'];

          $this->load->view('InstitutePage/InstituteCtaWidget'); 

          $this->load->view('InstitutePage/AdmissionsWidget');
          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1','bannerType'=>"content"));
          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1_2','bannerType'=>"content"));

          $this->load->view('InstitutePage/HighlightsWidget'); 
          if(!empty($facilities['facilities']) && count($facilities['facilities']) > 0)
          {
            $this->load->view('InstitutePage/InfrastructureWidget');  
          }?>

          <!-- CHP Interlinking -->
          <div class="new-row">
            <?php $this->load->view('common/chpInterLinking');?>
          </div>
          <!-- CHP Interlinking END-->
          
        <?php
          //AnA Widget
	        echo $anaWidget['html'];
        ?>
        
        
          <?php
	      //	Gallery Widget
          echo $galleryWidget['html'];

          $this->load->view('InstitutePage/CollegeCutoffWidget');

          //recommended listing widget
          echo modules::run('nationalInstitute/InstituteDetailPage/getRecommendedListingWidget',$listing_id,$listing_type, 'alsoViewed',$courseIdsMapping);
          
          //scholarship widget
          $this->load->view('InstitutePage/ScholarshipWidget');

          $this->load->view('InstitutePage/EventsWidget');
         
          
          //article widget
          echo $articleWidget['html'];

          //contact widget
          echo $schemaContact;
          echo $contactWidget; 

          if($listing_type == 'university'){
            $this->load->view('InstitutePage/CollegesList'); 
          }

          // group recommendation
          echo modules::run('nationalInstitute/InstituteDetailPage/getRecommendedListingWidget',$listing_id,$listing_type, 'similar');  
          
          //recommended listing widget
          //echo modules::run('nationalInstitute/InstituteDetailPage/getAlsoViewedListingWidget',$listing_id,$listing_type, 'similar');
          $this->load->view('InstitutePage/InterestedInstituteWidget');
      ?>
   
   <!--gallery show-->
   <div class="gallry-slider photonLayer" id="gallery-slider" style="display:none">
     
    </div>
 </div>

 <a href="javascript:void(0);" class="scrollToTop"></a>

</div>
