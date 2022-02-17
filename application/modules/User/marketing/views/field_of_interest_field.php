            <div>
                <div class="float_L" style="width: 175px; line-height: 18px;">
                    <div class="txt_align_r" style="padding-right:5px">Field of Interest<b class="redcolor">*</b>:</div>
                </div>
                <div id="fieldOfInterestFieldContainer" style="margin-left:177px;">
                    <div class="float_L">
                        <select validate = 'validateSelect' required = 'true' caption = 'the field of interest' id='fieldOfInterest' name="board_id" style = "width:215px;">
                            <option value=''>Select</option>
                            <?php
                                foreach($categories as $categoryId => $categoryName) {
                                    if ($categoryId != 14) {
                                    echo "<option value='". $categoryId."'>". $categoryName ."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="clear_L withClear">&nbsp;</div>
                    <div class="errorPlace" style="margin-top: 2px; line-height: 15px;">
                        <div class="errorMsg" id="fieldOfInterest_error" style="*padding-left:4px"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
