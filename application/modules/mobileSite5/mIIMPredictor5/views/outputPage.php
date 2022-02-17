<?php $userData = $predictorData['userData'];?>
<?php $eligibility = $predictorData['scoreData']['eligibility'];?>
<?php $ineligibility = $predictorData['scoreData']['inEligibility'];
$bannerPosition = 10;
?>

<?php if(!isset($isAjaxCall) || !$isAjaxCall){ ?>
  <div class="prf-tab-cont">
<?php } ?>
                  
                 <!--profile page starts-->
                   <?php if(count($eligibility)>0) :?>
                   <?php if(!isset($isAjaxCall) || !$isAjaxCall){ ?> 
                     <div class="prf-tabpane" id="tab_0">
                        <div class="accordion" id="iim_output">
                  <?php } ?>
                               <!-- <h3 class="accordin-h">You can expect a call from:</h3> -->
                             <?php if(count($eligibility)) :?> 
                             <?php $elegible_count=0;
                             $displayedTupleCount = 0;
                             $thirdTupleBanner = false;
                             $sixthTupleBanner = false;
                             foreach ($eligibility as $key=>$elegibile_iim):
                                $courseId = $elegibile_iim['courseId'];
                                  $instituteId = $elegibile_iim['instituteId'];
                                  $product  = "iimCallPredictor";
                                  $shortlistTrackingId = 1999;
                                  if(is_array($shortlistedCoursesOfUser) && in_array($courseId, $shortlistedCoursesOfUser)){
                                      $shortlistText  = "Shortlisted";
                                  }else{
                                      $shortlistText  = "Shortlist";
                                  }
                                  
                                  if(in_array($courseId, $applied_courses)){
                                      $ebText = 'Brochure Mailed';
                                      $ebClassName = 'btn-mob-dis';
                                  }else{
                                      $ebText = 'Apply Now';
                                      $ebClassName = 'tupleBrochureButton';
                                  }
                              ?> 

                            <div class="section">
                              <div class="mobile-iim-col">
        <a href="<?php echo $elegibile_iim['instituteUrl'];?>" class="accr-a active1">
                                  <?php echo $elegibile_iim['instituteName'];?></a>
        <div class="iim-rating rating-inline-widget">
	     <ul>
		<?php  if($elegibile_iim['reviewData']['totalCount']>0){ ?>
               <li>
                 <?php $this->load->view("mobile_listing5/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $elegibile_iim['allReviewsUrl'], 'aggregateReviewsData' => array('aggregateRating'=>$elegibile_iim['reviewData']['aggregateRating']), 'reviewsCount' => $elegibile_iim['reviewData']['totalCount'], 'forPredictor' => 1));?>
	      </li>
		<?php } ?>
              <?php
              if($elegibile_iim['courseExtraInfo']['fees']!=''){
                                                            if($elegibile_iim['reviewData']['totalCount']>0){
                                                                echo "<li><span> | </span></li>";
                                                            }
                                                            echo "<li> &#x20b9; ".$elegibile_iim['courseExtraInfo']['fees']."</li>";
                                                        }
                                                        if($elegibile_iim['courseExtraInfo']['extraInfo']['duration']!=''){
                                                            if($elegibile_iim['reviewData']['totalCount']>0 || $elegibile_iim['courseExtraInfo']['fees']!=''){
                                                                echo "<li><span> | </span></li>";
                                                            }
                                                            echo "<li>".$elegibile_iim['courseExtraInfo']['extraInfo']['duration']."</li>";
                                                        }
                                                        if($elegibile_iim['courseExtraInfo']['extraInfo']['educationType']!=''){
                                                            if($elegibile_iim['reviewData']['totalCount']>0 || $elegibile_iim['courseExtraInfo']['extraInfo']['duration']!='' || $elegibile_iim['courseExtraInfo']['fees'] !=''){
                                                                echo "<li><span> | </span></li>";
                                                            }
                                                            echo "<li>".$elegibile_iim['courseExtraInfo']['extraInfo']['educationType']."<li/>";
                                                        }
              ?>
          </ul>
         <!--  <div class="iim-ranktxt">Rank 1 for <a>MBA</a> <b>by NIRF 2018</b> <a>+4more</a></div> -->
        </div>
        <div class="iim-btngroup">
          <div class="new-tab">
                <div class="srp-sub-col">
            <a href="javascript:void(0);" product="iimCallPredictor" track="off" id="shrt_<?php echo $courseId;?>" class="trans_btn tupleShortlistButton shrt_<?php echo $courseId;?>" data-pagetype="iimCallPredictor" attr-val="<?php echo $courseId;?>" data-instid="<?php echo $instituteId;?>" data-trackid="1999"><?php echo $shortlistText;?></a>
                </div>
                <div class="srp-sub-col">
            <a id="brchr_<?php echo $courseId;?>" product="iimCallPredictor" track="on" data-instid="<?php echo $instituteId;?>" class="predict brchr_<?php echo $courseId;?> <?php echo $ebClassName;?>" attr-val="<?php echo $courseId;?>" data-trackid="1997" data-pagetype="iimCallPredictor" href="javascript:void(0);" gatext="IIM Download Brochure" cta-type="download_brochure"><?php echo $ebText;?></a>

                </div>
            </div>
        </div>

        <div class="iim-check">
          <span class="inst-eligblty">Eligibility :<em class="yes">Yes</em></span>
            <span class="inst-eligblty">
               <?php if(empty($userData['cat_percentile'])){ ?>
                                                            Cut-off: <em class="c-blue"><?php echo round($elegibile_iim['Total_Percentile'], 2); ?></em>  <?php }else{ ?>
                                                                Chances of Call: <em class="<?php echo strtolower($elegibile_iim['chances'])?>">
                                                                    <?php echo $elegibile_iim['chances'];?></em>
                                                                <?php } ?>
            </span>
        </div>
  </div>
  <div id="<?php echo $elegible_count;?>elegible" class="sec-cont open initial-show">
                    <ul class="ul-input">
                                     <?php if($userData['cat_percentile']>0):?><li><a href="#"><i class="blue-rund"></i><i class="z-l"></i>Your input</a></li><?php endif;?>
                                     <li><a href="#"><i class="orng-rund"></i><?php echo (date('Y')-2);?> Cut-off</a></li>
                                       <p class="clr"></p> 
                                  </ul>
                                <section class="ac-sec">
                            
                  <?php if(count($elegibile_iim)>0):?>
                                    <ul class="ac-prgrs">
                    <?php foreach($elegibile_iim as $elegible_key => $elegible_value):
                     if(!in_array($elegible_key, array('VRC_Percentile','DILR_Percentile','QA_Percentile','Total_Percentile'))) {
                                                                continue;
                                                            }
                     //echo $elegible_key."__".$elegible_value;
                    ?>
                                      <li>
                                        <div class="aa1">
                                          <label><?php echo str_replace("_Percentile","",$elegible_key);?></label>
                                        </div>
                                        <div class="aa2">
                                          <div class="progress-sm">
                        <?php 
                        if($userData[$elegible_key]) {
                        $user_data_value = $userData[$elegible_key];
                        } else {
                        $user_data_value = $userData['cat_percentile'];  
                        }
                        ?>
                         <?php if($userData['cat_percentile']>0):?>
                                               <div class="lmt">
                                                 <div class="progress-bar progress-bar-primary" style="<?php if($user_data_value >= 4) echo 'width:'.($user_data_value-4).'% !important'; else echo 'width:0% !important'; ?>"></div>
                                                <span class="percntile1 <?php if(!empty($user_data_value) && $user_data_value < $elegible_value){ echo 'warningMsg'; } ?>"><?php echo round($user_data_value,2); ?><em class="em-lft">%ile</em></span>
                                               </div>
                                               <?php endif;?>
                                          </div>
                                           <div class="progress-sm">
                                             <div class="lmt">
                                               <div class="progress-bar progress-bar-ornage" style="<?php if($elegible_value >= 4) echo 'width:'.($elegible_value-4).'% !important'; else echo 'width:0% !important'; ?>"></div>
                                               <span class="percntile"><?php echo round($elegible_value, 2); ?><em class="em-lft">%ile</em></span>
                                            </div>
                                          </div>
                                        </div>                                                                                    
                                        <p class="clr"></p>  
                                      </li> 
                                      <?php endforeach;?>
                                    </ul>
                                    <?php endif;?>
                                    <p class="clr"></p>
                                </section>
                               <?php if(!empty($elegibile_iim['articleLabel']) && !empty($elegibile_iim['articleUrl'])){ ?>
                               <section class="accrdn-f read-iims">                
                                Also read: <a href="<?php echo $elegibile_iim['articleUrl'];?>"><?php echo $elegibile_iim['articleLabel']; ?></a>                                               
                               </section>
                             <?php } ?>


                                
                              </div>
                            </div>
                            <?php $elegible_count++;$displayedTupleCount++;
                            
                              if($pageNum == 1 && ($displayedTupleCount == $bannerPosition || (count($eligibility)<$bannerPosition && count($eligibility) == $displayedTupleCount)))
                              {
                                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                              }
                              if($pageNum == 2 && ($displayedTupleCount == $bannerPosition || (count($eligibility)<$bannerPosition && count($eligibility) == $displayedTupleCount)))
                              {
                                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON'));
                                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON1'));
                              }
                              if($pageNum == 3 && ($displayedTupleCount == $bannerPosition || (count($eligibility)<$bannerPosition && count($eligibility) == $displayedTupleCount)))
                              {
                                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                              }
                          endforeach;?>
                            <?php endif;?> 
                            <?php if(count($scoreData['IIMWithOutData'])) :?> 
                             <?php $elegible_withoutdata_count=0;foreach ($scoreData['IIMWithOutData'] as $key=>$wd_elegibile_iim):?>
                            <div class="section">
                               <div class="h-1">
                                  <a href="<?php echo $iim_icp_links[$key]['url'];?>" class="accr-a"><?php echo str_replace("_"," ",$key);?><br/>
                                  <span class="inst-eligblty">Eligibility: <em class="yes">Yes</em></span>
                                 </a>
                               </div> 
                              <div id="<?php echo $elegible_withoutdata_count;?>wdelegible" class="sec-cont open initial-show">								    
                                <section class="ac-sec1">
									<?php if(count($wd_elegibile_iim)>0):?>
                                    <ul class="ac-prgrs">										
                                      <li>
                                        <div class="aa1">
                                          <label><?php echo str_replace("_Percentile","",$wd_elegible_key);?></label>
                                        </div>
                                        <div class="data-img">
                                              <img src="/public/mobile5/images/dataNotAvail.png" />
                                        </div>
 
                                        <div class="data-txt">
                                             <?php 
                                          if($userData['cat_percentile']>0) {
                                              echo "<p>We cannot predict your chances for this IIM, as the data required to generate it is currently not available with us. We will let you know once this is available. </p>";
                                            } else {
                                                echo "<p>We cannot predict the percentile you should aim to get a GD-PI call from this IIM, as the data required to generate it is currently not available with us. We will let you know once this is available.</p>";   
                                            } 
                                          ?>
                                        </div>                                                                                  
                                        <p class="clr"></p>  
                                      </li>                                       
                                    </ul>
                                    <?php endif;?>
                                    <p class="clr"></p>
                                </section>
                               
                               <section class="accrdn-f">
                                 <a href="<?php echo $elegibilitylinks[$key];?>"><?php echo str_replace("_"," ",$key);?></a>
                               </section>
                                
                              </div>
                            </div>
                            <?php $elegible_withoutdata_count++;
                            endforeach;?>
                            <?php endif;?> 
                    <?php if(!isset($isAjaxCall) || !$isAjaxCall){ ?>     
                          </div>
                      </div>
                   <?php } ?>
                  <!--eligible page ends-->
                  <?php endif;?> 

                  <?php if($predictorData['inEligibilityCount']>0):?>
                    

                            <!--ineligiblet-->
                <?php if(!isset($isAjaxCall) || !$isAjaxCall){ ?>
                   <div class="prf-tabpane" id="tab_1">
                      <div class="accordion" id="<?php echo $predictorData['eligibilityCount'] == 0 ? 'iim_output' : '';?>">
                <?php } ?>
                           
                         <!-- <h3 class="accordin-h">You are not eligible for the following IIMs:</h3> -->
                               <?php 
                               $inelegible_count=0;
                               foreach ($ineligibility as $key=>$inelegibile_iim):

                                  $courseId = $inelegibile_iim['courseId'];
                                  $instituteId = $inelegibile_iim['instituteId'];
                                  $product  = "iimCallPredictor";
                                  $shortlistTrackingId = 1999;
                                  if(is_array($shortlistedCoursesOfUser) && in_array($courseId, $shortlistedCoursesOfUser)){
                                      $shortlistText  = "Shortlisted";
                                  }else{
                                      $shortlistText  = "Shortlist";
                                  }
                                  
                                  if(in_array($courseId, $applied_courses)){
                                      $ebText = 'Brochure Mailed';
                                      $ebClassName = 'btn-mob-dis';
                                  }else{
                                      $ebText = 'Apply Now';
                                      $ebClassName = 'tupleBrochureButton';
                                  }

                                ?>

                              <div class="section">
                                <div class="mobile-iim-col">
        <a href="<?php echo $inelegibile_iim['instituteUrl'];?>" class="accr-a active1">
                                  <?php echo $inelegibile_iim['instituteName'];?></a>
        <div class="iim-rating rating-inline-widget">
	     <ul>
		<?php  if($inelegibile_iim['reviewData']['totalCount']>0){ ?>
               <li>
                 <?php $this->load->view("mobile_listing5/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $inelegibile_iim['allReviewsUrl'], 'aggregateReviewsData' => array('aggregateRating'=>$inelegibile_iim['reviewData']['aggregateRating']), 'reviewsCount' => $inelegibile_iim['reviewData']['totalCount'], 'forPredictor' => 1));?>
	      </li>
		<?php } ?>
              <?php
              if($inelegibile_iim['courseExtraInfo']['fees']!=''){
                                                            if($inelegibile_iim['reviewData']['totalCount']>0){
                                                                echo "<li><span> | </span></li>";
                                                            }
                                                            echo "<li> &#x20b9; ".$inelegibile_iim['courseExtraInfo']['fees']."</li>";
                                                        }
                                                        if($inelegibile_iim['courseExtraInfo']['extraInfo']['duration']!=''){
                                                            if($inelegibile_iim['reviewData']['totalCount']>0 || $inelegibile_iim['courseExtraInfo']['fees']!=''){
                                                                echo "<li><span> | </span></li>";
                                                            }
                                                            echo "<li>".$inelegibile_iim['courseExtraInfo']['extraInfo']['duration']."</li>";
                                                        }
                                                        if($inelegibile_iim['courseExtraInfo']['extraInfo']['educationType']!=''){
                                                            if($inelegibile_iim['reviewData']['totalCount']>0 || $inelegibile_iim['courseExtraInfo']['extraInfo']['duration']!='' || $inelegibile_iim['courseExtraInfo']['fees'] !=''){
                                                                echo "<li><span> | </span></li>";
                                                            }
                                                            echo "<li>".$inelegibile_iim['courseExtraInfo']['extraInfo']['educationType']."<li/>";
                                                        }
              ?>
          </ul>
         <!--  <div class="iim-ranktxt">Rank 1 for <a>MBA</a> <b>by NIRF 2018</b> <a>+4more</a></div> -->
        </div>
        <div class="iim-btngroup">
          <div class="new-tab">
                <div class="srp-sub-col">
            <a href="javascript:void(0);" product="iimCallPredictor" track="off" id="shrt_<?php echo $courseId;?>" class="trans_btn tupleShortlistButton shrt_<?php echo $courseId;?>" data-pagetype="iimCallPredictor" attr-val="<?php echo $courseId;?>" data-instid="<?php echo $instituteId;?>" data-trackid="1999"><?php echo $shortlistText;?></a>
                </div>
                <div class="srp-sub-col">
            <a id="brchr_<?php echo $courseId;?>" product="iimCallPredictor" track="on" data-instid="<?php echo $instituteId;?>" class="predict brchr_<?php echo $courseId;?> <?php echo $ebClassName;?>" attr-val="<?php echo $courseId;?>" data-trackid="1997" data-pagetype="iimCallPredictor" href="javascript:void(0);" gatext="IIM Download Brochure" cta-type="download_brochure"><?php echo $ebText;?></a>

                </div>
            </div>
        </div>

        <div class="iim-check">
          <span class="inst-eligblty">Eligibility : <em class="no">No</em></span>
        </div>
  </div>


                                  <div id="<?php echo $inelegible_count;?>" class="sec-cont open initial-show">
                                        

                                        <section class="ac-sec">

                                        <div class="input-col">
                                          <ul class="ul-input">
                                            <?php //if($userData['cat_percentile'] > 0):?>
                                               <li><a href="#"><i class="blue-rund"></i><i class="z-l"></i>Your input</a></li>
                                             <?php //endif;?>
                                               <li><a href="#"><i class="orng-rund"></i>Minimum score required</a></li>
                                               <p class="clr"></p> 
                                          </ul>

                                        </div>
                                            <?php if(count($inelegibile_iim)>0):?>
                                        
                                            <ul class="ac-prgrs">
                                                <?php foreach($inelegibile_iim as $Key=>$Val):?> 
                                                 <?php
                                                                    if(!in_array($Key, array('VRC_Percentile','DILR_Percentile','QA_Percentile','Total_Percentile','X_XII_avg','xthPercentage','xiithPercentage','graduationPercentage'))) {
                                                                        continue;
                                                                    }
                                                                    ?> 
                                                    <li>
                                                     
                                                      <div class="aa1">
                                                        <label><?php echo $fieldsmapping[$Key]; ?></label>
                                                      </div>

                                                      <div class="aa2">
                                                        <?php //if($userData['cat_percentile']>0):?>  
                                                        <div class="progress-sm">
                                                            <div class="lmt">
                                                             <div class="progress-bar progress-bar-primary" style="<?php if($userData[$Key] >= 4) echo 'width:'.($userData[$Key]-4).'% !important'; else echo 'width:0% !important'; ?>"></div>
                                                             <span class="percntile1"><?php echo $userData[$Key];?><em class="em-lft"><?php echo printMarksType($Key); ?></em></span>
                                                         </div>
                                                        </div>
                                                        <?php //endif;?>

                                                         <div class="progress-sm">
                                                          <div class="lmt">
                                                             <div class="progress-bar progress-bar-ornage" style="<?php if($Val >= 4) echo 'width:'.($Val-4).'% !important'; else echo 'width:0% !important'; ?>"></div>
                                                             <span class="percntile" ><?php echo $Val;?><em class="em-lft"><?php echo printMarksType($Key); ?></em></span>
                                                        </div>
                                                        </div>
                                                      </div>
                                                        
                                                      <p class="clr"></p>  
                                                    </li>

                                              <?php endforeach;?>
                                            </ul>
                                            <?php endif;?> 
                                            
                                            <p class="clr"></p>
                                        </section>
                                        <?php if(!empty($inelegibile_iim['articleLabel']) && !empty($inelegibile_iim['articleUrl'])){ ?>
                                       <section class="accrdn-f read-iims">
                                         Also read:<a href="<?php echo $inelegibile_iim['articleUrl'];?>" class=""><?php echo $inelegibile_iim['articleLabel']; ?></a>
                                       </section>
                                       <?php } ?>




                                  </div>


                              </div>   

                             <?php 
                                $inelegible_count++;
                                $displayedTupleCount++;
                                
                                if($pageNum == 1 && ($displayedTupleCount == $bannerPosition || (count($ineligibility)<$bannerPosition && count($ineligibility) == $displayedTupleCount)))
                                {
                                  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                                  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                                }
                                if($pageNum == 2 && ($displayedTupleCount == $bannerPosition || (count($ineligibility)<$bannerPosition && count($ineligibility) == $displayedTupleCount)))
                                {
                                  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON'));
                                  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON1'));
                                }
                                if($pageNum == 3 && ($displayedTupleCount == $bannerPosition || (count($ineligibility)<$bannerPosition && count($ineligibility) == $displayedTupleCount)))
                                {
                                  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                                  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                                }

                                 endforeach;
                                 ?>
                      <?php if(!isset($isAjaxCall) || !$isAjaxCall){ ?>
                          </div>
                      </div>
                    <?php } ?>

                  <?php endif;?>
      
