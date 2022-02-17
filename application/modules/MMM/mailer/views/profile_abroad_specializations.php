<li>
	<label>Specialization:</label>
	<div class="flLt">
                <div class="course-box" id="course_specialization" style="width:313px;border:1px solid #CCC;padding:5px 0;height:125px;overflow:auto">
			<div style="display:block;padding-left:5px"><input type="checkbox" onClick="checkUncheckChilds1(this, 'course_specialization_holder')" id="all_specialization" name="course_specialization[]" value="-1"/> All</div>
                        <div id="course_specialization_holder">
                            <?php foreach($course_specialization_list as $specializationId=>$specializationName){
					if($specializationName == "Other Specializations"){
						continue;
					}
			    ?>
				<div style="display:block;padding-left:5px"><input type="checkbox" name="course_specialization[]" value="<?php echo $specializationId; ?>" onClick="uncheckElement1(this,'all_specialization','course_specialization_holder');"> <?php echo $specializationName; ?></div>
                            <?php  }
			    foreach($course_specialization_list as $specializationId=>$specializationName){
					if($specializationName == "Other Specializations"){
			    ?>
				<div style="display:block;padding-left:5px"><input type="checkbox" name="course_specialization[]" value="<?php echo $specializationId; ?>" onClick="uncheckElement1(this,'all_specialization','course_specialization_holder');"> <?php echo $specializationName; ?></div>
			    <?php  }
			    }
			    ?>
                        </div>
		</div>
	</div>
</li>