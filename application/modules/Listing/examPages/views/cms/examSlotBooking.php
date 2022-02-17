<div class="clear-width dragable-block" id="exam_slotbooking_section">
    <select id="slotbooking_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['slotbooking'];?>,'slotbooking_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['slotbooking'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Slot Booking</h3>
    <div id="slotbooking_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label>Slot Booking : <p class="collapse-state"></p></label>
                <div class="cms-fields slotbooking-div">
                    <textarea id="slotbooking" name="slotbookingpagedata" class="cms-textarea tinymce-textarea" caption="Slot Booking" validationtype="html"><?=$slotbooking['slotbooking']['wikiData'];?></textarea>
                    <div id="slotbooking_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_slotbooking" value="<?php echo $slotbooking['slotbooking']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('slotbooking','0','slotbooking','');return false;" class="button button--secondary">Save</button>
                        <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                                    $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'slotbooking'));
                            } ?>                        
                    </div>
                    <?php } ?>
                </div>

                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_slotbooking"> Update Date</div>
            </li>
        </ul>
    </div>
</div>
