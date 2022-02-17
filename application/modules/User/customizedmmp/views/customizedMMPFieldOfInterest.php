<div>
    <div class="float_L" style="width:35%;line-height: 18px;">
        <div class="txt_align_r" style="padding-right:5px">Field of Interest<b class="redcolor">*</b>:</div>
    </div>
    <div id="fieldOfInterestFieldContainer" style="width:63%;float:right;text-align:left;">
        <div>
            <select validate = 'validateSelect' required = 'true' caption = 'the field of interest' id='fieldOfInterest' name="board_id" style = "width:90%;">
                <option value=''>Select</option>
                <?php
                    foreach($categories as $categoryId => $categoryName) {
                        echo "<option value='". $categoryId."'>". $categoryName ."</option>";
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
<div class="lineSpace_10" style="clear:both;">&nbsp;</div>
