<?php 
if($aggregateReviewsData['aggregateRating']['averageRating'] < 1) {
  return;
}

$anchorText = $reviewsCount.' reviews';
$viewAllAnchorText = 'View all '.$reviewsCount.' reviews';
if($reviewsCount == 1) {
  $anchorText = $reviewsCount.' review';
  $viewAllAnchorText = 'View '.$reviewsCount. ' review';
}
if($forAnsweredQuestions){
  $anchorText = '('.$reviewsCount.')';
}
$showAggregateWidget = true;
if(empty($aggregateReviewsData)){
  $showAggregateWidget = false;  
}
if(isset($isPaid) && $isPaid && $aggregateReviewsData['aggregateRating']['averageRating']<3.5){
   $showAggregateWidget = false;  
}  

if($showAggregateWidget) {
?>
<?php
  $ratingBlockClass = '';
  if($dontHover == 1){
    $ratingBlockClass = 'mrgn_rgt';  
  }
?>

<span class='rating-block ' on="tap:rating-toplightbox<?php echo $id;?>" role="button" tabindex="0">
  <?php echo number_format($aggregateReviewsData['aggregateRating']['averageRating'], 1, '.', ''); ?>
  <i class="empty_stars starBg">
    <i class="full_starts starBg <?php echo 'w-'.$aggregateReviewsData['aggregateRating']['averageRating']*20; ?>"></i>
  </i>
  <?php if($dontHover != 1)
  {?>
  
  <b class="icons bold_arw"></b> 
    
  <?php } ?>
  
</span>

<?php if($dontHover != 1)
  {?>
  
<amp-lightbox id="rating-toplightbox<?php echo $id;?>" layout="nodisplay">
  <div class="lightbox">
       <a class="cls-lightbox f25 color-f font-w6" on="tap:rating-toplightbox<?php echo $id;?>.close" role="button" tabindex="0">×</a>
       <div class="m-layer">
         <div class="min-div colo-w">
           <div class="pad10  rvw-fix color-w">
             <ol>
               <?php
                   $ratingDisplayOrder = $this->config->item('aggregateRatingDisplayOrder');
                   $percentFactor = 100/(count($aggregateReviewsData['aggregateRating']) - 1);
                   foreach ($ratingDisplayOrder as $key => $reviewType) {
                       if(!empty($aggregateReviewsData['aggregateRating'][$key])) {
                           $rating = $aggregateReviewsData['aggregateRating'][$key];
                           $ratingBar = $rating*$percentFactor;
                 ?>

                 <div class="table_row">
                    <div class="table_cell">
                      <p class="rating_label"><?php echo $reviewType;?> </p>
                    </div>
                    <div class="table_cell">
                       <span class="loadbar"><span class="fillBar <?php echo 'w-'.$ratingBar; ?>"></span></span>
                       <b class="bar_value"><?php echo number_format($rating, 1, '.', '');?></b>
                    </div>
                 </div>
                 <?php } 
                   }
                 ?> 
                 <div class="table_row">
                   <div class="fill_cell"><a  class="view_rvws" href="<?=$allReviewsUrl?>"><?=$viewAllAnchorText?><i class="sprite-str arw_l"></i></a></div>
                 </div>
             </ol>
           </div>
         </div>
       </div>
  
   
  </div>
</amp-lightbox>

<?php } } ?>

<?php if($dontRedirect == 1){ ?>
  <div class="view_rvws" >&nbsp; <?=$anchorText?> </div>
<?php } else if($forAnsweredQuestions == 1){ ?>
  <a class="view_rvws ga-analytic" data-vars-event-name="Header_reviews" href="<?=$allReviewsUrl?>"><?=$anchorText?></a>
<?php } else{ ?>
<a class="view_rvws ga-analytic" data-vars-event-name="Header_reviews" href="<?=$allReviewsUrl?>"><?=$anchorText?><i class="sprite-str arw_l"></i></a>
<?php } ?>