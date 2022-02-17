<?php if(count($getMenteeList)>0){ ?>
<div class="chat-dashboard-right-col flRt">
	 <div class="mentee-chat-list">
		<strong><?=count($getMenteeList)?> Mentee<?php if(count($getMenteeList)>1) {?>s<?php } ?></strong>
        <div class="mentee-chat-list-sec">
    		<div id="menteeList" class="mentee-list">
	                		<ul>
		                	<?php $id = 0;
		                	 	foreach($getMenteeList as $getMenteeListData){
		                	 ?>			                	 
		                    	<li <?php if($id==count($getMenteeList)-1){ ?>class="last" <?php } ?> id = "li_<?=$id?>"><?=$getMenteeListData['fullName']?><span class="mentee-info-tip" onmouseover="showMenteeTooltip('<?php echo $id; ?>');" onmouseout="hideMenteeTooltip();">i</span>
		                    	<br />
		                    	<span style="color:#989898"><?=$getMenteeListData['cityName']?></span>
								</li>
		                        <?php $id++; } ?>
		                    </ul>
	                	</div>	
    	</div>  
	</div>
</div>

<div id="menteeListTooltip" class="mentee-tooltip" style="display:none">
	<i class="campus-sprite mentee-tooltip-icon" id="tooltipIcon"></i>
    <p id="fullName" class="mentee-tooltip-title"></p>
    <ul>
    	<li><label>Exams taken/planned: </label> <span id="examsTaken"></span></li>
        <li><label>Preferred Branches:  </label> <span id="preferredBranches"></span></li>
        <li><label>Preferred Locations: </label> <span id="preferredLocations"></span></li>
    </ul>
</div>
<?php } ?>
<?php $popupdata = json_encode($getMenteeList); ?>
<script>
var popupdata = <?php echo $popupdata;?>;
var preferredLocations;
var preferredBranches;
var	examsTaken;	
var	fullName;
var offsetClick;
function showMenteeTooltip(idClicked){
	for(i in popupdata){
		if(i == idClicked)
		{
			fullName = popupdata[i]['fullName'];
			examsTaken = popupdata[i]['examName'];
			preferredBranches = popupdata[i]['prefferedBranchCombined'];
			preferredLocations = popupdata[i]['prefferedLocationCombined'];
			break;
		}
	}
	$j("#fullName").html(fullName);
	$j("#examsTaken").html(examsTaken);
	$j("#preferredBranches").html(preferredBranches);
	$j("#preferredLocations").html(preferredLocations);
	offsetClick = $j("#li_"+idClicked).offset();
	$j('#menteeListTooltip').show();
	if($j(window).scrollTop() + $j(window).height() < (offsetClick.top-50 + $j("#menteeListTooltip").height()) ) {
		var newTop = offsetClick.top - $j("#menteeListTooltip").height() + 50;
		$j("#menteeListTooltip").offset({top: newTop, left: offsetClick.left-320});
   	}
	else{
		$j("#menteeListTooltip").offset({top: offsetClick.top-50, left: offsetClick.left-320});
	}
	$j("#tooltipIcon").offset({top: offsetClick.top});
}

function hideMenteeTooltip(){
	$j('#menteeListTooltip').hide();
}
</script>
