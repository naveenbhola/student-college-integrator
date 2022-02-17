<?php
$showCoursesByStream 		= false;
$showCoursesByDepartment 	= false;

if(!empty($universityCourseBrowseSectionByStream['stream'])) {
	$showCoursesByStream = true;
}

if(!empty($universityCourseBrowseSectionByDept['department']['courses'])) {
	$showCoursesByDepartment = true;
}

$coursesByStream 				= $universityCourseBrowseSectionByStream['stream'];
//$snapShotCoursesByStream 		= $universityCourseBrowseSectionByStream['stream']['snapshot_courses'];
$courseURLs 					= $universityCourseBrowseSectionByStream['urls']['courses'];
$snapshotCourseURLs 			= $universityCourseBrowseSectionByStream['urls']['snapshot_courses'];
$defaultCategory 				= false;
$defaultCourseType 				= false;

$courseListByDepartment 				= $universityCourseBrowseSectionByDept['department']['courses'];
$courseURLsByDepartment					= $universityCourseBrowseSectionByDept['urls']['courses'];
$departmentTitles						= $universityCourseBrowseSectionByDept['department']['department_titles'];
$departmentURLs							= $universityCourseBrowseSectionByDept['department']['department_urls'];


$departmentSectionDefaultDepartmentId 	= false;
$departmentSectionDefaultCourseType 	= false;

