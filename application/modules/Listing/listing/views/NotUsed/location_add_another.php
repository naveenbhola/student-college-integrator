								<div class="row">	
									<div style="display: inline; float:left; width:100%">
										<div class="normaltxt_11p_blk w30 txt_align_r float_L">Location:&nbsp;</div>
										<div class="normaltxt_11p_blk float_L txt_align_l fontSize_9p">
											<div class="float_L mar_right_10p">
												<select id="c_country_ci" onChange="getCitiesForCountryListCollege();" name="i_country_id[]">
												<?php foreach($country_list as $country) {
										                    $countryId = $country['countryID'];
										                    $countryName = $country['countryName'];
										                    if($countryId == 1) { continue; }
										                    $selected = "";
										                    if($countryId == 2) { $selected = "selected"; } ?>
													<option value="<?php echo $countryId; ?>" <?php echo $selected; ?>><?php echo $countryName; ?></option>
										            <?php 										                	}
										            ?>
												</select>
												<select name="i_city_id[]" id="c_cities">
													<option>Select City</option>
												</select><br />
												<div class="lineSpace_10">&nbsp;</div>
											</div>
										</div>
									</div>
									
									<div class="lineSpace_10">&nbsp;</div>
								</div>

								<div id="another_add"></div>

                                <div class="row" id="another_link">   
                                     <div style="display: inline; float:left; width:100%">
                                          <div class="normaltxt_11p_blk w30 txt_align_r float_L"> <a onClick="addAnother();">+ Add another</a></div>
                                          <div class="lineSpace_10">&nbsp;</div>
                                     </div>
                                </div>
                                 <div class="lineSpace_10">&nbsp;</div>
														<script>

                        function addAnother()
                        {
                            document.getElementById('div_another_'+noOfCountry).style.display = "block";
                            getCitiesForCountryListAnother(noOfCountry);
                            document.getElementById('c_country'+noOfCountry).name = "i_country_id[]";
                            document.getElementById('c_cities'+noOfCountry).name = "i_city_id[]";
                            if(noOfCountry==2)
                                document.getElementById('another_link').style.display = "none";
                            noOfCountry++;
                        }

						var noOfCountry = 1;
						function appendAnother(num)
						{
							document.getElementById('another_add').innerHTML +='<div class="row" id="div_another_'+num+'" style="display:none;">\
									<div style="display: inline; float:left; width:100%">\
										<div class="normaltxt_11p_blk w30 txt_align_r float_L">&nbsp;</div>\
										<div class="normaltxt_11p_blk float_L txt_align_l fontSize_9p">\
											<div class="float_L mar_right_10p">\
												<select id="c_country'+num+'" onChange="getCitiesForCountryListAnother('+num+');">\
												<?php foreach($country_list as $country) {
										                    $countryId = $country['countryID'];
										                    $countryName = $country['countryName'];
										                    if($countryId == 1) { continue; }
										                    $selected = "";
										                    if($countryId == 2) { $selected = "selected"; } ?>
													<option value="<?php echo $countryId; ?>" <?php echo $selected; ?>><?php echo $countryName; ?></option>\
										            <?php 										                	}
										            ?>
												</select>\
												<select id="c_cities'+num+'"><option>Select City</option></select>\
												<a onClick="removeAnother('+num+')" >remove</a>\
												<div class="lineSpace_10">&nbsp;</div>\
											</div>\
										</div>\
									</div>\
								</div>';
						}
						
						function removeAnother(num)
						{
							document.getElementById('div_another_'+num).style.display = "none";
                            document.getElementById('c_country'+num).name = "";
                            document.getElementById('c_cities'+num).name = "";
                            noOfCountry--;
                            if (noOfCountry<3)
                                 document.getElementById('another_link').style.display = "block";
						}
						</script>
					
						<script>getCitiesForCountryListCollege();
                        for(var i=1;i<3;i++)
                               appendAnother(i);
                        
                        </script>
