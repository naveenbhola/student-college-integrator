<div <?=$cssClass?>  style='border: 0;margin: 0; padding:0;' id="<?=$widgetObj->getWidgetKey().'Container'?>">
<div class="ask-widget">
	<h2>Have a Question? Ask Our experts</h2>
	<p style="font-size:15px">Over 500 Experts are answering queries</p>
    <br />
    <div style="width:320px; float:left">
     <form name="askQuestionForm" id="askQuestionForm">	
	<textarea class="universal-select" name="questionText" id="questionText" profanity="true" default="Need expert guidance on education or career? Ask our experts." cols="2" rows="1" style="color:#959494;" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_What_Question;?>','<?php echo $GA_userLevel;?>');">Need expert guidance on education or career? Ask our experts.</textarea>
                        
	<input type="hidden" name="crs_pg_prms" id="crs_pg_prms" value="<?php echo $subCategoryObj->getId()."_".$subCategoryObj->getParentId(); ?>" />
    <input type="hidden" name="tracking_keyid" id="tracking_keyid_ques" value="<?php echo $trackingPageKeyId;?>">
    <br /><br />
	<input type="button" id ='AskAnAButtonCoursePage' value="Ask now" class="orange-button2" uniqueattr="CPGS_SESSION_<?php echo $widgetObj->getWidgetKey()?>/BUTTON" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_What_Question;?>','<?php echo $GA_userLevel;?>');"/>
    </form>
    </div>
    <div class="ask-girl"></div>
</div>
</div>
