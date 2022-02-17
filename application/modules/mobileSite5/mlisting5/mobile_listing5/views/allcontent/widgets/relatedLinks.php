<?php if($reviewURL!='' || $questionURL!='' || $articleURL!='' || $admissionURL!='' || $courseURL!='' || $cutoffURL!='' || $placementURL != ''){ ?>
<div class="crs-widget" style="margin-top:10px;">
    <?php $displayName = ($listingType == 'institute')?'college':$listingType; ?>
    <h2 class="head-L2 intrstd-head">Other <?=$displayName?> information</h2>
    <div class="intrstd-clgWdgt">
    <ul>
        <?php if($courseURL!=''){ ?>
        <li><a href="<?=$courseURL?>" class="link-blue-small" ga-attr="COURSE_LINK"><?=$courseCount ?> Course<?=($courseCount > 1 ? 's' : '')?> Offered </a></li>
        <?php } ?>
        <?php if($admissionURL!=''){ ?>
        <li><a href="<?=$admissionURL?>" class="link-blue-small" ga-attr="ADMISSION_LINK">Admission Process</a></li>
        <?php } ?>
        <?php if($placementURL!=''){ ?>        
        <li><a href="<?=$placementURL?>" class="link-blue-small" ga-attr="PLACEMENT_LINK"> Placements </a></li>
        <?php } ?>
        <?php if($reviewURL!=''){ ?>
        <li><a href="<?=$reviewURL?>" class="link-blue-small" ga-attr="REVIEWS_LINK"><?=$reviewCount ?> Student Review<?=($reviewCount > 1 ? 's' : '')?></a></li>
        <?php } ?>
        <?php if($questionURL!=''){ ?>
        <li><a href="<?=$questionURL?>" class="link-blue-small" ga-attr="QUESTIONS_LINK"><?=$questionCount ?> Answered Question<?=($questionCount > 1 ? 's' : '')?></a></li>
        <?php } ?>
        <?php if($articleURL!=''){ ?>
        <li><a href="<?=$articleURL?>" class="link-blue-small" ga-attr="ARTICLES_LINK"><?=$articleCount ?> News & Article<?=($articleCount > 1 ? 's' : '')?></a></li>
        <?php } ?>
        <?php if($scholarshipURL!=''){ ?>
        <li><a href="<?=$scholarshipURL?>" class="link-blue-small" ga-attr="SCHOLARSHIPS_LINK">Scholarships</a></li>
        <?php } if($cutoffURL!=''){ ?>
        <li><a href="<?=$cutoffURL?>" class="link-blue-small" ga-attr="cutoffURL_LINK"><?php echo $listingName; ?> Cut-offs for <?php echo $courseCount; ?> courses</a></li>
        
        <?php } ?>
    </ul>
    </div>
</div>
<?php } ?>
