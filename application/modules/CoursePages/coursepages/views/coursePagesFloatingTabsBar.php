<!--Removing sticky sub-navigation bar from all pages (LF-3300)-->
<div style="display:none">
	<div class="course-page-sticky-header">
		<?php $this->load->view('coursepages/coursePagesTabsBar', array('isFloatingBar' => TRUE,'prefix_to_append'=>'float')); ?>
	</div>
</div>
<script>var showCoursePagesFloatingBar = 1;</script>
