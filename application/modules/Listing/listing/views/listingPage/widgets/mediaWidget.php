<div class="section-cont">
<h4 class="section-cont-title">Photos &amp; Videos </h4>
<ul class="photo-vid">
	<?php
		$i=0;
		foreach($media as $m){
			$i++;
			if($i > 3){
				break;
			}
	?>
	<li><a href="<?=$mediaTabUrl?>"><img src="<?=$m->getThumbURL('small')?>" alt="<?=$m->getName()?>" /></a></li>
	<?php
		}
	?>
</ul>
<p class="view-all-pic">
	<?php if(count($photos) > 0){ ?><a href="<?=$mediaTabUrl?>">View all <?=count($photos)?> photos</a><?php } ?>
	<?php if(count($videos) > 0 && count($photos) > 0){ ?> | <?php } ?>
	<?php if(count($videos) > 0){ ?><a href="<?=$mediaTabUrl?>">View all <?=count($videos)?> videos</a><?php } ?>
</p>
<div class="clearFix"></div>
<div class="clearFix"></div>
<div class="clearFix"></div>
</div>