<?php
$blogQnADesc = (isset($blogInfo[0]['blogQnA'])&&(strlen($blogInfo[0]['blogQnA'])>2)) ? json_decode($blogInfo[0]['blogQnA'], true) : array('');
?>
<div class="lineSpace_10">&nbsp;</div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="bld mb5">Question & Answers <span style="color:#FF0000;">*</span>:</div>
	<div id="blogQnADescriptionContainer">
		<?php
			$blogQnADescriptionCount = 0;
			foreach($blogQnADesc as $blogQnADescription) {
				$pageContent = $blogQnADescription['answer'];
				$pageTag = $blogQnADescription['question'];
				$sequence = $blogQnADescription['sequence'];
		?>
		<div style="border:1px #aaa solid; width:80%;margin:10px;padding:10px">
		<div>
			<b>Sequence:</b>
			<div class="formatTextarea" id="blogQnASequenceTag_<?php echo $blogQnADescriptionCount; ?>_wrapper">
				<select  name="blogQnASequenceTag[]" id="blogQnASequenceTag_<?php echo ($blogQnADescriptionCount); ?>">
				<?php for($i = 1;$i<=count($blogQnADesc);$i++){ ?>
					<option value="<?=$i?>"><?=$i?></option>
				<?php } ?>
				</select>
			</div>
		<script>
			$('blogQnASequenceTag_<?=($blogQnADescriptionCount)?>').value = '<?=($blogQnADescriptionCount+1)?>';
		</script>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div>
			<b>Question:</b>
			<div class="formatTextarea">
				<textarea  name="blogQnADescTag[]" id="blogQnADescTag_<?php echo $blogQnADescriptionCount; ?>"><?php echo $pageTag; ?></textarea>
			</div>
		</div>
		
		<div class="errorPlace"><div id="blogQnADescTag_<?php echo $blogQnADescriptionCount; ?>_error" class="errorMsg"></div></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="bld topicTextarea" style="width:600px;">
			Answer :
			<div class="formatTextarea">
				<textarea name="blogQnADesc[]" class="textboxBorder mceEditor"  id="blogQnADesc_<?php echo $blogQnADescriptionCount++; ?>" rows="45" caption="blog description" style="width:99%;height:500px" ><?php echo $pageContent; ?></textarea>
				<div class="lineSpace_12">&nbsp;</div>
			</div>
		</div>
		</div>
		<?php
		}
		?>
		<script>noOfqueswikkicontents = <?php echo $blogQnADescriptionCount-1; ?>;</script>
	</div>
	<div><a href="javascript:void(0);" onclick="addqueswikkicontent()">Add More Questions</a></div>
	<div class="errorPlace"><div id="blogQnADesc_error" class="errorMsg"></div></div>
	
	
<script>

function addqueswikkicontent() {
	noOfqueswikkicontents++;
	no = noOfqueswikkicontents;
	var newdiv = document.createElement('div');
    var str = '\
		<div style="border:1px #aaa solid; width:80%;margin:10px;padding:10px">\
		<div>\
			<b>Sequence:</b>\
			<div class="formatTextarea" id="blogQnASequenceTag_'+noOfqueswikkicontents+'_wrapper">\
			</div>\
		</div>\
		<div class="lineSpace_10">&nbsp;</div>\
        <div>\
            <b>Question:</b>\
			<div class="formatTextarea">\
				<textarea  name="blogQnADescTag[]" id="blogQnADescTag_'+noOfqueswikkicontents+'"></textarea>\
			</div>\
        </div>\
	    <div class="errorPlace">\
		    <div id="blogQnADescTag_'+ noOfqueswikkicontents +'_error" class="errorMsg"></div>\
			<div class="lineSpace_12">&nbsp;</div>\
    	</div>\
		<div class="lineSpace_10">&nbsp;</div>\
        <div class="bld topicTextarea" style="width:725px;">\
            Answer: <div class="formatTextarea">\
                    <textarea name="blogQnADesc[]" class="textboxBorder mceEditor"  id="blogQnADesc_'+ noOfqueswikkicontents +'" rows="45" caption="blog description" style="width:99%;height:500px" ></textarea>\
                    <div class="lineSpace_12">&nbsp;</div>\
                </div>\
        </div>\
		</div>\
    ';

	newdiv.innerHTML = str;
	newdiv.setAttribute('id','main_container_'+no);
	$('blogQnADescriptionContainer').appendChild(newdiv);
    tinyMCE.execCommand('mceAddControl', false, 'blogQnADesc_'+no);
	for(var i = 0;i<=noOfqueswikkicontents;i++){
		var tempdiv = $('blogQnASequenceTag_'+i+'_wrapper');
		var tempvalue = i+1;
		if($('blogQnASequenceTag_'+i)){
			var tempvalue = $('blogQnASequenceTag_'+i).value;
		}
 		var tempHTML = '<select  name="blogQnASequenceTag[]" id="blogQnASequenceTag_'+i+'">';
		for(var j = 1;j<=(noOfqueswikkicontents+1);j++){
			tempHTML +=	'<option value="'+j+'">'+j+'</option>';
		}
		tempHTML += '</select>';
		tempdiv.innerHTML = tempHTML;
		$('blogQnASequenceTag_'+i).value = tempvalue;
	}
    setImageContainer();
}
</script>