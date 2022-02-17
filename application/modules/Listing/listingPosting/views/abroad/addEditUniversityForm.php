<?php
    /*
     * Note :: this same file is rendered for both add & edit.
     * Any kind of distinction can be made using $formName (addUniversityForm / editUniversityForm)
     */
    //echo "me".$formName;
    //prepare options for country select box
    $countryDropDownHtml = '<option value="" >Select a Country</option>';
    $expertDropDownHtml = '<option value="" >Select Author</option>';
    $univMainData = $formData['listing_data'][0];

    $univAttributes = $formData['listing_attributes'];
    $customAttributes = $formData['univ_custom_attributes'];
    $univDeptData = $formData['univ_department'];
    $univCampusData = $formData['univ_campus'];
    $univLinksData = $formData['univ_links'];
    $univMediaData = $formData['univ_media'];
    $scoreReporting = $formData['scoreReporting'];
    if($univMainData['announcement_text'] || $univMainData['announcement_action_text'] || $univMainData['announcement_start_date'] || $univMainData['announcement_end_date']) {
        $checkAnnouncement = "checked";
        $displayAnnouncement = "";
        $required = "required = true";
    } else {
        $checkAnnouncement = "";
        $displayAnnouncement = "style='display:none'";
        $required = "";
    }
    if($univMainData['announcement_start_date'] == '0000-00-00') {
        $univMainData['announcement_start_date'] = "";
    }
    if($univMainData['announcement_end_date'] == '0000-00-00') {
        $univMainData['announcement_end_date'] = "";
    }
    // shiksha apply.. data for edit mode
    $univShikshaApplyData = $formData['univ_application_profiles'];

    foreach($abroadCountries as $country)
    {
        /* if form is opened in edit mode country should get preselected*/
        if($formName == "editUniversityForm"){
            if($country->getId()==$univMainData['univ_country_id']){
                $selected ='selected="selected"';
            }
            else{
                $selected ="";
            }
        }
        $countryDropDownHtml .=  '<option '.$selected.' value='.$country->getId().'>'.$country->getName().'</option>';
    }
    foreach($expertsList as $key=>$value) {
    if($univMainData['univ_expert']==$value['user_id']){
        $expertDropDownHtml .=  '<option selected="selected" value="'.$value['user_id'].'">'.$value['name'].' ('.$value['origin'].')'.'</option>';
    } else {
        $expertDropDownHtml .=  '<option value="'.$value['user_id'].'">'.$value['name'].' ('.$value['origin'].')'.'</option>';
    }
    }
    if($formName != "addUniversityForm")
    {
        $typeOfInstitute1 = array($univMainData['univ_type1']=>" checked "  );
        $typeOfInstitute2 = array($univMainData['univ_type2']=>" checked "  );
    } else {
        $typeOfInstitute1 = array("public"=>" checked "  );
        $typeOfInstitute2 = array("university"=>" checked "  );
    }
    if(is_null($univAttributes['latitudeDir']['attrVal']))
    {
        $latDir =  array("n"=>"checked ");
    }else{
        $latDir = array($univAttributes['latitudeDir']['attrVal']=>" checked "  );
    }
    if(is_null($univAttributes['longitudeDir']['attrVal']))
    {
        $longDir = array("e"=>"checked ");
    }else{
        $longDir = array($univAttributes['longitudeDir']['attrVal']=>" checked "  );
    }
    $disabledTextareaClass = 'disabledEditor';
    $firstSectionHeadingImageClass = "minus-icon";
    $otherSectionHeadingImageClass = ($formName == "addUniversityForm" ? "plus-icon":"minus-icon");
    $otherSectionDisplayStyle      = ($formName == "addUniversityForm" ? "display:none;":"");

