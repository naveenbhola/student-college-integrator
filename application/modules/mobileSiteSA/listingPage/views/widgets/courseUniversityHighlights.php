<?php
$universityHighlight = $universityObj->getWhyJoin();
$shortUniversityHighlight = '';
if(strlen($universityHighlight) > 160){
    $shortUniversityHighlight = formatArticleTitle($universityHighlight,150);
}
?>

<section class="detail-widget">
<div class="detail-widegt-sec">
    <div class="detail-info-sec">  
            <div class="dynamic-content">
                <strong><?=  ucfirst($universityObj->getTypeOfInstitute2())?> Highlights</strong>
                <p><?=$universityHighlight?></p>
                <div id="university-link"><a href="<?php echo $universityObj->getURL();?>" target="_blank" style="text-align:center; display:block; padding:10px;">More details of this university <span class="detail-mark">></span> </a></div>
            </div>
    </div>
</div>
</section>
