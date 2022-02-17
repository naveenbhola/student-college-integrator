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
                            <div class="prf-tabpane" id="tab_0" style="display: block;">
                                <div class="accordion" id="iim_output">
                            <?php } ?>
                                     <?php if(count($eligibility)) :?> 
                                    <?php   $elegible_count=0; 
                                            $displayedTupleCount =0;
                                            $thirdTupleBanner = false;
                                            $sixthTupleBanner = false;
                                            foreach ($eligibility as $key=>$elegibile_iim):
                                                $courseId = $elegibile_iim['courseId'];
                                                $product  = "iimCallPredictor";
                                                $shortlistTrackingId = 1995;
                                                if(is_array($shortlistedCoursesOfUser) && in_array($courseId, $shortlistedCoursesOfUser)){
                                                    $shortlistFn    = "removeShortlistFromSearch(".$courseId.",".$shortlistTrackingId.",'".$product."')";
                                                    $shortlistText  = "Shortlisted";
                                                }else{
                                                    $shortlistFn    = "addShortlistFromSearch(".$courseId.",".$shortlistTrackingId.",'".$product."')";
                                                    $shortlistText    = "Shortlist";
                                                }
                                                
                                                if($_COOKIE['applied_'.$courseId] == 1){
                                                    $ebText = 'Brochure Mailed';
                                                    $ebClassName = 'ebDisabled';
                                                }else{
                                                    $ebText = 'Apply Now';
                                                    $ebClassName = 'iimEbutton';
                                                }
                                    ?>

                                    <div class="section">
                                        <div class="predictore-head">
                                            <div class="table">
                                                <div class="table-cell left-cell">
                                                    <a href="<?php echo $elegibile_iim['instituteUrl'];?>" >  <?php echo $elegibile_iim['instituteName'];?>
                                                    </a>
                                                    <div class="rating-inline-widget">
                                                        <?php $this->load->view("listingCommon/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $elegibile_iim['allReviewsUrl'], 'aggregateReviewsData' => array('aggregateRating'=>$elegibile_iim['reviewData']['aggregateRating']), 'reviewsCount' => $elegibile_iim['reviewData']['totalCount'], 'forPredictor' => 1));?>
                                                        <?php
                                                        if($elegibile_iim['courseExtraInfo']['fees']!=''){
                                                            if($elegibile_iim['reviewData']['totalCount']>0){
                                                                echo "<b> | </b>";
                                                            }
                                                            echo "&#x20b9;".$elegibile_iim['courseExtraInfo']['fees'];
                                                        }
                                                        if($elegibile_iim['courseExtraInfo']['extraInfo']['duration']!=''){
                                                            if($elegibile_iim['reviewData']['totalCount']>0 || $elegibile_iim['courseExtraInfo']['fees']!=''){
                                                                echo "<b> | </b>";
                                                            }
                                                            echo $elegibile_iim['courseExtraInfo']['extraInfo']['duration'];
                                                        }
                                                        if($elegibile_iim['courseExtraInfo']['extraInfo']['educationType']!=''){
                                                            if($elegibile_iim['reviewData']['totalCount']>0 || $elegibile_iim['courseExtraInfo']['extraInfo']['duration']!='' || $elegibile_iim['courseExtraInfo']['fees'] !=''){
                                                                echo "<b> | </b>";
                                                            }
                                                            echo $elegibile_iim['courseExtraInfo']['extraInfo']['educationType'];
                                                        }
                                                        ?>     
                                                    </div> 
                                                    <div class="cta-widget">
                                                       <button onclick=<?=$shortlistFn?> class="button button--secondary" instid="<?php echo $elegibile_iim['instituteId']?>" id="shrt<?php echo $courseId?>" product="iimCallPredictor" track="on" courseid="<?php echo $elegibile_iim['courseId']?>" ga-attr="IIM_Shortlist" product="<?php echo $product; ?>"><span><?php echo $shortlistText;?></span></button>

                                                       <button cta-type="download_brochure"  class="predict button button--orange <?php echo $ebClassName;?>" id="eb_<?php echo $elegibile_iim['courseId']?>" ga-attr="IIM_EB"><span instid="<?php echo $elegibile_iim['instituteId']?>" product="iimCallPredictor" track="on" courseid="<?php echo $elegibile_iim['courseId']?>" courseName="<?php echo $elegibile_iim['courseName'];?>"><?php echo $ebText;?></span></button>
                                                    </div>
                                                </div>   
                                                <div class="table-cell right-cell t-right  v-middle">
                                                    <span class="inst-eligblty">
                                                        Eligibility: <em class="yes">Yes</em>
                                                    </span>
                                                    <span class="inst-eligblty">
                                                        <?php if(empty($userData['cat_percentile'])){ ?>
                                                            Cut-off: <em class="c-blue"><?php echo round($elegibile_iim['Total_Percentile'], 2); ?></em>  <?php }else{ ?>
                                                                Chances of Call: <em class="<?php echo strtolower($elegibile_iim['chances'])?>">
                                                                    <?php echo $elegibile_iim['chances'];?></em>
                                                                <?php } ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div> 
                                        <div id="<?php echo $elegible_count;?>elegible" class="sec-cont open initial-show">
                                            <div class="input-col">
                                            <ul class="ul-input">
                                            <?php if($userData['cat_percentile']>0):?>
                                                <li><a href="#"><i class="blue-rund"></i><i class="z-l"></i>Your input</a></li>
                                            <?php endif;?>
                                                <li><a href="#"><i class="orng-rund"></i><?php echo (date('Y')-2);?> Cut-off</a></li>
                                                    <p class="clr"></p> 
                                            </ul>
                                            </div>
                                            <section class="ac-sec">
                                                <?php if(count($elegibile_iim)>0):?>
                                                <ul class="ac-prgrs">
                                                    <?php foreach($elegibile_iim as $elegible_key => $elegible_value):  

                                                            if(!in_array($elegible_key, array('VRC_Percentile','DILR_Percentile','QA_Percentile','Total_Percentile'))) {
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
                                                            <?php //_p($elegible_value);die;?>
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
                                            <section class="accrdn-f">
                                                Also read:<a target="_blank" href="<?php echo $elegibile_iim['articleUrl'];?>"><?php echo $elegibile_iim['articleLabel']; ?></a>
					    </section>  
					   <?php } ?>
                                        </div>
                                    </div>
                                    <?php $elegible_count++;$displayedTupleCount++; 
                                    if($pageNum == 1 && ($displayedTupleCount == $bannerPosition || (count($eligibility)<$bannerPosition && count($eligibility) == $displayedTupleCount))){
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA'));
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA1')); 
                                            $thirdTupleBanner = true;
                                        } 
                                    if($pageNum == 2 && ($displayedTupleCount == $bannerPosition || (count($eligibility)<$bannerPosition && count($eligibility) == $displayedTupleCount))) {
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON'));
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON1')); 
                                            $sixthTupleBanner = true;
                                        }
                                    if($pageNum == 3 && ($displayedTupleCount == $bannerPosition || (count($eligibility)<$bannerPosition && count($eligibility) == $displayedTupleCount))){
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA'));
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA1')); 
                                            $thirdTupleBanner = true;
                                        }
                                        endforeach;?>
                                <?php endif;?> 

                                <?php if(count($ineligibility)) :?> 
                                    <?php   $elegible_withoutdata_count=0;
                                            foreach ($ineligibility as $key=>$wd_elegibile_iim):
                                                //if(!in_array($wd_elegibile_iim[$key], array('VRC_Percentile','DILR_Percentile','QA_Percentile','Total_Percentile'))) {
                                                  //  echo $key;
                                                 //   continue;
                                                //}
                                              //  _p($key);
                                    ?>
                                    <div class="section">
                                        <div class="h-1">
                                            <a href="<?php echo $wd_elegibile_iim['instituteUrl'];?>" class="accr-a"><?php echo $wd_elegibile_iim['instituteName'];?><br/>
                                                <span class="inst-eligblty">Eligibility <em>Yes</em></span>
                                            </a>

                                        </div>
                                        <div id="<?php echo $elegible_withoutdata_count;?>wdelegible" class="sec-cont open initial-show">
                                            <section class="ac-sec">
                                                <?php if(count($wd_elegibile_iim)>0):?>
                                                <ul class="ac-prgrs">
                                                    <li>
                                                        <div class="aa1">
                                                            <label><?php echo str_replace("_Percentile","",$wd_elegible_key);?></label>
                                                        </div>
                                                        <div class="data-img">
                                                            <img src='<?php echo SHIKSHA_HOME."/public/images/ICP_dataNotAvail.png"; ?>' />
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
                                            <?php if(!empty($wd_elegibile_iim['articleLabel']) && !empty($wd_elegibile_iim['articleUrl'])){ ?>
                                            <section class="accrdn-f">
                                                Also read:<a target="_blank" href="<?php echo $wd_elegibile_iim['articleUrl'];?>"><?php echo $wd_elegibile_iim['articleLabel']; ?></a>
                                            </section>
                                        <?php } ?>
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
                                <div class="prf-tabpane" id="tab_1" style="display: block;">
                                    <div class="accordion" id="<?php echo $predictorData['eligibilityCount'] == 0 ? 'iim_output' : ''?>">
                            <?php } ?>
                                        <?php 
                                            $inelegible_count=0;
                                            foreach ($ineligibility as $key=>$inelegibile_iim):

                                                $courseId = $inelegibile_iim['courseId'];
                                                $product  = "iimCallPredictor";
                                                $shortlistTrackingId = 1995;
                                                if(is_array($shortlistedCoursesOfUser) && in_array($courseId, $shortlistedCoursesOfUser)){
                                                    $shortlistFn    = "removeShortlistFromSearch(".$courseId.",".$shortlistTrackingId.",'".$product."')";
                                                    $shortlistText  = "Shortlisted";
                                                }else{
                                                    $shortlistFn    = "addShortlistFromSearch(".$courseId.",".$shortlistTrackingId.",'".$product."')";
                                                    $shortlistText    = "Shortlist";
                                                }
                                                
                                                if($_COOKIE['applied_'.$courseId] == 1){
                                                    $ebText = 'Brochure Mailed';
                                                    $ebClassName = 'ebDisabled';
                                                }else{
                                                    $ebText = 'Apply Now';
                                                    $ebClassName = 'iimEbutton';
                                                }

                                        ?>  
                                                <div class="section">
                                                    <div class="predictore-head">
                                                        <div class="table">
                                                            <div class="table-cell left-cell">
                                                               <a href="<?php echo $inelegibile_iim['instituteUrl'];?>" ><?php echo $inelegibile_iim['instituteName'];?><br/>
                                                                    </a>
                                                                <div class="rating-inline-widget">
                                                                    <?php $this->load->view("listingCommon/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $inelegibile_iim['allReviewsUrl'], 'aggregateReviewsData' => array('aggregateRating'=>$inelegibile_iim['reviewData']['aggregateRating']), 'reviewsCount' => $inelegibile_iim['reviewData']['totalCount'], 'forPredictor' => 1));?>
                                                                    <?php
                                                                    if($inelegibile_iim['courseExtraInfo']['fees']!=''){
                                                                        if($inelegibile_iim['reviewData']['totalCount']>0){
                                                                            echo "<b> | </b>";    
                                                                        }
                                                                        echo "&#x20b9;".$inelegibile_iim['courseExtraInfo']['fees'];
                                                                    }
                                                                    if($inelegibile_iim['courseExtraInfo']['extraInfo']['duration']!=''){
                                                                        if($inelegibile_iim['reviewData']['totalCount']>0 || $inelegibile_iim['courseExtraInfo']['fees']!=''){
                                                                            echo "<b> | </b>";    
                                                                        }
                                                                        echo $inelegibile_iim['courseExtraInfo']['extraInfo']['duration'];
                                                                    }
                                                                    if($inelegibile_iim['courseExtraInfo']['extraInfo']['educationType']!=''){
                                                                        if($inelegibile_iim['reviewData']['totalCount']>0 || $inelegibile_iim['courseExtraInfo']['extraInfo']['duration']!='' || $inelegibile_iim['courseExtraInfo']['fees'] !=''){
                                                                            echo "<b> | </b>";    
                                                                        }
                                                                        echo $inelegibile_iim['courseExtraInfo']['extraInfo']['educationType'];
                                                                    }
                                                                    ?>     
                                                                </div> 

                                                    <div class="cta-widget">
                                                       <button onclick=<?=$shortlistFn?> class="button button--secondary" instid="<?php echo $inelegibile_iim['instituteId']?>" id="shrt<?php echo $courseId?>" product="iimCallPredictor" track="on" courseid="<?php echo $inelegibile_iim['courseId']?>" ga-attr="IIM_Shortlist" product="<?php echo $product; ?>"><span><?php echo $shortlistText;?></span></button>

                                                       <button cta-type="download_brochure"  class="predict button button--orange <?php echo $ebClassName;?>" id="eb_<?php echo $inelegibile_iim['courseId']?>" ga-attr="IIM_EB"><span instid="<?php echo $inelegibile_iim['instituteId']?>" product="iimCallPredictor" track="on" courseid="<?php echo $inelegibile_iim['courseId']?>" courseName="<?php echo $inelegibile_iim['courseName'];?>"><?php echo $ebText;?></span></button>
                                                    </div>

                                                            </div>   
                                                        </div>
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
                                                            <?php if($predictorData['inEligibilityCount']>0):?>
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
                                                        <?php if(!empty($inelegibile_iim['articleLabel']) && !empty($inelegibile_iim['articleUrl'])){ ?>
                                                        <section class="accrdn-f">
                                                            Also read:<a target="_blank" href="<?php echo $inelegibile_iim['articleUrl'];?>" class=""><?php echo $inelegibile_iim['articleLabel']; ?></a>
                                                        </section>
                                                    <?php } ?>
                                                    </div>
                                                </div>
                                            <?php $inelegible_count++; 
                                              $displayedTupleCount++;
                                    if($pageNum == 1 && ($displayedTupleCount == $bannerPosition || (count($ineligibility)<$bannerPosition && count($ineligibility) == $displayedTupleCount))) {
                                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA'));
                                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA1'));
                                            $thirdTupleBanner = true;
                                         } 
                                    if($pageNum == 2 && ($displayedTupleCount == $bannerPosition || (count($ineligibility)<$bannerPosition && count($ineligibility) == $displayedTupleCount))){
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON'));
                                            $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON1')); 
                                            $sixthTupleBanner = true;
                                        }
                                    if($pageNum == 3 && ($displayedTupleCount == $bannerPosition || (count($ineligibility)<$bannerPosition && count($ineligibility) == $displayedTupleCount))) {
                                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA'));
                                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA1'));
                                            $thirdTupleBanner = true;
                                         }
                                        endforeach; ?>
                            <?php if(!isset($isAjaxCall) || !$isAjaxCall){ ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php endif;?>
                        <?php if(!isset($isAjaxCall) || !$isAjaxCall){ ?>
                            <div id="loadingNew" style="text-align: center; margin-top: 10px;display:none;">
                                <img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" class="small-loader">
                            </div>

                            <div class="slide-box">
                                <a href="<?php echo SHIKSHA_HOME.'/mba/resources/iim-call-predictor?type=repeat#iimpredictorInputStep1';?>">
                                    <article class="start-again <?php if(empty($userData['cat_percentile'])){ echo 'full-width'; } ?>">
                                        <p>Want to change your Input? </p>
                                        <span class="start-again-link">Start Again</span>
                                        <?php if(!empty($userData['cat_percentile'])){ ?>
                                        <i class="vertical-line"></i>
                                        <?php } ?>
                                    </article>
                                </a>
                                <?php if(!empty($userData['cat_percentile'])){ ?>
                                <a href="<?php echo SHIKSHA_HOME.'/mba/resources/iim-call-predictor?type=repeat&modify=yes#catScoreStep';?>">
                                    <article class="start-again">
                                        <p> Want to change CAT Score? </p>
                                        <span class="start-again-link">Change Here</span>
                                    </article>
                                </a>    
                                <?php } ?>
                                <p class="clr"></p>
                            </div>
                        
                            <div>
                                <!-- <p class="italic-txt">* %ile to aim for IIM Bangalore, IIM Lucknow and IIM Rohtak are not shown due to data unavailability. We are trying our best to get this for you. IIM Sirmaur did not conduct a GD-PI round last year.</p> -->
                                <p class="italic-txt">The eligibility criteria considered here is the latest criteria shared by each IIM. For IIMs where data for current year is not available, data for last year has been used.</p>
                                <p class="italic-txt"><span>Disclaimer</span> - The results provided here are on the bases of past data for IIMs. The actual calls you get may differ from the ones shown above.</p>
                            </div>                                
                        </div>
                <?php } ?>
