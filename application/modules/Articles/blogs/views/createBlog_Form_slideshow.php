<?php

$blogslideshowDesc = (isset($blogInfo[0]['blogSlideShow'])&&(strlen($blogInfo[0]['blogSlideShow'])>2)) ? json_decode($blogInfo[0]['blogSlideShow'], true) : array('');
?>
<div class="lineSpace_10">&nbsp;</div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="bld mb5">Slides <span style="color:#FF0000;">*</span>:</div>
	<div id="blogslideshowDescriptionContainer">
		<?php
			$blogslideshowDescriptionCount = 0;
			foreach($blogslideshowDesc as $blogslideshowDescription) {
				$pageContent = $blogslideshowDescription['description'];
				$pageTag = $blogslideshowDescription['title'];
				$sequence = $blogslideshowDescription['sequence'];
				$image = $blogslideshowDescription['image'];
				$pageTagSub = $blogslideshowDescription['subTitle'];
		?>
		<div style="border:1px #aaa solid; width:80%;margin:10px;padding:10px">
		<div>
			<b>Sequence:</b>
			<div class="formatTextarea" id="blogslideshowSequenceTag_<?php echo $blogslideshowDescriptionCount; ?>_wrapper">
				<select  name="blogslideshowSequenceTag[]" id="blogslideshowSequenceTag_<?php echo ($blogslideshowDescriptionCount); ?>">
				
				<?php  for($i = 1;$i<=count($blogslideshowDesc);$i++){ ?>
					<option value="<?=$i?>"><?=$i?></option>
				<?php } ?>
				</select>
			</div>
		<script>
			$('blogslideshowSequenceTag_<?=($blogslideshowDescriptionCount)?>').value = '<?=($blogslideshowDescriptionCount+1)?>';
		</script>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div>
			<b>Slide Title:</b>
			<div class="formatTextarea">
				<input type="text"  name="blogslideshowDescTag[]" id="blogslideshowDescTag_<?php echo $blogslideshowDescriptionCount; ?>" value="<?php echo $pageTag; ?>" />
			</div>
			<b>Slide Sub-title:</b>
			<div class="formatTextarea">
				<input type="text"  name="blogslideshowDescTagSub[]" id="blogslideshowDescTagSub_<?php echo $blogslideshowDescriptionCount; ?>" value="<?=$pageTagSub?>" />
			</div>
		</div>
		<div class="errorPlace"><div id="blogslideshowDescTag_<?php echo $blogslideshowDescriptionCount; ?>_error" class="errorMsg"></div></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div>
			<b>Image:</b>
			<div class="formatTextarea">
				<input type="button" onclick="showSlideOverlay(<?=($blogslideshowDescriptionCount)?>);" class="orange-button" value="Upload Image"/>
				<?php
					if($image){
						$image = addingDomainNameToUrl(array('domainName'=>MEDIA_SERVER, 'url'=>$image));
					}
				?>
				<br/><img src="<?=($image?$image:SHIKSHA_HOME.'/public/images/blankImg.gif')?>" style="margin:10px;" width="120" height="80" id="blogslideshowDescImageSrc_<?php echo $blogslideshowDescriptionCount; ?>" />
				<input type="hidden" name ="blogslideshowDescImage[]" id="blogslideshowDescImage_<?php echo $blogslideshowDescriptionCount; ?>" value="<?=$image?>">
			</div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="bld topicTextarea" style="width:600px;">
			Description :
			<div class="formatTextarea">
				<textarea name="blogslideshowDesc[]" class="textboxBorder mceEditor"  id="blogslideshowDesc_<?php echo $blogslideshowDescriptionCount++; ?>" rows="45" caption="blog description" ><?php echo $pageContent; ?></textarea>
				<div class="lineSpace_12">&nbsp;</div>
			</div>
		</div>
		</div>
		<?php
		}
		?>
		<script>noOfslidewikkicontents = <?php echo $blogslideshowDescriptionCount-1; ?>;</script>
	</div>
	<div><a href="javascript:void(0);" onclick="addslidewikkicontent()">+ Add More</a></div>
	<div class="errorPlace"><div id="blogslideshowDesc_error" class="errorMsg"></div></div>
	
	
