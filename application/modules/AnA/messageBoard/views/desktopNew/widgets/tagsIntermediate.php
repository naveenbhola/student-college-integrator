<div id="selected-tags">
<?php
	if($entityType == 'question')
	{
		$GA_currentPage = 'QUESTION POSTING';
		$GA_Tap_On_Add_More_Tags 	= 'ADDTAGS_QUESTIONPOSTING_DESKAnA';
	}
	else
	{	
		$GA_currentPage = 'DISCUSSION POSTING';
		$GA_Tap_On_Add_More_Tags 	= 'ADDTAGS_DISCUSSIONPOSTING_DESKAnA';
	}
	foreach ($tagsData as $key => $value) {

			$uncheckedClass = "";
			$checkedAttr = "checked";
			if(!$value['checked']){
				$uncheckedClass = "un-tag";
				$checkedAttr = "unchecked";
			}
			?>
<a class="choosen-tag <?=$uncheckedClass;?>" status = "<?=$checkedAttr;?>" href="javascript:void(0);" tagId ="<?php echo $value['tagId']?>" classification="<?php echo $value['classification']?>"><span><i></i></span><span class="i-s"><?=$value['tagName'];?></span></a>
			<?php		
	}

?>
</div>
<a id="add-more-tag" class="add-tag" href="javascript:void(0);" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Add_More_Tags;?>','<?php echo $GA_userLevel;?>');" tabindex=7>Add more Tags</a>
<p class="clr"> </p>