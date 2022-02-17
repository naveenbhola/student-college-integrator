<div class="consultant-tab course-detail-tab overview-details" style="display:none;">
    <div class="course-detail-mid flLt" style="width:777px;">
        <h2 id="consultantTabHeading" style="height: 32px;">
            Following consultant<span class="consultantRegionSControl"><?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])==1?'':'s'?></span> can help you with admission related guidance for this course
        </h2>
        <div class="cons-scrollbar1 scrollbar1 clearwidth">
                <div class="cons-scrollbar scrollbar" style="visibility:hidden;height:400px;">
                    <div class="track">
                        <div class="thumb"></div>
                    </div>
                </div>
                <div class="viewport" style="height:299px">
                    <div class="overview" style="width:98%;">

        <strong class="verified-text-div consultantToolTip" style="position: relative; margin-top:0;">
            All consultants here are
            <span  onmouseover="showToolTipConsultantVerified(this)" onmouseout="showToolTipConsultantVerified(this)" > <i class="listing-sprite cons-verified-icon" ></i> Shiksha Verified
            <div style="left: auto; display:none;" class="tooltip-info">
                    <i class="common-sprite verified-up-pointer"></i>
                    All Consultant information available on Shiksha is independently verified by internal audit team.
            </div>
            </span>
        </strong>
        <div>
            <strong style="display:inline-block" class="flLt">Showing consultant<span class="consultantRegionSControl"><?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])==1?'':'s'?></span> in:</strong>
            <div style="margin-left:185px;" class="cutsom-cons-dropdwn">
                <i class="listing-sprite consultant-loc-icon"></i>
                <select class="regionSelector" onchange="changeRegion(this);$j('.consultant-tab .scrollbar1').tinyscrollbar_update(); studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'leftTabConsultantChangeRegion');"  id="tabSelectorForRegion">
                    <?php foreach($regionConsultantMapping as $regionId => $data){ ?>
                        <option value="<?=$regionId?>"><?=htmlentities($data['regionName'])?></option>
                    <?php } ?>

                </select>
            </div>
            <div class="clearwidth"></div>
        </div>
      <div id="courseConsultantTabVisibilityDiv">
            <?php
                foreach($regionConsultantMapping as $regionId => $regionData) { ?>
                <?php if(empty($regionData['consultantIds'])){ ?>
                    <div class='courseConsultantTab_<?=$regionId?> noResultDivBlock'>
                        <?php $this->load->view('listing/abroad/widget/zeroConsultantPage'); ?>
                    </div>
                <?php }else{ ?>
                    <ul class="consultant-detail-list courseConsultantTab_<?=$regionId?>" style="<?=($regionId == $currentRegion['regionId'])?'':'display:none;'?>">
                        <?php 
                        $count = count($regionData['consultantIds']);
                        $i=0;
                        foreach($regionData['consultantIds'] as $tconsultantId)
                        {
                            $i++;
                            $consultantEnquiryDataObj =
                                array
                                    (
                                        'source'        => $consultantSources['consultantTab'],
                                        'regionId'      => $regionId,
                                        'consultantTrackingPageKeyId' => 376,
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
                            <li  <?php if($i==$count){?> style="padding-bottom:55px;" <?php } ?>>
                                <div class="consultant-figure flLt" style="margin-right:15px;">
                                    <img width="100" height="68" alt="<?=htmlentities($consultantData[$tconsultantId]['consultantName'])?>" title="<?=htmlentities($consultantData[$tconsultantId]['consultantName'])?>" class="lazy" src="" data-original="<?=$consultantData[$tconsultantId]['consultantLogo']?>">
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
                                <div class="consultant-button-area flRt" style="padding-top:18px;">
                                    <a style=" border-radius:0 !important;" onclick=" studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'leftTabConsultantKnowMoreButton'); " class="button-style" href="<?=$consultantData[$tconsultantId]['consultantProfileUrl']?>" target="_blank">Know more</a>
                                    <a class="contact-btn2" href="Javascript:void(0);" onclick = "loadStudyAbroadForm('<?=base64_encode(json_encode($consultantEnquiryDataObj))?>','/consultantEnquiry/ConsultantEnquiry/getConsultantEnquiryForm','consultantEnquiryFormContainer');  studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'leftTabConsultantContactForm');">Contact</a> 	
                                </div>
                                <div class="clearfix"></div>
                            </li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
            <?php } ?>
              </div>
            </div>
         </div>
        </div>
    </div>
</div>
