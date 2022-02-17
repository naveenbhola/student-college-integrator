<html>
    <head>
        <title>Shiksha.com - ElasticSearch Monitoring</title>
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("abroad_cms"); ?>" type="text/css" rel="stylesheet" />
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("appmonitorbuttons"); ?>" type="text/css" rel="stylesheet" />
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("internalapps"); ?>" type="text/css" rel="stylesheet" />
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <style type='text/css'>
            h3 {font-size:18px; border-bottom: 1px solid #eee; padding-bottom: 10px; color:#333; margin-top:20px; margin-bottom: 10px; padding-left: 5px;}
            a.blink:link,a.blink:active,a.blink:visited {color:#245dc1; text-decoration: none; display:block; float:left; padding:5px 20px 5px 10px; line-height: 140%;}
            a.blink:hover {text-decoration: none; background: #f1f4f9;}
            ul.doclist {margin-left:0px; padding-left:0}
            ul.doclist li {margin-bottom: 5px; list-style-type: none; margin-left:0; padding-left:0}
            .smalldesc {font-size:12px; color:#999;}
            h1 {font-size: 26px;}
			a.doclink {color:#999; text-decoration:none; font-size:14px; }
			a.doclink:hover {text-decoration:underline;}
			table.failuretable { border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:13px; margin-bottom: 20px;}
			table.failuretable th { border-right:1px solid #ccc; border-bottom:1px solid #ccc; font-weight:bold; text-align: left; padding:8px 8px;}
			table.failuretable td { border-right:1px solid #ccc; border-bottom:1px solid #ccc; font-weight:normal; text-align: left; padding:15px 8px;}
			.leftd {background: #fffad2; font-weight:bold !important; color: #222;}
			.rightd {padding-left: 20px !important;}
			.servicerow {font-size:16px !important; background: #eee;}
			.resultrow {border-bottom: 1px solid #ddd; padding-bottom:15px; margin-bottom: 15px;}
			.resultrowlast {}
			.resultrowleft {float:left; width:200px;}
			.resultrowright {float:left; width:700px; color:#333;}
			ul.listitems {margin-left:14px;}
			ul.listitems li {padding-bottom: 5px;}
			.infoblockred {background: #fb3a3a; color:#fff; float: left; padding:2px 10px; border-radius:4px;}
			.infoblockblue {background: #7798b3; color:#fff; float: left; padding:2px 10px; border-radius:4px;}
			.infoblockyellow {background: #ff9800; color:#fff; float: left; padding:2px 10px; border-radius:4px;}
			.infoblockgreen {background: #8bc34a; color:#fff; float: left; padding:2px 10px; border-radius:4px;}
			
			code {
        font-family: Monaco, Consolas, "Andale Mono", "DejaVu Sans Mono", monospace;
        font-size: 95%;
        line-height: 140%;
        white-space: pre;
        white-space: pre-wrap;
        white-space: -moz-pre-wrap;
        white-space: -o-pre-wrap;
        background: #faf8f0;
		color: #2f6694;
    }
			.codeblock {background: #faf8f0; padding:10px 20px; margin: 10px 0;}
			textarea {width:600px; height:200px;}
        </style>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    </head>
    <body style="background: #fff;">
	<?php $this->load->view('InternalAppsCommon/headbar', array('app' => 'ServiceMonitor')); ?>	
	
	<div style='box-shadow: 0px 5px 4px -4px #888; padding-top:0px; margin-bottom:0px; z-index:109; position:relative; height:80px; background:#fff'>
    <div style='margin:0 auto; width:1200px; border:0px solid red;'>
		
		<div style='float:left; margin-top:	10px; margin-left:15px;'>
			<img src='/public/images/appmonitor/elasticsearch.svg' height='60' />
		</div>
		<div style='float:left; margin-top:	27px; margin-left:10px;'>
			<h1 style='font-size:24px; color:#555;'>
				ElasticSearch
			</h1>
		</div>
	</div>
	</div>
	<?php if($search != 'no') { ?>
	<div class='blockbg'>
		<div style='width:1200px; margin:0 auto;'>
				
		<div style='float:left; margin-left:15px; padding-top:3px;'>Host: </div>
		<div style='float:left; margin-left:10px; padding-top:1px;'>
			<select name='host' id='host'>
				<option value=''>Select</option>
				<?php foreach($hosts as $host) { ?>
					<option value='<?php echo $host; ?>' <?php echo $selectedHost == $host ? "selected='selected'": ""; ?>><?php echo $host; ?></option>
				<?php } ?>	
			</select>
		</div>
	
		<div style='float:left; margin-left:15px; padding-top:3px;'>Service: </div>
		<div style='float:left; margin-left:10px; padding-top:1px;'>
			<select name='service' id='service'>
				<option value=''>Select</option>
				<?php foreach($services as $host => $hostServices) { ?>
				<optgroup label="<?php echo $host; ?>">
				<?php foreach($hostServices as $service) { ?>
					<option value='<?php echo $host."::".$service; ?>' <?php echo $selectedService == $host."::".$service ? "selected='selected'": ""; ?>><?php echo $service; ?></option>
				<?php } ?>
				</optgroup>
				<?php } ?>
			</select>
		</div>
	
		<div style='float:left; margin-left:30px; padding-top:3px;'>Failure Type: </div>
		<div style='float:left; margin-left:10px; padding-top:1px;'>
			<select name='failureType' id='failureType'>
				<option value=''>Select</option>
				<?php foreach($failureTypes as $failureTypeId => $failureType) { ?>
					<option value='<?php echo $failureTypeId; ?>'  <?php echo $selectedFailureType == $failureTypeId ? "selected='selected'": ""; ?>><?php echo $failureType; ?></option>
				<?php } ?>
			</select>
		</div>
		
		<div style='float:left; margin-left:30px; padding-top:3px;'>Outage: </div>
		<div style='float:left; margin-left:10px; padding-top:1px;'>
			<select name='outageType' id='outageType'>
				<option value=''>Select</option>
				<?php foreach($outageTypes as $outageTypeId => $outageType) { ?>
					<option value='<?php echo $outageTypeId; ?>'  <?php echo $selectedOutageType == $outageTypeId ? "selected='selected'": ""; ?>><?php echo $outageType; ?></option>
				<?php } ?>
			</select>
		</div>
		
		<div style='float:left; margin-left:30px; padding-top:3px;'>Failover: </div>
		<div style='float:left; margin-left:10px; padding-top:1px;'>
			<select name='failoverType' id='failoverType'>
				<option value=''>Select</option>
				<?php foreach($failoverTypes as $failoverTypeId => $failoverType) { ?>
					<option value='<?php echo $failoverTypeId; ?>'  <?php echo $selectedFailoverType == $failoverTypeId ? "selected='selected'": ""; ?>><?php echo $failoverType; ?></option>
				<?php } ?>
			</select>
		</div>
		
		<div style='float:left; margin-left:40px;'>
			<a href='#' onclick="updateReport();" class='zbutton zsmall zgreen'>Go</a>
		</div>
		
		 <div style='clear:both'></div>
		</div>
	</div>
	<?php } ?>
	<script>
		function updateReport()
		{
			var host = $('#host').val();
			var service = $('#service').val();
			var failure = $('#failureType').val();
			var outage = $('#outageType').val();
			var failover = $('#failoverType').val();
			
			window.location = "/FailureMatrix/FailureMatrix/index?host="+host+"&service="+service+"&failure="+failure+"&outage="+outage+"&failover="+failover;
		}
	</script>