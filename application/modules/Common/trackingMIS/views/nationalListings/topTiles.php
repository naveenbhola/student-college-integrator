<!-- top tiles -->
<div class="row tile_count">
    <?php
    $ajaxBasedActions = array(
        'response',
        'registration',
        'traffic',
        'engagement'
    );
if (in_array(strtolower($actionName), $ajaxBasedActions)) {
    $index = 0;
    foreach ($tileData as $oneTile) {

        if ($actionName == 'traffic' || $actionName == 'engagement') { // Clickable traffic toptiles
            if ($oneTile->name != '(%) New Sessions') {
                $class = 'cursor_pointer border_1px_solid_eee border_radius_6 ';
                if (
                    ($actionName == 'traffic' && $oneTile->name == 'Users') || // This condition can be replaced with dataText as well in case of any issues with this condition
                    ($actionName == 'engagement' && $oneTile->dataText == 'pageview')
                ) {

                    $class .= 'defaultColor';
                } else {
                    $class .= 'bgColor';
                }
            } else {
                $class = 'bgColor';
            }
        }

        ?>
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count <?php echo $class; ?>" data-text="<?php echo isset($oneTile->dataText) ? $oneTile->dataText : ''; ?>">
            <div class="left"></div>
            <div class="right">
                <span class="count_top"><i class="fa fa-user"></i><?php echo isset($oneTile->name) ? $oneTile->name : $oneTile; ?></span>

                <div class="count font_22"
                     id="topTiles_<?php echo($index + 1); ?>"></div>
                <span class="count_bottom"><i id="<?php echo 'bottom_'.($index+1); ?>"><i class="fa"></i></i></span>
                <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>

            </div>
        </div>
        <?php $index++;
    }
} ?>
</div>
<!-- /top tiles -->