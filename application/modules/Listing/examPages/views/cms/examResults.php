<div class="clear-width dragable-block" id="exam_results_section">
	<select id="results_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['results'];?>,'results_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
        		$value = '';
            if($sectionOrder['results'] == $i) {
                $value = 'selected';
                }
        	?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Results</h3>
    <div  id="results_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul class="resultSectionList">
			<li>
				<div class="add-more-sec2 clear-width">
					<div class="sel-course-title"><strong>Result Declaration Date</strong></div>
					<ul>
				   		<li>
							<ul>
								<li>
									<label>Start Date : </label>
									<div class="cms-fields">
										<input class="universal-txt-field cms-text-field start-date" type="text" id="resultDeclarationStart_1" name="startDataResultDecSec" <?=$required?> value ="<?=$results['dates'][0]['start_date']?>" validationType = "html" readonly  onchange="checkForToDate(this);" />
										<i class="abroad-cms-sprite calendar-icon" id="resultDeclarationStartIcon_1" style="cursor:pointer" onclick="pickStartDate(this);"></i>
									</div>
								</li>
								<li>
									<label>End Date : </label>
									<div class="cms-fields">
										<input class="universal-txt-field cms-text-field end-date" type="text" id="resultDeclarationEnd_1" name="endDataResultDecSec"  <?=$required?> value ="<?=$results['dates'][0]['end_date']?>" validationType = "html" readonly  />
										<i class="abroad-cms-sprite calendar-icon" id="resultDeclarationEndIcon_1" style="cursor:pointer" onclick="pickEndDate(this);"></i>
									</div>
								</li>
								<li>
									<label>Event Name : </label>
									<div class="cms-fields">
										<input class="universal-txt-field cms-text-field" name="eventNameResultDecSec"  value ="<?=html_escape($results['dates'][0]['event_name'])?>" type="text" maxlength="255"/>
									</div>
								</li>
								<li>
									<label>Article Id : </label>
									<div class="cms-fields">
										<input class="universal-txt-field cms-text-field" name="articleIdResultDecSec" value ="<?php echo !empty($results['dates'][0]['article_id']) ? $results['dates'][0]['article_id'] : '' ?>" type="number" maxlength="255"/>
									</div>
								</li>
							</ul>
				   		</li>
					</ul>
				</div>
			</li>
			<li class="resultWiki">
                <label>Results : </label>

                <div class="cms-fields">
                    <textarea class="cms-textarea tinymce-textarea resultInfo" id="resultInfo" name="resultspagedata" caption="Result" validationtype="html"><?=$results['results']['wikiData'];?></textarea>
    				<div id="resultInfo_error" class="errorMsg" style="display:none;"></div>
    				<input type="hidden" id="prevUpdOn_results" value="<?php echo (count($results['dates'])>0) ? $results['dates'][0]['updatedOn'] : $results['results']['updatedOn'];?>">
    				<?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('resultInfo','0','results','');return false;" class="button button--secondary">Save</button>
		            <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
		                        $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'results'));
		                } ?>                        
                    </div>
                    <?php } ?>
  				 	<!-- <a href="javascript:void(0);" style= "display:none;margin:10px 0 0 0;" class="remove-link-2" onclick="removeTopperInterviewSection(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove</a>  -->
                </div>
				<div style=style="width:120px;margin-left: 245px;top: 30px;position: relative;"><input type="checkbox" value="on" id="updatedOn_results"> Update Date</div>                            
            </li>
        </ul>

    </div>
</div>
