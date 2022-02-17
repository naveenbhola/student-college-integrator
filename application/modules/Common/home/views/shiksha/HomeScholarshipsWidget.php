<?php
$scholarships = $scholarshipResults['scholarhips'];
if(is_array($scholarships) && count($scholarships) > 0 ) {
    foreach($scholarships as $scholarship) {
        $scholarshipTitle = $scholarship['title'];
        $scholarshipId = $scholarship['id'];
        $scholarshipUrl = $scholarship['url'];
        $scholarshipCityId = $scholarship['city_id'];
        $scholarshipCountryId = $scholarship['country_id'];
        $scholarshipCityName = $scholarship['city_name'];
        $scholarshipCountryName = $scholarship['country_name'];
        $displayTitle = $scholarshipTitle;
        if(strlen($displayTitle) > $characterLength) {
            $displayTitle = substr( $displayTitle, 0,$characterLength-3) . '...';
        }
        $sponsoredResult = (isset($scholarship['isSponsored']) && $scholarship['isSponsored'] == true) ? '<img src="/public/images/check.gif" style="margin-left:4px" align="absmiddle" />' : '<img src="/public/images/smallBullets.gif" style="margin-left:7px" align="absmiddle" />';
        $sponsoredClass = (isset($scholarship['isSponsored']) && $scholarship['isSponsored'] == "true") ? 'quesAnsBulletsSponsored': 'quesAnsBullets';
?>
    <div class="normaltxt_11p_blk arial">
        <div style="margin-bottom:2px" class="<?php echo $sponsoredClass; ?>">
            <a class="fontSize_12p" href="<?php echo $scholarshipUrl; ?>" title="<?php echo $scholarshipTitle;?>"><?php echo $displayTitle; ?></a>
        </div>
        <div style="line-height:10px">&nbsp;</div>
    </div>
<?php
    }
    if(count($scholarships) > $scholarships['total']) {
        $urlParams = '';
        if($categoryId > 1) {
            $urlParams .= 'categoryId='. $categoryId;
        }
        if($urlParams != '') {$urlParams .='&';}
        if($countryId > 1 && strpos($countryId, ',')=== false) {
            $urlParams .= 'countryId='. $countryId;
        }
?>
   <div align="right"><a href="/listing/Listing/showScholarshipsList?<?php echo $urlParams .'&c='. rand(); ?>">View All</a></div> 
<?php
    }
} else {
    echo "No scholarships as of now. Check back soon!";
}
?>
