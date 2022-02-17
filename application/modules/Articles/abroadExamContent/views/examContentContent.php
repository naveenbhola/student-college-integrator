<div class="examBreadcrumb clearfix">
<?php 
$this->load->view('studyAbroadCommon/breadCrumbs');
?>
</div>
<div class="content-wrap clearfix">
	<?php $this->load->view('widget/examContentTopNavigation'); ?>
	<div class="exam-content-div clearfix">
	<?php
		$this->load->view('examContentLeftColumn');
		$this->load->view('examContentRightColumn');
	?>
	</div>
</div>