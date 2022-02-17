
<div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="dashboard_graph">

                            <div class="row x_title">
                                <div class="col-md-6">
                            <h2><?php echo $headings[$splitType]['heading'].'  '.$resultCount;?></h2>
                            </div>
                            <div class="col-mod-9">
                            <p id="showTitle"></p>
                            </div>
                            <div class="col-mod-9">
                                <h2 id="<?php echo $splitType?>-count"></h2>
                                
                                    <button class="btn btn-default source" id="<?php echo $splitType;?>-button" style="display:none;float:right;" onclick="showLineargraph('<?php echo $splitType;?>'+'-div','<?php echo $splitType;?>'+'-button')">show Graph</button>
                            </div>
                        </div>

                            <div class="row x_title" id="<?php echo $splitType;?>-div" style="display:none">
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

visibleGraphButton('<?php echo $resultCount;?>','<?php echo $splitType;?>'+'-button');
//$('#<?php echo $splitType;?>'+'-count').text('Total Count:'+<?php if(isset($resultCount)) {echo $resultCount;} else echo 0;?>);

var linearChartData = '<?php echo $splitData;?>';
var compareLineChartData = '<?php echo $splitDataForCompare;?>';
linearChartData = JSON.parse(linearChartData);
if(compareLineChartData !='')
    compareLineChartData = JSON.parse(compareLineChartData);
var linearChartId = '<?php echo $splitType;?>'+'-graph';

createLineChart(linearChartData,compareLineChartData,linearChartId);
function showLineargraph(linearChartDivId,button)
{
    $('#'+linearChartDivId).slideToggle("slow");
    //$(selector).slideToggle("milliseconds",1000,callback)
    var al = $('#'+button).text();
    if( al == 'show Graph')
    {
        $('#'+button).text('hide Graph');
    }
    else if(al == 'hide Graph')
    {
        $('#'+button).text('show Graph');
    }
    
}
</script>