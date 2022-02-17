<h3>Leads / Match Responses</h3>
<?php
if(!(is_array($portingConditions))){
$portingConditions = array('searchagent'=>array(0=>0));
}

?>
<ul>
	<li id="leadgenies_holder">
		<?php
			if(count($leadGenieDetails)) {
		?>
			<div class="main-leads" id="main-leads-porting"><label><input type="checkbox" style = " display : none;" id="allleadgenies" onclick="checkUncheckChilds1(this, 'leadgenies_holder');" <?php echo (!empty($portingConditions['searchagent']) && count($portingConditions['searchagent']) == count($leadGenieDetails) ? 'checked' : ''); ?> /> <span><?php echo count($leadGenieDetails); ?></span> genies found</label></div>
			
			<div id="radioDiv"><input type='radio' name='genieType' value='lead' onclick='selectLeadGenieType()' <?php if($portingData['type'] == 'lead') echo 'checked'; ?>>Lead</input>&nbsp;&nbsp;&nbsp;&nbsp;<input type='radio' name='genieType' value='matchResponse' onclick='selectMRGenieType()' <?php if($portingData['type'] == 'matched_response') echo 'checked'; ?>>Match Response </input></div>
			
			<div class="sub-points">
				<ol>
					<?php
						foreach($leadGenieDetails as $key=>$value) {
					?>
					<div class="porting_<?php echo $value['type']; ?>" style="<?php if($value['type'] == 'response'){ echo 'display : none';}else{ echo 'display : block';} ?>">
					<li><label><input type="checkbox" name="lead_genies[]" class="porting_genie_<?php echo $value['type']; ?>" onclick="uncheckElement1(this, 'allleadgenies', 'leadgenies_holder');" <?php foreach($portingConditions['searchagent'] as $k=>$v){ if((is_array($portingConditions)) && ($k == $key)){ echo "checked"; } }?> value="<?php echo $key; ?>" /> <?php echo $value['Name']; ?></label></li></div>
					<?php	
							
						}
					?>
				</ol>
			</div>
		<?php
			} else {
		?>
			<div class="main-leads"><label>No genies found</label></div>
		<?php
			}
		?>
	</li>
</ul>
