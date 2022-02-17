<?php 
$this->load->config('trackingMIS/cdTrackingMISConfig');
$topTiles = $this->config->item('topTiles');
$headings = $this->config->item("charts");
$helperText = $this->config->item("helperText");
?>
<br/>
<div class ="row">
	<?php 
	$i = 0;
	$displayTiles = $topTiles[$page];
	foreach ($displayTiles as $key => $value) {?>
		<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <?php if($i % 6 != 0) {?>
        <div class="left"></div>
        <?php }?>
        <div class="right">
            <span class="count_top"><i class="fa fa-user"></i><?php if($key == 'avgResponse'){?>
                <a style = "text-decoration:none" title = "<?php echo $value;?>">&nbsp<?php echo $value;?></a>
                <?php } else {?><a style = "cursor: pointer" title = "<?php echo $value;?>" onclick="getLinearGraph('<?php echo $graphUrl;?>','<?php echo $key.'-graph';?>')">&nbsp<?php echo $value;?></a><?php }?></span>
            <div class="count font_18" id = "<?php echo $key.'count';?>"></div>
            <div class="loader_small_overlay cdLoader" style="" id="<?php echo $key.'loader';?>">
                <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
            </div>
            <!-- <span class="count_bottom"><i class="green">4% </i> From last Week</span> -->
        </div>   
        <input type="hidden" name="graphType" value="<?php echo $value;?>" id="<?php echo $key.'-graph';?>"> 
    </div>
	<?php $i++;}?>
	</div>
    <!-----
-->
<div class="row" id="linechartBoard" style="display:none">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="dashboard_graph">

                            <div class="row x_title">
                                <div class="col-md-6">
                            <h2 id="linechartHeading"></h2>
                            </div>
                        </div>

                            <div class="row x_title" id="linechart-div">
                                <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                                <div style="width: 100%;">
                                    <div id="linechart-graph" class="demo-placeholder linegraph" style="width: 95%; height:270px;">
                                    </div>
                                    
                                        <div class="loader_small_overlay topLineChart" style="" id="linechart-loader">
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
<!--
-->
    <div class="row">
       <?php 
            $i =1;
          
            $size = sizeof($headings[$page]['PIE_CHART']);
            $increment = $size;
            foreach ($headings[$page]['PIE_CHART'] as $key => $value) 
            {
              $data = array('number'=>$i,
                            'key' => $key,
                            'value'=>$value,
                            'helperText' => $helperText[$page]);
              $this->load->view('trackingMIS/CD/piechart',$data);
              $data ='';
              $data = array('number'=>($i+1),
                            'key' => $key,
                            'value'=>$value,
                            'helperText' => $helperText[$page]);
              $this->load->view('trackingMIS/CD/piechart',$data);
              $i = 1;  
            }
            //---------------------
        ?> 
    </div>
    <?php if($page == 'byInstitute' || $page == 'byUniversity' ) {?>
    <div class="row" id="dataTableDiv" style="display:block">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" id="dataTable">
                <div class="x_title">
                    <h2 id="dataTableHeading">Respondents Data</h2>
                    <div class="clearfix"></div>
                    <!---->
             
                <!---->
                </div>

                <div class="loader_small_overlay tableLoader" style="" id="dataTable-loader">
                                                 <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
                </div>
                <div class="x_content">
                    <table id="example" class="table table-striped responsive-utilities jambo_table" aria-describedby="example_info">


                    </table>
              </div>
            </div>  
</div>
</div>
<?php }?>
<div class="row" id="courseTableDiv" style="display:block">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" id="courseTableData">
                <div class="x_title">
                    <h2 id="courseTableHeading">Responses Delivery Course Wise</h2>
                    
                    <div class="clearfix"></div>
                </div>
                <div class="loader_small_overlay tableLoader" style="" id="courseTable-loader">
                                                 <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
                </div>
                <div class="x_content">
                    <table id="courseTable" class="table table-striped responsive-utilities jambo_table" aria-describedby="example_info">


                    </table>
              </div>
            </div>  
</div>
</div>
<div class="row" id="instituteTableDiv" style="display:block">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" id="instituteTableData">
                <div class="x_title">
                    <h2 id="instituteTableHeading">Responses Delivery Institute Wise</h2>
                    
                    <div class="clearfix"></div>
                </div>
                <div class="loader_small_overlay tableLoader" style="background: rgba(0, 0, 0, 0.18)  repeat scroll 0% 0%;" id="instituteTable-loader">
                                                 <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
                </div>
                <div class="x_content">
                    <table id="instituteTable" class="table table-striped responsive-utilities jambo_table" aria-describedby="example_info">


                    </table>
              </div>
            </div>  
</div>
</div>
<?php if($page == 'byInstitute' || $page == 'byUniversity') {?>
<div class="row" id="leadTableDiv" style="display:block">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" id="instituteTableData">
                <div class="x_title">
                    <h2 id="leadTableHeading">Lead Delivery Client Wise</h2>
                    
                    <div class="clearfix"></div>
                </div>
                <div class="loader_small_overlay tableLoader" style="" id="leadTable-loader">
                                                 <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
                </div>
                <div class="x_content">
                    <table id="leadDataTable" class="table table-striped responsive-utilities jambo_table" aria-describedby="example_info">


                    </table>
              </div>
            </div>  
</div>
</div>
<div class ="row" id ="mapdiv" style="">
        
        <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Unique Users<small>Top 20 Cities geo-presentation</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="loader_small_overlay" style="" id="mapdivLoader">
                <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
            </div>
                                    <div class="x_content">
                                        <div class="dashboard-widget-content">
                                            <div id="world-map-gdp" class="col-md-8 col-sm-12 col-xs-12" style="height:500px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
          
</div>
<?php }?>
<script type="text/javascript">
var graphUrl = '<?php echo $graphUrl;?>';
function showmsg(id)
{
    alert($('#'+id).val());
}

$(document).on('click','#dateList li a',function(){
   
   var str = $(this).text();
  $(this).parents(".dropdown").find('#dateButton').html( str+ '&nbsp&nbsp <span class="caret"></span>');
  //$(this).parents(".dropdown").find('#CourseIdButton').val($(this).data('value'));
});
</script>