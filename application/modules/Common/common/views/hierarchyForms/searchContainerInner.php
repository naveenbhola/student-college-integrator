<?php
foreach ($prefilledData as $value){ ?>
<li class="<?php echo $liClassName;?>">
	<div class="Customcheckbox">
		<input id="<?php echo $containerId;?>-<?php echo $value['id'];?>" type="checkbox" value= "<?php echo $value['id'];?>">
        <label for="<?php echo $containerId;?>-<?php echo $value['id'];?>"><?php echo $value['name'];?></label>
	</div>
</li>
<?php } ?>