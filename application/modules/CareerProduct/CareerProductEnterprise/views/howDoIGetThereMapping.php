
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	
<script>
$j = $.noConflict();
</script>

<div id="career-cms-wrapper">
        	<?php $this->load->view('CareerProductEnterprise/subtabsCareers');?>
		<div class="cms-section">		
		<ul>
                	<li>
                    	<label style="width:138px; font-size:16px">Careers:</label>
                        <div class="career-fields" style="width:300px">
                        	<select id="careerId" name="careerId" class="universal-select" style="width:210px"  onChange="careerObj.getPathInformation(this.value);">
					<option value=0 onChange="careerObj.reloadPage(this.value,'<?php echo $careerType;?>');">Select</option>
					<?php for($i=0;$i<count($careerList);$i++): ?>
					<option value="<?php echo $careerList[$i]['careerId'];?>" <?php if($careerId==$careerList[$i]['careerId']){echo "selected";}?>><?php echo $careerList[$i]['name'];?>
					<?php endfor; ?>
				</select>
			<div id="careers_error" class="errorMsg" style="display:none;">&nbsp;</div>
                        </div>
                    </li>
</ul>
</div>  <?php $addPathId = $maxCareerPath;?>
	<?php $count=1;?>
	<?php if(empty($careerPathInformation) && empty($maxCareerPath)):?>
	<?php $addPathId = 1;?>
	<?php endif;?>
	<?php if(empty($careerPathInformation) && !empty($maxCareerPath)):?>
	<?php $addPathId = $maxCareerPath+1;?>
	<?php endif;?>
	<?php if(!empty( $careerId)){?>
	<div class="flRt">
	<?php if($count==1){?>
		            	<input type="button" value="Add New Path" class="gray-button" onClick="careerObj.addNewPath('<?php echo $addPathId;?>','<?php echo $careerId;?>');" id="addNewPath<?php echo $i;?>"/>
				<?php }?>
	</div>
	<?php }else{?>
	<div class="flLt">
	<span style="color:red;">Please Select Career Option.</span>
	</div>
	<?php } ?>
				<div class="spacer10 clearFix"></div>
	<div id="path0" style="display:none;" ></div>
	<?php foreach($careerPathInformation as $key=>$value):?>
	   <div id="path<?php echo $value['pathId'];?>">
		<div class="cms-section">
		    	<div class="sectoion-title">
		    		<h2>Path <?php echo $count;?></h2>
		            <div class="flLt" style="margin-left: 10px;">
		                <input type="button" value="Delete Path" class="gray-button" onClick="careerObj.removePath('<?php echo $value['pathId'];?>','<?php echo $careerId;?>');"/>
		                <input type="hidden" id="countOfCustomFields_<?php echo $value['pathId'];?>" value="<?php if(count($value['stepDetails'])) { echo count($value['stepDetails']); } else { echo 2; } ?>" />
		            </div>
		        </div>
		
		<div class="steps-main-block">
			<?php $j=1;?>
			<?php
			if(empty($value)){
				$value = array('pathId'=>1,'stepDetails'=>array(array('stepOrder'=>'1','stepDescription'=>''),array('stepOrder'=>'2','stepDescription'=>'')));
			} 
			if(empty($value['stepDetails'])){
				$value = array('pathId'=>$value['pathId'],'stepDetails'=>array(array('stepOrder'=>'1','stepDescription'=>''),array('stepOrder'=>'2','stepDescription'=>'')));
			}
			?>
			<div id="path<?php echo $value['pathId'];?>_0" style="display:none"></div>
			<?php foreach($value['stepDetails'] as $k=>$v):?>
			 
			 <div class="steps-block" id="path<?php echo $value['pathId'];?>_<?php echo $v['stepOrder'];?>">
				<ul> 
		                    <li>
		                        <label>Step <?php echo $j;?>:</label>
		                        <div class="career-fields">
		                            <input minlength="1" maxlength="100" caption="step" validate="validateStr" type="text" class="universal-txt-field" style="width:87%;" id="subtitle<?php echo $value['pathId'];?>_<?php echo $v['stepOrder'];?>" name="subtitle<?php echo $value['pathId'];?>_<?php echo $v['stepOrder'];?>" value="<?php echo htmlentities($v['stepTitle']);?>" onkeyup="careerObj.enableSaveButton('<?php echo $value['pathId'];?>_<?php echo $v['stepOrder'];?>');"/>
					<div style="display:none"><div class="errorMsg"  id="subtitle<?php echo $value['pathId'];?>_<?php echo $v['stepOrder'];?>_error"></div></div>
		                        </div>
		                    </li>
				    <li>
		                        <label>Sub Heading:</label>
		                        <div class="career-fields">
		                             <textarea  minlength="1" maxlength="2500" class='mceEditor' caption="Description" 	id="wikkicontent_description<?php echo $value['pathId'];?>_<?php echo $v['stepOrder'];?>" name="wikkicontent_description<?php echo $value['pathId'];?>_<?php echo $v['stepOrder'];?>" style="width:370px;height:100px;" newAttr="<?php echo $value['pathId'];?>_<?php echo $v['stepOrder'];?>"><?php echo $v['stepDescription'];?></textarea>
					<div><div id="wikkicontent_description<?php echo $value['pathId'];?>_<?php echo $v['stepOrder'];?>_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
		                        </div>
                            	    </li>
				    <li>
		                        <label>&nbsp;</label>
		                        <div class="career-fields">
					<div><div id="stepOrder_<?php echo $v['stepOrder'];?>_<?php echo $value['pathId'];?>_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
		                            <div class="flLt"><input id="save_<?php echo $value['pathId'];?>_<?php echo $v['stepOrder'];?>" disabled="disabled" type="button" value="Save" class="gray-button" onClick="careerObj.saveCustomFieldInPathTab('<?php echo $value['pathId'];?>','<?php echo $v['stepOrder'];?>');"/></div>
		                            <div class="remove-steps"><a href="javascript:void(0);" onClick="careerObj.removeCustomFieldInPathTab('<?php echo $value['pathId'];?>','<?php echo $v['stepOrder'];?>','<?php echo $careerId;?>');">x Remove Step</a></div>
		                        </div>
                                   </li>
				</ul>
			 <div class="clearFix"></div>
                    </div>
			 <?php $j++;?>
			 <?php endforeach;?>
			<strong><a id="addStepHTML" href="javascript:void(0);" onClick="careerObj.addCustomFieldInPathTab('<?php echo $value['pathId'];?>','<?php echo $careerId;?>');">+ Add Step</a></strong>
		</div>
	
		<div class="btn-cont2"><input type="button" value="Preview" class="orange-button" onClick="careerObj.getPreviewInformation('<?php echo $value['pathId'];?>','<?php echo $careerId;?>','<?php echo $count;?>');"/></div>
		
	</div>
		<div class="clearFix"></div>
		<div class="steps-main-block" id="preview_<?php echo $value['pathId'];?>_<?php echo $careerId;?>" style="display:none;"></div>
	
