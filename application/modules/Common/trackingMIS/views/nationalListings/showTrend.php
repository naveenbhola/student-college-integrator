<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">

            <div class="row ">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 white_space_normal_overwrite">
                            <h3><?php echo $dimension != 'All' ? ucfirst($dimension). ' Page': 'All Pages'; ?></h3>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12 col-sm-12 col-xs-12 white_space_normal_overwrite">
                            <h6>Showing Trend for : <span class="bold-font"><?php echo strtoupper($actionName); ?></span></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-7 col-sm-7 col-xs-7 white_space_normal_overwrite">
                            <h6>Base start date : <span class='singleStartingDate' class="bold-font"><?php echo date('F d, Y', strtotime($dates['startDate'])); ?></span>, Base end date : <span class="singleEndDate" class="bold-font"><?php echo date('F d, Y', strtotime($dates['endDate'])); ?></span></h6>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4" id="viewtype">
                            <div class="row">
                                <button class="col-md-3 btn btn-default margin_0_important top_right_0_border_radius bottom_right_0_border_radius padding_top_6_important padding_bottom_6_important <?php echo $period == 'day' ? 'bgcolor' : 'defaultColor'; ?>" type="button" >Day</button>
                                <button class="col-md-3 btn btn-default margin_0_important border_radius_0 no_left_border_important no_right_border_important padding_top_6_important padding_bottom_6_important <?php echo $period == 'week' ? 'bgcolor' : 'defaultColor'; ?>" type="button" >Week</button>
                                <button class="col-md-3 btn btn-default defaultColor margin_0_important top_left_0_border_radius bottom_left_0_border_radius padding_top_6_important padding_bottom_6_important <?php echo $period == 'month' ? 'bgcolor' : 'defaultColor'; ?>" type="button" >Month</button>
                            </div>
                            <input type="hidden" name="viewtype" value="<?php echo $period != '' ? $period : 'day'; ?>"/>

                        </div>
                    </div>
                </div>

            </div>
            <?php
            $class = "cropper-hidden";
            if(isset($compareStartDate) && isset($compareEndDate)) {
            $class = "";
            }?>

            <div class="row <?php echo $class; ?>">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 white_space_normal_overwrite">
                            <h6>Comparison start date : <span id='comparisonStartingDate' class="bold-font"><?php echo isset($compareStartDate) ? date('F d, Y', strtotime($compareStartDate)): ''; ?></span>, Comparison end date : <span id="comparisonEndDate" class="bold-font"><?php echo isset($compareEndDate) ? date('F d, Y', strtotime($compareEndDate)): ''; ?></span></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row x_title"></div>
            <div class="row">
                <?php if (isset($pivots) && count($pivots) > 0) { ?>
                    <div class="col-md-1">

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 white_space_normal_overwrite text-center">
                                <h5>Aspect:</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <button class="col-md-12 col-sm-12 col-xs-12 btn btn-default dropdown-toggle white_space_normal_overwrite" type="button" id="pivot" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true"><span class="caret margin-right-2"></span><?php echo $pivots[ $pivotName ]; ?></button>
                            <ul class="dropdown-menu" aria-labelledby="pivot">
                                <?php foreach ($pivots as $pivotKey => $pivotValue) { ?>
                                    <li data-dropdown="<?php echo $pivotKey; ?>"><a
                                            href="javascript: void(0)"><?php echo $pivotValue; ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <input type="hidden" name="pivot" value="<?php echo $pivotName; ?>"/>
                    </div>
                    <div class="col-md-1">
                        <div class="row">
                            <div class="col-md-12"></div>
                        </div>
                    </div>
                <?php } else { ?><input type="hidden" name="pivot" value="<?php echo $actionName; ?>"/><?php } ?>
                <div id="legends" class="col-md-7 <?php echo $class; ?>">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5>Graph Legends:</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p>Base Data: <i id="baseDataColor" class="fa fa-square"
                                                                           style="color: <?php echo $colorCodes[0]; ?>;"></i></p>
                                                    <p >Comparison Data: <i id="compareDataColor" class="fa fa-square"
                                                                                style="color: <?php echo $colorCodes[1]; ?>;"></i></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="placeholder" style="height: 260px; display: none" class="demo-placeholder"></div>
                    <div style="width: 100%;">
                        <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
                        <div class="text-danger text-center cropper-hidden">
                            No result found for the selected criteria
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
