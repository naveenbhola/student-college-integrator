 <?php 
  
 if(!empty($RecommendedListingData)){?>
 <div  id= "inst-slider" class="ins-slider-cont clear-width photos-videos-sec other-details-wrap listingTuple"> 
    <div class="new-row">
       <div class="group-card bg-none gap clear">
         <h2 class="head-1"><?=$widgetHeading?></h2>
        
          <div class="col-md-12 shiksha-slider-cont <?php echo ($widgetType == 'similar') ? 'similarInstituteSlider' : 'alsoViewedInstituteSlider';?>" >
             <a id="navPhotoPrev" class="arrw-bx prv"><i class="arrows prev"></i></a>
             <div class="r-caraousal">
              <ul class="featuredSlider">
                <?php foreach($RecommendedListingData as $key=>$data){

                if($listing_type == 'course'){?>
                <li ga-attr="<?=$GA_Tap_On_Reco?>">
                  <div class="r-card r-card-course">
                        <a href="<?=$data['institute_url']?>" target="_blank">
                             <img class="lazy" data-original="<?=$data['image_url']?>" alt="<?=htmlentities($data['institute_name'])?>" />
                         </a>
                        <div class="new-card-dtls">
                        <a  href="<?=$data['institute_url']?>" class="para-4" target="_blank"><?php echo htmlentities(substr($data['institute_name'],0,70))?><?php if(strlen(htmlentities($data['institute_name']))>70){echo '...'; } ?></a>
                        <p class="para-7"><?php if(!empty($data['main_location'])){ echo $data['main_location'];}else{echo 'Andaman Nicobar Islands';}           
                                                if(!empty($data['establish_year']) && !empty($data['main_location'])){ echo ' | '.'Estd. '.$data['establish_year'];}else{
                                                  echo $data['establish_year'];
                                                }
                                          ?></p>
                       <!--  <p class="para-7">
                          <?php 
                                if($data['isNationalImportance'] == true){echo 'Institute of National Importance';}
                          ?>
                        </p> -->
                        <?php if($data['course_name']) {?>
                        <a href="<?=$data['course_url']?>" class="para-4 link" target="_blank">
                          <?php echo htmlentities(substr($data['course_name'],0,70))?><?php if(strlen(htmlentities($data['course_name']))>70){echo '...'; } ?>
                        </a>
                        <?php 
                        $buttonClass= 'button button--orange btn-pm brchre-btn';
                        $buttonClick= "ajaxDownloadEBrochure(this, ".$data['course_id'].",'".$listing_type."','".addslashes(htmlentities($data['course_name']))."','".$fromPage."','".$widgetTrackingKeyId."','".$dBrochureRecoLayer."','".$compareRecoLayer."','".$applyNowRecoLayer."','".$shortlistRecoLayer."')";
                        if(isset($_COOKIE['applied_'.$data['course_id']]) && $_COOKIE['applied_'.$data['course_id']]){
                            $buttonClass= 'button button--orange btn-pm brchre-btn disable-btn';
                            $buttonClick= "";
                        } ?>
                        <a class="<?=$buttonClass?>" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" onclick="<?=$buttonClick?>">Apply Now</a>
                        <?php } ?>
                      </div>
                  </div>
                </li>
                <?php }else{?>
                <li ga-attr="<?=$GA_Tap_On_Reco?>" <?php echo empty($GA_optlabel)?'':'ga-optlabel="'.$GA_optlabel.'"'; ?> >
                  <div class="r-card r-card-institute">
                         <a href="<?=$data['institute_url']?>" target="_blank">
                             <img class="lazy" data-original="<?=$data['image_url']?>" alt="<?=htmlentities($data['institute_name'])?>" />
                         </a>
                        <div class="new-card-dtls">
                            <a  href="<?=$data['institute_url']?>" class="para-4" target="_blank"><?php echo htmlentities(substr($data['institute_name'],0,70))?><?php if(strlen(htmlentities($data['institute_name']))>70){echo '...'; } ?></a>
                        
                        <p class="para-7"><?php if(!empty($data['main_location'])){ echo $data['main_location'];}else{echo 'Andaman Nicobar Islands';}           
                                                if(!empty($data['establish_year']) && !empty($data['main_location'])){ echo ' | '.'Estd. '.$data['establish_year'];}else{
                                                  echo $data['establish_year'];
                                                }
                                          ?></p>
                        <p class="para-7">&nbsp;</p>

                        <?php 
                        $buttonClass= 'btn-pm brchre-btn button button--orange';
                        $buttonClick= "ajaxDownloadEBrochure(this, ".$data['institute_id'].",'".$data["listingType"]."','".addslashes(htmlentities($data['institute_name']))."','".$fromPage."','".$widgetTrackingKeyId."','".$dBrochureRecoLayer."','".$compareRecoLayer."','".$applyNowRecoLayer."','".$shortlistRecoLayer."')";
                         ?>
                        <a class="<?=$buttonClass;?>" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" <?php echo empty($GA_deb_attr)?'':'ga-attr="'.$GA_deb_attr.'"'; ?> <?php echo empty($GA_optlabel)?'':'ga-optlabel="'.$GA_optlabel.'"'; ?> onclick="<?=$buttonClick?>">Apply Now</a>

                      </div>
                  </div>
                </li>          
                <?php }

                  }?>
              </ul>
             </div> 
           <a id="navPhotoNxt" class="arrw-bx nxt"><i class="arrows next"></i></a>
          </div>
          
        </div>    
       </div>
</div>
<?php } ?>
