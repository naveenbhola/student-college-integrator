<?php
      $pageKeyForAskQuestion = 'LISTING_INSTITUTEDETAIL_ASK_INSTITUTE_POSTQUESTION';
      $categoryId = 0;
      if(count($categories) > 0 && is_array($categories) ){
	    $categoryId = $categories[0];
      }
?>
<form id="form_askQuestion_<?php echo $formId;?>" method="post" action="mAnA5/AnAController/postQuestion" autocomplete="off"  novalidate>
    <input type="hidden" id="instituteId" name="instituteId" value="<?=$instituteId?>">
    <input type="hidden" id="locationId" name="locationId" value="<?=$locationId?>">
    <input type="hidden" id="courseId"  name="courseId"  value="<?=$course->getId()?>"/>	    
    <input type="hidden" id="categoryId"  name="categoryId"  value="<?=$categoryId?>"/>
    <input type="hidden" name="getmeCurrentCity" id="getmeCurrentCity" value="<?php echo $currentLocation->getCity()->getId();?>"/>
    <input type="hidden" name="getmeCurrentLocaLity" id="getmeCurrentLocaLity" value="<?php echo $currentLocation->getLocality()->getId();?>"/>
    <input type="hidden" name="tracking_keyid" id="tracking_keyid" value="<?php echo $trackingPageKeyId;?>">
</form>		
