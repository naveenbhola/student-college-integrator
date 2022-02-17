<div class="col-md-6 col-sm-6 col-xs-12" id="pieChart<?php echo $number;?>">
    <div class="x_panel tile fixed_height_320 overflow_hidden" id="pieChart<?php echo $number;?>">
        <div class="x_title">
            <div>
                <div class="pieHeadingSA"><?php echo $value['heading']; ?></div>
                <div id="dateRange_<?php echo $number;?>" class="dateSA"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table style="width:100%">
                <tr class="x_title">
                    <th style="width:37%;">
                            <p>Split View</p>
                    </th>
                    <!-- <th  style="width:37%;" class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <div>
                            <div class="countHeadingSA"><?php echo $value['countHeading']; ?>:&nbsp&nbsp </div>
                            <div id="total<?php echo $number;?>" class="countDateSA"></div>
                        </div>
                    </th> -->
                    <th>
                        <div class="col-md-7 col-lg-7 col-sm-7">
                            <p class=""><?php echo $value['type']; ?></p>
                        </div>
                        <div class="col-md-3 col-lg-2 col-sm-3 text-center">
                            <p class="">%</p>
                        </div>
                        <div class="col-md-2 col-lg-3 col-sm-4" style="text-align:center;padding:0px !important">
                            <p class="">Count</p>
                        </div>
                    </th>
                </tr>
                <tr>
                    <td>
                        <div id="pie_chart_div<?php echo $number;?>">
                            <?php
                            $chartId = "pie_chart_$number";
                            if(isset($value['id'])){
                                $chartId = $value['id'];
                            }
                            ?>
                            <canvas id="<?php echo $chartId; ?>" height="140" width="230" style="margin: 15px 10px 10px 0"></canvas>
                        </div>
                    </td>
                    <td>
                        <div style="height: 180px;" class="overflow_y">
                        <table class="tile_info tile_info_<?php echo $number;?>" style="padding:0px !important">
                        </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div>
                            <table style="width: 100%; text-align: right;" >
                                <tr>
                                    <td >
                                    <h4 id='total<?php echo $number; ?>'></h4>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="loader_small_overlay" id="pieChart_loader<?php echo $number;?>" style="display:none"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
    </div>
</div>