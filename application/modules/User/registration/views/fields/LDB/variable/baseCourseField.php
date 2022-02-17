<ul>
	<?php 

		if(!empty($isResponse) && $isResponse == 'yes'){
			$baseCourses = $baseCourse;
		}else{

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
		}
	 if(!empty($baseCourses['Popular Courses']) && empty($customFieldValueSource)){ ?>
	<li class="searchHide">
		<div class="stdAlnLbl">
			<label>Popular Courses</label>
		</div>
		<div class="nav-cont">
			<ul class="lyr-sblst2 lyr-Nopd">
				<?php foreach ($popularCourses as $courseName => $courseDetails) { ?>
					<li>
						<div class="Customcheckbox nav-checkBx">
							<input type="checkbox" class="twinSelect pcc course_<?php echo $regFormId; ?>" classHolder="course_<?php echo $regFormId; ?>" id="popCourse_<?php echo $courseDetails['courseId']; ?>" value="<?php echo $courseDetails['courseId']; ?>" match="<?php echo $courseDetails['level'].'_'.$regFormId; ?>" textUpdate="yes">
							<label for="popCourse_<?php echo $courseDetails['courseId']; ?>"><?php echo $courseName; ?></label>
						</div>
					</li>
					<?php } ?>
				</ul>
			</div>
		</li>
		<?php } ?>

		<?php 
		unset($baseCourses['Popular Courses']); 
		// _p($baseCourses); die;
		foreach ($baseCourses as $level => $baseCourseValues) { ?>
			<?php if(empty($baseCourseValues)){ 
				continue;
			} ?>
		<li>
			<div class="Customcheckbox nav-checkBx cP">
				<input type="checkbox" class="levelId pLevel course_<?php echo $regFormId; ?>" id="level_<?php echo str_replace(' ', '_',$level); ?>" classHolder="course_<?php echo $regFormId; ?>" value="<?php echo $level; ?>" match="<?php echo $level.'_'.$regFormId; ?>">
				<label for="level_<?php echo str_replace(' ', '_',$level); ?>"><?php echo $level; ?></label>
			</div>
			
			<div class="nav-cont">
				<ul class="lyr-sblst2">
					<?php foreach($baseCourseValues as $courseId=>$courseName){ ?>
						<li>
							<div class="Customcheckbox nav-checkBx">
								<input type="checkbox" class="cLevel twinSelect course_<?php echo $regFormId; ?>" classHolder="course_<?php echo $regFormId; ?>" id="baseCourse_<?php echo $courseId; ?>" value="<?php echo $courseId; ?>" match="<?php echo $level.'_'.$regFormId; ?>" name="baseCourses[]" parentId="level_<?php echo str_replace(' ', '_',$level); ?>">
								<label for="baseCourse_<?php echo $courseId; ?>"><?php echo $courseName; ?></label>
							</div>
						</li>
					<?php } ?>
				</ul>
			</div>
		</li>
		<?php } ?>
		<li class='nsf'><span>No Results Found</span></li>
	</ul>