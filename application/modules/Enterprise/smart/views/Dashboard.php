<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}
?>
<script>isSmartAbroad = <?php echo $_COOKIE['SMARTInterfaceMode'] == 'Abroad' ? '1' : '0'; ?>;</script>

<div id="content-child-wrap">
    <div id="smart-content">
    
	<?php
	if($_COOKIE['SMARTDualInterface'] == 'Yes') {
	?>
	<h2 style='float:left;'>Welcome <strong><?php echo trim($dynamicTitle); ?> </strong></h2>
	
	<div style='float:right; margin-right: 0px; margin-bottom: 20px;'>
		<?php if($_COOKIE['SMARTInterfaceMode'] == 'National') { ?>
			<a href='/smart/SmartMis/changeSMARTInterfaceMode/Abroad/dashboard' style='display: block; float:right; padding:10px; background:#f0f0f0; border:0px solid #ccc; border-left:none;'>Abroad</a>
			<span style='display: block; float:right; padding:10px; background:#4175F0; color:#fff; border:0px solid #ccc;'>
				National
			</span>
		<?php } else { ?>		
			<span style='display: block; float:right; padding:10px; background:#4175F0; color:#fff; border:0px solid #ccc;'>
				Abroad
			</span>
			<a href='/smart/SmartMis/changeSMARTInterfaceMode/National/dashboard' style='display: block; float:right; padding:10px; background:#f0f0f0; border:0px solid #ccc; border-left:none;'>National</a>
		<?php } ?>
		
	</div>
	<div style='clear:both;'></div>
	<?php } else { ?>
	<h2>Welcome <strong><?php echo trim($dynamicTitle); ?> </strong></h2>
	<?php } ?>
	<div id="smart-enterprise">
	    <div class="ent-box flLt">
		<div class="ent-content" style="height:485px;">
		    <?php echo $responseDropdowns; ?>
		    <?php echo $responseGraphBox; ?>
		</div>
	    </div>
		
		<?php if($_COOKIE['SMARTInterfaceMode'] == 'National' && false) { ?>
	    <div class="ent-box flRt">
		<div class="ent-content" style="height:485px;">
		    <?php echo $activityDropdowns; ?>
		    <?php echo $activityGraphBox; ?>
		</div>
	    </div>
		<?php } else { ?>
		<div class="ent-box flRt">
		<div class="ent-content" style="height:485px;">
		    <?php echo $creditDropdowns; ?>
		    <?php echo $creditDateBox; ?>
		</div>
	    </div>
		<?php } ?>
		
	    <div class="clearFix"></div>
		
		
		<?php if($_COOKIE['SMARTInterfaceMode'] == 'National') { ?>
			
	    <div class="ent-box flLt" style="display:none;">
		<?php
		    if (empty($salesUser)) {
		?>
		<div class="ent-content" style="height:340px;">
		<?php
		    }
		    else {
		?>
		<div class="ent-content" style="height:410px;">
		<?php
		    }
		?>
		    <?php //echo $creditDropdowns; ?>
		    <?php //echo $creditDateBox; ?>
		</div>
	    </div>
	    <!--div class="ent-box flRt" -->
		<?php
		    if (empty($salesUser)) {
		?>
		<!--div class="ent-content" style="height:340px;"-->
		<?php
		    }
		    else {
		?>
		<!--div class="ent-content" style="height:410px;"-->
		<?php
		    }
		?>
		    <!--h5>Industry News: <span id="category"><a href='#' onclick="hidelayer()"><?php //echo $flavoredArticles['categoryName']; ?></a></span>
			<div id="popup_layer" class="multiple-select-wrap" style="display:none; position:absolute; top:20px; right:50px;">
			    <div class="multiple-select" style="width:340px">
				<form method="post" action="">                
				    <ol>
					<li><input id="widget" type="radio" name="CategoryId" value="2" onclick="getWidgetData(this);">Science & Engineering</li>
					<li><input id="widget" type="radio" name="CategoryId" value="3" onclick="getWidgetData(this);">Management</li>
					<li><input id="widget" type="radio" name="CategoryId" value="4" onclick="getWidgetData(this);">Banking & Finance</li>
					<li><input id="widget" type="radio" name="CategoryId" value="5" onclick="getWidgetData(this);">Medicine, Beauty & Health Care</li>
					<li><input id="widget" type="radio" name="CategoryId" value="6" onclick="getWidgetData(this);">Hospitality, Aviation & Tourism</li>
					<li><input id="widget" type="radio" name="CategoryId" value="7" onclick="getWidgetData(this);">Media, Films & Mass Communication</li>
					<li><input id="widget" type="radio" name="CategoryId" value="9" onclick="getWidgetData(this);">Arts, Law, Languages and Teaching</li>
					<li><input id="widget" type="radio" name="CategoryId" value="10" onclick="getWidgetData(this);">Information Technology</li>
					<li><input id="widget" type="radio" name="CategoryId" value="11" onclick="getWidgetData(this);">Retail</li>
					<li><input id="widget" type="radio" name="CategoryId" value="12" onclick="getWidgetData(this);">Animation, Visual Effects, Gaming & Comics (AVGC)</li>
					<li><input id="widget" type="radio" name="CategoryId" value="13" onclick="getWidgetData(this);">Design</li>
					<li><input id="widget" type="radio" name="CategoryId" value="14" onclick="getWidgetData(this);">Test Preparation</li>
				    </ol>
				</form>
				<div class="clearFix"></div>                                        
			    </div>
			</div>
		    </h5-->
		    <!--div class="ent-details" style="padding-top:0">
			<div class="carausel-header2">
			    <h4 class="flLt">News &amp; Features</h4>
			    <div class="carausel-controls w-52">
				<div id="prev_article_rdgn" class="prev-item" onclick="if(is_processed1 == true) {showNextLatestUpdateSmart(--LatestUpdate_index_HPRDGN);}"></div>
				<div id="next_article_rdgn"class="next-item-active" onclick="if(is_processed1 == true){showNextLatestUpdateSmart(++LatestUpdate_index_HPRDGN);}"></div>
			    </div>
			</div>
			<div class="contents2" style="padding-top:0 !important">
			    <div style="overflow:hidden;width:410px; " id="flavouredArticle" >
				<?php //echo $flavoredArticles['widgetHtml']; ?>
			    </div>
			    <div class="clearFix"></div>
			</div>
		    </div-->
		<!--/div-->
	    <!--/div-->
	    <div class="clearFix"></div>
		<?php } ?>
		
		
	    <div class="ent-box full-width">
		
		<?php
		    if (empty($salesUser)) {
		?>
		<div class="ent-content" style="height:auto; min-height:190px; position:relative">
		<?php
		    }
		    else {
		?>
		<div class="ent-content" style="height:auto; min-height:290px; position:relative">
		<?php
		    }
		?>
		    <?php echo $leadsDropdowns; ?>
		    <?php echo $leadsDateBox; ?>
		</div>
	    </div>
	    <div class="clearFix"></div>
	    <?php
	    if(!empty($accountManagerDetails)) {
	    ?>
	    <div class="ent-box full-width">
		<div class="ent-content2">
		    <h6>For further queries, please get in touch with your account manager</h6>	
		    <ul class="list-row">
			<?php if(!empty($accountManagerDetails['name'])) { ?> <li><strong>Name of the Person: </strong><?php echo $accountManagerDetails['name']; ?></li> <?php } ?>
			<?php if(!empty($accountManagerDetails['email'])) { ?> <li><strong>Email: </strong><?php echo $accountManagerDetails['email']; ?></li> <?php } ?>
			<?php if(!empty($accountManagerDetails['mobile'])) { ?> <li><strong>Mobile: </strong><?php echo $accountManagerDetails['mobile']; ?></li> <?php } ?>
		    </ul>
		</div>
	    </div>
	    <div class="clearFix"></div>
	    <?php
	    }
	    elseif(empty($salesUser) && empty($accountManagerDetails)) {
	    ?>
	    <div class="ent-box full-width">
		<div class="ent-content2">		
		<h6>Thanks for signing up with Shiksha.com, your account has been successfully created. </h6>
		<span style="margin-top:20px; display: block">
		    To help you with institute creation, someone from our team will contact you to understand your requirements.<br />
		    Meanwhile if you want to contact us, you can email us at <a href="mailto: sales@shiksha.com">sales@shiksha.com</a>.<br>
		</span>
		<span style="font-size:14px; margin-top:20px; display: block">
		    Institutes inside India can call us at our local city office. <a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/contactUs" target="_new">Click here</a> to see details of our local offices.<br/>
		    Institutes outside India can call us at our Toll Free number: 1800-717-1094
		</span>
		</div>
	    </div>
	    <div class="clearFix"></div>
	    <?php
	    }
	    else {
	    }
	    ?>
	</div>
    </div>
