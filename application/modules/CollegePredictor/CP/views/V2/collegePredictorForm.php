<div  class="tab-form launch-rslt">
  <form id='search_form'>
      <?php $this->load->view('CP/V2/searchBoxTab4');?>  
  </form>

  <div class="rslt-tpl" id ='mainresultDiv'>
  	<?php 
  	$maxRoundNumbers = count($roundData);
  	if($maxRoundNumbers > 1) { ?>
	  	<div class="rslt-head">
	  	    <p>Institute &amp; Branch (Course) </p>
	  	    <table>
	  	        <tbody><tr>
	  	            <th>Rounds</th>
	  	            <th>Closing Rank</th>
	  	        </tr>
	  	    </tbody></table>
	  	</div>
	  	<?php } 
	  	$this->load->view('CP/V2/collegePredictorInner');
  	?>
  </div>
  <?php
    if(strtoupper($examName) == 'JEE-MAINS'){
     $this->load->view('mcollegepredictor5/V2/JeeShowInTable');
  }?>
  <div class="prd-toolDes">
    <p>Predictor tools work on the basis of past data. The output/results should be used purely for reference . Actual result of <?=date('Y');?> may vary. </p>
  </div>

</div>
