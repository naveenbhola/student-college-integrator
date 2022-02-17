<div class="popup_layer" id="facilityDetail" style="display:none">
    
</div>
<div class="popup_layer" id="moreLinks" style="display:none">
        <div class="hlp-popup nopadng">
            <a href="javascript:void(0);" class="hlp-rmv" data-rel ='back' >&times;</a>
            <div class="glry-div amen-div">
                <div class="hlp-info">
                    <div class="loc-list-col">
                        <div class="prm-lst">
                        </div>
                    </div>
                </div>
            </div>
        </div>      
</div>
<?php
    $this->load->view("institute/widgets/toolTipLayer"); 
    $this->load->view("institute/widgets/courseCompareLayer"); 
    $this->load->view("institute/widgets/shortListLayer"); 
?>
<div class="popup_layer" id="commonDetail" style="display:none">

</div>

<?php
    $this->load->view("institute/widgets/instituteTopSection"); 
?>

<div id="tab-section" class="nav-tabs">
    <?php $this->load->view("institute/widgets/navTabs"); ?>
</div>

<div class="new-container panel-pad">
    <?php 
        if($listing_type == 'university')
            $this->load->view('institute/widgets/collegeList');
        $this->load->view("institute/widgets/coursesOffered");
         $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "LAA"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "LAA1"));
        
        if(trim($reviewWidget['html']))
            echo $reviewWidget['html'];


        if(!empty($sponsoredWidgetData)) {
            $this->load->view('institute/widgets/sponsoredInstituteWidget'); 
        }
        $this->load->view('institute/widgets/AdmissionsWidget');

                $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "AON"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "AON1"));
        
        $this->load->view("institute/widgets/highlights");


        if(!empty($facilities['facilities']) && count($facilities['facilities']) > 0)
        {
            $this->load->view("institute/widgets/facilities");    
        }
        
        if(trim($anaWidget['html']))
            echo $anaWidget['html'];
	
	?>
	<div id='askProposition'>
        	<div style='text-align: center; margin-top: 10px; margin-bottom: 10px;'><img border='0' alt='' id='loadingImage1' class='small-loader' style='border-radius:50%;width: 60px;border: 1px solid rgb(229, 230, 230)' src='//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif'/></div>
	</div>
	<?php

        echo $galleryWidget;

		//recommended listing widget
        echo modules::run('mobile_listing5/InstituteMobile/getRecommendedListingWidget',$listing_id,$listing_type, 'alsoViewed', $courseIdsMapping);
        
        $this->load->view("institute/widgets/scholarship");        
		$this->load->view("institute/widgets/CollegeCutoffWidget");        
        $this->load->view("institute/widgets/events");
        //article widget
        echo $articleWidget;

        //group listing widget
        echo modules::run('mobile_listing5/InstituteMobile/getRecommendedListingWidget',$listing_id,$listing_type, 'similar');

        //contact widget
        echo $schemaContact;
        echo $contactWidget;
        
        $this->load->view("institute/widgets/brochureCTA");
        $this->load->view("institute/widgets/recoLinks");
        $this->load->view("institute/widgets/locationLayer");
    ?> 
</div>

<?php 
if($listing_type == 'university')
{
    $GA_currentPage = 'UNIVERSITY DETAIL PAGE';
    $GA_commonCTA_Name = '_UNIVERSITYDETAIL_MOBLDP';
    $GA_Tap_On_Action = 'COMPARE_STICKY';
    $GA_Tap_On_CTA = 'DBROCHURE_STICKY';
    $dBrochureStickyTrackingKeyId = 1081;
    $compareStickyTrackingKeyId = 1082;
    $pageType = 'universityDetailPage';
}
else
{
    $GA_currentPage = 'INSTITUTE DETAIL PAGE';
    $GA_commonCTA_Name = '_INSTITUTEDETAIL_MOBLDP';
    $GA_Tap_On_Action = 'COMPARE_STICKY';
    $GA_Tap_On_CTA = 'DBROCHURE_STICKY';
    $dBrochureStickyTrackingKeyId = 951;
    $compareStickyTrackingKeyId = 952;
    $pageType = 'instituteDetailPage';
}
?>

    

<div class="com-shrtlst-sec" id="stickyCTA" style="display:none;">
    <div class="lcard">
    <?php if($listing_type == 'university') { ?>
        <div class="compare-site-tour com-col"><a class="btn-mob-blue" href="javascript:void(0);" ga-attr="<?=$GA_Tap_On_Action?>" onclick="showCourseCompareLayerForUniversity('<?php echo $listing_type;?>','<?php echo $listing_id?>','<?php echo $compareStickyTrackingKeyId;?>');">Compare</a></div>
    <?php } else { ?>
        <div class="compare-site-tour com-col"><a class="btn-mob-blue" href="javascript:void(0);" ga-attr="<?=$GA_Tap_On_Action?>" onclick="showCourseCompareLayer('<?php echo $compareStickyTrackingKeyId;?>');">Compare</a></div>
    <?php } ?>
        <!-- add tracking key id 951 whenever download brochure functionality implemented-->
        <div class="deb-site-tour com-col"><a class="btn-mob" ga-attr="<?=$GA_Tap_On_CTA;?>" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" onclick="downloadCourseBrochure('<?php echo $listing_id?>','<?php echo $dBrochureStickyTrackingKeyId;?>',{'pageType':'<?php echo $pageType;?>','listing_type':'<?php echo $listing_type;?>','callbackFunctionParams':{'pageType':'<?php echo $pageType;?>','thisObj':this}});">Request Brochure</a></div>
    </div>
</div>
<script>
  var GA_currentPage = "<?php echo $GA_currentPage;?>";
  var ga_page_name = "<?php echo $GA_currentPage;?>";
  var ga_user_level = "<?php echo $GA_userLevel;?>";
  var ga_commonCTA_name = "<?php echo $GA_commonCTA_Name;?>";
</script>