</div>
<?php
if(is_array($footerContentaarray) && count($footerContentaarray)>0) {
	foreach ($footerContentaarray as $content) {
		echo $content;
	}
}
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

var is_processed = true;
var is_processed1 = true;
var max_no_elements_HPRDGN = '<?php echo count($carousel_array);?>';
var interval_index_HPRDGN = 0;
var testimonialindex_HPRDGN = 0;
var LatestUpdate_index_HPRDGN =0;
var total_flavoured_element = '<?php echo count($flavoredArticle);?>';
var total_latest_updt = '<?php echo $count_latest_updates;?>';
if(total_latest_updt%3 == 0) {
    total_latest_updt = total_latest_updt/3;
} else {
    total_latest_updt = parseInt(total_latest_updt/3,10)+1;
}
var index_latest_updt = 0;
var index_flavoured = 0;
if(total_flavoured_element>=total_latest_updt && total_latest_updt>0) {
    total_flavoured_element = total_latest_updt;
} else {
    total_latest_updt = total_flavoured_element; 
}

google.load('visualization', '1', {packages: ['corechart']});
google.setOnLoadCallback(initializeGraphs);

var chartDivIds = new Array();
var chartDataTables = new Array();
var charts = new Array();
var widgetData = new Array();
var selectedGraphType = new Array();
var selectedRange = new Array();
var dateLayerVisible = new Array();

function init() {
    chartDivIds = new Array('response','activity','credit','leads');
    chartDataTables = new Array();
    charts = new Array();
    selectedGraphType = new Array();
    selectedRange = new Array();
    dateLayerVisible = new Array();
    
    for (var index = 0; index < chartDivIds.length; index++) {
	if (chartDivIds[index] == 'response' || chartDivIds[index] == 'activity') {
	    chartDataTables[chartDivIds[index]] = new google.visualization.DataTable();
	}
    }
    
    for (var index = 0; index < chartDivIds.length; index++) {
	if ((chartDivIds[index] == 'response' || chartDivIds[index] == 'activity') && document.getElementById(chartDivIds[index]) != undefined) {
	    charts[chartDivIds[index]] = new google.visualization.ColumnChart(document.getElementById(chartDivIds[index]));
	}
    }
    
    for (var index = 0; index < chartDivIds.length; index++) {
	if (chartDivIds[index] == 'credit' || chartDivIds[index] == 'leads') {
	    selectedGraphType[chartDivIds[index]] = 'cumulative';
	}
	else {
	    selectedGraphType[chartDivIds[index]] = 'comparitive';
	}
    }
    
    for (var index = 0; index < chartDivIds.length; index++) {
	selectedRange[chartDivIds[index]] = 'Last 7 Days';
    }
    
    for (var index = 0; index < chartDivIds.length; index++) {
	dateLayerVisible[chartDivIds[index]] = 'no';
    }
}

function initializeGraphs() {
	init();
	displayDateBox('credit');
	displayDateBox('leads');
	
	var formDataObj = objectifyForm('response_Form');
	if(formDataObj['institute[]'] != undefined) {
		$j("#response_Form").find('.formbutton').click();
	}

	formDataObj = objectifyForm('activity_Form');
	if(formDataObj['institute[]'] != undefined) {
		$j("#activity_Form").find('.formbutton').click();
	}

	$j("#credit_Form").find('.client_hidden').click();
	$j("#leads_Form").find('.client_hidden').click();
}

function displayDateBox(widget) {
	var graphType = 'cumulative';
	var range = 'Last 7 Days';
	var dateIntervals = getDateIntervals(graphType, range, '', '', '', '');
	displayRange(widget, graphType, range, dateIntervals);
}

