<?php 
	$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style', 'marketing'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  'Customized MMP',
        'taburl' => site_url('customizemmp/mmp/showCustomizedMMP/'),
        'metaKeywords'  =>''
        );
	
	$this->load->view('enterprise/headerCMS', $headerComponents);
	$this->load->view('enterprise/cmsTabs');
?>
<script>
	
	function submitMMPTemplateForm(){
		var form_ele = document.forms["upload_mmp_template"];
		var file_ele_value = trim(form_ele.elements["template_file"].value);
		if(file_ele_value.length > 0){
			return true;
		}
		return false;
	}
	
</script>
<div class="mmp_main_container">
		<?php
		if((int)$page_details['count_courses'] > 0){
		?>
			<div style="margin-bottom: 10px; font-size: 13px; width: 100%;" class="orangeColor fontSize_14p bld"><b>Upload MMP Template for MMP ID = <?php echo $currentPageId;?></b>
				<div style="margin-top: 5px;" class="grayLine_1">&nbsp;</div>
			</div>
		<?php
		} else {
		?>
			<div class="mmp_message_container">
				<p><em>Please add some courses to MMP before uploading template</em></p>
				<ul>
					<li><a href="/customizedmmp/mmp/addRemoveMMPageCourses/<?php echo $currentPageId;?>">Pick courses</a> for MMP ID: <?php echo $currentPageId;?></li>
					<li><a href="/customizedmmp/mmp/showCustomizedMMP/<?php echo $currentPageId;?>">Go back</a></li>
				</ul>
			</div>
		<?php
		}
		?>
	<?php
	// Backend side form validation failed or success message
	if(is_array($mmp_custom_params) && !empty($mmp_custom_params)) {
		$errorFlag = $mmp_custom_params['error'];
		$successFlag = $mmp_custom_params['success'];
		if($errorFlag) {
			?>
			<div class="mmp_message_container" id="global_error_message_container">
				<p><em>Error occured while upload mmp template</em></p>
				<?php
				foreach($mmp_custom_params as $key=>$val) {
				?>
					<ul>
					<?php
						if($key != "error" && !is_array($val)){
						?>
							<li><?php echo $val;?></li>
						<?php
						} else if(is_array($val)){ 
							foreach($val as $k=>$v){
								?>
								<li><?php echo $v;?></li>
								<?php
							}
						}
					?>
					</ul>
					<?php
				}
				?>
			</div>
			<?php
		} else if($successFlag){
			?>
			<div class="mmp_success_message_container" id="global_success_message_container">
				<p><em style="color:#698B22;">MMP Template file successfully uploaded</em></p>
				<ul>
					<li><a href="<?php echo SHIKSHA_HOME ?>/customizedmmp/mmp/templateFormSandbox/<?php echo $currentPageId;?>/index.html">Preview MMP</a></li>
					<li>Go back to customized mmp listing page : <a href="<?php echo SHIKSHA_HOME ?>/customizedmmp/mmp/showCustomizedMMP/<?php echo $currentPageId;?>">customized MMP listings</a></li>
				</ul>
			</div>
			<?php
		}
	} else {
		$mmp_custom_params = array();
	}
	?>
	<?php
	if((int)$page_details['count_courses'] > 0) {
	?>
	 	<fieldset class="mmp_main_fieldset" id="mmp_add_new_page_container">
			<form enctype="multipart/form-data" accept-charset="utf-8" id="upload_mmp_template" name="upload_mmp_template" method="post" action="/customizedmmp/mmp/uploadMMPTemplate/" onSubmit="return submitMMPTemplateForm();">
				<legend class="mmp_main_fieldsetlegend">Upload MMP Template</legend>
				<div class="mmp_add_new_mmp_form_container">
					<div class="mmp_field_container">
						<label class="mmp_field_label" for="mmp_template">MMP Template <em>*</em></label>
						<input type="file" name="template_file" size="50" /> 
						<div id="mmp_template_help_div" class="mmp_field_help_note">MMP Template can be in zip format only. There should be images, js, css folder and index.html file in it. CONFUSION: <b>Ask developer</b></div>
					</div>
					<div class="mmp_form_buttons">
						<input type="submit" name="submit" value="submit"/>
						<input type="button" name="cancel" value="cancel" onclick="window.location='/customizedmmp/mmp/showCustomizedMMP/<?php echo $currentPageId;?>'"/>
						<input type="hidden" name="page_id" value="<?php echo $currentPageId; ?>"/>
					</div>
				</div>
			</form>
		</fieldset>
	<?php
	}
	?>
</div>
<?php $this->load->view('common/footer');?>
