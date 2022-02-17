<?php
$economy = $countryOverview['economy'];
if($economy){
?>
<div class="cntry-subWidgetDetail clear">
    <h3 class="popular-title">Economic overview of <?php echo ucwords($countryObj->getName()); ?></h3>
    <div class="clearfix"></div>
    <?php if($economy['growthLink']){ ?>
    <div class="cntry-subWidget clear">
        <p class="title">Economic growth rate</p>
        <p><strong><?php echo htmlentities(strip_tags($economy['percentage']));?> </strong> growth rate</p>
        <p><?php echo formatArticleTitle((strip_tags($economy['growthDescription'])),120);?><br>
            <a href="<?php echo $economy['growthLink'];?>" >Read more &gt;</a></p>
    </div>
    <?php } ?>
    <?php if($economy['sectorLink']){ ?>
    <div class="cntry-subWidget clear">
        <p class="title">Popular job sectors</p>
        <p><?php echo formatArticleTitle((strip_tags($economy['sectorDescription'])),120);?><br>
            <a href="<?php echo $economy['sectorLink'];?>" >Read more &gt;</a></p>
    </div>
    <?php } ?>
</div>
<?php } ?>