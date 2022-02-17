
<script>
    /*
     For Institute Suggestor
    */

    var autoSuggestorInstanceCompare = null;
    var isMobile = true;

    var keywordEnteredByUserCompare = '';
    function handleInputKeysForInstituteSuggestorCompare(e){
       
        window.jQuery('#keywordSuggest').change(function(){
            keywordEnteredByUserCompare = $('#keywordSuggest').val();        
        });
        
        if(autoSuggestorInstanceArray.autoSuggestorInstanceInstituteForCompareMobile ){
            autoSuggestorInstanceArray.autoSuggestorInstanceInstituteForCompareMobile.handleInputKeys(e);
        }
    }
    
    function handleClickForAutoSuggestorCompare(){
        if(autoSuggestorInstanceArray.autoSuggestorInstanceInstituteForCompareMobile){ 
            autoSuggestorInstanceArray.autoSuggestorInstanceInstituteForCompareMobile.hideSuggestionContainer();
        }
    }
               
    function handleAutoSuggestorMouseClickCompare(callBackData){
        if(autoSuggestorInstanceArray.autoSuggestorInstanceInstituteForCompareMobile){
            autoSuggestorInstanceArray.autoSuggestorInstanceInstituteForCompareMobile.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp'],callBackData['type']);
        }
    }
        
    function handleAutoSuggestorEnterPressedCompare(callBackData){
         if(autoSuggestorInstanceArray.autoSuggestorInstanceInstituteForCompareMobile){
            autoSuggestorInstanceArray.autoSuggestorInstanceInstituteForCompareMobile.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp'],callBackData['type']);
        }
    }

    function instituteSelected(instId,instTitle,listingType){
	if(instId > 0){
        trackEventByGAMobile('MOBILE_COLLEGE_SELECTION_SEARCH_FROM_COMPARE');
        $('#autoSuggSelInstId').val(instId);
        //Now make a backend call to fetch the Courses of the Institute, Institute name, URL
        //If any of the Institute course is matching Sub-category with the First course + It is not already selected, then redirect to the new compare page.
        //Else, show the HTML to user and let him choose the course
        $('#loaderDiv').height($(document).height());
        $('#loaderDivImg').css({'position':'absolute','top':$(window).height()/2 - 30,'left':$(window).width()/2 - 25});
        $('#loaderDiv').show();

        if(typeof(listingType) == 'undefined' || listingType ==''){
            var listingType = '';
        }
        var firstInstituteId = '0';
        var firstlistingType ='';
        if(filled_compares == 1){
            firstInstituteId = $('#instituteName1').attr('instituteId');
            firstlistingType = $('#instituteName1').attr('type');
        }
        $.ajax({
            url: "/mCompareInstitute5/compareInstitutes/getInstituteCoursesAjax",
            type: "POST",
            data: {'instituteId':instId,'listingType':listingType,'firstCourseId':$('#courseSelect1').val(),'secondCourseId':$('#courseSelect2').val(),'firstInstituteId':firstInstituteId,'firstlistingType':firstlistingType},
            success: function(result)
            {
                if (result.length<12 && parseInt(result)>0) {
                    //Recalculate the URL and then redirect the user to the new URL
                    var urlParam = '';
                    for (var i=1; i <= 1; i++){
                            courseIdSelected = $('#courseSelect'+i).val();
                            if (typeof(courseIdSelected)!='undefined' && courseIdSelected!='') {
                                    urlParam += '-'+courseIdSelected;
                            }
                    }
                    homepageWidgets.setCookieForAcademicUnit(result, instId, 'auto');
                    window.location = "/compare-colleges"+urlParam+"-"+result;
                }
                else if (result.length > 1){
                    if(filled_compares == 1){
                        prevCourse   = $('#courseSelectCustom1').html();
                        prevCourseId = $('#courseSelect1').val();   
                    }
                    $('#comparePageDataTable .flagClassForAjaxContent').remove();
                    $('#comparePageDataTable').prepend(result);
                    if(filled_compares == 1){
                        $('#courseSelectCustom1').html(prevCourse);
                        $('#courseSelect1').val(prevCourseId);   
                    }
                    $('#loaderDiv').hide();
                    $('#importantInfoDiv, #courseDisplayDiv').show();
                    $('#addToCompareLayerClose').trigger('click');
                }else{
                    $('#loaderDiv').hide();		    
		        }
            },
            error: function(e){                        
                $('#loaderDiv').hide();
            }
        });                
	}
    }
    
</script>

<div style="position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; opacity: 0.7; background: no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="loaderDiv"><img src="<?php echo MEDIA_SERVER;?>/public/images/smartajaxloader.gif" border=0 id="loaderDivImg"></div>
        
