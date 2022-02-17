<div class="clear-width dragable-block" id="exam_homepage_section">
    <select id="homepage_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['homepage'];?>,'homepage_sort','section')">
            <option value="1" selected="selected">1</option>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite plus-icon"></i>Exam Homepage</h3>
    <div id="homepage_<?=$formName?>" class="cms-form-wrap cms-accordion-div">
        <ul>
            <li>
                <label><span>Summary</span>* :</label>
                <div class="cms-fields">
                    <textarea id= "examInfo" name="homepagedata[]" class="cms-textarea tinymce-textarea" caption="Exam Info" validationType="html" required="true"><?=($homepage['Summary']['wikiData'])?></textarea>
                    
                    <div id="examInfo_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_Summary" value="<?php echo $homepage['Summary']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('examInfo','0','homepage','Summary');return false;" class="button button--secondary">Save</button>
                        <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                        $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'summary'));
                        } ?>
                    </div>
                    <?php } ?>
                </div>
                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_Summary"> Update Date</div><br>
            </li>
            <li>
                <label><span>Eligibility</span> : </label>
                <div class="cms-fields">
                    <textarea id= "eligibility" name="homepagedata[]" class="cms-textarea tinymce-textarea " caption="Eligibility" validationType="html"><?=($homepage['Eligibility']['wikiData'])?></textarea>
                <div id="eligibility_error" class="errorMsg" style="display:none;"></div>
                <input type="hidden" id="prevUpdOn_Eligibility" value="<?php echo $homepage['Eligibility']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                        <div class="belowEdit-btnWrapper">
                            <button onclick="saveWikiData('eligibility','1','homepage','Eligibility');return false;" class="button button--secondary">Save</button>
                            <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                                $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'eligibility'));
                            } ?>
                        </div>
                    <?php } ?>
                </div>
               <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_Eligibility"> Update Date</div><br>
            </li>
            <li>
                <label><span>Process</span> : </label>
                <div class="cms-fields">
                    <textarea id="process" name="homepagedata[]" class="cms-textarea tinymce-textarea" caption="Process" validationType="html"><?=($homepage['Process']['wikiData'])?></textarea>
                    <div id="process_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_Process" value="<?php echo $homepage['Process']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('process','2','homepage','Process');return false;" class="button button--secondary">Save</button>
                        <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                                $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'process'));
                        } ?>                        
                    </div>
                    <?php } ?>
                </div>
                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_Process"> Update Date</div><br>
            </li>
            <li>
                <label><span>Exam Centers</span> : </label>
                <div class="cms-fields">
                    <textarea id="examCenters" name="homepagedata[]" class="cms-textarea tinymce-textarea" caption="Exam Centers" validationType="html"><?=($homepage['Exam Centers']['wikiData'])?></textarea>
                    <div id="examCenters_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_ExamCenters" value="<?php echo $homepage['Exam Centers']['updatedOn'];?>">
                     <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('examCenters','3','homepage','Exam Centers');return false;" class="button button--secondary">Save</button>
                        <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                        $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'examcenters'));
                        } ?>
                    </div>
                    <?php } ?>
                </div>
                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_ExamCenters"> Update Date</div><br>
            </li>
            <li>
                <label><span>Exam Analysis</span> : </label>
                <div class="cms-fields">
                    <textarea id= "examAnalysis" name="homepagedata[]" class="cms-textarea tinymce-textarea" caption="Exam Analysis" validationType="html"><?=($homepage['Exam Analysis']['wikiData'])?></textarea>
                    <div id="examAnalysis_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_ExamAnalysis" value="<?php echo $homepage['Exam Analysis']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('examAnalysis','4','homepage','Exam Analysis');return false;" class="button button--secondary">Save</button>
                    <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                        $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'examanalysis'));
                    } ?>
                    <?php } ?>                    
                    </div>
                </div>
                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_ExamAnalysis"> Update Date</div><br>
            </li>
            <li>
                <label><span>Student Reaction</span> : </label>
                <div class="cms-fields">
                    <textarea id="studentReaction" name="homepagedata[]" class="cms-textarea tinymce-textarea" caption="Student Reaction" validationType="html"><?=($homepage['Student Reaction']['wikiData'])?></textarea>
                    <div id="studentReaction_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_StudentReaction" value="<?php echo $homepage['Student Reaction']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('studentReaction','5','homepage','Student Reaction');return false;" class="button button--secondary">Save</button>
                        <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                                $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'studentreaction'));
                        } ?>                         
                    </div>
                    <?php } ?>                   
                </div>
                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_StudentReaction"> Update Date</div><br>
            </li>
            <li>
                <label><span>Official Website</span> : </label>
                <div class="cms-fields">
                    <input id="officialWebsite" name="homepagedata[]" class="universal-txt-field cms-text-field form-control ng-pristine ng-invalid ng-touched" type="text" style="98% !important;" validationType="reglink" caption="Official Website" onkeyup="showErrorMessage(this, '<?=$formName?>');" value="<?=html_escape($homepage['Official Website']['wikiData'])?>" />
                    <div id="officialWebsite_error" class="errorMsg" style="display: none;"></div>
                </div>
            </li>
            <li>
                <label><span>Phone Number</span> : </label>
                <div class="cms-fields">
                    <input id="phone_number" name="homepagedata[]" class="universal-txt-field cms-text-field form-control ng-pristine ng-invalid ng-touched" type="text" style="98% !important;" validationType="phone_number" caption="should not exceed character limit of 20" maxlength="20" onkeyup="showErrorMessage(this, '<?=$formName?>');" value="<?=html_escape($homepage['Phone Number']['wikiData'])?>" />
                    <div id="phone_number_error" class="errorMsg" style="display: none;"></div>
                </div>
            </li>
            <li>
                <label><span>Contact Information</span> : </label>
                <div class="cms-fields">
                    <textarea id="contactInformation" name="homepagedata[]" class="cms-textarea tinymce-textarea" caption="Contact Information" validationType="html" ><?=($homepage['Contact Information']['wikiData'])?></textarea>
                    <div id="contactInformation_error" class="errorMsg" style="display: none;"></div>
                    <input type="hidden" id="prevUpdOn_ContactInformation" value="<?php echo $homepage['Contact Information']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('contactInformation','8','homepage','Contact Information');return false;" class="button button--secondary">Save</button>
                        <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                                $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'contactinfo'));
                        } ?>                                            
                    </div>
                    <?php } ?>
                </div>
                
                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_ContactInformation"> Update Date</div><br>
            </li>
             <?php
                $customElementCount = 1;
                $homePageLabels = array_keys($homepage);
                $key = array_search("Contact Information",$homePageLabels);
                $slicedHomePageData = array_slice($homepage, $key+1, NULL, true);

                $fixedLabels = array('Summary','Eligibility','Process','Exam Centers','Exam Analysis','Student Reaction','Official Website','Phone Number','Contact Information');
                foreach($slicedHomePageData as $customKey => $customValue)
                {
                    if(in_array($customKey, $fixedLabels))
                        break;
                    else
                    {
                ?>
                <li class="custom-wiki homepage-custom-wiki-section2">
                <div class="add-more-sec2 clear-width">
                    <ul>
                        <li>
                            <label>Label : </label>
                            <div class="cms-fields">
                            <input class="universal-txt-field cms-text-field custom-label-field" type="text" style="98% !important;" name="customLabel[]" value="<?=$customKey?>" maxlength="255"/>
                            </div>
                        </li>
                        <li>
                            <label>Wiki Data :</label>
                            <div class="cms-fields">
                            <textarea class="cms-textarea tinymce-textarea custom-wiki-data" id="customWikiData2_<?=$customElementCount?>" name="homepagedata[]" caption="this field" validationType="html"><?=$customValue['wikiData']?></textarea>
                            <div id="customWikiData2_<?=$customElementCount++?>_error" class="errorMsg" style="display:none;"></div>
                            <a href="javascript:void(0);" style= "margin:10px 0 0 0;" class="remove-link-2" onclick="removeHomePageWiki(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove</a>
                            </div>
                        </li>
                    </ul>
                </div>
                            </li>
                <?php
                    }
                }
                 ?>
                
                <li>
                                <a href="javascript:void(0);" id="addMoreSectionLink_2" class="add-more-link last-in-section" onclick="addMoreHomePageSection(this, 2);" style="margin-bottom:0;">[+] Add More</a>
                </li>
                <li><input type="hidden" class="homepageaddmore" value="<?php echo count($fixedLabels)+$counter-1;?>"></li>
                <li class="homepage-custom-wiki custom-wiki" style="display:none;">
                    <div class="add-more-sec2 clear-width">
                        <ul>
                            <li>
                                <label>Label : </label>
                                <div class="cms-fields">
                                  <input class="universal-txt-field cms-text-field custom-label-field" type="text" style="98% !important;" maxlength="255" onblur="isDuplicateLabels('<?=$formName;?>')" />
                                  <div id="homecustom_error" class="errorMsg" style="display:none;"></div>
                                </div>
                             </li>
                            <li>
                                <label>Wiki Data :</label>
                                <div class="cms-fields">
                                    <textarea class="cms-textarea tinymce-textarea custom-wiki-data" caption="this field" validationType="html"></textarea>
                                    <div id="" class="errorMsg" style="display: none;"></div>
                                    <a href="javascript:void(0);" style= "margin:10px 0 0 0;" class="remove-link-2" onclick="removeHomePageWiki(this);"><i class="abroad-cms-sprite remove-icon"></i>Remove</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
        </ul>
    </div>
</div>
<div id="layer-bg" class="layer-bg" style="display:none"></div>