<div class="clearFix"></div>
</div>
<div class="clearFix"></div>
	<script>
		//careerObj.careerPath['path'] = "<?php if(!empty($value['pathCount'])){ echo $maxCareerPath;}else{ echo "1";};?>";
		careerObj.careerPath['pathIdToDisplay'] = "<?php echo $count;?>";
		var pathId = "<?php echo $value['pathId'];?>";
		careerObj.careerPath['path'+pathId] = "<?php echo $v['stepOrder'];?>";
	</script>
	<?php $count++;?>
	<?php endforeach;?>
</div>
<script>
function stripHtml(s) {
	        return s.replace(/<\S[^><]*>/g, '');
}
window.onload = function() {
	initTMCEEditor();
	hideDisplayAddStep();
};

function hideDisplayAddStep() {
			$j('.steps-main-block').each(function(i){ 
				var len = $j(this).find(".steps-block").length; 
				if(len > 0 && len < 5) {
					$j(this).find("a[id='addStepHTML']").show();
				} else {
					$j(this).find("a[id='addStepHTML']").hide();
				}

			});
}

function myCustomInitInstance(ed) {
    if (tinymce.isIE || !tinymce.isGecko ) {
        tinymce.dom.Event.add(ed.getWin(), 'focus', function(e) {
        try {
                if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                        tinyMCE.activeEditor.setContent('');
                        }
            } catch (ex) {
                // do it later
            }
        });
        tinymce.dom.Event.add(ed.getWin(), 'blur', function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                    tinyMCE.activeEditor.setContent('');
                    }
        });
    } else {
        tinymce.dom.Event.add(ed.getDoc(), 'focus', function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                    tinyMCE.activeEditor.setContent('');
                    }
        });
        tinymce.dom.Event.add(ed.getDoc(), 'blur', function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                    tinyMCE.activeEditor.setContent('');
                    }
        });
    }
}

function initTMCEEditor(){;
tinyMCE.init({ 
	mode : "textareas",
	theme : "advanced",
    	plugins : "jbimages,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,undo,redo,|,cleanup,|,bullist,numlist,|,link,unlink,",
	theme_advanced_toolbar_location : "bottom",
        theme_advanced_path : false,
	theme_advanced_statusbar_location : "none",
	theme_advanced_toolbar_align : "center",
   	force_p_newlines : false,init_instance_callback: "myCustomInitInstance", force_br_newlines : true,forced_root_block : '',editor_selector : "mceEditor", editor_deselector : "mceNoEditor",  setup : function(ed) {
		ed.onKeyUp.add(function(ed, e) { 
            var text_limit = document.getElementById(tinyMCE.activeEditor.id).getAttribute('maxLength');
            var strip = (tinyMCE.activeEditor.getContent()).replace(/(<([^>]+)>)/ig,"");
	    if(document.all && (strip == "&nbsp;" || strip == "<p>&nbsp;</p>")) {
                strip = "";
            }
            var newAttr = document.getElementById(tinyMCE.activeEditor.id).getAttribute('newAttr');
	    careerObj.enableSaveButton(newAttr);
            
            var text = "<b>"+strip.length + "</b> out of <b>"+ text_limit + "</b> characters.";
            if (text_limit != null) {
                 if (strip.length > text_limit) {
		     document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'inline';
                    document.getElementById(tinyMCE.activeEditor.id +'_error').style.display = 'inline';
                    document.getElementById(tinyMCE.activeEditor.id+'_error').innerHTML = "You have used <b>"+ strip.length + "</b> Characters. Please use a maximum of "+ text_limit +" characters.";
                    tinyMCE.execCommand('mceFocus', false, tinyMCE.activeEditor.id);
                    return false;
                } else { 
                    document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'none';
                    document.getElementById(tinyMCE.activeEditor.id+'_error').innerHTML = '';
                }
            }
            var textBoxContent = trim(tinyMCE.activeEditor.getContent());
            textBoxContent = textBoxContent.replace(/[(\n)\r\t\"\']/g,' ');
            textBoxContent = textBoxContent.replace(/[^\x20-\x7E]/g,'');//alert(textBoxContent+"===="+tinyMCE.activeEditor.id);
	    document.getElementById(tinyMCE.activeEditor.id).value = textBoxContent;
		});
		}});
}
</script>
