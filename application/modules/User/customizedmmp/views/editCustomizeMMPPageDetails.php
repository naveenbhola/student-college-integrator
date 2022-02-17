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
	
	$mmp_name_help_note = "Name of mmp, it will only be used for remembering particular page.";
	$mmp_destination_url = "Where the user should land after successful registration through MMP.";
?>
<script>
	var mmp_name_help_note = "Name of mmp, it will only be used for remembering particular page.";
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

	function submitEditNewMMPForm() {
              	var form_ele = document.forms["edit_mmp_page_form"];
		var mmp_name_value = form_ele.elements["mmp_name"].value;
		var mmp_destination_url_value = form_ele.elements["mmp_destination_url"].value;
		
		var mmp_name_help_ele = document.getElementById("mmp_name_help_div");
		var mmp_destination_url_help_ele = document.getElementById("mmp_destination_url_help_div");
		
		mmp_name_help_ele.innerHTML = mmp_name_help_note;
		mmp_destination_url_help_ele.innerHTML = mmp_destination_url_help_note;
		
                mmp_name_help_ele.className = "mmp_field_help_note";
                mmp_destination_url_help_ele.className = "mmp_field_help_note";
		
		var validURLFlag = true;
		if(mmp_destination_url_value.length > 0){
			if(!is_valid_url(mmp_destination_url_value)) {
				mmp_destination_url_help_ele.innerHTML = "Not a valid URL. Either leave empty or fill it with a valid URL.";
                                mmp_destination_url_help_ele.className = "mmp_field_error_note";
				validURLFlag = false;
			}	
		}
		
		if(mmp_name_value.length <= 0){
			mmp_name_help_ele.innerHTML = "MMP name can't be blank";
                        mmp_name_help_ele.className = "mmp_field_error_note";
		}
		if(mmp_name_value.length > 0 && validURLFlag == true){
			return true;
		} else {
			return false;
		}
      	}
</script>

<div class="mmp_main_container">
	
	<div style="margin-bottom: 10px; font-size: 13px; width: 100%;" class="orangeColor fontSize_14p bld"><b>Edit MMP Details for ID = <?php echo $currentPageId;?></b>
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
	$mmp_name = $page_details['page_name'];
	$mmp_destination_url = $page_details['destination_url'];
	if(!empty($mmp_custom_params['page_name'])){
		$mmp_name = $mmp_custom_params['page_name'];
	}
	if(!empty($mmp_custom_params['destination_url'])){
		$mmp_destination_url = $mmp_custom_params['destination_url'];
	}
	?>
	<fieldset class="mmp_main_fieldset" id="mmp_add_new_page_container">
		<form id="edit_mmp_page_form" name="edit_mmp_page_form" method="post" action="/customizedmmp/mmp/createMMPPage/" onSubmit="return submitEditNewMMPForm();">
			<legend class="mmp_main_fieldsetlegend">Edit MMP</legend>
			<div class="mmp_add_new_mmp_form_container">
				<div class="mmp_field_container">
					<label class="mmp_field_label" for="mmp_name">MMP Name <em>*</em></label>
					<input type="text" size="15" id="mmp_name" name="mmp_name" value="<?php echo $mmp_name;?>"></input>
					<div id="mmp_name_help_div" class="mmp_field_help_note">Name of mmp, it will only be used for remembering particular page.</div>
				</div>
				<div class="mmp_field_container">
					<label class="mmp_field_label" for="mmp_destination_url">Destination URL<em>*</em></label>
					<input type="text" size="50" name="mmp_destination_url" id="mmp_destination_url" value="<?php echo $mmp_destination_url;?>"></input>
					<div id="mmp_destination_url_help_div" class="mmp_field_help_note">Where the user should land after successful registration through MMP.</div>
				</div>
				<div class="mmp_field_container">
					<label for="form_type" class="mmp_field_label">Type <em>*</em></label>
					<select id="form_type" name="form_type">
						<optgroup label="Type of forms">
							<?php
								foreach($form_types as $fkey=>$fval) {
									$selected = "";
									if($page_details['form_id'] == $fval['form_id']){
										$selected = "selected = 'yes'";
									}
									?>
									<option <?php echo $selected;?> value="<?php echo $fval['form_id']?>"><?php echo $fval['form_name']?></option>
									<?php
								}
							?>
						</optgroup>
					</select>
				</div>
				
				<div class="mmp_form_buttons">
					<input type="submit" name="submit" value="submit"/>
					<input type="button" name="cancel" value="cancel" onclick="window.location='/customizedmmp/mmp/showCustomizedMMP/<?php echo $currentPageId;?>'"/>
					<input type="hidden" name="page_id" value="<?php echo $currentPageId; ?>"/>
					<input type="hidden" name="action_type" value="update"/>
				</div>
			</div>
		</form>
	</fieldset>
</div>
<?php $this->load->view('common/footer');?>