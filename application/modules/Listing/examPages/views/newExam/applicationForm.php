<?php 
if($isHomePage)
{
  $className = 'setHeight';
}
if(!empty($appFormData['appFormWiki'])){
    if($isHomePage)
    {
      $this->htmlSummarizeLogicLib = $this->load->library('examPages/HtmlSummarizeLogicLib');
      $appFormData['appFormWiki'] = $this->htmlSummarizeLogicLib->summarizeData($appFormData['appFormWiki']);
    }
    $wikiLabel = new tidy ();
    $appFormData['appFormWiki'] = addTargetBlankInWikiData($appFormData['appFormWiki']);
    $wikiLabel->parseString (htmlspecialchars_decode($appFormData['appFormWiki']) , array ('show-body-only' => true ), 'utf8' );
    $wikiLabel->cleanRepair();
}?>
<div class="dflt__card mt__15 examTuple no__pad" id="<?php echo $section;?>">
  <?php 
        if($groupYear){
          $groupYear = ' '.$groupYear;
        }
        if($isHomePage)
         {?> 
            <h2 class="mt__10 f20__clr3"><a href ="<?php echo $snippetUrl['applicationform']; ?>" style="color:inherit;" ga-attr="<?php echo str_replace(' ', '_', strtoupper('Application Form'))?>_VIEW_DETAILS"><?php echo $examName.$groupYear.' '.'Application Form'?></a></h2>
        <?php }else{?>
          <!--<h1 class="mt__10 f20__clr3"><?//=$h1?></h1>-->
      <?php } ?>

  <div class="<?=$className;?> f14__clr3 pad__16" id="<?php echo $section.'det';?>">
    <div class="data_change">
    <?php if(count($groupList)>1 && !$isHomePage){?>
      <p class="f14__clr3 chngCrs"> Showing details for <strong><?php echo $groupName;?> </strong>            
          <a class="fnt__n" style="cursor:pointer" id="changeCourse">Change Course<i class="chnge-brnchico"></i></a>
      </p>
    <?php } ?>
    </div> 
      <?php if(!empty($appFormData['fileUrl'])){?>
        <p class="color-3 font-w6 m-5btm">The application form is available to download directly from Shiksha <span class="f14__clrb"><a id="appform-download" class="dwn-aplForm" href="javascript:void(0);" data-trackingKey="<?php echo $trackingKeyList['download_application_form'];?>" data-url="<?php echo $appFormData['fileUrl']?>" ga-attr="DOWNLOAD_APPLICATION_FORM">(Click to download)</a></span></p>
    <?php }
    if(!empty($appFormData['formURL'])){?>
      <p class="color-3 font-w6 m-5btm">The application form is <?php if(!empty($appFormData['fileUrl'])){ ?>also<?php } ?> available at this URL <?=$appFormData['formURL']?></p>
    <?php }
    if(!empty($wikiLabel)){
    ?>
    <div class="f14__clr3 mbtm_sp"><?php echo $wikiLabel; ?></div>
    <?php } ?>
  </div>
  <?php if($isHomePage) { ?>
      <div class="pd__top__10 txt__cntr mtop__10 pad__16" id="<?php echo $section.'detRm';?>">
         <a href ="<?php echo $snippetUrl['applicationform']; ?>" class="blue__brdr__btn arrow_after" ga-attr="<?php echo str_replace(' ', '_', strtoupper('Application Form'))?>_VIEW_DETAILS">View Details</a>
      </div>
  <?php } ?>
</div>
