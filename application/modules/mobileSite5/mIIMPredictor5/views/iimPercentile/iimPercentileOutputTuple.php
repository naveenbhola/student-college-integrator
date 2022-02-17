  <section class="predict-tabs">

    <?php $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => 'Slot1')); ?>
    <div class="cat align_cntr">
      <p class="rslt-iim f6">Following are the CAT <?php echo date('Y')-1;?> sectional &amp; overall cutoffs for all 20 IIMs</p>
    </div>
      <div class="prf-tab-cont">        
                 <!--profile page starts-->
                   <?php if(count($eligibility['eligibleList'])>0) :?>
                   <div class="prf-tabpane" id="tab_0">
                      <div class="accordion margin-brd">
                          
                               <!-- <h3 class="accordin-h">You can expect a call from:</h3> -->
                             <?php if(count($scoreData['IIMWithData'])) :?> 
                             <?php $elegible_count=0;
                             $displayedTupleCount = 0;
                             $thirdTupleBanner = false;
                             $sixthTupleBanner = false;
                             foreach ($scoreData['IIMWithData'] as $key=>$elegibile_iim):?> 

                            <div class="section">
                               <div class="h-1">
                                  <a ga-attr="ILP_NAME_IIM_PERCENTILE" ga-optlabel="IIM_PERCENTILE" href="<?php echo $iim_icp_links[$key]['url'];?>" class="accr-a active1">
                                  <?php echo str_replace("_"," ",$key);?><br/>
                                 </a>

                              <?php  $brochureClass = 'btn-iim-cat';
                                    $brochureText = 'Request Brochure';
                                    if(checkIfBrochureDownloaded($data['course_id'])) {
                                        $brochureClass = 'btn-iim-cat-dis';
                                        $brochureText = 'Brochure Mailed';
                                    }
                              ?>
                              <a class="<?=$brochureClass?> trans_btn accr_btn bgv1" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" ga-attr="RequestBrochure_IIM_PERCENTILE" ga-optlabel="IIM_PERCENTILE" onclick="downloadCourseBrochure('<?php echo $iim_icp_links[$key]['courseId'];?>','1969',{'pageType':'iim_percentile','callbackFunction':handleCourselistBrochure, 'callbackFunctionParams':{'thisObj': this}});"><?=$brochureText?></a>
                                 <!-- <a class="trans_btn accr_btn addToCmp bgv1" id='compare_<?php echo $iim_icp_links[$key]['courseId'];?>' href="javascript:void(0);" data-trackingKey="<?php echo $cmpTrackingKey;?>" data-listingId="<?php echo $iim_icp_links[$key]['courseId'];?>">Request Brochure</a>  -->

                               </div> 
                              <div id="<?php echo $elegible_count;?>elegible" class="sec-cont open initial-show">
                                <section class="ac-sec">
                            
                  <?php if(count($elegibile_iim)>0):?>
                                    <ul class="ac-prgrs">
                    <?php foreach($elegibile_iim as $elegible_key => $elegible_value):
                     if($elegible_key == 'chances' || $elegible_key == 'score_calculated') {
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
                               
                               <section class="accrdn-f">                
                                 <span class='rd-also'> Also Read : </span><a href="<?php echo $elegibilitylinks[$key];?>"><?php echo str_replace("_"," ",$key);?> shortlisting criteria</a>                                               
                               </section>
                                
                              </div>
                            </div>
                            <?php $elegible_count++;$displayedTupleCount++;
                              if($displayedTupleCount == 3 && !$thirdTupleBanner)
                              {
                                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                                $thirdTupleBanner = true;
                              }
                              if($displayedTupleCount == 6 && !$sixthTupleBanner)
                              {
                                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                                $sixthTupleBanner = true;
                              }
                          endforeach;?>
                            <?php endif;?> 
                            <?php if(count($scoreData['IIMWithOutData'])) :?> 
                             <?php $elegible_withoutdata_count=0;foreach ($scoreData['IIMWithOutData'] as $key=>$wd_elegibile_iim):?>
                             <?php  $brochureClass = 'btn-iim-cat';
                                    $brochureText = 'Request Brochure';
                                    if(checkIfBrochureDownloaded($data['course_id'])) {
                                        $brochureClass = 'btn-iim-cat-dis';
                                        $brochureText = 'Brochure Mailed';
                                    }
                              ?>
                            <div class="section">
                               <div class="h-1">
                                  <a ga-attr="ILP_NAME_IIM_PERCENTILE" ga-optlabel="IIM_PERCENTILE" href="<?php echo $iim_icp_links[$key]['url'];?>" class="accr-a"><?php echo str_replace("_"," ",$key);?><br/>
                                   <a class="<?=$brochureClass?> trans_btn accr_btn bgv1" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" ga-attr="RequestBrochure_IIM_PERCENTILE" ga-optlabel="IIM_PERCENTILE" onclick="downloadCourseBrochure('<?php echo $iim_icp_links[$key]['courseId'];?>','1969',{'pageType':'iim_percentile','callbackFunction':handleCourselistBrochure, 'callbackFunctionParams':{'thisObj': this}});"><?=$brochureText?></a>
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
                                 <span class='rd-also'> Also Read : </span> <a href="<?php echo $elegibilitylinks[$key];?>"><?php echo str_replace("_"," ",$key);?> shortlisting criteria</a>
                               </section>
                                
                              </div>
                            </div>
                            <?php $elegible_withoutdata_count++;$displayedTupleCount++;
                            if($displayedTupleCount == 3 && !$thirdTupleBanner)
                            {
                              $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                              $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                              $thirdTupleBanner = true;
                            }
                            if($displayedTupleCount == 6 && !$sixthTupleBanner)
                            {
                              $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                              $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                              $sixthTupleBanner = true;
                            }
                            endforeach;?>
                            <?php endif;?> 
                            
                          </div>
                   </div>
                  <!--eligible page ends-->
                  <?php endif;?> 

                  <?php if(count($eligibility['nonEligibilityData'])>0):?>
                    

                            <!--ineligiblet-->
                   <div class="prf-tabpane" id="tab_1">
                      <div class="accordion">
                           
                         <!-- <h3 class="accordin-h">You are not eligible for the following IIMs:</h3> -->
                               <?php 
                               $inelegible_count=0;
                               // _p($eligibility['nonEligibilityData']); die;
                               foreach ($eligibility['nonEligibilityData'] as $key=>$inelegibile_iim):?>

                              <div class="section">

                                 <div class="h-1">
                                    <a  href="<?php echo $iim_icp_links[$key]['url'];?>" class="accr-a active1">
                                    <?php echo str_replace("_"," ",$key); ?><br/>
                                    <span class="inst-eligblty">Eligibility: <em>No</em></span>
                                    </a>
                                     <a class="trans_btn accr_btn addToCmp" id='compare_<?php echo $iim_icp_links[$key]['courseId'];?>' href="javascript:void(0);" data-trackingKey="<?php echo $cmpTrackingKey;?>" data-listingId="<?php echo $iim_icp_links[$key]['courseId'];?>">Compare</a>
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
                                           
                                       <section class="accrdn-f">
                                         <a href="<?php echo $elegibilitylinks[$key];?>" class=""><?php echo str_replace("_"," ",$key); ?> eligibility criteria</a>
                                       </section>




                                  </div>


                              </div>   

                             <?php 
                                $inelegible_count++;
                                $displayedTupleCount++;
                                if($displayedTupleCount == 3 && !$thirdTupleBanner)
                                {
                                  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                                  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                                  $thirdTupleBanner = true;
                                }
                                if($displayedTupleCount == 6 && !$sixthTupleBanner)
                                {
                                  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                                  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                                  $sixthTupleBanner = true;
                                } 
                                 endforeach;
                                 ?>

                          </div>
                   </div>







                  <?php endif;?>
                  <div class="slide-box">
                     </div>

                    <div class="slide-box">
                      <?php if(!empty($userData['cat_percentile'])){ ?>
                     <a href="<?=SHIKSHA_HOME?>/mba/resources/iim-call-predictor?type=repeat&modify=yes#catScoreStep">
                       <article class="start-again">

                        <p>Want to change CAT score?</p>

                        <span class="start-again-link">Change here</span>

                       </article>
                       </a>
                     <?php } ?> 

                     <p class="clr"></p>

					</div>
                  <div class="college-widget">
                                <div class="college-banner">
                                  <?php 
                                  if(in_array($bannerTier, array(1,2,3))){
                                      $bannerProperties = array('pageId'=>'IIMCP', 'pageZone'=>'TIER'.$bannerTier.'_MOBILE');
                                      echo '<p class="bnr-hd">Featured College</p>';
                                      $this->load->view('common/banner', $bannerProperties);
                                  }
                                  ?>
                                </div>
                                <div class="college-ranking">
                                    <div class="-head">
                                        <span>
                                            <img align="left" src='<?php echo SHIKSHA_HOME."/public/images/rankings_mobile.png"; ?>' />
                                            View <strong>Top MBA</strong> Colleges in India
                                        </span>
                                    </div>
                                    <div class="-foot">
                                        <a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Ranking_Widget');" href='<?php echo SHIKSHA_HOME."/mba/ranking/top-mba-colleges-india/2-2-0-0-0"; ?>'>MBA Rankings</a>
                                    </div>
                                </div>
                                <div class="college-locations">
                                    <div class="-head">
                                        <span>
                                            <img src='<?php echo SHIKSHA_HOME."/public/images/college-by-location_mobile.png"; ?>' />
                                            View MBA Colleges by <strong>Location</strong>
                                        </span>
                                    </div>
                                    <div class="-body">
                                        <ul>
                                            <li><a  onclick="trackEventByGAMobile('ICP_Mobile_Category_Bangalore_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-bangalore"; ?>'>Bangalore</a></li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Delhi_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-delhi"; ?>'>Delhi</a></li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Chennai_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-chennai"; ?>'>Chennai</a></li>

                                            <li><a  onclick="trackEventByGAMobile('ICP_Mobile_Category_Delhi_NCR_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-delhi-ncr"; ?>'>Delhi/NCR</a></li>
                                            <li><a  onclick="trackEventByGAMobile('ICP_Mobile_Category_Hyderabad_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-hyderabad"; ?>'>Hyderabad</a></li>
                                            <li><a  onclick="trackEventByGAMobile('ICP_Mobile_Category_Kolkata_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-kolkata"; ?>'>Kolkata</a></li>
                                            <li><a  onclick="trackEventByGAMobile('ICP_Mobile_Category_Mumbai_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-mumbai"; ?>'>Mumbai</a></li>
                                            <li><a  onclick="trackEventByGAMobile('ICP_Mobile_Category_Mumbai)All_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-mumbai-all"; ?>'>Mumbai (All)</a></li>
                                            <li><a  onclick="trackEventByGAMobile('ICP_Mobile_Category_Pune_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-pune"; ?>'>Pune</a> </li>
                                            <li><a  onclick="trackEventByGAMobile('ICP_Mobile_Category_Karnataka_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-karnataka"; ?>'>Karnataka</a></li>
                                            <li><a  onclick="trackEventByGAMobile('ICP_Mobile_Category_Uttar_Pradesh_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-uttar-pradesh"; ?>'>Uttar Pradesh</a></li>
                                            <li><a  onclick="trackEventByGAMobile('ICP_Mobile_Category_India_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-india"; ?>'>All India</a></li>
                                        </ul>                           
                                    </div>
                                    <!-- <div class="-foot">
                                        <a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_India_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-in-india"; ?>'>More Locations</a>
                                    </div> -->
                                </div>
                            </div>
                            
                   <div>
                      <p class="italic-txt">* %ile to aim for IIM Bangalore, IIM Lucknow and IIM Rohtak are not shown due to data unavailability. We are trying our best to get this for you. IIM Sirmaur did not conduct a GD-PI round last year.</p>
                      <p class="italic-txt">The eligibility criteria considered here is the latest criteria shared by each IIM. For IIMs where data for current year is not available, data for last year has been used.</p>
                      <p class="italic-txt"><span>Disclaimer</span> - The results provided here are on the bases of past data for IIMs. The actual calls you get may differ from the ones shown above.</p>
                   </div>
                                  
                </div>
  </section> 

</body>
</html>
