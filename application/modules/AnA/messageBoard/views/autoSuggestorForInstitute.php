        <script>
            /*
             For Institute Suggestor
            */
            var autoSuggestorInstance = null;
            function initializeAutoSuggestorInstance(){
                /*if (window.addEventListener){
                    var ele = document.getElementById("keywordSuggest");
                    ele.addEventListener('keyup', handleInputKeys, false);
                } else if (window.attachEvent){
                    var ele = document.getElementById("keywordSuggest");
                    ele.attachEvent('onkeyup', handleInputKeys);
                }*/
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
                suggestionId = getTheSuggestionPlaceholder();
                if(instTitle.length>75){
                    instTitle = instTitle.substring(0,72)+'...';
                }
                $('suggestion'+suggestionId).innerHTML = '<div><a href="'+instURL+'" target="_blank">'+instTitle+'</a> &nbsp;<span>[ <b>x</b> <a onClick="hideInstituteSuggestion(\''+suggestionId+'\')" href="javascript:void(0)">remove</a> ]</span></div>';
                $('suggestion'+suggestionId).style.display = 'block';
                
                //Make the value in Auto suggestor as Blank
                $('keywordSuggest').value = '';
                
                //Also, check if the number of suggestions has reached 3, hide the auto suggestor
                if(shouldWeHideSuggestionBox()){
                    $('anaAutoSuggestor').style.display = 'none';
                }
                
                //Also, store this new Institute Id in a hidden variable which can be submitted with the form
                suggestionsArray[suggestionId] = instId;
                FillHiddenVariable();
                tempInstituteField = '';
                
                reevaluateHolderHeight();
            }
            
            var suggestionsArray = new Array();
            function hideInstituteSuggestion(suggestionId){
                $('suggestion'+suggestionId).innerHTML = '';
                $('suggestion'+suggestionId).style.display = 'none';                
                $('anaAutoSuggestor').style.display = '';
                suggestionsArray[suggestionId] = '';
                FillHiddenVariable();
                reevaluateHolderHeight();
            }

            function getTheSuggestionPlaceholder(){
                for(i=1;i<=3;i++){
                    if($('suggestion'+i) && $('suggestion'+i).innerHTML == '')
                        return i;
                }
                return 0;
            }
            
            function shouldWeHideSuggestionBox(){
                if($('suggestion1').innerHTML != '' && $('suggestion2').innerHTML != '' && $('suggestion3').innerHTML != '')
                    return true;
                else
                    return false;
            }
            
            function FillHiddenVariable(){
                $('suggestedInstitutes').value = suggestionsArray.toString();
                if($('suggestedInstitutes'+globalThreadIdForSuggestions))
                    $('suggestedInstitutes'+globalThreadIdForSuggestions).value = $('suggestedInstitutes').value;
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
            
            function reevaluateHolderHeight(){
                var height = 100;
                if($('suggestion1').innerHTML != '') height +=28;
                if($('suggestion2').innerHTML != '') height +=28;
                if($('suggestion3').innerHTML != '') height +=28;
                if(height==184) height = 140;
                if($('suggestionHolder'+globalThreadIdForSuggestions))
                    $('suggestionHolder'+globalThreadIdForSuggestions).style.height = height+'px';                
            }

        </script>
        <?php if(isset($movable) && $movable=='true'){
           $style="position: absolute; top:0;left:0;";
           $width="width:515px;";
        }else{
            $width="width:96%";
        }?>
        <div id="autoSuggestorContent" style="<?=$style?>">
            <div class="suggest-course-cont" >
                    <p>&nbsp;You may suggest relevant institutes for the user (Optional)</p>
                    <ul>
                        <li id="suggestion1" style="display:none;"></li>
                        <li id="suggestion2" style="display:none;"></li>
                        <li id="suggestion3" style="display:none;"></li>
                    </ul>
                    <div class="fbkBx" id="anaAutoSuggestor" style="position: relative; <?=$width?>">
                        <input type="text" name="keywordSuggest" id="keywordSuggest" style="height:18px;width:97.5%;color:#959494" class="ftBx" autocomplete="off" default="Type Institute name..." value="Type Institute name..." onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" />
                        <div id="suggestions_container" style="position:absolute; left:7px; top:44px;background-color:#fff;"></div>
                    </div>
                    <div class="clearFix"></div>
                    <input type="hidden" name="suggestedInstitutes" id="suggestedInstitutes" value="" />
            </div>
        </div>
