<div id="right-col">
	<h4 style="text-align:left;">Featured Institutes</h4>
	<?php
	$count = 1;
	if(count($featured_institutes) > 0){
		foreach($featured_institutes as $instituteId => $courses){
			foreach($courses as $course){
				if(!empty($course)){
					if(count($course->getFeaturedBMSKey()) > 0){
						$featuredKeys = $course->getFeaturedBMSKey();
						$key = rand(0, count($featuredKeys) - 1);
						$bmsKey = $featuredKeys[$key];
						$pageZone = "FEATURED".$count;
						$shikshaCriteria  = array('keyword' => $bmsKey);
						$bannerProperties = array('pageId'=>'SEARCH', 'pageZone'=> $pageZone);
						$bannerProperties['shikshaCriteria'] = $shikshaCriteria;
						$count++;
						?>
						<div style="text-align:center;">
						<?php
							$this->load->view('common/banner',$bannerProperties);
						?>
						</div>
						<div class="spacer20"></div>
						<?php
					}
				}
			}
		}
	} else {
		?>
		<div style="text-align:center;">
			<a href="http://ask.shiksha.com" target="_blank">
				<img src="/public/images/searchFeaturedBanner.gif" title="Shiksha Cafe" alt="Featured Banner"/>
			</a>
		</div>
		<div class="spacer20"></div>
		<?php
	}
	?>
</div>
<div class="clearFix"></div>