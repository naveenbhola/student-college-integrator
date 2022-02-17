<!--Start_Scholarship_Listing_Main_Form-->
<?php
   $attribute = array('name' => 'scholarship_main','method' => 'post','id' => 'scholarship_main');
   echo form_open_multipart('listing/Listing/addScholListingNew',$attribute);
?>
<script>
   var cal = new CalendarPopup("calendardiv");
   cal.offsetX = 20;
   cal.offsetY = 0;
</script>

<?php if($usergroup != "cms" || $onBehalfOf=="true"){
      $this->load->view('listing/packSelection');
   }
?>

<div class="row">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Scholarship Details</span></div>
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
         <div class="r1 bld">Scholarship Name:&nbsp;<span class="redcolor">*</span></div>
         <div class="r2">
            <input type="text" name="s_schol_name" id="s_schol_name" validate="validateStr" maxlength="100" minlength="10" required="true" class="w62_per" tip="schol_title" caption="Scholarship Name" />
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="s_schol_name_error" ></div>
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
	    <textarea name="s_description" id="s_description" validate="validateStr" maxlength="5000" minlength="0" style="height:130px" class="w62_per mceEditor" tip="schol_desc" caption="Description" /></textarea>
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="s_description_error" ></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>


<div class="row">
   <div>
      <div>
         <div class="r1 bld">Category:&nbsp;<span class="redcolor">*</span></div>
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
      <div>
         <div class="r1 bld">Amount of Scholarship:&nbsp;</div>
         <div class="r2">
            <textarea name="s_award_value" id="s_award_value" validate="validateStr" maxlength="1000" minlength="0" style="height:65px" class="w62_per" tip="schol_amt" caption="Scholarship Amount" ></textarea>
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace pd_top_1">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="s_award_value_error"/></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
         <div class="r1 bld">Number of Scholarships:&nbsp;</div>
         <div class="r2">
            <input type="text" name="s_no_of_schol" id="s_no_of_schol" validate="validateInteger" maxlength="5" minlength="0" tip="schol_no" caption="Number of Scholarship" />
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace pd_top_1">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="s_no_of_schol_error"/></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_10">&nbsp;</div>

<div class="row">
   <div>
      <div>
         <div class="r1 bld">Last date of Submission:&nbsp;</div>
         <div class="r2">
            <input type="text" name="last_date_sub" id="last_date_sub" caption="Last date of Submission" readonly validate="validateDate" maxlength="10" onclick="cal.select($('last_date_sub'),'sd','yyyy-MM-dd');" />
            <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal.select($('last_date_sub'),'sd','yyyy-MM-dd');" />
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace pd_top_1">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="last_date_sub_error"/></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_10">&nbsp;</div>


<div class="row">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Purpose of Scholarship </span></div>
   <div class="grayLine mar_top_5p"></div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
         <div class="r1 bld">For Studying/Training:&nbsp;</div>
         <div class="r2">
            <select name="s_level" id="s_level" onchange="showCollegeButton();" tip="schol_prp" >
               <?php $levels_type_arr = array("Select"=>"","X"=>"X","X-XII"=>"X-XII","Graduate"=>"Graduate","Post-Graduate"=>"Post-Graduate","Phd/Research"=>"Phd/Research","Other"=>"Other");

                  foreach($levels_type_arr as $key => $val)
                  {
                     echo '<option value="'.$val.'">'.$key.'</option>';
                  }
               ?>
            </select>
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="s_level_error" ></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row" id="addbutton" style="display:none">
   <div class="r1">
      <!--<button class="btn-submit11 w130" type="button" onClick="setOverlay();">
         <div class="btn-submit11"><p class="btn-submit12">Add College/Institute</p></div>
      </button>-->
      <a href="javascript:void(0);" onclick="showInstitute();" >Add College/Institute</a>
   </div>
   <div class="r2">
      <div id="selected_colleges"></div>
   </div>
   <div class="clear_L"></div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<?php $this->load->view('listing/schol_institute'); ?>

