<?php
$cityFilters  	= $filters['city'];
$stateFilters  	= $filters['state'];
$citySelected = false;
global $filtersCount;
$mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
$screenWidth = $mobile_details['resolution_width'];
$width = round(($screenWidth - 30)/3) - 5;
$widthStyle = 'width:'.(24*3)/$filtersCount.'%';
?>
<?php
		if(!empty($stateFilters) || !empty($cityFilters)){
			$tempCityFilterSelected = NULL;
			$tempStateFilterSelected = NULL;
			foreach($cityFilters as $filter){
				$isSelected = $filter->isSelected();
				if($isSelected){
					$tempCityFilterSelected = $filter;
					break;
				}
			}
			foreach($stateFilters as $filter){
				$isSelected = $filter->isSelected();
				if($isSelected){
					$tempStateFilterSelected = $filter;
					break;
				}
			}
			$useCityFilter = true;
			if(!empty($tempCityFilterSelected) && !empty($tempStateFilterSelected)){
				$tempCitySelected = $tempCityFilterSelected->getName();
				$tempStateSelected = $tempStateFilterSelected->getName();
				if(strtolower($tempCitySelected) == "all" && trim($tempCitySelected) != ""){
					$useCityFilter = false;
				}
			}
		
		$className = "locality-col";
		$blockRightBorderStyle = "";
		if(!$examBlockShown){
			$className = "ent-col";
			$blockRightBorderStyle = "border-right:none;";
			
		}
		global $widthPercent;
		$widthStyle = 30;
		if($widthPercent['exam']) {
			$widthStyle += $widthPercent['exam'];
		}
		if($widthPercent['course']) {
			$widthStyle += $widthPercent['course'];	
		}
		?>

		<select class="exam-filter" id="locationSelection<?=$number?>" onchange="redirectRanking('location','<?=$number?>',event);" style="width:<?=$widthStyle;?>%;">
			<option value=''>Location</option>

		<?php
		if(!empty($cityFilters)){
		?>
				<?php
					foreach($cityFilters as $filter){
						$title 		= $filter->getName();
						$url   		= $filter->getURL();
						$isSelected = $filter->isSelected();
						$className = "";
						//if($useCityFilter){
						?>
							<option <?=($rankingPageRequest->getCityName() == $title) ? "selected" : "";?> value="<?php echo $url;?>"><?php echo $title;?></option>
						<?php
						//}
					}
				?>
		<?php
		}
		?>

		<?php
		if(!empty($stateFilters)){
		?>
					<?php
						foreach($stateFilters as $filter){
							$title 		= $filter->getName();
							$url   		= $filter->getURL();
							?>
							<option value="<?php echo $url;?>"><?php echo $title;?></option>
							<?php
						}
					?>
		<?php
		}
		?>
		</select>

		<?php
		}
		?>
