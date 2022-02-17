	<?php 
		$url = site_url("events/Events/index/1") . "/";
	?>
	<script>//var pageURL = '<?php // echo $pageUrl;?>'</script>
	<!--Start_Left_Panel-->
	<div style="display:block; width:154px; float:left;">
			<!--Course_TYPE-->
			<div class="raised_blue_nL"> 
				<b class="b2"></b>
				<div class="boxcontent_nblue">
					<div class="row_blue tpBrd_nblue"><img src="/public/images/related_reviewIcon.jpg" align="absmiddle" width="28" height="24"/> Related Events</div>
					<div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>
					<div class="row">
						<div class="row_blue1 deactiveselectCategory" id="relatedEventsPlace">
							<?php 
								foreach($relatedEvents as $relatedEvent) {
									$url = '/events/Events/eventDetail/1/'. $relatedEvent['id'] ;
									$mainTitle = $relatedEvent['title'];
									if(strlen($mainTitle) > 20) {
										$title = substr($mainTitle, 0, 17) .'...';
									} else {
										$title = $mainTitle;
									}
							?>
								<div>
									<a title="<?php echo $mainTitle; ?>" href="<?php echo $url; ?>"><?php echo $title; ?></a>
								</div>
							<?php
								}
								if(count($relatedEvents) < 1) {
							?>
								<div>
									No Related Events Available
								</div>
							<?php
								}
							?>
						</div>
					</div>
					<div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>             
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>			
			</div>
			<!--End_Course_TYPE-->
	</div>
   	<!--End_Left_Panel-->