<div class="row clear_L">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Who Can Apply</span></div>
   <div class="grayLine mar_top_5p"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="closeFloat">
   <div class="row">
      <div class="r1 bld">Eligibility:&nbsp;</div>
      <div class="r2">
         <div>
            <div class="float_L">
               <div class="lefttd">Gender</div>
               <div class="righttd">
                  <select name="s_elg_gender" id="s_elg_1">
                     <option value="">Select</option>
                     <option value="Male">Male</option>
                     <option value="Female">Female</option>
                  </select>
               </div>
            </div>
            <div>
               <div class="lefttd">Residency Status</div>
               <div class="righttd">
                  <select name="s_elg_res_stat" id="s_elg_3" style="width:120px" >
                     <?php 
                        $resiStatus = array('Select'=>"",'Indian Citizen'=>'Indian Citizen','Non Resident Indian'=>'Non Resident Indian','Foreign Citizen'=>'Foreign Citizen');
                           foreach($resiStatus as $key => $val)
                           {
                              echo '<option value="'.$val.'">'.$key.'</option>';
                           }
                        ?>
                  </select>
               </dir>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="lineSpace_10">&nbsp;</div>
         <div>
            <div class="float_L">
               <div class="lefttd">Age (in yrs)</div>
               <div class="righttd"><input type="text" size="10" name="s_elg_age" id="s_elg_2" validate="validateInteger" minlength="1" maxlength="3" caption="Age" /></div>
            </div>
            <div>
               <div class="lefttd">Min Qualification</div>
               <div class="righttd">
                  <select name="s_elg_minqual" id="s_elg_4">
                     <?php $minqual_type_arr = array("Select"=>"","X"=>"X","XII"=>"XII","Undergraduate"=>"Undergraduate","Graduate"=>"Graduate","Post Graduate"=>"Post Graduate","Phd/Research"=>"Phd/Research");

                        foreach($minqual_type_arr as $key => $val)
                        {
                           echo '<option value="'.$val.'">'.$key.'</option>';
                        }
                     ?>
                  </select>
               </div>
            </div>
            <div class="clear_L"></div>
         </div>
         <div>
            <div class="float_L">
               <div class="errorMsg" id="s_elg_2_error" ></div>
               <div class="clear_L"></div>
            </div>
         </div>
         <div class="clear_L"></div>
         <div class="lineSpace_10">&nbsp;</div>
         <div>
            <div class="float_L">
               <div class="lefttd">Work Exp. (in yrs)</div>
               <div class="righttd">
                  <select name="s_elg_workex" id="s_elg_5">
                     <?php 
                        $workExpTypes = array('Select'=>"",'1'=>'1 year','2'=>'2 years','3'=>'3 years','4'=>'4 years','5'=>'5 years','6'=>'6 years','7'=>'7 years','8'=>'8 years','9'=>'9 years','10+'=>'10+ years');
                           foreach($workExpTypes as $key => $val)
                           {
                              echo '<option value="'.$val.'">'.$key.'</option>';
                           }
                           ?>
                  </select>
               </div>
            </div>
            <div>
               <div class="lefttd">Marks</div>
               <div class="righttd">
                  <select name="s_elg_marks" id="s_elg_6">
                     <?php 
                        $marksPercent = array('Select'=>"",'Pass'=>'Pass','40%'=>'40%','45%'=>'45%','50%'=>'50%','60%'=>'60%','65%'=>'65%','70%'=>'70%','75%'=>'75%','80%'=>'80%','85+%'=>'85+%');
                           foreach($marksPercent as $key => $val)
                           {
                              echo '<option value="'.$val.'">'.$key.'</option>';
                           }
                           ?>
                  </select>
               </div>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="lineSpace_10">&nbsp;</div>
         <div>
            <div class="float_L" >
               <div class="lefttd">Income Limit </div>
               <div class="righttd">
                  <input type="text" name="s_elg_faminc" id="s_elg_7" validate="validateInteger" maxlength="6" minlength="2" caption="Income Limit" />
               </div>
            </div>
            <div>
            </div>
            <div class="clear_L"></div>
         </div>
         <div>
            <div class="float_L">
               <div class="errorMsg" id="s_elg_7_error" ></div>
               <div class="clear_L"></div>
            </div>
         </div>
         <div class="lineSpace_10">&nbsp;</div>
      </div>
   </div>
