<?php $this->load->view('CareerProductEnterprise/subtabsCareers');
foreach($careerData as $key=>$value){
	$$key = $value;
}
?>
<form enctype="multipart/form-data" novalidate="novalidate" id="careerInterlinkingForm" method="post" onsubmit="careerObj.clearErrorMsg();if(validateFields(this) != true){careerObj.validateInterLinkingPage()!=true;return false;}var errmsg = hierarchyMappingForm.customHierarchyValidations('', 'careerCms', 'formPost');if(errmsg != ''){hierarchyMappingForm.showErrorMessage(errmsg);return false;}if(careerObj.validateInterLinkingPage()==true){AIM.submit(this,{'onStart': startCallback, 'onComplete': hierarchyMappingForm.onFormSubmitCallback});} else {return false;} " action="/CareerProductEnterprise/CareerEnterprise/saveInterLinkingInformation" autocomplete="off">
			<div class="cms-section">
			<div class="sectoion-title">
			<h2>Careers</h2>
			</div>
			<div class="steps-main-block">
			<ul>
			<li>
			<div class="career-fields">
			<select id="careerId" name="careerId" onChange="careerObj.redirectToCareerPage(this.value,'<?php echo $careerType;?>','interlink');" class="universal-select" style="width:330px">
				<option value=0>Select</option>
				<?php for($i=0;$i<count($careerList);$i++):?>
				<option value="<?php echo $careerList[$i]['careerId'];?>" <?php if($careerId==$careerList[$i]['careerId']){echo "selected";}?>><?php echo $careerList[$i]['name'];?></option>
				<?php endfor;?>
			</select>
			<div id="careers_error" class="errorMsg" style="display:none;">&nbsp;</div>
			</div>
			</li>
			</ul>
			</div>
			</div>
			
			<div class="cms-section">
			<?php 
			if(!empty($careerId)){
				$prefilledData = Modules::run('common/commonHierarchyForm/getPrefilledData', 'careerCms', array('careerId' => $careerId));
			}
			echo Modules::run('common/commonHierarchyForm/getHierarchyMappingForm', 'careerCms', $prefilledData);?>
			</div>
	    <div class="cms-section">
            <div class="sectoion-title">
            <h2>Entrance Exams</h2>
            </div>
	    <div class="steps-main-block">
            <ul>
	    <li>
                 <div class="career-fields">
                 <textarea profanity="true" class='mceEditor'  minlength="1" maxlength="10000"  caption="Entrance Exams"  name="wikkicontent_entranceexams" id="wikkicontent_entranceexams" style="width:350px;height:100px;" ><?php echo $wikkicontent_entranceexams;?></textarea>
                 <div><div id="wikkicontent_entranceexams_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                 </div>
                 </li>
	    </ul>
	    </div>
	    </div>
	    <div class="cms-section">
            <div class="sectoion-title">
            <h2>College Predictor Widget</h2>
            </div>
	    <div class="steps-main-block">
            <ul>
	    <li>
                 <div class="career-fields">
                 <input type="checkbox" name="checkboxLinks"  onclick="checkUncheckValue('checkboxLinks');" id="checkboxLinks" <?php if($checkboxLinks=='true'){ echo "checked";}?>>&nbsp;Display
                 <div><div id="checkboxLinks_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                 </div>
                 </li>
	    </ul>
	    </div>
	    </div>
            <div class="btn-cont" style="margin-bottom:5px;">
                <div id="successMessage" class="errorMsg">&nbsp;</div>
		<div id="correct_above_error" class="errorMsg">&nbsp;</div>
            	<input type="submit" value="Publish" class="orange-button" id="submitButton"/>&nbsp;&nbsp;
            </div>
	    
</form>
<script>
window.onload = function() {initTMCEEditor();};
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

function checkUncheckValue(id){
	var state = $j('#'+id).is(':checked');
	$j('#'+id).val(state);
	
}
</script>
