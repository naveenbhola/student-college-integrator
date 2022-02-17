<?php $userData = $predictorData['userData'];?>
<?php $eligibility = $predictorData['scoreData']['eligibility'];?>
<?php $ineligibility = $predictorData['scoreData']['inEligibility'];
?>
  <section class="predict-tabs">
    <div class="cat align_cntr">
            <?php if(empty($userData['cat_percentile']) && $predictorData['eligibilityCount'] == 0) { ?>
                  <p class="fnt20 clr1 mb10">You are not Eligible for any of the colleges. Eligibility are mentioned below.</p>
                  <!-- <a class="predict new-btn" id="enter-cat-score" ga-attr="ENTER_CAT_SCORE">Enter CAT score</a> -->
            <?php } else if(empty($userData['cat_percentile'])) {?>                  
                  <p class="fnt20 clr1 mb10">Cut-off scores & eligibility are mentioned below. But to predict your IIM and Non IIM calls please fill your CAT score (actual or expected)</p>
                  <a class="predict new-btn" id="enter-cat-score" ga-attr="ENTER_CAT_SCORE">Enter CAT score</a>
            <?php }elseif($predictorData['eligibilityCount'] == 0)
            { ?>
                <p class="fnt20 clr1 mb10">You are not Eligible for any of the colleges. Eligibility are mentioned below.</p>
            <?php }
            else { ?>
                <p class="fnt20 clr1 mb10">You are eligible to get calls from <strong><?=$predictorData['eligibilityCount'];?> reputed MBA Colleges</strong> in India. To know your chances (High or Low) of getting a call from these colleges, see results below :</p>
            <?php } ?>
    </div>
    <div id="tab-section">
      <?php $this->load->view('mIIMPredictor5/iimPredictorOutputTabs');?>
    </div>
    <?php $this->load->view('mIIMPredictor5/outputPage',array('predictorData'=>$predictorData));?>
      <div id="loadingNew" style="text-align: center; margin-top: 10px;display:none;">
          <img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" class="small-loader">
      </div>
                  <div class="slide-box">

                      <a href="<?=SHIKSHA_HOME?>/mba/resources/iim-call-predictor?type=repeat">
                       <article class="start-again <?php if(empty($userData['cat_percentile'])){ echo 'full-width'; } ?>" >

                            <p>Want to change your input?</p>

                             <!-- <a>Start Again</a> -->
                             <span class="start-again-link" ga-attr="START_AGAIN">Start Again</span>
                           

                       </article>
                       </a>
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
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Bangalore_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-bangalore"; ?>'>Bangalore</a></li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Delhi_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-delhi"; ?>'>Delhi</a></li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Chennai_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-chennai"; ?>'>Chennai</a></li>

                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Delhi_NCR_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-delhi-ncr"; ?>'>Delhi/NCR</a></li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Hyderabad_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-hyderabad"; ?>'>Hyderabad</a></li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Kolkata_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-kolkata"; ?>'>Kolkata</a></li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Mumbai_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-mumbai"; ?>'>Mumbai</a></li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Mumbai)All_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-mumbai-all"; ?>'>Mumbai (All)</a></li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Pune_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-pune"; ?>'>Pune</a> </li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Karnataka_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-karnataka"; ?>'>Karnataka</a></li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_Uttar_Pradesh_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-uttar-pradesh"; ?>'>Uttar Pradesh</a></li>
                                            <li><a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_India_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-india"; ?>'>All India</a></li>
                                        </ul>                           
                                    </div>
                                    <!-- <div class="-foot">
                                        <a target="_blank" onclick="trackEventByGAMobile('ICP_Mobile_Category_India_Widget');" href='<?php echo SHIKSHA_HOME."/mba/colleges/mba-colleges-in-india"; ?>'>More Locations</a>
                                    </div> -->
                                </div>
                            </div>
                            
                   <div>
                      <!-- <p class="italic-txt">* %ile to aim for IIM Bangalore, IIM Lucknow and IIM Rohtak are not shown due to data unavailability. We are trying our best to get this for you. IIM Sirmaur did not conduct a GD-PI round last year.</p> -->
                      <p class="italic-txt">The eligibility criteria considered here is the latest criteria shared by each IIM. For IIMs where data for current year is not available, data for last year has been used.</p>
                      <p class="italic-txt"><span>Disclaimer</span> - The results provided here are on the bases of past data for IIMs. The actual calls you get may differ from the ones shown above.</p>
                   </div>
                   <input type="hidden" name="pageNum" id="pageNum" value="<?php echo $pageNum > 0 ? $pageNum : 1;?>">
        <input type="hidden" name="resultType" id="resultType" value="<?php echo $resultType;?>">
        <input type="hidden" name="eligibility_count" id="eligibility_count" value="<?=$predictorData['eligibilityCount'];?>">
        <input type="hidden" name="inEligibility_count" id="inEligibility_count" value="<?=$predictorData['inEligibilityCount'];?>">
        <input type="hidden" name="limit_results" id="limit_results" value="<?=$limit_results;?>">
                                  
                </div>
  </section> 
<script type="text/javascript">
    userData ='<?php echo json_encode($userData);?>';
</script>
</body>
</html>
