<div class="col-md-6 col-sm-6 col-xs-12" id= "<?php echo $divId; ?>">
    <div class="x_panel tile fixed_height_320 overflow_hidden">
        <div class="fixed_height_300">
            <div class="x_title" >

                <div id = "source_before_<?php echo $id;?>" style="width:100%;font-size: 18px;font-weight: 400;"><?php echo $title; ?> <?php ($extraHeadingReq != false)?"Wise":"";?></div>
                <div class="clearfix"></div>
            </div>
            
            <?php if(count($tableHeading) >0){?>
                <div class="x_content widget_summary" style="padding-bottom:10px">
                    <div class="w_left w_55" style="width:80% !important">
                        <span title="B.Tech" style = "color: #73879C !important"><?php echo $tableHeading['left'];?></span>
                    </div>
                    <span class="sr-only"></span>
                    <div class="w_left w_20 margin_left_5" style="width:20% !important;margin-right:15px">
                        <span style = "color: #73879C !important"><?php echo $tableHeading['right'];?></span>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php }?>            
                



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
                            <div class="col-md-2 col-lg-2 col-sm-2">
                                <p class="">Count</p>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td id = "<?php echo $mainDiv; ?>">
                            <canvas class="canvas1" id="<?php echo $id; ?>" height="140"
                                    width="140" style="margin: 15px 10px 10px 0"></canvas>
                        </td>
                        <td>
                            <div class="overflow_y fixed_height_180">
                                <table class="tile_info" id='<?php echo $tableId; ?>'>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr >
                        <td></td>
                        <td>
                            <div>
                                <table style="width: 100%; text-align: right;" >
                                    <tr>
                                        <td id='<?php echo $totalValue; ?>'><h4 class="totalDataCount"></h4></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="loader_small_overlay" id = "<?php echo 'loader_'.$id; ?>"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
        </div>
    </div>
</div>
