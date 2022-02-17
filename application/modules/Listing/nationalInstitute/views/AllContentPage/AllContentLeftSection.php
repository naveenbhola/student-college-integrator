
<?php
	switch ($pageType) {
	    case 'articles':
	        $this->load->view("nationalInstitute/AllContentPage/articleDetailsSection");
	        break;

	    case 'questions':
	        $this->load->view("nationalInstitute/AllContentPage/anaDetailsSection");
	        break;

	    case 'reviews': ?>
	    	<div class="col-md-8 no-padLft left-widget">
	    
	        <?php $this->load->view("nationalInstitute/AllContentPage/reviewDetailsSection"); ?>
	        
	        </div>
<?php	break;

	    case 'admission':
	        $this->load->view("nationalInstitute/AllContentPage/admissionDetailsSection");
	        break;
	    case 'scholarships':
	    	$this->load->view("nationalInstitute/AllContentPage/scholarshipDetailsSection");
	    	break;
	     default:
	        # code...
	        break;
	 }     
?>