<?php
$pageHeadline = "";
if(!empty($page_headline)){
	$pageHeadline .= "Top " . $page_headline['page_name'] . " institutes";
	if(!empty($page_headline['location'])){
		$pageHeadline .= " in <span>" . $page_headline['location'] . "</span>";
	}
	if(!empty($page_headline['exam'])){
		$pageHeadline .= " accepting " . $page_headline['exam'];
	}
	echo "<h1>" . $pageHeadline . "</h1>";
	
	$categoryFilters = $filters['category'];
	$catFilterData = array();
	$catFilterData['category_filters'] = $categoryFilters;
	$cityTempFilters 	= $filters['city'];
	$stateTempFilters 	= $filters['state'];
	?>
	<div id="change_options_cont" class="change-options">
		<?php
		if(!empty($categoryFilters)){
		?>
			<div style="display:inline;" uniqueattr="RankingPage/categorylayer" onclick="showCategoryOverlay(event);" onmouseout="displayCategoryLayer('hide');" >
				<b>[</b> <a href="javascript:void(0);">Change Course</a> <span style="cursor:pointer;">▼</span> <b class="change-opt-space">]</b>
				<?php echo $this->load->view("ranking/ranking_category_overlay", $catFilterData); ?>
			</div>
		<?php
		} else {
			?>
			<b>[</b>Change Course<b class="change-opt-space">]</b>
			<?php
		}
		if(!empty($cityTempFilters) || !empty($stateFilters)){
			?>
			<div style="display:inline;" uniqueattr="RankingPage/locationlayer" onclick="showLocationOverlay(event)" onmouseout="displayLocationLayer('hide');" >
				<b>[</b> <a href="javascript:void(0);">Change Location</a> <span style="cursor:pointer;">▼</span> <b>]</b>
			</div>
			<?php
		} else {
			?>
			<b>[</b> Change Location<b>]</b>
			<?php
		}
		?>
		<?php //echo $this->load->view("ranking/ranking_category_overlay", $catFilterData); ?>
		
		<div>
			<?php echo $this->load->view("ranking/ranking_page_location_layer"); ?>
		</div>
	</div>
	<?php
}
?>