<html>
<head>
<title>MBA Dashboard</title>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("abroad_cms"); ?>" type="text/css" rel="stylesheet" />
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("header"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("category"); ?>"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
<script>
$j = $.noConflict();
</script>
<style>
	li{
		list-style : disc; 
	}
	#refineForm{
	    border: 1px solid lightgrey;
	    padding: 15px;
	    background: #F8F8F8;
	}
	#refineForm p {
		margin: 2px;
	}
	#reportTable{
		margin-top:10px;
		width:100%;
		font-size:16px;
		border-collapse: collapse;
	}
	.loader{background:#fff; padding:10px 25px; text-align:center; position:absolute; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; -moz-box-shadow:0 0 4px #939292; -webkit-box-shadow:0 0 4px #939292; box-shadow:0 0 4px #939292; font:normal 18px Tahoma, Geneva, sans-serif; color:#868585}
.loader img{vertical-align:middle; margin-right:10px}

</style>

	</head>
	<body style='background-color:white;margin:0;'>
	<div style="width: 90%;margin: auto;">
	<h1 style='color:grey;margin:10px;'>MBA Dashboard</h1>
	<form id="refineForm">
		<p>
			From Date: <input type="text" class="restrictKeyInput" id="fromDate" name="fromDate"> &nbsp;&nbsp;&nbsp;
			To Date: <input type="text" class="restrictKeyInput" id="toDate" name="toDate" disabled=""> &nbsp;&nbsp;&nbsp;
			</p>
		<p style="text-align:right;">	
			<input type="button" id="refineBtn" onclick="return refineReport();" value="Refine" >
			<input type="reset" id="resetBtn" onclick="location.reload();" value="Reset" >
		</p>
	</form>
	<table id="reportTable" border=1 class='cms-table-structure'>
		<?php $this->load->view("reports/mbaDashboardContent"); ?>
	</table>
	</div>
	<div class="loader" id="loadingImage" style="position: absolute; display: none; z-index: 9999;"><img src="/public/images/Loader2.GIF"> Loading</div>
	<div class="abroad-layer" id="overlayContainer" style="display: none;">
		<div class="abroad-layer-head clearfix">
	    	<div class="abroad-layer-logo flLt"><i alt="shiksha.com" class="layer-logo"></i></div>
	        <a href="JavaScript:void(0);" onclick="hideAbroadOverlay();" title="close" class="common-sprite close-icon flRt"></a>
	    </div>
	    
	    <div class="abroad-layer-content clearfix">
	    	<div class="abroad-layer-title" id="overlayTitle"></div>
			<div id="overlayContent"></div>
	    </div>
	</div>
	<div id="dim_bg"></div>
	<iframe scrolling="no" id="iframe_div" name="iframe_div" src="about:blank" frameborder="0" style="width:100%; height:100%;position:absolute; display:none; top:0px;left:0px; filter:alpha(opacity:20);opacity: .2;" container=""></iframe>
	</body>
	</html>
<script>

	function refineReport(){
		var formData = $j("#refineForm").serialize();
		showFilterLoader();
		$j.ajax({
			url		: "/listing/ListingReports/showMBADashboard",
			type    : "POST",
			data 	: formData+"&isAjax=1",
			success: function( data ) {
				$j("#reportTable").html(data);	
				hideFilterLoader();
			}
		});
	}
  $j(function() {
  	// $j("#fromDate").datepicker();
    $j("#fromDate").datepicker({
    	dateFormat:"dd-mm-yy",
		onSelect: function(date) {
        	initializeToDate(date);
     	}
	
	});
  });

  function initializeToDate(d){
  	arr = d.split("-");
  	var newDate = new Date(arr[2], arr[1]-1, arr[0]);
  	var endDate = newDate;
  	endDate.setDate(newDate.getDate() + 6);

  	$j("#toDate").removeAttr("disabled");
  	$j("#toDate").val("");
  	$j("#toDate").datepicker( "destroy" );
  	$j("#toDate").datepicker({
    	dateFormat:"dd-mm-yy",
		minDate : d,
		maxDate : endDate
	
	});


	$j('.restrictKeyInput').bind('keyup keydown keypress', function (evt) {
       return false;
   	});
  }
  </script>