<?php
if(!empty($other_courses_for_same_subcategory['same_subcat_courses'])){
	$maxFirstPassElements = 5;
	$firstPassCourses  = array_slice($other_courses_for_same_subcategory['same_subcat_courses'], 0, $maxFirstPassElements);
	$secondPassCourses = array_slice($other_courses_for_same_subcategory['same_subcat_courses'], $maxFirstPassElements, count($other_courses_for_same_subcategory['same_subcat_courses']));
	$displaySubCatName = $other_courses_for_same_subcategory['current_dominant_subcat_name'];
	if(trim(strtolower($displaySubCatName)) == "full time mba/pgdm"){
		$displaySubCatName = "MBA";
	}
?>
<div class="similar-courses-sec">
<div class="other-details-wrap clear-width similar-courses-section">
	<h2 class="mb14">Similar <?php echo $displaySubCatName;?> Courses in <?php echo $other_courses_for_same_subcategory['current_institute_name'];?></h2>
	<ul class="other-courses">
		<?php
		foreach($firstPassCourses as $course) {
		?>
		<li>
			<a uniqueattr="LISTING_COURSE_PAGES/MORE_COURSES_LINK_CLICK" href="<?php echo $course['course_url'];?>" title="<?php echo $course['course_title'];?>"><?php echo $course['course_title'];?></a>
			<?php
			if(!empty($course['duration_str']) || !empty($course['course_type_str'])) {
			?>
			<span>
				(<?php echo $course['duration_str']; echo !empty($course['duration_str']) ? "/ ".$course['course_type_str'] : $course['course_type_str'];?>)
			</span>
			<?php
			}
			?>
		</li>
		<?php
		}
		?>
	</ul>
	<?php
	if(!empty($secondPassCourses)){
	?>
	<a id="lp_other_courses_for_cat_anchor" href="javascript:void(0);" style="margin-left:8px;font-size:14px;" onclick="viewAllLPSameSubCatCourses();">View other <?php echo $displaySubCatName;?> courses offered by <?php echo $other_courses_for_same_subcategory['current_institute_name'];?></a>
	<ul id="lp_other_courses_for_cat" class="other-courses" style="display:none;">
		<?php
		foreach($secondPassCourses as $course) {
		?>
		<li>
			<a uniqueattr="LISTING_COURSE_PAGES/MORE_COURSES_LINK_CLICK" href="<?php echo $course['course_url'];?>"><?php echo $course['course_title'];?></a>
			<?php
			if(!empty($course['duration_str']) || !empty($course['course_type_str'])) {
			?>
			<span>
				(<?php echo $course['duration_str']; echo !empty($course['duration_str']) ? "/ ".$course['course_type_str'] : $course['course_type_str'];?>)
			</span>
			<?php
			}
			?>
		</li>
		<?php
		}
		?>
	</ul>
	<?php
	}
	?>
</div>
</div>
<?php
}
?>
<script>
	function viewAllLPSameSubCatCourses(){
		if($('lp_other_courses_for_cat')){
			$j('#lp_other_courses_for_cat_anchor').hide('slow');
			$j('#lp_other_courses_for_cat').show('slow');
		}
	}
</script>