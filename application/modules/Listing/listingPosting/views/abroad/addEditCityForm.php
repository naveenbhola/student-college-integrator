<?php
$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
?>
<script>
    var abroadStatesList =  <?php echo json_encode($abroadStatesList); ?>;
    var countryStateMapping = Array();
    abroadStatesList.forEach(function(entry)
    {
        if (typeof(countryStateMapping[entry.countryId]) == 'undefined') {
            countryStateMapping[entry.countryId] = Array();
        }

        countryStateMapping[entry.countryId] = countryStateMapping[entry.countryId].concat([[entry.state_id, entry.state_name]]);
    });

</script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>

<div class="abroad-cms-rt-box">
    <?php
    $displayData["breadCrumb"] 	= array(array("text" => "Lookups", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CITY ),
        array("text" => "City", "url" => "") );
    $displayData['pageTitle']  = $formName == ENT_SA_FORM_EDIT_CITY?"Edit City":"Add City";
    $pageAction = $formName == ENT_SA_FORM_EDIT_CITY ? "editCityAction":"addCityAction";

    // load the title section
    $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
    ?>

    <form id="form_<?=$formName;?>" name="addEditCityForm" method="post" action="/listingPosting/AbroadListingPosting/<?=$pageAction?>" onkeypress="return event.keyCode != 13;">
        <div class="cms-form-wrapper clear-width">
            <div class="clear-width">
                <h3 class="section-title">City details</h3>
                <div class="cms-form-wrap" style="margin-bottom:10px;">
                    <!-- Singleton city box starts-->
                    <input name="oldCountryId" value="<?php echo($countryIdAddCityForm);?>" hidden>
                    <input name="oldStateId" value="<?php echo($stateId); ?>" hidden>
                    <input name="oldCityName" value="<?php echo($cityName); ?>" hidden>
                    <input name="cityId" value="<?php echo($cityId); ?>" hidden>
                    <div class="singleton-city-box" style="float:left;">
                        <ul>
                            <li>
                                <label>Country Name* : </label>
                                <div class="cms-fields">
                                    <select id="country_<?="1_".$formName?>" onblur="showErrorMessage(this, '<?="1_".$formName?>');" caption="Country" name="countryPL[]" class="universal-select cms-field" onchange="showErrorMessage(this, '<?="1_".$formName?>');populateStateList(this)" required="true" autocomplete="off" validationType="select">
                                        <option value>Select a Country</option>
                                        <?php
                                        $options = "";
                                        foreach($abroadCountries as $key=>$countryObj)
                                        {
                                            $options .= "<option value=".$countryObj->getId().">".$countryObj->getName()."</option>";
                                        }
                                        echo $options;
                                        ?>
                                    </select>

                                    <div style="display: none" class="errorMsg" id="country_<?="1_".$formName?>_error"></div>
                                </div>
                            </li>
                            <li>
                                <label>State Name : </label>
                                <div class="cms-fields">
                                    <select id="state_<?="1_".$formName?>" onblur="showErrorMessage(this, '<?="1_".$formName?>');" name="statePL[]" class="universal-select cms-field" disabled="disabled" autocomplete="off" required="true" validationType="select" caption="State">
                                        <option value>Select a State</option>
                                    </select>

                                    <div style="display: none" class="errorMsg stateErrorMsg" id="state_<?="1_".$formName?>_error"></div>
                                </div>
                            </li>

                            <li>
                                <label>City Name* : </label>
                                <div class="cms-fields">
                                    <div>
                                        <input value="<?php echo $cityName?>" id="city_<?="1_".$formName?>" name="cityTB[]" class="universal-txt-field cms-text-field" type="text" required="true" caption="City" onblur="showErrorMessage(this, '<?="1_".$formName?>');<?php echo($formName == ENT_SA_FORM_EDIT_CITY?'':'isCityAlreadyExists(this);');?>" minlength="1" maxlength="50" autocomplete="off" validationType="str" />
                                        <div style="display: none" class="errorMsg" id="city_<?="1_".$formName?>_error"></div>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <label style="padding-top: 12px">Lat Long of the City* :</label>
                                <div class="cms-fields" style="margin-top: -3px">
                                    <div class="direction-col">
                                        <label>Latitude</label>
                                        <input id="latitude_<?="1_".$formName?>" value = "<?php echo $latitude?>" type="text" name="Latitude" required="true" caption="latitude" onblur="showErrorMessage(this, '<?="1_".$formName?>');" minlength="1" maxlength="10" autocomplete="off" validationType="float">
                                        <div style="display: none" class="errorMsg" id="latitude_<?="1_".$formName?>_error"></div>
                                    </div>
                                    <div class="direction-col units">
                                        <input required="true" onchange="showErrorMessage(this, '<?="1_".$formName?>');" validationType="radio" type="radio" name="latitudeDir" value="n" id="latitude_n_<?="1_".$formName?>" caption="direction" <?php echo ($latitudeDirection == 'n'?'checked':''); ?> >N
                                        <input required="true" onchange="showErrorMessage(this, '<?="1_".$formName?>');" validationType="radio" type="radio" name="latitudeDir" value="s" id="latitude_n_<?="1_".$formName?>" caption="direction" <?php echo ($latitudeDirection == 's'?'checked':''); ?> >S
                                        <div style="display: none" class="errorMsg" id="latitude_n_<?="1_".$formName?>_error"></div>
                                    </div>
                                    <div class="direction-col">
                                        <label>Longitude</label>
                                        <input id="longitude_<?="1_".$formName?>" value = "<?php echo $longitude?>" type="text" name="Longitude" required="true" caption="longitude" onblur="showErrorMessage(this, '<?="1_".$formName?>');" minlength="1" maxlength="10" autocomplete="off" validationType="float">
                                        <div style="display: none" class="errorMsg" id="longitude_<?="1_".$formName?>_error"></div>
                                    </div>
                                    <div class="direction-col units">
                                        <input required="true" onchange="showErrorMessage(this, '<?="1_".$formName?>');" validationType="radio" type="radio" name="longitudeDir" value="e" id="longitude_e_<?="1_".$formName?>" caption="direction" <?php echo ($longitudeDirection == 'e'?'checked':''); ?> >E
                                        <input required="true" onchange="showErrorMessage(this, '<?="1_".$formName?>');" validationType="radio" type="radio" name="longitudeDir" value="w" id="longitude_e_<?="1_".$formName?>" caption="direction" <?php echo ($longitudeDirection == 'w'?'checked':''); ?> >W
                                        <div style="display: none" class="errorMsg" id="longitude_e_<?="1_".$formName?>_error"></div>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <label style="">Type of City :</label>
                                <div class="cms-fields" style="">
                                    <select name="citySize" class="universal-select cms-field">
                                        <option value="">Select a City Type</option>
                                        <option value="small">Small</option>
                                        <option value="medium">Medium</option>
                                        <option value="large">Large</option>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label style="">Weather of City :</label>
                                <div class="cms-fields" style="">
                                    <div class="waetherCol">
                                        <?php
                                        $j = 0;
                                        foreach ($months as $month) {
                                            $j++;
                                            ?>
                                            <div class="setMnth">
                                                <label class="monthLabel"><?php echo $month; ?></label>
                                                <select name="minTemp[]" id="minTemp<?=$j?>" class="minTemp tempSelect universal-select cms-field">
                                                    <option value="">Min</option>
                                                    <?php
                                                    for ($i=-60; $i < 61; $i++) {
                                                        ?>
                                                        <option value="<?=$i?>"><?=$i?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <select name="maxTemp[]" id="maxTemp<?=$j?>" class="maxTemp tempSelect universal-select cms-field">
                                                    <option value="">Max</option>
                                                    <?php
                                                    for ($i=-60; $i < 61; $i++) {
                                                        ?>
                                                        <option value="<?=$i?>"><?=$i?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                                if($month == 'January'){
                                                    ?>
                                                    <a href="javascript:void(0);" onclick="copyJanuaryFieldsToAllMonths()">Copy to all months</a>
                                                    &nbsp;
                                                    <a href="javascript:void(0);" onclick="resetAllMonthFields()">Reset all months</a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <label style="">Wiki page for City :</label>
                                <div class="cms-fields" style="">
                                    <input type="text" id="wikiUrl_<?="1_".$formName?>" name="wikiUrl" value="<?php echo $wikiPageUrl?>" validationType="" caption="wiki url" onblur="showErrorMessage(this, '<?="1_".$formName?>');" minlength="1" maxlength="250" class="universal-txt-field cms-text-field" autocomplete="off">
                                    <div style="display: none" class="errorMsg" id="wikiUrl_<?="1_".$formName?>_error"></div>
                                </div>
                            </li>
                            <li>
                                <label style="">Off-Campus Accommodation:</label>
                                <div class="cms-fields" style="">
                                    <textarea validationtype="html" onblur="showErrorMessage(this, '<?="1_".$formName?>');" onchange="showErrorMessage(this, '<?="1_".$formName?>');" name="offCampusAccommodationDesc" caption="off-campus accommodation description" id="offCampusAccommodationDesc_<?="1_".$formName?>" maxlength="5000" minlength="10" class="cms-textarea tinymce-textarea"><?php echo $offCampusAccommodationDesc?></textarea>
                                    <div style="display: none" class="errorMsg" id="offCampusAccommodationDesc_<?="1_".$formName?>_error"></div>
                                </div>
                            </li>
                            <li>
                                <label style="">Off-Campus Accommodation link:</label>
                                <div class="cms-fields" ="">
                                <?php
                                    $i = 1;
                                    do{
                                       echo('<div class="offCampusAccommodationTuple" style="margin-top: 10px;">');
                                       echo('<input type="text" value="'.($offCampusAccommodationUrl[$i-1]?$offCampusAccommodationUrl[$i-1]:'').'" id="offCampusAccommodationUrl_'.$i.'_'.$formName.'" name="offCampusAccommodationUrl[]" validationType="link" caption="off-campus accommodation url" onblur="showErrorMessage(this, \''.$i.'_'.$formName.'\');" minlength="1" maxlength="250" class="universal-txt-field cms-text-field" autocomplete="off">');
                                       echo('<a href="javascript:void(0);" class="remove-link-2" onclick="removeOffCampusAccommodationLink(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove Link</a>');
                                       echo(' </div>');
                                       $i++;
                                    }while($i<=sizeof($offCampusAccommodationUrl));
                                echo('<div style="display: none" class="errorMsg offCampusAccommodationUrl" id="offCampusAccommodationUrl_1_'.$formName.'_error"></div>');
                                ?>
                                <a class="addMoreItem" href="JavaScript:void(0);" onclick="addMoreOffCampusAccommodationLink(this)">[+] Add more links</a>
                    </div>
                    </li>
                    <li>
                        <label style="">Population :</label>
                        <div class="cms-fields" style="">
                            <input type="text" value="<?php echo $cityPopulation ?>" id="cityPopulation_<?="1_".$formName?>" name="cityPopulation" validationType="numeric" caption="city population" onblur="showErrorMessage(this, '<?="1_".$formName?>');" minlength="1" maxlength="15" class="universal-txt-field cms-text-field" autocomplete="off">
                            <div style="display: none" class="errorMsg" id="cityPopulation_<?="1_".$formName?>_error"></div>
                        </div>
                    </li>
                    <li>
                        <label for="">Videos Link</label>
                        <div class="cms-fields videoFields">
                            <?php
                                $i = 1;
                                do {
                                    echo('<div class="video_link videoLinkDiv">');
                                    echo('<div class="add-more-sec">');
                                    echo('<label>Title</label>');
                                    echo('<input value="'.$videoData[$i-1]['videoTitle'].'" id="videoTitle_' . $i . '_' . $formName . '" name="videoTitle[]" class="universal-txt-field cms-text-field" type="text" caption="video title" onblur="showErrorMessage(this, ' . $i . '_' . $formName . '");" minlength="1" maxlength="100" autocomplete="off" validationType="str" />');
                                    echo('<label>Link</label>');
                                    echo('<input value="'.str_replace("youtube.com/v/","youtube.com/watch?v=",$videoData[$i-1]['videoUrl']).'" type="text" id="videoUrl_' . $i . '_' . $formName . '" name="videoUrl[]" validationType="" caption="youtube video url" onblur="showErrorMessage(this, ' . $i . '_' . $formName . '");" minlength="1" maxlength="250" class="universal-txt-field cms-text-field" autocomplete="off">');
                                    echo('<div style="display: none" class="errorMsg" id="videoUrl_' . $i . '_' . $formName . '_error"></div>');
                                    echo('</div>');
                                    echo('<a href="javascript:void(0);" class="remove-link-2" onclick="removeYoutubeVideoLink(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove Video</a>');
                                    echo('</div>');
                                    $i++;
                                } while($i<=sizeof($videoData));
                            ?>
                            <div class="videoLinkDiv2"></div>
                            <div style="display: none" class="errorMsg" id="videoTitle_<?="1_".$formName?>_error"></div>
                            <a class="addMoreItem" href="JavaScript:void(0);" onclick="addMoreYoutubeVideoLink(this);">[+] Add more Videos</a>
                        </div>
                    </li>
                    </ul>

                    <a href="javascript:void(0);" style="display:none;" class="remove-link" onclick="removeAddCityRow(this);"><i class="abroad-cms-sprite remove-icon"></i>Delete</a>
                </input>
                <!-- Singleton city box ends-->
            </div>
        </div>
</div>

<div class="button-wrap">
    <a href="javascript:void(0);" onclick="saveAndSubmitCityForm(this, '<?=$formName?>');" class="orange-btn">Save & Publish</a>
    <a href="javascript:void(0);" onclick="cancelAction('<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CITY?>')" class="cancel-btn">Cancel</a>
</div>

</div>

<div class="clearFix"></div>
</form>
</div>
<script>

    window.onbeforeunload =confirmExit;
    var preventOnUnload = false;
    var saveInitiated = false;
    function confirmExit()
    {//alert(saveInitiated);
        if(preventOnUnload == false)
            return 'Any unsaved change will be lost.';
    }

    function startCallback() {
        // make something useful before submit (onStart)
        //alert("Going to submit");
        return true;
    }

    function completeCallback(response) {
        saveInitiated = false;
        // check response
        var respData;
        if (response != 0) {
            respData = JSON.parse(response);
        }


        if (typeof respData != 'undefined' && respData.status == 0) {
            alert(respData.msg);
        }
        else{
            alert("City has been saved successfully.");
            //window.onbeforeunload = null;
            preventOnUnload = true;
            window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CITY?>";
        }
    }

    function initFormPosting() {
        AIM.submit(document.getElementById('form_<?php echo $formName; ?>'), {'onStart' : startCallback, 'onComplete' : completeCallback});
    }

    if(document.all) {
        document.body.onload = initFormPosting;
    } else {
        initFormPosting();
    }

</script>

