<?php
 $GA_Tap_On_View_All = 'VIEW_ALL_HIGHLIGHTS';
if(is_array($highlights) && count($highlights)>0){
?>
<div class="crs-widget listingTuple" id="highlights">
        <h2 class="head-L2">Highlights</h2>
        <div class="lcard">
            <ol>
		<?php for ($i=0; $i<count($highlights); $i++){ ?>
		<?php if($i==4){echo "</ol><ol id='highlightsText' style='display:none;'>";} ?>
                <li>
                    <?=makeURLAsHyperlink(htmlentities($highlights[$i]->getDescription()))?>
                </li>
		<?php } ?>
            </ol>
	    <?php
		if(count($highlights) > 4){ 
	    ?>
            <a id="highlightsViewAll" href="javascript:void(0);" class="link-blue-medium  v-more" ga-attr="<?=$GA_Tap_On_View_All;?>" onClick="showSection('highlights');">View all</a>
	    <?php } 
	    ?>
        </div>
    </div>
<?php
}
?>
