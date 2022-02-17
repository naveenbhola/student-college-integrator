<?php 
	switch($type){
		case 'registration':
		case 'clickedRecom':		
			$this->load->view("systemMailer/Career/recommendationMailer");
			break;
	}
?>
