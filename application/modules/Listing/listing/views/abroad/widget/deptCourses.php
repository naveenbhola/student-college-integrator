<?php
	$totalBachelorCount = count($coursesByCategory['Bachelors']);
	$totalMasterCount = count($coursesByCategory['Masters']);
	$totalPHDCount = count($coursesByCategory['PhD']);
	$totalCertificateCount = count($coursesByCategory['Certificate - Diploma']);
	
	if($totalBachelorCount) {
		$active['Bachelors'] = 'active';
		$defaultActive = 'Bachelors';
	} elseif ($totalMasterCount) {
		$active['Masters'] = 'active';
		$defaultActive = 'Masters';
	} else if ($totalPHDCount) {
		$active['PhD'] = 'active';
		$defaultActive = 'PhD';
	} else {
		$active['Certificate-Diploma'] = 'active';
		$defaultActive = 'Certificate-Diploma';
	}
	
if($totalBachelorCount || $totalMasterCount || $totalPHDCount || $totalCertificateCount) { ?>
	<div class="widget-wrap clearwidth" id="coursesOffered">
		<h2 class="font18">Courses offered by Department</h2>
		<div class="offered-course-sec clearwidth">
			<div class="course-header2">
				<h3>
					<span class="flLt"><?php echo $departmentObj->getName(); ?></span>
				</h3>
				<div class="course-options clearwidth">
					<ul>
						<?php if($totalBachelorCount) { ?>
							<li><a href="javascript:void(0)" id="Bachelors" onclick="showCoursesByCategory('Bachelors');" class="<?=$active['Bachelors']?>">Bachelors <span>(<?=$totalBachelorCount?>)</span></a></li>
						<?php }
						
						if($totalMasterCount) { ?>
							<li><a href="javascript:void(0)" id="Masters" onclick="showCoursesByCategory('Masters');" class="<?=$active['Masters']?>">Masters <span>(<?=$totalMasterCount?>)</span></a></li>
						<?php }
						
						if($totalPHDCount) { ?>
							<li><a href="javascript:void(0)" id="PhD" onclick="showCoursesByCategory('PhD');" class="<?=$active['PhD']?>">PhD <span>(<?=$totalPHDCount?>)</span></a></li>
						<?php }
						
						if($totalCertificateCount) { ?>
							<li><a href="javascript:void(0)" id="Certificate-Diploma" onclick="showCoursesByCategory('Certificate-Diploma');" class="<?=$active['Certificate-Diploma']?>">Certificate-Diploma <span>(<?=$totalCertificateCount?>)</span></a></li>
						<?php } ?>
					</ul>
				</div>
				<div class="course-option-list clearwidth">
					<?php if($totalBachelorCount) { ?>
						<div class="scrollbar1 clear-width" id="scrollbar_Bachelors">
							<div class="scrollbar">
								<div class="track">
									<div class="thumb">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport" style="height: 185px;">
								<div class="overview dyanamic-content">
									<ul class="other-courses" id="courses_Bachelors">
										<?php foreach($coursesByCategory['Bachelors'] as $courses) { ?>
											<li><a href="<?=$courses['course_url']?>"><?=$courses['course_name']?></a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					<?php } ?>
					
					<?php if($totalMasterCount) { ?>
						<div class="scrollbar1 clear-width" id="scrollbar_Masters" <?php if($totalBachelorCount){ ?> style="display: none" <?php } ?> >
							<div class="scrollbar">
								<div class="track">
									<div class="thumb">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport" style="height: 185px;">
								<div class="overview dyanamic-content">
									<ul class="other-courses" id="courses_Masters">
										<?php foreach($coursesByCategory['Masters'] as $courses) { ?>
											<li><a href="<?=$courses['course_url']?>"><?=$courses['course_name']?></a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					<?php } ?>
					
					<?php if($totalPHDCount) { ?>
						<div class="scrollbar1 clear-width" id="scrollbar_PhD" <?php if($totalBachelorCount || $totalMasterCount){ ?> style="display: none" <?php } ?> >
							<div class="scrollbar">
								<div class="track">
									<div class="thumb">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport" style="height: 185px;">
								<div class="overview dyanamic-content">
									<ul class="other-courses" id="courses_PhD">
										<?php foreach($coursesByCategory['PhD'] as $courses) { ?>
											<li><a href="<?=$courses['course_url']?>"><?=$courses['course_name']?></a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					<?php } ?>
					
					<?php if($totalCertificateCount) { ?>
						<div class="scrollbar1 clear-width" id="scrollbar_Certificate-Diploma" <?php if($totalBachelorCount || $totalMasterCount || $totalPHDCount){ ?> style="display: none" <?php } ?> >
							<div class="scrollbar">
								<div class="track">
									<div class="thumb">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport" style="height: 185px;">
								<div class="overview dyanamic-content">
									<ul class="other-courses" id="courses_Certificate-Diploma">
										<?php foreach($coursesByCategory['Certificate - Diploma'] as $courses) { ?>
											<li><a href="<?=$courses['course_url']?>"><?=$courses['course_name']?></a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					<?php } ?>
			   </div>
			<div class="clearFix"></div>
			</div>
		</div>
	</div>
<?php } ?>

<script>
	var defaultActive = '<?=$defaultActive?>';
</script>