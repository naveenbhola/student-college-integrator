<div class="review-col-lyr" id = "rev-dtl">
    <div class="review-head">
       <div class="titl">
         <div class="user-initial"><span><?php if($anonymousFlag == 'YES') echo 'A'; else echo substr($personalInfo['firstname'], 0, 1); ?></span></div>
            <div class="user-info">
                <p class="user-title"><?php echo $personalInfo['firstname'].' '.$personalInfo['lastname'] ?></p>
                                <p>Graduation Year - <?php echo $personalInfo['yearOfGraduation'] ?><span class="review-sprtr"> | </span><?php if($averageRating > 0){ ?><span class="review-rate">Rated</span> <span class="review-rating"><?php echo $averageRating; ?>/5</span><?php } ?> <?php if($averageRating > 0 && $recommendCollegeFlag){ ?><span class="review-sprtr"> | </span> <?php } if($recommendCollegeFlag == 'YES'){?>Recommended<i class="cmpre-sprite reco-icon"></i><?php } if($recommendCollegeFlag == 'NO'){?> <i class="cmpre-sprite non-reco-icon"></i> Not Recommended <?php } ?></p>
                <p class=""><i class="cmpre-sprite ic-clock"></i><?php echo date('dS M Y',strtotime($modificationDate)); ?></p>
            </div>
        </div>
        <a id="clsbt" class="remove-tab" href="javascript: void(0);" onclick="hideOverlay();">&times;</a>
    </div>
   
    <div class="collge-review-list" id="reviewLayerSection">
        

        <div class="review-list-content">
            <?php if($placementDescription){ ?>
                <p><b>Placements : </b></p>
                <span><?php echo $placementDescription; ?></span>
            <?php } ?>
        </div>
        <div class="review-list-content">
            <?php if($infraDescription){ ?>
                <p><b>Infrastructure : </b></p>
                <span><?php echo $infraDescription; ?></span>
            <?php } ?>
        </div>
        <div class="review-list-content">
            <?php if($facultyDescription){ ?>
                <p><b>Faculty : </b></p>
                <span><?php echo $facultyDescription; ?></span>
            <?php } ?>
        </div>
        <div class="review-list-content">
            <?php if($reviewDescription && $placementDescription){ ?>
                <p><b>Other Details : </b></p>
                <span><?php echo $reviewDescription; ?></span>
            <?php }else{ ?>
                <span><?php echo $reviewDescription; ?></span>
            <?php } ?>
        </div>
    </div>
</div>
