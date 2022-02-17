<?php
	if (!empty($executiveHierarchy)) {
?>
	<ul class="smart-form">
		<li>
			<label>Select Executive:</label>
			<div class="form-filed">
				<select id="<?php echo $widgetType; ?>_executive_dropdown" class="dropdown">
					<option>Choose a Executive</option>
				</select>
				<div style="position: relative; display: none;" id="<?php echo $widgetType; ?>_executive_selection" class="dropdown_selection">
					<div class="multiple-select-wrap" style="display: block; position: absolute; top: -12px; z-index:100;">
						<div id="<?php echo $widgetType; ?>_execCheckBoxHolder" class="multiple-select">
						<?php echo $hierarchyHtml; ?>
						</div>
					</div>
				</div>
			</div>
		</li>
	</ul>
<?php
	} elseif (!empty($salesUser)) {
?>
	<div id="<?php echo $widgetType; ?>_execCheckBoxHolder">
		<input type="hidden" name="executive_id[]" value="<?php echo $userId; ?>" />
	</div>
<?php
	}
?>
<?php
	if (!empty($executiveClientMapping)) {
?>
	<ul class="smart-form">
		<li>
			<label>Select Client:</label>
			<div class="form-filed">
				<select id="<?php echo $widgetType; ?>_client_dropdown" class="dropdown">
					<option>Select an Executive</option>
				</select>
				<div style="position: relative; display: none;" id="<?php echo $widgetType; ?>_client_selection" class="dropdown_selection">
					<div class="multiple-select-wrap" style="display: block; position: absolute; top: -12px; z-index:100;">
						<div id="<?php echo $widgetType; ?>_clientcheckboxholder" class="multiple-select">Choose a Executive</div>
					</div>
				</div>
			</div>
		</li>
	</ul>
<?php
	} elseif (!empty($salesUser)) {
?>
	<ul class="smart-form">
		<li>
			<label>Select Client:</label>
			<div class="form-filed">
				<select id="<?php echo $widgetType; ?>_client_dropdown" class="dropdown">
					<option>No Clients</option>
				</select>
				<div style="position: relative; display: none;" id="<?php echo $widgetType; ?>_client_selection" class="dropdown_selection">
					<div class="multiple-select-wrap" style="display: block; position: absolute; top: -12px; z-index:100;">
						<div id="<?php echo $widgetType; ?>_clientcheckboxholder" class="multiple-select">No Clients</div>
					</div>
				</div>
			</div>
		</li>
	</ul>
<?php
	} else {
?>
	<div id="<?php echo $widgetType; ?>_clientcheckboxholder">
		<input type="hidden" name="client_id[]" value="<?php echo $userId; ?>" />
	</div>
<?php
	}
?>
<ul class="smart-form">
	<li id="<?php echo $widgetType; ?>_institutebox">
		<label>Select <?php echo $isSmartAbroad ? "University" : "Institute"; ?>:</label>
		<div class="form-filed">
			<select id="<?php echo $widgetType; ?>_institute_dropdown" class="dropdown">
				<option>Select a Client</option>
			</select>
			<div style="position:relative; display:none;" id="<?php echo $widgetType; ?>_institute_selection" class="dropdown_selection">
				<div class="multiple-select-wrap" style="display:block; position:absolute; top:-12px; z-index:100;">
					<div id="<?php echo $widgetType; ?>_institutecheckboxholder" class="multiple-select">Choose a Client</div>
				</div>
			</div>
		</div>
	</li>
	<li id="<?php echo $widgetType; ?>_leadgeniebox" style="display:none;">
		<div class="form-filed">
			<div class="multiple-select-wrap">
				<p>Select Lead Genie:</p>
				<div id="<?php echo $widgetType; ?>_leadgeniecheckboxholder" class="multiple-select" style="width: 200px">Choose a Client</div>
			</div>
	
			<div class="multiple-select-wrap">
				<p>Selected Lead Genie:</p>
				<div class="multiple-select" style="width: 200px">
					<ol class="slected-option" id="<?php echo $widgetType; ?>_leadgenieSelect"></ol>
				</div>
			</div>
		</div>
	</li>
	<li id="<?php echo $widgetType; ?>_leadportingsbox" style="display:none;">
		<div class="form-filed">
			<div class="multiple-select-wrap">
				<p>Select Lead Portings:</p>
				<div id="<?php echo $widgetType; ?>_leadportingcheckboxholder" class="multiple-select" style="width: 200px">Choose a Client</div>
			</div>
	
			<div class="multiple-select-wrap">
				<p>Selected Lead Portings:</p>
				<div class="multiple-select" style="width: 200px">
					<ol class="slected-option" id="<?php echo $widgetType; ?>_leadportingSelect"></ol>
				</div>
			</div>
		</div>
	</li>
	<li id="<?php echo $widgetType; ?>_responseportingsbox" style="display:none;">
		<div class="form-filed">
			<div class="multiple-select-wrap">
				<p>Select Response Portings:</p>
				<div id="<?php echo $widgetType; ?>_responseportingcheckboxholder" class="multiple-select" style="width: 200px">Choose a Client</div>
			</div>
	
			<div class="multiple-select-wrap">
				<p>Selected Response Portings:</p>
				<div class="multiple-select" style="width: 200px">
					<ol class="slected-option" id="<?php echo $widgetType; ?>_responseportingSelect"></ol>
				</div>
			</div>
		</div>
	</li>
</ul>
<div class="populatedropdowns" onclick="populateDropDowns('<?php echo $widgetType; ?>');"></div>
