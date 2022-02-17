<?php 
  if($updates['totalUpdates']>1){
    $heading = "Updates";
  }else{
    $heading = "Update";
  }
?>
<section>
  <div class="data-card">
      <h2 class="color-3 f16 heading-gap font-w6"> Latest <?=$heading?> about <?=$examName?> </h2>
      <div class="card-cmn color-w f14">
          <ul class="cls-ul">
            <?php $this->load->view('mobile_examPages5/newExam/AMP/widgets/announcementsList',array('isLimit' => true));?>
          </ul>
        
   <?php if($updates['totalUpdates']>5){ ?>
      <div class="btn-sec">
        <a class="btn btn-secondary color-w color-b f14 font-w6 m-15top ga-analytic" data-vars-event-name="VIEW_ALL_UPDATES" on="tap:viewAllUpdates" role="button" tabindex="0">View All Updates</a>
      </div>
      <amp-lightbox class="" id="viewAllUpdates" layout="nodisplay" scrollable>
          <div class="lightbox">
              <div class="color-w full-layer">
                  <div class="f14 color-f bg-clr-b pad__110 font-w6">Latest <?=$heading?> about <?=$examName?><a class="cls-lightbox color-3 font-w6 t-cntr" on="tap:viewAllUpdates.close" role="button" tabindex="0">×</a>
                  </div>
                  <div class="col-prime pad10 margin-55">
                      <ul class="cls-ul ins-acc-ul ex-c-ul">
                          <?php $this->load->view('mobile_examPages5/newExam/AMP/widgets/announcementsList',array('isLimit' => false));?>
                      </ul>
                  </div>
              </div>
          </div>
      </amp-lightbox> 
     <?php } ?>
    </div>
  </div>

</section>
