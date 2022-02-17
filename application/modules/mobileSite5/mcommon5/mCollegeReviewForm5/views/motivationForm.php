
<div class="rating-title" style="margin-bottom: 6px">My biggest motivation to join this college was</div>
                  <select name="motivationReviewRatingFactor" id="motivationReviewRatingFactor">
                           <option selected="selected" value="" >Select</option>
                            <?php foreach($motivationReviewRatingFactor as $key => $motivation){ ?>
                                <option value="<?php echo $key?>">
                                    <?php echo $motivation?>
                                </option>
                            <?php }?>
                    </select>
                    
                            <div style="display:none;"><div class="errorMsg" id="motivation_error" style="*float:left"></div></div>