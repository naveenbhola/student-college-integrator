<?php

	$errorClasses = array();
	foreach($rs as $key=>$value)
		{
				$errorClasses[] = $value['ErrorClass'];
		}
		$errorClasses = array_unique($errorClasses);
?>
<html>
<head>
<title>Exception Log</title>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("abroad_cms"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
<script>
				function copyQuery(id)
				{
					var text = $(id).closest('td').attr('title');
					window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
					
				}
</script>
<style>
	li{
		list-style : disc; 
	}
	#refineForm{
	    border: 1px solid lightgrey;
	    margin: 14px;
	    padding: 15px;
	    background: #F8F8F8;
	}
</style>

	</head>
	<body style='background-color:white;'>
	<div>
	<h1 style='color:grey;margin:10px;'>Exception Logs</h1>
	<form id="refineForm">
		<p>
			From Date: <input type="text" id="fromDate" name="fromDate"> &nbsp;&nbsp;&nbsp;
			To Date: <input type="text" id="toDate" name="toDate"> &nbsp;&nbsp;&nbsp;
			Error Type <select name="errorClass"><option value="">Select</option><?php foreach ($errorClasses as $value) {
				echo "<option value='".$value."'>".$value."</option>";
			}?></select></p>
		<p style="text-align:right;">	
			<input type="button" id="refineBtn" onclick="return refineReport();" value="Refine">
			<input type="reset" id="resetBtn" onclick="location.reload();" value="Reset">
		</p>
	</form>
	<table id="reportTable" border=1 style='margin:1%;width:98%;font-size:12px;border-collapse: collapse;' class='cms-table-structure'>
		<?php $this->load->view("exceptionTableView"); ?>
	</table>
	</div>
	</body>
	</html>
<script>

	function refineReport(){
		var formData = $("#refineForm").serialize();
		$.ajax({
			url		: "/common/ErrorExceptionLogger/showExceptionLogs",
			type    : "POST",
			data 	: formData+"&isajax=1",
			success: function( data ) {
				$("#reportTable").html(data);	
			}
		});
	}
  $(function() {
  	$("#toDate, #fromDate").datepicker();
    $("#toDate, #fromDate").datepicker("option", "dateFormat", "dd-mm-yy");
  });
  </script>