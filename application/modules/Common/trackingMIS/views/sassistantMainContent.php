
<div class="right_col" role="main">
<div class="row x_title <?php echo $domesticClass;?>">
	<div class="col-md-2"></div>
	<div class="col-md-2"></div>
	<div class="col-md-2"></div>
	<div class="col-md-1"></div>
	<div class="col-md-3">
	    <div class="dropdown">
	        <button id="reportrange" class="btn btn-default col-md-11 col-sm-11 col-xs-11 white_space_normal_overwrite"
	                style="background: #fff;"><b class="caret margin-right-2"></b>
	            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
	            <span></span>
	        </button>
	    </div>
	</div>

	<div class="col-md-2 pull-right">
	    <button type="button" id="submit" class="btn btn-primary">Apply Filters</button>
	</div>
</div>

<div class="row tile_count">
<?php

	/*
		0.	Top filters :
				Date range

		1.	Top Tiles :
				total chats
				chats per session avg

		2.	Line Charts :
				chats per day
				chat per sessions avg
	*/

	$colMDClass = 'col-md-3';

	// Top tiles
    foreach ($diffChartFilter['TOP_TILES'] as $key => $value) {
        $class = 'bgColor';
        //$class = 'defaultColor';
	?>

	    <div class="animated flipInY <?php echo $colMDClass;?> col-sm-4 col-xs-4 tile_stats_count <?php echo $class; ?>" id="<?php echo 'topHeading_'.$value['id']; ?>" data-text="<?php echo $key; ?>">
	        <div class="left"></div>
	        <div class="right">
	            <span class="count_top" style="color: #73879c !important;"><i class="fa fa-user"></i> <?php echo $value['title']; ?></span>
	            <div class="count topTiles_size" id="<?php echo $value['id']; ?>">0</div>
	            <span class="count_bottom"><i id="<?php echo 'bottom_'.$value['id']; ?>"><i class="fa"></i></i></span>
	            <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>

	        </div>
	    </div>
	    <div class="row">
	<?php 
		$diffChartFilter['topTiles'][] = $key;
	} 

	// Trends : total Chats
	//$this->load->view('trackingMIS/lineChart',$data);

	foreach ($diffChartFilter['lineChart'] as $key => $value) { 
		if($value['type'] == "comparision" && $value['id'] == "sessionDurationWithAssistant"){
			$comparisionChartHeading = $value['heading']." Without Assistant Used (mm:ss)";
			$chartHeading = $value['heading']." With Assistant Used (mm:ss)";
		}else if($value['type'] == "comparision" && $value['id'] == "sessionCountWithAssistant"){
			$comparisionChartHeading = $value['heading']." Without Assistant Used";
			$chartHeading = $value['heading']." With Assistant Used";
		}else if($value['type'] == "comparision" && $value['id'] == "pagesCountWithAssistant"){
			$comparisionChartHeading = $value['heading']." Without Assistant Used";
			$chartHeading = $value['heading']." With Assistant Used";
		}else if($value['type'] == "comparision" && $value['id'] == "queriesVsAnsQueries"){
			$chartHeading = "Total no of queries";
			$comparisionChartHeading = "Total answered queries";
		}else if($value['type'] == "comparision" && $value['id'] == "queriesVsAnsQueriesPerSessions"){
			$chartHeading = "total no of queries / 1000 sessions";
			$comparisionChartHeading = "total answered queries / 1000 sessions";
		}else{
			$chartHeading = $value['heading'];
		}
	?>
		
	    <div class="col-md-12 col-sm-12 col-xs-12">
	        <div id="showView_<?php echo $value['id'];?>" lineChartId = "<?php echo $value['id'];?>">
	            <div style="float:left" >
	                <h6>
	                    <div class='dateRangeLineChart_<?php echo $value['heading'];?>'></div>
	                    <div class='lineChartHeading_<?php echo $value['id'];?>'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="square"></span >&nbsp;&nbsp;<span id="line_heading"><?php echo $chartHeading;?></span></div>
	                </h6>
	                <h6>
	                <div class='dateRangeLineChart1_<?php echo $value['heading'];?>' style="display: none;"></div>
	                    <div class='lineChartHeading1_<?php echo $value['id'];?>' style="display: none;">&nbsp&nbsp&nbsp&nbsp<span class="square1"></span>&nbsp&nbsp<span id="line_heading1"><?php echo $comparisionChartHeading;?></span></div>
	                </h6>
	            </div>
	            <div style="float:right"  class = "<?php echo $value['id'];?>" lineChartId = "<?php echo $value['id'];?>">
	                <div class="col-md-3">
	                    <div class="dropdown">
	                        <button class="btn btn-default fixed_width_100 bgcolor daily_<?php echo $value['id'];?> daily <?php echo $value['id'];?>" type="button" id="daily" >
	                            Day
	                        </button>
	                    </div> 
	                </div>

	                <div class="col-md-3">
	                    <div class="dropdown">
	                        <button class="btn btn-default  fixed_width_100 weekly_<?php echo $value['id'];?> weekly <?php echo $value['id'];?>" type="button" id="weekly" >
	                            Week
	                        </button>      
	                    </div>  
	                </div>

	                <div class="col-md-3">
	                    <div class="dropdown">
	                        <button class="btn btn-default fixed_width_100 monthly monthly_<?php echo $value['id'];?> <?php echo $value['id'];?>" type="button" id="monthly" >
	                            Month
	                        </button> 
	                    </div>
	                </div>
	            </div>
	        </div>
	        <br><br><br>
	        <div class="col-md-9 col-sm-9 col-xs-12" style="width:100%">
	            <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
	            <div style="width: 90%;" id='lineChartDiv_<?php echo $value['id'];?>'>
	                <div id="canvas_<?php echo $value['id'];?>" headingId = "<?php echo $value['id'];?>" class="demo-placeholder" style="width: 100%; height:270px;"></div>
	            </div>
	            <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
	        </div>
	        <div class="col-md-3 col-sm-3 col-xs-12 bg-transparent">
	        	<div class="clearfix"></div>
	    	</div>
		</div>
		<div id="message_<?php echo $value['id'];?>" class="message" >
		<h2>No record found for selected criteria.</h2>
		</div>
	<?php } ?>
	</div>

    <?php foreach($diffChartFilter['BAR_GRAPH'] as $id => $title){
		$this->load->view('trackingMIS/donutChart', array(
				'id' => $id,
				'title' => $title['heading'],
				'extraHeadingReq' => false,
				'tableHeading' => $title['tableHeading']
		));
	} ?>
</div>
</div>
