<div class="art-div clrTpl">
    

    <a class="shrt-list shortlistCta" tracking-id="<?=$shortlistTrackingId?>" ga-attr="SHORTLIST" cta-type="shortlist" customcallback="listingShortlistCallback" extPageType="<?php echo $pageType; ?>" customactiontype="ND_AllContentPage_<?=strtoupper($pageType);?>">Shortlist</a>

    <div class="col-md-3 right-text no-m-top" id="CTASection">
        <div class="dot-c">
           <span></span>
            <div class="dot-ul">
              <ul>
                <li><a class="askQuestion">Ask Question</a></li>
                <li><a class="contctDetls" tracking-id="<?=$contactDetailsTrackingId?>" customactiontype="listingContactDetail" customcallback="contactDetailCallback" cta-type="contact_details">Get Contact Details</a></li>
              </ul>
          </div>
        </div>

        <a tracking-id="<?=$compareTrackingId?>" class="btn-secondary addToCompare" ga-attr="<?=$GA_Tap_On_Compare;?>">Add to Compare</a>

        <input type="hidden" id="recoWidgetTrackingIds" brchr-reco-id="<?=$dBrochureRecoId?>" compr-reco-id="<?=$compareRecoId?>" apply-reco-id="<?=$applyNowRecoId?>" shortlst-reco-id="<?=$shortlistRecoId?>" value=""/>

        <a href="javascript:void(0);" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" class="btn-primary btn-pm" ga-attr="<?=$GA_Tap_On_DBrochure;?>" onclick="ajaxDownloadEBrochure(this,<?php echo $listing_id;?>,'<?php echo $listing_type;?>','<?php echo addslashes(htmlentities($instituteName));?>','allContentPage','<?php echo $dBrochureTrackingKeyId;?>','<?php echo $dBrochureRecoId;?>','<?php echo $compareRecoId;?>','<?php echo $applyNowRecoId;?>','<?php echo $shortlistRecoId;?>')">Apply Now</a>
    </div>
</div>
