<?php
if($isHomePage)
{
    $className = 'data-height-col';
}
?>
<section>
<div class="data-card">
     <?php 
    if($groupYear)
        $groupYear = ' '.$groupYear;
    if($isHomePage)
    { ?>        
        <h2 class="color-3 f16 heading-gap font-w6"><a href ="<?php echo $snippetUrl[$section]; ?>" data-vars-event-name="APPLICATION_FORM_VIEW_DETAILS" class="color-3 f16 font-w6 ga-analytic"><?php echo $examName.$groupYear.' '.'Application Form'?></a></h2>
    <?php }else{
        ?>
        <!--<h1 class="color-3 f16 heading-gap font-w6"><?//=$h1?></h1>-->
    <?php } ?>
    <div class="card-cmn color-w f14 color-3 l-12">
        <?php if(count($groupList)>1 && !$isHomePage){?>
              <p class="f14 color-3 font-w6 change-brdr"><?php echo "Showing details for ". $groupName;?>     
                <a class="font-w4 ga-analytic" data-vars-event-name="CHANGE_COURSE" id="changeCourse" on="tap:change-group" role="button" tabindex="0">Change Course<i class="chnge-brnchico"></i></a>
              </p>
            <?php } ?>
      <div class="<?=$className;?>">
        <?php if(!empty($appFormData['fileUrl'])){?>
            <p class="m-btm">The application form is available to download directly from Shiksha <span class="f14__clrb"><a class="ga-analytic" href="<?=SHIKSHA_HOME?>/muser5/UserActivityAMP/getResponseExamAmpPage?examGroupId=<?=$groupId?>&actionType=exam_download_application_form&sectionName=<?=$activeSectionName?>&fromwhere=exampage" data-vars-event-name="DOWNLOAD_APPLICATION_FORM">(click to download)</a></span></p>
        <?php }
        if(!empty($appFormData['formURL'])){?>
            <p class="m-btm">The application form is <?php if(!empty($appFormData['fileUrl'])){ ?>also<?php } ?> available at this URL <?=$appFormData['formURL']?> </p>
        <?php } 
         if(!empty($appFormData['appFormWiki'])){
            if($isHomePage)
            {
                $this->htmlSummarizeLogicLib = $this->load->library('examPages/HtmlSummarizeLogicLib');
                $appFormData['appFormWiki'] = $this->htmlSummarizeLogicLib->summarizeData($appFormData['appFormWiki']);
            }
            ?>
            <div class="m-btm mbtm_sp"><?php echo html_entity_decode($appFormData['appFormWiki']); ?></div>
        <?php } ?>
    </div>
    <?php if($isHomePage) {?>
        <div class="btn-sec">
            <a href ="<?php echo $snippetUrl[$section]; ?>" data-vars-event-name="APPLICATION_FORM_VIEW_DETAILS" class="btn btn-secondary color-w color-b f14 font-w6 m-15top ga-analytic">View Details</a>
        </div>
    <?php } ?>
    </div>
  </div>
</section>
<?php if(!$isHomePage){ $this->load->view("mcommon5/socialShareThis",array('pageType'=>'ampPage','className'=>'shadow'));}?>
          
