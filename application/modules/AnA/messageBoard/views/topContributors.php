<?php $topContributtos = (isset($topContributtingAndExpertPanel[0]) && is_array($topContributtingAndExpertPanel[0]))?$topContributtingAndExpertPanel[0]['mostcontributing']:array();
$userProfile = site_url('getUserProfile').'/';
?>
<!--Start_TopContributors-->
<div>
	<div class="raised_all">
		<b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_all">
			<div class="defaultAdd lineSpace_5">&nbsp;</div>
			<div style="margin:0 7px;padding-bottom:9px">
				<div class="shik_fontSize14" style="padding-bottom:15px;width:100%"><b>Top Contributors</b></div>
				<!-- <div class="globalTab" style="position:relative">
					<a href="#" style="margin-left:7px"><span>All</span></a>
					<a href="#" class="selectedGlobalTab"><span>This&nbsp;Week</span></a>
				</div> 
				<div class="grayLine_1">&nbsp;</div> -->
				<div class="marfull_LeftRight7">
					<?php
						foreach($topContributtos as $record):
							$userAskAndAnswerPage = site_url('messageBoard/MsgBoard/userAskAndAnswer').'/'.$record['displayname'].'/answer';
							$shortDisplayName = (strlen($record['displayname'])>17)?substr($record['displayname'],0,17).'...':$record['displayname'];
					?>
					<!--Start_Listing-->
					<div class="spirit_middle cup">
						<div style="padding:9px 0 0 0">
							<div>
								<div class="float_L" style="width:135px">
									<div><a href="<?php echo $userProfile.$record['displayname']; ?>"><b><?php echo $shortDisplayName; ?></b></a></div>
								</div>
								<div class="float_L" style="width:73px">
									<div class="grayColorFnt11 txt_align_r">Total Points</div>
								</div>
								<div class="clear_L">&nbsp;</div>
							</div>
							<div style="padding:6px 0 3px 0;width:100%">
								<div class="float_L" style="width:135px">
									<div class="grayColorFnt12" style="line-height:14px"><?php echo $record['Options']; ?>&nbsp;</div>
								</div>
								<div class="float_L" style="width:73px">
									<div class="txt_align_c Fnt11"><a href="/shikshaHelp/ShikshaHelp/upsInfo" style="color:#000"><b><?php echo $record['userPointValue']; ?></b></a></div>
								</div>
								<div class="clear_L">&nbsp;</div>
							</div>
							<div style="padding-bottom:6px">
								<div class="forA"><a href="/shikshaHelp/ShikshaHelp/upsInfo"><?php echo $record['level']; ?></a></div>
								<div class="sepratorColor" style="padding-top:3px"><a href="<?php echo $userAskAndAnswerPage; ?>"><?php echo $record['answerCount']; ?> Answers</a> | <a href="<?php echo $userAskAndAnswerPage; ?>"><?php echo $record['bestAnswerCount']; ?> Best Answers</a></div>
							</div>
							<div class="grayLine_1">&nbsp;</div>
						</div>
					</div>
					<!--End_Listing-->
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
</div>
<!--End_TopContributors-->