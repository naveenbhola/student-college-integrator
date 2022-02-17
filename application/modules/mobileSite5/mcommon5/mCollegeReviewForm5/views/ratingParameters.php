<div class="rateing-box" id="reviewRatingFormIdMobile" style="margin-left:0;">
                    <div class="rating-title li-label"> <strong class="headingText"><?php echo $rateSectionHeading; ?></strong> <span class="star-r">*</span></div>
                    <input name="ratingParam" id="ratingParam" value='<?php echo dec_enc('encrypt',serialize($ratingParam));?>' type="hidden" />
                        <ul>

                                        <?php foreach ($ratingParam as $key => $value) {?>

                                        <input name="Rating_<?php echo $key;?>" class="ratingParams" id="Rating_<?php echo $key;?>" value="" type="hidden" />
                                           <li> <label><?php echo $value?></label>
                                            <div class="clearfix">
                                                    <a href="javascript:void(0);" class="sprite rating-star" id="<?php echo $key?>_1" onclick="markStarRating(1,'<?php echo $key?>');" onmouseover="markStarRating(1,'<?php echo $key?>');"></a>

                                                    <a href="javascript:void(0);" class="sprite rating-star" id="<?php echo $key?>_2" onclick="markStarRating(2,'<?php echo $key?>');" onmouseover="markStarRating(2,'<?php echo $key?>');"></a>

                                                    <a href="javascript:void(0);" class="sprite rating-star" id="<?php echo $key?>_3" onclick="markStarRating(3,'<?php echo $key?>');" onmouseover="markStarRating(3,'<?php echo $key?>');"></a>

                                                    <a href="javascript:void(0);" class="sprite rating-star" id="<?php echo $key?>_4" onclick="markStarRating(4,'<?php echo $key?>');" onmouseover="markStarRating(4,'<?php echo $key?>');"></a>

                                                    <a href="javascript:void(0);" class="sprite rating-star" id="<?php echo $key?>_5" onclick="markStarRating(5,'<?php echo $key?>');" onmouseover="markStarRating(5,'<?php echo $key?>');"></a>
                                            </div>
                                            <p style="display:block" id="<?php echo $key; ?>_rating"></p>
                                        </li>
                                        <?php }?>
                                        
                                        <div style="display:none;"><div class="errorMsg" id="starRating_error" style="*float:left"></div></div>      
                                    </ul>
                        </div>
