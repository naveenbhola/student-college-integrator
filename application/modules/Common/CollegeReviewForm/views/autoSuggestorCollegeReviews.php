
<script>
    /*
     For Institute Suggestor
    */

    var autoSuggestorInstance = null;
    var mobileSearch = 'true';
    
    // To identify the category id     
    var stream = '<?php echo $stream; ?>';
    var substream = '<?php echo $substream; ?>';
    var baseCourse = '<?php echo $baseCourse; ?>';
    var educationType = '<?php echo $educationType; ?>';

   
    
    var keywordEnteredByUserCompare = '';
    function handleInputKeysForInstituteSuggestorCompare(e){
        $j('#tileSuggested').slideDown(400);
        window.jQuery('#keywordSuggest').change(function(){
            keywordEnteredByUserCompare = $j('#keywordSuggest').val();        
        });
        
        if(autoSuggestorInstance ){
            autoSuggestorInstance.handleInputKeys(e);
        }
    }
    
    function handleAutoSuggestorAfterBuildingSuggestionContainer(dict){
	$j('#suggestions_container').removeClass('suggestion-box');
	if(dict["suggestion_count"] != undefined){
                getSuggestedSearch(parseInt(dict["suggestion_count"]));
        }
    }
    
    function handleClickForAutoSuggestorCompare(){
        if(autoSuggestorInstanceArray.autoSuggestorInstituteSearchReviewsCMS){ 
            autoSuggestorInstanceArray.autoSuggestorInstituteSearchReviewsCMS.hideSuggestionContainer();
        }
    }
               
    function handleAutoSuggestorMouseClickCompare(callBackData){
        if(autoSuggestorInstanceArray.autoSuggestorInstituteSearchReviewsCMS){
            //autoSuggestorInstance.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp'],callBackData['type']);
        }
    }
    
    function handleAutoSuggestorRightKeyPressedCompare(callBackData){
        //autoSuggestorInstance.hideSuggestionContainer(); 
    }
    
    function handleAutoSuggestorEnterPressedCompare(callBackData){
         if(autoSuggestorInstanceArray.autoSuggestorInstituteSearchReviewsCMS){
            //autoSuggestorInstance.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp'],callBackData['type']);
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
        $j.ajax({
            url: "/mCollegeReviews5/CollegeReviewsController/getSearchReview",
            type: "POST",
            data: {'instituteId':instId,'fromPage':'D_review','stream':stream,'substream':substream,'baseCourse':baseCourse,'educationType':educationType,'instType':instType},
            success: function(result)
            {
		    obj = JSON.parse(result);
                    if (obj.html !=null && obj.html !='' && obj.url == null) {
			if ($j('#suggestions_container') && $j('#suggestions_container').length>0) {
			    var setLayerPosition = $j('#suggestions_container').offset().top + 150;
			    $j('#searchReviewLayer').css({'top': setLayerPosition});
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
        
