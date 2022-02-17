<?php
$titleText = 'Shiksha Competition Scores (LH)';
$this->load->view('AppMonitor/common/header');
$detailURL = SHIKSHA_HOME."/AppMonitor/competitorsPage/displayPageDetailsLH";

global $legendsArray;
$legendsArray = array(
	'desktop' => array(
			'SCORE' => array(95,80),
			'FCP' => array(800,1200),
			'FMP' => array(1000,1500),
			'SPEEDINDEX' => array(2000,3000),
			'INTERACTIVE' => array(2500,3500),
			'INPUT_LATENCY' => array(50,100),
			'FIRST_CPU_IDLE' => array(2000,3000),
			'TTFB' => array(400,600),
			'DOM_SIZE' => array(1000,2000),
			'BOOT_UP_TIME' => array(1500,3000)
			   ),
	'mobile' => array(
			'SCORE' => array(95,80),
			'FCP' => array(800,1200),
			'FMP' => array(1000,1500),
			'SPEEDINDEX' => array(2000,3000),
			'INTERACTIVE' => array(2500,3500),
			'INPUT_LATENCY' => array(50,100),
			'FIRST_CPU_IDLE' => array(2000,3000),
			'TTFB' => array(400,600),
			'DOM_SIZE' => array(1000,2000),
			'BOOT_UP_TIME' => array(1500,3000)
			   )
	);

function colorDecider($value,$type,$platform){
	global $legendsArray;
	$type = strtoupper($type);	
	
	if($value == ''){
		return '';
	}

	if($type == 'SCORE'){
		if($value >= $legendsArray[$platform]['SCORE'][0]){
			return 'limegreen';
		}
		else if($value >= $legendsArray[$platform]['SCORE'][1]){
			return 'orange';
		}
		return 'red';		
	}
	else{
		if($value <= $legendsArray[$platform][$type][0]){
			return 'limegreen';
		}
		else if ($value <= $legendsArray[$platform][$type][1]){
			return 'orange';
		}
		return 'red';
	}
}

function checkSRT($value){
	if($value <= 600 && $value > 0){
		return '<=600';
	}
	return $value;
}

function displayBestStar($valueArr,$siteName,$type='other'){
	if(count($valueArr) == 1){
		return;
	}
	
	if($type != 'score'){
		if($type == 'score'){
			if($valueArr[$siteName] <= 200 && $valueArr[$siteName] > 0){
				return "<i class='icons ic_best'></i>";
			}
		}

		$mins = array_keys($valueArr, min($valueArr));
		if($mins[0] == $siteName){
			return "<i class='icons ic_best'></i>";
		}		
	}
	else{
		$maxs = array_keys($valueArr, max($valueArr));
		if($maxs[0] == $siteName){
			return "<i class='icons ic_best'></i>";
		}		
	}
}

function showStats($value){
	if($value == 'Up'){
		return '<div class="arrow-up"></div>';
	}
	else if($value == 'Down'){
		return '<div class="arrow-down"></div>';
	}
}

