<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style'),
	//'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','discussion','events','listing','blog'),
	'js'	=> array('mailer','common','enterprise','CalendarPopup','ajax-api'),
	//'jsFooter'         => array('scriptaculous','utils'),
	'jsFooter'         => array(),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=>	'Mass Mailer',
	'taburl' => site_url('mailer/Mailer'),
	'metaKeywords'	=>'',
	'noextra_js'=>true
);
    $this->load->view('enterprise/headerCMS', $headerComponents);
    //$this->load->view('enterprise/cmsTabs',$cmsUserInfo);
    $this->load->view('common/calendardiv');
?>
<SCRIPT LANGUAGE="JavaScript">
    var calMain = new CalendarPopup("calendardiv");
    function validateAndSubmit(){
        if (trim(document.getElementById('temp1_name').value) == "")
        {
            alert('Please enter the template name');
            document.getElementById("temp1_name").focus();
            return false;
        }
        if (trim(document.getElementById('temp_desc').value) == "")
        {
            alert('Please enter the template description');
            document.getElementById("temp_desc").focus();
            return false;
        }

        if (trim(document.getElementById('temp_subj').value) == "")
        {
            alert('Please enter the template subject');
            document.getElementById("temp_subj").focus();
            return false;
        }

        if (trim(document.getElementById('temp_html').value) == "")
        {
            alert('Please enter the template html');
            document.getElementById("temp_html").focus();
            return false;
        }
        $("formForEditTemplate").submit()
    }
</SCRIPT>
</head>
<body>
	<div id="dataLoaderPanel" style="position:absolute;display:none">
		<img src="/public/images/loader.gif"/>
	</div>
	<div class="mar_full_10p"  style="margin-top: 15px;">
    		<!--div style="margin-left:1px">
        		<div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Mass Mailer</div>
        		<div class="grayLine"></div>
        		<div class="lineSpace_10">&nbsp;</div>
			</div-->
	<?php
	$this->load->view('mailer/left-panel-top');
	?>
	<?php
	// Form Start
	$temp_id = (isset($resultSet[0]['id']) && !empty($resultSet[0]['id']) )?$resultSet[0]['id']:'';
	$temp_name = (isset($resultSet[0]['name']) && !empty($resultSet[0]['name']) )?$resultSet[0]['name']:'';
	$temp_desc = (isset($resultSet[0]['description']) && !empty($resultSet[0]['description']) )?$resultSet[0]['description']:'';
	$temp_html = (isset($resultSet[0]['htmlTemplate']) && !empty($resultSet[0]['htmlTemplate']) )?$resultSet[0]['htmlTemplate']:'';
	$subject = (isset($resultSet[0]['subject']) && !empty($resultSet[0]['subject']) )?$resultSet[0]['subject']:'';
    //if($templateType == "sms") {
    //    echo "<b>SMS</b>";
    //}else {
    //    echo "<b>Mail</b>";
    //}
	?>
	
	<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:20px;"><strong><?php echo $mode == 'edit' ? 'Edit Template' : 'New Template'; ?></strong></div>
	
	<form id="formForEditTemplate" action="/mailer/Mailer/UpdateForm" method="POST">
            <input type="hidden" name="edit_form_mode" value="<?php echo $mode; ?>" />
            <input type="hidden" name="temp_id" value="<?php echo $temp_id; ?>" />
            <input type="hidden" name="templateType" value="<?php echo $templateType; ?>" />
            <div class="row">
                <div style="display:inline;float:left;width:100%">
                    <div class="r1_1 bld" style="width:120px; font-size:13px; padding-top:5px; color:#333;">Template Name:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input tip="mailer_temp_name" required="true" profanity="true" validate="validateStr" type="text" name="temp1_name" value="<?php echo $temp_name; ?>" id="temp1_name" size="30" maxlength="125" minlength="2" caption="Template Name" style="width:500px; border:1px solid #ccc; font-size:13px; padding:5px 2px;" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1" style="width:120px;">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "temp1_name_error"></div>
                        <div class="clear_L"></div>
                    </div>
					<div class="clearFix"></div>
                </div>
				<div class="clearFix"></div>
            </div>
			<div class="clearFix"></div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld" style="width:120px; font-size:13px; color:#333;">Description:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <textarea tip="mailer_temp_desc" type="text" name="temp_desc" id="temp_desc" maxlength="1000" minlength="2" required="true" profanity="true" validate="validateStr" caption="Template Description" style="width:500px; border:1px solid #ccc; font-size:13px; padding:5px 2px; height:40px;" /><?php echo $temp_desc ; ?></textarea>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1" style="width:120px;">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "temp_desc_error"></div>
                    </div>
					<div class="clearFix"></div>
                </div>
				<div class="clearFix"></div>
            </div>
			<div class="clearFix"></div>
            <div class="lineSpace_10">&nbsp;</div>

			<div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld" style="width:120px; font-size:13px; color:#333;">Subject:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <textarea tip="mailer_temp_subj" type="text" name="temp_subj" required="true" profanity="true" validate="validateStr" id="temp_subj" maxlength="1000" minlength="2"  caption="Template Subject" style="width:500px; border:1px solid #ccc; font-size:13px; padding:5px 2px; height:40px;" /><?php echo $subject ; ?></textarea>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1" style="width:120px;">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "temp_subj_error"></div>
                    </div>
					<div class="clearFix"></div>
                </div>
				<div class="clearFix"></div>
            </div>
			<div class="clearFix"></div>
            <div class="lineSpace_10">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld" style="width:120px; font-size:13px; color:#333;">HTML:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <textarea type="text" name="temp_html" id="temp_html" class="w62_per <?php if($templateType != "sms") {echo "mceEditor1";} ?>" style="height:600px;width:600px; font-size:13px;" caption="Template HTML" /><?php echo $temp_html ; ?></textarea>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1" style="width:120px;">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "temp_html_error"></div>
                    </div>
					<div class="clearFix"></div>
                </div>
				<div class="clearFix"></div>
            </div>
			<div class="clearFix"></div>
            <div class="lineSpace_10">&nbsp;</div>
			
			<div style="background:#f0f0f0; text-align: center; padding:5px 0">
			
				<button class="btn-submit19" type="button" onclick="opennewwindow();" value="" style="width:100px">
                    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Preview</p></div>
                </button>
			
				<button class="btn-submit19" type="button" onclick='validateAndSubmit()' value="" style="width:100px">
                    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Next</p></div>
                </button>
			</div>
			
            <!--div class="r1_1 bld" style="width:120px;">&nbsp;</div>
            <div class="r2_2">
                <button class="btn-submit19" type="button" onclick="return validate_update_tpl();" value="" style="width:100px">
                    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Next</p></div>
                </button>
            </div-->
            <div class="clear_L"></div>
            <div class="lineSpace_10">&nbsp;</div>
        </form>
