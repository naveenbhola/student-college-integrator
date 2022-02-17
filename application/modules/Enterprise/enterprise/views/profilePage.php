<?php $headerComponents = array(
								'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
								'js'	=>	array('common','enterprise','CalendarPopup','prototype','newcommon','listing','tooltip','cityList'),
								'displayname'=> (isset($validity[0]['displayname'])?$validity[0]['displayname']:""),
								'tabName'	=>	'',
								'title'	=>	'Enterprise Account Profile',
								'taburl' => site_url('enterprise/Enterprise'),
								'metaKeywords'	=>''
								);
								
$this->load->view('enterprise/headerCMS', $headerComponents); ?>
<div style="line-height:10px">&nbsp;</div>
<?php $this->load->view('enterprise/cmsTabs'); ?>
<div style="line-height:10px">&nbsp;</div>
<div class="mar_full_10p">
			<div style="width:223px; float:left">
				<div class="raised_greenGradient">
					<b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
					<div class="boxcontent_greenGradient1">
						<div class="mar_full_10p">
							<div class="lineSpace_5">&nbsp;</div>
							<div class="fontSize_12p bld">Basic Account Information</div>
							<div class="lineSpace_10">&nbsp;</div>
                                                        <div class="OrgangeFont bld">Institute Name:</div>
							<div><?php echo $details['businessCollege'];?></div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="OrgangeFont bld">Display Name:</div>
							<div><?php echo $details['displayname'];?></div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="OrgangeFont bld">Login Email ID:</div>
							<div><?php echo $details['email'];?></div>
							<div class="lineSpace_20">&nbsp;</div>
							<div><a href="changePassword" class="bld">Change Password</a></div>
							<div class="lineSpace_20">&nbsp;</div>
						</div>
					</div>
				  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
			</div>
			<form action="/enterprise/Enterprise/profileEdit" method="POST" id="profileEdit">
			<div style="margin-left:233px">
					<div class="OrgangeFont fontSize_14p bld">Modify Profile Data</div>
					<div class="lineSpace_10">&nbsp;</div>
					<?php if($_COOKIE['profileEdit']) { ?>
					<div class="fontSize_12p bld">Profile Edited Successfully.</div>
					<?php setcookie('profileEdit',0,'/',COOKIEDOMAIN); ?>
					<?php } ?>
					<div style="float:left; width:100%">
					<div class="raised_lgraynoBG">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
							<div class="boxcontent_lgraynoBG">
								<div class="float_L" style="width:100%">
									<!--div class="OrgangeFont mar_full_10p fontSize_13p bld lineSpace_28">Business Information</div-->
									<!--div class="grayLine"></div-->
									<!--div class="lineSpace_10">&nbsp;</div-->
									<?php if(is_array($details['categories'])>0):?>
									<div>
											<div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Category of Course offered:</div>
											<div class="" style="margin-left:200px">
											<?php $selCats = explode(',',$details['categories']); ?>
										    	<select name="c_categories[]" id="c_categories" multiple size="10" tip="course_categories" style="width:60%">
											    <?php foreach ($data['categories'] as $cats) {
											                if ($cats[2]=="base") { ?>
												            <optgroup label="<?php echo $cats[1];?>">
												<?php } else { ?>
													<option value="<?php echo $cats[0];?>" <?php if(in_array($cats[0],$selCats)) echo " selected "; ?>><?php echo $cats[1];?></option>
													<?php } ?>
												<?php } ?>
												</select>
											</div>
									</div>
									<?php endif;?>
									<div class="row errorPlace" style="margin-top:2px;">
									    	<div class="float_L">&nbsp;</div>
								            <div class="errorMsg" id="c_categories_error" style="margin-left:200px"></div>
								            <div class="clear_L"></div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div class="OrgangeFont mar_full_10p fontSize_13p bld lineSpace_28">Contact Information</div>
									<div class="grayLine"></div>
									<div class="lineSpace_10">&nbsp;</div>
									<div>
											<div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Contact Name:<span class="redcolor">*</span></div>
											<div class="" style="margin-left:200px">
												<input type="text" value="<?php echo $details['contactName'];?>" name="contactName" id="contactName" size="30" tip="contactname_id" maxlength="100" minlength="3" validate="validateStr" caption="Contact Name" required="true"/>
											</div>
										    <div class="clear_L"></div>
									</div>
									<div class="row errorPlace" style="margin-top:2px;">
									    	<div class="float_L">&nbsp;</div>
								            <div class="errorMsg" id="contactName_error" style="margin-left:200px"></div>
								            <div class="clear_L"></div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div>
											<div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Contact Address:</div>
											<div class="" style="margin-left:200px"><textarea style="width:200px; height:60px" name="contact_address" id="contact_address" maxlength="500" minlength="3" validate="validateStr" caption="Contact Address"><?php echo $details['contactAddress'];?></textarea></div>
											<div class="clear_L"></div>
									</div>
									<div class="row errorPlace" style="margin-top:2px;">
									    	<div class="float_L">&nbsp;</div>
								            <div class="errorMsg" id="contact_address_error" style="margin-left:200px"></div>
								            <div class="clear_L"></div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div>
											<div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Country:</div>
											<div class="" style="margin-left:200px">
								            <select id="country" name="countries" onChange="getCitiesForCountry()"; style="width:100px" caption="Country">
											      <?php
													  foreach($data['countryList'] as $country) :
													  $countryId = $country['countryID'];
													  $countryName = $country['countryName'];
													  if($countryId == 1) { continue; }
													  $selected = "";
													  if($countryId == $details['country']) { $selected = "selected='selected'"; }
											       ?>
											       <option value="<?php echo $countryId; ?>" <?php  echo $selected; ?>><?php echo $countryName; ?></option>
											       <?php endforeach; ?>
											    </select>
											</div>
											<div class="clear_L"></div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
                                                                        <div>
											<div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">City:</div>
                                                                                        <div class="" style="margin-left:200px">
                                                                                            <select id="cities" name="cities" style="width:150px" caption="City" onChange="checkCity(this, 'updateInstitutes');">
                                                                                            </select>
                                                                                            <!--input type="text" validate="validateStr" maxlength="255" required=true  minlength="2" name="otherCity" id="cities_other" style="display:none" value="" caption="City Name"/-->
                                                                                        </div>
                                                                                        <div class="clear_L"></div>
                                                                                        <div class="row errorPlace" style="margin-top:2px;">
                                                                                            <div class="r1_1">&nbsp;</div>
                                                                                            <div class="r2_2 errorMsg" id="cities_other_error"></div>
                                                                                        </div>
	 <div class="clear_L"></div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div>
											<div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Pincode:</div>
											<div class="" style="margin-left:200px">
												<input type="text" value="<?php echo $details['pincode'];?>" name="pincode" id="pincode" maxlength="10" minlength="5"  size="30" validate="validateInteger" caption="Pin Code" />
											</div>
											<div class="clear_L"></div>
									</div>
									<div class="row errorPlace" style="margin-top:2px;">
									    	<div class="float_L">&nbsp;</div>
								            <div class="errorMsg" id="pincode_error" style="margin-left:200px"></div>
								            <div class="clear_L"></div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div <?php if($details['mobile']>0):?> style="display:none;"<?php endif;?>>
											<div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Phone:<span class="redcolor">*</span></div>
											<div class="" style="margin-left:200px">
												<input name="mobile" value="<?php echo $details['mobile'];?>" type="text" id="mobile" maxlength="15" minlength="4" size="30" validate="validateInteger" caption="Phone Number" tip="phone_id" required="true"/>
											</div>
											<div class="clear_L"></div>
									</div>
									<div class="row errorPlace" style="margin-top:2px;">
									    	<div class="float_L">&nbsp;</div>
								            <div class="errorMsg" id="mobile_error" style="margin-left:200px"></div>
								            <div class="clear_L"></div>
									</div>
									<div class="clear_L"></div>
									<div class="lineSpace_28">&nbsp;</div>
									<div class="grayLine"></div>
									<div class="lineSpace_10">&nbsp;</div>
									<div class="txt_align_c">
											<button class="btn-submit19" onclick="return validateProfile();" type="submit" value="" style="width:70px">
													<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Save</p></div>
											</button>
									</div>
								</div>
								<div class="clear_L"></div>
							</div>
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
					<div class="clear_L"></div>
			</div>
			
			<div class="clear_L"></div>
</div>
<?php
			$CI = & get_instance();
			$CI->load->library('security');
			$CI->security->setCSRFToken();
			?>
			<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
			</form>
</div>
<div class="lineSpace_35">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>
<script>
addOnBlurValidate($('profileEdit'));
addOnFocusToopTip($('profileEdit'));
fillProfaneWordsBag();
function validateProfile()
{
	var flag = validateFields($('profileEdit'));
	var catFlag = validateCatCombo('c_categories',10,1);
	if (flag==false || catFlag == false) {
		return false;
	}
}
getCitiesForCountry(<?php echo $details['city'];?>);
</script>
