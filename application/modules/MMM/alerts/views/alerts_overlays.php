<script>
var categoryTreeMain = eval(<?php echo $category_tree; ?>);	
var SITE_URL = '';
var appId = <?php echo $appId; ?>;
var loggedInUserMobileNo = '<?php echo $mobile; ?>';
</script>

<?php 
	$formText1 = array('courseAndCollege' => array('title' => "Course/Institute Alert",'catTitle' => 'a new course or Institute is added to ','count'=>$this->courseAndCollegeCount),
			  'scholarship' => array('title' => "Scholarship Alert",'catTitle' => 'a new scholarship is added to ','count'=>$this->scholarshipCount),
			  'examForm' => array('title' => "Examination Form Alert",'catTitle' => 'a examination form is released to ','count' => $this->examFormCount),
			  'blog' => array('title' => "Blog Alert",'catTitle' => 'a blog is created for ','count' => $this->blogCount),	
			  'messageBoard' => array('title' => "Question Alert",'catTitle' => 'a question is posted for ','count'=>$this->messageBoardCount),		
			  'event' => array('title' => "Event Alert",'catTitle' => 'an event is posted for ','count' => $this->eventCount),
			  'collegeRating' => array('title' => "College Rating Alert",'catTitle' => 'someone rates my college for ','count' => $this->collegeRatingCount),
			  'commentAlert' => array('title' => "Comment Alert",'catTitle' => 'a comment is posted for ','count' =>0),		
			  'saveSearchAlert' => array('title' => "Search Alert",'catTitle' => 'a search result is added for ','count' =>0),
			);
       $jsonFormText1 = json_encode($formText1);
  ?>
  <?php 
	echo "<script language=\"javascript\"> ";
	echo "var formText1 = '".$jsonFormText1."';";
	echo 'var formTextArray = eval("eval("+formText1+")");';
	echo "</script>";
  ?>	

<div class="row" id="categoryCombos" style = "display:none;">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Category:</div>
		<div class="formInput inline" id="categoryPlace">&nbsp;</div>	
		<!-- <input type="hidden" name="category" id="board_id" validate="validateStr" maxlength="3" minlength="1" required="1" caption="Category" value=""/> -->
		<input type="hidden" name="categoryCrumb" id="categoryCrumb" value=""/>	
		<div class="formField errorPlace">
                        <div id="board_id_error" class="errorMsg"></div>
                </div>	
		<br clear="left" />
</div> 
	
