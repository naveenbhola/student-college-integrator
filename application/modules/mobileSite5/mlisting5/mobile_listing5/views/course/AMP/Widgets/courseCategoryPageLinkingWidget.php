<?php 
if(!empty($interLinkingLinks['rankingPageLinks'])){
	?>
	<div class="data-card pad10 mr-20">
		<h2 class="color-3 f15 font-w6">View colleges by ranking</h2>
		<ul class="in-ul">
			<?php
				foreach ($interLinkingLinks['rankingPageLinks'] as $rankingPageRow) {
					?>
					<li class="color-9"><a class="color-b f14 l-14 block ga-analytic" data-vars-event-name="RANKING_LINKS" href="<?php echo $rankingPageRow['url'];?>"><?php echo $rankingPageRow['title'];?></a></li>
					<?php 
				}
			?>
		</ul>
	</div>
	<?php
}
if(!empty($interLinkingLinks['categoryPageLinks'])){
	?>
	<div class="data-card pad10 mr-20">
		<h2 class="color-3 f15 font-w6">View colleges by location</h2>
		<ul class="in-ul">
			<?php
				foreach ($interLinkingLinks['categoryPageLinks'] as $categoryPageRow) {
					?>
					<li class="color-9"><a class="color-b f14 l-14 block ga-analytic" data-vars-event-name="CAT_LINKS" href="<?php echo $categoryPageRow['url'];?>"><?php echo $categoryPageRow['title'];?></a></li>
					<?php 
				}
			?>
		</ul>
	</div>
	<?php
}
?>