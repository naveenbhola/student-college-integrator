<?php
$work = $countryOverview['work'];
if($work){
?>
<div class="cntry-subWidgetDetail clear">
    <h3 class="popular-title">Study and Work in <?php echo ucwords($countryObj->getName()); ?></h3>
    <div class="clearfix"></div>
    <?php if($work['preLink']){ ?>
    <div class="cntry-subWidget clear">
        <p class="title">Part time Jobs in <?php echo ucwords($countryObj->getName()); ?></p>
        <?php if(empty($work['prestatus']) || $work['prestatus'] == "not permitted"){ ?>
        <p><strong>Not Permitted</strong>&nbsp;</p>
        <?php }else{ ?>
        <p><strong><?php echo $work['prehours'];?></strong> <?php echo $work['predays'];?> </p>
        <?php } ?>
       
        <p>
        <?php if($work['preDescription']){?><?php echo formatArticleTitle((strip_tags($work['preDescription'])),120);?><br><?php } ?>
        <a href="<?php echo $work['preLink'];?>" >Read more &gt;</a>
        </p>
    </div>
    <?php } ?>
    <?php if($work['postLink']){?>
    <div class="cntry-subWidget clear">
        <p class="title">Work Permit after Study in <?php echo ucwords($countryObj->getName()); ?></p>
        <?php if($work['poststatus'] == "not permitted"){?>
       <p><strong>Not Permitted</strong>&nbsp;</p>
        <?php }else{ ?>
        <p><strong><?php echo $work['posthours'];?></strong> <?php echo $work['postdays'];?></p>
        <?php } ?>
        <p><?php if($work['postDescription']){?><?php echo formatArticleTitle((strip_tags($work['postDescription'])),120);?><br><?php } ?>
            <a href="<?php echo $work['postLink'];?>" >Read more &gt;</a></p>
    </div>
    <?php } ?>
</div>
<?php } ?>