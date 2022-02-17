<div id="content-wrapper">
    <div class="wrapperFxd">
    	<div id="content-child-wrap">
	
        	<div id="management-wrapper">
                
                <!--Course Content starts here-->
                <div id="management-content-wrap">
                    <!--Course Left Col Starts here-->
                    <div id="management-left">
                    
                   <!-- Alumni Data Widget STARTS Here -->
                    <div class="alumni-data-widget clear-width">
                   	  <div class="alumni-head clear-width">
                            <h2 class="flLt">Alumni Employment Stats</h2>
                       	  	<p class="flRt">Data Source : <i class="common-bg data-source-logo"></i></p>
                        </div>
                        
                      <div class="alumni-details clear-width">
                            	<h4>Annual Salary Details of 200 alumni of this course</h4>
                       			<h3 class="alumni-sub-title">Average Salary By Work Experience</h3>
                        		<div id="salary-data-chart" class="alumni-graph">
                                	<img src="public/images/alumini-stats-image.png" width="318" height="218" alt="alumini stats" />
                                </div>
                                <h4>Employment Details of 200 alumni of this course</h4>
                                <div class="specialization-box clear-width">
                                	<div class="specialization-opt clear-width">
                                        <label class="flLt">View By Specialization</label>
                                        <div class="special-dropdown flLt "><p>All Specialization</p><i class="common-bg dropdwn-arrow"></i>
                                    		<div class="special-dropdown-layer" style="display:none;">
                                                <ul>
                                                    <li><a href="#">All Specialization</a></li>
                                                    <li><a href="#">Marketing</a></li>
                                                    <li><a href="#">Finance</a></li>
                                                    <li><a href="#">HR</a></li>
                                                    <li><a href="#">Advertising/Mass Communication</a></li>
                                                    <li><a href="#">Operations</a></li>
                                                    <li><a href="#">Information Technology</a></li>
                                                    <li><a href="#">Systems</a></li>
                                                    <li><a href="#">International Business</a></li>                                         
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                
                                	<div class="specialization-details clear-width">
                                        <div class="specialization-cols flLt">
                                            <h3>Companies they work for</h3>
                                            <ul class="operations-list">
                                                <li class="clear-width">
                                                    <label>HCL Technologies</label>
                                                    <div class="specialization-bar flLt"></div>
                                                    <span class="bar-caption">27</span>
                                                </li>
                                                <li class="clear-width">
                                                    <label>Tata Consultancy Services</label>
                                                    <div class="specialization-bar flLt" style="width:40%"></div>
                                                    <span class="bar-caption">23</span>
                                                </li>
                                                <li class="clear-width">
                                                    <label>ICICI Bank</label>
                                                    <div class="specialization-bar flLt" style="width:30%"></div>
                                                    <span class="bar-caption">19</span>
                                                </li>
                                                <li class="clear-width">
                                                    <label>Infosys Technologies</label>
                                                    <div class="specialization-bar flLt" style="width:20%"></div>
                                                    <span class="bar-caption">18</span>
                                                </li>
                                                <li class="clear-width">
                                                    <label>IBM Global Services</label>
                                                    <div class="specialization-bar flLt" style="width:10%"></div>
                                                    <span class="bar-caption">17</span>
                                                </li>
                                            </ul>
                                            <a href="#">See more &gt;</a>
                                        </div>
                                        <div class="specialization-cols flRt">
                                            <h3>Business Functions they are in</h3>
                                            <ul class="operations-list">
                                                <li class="clear-width">
                                                    <label>HCL Technologies</label>
                                                    <div class="specialization-bar2 flLt"></div>
                                                    <span class="bar-caption">27</span>
                                                </li>
                                                <li class="clear-width">
                                                    <label>Tata Consultancy Services</label>
                                                    <div class="specialization-bar2 flLt" style="width:40%"></div>
                                                    <span class="bar-caption">23</span>
                                                </li>
                                                <li class="clear-width">
                                                    <label>ICICI Bank</label>
                                                    <div class="specialization-bar2 flLt" style="width:30%"></div>
                                                    <span class="bar-caption">19</span>
                                                </li>
                                                <li class="clear-width">
                                                    <label>Infosys Technologies</label>
                                                    <div class="specialization-bar2 flLt" style="width:20%"></div>
                                                    <span class="bar-caption">18</span>
                                                </li>
                                                <li class="clear-width">
                                                    <label>IBM Global Services</label>
                                                    <div class="specialization-bar2 flLt" style="width:10%"></div>
                                                    <span class="bar-caption">17</span>
                                                </li>
                                            </ul>
                                            <a href="#">See more &gt;</a>
                                        </div>
                                    </div>
                                </div>
                        	</div>
                        </div>
                    <!-- Alumni Data Widget ENDS Here -->
                               
                    </div>
                    <!--Course Left Col Ends here-->
                    
               </div>
               <!--Course content ends here-->
            </div>
        
        </div>
    </div>
