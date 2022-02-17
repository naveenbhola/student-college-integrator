<?php 
	if(($type == "institute") || ($type == "course") || ($type == "notification") || ($type == "scholarship")){ ?>
		Dear Customer,<br/><br/>Click here to see the Listing you were interested <a href="<?php echo $url;?>"><?php echo $url;?></a><br/><br/>One of our Executives will get in touch with you shortly.<br /> Best Regards,<br /> Shiksha.com team
		<br />
<?php
	}elseif($type == "blog" || $type == "exam") { ?>
		Dear Customer,<br/><br/>Click here to see the Article you were interested <a href="<?php echo $url;?>"><?php echo $url;?></a><br/><br/>One of our Executives will get in touch with you shortly.<br /> Best Regards,<br /> Shiksha.com team
	<?php } else{?>
		Dear Customer,<br/><br/>Click here to see the <?php echo $type; ?> you were interested <a href="<?php echo $url;?>"><?php echo $url;?></a><br/><br/>One of our Executives will get in touch with you shortly.<br /> Best Regards,<br /> Shiksha.com team
<?php }
?>