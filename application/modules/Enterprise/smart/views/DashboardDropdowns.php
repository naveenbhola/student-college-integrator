<?php
	if($salesUser == 'Admin') {
		$dynamicWidgetTitle = array("responseWidget"=>"Response Viewer","activityWidget"=>"Activity Viewer","creditWidget"=>"Credit Viewer","leadsWidget"=>"Leads Allocation Table");
	}
	elseif($salesUser == 'Manager') {
		$dynamicWidgetTitle = array("responseWidget"=>"My Team's Response Viewer","activityWidget"=>"My Team's Activity Viewer","creditWidget"=>"My Team's Credit Viewer","leadsWidget"=>"My Team's Leads Allocation Table");
	}
	elseif($salesUser == 'Executive') {
		$dynamicWidgetTitle = array("responseWidget"=>"My Client's Response Viewer","activityWidget"=>"My Client's Activity Viewer","creditWidget"=>"My Client's Credit Viewer","leadsWidget"=>"My Client's Leads Allocation Table");
	}
	else {
		$dynamicWidgetTitle = array("responseWidget"=>"My Response Viewer","activityWidget"=>"My Activity Viewer","creditWidget"=>"My Credit Viewer","leadsWidget"=>"My Leads Allocation Table");
	}

	if ($widgetType == 'response') {
?>
<h5><?php echo $dynamicWidgetTitle['responseWidget']; ?>
	<div style="position:relative; display:inline">
		<i id="<?php echo $widgetType; ?>_help_button" widget="<?php echo $widgetType; ?>" class="info-icon" onmouseover="showHelpText(this.getAttribute('widget'));" onmouseout="hideHelpText(this.getAttribute('widget'));"></i>
		<div id="<?php echo $widgetType; ?>_help_text" class="popup-layer2">
			<span class="pointer"></span>
			<div class="layer-content">
				Response Viewer is a graphical representation of responses generated for your institutes in any selected time period.
			</div>
		</div>
	</div>
</h5>
<?php
	}
	else if ($widgetType == 'activity') {
?>
<h5><?php echo $dynamicWidgetTitle['activityWidget']; ?>
	<div style="position:relative; display:inline">
		<i id="<?php echo $widgetType; ?>_help_button" widget="<?php echo $widgetType; ?>" class="info-icon" onmouseover="showHelpText(this.getAttribute('widget'));" onmouseout="hideHelpText(this.getAttribute('widget'));"></i>
		<div id="<?php echo $widgetType; ?>_help_text" class="popup-layer2" style="left:-280px">
			<span class="pointer2"></span>
			<div class="layer-content">
				Activity Viewer is a graphical representation of Questions posted for your institutes, Questions answered by you and Questions answered by site users in any selected time period.
			</div>
		</div>
	</div>
</h5>
<?php
	}
	else if ($widgetType == 'credit') {
?>
<h5><?php echo $dynamicWidgetTitle['creditWidget']; ?>
	<div style="position:relative; display:inline">
		<i id="<?php echo $widgetType; ?>_help_button" widget="<?php echo $widgetType; ?>" class="info-icon" onmouseover="showHelpText(this.getAttribute('widget'));" onmouseout="hideHelpText(this.getAttribute('widget'));"></i>
		<div id="<?php echo $widgetType; ?>_help_text" class="popup-layer2">
			<span class="pointer"></span>
			<div class="layer-content">
				Credit Viewer displays your credit activity on Shiksha.com in any selected time period.
			</div>
		</div>
	</div>
</h5>
<?php
	}
	else if ($widgetType == 'leads') {
?>
<h5><?php echo $dynamicWidgetTitle['leadsWidget']; ?>
	<div style="position:relative; display:inline">
		<i id="<?php echo $widgetType; ?>_help_button" widget="<?php echo $widgetType; ?>" class="info-icon" onmouseover="showHelpText(this.getAttribute('widget'));" onmouseout="hideHelpText(this.getAttribute('widget'));"></i>
		<div id="<?php echo $widgetType; ?>_help_text" class="popup-layer2">
			<span class="pointer"></span>
			<div class="layer-content">
				Lead Allocation Table is a tabular representation of your 'Lead Genie' and 'Student Search' activities done in any selected time period. 
			</div>
		</div>
	</div>
</h5>	
<?php
	}
?>

