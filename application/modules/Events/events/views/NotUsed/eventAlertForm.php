<?php
      $email = '';	 	
      if(is_array($validateuser))
      { 	 	    
	      $userInfo = explode('|',$validateuser[0]['cookiestr']); 
	      $email = $userInfo[0];	
      }

      
?>
<!--Event Alert-->
<div id="categoryHolderForAlert" style="display:none;">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Category:</div>
				
				
				<div class="formInput inline" id="categoryPlaceForAlert">&nbsp;</div>	
				<div class="formField errorPlace">
		                        <div id="board_id_error" class="errorMsg"></div>
		                </div>	
				<br clear="left" />
</div>
<div id="eventAlertForm" class="w102" style="display:none; width:500px;" >
<div id="eventAlertForm_error"></div>
			
			<div class="row">
				<div class="mar_left_10p normaltxt_11p_blk bld fontSize_12p" id="formTitle3">Event Alert</div>
			</div>
		  	<div class="lineSpace_5">&nbsp;</div>
			<div class="row">
				<div class="mar_left_10p normaltxt_11p_blk">Send me an alert, <span class="mar_left_10p normaltxt_11p_blk" id="formCatTitle3">if an event is posted for </span></div>
			</div>
		  	<div class="lineSpace_5">&nbsp;</div>
			
			<div class="row">
			</div>
			<div class="lineSpace_10">&nbsp;</div>
		
			<?php $eventUrl = site_url('alerts/Alerts/createUpdateAlert').'/12';
			echo $this->ajax->form_remote_tag( array('url'=> $eventUrl,'success' => 'javascript:updateEventHomePage(request.responseText);'));  ?>	

						
			<input type="hidden" name="productId" id="productId"  value="6"/>	
			<input type="hidden" name="productName" id="productName"  value="event"/>	
			<input type="hidden" id="alertType" name="alertType" value="byLocation"/>
			<input type="hidden" id="city" name="city" value=""/>
			<input type="hidden" name="alertName" id="alertName"  value=""/>	
			<input type="hidden" name="alertId" id="alertId"  value=""/>	
			<input type="hidden" name="locationCrumb" id="locationCrumb" value=""/>
	
			<!-- start of location div -->
			<div id="locationPlace">
	<div class="row">
			<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">
				 Country : &nbsp;
			</div>
			<div>
				<select name="country" id="countryAl" onChange="getCitiesForCountry('',2,'Al');">
					<?php 
                foreach($country_list as $key => $countryName) {
                        $countryId = $key;
                        $countryName = $countryName;
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
			<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">	&nbsp; City
			</div>
			<div class="normaltxt_11p_blk">
				<select id="citiesAl" name="cities" onChange="checkCity(this, '','','Al');">
				</select>
				<input type="text" validate="validateStr" maxlength="25" minlength="0" name="cities_other" id="citiesAl_other" value="" style="display:none"/>
			</div>
			<br clear="left" />
		</div>
		<div class="row" style="display:none">
			<div class="float_L w12 pd_left_10p"> &nbsp; </div>
			<div class="errorMsg"  id="citiesAl_other_error">  </div>
			<br clear="left" />
		</div>
		<div class="lineSpace_5">&nbsp;</div>
	
	</div>
			<div class="lineSpace_10">&nbsp;</div>
			<!-- end of location div -->
			<div class="row">
				<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Frequency:</div>
				<div class="float_L normaltxt_11p_blk">
				<select class="w20" name="frequency" id="frequency">
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
			<div id = "alertemail"> Email <?php echo  $email; ?></div> 
<div class="lineSpace_5">&nbsp;</div>
		</div>
		<br clear="left" />
			</div>
		  	<div class="lineSpace_10">&nbsp;</div>								

			<div class="row">
				<div style="margin-left:120px">
					<div class="buttr3">
						<button class="btn-submit13 w3" type="Submit" name="Submit" onClick="return validateAlertForm2(this.form);">
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
