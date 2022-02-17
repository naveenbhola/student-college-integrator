<?php if($salary_total_employee >30) {?>  
		   <!-- Alumni Data Widget STARTS Here -->
                    <div id="naukri-charts" class="alumni-data-widget clear-width">
                   	  <div class="alumni-head clear-width">
                            <h2 class="flLt">Alumni employment stats</h2>
                       	  	<p class="flRt">Data Source : <i class="data-source-logo"></i></p>
                        </div>
                        
                      <div class="alumni-details clear-width">
                               <?php if($salary_data_count>0 && $salary_total_employee >30):?> 
                                    <h4>Annual salary details of <?php echo $salary_total_employee;?> alumni of this course</h4>
                                    <h3 class="alumni-sub-title">Average salary(INR) by work experience</h3>
                                        <?php if($isloggedin): ?>
                                        <div id="salary-data-chart" class="alumni-graph">
                                		<!--img src="public/images/alumini-stats-image.png" width="318" height="218" alt="alumini stats" /-->
                                	</div>
                                    <?php else:?>
                                        <div id="salary-data-chart" class="alumni-graph" style="display: none;"></div>
                                        <div style="background:url(/public/images/disabled-graph.jpg) 0 0 no-repeat; width:700px; height:296px; position:relative">
                                            <a class="manage-contact-btn orange-clr" style="padding:7px 10px; font-size:16px !important; margin:135px 0 0 40px !important" href="javascript:void(0);" onclick="makeResponseLogin(instituteIdForTracking, course_id);"><i class="sprite-bg reg-icon"></i>Register to view Alumni salary details. Registration is fast and free!</a>
                                        </div>
                                    <?php endif;?>
                                    
                               <?php endif;?>
                        	</div>

                                <div class="other-details-wrap clear-width dream-job-sec" style="width: 96.5%;">
                                        <i class="common-sprite dream-job-icon"></i>
                                        <div class="mt13">
                                                <span>Have a dream Job? </span>
                                                <a class="job-orange-btn flRt" href="<?=SHIKSHA_HOME.'/mba/resources/mba-alumni-data'?>">Find colleges to help you get there <i class="common-sprite job-side-icon"></i></a>
                                        </div>
                                </div>

                        </div>
                    <!-- Alumni Data Widget ENDS Here -->                                                  
<script type="text/javascript">    
    jQuery("#naukri_widget_data").hide();
var noOfBuckets = '<?php echo count($widgetData); ?>';
var count_salary_slots = 0;
var chart_data = eval('(' + '<?php echo isset($chart)?$chart:0;?>' + ')'); 
var placement_data = eval('(' + '<?php echo isset($placement_data)?$placement_data:0;?>' + ')'); 
var industry_data = eval('(' + '<?php echo isset($industry)?$industry:0;?>' + ')');  
//var complete_naukri_data = '<?php echo $complete_naukri_data;?>';
var selected_splz  = '<?php echo $selected_naukri_splzn;?>';	  
var max_value = 0;
google.load("visualization", "1", {packages:["corechart"],"callback": drawChart});
//google.setOnLoadCallback(drawChart);
var salary_data = null;
var placement_chart_data = null;
var industry_chart_data = null;
var placement_data_count = parseInt('<?php echo isset($placement_data_count)?$placement_data_count:0;?>',10);
var industry_count = parseInt('<?php echo isset($industry_count)?$industry_count:0;?>');

var compaiesoptions = {
  height: (200*placement_data_count)/5,
  width: 300,
  bar: {groupWidth: "60%"},
  legend: { position: "none" },
  vAxis: {textPosition: 'none',color:'none',gridlines:{color: 'none', count: 0}},
  hAxis: {baselineColor: 'white', textPosition: 'none' ,gridlines:{color: 'none', count: 0}},
  axisFontSize : 0,
  axisTitlesPosition:'out',
  fontSize:12,
  fontName:"Tahoma,Geneva,sans-serif",
  chartArea:{left:0,top:0,width:"80%",height:"100%",fontSize:12,color: 'black'},
   annotations: {
    textStyle: {
    fontSize:14,
    color:'#656565'
   }
  },
  colors: ['#7CC3E1', '#7CC3E1', '#7CC3E1', '#7CC3E1', '#7CC3E1']
};

var compaiesfunctionoptions = {
  height: (200*industry_count)/5,
  width: 300,
  bar: {groupWidth: "60%"},
  legend: { position: "none" },
  vAxis: {textPosition: 'none',color:'none',gridlines:{color: 'none', count: 0}},
  hAxis: {baselineColor: 'white', textPosition: 'none' ,gridlines:{color: 'none', count: 0}},
  axisFontSize : 0,
  axisTitlesPosition:'out',
  fontSize:12,
  fontName:"Tahoma,Geneva,sans-serif",
  chartArea:{left:0,top:0,width:"80%",height:"100%",fontSize:14,color: 'black'},
  annotations: {
    textStyle: {    
    fontSize:14,
    color:'#656565'
   }
  },
  colors: ['#DEC897', '#DEC897', '#DEC897', '#DEC897', '#DEC897'] 
  
};

function drawSalaryChart() {

if(document.getElementById('salary-data-chart')) {
	var chartsalary = new google.visualization.ColumnChart(document.getElementById('salary-data-chart'));
	salary_data = new google.visualization.DataTable();
	salary_data.addColumn('string', 'Experience Bucket');
	salary_data.addColumn('number', 'Average Salary(in lakhs)');
	salary_data.addColumn({type: 'string', role: 'annotation'});
	getSalaryDataForChart();
	var view = new google.visualization.DataView(salary_data);
	view.setColumns([0, 1, 1, 2]);
	var chart_final_data = [];	
        var salaryoptions = {
            height: 300,
            width: 300,
              series: {
                0: {
                  type: 'bars'
                },
                1: {
                  type: 'line',
                  color: '#4a4a4a',
                  lineWidth: 0,
                  pointSize: 0,
                  visibleInLegend: false,
                  fontSize:13 
                }
              },	
            fontName:"Tahoma,Geneva,sans-serif",
            vAxis: {baselineColor: '#cacaca',minValue:0,maxValue:max_value,baseline: 0,gridlines:{color: 'none', count:0}},
            hAxis: { textStyle:{color:'#7c7c7c'},gridlines:{color: 'none'}},
            colors: ['#c4e066', '#c4e066', '#c4e066', '#c4e066', '#c4e066'], 
            backgroundColor:'white',
            bar: {groupWidth: "30%"},
            chartArea:{left:50,top:0,bottom:50,width:"80%",height:"80%"},	 		
            fontSize:12,
            axisTitlesPosition:'out',
            animation:{
                  duration: 1000,
                  easing: 'in',
                }
        };
        
        google.visualization.events.addListener(chartsalary, 'ready',
        function() {
          jQuery("#naukri_widget_data").show();
        });
    
	chartsalary.draw(view, salaryoptions);
        
        /*hack to remove stroke from graph  "this will remove stroke from all tags with id rect"*/
        var rectTags = document.getElementsByTagName("rect");
        for (var i = 0; i < rectTags.length; i++) {
            if (rectTags[i].hasAttribute("width")) {
                var width = rectTags[i].getAttribute("width");
                if (parseInt(width) == 1) {
                    rectTags[i].setAttribute("width", 0);
                }
            }
        }
}

}

function drawPlacementChart() {


if(document.getElementById('placement_data')) {
	var chart_companies = new google.visualization.BarChart(document.getElementById('placement_data'));
	placement_chart_data = getPlacementDataForChart();
	google.visualization.events.addListener(chart_companies, 'ready',
        function() {
          jQuery("#naukri_widget_data").show();
        });
	chart_companies.draw(placement_chart_data,compaiesoptions);
}

}

function drawBusinessChart() {

if(document.getElementById('functional_data')) {
	var chart_functional = new google.visualization.BarChart(document.getElementById('functional_data'));
	industry_chart_data = getFunctionalDataForChart();
	google.visualization.events.addListener(chart_functional, 'ready',
        function() {
          jQuery("#naukri_widget_data").show();
        });
	chart_functional.draw(industry_chart_data,compaiesfunctionoptions);
}	

}
function drawChart() {

drawSalaryChart();
drawPlacementChart();
drawBusinessChart();

}

function getSalaryDataForChart() {    

    for(var i in chart_data) {		

  var expre = (chart_data[i].Exp_Bucket == '5+') ? '5-8' : chart_data[i].Exp_Bucket;
  expre = expre + " years";

	var ctc = parseFloat(chart_data[i].AvgCTC);	
        ctc = Math.round(ctc * 100) / 100;
        if(typeof(chart_data[i].Exp_Bucket) !='undefined' && chart_data[i].Exp_Bucket) {

                if(chart_data[i].Exp_Bucket == '5+') {
			max_value = ctc +1;
		} else if(chart_data[i].Exp_Bucket == '2-5') {		
			max_value = ctc +1;
		} else if(chart_data[i].Exp_Bucket == '0-2') {
			max_value = ctc +1;
		}
                count_salary_slots++;
    		salary_data.addRow([expre, ctc, ctc+' lakhs']);	
        }

    } 	
    max_value++;
}

function getPlacementDataForChart() {


     var data = new Array();
     data.push(["Company Name", 'Number of employees',{ role: 'annotation'}]);	
     for(var i in placement_data) {		

	var comp_name = placement_data[i].comp_name;
	var no_of_emps = placement_data[i].no_of_emps;	
        if(typeof(comp_name) !='undefined' && comp_name && typeof(no_of_emps) !='undefined' && no_of_emps) {		
                data.push([comp_name, 0,comp_name]);
    		data.push([comp_name, no_of_emps,no_of_emps]);                		
        }

     }	
   
     return google.visualization.arrayToDataTable(data);
   
}

function getFunctionalDataForChart() {

     var data = new Array();
     data.push(["Functional area", 'Number of employees',{ role: 'annotation' }]);	
     for(var i in industry_data) {		
         
	var comp_name = industry_data[i].industry;
	var no_of_emps = industry_data[i].no_of_emps;	        
        if(typeof(comp_name) !='undefined' && comp_name && typeof(no_of_emps) !='undefined' && no_of_emps) {
                data.push([comp_name, 0,comp_name]);
    		data.push([comp_name, no_of_emps,no_of_emps]);		
        }

     }	
   
     return google.visualization.arrayToDataTable(data);
   
}
</script>
<?php } ?>
