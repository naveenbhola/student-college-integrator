<?php?>
	<div class="featured-widget-box clearfix career-nxt-widget" style="<?php if($type==1){echo 'display:inline-block;float:right;width:300px';} else {echo 'width:100%';}?>;word-wrap:break-word;">
		<strong>Featured Colleges</strong>
		<ul>

			<?php foreach ($featuredColleges as $key => $value) {?>
				<li><a href="<?php echo $value['URL'];?>"><?php ?><?php echo htmlentities($value['title']); ?></a></li>
			<?php } ?>
			
		</ul>
	</div>
	<div class="clearFix"></div>