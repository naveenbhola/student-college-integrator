<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">
<?php
    $displayData['breadCrumb']  =   array(
                                        array('text' => 'All Country Homepages','url' => ENT_SA_CMS_COUNTRYHOME_PATH.ENT_SA_VIEW_COUNTRYHOME_WIDGETS),
                                        array('text' => 'Edit Country Home Widgets','url' => '')
                                            );
    $displayData['pageTitle'] = 'Edit Country Home Widgets';
    if($formName == ENT_SA_EDIT_COUNTRYHOMEWIDGETS && $widgetData['modifiedAt']!=''){
    $displayData["lastUpdatedInfo"] = array("title"    => "Last modified",
                                                "date"     => date("d-m-Y",  strtotime($widgetData['modifiedAt'])),
                                                "username" => $widgetData['modifiedByName']);
    $displayData["pageTitle"] .= "<label style='color:red;'>".($widgetData['status']=="draft"?" (Draft Version)":" (Published Version)")."</label>";
    }
    $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
?>
<form id="form_<?=$formName?>" name="<?=$formName?>" action="<?=ENT_SA_CMS_COUNTRYHOME_PATH.'saveCountryHomeWidgets'?>" method="POST" enctype="multipart/form-data">    
    <input name = "countryId" type="hidden" value="<?php echo $widgetData['country'];?>">
    <input name = "addedAt" type="hidden" value="<?php echo ($widgetData['addedAt']=='')?date('Y-m-d h:i:s'):$widgetData['addedAt'];?>">
    <input name = "addedBy" type="hidden" value="<?php echo ($widgetData['addedBy']=='')?$userid:$widgetData['addedBy'];?>">
    <div class="cms-form-wrapper clear-width">
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite minus-icon"></i>Student visa section for <?php echo $widgetData['name'];?></h3>
                <div class="cms-form-wrap">
                    <ul>
                    <li>
                            <label>Visa application process* : </label>
                            <div class="cms-fields">
                                <select class="universal-select cms-field" name="visaComplexity" id="visaComplexity" validationType="select" onblur="showErrorMessage(this, '<?=$formName?>');" caption=" visa application process">                         
                                    <option value="">Select level of difficulty</option>
                                    <option value="simple"   <?php if($widgetData['visaProcessComplexity']=='simple'){ echo 'selected="selected"';}?>>Simple</option>
                                    <option value="moderate" <?php if($widgetData['visaProcessComplexity']=='moderate'){ echo 'selected="selected"';}?>>Moderate</option>
                                    <option value="complex"  <?php if($widgetData['visaProcessComplexity']=='complex'){ echo 'selected="selected"';}?>>Complex</option>
                                </select>
                                <div style="display: none;clear: both;" class="errorMsg" id="visaComplexity_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Fees for visa application* : </label>
                            <div class="cms-fields">
                                <div class="flLt" style="margin-right:10px;width: 200px;">
                                <input name="feeAmount" id="feeAmount" validationType="numeric" maxlength="10" minlength="1" onblur="showErrorMessage(this, '');$j('#feeExtra_error').hide();" caption="visa fee amount" type="text" class="universal-txt-field cms-text-field cms-field2" placeholder="Enter fees" value="<?= ($widgetData['visaFeeAmount']>0)?$widgetData['visaFeeAmount']:'';?>" />
                                <div style="display: none" class="errorMsg extraError" id="feeAmount_error"></div>
                                <div style="display: none;clear: both;" class="errorMsg extraError" id="feeExtra_error"></div>
                                </div>
                                <div class="flLt">
                                <?php
                                  $currencyDropDownHtml = '<option value="" >Select Currency</option>';
                                  foreach($currencyData as $key => $currency){
                                          if($widgetData['visaFeeUnit'] == $currency['id']){
                                                  $currencyDropDownHtml .=  '<option selected="selected" value='.$currency['id'].'>'.$currency['currency_name'].'</option>';
                                          } else {
                                                  $currencyDropDownHtml .=  '<option value='.$currency['id'].'>'.$currency['currency_name'].'</option>';
                                          }
                                  }
                                ?>
                                <select class="universal-select cms-field2" id="visaFeeCurrencyId" name="visaFeeCurrencyId" onchange="$j('#feeExtra_error').hide();">                         
                                    <?= $currencyDropDownHtml;?>
                                </select>
                                <div style="display: none;clear: both;" class="errorMsg" id="visaFeeCurrencyId_error"></div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <label>Timelines & visa processing* :</label>
                            <div class="cms-fields">
                                <input value="<?php echo htmlentities($widgetData['visaTimeline']);?>" class="universal-txt-field cms-text-field" type="text" maxlength="32" id="visaTimeLine" name="visaTimeLine" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');" />
                                <div style="display: none;clear: both;" class="errorMsg" id="visaTimeLine_error"></div>
                            </div>
                        </li>
                         <li>
                            <label>Description* : </label>
                            <div class="cms-fields">
                                <textarea class="cms-textarea tinymce-textarea-limitedoptions" maxlength="500" id="visaDescription" name="visaDescription" validationType="html" onblur="showErrorMessage(this, '<?=$formName?>');" caption="visa process description "><?php echo $widgetData['visaDescription'];?></textarea>
                                <div style="display: none;clear: both;" class="errorMsg" id="visaDescription_error"></div>
                            </div>
                        </li>
                         <li>
                            <label>Article Link* : </label>
                            <div class="cms-fields">
                                <input value="<?php echo $widgetData['visaArticleLink'];?>" class="universal-txt-field cms-text-field linkField" maxlength="500" id="visaArticleLink" name="visaArticleLink" validationType="link" onblur="showErrorMessage(this, '<?=$formName?>');validateArticleLink(this);" caption=" article link " />
                                <div style="display: none;clear: both;" class="errorMsg" id="visaArticleLink_error"></div>
                                <div style="display: none;clear: both;" class="errorMsg linkError" id="visaArticleLink_link_error"></div>
                            </div>
                            
                        </li>
                         <li>
                            <label></label>
                            <div class="cms-fields">
                                <div style="display: none;clear: both;" class="errorMsg extraError" id="visaSectionError"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite minus-icon"></i>Working in <?php echo $widgetData['name'];?> section</h3>
                <div class="cms-form-wrap">
                    <ul class="clear-width">
                        <li>
                            <label style="padding-top:20px;">Part time work : </label>
                            <div class="cms-fields">
                                <strong class="partTime-head">Part time work while study</strong>
                                <select class="universal-select cms-field" id="partTimeWorkStatus" name="partTimeWorkStatus" onchange="validateWorkSectionCountryHomeWidgetForm();" validationType="select" onblur="showErrorMessage(this, '<?=$formName?>');" caption=" Part time work while study ">                         
                                    <option value="">Select Permitted/ Not Permitted</option>
                                    <option value="permitted" <?php if($widgetData['partTimeWorkStatus']=='permitted') echo 'selected="selected"';?>>Permitted</option>
                                    <option value="not permitted" <?php if($widgetData['partTimeWorkStatus']=='not permitted') echo 'selected="selected"';?>>Not permitted</option>
                                </select>
                                <div style="display: none;clear: both;" class="errorMsg" id="partTimeWorkStatus_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Hours : </label>
                            <div class="cms-fields">
                                <input value="<?php echo htmlentities($widgetData['partTimeWorkHours']);?>" class="universal-txt-field cms-text-field" maxlength="8" id="partTimeHours" name="partTimeHours" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');" caption=" hours " />
                                <div style="display: none;clear: both;" class="errorMsg" id="partTimeHours_error"></div>
                            </div>
                            
                        </li>
                        <li>
                            <label>Per no. of days : </label>
                            <div class="cms-fields">
                                <input value="<?php echo htmlentities($widgetData['partTimeWorkDays']);?>" class="universal-txt-field cms-text-field" maxlength="22" id="partTimeDays" name="partTimeDays" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');" caption=" per no. of days " />
                                <div style="display: none;clear: both;" class="errorMsg" id="partTimeDays_error"></div>
                            </div>
                            
                        </li>
                        <li>
                            <label>Description* : </label>
                            <div class="cms-fields">
                                <textarea class="cms-textarea tinymce-textarea-limitedoptions" maxlength="500" id="partTimeDescription" name="partTimeDescription" validationType="html" onblur="showErrorMessage(this, '<?=$formName?>');" caption=" part time work description "><?php echo $widgetData['partTimeWorkDescription'];?></textarea>
                                <div style="display: none;clear: both;" class="errorMsg" id="partTimeDescription_error"></div>
                            </div>
                            
                        </li>
                        <li>
                            <label>Article Link* : </label>
                            <div class="cms-fields">
                                <input value="<?php echo $widgetData['partTimeWorkArticleLink'];?>" class="universal-txt-field cms-text-field linkField" maxlength="500" id="partTimeArticleLink" name="partTimeArticleLink" validationType="link" onblur="showErrorMessage(this, '<?=$formName?>');validateArticleLink(this);" caption=" article link " />
                                <div style="display: none;clear: both;" class="errorMsg" id="partTimeArticleLink_error"></div>
                                <div style="display: none;clear: both;" class="errorMsg linkError" id="partTimeArticleLink_link_error"></div>
                            </div>
                            
                        </li>
                    </ul>
                    <ul class="clear-width" style="margin-top:20px;">
                        <li>
                            <label style="padding-top:20px;">Post time work: </label>
                            <div class="cms-fields">
                                <strong class="partTime-head">Post study work permit </strong>
                                <select class="universal-select cms-field" id="postStudyWorkStatus" name="postStudyWorkStatus" onchange="validateWorkSectionCountryHomeWidgetForm();"  validationType="select" onblur="showErrorMessage(this, '<?=$formName?>');" caption=" post study work ">                         
                                    <option value="">Select Permitted/ Not Permitted</option>
                                    <option value="permitted" <?php if($widgetData['postStudyWorkStatus']=='permitted'){ echo 'selected="selected"';}?>>Permitted</option>
                                    <option value="not permitted" <?php if($widgetData['postStudyWorkStatus']=='not permitted'){ echo 'selected="selected"';}?>>Not permitted</option>
                                </select>
                                <div style="display: none;clear: both;" class="errorMsg" id="postStudyWorkStatus_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Hours : </label>
                            <div class="cms-fields">
                                <input value="<?php echo htmlentities($widgetData['postStudyWorkHours']);?>" type="text" class="universal-txt-field cms-text-field" maxlength="8" id="postStudyHours" name="postStudyHours" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');" caption=" hours "  />
                                <div style="display: none;clear: both;" class="errorMsg" id="postStudyHours_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Per no. of days : </label>
                            <div class="cms-fields">
                                <input value="<?php echo htmlentities($widgetData['postStudyWorkDays']);?>" type="text" class="universal-txt-field cms-text-field"  maxlength="22" id="postStudyDays" name="postStudyDays" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');" caption=" per no. of days" />
                                <div style="display: none;clear: both;" class="errorMsg" id="postStudyDays_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Description* : </label>
                            <div class="cms-fields">
                                <textarea class="cms-textarea tinymce-textarea-limitedoptions" maxlength="500" id="postStudyDescription" name="postStudyDescription" validationType="html" onblur="showErrorMessage(this, '<?=$formName?>');" caption="post study description "><?php echo $widgetData['postStudyWorkDescription'];?></textarea>
                                <div style="display: none;clear: both;" class="errorMsg" id="postStudyDescription_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Article Link* : </label>
                            <div class="cms-fields">
                                <input value="<?php echo $widgetData['postStudyWorkArticleLink'];?>" type="text" class="universal-txt-field cms-text-field linkField" maxlength="500" id="postStudyArticleLink" name="postStudyArticleLink" validationType="link" onblur="showErrorMessage(this, '<?=$formName?>');validateArticleLink(this);" caption="post study article link " />
                                <div style="display: none;clear: both;" class="errorMsg" id="postStudyArticleLink_error"></div>
                                <div style="display: none;clear: both;" class="errorMsg linkError" id="postStudyArticleLink_link_error"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite minus-icon"></i>Economics overview for <?php echo $widgetData['name'];?></h3>
                <div class="cms-form-wrap">
                    <ul class="clear-width">
                        <li>
                            <label style="padding-top:20px;">Growth rate* : </label>
                            <div class="cms-fields">
                                <strong class="partTime-head">Economics growth rate</strong>
                                <input value="<?php echo htmlentities($widgetData['ecocnomicGrowthRate']);?>" type="text" class="universal-txt-field cms-text-field" maxlength="50" id="ecocnomicGrowthRate" name="ecocnomicGrowthRate" validationType="str" onblur="validateEconomicSectionCountryHomeWidgetForm();showErrorMessage(this, '<?=$formName?>');" caption="ecocnomic growth rate " />
                                <div style="display: none;clear: both;" class="errorMsg" id="ecocnomicGrowthRate_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Description* : </label>
                            <div class="cms-fields">
                                <textarea class="cms-textarea tinymce-textarea-limitedoptions" maxlength="500" id="economicDescription" name="economicDescription" validationType="html" onblur="validateEconomicSectionCountryHomeWidgetForm();showErrorMessage(this, '<?=$formName?>');" caption="economic description "><?php echo $widgetData['economicDescription'];?></textarea>
                                <div style="display: none;clear: both;" class="errorMsg" id="economicDescription_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Article Link* : </label>
                            <div class="cms-fields">
                                <input value="<?php echo $widgetData['economicArticleLink'];?>" type="text" class="universal-txt-field cms-text-field linkField" maxlength="500" id="economicArticleLink" name="economicArticleLink" validationType="link" onblur="validateEconomicSectionCountryHomeWidgetForm();showErrorMessage(this, '<?=$formName?>');validateArticleLink(this);" caption=" article link " />
                                <div style="display: none;clear: both;" class="errorMsg" id="economicArticleLink_error"></div>
                                <div style="display: none;clear: both;" class="errorMsg linkError" id="economicArticleLink_link_error"></div>
                            </div>
                        </li>
                        
                    </ul>
                    <ul class="clear-width" style="margin-top:20px;">
                        <li>
                            <label style="padding-top:20px;">Popular sectors* : </label>
                            <div class="cms-fields">
                                <strong class="partTime-head">Popular jobs sectors</strong>
                                <textarea class="cms-textarea tinymce-textarea-limitedoptions" maxlength="150" id="popularSector" name="popularSector" validationType="html" onblur="validateEconomicSectionCountryHomeWidgetForm();showErrorMessage(this, '<?=$formName?>');" caption=" popular sector "><?php echo $widgetData['popularSector'];?></textarea>
                                <div style="display: none;clear: both;" class="errorMsg" id="popularSector_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Article Link* : </label>
                            <div class="cms-fields">
                                <input value="<?php echo $widgetData['popularSectorArticleLink'];?>" type="text" class="universal-txt-field cms-text-field linkField" maxlength="500" id="popularSectorArticleLink" name="popularSectorArticleLink" validationType="link" onblur="validateEconomicSectionCountryHomeWidgetForm();showErrorMessage(this, '<?=$formName?>');validateArticleLink(this);" caption=" article link " />
                                <div style="display: none;clear: both;" class="errorMsg" id="popularSectorArticleLink_error"></div>
                                <div style="display: none;clear: both;" class="errorMsg linkError" id="popularSectorArticleLink_link_error"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
    </div>
    <div style="display: none;clear: both;margin-left:250px;" class="errorMsg extraError" id="wholeForm_error"></div>
    <div class="button-wrap">
        <?php if($widgetData['modifiedAt']!=''){
        $countryHomeUrl = SHIKSHA_STUDYABROAD_HOME.'/study-in-'.seo_url_lowercase($widgetData['name']).'-countryhome';    
        ?>
        <a href="<?php echo $countryHomeUrl;?>" class="gray-btn" target="_blank">Preview</a>
        <?php } ?>
        <input type="button" id="bttnSavePublish" name="bttnSavePublish" value="Save & Publish" onclick="saveCountryHomeWidgetForm(this, '<?=$formName?>')" class="orange-btn"/>
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
    if(typeof respData != 'undefined' && typeof respData.error != 'undefined'){
        preventOnUnload = true;
        saveInitiated = false;
        if (typeof respData.error_type != 'undefined') {
            alert(respData.error_type);
            location.reload();
        }
    }else{
        alert("widgets data has been saved successfully.");
        preventOnUnload = true;
        window.location.href="<?=ENT_SA_CMS_COUNTRYHOME_PATH.ENT_SA_VIEW_COUNTRYHOME_WIDGETS?>";
    }
    //return true;
}

function confirmRedirection(){   
    var choice = confirm("Are you sure you want to cancel? All data changes will be lost.");
    if (choice) {
        preventOnUnload = true;
        //window.onbeforeunload = null;
        window.location.href="<?=ENT_SA_CMS_COUNTRYHOME_PATH.ENT_SA_VIEW_COUNTRYHOME_WIDGETS?>";
    }
    else{
        preventOnUnload = true;
    }
}


</script>

