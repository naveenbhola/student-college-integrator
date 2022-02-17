<?php

$cityFiltersInlineLocationLayer = $city_filters;
$resultTypeInlineLocationLayer = $result_type;
if(!empty($cityFiltersInlineLocationLayer)){
?>
	<div class="refine-city-box" id="rp_inline_ll" style="display:none;">
		<ul>
			<?php
			foreach($cityFiltersInlineLocationLayer as $filter){
				$selected = "";
				if($filter->isSelected()){
					$selected = "checked";
				}
				$id = $filter->getId();
				$name = $filter->getName();
				if(strtolower($name) == "all"){
					$id = "all";
				}
			?>
				<li>
					<input uniqueattr="RankingPage/cityinlinelocationlayeroptionselect" <?php echo $selected;?> type="radio" name="city_radio" id="city_radio_<?php echo $id;?>" value="<?php echo $id;?>"/> <?php echo $name;?>
				</li>
			<?php
			}
			?>
		</ul>
		<div class="spacer15 clearFix"></div>
		<input type="button" class="orange-button" value="Ok"  uniqueattr="RankingPage/cityinlinelocationlayersubmit" onclick="filterResultsByCity('<?php echo $resultTypeInlineLocationLayer;?>');"/>
	</div>
<?php
}
?>
