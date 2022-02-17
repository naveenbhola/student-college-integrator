    	<!--Code Starts form here-->
    	<div id="path<?php echo $pathId;?>">
            <div class="cms-section">
            	<div class="sectoion-title">
            		<h2>Path <?php echo $pathIdToDisplay;?></h2>
                    <div class="flLt"  style="margin-left: 10px;">

                        <input type="button" value="Delete Path" class="gray-button" onClick="careerObj.removePath('<?php echo $pathId;?>','<?php echo $careerId;?>');"/>
                        <input type="hidden" id="countOfCustomFields_<?php echo $pathId;?>" value="2" />
                    </div>
                </div>
                
                <div class="steps-main-block">
		          <div id="path<?php echo $pathId;?>_0" style="display:none"></div>
                    <div class="steps-block" id="path<?php echo $pathId;?>_1">
                        <ul>
                            <li>
                                <label>Step 1:</label>
                                <div class="career-fields">
                                    <input  onkeyup="careerObj.enableSaveButton('<?php echo $pathId;?>_1');" minlength="1" maxlength="100" caption="step" validate="validateStr" type="text" class="universal-txt-field" style="width:87%;" id="subtitle<?php echo $pathId;?>_1" name="subtitle<?php echo $pathId;?>_1"/>
				 <div style="display:none"><div class="errorMsg"  id="subtitle<?php echo $pathId;?>_1_error"></div></div>
                                </div>
                            </li>
                            
                            <li>
                                <label>Sub Heading:</label>
                                <div class="career-fields">
                                    <textarea  minlength="1" maxlength="2500" class='mceEditor' caption="Description" 	newAttr="<?php echo $pathId;?>_1" id="wikkicontent_description<?php echo $pathId;?>_1" name="wikkicontent_description<?php echo $pathId;?>_1" style="width:370px;height:100px;" ></textarea>
				<div><div id="wikkicontent_description<?php echo $pathId;?>_1_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                                </div>
                            </li>
                            
                            <li>
                                <label>&nbsp;</label>
                                <div class="career-fields">
				    <div><div id="stepOrder_1_<?php echo $pathId;?>_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                                    <div class="flLt"><input   disabled="disabled" id="save_<?php echo $pathId;?>_1" type="button" value="Save" class="gray-button" onClick="careerObj.saveCustomFieldInPathTab('<?php echo $pathId;?>','1');"/></div>
                                    <div class="remove-steps"><a href="javascript:void(0);" onClick="careerObj.removeCustomFieldInPathTab('<?php echo $pathId;?>','1','<?php echo $careerId;?>');">x Remove Step</a></div>
                                </div>
                            </li>
                            
                        </ul>	
                        <div class="clearFix"></div>
                    </div>
                
                    <div class="steps-block" id="path<?php echo $pathId;?>_2">
                        <ul>
                            <li>
                                <label>Step 2:</label>
                                <div class="career-fields">
                                    <input  onkeyup="careerObj.enableSaveButton('<?php echo $pathId;?>_2');" minlength="1" maxlength="100" caption="step" validate="validateStr" type="text" class="universal-txt-field" style="width:87%;" id="subtitle<?php echo $pathId;?>_2" name="subtitle<?php echo $pathId;?>_2"/>
				<div style="display:none"><div class="errorMsg"  id="subtitle<?php echo $pathId;?>_2_error"></div></div>
                                </div>
                            </li>
                            
                            <li>
                                <label>Sub Heading:</label>
                                <div class="career-fields">
                                     <textarea  minlength="1" maxlength="2500" class='mceEditor' caption="Description" newAttr="<?php echo $pathId;?>_2" id="wikkicontent_description<?php echo $pathId;?>_2" name="wikkicontent_description<?php echo $pathId;?>_2" style="width:370px;height:100px;" ></textarea>
				<div><div id="wikkicontent_description<?php echo $pathId;?>_2_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                                </div>
                            </li>
                            
                            <li>
                                <label>&nbsp;</label>
                                <div class="career-fields">
				    <div><div id="stepOrder_2_<?php echo $pathId;?>_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                                    <div class="flLt"><input  disabled="disabled" id="save_<?php echo $pathId;?>_2" type="button" value="Save" class="gray-button" onClick="careerObj.saveCustomFieldInPathTab('<?php echo $pathId;?>','2');"/></div>
                                    <div class="remove-steps"><a href="javascript:void(0);" onClick="careerObj.removeCustomFieldInPathTab('<?php echo $pathId;?>','2','<?php echo $careerId;?>');">x Remove Step</a></div>
                                </div>
                            </li>
                            
                        </ul>	
                        <div class="clearFix"></div>
                    </div>
                
            		<strong><a id="addStepHTML" href="javascript:void(0);" onClick="careerObj.addCustomFieldInPathTab('<?php echo $pathId;?>','<?php echo $careerId;?>');">+ Add Step</a></strong>
                </div>
                <div class="btn-cont2"><input type="button" value="Preview" class="orange-button" onClick="careerObj.getPreviewInformation('<?php echo $pathId;?>','<?php echo $careerId;?>','<?php echo $pathIdToDisplay;?>');"/></div>
            </div>
        <div class="clearFix"></div>
	<div class="steps-main-block" id="preview_<?php echo $pathId;?>_<?php echo $careerId;?>" style="display:none;"></div>
            
        </div>
        <!--Code Ends here-->
