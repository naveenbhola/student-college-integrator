<?php if($reviewURL!='' || $questionURL!='' || $articleURL!='' || $admissionURL!='' || $courseURL!='' || $cutoffURL!='' || $placementURL != ''){ ?>
<div class="gap">   
    <h2 class="head-L2 head-gap">Essential information about <?php echo $listingName; ?></h2>
    <div class="lcard end-col noMrgn">
    <ul class="essentl-list">
        <?php if($courseURL!=''){ ?>
        <li><a href="<?=$courseURL?>"  ga-attr="COURSE_LINK"><?=$courseCount ?> Course<?=($courseCount > 1 ? 's' : '')?> Offered </a></li>
        <?php } ?>
        <?php if($admissionURL!=''){ ?>
        <li><a href="<?=$admissionURL?>" ga-attr="ADMISSION_LINK">Admission Process</a></li>
        <?php } ?>
        <?php if($placementURL!=''){ ?>        
        <li><a href="<?=$placementURL?>" ga-attr="<?=$GA_Tap_On_Placement;?>"> Placements </a></li>
        <?php } ?>
        <?php if($reviewURL!=''){ ?>
        <li><a href="<?=$reviewURL?>" ga-attr="REVIEWS_LINK"><?=$reviewCount ?> Student Review<?=($reviewCount > 1 ? 's' : '')?></a></li>
        <?php } ?>
        <?php if($questionURL!=''){ ?>
        <li><a href="<?=$questionURL?>" ga-attr="QUESTIONS_LINK"><?=$questionCount ?> Answered Question<?=($questionCount > 1 ? 's' : '')?></a></li>
        <?php } ?>
        <?php if($articleURL!=''){ ?>
        <li><a href="<?=$articleURL?>" ga-attr="ARTICLES_LINK"><?=$articleCount ?> News & Article<?=($articleCount > 1 ? 's' : '')?></a></li>
        <?php } if($cutoffURL!=''){ ?>
        <li><a href="<?=$cutoffURL?>" ga-attr="cutoffURL_LINK"><?php echo $listingName; ?> Cut-offs for <?php echo $courseCount; ?> courses</a></li>
        
        <?php } ?>
    </ul>
    </div>
</div>
<?php } ?>
