<div id="discussionSearchOverlay_ForAnA" style="display:none;">
    <div id="discussionMainTitle">&nbsp;</div>
    <div id="mainDiscussionIdPU" style="display:none">&nbsp;</div>
    <div id="userIdPU" style="display:none">&nbsp;</div>
    <div id="owernIdPU" style="display:none">&nbsp;</div>
    <div id="entityType" style="display:none">&nbsp;</div>
    <div class="lineSpace_10">&nbsp;</div>
    <div style="border-bottom:1px solid #eaeeed;"></div>
    <div class="bld mt10">Search for a discussion:</div>
    <div class="lineSpace_5">&nbsp;</div>
    <!-- <div style="height:33px;overflow:hidden">
         <div class="ana_sOBx float_L" style="width:330px">
             <div class="ana_sIBx">
                 <input type="text" name="discussionSearchText" class="ana_sIpt" style="position:relative;top:3px; color: rgb(173, 166, 173);" id="discussionSearchText" onblur="checkTextElementOnTransition(this,'blur');getDiscussionDataFromAjax(this.value);" onfocus="checkTextElementOnTransition(this,'focus')" default="Find Discussion Name or Url" value="<?php if(isset($_COOKIE['searchText']) && isset($_REQUEST['q'])) {echo htmlspecialchars($_COOKIE['searchText']);}else { echo 'Find Discussion Name or Url';}?>"/>
             </div>
         </div>
     </div>-->
    <div class="float_L pt5" style="width:100%">
        <div style="width:100%">
            <div class="float_L" style="width:100%" id="tempKeywordHolder">
                <div style="width:100%">
                    <div style="margin-right:10px">
                        <div class="" style="width:100%">
                            <div class="homeShik_textBoxBorder">
                                <input type="text" name="discussionSearchText" id="discussionSearchText" style="width: 88%; color: rgb(173, 166, 173); font-size:12px;" class="homeShik_searchtextBox" autocomplete="off" value="Find Discussion by Name or URL" default="Find Discussion by Name or URL" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur');" value="<?php if(isset($_COOKIE['searchText']) && isset($_REQUEST['q'])) {echo htmlspecialchars($_COOKIE['searchText']);}else { echo 'Find Discussion by Name or URL';}?>" onkeypress="return runScript(event,document.getElementById('discussionSearchText').value,document.getElementById('mainDiscussionIdPU').innerHTML);">&nbsp;<input type="Submit" value="Search"  onclick="getDiscussionDataFromAjax(document.getElementById('discussionSearchText').value,document.getElementById('mainDiscussionIdPU').innerHTML);" class="fbBtn" id="submitButtonreportAbuse">
                            </div>
                            <div class="autosuggest_container" id="output_container" style="position: absolute; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; border-top-color: rgb(104, 148, 203); border-right-color: rgb(104, 148, 203); border-bottom-color: rgb(104, 148, 203); border-left-color: rgb(104, 148, 203); top: 240px; left: 525px; width: 468px; visibility: hidden; z-index: 501; "></div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" value="Enter Location" default="Enter Location" id="templocation">
        </div>
    </div>

    <div class="lineSpace_5 clear_B">&nbsp;</div>
    <div>
	<input type="radio" value="1" name="searchType" id="searchType1" checked>Search Entire Words</input>
	<input type="radio" value="2" name="searchType" id="searchType2">Search Partial Words</input>
    </div>
    <div class="lineSpace_5">&nbsp;</div>
    <div style="border-bottom:1px solid #eaeeed;"></div>
    <div id="firstTimeResult">
        <?php
        echo $this->load->view('/common/relatedSearchDiscussionPart');

        ?>

    </div>

</div>

<script>
var questionLinkedGoogleSearchId = '';
function selectedDiscussionId(stringurl,id,totalCount){
var tmpArr = stringurl.split('/');
if( $j.inArray('getTopicDetail', tmpArr) === -1){
    tmp = stringurl.split('-');
    countTmp = tmp.length;
    tmpArr[4] = tmp[countTmp-1];
}
questionLinkedGoogleSearchId = tmpArr[4];
for(i=0;i<totalCount;i++){
    if(id==i){
        document.getElementById('id'+id).style.backgroundColor='#F1F8FF';
    }
    else{
        document.getElementById('id'+i).style.backgroundColor='#FFFFFF';
    }
}
}
function runScript(e,val1,val2) { 
    if (e.keyCode == 13) {
        getDiscussionDataFromAjax(val1,val2);
        return false;
    }
}
</script>
