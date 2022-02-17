<div class="apply_col">
 <div id="statsLoader"><img src="<?php echo IMGURL_SECURE; ?>/public/images/loader_hpg.gif" alt="Loading" /></div>
 <section class="page_width test_div" id="statsDiv">
   <h2 class="fnt_20">Result driven admission counseling for universities abroad
    <p>
      <span id="noOfApplications"><?php echo $admissionApplicationData['noOfApplications'];?></span> applications done across <span id="noOfUniversities"><?php echo $admissionApplicationData['noOfUniversities'];?></span> universities in <span id="noOfCountries"><?php echo $admissionApplicationData['noOfCountries'];?></span> countries
    </p>
  </h2>
  <p class="apl-cptn">Last application was done for <span id="latestApplicationStr"><?php echo $admissionApplicationData['latestApplicationStr'];?></span></p>
  <?php $this->load->view('/applyHome/widgets/profileEvaluationCTA',array('profileEvalCTALocation'=>'middle')); ?>
 </section>
</div>