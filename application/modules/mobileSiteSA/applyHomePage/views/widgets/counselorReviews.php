<div class="card_body clear_max">
  <?php 
    $reviewCount = 0;
    foreach($reviewInfo as $key=>$value){ 
        if($reviewCount==3){
            $this->load->view('widgets/counselorReviewPageApplyWidget');
        }
      $this->load->view('applyHomePage/widgets/counselorReviewTuple',array('value'=>$value));
      $reviewCount++;
    }?>
    <?php if($totalReviewCount==0){?>
        <p class="no_rvws">No review available</p>
    <?php } ?>
     <?php if($totalReviewCount<4){
         $this->load->view('widgets/counselorReviewPageApplyWidget');
     }
?>
    
</div>
<?php if($totalReviewCount>REVIEW_PER_PAGE){?>
<div class="load_more" id="uni_pagination_results">
  <a class="more_btn ripple" id="pgntnBtn">Load More Reviews</a>
</div>
<?php } ?>