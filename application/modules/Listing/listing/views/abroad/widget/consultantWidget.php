<?php
    $countForFirstTime = 0;
    $GAPage="";
    if($listingType=="university")
    {
        $GAPage = "ABROAD_UNIVERSITY_PAGE";
        $consultantTrackingPageKeyId = 387;
    } 
    if($listingType == "department")
    {
        $GAPage = "ABROAD_DEPARTMENT_PAGE";
        $consultantTrackingPageKeyId = 398;
    }
?>
<div class="consultant-box clearwidth">
    <div style="position:relative" class="consultant-que-box">
        <strong>Following consultant<span class="consultantRegionSControl"><?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])==1?'':'s'?></span> can help you with admission related guidance for this <?=empty($courseObj)?(empty($departmentObj)?(empty($universityObj)?'obviously, this will never happen':'university'):'department'):'course'?></strong>
        <p>Found <span id="consultantWidgetConsultantCount"><?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])?></span> consultant<span class="consultantRegionSControl"><?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])==1?'':'s'?></span> located in:</p>
        <div class="cutsom-cons-dropdwn">
            <i class="listing-sprite consultant-loc-icon"></i>
            <select class="regionSelector" onchange="changeRegion(this);studyAbroadTrackEventByGA('<?php echo $GAPage; ?>', 'rightWidgetConsultantChangeRegion'); ">
                <?php foreach($regionConsultantMapping as $regionId => $data){ ?>
                    <option value="<?=$regionId?>"><?=htmlentities($data['regionName'])?></option>
                <?php } ?>
            </select>
        </div>
        <i class="listing-sprite consultant-pointer"></i>
    </div>
    <div id="listingConsultantWidgetVisibilityDiv" class="consultant-contact-sec">
        <?php foreach($regionConsultantMapping as $regionId => $regionData) { ?>
            <?php if(empty($regionData['consultantIds'])){ ?>
                <div class='courseConsultantTab_<?=$regionId?> noResultDivBlock'>
                    <?php $this->load->view('listing/abroad/widget/zeroConsultantWidget'); ?>
                </div>
            <?php }else{ ?>
                <ul style="<?=($regionId == $currentRegion['regionId'])?'':'display:none;'?>" class="courseConsultantTab_<?=$regionId?>">
                    <?php foreach($regionData['consultantIds'] as $tconsultantId){
                        $consultantEnquiryDataObj =
                            array
                                (
                                    'source'        => $consultantSources['consultantWidget'],
                                    'regionId'      => $regionId,
                                    'consultantTrackingPageKeyId' => $consultantTrackingPageKeyId,
                                    'consultant'   =>
                                          array(
                                                'consultantId'    => $consultantData[$tconsultantId]['consultantId'],
                                                'consultantName'  => $consultantData[$tconsultantId]['consultantName'],
                                                'logoUrl'         => $consultantData[$tconsultantId]['consultantLogo'],
                                                'consultantUrl'   => $consultantData[$tconsultantId]['consultantProfileUrl'],
                                                'universityName'  => $universityObj->getName()
                                               )
                                );
                    ?>
                        <li>
                            <div style="margin-bottom:15px; display:table;" class="clearwidth">
                                <div class="consultant-figure">
                                    <img width="100" height="68" alt="<?=htmlentities($consultantData[$tconsultantId]['consultantName'])?>" title="<?=htmlentities($consultantData[$tconsultantId]['consultantName'])?>" src="<?=$consultantData[$tconsultantId]['consultantLogo']?>">
                                </div>
                                <div class="consultant-univ-detail consultantToolTip">
                                    <strong><a href="<?=$consultantData[$tconsultantId]['consultantProfileUrl']?>" target="_blank"><?=htmlentities($consultantData[$tconsultantId]['consultantName'])?></a></strong><br>
                                    <span class="font-11"><?=$consultantData[$tconsultantId]['regions'][$regionId]['office']['officeAddress']?></span>
                                    <?php if($consultantData[$tconsultantId]['isOfficialRepresentative'] == 'yes'){?>
                                        <div class="offered-rep-box" onmouseover="showToolTipConsultantVerified(this)" onmouseout="showToolTipConsultantVerified(this)" style="position: relative;">
                                            <i class="listing-sprite off-rep-icon"></i>
                                            <span>Official representative</span>
                                            <div style="left: auto; display:none; top: 32px;padding: 6px; width: 190px" class="tooltip-info">
                                            <i class="common-sprite verified-up-pointer"></i>
                                            This consultant is an official representative of this university
                                        </div>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="consultant-button-area">
                                <a style=" border-radius:0 !important;" onclick=" studyAbroadTrackEventByGA('<?php echo $GAPage;?>', 'RightWidgetConsultantKnowMoreButton'); " class="button-style" href="<?=$consultantData[$tconsultantId]['consultantProfileUrl']?>" target="_blank">Know more</a>
                                <a class="contact-btn2" href="Javascript:void(0);" onclick = "loadStudyAbroadForm('<?=base64_encode(json_encode($consultantEnquiryDataObj))?>','/consultantEnquiry/ConsultantEnquiry/getConsultantEnquiryForm','consultantEnquiryFormContainer');  studyAbroadTrackEventByGA('<?php echo $GAPage; ?>', 'rightWidgetConsultantContactForm');">Contact</a> 
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        <?php } ?>
    </div>
</div>
