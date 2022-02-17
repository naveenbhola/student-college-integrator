<?php

if(!empty($RecommendedListingData)){?>
<div class="crs-widget listingTuple" style="overflow:hidden;width:100%">
        <h2 class="head-L2 rc-titl"><?php echo $widgetHeading; ?></h2>
        <div class="rec-clg-list <?php echo ($widgetType == 'similar' ? 'similar-slider' : 'recommendation-slider'); ?>">
            <ul>
                <?php foreach($RecommendedListingData as $key=>$data){?>
                <?php if($listing_type == 'course'){ ?>
                  <li ga-attr="<?=$GA_Tap_On_Reco;?>">
                    <div class="recommend">
                      <a class="rco-clgFig" href="<?=$data['institute_url']?>">
                         <img class="lazy" data-original="<?=$data['image_url']?>" alt="<?=htmlentities($data['institute_name'])?>" />
                      </a>
                      <div class="rco-clgInfo">
                          <a href="<?=$data['institute_url']?>" class="head-L3 inst-name"><?=substr(htmlentities($data['institute_name']),0,60)?><?php if(strlen(htmlentities($data['institute_name']))>60){echo '...'; } ?></a>
                          <p class="para-L3">
                          <?php if(!empty($data['establish_year']) || !empty($data['main_location'])){?>
                            <?php if(!empty($data['main_location'])){ echo $data['main_location'];}           
                                              if(!empty($data['establish_year']) && !empty($data['main_location'])){ echo ' | '.'Estd. '.$data['establish_year'];}else{
                                                echo $data['establish_year'];
                                              }
                                        ?>
                          <?php } ?>
                          </p>
                          
                          <?php if($data['course_name']) {?>
                          <a href="<?=$data['course_url']?>" class="head-L3">
                            <?php echo htmlentities(substr($data['course_name'],0,60))?><?php if(strlen(htmlentities($data['course_name']))>60){echo '...'; } ?>
                          </a>
                          <?php } 
                                $brochureClass = 'button--orange';
                                $brochureText = 'Request Brochure';
                                if(checkIfBrochureDownloaded($data['course_id'])) {
                                    $brochureClass = 'btn-mob-dis';
                                    $brochureText = 'Brochure Mailed';
                                }
                          ?>
                          <a class="button <?=$brochureClass?> ui-link reco-req-brchr" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" <?php echo empty($GA_deb_attr)?'':'ga-attr="'.$GA_deb_attr.'"'; ?> <?php echo empty($GA_optlabel)?'':'ga-optlabel="'.$GA_optlabel.'"'; ?> onclick="downloadCourseBrochure('<?=$data['course_id']?>','<?=$widgetTrackingKeyId?>',{'pageType':'coursePage','callbackFunction':handleCourselistBrochure, 'callbackFunctionParams':{'thisObj': this}});"><?=$brochureText?></a>
                      </div>
                    </div>
                  </li>
                  <?php } else { ?>
                  <li ga-attr="<?=$GA_Tap_On_Reco;?>" <?php echo empty($GA_optlabel)?'':'ga-optlabel="'.$GA_optlabel.'"'; ?> >
                    <a class="recommend"  href="<?=$data['institute_url']?>">
                    <div class="rco-clgFig">
                       <img class="lazy" data-original="<?=$data['image_url']?>" alt="<?=htmlentities($data['institute_name'])?>" />
                    </div>
                    <div class="rco-clgInfo">
                        <p class="head-L3"><?=substr(htmlentities($data['institute_name']),0,60)?><?php if(strlen(htmlentities($data['institute_name']))>60){echo '...'; } ?></p>
                        <p class="para-L3">
                        <?php if(!empty($data['establish_year']) || !empty($data['main_location'])){?>
                          <?php if(!empty($data['main_location'])){ echo $data['main_location'];}           
                                if(!empty($data['establish_year']) && !empty($data['main_location'])){ echo ' | '.'Estd. '.$data['establish_year'];}else{
                                  echo $data['establish_year'];
                                }?>
                        <?php } ?>
                        </p>
                         <button class="button button--orange ui-link reco-req-brchr" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" <?php echo empty($GA_deb_attr)?'':'ga-attr="'.$GA_deb_attr.'"'; ?> <?php echo empty($GA_optlabel)?'':'ga-optlabel="'.$GA_optlabel.'"'; ?> onclick="downloadCourseBrochure('<?php echo $data['institute_id']; ?>','<?php echo $widgetTrackingKeyId; ?>',{'pageType':'<?php echo $pageType; ?>','listing_type':'<?php echo $data['listingType']; ?>','callbackFunctionParams':{'pageType':'<?php echo $pageType; ?>','thisObj': this}});">Request Brochure</button>
                    </div>
                  </a>
                </li>
                  <?php } ?>
                </li>
                <?php } ?>
            </ul>
        </div>
</div>
<?php } ?>
