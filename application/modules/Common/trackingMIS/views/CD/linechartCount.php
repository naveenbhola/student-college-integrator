<div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="dashboard_graph">

                            <div class="row x_title">
                                <div class="col-md-6">
                            <h2><?php echo $value['heading'];?></h2>
                            <h2 id="<?php echo $key.'-count';?>"></h2>
                            </div>
                            <div class="col-mod-9">
                            <p id="showTitle"></p>
                            </div>
                            <div class="col-mod-9">
                                <h2 id="<?php echo $key?>-count"></h2>
                                
                                    <button class="btn btn-default source" id="<?php echo $key;?>-button" style="display:none;float:right;" onclick="showLineargraph('<?php echo $key;?>'+'-div','<?php echo $key;?>'+'-button')">show Graph</button>
                            </div>
                        </div>

                            <div class="row x_title linechartDiv" id="<?php echo $key;?>-div" style=""><!-- display:none -->
                                <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                                <div style="width: 100%;">
                                    <div id="<?php echo $key;?>-graph" class="demo-placeholder linegraph" style="width: 95%; height:270px;">
                                    </div>
                                            <div class="loader_small_overlay linechartCount" style="" id="<?php echo $key;?>-loader">
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
<script type="text/javascript">
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