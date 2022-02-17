<div id = "h<?php echo $countHierarchy;?>" tuple="<?php echo $countHierarchy;?>" style="position:relative;" class="lstTpl">
<div class="main-table" style="width:100%;display:block;border-top: 1px solid #0ba5b5;margin-top:10px;">
<div class="right-div" style="display:inline-block;width:100px;vertical-align:top;">
	<p class="head-p">Hierarchy <?php echo $countHierarchy;?></p>
</div>
<div class="left-table" style="display:inline-block;margin-left:20px;vertical-align:top">
<table class="uni-table" cellspacing="0" cellpadding="0" style="padding-top:0;"">

	<?php if(is_array($formElements['stream'])): 
	$customAttr = '';
	if($formElements['stream']['customAttr']!=''){
		foreach ($formElements['stream']['customAttr'] as $attr => $value) {
			$customAttr .= ($customAttr=='') ? $attr.'="'.$value.'"' : ' '.$attr.'="'.$value.'"';
		}
	}
	if($formElements['stream']['mandatory'] == 'yes'){
		$required = "required = required";
	}
	?>
	<tr>
		<td>
			<div>
				<label class="label-width"><?php echo $formElements['stream']['label']?></label>
				<select <?php echo $required?> class="uni-select" <?php echo $customAttr?> num="<?php echo $countHierarchy;?>" name="stream[]" id="<?php echo $formElements['stream']['id'].$countHierarchy?>">
					<option value="">Choose stream</option>
					<?php 
					foreach ($stream['stream'] as $value) {
						$selected = '';
						if($existingData['selections']['stream_id'] == $value['id']){
							$selected = "selected = 'selected'";
						}
						?>
						<option <?php echo $selected;?> value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
						<?php 
					}
					?>
				</select>
			</div>
		</td>
	</tr>
	<?php endif;?>
	<?php if(is_array($formElements['subStream'])):
	$customAttr = '';
	if($formElements['subStream']['customAttr']!=''){
		foreach ($formElements['subStream']['customAttr'] as $attr => $value) {
			$customAttr .= ($customAttr=='') ? $attr.'="'.$value.'"' : ' '.$attr.'="'.$value.'"';
		}
	}
	?>
	<tr>
		<td>
			<div>
				<label class="label-width"><?php echo $formElements['subStream']['label']?></label>
				<select class="uni-select" <?php echo $customAttr?> num="<?php echo $countHierarchy;?>" name="subStream[]" id="<?php echo $formElements['subStream']['id'].$countHierarchy?>">
					<option value="">Choose sub stream</option>
					<?php foreach ($existingData['substreams'] as $id => $name) {
						$selected = '';
						if($existingData['selections']['substream_id'] == $id){
							$selected = "selected = 'selected'";
						}
						?>
						<option <?php echo $selected;?> value="<?php echo $id;?>"><?php echo $name;?></option>
					<?php } ?>
				</select>
			</div>
		</td>
	</tr>
	<?php endif;?>
	<?php if(is_array($formElements['specialization'])): 
	$customAttr = '';
	if($formElements['specialization']['customAttr']!=''){
		foreach ($formElements['specialization']['customAttr'] as $attr => $value) {
			$customAttr .= ($customAttr=='') ? $attr.'="'.$value.'"' : ' '.$attr.'="'.$value.'"';
		}
	}
	?>
	<tr>
		<td>
			<div>
				<label class="label-width"><?php echo $formElements['specialization']['label']?></label>
				<select class="uni-select" <?php echo $customAttr?> num="<?php echo $countHierarchy;?>" name="specialization[]" id="<?php echo $formElements['specialization']['id'].$countHierarchy?>">
					<option value="">Choose specialization</option>
					<?php foreach ($existingData['specializations'] as $id => $name) {
						$selected = '';
						if($existingData['selections']['specialization_id'] == $id){
							$selected = "selected = 'selected'";
						}
						?>
						<option <?php echo $selected;?> value="<?php echo $id;?>"><?php echo $name;?></option>
					<?php } ?>
				</select>
			</div>
		</td>
	</tr>
	<?php endif;?>
</table>

<?php if($formElements['stream']['showPrimaryHierarchy'] == 'yes') {?>
<p style="position:absolute;right:50px;top:15px;">
<?php $conditionForChecked = 0; 
if(!empty($existingData['primaryHierarchy'])){
	$conditionForChecked = 1;
}
elseif($countHierarchy==1){
	$conditionForChecked = 1;
}
?>
	<input class ="primaryBtn" id="ph-<?php echo $countHierarchy;?>" <?php if($conditionForChecked==1){?> checked <?php }?>  num="<?php echo $countHierarchy;?>" type="radio" name="primary" value="<?php echo $countHierarchy-1;?>">Primary
</p>
<?php } ?>

