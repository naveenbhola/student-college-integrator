<?php
$class = "cropper-hidden";
if (count($splitData) > 0 && isset($splitType) && $splitType != '') { $class = ""; }
else {
    $class = "";
}
$totalDataCount = 0;
?>

<div class="col-md-6 col-sm-6 col-xs-12 <?php echo $class; ?>">
    <div class="x_panel tile fixed_height_320 overflow_hidden">
        <div class="fixed_height_300">
            <div class="x_title">
                <h2><?php echo isset($splitType) ? ucfirst($splitType) : ucfirst($title); ?> Wise</h2>
                <h6>&nbsp;(<span class='singleShortStartingDate bold-font'><?php echo date('M d, y', strtotime($dates['startDate'])); ?></span> <span class="bold-font">to</span> <span class="singleShortEndDate bold-font" ><?php echo date('M d, Y', strtotime($dates['endDate']))?></span>)</h6>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <table class="" style="width:100%">
                    <tr class="x_title">
                        <th style="width:37%;">
                            <p>Split View</p>
                        </th>
                        <th>
                            <div class="col-md-7 col-lg-7 col-sm-7">
                                <p class="">Metric</p>
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-2 text-center">
                                <p class="">%</p>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-3">
                                <p class="text-right"><?php
                                    switch($actionName){
                                        case 'traffic':
                                            echo 'Sessions';
                                            break;
                                        case 'response':
                                            echo 'Responses';
                                            break;
                                        default:
                                            echo 'Count';
                                            break;

                                    }
                                    ?></p>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <canvas class="canvas1" id="<?php echo isset($splitType) ? str_replace(" ", "", $splitType) : $id; ?>" height="140"
                                    width="140"></canvas>
                        </td>
                        <td>
                            <div class="overflow_y fixed_height_180">
                                <table class="tile_info">
                                    <?php
                                    if (is_array($splitData)) {
                                        foreach ($splitData as $index => $value) {
                                            $totalDataCount += $value->ResponseCount;
                                        }
                                        foreach ($splitData as $index => $value) {
                                            ?>
                                            <tr>
                                                <td class="width_60_percent_important"><?php
                                                    $ucFirst = ucfirst(preg_replace('/([a-z])(_){0,}([A-Z])/', '$1 $3', $value->Pivot)); // Make camel cased word into separate words
                                                    $ucFirst = str_replace("_", " ", $ucFirst); // remove all underscores as well
                                                    ?><p title="<?php echo $ucFirst; ?>"
                                                         class="white_space_normal_overwrite"><i class="fa fa-square"
                                                                                                 style="color: <?php echo $colorCodes[ $index ]; ?>;"></i><?php echo $ucFirst; ?>
                                                    </p>
                                                </td>
                                                <td class="text_center_overwrite"><?php echo number_format($value->ResponseCount*100/$totalDataCount, 2, '.', ''); ?></td>
                                                <td class="nonempty text_center_overwrite"><?php echo number_format($value->ResponseCount); ?></td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr >
                        <td></td>
                        <td>
                            <div>
                                <table style="width: 100%; text-align: right;">
                                    <tr>
                                        <td><h4 class="totalDataCount">Total = <?php echo number_format($totalDataCount); $totalDataCount = 0; ?></h4></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="loader_small_overlay" id = "<?php echo 'loader_'.$id; ?>" style="display:none;"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
            <div class="text-danger text-center cropper-hidden">
                No result found for the selected criteria
            </div>
        </div>

    </div>
</div>
