<?php 
$starHtml = '';
$starClass = 'feedbackWidgetStar';
$starContainer = 'feedbackStarContainer';
if(!empty($withForm) && $withForm == true){
    $starClass = 'feedbackFormStar';
    $starContainer = 'feedbackFormStarContainer';
}
for ($i=1; $i < 6; $i++) { 
    if($rating > 0 && $i <= $rating){
        $class = 'fill';
    }else{
        $class = 'blank';
    }
    $starHtml .= '<span class="'.$starClass.'" star="'.$i.'"><i class="rating-icon '.$class.'"></i></span>';
}
?>
<div class="rating-container <?php echo $feedbackWidgetTypeClass ?>">
    <h2>Was this page helpful?</h2>
    <span class="rating-icon-container" id="<?php echo $starContainer; ?>"><?php echo $starHtml; ?></span>
    <input type="hidden" value="<?php echo $pageId; ?>" id="feedbackPageId" />
    <input type="hidden" value="<?php echo $pageType; ?>" id="feedbackPageType" />
    <?php 
    if(!empty($withForm) && $withForm == true){
    ?>
        <input type="hidden" name="rating" value="<?php echo $rating; ?>" id="ratingStar" />
    <?php 
    }
    ?>
</div>