<!--Start_Scholarship_Listing_Main_Form-->
<?php 
    global $formFlow;
    $formFlow = 'edit';
?>
<?php
    $attribute = array('name' => 'scholarship_main','method' => 'post','id' => 'scholarship_main');
    echo form_open_multipart('enterprise/Enterprise/updateScholarshipCMS',$attribute); 
?>
<script>
    var completeCategoryTree = eval(<?php echo $completeCategoryTree; ?>);
    var cal = new CalendarPopup("calendardiv");
    cal.offsetX = 20;
    cal.offsetY = 0;
</script>

<div style="display:none;">
<?php 
        $this->load->view('listing/packSelection');
    ?>
</div>

<input type="hidden" name="userPack" value="<?php echo $packType;?>" />
<input type="hidden" name="update_schol_id" value="<?php echo $scholarship_id;?>" />
<input type="hidden" id="listingProdId" name="listingProdId" value="<?php echo $packType; ?>" />
<div class="row">
    <div class="mar_left_5p txt_align_l"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Scholarship Details</span></div>
    <div class="grayLine mar_top_5p"></div>
</div>
<div class="lineSpace_25">&nbsp;</div>
<div class="row">	
    <div style="display: inline; float:left; width:100%">
        <div class="r1 bld">&nbsp;</div>
        <div class="r2">All fields marked with <span class="redcolor">*</span> are compulsory to fill in</div>
    </div>
</div>
<div class="lineSpace_25">&nbsp;</div>
<div class="row">	
    <div>
        <div>
            <div class="r1 bld">Scholarship Name:&nbsp;<span class="redcolor">*</span></div>
            <div class="r2">
                <input type="text" name="s_schol_name" id="s_schol_name" validate="validateStr" maxlength="100" minlength="10" required="true" class="w62_per" tip="schol_title" caption="Scholarship Name" value="<?php echo $title; ?>" />	
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
                <textarea type="text" name="s_description" id="s_description" validate="validateStr" maxlength="5000" style="height:130px" class="w62_per mceEditor" tip="schol_desc" /><?php echo $desc; ?></textarea>
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
                <script>
                    var selectCatArr = new Array();
                    <?php
                        $i=0;
                        foreach($categoryArr as $existingCateg)
                        {?>
                        selectCatArr[<?php echo $i;?>]="<?php echo $existingCateg["category_id"];?>";
                        <?php
                            $i++;
                        } 
                    ?>
                </script>
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
                <textarea name="s_award_value" id="s_award_value" validate="validateStr" maxlength="1000" minlength="0" style="height:65px" class="w62_per" tip="schol_amt" caption="Scholarship Amount" ><?php echo $value; ?></textarea>
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
                <input type="text" name="s_no_of_schol" id="s_no_of_schol" validate="validateInteger" maxlength="5" minlength="0" tip="schol_no" caption="Number of Scholarship" value="<?php echo $num_of_schols; ?>"/>
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
                <?php $last_date_submission = explode(" ",$last_date_submission); ?>
                <input type="text" name="last_date_sub" id="last_date_sub" caption="Last date of Submission" validate="validateDate" maxlength="10" onfocus="cal.select($('last_date_sub'),'sd','yyyy-MM-dd');" value="<?php echo $last_date_submission[0]; ?>"/>
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
<div class="clear_L lineSpace_10">&nbsp;</div>


<div class="row">
    <div class="mar_left_5p txt_align_l"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Purpose of Scholarship </span></div>
    <div class="grayLine mar_top_5p"></div>
</div>
<div class="lineSpace_13">&nbsp;</div>								

