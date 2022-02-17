<section class="s-container">
  <?php $this->load->view('mcommon5/AMP/dfpBannerView',array('bannerPosition' => 'leaderboard'));?>
<?php   $this->load->view("institute/AMP/Widgets/instituteTopSection");
        $this->load->view("institute/AMP/Widgets/navWidget");   ?>
<?php 
        $this->load->view("institute/AMP/Widgets/coursesOffered");
        if($dfpData['client'] == 1){
          $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "client"));
        }

        $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "LAA"));
        $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "LAA1"));

        if(trim($reviewWidget['html'])){
            echo $reviewWidget['html'];
        }

        

        if(!empty($admissionInfo) || (!empty($examList) && (count($examList) > 0)) )
        { 
          $this->load->view("institute/AMP/Widgets/AdmissionsWidget");
        }
        $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "AON"));
        $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "AON1"));
        ?>
           
       <?php 
        $this->load->view("institute/AMP/Widgets/highlights");


        //Facilities
        if(!empty($facilities['facilities']) && count($facilities['facilities']) > 0)
        {
          $this->load->view("institute/AMP/Widgets/facilities");    
        }


        $this->load->view('mcommon5/chpInterLinking');

        if(trim($anaWidget['html'])){
            echo $anaWidget['html'];
        }


        //ask current students
        echo modules::run('mobile_listing5/InstituteMobile/populateAnAProposition', $listing_id, $listing_type,true);

        if(trim($galleryWidget)){
            echo $galleryWidget;
        }

	//recommended listing widget
	echo modules::run('mobile_listing5/InstituteMobile/getRecommendedListingWidget',$listing_id,$listing_type, 'alsoViewed', $courseIdsMapping, true);

        $this->load->view("institute/AMP/Widgets/scholarship");
        $this->load->view("institute/AMP/Widgets/CollegeCutoffWidget");


        $this->load->view("institute/AMP/Widgets/events");
        
        if(trim($articleWidget)){
            echo $articleWidget;
        }


        if(trim($schemaContact)){
            echo $schemaContact;
        }


	      //group listing widget
        echo modules::run('mobile_listing5/InstituteMobile/getRecommendedListingWidget',$listing_id,$listing_type, 'similar', $courseIdsMapping, true);

        if(trim($contactWidget)){
            echo $contactWidget;
        }

        if($listing_type == 'university'){
          $this->load->view('institute/AMP/Widgets/collegeList');
        }
        
        $this->load->view("institute/AMP/Widgets/brochureCTA");
        $this->load->view("institute/AMP/Widgets/recoLinks");
  		
  		if($isMultilocation){
            echo modules::run('mobile_listing5/InstituteMobile/getMultiLocationLayer',$listing_id,$listing_type,$instituteObj,$currentLocationObj,$courseIdsMapping,true);
        }
  ?>


      
   

     </section>


   	<?php
    $this->load->view("institute/AMP/Widgets/instituteCTASticky");
    ?>
  
  <!--additional details-->
     <amp-lightbox id="additional-dtls" layout="nodisplay" scrollable>
       <div class="lightbox" on="tap:additional-dtls.close" role="button" tabindex="0">
          <a class="cls-lightbox  color-f font-w6 t-cntr">&times;</a>
          <div class="m-layer">
            <div class="min-div color-w catg-lt pad10">
              <div class="m-btm padb">
                <strong class="block m-btm color-3 f14 font-w6">Graduation</strong>
                  <p class="color-3 l-18 f12">Candidate appearing for the final year bachelor degree/equivalent qualification examination can also apply on provisional basis.</p>
              </div>
            </div>
          </div>
       </div>
     </amp-lightbox>
