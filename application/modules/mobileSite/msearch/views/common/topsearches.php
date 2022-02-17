<?php 
$display_array = array();	
$count = 0;
foreach(msearchLib::$shiksha_top_searches as $key=>$value) {
	$display_array[] = "<a href='".SHIKSHA_HOME.$key."'>$value</a>";	
	$count++;
	if($count == 10) {
		break;
	}
}
?>
<?php if(count($display_array)>0):?>
<div class="top-search">
	<h4><a href="/msearch/Msearch/showTopSearches" style="font-weight: bold;">Top Searches on Shiksha</a></h4>
    <p>
	<?php echo implode("<span>|</span>",$display_array); ?>
    </p>
<a href="/msearch/Msearch/showTopSearches" class="view-more">View more</a>
</div>
<?php endif; ?>
