<ul>
	<?php 
		if(empty($selectedHierarchies)){
             $selectedHierarchies[0]['streamId'] = $streamId;
             $selectedHierarchies[0]['substreamId'] = 'any';
             $selectedHierarchies[0]['specializationId'] = 'any';
        }    

		$baseCourses = $fields['baseCourses']->getValues(array('baseEntityArr'=>$selectedHierarchies, 'arrangeInAlpha'=>'yes', 'customBaseCourses'=>$customFieldValueSource['baseCourses']));

		$popularCourses = array();
		foreach($baseCourses['Popular Courses'] as  $courseId => $CourseName){
			$popularCourses[$CourseName['name']] = array('courseId'=>$courseId, 'level'=>$CourseName['level']);
		}

		ksort($popularCourses, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
	 if(!empty($baseCourses['Popular Courses'])){ ?>
			<div class="popularCourses">
				<?php foreach ($popularCourses as $courseName => $courseDetails) { ?>
						<input type="checkbox" class="twinSelect pcc course_<?php echo $regFormId; ?>" classHolder="course_<?php echo $regFormId; ?>" id="popCourse_<?php echo $courseDetails['courseId']; ?>" value="<?php echo $courseDetails['courseId']; ?>" match="<?php echo $courseDetails['level'].'_'.$regFormId; ?>" textUpdate="yes" label="<?php echo $courseName; ?>" >
				<?php } ?>
			</div>
		<?php } ?>
		
		<?php 
		unset($baseCourses['Popular Courses']);
		foreach ($baseCourses as $level => $baseCourseValues) { ?>
			<?php if(empty($baseCourseValues)){ 
				continue;
			} ?>
		<div class="subStrmGrp">
			<input type="checkbox" class="levelId pLevel course_<?php echo $regFormId; ?>" id="level_<?php echo str_replace(' ', '_',$level); ?>" classHolder="course_<?php echo $regFormId; ?>" value="<?php echo $level; ?>" match="<?php echo $level.'_'.$regFormId; ?>" label="<?php echo $level; ?>">
			<div class="child">
					<?php foreach($baseCourseValues as $courseId=>$courseName){ ?>
						<input type="checkbox" class="cLevel twinSelect course_<?php echo $regFormId; ?>" classHolder="course_<?php echo $regFormId; ?>" id="baseCourse_<?php echo $courseId; ?>" value="<?php echo $courseId; ?>" match="<?php echo $level.'_'.$regFormId; ?>" name="baseCourses[]" parentId="level_<?php echo str_replace(' ', '_',$level); ?>" label="<?php echo $courseName; ?>" >
					<?php } ?>
			</div>
		</div>
	<?php } ?>
	
</ul>