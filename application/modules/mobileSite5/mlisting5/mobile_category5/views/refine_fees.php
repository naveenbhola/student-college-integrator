<?php
global $filters;
global $appliedFilters;
$filters = $categoryPage->getFilters();
$appliedFilters = $request->getAppliedFilters();
$feesFilterValues = $filters['fees'] ? $filters['fees']->getFilteredValues() : array();
$count= 0;
if(isset($appliedFilters['fees']) && count($appliedFilters['fees'])>0){
	$count = count($appliedFilters['fees']);
}

?>

<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
	<a id="feesOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left"></span></i></a>   	
        <h3>Select Fees</h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap" style="height: 100%">
    <ul class="layer-list rnr-list">
		<?php $i=0;
		    foreach($feesFilterValues as $filter){
					$checked = '';
					if($appliedFilters == false){
								//$checked = "checked";	
					}
					elseif(in_array($filter,$appliedFilters['fees'])){
								$checked = "checked";
					}
					?>
			<li><label><input type="radio" <?=$checked?> name="fees[]" onClick="feesValue = ('<?=$filter?>');" id="feesSet<?=$i?>" value = "<?=$filter?>"/> <?=$filter?></label></li>
			<?php $i++;} ?>
	</ul>	
    </section>
	

<a id="eDButton" class="refine-btn" href="javascript:void(0);" onclick="selectCPFees();"><span class="icon-done"><i></i></span> Done</a>
<script>
var value = '<?php echo $appliedFilters['fees'][0];?>';
if (value != '') {
	
	var feesValue = value;
}else{
	var feesValue = jQuery("input:radio[name=fees]:checked").val();
}

if(feesValue ==''){
	feesValue = 'Change Fees';
}
</script>