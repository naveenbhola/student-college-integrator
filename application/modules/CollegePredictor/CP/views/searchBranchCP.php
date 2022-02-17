	<?php foreach ($branches as $index => $value):?>
		<li id="branch_<?php echo $index; ?>">
		<input type="checkbox" name="branch[]" multiple="multiple" class="flLt branch_check" value="<?php echo $value['branchAcronym'];?>" id="branch_<?php echo $value['branchAcronym'];?>" <?php if( !empty($branchAcronym) && in_array($value['branchAcronym'],$branchAcronym)   ) {echo 'checked';}else {echo '';} ?> >
		<label for="branch_<?php echo $value['branchAcronym'];?>"> 
		<span class="common-sprite"></span><p><?php echo $value['branchAcronym'];?></p>
		</label>
		</li>
	<?php endforeach;?>
	<li id="no_result_branch" style="display:none;">No result found for this branch</li>
