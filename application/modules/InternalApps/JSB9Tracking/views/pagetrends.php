<html>
	<head>
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("JSB9Tracking"); ?>" type="text/css" rel="stylesheet" />
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
	</head>	
	<body style='background-color:#FFF;'>
		<?php
			$this->load->view('JSB9Tracking/filters');
			$this->load->view('JSB9Tracking/chartView');
		?>
	</body>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		$(document).ready(function(){
	    	$("#fromDatePicker").datepicker();
	    	$("#toDatePicker").datepicker();
	    });
		
		function updateReport() {
			if($("#fromDatePicker").datepicker("getDate") > $("#toDatePicker").datepicker("getDate")){
				alert('Enter Valid Date Range.');
				return;
			}
	        trendStartDate = convertDateFormat($("#fromDatePicker").val());
			trendEndDate = convertDateFormat($("#toDatePicker").val());
			
			url = window.top.location.pathname;
			<?php if($selectedModule && $selectedModule != 'shiksha') { ?>
				url += '/<?php echo $selectedModule; ?>';
			<?php } ?>
			url += "?trendStartDate="+trendStartDate+"&trendEndDate="+trendEndDate;
			sourceApplication = $('#sourceApplication').val();
			if(sourceApplication != ''){
				url += "&sourceApplication="+sourceApplication;
			}
			window.top.location = url;
	    }
		
		function convertDateFormat(date) {
	        parts = date.split("/");
			return parts[2]+"-"+parts[0]+"-"+parts[1];
	    }
		
	   	google.load("visualization", "1", {packages:["corechart"]});
	   	google.setOnLoadCallback(drawChart);

	   function drawChart(){
			<?php
					foreach ($dailyAverageData as $page => $pageData) {
			?>
						var dataTable = new google.visualization.DataTable();
			 			dataTable.addColumn('date', 'Date');
			 			<?php
							foreach ($pageAttributes as $pageAttribute) { ?>
								dataTable.addColumn('number', '<?php echo $pageAttribute;?>', {'p': {'html': true}});
						<?php } ?>
						var twoDimensionalArray = [];
			<?php 		foreach ($pageData as $date => $attributes) {
			?>
							var data = [];
							var year = <?php echo date('Y',strtotime($date))?>;
							var month = <?php echo date('m',strtotime($date))?> -1;
							var day = <?php echo date('d',strtotime($date))?>;
	    					var date = new Date(year,month,day);
	    					data.push(date);
	    	<?php			foreach ($attributes as $attributeName => $attributeValue) {
	    	?>
	    						data.push(<?php echo $attributeValue;?>);
	    	<?php				
							}
			?>				twoDimensionalArray.push(data);
			<?php	 	}
			?>
	   
	        dataTable.addRows(twoDimensionalArray);
	        
		   
			
				/*var options = {
					title: "",
					width: 1170,
					height: 400,
					bar: {groupWidth: "80%", groupHeight:"80%"},
					hAxis: {
						title: 'Date',
						baseline: 0,
						gridlines: {
							count: 6
						}
					},
					vAxis : {title: 'No. of <?php echo $titleText;?>',textPosition : "out"},
					tooltip: { isHtml: true },
					backgroundColor: '#F8F8F8',
					colors : <?php echo json_encode($colors);?>,
					 animation:{
						duration: 1000,
						easing: 'out',
					}
				};*/

	      	var options = {
					    width: 1240,
					    height: 400,
					    title: '<?php echo $page;?>',

				    	hAxis: {title: 'Date'},
					    colors : <?php echo json_encode($colors);?>,
				      	series: {
				      				0:{targetAxisIndex:0},
				                   	1:{targetAxisIndex:0},
				                   	2:{targetAxisIndex:0},
				                   	3:{targetAxisIndex:0},
				                   	4:{targetAxisIndex:1}
				                },
				        vAxis: {
				        		textPosition : "out"
				        	},
				        animation:{
							duration: 1000,
							easing: 'out',
						},
				        /*focusTarget: 'category',*/
					    tooltip: { isHtml: true },
					    
					    backgroundColor: '#F8F8F8',
					    explorer: { axis: 'horizontal',actions: ["dragToZoom", "rightClickToReset"]}
	    		};

	 		var chart = new google.visualization.LineChart(document.getElementById('pageChart_<?php echo $page;?>'));
	 		chart.draw(dataTable, options);
	 		<?php 	}
			?>
	   }
	</script>
</html>
