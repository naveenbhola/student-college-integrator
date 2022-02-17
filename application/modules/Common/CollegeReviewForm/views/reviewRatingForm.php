<div id = "reviewRatingFormId" class="camp-rating clear-width">
    <input name="ratingParam" id="ratingParam" value='<?php echo dec_enc('encrypt',serialize($ratingParam));?>' type="hidden" />
                                 	<p class="form-title"><?php echo $rateSectionHeading; ?> <span>*</span></p>
                                 	<ul>
                                        <?php $index =0; foreach ($ratingParam as $key => $value) {?>
                                        <input name="Rating_<?php echo $key;?>" class="ratingParams" id="Rating_<?php echo $key;?>" value="" type="hidden" />
                                        <li class="clear-width">
                                            <span class="flLt" style="width:240px;"> <?php echo chr(97+$index++); echo ". ".$value?></span>
                                            <div class="flLt">
                                                <ol class="rating-list" onmouseout="hideStarRating('<?php echo $key?>');">
                                                    <li id="<?php echo $key?>_1" onclick="markStarRating(1,'<?php echo $key?>');" onmouseover="showStarRating(1,'<?php echo $key?>');" ><a href="javascript:void(0);" class="campus-sprite  star-icon"></a></li>
                                                    <li id="<?php echo $key?>_2" onclick="markStarRating(2,'<?php echo $key?>');" onmouseover="showStarRating(2,'<?php echo $key?>');" ><a href="javascript:void(0);" class="campus-sprite  star-icon"></a></li>
                                                    <li id="<?php echo $key?>_3" onclick="markStarRating(3,'<?php echo $key?>');" onmouseover="showStarRating(3,'<?php echo $key?>');" ><a href="javascript:void(0);" class="campus-sprite  star-icon"></a></li>
                                                    <li id="<?php echo $key?>_4" onclick="markStarRating(4,'<?php echo $key?>');" onmouseover="showStarRating(4,'<?php echo $key?>');" ><a href="javascript:void(0);" class="campus-sprite  star-icon"></a></li>
                                                    <li id="<?php echo $key?>_5" onclick="markStarRating(5,'<?php echo $key?>');" onmouseover="showStarRating(5,'<?php echo $key?>');" ><a href="javascript:void(0);" class="campus-sprite  star-icon"></a></li>
                                                </ol>
                                                <span class="flRt review-comment" id="<?php echo $key?>_rating" style="padding-top:5px;"></span>
                                            </div>
                                        </li>
                                        <?php }?>
                                        
                                        <div style="display:none;"><div class="errorMsg" id="starRating_error" style="*float:left"></div></div>      
                                    </ul>
</div>
