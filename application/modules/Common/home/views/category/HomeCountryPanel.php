<div>
	<input type = "hidden" id = "pagetype" value = "<?php echo $pagetype ?>"/>	
									<input type="hidden" id="startOffSet" value="0"/>
									<input type="hidden" id="countOffset" value="14"/>
	<div>
				<!--Start_TabSelection-->
				<!--End_TabSelection-->

				
				<div style="border:1px solid #D6DBDF;position:relative;top:1px;width:100%">

            <?php	$this->load->view('home/category/HomeCategoryCollegePanel', $categoryData);?>
					<div class="mar_full_10p"  style="padding:10px 0px;<?php echo count($events) < 1 ? 'height:'. $height .'px' : ''; ?>; display:none" id="categoryinstitutesBlock">
								<?php
									$messageText = '';
									
									$totalResults = $collegeList[0]['total'];
									if($totalResults > 0) {
										$startNum = 1;
										$endNum = 14;
										$endNum = $endNum > $totalResults ? $totalResults : $endNum;
										$messageText = 'Showing '. $startNum  . ' - '. $endNum .' institutes out of '. $totalResults;
									} else {
										$messageText = 'No institutes Available.';
									}	
								?>
								<div><label id="categoryCollegesCountLabel"><?php echo $messageText; ?></label></div>
								<div class="lineSpace_15">&nbsp;</div>
								<div id="collegeListPlace">
										<?php 
                                                $selectedCityList = explode(',',$selectedCity);
												foreach($collegeList[0]['institutes'] as $college){
												if(empty($college['id'])) {  continue; }
												$collegeId 		= $college['id'];
												$collegeName 	= ucwords($college['title']);
                                                if($countrySelected =='' &&  $selectedCity=='') {
												    $collegeCity	= $college['locationArr'][0]['city_name'];
                                                    $collegeCountry = $college['locationArr'][0]['country_name'];
                                                } else {
                                                    $collegeCity = '';
                                                    $collegeCountry = '';
                                                }
												if($countrySelected != '') {
													for($locationCount = 0; $locationCount < count($college['locationArr']); $locationCount++){
														if($college['locationArr'][$locationCount]['country_id'] == $countrySelected) {
															$collegeCity	= $college['locationArr'][$locationCount]['city_name'];
															$collegeCountry = $college['locationArr'][$locationCount]['country_name'];
                                                            if($selectedCity == '') {
                                                                break;
                                                            }

                                                            if($selectedCity != '' && in_array($college['locationArr'][$locationCount]['city_id'], $selectedCityList )) { break; }
														}
													}
												}
												$url = $college['url'];
												$location = $collegeCity;
												if($collegeCity != '' && $collegeCountry!= '') {
													$location .= ' - ';
												}
												$location .= $collegeCountry;
												
												$screen_res = $_COOKIE['client'];
												if($screen_res < 1000)
												$truncateStrLength = 16;
												else
												$truncateStrLength = 33;

												$sponsoredResult = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'check.gif' : 'grayBullet.gif' ;
												$truncateStrLengthForRecord = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? $truncateStrLength-3 : $truncateStrLength;
												$sponsoredResultMargin = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'margin-left:-6px' : '' ;
												$collegeDisplayName = strlen($collegeName) > $truncateStrLengthForRecord ? substr($collegeName, 0, $truncateStrLengthForRecord - 3) .'...' : $collegeName;
												$locationDisplayText = strlen($location) > $truncateStrLengthForRecord ? substr($location, 0, $truncateStrLengthForRecord - 3) .'...' : $location;
												$locationDisplayText = $locationDisplayText=='' ? '&nbsp;' : $locationDisplayText;
												$courseId = $college['courseArr'][0]['course_id'];
												$courseUrl = html_entity_decode($college['courseArr'][0]['url']);
												$courseName = html_entity_decode($college['courseArr'][0]['title']);
												$courseNameDisplayText = strlen($courseName) > $truncateStrLengthForRecord ? substr($courseName, 0, $truncateStrLengthForRecord - 3) .'...' : $courseName;
												$courseNameDisplayText = $courseNameDisplayText=='' ? '&nbsp;' : $courseNameDisplayText;
																			
										?>									
										<div class="w49_per float_L">
												<div class="row" style = "height:55px">
														<div class="float_L" style="padding:5px 5px 5px 0px; <?php echo $sponsoredResultMargin;?>">
															<img src="/public/images/<?php echo $sponsoredResult; ?>" align="absmiddle"/>
														</div>
														<div class="float_L">
															<div class="normaltxt_11p_blk_arial">
																<a class="fontSize_12p" href="<?php echo $url;?>" title="<?php echo $collegeName ;?>"><?php echo $collegeDisplayName; ?></a>
															</div>
															<?php if(strlen($courseName) != 0) { ?>
																<div class="normaltxt_11p_blk_arial">
																	<a href="<?php echo $courseUrl; ?>" title="<?php echo $courseName; ?>" class="blackFont"><?php echo $courseNameDisplayText; ?></a>
																</div>
															<?php } ?>
															<div class="normaltxt_11p_blk_arial">
																<span class="fontGreenColor"><?php echo $location ; ?></span>
															</div>
															<script>
																var isQuickSignUpUser = 0;
																var base64url = '';
																var UserLogged = 1;
															</script>
															<?php /* if($college['isSendQuery'] == 1){
																if(!(is_array($validateuser) && $validateuser != "false")) {
															?> 
															<script>
																var UserLogged = 0;
															</script>
															<?php $onClick = "showuserLoginOverLay('',2);return false;";
															}else {
																if($validateuser[0]['quicksignuser'] == 1 && $validateuser[0]['orusergroup'] == "quicksignupuser") {
																	$base64url = base64_encode($_SERVER['REQUEST_URI']);?>
																<script>
																	isQuickSignUpUser = 1;
																	base64url = '<?php echo $base64url ?>';
																</script>
															<?php $onClick = "window.location = '/user/Userregistration/index/$base64url/1'";
															} else {  
																	$onClick = "setRequestInfoForSearchParams('institute',$collegeId,'$collegeName','$url','');";
															}}?>
															<div class="normaltxt_11p_blk_arial">
																<a class="grayFont" onClick = "<?php echo $onClick?>" style = "cursor:pointer">Send Question to this Institute</a>
															</div>
                                                            <?php } */?>
															<div class="lineSpace_10">&nbsp;</div>
														</div>
														<div class="clear_L"></div>
												</div>
										</div>
										<?php } ?>								
                                </div>
					</div>
                                <!-- Pagination Place -->
                        <!-- Pagination Place -->
					<!--End_Internal_Data_Main_Institute-->
				</div>
				<!--End_Border-->
	</div>
</div>
