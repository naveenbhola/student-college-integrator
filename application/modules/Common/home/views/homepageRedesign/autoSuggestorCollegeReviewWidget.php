
<script>
    /*
     For Institute Suggestor on College Review HomePage Widget
    */

    var autoSuggestorInstance_CR = null;
    var mobileSearch = 'true';
    var subcatId = '<?php echo $subcatId;?>';
    if(subcatId == '') {
        subcatId = "23";
    }

    function initializeAutoSuggestorInstancesForCollgeReviews(){
        if (window.addEventListener){ 
            var ele = document.getElementById("keywordSuggest");
            ele.addEventListener('keyup', handleInputKeysForInstituteSuggestorCompare_CR, true);
	
        } else if (window.attachEvent){
            var ele = document.getElementById("keywordSuggest"); 
            ele.attachEvent('onkeyup', handleInputKeysForInstituteSuggestorCompare_CR); 
        }
        
        autoSuggestorInstance_CR = new AutoSuggestor("keywordSuggest" , "suggestions_container", false, 'institute',6);
        
        autoSuggestorInstance_CR.callBackFunctionOnMouseClick = handleAutoSuggestorMouseClickCompare_CR;
      
        autoSuggestorInstance_CR.callBackFunctionOnEnterPressed = handleAutoSuggestorEnterPressedCompare_CR;
    
        autoSuggestorInstance_CR.callBackFunctionOnRightKeyPressed = handleAutoSuggestorRightKeyPressedCompare_CR;
	   autoSuggestorInstance_CR.callBackFunctionAfterBuildingSuggestionContainer = handleAutoSuggestorAfterBuildingSuggestionContainer_CR;

	   autoSuggestorInstance_CR.instituteSuggestionsForCategoryIds = subcatId;
	   autoSuggestorInstance_CR.instituteSuggestionsIncludes = "reviews";
       
    }
    
    var keywordEnteredByUserCompare_CR = '';
    function handleInputKeysForInstituteSuggestorCompare_CR(e){
        $j('#tileSuggested').slideDown(400);
        window.jQuery('#keywordSuggest').change(function(){
            keywordEnteredByUserCompare_CR = $j('#keywordSuggest').val();        
        });
        
        // if(autoSuggestorInstance_CR ){
        //     autoSuggestorInstance_CR.handleInputKeys(e);
        // }
        $j('#suggestions_container').removeClass('suggestion-box').addClass('colgrvw-Sugstr');
    }
    
    function handleAutoSuggestorAfterBuildingSuggestionContainer_CR(dict){
	$j('#suggestions_container').removeClass('suggestion-box').addClass('colgrvw-Sugstr');
	if(dict["suggestion_count"] != undefined){
                getSuggestedSearch(parseInt(dict["suggestion_count"]));
        }
    }
    
    function handleClickForAutoSuggestorCompare_CR(){
        if(autoSuggestorInstanceArray.autoSuggestorInstance_CR){ 
            autoSuggestorInstanceArray.autoSuggestorInstance_CR.hideSuggestionContainer();
        }
    }
               
    function handleAutoSuggestorMouseClickCompare_CR(callBackData){
        if(autoSuggestorInstanceArray.autoSuggestorInstance_CR){
            //autoSuggestorInstance_CR.hideSuggestionContainer();
            instituteSelected_CR(callBackData['id'],callBackData['sp']);
        }
    }
    
    function handleAutoSuggestorRightKeyPressedCompare_CR(callBackData){
        //autoSuggestorInstance_CR.hideSuggestionContainer(); 
    }
    
    function handleAutoSuggestorEnterPressedCompare_CR(callBackData){
         if(autoSuggestorInstanceArray.autoSuggestorInstance_CR){
            //autoSuggestorInstance_CR.hideSuggestionContainer();
            instituteSelected_CR(callBackData['id'],callBackData['sp']);
        }
    }

    function instituteSelected_CR(instId,instTitle){

	if(instId > 0){
        $j.ajax({
            url: "/mCollegeReviews5/CollegeReviewsController/getReviewPageByInstituteId",
            type: "POST",
            data: {'instituteId':instId,'fromPage':'D_review'},
            success: function(result)
            {
		    obj = JSON.parse(result);
                    if (obj.html !=null && obj.html !='' && obj.url == null) {
			if ($j('#suggestions_container') && $j('#suggestions_container').length>0) {
			    var setLayerPosition = $j('#suggestions_container').offset().top + 150;
			    $j('#searchReviewLayer').css({'top': '100px'});
			}
			var window_height = $j(document).height();
			$j('#searchOpacityLayer').css({'background' : '#000' , 'opacity' : '0.4' , 'z-index' : '9999' , 'display' : 'block' , 'height' : window_height  ,'position':'fixed', 'left':0, 'bottom':0,'top':0,'right':0});
			$j('#searchReviewLayer').html(obj.html);
			$j('#searchReviewLayer').show();
		    }else if (obj.url !=null) {
			window.location = obj.url;
		    }
            },
            error: function(e){                        
            }
        });                
	}
    }
    function closeSearchLayer() { 
       $j('#searchOpacityLayer, #searchReviewLayer').hide();
       $j('#keywordSuggest').val('');
    } 
</script>
        