global $teamsLinkingData;
$teamsLinkingData = $teamsLinking;
function showTeamName($pageName){
	global $teamsLinkingData;
	foreach ($teamsLinkingData as $team){
		if($team['pageName'] == $pageName){
			return $team['team_name'];
		}
	}
	return 'SA';
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
.scoreClass {
    color: #ffffff;
    position: relative
}
.scoreClass .ic_best{right: 5px;margin-top:-10px;top:50%;}
.icons {
    background-image: url(https://images.shiksha.ws/pwa/public/images/desktop/shiksha-icons-sprite-6.png);
    background-repeat: no-repeat;
    display: inline-block;
}
.ic_best {
    height: 16px;
    width: 23px;
    background-position: -245px -25px;
    position: absolute;
    margin-left: 5px;
}
.arrow-up {
  width: 0; 
  height: 0; 
  border-left: 10px solid transparent;
  border-right: 10px solid transparent;
  border-bottom: 10px solid black;
}
.arrow-down {
  width: 0; 
  height: 0; 
  border-left: 10px solid transparent;
  border-right: 10px solid transparent;  
  border-top: 10px solid black;
}
</style>
<div class="clearFix"></div>
<div style='background-color:white;margin:0 auto;width:1200px; padding-bottom: 40px; border:0px solid red;'>

<div style='width:100%;min-height:220px;'>
       <div class="exceptionCounter" id="vexceptionCounter" style='width:250px;'>
	      <div style="font-size: 21px;margin-top: 37px;" id='counterTitle'>Pages Examined</div>
	      <?php if($teamFilter!='StudyAbroad')
	      	{?>
	      <div class="animateNumber" style="font-size: 75px;" id='countVal'><?=count($pageScores['Domestic'])?></div>
	  <?php }else
	  		{ ?>
	  	   <div class="animateNumber" style="font-size: 75px;" id='countVal'><?=count($pageScores['Studyabroad'])?></div>
	  		<?php } ?>
	      <div id='counterTimeWindow' style='margin-top:5px; font-weight:bold; color:#777;'></div>
	  	
      </div>
       
       <div class="exceptionCounter" style="float:right;margin-right:15px; width: 40%; height: 221px;">
		<table border='0' cellspacing='0' cellpadding='2' style="width:100%">
			<tr style="background-color: lightgrey; font-size:16px;font-weight:bold">
				<td>Legends</td>
				<td colspan='2' style="text-align: center;">Mobile</td>
				<td colspan='2' style="text-align: center;">Desktop</td>
			</tr>				
			<tr>
				<td>Score</td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['SCORE'][0],'SCORE','mobile')?>">>=<?=$legendsArray['mobile']['SCORE'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['SCORE'][1],'SCORE','mobile')?>">>=<?=$legendsArray['mobile']['SCORE'][1]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['SCORE'][0],'SCORE','desktop')?>">>=<?=$legendsArray['desktop']['SCORE'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['SCORE'][1],'SCORE','desktop')?>">>=<?=$legendsArray['desktop']['SCORE'][1]?></td>
			</tr>				
			<tr>
				<td>FCP (in ms)</td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['FCP'][0],'fcp','mobile')?>"><=<?=$legendsArray['mobile']['FCP'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['FCP'][1],'fcp','mobile')?>"><=<?=$legendsArray['mobile']['FCP'][1]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['FCP'][0],'fcp','desktop')?>"><=<?=$legendsArray['desktop']['FCP'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['FCP'][1],'fcp','desktop')?>"><=<?=$legendsArray['desktop']['FCP'][1]?></td>
			</tr>				
			<tr>
				<td>FMP (in ms)</td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['FMP'][0],'fmp','mobile')?>"><=<?=$legendsArray['mobile']['FMP'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['FMP'][1],'fmp','mobile')?>"><=<?=$legendsArray['mobile']['FMP'][1]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['FMP'][0],'fmp','desktop')?>"><=<?=$legendsArray['desktop']['FMP'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['FMP'][1],'fmp','desktop')?>"><=<?=$legendsArray['desktop']['FMP'][1]?></td>
			</tr>				
			<tr>
				<td>SPEED INDEX (in ms)</td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['SPEEDINDEX'][0],'speedIndex','mobile')?>"><=<?=$legendsArray['mobile']['SPEEDINDEX'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['SPEEDINDEX'][1],'speedIndex','mobile')?>"><=<?=$legendsArray['mobile']['SPEEDINDEX'][1]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['SPEEDINDEX'][0],'speedIndex','desktop')?>"><=<?=$legendsArray['desktop']['SPEEDINDEX'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['SPEEDINDEX'][1],'speedIndex','desktop')?>"><=<?=$legendsArray['desktop']['SPEEDINDEX'][1]?></td>
			</tr>				
			<tr>
				<td>INTERACTIVE (in ms)</td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['INTERACTIVE'][0],'interactive','mobile')?>"><=<?=$legendsArray['mobile']['INTERACTIVE'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['INTERACTIVE'][1],'interactive','mobile')?>"><=<?=$legendsArray['mobile']['INTERACTIVE'][1]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['INTERACTIVE'][0],'interactive','desktop')?>"><=<?=$legendsArray['desktop']['INTERACTIVE'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['INTERACTIVE'][1],'interactive','desktop')?>"><=<?=$legendsArray['desktop']['INTERACTIVE'][1]?></td>
			</tr>				
			<tr>
				<td>INPUT LATENCY (in ms)</td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['INPUT_LATENCY'][0],'INPUT_LATENCY','mobile')?>"><=<?=$legendsArray['mobile']['INPUT_LATENCY'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['INPUT_LATENCY'][1],'INPUT_LATENCY','mobile')?>"><=<?=$legendsArray['mobile']['INPUT_LATENCY'][1]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['INPUT_LATENCY'][0],'INPUT_LATENCY','desktop')?>"><=<?=$legendsArray['desktop']['INPUT_LATENCY'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['INPUT_LATENCY'][1],'INPUT_LATENCY','desktop')?>"><=<?=$legendsArray['desktop']['INPUT_LATENCY'][1]?></td>
			</tr>				
			<tr>
				<td>FIRST CPU IDLE (in ms)</td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['FIRST_CPU_IDLE'][0],'FIRST_CPU_IDLE','mobile')?>"><=<?=$legendsArray['mobile']['FIRST_CPU_IDLE'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['FIRST_CPU_IDLE'][1],'FIRST_CPU_IDLE','mobile')?>"><=<?=$legendsArray['mobile']['FIRST_CPU_IDLE'][1]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['FIRST_CPU_IDLE'][0],'FIRST_CPU_IDLE','desktop')?>"><=<?=$legendsArray['desktop']['FIRST_CPU_IDLE'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['FIRST_CPU_IDLE'][1],'FIRST_CPU_IDLE','desktop')?>"><=<?=$legendsArray['desktop']['FIRST_CPU_IDLE'][1]?></td>
			</tr>				
			<tr>
				<td>TIME TO FIRST BYTE (in ms)</td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['TTFB'][0],'ttfb','mobile')?>"><=<?=$legendsArray['mobile']['TTFB'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['TTFB'][1],'ttfb','mobile')?>"><=<?=$legendsArray['mobile']['TTFB'][1]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['TTFB'][0],'ttfb','desktop')?>"><=<?=$legendsArray['desktop']['TTFB'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['TTFB'][1],'ttfb','desktop')?>"><=<?=$legendsArray['desktop']['TTFB'][1]?></td>
			</tr>
			<tr>
				<td>DOM SIZE (count)</td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['DOM_SIZE'][0],'DOM_Size','mobile')?>"><=<?=$legendsArray['mobile']['DOM_SIZE'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['mobile']['DOM_SIZE'][1],'DOM_Size','mobile')?>"><=<?=$legendsArray['mobile']['DOM_SIZE'][1]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['DOM_SIZE'][0],'DOM_Size','desktop')?>"><=<?=$legendsArray['desktop']['DOM_SIZE'][0]?></td>
				<td style="background-color: <?=colorDecider($legendsArray['desktop']['DOM_SIZE'][1],'DOM_Size','desktop')?>"><=<?=$legendsArray['desktop']['DOM_SIZE'][1]?></td>
			</tr>
			
		</table>
       </div>
      <div class='clearFix'></div>