<div class="row">	
    <div>
        <div>
            <div class="r1 bld">For Studying/Training:&nbsp;</div>
            <div class="r2">
                <?php $levels_type_arr = array("Select"=>"","X"=>"X","X-XII"=>"X-XII","Graduate"=>"Graduate","Post-Graduate"=>"Post-Graduate","Phd/Research"=>"Phd/Research","Other"=>"Other");
                    if(($levels== NULL) || array_key_exists($levels,$levels_type_arr)){
                        $Other_Flag = 0;
                    }else{
                        $Other_Flag = 1;
                    }
                ?>
                <select name="s_level" id="s_level" onchange="showCollegeButton();" tip="schol_prp" >
                    <?php 
                        foreach($levels_type_arr as $key => $val)
                        {
                            if($Other_Flag == 0){
                                if($key == $levels){
                                    echo '<option value="'.$val.'" selected>'.$key.'</option>';
                                }else{
                                    echo '<option value="'.$val.'">'.$key.'</option>';
                                }
                            }
                            if($Other_Flag == 1){
                                if($key != "Other"){
                                    echo '<option value="'.$val.'">'.$key.'</option>';
                                }else{
                                    echo '<option value="'.$val.'" selected>'.$key.'</option>';
                                }
                            }
                        }
                    ?>
                </select>
                <?php
                    if($Other_Flag == 0){
                        echo '<input type="text" name="other_level_type" id="other_level_type" style="display:none" />';
                    }else if ($Other_Flag ==1){
                        echo '<input type="text" name="other_level_type" id="other_level_type" style="" value="'.$levels.'" />';
                    }
                ?>
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

<div class="row">
    <div class="mar_left_5p txt_align_l"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Who Can Apply</span></div>			
    <div class="grayLine mar_top_5p"></div>	
