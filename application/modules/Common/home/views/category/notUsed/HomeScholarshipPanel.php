<?php
	$scholarshipImage = isset($scholarshipImage) && $scholarshipImage != '' ? $scholarshipImage : '/public/images/foreign-edu-calender.jpg';
	
	$scholarshipCaption = isset($scholarshipCaption) && $scholarshipCaption != '' ? $scholarshipCaption : 'Featured Scholarships';
	
	$scholarshipPosition = isset($scholarshipPosition) &&  $scholarshipPosition!= '' ?  $scholarshipPosition : 'left';
	$class = $scholarshipPosition == 'left' ? 'float_L' : 'float_R';
?>
<div style="width:49%" class="<?php echo $class; ?>">
	<div>
		<div class="normaltxt_11p_blk fontSize_13p bld OrgangeFont arial">
			<span class="mar_left_10p"><?php echo $scholarshipCaption; ?></span>
		</div>
		<div style="line-height:5px">&nbsp;</div>
		<div class="raised_lgraynoBG">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_lgraynoBG">
				<div class="mar_full_10p" style="height:130px;padding:10px 0px;display:block" id="notificationEventsBlock">
					<span>
						<img src="<?php echo $scholarshipImage; ?>" align="left" />
					</span>
					<?php
						$CI_Instance = & get_instance();
						$clientWidth =  $CI_Instance->checkClientData();
						$characterLength = ($clientWidth < 1000) ? 22 : 33;
						$scholarships = $scholarshipResults['scholarhips'];
						if(is_array($scholarships))
						foreach($scholarships as $scholarship) {
							$scholarshipTitle = $scholarship['title'];
							$scholarshipId = $scholarship['id'];
							$scholarshipUrl = $scholarship['url'];
							$scholarshipCityId = $scholarship['city_id'];
							$scholarshipCountryId = $scholarship['country_id'];
							$scholarshipCityName = $scholarship['city_name'];
							$scholarshipCountryName = $scholarship['country_name'];
							$displayTitle = $scholarshipTitle;
							if(strlen($displayTitle) > $characterLength) {
								$displayTitle = substr( $displayTitle, 0,$characterLength-3) . '...';
							}
							$sponsoredResult = (isset($scholarship['isSponsored']) && $scholarship['isSponsored'] == true) ? '<img src="/public/images/check.gif" style="margin-left:4px" align="absmiddle" />' : '<img src="/public/images/smallBullets.gif" style="margin-left:7px" align="absmiddle" />';
					?>
                    <div class="mar_left_97p normaltxt_11p_blk arial">
                        <div style="margin-bottom:2px">
                        <?php echo $sponsoredResult; ?>
                        <a class="fontSize_12p" href="<?php echo $scholarshipUrl; ?>" title="<?php echo $scholarshipTitle;?>"><?php echo $displayTitle; ?></a>
                        </div>
                        <div style="line-height:10px">&nbsp;</div>
                    </div>
					<?php
						}
					?>
					<div class="clear_L"></div>
				</div>
				<div class="lineSpace_10"></div>
				<div class="clear_L"></div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>		
	</div>
</div>	
