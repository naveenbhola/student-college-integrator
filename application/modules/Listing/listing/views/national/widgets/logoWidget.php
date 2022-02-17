<?php
if($pageType == "course" || ($institute->getLogo() != "" && $pageType == "institute")){	
	$logoImage = "";
	if($institute->getLogo() != "") {
		// Check if already small / medium thumbnail image is there..
		if(strrpos($institute->getLogo(), '_m.') !== false || strrpos($institute->getLogo(), '_s.') !== false) {
			$logoImage = $institute->getLogo();
		} else {
			$pos = strrpos($institute->getLogo(), '.');		
			if($pos !== false) {
				$extStr = substr($institute->getLogo(), $pos);
				$logoImage = str_replace($extStr, "_m".$extStr, $institute->getLogo());
			}
		}
	}
?>
<div class="col-logo">
	<?php if($logoImage){ ?>
	<i class="sprite-bg col-left-arrow"></i> <img
		 src="<?=$logoImage?>" title="<?=html_escape($institute->getName())?>, <?=(($currentLocation->getLocality() && $currentLocation->getLocality()->getName()) ? $currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName().' Logo'?>" alt="<?=html_escape($institute->getName())?>, <?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName().' Logo'?>" align="left" />
	<div class="clearFix"></div>
	<?php }
	if($pageType == "course") {
	?>
	<ul>
		<li><a href="<?=$overviewTabUrl?>" rel="nofollow">View all courses of this <?php echo $collegeOrInstituteRNR;?></a></li>
		<li><a href="<?=$overviewTabUrl?>"><?=html_escape($institute->getName())?></a>
		<span>&rsaquo;</span>
		<?php echo $course->getName(); ?></li>
	</ul><?php
	}
	?>
</div>
<div class="clearFix"></div>
<?php
}
?>