</div>

<div style='width:100%;min-height:40px;'>
	<div style="font-size: 18px;margin-top: 10px; float:left;margin-left:10px;" id='counterTitle'><b>Filters:</b></div>
	 <div style="font-size: 18px; float: left;margin-top:10px; margin-left:40px;">Vertical
                  <select id="teamFilter" onchange="updateFilter()" style="width: 150px;font-size:16px;">

                          <option value="Domestic">Domestic</option>
                          <option value="StudyAbroad">StudyAbroad</option>
                  </select>
        </div>

	<div style="font-size: 18px; float: left;margin-top:10px; margin-left:40px;">Platform
		  <select id="platformFilter" onchange="updateFilter()" style="width: 150px;font-size:16px;">
			  <option value="all">All</option>
			  <option value="mobile">Mobile</option>
			  <option value="desktop">Desktop</option>
		  </select>	
	</div>
	<div style="font-size: 18px;margin-top:10px; margin-left:40px;float: left;">Fields
		  <select id="fieldFilter" onchange="updateFilter()" style="width: 200px;font-size:16px;">
			  <option value="all">All</option>
			  <option value="score">Google Score</option>
			  <option value="fcp">FCP</option>
			  <option value="fmp">FMP</option>
			  <option value="speedIndex">Speed Index</option>
			  <option value="interactive">Time to Interactive</option>
			  <option value="inputLatency">Input Latency</option>
			  <option value="fci">First CPU Idle</option>
			  <option value="ttfb">TTFB</option>
			  <option value="domSize">DOM Size</option>
		  </select>	
	</div>
	
	<?php if($teamFilter!='StudyAbroad'){ ?>
	<div style="font-size: 18px; float: left;margin-top:10px; margin-left:40px;">Team
		  <select id="subteamFilter" onchange="updateFilter()" style="width: 150px;font-size:16px;">
			  <option value="all">All</option>
			  <option value="Listings">Listings</option>
			  <option value="UGC">UGC</option>
			  <option value="Search">Search</option>
			  <option value="LDB">LDB</option>
		  </select>	
	</div>
	<?php } ?>
	
      <div class='clearFix'></div>
</div>

