<!--<script>var categoryTreeMain = eval(<?php echo $category_tree; ?>);	</script>-->
<script>
   var cal = new CalendarPopup("calendardiv");
   cal.offsetX = 20;
   cal.offsetY = 0;
</script>
<div>
   <?php
      $attribute = array('name' => 'admissionListing','method' => 'post','id' => 'admissionListing');
      echo form_open_multipart('listing/Listing/addAdmission',$attribute);
   ?>
   <!--Start_College_Listing_Form-->

    <?php if($usergroup != "cms" || $onBehalfOf=="true"){
         $this->load->view('listing/packSelection');
      }
   ?>

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
               <input type="text" name="a_name" id="a_name" validate="validateStr" maxlength="100" minlength="10" required="true" class="w62_per" tip="adm_title" caption="Admission Notification Name" />
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
               <textarea name="a_desc" id="a_desc" validate="validateStr" minlength="0" maxlength="5000" style="height:130px" class="w62_per mceEditor" tip="adm_desc" caption="Description" /></textarea>
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

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Admission Year:<span class="redcolor">*</span>&nbsp;</div>
            <div class="r2">
               <select name="a_year" tip="adm_year">
                  <option value="2008">2008</option>
                  <option value="2009">2009</option>
               </select>
            </div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Category:<span class="redcolor">*</span>&nbsp;</div>
            <div class="r2" id="c_categories_combo">
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
                  foreach($country_list as $country) :
                  $countryId = $country['countryID'];
                  $countryName = $country['countryName'];
                  if($countryId == 1) { continue; }
                  $selected = "";
                  if($countryId == 2) { $selected = "selected='selected'"; }
               ?>
               <option value="<?php echo $countryId; ?>" <?php //echo $selected; ?>><?php echo $countryName; ?></option>
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
   <div class="r2 errorMsg" id="ajax_error_show" style="text-align:right;"></div>
   <div class="clear_L lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">City:<span class="redcolor">*</span></div>
            <div class="r2">
               <select onchange="getInstitutesForCityList();" id="si_cities" name="a_city" style="width:150px" validate="validateSelect" required="true" minlength="1" maxlength="100" caption="City" >
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
            <div class="r1 bld">Institute:<span class="redcolor">*</span></div>
            <div class="r2">
               <select id="si_colleges" style="width:200px;" name="a_institute" tip="adm_college" validate="validateSelect" required="true" minlength="1" maxlength="1000" caption="College" onclick="setCollegeName(this);">
                  <option value="">Select College/Institute</option>
               </select>
            </div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <input name="college_name" value="" type="hidden" id="college_name">
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
               <textarea  validate="validateStr" name="a_app_fees" id="a_app_fees" maxlength="300" class="w62_per" tip="adm_fee" caption="Application Fee" ></textarea>
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
            <div class="lefttd">Min Qualification</div>
            <div class="righttd">
               <select name="s_elg_minqual" id="s_elg_4">
                  <option value="">Select</option>
                  <option value="X">X</option>
                  <option value="XII">XII</option>
                  <option value="UnderGraduate">Under Graduate</option>
                  <option value="Graduate">Graduate</option>
                  <option value="Post-Graduate">Post Graduate</option>
               </select>
            </div>
            <div class="clear_L lineSpace_10">&nbsp;</div>
            <div>
               <div class="lefttd">Age</div>
               <div class="righttd"><input type="text" size="10" name="s_elg_age" id="s_elg_2" validate="validateInteger" maxlength="3" minlength="1" caption="Age" /></div>
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
                     <option value="Pass">Pass</option>
                     <option value="40%">40 %</option>
                     <option value="45%">45 %</option>
                     <option value="50%">50 %</option>
                     <option value="60%">60 %</option>
                     <option value="65%">65 %</option>
                     <option value="70%">70 %</option>
                     <option value="75%">75 %</option>
                     <option value="80%">80 %</option>
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
                        <option value="Indian Citizen">Indian Citizen</option>
                        <option value="Non Resident Citizen">Non Resident Citizen</option>
                        <option value="Foreign Citizen">Foreign Citizen</option>
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
               <textarea name="a_app_proc" id="a_app_proc" validate="validateStr" maxlength="5000" minlength="0" class="w62_per" tip="adm_det" caption="Details"  ></textarea>
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
               <input type="radio" id="a_examy" name="a_exam" value="yes" onClick="document.getElementById('examSelectComboBoxPanel').style.display='block';" checked="checked"/> Yes&nbsp; &nbsp;
               <input type="radio" id="a_examn" name="a_exam" value="no" onClick="document.getElementById('examSelectComboBoxPanel').style.display='none';" autocomplete="off"/> No
            </div>
            <div class="clear_L"></div>
         </div>
      </div>
      <div class="clear_L"></div>
      <div class="lineSpace_10">&nbsp;</div>
      <div id="examSelectComboBoxPanel">
        <?php
            $examSelectPanelAttribs = array('examSelectOnChange'=>'checkExamOtherForNotification(this)');
            $this->load->view('common/examSelectPanel', $examSelectPanelAttribs);
        ?>
 
          <div class="lineSpace_10">&nbsp;</div>
          <?php $this->load->view('listing/admission_listing_exam_details'); ?>
      </div>
    
      <div class="lineSpace_10">&nbsp;</div>
      <?php
          $this->load->view('enterprise/mediaContentNotification');
      ?>

   </div>

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
               <input type="text" name="a_app_bro_start" validate="validateDate" maxlength="10" id="a_app_bro_start" readonly onclick="cal.select($('a_app_bro_start'),'sd','yyyy-MM-dd');"/>
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
               <input type="text" name="a_app_bro_end" validate="validateDate" maxlength="10" id="a_app_bro_end" readonly onclick="cal.select($('a_app_bro_end'),'ed','yyyy-MM-dd');"/>
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
               <input type="text" name="a_app_last" validate="validateDate" maxlength="10" id="a_app_last" readonly onclick="cal.select($('a_app_last'),'ld','yyyy-MM-dd');"/>
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
               <input type="text" name="s_contact_name" id="s_contact_name" validate="validateStr" maxlength="50" minlength="5" caption="Name" />
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
               <textarea name="s_contact_add" id="s_contact_add" validate="validateStr" maxlength="250" minlength="0" style="height:30px" class="w62_per" caption="Address"></textarea>
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
                <input type="text" name="s_phone_no" id="s_phone_no" maxlength="100" minlength="0" caption="Phone Number" />
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
               <input type="text" name="s_email" id="s_email" validate="validateEmail" maxlength="125" minlength="0" caption="Email" />
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
               <input type="text" name="s_fax_no" id="s_fax_no" validate="validateInteger" maxlength="13" minlength="5" caption="Fax" />
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
   <?php if ($usergroup != "cms"): ?>
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

   <div id="correct_above_error" style="display:none;color:red;"></div><br/>

   <div style="display: inline; float:left; width:100%">
      <div class="buttr3" >
         <button class="btn-submit7 w9" value=""  onClick="return validateAdmissionFields(this.form);" type="button">
            <div class="btn-submit7" ><p class="btn-submit8 btnTxtBlog" type="submit" name="Submit" id="Submit" value="Submit" >Submit Listing</p></div>
         </button>

      </div>
      <?php $redirectLocation = "/";
         if ( isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']!="") )
         $redirectLocation = $_SERVER['HTTP_REFERER'];
      ?>
      <div class="buttr2">
         <button class="btn-submit11 w4" value="" type="button" onClick="location.replace('<?php echo $redirectLocation;?>');">
            <div class="btn-submit11"><p class="btn-submit12">Cancel</p></div>
         </button>
      </div>
   </div>
   <div class="lineSpace_10">&nbsp;</div>



   <!--End_College_Listing_Form-->
   <?php echo '</form>'; ?>
<script>
   getCategories();
   getCitiesForCountryListScholarshipIns();
   document.admissionListing.reset();
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
                 $('correct_above_error').innerHTML  = "Please scroll up and correct the fields marked in Red!";
                 $('correct_above_error').style.display = 'inline';
                 return false;
         }
         else{
                 $('correct_above_error').innerHTML  = "";
                 $('correct_above_error').style.display = 'none';
                 createSubmitValues();
                 <?php if ($usergroup != "cms") : ?>
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
      Event.observe(window, 'load', function () { 
            tinyMCE.init({ mode : "textareas", theme : "simple",editor_selector : "mceEditor", editor_deselector : "mceNoEditor"});
      });


   <?php if($usergroup == "cms" && $onBehalfOf!="true"){ ?>
      packSpecificChangesCMS("<?php echo $listingType; ?>");
      <?php  } ?>

      fillProfaneWordsBag();
      var arr = new Object();
   </script>

</div>
