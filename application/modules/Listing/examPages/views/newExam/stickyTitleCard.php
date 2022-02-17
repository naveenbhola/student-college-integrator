<div class="full__width" id="fixed-card">
  <div class="sticky__card exams__container">
    <div class="title__card clear__space" id="fixed-cta">
        <p class="f20__clr3__sb"><?php if($groupYear){$groupYear = ' '.$groupYear;}
        if($examFullName){ echo $examFullName.$groupYear.'&nbsp;( '.$examName.' )';}else{
        echo $examName.$groupYear;
        }?></p>
        <div class="btns__col Rft">

                <?php if(array_key_exists('samplepapers', $snippetUrl)){?>
                    <a class="blue__brdr__btn dwn-esmpr" data-trackingKey="<?php echo $trackingKeyList['download_sample_paper'];?>" title="Download previous question papers to read offline" ga-attr="DOWNLOAD_SAMPLE_PAPERS_BUTTON_STICKY">Get Question Papers</a>
                <?php }?>
                <a class="prime__btn mlt__5 dwn-eguide <?php if(isset($guideDownloaded) && $guideDownloaded){?> disable-btn <?php }?>" data-trackingKey="<?php echo $trackingKeyList['download_guide'];?>" title="Download exam information to read offline" ga-attr="DOWNLOAD_GUIDE_STICKY">Download Guide</a>
        </div>
     </div>
     <div class="nav__block ovrflw__hidden ps__rl fix__sticky" id="nav-section-sticky">
               <?php $this->load->view('examPages/newExam/sectionNav'); ?>
     </div>
  </div>
</div>