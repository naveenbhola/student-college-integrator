
<?php if($action==='schr_apply'){?>
<div class="cmn-popup">
        <div class="cmn-det">
            <strong>Redirecting to external website</strong>
            <div class="dv-ht">
                <p>Taking you to scholarship website for taking further action</p>
            </div>
            <div class="btn-cntueSec">
	   	  	  <a href="<?php echo $scholarshipObj->getApplicationData()->getApplyNowLink();?>" class="btns btn-prime btn-width btn-pad">Continue</a>
	   	  	  <a href = "javascript:void(0)" class="btns btn-cncl btn-width csl">Cancel</a>
	   	  	</div>
        </div>
    </div>
<?php } else if($action=='schr_db'){?>
<div class="layer-header">
    <a class="back-box csl" href="javascript:void(0)" ><i class="sprite back-icn"></i></a>
    <p id="registrationLayerHeaderText">Brochure Mailed</p>
</div>
    <section class="content-wrap clearfix">
      <div class="bg_div">
          <p class="snt_mail"> <i class="sprite"></i> Brochure sent to   <span><?php echo htmlentities($email);?></span></p>
          <p>Go back to <a href="<?php echo SHIKSHA_STUDYABROAD_HOME.$refr?>"><?php echo htmlentities($title);?></a></p>
      </div>
    </section>



<?php } ?>
