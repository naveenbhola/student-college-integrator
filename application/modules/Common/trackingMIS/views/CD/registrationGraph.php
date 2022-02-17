<div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="dashboard_graph">

                            <div class="row x_title">
                                <div class="col-md-6">
                            <h2><?php echo $headings[$mainHeading]['heading'];?></h2>
                            </div>
                        </div>

                            <div class="row x_title" id="<?php echo $splitType;?>-div">
                                <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                                <div style="width: 100%;">
                                    <div id="<?php echo $splitType;?>-graph" class="demo-placeholder" style="width: 95%; height:270px;"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                               
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>

</div>
<script>
var linearChartData = '<?php echo $splitData;?>';
var compareLineChartData = '<?php echo $splitDataForCompare;?>';

if(linearChartData == '' && compareLineChartData == '')
{
 $('#'+linearChartId).html('<center style="padding-left:300px;padding-top:30px"><h2 padding-left=40px;>No result for selected Duration</h2></center>');  
 $('#'+linearChartId).height('100px');
}
else
{
   linearChartData = JSON.parse(linearChartData);
   if(compareLineChartData != '')
   {
    compareLineChartData = JSON.parse(compareLineChartData);
   }
	var linearChartId = '<?php echo $splitType;?>-graph';
    createLineChart(linearChartData,compareLineChartData,linearChartId);
}
</script>