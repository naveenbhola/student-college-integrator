<?php
$showFilters = false;

// determine whether to show filters or not
if($feeRangesForFilter|| $examsForFilter || $examsScoreForFilter || $specializationForFilters || $moreOptionsForFilters || (($categoryPageRequest->isAllCountryPage() && $countriesForFilters) || ($citiesForFilters || $statesForFilters)))
{
	// get user applied filters for all filter categories
	$examPreSelectedFilters 			= $appliedFilters["exam"];
	$examsScorePreSelectedFilters 		= $appliedFilters["examsScore"];
	$examsMinScorePreSelectedFilters	= $appliedFilters["examsMinScore"];
	$feesPreSelectedFilters 			= $appliedFilters["fees"];
	$specializationPreSelectedFilters 	= $appliedFilters["specialization"];
	$moreOptionsPreSelectedFilters 		= $appliedFilters["moreoption"];
	if($categoryPageRequest->checkIfCertDiplomaCountryCatPage())
	{
		$key = array_search('DEGREE_COURSE',$moreOptionsPreSelectedFilters);
		unset($moreOptionsPreSelectedFilters[$key]);
	}
	$countryPreSelectedFilters 			= $appliedFilters["country"];
	$statePreSelectedFilters 			= $appliedFilters["state"];
	$cityPreSelectedFilters 			= $appliedFilters["city"];
	$showFilters = true;
}
//if user has applied the some filters and we need to show the filters
if($showFilters)
{
	$widthlimit = '';
	if($snapshotOnlyPage == TRUE)
	{
		$widthlimit = 'style="width:615px;"';
	}
	//This logic tells the page that this page ONLY has snapshot courses and there is no other MORE OPTIONS filter other than scholarship.
	//In this case, don't show the filter at all.
	$showScholashipFilterLogic = true;
	if($showScholarshipFilter == 0 && in_array("OFR_SCHLSHP",array_keys($moreOptionsForFilters) ) && sizeof($moreOptionsForFilters) == 1){
		$showScholashipFilterLogic = false;
	}
	$showScholashipFilterOption = true;
	if($showScholarshipFilter == 0 && in_array("OFR_SCHLSHP",array_keys($moreOptionsForFilters) ) && sizeof($moreOptionsForFilters) > 1){
		$showScholashipFilterOption = false;
	}

	if($showScholashipFilterLogic == false && $snapshotOnlyPage == TRUE){
		$widthlimit = 'style="width:412px;"';
	}
	$examsScorePreSelectedDetails = explode('--',$examsScorePreSelectedFilters[0]);
	$examsMinScorePreSelectedDetails = explode("--",$examsMinScorePreSelectedFilters[0]);

	if(array_search($examsScorePreSelectedDetails[1], $userAppliedExamsScoreForFilters[$examsScorePreSelectedDetails[2]]) === false)
	{
		array_push($userAppliedExamsScoreForFilters[$examsScorePreSelectedDetails[2]],$examsScorePreSelectedDetails[1]);
		arsort($userAppliedExamsScoreForFilters[$examsScorePreSelectedDetails[2]]);
	}
	?>
<div id="filterBox" class="refine-box" <?php echo $widthlimit; ?>>
	<div class="refine-head clearwidth">
		<!--<h2 class="flLt">Refine Institutes By</h2>-->
		<h2 class="flLt"> Use below filters to refine the list of universities & colleges</h2>
		<div class="reset-refine font-12 flRt">
			<a href="javascript:void(0);" class="mL10" onclick="studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'resetAllFilters'); resetAllFilters();"><i class="cate-sprite reset-icon"></i>Reset all filters</a>
		</div>
	</div>

	<div class="refine-container clearwidth">
		<div class="refine-by-title clearfix">
			<?php
			if($examsForFilter) { ?>
				<div class="refine-col flLt">
					<strong>Exam Accepted</strong>
				</div>
			<?php } ?>
			<?php if($categoryPageRequest->isExamCategoryPage()) {// in exam category page country comes after exam ?>
				<div class="refine-col flLt" style="width: 150px">
					<strong>Countries</strong>
				</div>
			<?php } ?>
			<?php if($feeRangesForFilter) { ?>
				<div class="refine-col flLt">
					<strong>1st Year Total Fees (Rs)</strong>
				</div>
			<?php }
			if($specializationForFilters) { ?>
				<div class="refine-col flLt">
					<strong>Course Specialisation</strong>
				</div>
			<?php }
			if($moreOptionsForFilters && $showScholashipFilterLogic) { ?>
				<div class="refine-col flLt">
					<strong>More options</strong>
				</div>
			<?php }
			if(
			   !$categoryPageRequest->isExamCategoryPage() &&
			   (($categoryPageRequest->isAllCountryPage() && $countriesForFilters) || ($citiesForFilters || $statesForFilters))
			   ) { ?>
				<div class="refine-col flLt" style="width: 150px">
					<?php if($categoryPageRequest->isAllCountryPage() && $countriesForFilters) {
						$text = 'Countries';
					} else {
						$text = 'Locations';
					} ?>
					<strong><?php echo $text; ?></strong>
				</div>
			<?php } ?>
		</div>
		<form id="formCategoryPageFilter">
			<div class="refine-details customInputs clearwidth">

				<?php if($examsForFilter) { ?>
					<!-- Exam Filter -->
					<div class="refine-col flLt">
						<div class="scrollbar1" id="examFilterScrollbar">
							<div class="scrollbar">
								<div class="track">
									<div class="thumb">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport" style="height: 89px;">
								<div class="overview" style="height: 89px;">
									<?php if(!$categoryPageRequest->isExamCategoryPage()) { ?>
										<ul class="refine-list" style="width:100%;">
											<?php $key = 0;
											foreach($examsForFilter as $examid=>$exam) {
												if(trim($exam)) {
													$checked = in_array($examid, $examPreSelectedFilters) ? "checked" : "";
													$disabled = in_array($exam, $userAppliedExamsForFilters) ? "" : "disabled";
													// hide all other exams from exam category page
												?>
													<li class="labelInputs <?php echo $checked == "checked" ? "active": ""; ?>">
														<input type="checkbox" name="exam[]" id="exam-<?php echo $key; ?>" value="<?php echo $examid; ?>" <?php echo $checked; ?> autocomplete="off" <?php echo $disabled; ?>>
														<label for="exam-<?php echo $key; ?>" class="exam-col-chkbx flLt">
															<span class="common-sprite"></span><p style="margin-left:5px\9;"><?php echo $exam; ?></p>
														</label>
														<?php
														if(($naKey = array_search('N/A', $userAppliedExamsScoreForFilters[$examid])) !== false)
														{
															unset($userAppliedExamsScoreForFilters[$examid][$naKey]);
														}
														if(($naKey = array_search('-1', $userAppliedExamsScoreForFilters[$examid])) !== false)
														{
															unset($userAppliedExamsScoreForFilters[$examid][$naKey]);
														}
														if(count($userAppliedExamsScoreForFilters[$examid]) >0)
														{
														?>
														<div class="filter-dropdown flLt" style="<?php echo $checked == "checked" ? "": "display:none;"; ?>" >
														<select class="score-select-field" name="examsScore[]" autocomplete="off" <?php echo $disabled; ?>>
															<option value="">Select score</option>
															<?php
															foreach($userAppliedExamsScoreForFilters[$examid] as $scoreKey=>$val)
															{
																$scoreSelected = '';
																$formattedValue =$exam."--".floatval($val)."--".$examid;
																if(array_search($formattedValue,$examsScorePreSelectedFilters)!==false && array_search($formattedValue,$examsScorePreSelectedFilters)!==null)
																{
																	$scoreSelected = 'selected="selected"';
																}	?>
															<option value="<?php echo $formattedValue;?>" <?php echo $scoreSelected;?> ><?php echo $val;?> & below</option>
															<?php } ?>
														</select>
														</div>
														<?php } ?>
													</li>
												<?php }
											$key++; } ?>
										</ul>
									<?php }else{ ?>
										<?php
											$examid = reset($examPreSelectedFilters);
											$selectedExam = $examsForFilter[$examid];
											$userAppliedExamsScoreForFilters = $userAppliedExamsScoreForFilters[$examid];
											$userAppliedExamsMinScoreForFilters = $userAppliedExamsMinScoreForFilters[$examid];
											if($selectedExam== "IELTS")
											{
												$userSelectedExamMinScore = (float)$examsMinScorePreSelectedDetails[1];
												$userSelectedExamMaxScore = (float)$examsScorePreSelectedDetails[1];
											}else{
												$userSelectedExamMinScore = (integer)$examsMinScorePreSelectedDetails[1];
												$userSelectedExamMaxScore = (integer)$examsScorePreSelectedDetails[1];
											}
											if(($naKey = array_search('N/A',$userAppliedExamsScoreForFilters)) !== false){
												unset($userAppliedExamsScoreForFilters[$naKey]);
											}
										?>
										<ul class="refine-list exam-cpList" style="width:96%;">
											<li style="padding:0 !important;"><strong class="Exam-Title2"><?php echo $selectedExam; ?> Exam</strong></li>
											<li class="labelInputs active">
												<input type="checkbox" name="exam[]" id="exam-0" value="" autocomplete="off" checked="checked"/>
												<label class="exam-col-chkbx flLt">
													<p class="exam-cpName">Min Score</p>
												</label>
												<div class="filter-dropdown flLt" style="" >
													<select class="score-select-field" name="examsMinScore[]" autocomplete="off">
														<option value="">Any score</option>
														<?php
															foreach($userAppliedExamsMinScoreForFilters as $scoreKey=>$val)
															{
																if($val =="N/A"||$val=="" || $val=="-1"){
																	continue;
																}
																$scoreSelected = '';
																$formattedValue =$selectedExam."--".$val."--".$examid;
																if($val == $userSelectedExamMinScore)
																{
																	$scoreSelected = 'selected="selected"';
																	$minScoreSelectFilled = true;
																}
																$skipMinScoreStr = '';
																if($userSelectedExamMaxScore!=0)
																{
																	$skipMinScoreStr = ($val > $userSelectedExamMaxScore?'style="display:none;"':'');
																}
														?>
															<option value="<?php echo $formattedValue;?>" <?php echo $scoreSelected;?> <?php echo $skipMinScoreStr; ?>><?php echo $val;?></option>
														<?php } ?>
														<?php if(count($resultantUniversityObjects) == 0 && !$categoryPageRequest->isSolrFilterAjaxCall()){ ?>
															<option value="<?php echo $selectedExam."--".$categoryPageRequest->examScore[0]."--".$examid; ?>" selected="selected"><?php echo $categoryPageRequest->examScore[0]; ?></option>
														<?php } ?>
														<?php if($this->input->is_ajax_request() && $minScoreSelectFilled == false && $userSelectedExamMinScore!= 0){ $minScoreSelectFilled = true;?>
															<option value="<?php echo $selectedExam."--".$userSelectedExamMinScore."--".$examid?>" selected="selected"><?php echo $userSelectedExamMinScore ?></option>
														<?php } ?>
													</select>
												</div>
											</li>

											<li class="labelInputs active">
												<label class="exam-col-chkbx flLt">
													<p class="exam-cpName">Max Score</p>
												</label>
												<div class="filter-dropdown flLt" style="" >
													<select class="score-select-field" name="examsScore[]" autocomplete="off">
														<option value="">Any score</option>
														<?php
															foreach($userAppliedExamsScoreForFilters as $scoreKey=>$val)
															{
																if($val =="N/A"||$val==""|| $val=="-1"){
																	continue;
																}
																$scoreSelected = '';
																$formattedValue =$selectedExam."--".$val."--".$examid;
																if(array_search($formattedValue,$examsScorePreSelectedFilters)!==false && array_search($formattedValue,$examsScorePreSelectedFilters)!==null)
																{
																	$scoreSelected = 'selected="selected"';
																	$maxScoreSelectFilled = true;
																}
																$skipMaxScoreStr = '';
																if($userSelectedExamMinScore!=0)
																{
																		$skipMaxScoreStr = ($val < $userSelectedExamMinScore?'style="display:none;"':'');
																}
														?>
															<option value="<?php echo $formattedValue;?>" <?php echo $scoreSelected;?> <?php echo $skipMaxScoreStr; ?> ><?php echo $val;?></option>
														<?php } ?>
														<?php if(count($resultantUniversityObjects) == 0 && !$categoryPageRequest->isSolrFilterAjaxCall()){ ?>
															<option value="<?php echo $selectedExam."--".$categoryPageRequest->examScore[1]."--".$examid; ?>" selected="selected"><?php echo $categoryPageRequest->examScore[1]; ?></option>
														<?php } ?>
														<?php if($this->input->is_ajax_request() && $maxScoreSelectFilled == false && $userSelectedExamMaxScore!= 0){ $maxScoreSelectFilled = true;?>
															<option value="<?php echo $selectedExam."--".$userSelectedExamMaxScore."--".$examid; ?>" selected="selected"><?php echo $userSelectedExamMaxScore; ?></option>
														<?php } ?>
													</select>
												</div>
											</li>
										</ul>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php if($categoryPageRequest->isExamCategoryPage()) { ?>
					<!-- Location Filter -->
					<div class="refine-col flLt" style="width: 150px">
						<div class="scrollbar1" id="locationFilterScrollbar">
							<div class="scrollbar">
								<div class="track">
									<div class="thumb">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport" style="height: 89px;">
								<div class="overview" style="height: 89px;">
									<ul class="refine-list">
										<?php if($categoryPageRequest->isAllCountryPage())  // allcountries category page or abroad category page
										{		$key = 0;
												foreach($countriesForFilters as $index=>$country) {
												$checked = in_array($index, $countryPreSelectedFilters) ? "checked" : "";
												$disabled = array_key_exists($index, $userAppliedCountryForFilters) ? "" : "disabled";
												if($country) { ?>
													<li onclick="checkboxOnclickAction(this);">
														<input type="checkbox" name="countryList[]" id="country-<?php echo $key; ?>" value="<?php echo $index; ?>" <?php echo $disabled; ?> <?php echo $checked; ?> autocomplete="off">
														<label for="country-<?php echo $key; ?>">
															<span class="common-sprite"></span>
															<p>
																<?php echo $country;?>
																<!--<a href="Javascript:void(0);" onclick="<?php echo ($disabled)?'return false;':''?>" style="<?php echo ($disabled)?'color:#C0C0C0;text-decoration:none;cursor:default;':''?>"><?php echo $country?></a>-->
															</p>
														</label>
													</li>
												<?php }
											$key++; }
										}
										?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php if($feeRangesForFilter) { ?>
					<!-- Fees Filter -->
					<div class="refine-col flLt">
						<div class="scrollbar1" id="feesFilterScrollbar">
							<div class="scrollbar">
								<div class="track">
									<div class="thumb">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport" style="height: 89px;">
								<div class="overview" style="height: 89px;">
									<ul class="refine-list">
										<?php $key = 0;
									foreach($feeRangesForFilter as $feeskey=>$fees) {
											if(trim($fees)) {
												//$checked = "checked";
												$checked = in_array($feeskey, $feesPreSelectedFilters) ? "checked" : "";
												//if(empty($feesPreSelectedFilters))
												//	$checked = "checked";

												$disabled = in_array($fees, $userAppliedFeeRangesForFilter) ? "" : "disabled";
											?>
												<li class="<?php echo $checked == "checked" ? "active": ""; ?>">
												<input type="radio" name="fee[]" id="fee-<?php echo $key; ?>" value="<?php echo $feeskey; ?>" <?php echo $checked; ?> <?php echo $fees=="Any fees"?"checked":""; ?> autocomplete="off" <?php echo $disabled; ?>>
													<label for="fee-<?php echo $key; ?>">
														<span class="common-sprite"></span><p><?php echo str_replace("Lacs","Lakhs",$fees); ?></p>
													</label>
												</li>
											<?php }
										$key++; } //class="active" checked="checked" ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php if($specializationForFilters) { ?>
					<!-- Specialization Filter -->
					<div class="refine-col flLt">
						<div class="scrollbar1" id="specializationFilterScrollbar">
							<div class="scrollbar">
								<div class="track">
									<div class="thumb">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport" style="height: 89px;">
								<div class="overview" style="height: 89px;">
									<ul class="refine-list">
										<?php $key = 0;
										foreach($specializationForFilters as $index=>$specialization) {
											if(trim($specialization)) {
												$checked = in_array($index, $specializationPreSelectedFilters)? "checked" : "";
												$disabled = in_array($specialization, $userAppliedSpecializationForFilters) ? "" : "disabled";
											?>
												<li class="<?php echo $checked == "checked" ? "active": ""; ?>">
													<input type="checkbox" name="course[]" id="operation-<?php echo $key; ?>" value="<?php echo $index; ?>"<?php echo $checked; ?> autocomplete="off" <?php echo $disabled; ?>>
													<label for="operation-<?php echo $key; ?>">
														<span class="common-sprite"></span><p><?php echo $specialization; ?></p>
													</label>
												</li>
											<?php }
										$key++; } // (li)class="active" (input)checked="checked" ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if($moreOptionsForFilters && $showScholashipFilterLogic) { ?>
					<!-- More Options Filter -->
					<div class="refine-col flLt <?php echo ($categoryPageRequest->isExamCategoryPage()?'last':''); ?>">
						<div class="scrollbar1" id="moreOptionsFilterScrollbar">
							<div class="scrollbar">
								<div class="track">
									<div class="thumb">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport" style="height: 89px;">
								<div class="overview" style="height: 89px;">
									<ul class="refine-list">
										<?php $key = 0;
										foreach($moreOptionsForFilters as $moreoptionKey=>$moreOptions) {
											if(($snapshotOnlyPage == TRUE || $showScholashipFilterOption == false) && $moreOptions == "Offering Scholarship"){
												continue;
											}
											$checked = in_array($moreoptionKey, $moreOptionsPreSelectedFilters)? "checked" : "";
											$disabled = in_array($moreOptions, $userAppliedMoreOptionsForFilters) ? "" : "disabled";
											if(trim($moreOptions)) {?>
												<li class="<?php echo $checked == "checked" ? "active": ""; ?>">
													<input type="checkbox" name="moreopt[]" id="scholarship-<?php echo $key; ?>" value="<?php echo $moreoptionKey; ?>" <?php echo $checked; ?> autocomplete="off" <?php echo $disabled; ?>>
													<label for="scholarship-<?php echo $key; ?>">
														<span class="common-sprite"></span><p><?php echo $moreOptions; ?></p>
													</label>
												</li>
											<?php }
										$key++; } // (li)class="active" (input)checked="checked" ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php if(
								!$categoryPageRequest->isExamCategoryPage() &&
								(($categoryPageRequest->isAllCountryPage() && $countriesForFilters) || ($citiesForFilters || $statesForFilters))
							) { ?>
					<!-- Location Filter -->
					<div class="refine-col flLt last" style="width: 150px">
						<!--<div class="refine-search">
							<i class="cate-sprite refine-search-icon"></i>
							<input type="text" />
						</div>-->
						<div class="scrollbar1" id="locationFilterScrollbar">
							<div class="scrollbar">
								<div class="track">
									<div class="thumb">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport" style="height: 89px;">
								<div class="overview" style="height: 89px;">
									<ul class="refine-list">
										<?php if($categoryPageRequest->isAllCountryPage())  // allcountries category page or abroad category page
										{		$key = 0;
												foreach($countriesForFilters as $index=>$country) {
												$checked = in_array($index, $countryPreSelectedFilters) ? "checked" : "";
												$disabled = array_key_exists($index, $userAppliedCountryForFilters) ? "" : "disabled";
												if($country) { ?>
													<li onclick="checkboxOnclickAction(this);">
														<input type="checkbox" name="countryList[]" id="country-<?php echo $key; ?>" value="<?php echo $index; ?>" <?php echo $disabled; ?> <?php echo $checked; ?> autocomplete="off">
														<label for="country-<?php echo $key; ?>">
															<span class="common-sprite"></span>
															<p>
																<a href="<?php echo $countryUrl[$index]; ?>" target="_blank" onclick="<?php echo ($disabled)?'return false;':''; ?>" style="<?php echo ($disabled)?'color:#C0C0C0;text-decoration:none;cursor:default;':''; ?>"><?php echo $country; ?></a>
															</p>
														</label>
													</li>
												<?php }
											$key++; }
										}
										else  // country catgeory pages
										{	// show enabled state list
											$key = 0;
											foreach($statesForFilters as $index=>$state)
											{
												$checked = in_array($index, $statePreSelectedFilters) ? "checked" : "";
												$disabled = array_key_exists($index, $userAppliedStateForFilters) ? "" : "disabled";
												if(trim($state) && array_key_exists($index, $userAppliedStateForFilters)) { ?>
													<li onclick="checkboxOnclickAction(this);">
														<input type="checkbox" name="stateList[]" id="state-<?php echo $key; ?>" value="<?php echo $index; ?>" <?php echo $checked; ?> <?php echo $disabled; ?> autocomplete="off">
														<label for="state-<?php echo $key; ?>">
															<span class="common-sprite"></span><p><?php echo $state; ?></p>
														</label>
													</li>
												<?php }
												$key++;
											}

											// show enabled city list
											$key = 0;
											foreach($citiesForFilters as $index=>$city) {
												$checked = in_array($index, $cityPreSelectedFilters) ? "checked" : "";
												$disabled = array_key_exists($index, $userAppliedCitiesForFilters) ? "" : "disabled";
												if(trim($city) && array_key_exists($index, $userAppliedCitiesForFilters)) { ?>
													<li onclick="checkboxOnclickAction(this);">
														<input type="checkbox" name="cityList[]" id="city-<?php echo $key; ?>" value="<?php echo $index; ?>" <?php echo $checked; ?> <?php echo $disabled; ?> autocomplete="off">
														<label for="city-<?php echo $key; ?>">
															<span class="common-sprite"></span><p><?php echo $city; ?></p>
														</label>
													</li>
												<?php }
											$key++; }

											// show disabled state list
											$key = 0;
											foreach($statesForFilters as $index=>$state) {
												$checked = in_array($index, $statePreSelectedFilters) ? "checked" : "";
												$disabled = array_key_exists($index, $userAppliedStateForFilters) ? "" : "disabled";
												if(trim($state) && !array_key_exists($index, $userAppliedStateForFilters)) { ?>
													<li onclick="checkboxOnclickAction(this);">
														<input type="checkbox" name="stateList[]" id="state-<?php echo $key; ?>" value="<?php echo $index; ?>" <?php echo $checked; ?> <?php echo $disabled; ?> autocomplete="off">
														<label for="state-<?php echo $key; ?>">
															<span class="common-sprite"></span><p><?php echo $state; ?></p>
														</label>
													</li>
												<?php }
											$key++; }

											// show disabled city list
											$key = 0;
											foreach($citiesForFilters as $index=>$city) {
												$checked = in_array($index, $cityPreSelectedFilters) ? "checked" : "";
												$disabled = array_key_exists($index, $userAppliedCitiesForFilters) ? "" : "disabled";
												if(trim($city) && !array_key_exists($index, $userAppliedCitiesForFilters)) { ?>
													<li onclick="checkboxOnclickAction(this);">
														<input type="checkbox" name="cityList[]" id="city-<?php echo $key; ?>" value="<?php echo $index; ?>" <?php echo $checked; ?> <?php echo $disabled; ?> autocomplete="off">
														<label for="city-<?php echo $key; ?>">
															<span class="common-sprite"></span><p><?php echo $city; ?></p>
														</label>
													</li>
												<?php }
											$key++; }
										} ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</form>
		<input type="hidden" id="ajaxurl" name="ajaxurl" value="<?php echo $ajaxurl; ?>" />
		<div class="loader" id="loadingImage" style="position:absolute;display:none;z-index:9999;"><img src="//<?php echo IMGURL;?>/public/images/Loader2.GIF" width="48" height="48"/> Loading</div>
	</div>
</div>
<?php }else{?>
	 	<div id="filterBoxLoader" class="refine-box" style="height: 150px;">
	 	        <div class="refine-head clearwidth">
	 	        <div style="position: relative;top: 0px; left: 0px; height:150px; opacity: 0.7; background: url('//<?php echo IMGURL; ?>/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999;"></div>
	 	        </div>
	 	</div>
<?php   } ?>

<div id="examsScoreMsg" class="abroad-layer" style="display: none;position: absolute; z-index: 9999;width: auto;">
<div class="abroad-layer-content">
	<div class="abroad-layer-title"><span id="examsScoreNameText">IELTS </span></div>


	<p id="examsScoreMsgText" class="exam-score-content"></p>
	<div>
		<input class="button-style font-14 exam-ok-btn" id="okbtn1" type="button" name="OK" value="OK" onclick="removeExamsScoreAndApplyNewExam();">
		<input class="button-style font-14 exam-ok-btn" id="okbtn2" type="button" name="OK" value="OK" onclick="removeOtherExamAndApplyExamsScore();">
		<a href="javaScript:void(0);" onclick="hideExamScoreFilterMsg()">Cancel</a>
	</div>
</div>
</div>
