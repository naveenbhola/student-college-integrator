<?php
//    _p($consultantData);
//    die;
?>
    <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
    <div class="abroad-cms-rt-box">
        
        <?php
            $displayData['breadCrumb']  =   array(
                                                array('text' => 'All Locations','url' => ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_LOCALITY_TABLE),
                                                array('text' => (($formName == ENT_SA_FORM_ADD_LOCALITY)?'Add a City ':'Edit City'),'url' => '')
                                                    );
            $displayData['pageTitle'] = ($formName == ENT_SA_FORM_ADD_LOCALITY)?'Add a Location':'Edit Location';
            if($formName == ENT_SA_FORM_EDIT_LOCALITY){
            $displayData["lastUpdatedInfo"] = array("title"    => "Last modified",
                                                        "date"     => date("d-m-Y",  strtotime($locationFormData['modifiedAt'])),
                                                        "username" => $locationFormData['modifiedByName']);
            
            }
            
            
            $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
        ?>
        <form id="form_<?=$formName?>" name="<?=$formName?>" action="<?=ENT_SA_CMS_CONSULTANT_PATH.'saveLocalityFormData'?>" method="POST" enctype="multipart/form-data">    
            <div class="cms-form-wrapper clear-width">
                <div class="clear-width">
                    <h3 class="section-title non-collapsible"><!--<i class="abroad-cms-sprite minus-icon"></i>-->Location for consultants</h3>
                    <div style="margin-bottom:0;" class="cms-form-wrap">
                        <ul class="localityCityList">
                            <li>
                                <label>Select City* : </label>
                                <div class="cms-fields">
                                    <select <?=($formName == ENT_SA_FORM_EDIT_LOCALITY)?'disabled="disabled"':''?> name="locationCity[]" id="1_locationCity_<?=$formName?>" class="universal-select cms-field" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="showErrorMessage(this,'<?=$formName?>');" required="true" caption="Location City" validationType="select">
                                        <option value="">Select City</option>
                                        <?php
                                            foreach($consultantLocationCities as $value){
                                        ?>      
                                        <option <?=($value['cityId'] == $locationFormData['cityId'])?'selected="selected"':''?> value="<?=$value['cityId']?>"><?=$value['cityName']?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                    <div style="display: none" class="errorMsg" id="1_locationCity_<?=$formName?>_error"></div>
                                </div>
                            </li>
                            <li>
                                <label>Add a Locality Name* : </label>
                                <div class="cms-fields">
                                    <input type="text" class="universal-txt-field cms-text-field" name="localityName[]" id="1_localityName_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" required="true" maxlength="50" validationType="str" caption="Locality Name" value="<?=htmlspecialchars($locationFormData['name'])?>">
                                    <a href="javascript:void(0);" style= "display:none;" style="margin-left: 260px" onclick="removeLocationFromForm(this)" class="remove-link-2"><i class="abroad-cms-sprite remove-icon"></i>Remove</a>
                                    <div style="display: none" class="errorMsg" id="1_localityName_<?=$formName?>_error"></div>
                                </div>
                            </li>
                        </ul>
                        <?php if($formName == ENT_SA_FORM_ADD_LOCALITY){?>
                        <a class="addMoreLocality" href="javascript:void(0)" style="margin-left: 260px;" onclick="addAnotherLocality(this,'<?=$formName?>')">[+] Add another city</a>
                        <?php }?>
                    </div>
                    
                    <div style="display: none" class="errorMsg" id="locationAllValidationCheck_<?=$formName?>_error"></div>
                    
                    <input type="hidden" name="localityActionType" value="<?=$formName?>">
                    <input type="hidden" name="localityId" value="<?=$locationFormData['id']?>">
                </div>
            </div>
            <div class="button-wrap">
                <!--<a onclick="submitLocationFormData('draft','<?//=$formName?>',this);" class="gray-btn" href="javascript:void(0);">Save as Draft</a>-->
                <!--<a class="gray-btn" href="#">Preview</a>-->
                <a onclick="submitLocationFormData('<?=ENT_SA_PRE_LIVE_STATUS?>','<?=$formName?>',this);" class="orange-btn" href="javascript:void(0);">Save &amp; Publish</a>
                <a class="cancel-btn" href="javascript:void(0)" onclick="confirmRedirection();">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        window.onbeforeunload =confirmExit;
        var preventOnUnload = false;
        var saveInitiated = false;
        <?php if($formName == ENT_SA_FORM_EDIT_LOCALITY){
        ?>
        var originalLocalityName = '<?=base64_encode($locationFormData['name'])?>';
        <?php }?>
            function confirmExit(){
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
            console.log(respData);
            //return ;
            if(typeof respData != 'undefined' && typeof respData.fail != 'undefined'){
                preventOnUnload = true;
                saveInitiated = false;
                $j("#locationAllValidationCheck_<?=$formName?>_error").html("Please scroll up & correct the fields shown with error message.").show();
                $j.each(respData.fail,function(key,value){
                    console.log(key);
                    if(value == "TRUE"){
                        $j('[name="localityName[]"]:eq('+(key)+')').siblings('.errorMsg').html("Same locality for this city already exist").show();
                        if(formname == '<?=ENT_SA_FORM_EDIT_LOCALITY?>'){
                            $j('#1_locationCity_<?=$formName?>').attr('disabled','disabled');
                        }
                    }
                });
            }else if(typeof respData != 'undefined' && typeof respData.success != 'undefined'){
                alert("Location has been saved successfully.");
                preventOnUnload = true;
                window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_LOCALITY_TABLE?>";
            }else if(typeof respData != 'undefined' && typeof respData.accessDenied != 'undefined'){
                if(respData.accessDenied == 'disallowedaccess'){
                    window.location = '<?=SHIKSHA_STUDYABROAD_HOME?>/enterprise/Enterprise/disallowedAccess';
                }else if(respData.accessDenied == 'notloggedin'){
                    window.location = '<?=SHIKSHA_STUDYABROAD_HOME?>/enterprise/Enterprise/loginEnterprise';
                }
            }
            //return true;
        }
        
        function confirmRedirection(){   
            var choice = confirm("Are you sure you want to cancel? All data changes will be lost.");
            if (choice) {
                preventOnUnload = true;
                //window.onbeforeunload = null;
                window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_LOCALITY_TABLE?>";
            }
            else{
                preventOnUnload = true;
            }
        }
        
        function addAnotherLocality(elem,formName){
            var cloneUl = $j(elem).prev("ul").clone(true,true);
            var Ids = cloneUl.find('[name="locationCity[]"]').attr('id');
            Ids = parseInt(Ids.replace("_locationCity_"+formName,'')) + 1;
            var cloneUlIds = Ids + '_locationCity_' + formName;
            cloneUl.find('[name="locationCity[]"]').attr('id',cloneUlIds);
            cloneUl.find('[name="locationCity[]"]').siblings('.errorMsg').attr('id',cloneUlIds+'_error').html('');
            cloneUl.find('[name="localityName[]"]').attr('id',Ids+'_localityName_'+formName);
            cloneUl.find('[name="localityName[]"]').val('');
            cloneUl.find('[name="localityName[]"]').siblings('.errorMsg').attr('id',Ids+'_localityName_'+formName+'_error').html('');
            cloneUl.find('.remove-link-2').show();
            cloneUl.insertAfter($j(elem).prev());
            if($j('.localityCityList').length == 5){
                $j(elem).hide();
            }
        }
        
        function submitLocationFormData(mode,formName,elem){
            preventOnUnload = true;
            if(saveInitiated)
            {
            return false;
            }
            else{
            saveInitiated = true;
                $j(elem).click(function(){return false;});
            }
            
            var errorFlag = showErrorMessage(this, formname, true);
            <?php if($formName == ENT_SA_FORM_EDIT_LOCALITY){
            ?>
                    if(!errorFlag){
                        var newLocalityName = $j('#1_localityName_'+formname).val();
                        if(originalLocalityName == base64_encode(($j.trim(newLocalityName)))){
                            $j('#1_localityName_'+formname+'_error').html('No changes made, change locality name to publish.').show();
                            errorFlag = true;
                        }
                    }
            <?php }?>
            if(!errorFlag){
                errorFlag   =   checkDuplicate();
            }
    
    
            if(errorFlag){
                $j("#locationAllValidationCheck_"+formName+"_error").html("Fields marked in RED are mandatory while saving the form.").show();
                saveInitiated = false;
            }else{
                $j('#1_locationCity_'+formName).removeAttr("disabled");
                $j("#locationAllValidationCheck_"+formName+"_error").html("").hide();
                $j("#form_"+formName).submit();
            }
        }
        
        function removeLocationFromForm(elem){
            $j(elem).closest('ul').fadeOut(400,function(){
                $j(this).remove();
                $j('.addMoreLocality').show();
            });
        }
        
        function checkDuplicate(){
            var cityLocality = [];
            var tempCityLocality = '';
            var checkStatus = false;
            $j('[name="locationCity[]"]').each(function(index){
                var tempLocalityName = $j('[name="localityName[]"]:eq('+(index)+')').val();
                tempCityLocality = $j(this).val()+'-'+$j.trim(tempLocalityName);
                if($j.inArray(tempCityLocality,cityLocality) !== -1){
                    $j('[name="localityName[]"]:eq('+(index)+')').siblings('.errorMsg').html("This city and locality already exist on this form").show();
                    checkStatus = true;
                }else{
                    $j('[name="localityName[]"]:eq('+(index)+')').siblings('.errorMsg').html("").hide();
                    cityLocality.push(tempCityLocality);
                }
                
            });
            return checkStatus;
        }
        
    </script>

