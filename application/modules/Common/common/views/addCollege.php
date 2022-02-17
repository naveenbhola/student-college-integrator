<div id="addCollegeOverlay" style="display:none;">
<div id="collegeNetworkError" class="mar_left_10p errorMsg"></div>
<?php    
     /* echo $this->ajax->form_remote_tag( array(
        'url' => base_url().'/user/MyShiksha/collegeAdd/1',
        'success' => 'javascript:addCollegeResult(request.responseText);' 
        ));  */
	$url = base_url().'/user/MyShiksha/collegeAdd/1';
	echo $this->ajax->form_remote_tag( array('url'=>$url,'before' => 'if(!validateAddCollge(this)){return false;}','success' => 'javascript:addCollegeResult(request.responseText);'));
?>
				
           <div class="mar_full_10p">  
                <div class="row normal_11p_blk_arial">
				    <div class="r1 fontSize_12p bld">Select:&nbsp;</div>
                    <div class="r2">
                        <select style="height:18px;width:150px" class = "fontSize_12p" onChange = "showhideGraduationYear()"  id = "status" name ="status">
	                	<option value ="Student">Student</option>
				<option value = "Alumni">Alumni</option>
				<option value = "Faculty">Faculty</option>
				<option value = "Prospective Student">Prospective Student</option>	
		            	</select>
			        </div>
                    <div class="clear_L"></div>
			    </div>
			    <div class="lineSpace_10"></div>


		<div class="row normaltxt_11p_blk_arial" id="passingyear" style="margin:10px 0px">
			<div class="r1 fontSize_12p bld">Graduation Year:&nbsp;</div> 
			<div class="r2">
				<div style="float:left; background-color:#FFFFFF; height:18px; margin-left:4px;font-family:verdana; font-size:12px; width:55px; border:1px solid #999999; border-right:none; margin-left:-1px;" id="year" name="year" ><?php echo date('Y');?>
				</div>
				<div>
				<img src="/public/images/selectArrowUp.gif" onclick="increase()" /><br/><img src="/public/images/selectArrowDown.gif" onclick="decrease()" style="cursor:pointer" />
				</div>
			</div>
			<div class="clear_L"></div>
		</div>
			<div id="year_error" class="row errorMsg"></div>
			<div><div id="GradYear_error" class="row errorMsg"></div></div>
		<div class="lineSpace_10"></div>

				<div class="row normaltxt_11p_blk">
					<div class="r1 bld">Country:&nbsp;</div>
					<div class="r2">
							<select name="country" id="country_adcl" onChange="getCitiesForCountry('',false,'_adcl')">
											<?php
				                                foreach($country_list as $country) {
				                                    $countryId = $country['countryID'];
				                                    $countryName = $country['countryName'];
				                                    if($countryId == 1) { continue; }
				                                    $selected = "";
				                                    if($countryId == 2) { $selected = "selected"; }
				                            ?>
												<option value="<?php echo $countryId; ?>" $selected><?php echo $countryName; ?></option>
				                            <?php
				                                }
				                            ?>
							</select>
					</div>
					<div class="clear_L"></div>
				</div>
				<div class="lineSpace_10">&nbsp;</div>

				<div class="row normaltxt_11p_blk">
					<div class="r1 bld">City:&nbsp;</div>
					<div class="r2">
						<select name="cities_adcl" id="cities_adcl" onChange="checkCity(this, 'updateInstitutes','','_adcl');"></select>
						<input type="text" validate="validateStr" maxlength="25" minlength="0" name="cities_other" id="cities_adcl_other" value="" style="display:none"/>
					</div>
					<div class="clear_L"></div>                
				</div>

				<div class="row normaltxt_11p_blk" style="display:none">
					<div class="r1">&nbsp;</div>
					<div class="r2 errorMsg"  id="cities_other_error"></div>
					<div class="clear_L"></div>           
				</div>
				<div class="lineSpace_10">&nbsp;</div>

				<div class="row normaltxt_11p_blk">
					<div class="r1 bld">Institute Name:&nbsp;</div>
					<div class="r2">
						<select id="colleges_adcl"  name="colleges" onChange="checkInstitute(this, 'updateCourses','_adcl');" style="width:300px"></select>
						<input type="text" validate="validateStr" maxlength="125" minlength="0" name="colleges_other" id="colleges_adcl_other" value="" style="display:none"/>
						<input type="hidden" validate="validateStr" maxlength="125" minlength="0" name="collegeName" id="collegeName_adcl" value=""/>
					</div>
                    <div class="clear_L"></div>
			    </div>

				<div class="row normaltxt_11p_blk" style="display:none">
					<div class="r1">&nbsp;</div>
					<div class="r2 errorMsg"  id="colleges_other_error"></div>
                    <div class="clear_L"></div>
				</div>
				<div class="lineSpace_10">&nbsp;</div>

				<div class="row normaltxt_11p_blk">
					<div class="r1 bld">Course Name:&nbsp;</div>
					<div class="r2">
						<select id="courses_adcl" name="courses" onChange="checkCourse(this,'','_adcl');" style="width:355px"></select>
						<input type="text" validate="validateStr" maxlength="125" minlength="0"  name="courses_other" id="courses_adcl_other" value="" style="display:none"/>
					</div>
                    <div class="clear_L"></div>
				</div>

				<div class="row normaltxt_11p_blk" style="display:none">
					<div class="r1">&nbsp;</div>
					<div class="r2 errorMsg"  id="courses_other_error"></div>
                    <div class="clear_L"></div>                    
				</div>
                <div class="lineSpace_10">&nbsp;</div>

									<!--<input type="hidden" id="Location" name="Location" value="1"/>-->
									
									<input type="hidden" id="country_adcl" name="country" value="2"/>
									<input type="hidden" id="city_adcl" name="city" value="2"/>
									<input type="hidden" id="institute_adcl" name="institute" value="2"/>
									<input type="hidden" id="course_adcl" name="course" value="2"/>
									<input type="hidden" id="listingType" name="listingType" value="course"/>
									<input type = "hidden" id = "GradYear" name = "GradYear" value = ""/>
				<div class="row normaltxt_11p_blk">
					<div class="bld mar_left_27p">Type in the characters you see in the picture below: &nbsp;</div>
                </div>
                <div class="lineSpace_10">&nbsp;</div>

				<div class="row normaltxt_11p_blk">
                    <div class="r1">&nbsp;</div>
                    <div class="r2">
                        <img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5" id="addCollegesecimg" />
					</div>
                    <div class="clear_L"></div>
                </div>
				<div class="lineSpace_10">&nbsp;</div>

                <div class="row normaltxt_11p_blk">
                    <div class="r1">&nbsp;</div>
                    <div class="r2">
                        <input type="text" name="addCollegeSecCode" id="addCollegeSecCode" class="w8" maxlength="5"/>
					</div>
					 <div class="clear_L"></div>
					<div id="addCollegeSecCode_error" class="errorMsg" style="margin-left:80px"></div>
                   
				</div>
				<div class="lineSpace_10">&nbsp;</div>

				<div class="grayLine"></div>
				<div class="lineSpace_10">&nbsp;</div>
			    
                <div class="row normaltxt_11p_blk">
                    <div class="r1">&nbsp;</div>
                    <div class="r2">               
                        <div class="buttr3">
							<button class="btn-submit7 w3" type="submit">
									<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Submit</p></div>
							</button>
						</div>
						<div class="clear_L"></div>
                    </div>
                    <div class="clear_L"></div>
                     <div class="lineSpace_10">&nbsp;</div>
				</div>
          </div>
		</form>
</div>
<script>
	getCitiesForCountry('',false,'_adcl');
	document.getElementById('GradYear').value = parseInt(document.getElementById('year').innerHTML) ;
	function increase()
	{
	           var val=parseInt(document.getElementById('year').innerHTML);
	           if(val < 2014)
		   val++;
	           document.getElementById('year').innerHTML=val;
		   document.getElementById('GradYear').value = val;
	}

	function decrease()
	{
	            var val=parseInt(document.getElementById('year').innerHTML);
		    if(val > 1931)
	            val--;
	            document.getElementById('year').innerHTML=val;
		   document.getElementById('GradYear').value = val;
	}	
</script>