function drawDefaultChart(widget)
{
	var last7DaysObj = document.getElementById(widget + '_quick_link_1');
	var last30DaysObj = document.getElementById(widget + '_quick_link_2');
	var last90DaysObj = document.getElementById(widget + '_quick_link_3');
	var thisMonthObj = document.getElementById(widget + '_quick_link_4');
	var lastMonthObj = document.getElementById(widget + '_quick_link_5');
	var applyButtonObj = document.getElementById(widget + '_apply');
	
	if (widget == 'response' || widget == 'activity') {
	    var viewByDayObj = document.getElementById(widget + '_view_by_day');
	    var viewByWeekObj = document.getElementById(widget + '_view_by_week');
	    var viewByMonthObj = document.getElementById(widget + '_view_by_month');
	}
	
	if (selectedGraphType[widget] == 'comparitive' || selectedGraphType[widget] == 'cumulative') {
		if (selectedRange[widget] == 'Last 7 Days') {
			drawChart(last7DaysObj);
		}
		else if (selectedRange[widget] == 'Last 30 Days') {
			drawChart(last30DaysObj);
		}
		else if (selectedRange[widget] == 'Last 90 Days') {
			drawChart(last90DaysObj);
		}
		else if (selectedRange[widget] == 'This Month') {
			drawChart(thisMonthObj);
		}
		else if (selectedRange[widget] == 'Last Month') {
			drawChart(lastMonthObj);
		}
		else if (selectedRange[widget] == 'Duration') {
			drawChart(applyButtonObj);
		}
	}
	else if (selectedGraphType[widget] == 'view_by_day') {
		drawChart(viewByDayObj);
	}
	else if (selectedGraphType[widget] == 'view_by_week') {
		drawChart(viewByWeekObj);
	}
	else if (selectedGraphType[widget] == 'view_by_month') {
		drawChart(viewByMonthObj);
	}
}

function onClickAction(clickedObj) {
	var widget = clickedObj.getAttribute('chart');
	var graphType = clickedObj.getAttribute('graph_type');
	if (widget == 'response' || widget == 'activity') {
		if (graphType == 'view_by_day' || graphType == 'view_by_week' || graphType == 'view_by_month') {
			if(clickedObj.getAttribute('is_active') == 'false') {
				showSelectedViewBy(clickedObj.getAttribute('chart'), clickedObj.id);
				drawChart(clickedObj);
			}
		}
		else if (graphType == 'comparitive') {
			if (clickedObj.value == 'Apply') {
				if (validateApply(widget, true)) {
					hideDateLayer(clickedObj.getAttribute('chart'));
					disableViewBy(clickedObj.getAttribute('chart'), clickedObj.getAttribute('range'), clickedObj.getAttribute('start_date'), clickedObj.getAttribute('end_date'));
					showSelectedViewBy(clickedObj.getAttribute('chart'));
					drawChart(clickedObj);
				}
			}
			else {
				hideDateLayer(clickedObj.getAttribute('chart'));
				disableViewBy(clickedObj.getAttribute('chart'), clickedObj.getAttribute('range'));
				showSelectedViewBy(clickedObj.getAttribute('chart'));
				drawChart(clickedObj);
			}
			
		}
		else if (graphType == 'cumulative') {
			if(validateApply(widget, false)) {
				hideDateLayer(clickedObj.getAttribute('chart'));
				disableViewBy(clickedObj.getAttribute('chart'), clickedObj.getAttribute('range'), clickedObj.getAttribute('start_date'), clickedObj.getAttribute('end_date'));
				showSelectedViewBy(clickedObj.getAttribute('chart'));
				drawChart(clickedObj);
			}
		}
	}
	else if(widget == 'credit') {
		var range = clickedObj.getAttribute('range');
		if(widgetData[widget]) {
						if (range == 'Duration') {
							if(validateApply(widget, false)) {
								hideDateLayer(clickedObj.getAttribute('chart'));
								$j('#'+widget).hide();
								$j('#'+widget+'_text').hide();
								$j('#'+widget+'_ajax_loader').show();
								setTimeout(function(){
											$j('#'+widget+'_ajax_loader').hide();
											$j('#'+widget).show();
											$j('#'+widget+'_text').hide();
											drawChart(clickedObj);
										    },1000);
							}
						}
						else {
							hideDateLayer(clickedObj.getAttribute('chart'));
							$j('#'+widget).hide();
							$j('#'+widget+'_text').hide();
							$j('#'+widget+'_ajax_loader').show();
							setTimeout(function(){
										$j('#'+widget+'_ajax_loader').hide();
										$j('#'+widget).show();
										$j('#'+widget+'_text').hide();
										drawChart(clickedObj);
									    },1000);
						}
						
					    
		}
		else {
			hideDateLayer(clickedObj.getAttribute('chart'));
			alert("Select a client and click refresh");
		}
	}
	else if(widget == 'leads') {
		var range = clickedObj.getAttribute('range');
		var formDataObj = objectifyForm('leads_Form');
		
		if(formDataObj['executive_id[]'] == undefined && formDataObj['client_id[]'] == undefined) {
			alert("Select an executive to refresh data.");
			return false;
		}
		if(formDataObj['client_id[]'] == undefined) {
			alert("Select a client to refresh data.");
			return false;
		}
		
		if(formDataObj['client_id[]'] != undefined) {
			if (range == 'Duration') {
				if(validateApply(widget, false)) {
					hideDateLayer(clickedObj.getAttribute('chart'));
					drawChart(clickedObj);
				}
			}
			else {
				hideDateLayer(clickedObj.getAttribute('chart'));
				drawChart(clickedObj);
			}
		}
	}
	return true;
}

