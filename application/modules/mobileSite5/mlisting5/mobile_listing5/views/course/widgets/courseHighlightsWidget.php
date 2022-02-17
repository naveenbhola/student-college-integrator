<?php 
if(is_array($highlights) && count($highlights)>0){
?>
<div class="crs-widget listingTuple" id="highlights">
    <h2 class="head-L2">Highlights</h2>
    <div class="lcard">
        <ol class="higlight-list">
        <?php for ($i=0; $i<count($highlights); $i++){ ?>
		<?php if($i==4){echo "</ol><ol class='higlight-list' id='highlightsText' style='display:none;'>";} ?>
                <li>
                    <?=nl2br(makeURLAsHyperlink(htmlentities($highlights[$i]->getDescription())))?>
                </li>
        <?php } ?>
        </ol>
        <?php if(count($highlights)>4){?>
        <a id = 'highlightsViewAll' href="javascript:void(0);" class="link-blue-medium  v-more" ga-attr="VIEW_COMPLETE_HIGHLIGHTS">View all</a>
        <?php }?>
    </div>
</div>

<?php
}
?>