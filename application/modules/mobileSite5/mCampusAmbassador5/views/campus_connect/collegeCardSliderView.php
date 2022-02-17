<div class="college-Widget">

<div class="slide-prev" onclick="trackEventByGAMobile('CARD_SCROLLED_CCHOME_MOBILE_<?php echo $widgetType?>');"><i class="msprite prv-icn"></i></div>

 <ul class="slides">
  <?php
    $index = 0;
    if(!empty($result)){
      foreach($result as $instId => $instituteDetails){
  ?>
<li class="lazyLoadCC">
  <div class="slide-main-wrap" style="margin:20px 0px; padding:0 15px; -moz-box-sizing:border-box; -webkit-box-sizing:border-box; box-sizing:border-box;">
  <!-- <div class="slide-prev" onclick="trackEventByGAMobile('CARD_SCROLLED_CCHOME_MOBILE_<?php echo $widgetType?>');"><i class="msprite prv-icn"></i></div> -->
    <div class="slider-wrap" style="border:0 none;">
      <ul>
        <li class="clearfix">
          <div class="figure-box" style="height:170px; overflow: hidden">
            <?php if($index < 2){?>
              <img class="imageFinder" src="<?php echo SHIKSHA_HOME.'/public/images/college_default_img.jpg';?>" data-src="<?php if(isset($instituteDetails['imgUrl']) && $instituteDetails['imgUrl'] != ''){ echo $instituteDetails['imgUrl']; }else{ echo SHIKSHA_HOME.'/public/images/college_default_img.jpg'; } ?>" title="<?php echo $instituteDetails['name']; ?>" width="248" height="170" alt="<?php echo $instituteDetails['name']; ?>">
          <?php  }else { ?>
              <img class="imageFinder" src="<?php echo SHIKSHA_HOME.'/public/images/college_default_img.jpg';?>" data-src="<?php if(isset($instituteDetails['imgUrl']) && $instituteDetails['imgUrl'] != ''){ echo $instituteDetails['imgUrl']; }else{ echo SHIKSHA_HOME.'/public/images/college_default_img.jpg'; } ?>" title="<?php echo $instituteDetails['name']; ?>" width="248" height="170" alt="<?php echo $instituteDetails['name']; ?>">
          <?php }  $index++;?>
          </div>
          <div class="college-detail">
            <div class="clearfix">
              <p class="clg-titl"><strong style="font-size:12px;"><?php if(strlen($instituteDetails['name'])>50){?><a href="<?php echo $instituteDetails['instUrl'];?>"><?php echo substr($instituteDetails['name'],0,47).'...';?></a><?php }else{?><a href="<?php echo $instituteDetails['instUrl'];?>"><?php echo $instituteDetails['name'];?></a><?php } ?></strong>, <?php if(isset($instituteDetails['locality']) && $instituteDetails['locality'] != ''){ echo $instituteDetails['locality'].''.',' ;} ?> <?php echo $instituteDetails['cityName']; ?></p>

              <p class="clg-rank font-11"><strong>Rank: </strong>
                            <?php if(count($instituteDetails['rank']) > 0) { 
                                    $addingSeparator = false;
                                    $isNARequired = false;
                                    foreach ($instituteDetails['rank'] as $source => $rank) { 
                                        if($rank){
                                            $isNARequired = true;
                                            if($addingSeparator == false){
                                                $addingSeparator = true;
                                            }else{
                                                echo ',';
                                            }
                                        ?>
                                        <strong><?php echo $rank?></strong> <span>(<?php echo $source;?>)</span>
                                <?php   }else{
                                        $isNARequired = false;
                                    }

                                }
                                if($isNARequired == false && !$addingSeparator){
                                    echo "<span>NA</span>";
                                }
                                }else{  echo "<span>NA</span>";}

                                $Id = $instId;
                                $type = 'all_content_pages';
                                $optionalArgs['typeOfListing'] = $instituteDetails['listingType']; 
                                $optionalArgs['typeOfPage'] = 'questions';
                                $url = getSeoUrl($Id,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');


                                ?>
              </p>


              <p class="clg-count font-11"><a href="<?php echo $url;?>"><strong><?php echo $instituteDetails['CA_Count']; ?> Current Students</strong></a> to answer your questions</p>
              <a style="height:14px; overflow: hidden" class="font-11" href="<?php echo $url;?>"><strong>View Existing Questions</strong></a>
            </div>
            <div style="text-align: center; width:100%; margin-top:26px;">
              <a href="<?php echo $url;?>" class="ask-que-btn" onclick="setCookieForIntermediatePage('<?php echo $questionTrackingPageKeyId;?>')">Ask your question</a>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <!-- <div class="slide-next" onclick="trackEventByGAMobile('CARD_SCROLLED_CCHOME_MOBILE_<?php echo $widgetType?>');"><i class="msprite nxt-icn"></i></div> -->
    </div>
</li>
  
<?php  } ?>
<?php   } else {?>
  <div style="text-align:center;padding:4px;">
  <?php echo "No ".ucfirst($widgetType)." Colleges Found";?>
  </div>
<?php } ?>
</ul>
<div class="slide-next" onclick="trackEventByGAMobile('CARD_SCROLLED_CCHOME_MOBILE_<?php echo $widgetType?>');"><i class="msprite nxt-icn"></i></div>
</div>
<?php if($widgetType == 'topRanked') {?>
      <script>
          topRankedInstitutes = '<?php echo json_encode($dataForTopQuestion); ?>';
      </script>
<?php } ?>