<div id="<?php echo $widgetType; ?>_header" class="ent-header">
	<form id="<?php echo $widgetType; ?>_Form" autocomplete="off" onsubmit="return false;">
		<input type="hidden" name="report_type" value="<?php echo $widgetType; ?>" />
		<?php
			if (!empty($executiveHierarchy)) {
		?>
			<div style="float: left;">
				<select id="<?php echo $widgetType; ?>_executive_dropdown" class="dropdown">
					<option>Choose an Executive</option>
				</select>
				<div style="position: relative; display: none;" id="<?php echo $widgetType; ?>_executive_selection" class="dropdown_selection">
					<div class="multiple-select-wrap" style="display: block; position: absolute; top: 22px; left: 0px; *top: 12px; z-index:100;">
						<div id="<?php echo $widgetType; ?>_execCheckBoxHolder" class="multiple-select">
						<?php echo $hierarchyHtml; ?>
						</div>
					</div>
				</div>
			</div>
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
			<div style="float: left;">
				<select id="<?php echo $widgetType; ?>_client_dropdown" class="dropdown">
					<option>Choose a Client</option>
				</select>
				<div style="position: relative; display: none;" id="<?php echo $widgetType; ?>_client_selection" class="dropdown_selection">
					<div class="multiple-select-wrap" style="display: block; position: absolute; top: 22px; left: 0px; *top: 12px; z-index:100;">
						<div id="<?php echo $widgetType; ?>_clientcheckboxholder" class="multiple-select">Choose a Client</div>
					</div>
				</div>
			</div>
		<?php
			} elseif (!empty($salesUser)) {
		?>
			<div style="float: left;">
				<select id="<?php echo $widgetType; ?>_client_dropdown" class="dropdown">
					<option>No Clients</option>
				</select>
				<div style="position: relative; display: none;" id="<?php echo $widgetType; ?>_client_selection" class="dropdown_selection">
					<div class="multiple-select-wrap" style="display: block; position: absolute; top: 22px; left: 0px; *top: 12px; z-index:100;">
						<div id="<?php echo $widgetType; ?>_clientcheckboxholder" class="multiple-select">No Clients</div>
					</div>
				</div>
			</div>
		<?php
			} else {
		?>
			<div id="<?php echo $widgetType; ?>_clientcheckboxholder">
				<input type="hidden" name="client_id[]" value="<?php echo $userId; ?>" />
			</div>
		<?php
			}
		?>
		<?php
			if ($widgetType == 'response' || $widgetType == 'activity') {
		?>
		<?php
			if (!empty($executiveHierarchy)) {
		?>
			<div class="clearFix"></div>
		<?php
			}
		?>
			<div style="float: left;">
				<select id="<?php echo $widgetType; ?>_institute_dropdown" class="dropdown">
					<option>Choose an Institute</option>
				</select>
				<div style="position:relative; display:none;" id="<?php echo $widgetType; ?>_institute_selection" class="dropdown_selection">
					<div class="multiple-select-wrap" style="display:block; position:absolute; top: 22px; left: 0px; *top: 12px; z-index:100;">
						<div id="<?php echo $widgetType; ?>_institutecheckboxholder" class="multiple-select">Choose an Institute</div>
					</div>
				</div>
			</div>
		<?php
			}
		?>
		<?php
			if ($widgetType == 'leads') {
		?>
			<div style="float: left;">
				<select id="<?php echo $widgetType; ?>_status_dropdown" class="dropdown">
					<option>Active</option>
				</select>
				<div style="position:relative; display:none;" id="<?php echo $widgetType; ?>_status_selection" class="dropdown_selection">
					<div class="multiple-select-wrap" style="display:block; position:absolute; top: 22px; left: 0px; *top: 12px; z-index:100;">
						<div id="<?php echo $widgetType; ?>_statuscheckboxholder" class="multiple-select">
							<ol>
								<li><input type="radio" value="all" name="status" onclick="changeLeadStatus('<?php echo $widgetType; ?>','All');">All</li>
								<li><input type="radio" value="active" name="status" onclick="changeLeadStatus('<?php echo $widgetType; ?>','Active');" checked="checked">Active</li>
								<li><input type="radio" value="deleted" name="status" onclick="changeLeadStatus('<?php echo $widgetType; ?>','Deleted');">Deleted</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		<?php
			}
		?>
		<?php
			if ($widgetType == 'credit' && empty($salesUser)) {
		?>
		<input type="hidden" class="client_hidden" onclick="getGraphData(this);" />
		<script type="text/javascript">document.getElementById('<?php echo $widgetType; ?>_header').style.display="none";</script>
		<?php
			}
			else if ($widgetType == 'credit') {
		?>
		<input type="button" value="Refresh" uniqueattr="Dashboard/<?php echo $widgetType; ?>/RefreshButton" class="formbutton orange-button" style="font-size: 12px !important;" disabled="disabled" onclick="showSelectedClientName('credit'); getGraphData(this);" />
		<?php		
			}
			else if ($widgetType != 'leads' && $widgetType != 'credit') {
		?>
		<input type="button" value="Refresh" uniqueattr="Dashboard/<?php echo $widgetType; ?>/RefreshButton" class="formbutton orange-button" style="font-size: 12px !important;" disabled="disabled" onclick="getGraphData(this);" />
		<?php
			}
		?>
		<?php
			if ($widgetType == 'leads' && empty($salesUser)) {
		?>
		<input type="hidden" value="" name="timefilter[from]" id="<?php echo $widgetType; ?>_from" />
		<input type="hidden" value="" name="timefilter[to]" id="<?php echo $widgetType; ?>_to" />
		<input type="hidden" value="summary" name="timePeriod" />
		<input type="hidden" class="client_hidden" onclick="drawDefaultChart('<?php echo $widgetType; ?>')" />
		<script type="text/javascript">document.getElementById('<?php echo $widgetType; ?>_header').style.display="none";</script>
		<?php
			}
			else if ($widgetType == 'leads') {
		?>
		<input type="hidden" value="" name="timefilter[from]" id="<?php echo $widgetType; ?>_from" />
		<input type="hidden" value="" name="timefilter[to]" id="<?php echo $widgetType; ?>_to" />
		<input type="hidden" value="summary" name="timePeriod" />
		<input type="button" value="Refresh" uniqueattr="Dashboard/<?php echo $widgetType; ?>/RefreshButton" class="formbutton orange-button" style="font-size: 12px !important;" disabled="disabled" onclick="drawDefaultChart('<?php echo $widgetType; ?>')" />
		<?php
			}
		?>
	</form>
	<div class="populatedropdowns" onclick="populateDropDowns('<?php echo $widgetType; ?>');"></div>
</div>