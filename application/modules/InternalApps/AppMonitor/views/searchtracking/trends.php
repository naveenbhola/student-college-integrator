<?php 
$this->load->view('AppMonitor/common/header');
?>

<div class='blockbg'>
	<div style='width:1200px; margin:0 auto;'>
	<div style='float:left; margin-top:0px;'>	
		
	<div style='float:left; margin-left:15px; padding-top:3px;'>From Date: </div>
	<div style='float:left; margin-left:10px; padding-top:1px;'>
		<input type="text" id="fromDatePicker" readonly="readonly" value="<?php echo $trendStartDate; ?>" style='width:100px; cursor: text' />
	</div>

	<div style='float:left; margin-left:30px; padding-top:3px;'>To Date : </div>
	<div style='float:left; margin-left:10px; padding-top:1px;'>
		<input type="text" id="toDatePicker" readonly="readonly" value="<?php echo $trendEndDate; ?>"  style='width:100px; cursor: text' />
	</div>

	<div style='float:left; margin-left:30px; padding-top:3px;'>Device : </div>
	<div style='float:left; margin-left:10px; padding-top:1px;'>
		<select style='font-size:14px; padding:1px; color:#444;' name='device' id="device">
			<option value="" <?php echo ($device=='') ? 'selected="selected"': ''; ?>>Select</option>
			<option value="desktop" <?php echo ($device=='desktop') ? 'selected="selected"': ''; ?>>Desktop</option>
			<option value="mobile" <?php echo ($device=='mobile') ? 'selected="selected"': ''; ?>>Mobile</option>
		</select>
	</div>
	</div>
	<div style='float:left; margin-left:40px;'>
		<a href='#' onclick="updateReport();" class='zbutton zsmall zgreen'>Go</a>
	</div>
	 <div style='clear:both'></div>
	</div>
</div>
<div style='margin:0 auto;width:1240px;'>
	<?php 
		foreach ($chartKeys as $key) {
			if(in_array($key,array('searchZrps'))){
				?>
				<div class="flRt">PageType : 
					<select class="dropdownInputClass mbutton2" id="pageType_<?php echo $key; ?>">
						<option value="open">Open</option>
						<option value="close">Close</option>
						<option value="college">College</option>
						<option value="career">Career</option>
						<option value="exam">Exam</option>
					</select>
				</div>
				<?php
			}
			else if(in_array($key,array('getFiltersCounts','getTotalFilterCounts','getTupleClicksAfterFilters','getTotalTupleClickCounts'))){
				?>
				<div class="flRt">PageType : 
					<select class="dropdownInputClass mbutton2" id="pageType_<?php echo $key; ?>">
						<option value="search">Select</option>
						<option value="open">Open</option>
						<option value="close">Close</option>
					</select>
				</div>
				<?php
			}
			else if(in_array($key,array('getAdvancedFilterCounts'))){
				?>
				<div class="flRt">PageType : 
					<select class="dropdownInputClass mbutton2" id="pageType_<?php echo $key; ?>">
						<option value="course">Course</option>
						<option value="college">Institute</option>
					</select>
				</div>
				<?php
			}
			else if(in_array($key,array('getCriteriaCounts'))){
				?>
				<div class="flRt">Criteria Type : 
					<select class="dropdownInputClass mbutton2" id="pageType_<?php echo $key; ?>">
						<option value="spellcheck">SpellCheck</option>
						<option value="relax">Relax</option>
						<option value="relaxandspellcheck">Relax And Spellcheck</option>
					</select>
				</div>
				<?php
			}
			?>
			<div class="clearFix"></div>
			<div class="pageChart" id="pageChart_<?php echo $key;?>"><img src='/public/images/appmonitor/loader.gif' /></div>
			<?php
		}
	?>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	var xhrObjs = {},chartKeys = JSON.parse('<?php echo json_encode($chartKeys);?>');
	$(document).ready(function(){
		$("#fromDatePicker").datepicker();
		$("#toDatePicker").datepicker();

		chartKeys.forEach(function(ele){
			if($("pageType_"+ele)){
				$(document).on('change','#pageType_'+ele,function(event){
					drawChartByKey(ele);
				});
			}
		});
	});

	var drawChart = function(chartKey){
		return function(response,status,xhr){
			response = JSON.parse(response);
			var dataTable = new google.visualization.DataTable();
			dataTable.addColumn('date','Date');
			response['columns'].forEach(function(ele){
				dataTable.addColumn('number',ele);
			});
			var data = [];
			for(var date in response['data']){
				var temp = [];
				temp.push(new Date(date));
				if(response['data'][date] && typeof response['data'][date] == 'object'){
					response['data'][date].forEach(function(ele){temp.push(+ele);});
				}
				else{
					temp.push(+response['data'][date]);
				}
				data.push(temp);
			}
			dataTable.addRows(data);

			var options = {
				'chart' : {
					'title' : response['title']
				},
				// 'vAxis': {'logScale' : 'true'},
				'focusTarget' : 'category',
				'animation' : {'startup' : true,'easing' : 'out'},
				'explorer' : {'actions':['dragToZoom','rightClickToReset'],'axis':'horizontal','keepInBounds':true}
			};
			options['height'] = typeof(response['height']) != 'undefined' ? response['height'] : 400;
			var chart = new google.charts.Line(document.getElementById('pageChart_'+chartKey));
			chart.draw(dataTable,options);
		}
	}

	google.charts.load("current",{packages:["line"]});

	google.charts.setOnLoadCallback(function(){
		chartKeys.forEach(drawChartByKey);
	});

	function drawChartByKey(key){
		$('#pageChart_'+key).html('<img src="/public/images/appmonitor/loader.gif" />');
		var trendStartDate = convertDateFormat($("#fromDatePicker").val());
		var trendEndDate = convertDateFormat($("#toDatePicker").val());
		var postData = {'fromDate':trendStartDate,'toDate':trendEndDate,'device':$('#device').val()};
		if($('#pageType_'+key)){
			postData['pageType'] = $('#pageType_'+key).val();
		}
		xhrObjs[key] = $.post("/AppMonitor/SearchTracking/"+key+'Trends',postData,drawChart(key));
	}

	function convertDateFormat(date) {
		parts = date.split("/");
		return parts[2]+"-"+parts[0]+"-"+parts[1];
	}

	function updateReport() {
		if($("#fromDatePicker").datepicker("getDate") > $("#toDatePicker").datepicker("getDate")){
			alert('Enter Valid Date Range.');
			return;
		}
        trendStartDate = convertDateFormat($("#fromDatePicker").val());
		trendEndDate = convertDateFormat($("#toDatePicker").val());
		
		url = window.top.location.pathname;
		url += "?trendStartDate="+trendStartDate+"&trendEndDate="+trendEndDate;
		if($('#device').val()){
			url += "&device="+$('#device').val();
		}
		window.location = url;
    }

</script>