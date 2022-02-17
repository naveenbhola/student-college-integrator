<?php
    $consultantEnquiryDataObj =
        array
            (
                'source'        => 'consultant_bellyButton',
                //'regionId'      =>1,
                'consultantTrackingPageKeyId' => 372,
                'consultant'   =>
                      array(
                            'consultantId'    => $consultantObj->getId(),
                            'consultantName'  => $consultantObj->getName(),
                            'logoUrl'         => $consultantObj->getLogo(),
                            'consultantUrl'   => $consultantObj->getCanonicalUrl()
                           )
            );
    if($hasValidSubscription){
?>
<div class="info-list clearwidth">
    <ul>
        <li><a href="Javascript:void(0);" onclick = "loadStudyAbroadForm('<?=base64_encode(json_encode($consultantEnquiryDataObj))?>','/consultantEnquiry/ConsultantEnquiry/getConsultantEnquiryForm','consultantEnquiryFormContainer'); studyAbroadTrackEventByGA('ABROAD_CONSULTANT_PAGE', 'consultantContactForm');" class="button-style bold" style="border-radius:0;"><i class="consultant-sprite consultant-contact-icon"></i>Contact Consultant</a></li>
        <li><a href="<?=($consultantObj->getWebsite())?>" onmousedown="navigateToConsultant('<?=($consultantObj->getWebsite())?>');" target="_blank" rel="nofollow"><i class="consultant-sprite visit-web-icon"></i>Visit consultant website</a> </li>
    </ul>
</div>
<script>
    function navigateToConsultant(address) {
        if (address.indexOf("http://"!=0)) {
            address = "http://"+address;
        }
        $j.ajax({url:'/consultantEnquiry/ConsultantEnquiry/trackConsultantSiteVisit/<?=$consultantObj->getId()?>',
                method:'GET',
                success:function(response){
                    }
                });
    }
</script>
<?php } ?>