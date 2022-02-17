<p class="admission-help-text" style="margin-left: 220px;"><strong><span class="tick-mark">&#10004;</span> Admission help available for this university</strong></p>
<div class="verified-consultant-section">
    <div class="flLt verified-consultant-infoHead">
        <i class="cate-sprite verified-cons-user-icon"></i>
        <strong>Shiksha verified consultant<span id="consultantSControl">s</span> in </strong>
        <div style="position:relative">
            <span id="<?=$courseObj->getId();?>CityName"><?=$currentRegion['regionName']?></span>
            <a href="javascript:void(0);" onclick="showHideCityChanger('<?=$courseObj->getId()?>',this); studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'catPageConsultantChangeRegionLeft');">Change<i class="cate-sprite change-arrow arrowControl"></i></a>
            <div id="<?=$courseObj->getId()?>CityChange" class="change-city-layer" style="display: none;z-index: 1;">
                <ul>
                    <?php foreach($regionConsultantMapping as $regionId => $region){ ?>
                        <li>
                            <a href="javascript:void(0);" id="cityChangeLink<?=$regionId?>" onclick="changeConsultantCity('<?=$courseObj->getId()?>','<?=$regionId?>',this); studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'catPageConsultantChangeRegionLeft');"><?=$region['regionName']?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
       </div>
        <i class="cate-sprite verified-user-pointer"></i>
    </div>
    <?php foreach($regionConsultantMapping as $regionId => $regionData){?>
        <div class="verified-consultant-list" id="<?=$courseObj->getId()?>_<?=$regionId?>_consultantBlock" style="display:none">
            <?php
            if(empty($regionConsultantMapping[$regionId]['consultantIds'])){ 
                $this->load->view("categoryList/abroad/widget/categoryPageListingConsultantZeroResult"); 
            }else{
            ?>
                <ul>
                    <?php foreach($regionData['consultantIds'] as $consultantId){ ?>
                        <li>
                            <div class="verified-cons-height">
                                <a href="<?=$consultantData[$consultantId]['consultantProfileUrl']?>" target="_blank">
                                    <strong><?=formatArticleTitle($consultantData[$consultantId]['consultantName'],50)?></strong>
                                </a>
                                <span><?=formatArticleTitle($consultantData[$consultantId]['regions'][$regionId]['office']['officeAddress'],40)?></span>
                            </div>
                            <?php if(!empty($consultantData[$consultantId]['regions'][$regionId]['office']['displayNumber'])){?>
                                <p class="phone-info">
                                    <i class="cate-sprite verified-phone-icon-2"></i>
                                    <strong><?=$consultantData[$consultantId]['regions'][$regionId]['office']['displayNumber']?></strong>
                                </p>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
    <?php } ?>
</div>
