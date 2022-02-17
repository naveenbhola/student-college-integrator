<?php
if($noDataFound){
echo '<td id="hidetr_2"/></td>';
}
else{
?>
<html>
<head>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
$j = $.noConflict();
</script>
<script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
</head>
<body>
<div id="salary-data-chart" class="alumni-graph" style="width:620px;float:left;" >
</div>

<?php if(isset($totalAvg) && $totalAvg!=''){ ?>
<div class="alumini-salary">
            <p>Average Alumni Salary* (INR)</p>
            <p class="font-18"><?=$totalAvg?> lakhs</p>
</div>
<div style="float: left; width: 100%; text-align: right; color: #999">
      *Average of annual salary of Alumni from 440 MBA Colleges
</div>
<?php } ?>

<div class="other-details-wrap clear-width dream-job-sec" style="margin: 16px;width:90%">
        <i class="common-sprite dream-job-icon"></i>
        <div class="mt13">
                <span>Have a dream Job? </span>
                <a class="job-orange-btn flRt" href="<?=SHIKSHA_HOME.'/mba/resources/mba-alumni-data'?>">Find colleges to help you get there <i class="common-sprite job-side-icon"></i></a>
        </div>
</div>

<script>
var chart_data = eval('(' + '<?php echo isset($chart)?addslashes($chart):0;?>' + ')');
var count_salary_slots = 0;
var max_value = 0;
var chartsalary;
var salaryoptions = {};
var slantedTextFlag = false;
function drawSalaryChart() {
if(document.getElementById('salary-data-chart')) {
	salary_data = new google.visualization.DataTable();
	salary_data.addColumn('string', 'Experience Bucket');
	salary_data.addColumn('number', 'Alumni Salary(in lakhs)');
	salary_data.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
	salary_data.addColumn({type: 'string', role: 'annotation'});
	//salary_data.addColumn('number', 'Industry Average');
	getSalaryDataForChart();
	//salary_data = getDataArrayForChart();
	var view = new google.visualization.DataView(salary_data);
	view.setColumns([0, 1, 1, 2]);
	//console.log(data);
	chartsalary = new google.visualization.ComboChart(document.getElementById('salary-data-chart'));
	var chart_final_data = [];
	salaryoptions = {
            height: 300,
            width: 620,
	  seriesType: "bars",
          
		series: {
		  0: {
		    type: 'bars',
		  },
		  1: {
		    type: 'line',
		    color: '#4a4a4a',
		    //lineWidth: 0,
		    
		    pointSize: 0,
			//visibleInLegend: false,
		    fontSize:13 ,
		  }
		},
            fontName:"Tahoma,Geneva,sans-serif",
            vAxis: {baselineColor: '#cacaca',minValue:0,maxValue:max_value,baseline: 0,gridlines:{color:'#eae7e7',count:5},titleTextStyle: {italic: false}, format: '#,###',},
            hAxis: {title: chart_data[0].Exp_Bucket + " years work experience",textStyle:{color:'#7c7c7c'},gridlines:{color: 'none'},titleTextStyle: {italic: false}, slantedText:slantedTextFlag},
            colors: ['#c4e066'],
            backgroundColor:'#fafafa',
            bar: {groupWidth: 20},
            chartArea : {left : 45, top : 15, width : "80%", height : "75%"},
            fontSize:12,
            axisTitlesPosition:'out',
            animation:{
                  duration: 1000,
                  easing: 'in',
                },
	    legend: {position: 'none'},
	    annotations: {
		    textStyle: {
		    fontSize:14,
		    color:'#000'
		   }
	   }
        };
    
	chartsalary.draw(salary_data, salaryoptions);
        
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

function getSalaryDataForChart() {    
    var maxValueOfBar = new Array();
    for(var i in chart_data) {		
	var expre = chart_data[i].Exp_Bucket + " years experience";
	var ctc = parseFloat(chart_data[i].AvgCTC);	
        ctc = Math.round(ctc * 100) / 100;
	var totalAvg = parseFloat(chart_data[i].totalAvg );
        if(typeof(chart_data[i].Exp_Bucket) !='undefined' && chart_data[i].Exp_Bucket) {
                //if(chart_data[i].Exp_Bucket == '2-5') {         
                max_value = ctc +1;
                maxValueOfBar[i] = max_value
                //}
                if(ctc==0.5){
			var newctc = 'Not available';
		}else{
			var newctc = ctc+' lakhs';
		}
		var institute_name = chart_data[i].institute_name;
                count_salary_slots++;
		if(institute_name.length>47){
		  slantedTextFlag = true;
		}
                if(ctc==0.5){
			salary_data.addRow([institute_name, ctc, 'Not available', newctc]);
		}else{
			salary_data.addRow([institute_name, ctc, 'Alumni Salary(in lakhs): '+ctc, newctc]);			
		}	
        }


    } 	
    var max = maxValueOfBar[0];
    var len = maxValueOfBar.length;
    for (var i = 1; i < len; i++) if (maxValueOfBar[i] > max) max = maxValueOfBar[i];
    max_value = max;
}
google.setOnLoadCallback(drawSalaryChart);
</script>
</body>
</html>
<?php
} 
?>
