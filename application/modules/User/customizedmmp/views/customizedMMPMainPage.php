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
	
	$mmp_name_help_note = "Please enter the mmp id which needs to be customized";
	$mmp_destination_url = "Where the user should land after successful registration through MMP.";
?>
<script>
	var mmp_name_help_note = "Please enter the mmp id which needs to be customized";
	var mmp_destination_url_help_note = "Where the user should land after successful registration through MMP.";
	
	function toggleMMPDiv(id, displayStyle) {
		if(document.getElementById(id) != undefined){
			var ele = document.getElementById(id);
			if(displayStyle != undefined){
				ele.style.display = displayStyle;
			} else {
				if(ele.style.display == "none"){
					ele.style.display = "block";
				} else {
					ele.style.display = "none";
				}
			}
		}
	}
	
	function showAddNewMMPForm(){
		toggleMMPDiv("global_success_message_container", "none");
		toggleMMPDiv("global_error_message_container", "none");
		toggleMMPDiv("mmp_add_new_page_container");
	}
	
	function is_valid_url(url) {
		return url.match(/^(ht|f)tps?:\/\/[a-z0-9]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
	}

	function submitAddNewMMPForm() {
		var form_ele = document.forms["add_new_mmp_page_form"];
		var mmp_name_value = trim(form_ele.elements["mmp_name"].value);
		var mmp_destination_url_value = trim(form_ele.elements["mmp_destination_url"].value);
		
		var mmp_name_help_ele = document.getElementById("mmp_name_help_div");
		var mmp_destination_url_help_ele = document.getElementById("mmp_destination_url_help_div");
		
		mmp_name_help_ele.innerHTML = mmp_name_help_note;
		mmp_destination_url_help_ele.innerHTML = mmp_destination_url_help_note;
		
		mmp_name_help_ele.className = "mmp_field_help_note";
		mmp_destination_url_help_ele.className = "mmp_field_help_note";
		
		var validURLFlag = true;
		if(!is_valid_url(mmp_destination_url_value) && mmp_destination_url_value.length > 0) {
			mmp_destination_url_help_ele.innerHTML = "Not a valid URL. Either leave empty or fill it with a valid URL.";
			mmp_destination_url_help_ele.className =  "mmp_field_error_note";
			validURLFlag = false;
		}
		
		if(mmp_name_value.length <= 0){
			mmp_name_help_ele.innerHTML = "MMP name can't be blank";
			mmp_name_help_ele.className =  "mmp_field_error_note";
		}
		
		if(mmp_name_value.length > 0 && validURLFlag){
			return true;
		} else {
			return false;
		}
	}
</script>

<div class="mmp_main_container">
	
	<div class="new_mmp_button_container" id="new_mmp_button_container">
		<button class="btn-submit5 " onclick="javascript:showAddNewMMPForm();">
			<div class="btn-submit5">
				<p class="btn-submit6">Add New Template</p>
			</div>
		</button>
	</div>
	
	<?php
	// Backend side form validation failed or success message
	if(is_array($mmp_custom_params) && !empty($mmp_custom_params)) {
		$errorFlag = $mmp_custom_params['error'];
		$successFlag = $mmp_custom_params['success'];
		if($errorFlag){
			?>
			<table style="width:100%;">
				<tr>
					<td>
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
					</td>
				</tr>
			</table>
			<?php
		} else if($successFlag){
			?>
			<table style="width:100%;">
				<tr>
					<td>
						<div class="mmp_success_message_container" id="global_success_message_container">
							<?php
							if(isset($mmp_custom_params['success_header'])){
							?>
								<p><em><?php echo $mmp_custom_params['success_header']; ?></em></p>
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
					</td>
				</tr>
			</table>						
			<?php
		}
	} else {
		$mmp_custom_params = array();
	}
	?>
	<fieldset class="mmp_main_fieldset" id="mmp_add_new_page_container" style="display:none;">
		<form id="add_new_mmp_page_form" name="add_new_mmp_page_form" method="post" action="/customizedmmp/mmp/createMMPPage/" onSubmit="return submitAddNewMMPForm();">
			<legend class="mmp_main_fieldsetlegend">Add New Template</legend>
			<div class="mmp_add_new_mmp_form_container">
				<div class="mmp_field_container">
					<label class="mmp_field_label" for="mmp_name">MMP Name <em>*</em></label>
                    <select name="mmp_name">
                      <option value="0">Select</option>
                    <?php foreach($normalForms as $form) { 
                        echo '<option value="'.$form['id'].'">'.$form['name'].'</option>';
                    } ?>
                    </select> 
					<div id="mmp_name_help_div" class="mmp_field_help_note">Please enter the mmp name which needs to be customized</div>
				</div>
				<div class="mmp_form_buttons">
					<input type="submit" name="submit" value="submit"/>
					<input type="hidden" name="action_type" value="insert"/>
				</div>
			</div>
		</form>
	</fieldset>
	
	<div style="width:100%;margin-top:10px;float:left;margin-bottom:10px;">
		<div style="width:100%;float:left;">
			<div style="width:2%;float:left;line-height:15px;border:1px dotted #CCC;background-color:#ffffcc;">&nbsp;</div>
			<div style="width:3%;float:left;margin-left:5px;line-height:15px;color:#000;">Live</div>
			<div style="width:2%;float:left;line-height:15px;border:1px dotted #CCC;background-color:#F5F5F5;">&nbsp;</div>
			<div style="width:5%;float:left;margin-left:5px;line-height:15px;color:#000;">Disable</div>
			<div style="width:2%;float:left;line-height:15px;border:1px dotted #CCC;background-color:#fff;">&nbsp;</div>
			<div style="width:3%;float:left;margin-left:5px;line-height:15px;color:#000;">Dev</div>
			<div style="width:2%;float:left;line-height:15px;border:1px dotted #CCC;background-color:#E5EECC;">&nbsp;</div>
			<div style="width:20%;float:left;margin-left:5px;line-height:15px;color:#000;">Recently Edited/Active</div>
		</div>
	</div>
	
	<div style="clear:both;"></div>
	<div class="mmp_note_text_container" id="mmp_note_text_container" style="float:left;width:97%;">
		<div style="width:4%;float:left;">
			<span style="font-weight:bold;">Note</span>:
		</div>
		<div style="width:96%;float:left;color:#666;">
			1. All the changes like add/edit courses or edit details changes will not reflect until you upload a new mmp template.<br/>	
			2. MMP URL will be in active state only after making mmp live.
		</div>
	</div>
	<div style="clear:both;"></div>
	<fieldset class="mmp_main_fieldset" id="mmp_listing_container">
		<legend class="mmp_main_fieldsetlegend">Customized MMP Listing</legend>
		<?php
		if(!empty($marketing_details['mmp_details'])) {
		?>
		<div class="mmp_listing">
			<table class="mmp_listing_table">
				<tr>
					<th class="mmp_listing_col_heading" align="left">MMP ID</th>
					<th class="mmp_listing_col_heading" align="left">Name</th>
					<th class="mmp_listing_col_heading" align="left">Courses</th>
					<th class="mmp_listing_col_heading" align="left">Form type</th>
					<th class="mmp_listing_col_heading" align="left">MMP URL<br/><span style='font-size:10px;color:#B1B1B1;font-weight:normal;'><?php echo wordwrap('(Once the MMP in live status, this will be the final MMP URL)', 30, "<br/>", true); ?></span></th>
					<th class="mmp_listing_col_heading" align="left">Destination URL</th>
					<th class="mmp_listing_col_heading" align="left">Status</th>
					<th class="mmp_listing_col_heading" align="left">Edit</th>
				</tr>
				<?php
				foreach($marketing_details['mmp_details'] as $key=>$value) {
					$style = "";
					if($currentPageId == $value->page_id){
						$style = "background-color:#E5EECC";	
					} else if($value->status == "live"){
						$style = "background-color:#ffffcc";
					} else if($value->status == "history"){
						$style = "background-color:#f5f5f5";
					}
					
				?>
					<tr style='<?php echo $style;?>'>
						<td class="mmp_listing_col_style">
							<?php
								$editPageDetails = SHIKSHA_HOME .'/customizedmmp/mmp/editMMPPageDetails/'.$value->page_id."/";
							?>
							<?php echo $value->page_id;?>
						</td>
						<td class="mmp_listing_col_style">
							<?php echo wordwrap($value->page_name, 30, '<br/>', true);?>
						</td>
						<td class="mmp_listing_col_style">
							<b><?php
								$courseCount = 0;
								if(!empty($marketing_details['mmp_course_count'][$value->page_id])) {
									$courseCount = $marketing_details['mmp_course_count'][$value->page_id];
								}
								$coursesString = "courses";
								if($courseCount == 1){
									$coursesString = "course";
								}
								echo $courseCount . " ".$coursesString." <br/>";
								echo "<span style='font-size:10px;color:#B1B1B1;'>(". $value->page_type .")</span>";
								?>
							</b>
						</td>
						<td class="mmp_listing_col_style">
							<?php
								if(!empty($marketing_details['mmp_forms_list'][$value->page_id])){
									$form_id = $marketing_details['mmp_forms_list'][$value->page_id];
									echo  wordwrap($form_types[$form_id]['form_name'], 35, "<br/>") . "<br/>";
									echo "<span style='font-size:10px;color:#B1B1B1;'>". wordwrap($form_types[$form_id]['form_description'], 30, "<br/>", true) ."</span>";
									
								}
							?>
						</td>
						
						<?php
						$liveMMPURL = $mmp_urls[$value->page_id]['live_url'];
						$sandboxMMPURL = $mmp_urls[$value->page_id]['sandbox_url'];
						?>
						<td class="mmp_listing_col_style">
							<a href="<?php echo $liveMMPURL;?>"><?php echo wordwrap($liveMMPURL, 30, '<br/>', true);?></a>
						</td>

						<td class="mmp_listing_col_style">
							<a href="<?php echo $value->destination_url;?>"><?php echo wordwrap($value->destination_url, 30, '<br/>', true);?></a>
						</td>
						<td class="mmp_listing_col_style">
							<?php echo $value->status;?>
							<?php
							if($mmp_urls[$value->page_id]['live_mmp_exist'] && $value->status == "development"){
								echo "<br/><br/><span style='color:#C00;font-size:11px;'><sup>*</sup>".wordwrap("You have made some changes to this MMP after making it live, so keep in mind that some version of this mmp is still live.", 30, "<br/>", true)."</span>";
							}
							?>
						</td>
						<td class="mmp_listing_col_style">
							<?php
								$editCourseLink = SHIKSHA_HOME .'/customizedmmp/mmp/addRemoveMMPageCourses/'.$value->page_id."/";
								if($courseCount > 0){
									$uploadZipLink = SHIKSHA_HOME .'/customizedmmp/mmp/showUploadTemplateForm/'.$value->page_id."/";
								}
								$editPageDetails = SHIKSHA_HOME .'/customizedmmp/mmp/editMMPPageDetails/'.$value->page_id."/";
								$makeSandboxVersionLive = SHIKSHA_HOME .'/customizedmmp/mmp/makeMMPLive/'.$value->page_id."/";
							?>
								<?php
									$status = $value->status;
									$liveMMPURL = $mmp_urls[$value->page_id]['live_url'];
									$sandboxMMPURL = $mmp_urls[$value->page_id]['sandbox_url'];
									/*
									if($status == "live") {
										?>
										<a href="<?php echo $sandboxMMPURL; ?>">Sandbox mmp URL</a><br/>
										<a href="<?php echo $liveMMPURL; ?>">Live mmp URL</a>
                                        <div style="border-bottom:1px dotted #CCC;width:80%;margin: 2px 0px 5px 0px; line-height:5px;">&nbsp;</div>
										<?php
									} else if($status == "development"){
										?>
										<a href="<?php echo $sandboxMMPURL; ?>">Sandbox mmp URL</a>
										<?php
											if($mmp_urls[$value->page_id]['live_mmp_exist']){
											?>
											<br/><a href="<?php echo $liveMMPURL; ?>">Live mmp URL</a>
											<?php
											}
										?>
                                        <div style="border-bottom:1px dotted #CCC;width:80%;margin: 2px 0px 5px 0px; line-height:5px;">&nbsp;</div>
										<?php
									}
									*/
								?>
							<?php
							if($courseCount > 0){
							?>
								<a href="<?php echo $uploadZipLink;?>">Upload mmp template</a><br/>
								<?php
								if($status == "development" || $status == "live"){
									?>
									<div style="border-bottom:1px dotted #CCC;width:80%;margin: 2px 0px 5px 0px; line-height:5px;">&nbsp;</div>
									<a href="<?php echo $makeSandboxVersionLive;?>">Make sandbox version live</a>
									<?php	
								}
								?>
                                <div style="border-bottom:1px dotted #CCC;width:80%;margin: 2px 0px 5px 0px; line-height:5px;">&nbsp;</div>
							<?php
							}
							$editSandboxIndexFile = SHIKSHA_HOME .'/customizedmmp/mmp/listMMPFiles/'.$value->page_id."/sandbox/";
							$editLiveIndexFile = SHIKSHA_HOME .'/customizedmmp/mmp/listMMPFiles/'.$value->page_id."/live/";
							
							/*
							if($status == "development") {
							?>
								<a style="color:#C00;" href="<?php echo $editSandboxIndexFile;?>">Edit sandbox files</a>
                                <div style="border-bottom:1px dotted #CCC;width:80%;margin: 2px 0px 5px 0px; line-height:5px;">&nbsp;</div>
							<?php
							} else if($status == "live") {
							?>
								<a style="color:#C00;" href="<?php echo $editSandboxIndexFile;?>">Edit sandbox files</a><br/>
                                <div style="border-bottom:1px dotted #CCC;width:80%;margin: 2px 0px 5px 0px; line-height:5px;">&nbsp;</div>
							<?php
							}
							*/
							
							if($status == "live") {
								$disableMMP =  SHIKSHA_HOME .'/customizedmmp/mmp/makeMMPDisable/'.$value->page_id."/";
							?>
								<a style="color:#C00;" href="<?php echo $disableMMP;?>">Disable MMP</a>
                                <div style="border-bottom:1px dotted #CCC;width:80%;margin: 2px 0px 5px 0px; line-height:5px;">&nbsp;</div>
							<?php	
							}
							?>
						</td>
					</tr>
				<?php
				}
				?>
			</table>
		</div>
		<?php
		} else {
			?>
			<div class="mmp_message_container">
				<p><em>No MMP created yet.</em></p>
			</div>
			<?php
		}
		?>
	</fieldset>
</div>
<?php $this->load->view('common/footer');?>
