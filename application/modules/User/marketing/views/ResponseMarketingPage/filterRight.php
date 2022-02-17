<?php
global $filters;
global $appliedFilters;
$localityFilterValues = $filters['locality']->getFilteredValues();
$cityFilterValues = $filters['city']->getFilteredValues();
if($filters['locality'] && $request->getCityId() != 1) {
	if(count($localityFilterValues) > 0) {			
?>
		<h6>Regions</h6>
		<ul>
		<?php foreach($localityFilterValues as $zone=>$filter){?>
		<li><input type="checkbox" id="zone<?=$zone?>" value="<?=$zone?>" onclick="applyFullZone('<?=$zone?>');"/> <?=$filter['name']?></li>
		<li>
			<ul>			
			<?php foreach($filter['localities'] as $locality=>$filter1){ 
						$checked = '';
						if(in_array($locality,$appliedFilters['locality'])){
								$checked = "checked";
						}
			?>
				<li><input type="checkbox" <?=$checked?> value = "<?=$locality?>" class="zonelocality<?=$zone?>" name="locality[]" onclick="applyLocality('<?=$zone?>');" /> <?=$filter1?><script>applyLocality('<?=$zone?>');</script></li>
			<?php } ?>
			</ul>
		</li>
		<?php if(in_array($zone,$appliedFilters['zone'])&&count($appliedFilters['locality'])<1){ ?>
		<script>
			$('zone<?=$zone?>').checked = true;
			applyFullZone('<?=$zone?>');
		</script>
		<?php } ?>
		<?php } ?>
	 </ul>
<?php
	}
}

if($filters['city'] && !($request->getCityId()==1 && $request->getStateId()==1)){
	$cityFilterValues = $filters['city']->getFilteredValues();
	if(count($cityFilterValues) > 1){
?>
	   <h6>Cities</strong></h6>
	   <ul>
	    <?php foreach($cityFilterValues as $key=>$city){
				if($key == $request->getCityId()){
					continue;
				}
				$checked = '';
				if(in_array($key,$appliedFilters['city'])){
					$checked = "checked";
				}
			?>
		<li><input type="checkbox" <?=$checked?> name="city[]" value="<?=$key?>"/> <?=$city?></li>
		<?php } ?>
	</ul>
<?php	
	}
}elseif($filters['city']){
	global $cityList;
	$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
	$cityFilterValues = $filters['city']->getFilteredValues();
	if($appliedFilters['city'] && count($appliedFilters['city'])>0){
		$displayMainCity = 'none';
		$displaySubCity = '';
	}else{
		$displayMainCity = '';
		$displaySubCity = 'none';
	}
	if(count($cityFilterValues) > 1){
?>
		<h6>Cities</strong></h6>
		<ul>
	    <?php foreach($cityList[1] as $city){
			if(array_search($city->getName(),$cityFilterValues)){
				$checked = '';
				if(in_array($city->getId(),$appliedFilters['city'])){
					$checked = "checked";
				}
		?>
		<li><input type="checkbox" <?=$checked?> name="city[]" value="<?=$city->getId()?>"/> <?=$city->getName()?></li>
		<?php }} ?>
		<?php foreach($cityList[2] as $city){
			if(array_search($city->getName(),$cityFilterValues)){
				$checked = '';
				if(in_array($city->getId(),$appliedFilters['city'])){
					$checked = "checked";
				}
		?>
		<li><input type="checkbox" <?=$checked?> name="city[]" value="<?=$city->getId()?>"/> <?=$city->getName()?></li>
		<?php }} ?>
		<li id="mainCityFilters" style="display:<?=$displayMainCity?>"><a href="javascript:void(0);" onclick="$('mainCityFilters').style.display='none';$('subCityFilters').style.display='';">[ + ] More</a></li>
		</ul>
		<ul id="subCityFilters" style="display:<?=$displaySubCity?>">
	    <?php foreach(($cityList[3]) as $city){
			if(array_search($city->getName(),$cityFilterValues)){
				$checked = '';
				if(in_array($city->getId(),$appliedFilters['city'])){
					$checked = "checked";
				}
		?>
		<li><input type="checkbox" <?=$checked?> name="city[]" value="<?=$city->getId()?>"/> <?=$city->getName()?></li>
		<?php }} ?>
		</ul>
<?php	
	}
}
?>
