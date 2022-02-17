	<div class="mapping-section">

	<?php if ($editedFlow != 1) {?>
	<ol id="mapping-sample" style="display:none;">
		<li>
			<div class="col-1"><input type="text" maxlength="50" name="var_name[]" id="var_name_keyid" value="" /></div>
			<div class="col-1">
				<select name="var_key[]" id="var_key_keyid" class="variablesList">
					<?php
						for($i=0;$i<count($shikshaFields);$i++) {
					?>
						<option  

						value ="<?php echo $shikshaFields[$i]['fieldId'];?>"
						portingType="<?php if(in_array($shikshaFields[$i]['fieldId'],$customized))
						{
							echo "customized";
						}
						else
						{
							echo "";
						} ?>">
						<?php echo $shikshaFields[$i]['name'];?>
						 	
						</option>
						
					<?php
						}
					?>
						<option value="-1" portingType="both">Others</option>
				</select>
			</div>
			<div>	
				<input type="button" class="gray-button" value="Customize" id="customizedValue_keyid" style="display: none; width: 80px;"/>
			</div>
			<span class="col-2" id="otherValue_keyid" style="<?php echo (count($shikshaFields) ? 'display:none;': ''); ?>">
				<input type="text" maxlength="250" name="temp_name[]" id="temp_name_keyid" value="" />
			</span>
		</li>
	</ol>
<?php }?>
	<ol>
		<?php if ($editedFlow==1){?>
		<li>
			<div class="col-1"><strong>Client Fields</strong></div>
			<div class="col-1"><strong>Shiksha Fields</strong></div>
		</li>
	<?php }?>
	</ol>
	<div class="field_container_div">
		<ol id="field_mappings" class = "field_container">
			<?php

			if (count($clientFields) > 0 && $portingData['request_type'] !== "EMAIL") {
				if(!empty($customizedCount))
				{
					$j = $customizedCount;
					$traverse = $j + count($clientFields);
				}
				else
				{
					$j = 0;
					$traverse = count($clientFields);
				}
				$count = -1;
				for($j; $j < $traverse;$j++) {
					$count++;
			?>
			<li id="field_<?php echo $j; ?>">
				<div class="col-1"><input type="text" maxlength="50" name="var_name[]" id="var_name_<?php echo $j; ?>" value="<?php  echo $clientFields[$count]['client_field_name'];  ?>" /></div>
				<div class="col-1">
					<select name="var_key[]" id="var_key_<?php echo $j; ?>" class="variablesList" onchange="validateVariables('var_key_<?php echo $j; ?>','otherValue_<?php echo $j; ?>',<?php echo $j;?>);">
						<?php
							for($i=0;$i<count($shikshaFields);$i++) {
						?>
							<option value ="<?php echo $shikshaFields[$i]['fieldId'];?>" portingType="<?php if(in_array($shikshaFields[$i]['fieldId'],$customized))
							{
								echo "customized";
							}
							else
							{
								echo "";
							} ?>" 
							<?php if ($clientFields[$count]['master_field_id'] == $shikshaFields[$i]['fieldId']) { echo "selected";}?> ><?php echo $shikshaFields[$i]['name'];?></option>
							<?php
							}
						?>
							<option value="-1" portingType="both" <?php if($clientFields[$count]['master_field_id'] == -1) { echo "selected";} ?> >Others</option>
					</select>
				</div>
				<div>
					<?php if(in_array($clientFields[$count]['master_field_id'],$customized)) {?>
					<input type="button" class="gray-button" value="Customize" id="customizedValue_<?php echo $j;?>" onclick="showCourseDetails('var_key_<?php echo $j; ?>');" style="display: block; width: 80px;"/>
				<?php } else {?>
					<input type="button" class="gray-button" value="Customize" id="customizedValue_<?php echo $j;?>" onclick="showCourseDetails('var_key_<?php echo $j; ?>');" style="display: none; width: 80px;"/>
				<?php }?>
				</div>
				<span class="col-2" id="otherValue_<?php echo $j;?>" style="display:none;">
					<input type="text" maxlength="250" name="temp_name[]" id="temp_name_<?php echo $j; ?>" value="<?php if($clientFields[$count]['master_field_id'] == -1) { echo $clientFields[$count]['other_value']; } ?>" />
				</span>
				<?php
					if($clientFields[$j]['master_field_id'] == -1) {
				?>
				<script>
					$('otherValue_<?php echo $j;?>').style.display = '';
				</script>
				<?php
					}
				?>
				</li>
			<?php
					}
				} else { if($customizedCount=="" || $customizedCount==null)
					{
						$customizedCount=0;
					}
			?>
			<li id="field_<?php echo $customizedCount ?>">
				<div class="col-1"><input type="text" maxlength="50" name="var_name[]" id="var_name_<?php echo $customizedCount ?>" value="" /></div>
				<div class="col-1">
					<select name="var_key[]" id="var_key_<?php echo $customizedCount ?>" class="variablesList" onchange="validateVariables('var_key_<?php echo $customizedCount ?>','otherValue_<?php echo $customizedCount ?>',<?php echo $customizedCount ?>);">
						<?php
							for($i=0;$i<count($shikshaFields);$i++) {
						?>
							<option  

							value ="<?php echo $shikshaFields[$i]['fieldId'];?>"
							portingType="<?php if(in_array($shikshaFields[$i]['fieldId'],$customized))
							{
								echo "customized";
							}
							else
							{
								echo "";
							} ?>">
							<?php echo $shikshaFields[$i]['name'];?>
							 	
							</option>
						
						<?php
							}
						?>
							<option value="-1" portingType="both">Others</option>
					</select>
				</div>
				<div>
					<input type="button" class="gray-button" value="Customize" id="customizedValue_<?php echo $customizedCount ?>" onclick="showCourseDetails('var_key_<?php echo $customizedCount ?>')" style="display: none; width: 80px;"/>
				</div>
				<span class="col-2" id="otherValue_<?php echo $customizedCount ?>" style="<?php echo (count($shikshaFields) ? 'display:none;': ''); ?>">
					<input type="text" maxlength="250" name="temp_name[]" id="temp_name_<?php echo $customizedCount ?>" value="" />
				</span>
			</li>
			<?php
			}
			?>
		</ol>
		<ol>
			<?php if ($editedFlow != 1) { ?>
				<li><a href="javascript:void(0);" onclick="return addNewFeildMapping(this,<?php echo $customizedCount; ?>);">+ Add More</a></li>
			
			<?php } ?>
		</ol>
	</div>
	<div class="clearFix"></div>
</div>
