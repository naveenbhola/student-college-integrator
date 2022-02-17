<?php
global $filters;
global $appliedFilters;
$locationDataFlag = 1;
if($filters['country'] && count($filters['country']->getFilteredValues()) > 1){
			$filter_list_source = "country";
			$filter_list_title = "Countries";
} else if(is_array($topFilteredCitieslist) && count($topFilteredCitieslist) > 1){
			$filter_list_source = "topFilteredCities";
			$filter_list_title = "State & Top Cities";
} else if($filters['city'] && count($filters['city']->getFilteredValues()) > 1){			
			$filter_list_source = "city";
			$filter_list_title = "Regions";
} else {
			$locationDataFlag = 0;
}

if($locationDataFlag == 1) {
?>
<ul>
			<li><strong><?php echo $filter_list_title; ?></strong></li>
			<li>
			  <div id="location_filter_div" class="filter-search">
				<input type="text" id="location_filter_keyword_txtbox" class="s-box" />
				<span id="search_trunoff_icon" style="display: none;"><i class="icon-close" onclick="turnOffLocationFiltering();"></i></span>				
				<span class="icon-search"><i></i></span>
				<input type="hidden" name="filter_list_source" id="filter_list_source" value="<?php echo $filter_list_source; ?>">
			  </div>
			</li>
</ul>
<div id="location_list_div">Loading...</div>
<?php }