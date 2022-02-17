 <div id ="reviewratingId" class="permalink-review-detail clearfix" style="padding:0">
    <p class="parameter-title">Rating Parameters</p>

                    <ol class="rating-display">

                       <?php foreach ($reviewData['ratingValue'] as $description => $rating) {?>

                           <li>
                            <i class="<?php echo $imageToView[$description];?>"></i>
                            <label><?php echo $description?></label>
                            <p>
                                <?php 
                                for($i=1;$i<=5;$i++) { 
                                    if($i <= $rating) { ?>
                                        <span class="sprite course-rating-star-filled"></span>
                            <?php   } else { ?>
                                        <span class="sprite course-rating-star"></span>
                            <?php   }
                                } ?>                                                    
                            </p>
                            <p class="font-11"><?php echo $ratingNames[$rating-1]?></p>
                        </li>

                       <?php  }?> 
                        
                    </ol>
                 </div>