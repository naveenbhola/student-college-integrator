<?php 
if($value['mainAnswerId'] != -1)
{
	if($type=='Answer Comment'){
		$minLength = 5;
		$entityType = "comment";
	}else if($type=='Discussion Comment'){
		$minLength = 15;
		$entityType = "comment";
	}else if($type=='Discussion Reply' || $type=='Comment Reply'){
		$minLength = 5;
		$entityType = "reply";
	}else{
		$minLength = 15;
		$entityType = "answer";
	}
?>
	<div>
		<form id="editFormToBeSubmitted<?php echo $value['msgId'];?>" method="post" onsubmit="return false;" action="" novalidate="" style="display:none;">
          <div class="submit-ans clear-width cmnt_box_all" id="comment_box_<?php echo $value['msgId'];?>">
          <div class="submit-ans-child clear-width">

         <textarea validatesinglechar="true" required="true" minlength="<?php echo $minLength?>" maxlength="2500" caption='<?php echo $type;?>' validate="validateStr" rows="5" style="width:100%" class="ftxArea" id="replyText<?php echo $value['msgId'];?>" name="replyText<?php echo $value['msgId'];?>"><?=$value['msgTxt'];?></textarea>
         <div style="display:none;font-size:12px;color:red;" class="errorPlace Fnt11"><div id="replyText<?php echo $value['msgId'];?>_error" class="errorMsg"></div></div>
         <div class="info-text clear-width">
         
         <a class="flRt submit-btn" id="submitButton<?php echo $value['msgId'];?>" onclick="editEntityByModerator('<?=$value['msgId']?>')" 
href="javascript:void(0);">Save</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id="cancelButton<?php echo $value['msgId'];?>" onclick="hideCRAnswerEditForm('<?php echo $value['msgId'];?>')">Cancel</a>
         </div>
           <input type="hidden" value="<?php echo $value['threadId'];?>" name="threadid<?php echo $value['msgId'];?>">
<input type="hidden" value="<?php echo $value['msgId'];?>" name="answerId">
           <input type="hidden" value="seccodeForInlineAnswer" name="secCodeIndex">
           <input type="hidden" value="user" name="fromOthers<?php echo $value['msgId'];?>">
           <input type="hidden" id="actionPerformed<?php echo $value['msgId'];?>0" value="editAnswer" 
name="actionPerformed<?php echo $value['msgId'];?>">
           <input type="hidden" id="mentionedUsers<?php echo $value['msgId'];?>" value="" name="mentionedUsers<?php echo $value['msgId'];?>">
<input type="hidden" value="<?php echo $entityType;?>" name="entityType">
           </div>
          </div>
     </form>
	</div>
	<?php if(($value['fromOthers'] == 'discussion' && $value['mainAnswerId']!=0) || $value['fromOthers'] == 'user'){?>
		<a style="<?=!$isShown?'display:none;':''?>" class="moderatorControl moderatorControl<?=$value['msgId']?>" href="javascript:void(0);" id="editLink<?=$value['msgId']?>" onclick='editCRAnswer("<?=$value['msgId']?>")'>Edit this <?=$entityType?>.</a>

<?php 
	}
	if($value['mainAnswerId']!=-1 && $value['fromOthers']!='discussion')
	{
		$questionTxt = (strlen($value['questionTxt'])>150)?substr($value['questionTxt'],0,150).'... <a href="javascript:;" onclick="showMoreText(\'type1\', \''.$value['msgId'].'\')">read more</a>':$value['questionTxt'];
	?>
		<br><b>Question Text:</b><br><span id="readMoreQuestionTxtPart<?=$value['msgId']?>"><?=$questionTxt?></span><span style="display:none;" id="readMoreQuestionTxtWhole<?=$value['msgId']?>"><?=$value['questionTxt']?></span><br>
	<?php
	}
	if($value['mainAnswerId']>0){
	    if(!empty($value['answerTxt'])){
	    	$answerTxt = (strlen($value['answerTxt'])>150)?substr($value['answerTxt'],0,150).'... <a href="javascript:;" onclick="showMoreText(\'type2\', \''.$value['msgId'].'\')">read more</a>':$value['answerTxt'];
				if($value['fromOthers'] == 'discussion'){
					?>
					<p><b>Discussion Text:</b><br><span id="readMoreAnswerTxtPart<?=$value['msgId']?>"><?=$answerTxt?></span><span style="display:none;" id="readMoreAnswerTxtWhole<?=$value['msgId']?>"><?=$value['answerTxt']?></span></p>
					<?php
				}else{
					?>
					<p><b>Answer Text:</b><br><span id="readMoreAnswerTxtPart<?=$value['msgId']?>"><?=$answerTxt?></span><span style="display:none;" id="readMoreAnswerTxtWhole<?=$value['msgId']?>"><?=$value['answerTxt']?></span></p>
					<?php
				}         	
        }
		if(!empty($value['commentTxt'])){
			$commentTxt = (strlen($value['commentTxt'])>150)?substr($value['commentTxt'],0,150).'... <a href="javascript:;" onclick="showMoreText(\'type3\', \''.$value['msgId'].'\')">read more</a>':$value['commentTxt'];
			?>
			<p><b>Comment Text:</b><br><span id="readMoreCommentTxtPart<?=$value['msgId']?>"><?=$commentTxt?></span><span style="display:none;" id="readMoreCommentTxtWhole<?=$value['msgId']?>"><?=$value['commentTxt']?></span></p>
			<?php
		}
	}
}
if($value['parentId']==0 || ($value['fromOthers']=='discussion' && $value['mainAnswerId']==0)){
	if(empty($associatedTags[$value['threadId']])){
		$action = 'Add Tags';
	}else{
		$action = 'Edit Tags';
	}
?>
	<div>
		<form id="editTagsFormToBeSubmitted<?php echo $value['threadId'];?>" method="post" onsubmit="return false;" action="/Tagging/TaggingCMS/editTagsFromModeration" novalidate="" style="display:none;">
            <div class="cms-fields tag_parent">
                <div>
                    <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_name_<?php echo $value['msgId'];?>' id='tag_name_<?php echo $value['msgId'];?>'>
                     &nbsp;&nbsp;&nbsp; 
                     <div class="empty-message"></div>
                 </div>
            </div>
 
 			<div id="tagContainer_<?=$value['threadId'];?>">
			<?php
			    $counter=0;
			    while($counter<count($finalTags[$value['threadId']])){			
			?>
	    			<Input type = 'Checkbox' name='tagsName_<?php echo $value['threadId'];?>[]' id='tagsName_<?php echo $value['threadId'];?>' value ='<?php echo $finalTags[$value['threadId']][$counter]['tagId']; ?>' checked><?php echo $finalTags[$value['threadId']][$counter]['tagName']; ?>
			 		<Input type='hidden' name='tagsClass_<?php echo $finalTags[$value['threadId']][$counter]['tagId']; ?>' id='tagsClass_<?php echo $value['threadId'];?>' value ='<?php echo $finalTags[$value['threadId']][$counter]['classification']; ?>' >
			 <?php
			 		$counter++;
	    		}
	    	?>
			</div>
            <div class="info-text clear-width">
            <a class="flRt submit-btn" id="submitButton<?php echo $value['msgId'];?>" href="javascript:void(0);" onclick="submitEditTagForm('<?=$value['msgId']?>','<?=$value['threadId']?>','<?=strtolower($type)?>','<?=$action?>')">Submit</a>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="javascript:void(0);" id="cancelButton<?php echo $value['msgId'];?>" onclick="hideEditTagForm('<?php echo $value['threadId'];?>','<?php echo $action;?>')">Cancel</a>
            </div>
        </form>
	</div>
	<?php 
	$displayNoneStr = !$isShown?'display:none;':'';
	if(!empty($associatedTags[$value['threadId']])){
		echo '<a href="javascript:void(0);" id="editTagLink'.$value['threadId'].'" onclick="editEntityTags(\''.$value['threadId'].'\',\''.$action.'\');bindAutoSuggestor(\'cafeModeration\',\'\',\''.$value['threadId'].'\');" style="'.$displayNoneStr.' margin-bottom:10px;" class="moderatorControl moderatorControl'.$value['msgId'].'">Edit Tags</a>';
		echo '<p id="tagBlock'.$value['threadId'].'"><b>Tags:</b><br>'.$associatedTags[$value['threadId']].'</p>';
	}else{
	    echo '<a href="javascript:void(0);" id="editTagLink'.$value['threadId'].'" onclick="editEntityTags(\''.$value['threadId'].'\',\''.$action.'\');bindAutoSuggestor(\'cafeModeration\',\'\',\''.$value['threadId'].'\');" style="'.$displayNoneStr.' margin-bottom:10px;" class="moderatorControl moderatorControl'.$value['msgId'].'" >Add Tags</a>';
	
	}
}
if($value['parentId']==0){
	$countryId   = $value['countryId'];
	$categoryId  = $value['categoryId'];
	$parentId    = $categoryList[$categoryId][1];
	$countryName = $countryList[$countryId];
	$subCatName  = $categoryList[$categoryId][0];
	$catName     = $categoryList[$parentId][0];
	if(isset($catName) && $catName != '')
		echo "<div style='margin-top:20px;'><u><i>$catName - $subCatName In $countryName</i></u></div>";
}
?>