<?php
	if(is_array($infoWidgetData)){
		foreach($infoWidgetData as $temp){
			if($temp['actionType'] == 'lastQuestionTime'){
				$lastQuestionTime = floor((time() - strtotime($temp['actionValue']))/60);
			}
			if($temp['actionType'] == 'totalNumberOfQuestions'){
				$totalNumberOfQuestions = $temp['actionValue'];
			}
			if($temp['actionType'] == 'lastAnswerTime'){
				$lastAnswerTime = floor((time() - strtotime($temp['actionValue']))/60);
			}
			if($temp['actionType'] == 'bestAnswerCount'){
				$bestAnswerCount = $temp['actionValue'];
			}
		}
	}
?>
<div class="float_L" style="width:375px">
	<div class="bgAandAnsCorner">
		<div class="spirit_middle shik_AaInfo" style="width:270px">
			<div class="defaultAdd lineSpace_15">&nbsp;</div>
			<div style="margin-left:51px">
				<div class="float_L" style="width:171px">
					<div style="width:100%; height:40px" class="shik_box1">
						<div style="width:100%;" class="shik_box2" currentShownDiv="0" id="infoContainer">
							<div id="infoContainer0" style="width:100%;" class="shik_box3" divNumber="0">
								<div style="line-height:18px">Last Question asked <b><?php echo $lastQuestionTime; ?></b> minutes ago.</div>
							</div>
							<div id="infoContainer1" style="width:100%;" class="shik_box3" divNumber="1">
								<div style="line-height:18px"><b><?php echo $totalNumberOfQuestions; ?></b> Questions were asked in last 60 minutes.</div>
							</div>
							<div id="infoContainer2" style="width:100%;" class="shik_box3" divNumber="2">
								<div style="line-height:18px">Last question was answered <b><?php echo $lastAnswerTime; ?></b> minutes back.</div>
							</div>
							<div id="infoContainer3" style="width:100%;" class="shik_box3" divNumber="3">
								<div style="line-height:18px"><b><?php echo $bestAnswerCount; ?></b> of answers judged as "Best" till date.</div>
							</div>
							<div class="clear_L">&nbsp;</div>
						</div>
					</div>	
				</div>
				<div class="float_L" style="width:43px">
					<div style="margin-top:20px" class="arrowMove">
						<a href="javascript:void(0);" class="spirit_middle shik_leftMoveTxtArrow"  onClick="gliderObject.slideIt('infoContainer',-1,true);clearInterval(globalIntervalHandleForInfoSection);" id="infoContainer_Leftlink" >&nbsp;</a><a href="javascript:void(0);" class="spirit_middle shik_rightMoveTxtArrow" style="margin-left:3px;" onClick="gliderObject.slideIt('infoContainer',1,true);clearInterval(globalIntervalHandleForInfoSection);" id="infoContainer_Reftlink">&nbsp;</a>
					</div>
				</div>
				<div class="clear_L">&nbsp;</div>
									</div>
		</div>
	</div>
</div>
<script>
var gliderObject = new glider(document.getElementById('infoContainer'),0);
var globalIntervalHandleForInfoSection = setInterval(function (){
				try{
					gliderObject.slideIt('infoContainer',1,true);
				}catch(e){}
			},10000);
</script>