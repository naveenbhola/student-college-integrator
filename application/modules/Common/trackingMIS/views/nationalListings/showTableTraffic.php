<?php
$class = "cropper-hidden";
if (count($resultsToShow) > 0) {
    $class = "";
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 <?php echo $class; ?>">
    <div class="x_panel">
        <div class="x_title">
            <div class="row">
                <div class="col-md-3"><h2>Consolidated <?php echo ucfirst($pivotName); ?></h2></div>
            </div>

            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="clear">
                <table id="example" class="table table-striped responsive-utilities jambo_table dataTable">
                    <thead>
                    <tr class="headings" role="row">
                        <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                            style="width: 41px;">
                            <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox"
                                                                                                 class="tableflat"
                                                                                                 style="position: absolute; opacity: 0;">
                                <ins class="iCheck-helper"
                                     style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                            </div>
                        </th>
                        <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                            aria-controls="example" rowspan="1" colspan="1">Page Name
                        </th>
                        <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                            aria-controls="example" rowspan="1" colspan="1">Traffic Source
                        </th>
                        <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                            aria-controls="example" rowspan="1" colspan="1">Source Application
                        </th>
                        <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                            aria-controls="example" rowspan="1" colspan="1">Traffic Count
                        </th>
                        <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                            aria-controls="example" rowspan="1" colspan="1">Traffic %
                        </th>
                    </tr>
                    </thead>
                    <tbody id="data-table"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="loader_small_overlay" style="display: none;"><img
        src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
</div>

<script>
    Common.target = '<?php echo SHIKSHA_HOME; ?>/trackingMIS/Listings/<?php echo $actionName;?>?dim=<?php echo $metricName; ?>';
    Common.setPagination();
    var dataForCSV = JSON.parse('<?php echo json_encode($prepareDataForCSV);?>');
</script>