<script>

function addslidewikkicontent() {
	noOfslidewikkicontents++;
	no = noOfslidewikkicontents;
	var newdiv = document.createElement('div');
    var str = '\
		<div style="border:1px #aaa solid; width:80%;margin:10px;padding:10px">\
		<div>\
			<b>Sequence:</b>\
			<div class="formatTextarea" id="blogslideshowSequenceTag_'+noOfslidewikkicontents+'_wrapper">\
			</div>\
		</div>\
		<div class="lineSpace_10">&nbsp;</div>\
        <div>\
            <b>Slide Title:</b>\
			<div class="formatTextarea">\
				<input type="text"  name="blogslideshowDescTag[]" id="blogslideshowDescTag_'+noOfslidewikkicontents+'" value="" />\
			</div>\
			<b>Slide Subtitle:</b>\
			<div class="formatTextarea">\
				<input type="text"  name="blogslideshowDescTagSub[]" id="blogslideshowDescTagSub_'+noOfslidewikkicontents+'" value="" />\
			</div>\
        </div>\
	    <div class="errorPlace">\
		    <div id="blogslideshowDescTag_'+ noOfslidewikkicontents +'_error" class="errorMsg"></div>\
			<div class="lineSpace_12">&nbsp;</div>\
    	</div>\
		<div class="lineSpace_10">&nbsp;</div>\
		<div>\
			<b>Image:</b>\
			<div class="formatTextarea">\
				<input type="button" onclick="showSlideOverlay('+noOfslidewikkicontents+');" class="orange-button" value="Upload Image"/>\
				<br/><img src="/public/images/blankImg.gif" style="margin:10px;"  width="120" height="80"  id="blogslideshowDescImageSrc_'+noOfslidewikkicontents+'" />\
				<input type="hidden" name ="blogslideshowDescImage[]" id="blogslideshowDescImage_'+noOfslidewikkicontents+'" value="">\
			</div>\
		</div>\
		<div class="lineSpace_10">&nbsp;</div>\
        <div class="bld topicTextarea" style="width:725px;">\
            Description : <div class="formatTextarea">\
                    <textarea name="blogslideshowDesc[]" class="textboxBorder mceEditor"  id="blogslideshowDesc_'+ noOfslidewikkicontents +'" rows="45" caption="blog description" ></textarea>\
                    <div class="lineSpace_12">&nbsp;</div>\
                </div>\
        </div>\
		</div>\
    ';

	newdiv.innerHTML = str;
	newdiv.setAttribute('id','main_container_'+no);
	$('blogslideshowDescriptionContainer').appendChild(newdiv);
	tinyMCE.execCommand('mceAddControl', false, 'blogslideshowDesc_'+no);
    //tinyMCE.execCommand('mceAddControl', false, 'blogslideshowDesc_'+no);
	for(var i = 0;i<=noOfslidewikkicontents;i++){
		var tempdiv = $('blogslideshowSequenceTag_'+i+'_wrapper');
		var tempvalue = i+1;
		if($('blogslideshowSequenceTag_'+i)){
			var tempvalue = $('blogslideshowSequenceTag_'+i).value;
		}
 		var tempHTML = '<select  name="blogslideshowSequenceTag[]" id="blogslideshowSequenceTag_'+i+'">';
		for(var j = 1;j<=(noOfslidewikkicontents+1);j++){
			tempHTML +=	'<option value="'+j+'">'+j+'</option>';
		}
		tempHTML += '</select>';
		tempdiv.innerHTML = tempHTML;
		$('blogslideshowSequenceTag_'+i).value = tempvalue;
	}
    setImageContainer();
	
	
}
</script>
