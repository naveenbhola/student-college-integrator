<?php if($boomr_pageid == "ComparePage"){$shrtCmpClass = 'short-list-box';} ?>
<?php if(($isMBATemplateByCourse[$courseId] || ($pageType == 'mobileRankingPage') || $pageType == 'MOB_CareerCompass_Shortlist')) {
    if(count($courseMBAShortlistArray)>0 && in_array($courseId,$courseMBAShortlistArray) || $courseShortlistedStatus) {
        $class = 'sprite shortlisted-star';
        if($pageType == 'mobileComparePage'){
            $class = "shortlisted-star mcpstar";
        }
        $Shortlist = 'Shortlisted';
    } else {
        $class = 'sprite shortlist-star';
        if($pageType == 'mobileComparePage'){
            $class = "shortlist-star mcpstar";
        }
        $Shortlist = 'Shortlist';
    }
    if($animateStar == 'false') {
        $animateStar = 0;
    } else {
        $animateStar = 1;
    }
    switch($pageType) {
        case 'mobileCategoryPage':
            $shortlistPageType = 'NM_Category';
            break;
        case 'mobileCourseDetailPage':
            $shortlistPageType = 'NM_CourseListing';
            break;
        case 'mobileRankingPage':
            $shortlistPageType = 'NM_Ranking';
            break;
        case 'mobileComparePage':
            $shortlistPageType = 'NM_Compare';
            break;
        case 'MOB_CareerCompass_Shortlist':
            $shortlistPageType = 'NM_CareerCompass';
            break;
        case 'mobileAlsoViewedSection':
            $shortlistPageType = 'NM_AlsoViewed';
            break;
    }
    ?>
    <div class="side-col <?php if(isset($shrtCmpClass)){ echo $shrtCmpClass;}?>" id="shortlistDiv<?php echo $courseId;?>" onclick="callbackParams = {}; <?php if ( isset($tracking_keyid) ) {  ?>callbackParams['tracking_keyid'] = <?php echo $tracking_keyid; } ?>; callbackParams['courseId'] = <?php echo $courseId;?>; callbackParams['animateStar'] = <?php echo $animateStar;?>; if(typeof(rankingShortlist) == 'function'){rankingShortlist(callbackParams);}else{showRegistrationForm(<?php echo $courseId;?>, 'shortlist', '<?php echo $shortlistPageType; ?>', '', 'animateStarMBA', callbackParams,'<?php echo $tracking_keyid;?>');}; event.preventDefault(); event.stopPropagation();">
        <span class="<?php echo $class;?> <?php echo 'allChkShortlisted'.$courseId;?>" id="shortlistedStar<?php echo $courseId;?>"></span>
        <span id="shortlistedText<?php echo $courseId;?>" class="<?php echo 'allChkShortlistedText'.$courseId;?>"><?php echo $Shortlist;?></span>
    </div>
<?php } else {  
    if(count($courseShortArray)>0 && in_array($courseId,$courseShortArray))
    {    
        $class = 'sprite shortlisted-star';
        $Shortlist = 'Shortlisted';
        if($pageType == 'mobileComparePage'){
            $class = "shortlisted-star mcpstar";
        }
    } else {	
        $class = 'sprite shortlist-star';
        $Shortlist = 'Shortlist';
        if($pageType == 'mobileComparePage'){
            $class = "shortlist-star mcpstar";
        }
    } ?>
    <div class="side-col <?php if(isset($shrtCmpClass)){ echo $shrtCmpClass;}?>" id="shortlistDiv<?php echo $courseId;?>" onclick="<?php if (isset($tracking_keyid)) { ?>window.L_tracking_keyid = <?php echo $tracking_keyid; } ?>; animateStar('<?php echo $courseId;?>','<?php echo $pageType;?>');event.preventDefault(); event.stopPropagation();">
        <span class="<?php echo $class;?> <?php echo 'allChkShortlisted'.$courseId;?>" id="shortlistedStar<?php echo $courseId;?>"></span>
        <span id="shortlistedText<?php echo $courseId;?>" class="<?php echo 'allChkShortlistedText'.$courseId;?>"><?php echo $Shortlist;?></span>
    </div>
<?php } ?>
