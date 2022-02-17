<?php if(isset($quickLinks) && !empty($quickLinks)){ ?>
	<div class="related-link-sec" style="z-index:1;">
		<i class="cate-new-sprite quick-link-icon"></i>
		<h3 class="filter-title">Quick Links</h3>
		<ul>
			<?php foreach($quickLinks as $val){ ?>
			<li><a href="<?php echo $val['url']?>"><?php echo $val['urlTitle']?></a></li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>