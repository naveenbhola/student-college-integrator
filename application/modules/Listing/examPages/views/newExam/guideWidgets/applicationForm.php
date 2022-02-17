<?php 
if($isHomePage)
{
  $className = 'exm-dtl';
}
if(!empty($appFormData['appFormWiki'])){
    $wikiLabel = new tidy ();
    $wikiLabel->parseString (htmlspecialchars_decode($appFormData['appFormWiki']) , array ('show-body-only' => true ), 'utf8' );
    $wikiLabel->cleanRepair();
}?>
<div class="clg-blok dflt__card">
  <h2><?php echo $sectionNameMapping[$section]?></h2>
  <div class="<?=$className;?>">
    <?php if(!empty($appFormData['fileUrl'])){?>
      <p class="color-3 font-w6 m-5btm">The application form is available to download directly from Shiksha <span class="f14__clrb"><a class="dwn-aplForm" href="<?=$examPageUrl.'/application-form';?>?course=<?=$groupId?>&actionType=exam_download_application_form&fromwhere=exampage" target="_blank">Click Here</a></span></p>
    <?php } 
    if(!empty($appFormData['formURL'])){?>
      <p class="color-3 font-w6 m-5btm">The form is <?php if(!empty($appFormData['fileUrl'])){ ?>also<?php } ?> available at this URL <?=$appFormData['formURL']?></p>
    <?php } 
    if(!empty($wikiLabel)){
    ?>
    <p class="f14__clr3"><?php echo $wikiLabel; ?></p>
    <?php } ?>
  </div>
</div>