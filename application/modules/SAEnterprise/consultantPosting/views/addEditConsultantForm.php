<?php
//    _p($consultantData);
//    die;
?>
    <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
    <div class="abroad-cms-rt-box">
        
        <?php
            $displayData['breadCrumb']  =   array(
                                                array('text' => 'All Consultants','url' => ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE),
                                                array('text' => (($formName == ENT_SA_FORM_ADD_CONSULTANT)?'Add New ':'Edit ').'Consultant','url' => '')
                                                    );
            $displayData['pageTitle'] = ($formName == ENT_SA_FORM_ADD_CONSULTANT)?'Add New Consultant':'Edit Consultant';
            if($formName == ENT_SA_FORM_EDIT_CONSULTANT){
            $displayData["lastUpdatedInfo"] = array("title"    => "Last modified",
                                                        "date"     => date("d-m-Y",  strtotime($consultantData['cosultantGeneralInfo']['modifiedAt'])),
                                                        "username" => $consultantData['cosultantGeneralInfo']['modifiedByName']);
            $displayData["pageTitle"] .= "<label style='color:red;'>".($consultantData['cosultantGeneralInfo']['status']=="draft"?" (Draft Version)":" (Published Version)")."</label>";
            }
            
            
            $dropDownEstdYearConsultant = array('Select Year' => '')    + array_combine(range(date('Y'), 1950),range(date('Y'), 1950));
            
            $dropDownNumberOfEmployee = array('Select Number of Employees' => '') + array('1-10'=>'1-10','10-20'=>'10-20','20-50'=>'20-50','50-100'=>'50-100','100-200'=>'100-200','200-500'=>'200-500','500 and above'=>'500 and above');
            
            $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
        ?>
        <form id="form_<?=$formName?>" name="<?=$formName?>" action="<?=ENT_SA_CMS_CONSULTANT_PATH.'saveConsultantFormData'?>" method="POST" enctype="multipart/form-data">    
            <div class="cms-form-wrapper clear-width">
                <div class="clear-width">
                    <h3 style="cursor:pointer;" class="section-title"><i class="abroad-cms-sprite minus-icon"></i>Consultant info</h3>
                    <div style="margin-bottom:0;" class="cms-form-wrap">
                        <ul>
                            <li>
                                <label>Consultant Name* : </label>
                                <div class="cms-fields">
                                    <input type="text" class="universal-txt-field cms-text-field" name="consultantName" id="consultantName_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" required="true" maxlength="100" validationType="str" caption="Consultant Name" tooltip = "cons_name" value="<?=htmlspecialchars($consultantData['cosultantGeneralInfo']['name'])?>">
                                    <div style="display: none" class="errorMsg" id="consultantName_<?=$formName?>_error"></div>
                                </div>
                             </li>
                             <li>
                                <label>Consultant Description* : </label>
                                <div class="cms-fields">
                                    <textarea class="cms-textarea tinymce-textarea" name="consultantDescription" id="consultantDescription_<?=$formName?>" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');" required="true" maxlength="5000" caption="Consultant Description" tooltip="cons_desc" validationType="html"><?=$consultantData['cosultantGeneralInfo']['description']?></textarea>
                                    <div style="display: none" class="errorMsg" id="consultantDescription_<?=$formName?>_error"></div>
                                </div>
                             </li>
                             <li>
                                <label>Consultant logo* : </label>
                                <div class="cms-fields">
                                    <?php
                                        if($formName == ENT_SA_FORM_ADD_CONSULTANT)
                                        {
                                    ?>
                                        <input type="file" name="consultantLogo[]" id="consultantLogo_<?=$formName?>" onblur="showErrorMessage(this,<?=$formName?>);" onchange="showErrorMessage(this,'<?=$formName?>');" required="true" caption="Consultant Logo" tooltip="cons_logo" validationType="file">
                                        <div style="display: none" class="errorMsg" id="consultantLogo_<?=$formName?>_error"></div>
                                    <?php
                                        }
                                        else
                                        {
                                            if($consultantData['cosultantGeneralInfo']['logo'] == "")
                                            {
                                                $styleForLogoInput = '';
                                                $styleForLogoImageBox = 'style="display: none;"';
                                                $requiredForLogoInput = 'required=true';
                                            }
                                            else
                                            {
                                                $styleForLogoInput = 'style="display: none;"';
                                                $styleForLogoImageBox = '';
                                                $requiredForLogoInput = '';
                                            }
                                    ?>
                                        <input type="file" id="consultantLogo_<?=$formName?>" name="consultantLogo[]" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" <?=$requiredForLogoInput?> caption="Consultant Logo" tooltip="cons_logo" <?=$styleForLogoInput?> validationType="file"/>
                                        <div style="display: none" class="errorMsg" id="consultantLogo_<?=$formName?>_error"></div>
                                        
                                        <div class="image-box" <?=$styleForLogoImageBox?>>
                                            <img src="<?=$consultantData['cosultantGeneralInfo']['logo']?>" width="116" height="117" alt="logo"><i class="abroad-cms-sprite remove-icon2 remove-consultant-logo"></i>
                                            <input type="hidden" id="consultantLogoMediaUrl" name="consultantLogoMediaUrl" value="<?=$consultantData['cosultantGeneralInfo']['logo']?>"/>
                                        </div>
                                    <?php
                                        }
                                    ?>
                                    
                                </div>
                             </li>
                             <li>
                                <label>Year of Estd.* : </label>
                                <div class="cms-fields">
                                    <select name="consultantEstablishmentYear" id="consultantEstablishmentYear_<?=$formName?>" class="universal-select cms-field" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');" required="true" caption="Establishment Year" tooltip="cons_yoe" validationType="select">
                                        <?php
                                            foreach($dropDownEstdYearConsultant as $key=>$value){
                                        ?>      
                                        <option <?=($value==$consultantData['cosultantGeneralInfo']['establishmentYear'])?'selected="selected"':''?> value="<?=$value?>"><?=$key?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                    <div style="display: none" class="errorMsg" id="consultantEstablishmentYear_<?=$formName?>_error"></div>
                                </div>
                             </li>
                             <li>
                                <label>Facebook Page Links : </label>
                                <div class="cms-fields">
                                    <input type="text" class="universal-txt-field cms-text-field" name="facebookLink" id="fb_page_url_<?=$formName?>" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');" caption="Facebook Link" tooltip="cons_fb" maxlength="500" validationType="link" value="<?=htmlspecialchars($consultantData['cosultantGeneralInfo']['facebookLink'])?>">
                                    <div style="display: none" class="errorMsg" id="fb_page_url_<?=$formName?>_error"></div>
                                </div>
                             </li>
                             <li>
                                <label>Linkedin Page Links : </label>
                                <div class="cms-fields">
                                    <input type="text" class="universal-txt-field cms-text-field" name="linkedinLink" id="linkedin_page_url_<?=$formName?>" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');" caption="Linkedin Link" tooltip="cons_linkedin" maxlength="500" validationType="link" value="<?=htmlspecialchars($consultantData['cosultantGeneralInfo']['linkedInLink'])?>">
                                    <div style="display: none" class="errorMsg" id="linkedin_page_url_<?=$formName?>_error"></div>
                                </div>
                             </li>
                             <li>
                                <label>Consultant Photos*:</label>
                                <div class="cms-fields">
                                    <div class="add-more-sec">
                                        <?php
                                            if($formName == ENT_SA_FORM_ADD_CONSULTANT || count($consultantData['consultantPictureData']) == 0)
                                            {
                                        ?>
                                                <input type="file" name="consultantPhotos[]" id="1_consultantPhotos_<?=$formName?>" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');" required="true" caption="Consultant Photos" tooltip="cons_photos" validationType="file">
                                                <a href="javascript:void(0);" style= "display:none;" onclick="removeAddedElementInConsultant(this);" class="remove-link-2"><i class="abroad-cms-sprite remove-icon"></i>Remove Photo</a> 
                                                <div style="display: none" class="errorMsg" id="1_consultantPhotos_<?=$formName?>_error"></div>
                                        <?php
                                            }
                                            else
                                            {
                                                    if(count($consultantData['consultantPictureData'])>0)
                                                    {
                                        ?>
                                                    <div class="picture-list">
                                                        <ul>
                                                            <?php
                                                                for($i=0;$i<count($consultantData['consultantPictureData']);$i++)
                                                                {
                                                            ?>
                                                            <li>
                                                                <img src="<?=$consultantData['consultantPictureData'][$i]['thumburl']?>" width="143" height="106" alt="">
                                                                <input type="hidden" name="consultantPicturesMediaId[]" value="<?=$consultantData['consultantPictureData'][$i]['media_id']?>"/>
                                                                <input type="hidden" name="consultantPicturesMediaUrl[]" value="<?=$consultantData['consultantPictureData'][$i]['url']?>"/>
                                                                <input type="hidden" name="consultantPicturesMediaThumbUrl[]" value="<?=$consultantData['consultantPictureData'][$i]['thumburl']?>"/>
                                                                <i class="abroad-cms-sprite remove-icon2 remove-consultant-picture"></i>
                                                            </li>
                                                            <?php
                                                                }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                    <div class = "clearFix"></div>
                                        <?php        
                                                    }
                                                    
                                        ?>
                                                <div class="max-photo-check" style="display:<?=($i<10)?'block':'none'?>">
                                                <input type="file" name = "consultantPhotos[]" id = "1_consultantPhotos_<?=$formName?>"  onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');"  caption="Consultant Photos" tooltip="cons_photos" validationType="file"/>
                                                <!-- Check for below <a> tag functionality-->
                                                <a href="javascript:void(0);" style= "display:none;" onclick="removeAddedElementInConsultant(this);" class="remove-link-2"><i class="abroad-cms-sprite remove-icon"></i>Remove Photo</a> 
                                                <div style="display: none" class="errorMsg" id="1_consultantPhotos_<?=$formName?>_error"></div>
                                                <div class="clearFix"></div>
                                                </div>
                                        <?php
                                                    
                                            }
                                        ?>
                                    </div>
                                    <a href="javascript:void(0)" class="addMorePhotoLink" style="display:<?=($i<10)?'block':'none'?>" onclick="addAnotherImageForConsutant(this,'<?=$formName?>')">[+] Add another photo</a>
                                </div>
                             </li>
                             <li>
                                <label>Consultant website* : </label>
                                <div class="cms-fields">
                                    <input type="text" class="universal-txt-field cms-text-field" name="consultantWebsite" id="consultantWebsite_<?=$formName?>" onblur="showErrorMessage(this,'<?=$formName?>')" onchange="showErrorMessage(this,'<?=$formName?>')" required="true" caption="Consultant Website" tooltip="cons_website" maxlength="500" validationType="link" value="<?=htmlspecialchars($consultantData['cosultantGeneralInfo']['website'])?>">
                                    <div style="display: none" class="errorMsg" id="consultantWebsite_<?=$formName?>_error"></div>
                                </div>
                             </li>
                             <li>
                                <label>Does the consultant offer <br> paid services to students?*:</label>
                                <?php
                                    $offerPaidServiceNo = 'checked="checked"';
                                    if($consultantData['cosultantGeneralInfo']['offersPaidServices'] == 'yes'){
                                        $offerPaidServiceNo = '';
                                    }
                                ?>
                                <div style="margin-top:6px;" class="cms-fields">
                                    <input type="radio" name="consultantOfferPaidService[]" id="consultantOfferPaidServiceYes_<?=$formName?>" value="yes" required="true" caption="Consultant Offer Paid Service" tooltip="cons_paid" validationType="radio" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');showHideTextArea(this);" <?=($offerPaidServiceNo == '')?'checked="checked"':''?>> Yes
                                    <input type="radio" name="consultantOfferPaidService[]" id="consultantOfferPaidServiceNo_<?=$formName?>" value="no" required="true" caption="Consultant Offer Paid Service" tooltip="cons_paid" validationType="radio" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');showHideTextArea(this);" <?=$offerPaidServiceNo?>> No
                                    <!--<div style="display: none" class="errorMsg" id="consultantOfferPaidService_<?//=$formName?>_error"></div>-->
                                </div>
                            </li>
                            <li id="testli" style="display:<?=($offerPaidServiceNo == '')?'block':'none'?>">
                                <label>Description of services : </label>
                                <div class="cms-fields">
                                    <textarea class="cms-textarea tinymce-textarea" name="consultantServiceDescription" id="consultantServiceDescription_<?=$formName?>" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');" maxlength="2000" required="false" caption="Description of Service" tooltip="cons_paidDesc" validationType="html"><?=$consultantData['cosultantGeneralInfo']['paidServicesDetails']?></textarea>
                                    <div style="display: none" class="errorMsg" id="consultantServiceDescription_<?=$formName?>_error"></div>
                                </div>
                             </li>
                             <li>
                                <label>Offers Test Prep* : </label>
                                <?php
                                    $offerTestPrepServiceNo = 'checked="checked"';
                                    if($consultantData['cosultantGeneralInfo']['offersTestPrepServices'] == 'yes')
                                    {
                                        $offerTestPrepServiceNo = '';
                                    }
                                ?>
                                <div style="margin-top:6px;" class="cms-fields">
                                    <input type="radio" name="consultantOfferTestPrep[]" id="consultantOfferTestPrepYes_<?=$formName?>" value="yes" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');showHideTextArea(this);" caption="Consutant Offers Test Prep" tooltip="cons_offerTestPrep"  required="false" validationType="radio" <?=($offerTestPrepServiceNo == '')?'checked="checked"':''?>> Yes
                                    <input type="radio" name="consultantOfferTestPrep[]" id="consultantOfferTestPrepNo_<?=$formName?>" value="no" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');showHideTextArea(this);" caption="Consutant Offers Test Prep" tooltip="cons_offerTestPrep" required="false" validationType="radio" <?=$offerTestPrepServiceNo?>> No
                                    <!--<div style="display: none" class="errorMsg" id="consultantOfferTestPrepYes_<?//=$formName?>_error"></div>-->
                                </div>
                            </li>
                            <li style="display:<?=($offerTestPrepServiceNo=='')?'block':'none'?>">
                               <label>Test Prep services : </label>
                               <div class="cms-fields">
                                   <textarea class="cms-textarea tinymce-textarea" name="consutantTestPrepService" id="consutantTestPrepService_<?=$formName?>" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');" required="false" caption="Test Prep Service" tooltip="cons_testServices" validationType="html" maxlength="2000"><?=$consultantData['cosultantGeneralInfo']['testPrepServicesDetails']?></textarea>
                                   <div style="display: none" class="errorMsg" id="consutantTestPrepService_<?=$formName?>_error"></div>
                               </div>
                            </li>

                            <li>
                               <label>CEO Name : </label>
                               <div class="cms-fields">
                                   <input type="text" class="universal-txt-field cms-text-field" name="ceoName" id="ceoName_<?=$formName?>" maxlength="100" validationType="str" caption="CEO Name" tooltip="cons_coe" value="<?=htmlspecialchars($consultantData['cosultantGeneralInfo']['ceoName'])?>">
                                  
                               </div>
                            </li>
                            <li>
                               <label>CEO Description : </label>
                               <div class="cms-fields">
                                   <textarea class="cms-textarea tinymce-textarea" name="ceoDescription" id="ceoDescription_<?=$formName?>" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');" maxlength="2000" caption="CEO Description" tooltip="cons_coeDesc" validationType="html"><?=$consultantData['cosultantGeneralInfo']['ceoQualification']?></textarea>
                                   <div style="display: none" class="errorMsg" id="ceoDescription_<?=$formName?>_error"></div>
                               </div>
                            </li>
                            <li>
                                <label>Number of Employees of consultant* : </label>
                                <div class="cms-fields">
                                    <select name="consultantNumberOfEmployees" id="consultantNumberOfEmployees_<?=$formName?>" class="universal-select cms-field" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');" required="true" caption="Number Of Employees" tooltip="cons_employees" validationType="select">
                                        <?php
                                            foreach($dropDownNumberOfEmployee as $key=>$value){
                                        ?>      
                                        <option <?=($value==$consultantData['cosultantGeneralInfo']['employeeCount'])?'selected="selected"':''?> value="<?=$value?>"><?=$key?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                    <div style="display: none" class="errorMsg" id="consultantNumberOfEmployees_<?=$formName?>_error"></div>
                                </div>
                            </li>

                           </ul>
                    </div>
                    
                    <div class="clear-width">
                        <div class="cms-form-wrap" style="margin:0 0 10px 0; padding-top:8px; border-top:1px solid #ccc;">
                            <ul>
                                <li>
                                    <label>User Comments*: </label>
                                    <div class="cms-fields">
                                       <textarea class="cms-textarea" style="width:75%;" name="consultantUserComments" id="consultantUserComments_<?=$formName?>" maxlength="256" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');" required="true" caption="Comments" validationType="str"></textarea>
                                       <div style="display: none" class="errorMsg" id="consultantUserComments_<?=$formName?>_error"></div>
                                       <div style="display: none" class="errorMsg" id="consultantAllValidationCheck_<?=$formName?>_error"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" name="consultantSaveMode" id="consultantSaveMode" value="">
                    <input type="hidden" name="consultantActionType" id="consultantActionType" value="<?=$formName?>">
                    <input type="hidden" name="consultantCreatedBy" id="consultantCreatedBy" value="<?=$consultantData['cosultantGeneralInfo']['createdBy']?>">
                    <input type="hidden" name="consultantCreatedAt" id="consultantCreatedAt" value="<?=$consultantData['cosultantGeneralInfo']['createdAt']?>">
                    <input type="hidden" name="consultantSaveModeOld" id="consultantSaveModeOld" value="<?=$consultantData['cosultantGeneralInfo']['status']?>">
                    <input type="hidden" name="consultantId" id="consultantId" value="<?=$consultantData['cosultantGeneralInfo']['consultantId']?>">
                    
                </div>
            </div>
            <div class="button-wrap">
                <a onclick="submitConsultantFormData('draft','<?=$formName?>',this);" class="gray-btn" href="javascript:void(0);">Save as Draft</a>
                <!--<a class="gray-btn" href="#">Preview</a>-->
                <a onclick="submitConsultantFormData('<?=ENT_SA_PRE_LIVE_STATUS?>','<?=$formName?>',this);" class="orange-btn" href="javascript:void(0);">Save &amp; Publish</a>
                <a class="cancel-btn" href="javascript:void(0)" onclick="confirmRedirection();">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        window.onbeforeunload =confirmExit; 
        var preventOnUnload = false;
        var saveInitiated = false;
            function confirmExit(){//alert(saveInitiated);
                    if(preventOnUnload == false){
                        return 'Any unsaved change will be lost.';
                    }
            }
        if(document.all) {
            document.body.onload = initFormPosting;
        } else {
            initFormPosting();
        }
        
        function initFormPosting() {
            AIM.submit(document.getElementById('form_<?=$formName?>'), {'onStart' : startCallback, 'onComplete' : completeCallback});
        }
        
        function startCallback(){
            // perform logics if required before submit form data
            return true;
        }
        
        function completeCallback(response){
            // perform logics if required after submit form data
            var respData;
            if(response != 0){
                respData = JSON.parse(response);
            }
            //console.log(respData);
            if(typeof respData != 'undefined' && typeof respData.Fail != 'undefined'){
                preventOnUnload = true;
                saveInitiated = false;
                $j("#consultantAllValidationCheck_<?=$formName?>_error").html("Please scroll up & correct the fields shown with error message.").show();
                for(var prop in respData.Fail){
                    switch(prop){
                        case 'logo'     :   $j('#consultantLogo_<?=$formName?>_error').html(respData.Fail[prop]).show();
                                            break;
                        case 'picture'  :   if(respData.Fail[prop] == "Only Images of type jpeg,gif,png are allowed"){
                                                var photoErrorMsg = respData.Fail[prop];
                                            }else if(respData.Fail[prop] instanceof Array){
                                                for(var indexError in respData.Fail[prop]){       
                                                    var photoIndex = parseInt(indexError)+1;
                                                    if(respData.Fail[prop][indexError] != "no error"){
                                                        $j("#"+photoIndex+"_consultantPhotos_<?=$formName?>_error").html(respData.Fail[prop][indexError]).show();
                                                    }
                                                }
                                            }else{
                                                var photoErrorMsg = respData.Fail[prop]+" in one of the images";
                                            }
                                            $j("#"+($j("[name='consultantPhotos[]']").length)+"_consultantPhotos_<?=$formName?>_error").html(photoErrorMsg).show();
                                            break;
                    }
                }
            }else{
                alert("Consultant has been saved successfully.");
                preventOnUnload = true;
                window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE?>";
            }
            //return true;
        }
        
        function confirmRedirection(){   
            var choice = confirm("Are you sure you want to cancel? All data changes will be lost.");
            if (choice) {
                preventOnUnload = true;
                //window.onbeforeunload = null;
                window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE?>";
            }
            else{
                preventOnUnload = true;
            }
        }
        
        
    </script>

