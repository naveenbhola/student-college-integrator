	<?php foreach ($institutes as $index => $value):?>
		<li id="institute_<?php echo $value['id']?>">
		<input type="checkbox" name="college[]" multiple="multiple" class="flLt institute_check" value="<?php echo $value['id']?>" id="inst_<?php echo $value['id']?>" <?php if( !empty($colleges) && in_array($value['id'],$colleges)) {echo 'checked';} ?> >
		<label for="inst_<?php echo $value['id']?>" style="width:90% !important;">
		<span class="common-sprite"></span><p><?php echo $value['collegeName'].', '.$value['cityName'].', ' . $value['stateName'];?></p>
		</label>
		</li>
	<?php endforeach;?>	    
	<li id="no_result_inst" class='hid' >No result found for this institute</li>            
