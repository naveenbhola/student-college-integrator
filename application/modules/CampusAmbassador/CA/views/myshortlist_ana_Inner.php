<?php if(!empty($qna)){ ?>
 	<!--<input type="hidden" id="campus-connect-page" value="campus-qna"/>-->
 	<?php $count = 0 ;
 	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
 	$isCmsUser =0;
 	if((is_array($validateuser))&&(strcmp($validateuser[0]['usergroup'],'cms') == 0)){
 		$isCmsUser = 1;
 	}
 	?>
	<?php foreach ($qna as $index => $data){ ?> 
	<?php $count++;?>
	<?php if(!empty($data["title"])){ 
					$questionText = $data["title"];
					$quesUrl = getSeoUrl($data["msgId"], 'question');
					?>
			
	
					<li id="listingana_<?php echo $data["msgId"];?>">
						<a href="<?php echo $quesUrl; ?>" id="listquest_<?php echo $data["msgId"];?>" onclick="$j('.myshortlistQnaContainer').html('').hide();" style="text-decoration: none;" title="click" target="_blank"><?php echo $questionText;?></a>
						<?php if($data["answers"]>0){?>
						<span>(<span id="answerCountFor<?php echo $data["msgId"];?>"><?php echo $data["answers"];?> <?php echo ($data["answers"]>1)? 'answers' : 'answer';?></span>)</span>
						<?php }else{
						?>
						<span><span id="answerCountFor<?php echo $data["msgId"];?>"></span></span>
						<?php
						}?>
						<div class="myshortlistQnaContainer qna-detail-layer" style="display:none; min-height:385px; " id="quesOverlay_<?php echo $data["msgId"];?>"></div>
					</li>

<?php			
      }
      
      }
}
?>