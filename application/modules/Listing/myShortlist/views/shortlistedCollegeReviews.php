<div class="tab-content-section">
<?php if($viewData['totalReview'] < 1) { ?>
        <div class="notify-box ana-main-view" id="_rvnodata"><i class="shortlist-sprite notify-icn"></i>Sorry, reviews are currently not available. We will notify you as soon as the reviews are published.</div>
<?php }else { ?>
    
    <div class="reviews-title" onclick="window.open('<?php echo $reviewURL;?>','_blank');" style="cursor:pointer;">
            <strong><a href="javascript:void(0);" style="color:#333;">Overall rating of <?php echo $course->getInstituteName();?></a></strong> <b class="rating-point"><?php echo $viewData['averageRating']; ?></b> <span>(based on <?php echo ($viewData['totalReview'] > 1) ? $viewData['totalReview']. ' reviews' : $viewData['totalReview'].' review';?>)</span>
    </div>
    <?php } ?>
</div>