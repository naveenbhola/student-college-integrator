<div class="clear-width dragable-block" id="exam_importantdates_section">
	<select id="importantdates_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['importantdates'];?>,'importantdates_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
        		$value = '';
            if($sectionOrder['importantdates'] == $i) {
                $value = 'selected';
                }
        	?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
	<h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Important Dates</h3>
	<div id="importantdates_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <p style="color:red;padding: 0px 0px 12px;clear: both;shiksha/pwa/application/server/index.js">For any event (such as application window, counselling window, correction window) that is NOT a one-day-event (that is, has a start date and an end date that are different from each other) please ensure that you create only one event with different start date and end date.</p>

        <ul>
			<li>    
			<label>Introduction : </label>
	            <div class="cms-fields pattern-div">
                    <textarea id="upperWiki" name="importantdatespagedata" class="cms-textarea tinymce-textarea" caption="Important" validationtype="html"><?=$importantdates['upperWiki']['wikiData']?></textarea>
                    <div id="important_error" class="errorMsg" style="display:none;"></div>
                    <?php if($actionType == 'edit'){ ?>
	                    <div class="belowEdit-btnWrapper">
	                        <button onclick="saveWikiData('upperWiki','0','importantdates','upperWiki');return false;" class="button button--secondary">Save</button>
	                    </div>
	                <?php } ?>   
	            </div>
        	</li>
		</ul>

		<ul class="importantDateSection">
		<?php if($actionType == 'edit') { 
			$count = 1;
			foreach($importantdates['dates'] as $importantDateData) {
						if($importantDateData['eventCategory']=='')
							$importantDateData['eventCategory'] = 11;?>
		                <li class="importantDateElement">
						<div class="add-more-sec2 clear-width">
						<ul>
							<li>
								<ul>

									<li>
										<label>Event Category : </label>
										 <div class="cms-fields">
											<select id="importantdatesSelect">
											<?php 
											//_p($events);6
											foreach($events as $k=>$v)
											{ ?>
												<option value="<?php echo $k;?>" <?php if($importantDateData['eventCategory']==$k){ ?> selected="selected" <?php } ?> ><?php echo $v;?></option>
											<?php }
											?>
											</select>
										</div>
									</li>
                                    <li>
                                            <label>Start Date : </label>
                                             <div class="cms-fields">
                                                    <input class="universal-txt-field cms-text-field start-date" type="text" id="importantDateStart_<?=$count ?>" value = "<?=$importantDateData['start_date']?>" name="importantDateStart[]"  caption="Start date" <?=$required?> validationType = "html" readonly  onchange="checkForToDate(this);" />
                                                    <i class="abroad-cms-sprite calendar-icon" id="importantDateStartIcon_<?=$count ?>" style="cursor:pointer" onclick="pickStartDate(this);"></i>
                                                    <a href="javascript:void(0);" style= "display:<?=$count>1 ? "inline" : "none" ?>;" class="remove-link-2" onclick="removeImportantDatesSection(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove</a>
                                            </div>
                                    </li>

									<li>
										<label>End Date : </label>
										 <div class="cms-fields">
											<input class="universal-txt-field cms-text-field end-date" type="text" id="importantDateEnd_<?=$count ?>" name="importantDateEnd[]" value = "<?=$importantDateData['end_date']?>"  <?=$required?> validationType = "html" readonly  />
											<i class="abroad-cms-sprite calendar-icon" id="importantDateEndIcon_<?=$count?>" style="cursor:pointer" onclick="pickEndDate(this);"></i>
										</div>
									</li>
									<li>
										<label>Event Name : </label>
										 <div class="cms-fields">
											<input class="universal-txt-field cms-text-field" name="eventName[]" value = "<?=html_escape($importantDateData['event_name'])?>" type="text" maxlength="255"/>
										</div>
									</li>
									<li>
										<label>Article Id : </label>
										 <div class="cms-fields">
											<input class="universal-txt-field cms-text-field" name="articleId[]" value = "<?php echo !empty($importantDateData['article_id']) ? $importantDateData['article_id'] : ''?>" type="number" maxlength="255"/>
										</div> 
									</li>
								</ul>
							</li>
						</ul>
					</div>
		                        </li>
		                   
		                   
		<?php $count++;}} if((($actionType == 'edit') && count($importantdates['dates'])  < 1) || ($actionType != 'edit') ) {?>
		    <li class="importantDateElement">
				<div class="add-more-sec2 clear-width">
					<ul>
						<li>
							<ul>
								<li>
									<label>Event Category : </label>
										 <div class="cms-fields">
											<select id="importantdatesSelect">
											<?php 
											//_p($events);6
											foreach($events as $k=>$v)
											{ ?>
												<option value="<?php echo $k;?>" <?php if($k==11){ ?> selected="selected" <?php } ?> ><?php echo $v;?></option>
											<?php }
											?>
											</select>
										</div>
									</li>
								<li>
									<label>Start Date : </label>
									 <div class="cms-fields">
										<input class="universal-txt-field cms-text-field start-date" type="text" id="importantDateStart_1" name="importantDateStart[]"  caption="Start date" <?=$required?> validationType = "html" readonly  onchange="checkForToDate(this);"/>
										<i class="abroad-cms-sprite calendar-icon" id="importantDateStartIcon_1" style="cursor:pointer" onclick="pickStartDate(this);"></i>
										<a href="javascript:void(0);" style= "display:none;" class="remove-link-2" onclick="removeImportantDatesSection(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove</a> 
									</div>
								</li>
								<li>
									<label>End Date : </label>
									 <div class="cms-fields">
										<input class="universal-txt-field cms-text-field end-date" type="text" id="importantDateEnd_1" name="importantDateEnd[]" caption="Start date" <?=$required?> validationType = "html" readonly  />
										<i class="abroad-cms-sprite calendar-icon" id="importantDateEndIcon_1" style="cursor:pointer" onclick="pickEndDate(this);"></i>
									</div>
								</li>
								<li>
									<label>Event Name : </label>
									 <div class="cms-fields">
										<input class="universal-txt-field cms-text-field" name="eventName[]" type="text" maxlength="255"/>
									</div>
								</li>
								<li>
									<label>Article Id : </label>
									 <div class="cms-fields">
										<input class="universal-txt-field cms-text-field" name="articleId[]" type="number" maxlength="255"/>
									</div> 
								</li>
							</ul>
						</li>
					</ul>
				</div>
		    </li>
		    <?php }?>
		</ul>
		<a href="javascript:void(0);" id="addMoreImportantDateLink" class="add-more-link last-in-section" style="margin-bottom:0;" onclick="addMoreImportantDates(this);">[+] Add More Dates</a>
		<ul>
			  <li>    
	            <div class="cms-fields pattern-div">
	                    <textarea id="important" name="importantdatespagedata" class="cms-textarea tinymce-textarea" caption="Important" validationtype="html"><?=$importantdates['importantdates']['wikiData']?></textarea>
	                    <div id="important_error" class="errorMsg" style="display:none;"></div>
	                    <input type="hidden" id="prevUpdOn_importantdates" value="<?php echo (count($importantdates['dates'])>0) ? $importantdates['dates'][0]['updatedOn'] : $importantdates['importantdates']['updatedOn'];?>">
	                    <?php if($actionType == 'edit'){ ?>
	                    <div class="belowEdit-btnWrapper">
	                        <button onclick="saveWikiData('important','1','importantdates','important');return false;" class="button button--secondary">Save</button>
						<?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
				                        $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'importantdates'));
				         } ?>	                        
	                    </div>
	                <?php } ?>
	            </div>
				<div style="width:120px;margin-left: 245px;top: 30px;position: relative;"><input type="checkbox" value="on" id="updatedOn_importantdates"> Update Date</div>	            
        	</li>
		</ul>	
    </div>
</div>