function drawChart(selectedOption)
{
	var chartDivId = selectedOption.getAttribute('chart');
	var graphType = selectedOption.getAttribute('graph_type');
	var startDate;
	var endDate;
	var startDateCompare;
	var endDateCompare;
	var dateIntervals;
	
	if (graphType == 'view_by_day' || graphType == 'view_by_week' || graphType == 'view_by_month') {
		range = selectedRange[chartDivId];
	}
	else {
		range = selectedOption.getAttribute('range');
	}
	
	if (range == 'Duration' && graphType == 'comparitive') {
		startDate = selectedOption.getAttribute('start_date');
		endDate = selectedOption.getAttribute('end_date');
		startDateCompare = selectedOption.getAttribute('start_date_compare');
		endDateCompare = selectedOption.getAttribute('end_date_compare');
	}
	else if (range == 'Duration') {
		startDate = selectedOption.getAttribute('start_date');
		endDate = selectedOption.getAttribute('end_date');
		startDateCompare = '';
		endDateCompare = '';
	}
	else {
		startDate = '';
		endDate = '';
		startDateCompare = '';
		endDateCompare = '';
	}
	
	dateIntervals = getDateIntervals(graphType, range, startDate, endDate, startDateCompare, endDateCompare);
	
	if (graphType != 'view_by_day' && graphType != 'view_by_week' && graphType != 'view_by_month') {
		displayRange(chartDivId, graphType, range, dateIntervals);
	}
	
	if (chartDivId == 'response' || chartDivId == 'activity') {
		var dataForIntervals = groupByInterval(chartDivId, dateIntervals, widgetData[chartDivId]);
		var chartDataArray = getDataArrayForChart(chartDivId, graphType, range, dateIntervals, dataForIntervals);
		if (checkDataAvailable(chartDivId, dataForIntervals)) {
			drawVisualization(chartDivId, chartDataArray);
		}
	}
	else if (chartDivId == 'credit') {
		var dataForIntervals = groupByInterval(chartDivId, dateIntervals, widgetData[chartDivId]);
		var totalCredits = 0;
		var goldLeft = 0;
		for(var date in widgetData[chartDivId]) {
			totalCredits = widgetData[chartDivId][date][1];
			goldLeft = widgetData[chartDivId][date][2];
			break;
		}
		if (totalCredits.length > 4 || dataForIntervals[0][0].toString().length > 4 || goldLeft.length > 4) {
		    $j('#total_credits').css('font-size', '24px');
		    $j('#credits_consumed').css('font-size', '24px');
		    $j('#gold_left').css('font-size', '24px');
		}
		$j("#total_credits").html(totalCredits);
		$j("#credits_consumed").html(dataForIntervals[0][0]);
		$j("#gold_left").html(goldLeft);
	}
	else if (chartDivId == 'leads') {
		$j('#leads_from').val(convertDateToString(dateIntervals[0][0]));
		$j('#leads_to').val(convertDateToString(dateIntervals[0][1]));
		getGraphData(document.getElementById('leads_to'));
	}
	selectedGraphType[chartDivId] = graphType;
	selectedRange[chartDivId] = range;
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
    
    if (chart == 'response') {
	colors = ['#C0F57C'];
	hAxisTitle = 'Duration';
	vAxisTitle = 'Responses'
	isStacked = false;
    }
    else if (chart == 'activity') {
	colors = ['#F8A36B', '#B1D0F7', '#BCAABB'];
	hAxisTitle = 'Duration';
	vAxisTitle = 'Questions Posted / Answered'
	isStacked = false;
    }
    
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

function getDateIntervals(graphType, range, startDate, endDate, startDateCompare, endDateCompare) {
    startDate = startDate != '' ? convertStringToDate(startDate) : '';
    endDate = endDate != '' ? convertStringToDate(endDate) : '';
    startDateCompare = startDateCompare != '' ? convertStringToDate(startDateCompare) : '';
    endDateCompare = endDateCompare != '' ? convertStringToDate(endDateCompare) : '';
    var dateIntervals = new Array();
    var interval;
    
    if (range == 'Last 7 Days') {
	interval = 7;
    }
    else if (range == 'Last 30 Days') {
	interval = 30;
    }
    else if (range == 'Last 90 Days') {
	interval = 90;
    }
    
    if(graphType != 'comparitive') {
	if (range == 'Last 7 Days' || range == 'Last 30 Days' || range == 'Last 90 Days') {
	    startDate = new Date();
	    endDate = new Date();
	    endDate.setDate(endDate.getDate() - 1);
	    startDate.setDate(startDate.getDate() - interval);
	}
	else if (range == 'This Month') {
	    endDate = new Date();
	    startDate = new Date(endDate.getFullYear(), endDate.getMonth(), 1);
	}
	else if (range == 'Last Month') {
	    var currentDate = new Date();
	    startDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
	    endDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0);
	}
    }
    
    if(graphType == 'comparitive') {
	if (range == 'Last 7 Days' || range == 'Last 30 Days' || range == 'Last 90 Days') {
	    startDate = new Date();
	    endDate = new Date();
	    startDate.setDate(endDate.getDate() - interval*2);
	    while(startDate < endDate) {
		var dateArray = new Array();
		dateArray.push(new Date(startDate));
		startDate.setDate(startDate.getDate() + interval - 1);
		dateArray.push(new Date(startDate));
		startDate.setDate(startDate.getDate() + 1);
		dateIntervals.push(dateArray);
	    }
	}
	else if (range == 'This Month') {
	    endDate = new Date();
	    for (var month = 1; month >= 0; month--) {
		var dateArray = new Array();
		var firstDay = new Date(endDate.getFullYear(), endDate.getMonth() - month, 1);
		dateArray.push(new Date(firstDay));

		if (endDate - new Date(endDate.getFullYear(), endDate.getMonth() + 1, 0) == 0) {
		    var lastDay = new Date(endDate.getFullYear(), endDate.getMonth() - month + 1, 0);
		}
		else {
		    var lastDay = new Date(endDate.getFullYear(), endDate.getMonth() - month, endDate.getDate());
		}
		dateArray.push(new Date(lastDay));
		dateIntervals.push(dateArray);
	    }
	}
	else if (range == 'Last Month') {
	    endDate = new Date();
	    for (var month = 2; month > 0; month--) {
		var dateArray = new Array();
		var firstDay = new Date(endDate.getFullYear(), endDate.getMonth() - month, 1);
		dateArray.push(new Date(firstDay));
		var lastDay = new Date(endDate.getFullYear(), endDate.getMonth() - month + 1, 0);
		dateArray.push(new Date(lastDay));
		dateIntervals.push(dateArray);
	    }
	}
	else if (range == 'Duration') {
	    var dateArray = new Array();
	    dateArray.push(new Date(startDate));
	    dateArray.push(new Date(endDate));
	    dateIntervals.push(dateArray);
	    dateArray = new Array();
	    dateArray.push(new Date(startDateCompare));
	    dateArray.push(new Date(endDateCompare));
	    dateIntervals.push(dateArray);
	}
    }
    else if (graphType == 'cumulative') {
	var dateArray = new Array();
	dateArray.push(new Date(startDate));
	dateArray.push(new Date(endDate));
	dateIntervals.push(dateArray);
    }
    else if (graphType == 'view_by_day') {
	while(startDate <= endDate) {
	    var dateArray = new Array();
	    dateArray.push(new Date(startDate));
	    dateArray.push(new Date(startDate));
	    startDate.setDate(startDate.getDate() + 1);
	    dateIntervals.push(dateArray);
	}
    }
    else if (graphType == 'view_by_week') {
	while(startDate <= endDate) {
	    var dateArray = new Array();
	    var firstDay = new Date(startDate);
	    dateArray.push(new Date(firstDay));
	    if (startDate.getDay() != 0) {
		startDate.setDate(startDate.getDate() - startDate.getDay() + 7);
	    }
	    var timeDiff = endDate.getTime() - startDate.getTime();
	    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
	    if (diffDays < 0) {
		var lastDay = new Date(endDate);
	    }
	    else {
		var lastDay = new Date(startDate);
	    }
	    dateArray.push(new Date(lastDay));
	    dateIntervals.push(dateArray);
	    startDate.setDate(startDate.getDate() + 1);
	}
    }
    else if (graphType == 'view_by_month') {
	while(startDate <= endDate) {
	    var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
	    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
	    var dateArray = new Array();
	    var firstDay = new Date(startDate);
	    dateArray.push(new Date(firstDay));
	    if (diffDays < 0) {
		var lastDay = endDate;
	    }
	    else {
		var lastDay = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
	    }
	    dateArray.push(new Date(lastDay));
	    dateIntervals.push(dateArray);
	    startDate.setDate(1);
	    startDate.setMonth(startDate.getMonth() + 1);
	}
    }
    
    return dateIntervals;
}

