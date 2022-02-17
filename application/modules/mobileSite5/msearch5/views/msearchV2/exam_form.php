<?php 
		// $ExamPageLib             = $this->load->library('examPages/ExamPageLib');
	// $categoriesWithExamNames = $ExamPageLib->getCategoriesWithExamNames();
?>
<form formname="exams" onsubmit="return false;">
	<ul>
		<li>
             <?php $this->load->view('msearch5/msearchV2/mSelectWidget', array('id' => 'dropDown4','data'=>$categories,'placeholder'=>"placeholder='Select Stream'",'firstDisabled'=>true,'onchange'=>"onchange=getExamPagesForCategory()")); ?>
        </li>
        <li>
             <?php $this->load->view('msearch5/msearchV2/mSelectWidget', array('id' => 'dropDown5','disabled'=>true,'placeholder'=>"placeholder='Select Exam'",'firstDisabled'=>true,'onchange'=>"onchange=onChangeExamList()")); ?>
        </li>
<!-- 		<li>
			<select id="examSearchCategory" onclick="setTimeout(function() {$('.SumoSelect > .optWrapper:visible').css('height','auto !important');}, 500); event.preventDefault(); return false;" placeholder="Select Stream" class="select-stream" onchange="window.history.back();getExamPagesForCategory();">
				<option disabled="disabled" selected="selected"></option>
				<?php 
    				// foreach ($subCategoriesEntranceExamGNB as $key => $valueArr){
    				// 	?>
    				// 	<option value="<?php echo $key;?>"><?php echo $key;?></option>
    				// 	<?php
    				// }
    			?>
			</select>
			<p class='clr'></p>
			<span style="display:none;" class='select_err' id="examSearchCategory_error">Please select the stream</span>
		</li> -->
		<!-- <li>
			<select id="examSearchExams" onclick="setTimeout(function() {$('.SumoSelect > .optWrapper:visible').css('height','auto !important');}, 500); event.preventDefault(); return false;" disabled="disabled" onchange="window.history.back();onChangeExamList();" placeholder="Select Exam" class="select-fees">
				<option disabled="disabled" selected="selected"></option>
			</select>
			<p class='clr'></p>
			<span style="display:none;" class='select_err' id="examSearchExams_error">Please select an exam</span>
		</li> -->
		<li>
			<input type="button" class="green-btn" value="SEARCH" id="submitButtonCollegeSearch" onclick="searchExamPage();event.stopPropagation();">
		</li>
	</ul>
	<p class='clr'></p>
</form>