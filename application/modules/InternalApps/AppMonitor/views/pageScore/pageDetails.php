<?php

$titleText = 'Shiksha Page Scores';
$this->load->view('AppMonitor/common/header');
$detailURL = SHIKSHA_HOME."/AppMonitor/PageSpeedScore/displayPageDetails";

$result = array();
foreach ($pageScores as $page){
	$pageName = $page['pageName'];
	if(!isset($result[$pageName])){
		$result[$pageName] = $page;
	}
	if($page['device'] == 'desktop'){
		$result[$pageName]['desktopSpeedScore'] = $page['speedScore'];
	}
	else{
		$result[$pageName]['mobileSpeedScore'] = $page['speedScore'];
		$result[$pageName]['usabilityScore'] = $page['usabilityScore'];
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

    <h1 style='margin-top:18px;margin-left: 12px;color:#555;'><?=$pageName." on ".$device." stats"?></h1>

    <div style="padding: 10px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
    
        <div class="head1" style="border:0px solid red;">
        
            <div style="width:100%; padding:0px; margin-top:15px;">            

                <h2 class='sub-head' style='margin-top:40px;'>Speed Score</h2>
                <div id="speed_score" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

                <?php if($device == 'mobile'){ ?>
                <h2 class='sub-head' style='margin-top:40px;'>Usability Score</h2>
                <div id="usability_score" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
                <?php } ?>

                <h2 class='sub-head' style='margin-top:40px;'>No. of HTTP Requests</h2>
                <div id="http_requests" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

                <h2 class='sub-head' style='margin-top:40px;'>Size of Resources (unzipped)</h2>
                <div id="size_resources" style="width:1180px; height:400px; text-align: center; background: #f8f8f8"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
            </div>
            
            <div class="clearFix"></div>
            
            <h2 class='sub-head' style='margin-top:40px;'>Rule Impact (Shows which rule will impact your speed score/usability score by how much)</h2>

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
                $ruleImpact = explode('||',$pageDetails[(count($pageDetails)-1)]['ruleResult']);
                
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
        $("#myTable").tablesorter(); 
});

function updateReport() {
        trendStartDate = convertDateFormat($("#fromDatePicker").val());
        trendEndDate = convertDateFormat($("#toDatePicker").val());
        url = '/AppMonitor/PageSpeedScore/displayPageDetails/<?=str_replace(' ','-',$pageName)?>/<?=$device?>';
        window.location = url+"?trendStartDate="+trendStartDate+"&trendEndDate="+trendEndDate;
}

function convertDateFormat(date) {
        parts = date.split("/");
        return parts[2]+"-"+parts[0]+"-"+parts[1];
}

function drawChart() {

    var dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    dataTable.addColumn('number', 'Speed Score');
    dataTable.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
    dataTable.addRows(<?php echo $speedScoreDetails;?>);
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

    <?php if($device == 'mobile'){ ?>
    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    dataTable.addColumn('number', 'Usability Score');
    dataTable.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
    dataTable.addRows(<?php echo $usabilityScoreDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('usability_score'));
    chart.draw(dataTable, options);
    <?php } ?>
    
    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    dataTable.addColumn('number', 'HTTP Requests');
    dataTable.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
    dataTable.addRows(<?php echo $httpRequestsDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('http_requests'));
    chart.draw(dataTable, options);

    dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Date');
    dataTable.addColumn('number', 'HTML Size (in KB)');
    dataTable.addColumn('number', 'CSS Size (in KB)');
    dataTable.addColumn('number', 'JS Size (in KB)');
    dataTable.addColumn('number', 'Image Size (in KB)');
    dataTable.addRows(<?php echo $resourceSizeDetails;?>);
    var chart = new google.visualization.LineChart(document.getElementById('size_resources'));
    chart.draw(dataTable, options);
}

</script>
</html>