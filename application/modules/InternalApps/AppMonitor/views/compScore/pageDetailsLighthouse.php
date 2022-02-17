<?php

$titleText = 'Shiksha Competition Scores (LH)';
$this->load->view('AppMonitor/common/header');
$detailURL = SHIKSHA_HOME."/AppMonitor/competitorsPage/displayPageDetailsLH";

$result = array();
foreach ($pageScores as $page){
	$pageName = $page['pageName'];
	if(!isset($result[$pageName])){
		$result[$pageName] = $page;
	}
	if($page['device'] == 'desktop'){
		$result[$pageName]['desktopSpeedScore'] = $page['googleScore'];
	}
	else{
		$result[$pageName]['mobileSpeedScore'] = $page['googleScore'];
	}		
}
?>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tablesorter.min"); ?>"></script>
<style>
th.headerSortUp { 
    background-image: url(/public/images/small_asc.gif) !important; 
    background-color: #ADD8E6; 
} 
th.headerSortDown { 
    background-image: url(/public/images/small_desc.gif) !important; 
    background-color: #ADD8E6; 
} 
th.header { 
    background-image: url(/public/images/small.gif); 
    cursor: pointer; 
    font-weight: bold; 
    background-repeat: no-repeat; 
    background-position: center left; 
    padding-left: 20px; 
    border-right: 1px solid #dad9c7; 
    margin-left: -1px; 
} 	
</style>

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
        </div>
        <div style='float:left; margin-left:40px;'>
                <a href='#' onclick="updateReport();" class='zbutton zsmall zgreen'>Go</a>
        </div>
         <div style='clear:both'></div>
        </div>
</div>


<div class="clearFix"></div>


    

<div style='background-color:white;margin:0 auto;width:1200px; padding-bottom: 40px; border:0px solid red;'>

    <h1 style='margin-top:18px;margin-left: 12px;color:#555;'><?=$pageName." on ".ucfirst($device)." stats"?></h1>

    <div style="padding: 10px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
    
        <div class="head1" style="border:0px solid red;">
        
            <div style="width:100%; padding:0px; margin-top:15px;">            

                <h2 class='sub-head' style='margin-top:40px;'>Google Speed Score</h2>
                <div id="speed_score" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

                <h2 class='sub-head' style='margin-top:40px;'>First Contentful Paint (in ms)</h2>
                <div id="fcp_score" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

                <h2 class='sub-head' style='margin-top:40px;'>First Meaningful Paint (in ms)</h2>
                <div id="fmp_score" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

                <h2 class='sub-head' style='margin-top:40px;'>Speed Index (in ms)</h2>
                <div id="speed_index" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

                <h2 class='sub-head' style='margin-top:40px;'>First CPU Idle (in ms)</h2>
                <div id="cpu_idle" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
		
                <h2 class='sub-head' style='margin-top:40px;'>Time to Interactive (in ms)</h2>
                <div id="interactive" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

                <h2 class='sub-head' style='margin-top:40px;'>Input Latency (in ms)</h2>
                <div id="input_latency" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

                <h2 class='sub-head' style='margin-top:40px;'>Time to First Byte (in ms)</h2>
                <div id="ttfb_score" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
		
                <h2 class='sub-head' style='margin-top:40px;'>Main Thread Breakdown (in ms)</h2>
                <div id="main_thread" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

                <h2 class='sub-head' style='margin-top:40px;'>Boot Up Time (in ms)</h2>
                <div id="bootup_time" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

                <h2 class='sub-head' style='margin-top:40px;'>DOM Size</h2>
                <div id="dom_size" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
            </div>
            
            <div class="clearFix"></div>
            
	    <!--
            <h2 class='sub-head' style='margin-top:40px;'>Shiksha's Rule Impact (Shows which rule will impact your speed score/usability score by how much)</h2>

            <table id="myTable" class='tablesorter exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1180'>
                <thead>
                <tr style='background: none repeat scroll 0 0 #f7f7f7;'>
                    <th width='50'>S.No.</th>
                    <th>Rule Name</th>
                    <th width='120'>Rule Impact</th>
                </tr>
                </thead>
                <tbody>
                <?php
		foreach ($pageDetails as $pageD){
			if($pageD['site'] == 'Shiksha'){
				$ruleImpact = explode('||',$pageD['ruleResult']);
			}
		}
                
                if(count($ruleImpact) > 0) {
                    $j = 1;
                    foreach ($ruleImpact as $row) {
                        if($row!=''){
                            $background_color = "";
                            if($j % 2 == 0){
                                $background_color = "background-color:#F3FAF2";
                            }
                            $ruleSet = explode(',',$row);
                            echo "<tr style='".$background_color.";'>";
                            echo "<td valign='top' width='50'>".$j."</td>";
                            echo "<td valign='top'>".$ruleSet[0]."</td>";
                            echo "<td valign='top' width='150'>".$ruleSet[1]."</td>";
                            echo "</tr>";
                            $j++;
                        }
                    }
                    echo "</tbody></table>";
                }
                else {
                    echo "<tr><td colspan='3' style='font-size:16px;color:green;font-weight:bold; padding:20px 0;' align='center' >No rule found.</td></tr></tbody></table>";
                }
                
                ?>			
            -->
        </div>
    
    </div>
