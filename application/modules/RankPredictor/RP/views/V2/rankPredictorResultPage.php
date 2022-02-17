<div class="clg-predctr rnk-predictr">
    <div class="rnk-header">
        <div class="base-frame">
            <div class="rnk-rsltPage">
                <?php if(!empty($userPercentile)) {?>
                        <p>Your <?php echo $rpConfig[$examName]['examName']; ?> percentile <strong><?php echo $userPercentile > 0 ? $userPercentile : 0; ?></strong> <a href="javascript:void(0);" id="rankPreRset" class="btn-blue" ga-attr= 'Reset' ga-optlabel='Reuse'>Reset</a></p>
                <?php }else{ ?>
                    <p>Your <?php echo $rpConfig[$examName]['examName']; ?> score <strong><?php echo $enteredScore; ?></strong> <a href="javascript:void(0);" id="rankPreRset" class="btn-blue" ga-attr= 'Reset' ga-optlabel='Reuse'>Reset</a></p>
                <?php } ?>
                <div class="rank-flex">
                    <?php if(!empty($predictedMidPercentile)) { ?>
                        <div class="predict-flex percentile-box">
                            <p class="pred-hedng">Your Predicted Percentile</p>
                            <p class="rnk-count"><span><?php echo $predictedMinPercentile; ?></span><span class="active <?php echo empty($predictedMinPercentile)  && empty($predictedMaxPercentile)? "hide" : "";?>"><?php echo $predictedMidPercentile; ?></span><span><?php echo $predictedMaxPercentile; ?></span></p>
                        </div>
                    <?php } ?>
                    <div class="predict-flex">
                        <p class="pred-hedng">Your Predicted Rank</p>
                        <p class="rnk-count"><span><?php echo $predictedMinRank;?></span><span class="active <?php echo empty($predictedMinRank) && empty($predictedMaxRank) ? "hide" : "";?>"><?php echo $predictedMidRank; ?></span><span><?php echo $predictedMaxRank; ?></span></p>
                    </div>
                </div>
                <?php if($examName == 'jee-main') {?>
                    <p class="tal-txt">Above is the range of your possible rank. The predicted rank may vary due to <?php echo $rpConfig[$examName]['examName'];?> tie breaker rules for same score.</p> 
                <?php }else {?>
                    <p class="tal-txt">Above is the range of your possible rank, and it's mid-point. Your final rank will depend on the performance of other <?php echo $rpConfig[$examName]['examName']; ?> exam takers this year, and on <?php echo $rpConfig[$examName]['examName']; ?> tie breaker rules for the same score</p>
                <?php } ?>
                <p class="Rnkrd-mr"><strong>Disclaimer:</strong><?php echo $rpConfig[$examName]['disclaimer']; ?></p>
            </div>
        </div>
    </div>
    <?php 
    if(!empty($branchInformation)){
        ?>
        <div class="base-frame">
            <div class="rslt-contnr rnk-modify">
                <div class="clg-rslt">
                    <div class="predctr-titl">
                        <strong>You may be eligible for admission in colleges based on your rank and below criteria: </strong>
                        <p>Category <span>General</span> Rank Type <span>All India</span></p>
                    </div>
                    <div class="mdfy-src" id="modifySearchButton" ga-attr= 'Modify' ga-optlabel='ToCollegePredictor'>
                        <a href="javascript:void(0);" >Customize</a>
                    </div>
                </div>
                <?php $this->load->view('CP/V2/collegePredictorInner'); ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>