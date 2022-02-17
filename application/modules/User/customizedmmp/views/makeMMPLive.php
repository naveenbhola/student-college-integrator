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
	function submitMakeMMPLiveForm(){
		var x = window.confirm("Are you sure you want to make this version live?");
		if(x){
			return true;
		} else {
			return false;
		}
	}
</script>

<div class="mmp_main_container">
	<div style="margin-bottom: 10px; font-size: 13px; width: 100%;" class="orangeColor fontSize_14p bld"><b>Move MMP for page ID = <?php echo $currentPageId;?> from sandbox to live directory</b>
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
		<form id="make_mmp_live" name="make_mmp_live" method="post" action="/customizedmmp/mmp/moveMMPFromSandboxToLive/" onSubmit="return submitMakeMMPLiveForm();">
			<legend class="mmp_main_fieldsetlegend">Make MMP Live</legend>
			<div class="mmp_add_new_mmp_form_container">
				<?php
					$mmpSandboxLink = $sandbox_preview_link;
				?>
				<div style="color:#666;width:90%;text-align:left;margin:10px 0px 10px 0px"><a href="<?php echo $mmpSandboxLink;?>">Current MMP sandbox preview</a></div>
				<div style="color:#666;width:90%;text-align:center;font-style:bold;"><h2>Are you sure you want to make the sandbox version of this MMP as Live version</h2></div>
				<div class="mmp_field_help_note" style="width:90%;text-align:center;margin:0px;color:#C00;">Once you click "make it live" button, the zip file of current live version will be stored in live back up directory and the version of mmp that is running in sandbox folder will replace the live mmp version.</div>
				<div class="mmp_form_buttons" style="margin-left:0px;width:90%;text-align:center;">
					<input type="submit" name="submit" value="Yes, make it live"/>
					<input type="button" name="cancel" value="No" onclick="window.location='/customizedmmp/mmp/showCustomizedMMP/<?php echo $currentPageId;?>'"/>
					<input type="hidden" name="page_id" value="<?php echo $currentPageId; ?>"/>
				</div>
			</div>
		</form>
	</fieldset>
</div>
<?php $this->load->view('common/footer');?>