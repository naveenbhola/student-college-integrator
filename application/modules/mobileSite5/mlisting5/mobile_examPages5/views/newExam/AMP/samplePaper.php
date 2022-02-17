<?php 
 $wikiObj = $examContent['samplepapers']['wiki'][0];
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
        <h2 class="color-3 f16 heading-gap font-w6"><a href ="<?php echo $snippetUrl[$section]; ?>" data-vars-event-name="SAMPLE_PAPER_VIEW_ALL" class="color-3 f16 font-w6 ga-analytic"><?php echo $examName.$groupYear.' '.$samplePaperHeading ?></a></h2>
        <?php }else{
            ?>
            <!--<h1 class="color-3 f16 heading-gap font-w6"><?//=$h1?></h1>-->
        <?php } ?>
    <div class="card-cmn color-w f14 color-3">
        <?php if(count($groupList)>1 && !$isHomePage){?>
              <p class="f14 color-3 font-w6 change-brdr"><?php echo "Showing details for ". $groupName;?>     
                <a class="font-w4 ga-analytic" data-vars-event-name="CHANGE_COURSE" id="changeCourse" on="tap:change-group" role="button" tabindex="0">Change Course<i class="chnge-brnchico"></i></a>
              </p>
            <?php } ?>
      <div class="<?=$className;?>">
        <p class="sample-txt">Download previous year <?php echo $examName?> question papers and sample papers. Use them for practice and improve your speed and accuracy. You can download <?php echo $examName?> previous year question papers and the preparation guides for free.</p>

	<?php if($examId == '6244'){ ?>
	<table><tbody><tr><td><strong><a href="https://www.shiksha.com/b-tech/resources/jee-main-rank-predictor" target="_blank">Predict your JEE Main 2018 ranks with Shiksha's newly launched JEE Main rank predictor tool</a></strong></td>
	</tr></tbody></table>
	<?php } ?>

        <ul class="cmn-lst">
            <?php 
            if(!empty($wikiObj)){
            $samplepaperswiki = $wikiObj->getEntityValue();
            }
            if(!empty($samplePaperData) && count($samplePaperData) > 0){ ?>
                <h2 class="f14 inner-h2">Download <?php echo $examName.' '.'Question Papers';?></h2>  <?php
                foreach ($samplePaperData as $key => $val) {?>
            <li>
                <p class="pos-rl <?php echo getFileType($val['url']);?>"><?=$val['fileName']?>
                </p>
                <section class="i-block frt" amp-access="NOT validuser" amp-access-hide>
                    <a class="ga-analytic" href="<?=SHIKSHA_HOME?>/muser5/UserActivityAMP/getResponseExamAmpPage?examGroupId=<?=$groupId?>&actionType=exam_download_sample_paper&sectionName=<?=$activeSectionName?>&fromwhere=exampage&fileNo=<?=$key+1?>&clickId=samplePaperData" data-vars-event-name="DOWNLOAD_SAMPLE_PAPER"><i></i></a>
                </section>
                <section class="i-block frt" amp-access="validuser" amp-access-hide>
                    <a class="ga-analytic" href="<?=$examPageUrl;?>?course=<?=$groupId?>&actionType=exam_download_sample_paper&sectionName=<?=$activeSectionName?>&fromwhere=exampage&fileNo=<?=$key+1?>&clickId=samplePaperData" data-vars-event-name="DOWNLOAD_SAMPLE_PAPER"><i></i></a>
                </section>
            </li>
            <?php }}

            if(!empty($guidePaperData) && count($guidePaperData) > 0){ ?>
                <h2 class="f14 inner-h2">Download <?php echo $examName.' '.'Prep Guides';?></h2><?php                     
                foreach ($guidePaperData as $key1 => $val1) {?>
            <li>
                <p class="pos-rl <?php echo getFileType($val1['url']);?>"><?=$val1['fileName']?></p>
                <section class="i-block frt" amp-access="NOT validuser" amp-access-hide>
                    <a class="ga-analytic" data-vars-event-name="DOWNLOAD_PREP_GUIDE" href="<?=SHIKSHA_HOME?>/muser5/UserActivityAMP/getResponseExamAmpPage?examGroupId=<?=$groupId?>&actionType=exam_download_prep_guide&sectionName=<?=$activeSectionName?>&fromwhere=exampage&fileNo=<?=$key1+1?>&clickId=guidePaperData"><i></i></a>
                </section>
                <section class="i-block frt" amp-access="validuser" amp-access-hide>
                    <a class="ga-analytic" data-vars-event-name="DOWNLOAD_PREP_GUIDE" href="<?=$examPageUrl;?>?course=<?=$groupId?>&actionType=exam_download_prep_guide&sectionName=<?=$activeSectionName?>&fromwhere=exampage&fileNo=<?=$key1+1?>&clickId=guidePaperData"><i></i></a>
                </section>
            </li>
            <?php }} 
            ?>
            
            <?php
            if(!empty($samplepaperswiki) && !$isHomePage){ ?>
            <div class="m-btm mbtm_sp"><?php echo html_entity_decode($samplepaperswiki); ?></div>
        <?php } ?>
        </ul>
    </div>
    <?php if($isHomePage) {?>
        <div class="btn-sec">
            <a href ="<?php echo $snippetUrl[$section]; ?>" data-vars-event-name="SAMPLE_PAPER_VIEW_ALL" class="btn btn-secondary color-w color-b f14 font-w6 m-15top ga-analytic">View All</a>
        </div>
    <?php } ?>
    </div>
  </div>
</section>
<?php if(!$isHomePage){ $this->load->view("mcommon5/socialShareThis",array('pageType'=>'ampPage','className'=>'shadow'));}?>