<!--
        <div class="row">
                <div style="display:inline;float:left;width:100%">
                    <div class="r1_1 bld" style="width:120px; font-size:13px;">Poll:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input type="checkbox" name="poll_checkbox" id="poll_checkbox" value="poll_checkbox" onclick="view_poll_form(this)"; />
                    </div>
                    <div class="clear_L"></div>
                </div>
            </div>
-->
        <div class="lineSpace_10">&nbsp;</div>
        <div id="poll_form" style="display:none;" >
			<form id="Form_Create_New_Poll" method="POST">
					<div class="row">
					<div style="display:inline;float:left;width:100%">
						<div class="r1_1 bld">Poll Question:&nbsp;&nbsp;</div>
						<div class="r2_2">
							<input tip="mailer_poll_question" required="true" profanity="true" validate="validateStr" type="text" name="poll_question" id="poll_question" size="30" maxlength="125" minlength="5" caption="Poll Question"/>
						</div>
						<div class="clear_L"></div>
						<div class="row errorPlace" style="margin-top:2px;">
						<div class="r1_1">&nbsp;</div>
						<div class="r2_2 errorMsg" id= "poll_question_error"></div>
						<div class="clear_L"></div>
						</div>
					</div>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
					<div class="row">
						<div style="display: inline; float:left; width:100%">
							<div class="r1_1 bld">Answer 1:&nbsp;&nbsp;</div>
							<div class="r2_2">
								<input tip="mailer_ans_1" type="text" size="30" name="ans_1" type="text"  id="ans_1" maxlength="125" minlength="2" required="true" profanity="true" validate="validateStr" caption="Answer 1" />
							</div>
							<div class="clear_L"></div>
							<div class="row errorPlace" style="margin-top:2px;">
							<div class="r1_1">&nbsp;</div>
							<div class="r2_2 errorMsg" id= "ans_1_error"></div>
							</div>
						</div>
					</div>
					<div class="lineSpace_10">&nbsp;</div>

					<div class="row">
						<div style="display: inline; float:left; width:100%">
							<div class="r1_1 bld">Answer 2:&nbsp;&nbsp;</div>
							<div class="r2_2">
							<input tip="mailer_ans_2" type="text" size="30" name="ans_2" type="text"  id="ans_2" maxlength="125" minlength="2" required="true" profanity="true" validate="validateStr"  caption="Answer 2" />
							</div>
							<div class="clear_L"></div>
							<div class="row errorPlace" style="margin-top:2px;">
							<div class="r1_1">&nbsp;</div>
							<div class="r2_2 errorMsg" id= "ans_2_error"></div>
							</div>
						</div>
					</div>
					<div class="lineSpace_10">&nbsp;</div>

					<div class="row">
						<div style="display: inline; float:left; width:100%">
							<div class="r1_1 bld">Answer 3:&nbsp;&nbsp;</div>
							<div class="r2_2">
							<input tip="mailer_ans_3" type="text" size="30" name="ans_3" type="text"  id="ans_3" maxlength="125" minlength="2" required="true" profanity="true" validate="validateStr"   caption="Answer 3" />
							</div>
							<div class="clear_L"></div>
							<div class="row errorPlace" style="margin-top:2px;">
							<div class="r1_1">&nbsp;</div>
							<div class="r2_2 errorMsg" id= "ans_3_error"></div>
							</div>
						</div>
					</div>
					<div class="r1_1 bld">&nbsp;</div>
					<div class="r2_2">
						<button class="btn-submit19" type="button" onclick="return generate_html($('poll_question').value,$('ans_1').value,$('ans_2').value,$('ans_3').value);" value="" style="width:100px">
							<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:9px" class="btn-submit20">Generate HTML</p></div>
						</button>
					</div>
				<div class="clear_L"></div>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="row">
						<div style="display: inline; float:left; width:100%">
							<div class="r1_1 bld">&nbsp;&nbsp;</div>
							<div class="r2_2">
							<textarea id="html_poll_content" name="html_poll_content" style="display:none;height:150px;"></textarea>
							<div class="clear_L"></div>
						</div>
				</div>
			</form>
        </div>
	<?php
	// Form end
	?>
	<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	</div>
	<script>
    function opennewwindow(){
        myWindow=window.open('','');
        var tempHtml = document.getElementById('temp_html').value;
        tempHtml = tempHtml.replace(/&gt;/gi, '>').replace(/&lt;/gi, '<')
        myWindow.document.write(tempHtml);
        myWindow.focus();
        //exit();
    }
    /*
	tinyMCE.init({
		// General options
		mode : "textareas",
		//cleanup : false,
		//apply_source_formatting : false,
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",
		convert_urls : false ,
		relative_urls : false,
		remove_script_host : false,
		verify_html : false,
		preformatted : true,
		inline_styles : true,
		extended_valid_elements : "img[class|src|border=0|alt|title|hspace|vspace|width|height|align|hidden|input|<|>|type|value|td|br|method|form|textarea|action|tr",
		// Theme options
		theme_advanced_buttons1 : "code,|,bold,italic,underline,|,preview,|,fullscreen,",
		theme_advanced_buttons2 : "cut,copy,paste,|,search,replace,|,bullist,numlist,|,outdent,indent,|,undo,redo,",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_resizing : true,
		editor_selector : "mceEditor",
		editor_deselector : "mceNoEditor",
		// Example word content CSS (should be your site CSS) this one removes paragraph margins
		content_css : "css/word.css",
		
		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js"
	});
    addOnFocusToopTip(document.getElementById('formForEditTemplate'));
   	addOnBlurValidate(document.getElementById('formForEditTemplate'));
   	addOnFocusToopTip(document.getElementById('Form_Create_New_Poll'));
   	addOnBlurValidate(document.getElementById('Form_Create_New_Poll'));
    */
   	</script>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php //$this->load->view('enterprise/footer'); ?>
