<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style'),
	'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','discussion','events','listing','blog'),
	'jsFooter'         => array('scriptaculous','utils'),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=>	'Mass Mailer',
	'taburl' => site_url('mailer/Mailer'),
	'metaKeywords'	=>''
);
    $this->load->view('enterprise/headerCMS', $headerComponents);
    //$this->load->view('enterprise/cmsTabs',$cmsUserInfo);
    $this->load->view('common/calendardiv');
?>
<SCRIPT LANGUAGE="JavaScript">
    var calMain = new CalendarPopup("calendardiv");
</SCRIPT>
</head>
<body>
	<div id="dataLoaderPanel" style="position:absolute;display:none">
		<img src="/public/images/loader.gif"/>
	</div>
	<div class="mar_full_10p" style="margin-top: 15px;">
    		<!--div style="margin-left:1px">
        		<div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Mass Mailer</div>
        		<div class="grayLine"></div>
        		<div class="lineSpace_10">&nbsp;</div>
			</div-->
		<?php
		$this->load->view('mailer/left-panel-top');
		?>
		<form id="formForEdit_Var_Template" action="/mailer/Mailer/setTemplateVariables" method="POST">
		<input type="hidden" name="temp_op_mode" id="temp_op_mode" value="<?php echo $mode; ?>">
		<input type="hidden" name="temp_id" id="temp_id" value="<?php echo $temp_id; ?>">
		<input type="hidden" name="templateType"  value="<?php echo $templateType; ?>">
		
		<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:20px;"><strong>Define Template Variables</strong></div>
		
		<div>
			<div style="width:15px" class="float_L">&nbsp;</div>
				<div style="margin-left:20px">
					<?php
					if (count($result) > 0 ) {

					?>
					<div>
						<span class="OrgangeFont bld">Variable Name</span>
						<span class="OrgangeFont bld" style="padding-left:133px">Variable Value</span>
					</div>
					<?php
					}
					?>
					<div class="lineSpace_10">&nbsp;</div>
					<?php
					if (count($result) > 0 ) {
					for($j=0; $j < count($result);$j++) {
					?>
					<div style="margin-bottom: 5px;">
						<input type="text" name="var_name[]" value="<?php echo $result[$j]['varName']; ?>" id="var_name<?php echo $j; ?>" readonly style="width:200px; border:1px solid #ccc; padding:2px; background:#fafafa" />
						<select style="width:200px; margin-left: 10px; padding:2px;" onchange="return validate_VariablesKey('VariablesKey<?php echo $j; ?>','mailer_temp_var_value_var<?php echo $j; ?>');" name="VariablesKey[]" id="VariablesKey<?php echo $j; ?>" >
							<?php
							for($i=0;$i<count($VariablesKey);$i++) {
							?>
							<option value ="<?php echo $VariablesKey[$i]['varValue'];?>" <?php if ($result[$j]['varValue'] == $VariablesKey[$i]['varValue']) { echo "selected";}?> ><?php echo $VariablesKey[$i]['varValue'];?></option>

							<?php
							}
							?>
							<option value="-1"
							<?php
							if($result[$j]['flagOther'] == 'true') { echo "selected";}
							?> > others </option>
						</select>
						<span id="mailer_temp_var_value_var<?php echo $j;?>">
							<input tip="mailer_temp_var_value" required="true" profanity="true" validate="" type="text" name="temp_name[]" value="<?php if($result[$j]['flagOther'] == 'true') { echo $result[$j]['varValue']; } ?>" id="tep_name<?php echo $j; ?>" size="25" maxlength="2000" minlength="1" caption="Variable's Value" style="width:250px; border:1px solid #ccc; padding:2px; margin-left: 10px;" />
						</span>
						<div class="row errorPlace" style="margin-top:2px;">
						<div class="r1_1">&nbsp;</div>
						<div class="r2_2 errorMsg" id= "tep_name<?php echo $j; ?>_error"></div>
						<div class="clear_L"></div>
						</div>
					</div>
					<script>
					$('mailer_temp_var_value_var<?php echo $j;?>').style.display = 'none';
					<?php
					if($result[$j]['flagOther'] == 'true') {
					?>
					$('mailer_temp_var_value_var<?php echo $j;?>').style.display = '';
					<?php
					}
					?>
					</script>
					<?php
					}
					} else {
					?>
					<div class="r2_2 bld errorMsg" > Variables list is empty in your Template.
					</div>
					<?php
					}
					?>
				</div>
			</div>
			<div class="clear_L"></div>
			
			<div class="clearFix"></div>
			<div style="background:#f0f0f0; text-align: center; padding:5px 0; margin-top:10px;">
				<button class="btn-submit19" type="button" onclick="return validate_variable_tpl();" value="" style="width:100px">
					<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Submit</p></div>
				</button>
			</div>
			
		</div>
		
		
		
		<!--div class="r1_1 bld">&nbsp;</div>
		<div class="r2_2" style="padding:10px 0px 10px 30px;">
			<button class="btn-submit19" type="button" onclick="return validate_variable_tpl();" value="" style="width:100px">
			<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Next</p></div>
			</button>
		</div-->
		<div class="clear_L"></div>
		<div class="lineSpace_10">&nbsp;</div>
		</form>
	<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	</div>
	<script>
   	addOnFocusToopTip(document.getElementById('formForEdit_Var_Template'));
   	addOnBlurValidate(document.getElementById('formForEdit_Var_Template'));
   	</script>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php //$this->load->view('enterprise/footer'); ?>