</div>

<script type="text/javascript" src="//www.google.com/jsapi"></script>
<script type="text/javascript">
    
var noOfBuckets = <?php echo count($widgetData); ?>;
var chart_data = '<?php echo $chart;?>';    
google.load('visualization', '1', {packages: ['corechart']});
google.setOnLoadCallback(drawChart);

function drawChart()
{
	var chartDivId = 'salary-data-chart';
	var graphType = 'Column Chart';
	var bucketIntervals;
	
        var chartDataArray = getDataArrayForChart(chartDivId, graphType, bucketIntervals);
        if (checkDataAvailable(chartDivId)) {
            drawVisualization(chartDivId, chartDataArray);
        }
        
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
}

function drawVisualization(chartDivId, chartDataArray) {
    var options = setChartOptions(chartDivId);
    
    chartDataTables[chartDivId] = google.visualization.arrayToDataTable(chartDataArray);
    charts[chartDivId].draw(chartDataTables[chartDivId], options);
}

function setChartOptions(chart) {
    var hAxisTitle;
    var vAxisTitle;
    var isStacked;
    var colors;
    
    colors = ['#C0F57C'];
    hAxisTitle = 'Experience Bucket';
    vAxisTitle = 'Average Salary'
    isStacked = false;
    
    var options =
    {
            height    : 250,
            width     : 410,
            colors    : colors,
            chartArea : {left : 45, top : 15, width : "80%", height : "75%"},
            legend    : {position : 'none'},
            hAxis     : {title : hAxisTitle, titleTextStyle : {fontsize : 20}},
            vAxis     : {title : vAxisTitle, titleTextStyle : {fontsize : 20}, minValue : 0},
            animation : {duration : 750, easing : 'inAndOut',},
            isStacked : isStacked
    }
    
    return options;
}

function getDataArrayForChart(widget, graphType, range, dateIntervals, dataForIntervals) {
    var chartLables = getChartLabels(graphType, range, dateIntervals);
    var data = new Array();
    var labelArray;
    
    data.push(['Experience Bucket', 'Average Salary']);
    return data;
}

function checkDataAvailable(widget, dataForIntervals) {
    var chartDiv = document.getElementById(widget);
    var chartNoDataDiv = document.getElementById(widget + '_text');
    var dataAvailableFlag = false;
    for (var index = 0; index < dataForIntervals.length; index++) {
	for (var data = 0; data < dataForIntervals[index].length; data++) {
	    if (dataForIntervals[index][data] > 0) {
		dataAvailableFlag = true;
	    }
	}
    }
    
    if (dataAvailableFlag == false) {
	chartDiv.style.display = 'none';
	chartNoDataDiv.style.display = '';
    }
    else if (dataAvailableFlag == true) {
	chartDiv.style.display = '';
	chartNoDataDiv.style.display = 'none';
    }
    
    return dataAvailableFlag;
}
</script>