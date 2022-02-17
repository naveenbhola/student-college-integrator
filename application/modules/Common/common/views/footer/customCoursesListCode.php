<script>
	<?php if(isset($multiLocationCourses) && $multiLocationCourses != 'null'){ ?>
		var multiLocationCourses = <?php echo $multiLocationCourses; ?>;
		<?php }else{ ?>
		var multiLocationCourses = [0];
	<?php } ?>
</script>