<?php
// get the global values
global $filters;
global $appliedFilters;

// fetch Parent and current specialization values from Specialization Filter object(s)
$LDBCourses 			= $LDBCourseRepository->getLDBCoursesForSubCategory($request->getSubCategoryId());
$specializationValues		= $filters['specialization']->getFilteredValues();
$subCatObj 			= $categoryRepository->find($request->getSubCategoryId());

foreach($LDBCourses as $ldbCourse)
{
      $ldbCourseObj[$ldbCourse->getId()] = $ldbCourse;
}

foreach($specializationValues as $specializationId)
{
		if($ldbCourseObj[$specializationId])
				$finalLdbCourseObj[] = $ldbCourseObj[$specializationId];
}
// sort the current specialization filter values by their count
//asort($solrFacetValues['course_ldb_id_current']);
$Applicablespecializations 	= array_keys($solrFacetValues['course_ldb_id_current']);
?>
		<!-- Specialization filter starts -->
		<div class="filter-blocks clear last" id="specializationFilterSection">
				<div class="mb10">
						<h3 class="filter-title flLt" style="margin-bottom:0;">
						<i class="cate-new-sprite specialization-icon"></i>Specialisation</h3>
						<a class="flRt" href="javascript:void(0);" onclick="resetCategoryFilter('specializationFilterSection');"><i class="cate-new-sprite reset-icon"></i>Reset</a>
						<div class="clearfix"></div>
				</div>
				<div class="customInputs">
						<div class="scrollbar1" id="specializationFilterScrollbar">
							<div class="scrollbar">
								<div class="track">
									<div class="thumb"></div>
								</div>
							</div>
							<div class="viewport" style="height:145px;overflow:hidden;">
									<div class="overview">
											 <ul class="refine-list">
											<?php
											$key = 0;
											//foreach($LDBCourses as $ldbCourse)
											
											foreach($finalLdbCourseObj as $ldbCourse)
											
											{
										            $countVal = 0;
											    // check if the LDB course is an eligible specialization for this page
											    if(!($subCatObj->getName() == $ldbCourse->getCourseName() && $ldbCourse->getSpecialization() == 'All')
											       && in_array($ldbCourse->getId(),$specializationValues))
											    {
													$disabled = "disabled";
													$checked  = "";
													// determine for disabled state and count to be shown
													if(in_array($ldbCourse->getId(), $Applicablespecializations))
													{
														$disabled = "";
														$countVal = $solrFacetValues['course_ldb_id_current'][$ldbCourse->getId()] ? $solrFacetValues['course_ldb_id_current'][$ldbCourse->getId()] : 0;
													}
													// determine for checked state of the checkbox
													if(in_array($ldbCourse->getId(),$appliedFilters['specialization']) || ($ldbCourse->getId() == $request->getLDBCourseId() && empty($appliedFilters['specialization']) && !is_array($appliedFilters['specialization']))){
														$checked = "checked";
													}
													$specializatioName = $ldbCourse->getSpecialization() == 'All' ? $ldbCourse->getCourseName() : $ldbCourse->getSpecialization();
											?>
													<li>
														<input type="checkbox" id="specialization_<?=$key?>" <?=$checked ." ". $disabled?> name="specialization[]" value="<?=$ldbCourse->getId()?>" onclick="applyRnRFiltersOnCategoryPages();" autocomplete="off">
														<label for="specialization_<?=$key?>">
																<span class="common-sprite"></span><p><?=$specializatioName?> <em>(<?=$countVal?>)</em></p>
														</label>
													</li>
											<?php
											    }
											    $key++;
											}
											?>
											</ul>
									</div>
							</div>
						</div>
				</div>
		</div>
		<!-- Specialization filter Ends -->