<table id="myTable" class='tablesorter exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1180'>
    <tbody>
<?php
$platformArray = array('mobile','desktop');
$rowSpan = 18;
$rowSpanAlt = 9;
$displayOnce = false;

if($platformFilter == 'desktop'){
	$platformArray = array('desktop');
	if($fieldFilter == 'all'){
		$rowSpan = 9;
	}
	else{
		$rowSpan = 1;
		$rowSpanAlt = 1;
		$displayOnce = true;
	}
}
else if($platformFilter == 'mobile'){
	$platformArray = array('mobile');
	if($fieldFilter == 'all'){
		$rowSpan = 9;
	}
	else{
		$rowSpan = 1;
		$rowSpanAlt = 1;
		$displayOnce = true;
	}
}
else{
	if($fieldFilter != 'all'){
		$rowSpan = 2;
		$rowSpanAlt = 1;
		$displayOnce = true;
	}	
}


if(count($pageScores['Domestic']) > 0 && $teamFilter!='StudyAbroad') {
    $j = 1;
    $sitename=array('Shiksha','CollegeDunia','Careers360');	    
    foreach ($pageScores['Domestic'] as $key => $value) {

	if($subteamFilter != 'all'){
		if($subteamFilter != showTeamName($key)){
			continue;
		}
	}
	
        $background_color = "";
        if($j % 2 == 0){
            $background_color = "background-color:#F3FAF2";
        }
	?>
	
	<?php if(!$displayOnce || ($displayOnce && $j==1)){ ?>
	<tr style='background: none repeat scroll 0 0 #f7f7f7;'>
	    <th width='50'>S.No.</th>
	    <th width='160'>Page Name</th>
	    <th width='100'>Team</th>
	    <th width='100'>Platform</th>
	    <th width='100'>Values</th>
	    <th width='150' style='text-align: center;font-size:15px;'>Shiksha</th>
	    <th width='150' style='text-align: center;font-size:15px;'>CollegeDunia</th>
	    <th width='150' style='text-align: center;font-size:15px;'>Careers360</th>
	</tr>
	<?php } ?>
	
	<?php
	echo "<tr style='".$background_color.";'>";
	echo "<td valign='top' rowspan='".$rowSpan."'>".$j."</td>";
	echo "<td valign='top' rowspan='".$rowSpan."'>".$key."</td>";
	echo "<td valign='top' rowspan='".$rowSpan."'>".showTeamName($key)."</td>";
	
	foreach ($platformArray as $platform){
		if($platform == 'desktop' && count($platformArray) == 2 && $fieldFilter == 'all'){
			echo "<tr style='".$background_color.";'>";
		}
		
		echo "<td valign='top' rowspan='".$rowSpanAlt."'><a title='Click to view past trends' href='/AppMonitor/competitorsPage/displayPageDetailsLH/".str_replace(' ','-',$key)."/$platform'>".ucfirst($platform)."</a></td>";
		
		if($fieldFilter == 'all' || $fieldFilter == 'score'){
			echo "<td valign='top'>Google Score</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['googleScore'][$sitenameConfig],'score',$platform)."'>".$value[$platform]['googleScore'][$sitenameConfig].displayBestStar($value[$platform]['googleScore'],$sitenameConfig,'score').showStats($weekStats['Domestic'][$key][$platform]['googleScore'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
		
			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'fcp'){
			echo "<td valign='top'>FCP (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['fcp'][$sitenameConfig],'fcp',$platform)."'>".$value[$platform]['fcp'][$sitenameConfig].displayBestStar($value[$platform]['fcp'],$sitenameConfig).showStats($weekStats['Domestic'][$key][$platform]['fcp'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
		
			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'fmp'){
			echo "<td valign='top'>FMP (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['fmp'][$sitenameConfig],'fmp',$platform)."'>".$value[$platform]['fmp'][$sitenameConfig].displayBestStar($value[$platform]['fmp'],$sitenameConfig).showStats($weekStats['Domestic'][$key][$platform]['fmp'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
		
			echo "<tr style='".$background_color.";'>";
		}
		
		if($fieldFilter == 'all' || $fieldFilter == 'speedIndex'){
			echo "<td valign='top'>Speed Index (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {			
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['speedIndex'][$sitenameConfig],'speedIndex',$platform)."'>".$value[$platform]['speedIndex'][$sitenameConfig].displayBestStar($value[$platform]['speedIndex'],$sitenameConfig).showStats($weekStats['Domestic'][$key][$platform]['speedIndex'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
		
			echo "<tr style='".$background_color.";'>";
		}
		
		if($fieldFilter == 'all' || $fieldFilter == 'interactive'){
			echo "<td valign='top'>Interactive (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['interactive'][$sitenameConfig],'interactive',$platform)."'>".$value[$platform]['interactive'][$sitenameConfig].displayBestStar($value[$platform]['interactive'],$sitenameConfig).showStats($weekStats['Domestic'][$key][$platform]['interactive'][$sitenameConfig])."</td>";
			}
			echo "</tr>";

			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'fci'){
			echo "<td valign='top'>First CPU Idle (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['firstCPUIdle'][$sitenameConfig],'first_CPU_Idle',$platform)."'>".$value[$platform]['firstCPUIdle'][$sitenameConfig].displayBestStar($value[$platform]['firstCPUIdle'],$sitenameConfig).showStats($weekStats['Domestic'][$key][$platform]['firstCPUIdle'][$sitenameConfig])."</td>";
			}
			echo "</tr>";

			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'inputLatency'){
			echo "<td valign='top'>Input Latency (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['inputLatency'][$sitenameConfig],'input_Latency',$platform)."'>".$value[$platform]['inputLatency'][$sitenameConfig].displayBestStar($value[$platform]['inputLatency'],$sitenameConfig).showStats($weekStats['Domestic'][$key][$platform]['inputLatency'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
			
			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'ttfb'){
			echo "<td valign='top'>TTFB (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['ttfb'][$sitenameConfig],'ttfb',$platform)."'>".$value[$platform]['ttfb'][$sitenameConfig].displayBestStar($value[$platform]['ttfb'],$sitenameConfig).showStats($weekStats['Domestic'][$key][$platform]['ttfb'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
			
			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'domSize'){
			echo "<td valign='top'>DOM Size</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['domSize'][$sitenameConfig],'dom_Size',$platform)."'>".$value[$platform]['domSize'][$sitenameConfig].displayBestStar($value[$platform]['domSize'],$sitenameConfig).showStats($weekStats['Domestic'][$key][$platform]['domSize'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
		}
	}
        $j++;
    }
    echo "</tbody></table>";
}elseif(count($pageScores['Studyabroad']) > 0) {
    $j = 1;
    foreach ($pageScores['Studyabroad'] as $key => $value) {
    	$sitename='';
        $background_color = "";
        if($j % 2 == 0){
            $background_color = "background-color:#F3FAF2";
        }
        $displayOnce=0;
	?>
	
	<?php if(!$displayOnce || ($displayOnce && $j==1)){ ?>
	<tr style='background: none repeat scroll 0 0 #f7f7f7;'>
	    <th width='50'>S.No.</th>
	    <th width='160'>Page Name</th>
	    <th width='50'>Team</th>
	    <th width='100'>Platform</th>
	    <th width='100'>Values</th>
	    <?php  foreach ($value['desktop']['googleScore'] as $key1 => $value1) {
	    	$sitename[]=$key1;
	    echo "<th width='150' style='text-align: center;font-size:15px;'>$key1</th>";}
	    $sitename=array_unique($sitename);
	    
	    ?>
	</tr>
	</tr>
	<?php } ?>
	
	<?php
	echo "<tr style='".$background_color.";'>";
	echo "<td valign='top' rowspan='".$rowSpan."'>".$j."</td>";
	echo "<td valign='top' rowspan='".$rowSpan."'>".$key."</td>";
	echo "<td valign='top' rowspan='".$rowSpan."'>".showTeamName($key)."</td>";
	
	foreach ($platformArray as $platform){
		if($platform == 'desktop' && count($platformArray) == 2 && $fieldFilter == 'all'){
			echo "<tr style='".$background_color.";'>";
		}
		
		echo "<td valign='top' rowspan='".$rowSpanAlt."'><a title='Click to view past trends' href='/AppMonitor/competitorsPage/displayPageDetailsLH/".str_replace(' ','-',$key)."/$platform'>".ucfirst($platform)."</a></td>";


		if($fieldFilter == 'all' || $fieldFilter == 'score'){
			echo "<td valign='top'>Google Score</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['googleScore'][$sitenameConfig],'score',$platform)."'>".$value[$platform]['googleScore'][$sitenameConfig].displayBestStar($value[$platform]['googleScore'],$sitenameConfig,'score').showStats($weekStats['Studyabroad'][$key][$platform]['googleScore'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
		
			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'fcp'){
			echo "<td valign='top'>FCP (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['fcp'][$sitenameConfig],'fcp',$platform)."'>".$value[$platform]['fcp'][$sitenameConfig].displayBestStar($value[$platform]['fcp'],$sitenameConfig).showStats($weekStats['Studyabroad'][$key][$platform]['fcp'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
		
			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'fmp'){
			echo "<td valign='top'>FMP (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['fmp'][$sitenameConfig],'fmp',$platform)."'>".$value[$platform]['fmp'][$sitenameConfig].displayBestStar($value[$platform]['fmp'],$sitenameConfig).showStats($weekStats['Studyabroad'][$key][$platform]['fmp'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
		
			echo "<tr style='".$background_color.";'>";
		}
		
		if($fieldFilter == 'all' || $fieldFilter == 'speedIndex'){
			echo "<td valign='top'>Speed Index (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {			
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['speedIndex'][$sitenameConfig],'speedIndex',$platform)."'>".$value[$platform]['speedIndex'][$sitenameConfig].displayBestStar($value[$platform]['speedIndex'],$sitenameConfig).showStats($weekStats['Studyabroad'][$key][$platform]['speedIndex'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
		
			echo "<tr style='".$background_color.";'>";
		}
		
		if($fieldFilter == 'all' || $fieldFilter == 'interactive'){
			echo "<td valign='top'>Interactive (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['interactive'][$sitenameConfig],'interactive',$platform)."'>".$value[$platform]['interactive'][$sitenameConfig].displayBestStar($value[$platform]['interactive'],$sitenameConfig).showStats($weekStats['Studyabroad'][$key][$platform]['interactive'][$sitenameConfig])."</td>";
			}
			echo "</tr>";

			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'fci'){
			echo "<td valign='top'>First CPU Idle (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['firstCPUIdle'][$sitenameConfig],'first_CPU_Idle',$platform)."'>".$value[$platform]['firstCPUIdle'][$sitenameConfig].displayBestStar($value[$platform]['firstCPUIdle'],$sitenameConfig).showStats($weekStats['Studyabroad'][$key][$platform]['firstCPUIdle'][$sitenameConfig])."</td>";
			}
			echo "</tr>";

			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'inputLatency'){
			echo "<td valign='top'>Input Latency (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['inputLatency'][$sitenameConfig],'input_Latency',$platform)."'>".$value[$platform]['inputLatency'][$sitenameConfig].displayBestStar($value[$platform]['inputLatency'],$sitenameConfig).showStats($weekStats['Studyabroad'][$key][$platform]['inputLatency'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
			
			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'ttfb'){
			echo "<td valign='top'>TTFB (in ms)</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['ttfb'][$sitenameConfig],'ttfb',$platform)."'>".$value[$platform]['ttfb'][$sitenameConfig].displayBestStar($value[$platform]['ttfb'],$sitenameConfig).showStats($weekStats['Studyabroad'][$key][$platform]['ttfb'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
			
			echo "<tr style='".$background_color.";'>";
		}

		if($fieldFilter == 'all' || $fieldFilter == 'domSize'){
			echo "<td valign='top'>DOM Size</td>";
			foreach ($sitename as $key2 => $sitenameConfig) {
				echo "<td valign='top' class='scoreClass' style='text-align: center;font-size: 20px; background-color: ".colorDecider($value[$platform]['domSize'][$sitenameConfig],'dom_Size',$platform)."'>".$value[$platform]['domSize'][$sitenameConfig].displayBestStar($value[$platform]['domSize'],$sitenameConfig).showStats($weekStats['Studyabroad'][$key][$platform]['domSize'][$sitenameConfig])."</td>";
			}
			echo "</tr>";
		}



		
	}
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
<script>
$('#platformFilter option[value="<?=$platformFilter?>"]').prop('selected', true);
$('#fieldFilter option[value="<?=$fieldFilter?>"]').prop('selected', true);
$('#teamFilter option[value="<?=$teamFilter?>"]').prop('selected', true);
$('#subteamFilter option[value="<?=$subteamFilter?>"]').prop('selected', true);

function updateFilter() {
        platform = $("#platformFilter").val();
        field = $("#fieldFilter").val();
        team_name=$("#teamFilter").val();
        if($("#subteamFilter").length > 0){
                subteam_name=$("#subteamFilter").val();
        }
        else{
                subteam_name="all";
        }

        url = '/AppMonitor/competitorsPage/displayPageScoresLH';
        window.location = url+"?team_name="+team_name+"&platform="+platform+"&field="+field+"&subteam="+subteam_name;

}	
</script>
</html>