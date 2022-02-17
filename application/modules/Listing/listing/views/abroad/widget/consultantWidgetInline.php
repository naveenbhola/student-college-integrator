<div class="widget-wrap clearwidth">
    <h2 id="consultantTabHeading" style="font-size:12px">    
    Following consultant<span class="consultantRegionSControl"><?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])==1?'':'s'?></span> can help you with admission related guidance for this university
   </h2>
    <div>
    <strong class="consultantToolTip verified-text-title-2 flLt" >
        <span style="position: relative"><span onmouseover="showToolTipConsultantVerified(this)" onmouseout="showToolTipConsultantVerified(this)" ><i class="listing-sprite cons-verified-icon"  ></i> Shiksha Verified</span> consultant<span class="consultantRegionSControl"><?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])==1?'':'s'?></span> located in:
            <div style="display:none;font-size:12px;font-weight:normal !important;line-height:20px;
    padding:10px;top:23px; left:10px;width:330px;" class="tooltip-info"><i class="common-sprite verified-up-pointer"></i> All Consultant information available on Shiksha is independently verified by internal audit team.</div>
        </span>
    </strong>
    <div class="cutsom-cons-dropdwn" style="margin-left:290px;">
        <i class="listing-sprite consultant-loc-icon"></i>
            <select class="regionSelector" onchange="changeRegion(this);studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'inlineWidgetConsultantChangeRegion'); " id="tabSelectorForRegion">
                <?php foreach($regionConsultantMapping as $regionId => $data){ ?>
                    <option value="<?=$regionId?>"><?=htmlentities($data['regionName'])?></option>
                <?php } ?>
        </select>
    </div>
        <div class="clearwidth"></div>
    </div>
        <div id="courseConsultantInlineVisibilityDiv">
        <?php foreach($regionConsultantMapping as $regionId => $regionData){?>
            <?php if(empty($regionData['consultantIds'])){ ?>
                <div class='courseConsultantTab_<?=$regionId?> noResultDivBlock'>
                    <?php $this->load->view('listing/abroad/widget/zeroConsultantPage'); ?>
                </div>
            <?php }else{ ?>
                <ul class="consultant-detail-list courseConsultantTab_<?=$regionId?>" style="<?=($regionId == $currentRegion['regionId'])?'':'display:none;'?>">
                    <?php foreach($regionData['consultantIds'] as $tconsultantId){
                        $consultantEnquiryDataObj = array(
                                                            'source'        => $consultantSources['consultantTab'],
                                                            'regionId'      => $regionId,
                                                            'consultantTrackingPageKeyId' => 377,
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
                        <div class="consultant-sml-image">
                            <img width="100" height="68" alt="<?=htmlentities($consultantData[$tconsultantId]['consultantName'])?>" title="<?=htmlentities($consultantData[$tconsultantId]['consultantName'])?>" src="<?php echo ($consultantData[$tconsultantId]['consultantLogo']);?>">
                        </div>
                        <div class="consultant-detail flLt consultantToolTip">
                            <a href="<?=$consultantData[$tconsultantId]['consultantProfileUrl']?>" target="_blank"><strong><?=htmlentities($consultantData[$tconsultantId]['consultantName'])?></strong></a><br>
                            <span><?=$consultantData[$tconsultantId]['regions'][$regionId]['office']['officeAddress']?></span>
                            <?php if($consultantData[$tconsultantId]['regions'][$regionId]['office']['displayNumber']!=""){?>
                            <div class="consultant-contact-info"><i class="common-sprite cons-contct-icon"></i><strong><?=$consultantData[$tconsultantId]['regions'][$regionId]['office']['displayNumber']?></strong></div>
                            <?php }?>
                            <?php if($consultantData[$tconsultantId]['isOfficialRepresentative'] == 'yes'){?>
                            <div class="offered-rep-box" onmouseover="showToolTipConsultantVerified(this)" onmouseout="showToolTipConsultantVerified(this)" style="position: relative">
                                <i class="listing-sprite off-rep-icon"></i>
                                <span>Official representative</span>
                                <div style="left: auto; display:none; top: 32px; width:254px; padding:8px;" class="tooltip-info">
                                    <i class="common-sprite verified-up-pointer"></i>
                                    This consultant is an official representative of this university
                                </div>
                            </div>
                            <?php }?>
                        </div>
                        <div class="consultant-button-area flRt">
                            <a style=" border-radius:0 !important;" class="button-style" onclick=" studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'inlineWidgetConsultantKnowMoreButton'); " href="<?=$consultantData[$tconsultantId]['consultantProfileUrl']?>" target="_blank">Know more</a>
                            <a class="contact-btn2" href="Javascript:void(0);" onclick = "loadStudyAbroadForm('<?=base64_encode(json_encode($consultantEnquiryDataObj))?>','/consultantEnquiry/ConsultantEnquiry/getConsultantEnquiryForm','consultantEnquiryFormContainer'); studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'inlineWidgetConsultantContactForm');">Contact</a>  
                        </div>
                        <div class="clearfix"></div>
                    </li>

                    <?php } ?>
                </ul>
                <?php } ?>
        <?php } ?>
          </div>
</div>