</div>
                                                                </div>
                                                                <div class="linespace_13" >&nbsp;</div>
                                                                <div class="row">
                                                                   <div>
                                                                      <div>
                                                                         <div class="r1 bld">Other Details:&nbsp;</div>
                                                                         <div class="r2">
                                                                            <textarea name="s_elg_other" id="s_elg_other" validate="validateStr" maxlength="500" minlength="0" class="w62_per" caption="Other Details"></textarea>
                                                                         </div>
                                                                         <div class="clear_L"></div>
                                                                      </div>
                                                                      <div class="row errorPlace">
                                                                         <div class="r1">&nbsp;</div>
                                                                         <div class="r2 errorMsg" id="s_elg_other_error"></div>
                                                                         <div class="clear_L"></div>
                                                                      </div>
                                                                   </div>
                                                                </div>
                                                                <div class="lineSpace_13">&nbsp;</div>

                                                                <div class="row">
                                                                   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">How To Apply</span></div>
                                                                   <div class="grayLine mar_top_5p"></div>
                                                                </div>
                                                                <div class="linespace_13" >&nbsp;</div>
                                                                <div class="row">
                                                                   <div>
                                                                      <div>
                                                                         <div class="r1 bld">Application Procedure:&nbsp;</div>
                                                                         <div class="r2">
									    <textarea name="s_app_desc" id="s_app_desc" validate="validateStr" maxlength="5000" minlength="0" style="height:130px" class="w62_per mceEditor" tip="schol_app" caption="Application Procedure"></textarea>
                                                                         </div>
                                                                         <div class="clear_L"></div>
                                                                      </div>
                                                                      <div class="row errorPlace">
                                                                         <div class="r1">&nbsp;</div>
                                                                         <div class="r2 errorMsg" id="s_app_desc_error"></div>
                                                                         <div class="clear_L"></div>
                                                                      </div>
                                                                   </div>
                                                                </div>
                                                                <div class="lineSpace_13">&nbsp;</div>
                                                                
                                                                <div>
                                                                   <?php
                                                                       $this->load->view('enterprise/mediaContentSchol');
                                                                   ?>



                                                                   <div class="row">
                                                                      <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Selection Criteria</span></div>
                                                                      <div class="grayLine mar_top_5p"></div>
                                                                   </div>
                                                                   <div class="lineSpace_11">&nbsp;</div>
                                                                   <div class="row">
                                                                      <div>
                                                                         <div>
                                                                            <div class="r1 bld">Details:&nbsp;</div>
                                                                            <div class="r2">
									       <textarea name="s_sel_process" id="s_sel_process" validate="validateStr" maxlength="5000" minlength="0" style="height:130px" class="w62_per mceEditor" tip="schol_cri" caption="Details" ></textarea>
                                                                            </div>
                                                                            <div class="clear_L"></div>
                                                                         </div>
                                                                         <div class="row errorPlace pd_top_1">
                                                                            <div class="r1">&nbsp;</div>
                                                                            <div class="r2 errorMsg" id="s_sel_process_error"/></div>
                                                                            <div class="clear_L"></div>
                                                                         </div>
                                                                      </div>
                                                                   </div>
                                                                   <div class="lineSpace_11">&nbsp;</div>



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
                                                                               <textarea name="s_contact_add" id="s_contact_add" validate="validateStr" maxlength="250" minlength="0" style="height:30px" class="w62_per" caption="Address" ></textarea>
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
            <input type="text" name="s_phone_no" id="s_phone_no" maxlength="10" minlength="0" validate="validateInteger" caption="Phone Number" />
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
                                                                               <input type="text" name="s_fax_no" id="s_fax_no" validate="validateInteger" maxlength="10" minlength="0" caption="Fax" />
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
                                                                   <div class="lineSpace_20">&nbsp;</div>
                                                                   <div class="grayLine"></div>
                                                                   <div class="lineSpace_10">&nbsp;</div>
                                                                   <input type="hidden" name="addSchol" id="addSchol" value="yes">

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
                                                                      <div class="buttr3">
                                                                         <button class="btn-submit7 w9" type="button" onClick="validateScholFields(this.form);">
                                                                            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Submit Listing</p></div>
                                                                         </button>
                                                                      </div>
