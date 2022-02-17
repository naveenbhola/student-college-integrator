<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <!--  <div class="dashboard_graph">  -->
            
        <div id="showView">
            <div style="float:left" >
                <h6>
                    <div class='dateRangeLineChart'></div>
                    <div class='lineChartHeading'>&nbsp&nbsp&nbsp&nbsp<span class="square"></span >&nbsp&nbsp<span id="line_heading"><?php echo $pageDetails['LINE_CHART']['heading']; ?></span></div>
                </h6>
                <h6>
                    <div class='dateRangeLineChart1'></div>
                    <div class='lineChartHeading1'>&nbsp&nbsp&nbsp&nbsp<span class="square1"></span>&nbsp&nbsp<span id="line_heading1"><?php echo $pageDetails['LINE_CHART']['heading']; ?></span></div>
                </h6>
            </div>
            <div style="float:right">
                <!--       -->
                <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-default fixed_width_100 bgcolor" type="button" id="daily" >
                            Day
                        </button>
                    </div>
                   <!--  <input type="hidden" name="courseLevel" value='0'/> -->
                </div>

                <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-default  fixed_width_100" type="button" id="weekly" >
                            Week
                        </button>
                        
                    </div>
                   <!--  <input type="hidden" name="courseLevel" value='0'/> -->
                </div>

                <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-default fixed_width_100" type="button" id="monthly" >
                            Month
                        </button>
                        
                    </div>
                    <!-- <input type="hidden" name="courseLevel" value='0'/> -->
                </div>
            <!--       -->
            </div>
        </div>
        <br><br><br>
        <div class="col-md-9 col-sm-9 col-xs-12" style="width:100%">

            <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
            <div style="width: 90%;" id='lineChartDiv'>
                <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12 bg-transparent">
        <div class="clearfix"></div>
        <!--   </div> -->
    </div>
</div>
<div id="message" class="message" >
<h2>No record found for selected criteria.</h2>
</div>

