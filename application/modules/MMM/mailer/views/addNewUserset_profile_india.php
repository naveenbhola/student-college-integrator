<form id="formForaddNewUserset" enctype="multipart/form-data" action="/mailer/Mailer/insertUserset/" method="POST" >
	<input type="hidden" value="Profile" name="usersettype" />
	<ul class="profile-form">
		<li>
			<label>Country:</label>
			<div class="flLt">
				<input type="radio" name="country" value="india" checked="true" onclick="addNewUserset('profile_india');" /> India
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="country" value="abroad" onclick="addNewUserset('profile_abroad');" /> Abroad
			</div>
		</li>
		<li>
			<label>Desired Category:</label>
			<div class="flLt">
				<select onchange="getCourseForCategory(this.value)" id="catDiv" name="search_category_id" class="width-200">
					<?php
						echo "<option value=0>Choose a Category</option>";
						foreach($catSubcatCourseList as $key=>$parent){
							echo "<option value=".$key.">".$parent['name']."</option>";
						}
					?>
				</select>
			</div>
		</li>
		<li>
			<label>Desired LDBCourse:</label>
			<div class="flLt" id="wrapper_courseDiv">
				<div class="course-box">Choose a Course</div>
			</div>
		</li>
		<!--<li>
			<label>Mode of Study:</label>
			<div class="flLt">
				<input type="checkbox" value="full_time" name="full_time_mode" /> Full Time
				&nbsp;&nbsp;&nbsp;
				<input type="checkbox" value="part_time" name="part_time_mode" /> Part Time
			</div>
		</li>
		<li>
			<label>Degree Preference:</label>
			<div class="flLt">
				<input type="checkbox" value="aicte_approved" name="aicte_deg_pref" /> AICTE Approved
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="checkbox" value="ugc_approved" name="ugc_deg_pref" /> UGC Approved
				<div class="spacer5 clearFix"></div>
				<input type="checkbox" value="international" name="international_deg_pref" /> International Degree
				&nbsp;&nbsp;&nbsp;
				<input type="checkbox" value="no_preference" name="any_deg_pref" /> No Preference
			</div>
		</li>-->
		<li id='examBlock' style='display:none;'></li>
		<?php $this->load->view('mailer/profile_education'); ?>
		<?php $this->load->view('mailer/profile_current_location'); ?>
		<!--< ?php $this->load->view('mailer/profile_location_operator'); ?>-->
		<!--< ?php $this->load->view('mailer/profile_preferred_location'); ?>-->
		<!--< ?php $this->load->view('mailer/profile_preferred_locality'); ?>-->
		<?php $this->load->view('mailer/profile_current_locality'); ?>
		<!--< ?php $this->load->view('mailer/profile_generalinfo'); ?>-->
	</ul>
	<?php $this->load->view('mailer/filterbytime'); ?>
</form>