<div id="locationCombos" style = "display:none;">
	<div class="row">
			<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">
				 Country : &nbsp;
			</div>
			<div>
				<select name="country" id="country" onChange="getCitiesForCountry('',2);">
					<?php 
                foreach($countryList as $country) {
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
			<br clear="left" />
		</div> 
		<div class="lineSpace_11">&nbsp;</div>
		<div class="row" id="cityPlace">
			<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">City:
			</div>
			<div class="normaltxt_11p_blk">
				<select id="cities" onChange="checkCity(this, 'updateInstitutes',this.value);validateCitiesAndInstitue(this);">
				</select>
				<input type="text" validate="validateStr" maxlength="25" minlength="0" name="cities_other" id="cities_other" value="" style="display:none"/>
			</div>
			<br clear="left" />
		</div>
		<div class="row" style="display:none">
			<div class="float_L w12 pd_left_10p"> &nbsp; </div>
			<div class="errorMsg"  id="cities_other_error">  </div>
			<br clear="left" />
		</div>
		<div class="lineSpace_11">&nbsp;</div>
		<div class="row" id="collegePlace" style="display:inline;">
			<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">
				College Name : &nbsp;
			</div>
			<div class="normaltxt_11p_blk float_L ">
				<select id="colleges" onChange="checkInstitute(this);">
				</select>
				<input type="text" validate="validateStr" maxlength="125" minlength="0" name="colleges_other" id="colleges_other" value="" style="display:none"/>
			</div>

			<br clear="left" />
		</div>
		<div class="row" style="display:none">
			<div class="normaltxt_11p_blk bld float_L txt_align_r w20 mar_left_27p lineSpace_20"> &nbsp; </div>
			<div class="float_L errorMsg"  id="colleges_other_error">  </div>
			<br clear="left" />
		</div>
</div>

<!--Create Course/College Alert-->
<div id="form1" class="w102"  style="display:none; width:500px;">
<div id="error_msg1"></div>
	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk bld fontSize_12p" id="formTitle1"></div>
	</div>
		<div class="lineSpace_5">&nbsp;</div>

	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk">Send me an alert if <span class="mar_left_3p normaltxt_11p_blk" id="formCatTitle1"></span>
        </div>
	</div>
		<div class="lineSpace_5">&nbsp;</div>
	
	
	<?php $url = site_url('alerts/Alerts/createUpdateAlert').'/12';
	     echo $this->ajax->form_remote_tag( array('url'=> $url,'success' => 'javascript:updateMainPage(appId,1,request.responseText,'.$noOfAlerts.');'));  
	?>

	<div class="row">
				<div class="mar_left_10p normaltxt_11p_blk"><input type="radio" name="alertType" id="alertType11" value="byCategory" onClick="showDiv(new Array('categoryPlaceform1','locationPlace1'),0);setTitle(1,'category');" checked /> Category &nbsp;&nbsp;<input type="radio" name="alertType" id="alertType12" value="byCountry"  onClick="showDiv(new Array('categoryPlaceform1','locationPlace1'),1);setTitle(1,'country');"/> Country &nbsp;</div>
	</div>
		  	<div class="lineSpace_10">&nbsp;</div>

	<input type="hidden" name="productId" id="productId1"  value=""/>	
	<input type="hidden" name="productName" id="productName1"  value=""/>
	<input type="hidden" name="countryName" id="countryName1"  value=""/>	
	<input type="hidden" name="alertName" id="alertName1"  value=""/>				
	<input type="hidden" name="alertId" id="alertId1"  value=""/>
	
	<div class="row" id="categoryPlaceform1">
			
	</div>
		<div class="lineSpace_5">&nbsp;</div>						
					
	<div class="row" id="locationPlace1" style="display:none;">
			
	</div>
		<div class="lineSpace_5">&nbsp;</div>	
	
	
	<div class="row">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Frequency:</div>
		<div class="float_L normaltxt_11p_blk">
			<select class="w20" name="frequency" id="frequency1">
				<option value="daily">Once a day</option>
				<option value="weekly">Once a week</option>
				<option value="monthly">Once a month</option>
			</select>
		</div>
		<br clear="left" />
	</div>
		<div class="formField errorPlace">
                        <div id="frequency1_error" class="errorMsg"></div>
                </div>
		<div class="lineSpace_10">&nbsp;</div>	
	

	<div class="row">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20 bld">Deliver to:</div>
		<div class="float_L normaltxt_11p_blk" id="deliverto1">
			<div> <input type="checkbox" name="emailCheck" id="emailCheck1" value="on" checked disabled/> Email <span style="margin-left:22px;"><?php  echo $email; ?></span></div>
<div class="lineSpace_5">&nbsp;</div>
			<div> <input type="checkbox" name="smsCheck" id="smsCheck1" value="on" onClick="showTextBox(this,'loggedInUserMobile');" /> Mobile <span style="margin-left:18px;" id="mobileNoPlace"><?php if($mobile == ''): ?><input type="text" name="loggedInUserMobile" id="loggedInUserMobile" maxlength="15" style="display:none;"/> <?php endif; ?></span> &nbsp;</div>
<div class="lineSpace_5">&nbsp;</div>
<div class="formField errorPlace">
        <div id="smsCheck1_error" class="errorMsg"></div>
</div>
<div class="lineSpace_5">&nbsp;</div>
		</div>
		<br clear="left" />
	</div>
		<div class="lineSpace_10">&nbsp;</div>								

	<div class="row">
		<div style="margin-left:120px">
			<div class="buttr3">
				<button class="btn-submit13 w3" type="Submit" name="Submit" id="alertSubmitButton1" onClick="return validateAlertForm(this.form,1);">
					<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog" id="submitbutton1">Create alerts</p></div>
				</button>
			</div>
			<div class="buttr2">
				<button class="btn-submit5 w3" type="button" onClick="hideOverlay();">
				<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
				</button>
			</div>
			<div class="clear_L"></div>
		</div>			
	</div>	
	</form>
	<div style="line-height:15px;clear:both">&nbsp;</div>
</div>
<!--Create Course/College Alert -->

<!--Create Blog Alert-->
<div id="form2" class="w102"  style="display:none; width:500px;">
<div id="error_msg2"></div>
	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk bld fontSize_12p" id="formTitle2"></div>
	</div>
		<div class="lineSpace_5">&nbsp;</div>

	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk">Send me an alert if <span class="mar_left_3p normaltxt_11p_blk" id="formCatTitle2"></span></div>
	</div>
		<div class="lineSpace_5">&nbsp;</div>
	
	<?php $url = site_url('alerts/Alerts/createUpdateAlert').'/12';
	     echo $this->ajax->form_remote_tag( array('url'=> $url,'success' => 'javascript:updateMainPage(appId,2,request.responseText,'.$noOfAlerts.');'));  
	?>
				
	<input type="hidden" name="productId" id="productId2"  value=""/>	
	<input type="hidden" name="productName" id="productName2"  value=""/>
	<input type="hidden" name="alertType" id="alertType2"  value="byCategory"/>
	<input type="hidden" name="alertName" id="alertName2"  value=""/>				
	<input type="hidden" name="alertId" id="alertId2"  value=""/>
					
	<div class="row" id="categoryPlaceform2">
			
	</div>
		<div class="lineSpace_10">&nbsp;</div>			
	<div class="row">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Frequency:</div>
		<div class="float_L normaltxt_11p_blk">
			<select class="w20" name="frequency" id="frequency2">
				<option value="daily">Once a day</option>
				<option value="weekly">Once a week</option>
				<option value="monthly">Once a month</option>
			</select>
		</div>
		<br clear="left" />
	</div>
		<div class="formField errorPlace">
                        <div id="frequency1_error" class="errorMsg"></div>
                </div>
		<div class="lineSpace_10">&nbsp;</div>	
	
	<div class="row" id="authorPlace2" style="display:none;">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Author:</div>	
		<div class="float_L normaltxt_11p_blk">
			<select class="w20" id="author2" name="author">
				<option value="Manish">Manish</option>
				<option value="Shirish">Shirish</option>
				<option value="Vibhu">Vibhu</option>
			</select>
		</div>
		<br clear="left" />
	</div>
		<div class="lineSpace_10">&nbsp;</div>									
	
	<div class="row">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20 bld">Deliver to:</div>
		<div class="float_L normaltxt_11p_blk" id="deliverto1">
			<div>Email - <span style="margin-left:22px;"><?php echo $email; ?></span></div>
		</div>
		<br clear="left" />
	</div>
		<div class="lineSpace_10">&nbsp;</div>

	<div class="row">
		<div style="margin-left:120px">
			<div class="buttr3">
				<button class="btn-submit13 w3" type="Submit" name="Submit" id="alertSubmitButton2" onClick="return validateDscussionForm(this.form);">
					<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog" id="submitbutton2">Create alerts</p></div>
				</button>
			</div>
			<div class="buttr2">
				<button class="btn-submit5 w3" type="button" onClick="hideOverlay();">
				<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
				</button>
			</div>
			<div class="clear_L"></div>
		</div>			
	</div>
	
	</form>
</div>
<!--Create Blog Alert -->


<!--Event Alert-->
<div id="form3" class="w102" style="display:none; width:500px;" >
<div id="error_msg3"></div>
			
			<div class="row">
				<div class="mar_left_10p normaltxt_11p_blk bld fontSize_12p" id="formTitle3"></div>
			</div>
		  	<div class="lineSpace_5">&nbsp;</div>
			<div class="row">
				<div class="mar_left_10p normaltxt_11p_blk">Send me an alert if<span class="mar_left_3p normaltxt_11p_blk" id="formCatTitle3"></span></div>
			</div>
		  	<div class="lineSpace_5">&nbsp;</div>
			
			<?php $eventUrl = site_url('alerts/Alerts/createUpdateAlert').'/12';
			echo $this->ajax->form_remote_tag( array('url'=> $eventUrl,'success' => 'javascript:updateMainPage(appId,3,request.responseText,'.$noOfAlerts.');'));  ?>	

						
			<input type="hidden" name="productId" id="productId3"  value=""/>	
			<input type="hidden" name="productName" id="productName3"  value=""/>	
			<input type="hidden" id="city" name="city" value=""/>
			<input type="hidden" name="alertType" id="alertType3"  value="byLocation"/>
			<input type="hidden" name="alertName" id="alertName3"  value=""/>	
			<input type="hidden" name="alertId" id="alertId3"  value=""/>	
			<input type="hidden" name="locationCrumb" id="locationCrumb" value=""/>
			
			<div class="row" id="categoryPlaceform3">
			</div>
			<!-- start of location div -->
			<div id='locationPlace3'>
			</div> 
			<!-- end of location div -->

			<div class="row">
				<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Frequency:</div>
				<div class="float_L normaltxt_11p_blk">
				<select class="w20" name="frequency" id="frequency3">
					<option value="daily">Once a day</option>
					<option value="weekly">Once a week</option>
					<option value="monthly">Once a month</option>
				</select>
			</div>
				<br clear="left" />
			</div>
			<div class="formField errorPlace">
                        	<div id="frequency2_error" class="errorMsg"></div>
                	</div>	
		  	<div class="lineSpace_10">&nbsp;</div>				

			<div class="row">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20 bld">Deliver to:</div>
		<div class="float_L normaltxt_11p_blk" id="deliverto3">
			<div> Email <?php echo  $email; ?></div> 
<div class="lineSpace_5">&nbsp;</div>
		</div>
		<br clear="left" />
			</div>
		  	<div class="lineSpace_10">&nbsp;</div>								

			<div class="row">
				<div style="margin-left:120px">
					<div class="buttr3">
						<button class="btn-submit13 w3" type="Submit" name="Submit" id="alertSubmitButton3" onClick="return validateAlertForm2(this.form);">
							<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog" id="submitbutton3">Create alerts</p></div>
						</button>
					</div>
					
					<div class="buttr2">
							<button class="btn-submit5 w3" type="button" onClick="hideOverlay();">
							<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
							</button>
					</div>
					<div class="clear_L"></div>
				</div>			
			</div>
		</form>
	</div>
<!--Create Event Alert-->

<!--College rating Alert-->
<div id="form4" class="w102" style="display:none; width:500px;" >
<div id="error_msg4"></div>	
			
			<div class="row">
				<div class="mar_left_10p normaltxt_11p_blk bld fontSize_12p" id="formTitle4"></div>
			</div>
		  	<div class="lineSpace_5">&nbsp;</div>
			
			<div class="row">
				<div class="mar_left_10p normaltxt_11p_blk">Send me an alert if <span class="mar_left_3p normaltxt_11p_blk" id="formCatTitle4"></span></div>
			</div>
		  	<div class="lineSpace_5">&nbsp;</div>
			
			<?php  $createCollegeRating = site_url('alerts/Alerts/createUpdateAlert').'/12';
				echo $this->ajax->form_remote_tag( array('url'=> $url,'success' => 'javascript:updateMainPage(appId,4,request.responseText,'.$noOfAlerts.');'));  ?>	
			
			<div class="row">
				<div class="mar_left_10p normaltxt_11p_blk"><input type="radio" name="alertType" id="alertType41" value="byCategory" onClick="showDiv(new Array('categoryPlaceform4','locationPlace4'),0);" checked /> Category &nbsp;&nbsp;<input type="radio" name="alertType" id="alertType42" value="byCollege"  onClick="showDiv(new Array('categoryPlaceform4','locationPlace4'),1);" /> College &nbsp; </div>
			</div>
		  	<div class="lineSpace_10">&nbsp;</div>
			
			<input type="hidden" name="productId" id="productId4"  value=""/>	
			<input type="hidden" name="productName" id="productName4"  value=""/>
			<input type="hidden" name="alertType" id="alertType4"  value="byCollege"/>
			<input type="hidden" name="alertName" id="alertName4"  value=""/>				
			<input type="hidden" name="alertId" id="alertId4"  value=""/>	
			<input type="hidden" id="institute" name="institute" value=""/>
			<input type="hidden" name="instituteName" id="instituteName4"  value=""/>
			<div class="row" id="categoryPlaceform4">
			
			</div>
				<div class="lineSpace_10">&nbsp;</div>	
			
			<div class="row">
				<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Frequency:</div>
				<div class="float_L normaltxt_11p_blk">
				<select class="w20" name="frequency" id="frequency4">
					<option value="daily">Once a day</option>
					<option value="weekly">Once a week</option>
					<option value="monthly">Once a month</option>
				</select>
				</div>
				<br clear="left" />
			</div>
		  	<div class="lineSpace_10">&nbsp;</div>	
		
			<!-- start of location and college div -->
			<div id="locationPlace4">
			
				</div>
			<!-- end of location and college div -->

			<div class="row">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20 bld">Deliver to:</div>
		<div class="float_L normaltxt_11p_blk" id="deliverto4">
			<div style="margin-left:22px"> Email <?php echo  $email; ?></div> 
<div class="lineSpace_5">&nbsp;</div>
		</div>
		<br clear="left" />
			</div>
		  	<div class="lineSpace_10">&nbsp;</div>										

			<div class="row">
				<div style="margin-left:120px">
					<div class="buttr3">
						<button class="btn-submit13 w3" type="Submit" name="Submit" id="alertSubmitButton4" onClick="return validateAlertForm3(this.form);">
							<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog" id="submitbutton4">Create alerts</p></div>
						</button>
							
					</div>
					<div class="buttr2">
						<button class="btn-submit5 w3" type="button" onClick="hideOverlay();">
						<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
						</button>
					</div>
					
					<div class="clear_L"></div>
				</div>			
			</div>
		</form>
	</div>
<!--college rating Alert-->

<!--comment rating Alert-->
<div id="form5" class="w102" style="display:none; width:500px;" >
<div id="error_msg3"></div>	
			
			<div class="row">
				<div class="mar_left_10p normaltxt_11p_blk bld fontSize_12p" id="formTitle5"></div>
			</div>
		  	<div class="lineSpace_5">&nbsp;</div>
			
			<div class="row">
				<div class="mar_left_10p normaltxt_11p_blk">Send me an alert if <span class="mar_left_3p normaltxt_11p_blk" id="formCatTitle5"></span></div>
			</div>
		  	<div class="lineSpace_5">&nbsp;</div>
			
			<div class="row">
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			
			<?php  $createCollegeRating = site_url('alerts/Alerts/createUpdateAlert').'/12';
				echo $this->ajax->form_remote_tag( array('url'=> $url,'success' => 'javascript:updateMainPage(appId,3,request.responseText,'.$noOfAlerts.');'));  
			?>	
			
			<input type="hidden" name="productId" id="productId5"  value=""/>	
			<input type="hidden" name="productName" id="productName5"  value=""/>
			<input type="hidden" name="alertType" id="alertType5"  value="byCollege"/>
			<input type="hidden" name="alertName" id="alertName5"  value=""/>				
			<input type="hidden" name="alertId" id="alertId5"  value=""/>	
			<!-- start of location and college div -->
			
			<div class="row" id="categoryPlaceform5">
			
			</div>
				<div class="lineSpace_10">&nbsp;</div>	
		
			
			<div class="row" id="productPlace5">
				<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Product:</div>
				<div class="float_L normaltxt_11p_blk">
				<select class="w20" name="product" id="product5" onChange="javascript:changeProduct(this);">
					<option value="4">Blog</option>
					<option value="5">Discussion</option>
				</select>
				</div>
				<br clear="left" />
			</div>
		  	<div class="lineSpace_10">&nbsp;</div>	
			

			
			<div class="row">
				<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Frequency:</div>
				<div class="float_L normaltxt_11p_blk">
				<select class="w20" name="frequency" id="frequency5">
					<option value="daily">Once a day</option>
					<option value="weekly">Once a week</option>
					<option value="monthly">Once a month</option>
				</select>
				</div>
				<br clear="left" />
			</div>
		  	<div class="lineSpace_10">&nbsp;</div>	
		
			<div class="row">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20 bld">Deliver to:</div>
		<div class="float_L normaltxt_11p_blk" id="deliverto4">
			<div style="margin-left:22px"> Email <?php echo  $email; ?></div> 
<div class="lineSpace_5">&nbsp;</div>
		</div>
		<br clear="left" />
			</div>
		  	<div class="lineSpace_10">&nbsp;</div>										

			<div class="row">
				<div style="margin-left:120px">
					<div class="buttr3">
						<button class="btn-submit13 w3" type="Submit" name="Submit" id="alertSubmitButton5" onClick="return validateAlertForm3(this.form);">
							<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog" id="submitbutton5">Create alerts</p></div>
						</button>
							
					</div>
					<div class="buttr2">
						<button class="btn-submit5 w3" type="button" onClick="hideOverlay();">
						<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
						</button>
					</div>
					
					<div class="clear_L"></div>
				</div>			
			</div>
		</form>
	</div>
<!--comment rating Alert-->

<!--Create Save Search Alert-->
<div id="form6" class="w102"  style="display:none; width:500px;">
<div id="error_msg6"></div>
	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk bld fontSize_12p" id="formTitle6"></div>
	</div>
		<div class="lineSpace_5">&nbsp;</div>

	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk">Send me an alert if <span class="mar_left_3p normaltxt_11p_blk" id="formCatTitle6"></span></div>
	</div>
		<div class="lineSpace_5">&nbsp;</div>
	
	<input type="hidden" name="productId" id="userId6"  value=""/>	
	<input type="hidden" name="productName" id="keyword6"  value=""/>
	<input type="hidden" name="countryName" id="location6"  value=""/>	
	<input type="hidden" name="alertName" id="type6"  value=""/>				
	
	<div class="row"> <div class="mar_left_10p normaltxt_12p_blk" id="keywordPlaceform6">
			
	</div>
	</div>
	
	<!--div class="lineSpace_5">&nbsp;</div>
	<div class="row"> <div class="mar_left_10p normaltxt_12p_blk" id="locationPlaceform6">
			
	</div>
	</div-->
	<div class="lineSpace_10">&nbsp;</div>						
					

	<div class="row">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Frequency:</div>
		<div class="float_L normaltxt_11p_blk">
			<select class="w20" name="frequency" id="frequency611">
				<option value="daily">Once a day</option>
				<option value="weekly">Once a week</option>
				<option value="monthly">Once a month</option>
			</select>
		</div>
		<br clear="left" />
	</div>
		<div class="formField errorPlace">
                        <div id="frequency6_error" class="errorMsg"></div>
                </div>
		<div class="lineSpace_10">&nbsp;</div>	
	
	<div class="row" id="locationPlace6" style="display:none;">
			
	</div>
		<div class="lineSpace_10">&nbsp;</div>	

	<div class="row">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20 bld">Deliver to:</div>
		<div class="float_L normaltxt_11p_blk" id="deliverto6">
			<div> <input type="checkbox" name="emailCheck" id="emailCheck6" value="on" checked disabled/> Email <span style="margin-left:22px;"><?php  echo $email; ?></span></div>
<div class="lineSpace_5">&nbsp;</div>
<div class="lineSpace_5">&nbsp;</div>
<div class="lineSpace_5">&nbsp;</div>
		</div>
		<br clear="left" />
	</div>
		<div class="lineSpace_10">&nbsp;</div>								

	<div class="row">
		<div style="margin-left:120px">
			<div class="buttr3">
				<button class="btn-submit13 w3" type="Submit" name="Submit" id="alertSubmitButton6" onClick="validateSaveSearch(<?php echo $noOfAlerts?>);">
					<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog" id="submitbutton6">Update alert</p></div>
				</button>
			</div>
			<div class="buttr2">
				<button class="btn-submit5 w3" type="button" onClick="hideOverlay();">
				<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
				</button>
			</div>
			<div class="clear_L"></div>
		</div>			
	</div>
	
	</form>
</div>
<!--Create Save Search Alert -->


<!-- code for home code -->
<select name="countrySelect" id="countrySelect" style="display:none;">
<option vlaue="1" selected></option>
</select>
<script>
var locationHolder = '';	
getCitiesForCountry('',2); // 2 is for removing both "All cities" & "others" both.
var completeCategoryTree = eval(<?php echo $categoryForLeftPanel; ?>);
createCategoryDropDownForParents('categoryPlace','board_id','category',true);

function parentCategoryChanged(category)
{
    document.getElementById('categoryCrumb').value = completeCategoryTree[category][0];
}
</script>
<script language="javascript">
var categoryHolder = document.getElementById('categoryCombos').innerHTML;
document.getElementById('categoryCombos').innerHTML = '';
var productId = '';
var productName = '';	
</script>
