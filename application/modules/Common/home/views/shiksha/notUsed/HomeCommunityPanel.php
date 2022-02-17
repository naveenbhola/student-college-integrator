<?php
	
	if(!(is_array($validateuser) && $validateuser != "false")) { 
		$onClick = 'showuserOverlay(this,\'add\',1);return false;';
	}else {
		if($validateuser['quicksignuser'] == 1) {
	        $base64url = base64_encode($_SERVER['REQUEST_URI']);
	        $onClick = 'javascript:location.replace(\'/index.php/user/Userregistration/index/<?php
	echo $base64url?>/1\');return false;';
		} else {
			$onClick = '';
		}
	}
	$messageBoardCaption = isset($messageBoardCaption) && $messageBoardCaption != '' ? $messageBoardCaption : 'Ask & Answer';
	$networkCaption = isset($networkCaption) && $networkCaption != '' ? $networkCaption : 'Groups';
$messageBoardCaption = 'Ask & Answer';
	$messageBoardPosition = isset($messageBoardPosition) &&  $messageBoardPosition!= '' ?  $messageBoardPosition : 'right';
	$class = $messageBoardPosition == 'left' ? 'float_L' : 'float_R';
?>
<div style="width:49%" class="<?php echo $class; ?>">
	<div class="careerOptionPanelBrd">
		<div class="careerOptionPanelHeaderBg">
			<h4><span class="blackFont fontSize_13p">Communities</span></h4>
		</div>
		<div class="lineSpace_5">&nbsp;</div>
		<div id="blogTabContainer">
			<div id="blogNavigationTab">
				<ul>					
					<li container="community" tabName="communityMsgBoard"  class="selected" onClick="return selectHomeTab('community','MsgBoard');">						
						<a href="#" title="<?php echo $messageBoardCaption;?>"><?php echo $messageBoardCaption;?></a>						
					</li>
					<li container="community" tabName="communityGroup" class="" onClick="return selectHomeTab('community','Group');">						
						<a href="#" title="<?php echo $networkCaption;?>"><?php echo $networkCaption;?></a>						
					</li>
				</ul>
			</div>
			<div class="clear_L"></div>
		</div>
		<div class="grayLine" style="position:relative; top:1px"></div>
				<div class="mar_full_10p" style="display:block;/*height:235px*/" id="communityMsgBoardBlock">
					<?php $this->load->view('home/shiksha/HomeMsgBoardWidget'); ?>
				</div>
			
				<div class="mar_full_10p" style="display:none;height:235px" id="communityGroupBlock">
					<?php $this->load->view('home/shiksha/HomeGroupsWidget'); ?>
				</div>
			
				<div class="lineSpace_12">&nbsp;</div>
				<div class="clear_L"></div>	
	</div>
</div>
