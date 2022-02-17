<?php 
$featuredInstitutes = $widgets_data['featuredInstitutes'];
$slides = $featuredInstitutes['slides'];
$mainLinksSections = $widgets_data['featuredInstitutes']['sections'];

//MBA: rotate the slide by random number on each page load
if(count($slides) && $slides[0]['subcatId'] == 23) {
	$randNum = rand(0,count($slides)-1);
	for($i = 1; $i <= $randNum; $i++) {
		array_push($slides, array_shift($slides));
	}
}
?>
<script type="text/javascript">
var count_of_featured_slide = '<?php echo count($slides);?>';
function openLandingUrl(event,inNewTab,landingUrl) {
	if (inNewTab == 'NO') {
		window.location = landingUrl;
	}
	else {
		window.open(landingUrl);
	}
	//prevent event from propagating and opening another tab
	event.stopPropagation();
}
</script>
<?php 
if(count($mainLinksSections) || count($slides)) {	
?>
<div <?=$cssClass?> id="<?=$widgetObj->getWidgetKey().'Container'?>">
	<h2><?=$widgetObj->getWidgetHeading();?></h2>
	<?php if(count($slides)>0):?>
	<div class="featured-slider">
	<div id="royalSlider_cp_featured" class="royalSlider rsDefault">
		<?php $slide_no=0;foreach ($slides as $url) :
			$landing_url = urldecode($url['landingUrl']);
			if(strpos($landing_url,"http://") === FALSE && strpos($landing_url,"https://") === FALSE) {
				$landing_url = "http://".$landing_url;
			}
		?>
		<div class="rsContent" <?php if($url['open_new_tab'] == 'NO'):?>onclick ="updateCoursePageFeaturedInstituteTracking();openLandingUrl(event,'NO','<?php echo $landing_url;?>');" <?php else: ?> onclick ="updateCoursePageFeaturedInstituteTracking();openLandingUrl(event,'YES','<?php echo $landing_url;?>');"<?php endif;?> id="imagewraper<?php echo $slide_no;?>" uniqueattr="CPGS_SESSION_featuredinstitute/position<?php echo ($slide_no+1)?>">
		    <img src="<?php echo $url['imageUrl'];?>">
			<div class="black-strap">
				<div class="flRt" style="margin-right: 35px;">
					<input id="coursepage_button_<?php echo $slide_no;?>"<?php if($url['open_new_tab'] == 'NO'):?>onclick ="openLandingUrl(event,'NO','<?php echo $landing_url;?>');return false;" <?php else: ?> onclick ="openLandingUrl(event,'YES','<?php echo $landing_url;?>');return false;"<?php endif;?> type="button" value="Know More" class="orange-button2" uniqueattr="CPGS_SESSION_featuredinstitute/position<?php echo ($slide_no+1)?>"/>
				</div>
			<p>
			<?php echo $url['imageTitle']; ?>
			</p>			
			</div>
		</div>
		<?php $slide_no++;endforeach;?>
	</div>
	</div>
	<?php endif;
	/*
	 *	Lets show the Sections and respective links now..
	 */
	if(is_array($mainLinksSections) && count($mainLinksSections)) {
	?>
	<div class="view-section"><?php
	    $section_count_no = 1;	    
		foreach($mainLinksSections as $sectionNo => $sections) {	
			$section_count_no++;
			 	
			if($section_count_no % 2 == 0) {
				$count = 1;
	?>
				<div class="flLt view-cols"><?php
					$cntvar = 0;
					foreach($sections as $key => $sectionInfo) {
						
						$section_url = urldecode($sectionInfo['landinURL']);
						$sections_landing_url = urldecode($sectionInfo['sectionURL']);
						
						if(strpos($section_url,"http://") === FALSE && strpos($section_url,"https://") === FALSE) {
							$section_url = "http://".$section_url;									
						}
						
						if(strpos($sections_landing_url,"http://") === FALSE && strpos($sections_landing_url,"https://") === FALSE) {
							$sections_landing_url = "http://".$sections_landing_url;									
						}
						
						if($sectionInfo['open_new_tab'] == "YES") {
							$targetTag = 'target="_blank"';
						} else {
							$targetTag = '';
						}
						
						if($cntvar++ == 0) { ?>
							<p class="title"><?=$sectionInfo['sectionHeading']?></p>
							<ul class="bullet-item"><?php
						}
						?>
						<li><a uniqueattr="CPGS_SESSION_featuredinstitutesection<?php echo $sectionInfo['sectionHeading']?>/<?php echo "position".$count?>" href="<?=$section_url?>" <?=$targetTag?>><?=$sectionInfo['linkTitle']?></a></li><?php
						$count++;
					} ?>
					</ul>
				 <div class="clearFix spacer5"></div>
                                        <?php if(!empty($sectionInfo['sectionURL'])): ?>
	    				<div class="tar" style="font-size: 13px;margin-right: 20px;"><a target="_blank" uniqueattr="CPGS_SESSION_featuredinstitutesection/More" href="<?=$sections_landing_url;?>" title="<?php echo $sectionInfo['sectionHeading'];?>">More &raquo;</a></div><?php endif; ?> 
				</div><?php
			
			} else {
				$count = 1;
	?>	
				<div class="flRt view-cols"><?php
					$cntvar = 0;
					foreach($sections as $key => $sectionInfo) {
						
						$section_url = urldecode($sectionInfo['landinURL']);
						$sections_landing_url = urldecode($sectionInfo['sectionURL']);
						
						if(strpos($section_url,"http://") === FALSE && strpos($section_url,"https://") === FALSE) {
							$section_url = "http://".$section_url;									
						}
						
						if(strpos($sections_landing_url,"http://") === FALSE && strpos($sections_landing_url,"https://") === FALSE) {
							$sections_landing_url = "http://".$sections_landing_url;									
						}
						
						if($sectionInfo['open_new_tab'] == "YES") {
							$targetTag = 'target="_blank"';
						} else {
							$targetTag = '';
						}
						
						if($cntvar++ == 0) { ?>
							<p class="title"><?=$sectionInfo['sectionHeading']?></p>
							<ul class="bullet-item"><?php
						}							
						?>
						<li><a uniqueattr="CPGS_SESSION_featuredinstitutesection<?php echo $sectionInfo['sectionHeading']?>/<?php echo "position".$count?>" href="<?=$section_url?>" <?=$targetTag?>><?=$sectionInfo['linkTitle']?></a></li><?php
						$count++;
					} ?>
				    </ul>
				 <div class="clearFix spacer5"></div>
                                <?php if(!empty($sectionInfo['sectionURL'])): ?>
	    			<div class="tar" style="font-size: 13px;margin-right: 20px;"><a target="_blank" uniqueattr="CPGS_SESSION_featuredinstitutesection/More" href="<?=$sections_landing_url?>" title="<?php echo $sectionInfo['sectionHeading'];?>">More &raquo;</a></div> <?php endif;?>
				</div>
				<div class="clearFix spacer20"></div>
	<?php
			}
			
		}	// End of foreach($mainLinksSections as $sectionNo => $sections).
	?>	   
	    <div class="clearFix"></div>
	</div><?php
	}
	?>
</div>
<?php
}	// End of if(count($featuredInstitutes) || count($slides)).
?>