$styleOnHeader = "";
if(!$showCoursesByDepartment){
	$styleOnHeader = "border-right:none;";
}
if($showCoursesByStream || $showCoursesByDepartment) {
?>
<div id="coursesOfUniversitySec" class="widget-wrap clearwidth">
	<h2 class="font18">Courses offered by University</h2>
	<div class="offered-course-details clearwidth">
		<ul class="course-filter-tab" id="ul_cc_lbl">
			<?php
			if($showCoursesByStream){
				?>
				<li style="<?php echo $styleOnHeader;?>" class="active" id="uni_cc_stream_lbl"><a href="javascript:void(0);" onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'coursesByInterest'); switchUniversityCourseBrowserSections('uni_course_by_stream_cont');">Courses by Interest</a></li>
				<?php
			}
			if($showCoursesByDepartment){
				?>
				<li class="last" id="uni_cc_dept_lbl"><a href="javascript:void(0);" onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'coursesByDepartment'); switchUniversityCourseBrowserSections('uni_course_by_dept_cont');">Courses by Department</a></li>
				<?php
			}
			?>
		</ul>
		<div class="university-accordian clearwidth" id="uni_course_by_stream_cont" style="display:block;">
			<ul>
				<?php
				$count = 0;
				$totalCount = count($coursesByStream);
				foreach($coursesByStream as $categoryName => $coursesByCategory) {
					$iconActiveClass = "";
					if($count == 0) {
						$iconActiveClass = "active";
						$defaultCategory = $categoryName;
					}
					//$snapshotCoursesOfCategory = $snapShotCoursesByStream[$categoryName];
					$mainHeaderByCategory = identifyMainCourseTypeForCategoryBlock($coursesByCategory);
					if($count == 0){
						$defaultCourseType = $mainHeaderByCategory;
					}
					$lastClass = "";
					if($totalCount - 1 == $count){
						$lastClass = "last";
					}
					?>
					<li class="clearwidth <?php echo $lastClass;?>">
						<div class="offered-course-sec">
							<div class="course-icons">
								<p class="<?php echo $iconActiveClass;?>" id="<?php echo $categoryName;?>_icon_cat_cont">
									<i class="listing-sprite <?php echo getIconClassByCategoryName($categoryName);?>"></i>
								</p>
							</div>
							<div class="course-header" id="courseHeader" style="padding-right:0px;">
								<h3 style="cursor:pointer;" id="<?php echo $categoryName;?>_header_label" onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'coursesByInterest', '<?=$categoryName?>'); showBrowseSectionCoursesOnArrowClick('<?php echo $categoryName;?>', '<?php echo $mainHeaderByCategory;?>');"><?php echo $categoryName;?></h3>
								<div class="course-options clearwidth">
									<ul>
										<?php
										$headerHighlighted = "";
										if(!empty($coursesByCategory['Bachelors']['courses']) || !empty($coursesByCategory['Bachelors']['snapshot_courses'])) {
											$activeCourseType = "";
											if($count == 0) {
												$activeCourseType 	= "active";
												$headerHighlighted 	= "Bachelors";
											}
											$totalBachelorCount = count($coursesByCategory['Bachelors']['courses']) + count($coursesByCategory['Bachelors']['snapshot_courses']);
										?>
											<li>
												<a id="<?php echo $categoryName;?>_Bachelors_lable_class" class="<?php echo $activeCourseType;?>" href="javascript:void(0)" onclick="showBrowseSectionCourses('<?php echo $categoryName;?>', 'Bachelors');">Bachelors <span>(<?php echo $totalBachelorCount;?>)</span></a>
											</li>
										<?php
										}
										?>
										<?php
										if(!empty($coursesByCategory['Masters']['courses']) || !empty($coursesByCategory['Masters']['snapshot_courses'])) {
											$activeCourseType = "";
											if($count == 0 && empty($coursesByCategory['Bachelors']['courses']) && empty($coursesByCategory['Bachelors']['snapshot_courses'])) {
												$activeCourseType 	= "active";
												$headerHighlighted 	= "Masters";
											}
											$totalMasterCount = count($coursesByCategory['Masters']['courses']) + count($coursesByCategory['Masters']['snapshot_courses']);
										?>
											<li>
												<a id="<?php echo $categoryName;?>_Masters_lable_class" class="<?php echo $activeCourseType;?>" href="javascript:void(0);" onclick="showBrowseSectionCourses('<?php echo $categoryName;?>', 'Masters');">Masters <span>(<?php echo $totalMasterCount;?>)</span></a>
											</li>
										<?php
										}
										?>
										<?php
										if(!empty($coursesByCategory['PhD']['courses']) || !empty($coursesByCategory['PhD']['snapshot_courses']) ) {
											$activeCourseType = "";
											if($count == 0 && empty($coursesByCategory['Bachelors']['courses']) && empty($coursesByCategory['Bachelors']['snapshot_courses']) && empty($coursesByCategory['Masters']['courses']) && empty($coursesByCategory['Masters']['snapshot_courses'])) {
												$activeCourseType 	= "active";
												$headerHighlighted 	= "PhD";
											}
											$totalPHDCount = count($coursesByCategory['PhD']['courses']) + count($coursesByCategory['PhD']['snapshot_courses']);
										?>
											<li>
												<a id="<?php echo $categoryName;?>_PhD_lable_class" class="<?php echo $activeCourseType;?>" href="javascript:void(0);" onclick="showBrowseSectionCourses('<?php echo $categoryName;?>', 'PhD');">PhD <span>(<?php echo $totalPHDCount;?>)</span></a>
											</li>
										<?php
										}
										?>
										<?php
										if(!empty($coursesByCategory['Certificate - Diploma']['courses']) || !empty($coursesByCategory['Certificate - Diploma']['snapshot_courses']) ) {
											$activeCourseType = "";
											if($count == 0 && empty($coursesByCategory['Bachelors']['courses']) && empty($coursesByCategory['Bachelors']['snapshot_courses']) && empty($coursesByCategory['Masters']['courses']) && empty($coursesByCategory['Masters']['snapshot_courses']) && empty($coursesByCategory['PhD']['courses']) && empty($coursesByCategory['PhD']['snapshot_courses'])) {
												$activeCourseType 	= "active";
												$headerHighlighted 	= "Certificate-Diploma";
											}
											$totalCertificateCount = count($coursesByCategory['Certificate - Diploma']['snapshot_courses']) + count($coursesByCategory['Certificate - Diploma']['courses']);
										?>
											<li>
												<a id="<?php echo $categoryName;?>_Certificate-Diploma_lable_class" class="<?php echo $activeCourseType;?>" href="javascript:void(0);" onclick="showBrowseSectionCourses('<?php echo $categoryName;?>', 'Certificate-Diploma');">Certificate - Diploma <span>(<?php echo $totalCertificateCount;?>)</span></a>
											</li>
										<?php
										}
										?>
									</ul>
								</div>
								<div class="course-option-list clearwidth">
										<?php
										if(!empty($coursesByCategory['Bachelors']['courses']) || !empty($coursesByCategory['Bachelors']['snapshot_courses'])) {
											$displayStyle = "display:none";
											if($headerHighlighted == 'Bachelors'){
												$displayStyle = "display:block";
											}
											?>
											
											<div class="scrollbar1 clear-width" id="cl_scroll_<?php echo $categoryName;?>_Bachelors">
												<div class="scrollbar" style="display:none;">
													<div class="track">
														<div class="thumb">
															<div class="end"></div>
														</div>
													</div>
												</div>
												<div class="viewport">
													<div class="overview" style="width:100%;">
														<ul id="<?php echo $categoryName;?>_Bachelors_cc" class="other-courses" style="<?php echo $displayStyle;?>" >
															<?php
															foreach($coursesByCategory['Bachelors']['courses'] as $courseId => $courseInfo) {
																?>
																<li><a href="<?php echo $courseURLs[$courseId];?>"><?php echo htmlspecialchars($courseInfo['courseTitle']);?></a></li>
																<?php
															}
															foreach($coursesByCategory['Bachelors']['snapshot_courses'] as $courseId => $courseInfo) {
																?>
																<li>
																	<a href="<?php echo $snapshotCourseURLs[$courseId];?>"><?php echo htmlspecialchars($courseInfo['courseTitle']);?></a>
																	<span class="pop-stu">
																		(limited information available)
																	</span>
																</li>
																<?php
															}
														?>
														</ul>
													</div>
												</div>
											</div>
											<?php
										}
										?>
										<?php
										if(!empty($coursesByCategory['Masters']['courses']) || !empty($coursesByCategory['Masters']['snapshot_courses'])) {
											$displayStyle = "display:none";
											if($headerHighlighted == 'Masters'){
												$displayStyle = "display:block";
											}
											?>
											<div class="scrollbar1 clear-width" id="cl_scroll_<?php echo $categoryName;?>_Masters">
												<div class="scrollbar" style="display:none;">
													<div class="track">
														<div class="thumb">
															<div class="end"></div>
														</div>
													</div>
												</div>
												<div class="viewport">
													<div class="overview" style="width: 100%">
														<ul id="<?php echo $categoryName;?>_Masters_cc" class="other-courses" style="<?php echo $displayStyle;?>" >
															<?php
															foreach($coursesByCategory['Masters']['courses'] as $courseId => $courseInfo) {
																?>
																<li><a href="<?php echo $courseURLs[$courseId];?>"><?php echo htmlspecialchars($courseInfo['courseTitle']);?></a></li>
																<?php
															}
															foreach($coursesByCategory['Masters']['snapshot_courses'] as $courseId => $courseInfo) {
																?>
																<li>
																	<a href="<?php echo $snapshotCourseURLs[$courseId];?>"><?php echo htmlspecialchars($courseInfo['courseTitle']);?></a>
																	<span class="pop-stu">
																		(limited information available)
																	</span>
																</li>
																<?php
															}
														?>
														</ul>
													</div>
												</div>
											</div>
											<?php
										}
										?>
										<?php
										if(!empty($coursesByCategory['PhD']['courses']) || !empty($coursesByCategory['PhD']['snapshot_courses'])) {
											$displayStyle = "display:none";
											if($headerHighlighted == 'PhD') {
												$displayStyle = "display:block";
											}
											?>
											<div class="scrollbar1 clear-width" id="cl_scroll_<?php echo $categoryName;?>_PhD">
												<div class="scrollbar" style="display:none;">
													<div class="track">
														<div class="thumb">
															<div class="end"></div>
														</div>
													</div>
												</div>
												<div class="viewport">
													<div class="overview" style="width: 100%">
														<ul id="<?php echo $categoryName;?>_PhD_cc" class="other-courses" style="<?php echo $displayStyle;?>" >
															<?php
															foreach($coursesByCategory['PhD']['courses'] as $courseId => $courseInfo) {
																?>
																<li><a href="<?php echo $courseURLs[$courseId];?>"><?php echo htmlspecialchars($courseInfo['courseTitle']);?></a></li>
																<?php
															}
															foreach($coursesByCategory['PhD']['snapshot_courses'] as $courseId => $courseInfo) {
																?>
																<li>
																	<a href="<?php echo $snapshotCourseURLs[$courseId];?>"><?php echo htmlspecialchars($courseInfo['courseTitle']);?></a>
																	<span class="pop-stu">
																		(limited information available)
																	</span>
																</li>
																<?php
															}
														?>
														</ul>
													</div>
												</div>
											</div>
											<?php
										}
										?>
										<?php
										if(!empty($coursesByCategory['Certificate - Diploma']['courses']) || !empty($coursesByCategory['Certificate - Diploma']['snapshot_courses'])) {
											$displayStyle = "display:none";
											if($headerHighlighted == 'Certificate-Diploma') {
												$displayStyle = "display:block";
											}
											?>
											<div class="scrollbar1 clear-width" id="cl_scroll_<?php echo $categoryName;?>_Certificate-Diploma">
												<div class="scrollbar" style="display:none;">
													<div class="track">
														<div class="thumb">
															<div class="end"></div>
														</div>
													</div>
												</div>
												<div class="viewport">
													<div class="overview" style="width:100%;">
														<ul id="<?php echo $categoryName;?>_Certificate-Diploma_cc" class="other-courses" style="<?php echo $displayStyle;?>" >
															<?php
															foreach($coursesByCategory['Certificate - Diploma']['courses'] as $courseId => $courseInfo) {
																?>
																<li><a href="<?php echo $courseURLs[$courseId];?>"><?php echo htmlspecialchars($courseInfo['courseTitle']);?></a></li>
																<?php
															}
															foreach($coursesByCategory['Certificate - Diploma']['snapshot_courses'] as $courseId => $courseInfo) {
																?>
																<li>
																	<a href="<?php echo $snapshotCourseURLs[$courseId];?>"><?php echo htmlspecialchars($courseInfo['courseTitle']);?></a>
																	<span class="pop-stu">
																		(limited information available)
																	</span>
																</li>
																<?php
															}
														?>
														</ul>
													</div>
												</div>
											</div>
											<?php
										}
										?>
								</div>
								<div class="clearFix"></div>
							</div>
							<div class="accordian-arrow">
								<?php
								$arrowClass = "up-arrow";
								if($count == 0){
									$arrowClass = "down-arrow";
								}
								?>
								<i onclick="showBrowseSectionCoursesOnArrowClick('<?php echo $categoryName;?>', '<?php echo $mainHeaderByCategory;?>');" id="<?php echo $categoryName;?>_arrow_cont" class="common-sprite <?php echo $arrowClass;?>"></i>
							</div>
						</div>
					</li>
					<?php
					$count++;
				}
				?>
			</ul>
		</div>
		
		<div class="university-accordian clearwidth" id="uni_course_by_dept_cont" style="display:none;">
			<ul>
				<?php
				$count = 0;
				$totalCount = count($courseListByDepartment);
				foreach($courseListByDepartment as $departmentId => $coursesByDepartment) {
					$iconActiveClass = "";
					if($count == 0) {
						$iconActiveClass 						= "active";
						$departmentSectionDefaultDepartmentId 	= $departmentId;
					}
					$mainHeaderByDepartment = identifyMainCourseTypeForCategoryBlock($coursesByDepartment);
					if($count == 0){
						$departmentSectionDefaultCourseType = $mainHeaderByDepartment;
					}
					$lastClass = "";
					if($totalCount - 1 == $count){
						$lastClass = "last";
					}
					?>
					<li class="clearwidth <?php echo $lastClass;?>">
						<div class="offered-course-sec">
							<div class="course-header2" style="padding-right:0px;">
								<h3 style="cursor:pointer;" id="<?php echo $departmentId;?>_header_label" onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'coursesByDepartment', '<?=$departmentTitles[$departmentId]?>'); showBrowseSectionCoursesOnArrowClick('<?php echo $departmentId;?>', '<?php echo $mainHeaderByDepartment;?>');"><?php echo $departmentTitles[$departmentId];?></h3>
								<div class="course-options clearwidth">
									<ul>
										<?php
										$headerHighlighted = "";
										if(!empty($coursesByDepartment['Bachelors'])) {
											$activeCourseType = "";
											if($count == 0) {
												$activeCourseType 	= "active";
												$headerHighlighted 	= "Bachelors";
											}
											$totalBachelorCount = count($coursesByDepartment['Bachelors']);
										?>
											<li>
												<a id="<?php echo $departmentId;?>_Bachelors_lable_class" class="<?php echo $activeCourseType;?>" href="javascript:void(0)" onclick="showBrowseSectionCourses('<?php echo $departmentId;?>', 'Bachelors');">Bachelors <span>(<?php echo $totalBachelorCount;?>)</span></a>
											</li>
										<?php
										}
										?>
										<?php
										if(!empty($coursesByDepartment['Masters'])) {
											$activeCourseType = "";
											if($count == 0 && empty($coursesByDepartment['Bachelors'])) {
												$activeCourseType 	= "active";
												$headerHighlighted 	= "Masters";
											}
											$totalMasterCount = count($coursesByDepartment['Masters']);
										?>
											<li>
												<a id="<?php echo $departmentId;?>_Masters_lable_class" class="<?php echo $activeCourseType;?>" href="javascript:void(0);" onclick="showBrowseSectionCourses('<?php echo $departmentId;?>', 'Masters');">Masters <span>(<?php echo $totalMasterCount;?>)</span></a>
											</li>
										<?php
										}
										?>
										<?php
										if(!empty($coursesByDepartment['PhD'])) {
											$activeCourseType = "";
											if($count == 0 && empty($coursesByDepartment['Bachelors']) && empty($coursesByDepartment['Masters'])) {
												$activeCourseType 	= "active";
												$headerHighlighted 	= "PhD";
											}
											$totalPHDCount = count($coursesByDepartment['PhD']);
										?>
											<li>
												<a id="<?php echo $departmentId;?>_PhD_lable_class" class="<?php echo $activeCourseType;?>" href="javascript:void(0);" onclick="showBrowseSectionCourses('<?php echo $departmentId;?>', 'PhD');">PhD <span>(<?php echo $totalPHDCount;?>)</span></a>
											</li>
										<?php
										}
										?>
										<?php
										if(!empty($coursesByDepartment['Certificate - Diploma'])) {
											$activeCourseType = "";
											if($count == 0 && empty($coursesByDepartment['Bachelors']) && empty($coursesByDepartment['Masters']) && empty($coursesByDepartment['PhD'])) {
												$activeCourseType 	= "active";
												$headerHighlighted 	= "Certificate-Diploma";
											}
											$totalCertificateCount = count($coursesByDepartment['Certificate - Diploma']);
										?>
											<li>
												<a id="<?php echo $departmentId;?>_Certificate-Diploma_lable_class" class="<?php echo $activeCourseType;?>" href="javascript:void(0);" onclick="showBrowseSectionCourses('<?php echo $departmentId;?>', 'Certificate-Diploma');">Certificate - Diploma <span>(<?php echo $totalCertificateCount;?>)</span></a>
											</li>
										<?php
										}
										?>
									</ul>
								</div>
								
								<div class="course-option-list clearwidth">
										<?php
										if(!empty($coursesByDepartment['Bachelors'])) {
											$displayStyle = "display:none";
											if($headerHighlighted == 'Bachelors'){
												$displayStyle = "display:block";
											}
											?>
											
											<div class="scrollbar1 clear-width" id="cl_scroll_<?php echo $departmentId;?>_Bachelors">
												<div class="scrollbar" style="display:none;">
													<div class="track">
														<div class="thumb">
															<div class="end"></div>
														</div>
													</div>
												</div>
												<div class="viewport">
													<div class="overview">
														<ul id="<?php echo $departmentId;?>_Bachelors_cc" class="other-courses" style="<?php echo $displayStyle;?>" >
															<?php
															foreach($coursesByDepartment['Bachelors'] as $courseId => $courseInfo) {
																?>
																<li><a href="<?php echo $courseURLsByDepartment[$courseId];?>"><?php echo $courseInfo['courseTitle'];?></a></li>
																<?php
															}
														?>
														</ul>
													</div>
												</div>
												<div style="margin-top:15px;display:none;" id="view_dept_link_<?php echo $departmentId;?>_Bachelors">
													<a href="<?php echo $departmentURLs[$departmentId];?>" class="flRt">View <?php echo $departmentTitles[$departmentId];?> <span class="font-14">&rsaquo;</span></a>
												</div>
											</div>
											<?php
										}
										?>
										<?php
										if(!empty($coursesByDepartment['Masters'])) {
											$displayStyle = "display:none";
											if($headerHighlighted == 'Masters'){
												$displayStyle = "display:block";
											}
											?>
											<div class="scrollbar1 clear-width" id="cl_scroll_<?php echo $departmentId;?>_Masters">
												<div class="scrollbar" style="display:none;">
													<div class="track">
														<div class="thumb">
															<div class="end"></div>
														</div>
													</div>
												</div>
												<div class="viewport">
													<div class="overview">
														<ul id="<?php echo $departmentId;?>_Masters_cc" class="other-courses" style="<?php echo $displayStyle;?>" >
															<?php
															foreach($coursesByDepartment['Masters'] as $courseId => $courseInfo) {
																?>
																<li><a href="<?php echo $courseURLsByDepartment[$courseId];?>"><?php echo $courseInfo['courseTitle'];?></a></li>
																<?php
															}
														?>
														</ul>
													</div>
												</div>
												<div style="margin-top:15px;display:none;" id="view_dept_link_<?php echo $departmentId;?>_Masters">
													<a href="<?php echo $departmentURLs[$departmentId];?>" class="flRt">View <?php echo $departmentTitles[$departmentId];?> <span class="font-14">&rsaquo;</span></a>
												</div>
											</div>
											<?php
										}
										?>
										<?php
										if(!empty($coursesByDepartment['PhD'])) {
											$displayStyle = "display:none";
											if($headerHighlighted == 'PhD') {
												$displayStyle = "display:block";
											}
											?>
											<div class="scrollbar1 clear-width" id="cl_scroll_<?php echo $departmentId;?>_PhD">
												<div class="scrollbar" style="display:none;">
													<div class="track">
														<div class="thumb">
															<div class="end"></div>
														</div>
													</div>
												</div>
												<div class="viewport">
													<div class="overview">
														<ul id="<?php echo $departmentId;?>_PhD_cc" class="other-courses" style="<?php echo $displayStyle;?>" >
															<?php
															foreach($coursesByDepartment['PhD'] as $courseId => $courseInfo) {
																?>
																<li><a href="<?php echo $courseURLsByDepartment[$courseId];?>"><?php echo $courseInfo['courseTitle'];?></a></li>
																<?php
															}
														?>
														</ul>
													</div>
												</div>
												<div style="margin-top:15px;display:none;" id="view_dept_link_<?php echo $departmentId;?>_PhD">
													<a href="<?php echo $departmentURLs[$departmentId];?>" class="flRt">View <?php echo $departmentTitles[$departmentId];?> <span class="font-14">&rsaquo;</span></a>
												</div>
											</div>
											<?php
										}
										?>
										<?php
										if(!empty($coursesByDepartment['Certificate - Diploma'])) {
											$displayStyle = "display:none";
											if($headerHighlighted == 'Certificate-Diploma') {
												$displayStyle = "display:block";
											}
											?>
											<div class="scrollbar1 clear-width" id="cl_scroll_<?php echo $departmentId;?>_Certificate-Diploma">
												<div class="scrollbar" style="display:none;">
													<div class="track">
														<div class="thumb">
															<div class="end"></div>
														</div>
													</div>
												</div>
												<div class="viewport">
													<div class="overview">
														<ul id="<?php echo $departmentId;?>_Certificate-Diploma_cc" class="other-courses" style="<?php echo $displayStyle;?>" >
															<?php
															foreach($coursesByDepartment['Certificate - Diploma'] as $courseId => $courseInfo) {
																?>
																<li><a href="<?php echo $courseURLsByDepartment[$courseId];?>"><?php echo $courseInfo['courseTitle'];?></a></li>
																<?php
															}
														?>
														</ul>
													</div>
												</div>
												<div style="margin-top:15px;display:none;" id="view_dept_link_<?php echo $departmentId;?>_Certificate-Diploma">
													<a href="<?php echo $departmentURLs[$departmentId];?>" class="flRt">View <?php echo $departmentTitles[$departmentId];?> <span class="font-14">&rsaquo;</span></a>
												</div>
											</div>
											<?php
										}
										?>
								</div>
								<div class="clearFix"></div>
							</div>
							<div class="accordian-arrow">
								<?php
								$arrowClass = "up-arrow";
								if($count == 0){
									$arrowClass = "down-arrow";
								}
								?>
								<i onclick="showBrowseSectionCoursesOnArrowClick('<?php echo $departmentId;?>', '<?php echo $mainHeaderByDepartment;?>');" id="<?php echo $departmentId;?>_arrow_cont" class="common-sprite <?php echo $arrowClass;?>"></i>
							</div>
						</div>
					</li>
					<?php
					$count++;
				}
				?>
			</ul>
		</div>
	</div>