</div>

    
</body>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

$(document).ready(function(){
        $("#fromDatePicker").datepicker();
        $("#toDatePicker").datepicker();
        //$("#myTable").tablesorter(); 
});

function updateReport() {
        trendStartDate = convertDateFormat($("#fromDatePicker").val());
        trendEndDate = convertDateFormat($("#toDatePicker").val());
        url = '/AppMonitor/competitorsPage/displayPageDetailsLH/<?=str_replace(' ','-',$pageName)?>/<?=$device?>';
        window.location = url+"?trendStartDate="+trendStartDate+"&trendEndDate="+trendEndDate;
}

function convertDateFormat(date) {
        parts = date.split("/");
        return parts[2]+"-"+parts[0]+"-"+parts[1];
}

function drawChart() {

    var dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    <?php
    foreach ($siteList as $site){
	echo "dataTable.addColumn('number', '$site');";
    }
    ?>
    dataTable.addRows(<?php echo $googleScoreDetails;?>);
    var options = {
            title: "",
            width: 1170,
            height: 400,
            bar: {groupWidth: "80%", groupHeight:"80%"},
            hAxis: {title: 'Date',baseline: 0,gridlines: {count: 6}},
            tooltip: { isHtml: true },
            backgroundColor: '#F8F8F8',
            animation:{duration: 1000,easing: 'out'}
    };
    var chart = new google.visualization.LineChart(document.getElementById('speed_score'));
    chart.draw(dataTable, options);

    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    <?php
    foreach ($siteList as $site){
	echo "dataTable.addColumn('number', '$site');";
    }
    ?>
    dataTable.addRows(<?php echo $fcpDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('fcp_score'));
    chart.draw(dataTable, options);
    
    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    <?php
    foreach ($siteList as $site){
	echo "dataTable.addColumn('number', '$site');";
    }
    ?>
    dataTable.addRows(<?php echo $fmpDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('fmp_score'));
    chart.draw(dataTable, options);

    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    <?php
    foreach ($siteList as $site){
	echo "dataTable.addColumn('number', '$site');";
    }
    ?>
    dataTable.addRows(<?php echo $speedIndexDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('speed_index'));
    chart.draw(dataTable, options);
    
    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    <?php
    foreach ($siteList as $site){
	echo "dataTable.addColumn('number', '$site');";
    }
    ?>
    dataTable.addRows(<?php echo $firstCPUIdleDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('cpu_idle'));
    chart.draw(dataTable, options);

    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    <?php
    foreach ($siteList as $site){
	echo "dataTable.addColumn('number', '$site');";
    }
    ?>
    dataTable.addRows(<?php echo $interactiveDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('interactive'));
    chart.draw(dataTable, options);

    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    <?php
    foreach ($siteList as $site){
	echo "dataTable.addColumn('number', '$site');";
    }
    ?>
    dataTable.addRows(<?php echo $inputLatencyDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('input_latency'));
    chart.draw(dataTable, options);
    
    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    <?php
    foreach ($siteList as $site){
	echo "dataTable.addColumn('number', '$site');";
    }
    ?>
    dataTable.addRows(<?php echo $mainThreadBreakdownDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('main_thread'));
    chart.draw(dataTable, options);
    
    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    <?php
    foreach ($siteList as $site){
	echo "dataTable.addColumn('number', '$site');";
    }
    ?>
    dataTable.addRows(<?php echo $ttfbDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('ttfb_score'));
    chart.draw(dataTable, options);

    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    <?php
    foreach ($siteList as $site){
	echo "dataTable.addColumn('number', '$site');";
    }
    ?>
    dataTable.addRows(<?php echo $bootupTimeDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('bootup_time'));
    chart.draw(dataTable, options);

    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    <?php
    foreach ($siteList as $site){
	echo "dataTable.addColumn('number', '$site');";
    }
    ?>
    dataTable.addRows(<?php echo $domSizeDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('dom_size'));
    chart.draw(dataTable, options);
    
}

</script>
</html>