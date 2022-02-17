        <script>
            /*
             For Institute Suggestor
            */
            var autoSuggestorInstance = null;
            function initializeAutoSuggestorInstance(){
		if (window.addEventListener){
                    var ele = document.getElementById("keywordSuggest");
                    ele.addEventListener('keyup', handleInputKeysForInstituteSuggestor, false);
                } else if (window.attachEvent){
                    var ele = document.getElementById("keywordSuggest");
                    ele.attachEvent('onkeyup', handleInputKeysForInstituteSuggestor);
                }
                try{
                autoSuggestorInstance = new AutoSuggestor("keywordSuggest" , "suggestions_container", false, 'institute');
                autoSuggestorInstance.callBackFunctionOnMouseClick = handleAutoSuggestorMouseClick;
                autoSuggestorInstance.callBackFunctionOnEnterPressed = handleAutoSuggestorEnterPressed;
                autoSuggestorInstance.callBackFunctionOnRightKeyPressed = handleAutoSuggestorRightKeyPressed;
                }catch(e){}
            }

    		
            
            function handleInputKeysForInstituteSuggestor(e){
                if(autoSuggestorInstance && $('keywordSuggest').hasFocus){
                    autoSuggestorInstance.handleInputKeys(e);
                }
            }

            function handleClickForAutoSuggestor(){
                if(autoSuggestorInstance){
                    autoSuggestorInstance.hideSuggestionContainer();
                }
            }
                       
            function handleAutoSuggestorMouseClick(callBackData){
                if(autoSuggestorInstance){
                    autoSuggestorInstance.hideSuggestionContainer();
                    showSelectedInstitute(callBackData['id'],callBackData['sp']);
                }
            }
            
            function handleAutoSuggestorRightKeyPressed(callBackData){
                //autoSuggestorInstance.hideSuggestionContainer(); 
            }
            
            function handleAutoSuggestorEnterPressed(callBackData){
                if(autoSuggestorInstance){
                    autoSuggestorInstance.hideSuggestionContainer(); 
                    showSelectedInstitute(callBackData['id'],callBackData['sp']);
                }
            }
            
            var tempInstituteField = '';
            function showSelectedInstitute(instId,instTitle){
                if(checkValidInstitute(instId,instTitle)){
                    tempInstituteField = instId;
                    //First, get the Institute Link from the Id by making an AJAX call.
                    var url = "/messageBoard/MsgBoard/getInstituteURL";
                    new Ajax.Request(url, { method:'post', parameters: ('instituteId='+instId), onSuccess:
                                    function(request){
                                        instURL = request.responseText;
                                        fillSugesstions(instId,instTitle,instURL);
                                    }
                    });
                }
            }
            
            function fillSugesstions(instId,instTitle,instURL){
                //After getting the institute link and title, show the same to the user with a remove link.
                
                //Make the value in Auto suggestor as Blank
                $('keywordSuggest').value = instTitle;
                
                //Also, store this new Institute Id in a hidden variable which can be submitted with the form
                $('suggestedInstitutes').value = instId;

                /*var url = "/CA/CampusAmbassador/getLocationForInstitute";
                new Ajax.Request(url, { method:'post', parameters: ('instituteId='+instId), onSuccess:
                                function(request){
                                   	 var result = request.responseText;
                                   	 console.log(results);
                                }
                });*/
		insertCampusAmbassabor();
                
            //    FillHiddenVariable();
             //   tempInstituteField = '';
                
               // reevaluateHolderHeight();
            }
            
            function checkValidInstitute(instituteId,title){
                if(instituteId<=0 || title=='')
                    return false;
                if(tempInstituteField == instituteId)
                    return false;
                selectedInstitutes = $('suggestedInstitutes').value;
                if(selectedInstitutes.indexOf(instituteId)>=0){
                    $('keywordSuggest').value='';
                    return false;
                }
                else
                    return true;
            }

        </script>
                    <div class="field-col" id="anaAutoSuggestor" style="position: relative;">
                        <input type="text" name="keywordSuggest" id="keywordSuggest"  class="universal-txt-field" autocomplete="off" default="Type Institute name..." value="Type Institute name..." onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" />
                        <div id="suggestions_container" style="position:absolute; left:7px; top:44px;background-color:#fff;"></div>
                    </div>
               
                    <input type="hidden" name="suggestedInstitutes" id="suggestedInstitutes" value="" />
     
