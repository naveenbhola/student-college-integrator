<?php 
    if(empty($totalCountSubtext) && $pageType != 'admission') {
        $totalCountSubtext = "";
    }
    $GA_Tap_On_DBrochure = 'DBROCHURE';
    $GA_Tap_On_Compare = 'COMPARE';
    switch ($pageType) {
        case 'articles':
            $dBrochureTrackingKeyId = 945;
            $dBrochureRecoId = 1019;
            $compareRecoId 	 = 1020;
            $applyNowRecoId  = 1027;
            $shortlistRecoId = 1022;

            $compareTrackingId        = 1179;
            $shortlistTrackingId      = 1178;
            $askNowTrackingId         = 1180;
            $contactDetailsTrackingId = 1181;

            $totalCountSubtext = " News & Notifications ".$currentYear;
            break;
        case 'questions':
            $dBrochureTrackingKeyId = 947;

            $dBrochureRecoId  = 1030;
            $compareRecoId 	  = 1031;
            $applyNowRecoId   = 1028;
            $shortlistRecoId  = 1032;

            $compareTrackingId        = 1171;
            $shortlistTrackingId      = 1170;
            $askNowTrackingId         = 1172;
            $contactDetailsTrackingId = 1173;
            
            $totalCountSubtext = " Answered Questions";
	    if($contentType == 'discussion'){
		$totalCountSubtext = " Discussions";
	    }
            break;
        case 'reviews':
            $dBrochureTrackingKeyId = 943;

            $dBrochureRecoId   = 1015;
            $compareRecoId 	   = 1016;
            $applyNowRecoId    = 1017;
            $shortlistRecoId   = 1018;

            $compareTrackingId        = 1162;
            $shortlistTrackingId      = 1163;
            $askNowTrackingId         = 1164;
            $contactDetailsTrackingId = 1165;

            $totalCountSubtext = "  Reviews on Placements, Faculty & Facilities";
            break;
        case 'admission':
            $GA_Tap_On_DBrochure = 'DBROCHURE';
            $dBrochureTrackingKeyId = 974;
            $dBrochureRecoId = 1033;
            $compareRecoId   = 1034;
            $applyNowRecoId  = 1029;
            $shortlistRecoId = 1035;
            

            $compareTrackingId        = 1187;
            $shortlistTrackingId      = 1186;
            $askNowTrackingId         = 1188;
            $contactDetailsTrackingId = 1189;

            break;
        case 'scholarships':
            $totalCountSubtext = " Scholarships";
            $dBrochureTrackingKeyId   = 1555;
            $dBrochureRecoId          = 1591;
            $compareRecoId            = 1593;
            $applyNowRecoId           = 1597;
            $shortlistRecoId          = 1595;
            
            $compareTrackingId        = 1565;
            $shortlistTrackingId      = 1567;
            $askNowTrackingId         = 1569;
            $contactDetailsTrackingId = 1571;
            break;
        default:
            break;
    }
  	if($allReviewsCount>0 && $pageType=='reviews'){
                $totalCountText = "(".$allReviewsCount.")";
        }
        if($totalElements>0 && $pageType!='reviews'){
                $totalCountText = "(".$totalElements.")";
        }
    
?>
<div class="art-div clrTpl">
    <div class="col-md-8">
        <h1 class="head-1" style="font-weight:600">
            <a href="<?php echo $instituteUrl;?>" ><?php echo htmlentities(str_replace(", "," ",$instituteNameWithLocation));?></a><?php echo $totalCountSubtext; ?>
        </h1>
        <span class="art-count allContCountText"><?php echo $totalCountText; ?></span>
        <?php 
        if(!empty($topWidgetData)){
            ?>
            <div class="grade-p">
                <?php 
                    end($topWidgetData);
                    $lastKey = key($topWidgetData);
                    foreach ($topWidgetData as $key => $value) {
                        echo $value;
                        if(!empty($instituteToolTipData[$key])){
                            ?>
                            <div class="tp-block">
                                <i class="info-icn"></i>
                                <div class="tooltip top">
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner"><?php echo $instituteToolTipData[$key]['helptext']; ?></div>
                                </div>
                            </div>
                            <?php
                        }
                        if($key != $lastKey){
                            echo "<span> | </span>";
                        }
                    }
                ?>                
            </div>
            <?php
        }
        ?>
    </div>
    
    <a class="shrt-list shortlistCta" tracking-id="<?=$shortlistTrackingId?>" cta-type="shortlist" customcallback="listingShortlistCallback" customActionType="<?=$productName?>">Shortlist</a>

    <div class="col-md-4 right-text" id="CTASection">
        <div class="dot-c" ga-attr="ShowMoreCta">
           <span></span>
            <div class="dot-ul">
              <ul>
                <li class="askQuestion"><a tracking-id="<?=$askNowTrackingId?>">Ask Question</a></li>
                <?php if($showContactCTA){?>
                <li class="contactDetails" tracking-id="<?=$contactDetailsTrackingId?>" customActionType="listingContactDetail" cta-type="contact_details" customCallBack="contactDetailCallback" custom-toast-msg="<?=$contactToastMsg?>"><a>Get Contact Details</a></li>
                <?php } ?>
              </ul>
          </div>
        </div>

        <a tracking-id="<?=$compareTrackingId?>" class="btn-secondary addToCompare">Add to Compare</a>
        
        <input type="hidden" id="recoWidgetTrackingIds" brchr-reco-id="<?=$dBrochureRecoId?>" compr-reco-id="<?=$compareRecoId?>" apply-reco-id="<?=$applyNowRecoId?>" shortlst-reco-id="<?=$shortlistRecoId?>" value=""/>

        <a href="javascript:void(0);" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" class="btn-primary btn-pm" ga-attr="<?=$GA_Tap_On_DBrochure;?>" onclick="ajaxDownloadEBrochure(this,<?php echo $listing_id;?>,'<?php echo $listing_type;?>','<?php echo addslashes(htmlentities($instituteName));?>','<?=$productName?>','<?php echo $dBrochureTrackingKeyId;?>','','','','')">Apply Now</a>
    </div>
    <?php if(!empty($seoHeadingText)) { ?>
        <h2 class="reviews-Hd-det"><?php echo $seoHeadingText;?></h2>
    <?php } ?>
</div>
