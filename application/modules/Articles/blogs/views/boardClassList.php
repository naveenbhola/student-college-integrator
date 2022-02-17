<div class="bld mb5">Class:</div>
<select name="boardClass" id="boardClass" caption="Class Type" class="textboxBorder">
	<option value="">Select Class</option>
	<?php foreach($boardClass as $class){?>
    		<option value="<?php echo $class;?>"><?php echo $class; ?></option>
    <?php }?>
</select>