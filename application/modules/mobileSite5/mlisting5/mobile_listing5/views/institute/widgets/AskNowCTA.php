	<?php if($listing_type == 'university')
	{		
		$trackingKeyId = 1083;
	}
	else if($listing_type == 'institute')
	{
		$trackingKeyId = 953;
	}else if($listing_type == 'course')
	{
		$trackingKeyId = 957;
	}
	if($requestTrackingKeyId > 0) {
		$trackingKeyId = $requestTrackingKeyId;
	}
	$GA_Tap_On_ASK = 'ASK_QUESTION';
	?>
	<a class="btn-mob ask-btn" ga-attr="<?=$GA_Tap_On_ASK;?>" href="#questionPostingLayerOneDiv" is-href-url="false" tracking-key = '<?php echo $trackingKeyId;?>' id = 'ask_btn_ilp' data-inline="true" data-rel="dialog" data-transition="fade" >Ask Question</a>
