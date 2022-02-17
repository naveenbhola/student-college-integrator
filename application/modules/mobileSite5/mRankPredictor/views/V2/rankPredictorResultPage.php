<div class="clg-predctr rank-predctr">
    <div class="pred-RankDet">
        <div class="pred-RankHd">
            <?php if(!empty($userPercentile)) {?>
                    <p>Your <?php echo $rpConfig[$examName]['examName']; ?> percentile <strong><?php echo $userPercentile > 0 ? $userPercentile : 0; ?></strong></p>
            <?php } else { ?>
                    <p>Your <?php echo $rpConfig[$examName]['examName']; ?> score <strong><?php echo $enteredScore; ?></strong></p>
            <?php } ?>
            <a href="javascript:void(0);" id="rankPreRset" ga-attr= 'Reset' ga-optlabel='Reuse' class="btn-blue">Reset</a>
        </div>
        <div class="pred-rnSec">
            <?php
                if($examName == "jee-main" && !empty($predictedMidPercentile)) {?>
                <div class="percentile-box">
                    <p class="pred-hedng">Your Predicted Percentile</p>
                    <p class="rnk-count"><span><?php echo $predictedMinPercentile; ?></span><span class="active <?php echo empty($predictedMinPercentile) && empty($predictedMaxPercentile) ? 'hide':'';?>"><?php echo $predictedMidPercentile; ?></span><span><?php echo $predictedMaxPercentile; ?></span></p>
                </div>
            <?php } ?>
            <p class="pred-hedng">Your Predicted Rank</p>
            <p class="rnk-count"><span><?php echo $predictedMinRank; ?></span><span class="active <?php echo empty($predictedMinRank) && empty($predictedMaxRank) ? 'hide':'';?>"><?php echo $predictedMidRank; ?></span><span><?php echo $predictedMaxRank; ?></span></p>
            <?php if($examName != "jee-main"){?>
                <p class="tal-txt">Above is the range of your possible rank, and it's mid-point. Your final rank will depend on the performance of other <?php echo $rpConfig[$examName]['examName']; ?> exam takers this year, and on <?php echo $rpConfig[$examName]['examName']; ?> tie breaker rules for same score</p>
            <?php }else {?>
                <p class="tal-txt">Above is the range of your possible rank. The predicted percentile/rank may vary due to <?php echo $rpConfig[$examName]['examName'];?> tie breaker rules for same score.</p>                
            <?php }?>
            <p class="Rnkrd-mr"><strong>Disclaimer:</strong><span><?php echo $rpConfig[$examName]['disclaimer']; ?></span></p>
        </div>
    </div>
    <?php $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow')); ?>
    <?php 
    if(!empty($branchInformation)){
        ?>
        <div class="pred-RankDet no-pad">
            <strong class="modify-txt">You may be eligible for admission in colleges based on your rank and below criteria: </strong>
            <div class="modify-hd">
                <div class="modify-bx">
                    <label>Category</label>
                    <p>General</p>
                </div>
                <div class="modify-bx">
                    <label>Rank Type</label>
                    <p>All India</p>
                </div>
                <div class="modify-btn" id="modifySearchButton" ga-attr= 'Modify' ga-optlabel='ToCollegePredictor'><a href="javascript:void(0);" class="src-btn">Customize</a></div>
            </div>
        </div>
        <?php
        $this->load->view('mcollegepredictor5/V2/collegePredictorInner');
    }
    ?>
</div>