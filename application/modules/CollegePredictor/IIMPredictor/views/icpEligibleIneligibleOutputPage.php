<?php $userData = $predictorData['userData'];?>
<?php $eligibility = $predictorData['scoreData']['eligibility'];?>
<?php //$ineligibility = $predictorData['scoreData']['ineligibility'];?>
    <div class="prsnl-dtls">
        <section class="prsnl-cont clearfix">
            <aside class="prsnl-box">
                <div class="dtls">
                    <section class="predict-tabs">
                        <div class="expect align_cntr">
                        <?php if(empty($userData['cat_percentile']) && $predictorData['eligibilityCount'] == 0) { ?>
                              <p class="fnt_18 clr1 cat_marks">You are not Eligible for any of the colleges. Eligibility are mentioned below.</p>
                              <!-- <a class="predict nxt-btn" id="enter-cat-score" ga-attr="ENTER_CAT_SCORE">Enter CAT score</a> -->
                        <?php }
                         else if(empty($userData['cat_percentile'])) {?>
                              <p class="fnt_18 clr1 cat_marks">Cut-off scores & eligibility are mentioned below. But to predict your IIM and Non IIM calls please fill your CAT score (actual or expected)</p>
                              <a class="predict nxt-btn button button--orange" id="enter-cat-score" ga-attr="ENTER_CAT_SCORE">Enter CAT score</a>
                        <?php }elseif($predictorData['eligibilityCount'] == 0)
                        { ?>
                            <p class="fnt_18 clr6 cat_marks">You are not Eligible for any of the colleges. Eligibility are mentioned below.</p>
                        <?php }
                        else { ?>
                            <p class="fnt_18 clr1 cat_marks"> You are eligible to get calls from <strong><?=$predictorData['eligibilityCount'];?> reputed MBA Colleges</strong> in India. To know your chances (High or Low) of getting a call from these colleges, see results below :</p>
                        <?php } ?>
                        </div>

                        <?php if($predictorData['eligibilityCount']>0 && $predictorData['inEligibilityCount']>0):?>
                        <ul class="prdct-tabs">
                            <?php if(count($eligibility)>0) :?>
                            <li class="current"><a href="#tab_0" data-type="eligibility">Eligible</a><i class="v-line"></i></li>
                            <?php endif;?> 
                            <?php if($predictorData['inEligibilityCount']>0) :?>
                            <li><a href="#tab_1" data-type="inEligibility">Ineligible</a></li>
                            <?php endif;?> 
                        </ul>
                        <?php endif;?>
                        <?php $this->load->view('outputPage',array('predictorData'=>$predictorData));?>
                    </section>
                </div>
            <p class="clr"></p>
            </aside>
        </section>
        <input type="hidden" name="pageNum" id="pageNum" value="<?php echo $pageNum > 0 ? $pageNum : 1;?>">
        <input type="hidden" name="resultType" id="resultType" value="<?php echo $resultType;?>">
        <input type="hidden" name="eligibility_count" id="eligibility_count" value="<?=$predictorData['eligibilityCount'];?>">
        <input type="hidden" name="inEligibility_count" id="inEligibility_count" value="<?=$predictorData['inEligibilityCount'];?>">
        <input type="hidden" name="limit_results" id="limit_results" value="<?=$limit_results;?>">
    </div>
            <!--personal div ends--> 
<script type="text/javascript">
    isCompareEnable = true;
    userData ='<?php echo json_encode($userData);?>';
</script>

