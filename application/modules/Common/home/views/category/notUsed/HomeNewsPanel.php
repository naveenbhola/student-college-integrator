<?php	
	$newsImage = isset($newsImage) && $newsImage != '' ? $newsImage : '/public/images/foreign-edu-calender.jpg';
	$newsCaption = isset($newsCaption) && $newsCaption != '' ? $newsCaption : 'Featured Scholarships';
	$newsPosition = isset($newsPosition) &&  $newsPosition!= '' ?  $newsPosition : 'left';
	$class = $newsPosition == 'left' ? 'float_L' : 'float_R';
?>
<div style="width:49%;" class="<?php echo $class; ?>">
	<div>
		<div class="normaltxt_11p_blk fontSize_13p bld OrgangeFont arial">
			<span class="mar_left_10p"><?php echo $newsCaption; ?></span>
		</div>
		<div style="line-height:5px">&nbsp;</div>
		<div class="raised_lgraynoBG">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_lgraynoBG">
				<div class="mar_full_10p" style="padding:10px 0px;display:block;height:130px;" id="notificationEventsBlock">
					<span>
						<img src="<?php echo $newsImage; ?>" align="left" />
					</span>
                    <div class="mar_left_97p normaltxt_11p_blk arial">
                        <script>
							google_ad_client = 'ca-shiksha_site_js';
							google_ad_channel ='';
							google_ad_output = 'js';
							google_max_num_ads = '5';
							google_encoding = 'utf8';
							google_safe = 'high';
							google_adtest = 'on';
							google_feedback= "on";
							google_ad_type = "text";
							google_color_line = "FF0000";
							google_kw_type='broad';
							google_bid_type='cpc,cpm';
							google_kw = '<?php //echo $categoryData['name'] .','; ?> education, india';	
						</script>
										
						<script language="JavaScript" src="/public/js/<?php echo getJSWithVersion("shikshaAdsBlock"); ?>"></script>
						<script language="JavaScript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
                    </div>
					<div class="clear_L"></div>
				</div>
				<div class="lineSpace_10"></div>
				<div class="clear_L"></div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>		
	</div>
</div>	
