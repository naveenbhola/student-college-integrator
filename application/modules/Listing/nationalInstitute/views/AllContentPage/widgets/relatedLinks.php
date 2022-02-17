
<?php 
$GA_Tap_On_Questions = 'QUESTIONS_LINK';
$GA_Tap_On_Reviews = 'REVIEWS_LINK';
$GA_Tap_On_Courses = 'COURSE_LINK';
$GA_Tap_On_Admission = 'ADMISSION_LINK';
$GA_Tap_On_Article = 'ARTICLES_LINK';
$GA_Tap_On_Scholarship = 'SCHOLARSHIPS_LINK';
$GA_Tap_On_Placement = 'PLACEMENTS_LINK';

if($reviewURL!='' || $questionURL!='' || $articleURL!='' || $admissionURL!='' || $courseURL!=''){ ?>
<div class="group-card gap rght-crd" style="width:396px;height:auto;">
    <?php $displayName = ($listingType == 'institute')?'college':$listingType; ?>
    <h2 class="rgt-title">Other <?=$displayName?> information</h2>
    <ul style="margin-top:10px;">
        <?php if($courseURL!=''){ ?>
        <li><a href="<?=$courseURL?>" ga-attr="<?=$GA_Tap_On_Courses;?>"><?php echo $courseCount ?> Course<?=($courseCount > 1 ? 's' : '')?> Offered </a></li>
        <?php } ?>
        <?php if($admissionURL!=''){ ?>
        <li><a href="<?=$admissionURL?>" ga-attr="<?=$GA_Tap_On_Admission;?>">Admission Process</a></li>
        <?php } ?>
        <?php if($placementURL!=''){ ?>        
        <li><a href="<?=$placementURL?>" ga-attr="<?=$GA_Tap_On_Placement;?>"> Placements </a></li>
        <?php } ?>
        <?php if($reviewURL!=''){ ?>
        <li><a href="<?=$reviewURL?>" ga-attr="<?=$GA_Tap_On_Reviews;?>"><?php echo $reviewCount ?> Student Review<?=($reviewCount > 1 ? 's' : '')?></a></li>
        <?php } ?>
        <?php if($questionURL!=''){ ?>
        <li><a href="<?=$questionURL?>" ga-attr="<?=$GA_Tap_On_Questions;?>"><?php echo $questionCount ?> Answered Question<?=($questionCount > 1 ? 's' : '')?></a></li>
        <?php } ?>
        <?php if($articleURL!=''){ ?>
        <li><a href="<?=$articleURL?>" ga-attr="<?=$GA_Tap_On_Article;?>"><?php echo $articleCount ?> News & Article<?=($articleCount > 1 ? 's' : '')?></a></li>
        <?php } ?>
        <?php if($scholarshipURL!=''){ ?>
        <li><a href="<?=$scholarshipURL?>" ga-attr="<?=$GA_Tap_On_Scholarship;?>">Scholarships</a></li>
        <?php } ?>
        <?php if($cutoffURL!=''){ ?>
        <li><a href="<?=$cutoffURL?>" ga-attr="<?=$GA_Tap_On_Cutoff;?>"><?php echo $listingName; ?> Cut-offs for <?php echo $courseCount; ?> courses</a></li>
        <?php } ?>
    </ul>
</div>
<?php } ?>