?>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">

    <?php
        $displayData["breadCrumb"] 	= array(array("text" => "All Universities", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_UNIVERSITY ),
                                                array("text" => ($formName=="addUniversityForm"?"Add New":"Edit")." University", "url" => "") );
        $displayData["pageTitle"]  	= ($formName=="addUniversityForm"?"Add New":"Edit")." University Details";
        if($formName =="editUniversityForm")
        {
            $displayData["pageTitle"] .= "<label style='color:red;'>".($univMainData['univ_status']=="draft"?" (Draft Version)":" (Published Version)")."</label>";
        }
        if($formName != "addUniversityForm"){
            $displayData["lastUpdatedInfo"] = array("title"    => "Last modified",
                                                    "date"     => $univMainData['univ_last_modify_date'],
                                                    "username" => $univMainData['univ_modified_by_name']);
        }
        // load the title section
        $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);

        if($formName == ENT_SA_FORM_EDIT_UNIVERSITY) {
            $info = array();
            $info['percentage_completion'] = $univMainData['univ_percentage_completion'];
            $this->load->view("listingPosting/abroad/listingProgressBar", $info);
        }
    ?>


    <form id ="form_<?php echo $formName; ?>" name="<?php echo $formName; ?>" action="<?=ENT_SA_CMS_PATH?>saveUniversityFormData" method="POST" enctype="multipart/form-data">
        <div class="cms-form-wrapper clear-width">
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$firstSectionHeadingImageClass?>"></i>Add Author</h3>
                <div class="cms-form-wrap cms-accordion-div">
                    <ul>
                        <li>
                             <label>Author of this content* : </label>
                            <div class="cms-fields">

                                <select class="universal-select cms-field"  name = "univExpert" id = "expert_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" required = true caption="Author" tooltip="univ_expert" validationType = "select">
                                    <?php echo $expertDropDownHtml; ?>
                                </select>
                                <div style="display: none" class="errorMsg" id="expert_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                    </ul>
                  </div>
                </div>
                <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$firstSectionHeadingImageClass?>"></i>University Basic Information</h3>
                <div class="cms-form-wrap cms-accordion-div">
                    <ul>
                        <li>
                            <label>University Name* : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" name = "univName" id="univName_<?php echo $formName; ?>" type="text" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" required = true maxlength=100 caption ="University Name" validationType = "str" value="<?=htmlspecialchars($univMainData['univ_name'])?>"/>
                                <div style="display: none" class="errorMsg" id="univName_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Country Name* : </label>
                            <div class="cms-fields">
                                <?php
                                    if($formName == "editUniversityForm"){
                                        $disabled = 'disabled = "disabled"';
                                    }
                                    else{
                                        $disabled = "";
                                    }
                                ?>
                                <select class="universal-select cms-field" <?=$disabled?> name = "univCountry" id = "country_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');populateStateCityPicklists(this, '<?php echo $formName; ?>');" required = true caption="Country" tooltip="univ_country" validationType = "select">
                                    <?php echo $countryDropDownHtml; ?>
                                </select>
                                <div style="display: none" class="errorMsg" id="country_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>University Logo* : </label>
                            <div class="cms-fields">
                                <?php if($formName == "addUniversityForm"){ ?>
                                    <input type="file" id="univLogo_<?php echo $formName; ?>" name = "univLogo[]" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" required = true caption = "University Logo" tooltip = "univ_logo" validationType = "file"/>
                                    <div style="display: none" class="errorMsg" id="univLogo_<?php echo $formName; ?>_error"></div>
                                <?php } else {
                                        if($univMainData['univ_logo_link'] == ""){
                                            $styleForLogoInput = '';
                                            $styleForLogoImageBox = 'style="display: none;"';
                                            $requiredForLogoInput = 'required=true';
                                        }
                                        else{
                                            $styleForLogoInput = 'style="display: none;"';
                                            $styleForLogoImageBox = '';
                                            $requiredForLogoInput = '';
                                            //$univMainData['univ_logo_link'] = MEDIAHOSTURL.$univMainData['univ_logo_link'];
                                        }
                                    ?>
                                    <input type="file" id="univLogo_<?php echo $formName; ?>" name = "univLogo[]" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" <?=$requiredForLogoInput?> caption = "University Logo" <?=$styleForLogoInput?> tooltip = "univ_logo" validationType = "file"/>
                                    <div style="display: none" class="errorMsg" id="univLogo_<?php echo $formName; ?>_error"></div>
                                    <div class="image-box" <?=$styleForLogoImageBox?>>
                                        <img src="<?= ($univMainData['univ_logo_link']!=''? MEDIAHOSTURL.$univMainData['univ_logo_link']:'');?>" width="116" height="117" alt="logo"><i class="abroad-cms-sprite remove-icon2 remove-univ-logo"></i>
                                        <input type="hidden" id = "univLogoMediaUrl" name = "univLogoMediaUrl" value="<?=($univMainData['univ_logo_link'])?>"/>
                                    </div>
                                <?php } ?>
                                <div style="display: none" class="errorMsg" id="univLogo_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Year of establishment : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" name = "univEstablishedYear" id="univEstablished_<?php echo $formName; ?>" type="text" minlength = "4" maxlength = "4" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Year of establishment" tooltip = "univ_estd"  validationType = "year" value="<?=($univMainData['univ_established'])?>"/>
                                <div style="display: none" class="errorMsg" id="univEstablished_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Acronym : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" name = "univAcronym" type="text" id="univAcronym_<?php echo $formName; ?>" maxlength = "100" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Acronym" tooltip = "univ_acrnym"  validationType = "str" value="<?=($univMainData['univ_acronym'])?>"/>
                                <div style="display: none" class="errorMsg" id="univAcronym_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                         <li>
                            <label>University Wiki : </label>
                            <div class="cms-fields">
                                <textarea class="cms-textarea tinymce-textarea" name = "univWiki" id="univWiki_<?php echo $formName; ?>" maxlength = "5000" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');"   caption = "University Wiki" tooltip = "univ_wiki" validationType = "html"><?php echo $univAttributes['univ_wiki']['attrVal']; ?></textarea>
                                <div style="display: none" class="errorMsg" id="univWiki_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Why join or USP? : </label>
                            <div class="cms-fields">
                                <textarea class="cms-textarea tinymce-textarea" name = "univUSP" id="univUSP_<?php echo $formName; ?>" maxlength = "1000" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "USP" tooltip = "univ_usp" validationType = "html"><?php echo $univAttributes['why_join']['attrVal']; ?></textarea>
                                <div style="display: none" class="errorMsg" id="univUSP_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Type of Institute* : </label>
                            <div class="cms-fields" style="margin-top:4px;">
                                <input type="radio" name = "instituteType1" id = "univInsType1Public_<?php echo $formName; ?>"    required = true  caption = "Institute Type 1" tooltip = "univ_instype1" value="public" <?=$typeOfInstitute1["public"]?> /> Public
                                <input type="radio" name = "instituteType1" id = "univInsType1Private_<?php echo $formName; ?>"   required = true  caption = "Institute Type 1" tooltip = "univ_instype1" value="private" <?=$typeOfInstitute1["private"]?> /> Private
                                <input type="radio" name = "instituteType1" id = "univInsType1NonProfit_<?php echo $formName; ?>" required = true  caption = "Institute Type 1" tooltip = "univ_instype1" value="not_for_profit" <?=$typeOfInstitute1["not_for_profit"]?> /> Not for Profit
                                <div style="display: none" class="errorMsg" id="univInsType1_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Type of Institute II* : </label>
                            <div class="cms-fields" style="margin-top:4px;">
                                <?php
                                    if ($formName == "editUniversityForm"){
                                        $disabled = 'disabled = "disabled"';
                                    }
                                    else{
                                        $disabled = '';
                                    }
                                ?>
                                <input type="radio" name = "instituteType2" <?=$disabled?> id = "univInsType2University_<?php echo $formName; ?>" required = true  caption = "Institute Type 2" tooltip = "univ_instype2" value="university" <?=$typeOfInstitute2["university"]?> /> University
                                <input type="radio" name = "instituteType2" <?=$disabled?> id = "univInsType2College_<?php echo $formName; ?>"    required = true  caption = "Institute Type 2" tooltip = "univ_instype2" value="college" <?=$typeOfInstitute2["college"]?> /> College
                                <div style="display: none" class="errorMsg" id="univInsType2_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label style="padding-top: 12px">Lat/Long of Institute main campus :</label>
                            <div class="cms-fields" style="margin-top: -3px">
                                <div class="direction-col units">
                                    <input name="latitudeDir" value="n" id="latitude_n_<?php echo $formName; ?>" caption="direction" type="radio" onblur="showErrorMessage(this, '<?php echo $formName; ?>');" onchange="showErrorMessage(this, '<?php echo $formName; ?>');" <?php echo $latDir['n']; ?>>N
                                    <input name="latitudeDir" value="s" id="latitude_s_<?php echo $formName; ?>" caption="direction" type="radio" onblur="showErrorMessage(this, '<?php echo $formName; ?>');" onchange="showErrorMessage(this, '<?php echo $formName; ?>');" <?php echo $latDir['s']; ?>>S
                                    <div style="display: none" class="errorMsg" id="latitudeDir_<?php echo $formName; ?>_error"></div>
                                </div>
                                <div class="direction-col">
                                    <label>Latitude</label>
                                    <input type="text" required=true name="univLatitude" latlongtype="latitude" id="univLatitude_<?php echo $formName; ?>" validationType = "latLong" onblur="showErrorMessage(this, '<?php echo $formName; ?>');" onchange="showErrorMessage(this, '<?php echo $formName; ?>');" caption="latitude" maxlength="10" value="<?php echo $univAttributes['latitude']['attrVal']; ?>">
                                </div>
                                <div class="direction-col units">
                                    <input name="longitudeDir" value="e" id="latitude_e_<?php echo $formName; ?>" caption="direction" type="radio" onblur="showErrorMessage(this, '<?php echo $formName; ?>');" onchange="showErrorMessage(this, '<?php echo $formName; ?>');" <?php echo $longDir['e']; ?>>E
                                    <input name="longitudeDir" value="w" id="latitude_w_<?php echo $formName; ?>" caption="direction" type="radio" onblur="showErrorMessage(this, '<?php echo $formName; ?>');" onchange="showErrorMessage(this, '<?php echo $formName; ?>');" <?php echo $longDir['w']; ?>>W
                                    <div style="display: none" class="errorMsg" id="longitudeDir_<?php echo $formName; ?>_error"></div>
                                </div>
                                <div class="direction-col">
                                    <label>Longitude</label>
                                    <input type="text" required=true name="univLongitude" latlongtype="longitude" id="univLongitude_<?php echo $formName; ?>" validationType = "latLong" onblur="showErrorMessage(this, '<?php echo $formName; ?>');" onchange="showErrorMessage(this, '<?php echo $formName; ?>');" caption="longitude" maxlength="10" value="<?php echo $univAttributes['longitude']['attrVal']; ?>">
                                </div>
                                <div style="display: none" class="errorMsg latLonErr" id="univLatitude_<?php echo $formName; ?>_error"></div>
                                <div style="display: none" class="errorMsg latLonErr" id="univLongitude_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Affiliation details : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" name = "univAffiliation" type="text" id="univAffiliation_<?php echo $formName; ?>" maxlength = "100" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Affiliation" tooltip = "univ_affDetails"  validationType = "str" value="<?=($univMainData['univ_affiliation'])?>"/>
                                <div style="display: none" class="errorMsg" id="univAffiliation_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Accreditation details : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field last-in-section" name = "univAccreditation" type="text" id="univAccreditation_<?php echo $formName; ?>" maxlength = "100" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Accreditation" tooltip = "univ_accrDetails"  validationType = "str" value="<?=($univMainData['univ_accreditation'])?>"/>
                                <div style="display: none" class="errorMsg" id="univAccreditation_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div><!-- end:: cms-form-wrap -->
            </div><!-- end:: section parent(clear-width) -->

                <?php $this->load->view('listingPosting/abroad/widgets/universityStats',array('otherSectionHeadingImageClass'=>$otherSectionHeadingImageClass,'otherSectionDisplayStyle'=>$otherSectionDisplayStyle,
                    'univStatsData'=>$univAttributes, 'univCustomAttributes'=>$customAttributes)); ?>

            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$otherSectionHeadingImageClass?>"></i>Contact Details</h3>
                <div class="cms-form-wrap cms-accordion-div" style = "<?=$otherSectionDisplayStyle?>">
                    <ul>
                        <li>
                            <label>Email Address : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" name = "univContactEmail" id = "univContactEmail_<?php echo $formName; ?>" type="text" maxlength = "50" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Email Address" tooltip = "univ_contemail"  validationType = "email" value="<?=($univMainData['univ_contact_email'])?>"/>
                                <div style="display: none" class="errorMsg" id="univContactEmail_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Phone Number : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" name = "univContactPhone" type="text" id = "univContactPhone_<?php echo $formName; ?>" maxlength = "30" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Phone Number" tooltip = "univ_contphn"  validationType = "str" value="<?=($univMainData['univ_contact_main_phone'])?>"/>
                                <div style="display: none" class="errorMsg" id="univContactPhone_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Address : </label>
                            <div class="cms-fields">
                                <textarea class="cms-textarea" name = "univContactAddress" id = "univAddress_<?php echo $formName; ?>" maxlength = "100" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Address" tooltip = "univ_contaddress"  validationType = "str"><?=($univMainData['univ_address'])?></textarea>
                                <div style="display: none" class="errorMsg" id="univAddress_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Website Link : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field last-in-section" name = "univContactWebsite" id = "website_<?php echo $formName; ?>" type="text" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption ="Website Link" tooltip = "univ_contwebsite"  validationType = "link" value="<?=($univMainData['univ_contact_website'])?>"/>
                                <div style="display: none" class="errorMsg" id="website_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div><!-- end:: cms-form-wrap -->
            </div><!-- end:: section parent(clear-width) -->

            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$otherSectionHeadingImageClass?>"></i>Visual/Social Information</h3>
                <div class="cms-form-wrap cms-accordion-div" style = "<?=$otherSectionDisplayStyle?>">
                    <ul>
                        <li>
                            <label style="padding-top:<?php echo ($formName == "addUniversityForm"|| count($univMediaData['picture'])==0?"6":"12"); ?>px;">Pictures : </label>
                            <div class="cms-fields">
                                <div class="add-more-sec">
                                    <?php if($formName == "addUniversityForm" || count($univMediaData['picture'])==0) {?>
                                        <input type="file" name = "univPictures[]" id = "1_univPictures_<?php echo $formName; ?>"  onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" tooltip = "univ_pics" caption = "Image" validationType = "file"/>
                                        <input class="universal-txt-field" id = "1_univPicCaption_<?php echo $formName; ?>" name="univPictureCaption[]" maxlength = "100" placeholder="Caption" type="text" style="width:25%" validationType = "mediaCaption" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "image caption">
                                        <a href="javascript:void(0);" style= "display:none;" class="remove-link-2" onclick="removeAddedElementInUniversity(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove Picture</a>
                                        <div style="display: none" class="errorMsg imgErr" id="1_univPictures_<?php echo $formName; ?>_error"></div>
                                        <div style="display: none" class="errorMsg capErr" id="1_univPicCaption_<?php echo $formName; ?>_error"></div>

                                    <?php } else {
                                        if(count($univMediaData)>0){ ?>
                                        <div class="picture-list">
                                          <ul>
                                            <?php for($i = 0 ; $i<count($univMediaData['picture']); $i++) { ?>
                                                <li>
                                                    <img src="<?php echo MEDIAHOSTURL.$univMediaData['picture'][$i]['univ_media_thumburl']; ?>" width="143" height="106" alt="">
                                                    <input type="hidden" name = "univPicturesMediaId[]" value="<?php echo ($univMediaData['picture'][$i]['univ_media_id']); ?>"/>
                                                    <input type="hidden" name = "univPicturesMediaUrl[]" value="<?php echo ($univMediaData['picture'][$i]['univ_media_url']); ?>"/>
                                                    <input type="hidden" name = "univPicturesMediaThumbUrl[]" value="<?php echo ($univMediaData['picture'][$i]['univ_media_thumburl']); ?>"/>
                                                    <i class="abroad-cms-sprite remove-icon2 remove-univ-picture"></i>
                                                    <input class="universal-txt-field" name="univPictureCaptionSet[]" maxlength = "100" type="text" placeholder="Caption" style="width:95%" value="<?php echo $univMediaData['picture'][$i]['univ_media_name']; ?>" validationType = "mediaCaption" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "image caption">
                                                </li>
                                            <?php }//end foreach ?>
                                            </ul>
                                       </div>
                                       <div class = "clearFix"></div>
                                        <?php } //end if ?>
                                        <input type="file" name = "univPictures[]" id = "1_univPictures_<?php echo $formName; ?>"  onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" tooltip = "univ_pics" caption = "Image" validationType = "file"/>
                                        <input class="universal-txt-field" id = "1_univPicCaption_<?php echo $formName; ?>" name="univPictureCaption[]" maxlength = "100" placeholder="Caption" type="text" style="width:25%" validationType = "mediaCaption" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "image caption">
                                        <div style="display: none" class="errorMsg imgErr" id="1_univPictures_<?php echo $formName; ?>_error"></div>
                                        <div style="display: none" class="errorMsg capErr" id="1_univPicCaption_<?php echo $formName; ?>_error"></div>
                                       <div class="clearFix"></div>
                                    <?php } ?>
                                </div>
                                <a href="JavaScript:void(0);" onclick = "addAnotherImageInUniv(this,'<?php echo $formName; ?>');" >[+] Add more pictures</a>
                            </div>
                        </li>

                        <li>
                            <label>Videos Link : </label>
                            <div class="cms-fields">
                                <?php $i = 0;
                                      do{
                                          if($i==0){$style='style= "display:none;"';}
                                          else {$style='style="float:right;position:absolute;margin: 0px 10px 0px 0px !important;"';}
                                          ?>
                                <div class="video_link">
                                <div class="add-more-sec">
                                    <label style="width:55%;" class="vidlnklbl">Link <?php echo ($i+1); ?></label>
                                    <a href="javascript:void(0);" <?=$style?> class="remove-link-2 remVidLnk" onclick="removeAddedVideoInUniv(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove Video Link</a>
                                    <input class="universal-txt-field cms-text-field" name = "univVideoLink[]" id="1_univVideoLink_<?php echo $formName; ?>" type="text" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" tooltip = "univ_video" caption = "video link" validationType = "link" value="<?=(str_replace("youtube.com/v/","youtube.com/watch?v=",$univMediaData['video'][$i]['univ_media_url']))?>"/>
                                    <div style="display: none" class="errorMsg vidErr" id="<?php echo ($i+1); ?>_univVideoLink_<?php echo $formName; ?>_error"></div>
                                    <label>Caption</label>
                                    <input class="universal-txt-field cms-text-field" id = "<?php echo ($i+1); ?>_univVidCaption_<?php echo $formName; ?>" name="univVideoCaption[]" maxlength = "100" type="text" value="<?php echo $univMediaData['video'][$i]['univ_media_name']; ?>" placeholder="caption" validationType = "mediaCaption" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "video caption">
                                    <div style="display: none" class="errorMsg capErr" id="<?php echo ($i+1); ?>_univVidCaption_<?php echo $formName; ?>_error"></div>
                                </div>
                                </div>
                                <?php $i++; }while($i<count($univMediaData['video'])); ?>
                                <a href="JavaScript:void(0);" onclick = "addAnotherVideoInUniv(this,'<?php echo $formName; ?>');" >[+] Add more Videos</a>
                            </div>
                        </li>
                        <li>
                            <label>Facebook page : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field last-in-section" name = "univFBPage" id = "univFBPage_<?php echo $formName; ?>" type="text" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" tooltip = "univ_fbpage" caption = "Facebook Page Link" validationType = "link" value="<?=($univLinksData['facebook_page']['univ_link'])?>"/>
                                <div style="display: none" class="errorMsg" id="univFBPage_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div><!-- end:: cms-form-wrap -->
            </div><!-- end:: section parent(clear-width) -->

            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$otherSectionHeadingImageClass?>"></i>Admission Contact Details</h3>
                <div class="cms-form-wrap cms-accordion-div" style = "<?=$otherSectionDisplayStyle?>">
                    <ul>
                        <li>
                            <label>University website link* : </label>
                            <div class="cms-fields">
                                 <input class="universal-txt-field cms-text-field" name = "univWebLink" type="text" id="univWebLink_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" required = true caption="University website link" tooltip = "univ_website"  validationType = "link" value="<?=($univLinksData['website_link']['univ_link'])?>"/>
                                 <div style="display: none" class="errorMsg" id="univWebLink_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>State/Country/Province : </label>
                            <div class="cms-fields">
                                <select class="universal-select cms-field" name = "univState" id = "univState_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');fetchCitiesByStates(this,'<?php echo $formName; ?>');" caption = "State" tooltip = "univ_state" disabled = "disabled" required = true validationType = "select" valueToBe="<?=($univMainData['univ_state_id'])?>">
                                    <option value="">Select a State</option>
                                </select>
                                <div style="display: none" class="errorMsg" id="univState_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>City* : </label>
                            <div class="cms-fields">
                                <select class="universal-select cms-field" name = "univCity" id = "univCity_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" required = true caption = "City" tooltip = "univ_city"  validationType = "select" valueToBe="<?=($univMainData['univ_city_id'])?>">
                                    <option value="">Select a city</option>
                                </select>
                                <div style="display: none" class="errorMsg" id="univCity_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Admission office contact person : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field last-in-section" name = "univAdmissionContact" type="text" id="univAdmissionContact_<?php echo $formName; ?>" maxlength = "50" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Admission office contact person" tooltip = "univ_contperson"  validationType = "str" value="<?=($univMainData['univ_admission_contact_person'])?>"/>
                                <div style="display: none" class="errorMsg" id="univAdmissionContact_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div><!-- end:: cms-form-wrap -->
            </div><!-- end:: section parent(clear-width) -->

            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$otherSectionHeadingImageClass?>"></i>Departments</h3>
                <div class="cms-form-wrap cms-accordion-div" style = "<?=$otherSectionDisplayStyle?>">
                    <?php
                        $i=0;
                        do{ ?>
                            <div class="add-more-sec clear-width">
                            <ul>
                                <li>
                                    <label>Name of Department : </label>
                                    <div class="cms-fields">
                                        <?php if($i==0){$style='style= "display:none;"';}
                                        else {$style="";} ?>
                                            <input class="universal-txt-field cms-text-field" name = "univDeptName[]" id="1_univDeptName_<?php echo $formName; ?>" maxlength = "100" type="text" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" tooltip = "univ_deptname" caption = "Name of Department" validationType = "str" value="<?=$univDeptData[$i]['univ_dept_name']?>"/>
                                            <a href="javascript:void(0);"  class="remove-link-2" <?=$style?> onclick="removeAddedElementInUniversity(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove Department</a>
                                            <div style="display: none" class="errorMsg" id="1_univDeptName_<?php echo $formName; ?>_error"></div>
                                    </div>
                                </li>
                                <li>
                                    <label>Website of Department : </label>
                                    <div class="cms-fields">
                                        <input class="universal-txt-field cms-text-field last-in-section" name = "univDeptWebsite[]" id="1_univDeptWebsite_<?php echo $formName; ?>" type="text" onblur = "showErrorMessage(this, '<?php echo $formName; ?>',false,$j('[name=univDeptWebsite]').length);" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" tooltip = "univ_deptwebsite" caption = "Website of Department" validationType = "link" value="<?=$univDeptData[$i]['univ_dept_url']?>"/>
                                        <div style="display: none" class="errorMsg" id="1_univDeptWebsite_<?php echo $formName; ?>_error"></div>
                                    </div>
                                </li>
                            </ul>
                            </div>
                    <?php $i++; }while($i<count($univDeptData));//end do-while loop ?>
                    <a href="JavaScript:void(0);" class="add-more-link" onclick = "addAnotherDeptInUniv(this,'<?php echo $formName; ?>');" style="margin-bottom:0;">[+] Add another department</a>
                </div><!-- end:: cms-form-wrap -->
            </div><!-- end:: section parent(clear-width) -->

            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$otherSectionHeadingImageClass?>"></i>Campus Accomodation</h3>
                <div class="cms-form-wrap cms-accordion-div" style = "<?=$otherSectionDisplayStyle?>">
                    <ul>
                        <li>
                            <label>Accomodation Details : </label>
                            <div class="cms-fields">
                                <textarea class="cms-textarea tinymce-textarea" name = "univAccomodationDetail" id="univAccomodationDetail_<?php echo $formName; ?>" maxlength = "4000" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" tooltip = "univ_accomodationdesc" validationType = "html" caption = "Accomodation Details"><?=($univMainData['univ_campus_acco_details'])?></textarea>
                                <div style="display: none" class="errorMsg" id="univAccomodationDetail_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Accomodation website link : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" name = "univAccomodationLink" type="text" id = "univAccomodationLink_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption="Accomodation website link" tooltip = "univ_accomodationlink"  validationType = "link" value="<?=($univMainData['univ_campus_acco_url'])?>"/>
                                <div style="display: none" class="errorMsg" id="univAccomodationLink_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Living expenses (per year) : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" name = "univLivingExpense" type="text" id="univLivingExpense_<?php echo $formName; ?>" maxlength = "9" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Living expenses" tooltip = "univ_livexpense" validationType = "numeric" value="<?=($univMainData['univ_living_expenses'])?>"/>
                                <div style="display: none" class="errorMsg" id="univLivingExpense_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Currency : </label>
                            <div class="cms-fields">
                                <!--if above is filled onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" required = true -->
                                 <select class="universal-select cms-field" name = "univCurrency" id="univCurrency_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Currency"  validationType = "select">
                                    <option value = "">Select a Currency </option>
                                    <?php
                                        foreach($currencies as $currency)
                                        {
                                            if($univMainData['univ_currency_id'] == $currency['id']){
                                                $selected = 'selected="selected"';
                                            }
                                            else{
                                                $selected = '';
                                            }
                                            echo '<option '.$selected.' value="'.$currency['id'].'">'.$currency['currency_name'].'</option>';
                                        }
                                    ?>
                                </select>
                                <div style="display: none" class="errorMsg" id="univCurrency_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Living expenses description : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" type="text" name = "univLivingExpenseDescription" id = "univLivingExpenseDescription_<?php echo $formName; ?>" maxlength = "100" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Living expenses description" validationType = "str" tooltip="univ_livexpensedesc" value="<?=($univMainData['univ_living_expense_details'])?>"/>
                                <div style="display: none" class="errorMsg" id="univLivingExpenseDescription_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Living expenses link : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field last-in-section" name = "univLivingExpenseLink" type="text" id = "livingExpenseLink_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption="Living expenses link"  tooltip = "univ_livexpenselink" validationType = "link" value="<?=($univMainData['univ_living_expenses_url'])?>"/>
                                <div style="display: none" class="errorMsg" id="livingExpenseLink_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div><!-- end:: cms-form-wrap -->
            </div><!-- end:: section parent(clear-width) -->

            <!--Shiksha Apply div starts here-->
                <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$otherSectionHeadingImageClass?>"></i>Conditional Offer at this university</h3>
                <div class="cms-form-wrap cms-accordion-div" style = "<?=$otherSectionDisplayStyle?>">
                    <ul>
                        <li>
                            <label>Conditional Offer : </label>
                            <?php
                                $conditionalOffer = array('univconditionalOfferYes'=>'','univconditionalOfferNo'=>'','univconditionalOfferNotMentioned'=>'');
                                $conditionalOfferDescription ='';
                                $conditionalOfferLink ='';
                                if($formName == "addUniversityForm")
                                {
                                        $conditionalOffer['univconditionalOfferNotMentioned']='checked="checked"';
                                        $conditionalOfferDescription ='disabled = "disabled"';
                                        $conditionalOfferLink ='disabled = "disabled"';
                                }
                                else
                                {

                                    $offermade = $univMainData['univ_conditionalOffer'];
                                    switch ($offermade) {
                                        case 'yes':
                                            $conditionalOffer['univconditionalOfferYes']='checked="checked"';
                                            break;
                                        case 'no':
                                            $conditionalOffer['univconditionalOfferNo']='checked="checked"';
                                            $conditionalOfferDescription ='disabled = "disabled"';
                                            $conditionalOfferLink ='disabled = "disabled"';
                                            break;
                                        case 'not mentioned':
                                            $conditionalOffer['univconditionalOfferNotMentioned']='checked="checked"';
                                            $conditionalOfferDescription ='disabled = "disabled"';
                                            $conditionalOfferLink ='disabled = "disabled"';
                                            break;
                                        default :
                                            $conditionalOffer['univconditionalOfferNotMentioned']='checked="checked"';
                                            $conditionalOfferDescription ='disabled = "disabled"';
                                            $conditionalOfferLink ='disabled = "disabled"';
                                            break;
                                        }
                                }
                            ?>
                            <div class="cms-fields" style="margin-top:4px;">
                                <input type="radio" name = "conditionalOffer" id = "univconditionalOfferYes_<?php echo $formName; ?>" onclick="enableDisbaleDescAndLink(this);" caption = "conditional Offer Yes" value="yes" <?php echo $conditionalOffer['univconditionalOfferYes']; ?>/> Yes
                                <input type="radio" name = "conditionalOffer" id = "univconditionalOfferNo_<?php echo $formName; ?>"  onclick="enableDisbaleDescAndLink(this);" caption = "conditional Offer No" value="no" <?php echo $conditionalOffer['univconditionalOfferNo']; ?> /> No
                                <input type="radio" name = "conditionalOffer" id = "univconditionalOfferNotMentioned_<?php echo $formName; ?>" onclick="enableDisbaleDescAndLink(this);" caption = "conditional Offer Not Mentioned" value="not mentioned" <?php echo $conditionalOffer['univconditionalOfferNotMentioned']; ?> /> Not mentioned on the university website
                            </div>
                        </li>
                        <li>
                            <label>Description : </label>
                            <div class="cms-fields">
                                <textarea <?php echo $conditionalOfferDescription; ?>  class="cms-textarea" maxlength = "1000" name="conditionalOfferDescription" id = "conditionalOfferDescription_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '');" onchange = "showErrorMessage(this, '');" caption = "Conditional Offer Description" validationType = "str"><?=$univMainData['univ_conditionalOfferDescription']?></textarea>
                                <div style="display: none" class="errorMsg" id="conditionalOfferDescription_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Link* : </label>
                            <div class="cms-fields">
                                <input <?php echo $conditionalOfferLink;?> required="required" class="universal-txt-field cms-text-field last-in-section" name = "conditionalOfferLink" type="text" id = "conditionalOfferLink_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption=" link for Conditional Offer at this university"  validationType = "link" maxlength="250" value="<?=($univMainData['univ_conditionalOfferLink'])?>"/>
                                <div style="display: none" class="errorMsg" id="conditionalOfferLink_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div><!-- end:: cms-form-wrap -->
            </div><!-- end:: section parent(clear-width) -->
            <!--Shiksha Apply div starts here-->
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$otherSectionHeadingImageClass?>"></i>Shiksha Apply</h3>
                <div class="cms-form-wrap cms-accordion-div" style = "<?=$otherSectionDisplayStyle?>">
                    <?php //based on form name and shiksha apply data we check or uncheck the shiksha apply checkbox
                         //the university has no shiksha apply data it can fill the form in edit mode or it is an add form --- verfiy
                        if($formName == "addUniversityForm" || count($univShikshaApplyData)==0 ){
                            $checked = '';
                            $requiredShikshaApplyField = '';
                            $readOnly = "";
                            $onchangeEvent  = "showUnivShikshaApplySection();";

                        }
                        else if(count($univShikshaApplyData)!=0 && $formData['shikshaApplyMappedCourses']!=0) //the university can't be unchecked as courses are there
                        {
                            $checked = 'checked="checked"';
                            $requiredShikshaApplyField = 'required=true';
                            $readOnly ="";
                            $onclickEvent  = "alert('This university has shiksha apply enabled courses.Please disable them first');return false;";
                            $onchangeEvent  = "";
                        }
                        else{   //the university data is filled and no active consultant and no active shiksha applied course hence can be unchecked once filled already in edit mode
                            $checked = 'checked="checked"';
                            $requiredShikshaApplyField = 'required=true';
                            $onchangeEvent  = "showUnivShikshaApplySection();";
                            $readOnly = "";
                            } ?>

                    <div>
                        <ul><!-- checkbox to enable shiksha apply -->
                            <li>
                                <div class="cms-fields">
                                    <input id="shikshaApplyUnivCheckbox" <?= $readOnly;?> name="shikshaApplyUnivCheckbox" <?=$checked?> type="checkbox" onclick="<?= $onclickEvent;?>" onchange="<?= $onchangeEvent;?>"><label style="float:none;cursor:pointer;" for="shikshaApplyUnivCheckbox"> Shiksha Apply on this university</label>
                                </div>
                            </li>
                        </ul><!-- END: checkbox to enable shiksha apply -->
                    </div>
                    <?php //foreach($univShikshaApplyData as $key=>$applicationProfile){
                            $applyNum = 0;
                        do{
                    ?>
                    <div class="add-more-sec<?=($applyNum>0?'2 clear-width':'')?> shikshaApplyUniv" style="<?=(count($univShikshaApplyData)>0?$otherSectionDisplayStyle:'display:none;')?>">
                        <input type="hidden" class = "applynum" value="<?=($applyNum)?>">
                        <div style="margin-bottom:10px;" class="cms-fields applicationProfileLabel"><strong>Application profile <?=($applyNum+1)?></strong></div>
                        <ul>
                            <li>
                                <label>Name of application profile : </label>
                                <div class="cms-fields">
                                    <input name = "univApplicationProfileId[]" type="hidden" value="<?=($univShikshaApplyData[$applyNum]['applicationProfileId'])?>">
                                    <input name = "univApplicationProfileAddedAt[]" type="hidden" value="<?=($univShikshaApplyData[$applyNum]['addedOn'])?>">
                                    <input name = "univApplicationProfileAddedBy[]" type="hidden" value="<?=($univShikshaApplyData[$applyNum]['addedBy'])?>">
                                    <input class="universal-txt-field cms-text-field shikshaApplyField" name = "univApplicationProfileName[]" <?=($requiredShikshaApplyField)?> maxlength = "100" type="text" id = "<?=($applyNum+1)?>_univApplicationProfileName_<?php echo $formName; ?>"  onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');"  caption = "name of application profile" validationType = "str" value="<?=htmlspecialchars($univShikshaApplyData[$applyNum]['name'])?>">
                                    <div style="display: none" class="errorMsg" id="<?=($applyNum+1)?>_univApplicationProfileName_<?php echo $formName; ?>_error"></div>
                                </div>
                            </li>
                            <li>
                                <label>Upload application process : </label>
                                <div class="cms-fields">
                                    <?php if($formName == "addUniversityForm"){?>
                                    <input type="file" class="shikshaApplyField" name = "univApplicationProcessUpload[]" <?=($requiredShikshaApplyField)?> id = "<?=($applyNum+1)?>_univApplicationProcessUpload_<?php echo $formName; ?>" caption="application process" validationType="file">
                                    <?php } else {
                                    if($univShikshaApplyData[$applyNum]['applicationProcessUrl']!=""){?>
                                    <div class="brochure-link-box">
                                        <input name = "univApplicationProcessUploadLink[<?=$applyNum?>]" class = "universal-txt-field cms-text-field" type = "text" disabled="disabled" id = "univApplicationProcessUploadLink_<?php echo $formName; ?>" value = "<?=$univShikshaApplyData[$applyNum]['applicationProcessUrl']?>"/>
                                        <a href="javascript:void(0);" class="remove-link-2" onclick="removeUploadedApplicationProcess(this);"><i class="abroad-cms-sprite remove-icon"></i>Remove File</a>
                                    </div>
                                    <?php $style='style = "display:none;" '; }
                                    else {
                                        $style="";
                                        } ?>
                                    <input type="file" <?=$style?> class="shikshaApplyField" name = "univApplicationProcessUpload[]" id = "<?=($applyNum+1)?>_univApplicationProcessUpload_<?php echo $formName; ?>" caption="application process" validationType="file">
                               <?php } ?>
                                    <div style="display: none" class="errorMsg" id="<?=($applyNum+1)?>_univApplicationProcessUpload_<?php echo $formName; ?>_error"></div>
                                </div>
                            </li>
                            <li>
                                <div class="cms-fields">
                                    Student's checklist for applying for this course
                                    <div style="margin-top:10px">
                                        <input type="hidden" name="univSOPRequiredReal[]" value="<?=($univShikshaApplyData[$applyNum]['sopRequired']=='1'?'on':'off')?>">
                                        <input type="checkbox" name="univSOPRequired[]" <?=($univShikshaApplyData[$applyNum]['sopRequired']=='1'?'checked="checked"':'')?> id="<?=($applyNum+1)?>_univSOPRequired_<?php echo $formName; ?>" <?=($applicationProfile['sopRequired'][$applyNum])?>onchange="toggleEditorState(this);"><label style="float:none;cursor:pointer;" for="<?=($applyNum+1)?>_univSOPRequired_<?php echo $formName; ?>"> SOP</label><br>
                                    </div>
                                    <label style="float:none;">Comments (If any)</label>
                                </div>
                            </li>
                            <li>
                                <div class="requirement-field-sec cms-fields">
                                    <textarea class="cms-textarea tinymce-textarea <?=($univShikshaApplyData[$applyNum]['sopRequired']=='1'?'':$disabledTextareaClass)?>" name = "univSOPComments[]" id="<?=($applyNum+1)?>_univSOPComments_<?php echo $formName; ?>" maxlength = "1000" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "SOP comments" validationType = "html"><?=($univShikshaApplyData[$applyNum]['sopComments'])?></textarea>
                                    <div id="<?=($applyNum+1)?>_univSOPComments_<?php echo $formName; ?>_error" class="errorMsg" style="display: none;margin:top:-5px;"></div>
                                </div>
                            </li>
                            <li>
                                <div class="cms-fields">
                                    <div style="margin-top:10px">
                                        <input type="hidden" name="univLORRequiredReal[]" value="<?=($univShikshaApplyData[$applyNum]['lorRequired']=='1'?'on':'off')?>">
                                        <input type="checkbox" name="univLORRequired[]" <?=($univShikshaApplyData[$applyNum]['lorRequired']=='1'?'checked="checked"':'')?> id="<?=($applyNum+1)?>_univLORRequired_<?php echo $formName; ?>" onchange="toggleEditorState(this);"><label style="float:none;cursor:pointer;" for="<?=($applyNum+1)?>_univLORRequired_<?php echo $formName; ?>"> LOR</label><br>
                                    </div>
                                    <label style="float:none;">Comments (If any)</label>
                                </div>
                            </li>
                            <li>
                                <div class="requirement-field-sec cms-fields">
                                    <textarea class="cms-textarea tinymce-textarea <?=($univShikshaApplyData[$applyNum]['lorRequired']=='1'?'':$disabledTextareaClass)?>" name = "univLORComments[]" id="<?=($applyNum+1)?>_univLORComments_<?php echo $formName; ?>" maxlength = "1000" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "LOR comments" validationType = "html"><?=($univShikshaApplyData[$applyNum]['lorComments'])?></textarea>
                                    <div id="<?=($applyNum+1)?>_univLORComments_<?php echo $formName; ?>_error" class="errorMsg" style="display: none;margin:top:-5px;"></div>
                                </div>
                            </li>
                            <li>
                                <div class="cms-fields">
                                    <div style="margin-top:10px">
                                        <input type="hidden" name="univEssayRequiredReal[]" value="<?=($univShikshaApplyData[$applyNum]['essayRequired']=='1'?'on':'off')?>">
                                        <input type="checkbox" name="univEssayRequired[]" <?=($univShikshaApplyData[$applyNum]['essayRequired']=='1'?'checked="checked"':'')?> id="<?=($applyNum+1)?>_univEssayRequired_<?php echo $formName; ?>" onchange="toggleEditorState(this);"><label style="float:none;cursor:pointer;" for="<?=($applyNum+1)?>_univEssayRequired_<?php echo $formName; ?>"> Essays</label><br>
                                    </div>
                                    <label style="float:none;">Comments (If any)</label>
                                </div>
                            </li>
                             <li>
                                <div class="requirement-field-sec cms-fields">
                                    <textarea class="cms-textarea tinymce-textarea <?=($univShikshaApplyData[$applyNum]['essayRequired']=='1'?'':$disabledTextareaClass)?>" name = "univEssayComments[]" id="<?=($applyNum+1)?>_univEssayComments_<?php echo $formName; ?>" maxlength = "1000" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "essay comments" validationType = "html"><?=($univShikshaApplyData[$applyNum]['essayComments'])?></textarea>
                                    <div id="<?=($applyNum+1)?>_univEssayComments_<?php echo $formName; ?>_error" class="errorMsg" style="display: none;margin:top:-5px;"></div>
                                </div>
                            </li>
                             <li>
                                <div class="cms-fields">
                                    <div style="margin-top:10px">
                                        <input type="hidden" name="univCVRequiredReal[]" value="<?=($univShikshaApplyData[$applyNum]['cvRequired']=='1'?'on':'off')?>">
                                        <input type="checkbox" name="univCVRequired[]" <?=($univShikshaApplyData[$applyNum]['cvRequired']=='1'?'checked="checked"':'')?> id="<?=($applyNum+1)?>_univCVRequired_<?php echo $formName; ?>" onchange="toggleEditorState(this);"><label style="float:none;cursor:pointer;" for="<?=($applyNum+1)?>_univCVRequired_<?php echo $formName; ?>"> CV</label><br>
                                    </div>
                                    <label style="float:none;">Comments (If any)</label>
                                </div>
                            </li>
                            <li>
                                <div class="requirement-field-sec cms-fields">
                                    <textarea class="cms-textarea tinymce-textarea <?=($univShikshaApplyData[$applyNum]['cvRequired']=='1'?'':$disabledTextareaClass)?>" name = "univCVComments[]" id="<?=($applyNum+1)?>_univCVComments_<?php echo $formName; ?>" maxlength = "1000" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "CV comments" validationType = "html"><?=($univShikshaApplyData[$applyNum]['cvComments'])?></textarea>
                                    <div id="<?=($applyNum+1)?>_univCVComments_<?php echo $formName; ?>_error" class="errorMsg" style="display: none;margin:top:-5px;"></div>
                                </div>
                            </li>
                            <li>
                                <div class="cms-fields">
                                    <div style="margin-top:10px">
                                        <label style="float:none;">All documents required</label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="requirement-field-sec cms-fields">
                                    <textarea class="cms-textarea tinymce-textarea shikshaApplyField alwaysEnabled" <?=($requiredShikshaApplyField)?> name = "univAllDocuments[]" id="<?=($applyNum+1)?>_univAllDocuments_<?php echo $formName; ?>" maxlength = "1000" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "all documents required" validationType = "html"><?=($univShikshaApplyData[$applyNum]['allDocuments'])?></textarea>
                                    <div id="<?=($applyNum+1)?>_univAllDocuments_<?php echo $formName; ?>_error" class="errorMsg" style="display: none;margin:top:-5px;"></div>
                                </div>
                            </li>
                            <li>
                            	<label>Admission type:</label>
                                <div class="cms-fields">
                                    <select class="universal-select cms-field shikshaApplyField" <?=($requiredShikshaApplyField)?> id = "<?=($applyNum+1)?>_univAdmissionType_<?php echo $formName; ?>" name = "univAdmissionType[]"  onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption="admission type" validationType = "select">
                                    	<option value="">Select admission type</option>
                                    	<option value="rollingAdmission" <?=($univShikshaApplyData[$applyNum]['admissionType']=='rollingAdmission'?'selected="selected"':'')?>>Rolling Admission</option>
                                    	<option value="regularDeadline" <?=($univShikshaApplyData[$applyNum]['admissionType']=='regularDeadline'?'selected="selected"':'')?>>Regular Deadline</option>
                                    </select>
                                    <div id="<?=($applyNum+1)?>_univAdmissionType_<?php echo $formName; ?>_error" class="errorMsg" style="display: none;margin:top:-5px;"></div>
                                </div>
                            </li>
                            <li>
                            	<label>Last date for application submission:</label>
                                <div class="cms-fields">
                                    <?php $lastDateNum = 0;
                                        do {
                                            $lastDate = date_format(date_create_from_format('Y-m-d',$univShikshaApplyData[$applyNum]['submissionDates'][$lastDateNum]['applicationSubmissionLastDate']),'d/m/Y');
                                         ?>
                                    <div class="addMoreDates" style="margin-top:5px;">
                                        <input type="hidden" class = "lastdatenum" value="<?=($lastDateNum)?>">
                                        <input type="text" class="universal-txt-field cms-text-field flLt" id="<?=($lastDateNum+1)?>_<?=($applyNum+1)?>_univApplicationSubmissionName_<?php echo $formName; ?>" name = "univApplicationSubmissionName[<?=$applyNum?>][]" caption="application submission name" placeholder="Application submission name" minlength="0" maxlength="100" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" validationType = "str" value="<?=(htmlspecialchars($univShikshaApplyData[$applyNum]['submissionDates'][$lastDateNum]['applicationSubmissionName']))?>">
                                        <a href="Javascript:void(0);" style="<?=($lastDateNum>0?'':'display:none;')?>text-decoration:none;clear:none;" class="remove-link-2 flRt" onclick="removeShikshaApplySubmissionDate(this);">Remove <i class="abroad-cms-sprite remove-icon" style="margin-top:2px;"></i>&nbsp;</a>
                                        <div class="clearFix"></div>
                                        <div id="<?=($lastDateNum+1)?>_<?=($applyNum+1)?>_univApplicationSubmissionName_<?php echo $formName; ?>_error" class="errorMsg nameErrMsg" style="display: none;margin:top:-5px;"></div>
                                        <div class="bgColr">
                                            <div class="deadLines">
                                                <label>Intake Season</label>
                                                <select class="universal-select cms-field" id="<?=($lastDateNum+1)?>_<?=($applyNum+1)?>_univApplicationIntakeSeason_<?php echo $formName; ?>" name = "univApplicationIntakeSeason[<?=$applyNum?>][]">
                                                    <option value="">Select season</option>
                                                    <?php foreach($applyIntakeFields['intakeSeasons'] as $season){ ?>
                                                    <option value="<?php echo $season; ?>" <?php echo ($univShikshaApplyData[$applyNum]['submissionDates'][$lastDateNum]['intakeSeason'] == $season?'selected="selected"':''); ?> ><?php echo ucfirst($season); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="deadLines">
                                                <label>Intake Month</label>
                                                <select class="universal-select cms-field" id="<?=($lastDateNum+1)?>_<?=($applyNum+1)?>_univApplicationIntakeMonth_<?php echo $formName; ?>" name = "univApplicationIntakeMonth[<?=$applyNum?>][]">
                                                    <option value="">Select Month</option>
                                                    <?php foreach($applyIntakeFields['intakeMonths'] as $key => $month){ ?>
                                                    <option value="<?php echo $key; ?>" <?php echo ($univShikshaApplyData[$applyNum]['submissionDates'][$lastDateNum]['intakeMonth'] == $key?'selected="selected"':''); ?> ><?php echo ucfirst($month); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="deadLines">
                                                <label>Intake Year</label>
                                                <select class="universal-select cms-field" id="<?=($lastDateNum+1)?>_<?=($applyNum+1)?>_univApplicationIntakeYear_<?php echo $formName; ?>" name = "univApplicationIntakeYear[<?=$applyNum?>][]">
                                                    <option value="">Select Year</option>
                                                    <?php foreach($applyIntakeFields['intakeYears'] as $key => $year){ ?>
                                                    <option value="<?php echo $year; ?>" <?php echo ($univShikshaApplyData[$applyNum]['submissionDates'][$lastDateNum]['intakeYear'] == $year?'selected="selected"':''); ?> ><?php echo $year; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="deadLines">
                                                <label>Intake Round</label>
                                                <select class="universal-select cms-field" id="<?=($lastDateNum+1)?>_<?=($applyNum+1)?>_univApplicationIntakeRound_<?php echo $formName; ?>" name = "univApplicationIntakeRound[<?=$applyNum?>][]">
                                                    <option value="">Select Round</option>
                                                    <?php foreach($applyIntakeFields['intakeRounds'] as $key => $round){ ?>
                                                    <option value="<?php echo $key; ?>" <?php echo ($univShikshaApplyData[$applyNum]['submissionDates'][$lastDateNum]['intakeRound'] == $key?'selected="selected"':''); ?> ><?php echo ucfirst($round); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="deadLines" style="width: 35%;height: 44px;">
                                                <label>Deadline</label>
                                                <div class="flLt">
                                                    <input id="<?=($lastDateNum+1)?>_<?=($applyNum+1)?>_lastdate_<?php echo $formName; ?>" class="universal-txt-field cms-text-field" type="text" style="width:150px !important;margin:0px 5px;" readonly="" placeholder="DD/MM/YYYY" validationtype="str" caption="date" onchange="showErrorMessage(this, '<?php echo $formName; ?>');" onblur="showErrorMessage(this, '<?php echo $formName; ?>');" maxlength="10" name="lastdate[<?=$applyNum?>][]" value="<?=($lastDate)?>">
                                                    <i id="<?=($lastDateNum+1)?>_<?=($applyNum+1)?>_lastdate_<?php echo $formName; ?>_img" class="abroad-cms-sprite calendar-icon" onclick="pickLastSubmissionDate(this);" name="lastdate_<?php echo $formName; ?>_img[<?=$applyNum?>][]"></i>
                                                    <div id="<?=($lastDateNum+1)?>_<?=($applyNum+1)?>_lastdate_<?php echo $formName; ?>_error" class="errorMsg dateErrMsg" style="display: none;margin:top:-5px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        $lastDateNum++;
                                        } while($lastDateNum<count($univShikshaApplyData[$applyNum]['submissionDates']));?>
                                    <a href="Javascript:void(0);" onclick = "addMoreSubmissionDates(this);">[+] Add another date</a>
                                </div>
                            </li>
                             <li>
                            	<label>Application FAQ Link:</label>
                                <div class="cms-fields">
                                    <input type="text" class="universal-txt-field cms-text-field flLt"  name = "univApplicationFAQLink[]" id = "<?=($applyNum+1)?>_univApplicationFAQLink_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "application faq link" validationType = "link" value="<?=($univShikshaApplyData[$applyNum]['applicationFaqLink'])?>"/>
                                    <div id="<?=($applyNum+1)?>_univApplicationFAQLink_<?php echo $formName; ?>_error" class="errorMsg" style="display: none;"></div>
                                </div>
                            </li>
                            <li>
                            	<label>Apply Now Link:</label>
                                <div class="cms-fields">
                                    <input type="text" class="universal-txt-field cms-text-field flLt shikshaApplyField last-in-section" <?=($requiredShikshaApplyField)?> name = "univApplyNowLink[]" id = "<?=($applyNum+1)?>_univApplyNowLink_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "apply now link" validationType = "link" value="<?=($univShikshaApplyData[$applyNum]['applyNowLink'])?>"/>
                                    <div class="clearFix"></div><div id="<?=($applyNum+1)?>_univApplyNowLink_<?php echo $formName; ?>_error" class="errorMsg" style="display: none;"></div>
                                </div>
                            </li>
                        </ul>
                        <a href="Javascript:void(0);" style="<?=($applyNum>0?'':'display:none;')?>" class="remove-link" onclick="removeUnivApplicationProfile(this);"><i class="abroad-cms-sprite remove-icon"></i>Remove application profile <?=($applyNum+1)?></a>
                    </div><!-- END: div.add-more-sec -->
                    <?php
                            $applyNum++;
                        } while($applyNum<count($univShikshaApplyData));
                    ?>
                    <div style="margin-bottom:15px;<?=(count($univShikshaApplyData)>0?$otherSectionDisplayStyle:'display:none;')?>" id="addMoreApplicationProfile" class="cms-fields">
                        <a href="Javascript:void(0);" onclick="addMoreApplicationProfile();">[+] Add another application profile</a>
                    </div>
                </div><!--END: div.cms-accordion-div -->
            </div><!--END: div.clear-width -->

            <!--shiksha apply div ends here-->
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$otherSectionHeadingImageClass?>"></i>Additional information</h3>
                <div class="cms-form-wrap cms-accordion-div" style = "<?=$otherSectionDisplayStyle?>">
                    <ul>
                        <li>
                            <label>University brochure link : </br><p style="position: relative; left: -7px; font-size: 9px;">(Max upload size 50 MB)</p></label>
                            <div class="cms-fields">
                               <?php if($formName == "addUniversityForm"){?>
                                <input name = "univBrochureLink[]" type="file" id = "univBrochureLink_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');"  caption = "Brochure Link" tooltip = "univ_brochurelink" validationType = "file"/>
                               <?php } else {
                                    if($univMainData['univ_brochure_link']!=""){?>
                                    <div class="brochure-link-box">
                                        <input name = "univBrochureSavedLink" class = "universal-txt-field cms-text-field" type = "text" disabled="disabled" id = "univBrochureSavedLink_<?php echo $formName; ?>" value = "<?= MEDIAHOSTURL.$univMainData['univ_brochure_link']?>"/>
                                        <a href="javascript:void(0);" class="remove-link-2" onclick="removeBrochureInUniversity(this);"><i class="abroad-cms-sprite remove-icon"></i>Remove Brochure</a>
                                    </div>
                                    <?php $style='style = "display:none;" '; }
                                    else {
                                        $style="";
                                        } ?>
                               <input name = "univBrochureLink[]" type="file" <?=$style?> id = "univBrochureLink_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');"  caption = "Brochure Link" tooltip = "univ_brochurelink" validationType = "file"/>
                               <?php } ?>
                               <div style="display: none" class="errorMsg" id="univBrochureLink_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <?php $style= "";
                            $i=0;
                            do {
                                $style =($i==0? 'style="display:none;"':'');
                                $srNum=$i+1; ?>
                        <li class="scoreReport">
                            <label>Exam name for Score Reporting :</label>
                            <div class="cms-fields">
                                <select class="universal-select cms-field" name="scoreReportingExam[]" id="<?php echo $srNum; ?>_scoreReportingExam_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "exam name for score reporting" validationType = "select">
                                    <option value=''>Select Exam</option>
                                    <?php foreach($abroadExamsMasterList as $exam){?>
                                    <option value="<?php echo $exam['examId']; ?>" <?php echo ($scoreReporting[$i]['scoreReportingExam'] ==$exam['examId']?'selected="selected"':''); ?>><?php echo $exam['exam']; ?></option>
                                    <?php } ?>
                                </select>
                                <a href="javascript:void(0);" <?php echo $style; ?> class="remove-link-2" onclick="removeScoreReporting(this);"><i class="abroad-cms-sprite remove-icon"></i>Remove score reporting</a>
                                <div style="display: none" class="errorMsg" id="<?php echo $srNum; ?>_scoreReportingExam_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li class="scoreReport">
                            <label>Codes for Score Reporting :</label>
                            <div class="cms-fields">
                                <textarea class="cms-textarea tinymce-textarea" name = "scoreReportingCode[]" id="<?php echo $srNum; ?>_scoreReportingCode_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Codes for score reporting" validationType = "html" maxlength="5000"><?php echo $scoreReporting[$i]['scoreReportingCode']; ?></textarea>
                                <div style="display: none" class="errorMsg" id="<?php echo $srNum; ?>_scoreReportingCode_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <?php $i++;}while($i<count($scoreReporting)); ?>
                        <li>
                            <div class="cms-fields">
                                <input type="hidden" id="scoreReportingCount" value="<?php echo (count($scoreReporting)>0?count($scoreReporting):1); ?>">
                                <a href="Javascript:void(0);" onclick="addMoreScoreReporting(this,'<?php echo $formName; ?>');">[+] Add more score reporting</a>
                            </div>
                        </li>
                        <li style="margin-bottom:0;">
                            <?php
                                $i=0;
                                do{ ?>
                                    <div class="cms-form-wrap" style="margin:0;">
                                    <ol style="padding:0;">
                                        <li>
                                            <label>Name of campus : </label>
                                            <div class="cms-fields">
                                                <?php
                                                    if($i==0){$style='style= "display:none;"';}
                                                    else {$style="";}
                                                ?>
                                                <input class="universal-txt-field cms-text-field" name = "univCampusName[]" type="text" maxlength = "100" id = "1_univCampusName_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');"  caption = "Campus Name" tooltip = "univ_campusname"  validationType = "str" value="<?=$univCampusData[$i]['univ_campus_name']?>"/>
                                                <a href="javascript:void(0);" <?=$style?> class="remove-link-2" onclick="removeAddedElementInUniversity(this,1)"><i class="abroad-cms-sprite remove-icon"></i>Remove Campus</a>
                                                <div style="display: none" class="errorMsg" id="1_univCampusName_<?php echo $formName; ?>_error"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <label>Website of each campus : </label>
                                            <div class="cms-fields">
                                                <input class="universal-txt-field cms-text-field" name = "univCampusWebsite[]" type="text" id = "1_univCampusWebsite_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Campus Website" tooltip = "univ_campuslink"  validationType = "link" value="<?=$univCampusData[$i]['univ_campus_url']?>"/>
                                                <div style="display: none" class="errorMsg" id="1_univCampusWebsite_<?php echo $formName; ?>_error"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <label>Campus address : </label>
                                            <div class="cms-fields">
                                                <textarea class="cms-textarea" name = "univCampusAddress[]" id = "1_univCampusAddress_<?php echo $formName; ?>" maxlength="200" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Campus Address" tooltip = "univ_campusaddress"  validationType = "str" ><?=$univCampusData[$i]['univ_campus_addr']?></textarea>
                                                <div style="display: none" class="errorMsg" id="1_univCampusAddress_<?php echo $formName; ?>_error"></div>
                                            </div>
                                        </li>
                                    </ol>
                                    </div>
                            <?php $i++; }while($i<count($univCampusData));//end do-while loop ?>
                        </li>
                        <a href="JavaScript:void(0);" class="add-more-link"  onclick = "addAnotherCampusInUniv(this,'<?php echo $formName; ?>');" >[+] Add another campus address</a>
                        <li>
                            <label>Indian consultants page link : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" name = "univIndianConsultant" type="text" id = "univIndianConsultant_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Indian consultants page link" tooltip = "univ_indianconslink"  validationType = "link" value="<?=($univLinksData['india_consultants_page_link']['univ_link'])?>"/>
                                <div style="display: none" class="errorMsg" id="univIndianConsultant_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>International Students Page link : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field last-in-section" name = "univInternationalStudentsLink" type="text" id = "univInternationalStudents_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "International Students Page link" tooltip = "univ_abroadstudentpage" validationType = "link" value="<?=($univLinksData['international_students_page_link']['univ_link'])?>"/>
                                <div style="display: none" class="errorMsg" id="univInternationalStudents_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Indian student association : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field last-in-section" name = "univAsianStudentsLink" type="text" id = "univAsianStudentsLink_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Indian/Asian Students Page link" validationType = "link" value="<?=($univLinksData['asian_students_page_link']['univ_link'])?>"/>
                                <div style="display: none" class="errorMsg" id="univAsianStudentsLink_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div><!-- end:: cms-form-wrap -->
            </div><!-- end:: section parent(clear-width) -->

            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$otherSectionHeadingImageClass?>"></i>Announcement (For Sales)</h3>
                <div class="cms-form-wrap cms-accordion-div" style = "<?=$otherSectionDisplayStyle?>">
                    <div class="cms-fields" style="margin-bottom:5px;">
                        <input type="checkbox" <?=$checkAnnouncement?> name="addAnnouncement" id="addAnnouncement" onchange="showHideAnnouncement();">Add announcment
                    </div>
                    <ul id="announcementDetails" <?=$displayAnnouncement?> >
                        <li>
                            <label>Announcement text* : </label>
                            <div class="cms-fields">
                                <textarea style="width:98%;" class="cms-textarea" name = "announcementText" id = "announcementText" maxlength="140" <?=$required?> onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Announcement text" validationType = "str" ><?=$univMainData['announcement_text']?></textarea>
                                <div style="display: none" class="errorMsg" id="announcementText_error"></div>
                            </div>
                        </li>

                        <li>
                            <label>Call to action text* : </label>
                            <div class="cms-fields">
                                <textarea class="cms-textarea tinymce-textarea" name = "announcementCallToAction" id="announcementCallToAction" <?=$required?> validationType = "html" caption = "Call to action text" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');"><?=$univMainData['announcement_call_to_action_text']?></textarea>
                                <div style="display: none" class="errorMsg" id="announcementCallToAction_error"></div>
                            </div>
                        </li>

                        <li>
                            <label>Start date* : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" type="text" name="startDateDisplay" id="startDateDisplay" caption="Start date" <?=$required?> validationType = "html" readonly value="<?=$univMainData['announcement_start_date']?>" />
                                <i class="abroad-cms-sprite calendar-icon" name="startCal" id="startCal" style="cursor:pointer" onclick="announcementPickStartDate();"></i>
                                <div style="display: none" class="errorMsg" id="startDateDisplay_error"></div>
                            </div>
                        </li>

                        <li>
                            <label>End date* : </label>
                            <div class="cms-fields">
                                <input class="universal-txt-field cms-text-field" type="text" name="endDateDisplay" id="endDateDisplay" caption="End date" <?=$required?> validationType = "html" readonly value="<?=$univMainData['announcement_end_date']?>" />
                                <i class="abroad-cms-sprite calendar-icon" name="endCal" id="endCal" style="cursor:pointer" onclick = "announcementPickEndDate();"></i>
                                <div style="display: none" class="errorMsg" id="endDateDisplay_error"></div>
                                <div style="display: none" class="errorMsg" id="date_error"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="clear-width">
                    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$otherSectionHeadingImageClass?>"></i>SEO Details</h3>
                    <div class="cms-form-wrap cms-accordion-div" style = "<?=$otherSectionDisplayStyle?>">
                            <ul>
                                    <li>
                                        <label>SEO Title : </label>
                                        <div class="cms-fields">
                                                <input class="universal-txt-field cms-text-field" maxlength = "500" name = "univSeoTitle" type="text" id = "univSeoTitle_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Seo Title" validationType = "str"  value="<?=$univMainData['univ_seo_title']?>"/>
                                        </div>
                                    </li>
                                    <li>
                                        <label>SEO Keywords : </label>
                                        <div class="cms-fields">
                                                <textarea class="cms-textarea" maxlength = "500" name="univSeoKeywords" id = "univSeoKeywords_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Seo Keywords" validationType = "str"><?=$univMainData['univ_seo_keywords']?></textarea>
                                        </div>
                                    </li>
                                    <li>
                                        <label>SEO Description : </label>
                                        <div class="cms-fields">
                                                <textarea class="cms-textarea" maxlength = "500" name="univSeoDescription" id = "univSeoDescription_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Seo Description" validationType = "str"><?=$univMainData['univ_seo_description']?></textarea>
                                        </div>
                                    </li>
                            </ul>
                    </div>
            </div>
            <div class="clear-width">
                <div class="cms-form-wrap" style="margin:0 0 10px 0; padding-top:8px; border-top:1px solid #ccc;">
                    <ul>
                        <li>
                            <label>User Comments*: </label>
                            <div class="cms-fields">
                               <textarea class="cms-textarea" name = "univUserComments" style="width:75%;" id = "univUserComments_<?php echo $formName; ?>" maxlength="256" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" required = true caption = "Comments" tooltip="univ_comments" validationType = "str"></textarea>
                               <div style="display: none" class="errorMsg" id="univUserComments_<?php echo $formName; ?>_error"></div>
                               <div style="display: none" class="errorMsg" id="univOverAll_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div><!-- end:: cms-form-wrap -->
            </div><!-- end:: section parent(clear-width) -->

        </div><!-- end:: cms-form-wrapper -->
        <input type = "hidden" id = "oldSubmitDate" name = "oldSubmitDate" value = "<?=($univMainData['univ_submit_date'])?>" />
        <input type = "hidden" id = "univSeoUrl" name = "univSeoUrl" value = "<?=($univMainData['univ_seo_url'])?>" />
        <input type = "hidden" id = "oldUnivId" name = "oldUnivId" value = "<?=($univMainData['univ_id'])?>" />
        <input type = "hidden" id = "oldUnivLocationId" name = "oldUnivLocationId" value = "<?=($univMainData['univ_location_id'])?>" />
        <input type = "hidden" id = "oldUnivSaveMode" name = "oldUnivSaveMode" value = "<?=($univMainData['univ_status'])?>" />
        <input type = "hidden" id = "univSaveMode" name = "univSaveMode" value="" />
        <input type = "hidden" id = "univActionType" name = "univActionType" value="<?php echo $formName; ?>" />
        <div class="button-wrap">
                <a href="JavaScript:void(0);" onclick = "submitUniversityFormData('draft','<?php echo $formName; ?>');" class="gray-btn">Save as Draft</a>
                <?php if($previewLinkFlag){?><a target="_blank" href="<?=SHIKSHA_STUDYABROAD_HOME.$formData['listing_data']['0']['univ_seo_url']?>" class="gray-btn">Preview</a><?php }?>
                <a href="JavaScript:void(0);" onclick = "submitUniversityFormData('<?=ENT_SA_PRE_LIVE_STATUS?>','<?php echo $formName; ?>');" class="orange-btn">Save & Publish</a>
                <a href="JavaScript:void(0);" onclick = "confirmRedirection();" class="cancel-btn">Cancel</a>
        </div><!-- end:: button-wrap -->

        <div class="clearFix"></div>
    </form>
</div><!-- abroad-cms-rt-box -->

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

        if (typeof respData != 'undefined' &&typeof respData.Fail != 'undefined') {
            preventOnUnload = true;
            //var respData = JSON.parse(response);
            //alert("All submitted"+response);
            $j("#univOverAll_<?php echo $formName; ?>_error").html("Please scroll up & correct the fields shown with error message.").show();
            //console.log(respData.Fail);
            for (var prop in respData.Fail) {
                switch (prop) {
                    case "logo" :
                        $j("#univLogo_<?php echo $formName; ?>_error").html(respData.Fail[prop]).show();
                        break;
                    case "photo":
                        if (respData.Fail[prop] == "Only Images of type jpeg,gif,png are allowed") {
                            var photoErrorMsg = respData.Fail[prop];
                        } else if(respData.Fail[prop] instanceof Array)
                        {
                              for(var indexError in respData.Fail[prop])
                                {       var photoIndex = parseInt(indexError)+1;
                                      if(respData.Fail[prop][indexError] != "no error"){
                                		 $j("#"+photoIndex+"_univPictures_<?php echo $formName; ?>_error").html(respData.Fail[prop][indexError]).show();

                                      }
                                }

                        }
                        else{
                            var photoErrorMsg = respData.Fail[prop]+" in one of the images";
                        }
                        $j("#"+($j("[name='univPictures[]']").length)+"_univPictures_<?php echo $formName; ?>_error").html(photoErrorMsg).show();
                        break;
                    case "univApplicationProcessUpload":
                        for(var indexError in respData.Fail[prop])
                        {
                            //console.log(respData.Fail[prop][indexError]);
                            var profileIndex = parseInt(indexError)+1;
                            if(respData.Fail[prop][indexError] != ""){
                                $j("#"+profileIndex+"_univApplicationProcessUpload_<?php echo $formName; ?>_error").html(respData.Fail[prop][indexError]).show();
                            }
                        }
                        break;
                    case "univBrochureLink":
                        $j("#univBrochureLink_<?php echo $formName; ?>_error").html(respData.Fail[prop]).show();
                        break;
                    case "date":
                        $j("#date_error").html(respData.Fail[prop]).show();
                        break;
                    case "callToActionText":
                        $j("#announcementCallToAction_error").html(respData.Fail[prop]).show();
                        break;
                }
            }
        }
        else{
        alert("University has been saved successfully.");
        //window.onbeforeunload = null;
        preventOnUnload = true;
        window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_UNIVERSITY?>";
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
    function confirmRedirection()
    {   var choice = confirm("Are you sure you want to cancel? All data changes will be lost.");
        if (choice) {
            preventOnUnload = true;
            //window.onbeforeunload = null;
            window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_UNIVERSITY?>";
        }
        else{
            preventOnUnload = true;
        }
    }
</script>
