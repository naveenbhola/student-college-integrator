<div class="clear-width dragable-block" id="exam_samplepapers_section">
    <select id="samplepapers_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['samplepapers'];?>,'samplepapers_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['samplepapers'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Sample Papers</h3>
    <div id="samplepapers_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label>Introduction : </label>
                <div class="cms-fields samplepapers-div">
                    <textarea id="samplepapersupperWiki" name="samplepaperspagedata" class="cms-textarea tinymce-textarea" caption="Sample Papers" validationtype="html"><?=$samplepapers['upperWiki']['wikiData'];?></textarea>
                    <div id="samplepapers_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_samplepapers" value="<?php echo ($samplepapers['samplepapers']['updatedOn']) ? $samplepapers['samplepapers']['updatedOn'] : ((count($samplepapers['files']['samplepapers'])>0) ? $samplepapers['files']['samplepapers'][0]['updatedOn'] : $samplepapers['files']['guidepapers'][0]['updatedOn'] ) ;?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('samplepapersupperWiki','0','samplepapers','samplepapersupperWiki');return false;" class="button button--secondary">Save</button>
                    </div>
                    <?php } ?>                    

                </div>
            </li>
            <li>
                <label class="cmsFont">Sample Papers Upload </label> 
                 <div class="cms-fields application-div"> 
                     <input name="samplepaper" type="file" multiple id="samplepaper" onchange="uploadSamplePapers(samplepaper,'paperslist')"> 
                       <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                            $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'samplepapers'));
                        } ?>
                     <div aria-valuenow="100" class="progress-bar progress-bar-danger" data-transitiongoal="25" style="display: none;">100</div>
                     <div class="col-md-3" class="viewform" style="display: none" id="viewform">
                             <a class="btn cmsButton cmsFont" target="_blank" href="">View uploaded paper</a>
                    </div>
                    <div class="col-md-2 hide" id="cross">
                             <a class="btn btn-default" target="_blank" onclick="clearBrochureData(samplepaper)">X</a>
                    </div>
                    <input type="hidden" id="appformid" value="">
                 </div>
            </li>
            <li id="paperslist">
            </li>
             <li>
                <label class="cmsFont">Preparation Guides Upload </label> 
                 <div class="cms-fields application-div"> 
                     <input name="guidepaper" type="file" multiple id="guidepaper" onchange="uploadSamplePapers(guidepaper,'guidelist')"> 
                     <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                            $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'guidepapers'));
                        } ?>
                     <div aria-valuenow="100" class="progress-bar progress-bar-danger" data-transitiongoal="25" style="display: none;">100</div>
                     <div class="col-md-3" class="viewform" style="display: none" id="viewform">
                             <a class="btn cmsButton cmsFont" target="_blank" href="">View uploaded paper</a>
                    </div>
                    <div class="col-md-2 hide" id="cross">
                             <a class="btn btn-default" target="_blank" onclick="clearBrochureData(guidepaper)">X</a>
                    </div>
                 </div>
            </li>
            <li id="guidelist">
            </li>
            <li>
                <label>Sample Papers : <p class="collapse-state"></p></label>
                <div class="cms-fields samplepapers-div">
                    <textarea id="samplepapers" name="samplepaperspagedata" class="cms-textarea tinymce-textarea" caption="Sample Papers" validationtype="html"><?=$samplepapers['samplepapers']['wikiData'];?></textarea>
                    <div id="samplepapers_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_samplepapers" value="<?php echo ($samplepapers['samplepapers']['updatedOn']) ? $samplepapers['samplepapers']['updatedOn'] : ((count($samplepapers['files']['samplepapers'])>0) ? $samplepapers['files']['samplepapers'][0]['updatedOn'] : $samplepapers['files']['guidepapers'][0]['updatedOn'] ) ;?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('samplepapers','1','samplepapers','samplepapers');return false;" class="button button--secondary">Save</button>
                        <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                            $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'samplepaperswiki'));
                        } ?>                        
                    </div>
                    <?php } ?>
                </div>

                 <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_samplepapers"> Update Date</div>
            </li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    uploadedSampleFiles = [];
    uploadedGuideFiles = [];
</script>
<?php foreach ($samplepapers['files']['samplepapers'] as $sKey => $sValue) { ?>
        <script>uploadedSampleFiles[<?=$sKey?>] = {'file_url' : '<?php echo $sValue['file_url'];?>','thumbnail_url' : '<?php echo $sValue['thumbnail_url'];?>','file_name' : '<?php echo $sValue['file_name'];?>','file_relative_url' : '<?php echo $sValue['file_relative_url'];?>','position':<?=$sValue['position']?>};
        //updateSortOptions('paperslist');
        </script>
<?php } ?>
<?php foreach ($samplepapers['files']['guidepapers'] as $gKey => $gValue) { ?>
        <script>
        uploadedGuideFiles[<?=$gKey?>] = {'file_url' : '<?php echo $gValue['file_url'];?>','thumbnail_url' : '<?php echo $gValue['thumbnail_url'];?>','file_name' : '<?php echo $gValue['file_name'];?>','file_relative_url' : '<?php echo $gValue['file_relative_url'];?>','position':<?=$gValue['position']?>};
        //updateSortOptions('guidelist');
        </script>
<?php } ?>

<script>
if(uploadedSampleFiles.length>0){
    updateSortOptions('paperslist');
}
if(uploadedGuideFiles.length>0){
    updateSortOptions('guidelist');
}
</script>
