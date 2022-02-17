<?php
// _p($contactInfoForAvailableCourseLocations); die;
 //_p($courseFeeLocationWise); _p($courseFeeUnitLocationWise); // die;
 // _p($available_locations_of_courses);
// Collect the location ids that are already assigned to this Course..
$lenCount = count($available_locations_of_courses);
$locationIDsArray = array();
for($i = 0; $i < $lenCount; $i++) {
    $locationIDsArray[] = $available_locations_of_courses[$i][institute_location_id];

}
?>
<script>
var innerSeparatorChar = "|=#=|";
var outerSeparatorChar = "||++||";

var imp_date_location = "";
var imp_date_location_array = {};
var course_contact_details_location = "";
var course_contact_details_location_array = {};
var submissiondatesArray = '<?php echo json_encode($submissiondatesArray);?>';
submissiondatesArray = eval("eval("+submissiondatesArray+")");
if(submissiondatesArray != "") {
	for(var i in submissiondatesArray) {
		imp_date_location_array[i] = i+"_"+submissiondatesArray[i].date_form_submission+"_"+submissiondatesArray[i].date_result_declaration+"_"+submissiondatesArray[i].date_course_comencement; 
	}
}
var course_contact_details_locationwise_list = <?php echo json_encode($course_contact_details_locationwise_list);?>;
//course_contact_details_locationwise_list = eval("eval("+course_contact_details_locationwise_list+")");
if(course_contact_details_locationwise_list != "") {
	for(var i in course_contact_details_locationwise_list) {
		course_contact_details_location_array[i] = i+innerSeparatorChar+course_contact_details_locationwise_list[i].contact_name_location+
innerSeparatorChar+course_contact_details_locationwise_list[i].contact_phone_location+innerSeparatorChar+course_contact_details_locationwise_list[i].contact_mobile_location+innerSeparatorChar+course_contact_details_locationwise_list[i].contact_email_location;
	}
}
</script>
<input type="hidden" name="locationInfoHiddenVar" id="locationInfoHiddenVar" value="<?=implode(", ", $locationIDsArray)?>" />
<input type="hidden" name="locationFeeInfoHiddenVar" id="locationFeeInfoHiddenVar" />
<input type="hidden" name="locationFeeDisclaimerHiddenVar" id="locationFeeDisclaimerHiddenVar" />
<input type="hidden" name="locationFeeIDHiddenVar" id="locationFeeIDHiddenVar" />
<input type="hidden" name="headOfclocationIdHiddenVar" id="headOfclocationIdHiddenVar" value="<?php echo $headOfcLocationIDForCourse;?>" />
<input type="hidden" name="important_date_info_location" id="important_date_info_location" value=""/>
<input type="hidden" name="course_contact_details_locationwise" id="course_contact_details_locationwise" value=""/>
<?php

//_p($locationIDsArray);// die;
// Now collect the location ids (that are left and can be assigned to this Course in future) and their info..
$available_locations_of_institutes  = array();
$lenCount = count($locations);
if(count($locationIDsArray) > 0) {
    // echo "<br> Yes count = ".count($locationIDsArray) ;
    for($i = 0; $i < $lenCount; $i++) {
        // $locationIDsArray[] = $available_locations_of_courses[$i][institute_location_id];
        if(in_array($locations[$i][institute_location_id], $locationIDsArray)) {
            continue;
        } else {
            // echo "<br> Not in array, ilid : ".$locations[$i][institute_location_id];
            $available_locations_of_institutes[] = $locations[$i];
        }
    }
} else {
    // echo "<br> NO , count = ".count($locationIDsArray) ;
    $available_locations_of_institutes = $locations;
}

// Collecting the Locations' Contacts for this Course..
$locationsHavingContactsArray = array();
$lenCount = count($contactInfoForAvailableCourseLocations);
if($lenCount > 0) {
    for($i = 0; $i < $lenCount; $i++) {
        $institute_location_id = $contactInfoForAvailableCourseLocations[$i][institute_location_id];
        $locationsHavingContactsArray[$institute_location_id] = $contactInfoForAvailableCourseLocations[$i]['contact_person'];
    }
}

