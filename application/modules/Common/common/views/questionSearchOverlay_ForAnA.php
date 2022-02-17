<div id="questionSearchOverlay_ForAnA" style="display:none;">
        <div id="questionMainTitlePU">&nbsp;</div>
        <div id="mainQuestionIdPU" style="display:none">&nbsp;</div>
        <div id="userIdPU" style="display:none">&nbsp;</div>
        <div id="owernIdPU" style="display:none">&nbsp;</div>
	<div id="entityType" style="display:none">&nbsp;</div>
        <div class="lineSpace_20">&nbsp;</div>
        <div><b>Search for a question:</b></div>
        <div class="lineSpace_5">&nbsp;</div>
        
        <div class="float_L pt5" style="width:100%">
        <div style="width:100%">
            <div class="float_L" style="width:100%" id="tempKeywordHolder">
                <div style="width:100%">
                    <div style="margin-right:10px">
                        <div class="" style="width:100%">
                            <div class="homeShik_textBoxBorder">
                                <input type="text" name="questionSearchText" id="questionSearchText" style="width: 88%; color: rgb(173, 166, 173); font-size:12px;" class="homeShik_searchtextBox" autocomplete="off" value="Find Question by Name or URL or Question-Id" default="Find Question by Name or URL or Question-Id" onblur="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" value="<?php if(isset($_COOKIE['searchText']) && isset($_REQUEST['q'])) {echo htmlspecialchars($_COOKIE['searchText']);}else { echo 'Find Question by Name or URL or Question-Id';}?>" onkeypress="return runScript(event,document.getElementById('questionSearchText').value);">&nbsp;<input type="Submit" value="Search" onclick="getQuestionDataFromGoogleAjax(document.getElementById('questionSearchText').value);" class="fbBtn" id="submitButtonreportAbuse">
                            </div>
                            <div class="autosuggest_container" id="output_container" style="position: absolute; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; border-top-color: rgb(104, 148, 203); border-right-color: rgb(104, 148, 203); border-bottom-color: rgb(104, 148, 203); border-left-color: rgb(104, 148, 203); top: 240px; left: 525px; width: 468px; visibility: hidden; z-index: 501; "></div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" value="Enter Location" default="Enter Location" id="templocation">
        </div>
    </div>


    <div class="lineSpace_10 clear_B">&nbsp;</div>
    <div style="border-bottom:1px solid #eaeeed;"></div>

<div id="firstTimeResult">
<?php 
if($typeOfSearch == 'QER')
	echo $this->load->view('/common/qerRelatedSearchQuestionPart');
else
	echo $this->load->view('/common/googleRelatedSearchQuestionPart');
?>
</div>
</div>
<script>
//alert(getCookie('userId'));
var questionLinkedGoogleSearchId = '';
function selectedQuestionId(stringurl,id,totalCount){
var tmpArr = stringurl.split('/');
if( $j.inArray('getTopicDetail', tmpArr) === -1 ){
    tmp = stringurl.split('-');
    countTmp = tmp.length;
    tmpArr[4] = tmp[countTmp-1];
}

questionLinkedGoogleSearchId = tmpArr[4];
for(i=0;i<totalCount;i++){
    if(id==i){
        document.getElementById('id'+id).style.backgroundColor='#F1F8FF';
        if(jQuery('#id'+id).find(".relatedQuestionSearchRadioBtn").length >0)
            jQuery('#id'+id).find(".relatedQuestionSearchRadioBtn").attr("checked","checked");
    }
    else{
        document.getElementById('id'+i).style.backgroundColor='#FFFFFF';
    }
}
}
function runScript(e,val1,val2) {
    if (e.keyCode == 13) {
        getQuestionDataFromGoogleAjax(val1,val2);
        return false;
    }
}
</script>

