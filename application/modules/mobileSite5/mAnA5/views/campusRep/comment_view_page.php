<?php $nameToBeDisplayed = (!empty($lastName))?$firstName.' '.$lastName : $firstName; ?>
<div class="posted-ans-detail hideComment_<?php echo $mainAnsId;?>" id="hideComment_<?php echo $mainAnsId;?>">
		    
       <?php
	      $text = html_entity_decode(html_entity_decode($text,ENT_NOQUOTES,'UTF-8'));
	      $text =  formatQNAforQuestionDetailPage($text,500);
	      $showShortText = false;
	      $textShortened = '';
	      $htmlTagsRemovedText = strip_tags($text);
	      if(strlen($htmlTagsRemovedText)>120){
		      $showShortText = true;
		      $commentTextShortened = substr($htmlTagsRemovedText,0,120);
	      }
       ?>
       <?php if($showShortText){ ?>
	      <p id="shortened<?=$msgId;?>"><?php echo $commentTextShortened;?><a id="expandLink<?=$msgId;?>" href='javascript:void(0)' onClick='expandAnswer("<?=$msgId;?>");'>...Read More</a></p>
	      <p id="expanded<?=$msgId;?>" style="display: none;"><?php echo $text;?></p>
       <?php }else{ ?> 
	      <p><?php echo $text;?></p>
       <?php } ?>
       <p class="posted-info clearfix">
   <span><label>Posted by:</label> <?php echo $nameToBeDisplayed;?></span>
   <span><?php echo $postedDate; ?></span>
   </p>
</div>