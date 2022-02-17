<div width:100%>
	<div class="float_L">
		<div class="Fnt14" style="margin-left:5px;">Select Courses: </div>
	</div>
	<div class="float_L" style="margin-left:20px;">
			<?php
				foreach($subcategories as $category){
			?>
				<div style="margin-bottom:10px;">
				<div>
					<b style="font-size:13px;">
						<?=$category['categoryName']?>
					</b>
				</div>
			<?php
					foreach($subCategoryCourses[$category['categoryID']] as $course){
			?>
						<div style="font-weight:normal;">
							<input <?php if(in_array($course['SpecializationId'],$popCourses) >0){ echo 'checked';}?> type="checkbox" name="course" value="<?=$course['SpecializationId']?>"/> <?=$course['CourseName']?>
						</div>
			<?php
					}
			?>
				</div>
			<?php		
				}
			?>
	</div>
</div>
	<div class="clear_B"></div>
	<div style="margin-left:132px">
		<input type="button" id="setPopCourseButton" value="Set as Popular Courses" onclick="setPopCourseList();"/>
	</div>