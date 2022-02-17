<?php 
$marginTopStyle = '';
if(count($importantDatesData['examsHavingDates']) > 1 || (count($importantDatesData['examsHavingDates']) > 0 && $importantDatesData['isCourseDates'])){?>	
	<div class="dropdown-primary">
	    <input class="option-slctd" id="importantDatesSelect_input" readonly="true" value="All">
	</div>
	<div class="select-Class">
		<select id="importantDatesSelect" style="display:none;">
		<option value="" ga-attr="ADMISSION_EXAM_FILTER_LAYER_COURSEDETAIL_DESKTOP">All</option>
		<?php 
	    foreach ($importantDatesData['examsHavingDates'] as $row) { ?>
			<option value="<?php echo $row['exam_id']; ?>" ga-attr="ADMISSION_EXAM_FILTER_LAYER_COURSEDETAIL_DESKTOP"><?php echo $row['exam_name']?></option>
	    <?php
	    }
	    ?>
		</select>
	</div>
<?php
} else {
	$marginTopStyle = 'margin-top:5px;';
	} ?>


<?php if(count($importantDatesData['importantDates']) > 0){ 
	$this->load->view('mobile_listing5/course/widgets/courseImportantDatesWidget', array('marginTopStyle' => $marginTopStyle));
}
?>
