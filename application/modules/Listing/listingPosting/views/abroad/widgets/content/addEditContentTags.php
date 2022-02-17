<li class="commonTags">
	<label class="article">Article Tags </label>
	<label class="guide" style="display: none">Guide Tags </label>
	<label class="examPage" style="display: none">Exam Page Tags </label>
	<!-- ----------------------------------------------COMMON TAG CONTENT STARTS---------------------------------------------------- -->
	<div class="cms-fields">
		<div id="tagContDiv">
			<?php if(empty($content['tag_info'])) { $content['tag_info'][0] = ""; }
			foreach($content['tag_info'] as $key=>$tagArr) { ?>
				<div class="add-more-sec tagDiv">
					<select class="universal-select cms-field" name="tag[]" id="tag_<?=$key+1?>">                         
						<option value="">Select a tag</option>
						<?php foreach($tags as $tag)
						{
							if($tag['id'] == $tagArr['tag_id']){
								$selected = "selected";
							} else {
								$selected = "";
							} ?>
							<option <?=$selected?> value="<?=$tag['id']?>"><?=$tag['tag_title']?></option>
						<?php } ?>
					</select>
					
					<a class="remove-link-2" href="javascript:void(0);" <?php if($key == 0){ ?> style="display:none;" <?php } ?> onclick="removeElementChunk(this, 'tag', 5);setImageContainer();">
						<i class="abroad-cms-sprite remove-icon"></i>Remove Tag
					</a>
				</div>
			<?php } ?>
		</div>
		<div style="display: none; margin-bottom: 5px" class="errorMsg" id="tag_error">error</div>
		<a href="javascript:void(0);" <?php if(count($content['tag_info']) >= 5) { ?> style="display: none" <?php } ?> id="tag_addMore" onclick="addMoreElementChunk('tag', 5); setImageContainer();">[+] Add Another Tag</a>
	</div>
	<!-- -----------------------------------------------COMMON TAG CONTENT ENDS---------------------------------------------------- -->
</li>
<?php if($showLifecycleTags){ ?>
<li class="lifecycleTag" style="padding: 15px 0; border:1px solid #ccc;">
	<label class="article">Lifecycle Tags </label>
	<label class="guide" style="display: none">Lifecycle Tags </label>
	<!-- ----------------------------------------------COMMON LIFECYCLE TAG CONTENT STARTS---------------------------------------------------- -->
	<div class="cms-fields">
		<div id="lifecycleTagContDiv">
			<?php if(count($userLifecycleTags)>0) { ?>
			<?php 	foreach($userLifecycleTags as $key => $userLifecycleTag) { ?>
				<div class="add-more-sec ">
					<select style="width:220px;" class="universal-select cms-field flLt lifecycle-tags" name="lifecycleTagL1[]" id="lifecycleTagL1_<?=$key+1?>" onchange = "populateLevel2LifecycleTags(this);">                         
						<option value="" key="">Select level 1 tag</option>
						<?php foreach($lifecycleTags as $k => $v){ ?>
							<option key = "<?=($k)?>" value="<?=($v['lvl1_value'])?>" <?=($userLifecycleTag['level1'] == $v['lvl1_value']? 'selected="selected"':'')?> ><?=($v['lvl1_value'])?></option>
						<?php } ?>
					</select>
					<select style="width:220px; margin-left:15px;" class="universal-select cms-field flLt lifecycle-tags" name="lifecycleTagL2[]" id="lifecycleTagL2_<?=$key+1?>" onchange="hideLifeCycleErrorDiv(this);">                         
						<option value="">Select level 1 tag first</option>
					</select>
					<a class="remove-link-2" href="javascript:void(0);" style="margin:3px 0 0 8px;" onclick="removeLifecycleTag(this);" >
						<i class="abroad-cms-sprite remove-icon"></i>Remove
					</a>
					<div style="display: none; margin-bottom: 5px" class="errorMsg clearFix"></div>
					<div class="clearFix"></div>
				</div>
			<?php 	} ?>
			<?php } else { $key =0; ?>
				<div class="add-more-sec ">
					<select style="width:220px;" class="universal-select cms-field flLt lifecycle-tags" name="lifecycleTagL1[]" id="lifecycleTagL1_<?=$key+1?>" onchange = "populateLevel2LifecycleTags(this);">                         
						<option value="" key="">Select level 1 tag</option>
						<?php foreach($lifecycleTags as $k => $v){ ?>
							<option key = "<?=($k)?>" value="<?=($v['lvl1_value'])?>"><?=($v['lvl1_value'])?></option>
						<?php } ?>
					</select>
					<select style="width:220px; margin-left:15px;" class="universal-select cms-field flLt lifecycle-tags" name="lifecycleTagL2[]" id="lifecycleTagL2_<?=$key+1?>" onchange="hideLifeCycleErrorDiv(this);">                         
						<option value="">Select level 1 tag first</option>
					</select>
					<a class="remove-link-2" href="javascript:void(0);" style="margin:3px 0 0 8px;" onclick="removeLifecycleTag(this);" >
						<i class="abroad-cms-sprite remove-icon"></i>Remove
					</a>
					<div style="display: none; margin-bottom: 5px" class="errorMsg clearFix"></div>
					<div class="clearFix"></div>
				</div>
			<?php } // end else ?>
		</div>
		<a href="javascript:void(0);" <?php if(count($content['tag_info']) >= 20) { ?> style="display: none" <?php } ?> id="tag_addMore" onclick="addMoreLifecycleTags();">[+] Add Another Tag</a>
	</div>
	<!-- -----------------------------------------------COMMON LIFECYCLE TAG CONTENT ENDS---------------------------------------------------- -->
</li>
<?php } ?>