<div class="clear-width dragable-block" id="exam_news_section">
    <select id="news_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['news'];?>,'news_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['news'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>News</h3>
    <div id="news_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label>News : <p class="collapse-state"></p></label>
                <div class="cms-fields news-div">
                    <textarea id="news" name="newspagedata" class="cms-textarea tinymce-textarea" caption="news" validationtype="html"><?=$news['news']['wikiData'];?></textarea>
                    <div id="news_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_news" value="<?php echo $news['news']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('news','0','news','');return false;" class="button button--secondary">Save</button>
                    <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                        $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'news'));
                        } ?>                        
                    </div>
                    <?php } ?>                    
                </div>

                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_news"> Update Date</div>
            </li>
        </ul>
    </div>
</div>


