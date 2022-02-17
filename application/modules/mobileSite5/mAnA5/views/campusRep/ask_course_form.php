<?php
      $pageKeyForAskQuestion = 'LISTING_INSTITUTEDETAIL_ASK_INSTITUTE_POSTQUESTION';
      $categoryId = 0;
      if(count($categories) > 0 && is_array($categories) ){
	    $categoryId = $categories[0];
      }
?>


<form id="form_askQuestion" method="post" action="mAnA5/AnAController/postQuestion" autocomplete="off"  novalidate>
        		
    <input type="hidden" id="instituteId" name="instituteId" value="<?=$instituteId?>">
    <input type="hidden" id="locationId" name="locationId" value="<?=$locationId?>">
    <input type="hidden" id="courseId"  name="courseId"  value="<?=$course->getId()?>"/>	    
    <input type="hidden" id="categoryId"  name="categoryId"  value="<?=$categoryId?>"/>
    <input type="hidden" name="getmeCurrentCity" id="getmeCurrentCity" value="<?php echo $currentLocation->getCity()->getId();?>"/>
    <input type="hidden" name="getmeCurrentLocaLity" id="getmeCurrentLocaLity" value="<?php echo $currentLocation->getLocality()->getId();?>"/>
    <input type="hidden" name="tracking_keyid" id="tracking_keyid" value=''>
    <a class="button blue small ask-btn" href="javascript:void(0)" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','ASKYOURQUESTION_TOP_COURSELISTING_WEBAnA','<?php echo $GA_userLevel;?>');$('#tracking_keyid').val('<?php echo $trackingPageKeyId?>');$('#form_askQuestion').submit();"><span><h3 style="margin-bottom: 0px;font-weight:auto;">ASK YOUR QUESTION</h3></span></a>

</form>		
