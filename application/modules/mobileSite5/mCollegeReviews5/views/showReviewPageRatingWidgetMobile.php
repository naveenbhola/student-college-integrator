 <div class="review-detail" style="margin: 10px 0px 0px;">
                    <ol class="rating-display clearfix">

                       <?php foreach ($reviewDataValue as $description => $rating) {?>

                           <li>
                            <i class=""></i>
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
                            <p class="font-11"><?php echo $ratingTextValue[$rating];?></p>
                            </li>
                            </p>
                        </li>

                       <?php  }?> 
                        
                    </ol>
                 </div>