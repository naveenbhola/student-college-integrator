
<script>
    /*
     For Institute Suggestor
    */

    var autoSuggestorInstance = null;
    var isMobile = true;

    var crCatId = '<?php echo $subcatId; ?>';



    function initializeAutoSuggestorInstancesForCollgeReviews(){
        /*return;
        if (window.addEventListener){ 
            var ele = document.getElementById("keywordSuggest"); 
            ele.addEventListener('keyup', handleInputKeysForInstituteSuggestorCompare, false); 
        } else if (window.attachEvent){
            var ele = document.getElementById("keywordSuggest"); 
            ele.attachEvent('onkeyup', handleInputKeysForInstituteSuggestorCompare); 
        }
        
        autoSuggestorInstance = new AutoSuggestor("keywordSuggest" , "suggestions_container", false, 'institute',6);*/
        
        autoSuggestorInstanceArray.autoSuggestorInstance_CR.callBackFunctionOnMouseClick = handleAutoSuggestorMouseClickCompare_CR;
      
        autoSuggestorInstanceArray.autoSuggestorInstance_CR.callBackFunctionOnEnterPressed = handleAutoSuggestorEnterPressedCompare_CR;
    
        // autoSuggestorInstanceArray.autoSuggestorInstance_CR.callBackFunctionOnRightKeyPressed = handleAutoSuggestorRightKeyPressedCompare_CR;
	autoSuggestorInstanceArray.autoSuggestorInstance_CR.callBackFunctionAfterBuildingSuggestionContainer = handleAutoSuggestorAfterBuildingSuggestionContainer;

	// autoSuggestorInstance.instituteSuggestionsForCategoryIds = crCatId;
	autoSuggestorInstanceArray.autoSuggestorInstance_CR.instituteSuggestionsIncludes = "reviews";
       
    }
    
    var keywordEnteredByUserCompare = '';
    function handleInputKeysForInstituteSuggestorCompare(e){
        $('#tileSuggested').slideDown(400);
        window.jQuery('#keywordSuggest').change(function(){
            keywordEnteredByUserCompare = $('#keywordSuggest').val();        
        });
        
        if(autoSuggestorInstance ){
            autoSuggestorInstance.handleInputKeys(e);
        }
    }
    
    function handleAutoSuggestorAfterBuildingSuggestionContainer(dict){
	$('#suggestions_container').removeClass('suggestion-box');
	if(dict["suggestion_count"] != undefined){
                getSuggestedSearch(parseInt(dict["suggestion_count"]));
        }
    }
    
    function handleClickForAutoSuggestorCompare(){
        if(autoSuggestorInstance){ 
            autoSuggestorInstance.hideSuggestionContainer();
        }
    }
               
    function handleAutoSuggestorMouseClickCompare(callBackData){
        if(autoSuggestorInstance){
            //autoSuggestorInstance.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp']);
        }
    }
    
    function handleAutoSuggestorRightKeyPressedCompare(callBackData){
        //autoSuggestorInstance.hideSuggestionContainer(); 
    }
    
    function handleAutoSuggestorEnterPressedCompare(callBackData){
         if(autoSuggestorInstance){
            //autoSuggestorInstance.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp']);
        }
    }

    function handleAutoSuggestorMouseClickCompare_CR(callBackData){
        if(autoSuggestorInstanceArray.autoSuggestorInstance_CR){
            autoSuggestorInstanceArray.autoSuggestorInstance_CR.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp'],callBackData['type']);
        }
    }

    function handleAutoSuggestorEnterPressedCompare_CR(callBackData){
         if(autoSuggestorInstanceArray.autoSuggestorInstance_CR){
            autoSuggestorInstanceArray.autoSuggestorInstance_CR.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp'],callBackData['type']);
        }
    }

    function instituteSelected(instId,instTitle,instType){
	if(instId > 0){
        $.ajax({
            url: "/mCollegeReviews5/CollegeReviewsController/getSearchReview",
            type: "POST",
            data: {'instituteId':instId,'fromPage':'M_review','stream':stream,'substream':substream,'baseCourse':baseCourse,'educationType':educationType,'instType':instType},
            success: function(result)
            {
		    obj = JSON.parse(result);
                    if (obj.html !=null && obj.html !='' && obj.url == null) {
			$('#searchReviewLayer').html(obj.html);
			$("#callSearchReviewLayer").trigger("click");
		    }else if (obj.url !=null) {
			window.location = obj.url;
		    }
            },
            error: function(e){                        
            }
        });                
	}
    }
</script>
        
