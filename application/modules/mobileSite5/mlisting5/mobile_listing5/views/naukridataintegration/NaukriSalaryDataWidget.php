<?php if($salary_total_employee >30 || $total_naukri_employees > 30) {?>  
					<!-- Alumni Data Widget STARTS Here -->
					<section class="clearfix">

								<div class="alumini-title">Alumni employment stats</div>


								<div class="alumini-cont">

								<div style="border-bottom: 0px none; clear: left;font-size: 12px;margin-bottom: 15px;<?php echo ($pageType != 'mobileshortlist') ? 'padding-top: 10px;' : ''?>">
                                                                        Data based on resumes from <i class="sprite data-source" style="float:none;"></i> , India's No. 1 Job Site
                                                                </div>
					  
                               <?php if($salary_data_count>0 && $salary_total_employee >30):?> 
									<div class="alumini-sub-title">Annual salary details of <?php echo $salary_total_employee;?> alumni of this course</div>

									<div class="alumini-sal-stats">

										<p>Average salary(INR) by work experience</p>
										<div id="salary-data-chart" class="alunimi-graph">
										</div>

									</div>
                               <?php endif;?>
							   
							   
                                <?php if(count($naukri_specializations)>0 && $total_naukri_employees > 30 /*&& (($placement_data_count>0 || $industry_count>0))*/):?>

									<div class="alumini-sub-title">Employment details of <?php echo $total_naukri_employees;?> alumni of this course</div>



									<div class="alumini-emp-detail">

										<div class="sorting-spl">

											<label>View By Specialization</label>
											
											


															<a href="#alumniDataSpecialization" data-inline="true" data-rel="dialog" data-transition="slide" class="selectbox" onClick="isOverlayOpen=true;" style="height: 25px;min-height: inherit;width: 135px;">
															<p style="height: 6px;padding: 6px 4px 10px 6px;overflow:hidden;width:110px;">
																	<span>
																	<?php
																	if($selected_naukri_splzn) {
																	if($selected_naukri_splzn == "Marketing") {
																		echo "Sales & Marketing";
																	}
																	elseif($selected_naukri_splzn == "HR/Industrial Relations") {
																		echo "Human Resources";
																	}
																	elseif($selected_naukri_splzn == "Information Technology") {
																		echo "IT";
																	}
																	elseif($selected_naukri_splzn == "All Specialization") {
																	    echo "All Specializations";
																	}
																	else {
																		  echo $selected_naukri_splzn;
																	}
																	}
																	else {
																	echo "All Specializations";
																	}
																	?>
																	</span>
																	<i class="icon-select2"></i>
															</p>
															</a> 
 
															<div class="special-dropdown-layer" style="display:none;z-index:1;" id="special-dropdown">
																	<ul class="stream-list">
																			<li onclick="getNaukriIntegrationWidget('All Specialization',5,5);" style="cursor:pointer;">All Specializations</li>
																			<?php foreach($naukri_specializations as $splz):
																			if($splz == "Systems" || $splz == "Other Management") { continue; }
																			elseif($splz == "Marketing") {?>
																			<li onclick="getNaukriIntegrationWidget('<?php echo $splz;?>',5,5);" style="cursor:pointer;">Sales & Marketing</li>
																			<?php } elseif($splz == "HR/Industrial Relations") {?>
																			<li onclick="getNaukriIntegrationWidget('<?php echo $splz;?>',5,5);" style="cursor:pointer;">Human Resources</li>
																			<?php } elseif($splz == "Information Technology") {?>
																			<li onclick="getNaukriIntegrationWidget('<?php echo $splz;?>',5,5);" style="cursor:pointer;">IT</li>
																			<?php } else {?>
																			<li onclick="getNaukriIntegrationWidget('<?php echo $splz;?>',5,5);" style="cursor:pointer;"><?php echo $splz;?></li>																		  
																			<?php } endforeach;
																			if(in_array("Other Management", $naukri_specializations)) { ?>
																			<li onclick="getNaukriIntegrationWidget('Other Management',5,5);" style="cursor:pointer;">Other Management</li>
																			<?php } ?>
																	</ul>
															</div>

											

														</div>                                    

											</div>  
									
									<?php if($placement_data_count>0): ?>
									<div class="alumini-comp-details">

										<p>Companies they work for</p>
										<div id="placement_data" class="operations-list" style="margin-top:15px;margin-left:10px;">
										</div>
									</div>
									<?php endif;?>
									
									<?php if($industry_count>0):?>
									<div class="alumini-comp-details">
										<p>Business functions they are in</p>
										<div class="operations-list" id="functional_data" style="margin-top:15px;margin-left:10px;">
										</div>

									</div>
									<?php endif; ?>
				    
									<?php if(($placement_comp_count>5 && $no_of_companies <10) || ($industry_fa_count>5 && $no_of_functional <10)) {?>
									  <a onclick="getNaukriIntegrationWidget(universal_selected_naukri_splz,10,10);" href="javascript:void(0);"class="flRt" style="margin-bottom: 10px;margin-right: 5px;">See more &gt;</a>
									<?php } ?>

									<?php endif; ?>
									
								</div>

							</section>
<?php 

	if($pageType != 'mobileshortlist' && $pageType != 'mobileListingCoursepage')
		$this->load->view('mNaukriTool5/widgets/careerCompassWidget',array('pageName'=>'COURSE_LISTING')); 
?>
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

	var expre = chart_data[i].Exp_Bucket + " years";
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

var htmlSpec = $('#special-dropdown').html();
$('#specLayer').html(htmlSpec);
</script>
<?php } ?>