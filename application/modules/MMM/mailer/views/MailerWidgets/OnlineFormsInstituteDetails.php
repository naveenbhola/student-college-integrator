<?php
	$institutescount = count($institute_name);
	for($i = 0; $i < $institutescount; $i++) {
?>
	<a href="<?php echo $online_form_url[$i]; ?>~onlineform<!-- #widgettracker --><!-- widgettracker# -->">Application for <?php echo $institute_name[$i]." - ".$course_name[$i];   ?></a>
	<br><br>
<?php } ?>
