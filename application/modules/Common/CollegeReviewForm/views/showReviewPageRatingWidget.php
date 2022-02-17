<div class="flRt review-detail-right" style="padding:0; width:41%;">
                    <div style="padding: 10px 5px; background: none repeat scroll 0% 0% rgb(238, 238, 238); font-size: 16px; color: rgb(102, 102, 102);">Rating parameters</div>
                    <ol class="rating-display" style="padding:10px 3px;">

                        <?php foreach ($reviewData['ratingValue'] as $description => $rating) {?>

                           <li class="clear-width">
                            <i class="<?php echo $imageToView[$description];?>"></i>
                            <label><?php echo $description?></label>
                            <p>
                                <?php 
                                for($i=1;$i<=5;$i++) { 
                                    if($i <= $rating) { ?>
                                        <span class="sprite-bg course-rating-star-filled"></span>
                            <?php   } else { ?>
                                        <span class="sprite-bg course-rating-star"></span>
                            <?php   }
                                } ?>                                                    
                            </p>
                            <p class="review-rating-font-11" style="line-height:10px;"><?php echo $ratingNames[$rating-1]?></p>
                        </li>

                       <?php  }?> 

                    </ol>
                 </div>
                <div class="clearFix"></div>