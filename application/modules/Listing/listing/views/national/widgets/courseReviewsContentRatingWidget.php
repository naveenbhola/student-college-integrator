 <div class="flRt review-detail-right" onmouseenter="toggleRatingText(this,1,'visible');" onmouseleave="toggleRatingText(this,0,'hidden');">
                    <ol class="rating-display">

                        <?php foreach ($rating as $description => $ratingValue) {?>
                        <li class="clear-width">
                            <label><?php echo $description;?></label>
                            <p>
                                <?php echo printRating($ratingValue, 'course-rating-star-filled', 'course-rating-star','sprite-bg'); ?>
                            </p>
                            <p class="font-11" style="line-height:10px;visibility:visible;opacity:0"><?php echo printRatingText($ratingValue);?></p>
                        </li>
                        <?php }?> 
                        
                    </ol>
                 </div>