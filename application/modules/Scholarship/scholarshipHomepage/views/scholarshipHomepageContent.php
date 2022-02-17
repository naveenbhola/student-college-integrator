<div class="home__container">
<?php 
  $this->load->view('scholarshipHomepage/sections/searchScholarships');
  if(count($scholarshipStatistics)>0){
  	$this->load->view('scholarshipHomepage/sections/countryScholarships');
  }
  //$this->load->view('scholarshipHomepage/sections/studentGuidesForScholarships');
	if(count($popularContent)>0){ // we only check one because the difference lies only in sorting
		$this->load->view('scholarshipHomepage/sections/popularScholarships');
		$this->load->view('scholarshipHomepage/sections/articlesOnScholarships');
	}
?>
</div>