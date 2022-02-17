<?php
$visa = $countryOverview['visa'];
 if($visa != false){
?>
<h3 class="popular-title">Students Visa for reaching <?php echo ucwords($countryObj->getName()); ?></h3>
<div class="clearfix"></div>
<?php if($visa['complexity'] != ""){ ?>
<div class="cntry-subWidget clear">
    <p class="title">Visa application process</p>
    <div class="visa-processSec">
        <div class="process-bar">
            <span class="bar-circle <?php if($visa['complexity'] == "simple") echo "active";?>"><strong>Simple</strong></span>
            <span class="bar-circle <?php if($visa['complexity'] == "moderate") echo "active";?>" style="left:46%;"><strong>Moderate</strong></span>
            <span class="bar-circle <?php if($visa['complexity'] == "complex") echo "active";?>" style="right:0; left:auto;"><strong>Complex</strong></span>
        </div>
    </div>
</div>
<?php } ?>
<?php if($visa['fees']){ ?>
<div class="cntry-subWidget clear">
    <p class="title">Fees for visa</p>
    <strong><?php echo $visa['fees']; ?></strong>
</div>
<?php } ?>
<?php if($visa['timeline']){ ?>
<div class="cntry-subWidget clear">
    <p class="title">Timelines and visa processing</p>
    <strong><?php echo htmlentities($visa['timeline']); ?></strong>
</div>
<?php } ?>
<?php if($visa['description']){ ?>
<p class="widget-content clear"><?php echo formatArticleTitle((strip_tags($visa['description'])),500); ?>
<a href="<?php echo $visa['link']; ?>" >Read more about visa in <?php echo ucwords($countryObj->getName()); ?>&gt;</a></p>
<?php } ?>
<?php } ?>