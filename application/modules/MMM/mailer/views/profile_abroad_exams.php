<?php global $examGrades; ?>
<li>
	<label>Exams:</label>
	<div class="flLt">
		<div class="course-box" id="abroad_exams_holder">
		<?php
			$exams = array(
				'TOEFL' => array('marksType' => ' Marks', 'minScore' => '0', 'maxScore' => '120', 'range' => '1'),
				'IELTS' => array('marksType' => ' Marks', 'minScore' => '1', 'maxScore' => '9', 'range' => '0.5'),
				'GMAT' => array('marksType' => ' Score', 'minScore' => '200', 'maxScore' => '800', 'range' => '10'),
				'GRE' => array('marksType' => ' Marks', 'minScore' => '260', 'maxScore' => '340', 'range' => '1'),
				'SAT' => array('marksType' => ' Points', 'minScore' => '600', 'maxScore' => '2400', 'range' => '1'),
				'PTE' => array('marksType' => ' Marks', 'minScore' => '10', 'maxScore' => '90', 'range' => '1'),
				'CAEL' => array('marksType' => ' Marks', 'minScore' => '10', 'maxScore' => '90', 'range' => '10'),
				'MELAB' => array('marksType' => ' Marks', 'minScore' => '33', 'maxScore' => '99', 'range' => '1'),
				'CAE' => array('marksType' => ' Grades', 'minScore' => 'C', 'maxScore' => 'A'),
				);
			foreach($exams as $examName => $examProperties) {
		?>
				<div class="clearFix"></div>
				<input type="checkbox" id="exam_<?php echo $examName; ?>" name="exam_<?php echo $examName; ?>" onclick="competitiveExamScore(this)" /> <?php echo $examName; ?> &nbsp;<?php echo $examProperties['marksType']; ?>:
				<select id="<?php echo $examName; ?>_min_score" name="<?php echo $examName; ?>_min_score" disabled="true" class="width-100" onclick="validateExamMax('<?php echo $examName; ?>')">
					<option value="">Min</option>
					<?php
					if($examProperties['marksType'] == ' Grades') {
					    foreach(range($examProperties['minScore'],$examProperties['maxScore']) as $gradeScore) {
						echo "<option value=\"".$examGrades[$examName][$gradeScore]."\">".$gradeScore."</option>";
					    }
					}
					else {
					    $range = $examProperties['range'] ? $examProperties['range'] : 1;
					    for($i=$examProperties['minScore'];$i<=$examProperties['maxScore'];$i+=$range)
					    {
						echo "<option value=\"".$i."\">".$i."</option>";
					    }
					}
					?>
				</select>&nbsp;&nbsp;
				<select id="<?php echo $examName; ?>_max_score" name="<?php echo $examName; ?>_max_score" disabled="true" class="width-100" onclick="validateExamMax('<?php echo $examName; ?>')">
					<option value="">Max</option>
					<?php
					if($examProperties['marksType'] == ' Grades') {
					    foreach(range($examProperties['minScore'],$examProperties['maxScore']) as $gradeScore) {
						echo "<option value=\"".$examGrades[$examName][$gradeScore]."\">".$gradeScore."</option>";
					    }
					}
					else {
					    $range = $examProperties['range'] ? $examProperties['range'] : 1;
					    for($i=$examProperties['minScore'];$i<=$examProperties['maxScore'];$i+=$range)
					    {
						echo "<option value=\"".$i."\">".$i."</option>";
					    }
					}
					?>
				</select>
		<?php
			}
		?>
		</div>
	</div>
</li>