function groupByInterval(widget, dateIntervals , countsByDate) {
    if (dateIntervals.length) {
	var interval = 0;
	var intervalCount;
	var lengthOfArray;
	var intervalCountArray = new Array();
	var firstIntervalDate = dateIntervals[interval][0];
	var secondIntervalDate = dateIntervals[interval][1];
	var betweenIntervalDate = new Date(firstIntervalDate);
	
	var lengthOfArray = 1;
	if (widget == 'activity') {
		lengthOfArray = 3;
	}
	else if (widget == 'credit') {
		lengthOfArray = 3;
	}
	
	intervalCount = new Array(lengthOfArray);
	for (var index = 0; index < lengthOfArray; index++)
	    intervalCount[index] = 0;
	
	interval++;
	
	while (betweenIntervalDate <= secondIntervalDate) {
	    if (countsByDate[convertDateToString(betweenIntervalDate)]) {
		for (var index = 0; index < countsByDate[convertDateToString(betweenIntervalDate)].length; index++) {
		    if (countsByDate[convertDateToString(betweenIntervalDate)][index]) {
			if (typeof intervalCount[index] == 'undefined') {
			    intervalCount[index] = 0;
			}
			intervalCount[index] += parseInt(countsByDate[convertDateToString(betweenIntervalDate)][index]);
		    }
		}
	    }
	    
	    betweenIntervalDate.setDate(betweenIntervalDate.getDate() + 1);
	    if (betweenIntervalDate - secondIntervalDate >= 1) {
		if (typeof dateIntervals[interval] != 'undefined') {
		    firstIntervalDate = dateIntervals[interval][0];
		    secondIntervalDate = dateIntervals[interval][1];
			betweenIntervalDate = new Date(firstIntervalDate);
		    interval++;
		}
		intervalCountArray.push(intervalCount);
		
		intervalCount = new Array(lengthOfArray);
		for (var index = 0; index < lengthOfArray; index++)
		    intervalCount[index] = 0;
	    }
	}
    }
    
    return intervalCountArray;
}

function getChartLabels(graphType, range, dateIntervals) {
    var chartLables = new Array();
    var monthNames = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun",
		      "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
    
    if (graphType == 'comparitive') {
	if (range == 'Last 7 Days') {
	    chartLables = {
			    'column1':'Last to Last 7 Days',
			    'column2':'Last 7 Days'
	    };
	}
	else if (range == 'Last 30 Days') {
	    chartLables = {
			    'column1':'Last to Last 30 Days',
			    'column2':'Last 30 Days'
	    };
	}
	else if (range == 'Last 90 Days') {
	    chartLables = {
			    'column1':'Last to Last 90 Days',
			    'column2':'Last 90 Days'
	    };
	}
	else if (range == 'This Month') {
	    chartLables = {
			    'column1':'Last Month: ' + monthNames[dateIntervals[0][0].getMonth()]+' '+dateIntervals[0][0].getDate()+'-'+dateIntervals[0][1].getDate(),
			    'column2':'This Month: ' + monthNames[dateIntervals[1][0].getMonth()]+' '+dateIntervals[1][0].getDate()+'-'+dateIntervals[1][1].getDate()
	    };
	}
	else if (range == 'Last Month') {
	    chartLables = {
			    'column1':monthNames[dateIntervals[0][0].getMonth()] + " '" + dateIntervals[0][0].getFullYear() % 100,
			    'column2':monthNames[dateIntervals[1][0].getMonth()] + " '" + dateIntervals[1][0].getFullYear() % 100
	    };
	}
	else if (range == 'Duration') {
	    chartLables = {
			    'column1':monthNames[dateIntervals[0][0].getMonth()]+' '+dateIntervals[0][0].getDate()+', '+dateIntervals[0][0].getFullYear() + ' - ' + monthNames[dateIntervals[0][1].getMonth()]+' '+dateIntervals[0][1].getDate()+', '+dateIntervals[0][1].getFullYear(),
			    'column2':monthNames[dateIntervals[1][0].getMonth()]+' '+dateIntervals[1][0].getDate()+', '+dateIntervals[1][0].getFullYear() + ' - ' + monthNames[dateIntervals[1][1].getMonth()]+' '+dateIntervals[1][1].getDate()+', '+dateIntervals[1][1].getFullYear()
	};
	}
    }
    else if (graphType == 'cumulative') {
	chartLables = {
			    'column1':monthNames[dateIntervals[0][0].getMonth()]+' '+dateIntervals[0][0].getDate()+', '+dateIntervals[0][0].getFullYear() + ' - ' + monthNames[dateIntervals[0][1].getMonth()]+' '+dateIntervals[0][1].getDate()+', '+dateIntervals[0][1].getFullYear()
	};
    }
    else if (graphType == 'view_by_day' || graphType == 'view_by_week' || graphType == 'view_by_month') {
	if (graphType == 'view_by_day') {
	    for (var index = 0; index < dateIntervals.length; index++) {
		var column = 'column' + parseInt(index + 1);
		chartLables[column] = monthNames[dateIntervals[index][0].getMonth()] + ' ' + dateIntervals[index][0].getDate();
	    }
	}
	else if (graphType == 'view_by_week') {
	    for (var index = 0; index < dateIntervals.length; index++) {
		var column = 'column' + parseInt(index + 1);
		chartLables[column] = monthNames[dateIntervals[index][0].getMonth()] + ' ' + dateIntervals[index][0].getDate();
	    }
	}
	else if (graphType == 'view_by_month') {
	    for (var index = 0; index < dateIntervals.length; index++) {
		var column = 'column' + parseInt(index + 1);
		chartLables[column] = monthNames[dateIntervals[index][0].getMonth()] + "' " + dateIntervals[index][0].getFullYear() % 100;
	    }
	}
    }
    
    return chartLables;
}