<?php $redirectLocation = "/";
	if ( isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']!="") )
		$redirectLocation = $_SERVER['HTTP_REFERER'];
?>
                                                                      <div class="buttr2">
                                                                         <button class="btn-submit11 w4" value="" type="button" onClick="location.replace('<?php echo $redirectLocation; ?>');">
                                                                            <div class="btn-submit11"><p class="btn-submit12">Cancel</p></div>
                                                                         </button>
                                                                      </div>
                                                                   </div>
                                                                   <div id="location_elements"></div>
                                                                </form>
                                                                <div class="lineSpace_10">&nbsp;</div>
                                                             </div>
                                                             <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <!--End_Mid_Panel-->
                                                    <script>
                                                       //getCategorySelectBox();
                                                       getCategories();
                                                       //getCitiesForCountryListScholarshipIns();
                                                       var formName = "scholarship_main";
                                                       function validateScholFields(objForm){
                                                             hideInstitute();
                                                             //var elig_flag = vaildateScholEligibility();
                                                             var elig_flag = true;
                                                             var purpose_flag = validatePurpose();
                                                             var flag = validateFields(objForm);
                                                             var catCombo_flag = validateCatCombo('c_categories',10,1);
                                                             if(elig_flag == true && catCombo_flag == true){
                                                                     // var flag = validateFields(objForm);
                                                                     if(flag == false){
                                                                             $('correct_above_error').innerHTML  = "Please scroll up and correct the fields marked in Red!";
                                                                             $('correct_above_error').style.display = 'inline';
                                                                             return flag;
                                                                     }
                                                                     else{
                                                                             $('correct_above_error').innerHTML  = "";
                                                                             $('correct_above_error').style.display = 'none';
                                                                             <?php if ($usergroup != "cms") : ?>
                                                                             validateCaptchaForListing();
                                                                             <?php  else : ?>
                                                                             objForm.submit();
                                                                             <?php endif; ?>
                                                                     }
                                                             }
                                                             else{
                                                                     $('correct_above_error').innerHTML  = "Please scroll up and correct the fields marked in Red!";
                                                                     $('correct_above_error').style.display = 'inline';
                                                                     return false;
                                                             }
                                                     }

                                                          function validatePurpose()
                                                          {
                                                                obj = $('s_level');
                                                                if (obj.value=="")
                                                                {
                                                                      $('s_level_error').parentNode.style.display = 'inline';
                                                                      $('s_level_error').innerHTML  = "Select purpose of scholarship";
                                                                      return false;
                                                                }
                                                                else
                                                                {
                                                                      $('s_level_error').parentNode.style.display = 'none';
                                                                      $('s_level_error').innerHTML  = "";
                                                                      return true;
                                                                }
                                                             }

                                                             function showCollegeButton()
                                                             {
                                                                   var val = $('s_level').value;
                                                                   if (val== "Graduate" || val=="Post-Graduate" || val=="Phd/Research")
                                                                   $('addbutton').style.display = "";
                                                                   else
                                                                   hideelement('add_location','addbutton');

                                                                }

                                                                function chkInstitute(instituteId)
                                                                {
                                                                      var ids = document.getElementsByName('institute_id[]');
                                                                      for(var i=0;i<ids.length;i++) {
                                                                            if (ids[i].value == instituteId) {
                                                                                  return false;
                                                                            }
                                                                      }
                                                                      return true;
                                                                   }
																function HACK_removeInstitute(id)
																{   // Remove href tag from display string
																	var child = document.getElementById(id);
																	var parent = document.getElementById('selected_colleges');
																	parent.removeChild(child);
																	// parse id and split it 3 different ids
																	// remove element from location_elements parent id
																	var temp = new Array();
																	temp = id.split('~');
																	var country_id = temp[0];
																	var child = document.getElementById(country_id);
																	var parent = document.getElementById('location_elements');
																	parent.removeChild(child);
																	var city_id = temp[1];
																	var child = document.getElementById(city_id);
																	var parent = document.getElementById('location_elements');
																	parent.removeChild(child);
																	var colleges_id = temp[2];
																	var child = document.getElementById(colleges_id);
																	var parent = document.getElementById('location_elements');
																	parent.removeChild(child);
																}

								    							function addInstitute()
                                                      			{
                                                              		if ($('si_colleges').value!="") {
                                                                      if (chkInstitute($('si_colleges').value)) {
                                                                      		  var uniq_id = "";
                                                                              var country = document.createElement('input');
                                                                              country.type = "hidden";
                                                                              country.value = $('si_country').value;
                                                                              country.name = "country_id[]";
                                                                              country.id = "country_"+$('si_country').value;
                                                                              uniq_id ="country_"+$('si_country').value;
                                                                              $('location_elements').appendChild(country); 
                                                                              var city = document.createElement('input');
                                                                              city.type = "hidden";
                                                                              city.value = $('si_cities').value;
                                                                              var str = $('si_cities').options[$('si_cities').selectedIndex].innerHTML;
                                                                              city.name = "city_id[]";
                                                                              city.id = "city_"+$('si_cities').value;
                                                                              uniq_id +="~city_"+$('si_cities').value;
                                                                              $('location_elements').appendChild(city);
                                                                              var colleges = document.createElement('input');
                                                                              colleges.type = "hidden";
                                                                              colleges.value = $('si_colleges').value;
                                                                              str  = $('si_colleges').options[$('si_colleges').selectedIndex].innerHTML + ", " + str;
                                                                              colleges.name = "institute_id[]";
                                                                              colleges.id = "colleges_"+$('si_colleges').value;
                                                                              uniq_id +="~colleges_"+$('si_colleges').value;
                                                                              str = "<a id="+uniq_id+" onclick='return HACK_removeInstitute(\""+uniq_id+"\");' style='cursor:pointer'>"+str+"&nbsp;<b>remove<b></a><br>";
                                                                              $('location_elements').appendChild(colleges);
                                                                              if ($('selected_colleges').innerHTML!="")
                                                                              $('selected_colleges').innerHTML  += str;
                                                                              else 
                                                                              $('selected_colleges').innerHTML = str;
                                                                              clearInstitute();
										     								  hideInstitute();
                                                                        }
																		else {
																				$('si_colleges_error').parentNode.style.display = "inline";
																				$('si_colleges_error').innerHTML = "Selected college/institute is already added.";
																		}
																	}
																	else {
																			$('si_colleges_error').parentNode.style.display = "inline";
																			$('si_colleges_error').innerHTML = "Select college/institute first.";
																	}
                                                      			}

								   function clearInstitute()
								   {
									 $('si_cities').innerHTML = '<option value="">Select City</option>';
									 $('si_colleges').innerHTML = '<option value="">Select Institute</option>';
									    $('si_country').selectedIndex = 0;
								   }

                                                                      function showInstitute ()
                                                                      {
									    $('si_country').setAttribute('required','true');
                                                                            $('si_cities').setAttribute('required','true');
									    $('si_colleges').setAttribute('required','true');
									    clearInstitute();
                                                                            showelement('add_location');
                                                                         }

                                                                         function hideInstitute ()
                                                                         {
                                                                               $('si_country').setAttribute('required','');
                                                                               $('si_cities').setAttribute('required','');
                                                                               $('si_colleges').setAttribute('required','');
                                                                               hideelement('add_location');
                                                                            }

                                                                            function vaildateScholEligibility(){
                                                                                  var no_of_elg = 8;
                                                                                  var returnFlag = true;
                                                                                  var atleastOneElg =  false;
                                                                                  for(i=1; i < no_of_elg; i++)
                                                                                  {
                                                                                        if($('s_elg'+i).checked == true){
                                                                                              atleastOneElg = true;
                                                                                              element = $('s_elg_'+i);
                                                                                              if(element.getAttribute('ifvalidate')) {
                                                                                                    var methodName = element.getAttribute('ifvalidate');
                                                                                                    //alert("method name"+methodName);
                                                                                                    var textBoxContent = element.value;
                                                                                                    textBoxContent = textBoxContent.replace(/[(\n)\r\t\"\']/g,' ');
                                                                                                    var textBoxMaxLength  = element.getAttribute('maxlength');
                                                                                                    var textBoxMinLength  = element.getAttribute('minlength');
                                                                                                    var methodSignature = methodName + '("'+ textBoxContent +'", '+ textBoxMaxLength +', '+ textBoxMinLength +')';
                                                                                                    var validationResponse = eval(methodSignature);
                                                                                                    if(validationResponse !== true) {
                                                                                                          $(element.id +'_error').parentNode.style.display = 'inline';
                                                                                                          $(element.id +'_error').innerHTML = validationResponse;
                                                                                                          returnFlag = false;
                                                                                                       } else {
                                                                                                          $(element.id +'_error').parentNode.style.display = 'none';
                                                                                                          $(element.id +'_error').innerHTML = '';
                                                                                                    }
                                                                                              }
                                                                                        }
                                                                                        else{
                                                                                              continue;
                                                                                        }
                                                                                  }
                                                                                  if(atleastOneElg !== true) {
                                                                                        $('s_elg_error').parentNode.style.display = 'inline';
                                                                                        $('s_elg_error').innerHTML = "Please select atleast one of the following eligibility criterias.";
                                                                                     } else {
                                                                                        $('s_elg_error').parentNode.style.display = 'none';
                                                                                        $('s_elg_error').innerHTML = '';
                                                                                  }
                                                                                  return (returnFlag && atleastOneElg);
                                                                               }

                                                                               function setOverlay()
                                                                               {
                                                                                     var overlayWidth = 450;
                                                                                     var overlayHeight = window.screen.height/2;
                                                                                     var overlayTitle = 'Select Institute';

                                                                                     var overLayForm = $('add_location').innerHTML;
                                                                                     $('add_location').innerHTML = '';
                                                                                     overlayContent = overLayForm;
                                                                                     overlayParent = $('add_location');

                                                                                     showOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent);

                                                                                  }
                                                                                  addOnFocusToopTip(document.scholarship_main);
										  addOnBlurValidate(document.scholarship_main);
										  Event.observe(window, 'load', function () { 
											tinyMCE.init({ mode : "textareas", theme : "simple",editor_selector : "mceEditor", editor_deselector : "mceNoEditor"});
                                                                                  });
                                                                                  
                                                                                  <?php if($usergroup == "cms" && $onBehalfOf!="true"){ ?>
   packSpecificChangesCMS("<?php echo $listingType; ?>");
   <?php  } ?>
                                                                                  fillProfaneWordsBag();
                                                                               </script>
                                                                               <!--End_Scholarship_Listing_Main_Form-->
                                                                               <?php  $this->load->view('common/overlay'); ?>
