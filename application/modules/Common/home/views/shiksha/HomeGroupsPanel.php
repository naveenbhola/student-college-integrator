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
	$groupsPosition = isset($groupsPosition) &&  $groupsPosition!= '' ?  $groupsPosition : 'right';
	$class = $groupsPosition == 'left' ? 'float_L' : 'float_R';
    if(isset($networks['totalCount']) && $networks['totalCount'] > 0) {
?>
<div>
	<div class="careerOptionPanelBrd">
		<div class="careerOptionPanelHeaderBg">
             <h6><span class="blackFont fontSize_13p">Groups</span></h6>
		</div>
		<div class="lineSpace_5">&nbsp;</div>
		<div class="mar_full_10p" style="display:block;<?php echo isset($groupsPanelHeight) ? 'height:'. $groupsPanelHeight .'px;' : ''; ?>" id="communityGroupBlock">
			<?php $this->load->view('home/shiksha/HomeGroupsWidget'); ?>
		</div>
		<div class="lineSpace_12">&nbsp;</div>
	</div>
</div>
<?php
}
?>
