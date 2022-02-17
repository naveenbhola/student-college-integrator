<?php 
$maxRoundNumbers = count($roundData);

if($maxRoundNumbers == 1) {
    $tupleMainClass = 'tpl-sec tpl-tbl';
}
else {
    $tupleMainClass = 'tpl-sec';
}
?>
<?php
       
        $displayedTupleCount = 0;
        foreach($branchInformation as $key=>$branchObj) {
            $type = null;
            $courseId = $branchObj->getShikshaCourseId();
            $isHomeState = $branchObj->getIsHomeStateTuple();
            if($courseId){
                $courseUrl = $courseData[$courseId]['courseUrl'];
                $type = "internal";
            }
            else{
                $courseUrl = $branchObj->getInstCourseLink();
                if(empty($courseUrl) || $courseUrl == 'NULL')
                {
                    $courseUrl = $branchObj->getInstLink();
                }
                $type = "external";
            }
            $instUrl = '';
            if($courseId > 0 && $courseId !=''){
                if(!empty($courseData[$courseId]['instituteUrl'])){
                    $instUrl = $courseData[$courseId]['instituteUrl'];
                }
                
            }
            ?>
            <div class="<?=$tupleMainClass;?>">
                <div class="tpl-lft">
                    <div class="tpl-lftBx">
                        <div>
                            <p><?php if($instUrl != ''){ ?><a href="<?=$instUrl;?>" target="_blank" class="instut-lnk"><?php } ?><?=$branchObj->getCollegeName()?><?php if($instUrl != ''){ ?></a><?php } ?></p>
                            <?php 
                                if($courseData[$courseId]['showHomeState'] && $isHomeState == 1){
                                    ?>
                                    <span class="tpl-loc"><?=$branchObj->getCityName();?>
                                        <label class="nblink">Home State</label>
                                    </span>
                            <?php }  
                                else{
                                ?>
                                    <span class="tpl-loc"><?=$branchObj->getCityName();?></span>
                                <?php } ?>
                               
                            <?php 

                                if($courseUrl=='NULL' || empty($courseUrl)){ 
                                    ?>
                                    <div class='brchname'><?=$branchObj->getBranchName();?></div>
                                    <?php 
                                }else { 
                                    ?>
                                    <a href="<?=addhttp($courseUrl);?>" target="_blank" <?php if($type == "external"): ?> rel="nofollow" <?php endif;?> onclick="trackEventByGA('Branchtabclick','BRANCH_TAB');"><?=$branchObj->getBranchName();?></a>
                                    <?php 
                                } ?>
                                    <div class="pred_tuple">
                                <?php if(!empty($courseData[$courseId]['aggregateReviewsData'])  && $courseData[$courseId]['aggregateReviewsData']['aggregateRating']['averageRating'] > 0) {
                                    $this->load->view("listingCommon/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $courseData[$courseId]['reviewUrl'], 'aggregateReviewsData' => $courseData[$courseId]['aggregateReviewsData'], 'reviewsCount' => $courseData[$courseId]['reviewCount'], 'forPredictor' => 1));
                                    
                            }
                           
                            ?>
                            <span><?php 
                            $courseFees = $courseData[$courseId]['feesData']['value'];
                            if(!empty($courseFees)){
                                if(!empty($courseData[$courseId]['aggregateReviewsData'])  && $courseData[$courseId]['aggregateReviewsData']['aggregateRating']['averageRating'] > 0)
                                {
                                    echo "<span class='seprtr'>|</span>";    
                                }
                                
                                if($courseFees != "-"){
                                    $courseFees = getRupeesDisplableAmount($courseFees);
                                }
                                echo "&#8377;"." ".$courseFees;
                            }?>
                            </span>
                           
                            <span> <?php  
                            if(!empty($courseData[$courseId]['courseDuration']['value']) && 
                                !empty($courseData[$courseId]['courseDuration']['unit'])){
                                if(!empty($courseData[$courseId]['aggregateReviewsData'])  && $courseData[$courseId]['aggregateReviewsData']['aggregateRating']['averageRating'] > 0
                                    || !empty($courseFees)){
                                    echo "<span class='seprtr'>|</span>";
                                }
                                echo $courseData[$courseId]['courseDuration']['value'];
                                echo " ";
                                echo $courseData[$courseId]['courseDuration']['unit'];
                            }
                            ?></span>
                           
                        </div>
                        <?php 
                            if($maxRoundNumbers == 1) {
                                $this->load->view('CP/V2/collegePredictorCta',array('currentCourseId' => $courseId));
                            }    
                        ?>
                        </div>
                        <?php 
                            if($maxRoundNumbers > 1) {
                                $this->load->view('CP/V2/collegePredictorCta',array('currentCourseId' => $courseId)); ?>
                        </div>
                    </div>
                <?php }
                if($maxRoundNumbers == 1) { 
                    $roundsInfo = $branchObj->getRoundsInfo(); ?>
                        <div class="cls-rnk">
                            <p>Closing <?php echo (!empty($invertLogic) && $invertLogic==1)?"Score":"Rank"; ?>: <strong><?=$roundsInfo[1]['closingRank']?></strong></p>
                        <?php 
                }
                else { 
                    ?>
                    <div class="tpl-rgt">
                        <table>
                            <tbody>
                                <?php 
                                    $roundsInfo = $branchObj->getRoundsInfo();
                                    $counterForNullRounds = 0;
                                    foreach (array_reverse($roundsInfo) as $key => $roundData) { 
                                        if($roundData['closingRank']==0){
                                            $counterForNullRounds++;
                                        }
                                        else{
                                            break;
                                        }
                                    }
                                    if($counterForNullRounds  == 6){
                                        $counterForNullRounds--;
                                    }
                                    $counterForNullRounds = 7 - $counterForNullRounds; 
                                    $thumbMarkShown = false;
                                    foreach ($roundsInfo as $key => $roundData) { 
                                        if($counterForNullRounds == 0){
                                            break;
                                        }
                                        else{
                                            $counterForNullRounds--;
                                        } 
                                        $roundHighlightClass = (!$roundData['isEligible']) ? 'disabled' : '';
                                        $closingRank = $roundData['closingRank'];
                                        $roundEligibleClass = 'close';
                                        
                                        if($thumbMarkShown && $roundData['isEligible']) {
                                            $roundEligibleClass = 'tick-mrk';
                                        }
                                        else if($roundData['isEligible']) {
                                            $thumbMarkShown = true;
                                            $roundEligibleClass = 'thumb-mrk';
                                            $roundHighlightClass = 'active';
                                        }
                                        if($defaultView){
                                            $roundHighlightClass = "";
                                        }
                                        ?>
                                        <tr class="<?=$roundHighlightClass;?>">
                                            <?php 
                                                if($closingRank > 0 ) { 
                                                    ?>
                                                    <td>Round <?=$roundData['round'];?></td>
                                                    <td><?=(is_string($roundData['closingRankString'])) ? $roundData['closingRankString']: $closingRank;?></td>
                                                    <?php 
                                                    if(!$defaultView){
                                                        ?>
                                                        <td><span class="<?=$roundEligibleClass?>"></span></td>
                                                        <?php 
                                                    }
                                                    ?>
                                                    <?php 
                                                }
                                                else { 
                                                    ?>
                                                    <td colspan="3" class="na-txt">Data for round <?=$roundData['round'];?> is not available</td>
                                                    <?php 
                                                } 
                                            ?>
                                        </tr>
                                        <?php 
                                    } 
                                ?>
                            </tbody>
                        </table>
                        <?php 
                } ?>
                <?php 
                if($maxRoundNumbers == 1) { ?>
                        </div>
                     </div>
                    <?php  
                }
                ?>
    </div>
</div>
<?php 
if($courseData[$courseId]['isIIT'] == 1){?>
    <div class="disc_msg">Disclaimer: With assumption that you get a similar rank in JEE Advanced 2019.</div>

<?php 
}
    $displayedTupleCount++;
    if($displayedTupleCount == 3)
    {
        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA'));
        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA1'));
    }
    if($displayedTupleCount == 6)
    {
        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON'));
        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON1'));
    }
}
?>
<?php if($total > $start + $count && !$defaultView) { ?>
<a href="javascript:void(0);" onclick="searchData(1,startCount,'rank','asc','loadmore','','<?php echo $loadtrackingPageKeyId?>'); startCount+=count;" class="load-mr">Load More</a>
<?php } ?>
<script>
    <?php if($start == 0 && isset($count)) { ?>
        window.startCount = <?=$count?>;
    <?php } ?>
</script>
