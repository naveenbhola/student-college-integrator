
<div class="col-md-6 col-sm-6 col-xs-12">
    <div class="x_panel tile fixed_height_320 overflow_hidden">
        <div class="x_title">
            <!-- id="<?php echo $splitType.'-heading';?>" -->
            <h2 id="<?php echo $splitType.$keyId.'-heading';?>"><?php echo $headings[$splitType]['heading'];?></h2>
           <!--  <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                    </ul>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
            </ul> -->
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <table class="" style="width:100%">
                <tr>
                   
                    <th style="width:37%;">
                        <p class = "" >Split View</p>
                    </th>
                    <th>
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                            <p class=""><?php echo $headings[$splitType]['typeHeading'];?></p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <p class="">%</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <p class="">Count</p>
                        </div>
                    </th>
                </tr>
                <tr>
                    <td>
                        <div id="<?php echo $splitType.$keyId.'-div';?>">
                        <canvas id="<?php echo $splitType.$keyId.'-graph';?>" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
                        </div>
                    </td>
                    <td>
                        <div style="height: 180px;" class="overflow_y">
                        <table class="tile_info" id="<?php echo $splitType.$keyId.'-info';?>">
                            
                        </table>
                    </div>
                    </td>
                </tr>
                 <tr>
                    <td>
                      </td>
                      <td>  
                        <div>
                        <table style="width: 100%; text-align: right;">
                            <tbody>
                                <tr>
                                        <td id="">
                                    <h4 class="totalDataCount" id="<?php echo $splitType.$keyId.'-count';?>"></h4>
                                </td>
                            </tr>
                        </tbody>
                </table>
            </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script>
var pieChartData = '<?php echo $splitData;?>';
var pieChartIndex = '<?php echo $splitIndex;?>';
var pieChartId = '<?php echo $splitType.$keyId;?>';
var isComapreHeading = '<?php echo $keyId;?>';
if(isComapreHeading == 0)
    $('#'+pieChartId+'-heading').text("<?php echo $headings[$splitType]['heading'];?>("+ startDate+" to "+endDate+')');
else if(isComapreHeading == 1)
    $('#'+pieChartId+'-heading').text("<?php echo $headings[$splitType]['heading'];?>("+ compare_startDate+" to "+compare_endDate+')');
pieChartData = JSON.parse(pieChartData);

pieChartIndex = pieChartIndex;
var totalResponses = '<?php echo $totalCount;?>';
var totalHeading = '<?php echo $headings[$splitType]['countHeading'];?>';
$('#'+pieChartId+'-count').text( totalHeading+ ' :'+totalResponses);
createPieChart(pieChartData,pieChartIndex,pieChartId);
</script>