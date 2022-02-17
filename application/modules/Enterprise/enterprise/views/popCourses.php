
<div style="padding:10px;height:100%">
	
	<div class="orangeColor bld Fnt14 ml5">
		Set Popular Courses
	</div>
	<div style="width:100%;border-top:1px solid #dddddd;margin-top:5px;">
		&nbsp;
	</div>
	<div>
		<div class="float_L">
			<div class="Fnt14" style="margin-top:2px;">Select Category: </div>
		</div>
		<div class="float_L" style="margin-left:20px;">
			<select id="maincategory" name="maincategory" size="1" onchange="getPopCourseList();">
				<option value="0">Select Category</option>
				<?php
					foreach($catTree as $category){
						if($category['parentId'] == 1){
							echo '<option value="'.$category['categoryID'].'">'.$category['categoryName'].'</option>';
						}
					}
				?>
			</select>
		</div>
	</div>
	<div class="clear_B"></div>
	<div id="popinstituteContent" style="margin:20px 0;">
		
	</div>
	
</div>
<div id="keypage_dates" style="display:none;"></div>
