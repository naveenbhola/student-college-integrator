<div class="widget margin-top-0">
	<?php
	if(!empty($filters['subCat'])) {
		$this->load->view('nationalV2/nocoursecontext_categoryfilters');
	}
	?>
    <?php $this->load->view('nationalV2/location_filter'); ?>
</div>