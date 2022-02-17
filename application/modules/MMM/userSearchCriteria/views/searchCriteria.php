<div id="mmm-cont" style="margin:0 auto;">
	<h2>New Userset</h2>
	<div class="flRt"><input type="button" value=" " class="gCancelBtn" onclick="doCancelNewUserset();" style="cursor:pointer" /></div>
	<div class="clearFix spacer10"></div>
	<div class="userset-tab">
		<ul>
			<li><a id="userset_profile_link" class="active" href="#" onclick="addNewUserset('profile_india');">Profile-based targeting</a></li>
			<li><a id="userset_activity_link" href="#" onclick="addNewUserset('activity');">Activity-based targeting</a></li>
			<li><a id="userset_exam_link" href="#" onclick="addNewUserset('exam');">Exam-based targeting</a></li>
		</ul>
	</div>

	<div class="clearFix"></div>

	<div class="tab-details" id="userSetTabDetails" style="padding-bottom:10px;">
	<?php
		if ($userset_type == "profile_india") {
			$this->load->view('userSearchCriteria/searchIndia');
		}
		if ($userset_type == "activity") {
			$this->load->view('userSearchCriteria/searchActivity');
		}
		if ($userset_type == "exam") {
			$this->load->view('userSearchCriteria/searchExam');
		}
	?>
	</div>
</div>