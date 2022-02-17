<div class="clear_B"></div>

</div>
 <!-- closing abroad-cms-wrapper -->
<?php 

    if(!isset($jbimagesButton))
       $jbimagesButton = 'jbimages';
?>
</body>
</html>

</script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/jquery_ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/multiSelect/jquery.multiselect.min.js"></script>

<script>
    var setActivityCookie = <?php echo ($setActivityCookie === true? 1:0); ?>;
    var userGroupInactivity = <?php echo($usergroup === 'saShikshaApply'? 1:0); ?>;
    var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';

    var examHomepageAvailable = false;
    <?php if($examHomepageAvailable){?>
    examHomepageAvailable = true;
    <?php }?>
    var countryIdToBeSelected = <?=$countryIdAddCityForm ? $countryIdAddCityForm : 0 ?>;
    var stateIdToBeSelected = <?php echo $stateId?$stateId : 0 ?>;
    var citySize = "<?php echo $citySize ? $citySize : 0 ?>";
    var weatherInfo = <?php echo json_encode(json_decode($cityWeatherDetails));?>;
    var oldCityName = "<?php echo $cityName ?>"
    $j = $.noConflict();
    var dataTable;
    $j(document).ready(function($j) {       
        <?php                
            if($usergroup == 'saShikshaApply' && ENVIRONMENT != 'development' && $formName != ENT_SA_VIEW_COUNSELLOR_REPORT){ ?>
                $j("body").on("contextmenu",function(e){
                    return false;        
                });

                $j('body').bind('cut copy', function (e) {
                    e.preventDefault();
                });
        <?php } ?>


        initializeDatatables();

        //call actionTrackingForInactivity fucntion in studyAbroadCMS.js for logging out counsellors after inactivity for specific time
        //actionTrackingForInactivity();
        if (typeof userGroupInactivity != 'undefined' && userGroupInactivity == 1) {
            actionTrackingForInactivity();
        }

        if (typeof tableName != 'undefined' && tableName == 'viewCurrencies') {
            bindCurrencyTableClickHandlers();
            initializeCurrencyDatatable();
        }else if (typeof tableName != 'undefined' && tableName == 'viewAddSASalesMISUsers') {
            bindSASalesMISUsersClickHandlers();
            initializeSASalesMISUsersDatatable();
        }else if (typeof tableName != 'undefined' && tableName == 'getSpamContentData') {
            bindReportSpamTableClickHandlers();
            initializeSASpamContentDatatable();
        }
        $j("[tooltip]").live('focus',studyabroadtooltipshow);
        $j("[tooltip]").live('blur',studyabroadtooltiphide);
        <?php
            if($formName == ENT_SA_EXAM_NAVBAR_LINKS)
            {
                ?>
                    examNavbarLinksOnLoad();
                <?php
            }
        ?>
        hideSuccessMessage();
        // this function is called only in case of addCity form
        performAddCityFormEnd();
    
        /*
         * initialize tinymce editor
         */
        var uploader;
        tinymce.init({
            selector: ".tinymce-textarea",
            theme: "modern",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen textcolor emoticons",
                "insertdatetime media table contextmenu paste jbimages"//moxiemanager
            ],
            file_browser_callback: false,
            toolbar1: /*""*/ " bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link image media preview ",
            toolbar2: "undo redo | styleselect | forecolor backcolor emoticons | <?php echo $jbimagesButton?> ",
            relative_urls : false,
            rel_list: [
                        {title: 'alternate'    , value: 'alternate' },
                        {title: 'author'       , value: 'author'    },
                        {title: 'bookmark'     , value: 'bookmark'  },
                        {title: 'help'         , value: 'help'      },
                        {title: 'license'      , value: 'license'   },
                        {title: 'next'         , value: 'next'      },
                        {title: 'nofollow'     , value: 'nofollow'  },
                        {title: 'noreferrer'   , value: 'noreferrer'},
                        {title: 'prefetch'     , value: 'prefetch'  },
                        {title: 'prev'         , value: 'prev'      },
                        {title: 'search'       , value: 'search'    },
                        {title: 'tag'          , value: 'tag'       }
                    ],
            // added event handlers for focus & blur events to show/hide tooltips
            init_instance_callback : function(editor) {
                editor.on('focus', function(e) {
                    toolTipFlag = true;
                    studyabroadtooltipshow(e,document.getElementById(editor.id));
                    showErrorMessage(document.getElementById(editor.id), formname );
                });
                editor.on('change',function(e){
                    $j("#"+editor.id).html(editor.getContent());
                    showErrorMessage(document.getElementById(editor.id), formname );
                    });
                editor.on('blur', function(e) {
                   
                    tinyMceToolTipHideFlag = true;
                    $j("#"+editor.id).html(editor.getContent());
                    showErrorMessage(document.getElementById(editor.id), formname );
                    studyabroadtooltiphide();
                    
                });
                editorDisable();
                //console.log("Editor: " + editor.id + " is now initialized.");
            }
        
        });
        
        if (typeof formname != 'undefined' && formname == "editUniversityForm") {
            // trigger country change so that state dropdown gets populated
            var country = $j("#country_"+formname).val();
            var data = {'countryId':country};
            var url =  '/listingPosting/AbroadListingPosting/getStatesByCountry/'+country;
            $j.ajax({'url':url,
                     'async':false,
                     'type':'POST',
                     'data':data,
                     'success' : function(response){ 
                                    //console.log(JSON.parse(response));
                                    if (JSON.parse(response) != '') {
                                        $j("#univState_"+formname).html(JSON.parse(response));
                                        //set the selected state
                                        var valueToBe = $j("#univState_"+formname).attr('valueToBe');
                                        $j("#univState_"+formname).val(valueToBe);
                                        $j("#univState_"+formname).removeAttr("disabled");
                                    }
                                    else{
                                        $j("#univState_"+formname).attr("disabled","disabled");
                                        //set the selected city 
                                        valueToBe = $j("#univCity_"+formname).attr('valueToBe');
                                        $j("#univCity_"+formname).val(valueToBe);
                                    }
                            }
                    });
            var state =     $j("#univState_"+formname).val();
            if (typeof state != 'undefined' && state > 0) {
                url =  '/listingPosting/AbroadListingPosting/getCitiesByState/'+state;
                data = {'stateId':state};
            }
            else{
                url =  '/listingPosting/AbroadListingPosting/getCitiesByCountry/'+country;
                data = {'countryId':country};
            }
            $j.ajax({'url':url,
                     'async':false,
                     'type':'POST',
                     'data':data,
                     'success' : function(response){ 
                                    if (response != '') { 
                                        $j("#univCity_"+formname).html(JSON.parse(response));
                                        //set the selected state
                                        valueToBe = $j("#univCity_"+formname).attr('valueToBe');
                                        $j("#univCity_"+formname).val(valueToBe);
                                    }
                            }
                    });
        }
        
        /*
         *The following is to show the current fees on course edit form
         */
        var isCloneFlag = false;
        <?php
        if((isset($isCloneFlag) && $isCloneFlag) || $formName == ENT_SA_FORM_EDIT_COURSE){
        ?>
            maintainTotalFees('<?php echo $formName;?>');
            <?php if((isset($isCloneFlag) && $isCloneFlag))
                {
                    ?>
                        isCloneFlag=true;
                    <?php
                }
                ?>

        <?php   }
        if($formName == ENT_SA_FORM_EDIT_COURSE || $formName == ENT_SA_FORM_ADD_COURSE){
        ?>
           toggleCourseEntryRequirements();
           validateWorkExperinceRequired();
           validateInterviewProcessDate();
        <?php }?>
        <?php if($formName == ENT_SA_SPECIALIZATION_FORM && !empty($formData)){ ?>
            formdata = <?php echo json_encode($formData); ?>;
            prefillForm();
        <?php } ?>
        /*
         * activate accordion behaviour over the form sections
         * (click will toggle visibility of respective section)
         */
        <?php
        if(!in_array($formName,array(ENT_SA_FORM_ADD_CONTENT,ENT_SA_FORM_EDIT_CONTENT,ENT_SA_FORM_ADD_CITY,ENT_SA_FORM_ADD_SNAPSHOT_COURSE,ENT_SA_FORM_EDIT_SNAPSHOT_COURSE,ENT_SA_FORM_ADD_BULK_SNAPSHOT_SOURES,ENT_SA_FORM_ADD_RANKING,ENT_SA_FORM_EDIT_RANKING,ENT_SA_FORM_ADD_PAID_CLIENT,ENT_SA_FORM_EDIT_PAID_CLIENT,ENT_SA_ADD_EDIT_CANDIDATES_SHORTLIST,ENT_SA_ADD_SCHOLARSHIP,ENT_SA_EDIT_SCHOLARSHIP)))
        {
            
        ?>
        $j(".section-title:not(.non-collapsible)").live("click",function(){
                $j(this).next().slideToggle();
                $j(this).find('i').toggleClass("minus-icon plus-icon");
            });
        <?php } ?>
        
        /*
         * pressing tab on last input element of the section will open the next section and
         * set the focus to its first input element
         */
        $j(".last-in-section").live('keydown',function(event){
            if(event.keyCode == 9) // press tab
            {   //get the h3 title tag of next section
                var h3tag = $j(this).closest(".cms-accordion-div").closest(".clear-width").next().find('.section-title');
                if(h3tag.find('i').hasClass("plus-icon")) // open the section only if the section is closed
                {
                  h3tag.trigger("click");
                }
            }
        });
        
        if (typeof(contentPageAction) != "undefined" && contentPageAction == 'edit') {
            populateValueArray('country', selectedUniversityIds, true);
            if(contentPageType == 'guide') {
                $j(".article").hide();
                $j(".guide").show();
            }else if(contentPageType == 'examPage'){
                $j(".article").hide();
                $j(".guide").hide();
                $j(".examPage").show();
                changeSecTitles();
            }else if(contentPageType == 'applyContent'){
                $j(".article").hide();
                $j(".guide").hide();
                $j(".examPage").hide();
                $j(".commonTags").hide();
                $j(".applyContent").show();
            }else if(contentPageType == 'examContent'){
                $j(".article").hide();
                $j(".guide").hide();
                $j(".examPage").hide();
                $j(".commonTags").hide();
                $j(".applyContent").hide();
                $j(".examContent").show();
                initExamContentHomePageCheckActions();
            }
            if(enableCategories) {
                enableCatDropdown();
                if (selectedSubCatIds) {
                    appendChildCategories('content', selectedSubCatIds);
                }
            }
            if (downloadChecked) {
                enableUploadFile();
            }
            if($j('[name="setHomepage"]:checked').length>0){
                 $j("#setHomepageDiv").parent('li').show();
            }else{
                if(contentPageType == 'examContent'){
                    $j("#setHomepageDiv").parent('li').hide();
                }
            }
            if(contentPageType == 'examContent'){
                if (examHomepageAvailable==true) {
                    $j("#setHomepageDiv").parent('li').show();
                }
            }
        }
        
        if (typeof(formName) != "undefined" && formName == "snapshotEdit") {
            appendChildCategories(formName, childCatId);
            getUniversitiesDropDownForCountry(formName, selectedUniversityId);
        }
        
        if(typeof(showSubcategorySelected)!='undefined' && showSubcategorySelected == 1) {
            appendChildCategories(formname, childCatId);
        }

       // if(typeof(getDepartmentDropDown)!='undefined' && getDepartmentDropDown == 1) {
         //   getDepartmentsDropDownForUniversity(formname);
        //}
        $j("#university_deptform").bind('keydown', function(e) {
            enableDepartmentBasicInformationBlock(e);
        });
        $j("#contact_phone_no_deptform").bind('keydown', function(e) {
            enableDepartmentAdditionalInformationBlock(e);
        });
       if('editRankingForm' == '<?=$formName?>' ){
    	   confirmBeforeChangeValueInDropDown();
    	   editRankingPopulateChildCategory('<?=$formData['subcategory_id']?>');
           }
        
        $j(".remove-univ-logo").click(function(){
            $j("#univLogoMediaId").val("");
            $j("#univLogoMediaUrl").val("");
            $j("#univLogo_"+formname).attr('required',true).show();
            $j(this).closest(".image-box").hide();
        });
        $j(".remove-univ-picture").click(function(){
            $j(this).closest("li").find('[name="univPicturesMediaId[]"]').val("");
            $j(this).closest("li").find('[name="univPicturesMediaUrl[]"]').val("");
            $j(this).closest("li").find('[name="univPicturesMediaThumbUrl[]"]').val("");
            $j(this).closest("li").hide();
            if ($j(this).closest('ul').find('li:visible').length == 0) {
                $j(this).closest('.add-more-sec').find('.clearFix').hide();
                $j(this).closest('.add-more-sec').closest('li').find('label').css('padding-top','6px');
            }
        });

        $j(".remove-couns-img").click(function(){
            $j("#counselorImageUrl").val("");
            $j("#counsellorImage_"+formname).attr('required',true).show();
            $j(this).closest(".image-box").hide();
        });
        
        if (formname == 'showEditCourseForm' || formname == 'showAddCourseForm') {
            if(typeof univId !== "undefined")
                getUniversitiesDropDownForCountry(formname,univId);
            else
                getUniversitiesDropDownForCountry(formname);
            getDepartmentsDropDownForUniversity(formname);//'showAddCourseForm'
            if (formname == 'showAddCourseForm' && isCloneFlag === false) {
                getShikshaApplyProfileForUniversity(formname);
            }
        }
        if (formname == 'editPaidClient') {
            $j("#course-search").trigger('click');
            allowChangeCourse = false; // declared in addPaidClient.php
        }
	examTemplate = $j('#moresectiontemplate').html();
	$j('#moresectiontemplate').remove();
        
        // get values for l2 lifecycle tag & preselect in case of edit content form
        <?php if($formName == ENT_SA_FORM_EDIT_CONTENT){ ?>
            // if there were any lifecycle tags saved prevviously
            if (userLifecycleTags.length > 0) {
                var l2_id = '';
                // for each l1 tag preselected
                $j('[name="lifecycleTagL1[]"]').each(function(index,tag){
                    // populate dropdowns
                    $j(tag).trigger('change');
                    // get each l2 tag option selected
                    l2_id = '#lifecycleTagL2_'+$j(tag).attr('id').replace('lifecycleTagL1_','');
                    $j(l2_id).find('option').each(function(ind,val){
                        if ($j(val).val() == userLifecycleTags[index].level2) { // compare with value of l2 tag retrieved from db
                            $j(val).attr('selected','selected');
                        }
                    });
                });
            } // end if (js)
            
        <?php }//end if (php) ?>
        <?php
            if($formName == ENT_SA_FORM_ADD_CONSULTANT || $formName == ENT_SA_FORM_EDIT_CONSULTANT){
        ?>
            $j('.remove-consultant-logo').click(function(){
                $j("#consultantLogoMediaUrl").val("");
                $j("#consultantLogo_"+formname).attr('required',true).show();
                $j(this).closest(".image-box").hide();
            });
            
            $j('.remove-consultant-picture').click(function(){
                $j(this).closest("li").find('[name="consultantPicturesMediaId[]"]').val("");
                $j(this).closest("li").find('[name="consultantPicturesMediaUrl[]"]').val("");
                $j(this).closest("li").find('[name="consultantPicturesMediaThumbUrl[]"]').val("");
                $j(this).closest("li").hide();
                if ($j(this).closest('ul').find('li:visible').length == 0) {
                    $j(this).closest('.add-more-sec').find('.clearFix').hide();
                    $j(this).closest('.add-more-sec').closest('li').find('label').css('padding-top','6px');
                    $j(this).closest('.add-more-sec').find('input[name="consultantPhotos[]"]').attr('required','true');
                }
                $j('.max-photo-check').css('display','block');
                if(($j('input[name="consultantPhotos[]"]').length + $j('.picture-list').find('li:visible').length) >= 10){
                    $j('.addMorePhotoLink').css('display','none');
                }else{
                    $j('.addMorePhotoLink').css('display','block');
                }
                
            });
            
        <?php    }
        ?>
        <?php if($formName == ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING || $formName == ENT_SA_FORM_EDIT_CONSULTANT_UNIVERSITY_MAPPING){ ?>
            cloneVar = $j(".clonable").clone();
            cloneVar.removeClass('clonable').children(":first").css("display","block");
        <?php } ?>
        <?php if($formName == ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING){ ?>
            cloneCount++;
            var temp = cloneVar.html().replace(new RegExp("%id%",'g'),cloneCount);
            $j("#formSubsectionCountList").append("<li id='primaryForm'>"+temp+"</li>");
            $j("#primaryForm").find(".remove-link").css("display","none");
            universityCount++;
        <?php } ?>
        
        <?php if($formName == ENT_SA_FORM_ADD_CONSULTANT_LOCATION && $_GET['all'] == 1){ ?>
            $j('html, body').animate({
                scrollTop: $j("#consultant-location-table").offset().top + 30
            }, 1000);
        <?php } else if($formName == ENT_SA_FORM_EDIT_CONSULTANT_LOCATION){  ?>
            $j('[name="consultantLocationCity[]"]').trigger('change');
            $j('[name="consultantLocationLocality[]"]').val(<?=($consultantLocationDetails['localityId'])?>);
        <?php }  ?>
        <?php if(!$consultantExists && $formName == ENT_SA_FORM_ADD_CONSULTANT_LOCATION) { ?>
            alert("Location cannot be added to the consultant selected by you as it was never published. \nPlease select another consultant from given list or publish the consultant you selected.");
        <?php } ?>
        
        <?php if(($formName== ENT_SA_FORM_ADD_STUDENT_PROFILE || $formName== ENT_SA_FORM_EDIT_STUDENT_PROFILE) && $consultantId !=''){?>
        $j('[name="consultantId"]').each(function(){
		$j(this).val('<?= $consultantId;?>');
                $j(this).attr('disabled',true);
		});
        getUniversityByConsultant('<?= $consultantId;?>');
        <?php if($formName== ENT_SA_FORM_EDIT_STUDENT_PROFILE){?>
         populateFormForEditFields();
        <?php }?> 
        <?php } 
                if(in_array($formName, array(ENT_SA_FORM_ADD_CONSULTANT,ENT_SA_FORM_EDIT_CONSULTANT,ENT_SA_FORM_ADD_STUDENT_PROFILE,ENT_SA_FORM_EDIT_STUDENT_PROFILE,ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING,ENT_SA_FORM_EDIT_CONSULTANT_UNIVERSITY_MAPPING))){
        ?>
                detectIE();
        <?php }?>
        
        <?php if(!$consultantExists && $requestedConsultantId >0 && $formName == ENT_SA_FORM_ASSIGN_CITY) { ?>
            alert("Either the selected consultant does not exists or has been deleted.\nPlease select another consultant from the given list or publish the consultant along with relevant university mapping & student profile.");
        <?php } ?>
        
        <?php if($formName==ENT_SA_FORM_EDIT_RMS_UNIVERSITY_COUNSELLOR_MAPPING){?>
        if (showUniversitySession=="1") {
            setTimeout(function(){
                $j('#universitySessionDetail0').click();
                changeUniversitySessionDetails($j('#universitySessionDetail0'));
            },500);    
        }
        <?php } ?>
        <?php if(($formName==ENT_SA_FORM_ADD_CLIENT_ACTIVATION || $formName==ENT_SA_FORM_EDIT_CLIENT_ACTIVATION || $formName==ENT_SA_VIEW_LISTING_CLIENT_ACTIVATION)){ ?>
            bindClientActivationEvents();
        <?php } ?>
        
        /* we have added a class disabledEditor that will load certain editors in disabled mode,
         * following code does it using that class
         * (added 1 sec delay because it takes some time to apply he editors on textarea)
         */
        setTimeout(function(){
            $j('.disabledEditor').each(function (index,value){
                tinymce.get($j(value).attr('id')).getBody().setAttribute('contenteditable', false);    
            });
        },1000);


    });
</script>
