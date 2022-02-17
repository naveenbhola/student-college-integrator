     <?php $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_Slot1','bannerType'=>"content")); ?>
    <section class="predict-tabs">
                        <div class="expect align_cntr">
                              <p class="fnt_18 clr1 cat_marks rslt-iim f6">Following are the CAT <?php echo date('Y')-1;?> sectional &amp; overall cutoffs for all 20 IIMs</p>
                        </div>

                        <?php if(count($eligibility['eligibleList'])>0 && count($eligibility['nonEligibilityData'])>0):?>
                        <?php endif;?>
                        <div class="prf-tab-cont">
                            <!--profile page starts-->
                            <?php if(count($eligibility['eligibleList'])>0) :?>
                            <div class="prf-tabpane" id="tab_0" style="display: block;">
                                <div class="accordion">
                                    <?php if(count($scoreData['IIMWithData'])) :?> 
                                    <?php   $elegible_count=0; 
                                            $displayedTupleCount =0;
                                            $thirdTupleBanner = false;
                                            $sixthTupleBanner = false;
                                            foreach ($scoreData['IIMWithData'] as $key=>$elegibile_iim):
                                    ?>

                                    <div class="section">
                                        <div class="h-1">
                                                <a ga-attr="ILP_NAME_IIM_PERCENTILE" target='_blank' href="<?php echo $iim_icp_links[$key]['url'];?>" class="accr-a active1"><?php echo str_replace("_"," ",$key);?><br>
                                                </a>
                                                <?php 
                                                if(isset($_COOKIE['applied_'.$iim_icp_links[$key]['courseId']]) && $_COOKIE['applied_'.$iim_icp_links[$key]['courseId']] == 1){
                                                ?>
                                            <div>
                                                <a class="trans_btn accr_btn button button--orange courseBrochure disable-btn <?php echo $siteTourClass; ?>">Download Brochure
                                                </a>
                                                <p class="success-msg-listing"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
                                            </div>
                                            <?php
                                            }else{?>
                                            <div>    
                                                <a href="javascript:void(0);" <?=$trackingId;?> class="trans_btn accr_btn button button--orange courseBrochure brochureClick" courseId ="<?php echo $iim_icp_links[$key]['courseId'];?>" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>">Download Brochure
                                                </a>
                                                <p class="success-msg-listing hid"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
                                            </div>
                                            <?php }?> 
                                        </div> 
                                        <div id="<?php echo $elegible_count;?>elegible" class="sec-cont open initial-show">
                                            
                                            <section class="ac-sec">
                                                <?php if(count($elegibile_iim)>0):?>
                                                <ul class="ac-prgrs">
                                                    <?php foreach($elegibile_iim as $elegible_key => $elegible_value):
                                                            if($elegible_key == 'chances' || $elegible_key == 'score_calculated') {
                                                                continue;
                                                            }
                                                    ?>
                                                    <li>
                                                        <div class="aa1">
                                                            <label><?php echo str_replace("_Percentile","",$elegible_key);?></label>
                                                        </div>
                                                        <div class="aa2">
                                                            <div class="progress-sm">
                                                                <div class="lmt">
                                                                    <?php 
                                                                        if($userData[$elegible_key]) {
                                                                            $user_data_value = $userData[$elegible_key];
                                                                        } else {
                                                                            $user_data_value = $userData['cat_percentile'];  
                                                                        }
                                                                    ?>
                                                                    <?php if($userData['cat_percentile']>0):?>
                                                                    <div class="progress-bar progress-bar-primary" style="<?php if($user_data_value >= 4) echo 'width:'.($user_data_value-4).'% !important'; else echo 'width:0% !important'; ?>"></div>
                                                                    <span class="percntile1 <?php if(!empty($user_data_value) && $user_data_value < $elegible_value){ echo 'warningMsg'; } ?>"><?php echo round($user_data_value,2); ?><em class="em-lft">%ile</em></span>
                                                                    <?php endif;?>
                                                                </div>
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
                                                <span class='rd-also'> Also Read : </span> <a target="_blank" href="<?php echo $elegibilitylinks[$key];?>"><?php echo str_replace("_"," ",$key); ?> shortlisting criteria</a>
                                            </section>  
                                        </div>
                                    </div>
                                    <?php $elegible_count++;$displayedTupleCount++; 
                                    if($displayedTupleCount == 3 && !$thirdTupleBanner){
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA'));
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA1')); 
                                            $thirdTupleBanner = true;
                                        } 
                                    if($displayedTupleCount == 6 && !$sixthTupleBanner) {
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON'));
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON1')); 
                                            $sixthTupleBanner = true;
                                        }
                                        endforeach;?>
                                <?php endif;?> 
                                <?php if(count($scoreData['IIMWithOutData'])) :?> 
                                    <?php   $elegible_withoutdata_count=0;
                                            foreach ($scoreData['IIMWithOutData'] as $key=>$wd_elegibile_iim):
                                    ?>
                                    <div class="section">
                                        <div class="h-1">
                                            <a ga-attr="ILP_NAME_IIM_PERCENTILE" href="<?php echo $iim_icp_links[$key]['url'];?>" target='_blank' class="accr-a"><?php echo str_replace("_"," ",$key);?><br/>
                                            </a>
                                        <?php 
                                        if(isset($_COOKIE['applied_'.$iim_icp_links[$key]['courseId']]) && $_COOKIE['applied_'.$iim_icp_links[$key]['courseId']] == 1){
                                        ?>
                                        <div>
                                            <a class="button button--orange courseBrochure disable-btn <?php echo $siteTourClass; ?>">Download Brochure
                                            </a>
                                            <p class="success-msg-listing"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
                                        </div>
                                        <?php
                                        }else{?>
                                        <div>    
                                            <a href="javascript:void(0);" <?=$trackingId;?> class="trans_btn accr_btn button button--orange brochureClick" courseId ="<?php echo $iim_icp_links[$key]['courseId'];?>"  ga-track='' cta-type="<?php echo CTA_TYPE_EBROCHURE;?>">Download Brochure
                                            </a>
                                            <p class="success-msg-listing hid"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
                                        </div>
                                            <?php }?>    
                                        </div>
                                        <div id="<?php echo $elegible_withoutdata_count;?>wdelegible" class="sec-cont open initial-show">
                                            <section class="ac-sec">
                                                <?php if(count($wd_elegibile_iim)>0):?>
                                                <ul class="ac-prgrs">
                                                    <li>
                                                        <div class="aa1">
                                                            <label><?php echo str_replace("_Percentile","",$wd_elegible_key);?></label>
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
                                                <span class='rd-also'> Also Read : </span> <a target="_blank" href="<?php echo $elegibilitylinks[$key];?>"><?php echo str_replace("_"," ",$key); ?> shortlisting criteria</a>
                                            </section>
                                        </div>
                                    </div>
                                    <?php $elegible_withoutdata_count++;$displayedTupleCount++;
                                    if($displayedTupleCount == 3 && !$thirdTupleBanner) {
                                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA'));
                                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA1'));
                                        $thirdTupleBanner = true;
                                     } 
                                    if($displayedTupleCount == 6 && !$sixthTupleBanner){
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON'));
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON1')); 
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
                                <div class="prf-tabpane" id="tab_1" style="display: none;">
                                    <div class="accordion">
                                        <?php 
                                            $inelegible_count=0;
                                            foreach ($eligibility['nonEligibilityData'] as $key=>$inelegibile_iim):
                                        ?>  
                                                <div class="section">
                                                    <div class="h-1">
                                                        <a ga-attr="ILP_NAME_IIM_PERCENTILE"  href="<?php echo $iim_icp_links[$key]['url'];?>" class="accr-a active1"><?php echo str_replace("_"," ",$key); ?><br/>
                                                        </a>
                                                    <?php 
                                                    if(isset($_COOKIE['applied_'.$courseObj->getId()]) && $_COOKIE['applied_'.$courseObj->getId()] == 1){
                                                    ?>
                                                    <div>
                                                        <a class="trans_btn accr_btn button button--orange disable-btn <?php echo $siteTourClass; ?>">Download Brochure
                                                        </a>
                                                        <p class="success-msg-listing"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
                                                    <div>
                                                    <?php
                                                    }else{?>
                                                     <div>   
                                                        <a href="javascript:void(0);" <?=$trackingId;?> class="trans_btn accr_btn button button--orange brochureClick" courseId ="<?php echo $iim_icp_links[$key]['courseId'];?>"  ga-track='' cta-type="<?php echo CTA_TYPE_EBROCHURE;?>">Download Brochure
                                                        </a>
                                                        <p class="success-msg-listing hid"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
                                                    </div>
                                                    <?php }?>    

                                                    </div> 
                                                    <div id="<?php echo $inelegible_count;?>" class="sec-cont open initial-show">
                                                        <section class="ac-sec">
                                                            <div class="input-col">
                                                                <ul class="ul-input">
                                                                    <li><a href="#"><i class="blue-rund"></i><i class="z-l"></i>Your input</a></li>
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
                                                                        <div class="progress-sm">
                                                                            <div class="lmt">
                                                                                <div class="progress-bar progress-bar-primary" style="<?php if($userData[$Key] >= 4) echo 'width:'.($userData[$Key]-4).'% !important'; else echo 'width:0% !important'; ?>"></div>
                                                                                <span class="percntile1"><?php echo $userData[$Key];?><em class="em-lft"><?php echo printMarksType($Key); ?></em></span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="progress-sm">
                                                                            <div class="lmt">   
                                                                                <div class="progress-bar progress-bar-ornage" style="<?php if($Val >= 4) echo 'width:'.($Val-4).'% !important'; else echo 'width:0% !important'; ?>"></div>
                                                                                <span class="percntile"><?php echo $Val;?><em class="em-lft"><?php echo printMarksType($Key); ?></em></span>
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
                                                            <span class='rd-also'> Also Read : </span> <a href="<?php echo $elegibilitylinks[$key];?>" class=""><?php echo str_replace("_"," ",$key); ?> eligibility criteria</a>
                                                        </section>
                                                    </div>
                                                </div>
                                            <?php $inelegible_count++; 
                                              $displayedTupleCount++;
                                    if($displayedTupleCount == 3 && !$thirdTupleBanner) {
                                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA'));
                                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA1'));
                                            $thirdTupleBanner = true;
                                         } 
                                    if($displayedTupleCount == 6 && !$sixthTupleBanner){
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON'));
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON1')); 
                                            $sixthTupleBanner = true;
                                        }
                                        endforeach; ?>
                                    </div>
                                </div>
                            <?php endif;?>
                        
                            <div>
                                <p class="italic-txt">* %ile to aim for IIM Bangalore, IIM Lucknow and IIM Rohtak are not shown due to data unavailability. We are trying our best to get this for you. IIM Sirmaur did not conduct a GD-PI round last year.</p>
                                <p class="italic-txt">The eligLoading…ibility criteria considered here is the latest criteria shared by each IIM. For IIMs where data for current year is not available, data for last year has been used.</p>
                                <p class="italic-txt"><span>Disclaimer</span> - The results provided here are on the bases of past data for IIMs. The actual calls you get may differ from the ones shown above.</p>
                            </div>                                
                        
                        </div>
                    </section>
            <!--personal div ends--> 

<script type="text/javascript">
    isCompareEnable = true;
</script>