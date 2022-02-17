
<div style="padding:10px;height:100%">
	
	<div class="orangeColor bld Fnt14 ml5">
		Set Category Page Header
	</div>
	<div style="width:100%;margin:20px 0;font-size:20px;">
		Page:
	</div>
	<div>
		<select id="maincategory" name="maincategory" size="1" onchange="resetCities();">
			<option value="">Select Category</option>
			<?php
				foreach($catTree as $category){
					if($category['parentId'] == 1){
						echo '<option value="'.$category['categoryID'].'">'.$category['categoryName'].'</option>';
					}
				}
			?>
		</select>
	</div>
	<div id="OR2" style="margin-top:10px;margin-left:120px;font-weight:bold">
				<div class="lineSpace_10" >OR</div>
	</div>
		<div>
				<div class="formInput" id="categoryPlace">&nbsp;</div>
				<div class="formInput normaltxt_11p_blk_verdana mb5" id="c_categories_combo" onclick="resetCities();"></div>
		</div>
		<div>
		<div id="subcategorynamesnu_error" class="errorMsg" style="padding-left:92px"></div>
		</div>
		<!-- category select end -->			
		<script>
		var completeCategoryTree = eval(<?php echo $categoryList; ?>);
		getCategories(true,"c_categories_combo","subcategory","subcategory",false,false);
		</script>
		
	<div id="OR2" style="margin:10px 0;margin-left:120px;font-weight:bold">
				<div class="lineSpace_10" >OR</div>
	</div>
	<div>
		<select id="ldbcourse" name="ldbcourse" size="1" onchange="resetCities();">
		<option value="">Select a LDB Course</option>
		<?php
			foreach($catTree as $category){
				if($category['parentId'] > 1){
					echo '<optgroup label="'.$category['categoryName'].'" title="'.$category['categoryName'].'">';
					//echo '<option> lable="'.$category['categoryName'].'"</option>';
					foreach($courseList[$category['categoryID']] as $course){
						echo '<option value="'.$course['SpecializationId'].'">'.$course['CourseName'].'</option>';
					}
					echo '</optgroup>';
				}
			}
			//echo "<pre>".print_r($courseList,true)."</pre>";
		?>
		</select>
	</div>
	<div style="margin:5px 0">
		<div id="errortext" class="errorMsg">
	</div>	
	</div>
	<div style="width:100%;margin:20px 0;font-size:20px;">
		Location:
	</div>

	<div>
		<select id = "cities" name = "cities" caption = "city" onchange="getCategorypageHeader();">
								<option value = ''>Select City</option>
								<?php
								for($j = 0;$j < count($cities); $j++) {?>
								<option value = "<?php echo $cities[$j]['cityId']?>" title="<?php echo $cities[$j]['cityName']?>"><?php echo $cities[$j]['cityName']?></option>
								<?php } ?>
		</select>
	</div>
	<div id="OR2" style="margin:10px 0;margin-left:120px;font-weight:bold">
		<div class="lineSpace_10" >OR</div>
	</div>
	<div>
		<select id = "states" name = "states"  caption = "state" onchange="getCategorypageHeader();">
								<option value = ''>Select State</option>
								<?php
								foreach($states as $state) {if($state['stateId']> 0){?>
								<option value = "<?php echo $state['stateId']?>" title="<?php echo $state['stateName']?>"><?php echo $state['stateName']?></option>
								<?php }} ?>
		</select>
	</div>
	<div id="OR2" style="margin:10px 0;margin-left:120px;font-weight:bold">
		<div class="lineSpace_10" >OR</div>
	</div>
	<div>
		<select id = "zones" name = "zones"  caption = "zone" onchange="getCategorypageHeader();">
								<option value = ''>Select Zone</option>
								<?php
								foreach($zones as $key=>$zone) {if($key> 0){?>
								<option value = "<?php echo $key?>" title="<?php echo $zone?>"><?php echo $zone?></option>
								<?php }} ?>
		</select>
	</div>
	<div id="OR2" style="margin:10px 0;margin-left:120px;font-weight:bold">
		<div class="lineSpace_10" >OR</div>
	</div>
	<div>
		<select id = "localities" name = "localities"  caption = "locality" onchange="getCategorypageHeader();">
								<option value = ''>Select Locality</option>
								<?php
								foreach($localities as $locality) {if($locality['localityId']> 0){?>
								<option value = "<?php echo $locality['localityId']?>" title="<?php echo $locality['localityName']?>"><?php echo $locality['localityName']?></option>
								<?php }} ?>
		</select>
	</div>
	<div style="margin:5px 0">
		<div id="errortext2" class="errorMsg">
	</div>	
	</div>
	<div style="width:100%;margin:20px 0;font-size:20px;">
		Text:
	</div>	
	<div>
		<textarea name = "categorytext"  id = "categorytext"></textarea>
	</div>
	<div style="margin:5px 0">
		<div id="errortext3" class="errorMsg">
	</div>	
	</div>
	<div>
		<input type="button" value="Set Text" onclick="setCategorypageHeader();"/>
	</div>

	<div class="clear_B"></div>
	
</div>
<div id="keypage_dates" style="display:none;"></div>
<script>

</script>