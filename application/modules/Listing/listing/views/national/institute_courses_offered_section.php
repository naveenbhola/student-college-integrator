<?php
if(!empty($course_browse_section_data)) {
?>
	<div class="other-details-wrap clear-width all-courses-sec">
		<h2 style="margin:0;">Courses Offered</h2>
		<div class="institute-accordian" id="accordion">
			<ul>
				<?php
					$mainCategoryFlag 		= TRUE;
					$defaultSubCategoryId 	= FALSE;
					$defaultMainCatOpen = TRUE;
					if(count($course_browse_section_data) > 1){
						$defaultMainCatOpen = FALSE;
						$mainCategoryFlag 	= FALSE;
					}
					if(count($course_browse_section_data) > 1){
						echo '<script>var course_browse_section_data_flag = true;</script>';
					}
					else{
						echo '<script>var course_browse_section_data_flag = false;</script>';
					}
					foreach($course_browse_section_data as $categoryId => $categorySection) {
						$categoryIconClass = getMainIconClassByCategory($categoryId);
						$catHeaderStyle = "cursor:pointer";
						if($mainCategoryFlag) { //Default open
							$categoryIconClass = getMainIconClassByCategory($categoryId, TRUE);
							$catHeaderStyle = "cursor:default";
						}
					?>
						<li class="clear-width">
							<div class="offered-course" id="ICS_cat_block_<?php echo $categoryId;?>">
								<div class="course-icons"><i id="ICS_icon_<?php echo $categoryId;?>" class="sprite-bg <?php echo $categoryIconClass;?>"></i></div>
								<div class="course-header">
									<h3 style="<?php echo $catHeaderStyle;?>" id="ICS_cat_header_<?php echo $categoryId;?>" uniqueattr="LISTING_INSTITUTE_PAGES/CO_CAT_HEADER_<?php echo $categoryId;?>" onclick="ICS_toggleCategorySection(<?php echo $categoryId;?>, true);">
										<span class="flLt"><?php echo $categorySection['category_name']; ?></span>
									</h3>
									<div class="course-tab clear-width">
										<ul>
											<?php
											$mainSubCategoryFlag = TRUE;
											foreach($categorySection['subcategory_courses'] as $subcategoryCourses){
												$subcategoryName = $subcategoryCourses['subcategory_name'];
												$subcategoryId = $subcategoryCourses['subcategory_id'];
												$subCategoryShortName = $subcategoryCourses['subcategory_short_name'];
												$subcategoryCourseCount = $subcategoryCourses['course_count'];
												$subCategoryTitleClass = "";
												if($mainSubCategoryFlag && $mainCategoryFlag) {
													$subCategoryTitleClass = "active";
													$mainSubCategoryFlag 	= FALSE;
													$defaultSubCategoryId 	= $subcategoryId;
												}
												?>
												<li><a href="javascript:void(0);" value="<?php echo $subcategoryId;?>" uniqueattr="LISTING_INSTITUTE_PAGES/CO_SUBCAT_LBL_<?php echo $subcategoryId;?>" id="ICS_subcat_header_<?php echo $subcategoryId;?>" label="ICS_subcatlabel" onclick="ICS_toggleSubCatLabel('<?php echo $categoryId."_".$subcategoryId;?>');" class="<?php echo $subCategoryTitleClass;?>"><?php echo $subCategoryShortName;?> <span>(<?php echo $subcategoryCourseCount;?>)</span></a></li>
												<?php
											}
											?>
										</ul>
									</div>
									<?php
									$courseDataSectionFlag = TRUE;
									$courseDataSectionDisplayStyle = "block";
									if($courseDataSectionFlag && $mainCategoryFlag){
										$courseDataSectionDisplayStyle = "block";
										$courseDataSectionFlag = FALSE;
									}
									?>
									<div id="coursedata_section_<?php echo $categoryId;?>" style="display:<?php echo $courseDataSectionDisplayStyle;?>">
									<?php
									$subCategoryCourseSectionFlag = TRUE;
									foreach($categorySection['subcategory_courses'] as $subcategoryCourses) {
										$tempSubCategoryId = $subcategoryCourses['subcategory_id'];
										$courses = $subcategoryCourses['courses'];
										$subCategoryCoursesBlockStyle = "block";
										if($subCategoryCourseSectionFlag){
											$subCategoryCoursesBlockStyle = "block";
											$subCategoryCourseSectionFlag = FALSE;
										}
										?>
										<div class="scrollbar1 clear-width" style="padding-bottom: 10px;" id="cds_scroll_<?php echo $tempSubCategoryId;?>">
											<div class="scrollbar" style="display:none;">
												<div class="track">
													<div class="thumb">
														<div class="end"></div>
													</div>
												</div>
											</div>
											<div class="viewport">
												<div class="overview">
													<div class="course-tab-detail clear-width" style="display:<?php echo $subCategoryCoursesBlockStyle;?>" label="cds_subcat" id="cds_<?php echo $tempSubCategoryId;?>">
														<ul class="other-courses">
															<?php
															foreach($courses as $course) {
																$flagDuration = false;
																if(!empty($course['duration_str']))
																	$flagDuration = true;
															?>
																<li><a uniqueattr="LISTING_INSTITUTE_PAGES/CO_LINK_CLICK" href="<?php echo $course['course_url'];?>"><?php echo $course['course_title']; if($flagDuration) { ?>, <?php } ?></a> <span><?php echo $course['duration_str'];?></span></li>
															<?php } ?>
														</ul>
													</div>
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
									$accordianFlag = TRUE;
									$accordianClass = "dwn-arrow";
									
									if($accordianFlag && $mainCategoryFlag) {
										$accordianClass = "up-arrow";
                                        $appendStyle ="style='cursor: default'";
										$accordianFlag = FALSE;
									}
									?>
									<i id="accordian_arrow_<?php echo $categoryId;?>" uniqueattr="LISTING_INSTITUTE_PAGES/CO_ACCORDIAN_ARROW_<?php echo $categoryId;?>" onclick="ICS_toggleCategorySection(<?php echo $categoryId;?>, true);" <?php if(!empty($appendStyle)) {echo $appendStyle;}?> class="sprite-bg <?php echo $accordianClass;?>"></i>
								</div>
							</div>
						</li>
				<?php
					$mainCategoryFlag = FALSE;
					}
				?>
			 </ul>
		</div>
	</div>
<?php
}
?>
<?php
function getMainIconClassByCategory($categoryId, $active = FALSE) {
	$className = "";
	switch($categoryId){
		case 2: //Engineering
			$className = $active == TRUE ? "science-icon-active" : "science-icon";
			break;
		case 3: //Management
			$className = $active == TRUE ? "management-icon-active" : "management-icon";
			break;
		case 4: //Banking & Finance
			$className = $active == TRUE ? "banking-icon-active" : "banking-icon";
			break;
		case 5: // Medicine, Beauty & Health Care 	
			$className = $active == TRUE ? "medicine-icon-active" : "medicine-icon";
			break;
		case 6: //Hospitality, Aviation & Tourism
			$className = $active == TRUE ? "hospital-icon-active" : "hospital-icon";
			break;
		case 7: //Media, Films & Mass Communication 	
			$className = $active == TRUE ? "media-icon-active" : "media-icon";
			break;
		case 9: // Arts, Law, Languages and Teaching 	
			$className = $active == TRUE ? "law-icon-active" : "law-icon";
			break;
		case 10: // Information Technology
			$className = $active == TRUE ? "tech-icon-active" : "tech-icon";
			break;
		case 11: // Retail
			$className = $active == TRUE ? "retail-icon-active" : "retail-icon";
			break;
		case 12: // Animation, Visual Effects, Gaming & Comics (AVGC)
			$className = $active == TRUE ? "animation-icon-active" : "animation-icon";
			break;
		case 13: // Design
			$className = $active == TRUE ? "design-icon-active" : "design-icon";
			break;
		case 14: // Test Preparation
			$className = $active == TRUE ? "test-icon-active" : "test-icon";
			break;
	}
	return $className;
}
?>
<script>
	var ICS_defaultMainCatOpen = '<?php echo $defaultMainCatOpen;?>';
	var ICS_defaultSubCategoryId = '<?php echo $defaultSubCategoryId;?>';
	
</script>