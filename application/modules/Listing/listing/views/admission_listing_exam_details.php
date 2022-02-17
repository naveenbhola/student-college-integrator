<!-- exam details -->
								<div id="a_exam_details" style="display:none;">
									<div class="row">
										<div>
											<div>
												<div class="r1 bld">Examination Name:&nbsp;</div>
												<div class="r2">
													<input name="a_exam_name" id="a_examy_name" type="text" ifvalidate="validateStr" value="" maxlength="30" minlength="3" size="30"/>
												</div>
												<div class="clear_L"></div>
											</div>
											<div class="row errorPlace">
												<div class="r1">&nbsp;</div>
												<div id="a_examy_name_error" class="r2 errorMsg"></div>
												<div class="clear_L"></div>
											</div>
										</div>
									</div>
									<div class="lineSpace_13">&nbsp;</div>
									<div class="row">
										<div>
											<div>
												<div class="r1 bld">Examination Date:&nbsp;</div>
												<div class="r2">
													<input name="a_exam_date" id="a_examy_date" type="text" onChange="cal.select($('a_examy_date'),'examd','yyyy-MM-dd');" readonly />
													<img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="examd" onClick="cal.select($('a_examy_date'),'examd','yyyy-MM-dd');" />
												</div>
												<div class="clear_L"></div>
											</div>
											<div class="row errorPlace">
												<div class="r1">&nbsp;</div>
												<div class="r2 errorMsg" id="a_examy_date_error ></div>
												<div class="clear_L"></div>
											</div>
										</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div class="row">
										<div>
											<div>
												<div class="r1 bld">Duration of Examination:&nbsp;</div>
												<div class="r2">
													<input type="text" id="a_examy_duration" name="a_exam_duration"  ifvalidate="validateStr" maxlength="10" minlength="0" size = "12"/>
												</div>
												<div class="clear_L"></div>
											</div>
											<div class="row errorPlace">
												<div class="r1">&nbsp;</div>
												<div id="a_examy_duration_error" class="errorMsg r2"></div>
												<div class="clear_L"></div>
											</div>
										</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div class="row">
										<div>
											<div>
												<div class="r1 bld">Exam Timing:&nbsp;</div>
												<div class="r2">
													<input type="text" name="a_exam_timing" id="a_examy_timing" ifvalidate="validateTime" maxlength="5" size="6"
		 onFocus="if(this.value=='hh:mm'){this.value = '';}"
		 onblur="if(this.value==''){this.value='hh:mm';}"/>
												</div>
												<div class="clear_L"></div>
											</div>
											<div class="row errorPlace">
												<div class="r1">&nbsp;</div>
												<div id="a_examy_timing_error" class="errorMsg r2"></div>
												<div class="clear_L"></div>
											</div>
										</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<?php $this->load->view('listing/admission_listing_exam_centre_details'); ?>
									<input type="hidden" id="a_num_exam_centre" name="a_num_exam_centre" value="0" autocomplete="off"/>
									<div class="row">
										<div>
											<div class="r1">
												<input type="button" id="add_centre_button" onClick="addExamCentre();" style="border:1px solid; margin-left:250px;" value="Add Centre" />
											</div>
										</div>
										<div class="clear_L"></div>
									</div>

									<div id="a_exam_centres" >
									<div id="a_exam_centre" style="display:none">
										<div class="row">
											<div>
												<div>
													<div class="r1 bld">Address 1:&nbsp;</div>
													<div class="r2">
														<input type="text" id="a_address_line1" />
													</div>
													<div class="clear_L"></div>
												</div>
											</div>
											<div class="lineSpace_10">&nbsp;</div>
										</div>
										<div class="row">
											<div>
												<div>
													<div class="r1 bld">Address 2:&nbsp;</div>
													<div class="r2">
														<input type="text" id="a_address_line2"/>
													</div>
													<div class="clear_L"></div>
												</div>
											</div>
											<div class="lineSpace_10">&nbsp;</div>
										</div>
										<div class="row">
											<div>
												<div>
													<div class="r1 bld">Country:&nbsp;</div>
													<div class="r2">
													   <select id="a_country_id" onChange="getCitiesForCountryListEntrance();" validate="validateSelect" minlength="1" maxlength="100" caption="Country">
															<option value="">Select Country</option>
															<?php
																foreach($country_list as $country) :
																	$countryId = $country['countryID'];
																	$countryName = $country['countryName'];
																	if($countryId == 1) { continue; }
																		$selected = "";
																	if($countryId == 2) { $selected = "selected='selected'"; }
														 	?>
															<option value="<?php echo $countryId; ?>" <?php // echo $selected; ?>><?php echo $countryName; ?></option>
														 <?php endforeach; ?>
														</select>
													</div>
													<div class="clear_L"></div>
												</div>
											</div>
										</div>
										<div class="row errorPlace">
										 	<div class="r1">&nbsp;</div>
										 	<div class="r2 errorMsg" id="a_country_id_error" ></div>
										 	<div class="clear_L"></div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="row">
											<div>
												<div>
													<div class="r1 bld">City:&nbsp;</div>
													<div class="r2">
													   <select id="a_city_id" style="width:150px" validate="validateSelect" minlength="1" maxlength="100" caption="City">
															<option value="">Select City</option>
														</select>
														<span id="a_city_id_other" style="display:none"><input type="text" id="a_city_id_val"></span>
													</div>
													<div class="clear_L"></div>
												</div>
											</div>
										</div>
										<div class="row errorPlace">
										 	<div class="r1">&nbsp;</div>
										 	<div class="r2 errorMsg" id="a_city_id_error" ></div>
										 	<div class="clear_L"></div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="row">
											<div>
												<div>
													<div class="r1 bld">Zip:&nbsp;</div>
													<div class="r2">
														<input type="text" id="a_zip" validate="validateZip" maxlength="6" minlength="5" caption="Zip"/>
													</div>
													<div class="clear_L"></div>
												</div>
												<div class="row errorPlace">
													<div class="r1">&nbsp;</div>
													<div id="a_zip_error" class="errorMsg r2"></div>
													<div class="clear_L"></div>
												</div>
											</div>
											<div class="lineSpace_10">&nbsp;</div>
										</div>
										<div class="row">
											<div>
												<div>
													<div class="r1 bld"><input type="button" onClick="return validateExamCentre();" style="border:1px solid;  float:right;" value="Go" /></div>
                                       <div class="r2"><input type="button" onClick="deleteExamCentre();" style="border:1px solid;" value="Cancel" /></div>
                                       <div class="clear_L"></div>
                                     </div>
											</div>
										</div>
									<div class="lineSpace_13">&nbsp;</div>
								</div>
							</div>
							</div>
							</div>
<!-- exam details -->
