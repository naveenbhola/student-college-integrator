<?php 
  if($updates['totalUpdates']>1){
    $heading = "Updates";
  }else{
    $heading = "Update";
  }
?>
 <section>
      <div class="data-card">
          <h2 class="color-3 f16 heading-gap font-w6"> Latest <?=$heading?> about <?=$examName?></h2>
          <div class="lcard color-w f14" id="updates-list">        
              <?php $this->load->view('mobile_examPages5/newExam/widgets/announcementList');?>
              <?php if($updates['totalUpdates']>5){?>
              <div class="btn-sec">
                  <a class="btn btn-secondary color-w color-b f14 font-w6 m-15top m-5btm" href="javascript:void(0);" id="view-announcement" ga-attr="VIEW_ALL_UPDATES">View All Updates</a>
                </div>
              <?php } ?>
          </div>
      </div>
      <!--
      <div class="d-annmt card-cmn f14 subscribe__div">
        <strong class="font-w6">Get Updates in your email.</strong>
        <div class="btn-sec">
              <a class="btn btn-secondary color-w color-b f14 font-w6 m-15top sb-btn <?php echo $isSubscribe? 'btn-mob-dis' : '';?>" href="javascript:void(0);" id="subscribe" data-tracking="<?=$trackingKeys['subscribe_to_latest_updates'];?>" ga-attr="SUBSCRIBE"><?php echo $isSubscribe ? 'Subscribed' : 'Subscribe';?></a>
        </div>
    </div>
    -->
</section>
