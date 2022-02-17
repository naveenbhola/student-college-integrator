
<li <?php if(!$stat['lable']){?> class='emptyStat' <?php } ?> >

	<?php if($stat['lable']){?>
	<?php if($stat['isCTA']) { ?>
	<a class="activity-a1" isCTA='1' id ="<?php echo $stat['lable'];?>" label ="<?php echo $stat['lable'];?>" data-enhance="false" href='<?php echo $stat['redirectionUrl']?>' ><h3><?php echo $stat['lable']; ?></h3><?php if($stat['value'] >0){?> <p><?php echo $stat['value'];?></p><?php } ?></a>
	<?php } else { ?>
		<a href="javascript:void(0);" class="activity-a " id="<?php echo str_replace(' ', '',$stat['lable']);?>" label ="<?php echo $stat['lable'];?>" <?php if($stat['lable']){?> <?php }?>><h3><?php echo $stat['lable']; ?></h3><p><?php echo $stat['value'];	?></p></a>
	<?php } ?>
	<?php } ?>
</li>