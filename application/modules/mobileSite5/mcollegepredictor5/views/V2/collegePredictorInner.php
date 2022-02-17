<?php 
$maxRoundNumbers = count($roundData);
$displayedTupleCount =0 ;
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
<div class="rslt-crd <?php echo $defaultView ? 'launch-rslt' : ''; ?>">
<div class="crd-desc <?php if($courseId > 0) echo "shrt" ?>">
    <p><?php if($instUrl != ''){ ?><a href="<?=$instUrl;?>" class="instut-lnk"><?php } ?><?=$branchObj->getCollegeName()?><?php if($instUrl != ''){ ?></a><?php } ?></p>
    <?php
    if($courseData[$courseId]['showHomeState'] && $isHomeState == 1){
    ?>
        <span class="crd-loc"><?=$branchObj->getCityName();?>
        <label class="nblink">Home State</label>
        </span>

    <?php } 
    else{
    ?>
        <span class="crd-loc"><?=$branchObj->getCityName();?></span>
    <?php } ?>
    
    <?php if($courseUrl=='NULL' || empty($courseUrl)){ ?>
       <div class='brchname'> <?=$branchObj->getBranchName();?> </div>
    <?php }  else { ?>
        <a href="<?=addhttp($courseUrl);?>" <?php if($type == "external"): ?>target="_blank" rel="nofollow" <?php endif;?> ><?=$branchObj->getBranchName();?></a>
    <?php } ?>
    
    <?php
        if(isset($shortlistedCoursesOfUser[$courseId])){
          $class = 'act';
        }else{
          $class = '';
        } 
    ?>
    <i class="shrtbtn <?php echo $class; ?>" id='<?php echo "shortlist_{$courseId}";?>' trackingKeyId="<?php echo $shortlisttrackingPageKeyId; ?>" clientCourseId="<?php echo $courseId; ?>"></i>
</div>
<div class="pred_tuple">
    <?php 
        if(!empty($courseData[$courseId]['aggregateReviewsData'])  && $courseData[$courseId]['aggregateReviewsData']['aggregateRating']['averageRating'] > 0) {
            $this->load->view("mobile_listing5/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $courseData[$courseId]['reviewUrl'], 'aggregateReviewsData' => $courseData[$courseId]['aggregateReviewsData'], 'reviewsCount' => $courseData[$courseId]['reviewCount'],'forPredictor' => 1));
        }
       
        ?>
        <span><?php 
        $courseFees = $courseData[$courseId]['feesData']['value'];
        if(!empty($courseFees)){ 
            if(!empty($courseData[$courseId]['aggregateReviewsData'])  && $courseData[$courseId]['aggregateReviewsData']['aggregateRating']['averageRating'] > 0){?>
            <span class='seprtr'>|</span>
        <?php } ?>
            <?php if($courseFees != "-"){
                $courseFees = getRupeesDisplableAmount($courseFees);
            }
            echo "&#8377;"." ".$courseFees;
            //echo "<span class='seprtr'>|</span>";
        }?>
        </span>
        <?php  
        if(!empty($courseData[$courseId]['courseDuration']['value']) && 
            !empty($courseData[$courseId]['courseDuration']['unit'])){ 
                if(!empty($courseData[$courseId]['aggregateReviewsData'])  && $courseData[$courseId]['aggregateReviewsData']['aggregateRating']['averageRating'] > 0 || !empty($courseFees)){?>
                    <span class='seprtr'>|</span>
                <?php } ?>
        <span> 
        <?php    
            echo $courseData[$courseId]['courseDuration']['value'];
            echo " ";
            echo $courseData[$courseId]['courseDuration']['unit']; ?>
        </span>    
         <?php } 
        ?>
    </div>
<?php if($maxRoundNumbers == 1) { 
    $roundsInfo = $branchObj->getRoundsInfo();
    ?>
    <p class="noRnd">Closing <?php echo (!empty($invertLogic) && $invertLogic==1)?"Score":"Rank";?>: <strong><?=$roundsInfo[1]['closingRank']?></strong></p>
<?php 
} else { ?>
<table>
    <tbody>
        <tr>
            <th class="tal">Round</th>
            <th>Closing Rank</th>
            <?php if(!$defaultView){
                ?>
                <th>Your Chances</th>
                <?php
            } ?>
        </tr>
        <?php $roundsInfo = $branchObj->getRoundsInfo();
        $counterForNullRounds = 0;
        foreach (array_reverse($roundsInfo) as $key => $roundData) { 
            if($roundData['closingRank']==0){
                $counterForNullRounds++;
            }
            else{
                break;
            }
        }
        $counterForNullRounds = 7 - $counterForNullRounds; 
       // _P($counterForNullRounds);
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
                        $roundHighlightClass = '';
                    }
                ?>
                <tr class="<?=$roundHighlightClass;?>">
                    <?php if($closingRank > 0) { ?>
                    <td class="tal">Round <?=$roundData['round'];?></td>
                        <td><?=(is_string($roundData['closingRankString'])) ? $roundData['closingRankString']: $closingRank;?></td>
                        <?php 
                        if(!$defaultView){
                            ?>
                            <td><span class="<?=$roundEligibleClass?>"></span></td>
                            <?php
                        }
                        ?>
                    <?php } else { ?>
                        <td style="text-align: center;" colspan="3" class="na-txt">Data for round <?=$roundData['round'];?> is not available</td>
                    <?php } ?>
                </tr>
            <?php }?>
    </tbody>
</table>
<?php }?>
<?php 
if($courseId > 0){
    ?>
    
    <div class="rslt-btn">
        <a href="javascript:void(0);" compareCourseId=<?php echo $courseId; ?> trackingKeyId="<?php echo $comparetrackingPageKeyId; ?>" id='compare_<?php echo $courseId ?>' class="blue-rnkBtn addToCmp">Compare</a>

        <?php 
        $brochureButtonDisable=false;
        if(checkIfBrochureDownloaded($courseId)){
            $brochureButtonDisable=true;
        }
        ?>
        <a href="javascript:void(0);" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>"  clientCourseId=<?php echo $courseId; ?> courseName="$courseData[$courseId]['courseName']" trackingKeyId="<?php echo $brochuretrackingPageKeyId; ?>" class="orng-rnkBtn <?php echo $brochureButtonDisable? "btn-mb-dis":"dnldBrchr";?>"><?php echo $brochureButtonDisable? "Brochure Mailed":"Apply Now";?></a>
    </div>
    <?php
}
?>

<?php 
if($courseData[$courseId]['isIIT'] == 1){?>
    <div class="disc_msg">Disclaimer: With assumption that you get a similar rank in JEE Advanced 2019.</div>
<?php } ?>
</div>

<?php $displayedTupleCount++; 
    if($displayedTupleCount == 1 && $boomr_pageid == 'RANK_PREDICTOR'){
        $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow'));
    }

    if($displayedTupleCount == 3)
    {
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
    }
    if($displayedTupleCount == 6)
    {
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON'));
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON1'));
    }
}
?>
<script>
    window.totalResults = '<?=$totalResults;?>';
</script>