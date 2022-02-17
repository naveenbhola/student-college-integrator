<?php 

switch($level){

	case 'xth':?>
			<h4>School Name </h4>
            <div>
                <input type="text" name="xthSchool" id="xthSchool" regFieldId="xthSchool" class="register-fields form-control" placeholder="Xth School" value="" default="Xth School" profanity="1" />
            </div>

            <h4>Xth Completion Year </h4>
            <div>
                <input type="text" name="xthCompletionYear" id="xthCompletionYear" regFieldId="xthYear" class="register-fields form-control" placeholder="Xth Completion Year" value="" default="Xth Completion Year" profanity="1" />
            </div>

            <h4>tenth Board </h4>
            <div>
                <select caption="10th Board" label="10th Board" mandatory="1" regfieldid="tenthBoard" class="form-control" name="tenthBoard">
						<option value="">Select Board</option>
						<option value="CBSE">CBSE</option>
						<option value="ICSE">ICSE/State Boards</option>
						<option value="IGCSE">Cambridge IGCSE</option>
                        <option value="IBMYP">International Baccalaureate</option>
                        <option value="NIOS">NIOS</option>
				</select>
            </div>

            <h4>Xth Marks</h4>
            <div>
                <input type="text" name="tenthmarks" id="tenthmarks" regFieldId="tenthmarks" class="register-fields form-control" placeholder="Xth Marks" value="" default="Xth Marks" profanity="1" />
            </div>

            <input type='hidden' name="CurrentSubjects[]" value="Accountancy" />
            <input type='hidden' name="CurrentSubjects[]" value="Biology" />
            <input type='hidden' name="CurrentSubjects[]" value="Business Studies/Management" />

	<?php break;

	case 'xiith': ?>
			<h4>School Name </h4>
            <div>
                <input type="text" name="xiithSchool" id="xiithSchool" regFieldId="xiithSchool" class="register-fields form-control" placeholder="Xiith School" value="" default="Xiith School" profanity="1" />
            </div>

            <h4>XII Specialization </h4>
            <div>
                <input type="text" name="xiiSpecialization" id="xiiSpecialization" regFieldId="xiiSpecialization" class="register-fields form-control" placeholder="xii Specialization" value="" default="xii Specialization" profanity="1" />
            </div>

            <h4>XIIth Year</h4>
            <div>
                <input type="text" name="xiiYear" id="xiiYear" regFieldId="xiiYear" class="register-fields form-control" placeholder="XII Year" value="" default="XII Year" profanity="1" />
            </div>

            <h4>XIIth Marks</h4>
            <div>
                <input type="text" name="xiiMarks" id="xiiMarks" regFieldId="xiiMarks" class="register-fields form-control" placeholder="xii Marks" value="" default="xii Marks" profanity="1" />
            </div>
	<?php break;

	case 'bachelors': ?>
			<h4>Bachelors Degree</h4>
            <div>
                <input type="text" name="bachelorsDegree" id="bachelorsDegree" regFieldId="bachelorsDegree" class="register-fields form-control" placeholder="Bachelors Degree" value="" default="Bachelors Degree" profanity="1" />
            </div>

            <h4>bachelors University </h4>
            <div>
                <input type="text" name="bachelorsUniv" id="bachelorsUniv" regFieldId="bachelorsUniv" class="register-fields form-control" placeholder="bachelors University" value="" default="bachelors University" profanity="1" />
            </div>

            <h4>Bachelors College</h4>
            <div>
                <input type="text" name="bachelorsCollege" id="bachelorsCollege" regFieldId="bachelorsCollege" class="register-fields form-control" placeholder="Bachelors College" value="" default="XII Year" profanity="1" />
            </div>

            <h4>Bachelors Marks</h4>
            <div>
                <input type="text" name="bachelorsMarks" id="bachelorsMarks" regFieldId="bachelorsMarks" class="register-fields form-control" placeholder="Bachelors Marks" value="" default="bachelorsMarks" profanity="1" />
            </div>

            <h4>Bachelors Stream</h4>
            <div>
                <input type="text" name="bachelorsStream" id="bachelorsStream" regFieldId="bachelorsStream" class="register-fields form-control" placeholder="Bachelors Stream" value="" default="bachelorsStream" profanity="1" />
            </div>

            <h4>Bachelors Specialization</h4>
            <div>
                <input type="text" name="bachelorsSpec" id="bachelorsSpec" regFieldId="bachelorsSpec" class="register-fields form-control" placeholder="Bachelors Specialization" value="" default="bachelorsSpec" profanity="1" />
            </div>

            <h4>Graduation Completion Year</h4>
            <div>
                <input type="text" name="graduationCompletionYear" id="graduationCompletionYear" regFieldId="graduationCompletionYear" class="register-fields form-control" placeholder="Bachelors completion year" value="" default="graduationCompletionYear" profanity="1" />
            </div>
	<?php break;

		case 'masters': ?>
			<h4>Masters Degree</h4>
            <div>
                <input type="text" name="mastersDegree" id="mastersDegree" regFieldId="mastersDegree" class="register-fields form-control" placeholder="Masters Degree" value="" default="Masters Degree" profanity="1" />
            </div>

            <h4>Masters University </h4>
            <div>
                <input type="text" name="mastersUniv" id="mastersUniv" regFieldId="mastersUniv" class="register-fields form-control" placeholder="Masters University" value="" default="Masters University" profanity="1" />
            </div>

            <h4>Master College</h4>
            <div>
                <input type="text" name="mastersCollege" id="mastersCollege" regFieldId="mastersCollege" class="register-fields form-control" placeholder="Master College" value="" default="Master College" profanity="1" />
            </div>

            <h4>Masters Marks</h4>
            <div>
                <input type="text" name="mastersMarks" id="masterssMarks" regFieldId="mastersMarks" class="register-fields form-control" placeholder="Masters Marks" value="" default="mastersMarks" profanity="1" />
            </div>

            <h4>Masters Stream</h4>
            <div>
                <input type="text" name="mastersStream" id="mastersStream" regFieldId="mastersStream" class="register-fields form-control" placeholder="Masters Stream" value="" default="mastersStream" profanity="1" />
            </div>

            <h4>Masters Specialization</h4>
            <div>
                <input type="text" name="mastersSpec" id="mastersSpec" regFieldId="mastersSpec" class="register-fields form-control" placeholder="Masters Specialization" value="" default="mastersSpec" profanity="1" />
            </div>
            
            <h4>Masters Completion Year</h4>
            <div>
                <input type="text" name="mastersCompletionYear" id="mastersCompletionYear" regFieldId="mastersCompletionYear" class="register-fields form-control" placeholder="Masters Completion Year" value="" default="mastersCompletionYear" profanity="1" />
            </div>
	<?php break;

		case 'phd': ?>
			<h4>PhD Degree</h4>
            <div>
                <input type="text" name="phdDegree" id="phdDegree" regFieldId="phdDegree" class="register-fields form-control" placeholder="PhD Degree" value="" default="PhD Degree" profanity="1" />
            </div>

            <h4>PhD University </h4>
            <div>
                <input type="text" name="phdUniv" id="phdUniv" regFieldId="phdUniv" class="register-fields form-control" placeholder="PhD University" value="" default="PhD University" profanity="1" />
            </div>

            <h4>PhD College</h4>
            <div>
                <input type="text" name="phdCollege" id="phdCollege" regFieldId="phdCollege" class="register-fields form-control" placeholder="PhD College" value="" default="PhD Year" profanity="1" />
            </div>

            <h4>PhD Marks</h4>
            <div>
                <input type="text" name="phdMarks" id="phdMarks" regFieldId="phdMarks" class="register-fields form-control" placeholder="PhD Marks" value="" default="phdMarks" profanity="1" />
            </div>

            <h4>PhD Stream</h4>
            <div>
                <input type="text" name="phdStream" id="phdStream" regFieldId="phdStream" class="register-fields form-control" placeholder="PhD Stream" value="" default="phdStream" profanity="1" />
            </div>

            <h4>PhD Specialization</h4>
            <div>
                <input type="text" name="phdSpec" id="phdSpec" regFieldId="phdSpec" class="register-fields form-control" placeholder="PhD Specialization" value="" default="phdSpec" profanity="1" />
            </div>
            
            <h4>PhD Completion Year</h4>
            <div>
                <input type="text" name="phdCompletionYear" id="phdCompletionYear" regFieldId="phdCompletionYear" class="register-fields form-control" placeholder="PhD Completion Year" value="" default="graduationCompletionYear" profanity="1" />
            </div>
	<?php break;
}