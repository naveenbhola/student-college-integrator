
<script>
    /*
     For Institute Suggestor on Campus Connect HomePage Widget
    */

    var autoSuggestorInstance_CC = null;
    var mobileSearch_CC = 'true'; // this is used only for UI (if true then return in li, false then div)
    function initializeAutoSuggestorInstancesForCampusConnect(){
        
        if (window.addEventListener){ 
            var ele = document.getElementById("keywordSuggestCampusConnect"); 
            ele.addEventListener('keyup', handleInputKeysForInstituteSuggestorCompare_CC, false); 
        } else if (window.attachEvent){
            var ele = document.getElementById("keywordSuggestCampusConnect"); 
            ele.attachEvent('onkeyup', handleInputKeysForInstituteSuggestorCompare_CC); 
        }
        
        autoSuggestorInstance_CC = new AutoSuggestor("keywordSuggestCampusConnect" , "suggestions_containerCampusConnect", false, 'institute',8);
        
        autoSuggestorInstance_CC.callBackFunctionOnMouseClick = handleAutoSuggestorMouseClickCompare_CC;
      
        autoSuggestorInstance_CC.callBackFunctionOnEnterPressed = handleAutoSuggestorEnterPressedCompare_CC;
    
        autoSuggestorInstance_CC.callBackFunctionOnRightKeyPressed = handleAutoSuggestorRightKeyPressedCompare_CC;
	autoSuggestorInstance_CC.callBackFunctionAfterBuildingSuggestionContainer = handleAutoSuggestorAfterBuildingSuggestionContainer_CC;
	
	autoSuggestorInstance_CC.instituteSuggestionsIncludes = "cr";
       
    }
    
    var keywordEnteredByUserCompare_CC = '';
    function handleInputKeysForInstituteSuggestorCompare_CC(e){
        window.jQuery('#keywordSuggestCampusConnect').change(function(){
	    $j("#suggestions_containerCampusConnect").show();
            keywordEnteredByUserCompare_CC = $j('#keywordSuggestCampusConnect').val();        
        });
        
        // if(autoSuggestorInstance_CC ){
        //     autoSuggestorInstance_CC.handleInputKeys(e);
        // }
	$j('#suggestions_containerCampusConnect').removeClass('suggestion-box').addClass('colgrvwSugstr');
    }
    
    function handleAutoSuggestorAfterBuildingSuggestionContainer_CC(dict){
	$j('#suggestions_containerCampusConnect').removeClass('suggestion-box').addClass('colgrvwSugstr');
    }
    
    function handleClickForAutoSuggestorCompare_CC(){
        if(autoSuggestorInstance_CC){ 
            autoSuggestorInstance_CC.hideSuggestionContainer();
        }
    }
               
    function handleAutoSuggestorMouseClickCompare_CC(callBackData){
        if(autoSuggestorInstance_CC){
            //autoSuggestorInstance_CC.hideSuggestionContainer();
            instituteSelected_CC(callBackData['id'],callBackData['sp']);
        }
    }
    
    function handleAutoSuggestorRightKeyPressedCompare_CC(callBackData){
        //autoSuggestorInstance_CC.hideSuggestionContainer(); 
    }
    
    function handleAutoSuggestorEnterPressedCompare_CC(callBackData){
         if(autoSuggestorInstance_CC){
            //autoSuggestorInstance_CC.hideSuggestionContainer();
            instituteSelected_CC(callBackData['id'],callBackData['sp']);
        }
    }

    function instituteSelected_CC(instId,instTitle){
        var courseHomePageId = '<?php echo $courseHomePageId;?>';
	if(instId > 0){
        $j.ajax({
            url: "/CA/CampusConnectController/getCampusIntermediateUrl",
            type: "POST",
            data: {'instituteId':instId,'courseHomePageId':courseHomePageId, 'action':'CCHomeSearchTracking', 'search_string':keywordEnteredByUserCompare_CC, 'search_clicked':instTitle},
            success: function(result)
            {
                    obj = JSON.parse(result);
		    if (obj.url !='') {
			window.location = obj.url;
		    }
            },
            error: function(e){                        
            }
        });                
	}
    }
</script>