<div class="clear-width dragable-block" id="exam_answerkey_section">
    <select id="answerkey_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['answerkey'];?>,'answerkey_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['answerkey'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Answer Key</h3>
    <div id="answerkey_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label>Answer Key : <p class="collapse-state"></p></label>
                <div class="cms-fields answerkey-div">
                    <textarea id="answerkey" name="answerkeypagedata" class="cms-textarea tinymce-textarea" caption="Answer Key" validationtype="html"><?=$answerkey['answerkey']['wikiData'];?></textarea>
                    <div id="answerkey_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_answerkey" value="<?php echo $answerkey['answerkey']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('answerkey','0','answerkey','');return false;"  class="button button--secondary">Save</button>
                    <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                                $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'answerkey'));
                        } ?>                        
                    </div>
                    <?php } ?>
                </div>
                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_answerkey"> Update Date</div>            
            </li>
        </ul>
    </div>
</div>
