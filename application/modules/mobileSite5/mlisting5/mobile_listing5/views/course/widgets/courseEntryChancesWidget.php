<?php if(!empty($predictorData)) {
    $showRankWidget = false;
    if($pageName == 'Admission')
    {
        $gaAttr = "ga-attr = 'FIND_OUT_PREDICTOR'";
    }
    ?>
<div class="crs-widget gap">
	<div class="lcard end-col">
		<h2 class="admisn">Want to know your chances of getting into this college?</h2>
		<?php if(count($predictorData) > 1 ) { ?>
		<a href="javascript:void(0);" class="btn-mob-blue eli-tool" <?=$gaAttr;?>>Find Now</a>
        
        <input class="option-slctd hid" id="eligibilityPredictorSelect_input" value="">
        <select id="eligibilityPredictorSelect" style="display:none;">
            <?php             
            foreach ($predictorData as $data) { ?>
                <option value="<?php echo addslashes($data['url']); ?>"><?php echo htmlentities($data['name']);?></option>
            <?php
            }
            ?>
        </select>
		<?php }else{  if($predictorData[0]['name'] =='JEE-Mains') { $showRankWidget = true; } ?>
		<a data-url="<?php echo $predictorData[0]['url']?>" data-name="<?php echo $predictorData[0]['name']?>" class="btn-mob-blue eli-tool" <?=$gaAttr;?>><?php echo $showRankWidget == true ?'Predict College':'Find Now' ?></a>		
		<?php } ?>
	</div>
</div>
<?php if($showRankWidget == true) { ?>
<div class="crs-widget gap">
    <div class="lcard end-col">
    <h2 class="admisn">Want to know your chances of getting into this college?</h2>
        <a data-url="<?php echo $predictorData[0]['rank_predictor_url']?>" data-name="<?php echo $predictorData[0]['name']?>" class="btn-mob-blue eli-tool" <?=$gaAttr;?>>Predict Rank</a>     
    </div>
</div>
<?php } } ?>


<?php if(!empty($cutOffData)) { foreach ($cutOffData as $row) { ?>
    <div class="crs-widget gap">
        <div class="lcard end-col">
            <h2 class="admisn">Check cut-offs for this course and others</h2>
            <a data-url="<?php echo $row['url']?>" data-name="<?php echo $predictorData[0]['name']?>" class="btn-mob-blue eli-tool" <?=$gaAttr;?>><?php echo $row['text']; ?></a>     
        </div>
    </div>
<?php  } } ?>
