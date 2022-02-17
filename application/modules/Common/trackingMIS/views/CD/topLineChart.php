<div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="dashboard_graph">

                            <div class="row x_title">
                                <div class="col-md-6">
                            <h2><?php echo $value['heading'];?></h2>
                            </div>
                        </div>

                            <div class="row x_title" id="<?php echo $key;?>-div">
                                <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                                <div style="width: 100%;">
                                    <div id="<?php echo $key;?>-graph" class="demo-placeholder linegraph" style="width: 95%; height:270px;">
                                    </div>
                                    
                                        <div class="loader_small_overlay topLineChart" style="" id="<?php echo $key;?>-loader">
                                                 <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
                                            </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                               
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>

</div>