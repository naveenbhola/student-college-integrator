<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}
?>
<?php
	echo includeJSFiles('jQuery-v-1.7');
?>
<script>$j = $.noConflict();</script>
<!--Code Starts form here-->
<script src="/public/js/header.js" language="javascript"></script>
<script type="text/javascript">
	
var subscriptions = <?php echo (empty($subscriptions) ? '{}' : json_encode($subscriptions)); ?>;
	
</script>

<?php
        $this->load->view('listing/national/widgets/listingsOverlay');
?>



<div id="lms-port-wrapper">
	<div class="page-title">
		<h2>OAF Porting Set Up</h2>
	</div>
	<div id="lms-port-content">
	<form id="newPortingForm" autocomplete="off" onsubmit="return false;" >
		<div class="lms-section">
			<div class="form-section">
				<ul>
					<li>
						<label>OAF Porting Name:</label>
						<div class="form-fields">
							<input type ="hidden" name ="customizedCount" id = "customizedCount" value = "<?php echo $preEditedCount+1; ?>">
							<input type="hidden" name="preEditedCount" id = "preEditedCount" value="<?php echo $preEditedCount; ?>" />
							<input type="hidden" name="porting_id" id = porting_id" value="<?php echo $portingId; ?>" />
							<input type="text" name="porting_name" id="porting_name" value="<?php echo $portingData['name'];?>" style="width:200px" maxlength="50" />
							<input type="hidden" name="userid" id="userid" value="<?php echo $portingData['client_id'];?>" />
						</div>
					</li>
					<?php if(empty($portingData['client_id'])) { ?>
					<li>
						<label>Client ID:</label>
						<div class="form-fields">
							<input type="text" id="client_id" style="width:200px" /> &nbsp;
							<input type="button" value="Go" class="gray-button" onclick="getClientFormData();" />
						</div>
					</li>

				<?php } else {?>
					
							<input type="hidden" id="client_id" value = "<?php echo $portingData['client_id'];?> " /> 
				
				<?php }?>

				</ul>
			</div>
		</div>
		<?php if (empty($formListView)){  ?>
		<div class="lms-section" id="porting-data-div" style="display: none">
			
			<div class="lms-section last-section" id="formList-div" style="display:none;"></div>
		</div>	
		<?php }else {?>
		<div class="lms-section" id="porting-data-div" style="display: inline">
			<div class="lms-section last-section" id="formList-div" style="display:inline;">
					<?php  echo ($formListView) ?>
			</div>
		</div>	
			<?php } ?>
		
		<div class="lms-section last-section">
			<div class="form-section">
				<ul>
					<li>
						<label style="font-size:14px">Porting Method:</label>
						<div class="form-fields">
							<select name="porting_method" id="porting_method" style="width:150px" onchange="changePortingMethod();">
								<option value="GET" <?php if ($portingData['request_type'] == "GET") { echo "selected";}?> >Get</option>
								<option value="POST" <?php if ($portingData['request_type'] == "POST") { echo "selected";}?> >Post</option>
							</select>
						</div>
					</li>
					
					<div id ="porting_get_post" <?php if ($portingData['request_type'] == "EMAIL"):?> style="display: none;" <?php else:?> style="display:block;" <?php endif;?> > 
					
					<li>
						<label>Data Format:</label>
						<div class="form-fields">
							<input type='radio' name='dataFormatType' id= "regularDataFormat" value='regular' onclick="displayKeyDiv(this);" checked='<?php if ($portingData['data_format'] == "regular") { echo "unchecked";} else { echo "checked"; } ?>' >Regular</input>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type='radio' name='dataFormatType' id="jsonDataFormat" value='json' onclick="displayKeyDiv(this);" <?php if ($portingData['data_format'] == "json") { echo "checked";} ?> >JSON</input>
							<input type='radio' name='dataFormatType' value='xml' style= 'margin-left:20px' onclick="displayKeyDiv(this);" <?php if ($portingData['data_format'] == "XML") { echo "checked";} ?> >XML</input>
							<div id = "SOAPDataFormat" style="display :<?php echo (($portingData['data_format'] == 'SOAP' || $portingData['request_type'] == "POST")?'inline':'none');?>">
							<input type='radio' name='dataFormatType'  value='soap' style= 'margin-left:20px;' onclick="displayKeyDiv(this);" <?php if ($portingData['data_format'] == "SOAP") { echo "checked";} ?> >SOAP</input>
							</div>
						</div>
					</li>
					<li>
						<label>Data Encoding:</label>
						<div class="form-fields">
							<input type="checkBox" name="dataEncode" id="dataEncode" <?php if($portingData['dataEncode']=='yes'){echo "checked";}if(empty($portingData)){echo "checked";}?> >Yes</input>
						</div>
					</li>
					
					<li id="JSONKeyDiv" style="<?php if ($portingData['data_format'] == 'json') { echo 'display: block;'; } else { echo 'display: none;'; } ?>" >
						<label>Key Name:</label>
						<div class="form-fields">
							<input type="text" id="jsonDataKey" name="jsonDataKey" value="<?php if ($portingData['data_format'] == 'json') { echo $portingData['data_key']; } ?>" style="width:290px" />
						</div>
					</li>
					
					<li id="jsonFormatDiv" style="<?php if ($portingData['data_format'] == 'json') { echo 'display: block;'; } else { echo 'display: none;'; }  ?>" >
						<label>JSON-Regular Expression:</label>
						<div class="form-fields">
							<textarea id="jsonFormat" name="jsonFormat" value="<?php if ($portingData['data_format'] == 'json' ) { echo htmlentities($portingData['xml_format']); } ?>" style="width:290px" /><?php if ($portingData['data_format'] == 'json') { echo htmlentities($portingData['xml_format']); } ?></textarea>
						</div>
					</li>


					<li id="xmlKeyDiv" style="<?php if ($portingData['data_format'] == 'XML') { echo 'display: block;'; } else { echo 'display: none;'; } ?>" >
						<label>Key Name:</label>
						<div class="form-fields">
							<input type="text" id="xmlDataKey" name="xmlDataKey" value="<?php if ($portingData['data_format'] == 'XML') { echo $portingData['data_key']; } ?>" style="width:290px" />
						</div>
					</li>
					
					<li id="xmlFormatDiv" style="<?php if ($portingData['data_format'] == 'XML' || $portingData['data_format'] == 'SOAP') { echo 'display: block;'; } else { echo 'display: none;'; }  ?>" >
						<label>XML-Format:</label>
						<div class="form-fields">
							<textarea id="xmlFormat" name="xmlFormat" value="<?php if ($portingData['data_format'] == 'XML' || $portingData['data_format'] == 'SOAP') { echo htmlentities($portingData['xml_format']); } ?>" style="width:290px" /><?php if ($portingData['data_format'] == 'XML' || $portingData['data_format'] == 'SOAP') { echo htmlentities($portingData['xml_format']); } ?></textarea>
						</div>
					</li>
					
					<li>
						<label>Porting URL:</label>
						<div class="form-fields">
							<input type="text" name="porting_url" id="porting_url" value="<?php echo $portingData['api'];?>" style="width:290px" />
						</div>
					</li>
					
					<li>
						<label>Field Mapping:</label>
						<div class = "form-fields" id = "edited-field-mapping">
							<?php if(!empty($shikshaFields)) { 
									echo ($fieldMappingView);
							 }?>
						</div>
						<div class="form-fields" id ="fields-mapping">
							
						</div>
					</li>
					</div>
					
					<li>
						<div class="porting-duration">
							<div class="form-fields">
								<?php  if ($editPortedValues==1){?>
								<input type="button" class="orange-button" value="Update" onClick="doSubmitPortingForm(1);" />
								<?php } else {?>
								<input type="button" class="orange-button" value="Save" onClick="doSubmitPortingForm(0);" />
								<?php }?>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="clearFix"></div>
	</form>
	</div>
</div>
<div id="responseFormNew" style="display:none"></div>


<!--Code Ends here-->
<?php $this->load->view('common/footer');?>
<script>
	if(typeof(COOKIEDOMAIN) == 'undefined'){
		var COOKIEDOMAIN = cookieDomain;	
	}
</script>
<style>
.cross-icon{background:url(/public/images/management-sprite.png) no-repeat;background-position:-323px 0; width:19px; height:16px; top:5px; right:-4px};
</style>
