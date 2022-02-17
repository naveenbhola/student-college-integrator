<?php 
    if(empty($totalCountSubtext) && $pageType != 'admission') {
        $totalCountSubtext = "";
    }
    $GA_Tap_On_DBrochure = 'DBROCHURE';
    switch ($pageType) {
        case 'articles':
            $dBrochureTrackingKeyId   = 946;
            $dBrochureRecoId          = 1019;
            $compareRecoId            = 1020;
            $applyNowRecoId           = 1027;
            $shortlistRecoId          = 1022;
            
            $compareTrackingId        = 1182;
            $askNowTrackingId         = 1183;
            $contactDetailsTrackingId = 1184;
            $shortlistTrackingId      = 1185;
            $totalCountSubtext = " - Articles";
            break;
        case 'questions':
            $dBrochureTrackingKeyId = 948;

            $dBrochureRecoId  = 1030;
            $compareRecoId 	  = 1031;
            $applyNowRecoId   = 1028;
            $shortlistRecoId  = 1032;
            
            $compareTrackingId        = 1177;
            $askNowTrackingId         = 1174;
            $contactDetailsTrackingId = 1175;
            $shortlistTrackingId      = 1176;
            $totalCountSubtext = " - Questions";
	    if($contentType == 'discussion'){
		$totalCountSubtext = " - Discussions";
	    }
            break;
        case 'reviews':
            $dBrochureTrackingKeyId = 944;

            $compareTrackingId        = 1039;
            $askNowTrackingId         = 1167;
            $contactDetailsTrackingId = 1168;
            $shortlistTrackingId      = 1169;
            $totalCountSubtext = " - Reviews";
            break;
        case 'admission':
            $GA_Tap_On_DBrochure = 'DBROCHURE';
            $dBrochureTrackingKeyId = 975;
            $dBrochureRecoId = 1033;
            $compareRecoId   = 1034;
            $applyNowRecoId  = 1029;
            $shortlistRecoId = 1035;

            $compareTrackingId        = 1193;
            $askNowTrackingId         = 1190;
            $contactDetailsTrackingId = 1191;
            $shortlistTrackingId      = 1192;
            // $totalCountSubtext = " Admission ".date("Y");
            break;
        case 'scholarships':
            $totalCountSubtext = " - Scholarships";
            $dBrochureTrackingKeyId   = 1577;
            // $dBrochureRecoId          = 1033;
            // $compareRecoId            = 1034;
            // $applyNowRecoId           = 1029;
            // $shortlistRecoId          = 1035;
            
            $compareTrackingId        = 1579;
            $shortlistTrackingId      = 1581;
            $askNowTrackingId         = 1583;
            $contactDetailsTrackingId = 1585;
            break;
        default:
            # code...
            break;
    }
        if($allReviewsCount>0 && $pageType=='reviews'){
                $totalCountText = "(".$allReviewsCount.")";
        }
        if($totalElements>0 && $pageType!='reviews'){
                $totalCountText = "(".$totalElements.")";
        }

?>

<div class="top-header h-shadow" id="fixed-card">
  <div class="new-container new-header">
   <div class="n-col clear" >
    <div class="new-row"  id="fixed-cta">
      

    <div class="col-md-8">
        <p class="head-1 stcky-adHd">
            <a href="<?php echo $instituteUrl;?>"><?php echo htmlentities($instituteNameWithLocation);?></a><?php echo $totalCountSubtext; ?>
        </p>
        <span class="art-count allContCountText"><?php echo $totalCountText; ?></span>
    </div>
    
    <div class="col-md-4 right-text">
        <div class="dot-c" ga-attr="ShowMoreCtaSticky">
           <span></span>
            <div class="dot-ul">
              <ul>
                <li class="askQuestion"><a tracking-id="<?=$askNowTrackingId?>">Ask Question</a></li>
                <?php if($showContactCTA){?>
                    <li class="contactDetails" tracking-id="<?=$contactDetailsTrackingId?>" customActionType="listingContactDetail" cta-type="contact_details" customCallBack="contactDetailCallback" custom-toast-msg="<?=$contactToastMsg?>"><a>Get Contact Details</a></li>
                <?php } ?>
                <li class="shortlistCta" tracking-id="<?=$shortlistTrackingId?>" cta-type="shortlist" customcallback="listingShortlistCallback" customActionType="<?=$productName?>"><a>Shortlist</a></li>
              </ul>
          </div>
        </div>
        <a tracking-id="<?=$compareTrackingId?>" class="btn-secondary addToCompare">Add to Compare</a>
        <a href="javascript:void(0);" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" class="btn-primary btn-pm" ga-attr="<?=$GA_Tap_On_DBrochure;?>" onclick="ajaxDownloadEBrochure(this,<?php echo $listing_id;?>,'<?php echo $listing_type;?>','<?php echo addslashes(htmlentities($instituteName));?>','<?=$productName?>','<?php echo $dBrochureTrackingKeyId;?>','','','','')">Apply Now</a>
    </div>

   </div> 
    
    <?php
        if($pageType!="admission"){
            $this->load->view("AllContentPage/widgets/filters");
        }
    ?>
   </div>
  </div>     
</div>