</div>
<?php
}

function getIconClassByCategoryName($categoryName) {
	$className = "";
	switch($categoryName){
		case 'Computers':
			$className = "comp-icon";
			break;
		case 'Medicine':
			$className = "med-icon";
			break;
		case 'Humanities':
			$className = "human-icon";
			break;
		case 'Law':
			$className = "law-icon";
			break;
		case 'Science':
			$className = "sci-icon";
			break;
		case 'Business':
			$className = "busi-icon";
			break;
		case 'Engineering':
			$className = "engg-icon";
			break;
	}
	return $className;
}

function identifyMainCourseTypeForCategoryBlock($coursesByCategory) {
	$mainHeaderByCategory = "";
	if( !empty($coursesByCategory['Bachelors']) ) {
		$mainHeaderByCategory = 'Bachelors';
	}
	if( !empty($coursesByCategory['Masters']) && empty($coursesByCategory['Bachelors']) ) {
		$mainHeaderByCategory = 'Masters';
	}
	if( !empty($coursesByCategory['PhD']) && empty($coursesByCategory['Bachelors']) && empty($coursesByCategory['Masters']) ) {
		$mainHeaderByCategory = 'PhD';
	}
	if( !empty($coursesByCategory['Certificate - Diploma']) && empty($coursesByCategory['PhD']) && empty($coursesByCategory['Bachelors']) && empty($coursesByCategory['Masters']) ) {
		$mainHeaderByCategory = 'Certificate-Diploma';
	}
	return $mainHeaderByCategory;
}
?>

<script>
	var SA_UCS_DEFAULT_CATEGORY 	= "<?php echo $defaultCategory;?>";
	var SA_UCS_DEFAULT_COURSE_TYPE 	= "<?php echo $defaultCourseType;?>";
	
	var SA_UCS_DEFAULT_DEPARTMENT_ID 			= "<?php echo $departmentSectionDefaultDepartmentId;?>";
	var SA_UCS_DEFAULT_DEPARTMENT_COURSE_TYPE 	= "<?php echo $departmentSectionDefaultCourseType;?>";
	
</script>