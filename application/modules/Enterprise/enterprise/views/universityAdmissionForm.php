
<p style="padding:5px 0px 10px;font-weight:600">UILP Child Pages</p>
	<div style="display:inline;position:relative;">
                <label>Listing Name </label>
				<input type="hidden" style="" id="listingId" name="listingId" value="<?=$_POST['listingId'];?>" />
				<input type="hidden" style="" id="listingName" name="listingName" value="<?=$_POST['listingName'];?>" />

				<input id="keywordSuggest" placeholder="Remove Listing ID to use Listing Name" type="text" class="collge-text" name="keyword" value="<?=$_POST['keyword']?>"  minlength="1" style="width:300px;padding:4px 10px;margin-left:10px"   /> 
                
                <label>Listing Id</label>
                <input id="listing_id" type="number" name="listing_id" value=""  style="width:300px;padding:4px 10px;margin-left:6px"   /> 

                <select id='widgetType' name="widgetType" style="
                    margin-left: 4px;
                    height: 30px;
                ">
                  <option value="admission_info">Admission</option>
                  <option value="cutoff_info">Cutoff</option>
                  <option value="placement_info">Placement</option>
                  <option value="acp_info">ACP</option>
                  <option value="bip_info">BIP</option>
                  <option value="sip_info">SIP</option>
                  <option value="icox_info">ICOX</option>

                </select>

					<a style="background: #ccc;cursor:pointer;text-decoration:none;width: 30px;height: 30px;padding: 6px;border-radius: 50%; font-size: 13px;margin-left: 7px;" onclick="loadAdmissionPostingView()">Go</a>
				<ul id="suggestions_container" class="suggestion-box" style="display: none;position:absolute;top:19px;width:301px;left:101px;z-index:100"></ul>
				
    </div>



    <div id="AdmissionInfo">
    		
    </div>
	<div class="clearFix"></div>
    

<script>
var mobileSearch = 'true';
    //var isMobile = true;
    function initializeUniversityAutoSuggestorInstances(){
        autoSuggestorInstanceArray.autoSuggestorUniversitySearchCMS.callBackFunctionOnMouseClick = handleUniversityAutoSuggestorMouseClick;
      
        autoSuggestorInstanceArray.autoSuggestorUniversitySearchCMS.callBackFunctionOnEnterPressed = handleUniversityAutoSuggestorMouseClick;
    
        autoSuggestorInstanceArray.autoSuggestorUniversitySearchCMS.callBackFunctionOnRightKeyPressed = handleUniversityAutoSuggestorRightKeyPressed;
        autoSuggestorInstanceArray.autoSuggestorUniversitySearchCMS.callBackFunctionOnTabPressed = handleUniversityAutoSuggestorTabPressed;
       
    }
    
    function handleClickForAutoSuggestorUniversity(obj, dict){
    	console.log(dict)
        if(autoSuggestorInstanceArray.autoSuggestorInstanceInstitute){ 
            autoSuggestorInstanceArray.autoSuggestorInstanceInstitute.hideSuggestionContainer();
        }
    }
               
    function handleUniversityAutoSuggestorMouseClick(callBackData){
    	console.log(callBackData);
    	if(callBackData && autoSuggestorInstanceArray.autoSuggestorUniversitySearchCMS){
    	    autoSuggestorInstanceArray.autoSuggestorUniversitySearchCMS.hideSuggestionContainer();	
    	    //$j('#listingId').val(callBackData['words_achieved_id']);
             universitySelected(callBackData['words_achieved_id'],callBackData['words_achieved']);
        }
    }
    
    function handleUniversityAutoSuggestorRightKeyPressed(callBackData){
        //autoSuggestorInstance.hideSuggestionContainer(); 
    }
    

    function universitySelected(instId,instTitle){
		document.getElementById('listingId').value = instId;
		document.getElementById('listingName').value = instTitle;
    }

    function handleUniversityAutoSuggestorTabPressed(e, autoSuggestorInstance) {
	autoSuggestorInstance.handleInputKeys(e);
	return false;
	}

window.onload=function(){
	if(typeof(initializeUniversityAutoSuggestorInstances) == "function") {
			initializeUniversityAutoSuggestorInstances(); //For initiating AutoSuggestor Instance
		}
};

</script>
