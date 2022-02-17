<!--<script>var categoryTreeMain = eval(<?php echo $category_tree; ?>);	</script>-->
<?php 
    global $formFlow;
    $formFlow = 'edit';

    $tests_required_other = json_decode(htmlspecialchars_decode($details['tests_required_other']),true);
    $exam_info = $tests_required_other;
?>
<?php //echo "<pre>";print_r($details);echo "</pre>"; ?>
<script>
   var cal = new CalendarPopup("calendardiv");
   cal.offsetX = 20;
   cal.offsetY = 0;
</script>
<div>
   <?php
      $attribute = array('name' => 'admissionListing','method' => 'post','id' => 'admissionListing');
      echo form_open_multipart('enterprise/Enterprise/editNotificationSubmit',$attribute);
   ?>
   <!--Start_College_Listing_Form-->
   <input type="hidden" value="<?php echo $details['admission_notification_id'];?>" name="admission_notification_id">
<input type="hidden" id="listingProdId" name="listingProdId" value="<?php echo $details['packType']; ?>" />

<div style="display:none;">
<?php 
        $this->load->view('listing/packSelection');
    ?>
</div>
   <input type="hidden" name="pageform" value="yes"/>
   <div class="row">
      <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Admission Details</span></div>
      <div class="grayLine mar_top_5p"></div>
   </div>

   <div class="lineSpace_25">&nbsp;</div>
   <div class="row">
      <div style="display: inline; float:left; width:100%">
         <div class="r1 bld">&nbsp;</div>
         <div class="r2">All field marked with <span class="redcolor">*</span> are compulsory to fill in</div>
      </div>
   </div>
   <div class="lineSpace_25">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Name of Admission Notification:<span class="redcolor">*</span>&nbsp;</div>
            <div class="r2">
	       <input type="text" name="a_name" id="a_name" validate="validateStr" maxlength="100" minlength="10" required="true" class="w62_per" tip="adm_title" caption="Admission Notification Name" value="<?php echo $details['title']; ?>"/>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="a_name_error" ></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Description:&nbsp;</div>
            <div class="r2">
	       <textarea name="a_desc" id="a_desc" validate="validateStr" minlength="0" maxlength="5000" style="height:130px" class="w62_per mceEditor" tip="adm_desc" caption="Description" /><?php echo $details['desc']; ?></textarea>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="a_desc_error" ></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <?php $admYears = array('2008','2009'); ?>
   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Admission Year:<span class="redcolor">*</span>&nbsp;</div>
            <div class="r2">
	       <select name="a_year" tip="adm_year">
		  <?php foreach($admYears as $year) { ?>
			<option value="<?php echo $year;?>" <?php if ($year==$details['admission_year']) echo " selected "; ?>><?php echo $year;?></option>
		  <?php } ?>
               </select>
            </div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <?php $selCats = array();
      foreach ($details['categoryArr'] as $cats) {
	 array_push($selCats,$cats['category_id']);
      }
   ?>
   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Category:<span class="redcolor">*</span>&nbsp;</div>
	    <div class="r2">
	       <select name="c_categories[]" id="c_categories" multiple size="10">
	       <?php foreach ($details['categories'] as $cats) {
	                if ($cats[2]=="base") { ?>
		            <optgroup label="<?php echo $cats[1];?>">
		<?php } else { ?>
			<option value="<?php echo $cats[0];?>" <?php if(in_array($cats[0],$selCats)) echo " selected "; ?>><?php echo $cats[1];?></option>
			<?php } ?>
		<?php } ?>
		</select>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="c_categories_error" ></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div class="r1 bld">Country:<span class="redcolor">*</span></div>
         <div class="r2">
            <select onchange="getCitiesWithCollege('si_cities','si_country');" id="si_country" name="a_country" validate="validateSelect" required="true" minlength="1" maxlength="100" caption="Country">
               <option value="">Select Country</option>
               <?php
                  foreach($countryList as $country) :
                  $countryId = $country['countryID'];
                  $countryName = $country['countryName'];
                  if($countryId == 1) { continue; }
                  $selected = "";
                  if($countryId == $details['sel_country_id']) { $selected = "selected='selected'"; }
               ?>
               <option value="<?php echo $countryId; ?>" <?php echo $selected; ?>><?php echo $countryName; ?></option>
               <?php endforeach; ?>
            </select>
         </div>
         <div class="clear_L"></div>
      </div>
   </div>
   <div class="row errorPlace">
      <div class="r1">&nbsp;</div>
      <div class="r2 errorMsg" id="si_country_error" ></div>
      <div class="clear_L"></div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">City:<span class="redcolor">*</span></div>
            <div class="r2">
	       <select onchange="getInstitutesForCityList();" id="si_cities" name="a_city" style="width:150px" validate="validateSelect" required="true" minlength="1" maxlength="100" caption="City">
		  <?php foreach($details['cities'] as $city) { ?>
		  <option value="<?php echo $city['cityID']; ?>" <?php if($city['cityID']==$details['sel_city_id']) echo " selected "; ?>><?php echo $city['cityName'];?></option>
		  <?php } ?>
               </select>
            </div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="row errorPlace">
      <div class="r1">&nbsp;</div>
      <div class="r2 errorMsg" id="si_cities_error" ></div>
      <div class="clear_L"></div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">College:<span class="redcolor">*</span></div>
            <div class="r2">
	       <select id="si_colleges" style="width:200px;" name="a_institute" tip="adm_college" validate="validateSelect" required="true" minlength="1" maxlength="1000" caption="College" onclick="setCollegeName(this);">
		  <?php foreach($details['institutes'] as $ins) { ?>
		  <option value="<?php echo $ins['instituteID']; ?>" <?php if($ins['instituteID']==$details['sel_institute_id']) { echo " selected "; $cn=$ins['instituteName']; }?>><?php echo $ins['instituteName'];?></option>
		  <?php } ?>
               </select>
            </div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <input name="college_name" value="<?php echo $cn; ?>" type="hidden" id="college_name">
   <div class="row errorPlace">
      <div class="r1">&nbsp;</div>
      <div class="r2 errorMsg" id="si_colleges_error" ></div>
      <div class="clear_L"></div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>
   <script>
	function setCollegeName(obj)
	{
		for(var i=0; i < obj.options.length; i++) {
	        if(obj.options[i].selected == true) {
	            $('college_name').value = obj.options[i].innerHTML;
	        }
	    }
	}
	</script>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Application Fee:</div>
            <div class="r2">
	       <textarea  validate="validateStr" name="a_app_fees" id="a_app_fees" maxlength="300" class="w62_per" tip="adm_fee" caption="Application Fee" ><?php echo $details['application_fees'];?></textarea>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="a_app_fees_error" ></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row clear_L">
      <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Admission Procedure</span></div>
      <div class="grayLine mar_top_5p"></div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="closeFloat">
      <div class="row">
         <div class="r1 bld">Eligibility:&nbsp;</div>
	 <div class="r2">
	    <?php
		foreach ($details['eligibilityArr'] as $eligVals)
		{
		   $$eligVals['criteria'] = $eligVals['value'];
		}
	     ?>
            <div class="lefttd">Min Qualification</div>
            <div class="righttd">
	       <select name="s_elg_minqual" id="s_elg_4">
                  <option value="">Select</option>
		  <option value="X" <?php if($minqual=="X") echo "selected";?> >X</option>
		  <option value="XII" <?php if($minqual=="XII") echo "selected";?> >XII</option>
		  <option value="UnderGraduate" <?php if($minqual=="UnderGraduate") echo "selected";?>>Under Graduate</option>
		  <option value="Graduate" <?php if($minqual=="Graduate") echo "selected";?> >Graduate</option>
		  <option value="Post-Graduate" <?php if($minqual=="Post-Graduate") echo "selected";?> >Post Graduate</option>
               </select>
            </div>
            <div class="clear_L lineSpace_10">&nbsp;</div>
            <div>
               <div class="lefttd">Age</div>
	       <div class="righttd"><input type="text" size="10" name="s_elg_age" id="s_elg_2" validate="validateInteger" maxlength="3" minlength="1" caption="Age" value="<?php echo $age;?>" /></div>
            </div>
            <div class="clear_L">
               <div class="float_L">
                  <div class="errorMsg" id="s_elg_2_error" ></div>
                  <div class="clear_L"></div>
               </div>
            </div>
            <div class="clear_L lineSpace_10">&nbsp;</div>
            <div>
               <div class="lefttd">Marks</div>
               <div class="righttd">
                  <select name="s_elg_marks" id="s_elg_6">
                     <option value="">Select</option>
		     <option value="Pass" <?php if($marks=="Pass") echo "selected"; ?> >Pass</option>
		     <option value="40%" <?php if($marks=="40%") echo "selected"; ?> >40 %</option>
		     <option value="45%" <?php if($marks=="45%") echo "selected"; ?> >45 %</option>
		     <option value="50%" <?php if($marks=="50%") echo "selected"; ?> >50 %</option>
		     <option value="60%" <?php if($marks=="60%") echo "selected"; ?> >60 %</option>
		     <option value="65%" <?php if($marks=="65%") echo "selected"; ?> >65 %</option>
		     <option value="70%" <?php if($marks=="70%") echo "selected"; ?> >70 %</option>
		     <option value="75%" <?php if($marks=="75%") echo "selected"; ?> >75 %</option>
		     <option value="80%" <?php if($marks=="80%") echo "selected"; ?> >80 %</option>
                  </select>
               </div>
            </div>
            <div class="clear_L lineSpace_10">&nbsp;</div>
            <div>
               <div class="float_L" >
                  <div class="lefttd">Residency Status</div>
                  <div class="righttd">
                     <select name="s_elg_res_stat" id="s_elg_3">
                        <option value="">Select</option>
			<option value="Indian Citizen" <?php if($res_stat=="Indian Citizen") echo "selected"; ?> >Indian Citizen</option>
			<option value="Non Resident Citizen" <?php if($res_stat=="Non Resident Citizen") echo "selected"; ?> >Non Resident Citizen</option>
			<option value="Foreign Citizen" <?php if($res_stat=="Foreign Citizen") echo "selected"; ?> >Foreign Citizen</option>
                     </select>
                  </div>
               </div>
               <div class="clear_L"></div>
            </div>
         </div>
      </div>
   </div>
   <div class="lineSpace_20">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Details:&nbsp;</div>
            <div class="r2">
	       <textarea name="a_app_proc" id="a_app_proc" validate="validateStr" maxlength="5000" minlength="0" class="w62_per" tip="adm_det" caption="Details"><?php echo $details['application_procedure'];?></textarea>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="a_app_proc_error"></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_10">&nbsp;</div>
    <script>
        function checkExamOtherForNotification(examObj){
            if(!examObj) { return; }
            var otherExamOption = examObj.options[examObj.options.length - 1]; 
            if(otherExamOption.value === '-1') {
                if(otherExamOption.selected === true) {
                    document.getElementById('a_exam_details').style.display = 'block';
                } else {
                    document.getElementById('a_exam_details').style.display = 'none';
                }
            }
        }
    </script>
   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Does this admission need an entrance examination:&nbsp;</div>
            <div class="r2">
	       <input type="radio" id="a_examy" name="a_exam" value="yes" <?php if($details['entrance_exam']=="yes" && is_array($exam_info[0])) echo "checked";  ?> onClick="document.getElementById('examSelectComboBoxPanel').style.display='block';"/> Yes&nbsp; &nbsp;
	       <input type="radio" id="a_examn" name="a_exam" value="no" <?php if($details['entrance_exam']=="no" ) echo "checked";?> onClick="document.getElementById('examSelectComboBoxPanel').style.display='none';"  autocomplete="off"/> No
            </div>
            <div class="clear_L"></div>
         </div>
      </div>
      <div class="clear_L"></div>

      <div class="lineSpace_10">&nbsp;</div>
      
      <div id="examSelectComboBoxPanel" style="<?php if($details['entrance_exam']=="yes" && is_array($exam_info[0])) echo "block"; else echo "none"; ?>">
      <?php 
        $examSelectPanelAttribs = array('examSelectOnChange'=>'checkExamOtherForNotification(this)', 'examSelected'=> $details['tests_required'], 'otherExam'=> ($details['tests_required_other'] != '[]' ? 'aa': ''));
        $this->load->view('common/examSelectPanel', $examSelectPanelAttribs);
      ?>
      <div class="lineSpace_10">&nbsp;</div>

      <!-- exam details -->
      <div id="a_exam_details" style="display:<?php if(isset($exam_info[0]) && $exam_info[0]['exam_name']!='' ) echo "block"; else echo "none"; ?>">
	 <div class="row">
	    <div>
	       <div>
		  <div class="r1 bld">Examination Name:&nbsp;</div>
		  <div class="r2">
		     <input name="a_exam_name" id="a_examy_name" type="text" ifvalidate="validateStr" maxlength="10" minlength="5" size="15" value="<?php echo $exam_info[0]['exam_name']; ?>" />
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

		     <input name="a_exam_date" id="a_examy_date" type="text" maxlength="10" readonly onclick="cal.select($('a_examy_date'),'examd','yyyy-MM-dd');" value="<?php if ($exam_info[0]['exam_date']!="0000-00-00 00:00:00"){ echo substr($exam_info[0]['exam_date'],0,10);} ?>"  />
		     <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="examd" onClick="cal.select($('a_examy_date'),'examd','yyyy-MM-dd');" />
		  </div>
		  <div class="clear_L"></div>
	       </div>
	       <div class="row errorPlace">
		  <div class="r1">&nbsp;</div>
		  <div class="r2 errorMsg" id="a_examy_date_error" ></div>
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
		     <input type="text" id="a_examy_duration" name="a_exam_duration"  ifvalidate="validateStr" maxlength="10" minlength="0" size = "12"  value="<?php echo $exam_info[0]['exam_duration']; ?>" />
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
		     <input type="text" name="a_exam_timing" id="a_examy_timing" ifvalidate="validateTime" maxlength="5" size="6" onFocus="if(this.value=='hh:mm'){this.value = '';}" onblur="if(this.value==''){this.value='hh:mm';}" value="<?php echo $exam_info[0]['exam_timings']; ?>" />
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
	 <?php //$this->load->view('listing/admission_listing_exam_centre_details'); ?>

	 <div class="row">
	    <div>
	       <div class="r1 bld">Examination Centers:&nbsp;</div>
	       <div class="r2" id="exam_centres">
		  <?php for($i=0;$i<count($exam_info[0]['exam_centres_info']);$i++) { ?>
		  <span id="s_link_centre<?php echo $i; ?>"><a onclick="showExamCentre(<?php echo $i;?>)"><?php echo $exam_info[0]['exam_centres_info'][$i]['address_line1'].", ".$exam_info[0]['exam_centres_info'][$i]['city']; ?></a></span>
		  <?php } ?>
	       </div>
	       <div class="clear_L"></div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	 </div>

	 <input type="hidden" id="a_num_exam_centre" name="a_num_exam_centre" value="<?php echo count($exam_info[0]['exam_centres_info']); ?>" />
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
				 foreach($countryList as $country) :
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
      <!-- exam details -->
    </div>

      <div class="lineSpace_10">&nbsp;</div>
      
      <?php 
          $this->load->view('enterprise/mediaContentNotification');
      ?>

   <div class="lineSpace_10 clear_L">&nbsp;</div>
   <div class="row">
      <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Important Dates</span></div>
      <div class="grayLine mar_top_5p"></div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Application form &amp; brochures are available from:&nbsp;</div>
            <div class="r2">
	       <input type="text" name="a_app_bro_start" validate="validateDate" maxlength="10" id="a_app_bro_start" readonly onclick="cal.select($('a_app_bro_start'),'sd','yyyy-MM-dd');" value="<?php if($details['application_brochure_start_date']!="0000-00-00 00:00:00") echo substr($details['application_brochure_start_date'],0,10);?>" />
               <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal.select($('a_app_bro_start'),'sd','yyyy-MM-dd');" />
            </div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="row errorPlace">
      <div class="r1">&nbsp;</div>
      <div class="r2 errorMsg" id="a_app_bro_start_error"></div>
      <div class="clear_L"></div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Last Date for sale of Application form &amp; brochures:&nbsp;</div>
            <div class="r2">
	       <input type="text" name="a_app_bro_end" validate="validateDate" maxlength="10" id="a_app_bro_end" readonly onclick="cal.select($('a_app_bro_end'),'ed','yyyy-MM-dd');"  value="<?php if($details['application_brochure_end_date']!="0000-00-00 00:00:00") echo substr($details['application_brochure_end_date'],0,10);?>"/>
               <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="ed" onClick="cal.select($('a_app_bro_end'),'ed','yyyy-MM-dd');" />
            </div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="row errorPlace">
      <div class="r1">&nbsp;</div>
      <div class="r2 errorMsg" id="a_app_bro_end_error"></div>
      <div class="clear_L"></div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Last date for Receipt of Application:&nbsp;</div>
            <div class="r2">
	       <input type="text" name="a_app_last" validate="validateDate" maxlength="10" id="a_app_last" readonly onclick="cal.select($('a_app_last'),'ld','yyyy-MM-dd');"  value="<?php if($details['application_end_date']!="0000-00-00 00:00:00") echo substr($details['application_end_date'],0,10);?>"/>
               <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="ld" onClick="cal.select($('a_app_last'),'ld','yyyy-MM-dd');" />
            </div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="row errorPlace">
      <div class="r1">&nbsp;</div>
      <div class="r2 errorMsg" id="a_app_last_error"></div>
      <div class="clear_L"></div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Contact Information</span></div>
      <div class="grayLine mar_top_5p"></div>
   </div>
   <div class="lineSpace_10">&nbsp;</div>
   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Contact Name:&nbsp;</div>
            <div class="r2">
	       <input type="text" name="s_contact_name" id="s_contact_name" validate="validateStr" maxlength="50" minlength="5" caption="Name" value="<?php echo $details['contact_name']; ?>" />
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="s_contact_name_error" ></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Contact Address:&nbsp;</div>
            <div class="r2">
	       <textarea name="s_contact_add" id="s_contact_add" validate="validateStr" maxlength="250" minlength="0" style="height:30px" class="w62_per" caption="Address"><?php echo $details['address'];?></textarea>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="s_contact_add_error" ></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Phone Number:&nbsp;</div>
            <div class="r2">
                <input validate="validateInteger" type="text" name="s_phone_no" id="s_phone_no" maxlength="10" minlength="0" caption="Phone Number" value="<?php echo $details['contact_cell']; ?>" />
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="s_phone_no_error" ></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Email:&nbsp;</div>
            <div class="r2">
	       <input type="text" name="s_email" id="s_email" validate="validateEmail" maxlength="125" minlength="0" caption="Email" value="<?php echo $details['contact_email']; ?>" />
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="s_email_error" ></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Fax:&nbsp;</div>
            <div class="r2">
	       <input type="text" name="s_fax_no" id="s_fax_no" validate="validateInteger" maxlength="13" minlength="5" caption="Fax" value="<?php echo $details['contact_fax']; ?>" />
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="s_fax_no_error" ></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_10">&nbsp;</div>

   <div class="grayLine"></div>
   <div class="lineSpace_10">&nbsp;</div>
   <?php if ($usergroup!="cms"): ?>
   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Type the characters you see in picture:<span class="redcolor">*</span></div>
            <div class="r2">
               <img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&secvariable=seccode&randomkey=<?php echo rand(); ?>" id="topicCaptcha"/><br />
		  			<input type="text" name="captcha_text" id="captcha_text" caption="Security Code" tip="secCode" >
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="captcha_text_error"></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>
   <?php endif; ?>

   <div style="display: inline; float:left; width:100%">
      <div class="buttr3" >
         <button class="btn-submit7 w9" onClick="return validateAdmissionFields(this.form);" type="button">
            <div class="btn-submit7" ><p class="btn-submit8 btnTxtBlog" type="submit" name="Submit" id="Submit" value="Submit" >Edit Listing</p></div>
         </button>

      </div>
      <?php $redirectLocation = "/";
         if ( isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']!="") )
         $redirectLocation = $_SERVER['HTTP_REFERER'];
      ?>
      <div class="buttr2">
         <button class="btn-submit11 w4" type="button" onClick="location.replace('<?php echo $redirectLocation;?>');">
            <div class="btn-submit11"><p class="btn-submit12">Cancel</p></div>
         </button>
      </div>
   </div>
   <div class="lineSpace_10">&nbsp;</div>



   <!--End_College_Listing_Form-->
   <?php echo '</form>'; ?>
<script>
   var formName = "admissionListing";
   function validateAdmissionFields(objForm){
         var returnFlag =true;
         /*if(document.getElementById('a_elg_a').checked == true ){
               var ageInput = document.getElementById('a_elg_age').value;
               var age_flag = validateInteger(ageInput,2,1);

               returnFlag = true;
               if(age_flag !== true) {
                     document.getElementById('a_elg_age_error').parentNode.style.display = 'inline';
                     //document.getElementById(formElement.id +'_error').style.display = 'inline';
                     document.getElementById('a_elg_age_error').innerHTML = age_flag;
                     returnFlag = false;
                  } else {
                     document.getElementById('a_elg_age_error').parentNode.style.display = 'none';
                     //document.getElementById(formElement.id +'_error').style.display = 'none';
                     document.getElementById('a_elg_age_error').innerHTML = '';
               }

         }*/

         //var exam_check_flag = validateExamDetails();
         //var appDateCheck = validateAppDate();
         var flag = validateFields(objForm);
         var catCombo_flag = validateCatCombo('c_categories',10,1);
         if((flag == false) || (catCombo_flag == false)){
               return false;
         }
         else{
               createSubmitValues();
               <?php if ($usergroup!="cms") : ?>
               validateCaptchaForListing();
               <?php  else : ?>
               objForm.submit();
               <?php endif; ?>
         }
      }

      function validateExamDetails(){
            var returnFlagExam = true;
            if(document.getElementById('a_examn').checked == true) {
                  return returnFlagExam;
            }
            else {
                  var exam_details_div = document.getElementById('a_exam_details');
                  var elems = exam_details_div.getElementsByTagName('input');
                  for(i = 0;i <elems.length; i++){
                        var formElement = elems[i]
                        if(formElement.getAttribute('ifvalidate')) {
                              var methodName = formElement.getAttribute('ifvalidate');
                              var textBoxContent = trim(formElement.value);
                              formElement.value = textBoxContent;
                              textBoxContent = textBoxContent.replace(/[(\n)\r\t\"\']/g,' ');
                              var textBoxMaxLength  = formElement.getAttribute('maxlength');
                              var textBoxMinLength  = formElement.getAttribute('minlength');
                              var methodSignature = methodName + '("'+ textBoxContent +'", '+ textBoxMaxLength +', '+ textBoxMinLength +')';
                              var validationResponse = eval(methodSignature);
                              if(validationResponse !== true) {
                                    document.getElementById(formElement.id +'_error').parentNode.style.display = 'inline';
                                    //document.getElementById(formElement.id +'_error').style.display = 'inline';
                                    document.getElementById(formElement.id +'_error').innerHTML = validationResponse;
                                    returnFlagExam = false;
                                 } else {
                                    document.getElementById(formElement.id +'_error').parentNode.style.display = 'none';
                                    //document.getElementById(formElement.id +'_error').style.display = 'none';
                                    document.getElementById(formElement.id +'_error').innerHTML = '';
                              }
                              //alert(methodSignature + "===" + returnFlag);
                        }
                  }
            }
            return returnFlagExam;
      }

      function validateAppDate(){
            var date1 =  document.getElementById('a_app_bro_start').value;
            var date2 =  document.getElementById('a_app_bro_end').value;
            var date3 =  document.getElementById('a_app_last').value;

            if((date1 <= date2) && (date2 <= date3))
            {
                  document.getElementById('a_app_dates_error').parentNode.style.display = 'none';
                  //document.getElementById(formElement.id +'_error').style.display = 'inline';
                  document.getElementById('a_app_dates_error').innerHTML = '';
                  return true;
            }
            else{
                  document.getElementById('a_app_dates_error').parentNode.style.display = 'inline';
                  //document.getElementById(formElement.id +'_error').style.display = 'inline';
                  document.getElementById('a_app_dates_error').innerHTML = 'Please put some valid dates.';
                  return false;
            }
      }


      addOnFocusToopTip(document.admissionListing);
      addOnBlurValidate(document.admissionListing);
      tinyMCE.init({ mode : "textareas", theme : "simple",editor_selector : "mceEditor", editor_deselector : "mceNoEditor"});
      fillProfaneWordsBag();
   var arr = new Object();
   <?php
      if (is_array($exam_info[0]['exam_centres_info'])) {
      for($i=0;$i<count($exam_info[0]['exam_centres_info']);$i++) { ?>
      arr[<?php echo $i;?>] = new Object();
      arr[<?php echo $i;?>]['a_address_line1'] = '<?php echo $exam_info[0]['exam_centres_info'][$i]['address_line1'];?>';
      arr[<?php echo $i;?>]['a_address_line2'] = '<?php echo  $exam_info[0]['exam_centres_info'][$i]['address_line2'];?>';
      arr[<?php echo $i;?>]['a_country_id'] = '<?php echo $exam_info[0]['exam_centres_info'][$i]['country_id'];?>';
      arr[<?php echo $i;?>]['a_city_id'] = '<?php echo $exam_info[0]['exam_centres_info'][$i]['city_id'];?>';
      arr[<?php echo $i;?>]['a_zip'] = '<?php echo $exam_info[0]['exam_date']['exam_centres_info'][$i]['zip']?>';
      <?php } } ?>

      function removeNotificationDoc(docId,divId)
   {
           $('doc_'+divId).innerHTML = "";
           $('a_upload_'+divId).style.display = 'inline';
           var url = "/enterprise/Enterprise/removeNotificationDoc/"+docId+"/<?php echo $details['admission_notification_id'];?>";
		new Ajax.Request(url,{method:'get'});
   }

   <?php if($details['packType'] > 0){ ?>
   packSpecificChanges("<?php echo $listingType; ?>");
   <?php  }else{ ?>
   packSpecificChangesCMS("<?php echo $listingType; ?>");
   <?php  } ?>

   </script>
</div>
