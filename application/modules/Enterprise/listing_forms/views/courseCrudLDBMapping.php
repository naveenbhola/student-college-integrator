<script>
var catSubcatCourseList = <?php echo json_encode($catSubcatCourseList); ?>;
// To provide a subcategory list which specifies LDB courses that are to be excluded in enterprise add new course form.
var LDBCourseExclusionList = <?php echo json_encode($restrictedLDBCourse); ?>;
currentDiv = 0;
</script>
<div class="row">
	<div class="row1 Fnt13"><b>Map to LDB Course<span class="redcolor fontSize_13p">*</span><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'map_ldb_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</b></div>
	<div id="map_ldb_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('map_ldb_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Should be a best match to the category/ Sub category/ course if main category is not available .</li>
					<li>Can be matched with more LDB category/ sub category/ courses if it’s a more than one match.</li>
					<li>Example:<br/> 
					MBA in Hospital Management can be mapped with<br/>
					<ul>
					<li>Medicine, Beauty & Health Care > Health Care & Hospital Management >   
             MBA in Hospital Management and/or</li>
					<li>Management > Full Time MBA> MBA in hospital Management </li>
					</ul>
                                        </li>
					
					</ul>
	</div>	
	<div class="row2">
		<?php
			$displayLimit = count($ldbMappedCourses)?count($ldbMappedCourses):1;
			for($i=1 ; $i<=$displayLimit ; $i++){ ?>
				<div class="courseMapDiv" style="display:<?php if($i>$displayLimit) {echo 'none';}else{echo '';}?>" id="courseMapDiv_<?php echo $i?>">
					<select onchange="getSubCatForCategory(this.value,<?php echo $i;?>)" id="catDiv_<?php echo $i;?>" name="c_categories[]">
					<?php echo "<option value=0>Choose a Category</option>";
						foreach($catSubcatCourseList as $key=>$parent){
							echo "<option value=".$key;
							if($key == $ldbMappedCourses[$i-1]['parentId'])
								echo ' selected';
							echo ">".$parent['name']."</option>";
						}
					?>
					</select>
					<div class="spacer5 clearFix"></div>
					<div id="wrapper_subCatDiv_<?php echo $i;?>">
						<select id="subCatDiv_<?php echo $i;?>" name="courseSubcatMap[]" onchange="getCourseForSubCategory(this.value,<?php echo $i;?>)">
							<option value=0>Choose a SubCategory</option>
						</select>
					</div>
					<div class="spacer5 clearFix"></div>
					<div id="wrapper_courseDiv_<?php echo $i;?>">
						<select id="courseDiv_<?php echo $i;?>" name="courseMap_<?php echo $i; ?>">
							<option value=0>Choose a Course</option>
						</select>
					</div>
					<div class="spacer5 clearFix"></div>
					<?php if($i>1){
						$style = '';
					} else {
						$style = 'display: none';
					} ?>
					<div class="courseMapDelete" style="<?php echo $style;?>" ><a href="javascript:void(0)" onclick="removeElementChunk(this, LDB_COURSE_MAPPING_LIMIT);">- Remove</a></div>
					<div class="spacer5 clearFix"></div>
					
				</div>
				<?php
					if($ldbMappedCourses[$i-1]['parentId']){ ?>
					<script>
						getSubCatForCategory(<?=$ldbMappedCourses[$i-1]['parentId']?>,<?=$i?>,<?=$ldbMappedCourses[$i-1]['categoryID']?>);
						getCourseForSubCategory(<?=$ldbMappedCourses[$i-1]['categoryID']?>,<?=$i?>,<?=$ldbMappedCourses[$i-1]['LDBCourseID']?>);
					</script>
				<?php }
			}
			$style = '';
			if($i > LDB_COURSE_MAPPING_LIMIT) {
				$style = 'display: none';
			}
		?>
		<div class="spacer5 clearFix"></div>
		<div id="courseMapAdd" style='<?php echo $style; ?>'><a href="javascript:void(0)" onclick="addMoreCourseMaps(LDB_COURSE_MAPPING_LIMIT)">+ Add more</a></div>
		<div style="display: none;"><div class="errorMsg" id="c_shiksha_map_error">show</div></div>
	</div>
</div>

<script>
currentDiv = '<?=($displayLimit)?>';
LDB_COURSE_MAPPING_LIMIT = <?=LDB_COURSE_MAPPING_LIMIT?>;
//showCourseMapDiv();

function removeCourseMapDiv(index) {
	if(typeof($j) == 'undefined') {
		return false;
	}
	
	var ids_to_reset = ['catDiv','subCatDiv','courseDiv'];
	
	$j.each(ids_to_reset,function(index1,element1){
		$j("#"+element1+"_"+index).val("0");
	});
	
	$j('#courseMapDiv_'+index).hide();
	
	if(typeof(currentDiv) !='undefined') {
		currentDiv--;
		if(currentDiv <= LDB_COURSE_MAPPING_LIMIT) {
			$j('#courseMapAdd').show();
		}
	}
}
</script>