?>
<style>
.location-cont{width:70px; border:1px solid #dcdadb; font:normal 12px Arial, Helvetica, sans-serif;width:100%;}
.location-header{background:#e8e8e8; padding:4px; overflow:hidden; height:16px}
.location-details{padding:4px; clear:left; overflow:hidden}
.col-1,.col-2,.col-3,.col-4{width:300px; float:left; border:}
.col-2{width:260px;}
.col-3{width:170px;}
.col-4{width:120px;}
.loc-row{margin-bottom:3px}
</style>
<div class="spacer20 clearFix"></div>
<div class="formHeader"><a class="formHeader" name="wikicontent" style="text-decoration:none">Assign Locations to the Course <span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'outer_location_contact_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></a></div>
<div id="outer_location_contact_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('outer_location_contact_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>					
					<li>Already added location in Institute details will be shown in the box. Just add it to the course.</li>
					<li>In case of multi location institute, select the location for the courses available at that location.</li>	
					<li>If a course is available at more than 2 locations, add all those required.</li>
					<li>If the fee is estimated on the basis of first year fee, a disclaimer with the text <?php echo FEES_DISCLAIMER_TEXT; ?> will be shown if the Show Disclaimer option is checked.</li>
					</ul>
	</div>	
<div class="spacer5 clearFix"></div>
<div class="line_1"></div>
<div class="spacer10 clearFix"></div>
<strong>Already Assigned Locations</strong>
<div class="spacer5 clearFix"></div>
<div class="location-cont" id="outer_location_contact_container" style="margin-left:40px; width:95%"><?php
$count = count($available_locations_of_courses);

if($count > 0){
?>
	<div class="location-header">
    	<div class="col-1">Location</div>
        <div class="col-2">Fees</div>
        <div class="col-3">Contact Details</div>
        <div class="col-4">Important Dates</div>
    </div><?php

    for($i = 0; $i < $count; $i++) {
        $contentToDisplay = $available_locations_of_courses[$i]['city_name'] . ($available_locations_of_courses[$i]['locality'] == "" ? "" : " -- ".$available_locations_of_courses[$i]['locality']) ;
        $institute_location_id = $available_locations_of_courses[$i]['institute_location_id'];


?>
    <div class="location-details">
        <div class="col-1" id="first_col_div_<?=$i?>"><?php
            if($institute_location_id == $headOfcLocationIDForCourse) {
                echo '<input type="radio" name="locationRadioBttn" group="radioBttns" value="'.$institute_location_id.'" checked /> ';
                echo $contentToDisplay;
                echo ' <img src="/public/images/head-office-arrow.gif" style="margin-left:15px" /> Head Office';
            } else {
                echo '<input type="radio" name="locationRadioBttn" group="radioBttns" value="'.$institute_location_id.'" /> ';
                echo $contentToDisplay;
            }
        ?> </div>
        <div class="col-2" id="location_id_div_<?=$institute_location_id?>"><?php
            if(isset($courseFeeLocationWise[$institute_location_id]) && ($courseFeeLocationWise[$institute_location_id] != 0 || $courseFeeLocationWise[$institute_location_id] != "")) {
                echo $courseFeeUnitLocationWise[$institute_location_id]." ".$courseFeeLocationWise[$institute_location_id];
            }
        ?>[ <a href="javascript: void(0);" onClick="setFeeForLocation(<?php echo $institute_location_id; ?>);">Update Fees</a> ]</div>
        <?php if(isset($course_contact_details_locationwise_list[$institute_location_id]) && (!empty($course_contact_details_locationwise_list[$institute_location_id]['contact_name_location']) || !empty($course_contact_details_locationwise_list[$institute_location_id]['contact_phone_location']) || !empty($course_contact_details_locationwise_list[$institute_location_id]['contact_mobile_location']) || !empty($course_contact_details_locationwise_list[$institute_location_id]['contact_email_location']))):
        $name = $course_contact_details_locationwise_list[$institute_location_id]['contact_name_location'];
        if(strlen($name)>5) {
        	$name = substr($name, 0,5);
        	$name = $name."...";
        }
        ?>
        <div class="col-3"><span id="<?php echo $institute_location_id.'ownername'.$i;?>"><?php echo $name;?></span> [ <a href="javascript:void(0);" onclick="showContactDetailsLayer('<?php echo $institute_location_id;?>',this,'<?php echo $institute_location_id."ownername".$i;?>')">Update</a> ]
        </div>
        <?php else:?>
        <div class="col-3"><span id="<?php echo $institute_location_id.'ownername'.$i;?>"></span>[ <a href="javascript:void(0);" onclick="showContactDetailsLayer('<?php echo $institute_location_id;?>',this,'<?php echo $institute_location_id."ownername".$i;?>')">Add</a> ]</div>
        <?php endif;?>
        <?php if(isset($submissiondatesArray[$institute_location_id])):?>
        <div class="col-4">[ <a href="javascript:void(0);" onclick="showImportantDateLayer('<?php echo $institute_location_id;?>',this)">Update</a> ]</div>
        <?php else:?>
        <div class="col-4">[ <a href="javascript:void(0);" onclick="showImportantDateLayer('<?php echo $institute_location_id;?>',this)">Add</a> ]</div>
        <?php endif;?>
    </div><?php
    }   // End of for($i = 0; $i < $count; $i++).
    ?>
    <br />
    <div class="location-details" style="background:#f9f9f9">
    	<span style="padding:3px 12px;"><a href="javascript: void(0);" onclick="javascript: setHeadOffice();"><img src="/public/images/head-office-arrow.gif" /> Set as Head Office</a></span>
    </div>
    <div style="clear:both"></div><?php
} else {    ?>
    <div class="location-details" style="background:#f9f9f9">
    	<span style="padding:0px 12px; height:20px;">No locations assigned yet!</span>
    </div>
<?php
} // End of if($count > 0).
?>
</div>
<div style="line-height:20px;clear:both">&nbsp;</div>
<!-- NOW COMES THE ASSIGN LOCATION FORM -->
<strong>Assign more Locations</strong><span class="redcolor fontSize_13p">*</span>
<div class="spacer5 clearFix"></div>
<div class="" style="margin-left:40px">
        <table border="0" cellpadding="0" cellspacing="0" width="75%">
            <tr><td width="300">
                    <select id=available_locations_list_box multiple size=7 style="width:300px; border:1px solid #cbcbcb; overflow:auto; padding:4px"><?php
                        $available_locations_of_institutes_count = count($available_locations_of_institutes);
                        if($available_locations_of_institutes_count > 0) {
                            for($i = 0; $i < $available_locations_of_institutes_count; $i++) {
                                $contentToDisplay = $available_locations_of_institutes[$i]['city_name'] . ($available_locations_of_institutes[$i]['locality'] == "" ? "" : " -- ".$available_locations_of_institutes[$i]['locality']) ;
                                echo '<option value="'.$available_locations_of_institutes[$i]['institute_location_id'].'">'.$contentToDisplay.'</option>';
                            }
                        }
                    ?>
                    </select>
            </td>
            <td align="center" width="100">
                <input type="button" value=">>" onclick="javascript:moveoutid();"/>
                <div class="spacer10 clearFix"></div>
                <input type="button" value="<<" onclick="javascript:moveinid();"/>
            </td>
            <td width="300">
                    <select id=assigned_locations_list_box multiple size=7 style="width:300px; border:1px solid #cbcbcb; overflow:auto; padding:4px"><?php
                        $available_locations_of_courses_count = count($available_locations_of_courses);
                        if($available_locations_of_courses_count > 0) {
                            for($i = 0; $i < $available_locations_of_courses_count; $i++) {
                                $contentToDisplay = $available_locations_of_courses[$i]['city_name'] . ($available_locations_of_courses[$i]['locality'] == "" ? "" : " -- ".$available_locations_of_courses[$i]['locality']) ;
                                echo '<option value="'.$available_locations_of_courses[$i]['institute_location_id'].'">'.$contentToDisplay.'</option>';
                            }
                        }
                    ?>
                    </select>
            </td></tr>
        </table>

<div class="spacer5 clearFix"></div>
<div style="display: none;">
<div id="location_table_error" class="errorMsg">Please assign & save any location to proceed first.</div>
</div><div class="spacer10 clearFix"></div>
<input type="button" name="bttnSaveLocationsForCourse" id="bttnSaveLocationsForCourse" value="Save" onClick="javascript: addLocationsInQueue();" />
</div>
<div class="spacer20 clearFix"></div>
<script>
    var isLocationContactAddedOnce = <?php echo ($flow == 'add' ? 0 : 1); ?>;
    
    function prepare_course_locationFee_contact_data() {
        var new_array = new Array();
        var feeAttributes = institueLocationIdValueForFeeArray.join(outerSeparatorChar);
        $("locationFeeIDHiddenVar").value = institueLocationIdForFeeArray.join(outerSeparatorChar);
        $("locationFeeInfoHiddenVar").value = feeAttributes;
        var j =0;
        for(var i in imp_date_location_array) {
				new_array[j] = imp_date_location_array[i];
				j++;
        }
        $('important_date_info_location').value = new_array.join(outerSeparatorChar);
        var new_array1 = new Array();
        var j =0;
        for(var i in course_contact_details_location_array) {
        		new_array1[j] = course_contact_details_location_array[i];
				j++;
        }
        $('course_contact_details_locationwise').value = new_array1.join(outerSeparatorChar);
        
    }

    /**
     * Set the fee and related information such as the fee value, the fee unit and the fees disclaimer flag for a multilocation listing.
     *
     * @param institute_location_id The location id of the institute
     */
    function setFeeForLocation(institute_location_id) {
        var feeValue = "", feeUnit = "INR", feesDisclaimer;

        var indexKeyValue = institueLocationIdForFeeArray.indexAt(institute_location_id);
        if(indexKeyValue != -1) {
            feeAttributes = institueLocationIdValueForFeeArray[indexKeyValue].split(innerSeparatorChar);
            feeValue = feeAttributes[0];
            feeUnit = feeAttributes[1];
            feesDisclaimer = feeAttributes[2];
        }

        var div_id = $('location_id_div_'+institute_location_id);
        div_id.innerHTML = '';

        var formFragment = document.createDocumentFragment();

        var feesAmount = document.createElement('input');
        feesAmount.setAttribute('name', 'fees_amount[]');
        feesAmount.setAttribute('id', 'fees_amount_'+institute_location_id);
        feesAmount.setAttribute('type', 'text');
        feesAmount.setAttribute('maxlength', '10');
        feesAmount.setAttribute('minlength', '0');
        feesAmount.setAttribute('style', 'width: 40px;');
        feesAmount.setAttribute('caption', 'course fees for this Location');
        feesAmount.value = feeValue; // set fee value from server
        formFragment.appendChild(feesAmount);


        var currencyDropDown = document.createElement('select');
        currencyDropDown.setAttribute('name', 'c_fees_currency[]');
        currencyDropDown.setAttribute('style', 'width: 55px;');
        currencyDropDown.setAttribute('id', 'c_fees_currency_'+institute_location_id);
        <?php
        global $currencyAttributesArray;
        $len = count($currencyAttributesArray);
        $currencyDropDownOptions = "";
        for($i = 0; $i < $len; $i++) {  ?>
        var option = document.createElement('option');
        option.value = '<?=$currencyAttributesArray[$i]['currencyType']?>';
        option.innerHTML = '<?=$currencyAttributesArray[$i]['currencySymbol']?>';
        if(feeUnit == "<?=$currencyAttributesArray[$i]['currencyType']?>") { option.selected = true; }
        currencyDropDown.appendChild(option);
        <?php }  // End of for($i = 0; $i < $len; $i++). ?>
        formFragment.appendChild(currencyDropDown);

        var feesDisclaimerCheck = document.createElement('input');
        feesDisclaimerCheck.setAttribute('type', 'checkbox');
        feesDisclaimerCheck.setAttribute('name', 'fees_disclaimer_'+institute_location_id);
        feesDisclaimerCheck.setAttribute('id', 'fees_disclaimer_'+institute_location_id);
        feesDisclaimerCheck.onchange = function(){
            if(this.checked == true){
                this.value = 1;
            } else {
                this.value = 0;
            }
        };

        if(feesDisclaimer == 1) {
            feesDisclaimerCheck.checked = true;
            feesDisclaimerCheck.value = 1;
        } else {
            feesDisclaimerCheck.value = 0;
        }
        formFragment.appendChild(feesDisclaimerCheck);

        var feesDisclaimerSpan = document.createElement('span');
        feesDisclaimerSpan.setAttribute('title', 'One year fees disclaimer');
        feesDisclaimerSpan.innerHTML = 'Show Disclaimer?';
        formFragment.appendChild(feesDisclaimerSpan);

        var feeSaveButton = document.createElement('input');
        feeSaveButton.setAttribute('type', 'button');
        feeSaveButton.setAttribute('name', 'bttnFeeSave'+institute_location_id);
        feeSaveButton.setAttribute('id', 'bttnFeeSave'+institute_location_id);
        feeSaveButton.setAttribute('style', 'padding:0px;');
        feeSaveButton.value = 'OK';
        feeSaveButton.onclick = function(){
            updateFeeForLocation(institute_location_id);
        };
        formFragment.appendChild(feeSaveButton);

        div_id.appendChild(formFragment);
    }

    var institueLocationIdForFeeArray = new Array;
    var institueLocationIdValueForFeeArray = new Array;

<?php if($flow == 'edit') {
        $i = 0;
        foreach($courseFeeLocationWise as $key => $value) { ?>
            institueLocationIdForFeeArray[<?=$i?>] = <?=$key?>;
            institueLocationIdValueForFeeArray[<?=$i?>] = '<?=$value?>' + innerSeparatorChar + '<?=$courseFeeUnitLocationWise[$key]?>' + innerSeparatorChar + '<?=$showFeeDisclaimer[$key]?>'; // added fee disclaimer flag
<?php
            $i++;
        }
    }  ?>
    
    function updateFeeForLocation(institute_location_id) {
        var feeValue = $("fees_amount_"+institute_location_id).value;
        var feeUnit = $("c_fees_currency_"+institute_location_id).value;
        var feeDisclaimerCheck = $("fees_disclaimer_"+institute_location_id).value;

        if(isNaN(feeValue)) {
            alert("Oops! Please enter valid numeric value first.");
            return false;
        }

        if(feeValue == "")
            feeUnit = "";

        var valueToStore = feeValue + innerSeparatorChar + feeUnit + innerSeparatorChar + feeDisclaimerCheck; // LF-4327

        var indexKeyValue = institueLocationIdForFeeArray.indexAt(institute_location_id);
         if(indexKeyValue == -1) {
             institueLocationIdForFeeArray.push(institute_location_id);
             institueLocationIdValueForFeeArray.push(valueToStore);
         } else {
             institueLocationIdValueForFeeArray[indexKeyValue] = valueToStore;
             // alert("Replacing value : '"+valueToStore+"' for index =  "+indexKeyValue);
         }         

         var div_id = $('location_id_div_'+institute_location_id);
         div_id.innerHTML = '<div class="col-2" id="location_id_div_'+institute_location_id+'">'+feeUnit+' '+feeValue+' [ <a href="javascript: void(0);" onClick="javascript: setFeeForLocation('+institute_location_id+');">Update Fees</a> ]</div>';
    }
    
    function addLocationsInQueue() {
        
        var institueLocationIdArray = new Array;
        var institueLocationIdValueArray = new Array;
        
        var sda = document.getElementById('assigned_locations_list_box');
        var len = sda.length;
        if(len == 0) {
            alert("Oops! Please assign any location to proceed!");
            return false;
        }

        for(var j=0; j<len; j++)
        {
                // alert("Value = "+sda.options[j].value);
                institueLocationIdArray.push(parseInt(sda.options[j].value));
                institueLocationIdValueArray.push(sda.options[j].text);
        }

        modifyDisplayTable(institueLocationIdArray, institueLocationIdValueArray);

        if($('location_table_error').parentNode.style.display != 'none') {
            $('location_table_error').parentNode.style.display = 'none';
        }

    }

    function modifyDisplayTable(institueLocationIdArray, institueLocationIdValueArray) {

                    var len = institueLocationIdArray.length;
                    var institute_location_ids = "";

                    $("outer_location_contact_container").innerHTML = '<div class="location-cont"><div class="location-header"><div class="col-1">Location</div><div class="col-2">Fees</div><div class="col-3">Contact Details</div><div class="col-4">Important Dates</div></div>';

                    var feeValue = ""; var feeUnitText = ""; var feeUnitValue = ""; var e;
                    feeValue = ($("c_fees_amount").value == "" ? "" : $("c_fees_amount").value + " ");
                    
                    if(isNaN(feeValue)) { // If user has not entered the numeric global fee..
                        feeValue = "";
                    }

                    if(feeValue != "") {
                        e = $("c_fees_currency");
                        feeUnitText = e.options[e.selectedIndex].text + " ";
                        feeUnitValue = e.options[e.selectedIndex].value;
                    }

                    var valueToStore; var indexKeyValue; var myfeeValue = ""; var myfeeUnit = ""; var isHeadOfcLocationSet = 0;
                    var locationsTuples = ' ';
                    for(var j=0; j<len; j++)
                    {
                           locationsTuples += '<div class="location-details">';
                            if($("headOfclocationIdHiddenVar").value == "") {                                
                               locationsTuples += '<div class="col-1" id="first_col_div_'+j+'"><input type="radio" name="locationRadioBttn" group="radioBttns" value="'+institueLocationIdArray[j]+'" checked />'+institueLocationIdValueArray[j]+' <img src="/public/images/head-office-arrow.gif" style="margin-left:15px" /> Head Office</div>';
                                $("headOfclocationIdHiddenVar").value = institueLocationIdArray[j];
                                isHeadOfcLocationSet = 1;
                            } else if($("headOfclocationIdHiddenVar").value == institueLocationIdArray[j]) {
                                // alert("Head ofc name: "+institueLocationIdValueArray[j]+" , id ="+institueLocationIdArray[j]);
                               locationsTuples += '<div class="col-1" id="first_col_div_'+j+'"><input type="radio" name="locationRadioBttn" group="radioBttns" value="'+institueLocationIdArray[j]+'" checked />'+institueLocationIdValueArray[j]+' <img src="/public/images/head-office-arrow.gif" style="margin-left:15px" /> Head Office</div>';
                                isHeadOfcLocationSet = 1;
                            } else {
                               locationsTuples += '<div class="col-1" id="first_col_div_'+j+'"><input type="radio" name="locationRadioBttn" group="radioBttns" value="'+institueLocationIdArray[j]+'" />'+institueLocationIdValueArray[j]+'</div>';
                            }

                             indexKeyValue = institueLocationIdForFeeArray.indexAt(institueLocationIdArray[j]);
                             // alert("institueLocationId = "+institueLocationIdArray[j]+" , indexKeyValue : "+indexKeyValue);
                             if(indexKeyValue == -1) {
                                 if(feeValue != "") {
                                     institueLocationIdForFeeArray.push(institueLocationIdArray[j]);
                                     valueToStore = feeValue + innerSeparatorChar + feeUnitValue;
                                     institueLocationIdValueForFeeArray.push(valueToStore);
                                     // alert("Value stored = "+institueLocationIdValueForFeeArray[institueLocationIdForFeeArray.indexAt(institueLocationIdArray[j])]);
                                     myfeeValue = feeValue;
                                     myfeeUnit = feeUnitText;
                                 }
                             } else {
                                 // institueLocationIdValueForFeeArray[indexKeyValue] = valueToStore;
                                 feeAttributes = institueLocationIdValueForFeeArray[indexKeyValue].split(innerSeparatorChar);
                                 myfeeValue = feeAttributes[0];
                                 myfeeUnit = feeAttributes[1] + " ";
                                 // alert("Else part myfeeValue = "+myfeeValue+" , myfeeUnit = "+myfeeUnit + " , Array Value = "+institueLocationIdValueForFeeArray[indexKeyValue]);
                             }                             
                            
                           locationsTuples += '<div class="col-2" id="location_id_div_'+institueLocationIdArray[j]+'">' + myfeeUnit + myfeeValue + ' [ <a href="javascript: void(0);" onClick="javascript: setFeeForLocation('+institueLocationIdArray[j]+');">Update Fees</a> ]</div>';

                            if((typeof(course_contact_details_location_array[institueLocationIdArray[j]]) !='undefined') && (course_contact_details_location_array[institueLocationIdArray[j]] != (institueLocationIdArray[j]+"|=#=||=#=||=#=||=#=|"))) {
                                var name_array = course_contact_details_location_array[institueLocationIdArray[j]].split(innerSeparatorChar);
                                var name = name_array[1];
                                if(name.length >5) {
                                        name = name.substr(0,5);
                                        name = name+"...";
                                } 
                            	locationsTuples += '<div class="col-3"><span id="'+institueLocationIdArray[j]+'ownername'+j+'">'+name+'</span>[ <a href="javascript:void(0);" onclick="showContactDetailsLayer('+institueLocationIdArray[j]+',this,'+"'"+institueLocationIdArray[j]+'ownername'+j+"'"+');">Update</a> ]</div>';
                            } else {
                            	locationsTuples += '<div class="col-3"><span id="'+institueLocationIdArray[j]+'ownername'+j+'"></span>[ <a href="javascript:void(0);" onclick="showContactDetailsLayer('+institueLocationIdArray[j]+',this,'+"'"+institueLocationIdArray[j]+'ownername'+j+"'"+');">Add</a> ]</div>';
                            }
                            if((typeof(imp_date_location_array[institueLocationIdArray[j]]) !='undefined') && (imp_date_location_array[institueLocationIdArray[j]] != (institueLocationIdArray[j]+"___"))) {
                            	locationsTuples += '<div class="col-4">[ <a href="javascript:void(0);" onclick="showImportantDateLayer('+institueLocationIdArray[j]+',this);">Update</a> ]</div>';
                            } else {
                            	locationsTuples += '<div class="col-4">[ <a href="javascript:void(0);" onclick="showImportantDateLayer('+institueLocationIdArray[j]+',this);">Add</a> ]</div>';
                            }
                            locationsTuples += '</div>';

                            institute_location_ids += (institute_location_ids == "" ? "" : ", ") + institueLocationIdArray[j];

                            myfeeUnit = ""; myfeeValue = "";
                            
                    } // End of for(var j=0; j<len; j++).
                    $("outer_location_contact_container").innerHTML += locationsTuples;
                    if(isHeadOfcLocationSet == 0) {    // i.e. Head ofc not set yet, now make the 1st location as Head Ofc..
                        $("first_col_div_0").innerHTML = '<input type="radio" name="locationRadioBttn" group="radioBttns" value="'+institueLocationIdArray[0]+'" checked />'+institueLocationIdValueArray[0]+' <img src="/public/images/head-office-arrow.gif" style="margin-left:15px" /> Head Office';
                        $("headOfclocationIdHiddenVar").value = institueLocationIdArray[0];
                    }

                   $("outer_location_contact_container").innerHTML += '<br /><div class="location-details" style="background:#f9f9f9"><span style="padding:3px 12px"><a href="javascript: void(0);" onclick="javascript: setHeadOffice();"><img src="/public/images/head-office-arrow.gif" /> Set as Head Office</a></span></div><div style="clear:both"></div>';
                    // alert("institute_location_ids = "+institute_location_ids);
                   $("locationInfoHiddenVar").value = institute_location_ids;

    }

    function setHeadOffice() {

        var locationId = getRadioButtonsCheckedValue(document.getElementsByName("locationRadioBttn"));
        if(locationId == "") {
            alert("Oops! Please select any location first!");
            return false;
        }
        
        if($("headOfclocationIdHiddenVar").value == locationId) {
            return false;
        }

        var institueLocationIdArray = $("locationInfoHiddenVar").value.split(", ");
        var len = institueLocationIdArray.length;

        for(i = 0; i < len; i++){
            if(institueLocationIdArray[i] == locationId) {
                var locationIndex = i;
                break;
            }
        }

        var oldHeadOfcLocationId = $("headOfclocationIdHiddenVar").value;
        for(i = 0; i < len; i++){
            if(institueLocationIdArray[i] == oldHeadOfcLocationId) {
                var oldHeadOfcLocationIndex = i;
                break;
            }
        }


        locationInfo = $('first_col_div_'+locationIndex).innerHTML;
        var lastIndex = locationInfo.lastIndexOf('>');
        locationInfo = trim(locationInfo.substring((lastIndex+1)));
	
	$('first_col_div_'+locationIndex).innerHTML = '<input type="radio" name="locationRadioBttn" group="radioBttns" value="'+locationId+'" checked /> '+locationInfo+' <img src="/public/images/head-office-arrow.gif" style="margin-left:15px" /> Head Office';
        locationInfo = $('first_col_div_'+oldHeadOfcLocationIndex).innerHTML;        
        lastStringIndex = (locationInfo.toLowerCase()).lastIndexOf('<img');
        
        locationInfo = trim(locationInfo.substring(0, (lastStringIndex -1)));
        lastIndex = locationInfo.lastIndexOf('>');
        locationInfo = trim(locationInfo.substring((lastIndex+1)));
        
        $('first_col_div_'+oldHeadOfcLocationIndex).innerHTML = '<input type="radio" name="locationRadioBttn" group="radioBttns" value="'+oldHeadOfcLocationId+'" /> '+locationInfo;
        $("headOfclocationIdHiddenVar").value = locationId;
	
        // alert("hd ofc : "+$("headOfclocationIdHiddenVar").value);
    }

    var tempArray = {};
    function updateAvailableLocations(startAt) {
            var selected_subcription = getSelectedSubscription();
            // alert("updateAvailableLocations, selected_subcription = "+selected_subcription);
            var sda = document.getElementById('available_locations_list_box');
            var len = sda.length;
            if(selected_subcription == 1) {
                tempArray = {};
                <?php if($flow != 'edit') { ?>
                         setDefaultLocationState();
                <?php } ?>

                for(var j=startAt; j<len; j++) {
                        if(sda[j] == null || sda[j] == undefined)
                        break;
                        var tmp = sda.options[j].text;
                        var tmp1 = sda.options[j].value;
                        tempArray[tmp1] = tmp;
                        sda.remove(j);
                        j--;
                }
            } else {
                for(var i in tempArray) {
                    // alert("i = "+i+" , val =  "+tempArray[i]);
                    var y = document.createElement('option');
                    y.text = tempArray[i];;
                    y.value= i;
                    try
                    {
                        sda.add(y,null);
                    }
                    catch(ex)
                    {
                        sda.add(y);
                    }
                }
            }
    }

    function setDefaultLocationState() {
            var sda1 = document.getElementById('assigned_locations_list_box');
            var len1 = sda1.length;
            for(i = 0; i < len1; i++) {
                // alert("yes i : "+i);
                 if(sda1[i] == null || sda1[i] == undefined)
                    break;

                 sda1[i].selected = 'selected';
            }
            
            moveinid();
            sortItems($("available_locations_list_box"));

            $("locationInfoHiddenVar").value = "";
            $("outer_location_contact_container").innerHTML = '<div class="location-details" style="background:#f9f9f9"><span style="padding:0px 12px; height:20px;">No locations assigned yet!</span></div>';
    }
    
    function moveoutid()
    {
            var sda = document.getElementById('available_locations_list_box');
            var len = sda.length;
            var sda1 = document.getElementById('assigned_locations_list_box');

            for(var j=0; j<len; j++)
            {
                    if(sda[j] == null || sda[j] == undefined)
                    break;

                    if(sda[j].selected)
                    {
                            if(!validateSubscriptionForMultiLocations(0)) {
                                return false;
                            }

                            var tmp = sda.options[j].text;
                            var tmp1 = sda.options[j].value;
                            sda.remove(j);
                            j--;
                            var y=document.createElement('option');
                            y.text=tmp;
                            y.value= tmp1;
                            try
                            {sda1.add(y,null);
                            }
                            catch(ex)
                            {
                            sda1.add(y);
                            }
                    }
            }

            sortItems(sda1);
    }

function sortItems(lb) {    
    arrTexts = new Array();
    arrValues = new Array();
    arrOldTexts = new Array();

    for(i=0; i<lb.length; i++)  {
      arrTexts[i] = lb.options[i].text;
      arrValues[i] = lb.options[i].value;
      arrOldTexts[i] = lb.options[i].text;
    }
    
    arrTexts.sort();

    for(i=0; i<lb.length; i++)  {
      lb.options[i].text = arrTexts[i];
      // lb.options[i].value = arrTexts[i];
      for(j=0; j<lb.length; j++)
      {
        if (arrTexts[i] == arrOldTexts[j])
        {
            lb.options[i].value = arrValues[j];
            j = lb.length;
        }
      }
    }
}

    function moveinid()
    {
            var sda = document.getElementById('available_locations_list_box');
            var sda1 = document.getElementById('assigned_locations_list_box');
            var len = sda1.length;
            for(var j=0; j<len; j++)
            {
                    if(sda1[j] == null || sda1[j] == undefined)
                    break;

                    if(sda1[j].selected)
                    {
                            var tmp = sda1.options[j].text;
                            var tmp1 = sda1.options[j].value;
                            sda1.remove(j);
                            j--;
                            var y=document.createElement('option');
                            y.text=tmp;
                            y.value=tmp1;
                            try
                            {
                            sda.add(y,null);}
                            catch(ex){
                            sda.add(y);
                            }

                    }
            }
    }

function getSelectedSubscription(){
    var packType = parseInt(<?php if($flow == "edit" && $packType == 1) { echo '1'; } else { echo '0';}   ?>);
    var selected_subcription;

    if(packType == 1){
       selected_subcription = packType;
    } else if(!$('selectedSubscription')) {
         return 'NO_VALUE';
    } else {
        selected_subcription = $('selectedSubscription').value;
        selected_subcription = subscriptionsList[selected_subcription].BaseProductId;
    }
    return selected_subcription;
}

function validateSubscriptionForMultiLocations(lenToCheck) {
    
    var selected_subcription = getSelectedSubscription();
    if(selected_subcription == "NO_VALUE")
        return true;
    
    // alert("validateSubscriptionForMultiLocations, selected_subcription = "+selected_subcription+" , len of box = "+document.getElementById('assigned_locations_list_box').length)
    if(selected_subcription == GOLD_SL_LISTINGS_BASE_PRODUCT_ID) {
        // Return false if this listing is multilocation..
        var sda = document.getElementById('assigned_locations_list_box');
        var len = sda.length;
        if(len > lenToCheck) {
            alert("You can select at most 1 location in Gold SL subscription.");
            return false;
        } else if($("locationInfoHiddenVar").value != "") {
            var locationInfoCount = ($("locationInfoHiddenVar").value.split(", ")).length;
            // alert("locationInfoCount = "+locationInfoCount);
            if(locationInfoCount >= 2) {
                alert("You can select and save at most 1 location in Gold SL subscription.");
                $('location_table_error').innerHTML = "You can select and save at most 1 location in Gold SL subscription."
                $('location_table_error').parentNode.style.display = 'block';
                $('location_table_error').focus();
                return false;
            }
        }
    }

    return true;
}

    <?php if($flow == 'edit') { ?>
updateAvailableLocations(0);
<?php } ?>
</script>
