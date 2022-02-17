                    <div class="col-md-6 col-sm-6 col-xs-12 AppLineChart">
                        <div class="dashboard_graph">

                            <div class="row x_title">
                                <div class="col-md-6">
                            <h2 id=""><?php echo $heading;?><span id="<?php echo $key.'-count';?>"> </span></h2>
                            <h2 id="">
                            </div>
                        </div>

                            <div class="row x_title" id="<?php echo $key.'-div'?>">
                                <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                                <div style="width: 100%;">
                                    <div id="<?php echo $key.'-graph';?>" class="demo-placeholder linegraph" style="width: 95%; height:270px;">
                                    </div>
                                    
                                        <div class="loader_small_overlay topLineChart" style="display:none" id="linechart-loader">
                                                 <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
                                            </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                               
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>

<script>

var linearChartData = '<?php echo $data;?>';
linearChartData = JSON.parse(linearChartData);
var linearChartId = '<?php echo $key;?>-graph';
var count = '<?php echo $count?>';
createLineChart(linearChartData,linearChartId);
var countId = '<?php echo $key;?>-count';
//console.log(count);
showCount(countId,count);
</script>
