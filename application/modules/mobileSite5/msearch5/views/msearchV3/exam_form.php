<?php 
		// $ExamPageLib             = $this->load->library('examPages/ExamPageLib');
	// $categoriesWithExamNames = $ExamPageLib->getCategoriesWithExamNames();
?>
<form formname="exams" onsubmit="return false;">
	<ul>
		<li>
			<div class="search-layer-field">
                <input type="text" placeholder="Find exams by name or course..." autocomplete="off" name="search" id="searchby-exam" class="search-clg-field">
                <a style="display:none;" class="clg-rmv keywordCross">&times;</a>
                
		        <!-- <div class="custom-searchbn" onclick="submitExamsSearch(); event.stopPropagation();">
		            Search
		            <input type="button" class="orange f18" value="" id="submit_exams" value="Search">
		        </div> -->
		    </div> 
		    <ul id="search-exam-layer" class="college-course-list" style="display: none;"></ul>
		    <span style="display: none;" class="select_err" id="searchby-exam_error">Please select the stream</span>
			<p class='clr'></p>
			<!-- <ul id="search-college-layer" class="college-course-list" style="display: none;">
			</ul>
			<span style="display:none;" class='select_err' id="collegeWidgetError"></span>
			<p class='clr'></p>
             <?php $this->load->view('msearch5/msearchV2/mSelectWidget', array('id' => 'dropDown4','data'=>$categories,'placeholder'=>"placeholder='Select Stream'",'firstDisabled'=>true,'onchange'=>"onchange=getExamPagesForCategory()")); ?> -->
        </li>
		<li>
			<input type="button" class="green-btn" value="SEARCH" id="submit_exams" onclick="submitExamSearchFromButton();event.stopPropagation();">
		</li>
	</ul>
	<p class='clr'></p>
</form>