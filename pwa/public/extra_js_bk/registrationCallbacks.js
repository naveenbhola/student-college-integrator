/* Callback functions - start */
function callBackAfterRegn(){
  console.log('regn callback');
  window.location.reload();
}

function gaTrackEventCustom(){}

function callBackAfterResp(){
  console.log('resp callback');
  window.location.reload();
}

function finalButtonANAPostingCallback(response, callBackData){
  if(callBackData != '' && typeof(callBackData) == 'string'){
    callBackData = JSON.parse(callBackData);
  }

  if(document.querySelector('#instituteCoursesQP') != null && document.querySelector('#courseSelectionTab') != null){
    document.querySelector('#instituteCoursesQP').value = '';
  }

  document.querySelector("#qsn-prvw .opacticy-col").style.display = 'block';
  let action = callBackData.whichCase;
  let editEntityId = 0;

  if(action == "editTag"){
  }else if(action == "editing"){
  }else{
    entityText = document.querySelector("#"+callBackData.anaConfig.titleTextField).value.trim();
    entityDesc = document.querySelector("#"+callBackData.anaConfig.descriptionTextField).value.trim();
  }

  let tagsData = {"tags":[]};
  let entityType = callBackData.typeForQPost;

  let status, itemNew;
  document.querySelectorAll('#slctd-tags .choosen-tag').forEach(
    currObj => {
      status = currObj.getAttribute('status');
      if(status == "checked"){
        itemNew = {}
        itemNew ["tagId"] = currObj.getAttribute('tagId');
        itemNew ["classification"] = currObj.getAttribute('classification');
        itemNew ["conflicted"] = false;
        tagsData.tags.push(itemNew);
      }
    }
  );

  let tagsDataEncoded = JSON.stringify(tagsData);
  let trackingPageKeyId = document.querySelector('#quesDiscKeyId').value;

  let ajaxURL = callBackData.anaConfig.postingDomain + "/messageBoard/AnAPostDesktop/postingQuesDisc";
  let ajaxData = {};

  if((typeof(callBackData.courseId) != 'undefined' && callBackData.courseId > 0) || (typeof(callBackData.instituteId) != 'undefined'  && callBackData.instituteId > 0)){
    ajaxData = {'entityType' : entityType,'entityText' : entityText,'entityDesc' : entityDesc,'tagsData' : tagsDataEncoded, 'editEntityId' : editEntityId,'listingTypeId':callBackData.instituteId,'listingType':callBackData.listingType,'courseId':callBackData.courseId,'action':action,'trackingPageKeyId':trackingPageKeyId,'shiksha_auth_token':document.querySelector('#shiksha_auth_token').value};
  }else{
    ajaxData = {'entityType' : entityType,'entityText' : entityText,'entityDesc' : entityDesc,'tagsData' : tagsDataEncoded, 'editEntityId' : editEntityId,'action':action,'trackingPageKeyId':trackingPageKeyId,'entityId':callBackData.entityId,'tagEntityType':callBackData.tagEntityType,'shiksha_auth_token':document.querySelector('#shiksha_auth_token').value};
  }
  console.log('final ajax data', ajaxData);
  //no other option but to use ajax method here, will be removed when regn moved to PWA
  $j.ajax({
    async: true,
    type: "post",
    data: ajaxData,
    url : ajaxURL,
    success : function(response){
      window.location.href = response;
    }
  });
}

function placementCTA(){ 
    if(window.placementCTA()){
      window.placementCTA();
    }
} 



/* Callback functions - end */
