<div class="custom-dropdown">
	<select class="universal-select courseDropdown" data-enhance="false" id="<?php echo ($ajaxFlag || $secondCourse? 'secondaryCourse':'primaryCourse'); ?>">
		<?php if($ajaxFlag){ ?>
			<option value="">Select course</option>
		<?php }
				// sorting course alphabetically
				usort($courseList,function($a,$b){return $a['courseName']>$b['courseName']; });
				foreach($courseList as $courseData){
					if($currentCourse instanceOf AbroadCourse && $courseData['courseId'] == $currentCourse->getId()){
						continue;
					}
			?>
			<option value="<?php echo  htmlentities($courseData['courseId']); ?>" <?php echo (in_array($courseData['courseId'],array_keys($courseDataObjs))? 'selected="selected"':''); ?>>
				<?php echo  htmlentities($courseData['courseName']); ?>
			</option>
		<?php } ?>
	</select>
</div>