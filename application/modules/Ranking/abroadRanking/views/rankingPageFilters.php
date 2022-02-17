<?php
$showFilters = false;

// determine whether to show filters or not
if($feeRangesForFilter|| $examsForFilter || $specializationForFilters || (($isAllCountryPage==1 && $countriesForFilters) || ($citiesForFilters || $statesForFilters)))
{
	// get user applied filters for all filter categories
	$examPreSelectedFilters 		= $appliedFilters["exam"];
	$feesPreSelectedFilters 		= $appliedFilters["fees"];
	$specializationPreSelectedFilters 	= $appliedFilters["specialization"];
	$countryPreSelectedFilters 		= $appliedFilters["country"];
	$statePreSelectedFilters 		= $appliedFilters["state"];
	$cityPreSelectedFilters 		= $appliedFilters["city"];

	$showFilters = true;
} ?>

<?php if($showFilters) { ?>
<div id="filterBox" class="refine-box">
	<div class="refine-head clearwidth" style="width:764px;">
                <h2 class="flLt">Use the below filters to refine the list of colleges</h2>
		<div class="reset-refine font-14 flRt">
			<a href="javascript:void(0);" class="mL10" onclick="studyAbroadTrackEventByGA('ABROAD_RANKING_PAGE', 'resetAllFilters'); resetAllFilters();"><i class="cate-sprite reset-icon"></i>Reset all filters</a>
		</div>
	</div>

	<div class="refine-container clearwidth" style="width:764px;">
		<div class="refine-by-title clearfix">
			<?php if($feeRangesForFilter) { ?>
				<div class="refine-col flLt">
					<strong>1st Year Total Fees (Rs)</strong>
				</div>
			<?php } ?>

			<?php if($examsForFilter) { ?>
				<div class="refine-col flLt" style="width:150px;">
					<strong>Exam Accepted</strong>
				</div>
			<?php } ?>

			<?php if($specializationForFilters) { ?>
				<div class="refine-col flLt">
					<strong>Course Specialisation</strong>
				</div>
			<?php } ?>


			<?php
                        if(($isAllCountryPage==1 && $countriesForFilters) || ($citiesForFilters || $statesForFilters)) { ?>
                        	<div class="refine-col flLt">
					<?php
                                        if($countriesForFilters && $isAllCountryPage==1) {
						$text = 'Countries';
					} else {
						$text = 'States & Top Cities';
					} ?>
					<strong><?=$text?></strong>
				</div>
			<?php } ?>
		</div>
		<form id="formRankingPageFilter">
			<div class="refine-details customInputs clearwidth">
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
												<li class="<?=$checked == "checked" ? "active": ""?>">
												<input type="radio" name="fee[]" id="fee-<?=$key?>" value="<?=$feeskey?>" <?=$checked?> <?=$fees=="Any fees"?"checked":""?> autocomplete="off" <?=$disabled?>>
													<label for="fee-<?=$key?>">
														<span class="common-sprite"></span><p><?=str_replace("Lac","Lakh",$fees)?></p>
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

				<?php if($examsForFilter) { ?>
					<!-- Exam Filter -->
					<div class="refine-col flLt" style="width: 150px">
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
									<ul class="refine-list">
										<?php $key = 0;
										foreach($examsForFilter as $examid=>$exam) {
											if(trim($exam)) {
												$checked = in_array($examid, $examPreSelectedFilters) ? "checked" : "";
												$disabled = in_array($exam, $userAppliedExamsForFilters) ? "" : "disabled";
											?>
												<li class="<?=$checked == "checked" ? "active": ""?>">
													<input type="checkbox" name="exam[]" id="exam-<?=$key?>" value="<?=$examid?>" <?=$checked?> autocomplete="off" <?=$disabled?>>
													<label for="exam-<?=$key?>">
														<span class="common-sprite"></span><p><?=$exam?></p>
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
												<li class="<?=$checked == "checked" ? "active": ""?>">
													<input type="checkbox" name="course[]" id="operation-<?=$key?>" value="<?=$index?>"<?=$checked?> autocomplete="off" <?=$disabled?>>
													<label for="operation-<?=$key?>">
														<span class="common-sprite"></span><p><?=$specialization?></p>
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


				<?php if(($isAllCountryPage==1 && $countriesForFilters) || ($citiesForFilters || $statesForFilters)) { ?>
					<!-- Location Filter -->
					<div class="refine-col flLt last">
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
										<?php
										if($isAllCountryPage==1)
                                                                                {
											$key = 0;
											foreach($countriesForFilters as $index=>$country) {
												$checked = in_array($index, $countryPreSelectedFilters) ? "checked" : "";
												$disabled = array_key_exists($index, $userAppliedCountryForFilters) ? "" : "disabled";
												if($country) { ?>
													<li>
														<input type="checkbox" name="countryList[]" id="country-<?=$key?>" value="<?=$index?>" <?=$disabled?> <?=$checked?> autocomplete="off">
														<label for="country-<?=$key?>">
															<span class="common-sprite"></span>
															<p>
																<a href="<?=$countryUrl[$index]?>" target="_blank" onclick="<?=($disabled)?'return false;':''?>" style="<?=($disabled)?'color:#C0C0C0;text-decoration:none;cursor:default;':''?>"><?=$country?></a>
															</p>
														</label>
													</li>
												<?php }
											$key++; }
										}
										else {
											// show enabled state list
											$key = 0;
											foreach($statesForFilters as $index=>$state) {
												$checked = in_array($index, $statePreSelectedFilters) ? "checked" : "";
												$disabled = array_key_exists($index, $userAppliedStateForFilters) ? "" : "disabled";
												if(trim($state) && array_key_exists($index, $userAppliedStateForFilters)) { ?>
													<li>
														<input type="checkbox" name="stateList[]" id="state-<?=$key?>" value="<?=$index?>" <?=$checked?> <?=$disabled?> autocomplete="off">
														<label for="state-<?=$key?>">
															<span class="common-sprite"></span><p><?=$state?></p>
														</label>
													</li>
												<?php }
											$key++; }

											// show enabled city list
											$key = 0;
											foreach($citiesForFilters as $index=>$city) {
												$checked = in_array($index, $cityPreSelectedFilters) ? "checked" : "";
												$disabled = array_key_exists($index, $userAppliedCitiesForFilters) ? "" : "disabled";
												if(trim($city) && array_key_exists($index, $userAppliedCitiesForFilters)) { ?>
													<li>
														<input type="checkbox" name="cityList[]" id="city-<?=$key?>" value="<?=$index?>" <?=$checked?> <?=$disabled?> autocomplete="off">
														<label for="city-<?=$key?>">
															<span class="common-sprite"></span><p><?=$city?></p>
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
													<li>
														<input type="checkbox" name="stateList[]" id="state-<?=$key?>" value="<?=$index?>" <?=$checked?> <?=$disabled?> autocomplete="off">
														<label for="state-<?=$key?>">
															<span class="common-sprite"></span><p><?=$state?></p>
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
													<li>
														<input type="checkbox" name="cityList[]" id="city-<?=$key?>" value="<?=$index?>" <?=$checked?> <?=$disabled?> autocomplete="off">
														<label for="city-<?=$key?>">
															<span class="common-sprite"></span><p><?=$city?></p>
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
		<input type="hidden" id="ajaxurl" name="ajaxurl" value="<?=$ajaxurl?>" />
		<div class="loader" id="loadingImage" style="position:absolute;display:none;z-index:9999;"><img src="public/images/Loader2.GIF" /> Loading</div>
	</div>
</div>
<?php } ?>
<script type="text/javascript">
var rankingKey = '<?php echo $rankingPageObject->getId();?>';
</script>
