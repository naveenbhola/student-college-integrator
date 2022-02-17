<div class="_tbBox slctnCrt-<?php echo $containerId;?>" style="margin-left:197px;">
	<?php
	$flagForLoopClosure = 0; 
	if(!empty($formatCreateTags) && empty($createtags)){
		$flagForLoopClosure = 1;
		foreach ($formatCreateTags as $key => $createtags) {
			if($key == 'city'){
				$containerId = "location-cN";
				$containerName = "cityList";
			}
			elseif($key == 'state'){
				$containerId = "location-sN";
				$containerName = "stateList";
			}
			elseif($key == 'country'){
				$containerId = "location-ctN";
				$containerName = "countryList";
			}
			else{
				$containerId = $key;
				$containerName = $key;
			}
			foreach ($createtags as $id=>$name) {?>
				<div class="slct-bx totalAttr" id="tb-<?php echo $containerId;?>-<?php echo $id;?>"><span><?php echo $name;?></span>&nbsp;<a href="javascript:void(0);" class="rmvSlctn" data-chkid="<?php echo $containerId;?>-<?php echo $id;?>">&times;</a>
					<input type="hidden" name="<?php echo $containerName;?>[0][]" value="<?php echo $id;?>">
				</div>
			<?php }
		}
	}
	else{ 
		foreach ($createtags as $id=>$name) {
			if($label == 'Institute'){
				$idTypeArray = explode('_', $id);
				$id = $idTypeArray[1];
				$containerId = $idTypeArray[0];
				if($containerId == 'institute'){
					$containerId = 'college';
				}
			}
			?>
	<div class="slct-bx totalAttr" id="tb-<?php echo $containerId;?>-<?php echo $id;?>"><span><?php echo $name;?></span>&nbsp;<a href="javascript:void(0);" class="rmvSlctn" data-chkid="<?php echo $containerId;?>-<?php echo $id;?>">&times;</a>
		<input type="hidden" name="<?php echo $containerId;?>[0][]" value="<?php echo $id;?>">
	</div>
	<?php }} ?>
</div>