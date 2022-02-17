<style>
.location-cont{width:70px; border:1px solid #dcdadb; font:normal 12px Arial, Helvetica, sans-serif;}
.location-header{background:#e8e8e8; padding:4px; overflow:hidden}
.location-details{padding:4px; clear:left; overflow:hidden}
.col-1,.col-2,.col-3,.col-4{width:300px; float:left;}
.col-2{width:200px;}
.col-3{width:200px;}
.col-4{width:200px;}
.loc-row{margin-bottom:3px}
.saveButtonNew{background:url(/public/images/saveButton.gif) 0 0 no-repeat; width:60px; height:24px; border:0 none; cursor:pointer}
</style>
<?php
 if(isset($locations[0]['country_id']) && $locations[0]['country_id'] != 2) {
     $isAbroadListing = 1;
 } else {
     $isAbroadListing = 0;
 }
?>
<div class="formHeader"><a class="formHeader" name="contact" style="text-decoration:none" >Manage Locations and Contact Details</a><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'location-cont_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></div>
<div id="location-cont_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('location-cont_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Check & verify all the contact details before uploading/changing them either by institute’s website or by calling them before saving the contact details.</li>	
					<li>Save the contact details.</li>
								
					</ul>
			</div> 
<div class="line_1"></div>
<div class="spacer10 clearFix"></div>
<strong>Locations already added</strong>
<div class="spacer8 clearFix"></div>
<div class="location-cont" id="outer_location_contact_container" style="margin-left:15px; width:600px">
	<?php $locationCount = count($locations);
  if($locationCount > 0 ) { ?>
        <div class="location-header"><strong>Locations</strong></div>
        <div class="location-details" id="main_location_contact_container"><?php
            for($i = 0; $i < $locationCount; $i++) {    ?>
                <div class="loc-row" id="actual_loc_data_container_<?=$i?>"><input type="radio" name="locationContactRadioBttn" group="radioBttns" value="<?=$i?>" /> <?php echo $locations[$i]['city_name'].($locations[$i]['locality'] == "" ? "" : " -- ".$locations[$i]['locality']); ?></div><?php
            } ?>
        </div>
        <div style="border-top:1px solid #e8e8e8; padding:8px;">
            <img src="/public/images/edit-icn.gif" style="vertical-align:bottom" /> <a href="javascript:void(0);" onClick="javascript: editLocationContactData();">Edit</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <img src="/public/images/del-icn.gif" style="vertical-align:bottom" /> <a href="javascript:void(0);" onClick="javascript: deleteLocationContactData();">Delete</a>
        </div>
        <div style="clear:both"></div><?php
  } else {  ?>
        <div id="no_location_error_msg_div" style="height:50px;padding:5px;"><strong>No location has been added. Please add location from below.</strong></div>
<?php
  } // End of if($locationCount > 0 ).
  ?>
</div>

<div class="spacer20 clearFix"></div>
<strong>Add/Modify Locations and Contact Details</strong>
<div class="spacer15 clearFix"></div>
		<div class="row">
			<div style="float:left;width:200px;line-height:22px; padding-right:5px"><div class="txt_align_r bld">Name of person:</div></div>
			<div style="float:left;width:220px"><div><input type="text" maxlength="100" minlength="5" id="c_cordinator_name" name="c_cordinator_name" caption="Name" style="width:150px" tip="listing_name" validate="validateStr" profanity="true" /></div>
			<div style="display:none;"><div style="margin-top:2px" class="errorMsg" id="c_cordinator_name_error"></div></div>
			</div>
			<div style="float:left;width:40px;line-height:20px">&nbsp;</div>
			<div style="float:left;width:275px">&nbsp;</div>
			<div class="clear_L withClear">&nbsp;</div>
		</div>

		<div class="spacer15 clearFix"></div>

		<div class="row">
			<div style="float:left;width:200px;line-height:22px; padding-right:5px"><div class="txt_align_r bld">Main phone:</div></div>
			<div style="float:left;width:160px"><div><input type="text" style="width:150px" tip="listing_main_phone_no" caption="Main phone" name="main_phone_number"  id="main_phone_number" minlength="10" maxlength="15" validate="validateInteger" /></div>
			<div style="display:none"><div style="margin-top:2px" class="errorMsg" id="main_phone_number_error"></div></div>
			</div>
			<div style="float:left;width:100px;line-height:22px; padding-right:5px"><div class="txt_align_r bld">Mobile number:</div></div>
			<!--div style="float:left;width:275px"><div><input type="text" caption="Mobile number" minlength="10" id="main_mobile_number" name="main_mobile_number" tip="listing_main_mobile_no" maxlength="10" style="width:150px" leftPosition="85" validate="validateInteger" /></div-->
			<div style="float:left;width:350px"><div><input type="text" caption="Mobile number" id="main_mobile_number" name="main_mobile_number" tip="listing_main_mobile_no" style="width:150px" leftPosition="85" maxlength="20" /></div>
			<div style="display:none"><div style="margin-top:2px" class="errorMsg" id="main_mobile_number_error" ></div></div>
			</div>
			<div class="clear_L withClear">&nbsp;</div>
		</div>

        <div class="row" style="padding:5px 0 ;">
			<div style="float:left;width:200px;line-height:17px; padding-right:5px">&nbsp;</div>
			<div style="float:left;width:210px">
			<div><a id="extra_phone_number" href="javascript:void(0);" onClick="return removephones();" >+ Add more phone numbers</a></div>
			</div>
			<div style="float:left;width:50px;line-height:20px">&nbsp;</div>
			<div style="float:left;width:275px">&nbsp;</div>
			<div class="clear_L withClear">&nbsp;</div>
		</div>

            <div class="row" style="display:none;padding-bottom:10px" id="alternate_phones">
			<div style="float:left;width:200px;line-height:22px; padding-right:5px"><div class="txt_align_r">Alternate phone:</div></div>
			<div style="float:left;width:160px"><div><input type="text" tip="listing_main_phone_no" caption="Alternate phone" name="alternate_numbers"  id="alternate_numbers" minlength="10" maxlength="15" blurMethod="contact_number_to_blur('alternate_numbers','Alternate phone');" style="width:150px" /></div>
			<div style="display:none"><div style="margin-top:2px" class="errorMsg" id="alternate_numbers_error"></div></div>
			</div>
			<div style="float:left;width:100px;line-height:22px; padding-right:5px"><div class="txt_align_r">Fax number:</div></div>
			<div style="float:left;width:280px;line-height:17px; padding-right:5px"><div><input type="text" tip="listing_main_phone_no" caption="Fax number" name="fax_number"  id="fax_number12" minlength="10" maxlength="15" blurMethod="contact_number_to_blur('fax_number12','Fax number');" style="width:150px" /></div>
			<div style="display:none"> <div style="margin-top:2px; line-height:normal" class="errorMsg" id="fax_number12_error"></div></div>
			</div>
			<div class="clearFix"></div>
		</div>
        
		<div class="row">
			<div style="float:left;width:200px;line-height:22px; padding-right:5px"><div class="txt_align_r bld">Email Address:</div></div>
			<div style="float:left;width:160px"><div><!--input type="text" style="width:150px;" name="c_cordinator_email" maxlength="125" minlength="0" id="c_cordinator_email" caption="Email" /-->
                            <input type="text" value="" id="c_cordinator_email" caption="Email" minlength="0" maxlength="125" validate="validateEmail" name="c_cordinator_email" style="width:150px;" profanity="true"></div>
			<div style="display:none"><div style="margin-top:2px" class="errorMsg" id="c_cordinator_email_error"></div></div>
			</div>
			<div style="float:left;width:100px;line-height:22px; padding-right:5px"><div class="txt_align_r bld">Website:</div></div>
			<div style="float:left;width:250px;line-height:17px; padding-right:5px"><div><input name="c_website" maxlength="250" minlength="0" style="width:150px;" id="c_website" caption="Institute Website Address" type="text" /></div>
			<div style="display:none"><div style="margin-top:2px" class="errorMsg" id="c_website_error"></div></div>
			</div>
			<div class="clearFix"></div>
		</div>

        <div class="spacer10 clearFix"></div>
		<div class="row">
			<div style="float:left;width:200px;line-height:17px; padding-right:5px"><div class="txt_align_r bld">Country<span class="redcolor fontSize_13p">*</span>:</div></div>
			<div style="float:left;width:160px">
				<div>
					<select required="true" tip="institute_country"  name="country[]" id="country" minlength="1" maxlength="100" style="width:156px" onchange="getCitiesForCountry('',false,'',false);clearLocality();">
						<?php
							foreach($country_list as $countryItem) {
								$countryId = $countryItem['countryID'];
								$countryName = $countryItem['countryName'];
								if($countryId == 1) { continue; }
                                                                $selected = '';
						?>
							<option value="<?php echo $countryId; ?>" <?php echo $selected; ?> ><?php echo $countryName; ?></option>
						<?php
							}
						?>                        
					</select>
				</div>
				<div style="display:none"><div style="margin-top:2px" class="errorMsg" id="country_error" ></div></div>
			</div>
			<div style="float:left;width:100px;line-height:20px; padding-right:5px"><div class="txt_align_r bld">City<span class="redcolor fontSize_13p">*</span>:</div></div>
			<div style="float:left;width:360px">
				<div style="width:350px;">
					<select name="cities[]" id="cities" tip="institute_city" caption="City from the list" style="width:156px; display: inline;" onChange="checkCity(this, 'getZonesForCity');clearLocality1();" /></select>
					<input type="text" maxlength="25" minlength="2" name="cities_other[]" id="cities_other" value="" style="display:none;" caption="City Name"/>
				</div>
				<div style="display:none"><div style="margin-top:2px" id="cities_error" class="errorMsg">Please select City from the list.</div></div>
				<div style="display:none"><div style="margin-top:2px" id="cities_other_error" class="errorMsg"></div></div>
			</div>
			<div class="clearFix"></div>
		</div>
		<div class="spacer10 clearFix"></div>

		<div class="row" id="zonewala" style="display:none;height:30px;">
			<div style="float:left;width:200px;line-height:17px; padding-right:5px"><div class="txt_align_r bld">Zone:</div></div>
			<div style="float:left;width:180px">
				<div>
					<select caption="Zone from the list" name="zone" id="zone" tip="locality_zone" minlength="1" maxlength="100" style="width:156px" onchange="getLocalitiesForZone(this);"></select>
				</div>
				<div style="display:none"><div style="margin-top:2px" class="errorMsg" id="zone_error" ></div></div>
			</div>
			<div style="float:left;width:80px;line-height:17px; padding-right:5px"><div class="txt_align_r bld">Locality:</div></div>
			<div style="float:left;width:180px">
				<div>
					<select name="localities[]" id="localities" tip="locality"  onChange="selectLocality(this);" minlength="1" maxlength="100" caption="Locality from the list" style="width:156px"/>
						<option value="select" > Select </option>
						<option value="others" > Others </option>
					</select>
					&nbsp;
					<span id="user_locality_div"></span>
					<div style="display:none;"><div style="margin-top:2px" class="errorMsg txt_align_l" id="user_added_locality_name_error"></div></div>
				</div>
				<div style="display:none"><div style="margin-top:2px" id="localities_error" class="errorMsg"></div></div>
			</div>
			<div class="clear_L withClear">&nbsp;</div>
		</div>
		<div class="spacer10 clearFix"></div>
        
		<div class="row">
			<div style="float:left;width:200px;line-height:22px; padding-right:5px"><div class="txt_align_r bld">Address Line 1:</div></div>
			<div style="float:left;width:405px"><div><input type="text" tip="address_line_1" caption="Address Line 1" name="address1"  id="address1" minlength="0" maxlength="100" style="width:414px" /></div>
			<div style="display:none"><div style="margin-top:2px" class="errorMsg" id="address1_error"></div></div>
			</div>
			<div style="float:left;width:40px;line-height:20px">&nbsp;</div>
			<div style="float:left;width:90px">&nbsp;</div>
			<div class="clear_L withClear">&nbsp;</div>
		</div>
		<div class="spacer10 clearFix"></div>

		<div class="row">
			<div style="float:left;width:200px;line-height:22px; padding-right:5px"><div class="txt_align_r bld">Address Line 2:</div></div>
			<div style="float:left;width:405px"><div><input type="text" tip="address_line_2" caption="Address Line 2" name="address2"  id="address2" minlength="0" maxlength="100" style="width:414px" /></div>
			<div style="display:none"><div style="margin-top:2px" class="errorMsg" id="address2_error"></div></div>
			</div>
			<div style="float:left;width:40px;line-height:20px">&nbsp;</div>
			<div style="float:left;width:90px">&nbsp;</div>
			<div class="clear_L withClear">&nbsp;</div>
		</div>
		<div class="spacer10 clearFix"></div>

		<div class="row">
			<div style="float:left;width:200px;line-height:22px; padding-right:5px"><div class="txt_align_r bld">Locality:</div></div>
			<div style="float:left;width:160px"><div>
				<input autocomplete="off" onchange="changeLocality1()" id="locality" type="text" maxlength="250" minlength="0" style="width:150px;" caption="Locality" disabled="true"/>
			</div>
			<div style="display:none"><div style="margin-top:2px" class="errorMsg txt_align_l" id="locality_error"></div></div>
			</div>
			<div style="float:left;width:100px;line-height:22px; padding-right:5px"><div class="txt_align_r bld">City:</div></div>
			<div style="float:left;width:200px;line-height:17px; padding-right:5px"><div><input disabled="true" name="city" maxlength="250" minlength="0" style="width:150px;" id="city" caption="City" type="text" /></div>
			<div style="display:none"><div style="margin-top:2px" class="errorMsg txt_align_l" id="city_error"></div></div>
			</div>
			<div class="clear_L withClear">&nbsp;</div>
		</div>
		<div class="spacer10 clearFix"></div>

		<div class="row">
			<div style="float:left;width:200px;line-height:20px; padding-right:5px"><div class="txt_align_r bld">Pincode<span class="redcolor fontSize_13p">*</span>:</div></div>
			<div style="float:left;width:405px"><div><!--input type="text" tip="listing_pin_code_no" caption="Pincode" name="pin_code" id="pin_code" minlength="6" maxlength="6" style="width:150px" /-->
                            <input type="text" value="" style="width:150px" id="pin_code" name="pin_code" caption="Pincode" tip="listing_pin_code_no" profanity="true" onkeypress="setMaxMinLenghtForPincode($('country').value);"/>
                            </div>
			<div style="display:none"><div style="margin-top:2px" class="errorMsg" id="pin_code_error"></div></div>
			</div>
			<div style="float:left;width:40px;line-height:20px">&nbsp;</div>
			<div style="float:left;width:90px">&nbsp;</div>
			<div class="clear_L withClear">&nbsp;</div>
		</div>
            
            <div class="spacer10 clearFix"></div>
            <!--div style="padding-left:205px"><input class="saveButtonNew" type="button" name="bttnSaveLocationContact"  id="bttnSaveLocationContact" value=" " onClick="javascript: saveLocationContactInfo();" />&nbsp;&nbsp; <a href="javascript:void(0);" name="bttnCancelLocationContact"  id="bttnCancelLocationContact" onClick="javascript: resetLocationContactFormData();">Cancel</a></div-->
            <div style="padding-left:205px"><input class="saveButtonNew" type="button" name="bttnSaveLocationContact"  id="bttnSaveLocationContact" value=" " onClick="javascript: check_Institute_name($('pin_code'));" />&nbsp;&nbsp; <a href="javascript:void(0);" name="bttnCancelLocationContact"  id="bttnCancelLocationContact" onClick="javascript: resetLocationContactFormData();">Cancel</a></div>            
			<div class="spacer20 clearFix"></div>

<input type="hidden" name="isAbroadListing" id="isAbroadListing" value="<?php echo $isAbroadListing; ?>" />
        <input type="hidden" name="contactInfoHiddenVar" id="contactInfoHiddenVar" />
        <input type="hidden" name="locationInfoHiddenVar" id="locationInfoHiddenVar" />
        <script>
            var innerSeparatorChar = "|=#=|";
            var outerSeparatorChar = "||++||";
            var contactInfo = "";
            var locationInfo = "";
            var isLocationContactAddedOnce = <?php echo ($flow == 'add' ? 0 : 1); ?>;
            var instituteExistinglocationArray = new Array;
            var instituteExistingContactsArray = new Array;
            var locationIdsWithCoursesArray = new Array;

<?php
// Collecting ILIDS those are associated wityh this Institute's Courses to stop deleting them..
if($locationIdsAssociatedWithCourses != "" && is_array($locationIdsAssociatedWithCourses)) {    
    $countVar = count($locationIdsAssociatedWithCourses);
    for($i = 0; $i < $countVar; $i++) { ?>
        locationIdsWithCoursesArray[<?=$i?>] = '<?php echo $locationIdsAssociatedWithCourses[$i]; ?>';
<?php
    }
}
?>
    
    function saveLocationContactInfo() {        
        // alert("instituteExistinglocationArray = "+instituteExistinglocationArray[0]+" , instituteExistingContactsArray = "+instituteExistingContactsArray[0]);

        var countryID = $("country").options[$("country").selectedIndex].value;
        var localContactInformation = getContactInformation();
        var localLocationInformation = getLocationInformation();

        if(existingLocationIndextoEdit == "") {  // New entry
           instituteExistinglocationArray.push(localLocationInformation);
           instituteExistingContactsArray.push(localContactInformation);
        } else { // Update the existing entry..
           var tempArray = instituteExistinglocationArray[existingLocationIndextoEdit].split(innerSeparatorChar);
           if(tempArray.length >= 10) {
               localLocationInformation += innerSeparatorChar + tempArray[9];   // Assigning the existing Institute Location ID.
           }

           instituteExistinglocationArray[existingLocationIndextoEdit] = localLocationInformation;
           instituteExistingContactsArray[existingLocationIndextoEdit] = localContactInformation;
        }

        if(countryID != 2) {
            $("isAbroadListing").value = 1;
        } else {
            $("isAbroadListing").value = 0;
        }

        modifyDisplayTable();
        sortDisplayTable();
        
        alert("Thanks, Location - Contact Information has been saved successfully.");
        $('bttnSaveLocationContact').disabled = false;
    }


    function modifyDisplayTable() {
                    var cityName = $("cities").options[$("cities").selectedIndex].text;
                    var localityName = $("localities").options[$("localities").selectedIndex].text;
                    if(localityName == "Select") {
                        localityName = $("locality").value;
                    }

                    var data_to_display = cityName + (localityName == "" ? "" : " -- " + localityName );
                    var location_index_value = (parseInt(instituteExistinglocationArray.length) - 1);

                    if(existingLocationIndextoEdit == "") {
                            if(isLocationContactAddedOnce == 1) {
                                $("main_location_contact_container").innerHTML += '<div style="padding-left:4px" class="loc-row" id="actual_loc_data_container_'+location_index_value+'"><input type="radio" name="locationContactRadioBttn" group="radioBttns" value="'+location_index_value+'" /> '+data_to_display+'</div>';
                            } else {
                                var myHtml = '<div class="location-header"><strong>Locations</strong></div><div class="location-details" id="main_location_contact_container">';
                                myHtml += '<div style="padding-left:4px" class="loc-row" id="actual_loc_data_container_'+location_index_value+'"><input type="radio" name="locationContactRadioBttn" group="radioBttns" value="'+location_index_value+'" /> '+data_to_display+'</div>';
                                myHtml += '</div><div style="border-top:1px solid #e8e8e8; padding:8px 8px 8px 10px;"><img src="/public/images/edit-icn.gif" style="vertical-align:bottom" /> <a href="javascript:void(0);" onClick="javascript: editLocationContactData();">Edit</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="/public/images/del-icn.gif" style="vertical-align:bottom" /> <a href="javascript:void(0);" onClick="javascript: deleteLocationContactData();">Delete</a></div><div style="clear:both"></div>';
                                $("outer_location_contact_container").innerHTML = myHtml;

                                isLocationContactAddedOnce = 1;
                            }
                     } else {
                           $("actual_loc_data_container_"+existingLocationIndextoEdit).innerHTML = '<input type="radio" name="locationContactRadioBttn" group="radioBttns" value="'+existingLocationIndextoEdit+'" /> '+data_to_display;
                           existingLocationIndextoEdit = "";
                     }

                    resetLocationContactFormData();
                    $('main_location_contact_container').scrollIntoView();
    }


    function sortDisplayTable() {
        var totatLocations = instituteExistinglocationArray.length;
        if(totatLocations <= 1 ) {
            return;
        }

        var text; var startPos;
        var displayDataArray = new Array;
        var oldDisplayOrder = new Array;
        for(i = 0; i < totatLocations; i++) {
            text = $('actual_loc_data_container_'+i).innerHTML;
            startPos = text.indexOf(">");
            actualText = trim(text.substr((startPos + 1)));
            displayDataArray[i] = actualText;
            oldDisplayOrder[i] = actualText;
            // alert("actualText = '"+actualText+"' , Full Text = "+text);
        }

        displayDataArray.sort();
        len = displayDataArray.length;
        oldLen = oldDisplayOrder.length;
        var myHtml = '';
        for(i = 0; i < len; i++) {
            for(j=0; j < oldLen; j++)
            {
                if(displayDataArray[i] == oldDisplayOrder[j])
                {
                    myHtml += '<div style="padding-left:4px" class="loc-row" id="actual_loc_data_container_'+j+'"><input type="radio" name="locationContactRadioBttn" group="radioBttns" value="'+j+'" /> '+displayDataArray[i]+'</div>';
                }
            }
        }

        $("main_location_contact_container").innerHTML = myHtml;
        return;
    }

    // Function to prepare the hidden variables of Location & Contact info.
    function prepare_institute_location_contact_data() {
        $("locationInfoHiddenVar").value = instituteExistinglocationArray.join(outerSeparatorChar);
        $("contactInfoHiddenVar").value = instituteExistingContactsArray.join(outerSeparatorChar);
    }

     var ilid;
     var counter;

            <?php
            if($flow != 'add') {
                $locationsCount = count($locations);

            for($i = 0; $i < $locationsCount; $i++) { ?>

                ilid = <?=$i?>;
                instituteExistinglocationArray[ilid] = parseInt('<?=$locations[$i]['country_id']?>');
                instituteExistinglocationArray[ilid] += innerSeparatorChar + '<?=$locations[$i]['city_id']?>';
                instituteExistinglocationArray[ilid] += innerSeparatorChar + '<?=$locations[$i]['zone']?>';
                instituteExistinglocationArray[ilid] += innerSeparatorChar + '<?=$locations[$i]['localityId']?>';
                instituteExistinglocationArray[ilid] += innerSeparatorChar + '<?=addslashes($locations[$i]['address1'])?>';
                instituteExistinglocationArray[ilid] += innerSeparatorChar + '<?=addslashes($locations[$i]['address2'])?>';
                instituteExistinglocationArray[ilid] += innerSeparatorChar + '<?=addslashes($locations[$i]['locality'])?>';
                instituteExistinglocationArray[ilid] += innerSeparatorChar + '<?=$locations[$i]['city']?>';
                instituteExistinglocationArray[ilid] += innerSeparatorChar + '<?=addslashes($locations[$i]['pincode'])?>';
                instituteExistinglocationArray[ilid] += innerSeparatorChar + '<?=$locations[$i]['institute_location_id']?>';

                instituteExistingContactsArray[ilid] = '<?=addslashes($contactInfo[$i]['contact_person'])?>';
                instituteExistingContactsArray[ilid] += innerSeparatorChar + '<?=$contactInfo[$i]['contact_main_phone']?>';
                instituteExistingContactsArray[ilid] += innerSeparatorChar + '<?=$contactInfo[$i]['contact_cell']?>';
                instituteExistingContactsArray[ilid] += innerSeparatorChar + '<?=$contactInfo[$i]['contact_alternate_phone']?>';
                instituteExistingContactsArray[ilid] += innerSeparatorChar + '<?=$contactInfo[$i]['contact_fax']?>';
                instituteExistingContactsArray[ilid] += innerSeparatorChar + '<?=addslashes($contactInfo[$i]['contact_email'])?>';
                instituteExistingContactsArray[ilid] += innerSeparatorChar + '<?=addslashes($contactInfo[$i]['website'])?>';
<?php
    } // End of for($i = 0; $i < $locationsCount; $i++).
    
}   // End of if($flow != 'add').
?>

    var existingLocationIndextoEdit = '';

    function isLocationAssignedToCourse(indexValueToDelete){        
        tmpArray = instituteExistinglocationArray[indexValueToDelete].split(innerSeparatorChar);
        var returnedArray = new Array;
        if(tmpArray.length <= 9) {
            returnedArray[0] = 0;
            return returnedArray;
        }
        
        var totalLength = locationIdsWithCoursesArray.length;
        for(i = 0; i < totalLength; i++) {
                if(tmpArray[9] == locationIdsWithCoursesArray[i]) {
                    returnedArray[0] = 1;
                    returnedArray[1] = tmpArray[6] == "" ? tmpArray[7] : tmpArray[6] +", "+ tmpArray[7];
                    return returnedArray;
                }            
        }

        returnedArray[0] = 0;
        return returnedArray;        
    }

    function deleteLocationContactData() {
                var indexValueToDelete = getRadioButtonsCheckedValue( document.getElementsByName("locationContactRadioBttn"));

                if(indexValueToDelete == "") {
                    alert("Oops! Please select any Location first.");
                    return false;
                }

                responseArray = isLocationAssignedToCourse(indexValueToDelete);
                if(locationIdsWithCoursesArray.length > 0 && responseArray[0] == 1) {
                    alert("The location '" + responseArray[1] + "' can not be deleted because it has been assigned to a course.");
                    return false;
                }

                if(!confirm("Are you sure, you want to remove the selected Location & Contact info?")) {
                    return false;
                }
        
                $("isAbroadListing").value = 0;

                var tempLocArray = new Array;
                var tempContactsArray = new Array;
                tempLocArray = instituteExistinglocationArray.slice();
                tempContactsArray = instituteExistingContactsArray.slice();

                instituteExistingContactsArray.length = 0;
                instituteExistinglocationArray.length = 0;

                // alert("instituteExistingContactsArray = "+instituteExistingContactsArray[0]+" , instituteExistinglocationArray = "+instituteExistinglocationArray[0]);
                // alert("tempLocArray length = "+tempLocArray.length+", tempLocArray = "+tempLocArray[0]+" , tempContactsArray = "+tempContactsArray[0]);

                var totalLength = tempLocArray.length; var j = 0;
                for(i = 0; i < totalLength; i++) {
                    if(i == indexValueToDelete) {
                        continue;
                    }

                    instituteExistinglocationArray[j] = tempLocArray[i];
                    instituteExistingContactsArray[j] = tempContactsArray[i];
                    j++;
                    
                }

                // Updating the display table now..
                totalLength = instituteExistinglocationArray.length;
                var myHtml = '';
                
                if(totalLength == 0) {
                    myHtml = '<div id="no_location_error_msg_div" style="height:50px; padding:5px;"><strong>No location has been added. Please add location from below.</div>';
                    isLocationContactAddedOnce = 0;
                } else {
                    var data_to_display;
                    myHtml = '<div class="location-header">Locations</div><div class="location-details" id="main_location_contact_container">';

                    for(i = 0; i < totalLength; i++) {
                        tmpArray = instituteExistinglocationArray[i].split(innerSeparatorChar);
                        data_to_display = (tmpArray[6] == "" ? "" : tmpArray[6] + ", " ) + tmpArray[7];
                        myHtml += '<div class="loc-row" id="actual_loc_data_container_'+i+'"><input type="radio" name="locationContactRadioBttn" group="radioBttns" value="'+i+'" />'+data_to_display+'</div>';
                    }

                    myHtml += '</div><div style="border-top:1px solid #e8e8e8; padding:5px;"><img src="/public/images/edit-icn.gif" style="vertical-align:bottom" /> <a href="javascript:void(0);" onClick="javascript: editLocationContactData();">Edit</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="/public/images/del-icn.gif" style="vertical-align:bottom" /> <a href="javascript:void(0);" onClick="javascript: deleteLocationContactData();">Delete</a></div><div style="clear:both"></div>';
                    
                }   // End of if(totalLength == 0).

                $("outer_location_contact_container").innerHTML = myHtml;

                resetLocationContactFormData();
                sortDisplayTable();
    }

    function editLocationContactData() {
                var indexValueToEdit = getRadioButtonsCheckedValue( document.getElementsByName("locationContactRadioBttn"));

                existingLocationIndextoEdit = indexValueToEdit;

                if(indexValueToEdit == "") {
                    alert("Oops! Please select any Location first.");
                    return false;
                }

                var contactsValues = instituteExistingContactsArray[indexValueToEdit].split(innerSeparatorChar);
                
                 $("c_cordinator_name").value = contactsValues[0];
                 $("main_phone_number").value = contactsValues[1];
                 $("main_mobile_number").value = contactsValues[2];

                 if(contactsValues[3] != "") {
                     if($("alternate_phones").style.display == 'none')
                     removephones();
                 
                     $("alternate_numbers").value = contactsValues[3];
                     $("fax_number12").value = contactsValues[4];
                 } else {
                     $("alternate_numbers").value = contactsValues[3];
                     $("fax_number12").value = contactsValues[4];
                     $('alternate_phones').style.display = 'none';
                     $('extra_phone_number').innerHTML = "+ Add more phone numbers";
                 }
                 
                 $("c_cordinator_email").value = contactsValues[5];
                 $("c_website").value = contactsValues[6];

                 var locationValues = instituteExistinglocationArray[indexValueToEdit].split(innerSeparatorChar);

                 $("country").selectedIndex = parseInt( getDropDownIndex($("country"), locationValues[0]) );

                 getCitiesForCountry('',false,'',false);                  
                 clearLocality();
                 
                 setTimeout(function(){populateOtherFields(locationValues)}, 300);
    }

    function populateOtherFields(locationValues) {
                    $("cities").selectedIndex = parseInt( getDropDownIndex($("cities"), locationValues[1]) );
                     checkCity($("cities"), 'getZonesForCity');clearLocality1();

                     if(locationValues[2] != 0) {
                         $("zone").selectedIndex =  parseInt( getDropDownIndex($("zone"), locationValues[2]) );
                         getLocalitiesForZone($("zone"));
                         if(locationValues[3] != 0) {
                            $("localities").selectedIndex =  parseInt( getDropDownIndex($("localities"), locationValues[3]) );
                         }
                     }

                     $("address1").value = locationValues[4];
                     $("address2").value = locationValues[5];
                     $("locality").value = locationValues[6];
                     $("city").value = locationValues[7];
                     $("pin_code").value = locationValues[8];
                     $("c_cordinator_name").focus();

    }
    
    function getContactInformation() {
        var dataValues = $("c_cordinator_name").value;
        dataValues += innerSeparatorChar + $("main_phone_number").value;
        dataValues += innerSeparatorChar + $("main_mobile_number").value;
        dataValues += innerSeparatorChar + $("alternate_numbers").value;
        dataValues += innerSeparatorChar + $("fax_number12").value;
        dataValues += innerSeparatorChar + $("c_cordinator_email").value;
        dataValues += innerSeparatorChar + $("c_website").value;
        return dataValues;
    }

    function getLocationInformation() {
        var dataValues = $("country").options[$("country").selectedIndex].value;
        dataValues += innerSeparatorChar + $("cities").options[$("cities").selectedIndex].value;
        dataValues += innerSeparatorChar + $("zone").options[$("zone").selectedIndex].value;
        dataValues += innerSeparatorChar + $("localities").options[$("localities").selectedIndex].value;
        dataValues += innerSeparatorChar + $("address1").value;
        dataValues += innerSeparatorChar + $("address2").value;
        dataValues += innerSeparatorChar + $("locality").value;
        dataValues += innerSeparatorChar + $("city").value;
        dataValues += innerSeparatorChar + $("pin_code").value;
        return dataValues;
    }

    function validateLocationContactData() {       
       var countryID = $("country").options[$("country").selectedIndex].value;
       if((countryID != 2 && instituteExistinglocationArray.length > 0) || ($("isAbroadListing").value == 1 && existingLocationIndextoEdit == "")) {           
            if(!(countryID != 2 && instituteExistinglocationArray.length == 1 && existingLocationIndextoEdit != "")) {
                alert("Sorry! You can enter only 1 Location & Contact information for Abroad Listing!");
                return false;
            }
        }

        if(countryID == 2 && existingLocationIndextoEdit != '') { // For Abroad Institute we can change the city (Bug: 61755).
            var responseArray = isLocationAssignedToCourse(existingLocationIndextoEdit);
            if(responseArray[0] == 1) {
                tmpArray = instituteExistinglocationArray[existingLocationIndextoEdit].split(innerSeparatorChar);
                // alert("Existing City id = "+tmpArray[1]+", and now city id = "+$("cities").options[$("cities").selectedIndex].value);
                if(tmpArray[1] != $("cities").options[$("cities").selectedIndex].value) {
                    alert("Sorry! This location has been assigned to any course of this institute, so you can't change the City for this location.");
                    return false;
                }
            }
        }

        var nameLength = $("c_cordinator_name").value.length;
        if(nameLength != 0 && (nameLength < 5 || nameLength > 100)) {
           $('c_cordinator_name_error').innerHTML =  'The Name must contain atleast 5 characters.';
           $("c_cordinator_name_error").parentNode.style.display = 'inline';
           $("c_cordinator_name_error").focus();
           return false;
        }
	
	isAbroadCountry = 0;
	charLength = 10;
	if (countryID != 2) {
	  isAbroadCountry = 1;
	  charLength = 20;
	}

        if($("main_mobile_number").value != "" && !validateMobileNumber("main_mobile_number", charLength, isAbroadCountry)) {
           return false;
        }       

        var cemail = $("c_cordinator_email").value;
        if(cemail.length != 0 && (cemail.indexOf("@") == -1 || cemail.indexOf(".") == -1  )) {
            $("c_cordinator_email_error").parentNode.style.display = 'inline';
            $("c_cordinator_email_error").innerHTML = 'The Email specified is not correct.';
            $("c_cordinator_email_error").focus();
            return false;
        }

        if ($('c_website').value == 'http://') {
            $('c_website').value = '';
        }

        if($("c_website").value != "") {
            var isValidated = validateUrl($("c_website").value, 'Website Address');
            if(isValidated != true) {
                $("c_website_error").parentNode.style.display = 'inline';
                $("c_website_error").innerHTML = isValidated;
                $("c_website_error").focus();
                return false;                
            }
        }

        if($("cities").selectedIndex == 0) {
            $("cities_error").parentNode.style.display = 'inline';
            $("cities_error").innerHTML = 'Please select a City from the list.';
            $("cities_error").focus();
            return false;
        }

        if($("zonewala").style.display != 'none') {
            if($("zone").selectedIndex == 0) {
                $("zone_error").parentNode.style.display = 'inline';
                $("zone_error").innerHTML = 'Please select a Zone from the list.';
                $("zone_error").focus();
                return false;
            }

            if($("zone").selectedIndex != 0 && $("localities").selectedIndex == 0) {
                $("localities_error").parentNode.style.display = 'inline';
                $("localities_error").innerHTML = 'Please select a Locality from the list.';
                $("localities_error").focus();
                return false;
            }
        }

        if(!checkPincodeValue()) {
            return false;
        }
        
        if(!validateForDuplicateLocation()) {
            return false;
        }

        return true;
    }

    function checkPincodeValue(){
       
        if(isNaN($("pin_code").value) && $('country').value == 2) {
		$("pin_code_error").innerHTML = 'Please fill the Pincode with correct numeric value.';
                $('pin_code_error').focus();
                $("pin_code_error").parentNode.style.display = 'inline';
            	return false;
        }
        if($("pin_code").value == "") {
            $("pin_code_error").parentNode.style.display = 'inline';
            if($('country').value > 2) { 
	            $("pin_code_error").innerHTML = 'Please fill the Pincode with the correct alphanumeric value.';

            } else {
		    $("pin_code_error").innerHTML = 'Please fill the Pincode with correct numeric value.';
            }
            $('pin_code_error').focus();
            return false;
        } else if($("pin_code").value.length >0) {
	    var charcheck = /^[0-9a-zA-Z]+$/;
            var error_message = "";
            if($('country').value > 2) {
                if(charcheck.test($("pin_code").value) == false) {
            		error_message = 'Please fill the Pincode with the correct alphanumeric value.';
		} else if(!(3 <= $("pin_code").value.length && $("pin_code").value.length <= 10)) {
			error_message = 'Please fill the Pincode within the range of 3 to 10 characters.';
		}
                
            } else {
                if($('country').value == 2 && $("pin_code").value.length !=6) {
			error_message = 'Please fill the Pincode with 6 digits';
		}
            }

            if(error_message) {
		$("pin_code_error").parentNode.style.display = 'inline';
		$("pin_code_error").innerHTML = error_message;
		$('pin_code_error').focus();
		return false;
            }
        }

        return true;
    }

    function validateForDuplicateLocation() {
        var arraylength = instituteExistinglocationArray.length;
        var matchFound = 0;
        var tempArray = new Array;
        var countryID = $("country").options[$("country").selectedIndex].value;
        var cityID = $("cities").options[$("cities").selectedIndex].value;
        var zoneID = $("zone").options[$("zone").selectedIndex].value;
        var localityID = $("localities").options[$("localities").selectedIndex].value;
	var localityName = $("locality").value;	
        // alert("existingLocationIndextoEdit = "+existingLocationIndextoEdit);

        for(i = 0; i < arraylength; i++ ){
            tempArray = instituteExistinglocationArray[i].split(innerSeparatorChar);
            matchFound = 0;

            if(existingLocationIndextoEdit != "" && existingLocationIndextoEdit == i) {
                continue;
            }

            //alert("Index = "+i+" , Going to Add entry : "+ countryID + " , " + cityID + " , "  + zoneID + " , " + localityID + " , localityName = "+localityName + " \n\n Existing engtry : "+ tempArray[0] + " , " + tempArray[1] + " , "  + tempArray[2] + " , " + tempArray[3] + " , "+tempArray[6]);
            // Lets check the duplicacy for this location..
            if(countryID == tempArray[0]) {
                matchFound = 1;
            } else {
                continue;
            }

            if(cityID == tempArray[1]) {
                matchFound = 1;
            } else {
                continue;
            }

            if(zoneID != '0' && zoneID != '') {

                if(zoneID == tempArray[2]) {
                    matchFound = 1;
                } else {
                    continue;
                }

                if(localityID != 'select' && localityID != '' && localityID == tempArray[3]) {
                    matchFound = 1;
                } else {		 
                     continue;
                }

            } else {
	      if(localityName != "" && localityName == tempArray[6]) {
		  matchFound = 1;
              } else {
		 // alert("matchFound =  "+matchFound+", localityName = "+localityName);
		 if (localityName != "") {
		   continue; 
		 }                 
	      }
	    } // End of if(zoneID != '0' && zoneID != '').

            if(matchFound == 1) {
                alert("Oops! This location has already been added.");
                return false;
            }
        }

        return true;
    }

    function resetLocationContactFormData() {
         $("c_cordinator_name").value = "";
         $("main_phone_number").value = "";
         $("main_mobile_number").value = "";
         $("alternate_numbers").value = "";
         $("fax_number12").value = "";
         $("c_cordinator_email").value = "";
         $("c_website").value = "";
         $("address1").value = "";
         $("address2").value = "";
         $("locality").value = "";
         $("city").value = "";
         $("pin_code").value = "";
         // $("country").selectedIndex = 0;
         $("cities").selectedIndex = 0;
         checkCity($("cities"), 'getZonesForCity');clearLocality1();

         if($('alternate_phones').style.display != "none"){
            removephones();
         }

         if(existingLocationIndextoEdit != undefined) {
             existingLocationIndextoEdit = "";
         }
    }
function setMaxMinLenghtForPincode(ele_value) {
	$j('#pin_code').removeAttr('minlength maxlength');
	if(ele_value == 2) {
		$j('#pin_code').attr('maxlength','6');
		
	} else {
		$j('#pin_code').attr('minlength','3');
		$j('#pin_code').attr('maxlength','10');
       }
}
</script>
