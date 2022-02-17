<?php
$expertPanel = (isset($topContributtingAndExpertPanel[0]) && is_array($topContributtingAndExpertPanel[0]))?$topContributtingAndExpertPanel[0]['expertUsers']:array();
$userProfile = site_url('getUserProfile').'/';
?>
<div>
	<div class="raised_all">
		<b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_all">
			<div class="defaultAdd lineSpace_5">&nbsp;</div>
			<div style="margin:0 7px;">
				<div style="width:100%;">
					<div class="orangeColor shik_fontSize14" style="padding-bottom:11px"><b>Expert Profiles</b></div>
				</div>
				<?php
					foreach($expertPanel as $record):
						// Need to find the solution as DB structure not sufficient.
						if($record['userid'] == 15){
							$record['Options'] = 'MBA - IIM Ahmedabad';
							$record['experience'] = '10 years';
						}
						if($record['userid'] == 23){
							$record['Options'] = 'MBA - IIM Ahmedabad';
							$record['experience'] = '17 years';
						}
						$userAskAndAnswerPage = site_url('messageBoard/MsgBoard/userAskAndAnswer').'/'.$record['displayname'].'/answer';
				?>
				<!--Start_Listing-->
				<div>
					<div style="padding-bottom:10px">
						<div class="float_L" style="width:58px"><a href="<?php echo $userProfile.$record['displayname']; ?>"><img src="<?php echo getSmallImage($record['avtarimageurl']); ?>" border="0" /></a></div>
						<div class="float_L" style="width:165px">
							<div style="margin-left:10px">
								<div><a href="<?php echo $userProfile.$record['displayname']; ?>"><b><?php echo $record['displayname'] ?></b></a></div>
								<div style="padding-top:3px"><?php echo $record['Options']; ?><br /><?php if($record['experience'] != ''){ echo "Work exp:&nbsp;".$record['experience']; }; ?></div>
								<div style="padding-top:7px"><a href="<?php echo $userAskAndAnswerPage; ?>"><?php echo $record['answerCount']; ?> Answers</a><br /><a href="<?php echo $userAskAndAnswerPage; ?>"><?php echo $record['bestAnswerCount']; ?> Best Answers</a></div>
							</div>
						</div>
						<div class="clear_L">&nbsp;</div>
					</div>
					<div class="grayLine_1">&nbsp;</div>
					<div class="defaultAdd lineSpace_10">&nbsp;</div>
				</div>
				<!--End_Listing-->
				<?php endforeach; ?>
			</div>
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
</div>