<script>
function stripHtml(s) {
	        return s.replace(/<\S[^><]*>/g, '');
}
	
	function isProfaneTinymce(str) {
	        var profaneWordsBag = eval(base64_decode('WyJzdWNrIiwiZnVjayIsImRpY2siLCJwZW5pcyIsImN1bnQiLCJwdXNzeSIsImhvcm55Iiwib3JnYXNtIiwidmFnaW5hIiwiYmFiZSIsImJpdGNoIiwic2x1dCIsIndob3JlIiwid2hvcmlzaCIsInNsdXR0aXNoIiwibmFrZWQiLCJpbnRlcmNvdXJzZSIsInByb3N0aXR1dGUiLCJzZXgiLCJzZXh3b3JrZXIiLCJzZXgtd29ya2VyIiwiYnJlYXN0IiwiYnJlYXN0cyIsImJvb2IiLCJib29icyIsImJ1dHQiLCJoaXAiLCJoaXBzIiwibmlwcGxlIiwibmlwcGxlcyIsImVyb3RpYyIsImVyb3Rpc20iLCJlcm90aWNpc20iLCJsdW5kIiwiY2hvb3QiLCJjaHV0IiwibG9yYSIsImxvZGEiLCJyYW5kIiwicmFuZGkiLCJ0aGFyYWsiLCJ0aGFyYWtpIiwidGhhcmtpIiwiY2hvZCIsImNob2RuYSIsImNodXRpeWEiLCJjaG9vdGl5YSIsImdhYW5kIiwiZ2FuZCIsImdhbmR1IiwiZ2FhbmR1IiwiaGFyYWFtaSIsImhhcmFtaSIsImNodWRhaSIsImNodWRuYSIsImNodWR0aSIsImJhZGFuIiwiY2hvb2NoaSIsInN0YW4iLCJuYW5naSIsIm5hbmdhIiwibmFuZ2UiLCJwaHVkZGkiLCJmdWRkaSIsImxpZmVrbm90cyIsIjA5ODEwMTEyOTU0IiwiYWJpZGphbiIsInNpZXJyYS1MZW9uZSIsInNlbmVnYWwiLCJzaWVycmEgbGVvbmUiLCJsdWNreSBtYW4iLCJzaXJhIiwibWFkaGFyY2hvZCIsInRoYWJvIiwiZnVja2VkIiwiZnVja2luZyIsInB1YmxpYyBzaXRlIiwiRGFrdSIsInByaXZhdGUgbWFpbCIsInByaXZhdGUgbWFpbGJveCIsInNleHkiLCJqb2JzIHZhY2FuY2llcyIsIm9tbmkgY2l0eSIsImJhc3R1cmQiLCJqZWhhZCIsInRlbmRlcm5lc3MgY2FyZSJd'));
	        var words = str.split(" ");
	        for(var wordsCount = 0; wordsCount < words.length; wordsCount++) {
	                for(var profaneWordsCount = 0; profaneWordsCount < profaneWordsBag.length; profaneWordsCount++) {
	                        if(words[wordsCount] ==  profaneWordsBag[profaneWordsCount] ) {
	                                return profaneWordsBag[profaneWordsCount];
	                        }
	                }
	        }
	        return false;
	}
initTMCEEditor();
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

function initTMCEEditor(){
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
            /*
            var text = strip.split(' ').length + " Words, " +  strip.length + " Characters. You have " +(text_limit-strip.length)+" Chracter remaining.";
            */
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

            /*var profaneResponseWikki = isProfaneTinymce(stripHtml(textBoxContent));
            if(profaneResponseWikki !== false) {
                document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'inline';
                document.getElementById(tinyMCE.activeEditor.id +'_error').innerHTML = 'Please do not use objected words ('+ profaneResponseWikki +') for the Description';
                return false;
            }*/
		});
		}});
}
</script>

   
