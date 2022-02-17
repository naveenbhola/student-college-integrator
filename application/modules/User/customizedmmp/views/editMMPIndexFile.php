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
	function submitEditMMPIndexFile(){
		var textarea_ele = document.getElementById("file_content");
		var textarea_val = "";
		if(textarea_ele != undefined){
			textarea_val = textarea_ele.value;
		}
		if(textarea_val.length > 0){
			var x = window.confirm("Are you sure you want to make this change in index file?");
			if(x){
				return true;
			} else {
				return false;
			}	
		} else {
			var error_ele = document.getElementById("file_content_error_div");
			error_ele.innerHTML = "File content can't be blank";
			return false;
		}
	}
</script>

<div class="mmp_main_container">
	<div style="margin-bottom: 10px; font-size: 13px; width: 100%;" class="orangeColor fontSize_14p bld"><b>Edit Index file(<?php echo $file_type;?>) for MMP ID = <?php echo $currentPageId;?></b>
		<div style="margin-top: 5px;" class="grayLine_1">&nbsp;</div>
	</div>
	<?php
	// Backend side form validation failed or success message
	if(is_array($mmp_custom_params) && !empty($mmp_custom_params)) {
		$errorFlag = $mmp_custom_params['error'];
		$successFlag = $mmp_custom_params['success'];
		if($errorFlag) {
			?>
			<div class="mmp_message_container" id="global_error_message_container">
				<?php
				if(isset($mmp_custom_params['error_header'])){
				?>
					<p><em><?php echo $mmp_custom_params['error_header']; ?></em></p>
				<?php
				}
				if(isset($mmp_custom_params['error_text']) && count($mmp_custom_params['error_text']) > 0) {
				?>
					<ul>
					<?php
						foreach($mmp_custom_params['error_text'] as $key => $value){
							if(!is_array($value)){
							?>
								<li><?php echo $value;?></li>
							<?php
							} else {
								foreach($value as $k=>$v){
								?>
									<li><?php echo $v;?></li>
								<?php
								}
							}
						?>
						<?php
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
			<div class="mmp_message_container" id="global_error_message_container">
				<?php
				if(isset($mmp_custom_params['success_header'])){
				?>
					<p><em style="color:#698B22;"><?php echo $mmp_custom_params['success_header']; ?></em></p>
				<?php
				}
				if(isset($mmp_custom_params['success_text']) && count($mmp_custom_params['success_text']) > 0) {
				?>
					<ul>
					<?php
						foreach($mmp_custom_params['success_text'] as $key => $value){
						?>
							<li><?php echo $value;?></li>
						<?php
						}
					?>
					</ul>
					<?php
				}
				?>
			</div>
			<?php
		}
	} else {
		$mmp_custom_params = array();
	}
	?>
	<fieldset class="mmp_main_fieldset" id="mmp_add_new_page_container">
		<form id="edit_mmp_index_file" name="edit_mmp_index_file" method="post" action="/customizedmmp/mmp/postFileContent/" accept-charset="utf-8" onSubmit="return submitEditMMPIndexFile();">
			<legend class="mmp_main_fieldsetlegend">MMP Index file content</legend>
			<div class="mmp_add_new_mmp_form_container">
				<?php
					if($file_type == "sandbox"){
						$mmpPreviewLink = $sandbox_mmp_url;
						$headerText = "Current MMP sandbox file preview";
					} else if($file_type == "live"){
						$mmpPreviewLink = $live_mmp_url;
						$headerText = "Current MMP live file preview";
					}
					
					$editFileLink = SHIKSHA_HOME .'/customizedmmp/mmp/listMMPFiles/'. $currentPageId ."/". $file_type. "/";
				?>
				<div style="color:#666;width:90%;text-align:left;margin:10px 0px 10px 0px"><a href="<?php echo $mmpPreviewLink;?>"><?php echo $headerText;?></a></div>
				<div style="color:#666;width:90%;text-align:left;margin:10px 0px 10px 0px"><a href="<?php echo $editFileLink;?>">Edit other files</a></div>
				
				<div style="color:#666;width:90%;text-align:left;font-style:bold;">
					<h2>Edit <?php echo $file_name; ?></h2>
					<div class="mmp_field_help_note" style="width:100%;text-align:left;margin-left:0px;margin-bottom:3px;color:#C00;">The changes that you make here will start reflecting once you press the 'done editing' button, The older file will save as archive and this new file will replace the older one.</div>
					<div style="width:100%;height:400px;text-align:left;">
						<textarea style="width:100%;height:100%;" rows="25" name="file_content" id="file_content"><?php echo $file_content;?></textarea>
					</div>
				</div>
				<div class="mmp_field_help_note" id="file_content_error_div" style="width:90%;text-align:left;margin-top:10px;margin-left:0px;color:#C00;"></div>
				<div class="mmp_form_buttons" style="margin-left:0px;width:90%;text-align:center;">
					<input type="submit" name="submit" value="Done editing"/>
					<input type="button" name="cancel" value="No" onclick="window.location='/customizedmmp/mmp/showCustomizedMMP/<?php echo $currentPageId;?>'"/>
					<input type="hidden" name="page_id" value="<?php echo $currentPageId; ?>"/>
					<input type="hidden" name="file_type" value="<?php echo $file_type; ?>"/>
					<input type="hidden" name="file_name" value="<?php echo $file_name; ?>"/>
					<input type="hidden" name="dir_name" value="<?php echo $dir_name; ?>"/>
				</div>
			</div>
		</form>
	</fieldset>
</div>
<?php $this->load->view('common/footer');?>