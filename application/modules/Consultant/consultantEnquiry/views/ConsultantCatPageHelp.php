
<style>
.listing-sprite{background:url(/public/images/abroad-listing-sprite1.png); display:inline-block;font-style:none; vertical-align:middle; position:relative}
.consultant-loc-icon{background-position:-238px -96px; width:9px; height:13px;}
.cutsom-cons-dropdwn{border:1px solid #81bcf7; background:#fff; padding:1px 0px 1px 6px; width:207px;  width:207px\9; margin-top:10px; font-weight:bold; font-size:12px;}
.cutsom-cons-dropdwn select {-webkit-appearance:none;-moz-appearance:none;-o-appearance:none;appearance:none;padding: 2px 60px 2px 2px;padding: 2px 0px 2px 2px\9;border:#ccc solid 1px;background:url(/public/images/custom-bg.jpg) no-repeat right !important; border:0 none; width:184px; font-weight:bold; font-size:12px; line-height:16px; color:#666}
.cutsom-cons-dropdwn select option{}
.cutsom-cons-dropdwn select{background:#fff\9 !important; /* IE8 and below */  padding:0px\9\0;}
.cutsom-cons-dropdwn select::-ms-expand {display: none;}
.cons-phone-icon{background-position:-234px -134px; width:18px; height:18px; position: relative; top: 0px;}
.no-Result-message{color:#666; font-size:12px; margin:45px 0 0;}
.no-Result-message p{margin:10px 0 15px; font-size:12px;}
.abroad-layer-content .consultant-arrow{background-position:-292px -89px; width:54px; height:94px; position:absolute; right:248px; top:-85px;}
</style>

<div id="consultantCatPageHelp" class="verified-box clearwidth" style="display: none;">
        <strong style="display:inline-block; padding-top:3px;" class="flLt">Consultant<span class="consultantRegionSControl"><?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])==1?'':'s'?></span> located in:</strong>
        <div style="margin-left:185px; margin-top:0;" class="cutsom-cons-dropdwn">
            <i class="listing-sprite consultant-loc-icon"></i>
            <select class="regionSelector" onchange="changeRegion(this);" id="tabSelectorForRegion">
                <?php foreach($regionConsultantMapping as $regionId => $data){ ?>
                    <option value="<?=$regionId?>"><?=htmlentities($data['regionName'])?></option>
                <? } ?>

            </select>
        </div>
    <div class="clearfix"></div>

    <div id="consultantHelpDiv">
    <?php foreach($regionConsultantMapping as $regionId => $regionData) { ?>
        <?php if(empty($regionData['consultantIds'])){ ?>
         <div class='consultantHelpTab_<?=$regionId?> noResultDivBlock'>
            <?php $this->load->view('listing/abroad/widget/zeroConsultantPage'); ?>
        </div>
        <?php }else{ ?>
          <ul class="consultant-ins-list customInputs-large-2 consultantHelpTab_<?=$regionId?>" id="consultant-ins-list" style="margin-top:20px;<?=($regionId == $currentRegion['regionId'])?'':'display:none;'?>">

          <?php foreach($regionData['consultantIds'] as $consultantId)
          {
            if(empty($consultantData[$consultantId]['excludedCourses']) || (array_search($courseId, $consultantData[$consultantId]['excludedCourses'])===FALSE))
            {
            ?>

                 <li style="padding:10px;">
                    <div class="consultant-info-box" style="margin-left:0; line-height: 20px">
                        <a href="<?php echo $consultantData[$consultantId]['consultantProfileUrl']?>" target="_blank">
                            <strong class="consultantNameStrong" style="word-wrap:break-word"><?php echo htmlentities(formatArticleTitle($consultantData[$consultantId]['consultantName'],50));?></strong>
                        </a>
                        <p class="font-11 consultantOfficeAddressP" style="word-wrap:break-word; color: #666;">
                           <?php echo htmlentities(formatArticleTitle($consultantData[$consultantId]['regions'][$regionId]['office']['officeAddress'],50));?>
                        </p>
                        <strong style="color:#666; margin-top:8px; display: block; font-size:12px;">
                            <i class="listing-sprite cons-phone-icon"></i>
                          <?php echo htmlentities(formatArticleTitle($consultantData[$consultantId]['regions'][$regionId]['office']['phoneNumber'],50));?>
                        </strong>
                     </div>
                </li>
    <?php }
    } ?>
          </ul>
    <?php    }
    }?>
    </div>
        <a href="Javascript:void(0);" onclick = "hideAbroadOverlay();" class="button-style" style="padding:10px 15px; font-size:18px;"><strong>Close</strong></a>
</div>
