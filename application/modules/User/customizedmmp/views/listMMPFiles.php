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
</script>

<div class="mmp_main_container">
	<div style="margin-bottom: 10px; font-size: 13px; width: 100%;" class="orangeColor fontSize_14p bld"><b>Files list(<?php echo $file_type;?>) for MMP ID = <?php echo $currentPageId;?></b>
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
	$fileEditLink = SHIKSHA_HOME .'/customizedmmp/mmp/editFile/';
	?>
	<fieldset class="mmp_main_fieldset" id="mmp_add_new_page_container">
		<legend class="mmp_main_fieldsetlegend">Edit files</legend>
		<div class="mmp_add_new_mmp_form_container">
			<div style="color:#666;width:90%;text-align:left;font-style:bold;">
				<?php
				if(!empty($files_list)){
					foreach($files_list as $key=>$value){
						if(is_array($value)){
						?>
							<p><em style="color:#698B22;"><?php echo $key;?></em></p>
							<ul>
							<?php
							foreach($value as $k=>$v){
								?>
								<li><?php echo $k?> <a href="<?php echo $fileEditLink.$currentPageId."/".$file_type."/".$key."/".$k; ?>">[edit]</a></li>
								<?php
							}
							?>
							</ul>
						<?php	
						}
					}
					?>
					<p><em style="color:#698B22;">html</em></p>
					<ul>
					<?php
					foreach($files_list as $key=>$value){
						?>
						<?php
						if($key == "css" || $key == "js" || $key=="images"){
							continue;
						} else {
							?>
							<li><?php echo $key;?> <a href="<?php echo $fileEditLink.$currentPageId."/".$file_type."/index/".$key; ?>">[edit]</a></li>
							<?php
						}
					}
					?>
					</ul>
					<?php
				} else {
					?>
					<p><em style="color:#698B22;">No files</em></p>
					<?php
				}
				?>
			</div>
			<div class="mmp_field_help_note" id="file_content_error_div" style="width:90%;text-align:left;margin-top:10px;margin-left:0px;color:#C00;"></div>
			
		</div>
	</fieldset>
</div>
<?php $this->load->view('common/footer');?>