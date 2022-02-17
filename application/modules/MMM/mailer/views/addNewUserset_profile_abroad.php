<form id="formForaddNewUserset" enctype="multipart/form-data" action="/mailer/Mailer/insertUserset/" method="POST" >
	<input type="hidden" value="Profile" name="usersettype" />
	<ul class="profile-form">
		<li>
			<label>Country:</label>
			<div class="flLt">
				<input type="radio" name="country" value="india" onclick="addNewUserset('profile_india');" /> India
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="country" value="abroad" checked="true" onclick="addNewUserset('profile_abroad');" /> Abroad
			</div>
		</li>
		<li>
			<label>Desired Course/Category:</label>
			<div class="flLt">
				<div class="course-box" id="course_and_category" style="width:313px;border:1px solid #CCC;padding:5px 0;height:125px;overflow:auto">
					<div style="display:block;padding-left:5px"><input type="checkbox" meta="" onchange="checkUncheckChilds1(this, 'course_categories_holder'); desiredCourseOnChangeActions();" id="all_courses_categories" name="search_category_id[]" value="-1"/> All Courses & Categories</div>
					<div id="course_categories_holder">
						<div id="courseDiv"><span style="display:block;padding-left:5px; margin: 5px;"><i>Courses</i></span>
						<?php foreach($catSubcatCourseList['popular'] as $categoryId => $category){
						?>
							<div style="display:block;padding-left:5px"><input type="checkbox" meta="popular" id ="<?php echo $category['SpecializationId']; ?>" name="search_category_id[]" value="<?php echo $category['SpecializationId']; ?>" onchange="uncheckElement1(this,'all_courses_categories','course_categories_holder'); desiredCourseOnChangeActions();"> <?php echo $category['CourseName']; ?></div>
						<?php  } ?>
						</div>
						<div id="catDiv"><span style="display:block;padding-left:5px; margin: 5px;"><i>Categories</i></span>
						<?php foreach($catSubcatCourseList['category'] as $categoryId => $category){
						?>
							<div style="display:block;padding-left:5px"><input type="checkbox" meta="category" id="<?php echo $category['categoryID']; ?>" name="search_category_id[]" value="<?php echo $category['categoryID']; ?>" onchange="uncheckElement1(this,'all_courses_categories','course_categories_holder'); desiredCourseOnChangeActions();"> <?php echo $category['categoryName']; ?></div>
						<?php  }
						?>
						</div>
					</div>
				</div>
			</div>
		</li>
		<li id="course_level_holder" style='display:none;'>
			<label>Course Level:</label>
			<div class="flLt" id="course_level_div">
				<input type="checkbox" value="ug" id="desiredCourseLevelUG" name="desiredCourseLevel[]" /> Bachelors
				&nbsp;&nbsp;&nbsp;
				<input type="checkbox" value="pg" id="desiredCourseLevelPG" name="desiredCourseLevel[]" /> Masters
				&nbsp;&nbsp;&nbsp;
				<input type="checkbox" value="phd" id="desiredCourseLevelPHD" name="desiredCourseLevel[]" /> Ph.D.
			</div>
		</li>
		<li id="specialization_holder" style='display:none;'></li>
		<!--< ?php $this->load->view('mailer/profile_abroad_exams'); ?>
		<li>
			<label>Student Budget:</label>
			<div class="flLt">
				< ?php foreach($budgetValues as $budgetValue => $budgetDisplay) { ?>
				<input type="checkbox" value="< ?php echo $budgetValue; ?>" name="budget[]"> < ?php echo $budgetDisplay; ?></input>&nbsp;&nbsp;<br/>
				< ?php } ?>
			</div>
		</li>-->
		<li>
			<label>Student Passport:</label>
			<div class="flLt">
				<select id='passportDiv' name='passport' class="width-200">
					<option value=''>Select</option>
					<option value='yes'>Yes</option>
					<option value='no'>No</option>
				</select>
			</div>
		</li>
		<?php $this->load->view('mailer/profile_destination_country'); ?>
		<?php $this->load->view('mailer/profile_current_location'); ?>
	</ul>
	<?php $this->load->view('mailer/filterbytime'); ?>
</form>
<script>
	function desiredCourseOnChangeActions(){
		var noOfCheckedElements = $j('#course_and_category').find("input[name='search_category_id[]']:checked").length;
		var noOfCheckedCategories = $j('#course_and_category').find("input[meta='category']:checked").length;
			
		if (noOfCheckedCategories > 0) {
			$('course_level_holder').style.display = '';
		}
		else {
			$('course_level_holder').style.display = 'none';
			var courseLevelDiv = document.getElementById('course_level_div');
			var inputs = courseLevelDiv.getElementsByTagName("input");
			for (var i = 0; i < inputs.length; i++) {  
				if (inputs[i].type == "checkbox") {  
					inputs[i].checked = false;
				}  
			} 
		}
		if (noOfCheckedElements == 1) {
			var ldbCourseId = $j('#course_and_category').find("input[type='checkbox']:checked").attr('value');
			var type = $j('#course_and_category').find("input[type='checkbox']:checked").attr('meta');
			var url = '/mailer/Mailer/getSpecializationsForStudyAbroad/'+ldbCourseId+'/'+type;
			new Ajax.Request( url,
				{   method:'post',
				    onSuccess:function(request){
						if (request.responseText) {
							$('specialization_holder').innerHTML = request.responseText;
							$('specialization_holder').style.display = '';
							ajax_parseJs($('specialization_holder'));
						}
						else {
							$('specialization_holder').innerHTML = '';
							$('specialization_holder').style.display = 'none';
						}
					}
				}
			);
			return true;
		}
		else {
			$('specialization_holder').innerHTML = '';
			$('specialization_holder').style.display = 'none';
			return true;
		}
	}
</script>