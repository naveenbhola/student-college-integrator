<?php 
 if($isHomePage)
 {
      $className = 'setHeight';
 }
?>
<section id="<?php echo $section;?>">
    <div class="data-card wdg-card m15">
  <?php 
    if($groupYear)
        $groupYear = ' '.$groupYear;
    if($isHomePage)
    { ?>        
    <h2 class="color-3 f16 heading-gap font-w6"><a href ="<?php echo $snippetUrl[$section]; ?>" class="color-3" ga-attr="APPLICATION_FORM_VIEW_DETAILS"><?php echo $examName.$groupYear.' '.'Application Form'?></a></h2>
    <?php }else{
        ?>
        <!--<h1 class="color-3 f16 heading-gap font-w6"><?//=$h1?></h1>-->
    <?php } ?>
    <div class="lcard color-w f14 color-3">
        <div class="<?=$className;?>" id="<?php echo $section.'det';?>">
         <?php if(count($groupList)>1 && !$isHomePage){?>
                  <p class="f14 color-3 change-brdr">Showing details for <strong class="font-w6"><?php echo $groupName;?> </strong>   
                  <a href="javascript:void(0);" class="font-w4" id="changeCourse" ga-attr="CHANGE_COURSE">Change Course<i class="chnge-brnchico"></i></a>
                  </p>
                <?php }?>
        <?php if(!empty($appFormData['fileUrl'])){?>
            <p class="color-3 font-w6 m-5btm">The application form is available to download directly from Shiksha <span class="f14__clrb"><a id="appform-download" data-link="<?=$appFormData['fileUrl'];?>" data-tracking="<?=$trackingKeys['download_application_form'];?>" ga-attr="DOWNLOAD_APPLICATION_FORM">(click to download)</a></span></p>
        <?php } 
        if(!empty($appFormData['formURL'])){?>
            <p class="color-3 font-w6 m-5btm">The application form is <?php if(!empty($appFormData['fileUrl'])){ ?>also<?php } ?> available at this URL <?=$appFormData['formURL']?> </p>
        <?php } 
        if(!empty($appFormData['appFormWiki'])){
            if($isHomePage)
            {
                $this->htmlSummarizeLogicLib = $this->load->library('examPages/HtmlSummarizeLogicLib');
                $appFormData['appFormWiki'] = $this->htmlSummarizeLogicLib->summarizeData($appFormData['appFormWiki']);
            }
        ?>
            <div class="f14__clr3 mbtm_sp"><?php echo html_entity_decode($appFormData['appFormWiki']); ?></div>
        <?php } ?>
        </div>
        <?php if($isHomePage) { ?>
            <div class="btn-sec" id="<?php echo $section.'detRm';?>">
                <a href ="<?php echo $snippetUrl[$section]; ?>" class="btn btn-secondary color-w color-b f14 font-w6 m-15top m-5btm" ga-attr="APPLICATION_FORM_VIEW_DETAILS">View Details</a>
            </div>
        <?php } ?>
        </div>
    </div>
</section>
<?php if(!$isHomePage){
    $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow')); 
}?>