</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="closeFloat">
    <div class="row">
        <div class="r1 bld">Eligibility:&nbsp;</div>
        <?php
            if(isset($eligibilityArr))
            {
                if(count($eligibilityArr) >= 1)
                {
                    foreach($eligibilityArr as $criteriaNum)
                    {
                        $eligType[trim($criteriaNum['criteria'])]=trim($criteriaNum['value']);
                    }
                }
            }
        ?>
        <div class="r2">
            <div>
                <div class="float_L">
                    <div class="lefttd">Gender</div>
                    <div class="righttd">
                        <select name="s_elg_gender" id="s_elg_1">
                            <?php 
                                $genderTypes = array('Select'=>"",'Male'=>'Male','Female'=>'Female');
                                if(isset($eligType['gender']) && $eligType['gender'] != "")
                                {
                                    foreach($genderTypes as $key => $val)
                                    {
                                        if($eligType['gender'] == $val)
                                        {
                                            echo '<option value="'.$val.'" selected>'.$key.'</option>';
                                        }else{
                                            echo '<option value="'.$val.'">'.$key.'</option>';
                                        }
                                    }
                                }else{
                                    foreach($genderTypes as $key => $val)
                                    {
                                        echo '<option value="'.$val.'">'.$key.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>										
                <div>
                    <div class="lefttd">Residency Status</div>
                    <div class="righttd">
                        <select name="s_elg_res_stat" id="s_elg_3" style="width:120px" >
                            <?php 
                                $resiStatus = array('Select'=>"",'Indian Citizen'=>'Indian Citizen','Non Resident Indian'=>'Non Resident Indian','Foreign Citizen'=>'Foreign Citizen');
                                if(isset($eligType['res_stat']) && $eligType['res_stat'] != "")
                                {
                                    foreach($resiStatus as $key => $val)
                                    {
                                        if($eligType['res_stat'] == $val)
                                        {
                                            echo '<option value="'.$val.'" selected>'.$key.'</option>';
                                        }else{
                                            echo '<option value="'.$val.'">'.$key.'</option>';
                                        }
                                    }
                                }else{
                                    foreach($resiStatus as $key => $val)
                                    {
                                        echo '<option value="'.$val.'">'.$key.'</option>';
                                    }
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
                    <div class="lefttd">Age</div>
                    <div class="righttd"><input type="text" size="10" name="s_elg_age" id="s_elg_2" minlength="1" maxlength="3" caption="Age" value="<?php 
                            if(isset($eligType['age']) && $eligType['age'] != "")
                            {
                                echo $eligType['age'];
                            }
                            else
                            {
                                echo '';
                            }
                        ?>"
                        /></div>
                </div>										
                <div>
                    <div class="lefttd">Min Qualification</div>
                    <div class="righttd">
                        <select name="s_elg_minqual" id="s_elg_4">
                            <?php 
                                $minqualTypes = array('Select'=>"",'X'=>'X','XII'=>'XII','Undergraduate'=>'Undergraduate','Graduate'=>'Graduate','Post Graduate'=>'Post-Graduate','Phd/Research'=>'Phd-Research');
                                if(isset($eligType['minqual']) && $eligType['minqual'] != "")
                                {
                                    foreach($minqualTypes as $key => $val)
                                    {
                                        if($eligType['minqual'] == $val)
                                        {
                                            echo '<option value="'.$val.'" selected>'.$key.'</option>';
                                        }else{
                                            echo '<option value="'.$val.'">'.$key.'</option>';
                                        }
                                    }
                                }else{
                                    foreach($minqualTypes as $key => $val)
                                    {
                                        echo '<option value="'.$val.'">'.$key.'</option>';
                                    }
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
                                if(isset($eligType['workex']) && $eligType['workex'] != "")
                                {
                                    foreach($workExpTypes as $key => $val)
                                    {
                                        if($eligType['workex'] == $val)
                                        {
                                            echo '<option value="'.$val.'" selected>'.$key.'</option>';
                                        }else{
                                            echo '<option value="'.$val.'">'.$key.'</option>';
                                        }
                                    }
                                }else{
                                    foreach($workExpTypes as $key => $val)
                                    {
                                        echo '<option value="'.$val.'">'.$key.'</option>';
                                    }
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
                                if(isset($eligType['marks']) && $eligType['marks'] != "")
                                {
                                    foreach($marksPercent as $key => $val)
                                    {
                                        if($eligType['marks'] == $val)
                                        {
                                            echo '<option value="'.$val.'" selected>'.$key.'</option>';
                                        }else{
                                            echo '<option value="'.$val.'">'.$key.'</option>';
                                        }
                                    }
                                }else{
                                    foreach($marksPercent as $key => $val)
                                    {
                                        echo '<option value="'.$val.'">'.$key.'</option>';
                                    }
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
                        <input type="text" name="s_elg_faminc" id="s_elg_7" maxlength="6" minlength="2" caption="Income Limit" value="<?php echo $eligType['faminc']; ?>" />
                    </div>
                </div>										
                <div>
                </div>
                <div class="clear_L"></div>
            </div>
            <div class="clear_L lineSpace_10">&nbsp;</div>
        </div>
    </div>
    <div class="clear_L linespace_13" >&nbsp;</div>
    <div class="row">	
        <div>
            <div>
                <div class="r1 bld">Other Details:&nbsp;</div>
                <div class="r2">
                    <textarea name="s_elg_other" id="s_elg_other" validate="validateStr" maxlength="500" minlength="0" class="w62_per"><?php
                            if(isset($eligType['other']) && $eligType['other'] != "")
                            {
                                echo $eligType['other'];
                            }
                            else
                            {
                                echo '';
                            }
                        ?></textarea>
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
        <div class="mar_left_5p txt_align_l"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">How To Apply</span></div>			
        <div class="grayLine mar_top_5p"></div>	
    </div>
    <div class="linespace_13" >&nbsp;</div>
    <div class="row">	
        <div>
            <div>
                <div class="r1 bld">Application Procedure:&nbsp;</div>
                <div class="r2">
                    <textarea name="s_app_desc" id="s_app_desc" validate="validateStr" maxlength="5000" minlength="0" style="height:130px" class="w62_per mceEditor" tip="schol_app"><?php echo $application_procedure; ?>
                    </textarea>
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
    <?php if($flagMedia == 1) {
            $this->load->view('enterprise/mediaContentSchol');
    ?>
    <input type='hidden' name='s_media_content' value='1'/>
    <?php } ?>


        <div class="row">
            <div class="mar_left_5p txt_align_l"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Selection Criteria</span></div>			
            <div class="grayLine mar_top_5p"></div>	
        </div>
        <div class="lineSpace_11">&nbsp;</div>
        <div class="row">	
            <div>
                <div>
                    <div class="r1 bld">Details:&nbsp;</div>
                    <div class="r2">
                        <textarea name="s_sel_process" id="s_sel_process" validate="validateStr" maxlength="5000" minlength="0" style="height:130px" class="w62_per mceEditor" tip="schol_cri" ><?php echo $selection_process; ?></textarea>
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
            <div class="mar_left_5p txt_align_l"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Contact Information</span></div>			
            <div class="grayLine mar_top_5p"></div>	
        </div>
        <div class="lineSpace_10">&nbsp;</div>
        <div class="row">	
            <div>
                <div>
                    <div class="r1 bld">Contact Name:&nbsp;</div>
                    <div class="r2">
                        <input type="text" name="s_contact_name" id="s_contact_name" validate="validateStr" maxlength="50" minlength="5" caption="Name" value="<?php echo $contact_name; ?>" />
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
                        <textarea name="s_contact_add" id="s_contact_add" validate="validateStr" maxlength="250" style="height:30px" class="w62_per" caption="Address" ><?php echo $contact_address; ?></textarea>
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
                        <input  validate="validateInteger" type="text" name="s_phone_no" id="s_phone_no" maxlength="10" minlength="0" caption="Phone Number" value="<?php echo $contact_cell; ?>"/>
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
                        <input type="text" name="s_email" id="s_email" validate="validateEmail" maxlength="125" minlength="0" caption="Email" value="<?php echo $contact_email; ?>" />
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
                        <input type="text" name="s_fax_no" id="s_fax_no" validate="validateInteger" maxlength="10" minlength="0" caption="Fax" value="<?php echo $contact_fax; ?>"/>
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

        <?php if ($usergroup != 'cms'): ?>
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
            <div class="buttr3">
                <button class="btn-submit7 w7" type="button" onClick="validateScholFields(this.form);">
                    <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Update Scholarship</p></div>
                </button>
            </div>

<?php $redirectLocation = "/";
	if ( isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']!="") )
		$redirectLocation = $_SERVER['HTTP_REFERER'];
?>
            <div class="buttr2">
                <button class="btn-submit11 w4" value="cancel" type="button" onClick="location.replace('<?php echo $redirectLocation;?>');" >
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
                                                      selectMultiComboBox(document.getElementById('c_categories'),selectCatArr);
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
                                                                              return flag;
                                                                      }
                                                                      else{
                                                                              <?php if ($usergroup != "cms") : ?>
                                                                              validateCaptchaForListing();
                                                                              <?php  else : ?>
                                                                              objForm.submit();
                                                                              <?php endif; ?>
                                                                      }
                                                              }
                                                              else{
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
                                                              $('addbutton').style.display = "none";

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
													  {			// Remove href tag from display string
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
                                                              $('si_colleges').innerHTML = '<option value="">Select College</option>';
                                                              $('si_country').selectedIndex = 0;
                                                      }

                                                  function showInstitute ()
                                                  {
                                                          $('si_country').setAttribute('required','true');
                                                          $('si_cities').setAttribute('required','true');
                                                          $('si_colleges').setAttribute('required','true');
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
                                                          var overlayTitle = 'Select College';

                                                          var overLayForm = $('add_location').innerHTML;
                                                          $('add_location').innerHTML = '';
                                                          overlayContent = overLayForm;
                                                          overlayParent = $('add_location');

                                                          showOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent); 

                                                  }

                                                  function removeScholDoc(docId,divId)
                                                  {
                                                          $('doc_'+divId).innerHTML = '';
                                                          $('s_upload_'+divId).style.display = 'inline';
                                                          var url = "/enterprise/Enterprise/removeScholMedia/"+<?php echo $scholarship_id;?>+"/"+docId;
                                                          new Ajax.Request(url,{method:'get'});
                                                  }

                                                  addOnFocusToopTip(document.scholarship_main);
                                                  addOnBlurValidate(document.scholarship_main);
                                                  tinyMCE.init({ mode : "textareas", theme : "simple",editor_selector : "mceEditor", editor_deselector : "mceNoEditor"});
                                                  fillProfaneWordsBag();

   <?php if($packType > 0){ ?>
   packSpecificChanges("<?php echo $listingType; ?>");
   <?php  }else{ ?>
   packSpecificChangesCMS("<?php echo $listingType; ?>");
   <?php  } ?>

                                              </script>	
                                              <!--End_Scholarship_Listing_Main_Form-->
                                              <?php  $this->load->view('common/overlay'); ?>	
