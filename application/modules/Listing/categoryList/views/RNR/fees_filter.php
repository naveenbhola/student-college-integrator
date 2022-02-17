<?php
// get the global values
global $filters;
global $appliedFilters;

// fetch Parent and current fees filter values from Fees filter object(s)
$feesFilterValues 		= $filters['fees'] ?$filters['fees']->getFilteredValues() : array();
$feesCurrentFilterValues 	= $filters['feesCurrent'] ? $filters['feesCurrent']->getFilteredValues() : array();

$feesCount = $solrFacetValues['facet_queries'];
//
?>
<!-- Fees filter starts -->
<div class="filter-blocks clear" id="feesFilterSection">
		<div class="mb10">
			<h3 class="filter-title flLt" style="margin-bottom:0;">
			<i class="cate-new-sprite fee-icon"></i>Total Fees (INR)</h3>
			<a class="flRt" href="javascript:void(0);" onclick="resetCategoryFilter('feesFilterSection');"><i class="cate-new-sprite reset-icon"></i>Reset</a>
			<div class="clearfix"></div>
		</div>
	<div class="customInputs">
		<div class="scrollbar1" id="feesFilterScrollbar">
			<div class="scrollbar">
				<div class="track">
					<div class="thumb"></div>
				</div>
			</div>
			<div class="viewport" style="height:150px;overflow:hidden;">
				<div class="overview">
					<ul class="refine-list">
					<?php
					    $key = 0;
					    //foreach($feesFilterValues as $filter)
					    $ranges = $GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'];
					    $ranges["No Limit"] = "No Limit";
					    foreach($ranges as $filter)
					    {
						$checked 	= '';
						$disabled 	= '';

						// determine whether the radio button will be selected or not
						if($appliedFilters == false){
								//$checked = "checked";	
						}
						elseif(trim($filter) == $appliedFilters['fees'][0] || ($request->getFeesValue() && $GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'][$request->getFeesValue()] == trim($filter) && empty($appliedFilters['fees']) && !is_array($appliedFilters['fees']))) {
								$checked = "checked";
						}
						$feeValue = array_search($filter,$GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS']);
						
						if($filter == 'No Limit')
						{
							$countVal = $feesCount['NoLimit'];
							$disabled = $countVal ? '' : 'disabled';
						}
						else
						{
							$countVal = $feesCount[$feeValue];//_p($feeValue);
							$disabled = $countVal ? '' : 'disabled';
						}
					?>
							<li>
								<input type="radio" <?=$checked.' '.$disabled?> id="fees_<?=$key?>" name="fees" value="<?=$filter?>" onclick="applyRnRFiltersOnCategoryPages();" autocomplete="off">
								<label for="fees_<?=$key?>">
									<span class="common-sprite"></span><p><?=$filter?> <em>(<?=$countVal ? $countVal : 0 ?>)</em></p>
								</label>
							</li>
					<?php
					$key++;
						}
					?>
					 </ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Fees filter Ends -->