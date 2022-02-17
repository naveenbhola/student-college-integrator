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

$chartJSON = array();
foreach ($chartDetails as $data){
	$pageName = $data['pageName'];
	$newDate = date("d M,Y", strtotime($data['creationDate']));
	if($data['device'] == 'desktop'){
		$chartJSON[$pageName]['desktopJSON'][] = array($newDate, intVal($data['speedScore']));
	}
	else{
		$chartJSON[$pageName]['mobileJSON'][] = array($newDate, intVal($data['speedScore']));
		$chartJSON[$pageName]['mobileUsabilityJSON'][] = array($newDate, intVal($data['usabilityScore']));		
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
<div class="clearFix"></div>
<div style='background-color:white;margin:0 auto;width:1200px; padding-bottom: 40px; border:0px solid red;'>

<div style='width:100%;min-height:220px;'>
       <div class="exceptionCounter" id="vexceptionCounter" style='width:250px;'>
	      <div style="font-size: 21px;margin-top: 37px;" id='counterTitle'>Pages Examined</div>
	      <div class="animateNumber" style="font-size: 75px;" id='countVal'><?=count($result)?></div>
	      <div id='counterTimeWindow' style='margin-top:5px; font-weight:bold; color:#777;'></div>
      </div>
      <div class='clearFix'></div>
</div>

<table id="myTable" class='tablesorter exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1180'>
    <thead>
    <tr style='background: none repeat scroll 0 0 #f7f7f7;'>
        <th width='50'>S.No.</th>
        <th width='100'>Team Name</th>
        <th width='160'>Page Name</th>
        <th width='300'>URL</th>
        <th width='100'>GPI Desktop Speed <br/>Score</th>
        <th width='100'>GPI Mobile Speed <br/>Score</th>
        <th width='100'>GPI Mobile Usability <br/>Score</th>
    </tr>
    </thead>
    <tbody>
<?php
if(count($result) > 0) {
    $j = 1;
    foreach ($result as $row) {

        $background_color = "";
        if($j % 2 == 0){
            $background_color = "background-color:#F3FAF2";
        }

        echo "<tr style='".$background_color.";'>";
        echo "<td valign='top'>".$j."</td>";
        echo "<td valign='top'>".$row['teamName']."</td>";
        echo "<td valign='top'>".$row['pageName']."</td>";
        echo "<td valign='top'>".$row['URL']."</td>";
        echo "<td valign='top' style='text-align: center;font-size: 20px;'>
	<a href='$detailURL/".str_replace(' ','-',$row['pageName'])."/desktop'>".round($row['desktopSpeedScore'])."
	<div id='desktop_speed_".$row['pageName']."' style='width: 150px; height: 60px; background: #f8f8f8; margin:10px;'></div>
	</a>
	</td>";
        echo "<td valign='top' style='text-align: center;font-size: 20px;'>
	<a href='$detailURL/".str_replace(' ','-',$row['pageName'])."/mobile'>".round($row['mobileSpeedScore'])."
	<div id='mobile_speed_".$row['pageName']."' style='width: 150px; height: 60px;background: #f8f8f8; margin:10px;'></div>
	</a>
	</td>";
        echo "<td valign='top' style='text-align: center;font-size: 20px;'>
	<a href='$detailURL/".str_replace(' ','-',$row['pageName'])."/mobile'>".round($row['usabilityScore'])."
	<div id='mobile_usability_".$row['pageName']."' style='width: 150px; height: 60px;background: #f8f8f8; margin:10px;'></div>
	</a>
	</td>";
        echo "</tr>";
        $j++;
    }
    echo "</tbody></table>";
}
else {
    echo "<tr><td colspan='7' style='font-size:16px;color:green;font-weight:bold; padding:20px 0;' align='center' >No results found.</td></tr></tbody></table>";
}

?>	
</div>
</body>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
$(document).ready(function() 
    {
        $("#myTable").tablesorter(); 
    } 
);

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {

    var options = {
            title: "",
            width: 150,
            height: 60,
            bar: {groupWidth: "80%", groupHeight:"80%"},
            hAxis: {textPosition: 'none'},
	    legend: {position: 'none'},
            backgroundColor: '#F8F8F8',
	    colors: ['#000000'],
            animation:{duration: 1000,easing: 'out'}
    };

    <?php
	foreach ($chartJSON as $pageName=>$chart){
		$desktopData = (isset($chart['desktopJSON']) && is_array($chart['desktopJSON']))?json_encode($chart['desktopJSON']):'';
		$mobileData = (isset($chart['mobileJSON']) && is_array($chart['mobileJSON']))?json_encode($chart['mobileJSON']):'';
		$mobileUsabilityData = (isset($chart['mobileUsabilityJSON']) && is_array($chart['mobileUsabilityJSON']))?json_encode($chart['mobileUsabilityJSON']):'';
	?>
		<?php if(isset($desktopData) && $desktopData!=''){ ?>
		var dataTable = new google.visualization.DataTable();
		dataTable.addColumn('string', 'Date');
		dataTable.addColumn('number', 'Speed Score');
		dataTable.addRows(<?php echo $desktopData;?>);
		var chart = new google.visualization.LineChart(document.getElementById('<?php echo "desktop_speed_$pageName";?>'));
		chart.draw(dataTable, options);
	    
		<?php }
		if(isset($mobileData) && $mobileData!=''){ ?>
		var dataTable = new google.visualization.DataTable();
		dataTable.addColumn('string', 'Date');
		dataTable.addColumn('number', 'Speed Score');
		dataTable.addRows(<?php echo $mobileData;?>);
		var chart = new google.visualization.LineChart(document.getElementById('<?php echo "mobile_speed_$pageName";?>'));
		chart.draw(dataTable, options);
	       
	        <?php }
		if(isset($mobileUsabilityData) && $mobileUsabilityData!=''){ ?>
		var dataTable = new google.visualization.DataTable();
		dataTable.addColumn('string', 'Date');
		dataTable.addColumn('number', 'Speed Score');
		dataTable.addRows(<?php echo $mobileUsabilityData;?>);
		var chart = new google.visualization.LineChart(document.getElementById('<?php echo "mobile_usability_$pageName";?>'));
		chart.draw(dataTable, options);
		<?php } ?>
    <?php		
	}
    ?>
}

</script>
</html>