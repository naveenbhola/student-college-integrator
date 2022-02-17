<?php 
 $wikiObj1 = $examContent['preptips']['wiki'][0];
 $wikiObj2 = $examContent['preptips']['wiki'][1];
 $isShowFiles = true;
 if(empty($activeSectionName)){
    $activeSectionName = 'homepage';
 }
?>
<section>
<div class="data-card">
     <?php
        if($groupYear)
            $groupYear = ' '.$groupYear;
        if($isHomePage)
        { ?>
        <h2 class="color-3 f16 heading-gap font-w6"><a href ="<?php echo $snippetUrl[$section]; ?>" data-vars-event-name="PREP_TIPS_VIEW_ALL" class="color-3 f16 font-w6 ga-analytic"><?php echo $examName.$groupYear.' '.'Preparation Tips' ?></a></h2>
        <?php }else{
            ?>
<!--            <h1 class="color-3 f16 heading-gap font-w6"><?//=$h1?></h1>-->
        <?php } ?>
    <div class="card-cmn color-w f14 color-3">
        <?php if(count($groupList)>1 && !$isHomePage){?>
              <p class="f14 color-3 font-w6 change-brdr"><?php echo "Showing details for ". $groupName;?>     
                <a class="font-w4 ga-analytic" data-vars-event-name="CHANGE_COURSE" id="changeCourse" on="tap:change-group" role="button" tabindex="0">Change Course<i class="chnge-brnchico"></i></a>
              </p>
            <?php } ?>
      <div class="<?=$className;?>">
        <?php 
            if(!empty($wikiObj1)){
                $preptipsWiki1 = $wikiObj1->getEntityValue();
            }
        ?>
        <?php
            if(!empty($preptipsWiki1)) { 
                    if($isHomePage)
                    {
                        $isShowFiles = false;
                    }
                    ?>
                <div class="m-btm mbtm_sp"><?php echo html_entity_decode($preptipsWiki1); ?>
                </div>
        <?php } ?>

        <ul class="cmn-lst">
            <?php 
            if(!empty($preptipsData) && count($preptipsData) > 0 && $isShowFiles) { ?>
                <h2 class="f14 inner-h2">Download <?php echo $examName.' '.'Preparation Tips';?></h2>  <?php
                foreach ($preptipsData as $key => $val) {?>
            <li>
                <p class="pos-rl <?php echo getFileType($val['url']);?>"><?=$val['fileName']?>
                </p>
                <section class="i-block frt" amp-access="NOT validuser" amp-access-hide>
                    <a class="ga-analytic" href="<?=SHIKSHA_HOME?>/muser5/UserActivityAMP/getResponseExamAmpPage?examGroupId=<?=$groupId?>&actionType=exam_download_prep_tip&sectionName=<?=$activeSectionName?>&fromwhere=exampage&fileNo=<?=$key+1?>&clickId=guidePaperData" data-vars-event-name="DOWNLOAD_PREP_TIPS_PAPER"><i></i></a>
                </section>
                <section class="i-block frt" amp-access="validuser" amp-access-hide>
                    <a class="ga-analytic" href="<?=$examPageUrl;?>?course=<?=$groupId?>&actionType=exam_download_prep_tip&sectionName=<?=$activeSectionName?>&fromwhere=exampage&fileNo=<?=$key+1?>&clickId=guidePaperData" data-vars-event-name="DOWNLOAD_PREP_TIPS_PAPER"><i></i></a>
                </section>
            </li>
            <?php }} ?>
                
                <?php 
                    if(!empty($wikiObj2)){
                        $preptipsWiki2 = $wikiObj2->getEntityValue();
                    }
                ?>
            <?php
                if(!empty($preptipsWiki2) && !$isHomePage) { ?>
                    <div class="m-btm mbtm_sp"><?php echo html_entity_decode($preptipsWiki2); ?>
                    </div>
            <?php } ?>
        </ul>
    </div>
    <?php if($isHomePage) {?>
        <div class="btn-sec">
            <a href ="<?php echo $snippetUrl[$section]; ?>" data-vars-event-name="PREP_TIPS_VIEW_ALL" class="btn btn-secondary color-w color-b f14 font-w6 m-15top ga-analytic">View All</a>
        </div>
    <?php } ?>
    </div>
  </div>
</section>
<?php if(!$isHomePage){ $this->load->view("mcommon5/socialShareThis",array('pageType'=>'ampPage','className'=>'shadow'));}?>