function getDataArrayForChart(widget, graphType, range, dateIntervals, dataForIntervals) {
    var chartLables = getChartLabels(graphType, range, dateIntervals);
    var data = new Array();
    var labelArray;
    
    if (widget == 'response') {
	data.push(['Duration', 'Responses']);
    }
    else if (widget == 'activity') {
	data.push(['Duration', 'Questions Posted', 'Questions Answered by Users', 'Questions Answered by Institute']);
    }
    
    if (graphType == 'comparitive' && range != 'Duration') {
	for (var index = dataForIntervals.length - 1; index >= 0; index--) {
	    labelArray = new Array();
	    labelArray.push(chartLables['column' + String(index + 1)]);
	    for (var value = 0; value < dataForIntervals[index].length; value++) {
		labelArray.push(dataForIntervals[index][value]);
	    }
	    data.push(labelArray);
	}
    }
    else {
    for (var index = 0; index < dataForIntervals.length; index++) {
	labelArray = new Array();
	labelArray.push(chartLables['column' + String(index + 1)]);
	for (var value = 0; value < dataForIntervals[index].length; value++) {
	    labelArray.push(dataForIntervals[index][value]);
	}
	data.push(labelArray);
	}
    }
    
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

function showCompareDate(checked, widget) {
    var applyId = widget + '_apply';
    var applyButton = document.getElementById(applyId);
    
    if (checked == true) {
	$j('#' + widget + '_compare_date_box').show();
	applyButton.setAttribute('graph_type', 'comparitive');
    }
    else {
	$j('#' + widget + '_compare_date_box').hide();
	applyButton.setAttribute('graph_type', 'cumulative');
    }
}

function setDuration(widget, range, date, compare) {
    var applyId = widget + '_apply';
    var viewByDayId = widget + '_view_by_day';
    var viewByWeekId = widget + '_view_by_week';
    var viewByMonthId = widget + '_view_by_month';
    var applyButton = document.getElementById(applyId);
    var viewByDayLink = document.getElementById(viewByDayId);
    var viewByWeekLink = document.getElementById(viewByWeekId);
    var viewByMonthLink = document.getElementById(viewByMonthId);
    if (range == 'timefilter[from]') {
	applyButton.setAttribute('start_date' + compare, date);
	viewByDayLink.setAttribute('start_date' + compare, date);
	viewByWeekLink.setAttribute('start_date' + compare, date);
	viewByMonthLink.setAttribute('start_date' + compare, date);
    }
    else if (range == 'timefilter[to]') {
	applyButton.setAttribute('end_date' + compare, date);
	viewByDayLink.setAttribute('end_date' + compare, date);
	viewByWeekLink.setAttribute('end_date' + compare, date);
	viewByMonthLink.setAttribute('end_date' + compare, date);
    }
}

function displayRange(widget, graphType, range, dateIntervals) {
	var monthNames = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun",
						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
	var rangeId = widget + '_range';
	var durationId = widget + '_duration';
	var rangeHTML = document.getElementById(rangeId);
	var durationHTML = document.getElementById(durationId);
	rangeHTML.innerHTML = range;
	if (graphType == 'comparitive' && range != 'Duration') {
		durationHTML.innerHTML = monthNames[dateIntervals[1][0].getMonth()]+' '+dateIntervals[1][0].getDate()+', '+dateIntervals[1][0].getFullYear() + ' - ' + monthNames[dateIntervals[dateIntervals.length-1][1].getMonth()]+' '+dateIntervals[dateIntervals.length-1][1].getDate()+', '+dateIntervals[dateIntervals.length-1][1].getFullYear();
	}
	else if (graphType == 'comparitive' && range == 'Duration') {
		durationHTML.innerHTML = monthNames[dateIntervals[0][0].getMonth()]+' '+dateIntervals[0][0].getDate()+', '+dateIntervals[0][0].getFullYear() + ' - ' + monthNames[dateIntervals[0][1].getMonth()]+' '+dateIntervals[0][1].getDate()+', '+dateIntervals[0][1].getFullYear();
	}
	else {
		durationHTML.innerHTML = monthNames[dateIntervals[0][0].getMonth()]+' '+dateIntervals[0][0].getDate()+', '+dateIntervals[0][0].getFullYear() + ' - ' + monthNames[dateIntervals[dateIntervals.length-1][1].getMonth()]+' '+dateIntervals[dateIntervals.length-1][1].getDate()+', '+dateIntervals[dateIntervals.length-1][1].getFullYear();
	}
}

function showSelectedViewBy(widget, viewById) {
    var viewByDayId = widget + '_view_by_day';
    var viewByWeekId = widget + '_view_by_week';
    var viewByMonthId = widget + '_view_by_month';
    var viewByDayLink = document.getElementById(viewByDayId);
    var viewByWeekLink = document.getElementById(viewByWeekId);
    var viewByMonthLink = document.getElementById(viewByMonthId);
    
    if (viewById == null) {
	viewByDayLink.style.borderBottom= '';
	viewByWeekLink.style.borderBottom= '';
	viewByMonthLink.style.borderBottom= '';
    }
    else if (viewById == viewByDayId) {
	viewByDayLink.style.borderBottom= '2px solid #FF8000';
	viewByWeekLink.style.borderBottom= '';
	viewByMonthLink.style.borderBottom= '';
    }
    else if (viewById == viewByWeekId) {
	viewByDayLink.style.borderBottom= '';
	viewByWeekLink.style.borderBottom= '2px solid #FF8000';
	viewByMonthLink.style.borderBottom= '';
    }
    else if (viewById == viewByMonthId) {
	viewByDayLink.style.borderBottom= '';
	viewByWeekLink.style.borderBottom= '';
	viewByMonthLink.style.borderBottom= '2px solid #FF8000';
    }
}

function disableViewBy(widget, range, startDate, endDate) {
    var viewByDayId = widget + '_view_by_day';
    var viewByWeekId = widget + '_view_by_week';
    var viewByMonthId = widget + '_view_by_month';
    var viewByDayLink = document.getElementById(viewByDayId);
    var viewByWeekLink = document.getElementById(viewByWeekId);
    var viewByMonthLink = document.getElementById(viewByMonthId);
    
    if (range == 'Last 7 Days') {
	viewByDayLink.setAttribute('is_active', 'false');
	viewByDayLink.style.color = '';
	viewByDayLink.style.textDecoration = '';
	viewByDayLink.style.cursor = '';
	
	viewByWeekLink.setAttribute('is_active', 'false');
	viewByWeekLink.style.color = '';
	viewByWeekLink.style.textDecoration = '';
	viewByWeekLink.style.cursor = '';
	
	viewByMonthLink.setAttribute('is_active', 'true');
	viewByMonthLink.style.color = 'Grey';
	viewByMonthLink.style.textDecoration = 'none';
	viewByMonthLink.style.cursor = 'default';
    }
    else if (range == 'Last 30 Days' || range == 'Last 90 Days' || range == 'This Month' || range == 'Last Month') {
	viewByDayLink.setAttribute('is_active', 'true');
	viewByDayLink.style.color = "Grey";
	viewByDayLink.style.textDecoration = 'none';
	viewByDayLink.style.cursor = 'default';
	
	viewByWeekLink.setAttribute('is_active', 'false');
	viewByWeekLink.style.color = '';
	viewByWeekLink.style.textDecoration = '';
	viewByWeekLink.style.cursor = '';
	
	viewByMonthLink.setAttribute('is_active', 'false');
	viewByMonthLink.style.color = '';
	viewByMonthLink.style.textDecoration = '';
	viewByMonthLink.style.cursor = '';
    }
    else if (range == 'Duration') {
	startDate = convertStringToDate(startDate);
	endDate = convertStringToDate(endDate);
	var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
	var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
	
	if (diffDays >= 0 && diffDays < 6) {
	    viewByDayLink.setAttribute('is_active', 'false');
	    viewByDayLink.style.color = '';
	    viewByDayLink.style.textDecoration = '';
	    viewByDayLink.style.cursor = '';
	    
	    viewByWeekLink.setAttribute('is_active', 'true');
	    viewByWeekLink.style.color = 'Grey';
	    viewByWeekLink.style.textDecoration = 'none';
	    viewByWeekLink.style.cursor = 'default';
	    
	    viewByMonthLink.setAttribute('is_active', 'true');
	    viewByMonthLink.style.color = 'Grey';
	    viewByMonthLink.style.textDecoration = 'none';
	    viewByMonthLink.style.cursor = 'default';
	}
	else if (diffDays >= 6 && diffDays <= 14) {
	    viewByDayLink.setAttribute('is_active', 'false');
	    viewByDayLink.style.color = '';
	    viewByDayLink.style.textDecoration = '';
	    viewByDayLink.style.cursor = '';
	    
	    viewByWeekLink.setAttribute('is_active', 'false');
	    viewByWeekLink.style.color = '';
	    viewByWeekLink.style.textDecoration = '';
	    viewByWeekLink.style.cursor = '';
	    
	    viewByMonthLink.setAttribute('is_active', 'true');
	    viewByMonthLink.style.color = 'Grey';
	    viewByMonthLink.style.textDecoration = 'none';
	    viewByMonthLink.style.cursor = 'default';
	}
	else if (diffDays > 14 && diffDays <= 89) {
	    viewByDayLink.setAttribute('is_active', 'true');
	    viewByDayLink.style.color = "Grey";
	    viewByDayLink.style.textDecoration = 'none';
	    viewByDayLink.style.cursor = 'default';
	    
	    viewByWeekLink.setAttribute('is_active', 'false');
	    viewByWeekLink.style.color = '';
	    viewByWeekLink.style.textDecoration = '';
	    viewByWeekLink.style.cursor = '';
	    
	    viewByMonthLink.setAttribute('is_active', 'false');
	    viewByMonthLink.style.color = '';
	    viewByMonthLink.style.textDecoration = '';
	    viewByMonthLink.style.cursor = '';
	}
	else if (diffDays > 89 && diffDays <= 364) {
	    viewByDayLink.setAttribute('is_active', 'true');
	    viewByDayLink.style.color = "Grey";
	    viewByDayLink.style.textDecoration = 'none';
	    viewByDayLink.style.cursor = 'default';
	    
	    viewByWeekLink.setAttribute('is_active', 'true');
	    viewByWeekLink.style.color = 'Grey';
	    viewByWeekLink.style.textDecoration = 'none';
	    viewByWeekLink.style.cursor = 'default';
	    
	    viewByMonthLink.setAttribute('is_active', 'false');
	    viewByMonthLink.style.color = '';
	    viewByMonthLink.style.textDecoration = '';
	    viewByMonthLink.style.cursor = '';
	}
	else if (diffDays > 364) {
	    viewByDayLink.setAttribute('is_active', 'true');
	    viewByDayLink.style.color = "Grey";
	    viewByDayLink.style.textDecoration = 'none';
	    viewByDayLink.style.cursor = 'default';
	    
	    viewByWeekLink.setAttribute('is_active', 'true');
	    viewByWeekLink.style.color = 'Grey';
	    viewByWeekLink.style.textDecoration = 'none';
	    viewByWeekLink.style.cursor = 'default';
	    
	    viewByMonthLink.setAttribute('is_active', 'true');
	    viewByMonthLink.style.color = 'Grey';
	    viewByMonthLink.style.textDecoration = 'none';
	    viewByMonthLink.style.cursor = 'default';
	}
    }
}

var showingHelpText = false;

function showHelpText(widget) {
	if (!showingHelpText) {
		showingHelpText = true;
		var helpTextHTMLObj = document.getElementById(widget + '_help_text');
		$j(helpTextHTMLObj).fadeIn( "slow", function() {showingHelpText = false;});
	}
}

function hideHelpText(widget) {
	var helpTextHTMLObj = document.getElementById(widget + '_help_text');
	$j(helpTextHTMLObj).fadeOut( "slow", function() {});
}

function validateApply(widget, comparitive)
{
	var startDate = document.getElementById(widget + '_timerange_from').value;
	var endDate = document.getElementById(widget + '_timerange_to').value;
	
	if (startDate == '' || startDate == 'yyyy-mm-dd') {
		alert('Select a start date.');
		return false;
	}
	else if (endDate == '' || endDate == 'yyyy-mm-dd') {
		alert('Select a end date.');
		return false;
	}
	
	var timeDiff = convertStringToDate(endDate).getTime() - convertStringToDate(startDate).getTime();
	var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
	
	if (diffDays < 0) {
		alert("Please select a 'TO' date greater than the 'FROM' date.");
		return false;
	}
	
	if (comparitive == true) {
		var startDateCompare = document.getElementById(widget + '_timerange_from_compare').value;
		var endDateCompare = document.getElementById(widget + '_timerange_to_compare').value;
	    
		if (startDateCompare == '' || startDateCompare == 'yyyy-mm-dd') {
			alert('Select a compare start date.');
			return false;
		}
		else if (endDateCompare == '' || endDateCompare == 'yyyy-mm-dd') {
			alert('Select a compare end date.');
			return false;
		}

		var timeDiffCompare = convertStringToDate(endDateCompare).getTime() - convertStringToDate(startDateCompare).getTime();
		var diffDaysCompare = Math.ceil(timeDiffCompare / (1000 * 3600 * 24));
		
		if (diffDaysCompare < 0) {
			alert("Please select a 'TO' date greater than the 'FROM' date.");
			return false;
		}
	}
	return true;
}

function timerangeFrom(widget, compare)
{
	var fromId = widget + '_timerange_from' + compare;
	var toId = widget + '_timerange_to' + compare;
	var calId = widget + '_timerange_from_img' + compare;
	var calMain = new CalendarPopup('calendardiv');
	frmdisDate = new Date();
	var passedYear = new Date().getFullYear() - 1;
	disDate = new Date(passedYear,0,1);
	isresponseViewer = 1;
	calMain.select($(fromId), calId, 'yyyy-mm-dd');
	return false;
}

function timerangeTo(widget, compare)
{
	var fromId = widget + '_timerange_from' + compare;
	var toId = widget + '_timerange_to' + compare;
	var calId = widget + '_timerange_to_img' + compare;
	var calMain = new CalendarPopup('calendardiv');
	frmdisDate = new Date();
	var passedYear = new Date().getFullYear() - 1;
	disDate = new Date(passedYear,0,1);
	isresponseViewer = 1;
	calMain.select($(toId), calId, 'yyyy-mm-dd');
	return false;
}


function hideLayer()
{
	var divId = 'popup_layer';
	var dateLayer = document.getElementById(divId);
        if (dateLayer.style.display == 'none') {
        	dateLayer.style.display = '';
	}
	else {
		dateLayer.style.display = 'none';
	}
}


function hideDateLayer(widget)
{
	var divId = widget + '_popup_layer';
	var dateLayer = document.getElementById(divId);
	if (dateLayerVisible[widget] == 'no') {
		for (var index = 0; index < chartDivIds.length; index++) {
			if (dateLayerVisible[chartDivIds[index]] == 'yes') {
				hideDateLayer(chartDivIds[index]);
			}
		}
		dateLayer.style.display = '';
		dateLayerVisible[widget] = 'yes';
	}
	else {
		dateLayer.style.display = 'none';
		dateLayerVisible[widget] = 'no';
	}
}

function hideCalendar(event) {
    $j('.dropdown_selection').hide();
    $j('.dropdown').attr('disabled',false);
    var cal = document.getElementById('calendarDiv');
    var calImg = getDOMElementsByClassName(document.body,'icon-cal');
    var targetFlag = 1;
    
    if (event.target) {
	    eventTarget = event.target;
    }
    else if (event.srcElement) {
	    eventTarget = event.srcElement;
    }
    
    for (var index = 0; index < calImg.length; index++) {
	if (eventTarget == calImg[index]) {
	    targetFlag = 0;
	}
    }
    
if (cal && cal.style.display == 'block' && targetFlag) {
	    closeCalendar();
    }
}

$j('#response_layer_action').click(function(event) {
    hideCalendar(event);
    event.stopPropagation();
});

$j('#response_popup_layer').click(function(event) {
    hideCalendar(event);
    event.stopPropagation();
});

$j('#activity_layer_action').click(function(event) {
    hideCalendar(event);
    event.stopPropagation();
});

$j('#activity_popup_layer').click(function(event) {
    hideCalendar(event);
    event.stopPropagation();
});

$j('#credit_layer_action').click(function(event) {
    hideCalendar(event);
    event.stopPropagation();
});

$j('#credit_popup_layer').click(function(event) {
    hideCalendar(event);
    event.stopPropagation();
});

$j('#leads_layer_action').click(function(event) {
    hideCalendar(event);
    event.stopPropagation();
});

$j('#leads_popup_layer').click(function(event) {
    hideCalendar(event);
    event.stopPropagation();
});

$j('#category').on('click',function(event) {
    event.stopPropagation();
});
$j('#popup_layer').on('click',function(event) {
    event.stopPropagation();
});

$j(document).click(function(event) {
    var cal = document.getElementById('calendarDiv');
    var targetIsCalendar = false;
    
    if (event.target) {
	    eventTarget = event.target;
    }
    else if (event.srcElement) {
	    eventTarget = event.srcElement;
    }
    
    targetParent = eventTarget.parentNode;
    
    while (targetParent) {
	    if (targetParent == cal) {
		    targetIsCalendar = true;
		    break;
	    }
	    targetParent = targetParent.parentNode;
    }

    if (!targetIsCalendar) {
	    $j('#response_popup_layer').hide();
	    $j('#activity_popup_layer').hide();
	    $j('#credit_popup_layer').hide();
	    $j('#leads_popup_layer').hide();
	    dateLayerVisible['response'] = 'no';
	    dateLayerVisible['activity'] = 'no';
	    dateLayerVisible['credit'] = 'no';
	    dateLayerVisible['leads'] = 'no';
	    hideCalendar(event);
    }
	
	$j('#popup_layer').hide();
});

function showNextLatestUpdateSmart(index) {
    //if(is_processed1 == false) return false;
    if (index < 0) {
        index = 0;
        LatestUpdate_index_HPRDGN = 0;
        return false;
    }
	
    if (index > total_flavoured_element - 1 || index > total_latest_updt - 1) {
        if (total_latest_updt > 0) {
            index = total_latest_updt - 1;
            LatestUpdate_index_HPRDGN = total_latest_updt - 1;
        } else {
            index = total_flavoured_element - 1;
            LatestUpdate_index_HPRDGN = total_flavoured_element - 1;
        }
        return false;
    }
    $('prev_article_rdgn').className = "prev-item-active";
    $('next_article_rdgn').className = "next-item-active";
    if (index == 0) {
        index = 0;
        $('prev_article_rdgn').className = "prev-item";
        $('next_article_rdgn').className = "next-item-active";
    }
    index_latest_updt = index;
    index_flavoured = index;
	
    if (index_flavoured == total_flavoured_element - 1 || index_latest_updt == total_latest_updt - 1) {
        $('prev_article_rdgn').className = "prev-item-active";
        $('next_article_rdgn').className = "next-item";
    }
    if(navigator.appCodeName.toLowerCase() == 'mozilla') {
        slideWidget(index_flavoured, 'flavoured_update_ul_smart', 10, 50, 410);
    } else {
        slideWidget(index_flavoured, 'flavoured_update_ul_smart', 10, 40, 410);
    }
    
    return true;
}
</script>
