<?php
if(count($successVideoArray)>0){
?>
<!-- Counseler video story Section -->
<div class="apl-widgt vid-sec apl-box">
  <p class="apl-hed">What Students have to<br/> Say About Us</p>
<?php
    $counter = 0;
    foreach($successVideoArray as $key=>$details){$counter++;
?>
  <div id="nikhil_prof" class="story-dv">
      <div class="click_play" videoid="<?php echo $details['videoId']; ?>" counter="<?php echo $counter; ?>">
        <strong class="play_back anim-box"> <i></i></strong>
        <img class="lazy" alt="<?php echo $details['name']; ?>" title="<?php echo $details['name']; ?>" data-src="<?php echo IMGURL_SECURE ?>/public/mobileSA/images/applyPage/<?php echo $details['image']; ?>.jpg"/>
      </div>
      <div id="youtube-<?=$counter?>" class="youtubeContainer" style="display:none"></div>
      <p class="st-titl"><?php echo $details['name']; ?> <span><?php echo $details['exam'][0]; ?></span></p>
      <p>Admitted to <?php echo $details['univName']; ?></p>
      <a href="<?php echo $details['articleURL'];?>" class="cmplSt-link">Read My Success Story</a>
  </div>
<?php } ?>
</div>
<!-- Counseler video story Section -->
<?php } ?>
