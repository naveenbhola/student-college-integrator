<div class="widget-left">
	<?php 
		if(!empty($institutes['instituteData'])){
			foreach ($institutes['instituteData'] as $key => $instObj) {
				$this->load->view('nationalV2/tuple',array('instObj' => $instObj));
			}
		}

	 ?>
	<?php // $this->load->view('nationalV2/tuple'); ?>
	<?php //  $this->load->view('nationalV2/tuple'); ?>
	
	<?php $this->load->view('nationalV2/pagination'); ?>
	
	
</div>