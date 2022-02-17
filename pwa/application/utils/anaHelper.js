import React from 'react';
import {triggerEvent, extend, toggle, isUserLoggedIn, getCookie} from './commonHelper';
import {validateAnAField, validateAnAPostTextCustomValidations, validateAnAQuesCourse} from './anaValidationHelper';
import {postRequest} from './ApiCalls';
import config from './../../config/config';
import {showResponseFormWrapper, showRegistrationFormWrapper} from './regnHelper';
import {tagAutosuggestorConfig} from './autosuggestorConfig';
import {initializeAutoSuggestorInstanceSearchV2} from './searchHelper';

let supportsKeys = false;
let whichCase = "posting";
let alreadyIntitialized = 0;
let typeForQPost = '', actualType = '';
//let removedTagsFromAddMoreLayer = [];
let params = {
                titleTextField       : "qstn-input" ,
                descriptionTextField : "more-input-posting",
                postingForPage       : "anaHomePage",
                postingType          : "default",
                courseField          : "instituteCoursesQP",
                instituteField       : "instituteIdQP"
              };

export function initANALayer(extraParams){
  params = extend(params, extraParams);
  params.postingDomain = config().SHIKSHA_HOME;
}

export function openQuestionPostingLayer(){
  if(document.querySelector('#askCourseSelected') != null){
    document.querySelector('#askCourseSelected').value = '';
  }
  openLyeredQDPosting();
}

function openLyeredQDPosting(titleText, descriptionText){
  //document.querySelector("#tags-layer").style.display = 'block';
  //document.querySelector("#an-layer").style.display = 'block';

  //document.querySelector('html').style.overflow = 'hidden';
  //document.querySelector('#tags-head-post-q').style.display = 'block';

  if(typeof(titleText) != "undefined" && titleText != ""){
    document.querySelector("#"+params.titleTextField).value = titleText;
  }

  if(typeof(descriptionText) != "undefined" && descriptionText != ""){
    document.querySelector("#"+params.descriptionTextField).value = descriptionText;
  }

  setTimeout(function(){
    document.getElementById(params.titleTextField).focus();
    triggerEvent(document.getElementById(params.titleTextField), 'keyup');
    triggerEvent(document.getElementById(params.descriptionTextField), 'keyup');
  },100);
}

export function handleQuestionFieldKeyUp(event){
  let thisObj = event.target;
  autoGrowField(thisObj,200);
  textKey(thisObj);
}
export function handleQuestionFieldFocus(type, event){
  typeForQPost = type;
  document.querySelector("#more-qstns-posting").style.display = 'block';
  var descriptionText  = document.querySelector("#"+params.descriptionTextField).value.trim();

  if(typeForQPost == "discussion"){
    onclickHandlerForAddLink(null, 'normal', true);
    document.querySelector("#plus-minus-icon").style.display = 'none';
    document.querySelector("#lnk-add-more").style.paddingLeft = "2px";
  }else if(descriptionText != ""){
    onclickHandlerForAddLink(null, 'normal');
    document.querySelector("#plus-minus-icon").style.display = 'block';
    document.querySelector("#lnk-add-more").style.paddingLeft = "20px";
  }else{
    document.querySelector("#plus-minus-icon").style.display = 'block';
    document.querySelector("#lnk-add-more").style.paddingLeft = "20px";
  }
}

export function handleQuestionDescFieldKeyPress(event){
  handleCharacterInTextField(event);
}

export function handleQuestionDescFieldKeyUp(event){
  let thisObj = event.target;
  autoGrowField(thisObj,300);
  textKey(thisObj);
}

export function onclickHandlerForAddLink(event, state, isDirectCall){
  if(typeof(isDirectCall) == "undefined"){
    isDirectCall = false;
  }

  if(typeForQPost == "discussion" && !isDirectCall)
      return;

  if(typeof(state) == "undefined"){
      state = "toggle";
  }
  let nextState = document.querySelector("#plus-minus-icon").getAttribute('nextClass');
  if(nextState == "after" ){
    document.querySelector("#"+params.descriptionTextField).style.display = 'block';
    document.querySelector("#more-ques-posting").style.display = 'block';
    document.querySelector("#plus-minus-icon").classList.remove('before');
    document.querySelector("#plus-minus-icon").classList.add('after');
    document.querySelector("#plus-minus-icon").setAttribute('nextClass', 'before');
  }
  else if(nextState == "before" && state == "toggle"){
    document.querySelector("#more-ques-posting").style.display = 'none';
    document.querySelector("#plus-minus-icon").classList.remove('after');
    document.querySelector("#plus-minus-icon").classList.add('before');
    document.querySelector("#plus-minus-icon").setAttribute('nextClass','after');
  }
}

export function bindElementsAndInitialize(pageType){
  document.querySelector("#"+params.titleTextField).value = '';
  document.querySelector("#"+params.descriptionTextField).value = '';
  document.querySelector('#nextButtonPosting').addEventListener('click', handleNextBtnClick);
  if(pageType == "discussion"){
    typeForQPost = "discussion";
    actualType = "discussion";
  }else{
    typeForQPost = "question";
    actualType = "question";
  }
  document.addEventListener('click', handleDocumentClick);

  document.querySelector('#'+params.titleTextField).addEventListener('keyup', handleQuestionFieldKeyUp);
  document.querySelector('#'+params.titleTextField).addEventListener('focus', handleQuestionFieldFocus( pageType));
  document.querySelector('#more-input-posting').addEventListener('keyup', handleQuestionDescFieldKeyUp);
  document.querySelector('#more-input-posting').addEventListener('keypress', handleQuestionDescFieldKeyPress);
  document.querySelector('#lnk-add-more').addEventListener('click', onclickHandlerForAddLink);
  document.querySelector('#cancelButtonSecondLayer').addEventListener('click', handleCancelButtonSecondLayer);
  document.querySelector('#finalButtonPosting').addEventListener('click', handleFinalButtonPosting);
  document.querySelector('#edit-qstn').addEventListener('click', editQuestionWhilePosting);

  if(document.querySelector('#askCourseSelected') != null){
    document.querySelector('#askCourseSelected').addEventListener('focus', showAskCoursesDropdown);
    document.querySelector('#askCourseSelected').addEventListener('keyup', inputSearchList);
  }
}

function handleDocumentClick(e){
  if(e.target == document.querySelector("#askCourseSelected")){
    return;
  }else{
    if(document.querySelector('#ask_courses') != null){
      document.querySelector('#ask_courses').style.display = 'none';
      document.querySelector("#more-qstns-posting").style.overflow = 'hidden';
    }
  }
}

export function showAskCoursesDropdown(){
  document.querySelector("#ask_courses").style.display = 'block';
  document.querySelector('#mainOvLst li').style.display = 'block';
  if(document.querySelector('#ncf') != null){
    document.querySelector('#ncf').parentNode.removeChild(document.querySelector('#ncf'));
  }
  //updateTinyCourseSelection('courseSelectionTiny');
  if(document.querySelector("#ask_courses").style.display != 'none'){
       document.querySelector('#more-qstns-posting').style.overflow = '';
  }else{
       document.querySelector("#more-qstns-posting").style.overflow = 'hidden';
  }
}

function updateTinyCourseSelection(){}

function inputSearchList(){
  let input, filter, ul, li, a, i;
  input = document.getElementById("askCourseSelected");
  filter = input.value.toUpperCase().trim();
  ul = document.getElementById("cLst");
  li = ul.getElementsByTagName("li");
  document.querySelector('.box-dwn').style.display = 'block';
  if(document.querySelector('#ncf') != null){
    document.querySelector('#ncf').parentNode.removeChild(document.querySelector('#ncf'));
  }
  for (i = 0; i < li.length; i++) {
      a = li[i].getElementsByTagName("a")[0];
      if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
          li[i].style.display = "";
      } else {
          li[i].style.display = "none";
      }
  }
  let courseCount = document.querySelectorAll('#cLst li').length;
  let courseHidden = 0;
  document.querySelectorAll('#cLst li').forEach(
    currObj => {
      if(currObj.style.display == 'none'){
        courseHidden++;
      }
    }
  );
  if(courseHidden == courseCount){
    let ncfNode = document.createElement("li");
    ncfNode.className = 'course-li';
    ncfNode.id = 'ncf';
    ncfNode.style.marginTop = '5px';
    ncfNode.style.marginLeft = '9px';

    let textNode = document.createTextNode("Sorry, no courses found.");
    ncfNode.appendChild(textNode);
    document.querySelector('#mainOvLst').appendChild(ncfNode);
  }
}

export function askCourseSelection(courseId, courseName){
  document.querySelector('#instituteCoursesQP').value = courseId;
  document.querySelector('#askCourseSelected').value = courseName;
}

function editQuestionWhilePosting(){
  document.querySelector("#qsn-post").style.display = 'block';
  document.querySelector("#qsn-prvw").style.display = 'none';
}

function handleFinalButtonPosting(){
  let trackingPageKeyId = document.querySelector('#quesDiscKeyId').value;
  let courseId = 0;
  let entityId   = 0;
  let examResponseId = 0;
  let tagEntityType = '';
  let instituteId = 0;
  let listingType = '', actionType = '';

  if(params.courseField != "" && document.querySelector("#"+params.courseField) != null){
    courseId = document.querySelector("#"+params.courseField).value;
    actionType = document.querySelector('#responseActionTypeQP').value;
  }
  if(params.instituteField != '' && document.querySelector("#"+params.instituteField) != null){
    instituteId = document.querySelector("#"+params.instituteField).value;
    listingType = 'institute';
  }

  if(document.querySelector('#entityId') != null && document.querySelector('#entityId').value != ''){
    entityId = document.querySelector('#entityId').value.trim();
  }
  if(document.querySelector('#examResponseId') != null && document.querySelector('#examResponseId').value != ''){
    examResponseId = document.querySelector('#examResponseId').value.trim();
  }

  if(document.querySelector('#tagEntityType') != null && document.querySelector('#tagEntityType').value!=''){
    tagEntityType = document.querySelector('#tagEntityType').value.trim();
  }
  let isCreateResponse = (courseId>0)?true:false;

  let callbackData = {};

  if(courseId == 0 && instituteId == 0){
      callbackData =  {'trackingPageKeyId':trackingPageKeyId, 'entityId':entityId, 'tagEntityType':tagEntityType, 'anaConfig':params, 'typeForQPost':typeForQPost, 'whichCase':whichCase};
  }else{
      callbackData = {'trackingPageKeyId':trackingPageKeyId, 'courseId' : courseId ,'instituteId': instituteId , 'listingType':listingType, 'actionType' : actionType, 'isCreateResponse':isCreateResponse, 'cta':'ask', 'anaConfig':params, 'typeForQPost':typeForQPost, 'whichCase':whichCase};
  }
  if(examResponseId > 0){
    callbackData =  {'trackingPageKeyId':trackingPageKeyId, 'entityId':entityId, 'tagEntityType':tagEntityType, 'anaConfig':params, 'typeForQPost':typeForQPost, 'whichCase':whichCase, isCreateResponse : true, 'listingId' : examResponseId, listingType : 'exam', actionType:'exam_ask_question'};
  }
  prepareRegistrationForm(callbackData, tagEntityType);
}

function prepareRegistrationForm(callbackData, formType){
  if(typeof(formType) == 'undefined' || formType == ''){
    formType = 'default';
  }
  switch(formType){
    case 'Exams':
      //examRegisterUserCallWrapAnA("finalButtonPostingCallback",callbackData);
      registerUserCallWrapAnA("finalButtonANAPostingCallback", callbackData);
      break;
    default:
      registerUserCallWrapAnA("finalButtonANAPostingCallback", callbackData);
  }
}

function registerUserCallWrapAnA(callbackFunction, callBackData){
  if(typeof registrationIdentifier === 'undefined'){
	    let registrationIdentifier = '';
	}
  let listingType = 'course';
  if(typeof callBackData.listingType !== 'undefined' && callBackData.listingType !== ''){
    listingType = callBackData.listingType;
  }

  let formData = {
    'trackingKeyId': callBackData.trackingPageKeyId,
    'cta':callBackData.cta,
    'callbackFunction': callbackFunction,
    'callbackFunctionParams':callBackData
  };

  let createResponse = false;
  if(typeof(callBackData.isCreateResponse) != "undefined" && callBackData.isCreateResponse == true){
  	createResponse = true;
  }

  if(createResponse){
    if(listingType === 'exam'){
      showResponseFormWrapper(callBackData.listingId, callBackData.actionType, listingType, formData);
    }else{
      showResponseFormWrapper(callBackData.courseId, callBackData.actionType, listingType, formData);
    }
  }else{
    if(!isUserLoggedIn() && getCookie('user').trim() == ''){
  		//registrationForm.showRegistrationForm(formdata);
      showRegistrationFormWrapper(formData);
		}
		else{
	    callBackData.userAlreadyLoggedIn = true;
	    window[callbackFunction]({}, callBackData);
		}
  }
}

function handleCancelButtonSecondLayer(){
  //clickHandlerForCancelSecLayer();
}

function clickHandlerForCancelSecLayer(){
  document.querySelector("#qsn-prvw").style.display = 'none';

  document.querySelector("#qsn-post").style.display = 'block';
  //document.querySelector("#tags-head-post-q").style.display = 'block';
  resetQuestionDiscPostngLayer();
}

function handleNextBtnClick() {
  let result = true;
  result = validateAnAField(document.querySelector("#"+params.titleTextField));
  if(result && window.getComputedStyle(document.querySelector('#more-ques-posting')).display != 'none'){
    result = validateAnAField(document.querySelector("#"+params.descriptionTextField));
  }
  if(result){
    var titleVal = document.querySelector("#"+params.titleTextField).value.trim();
    var descVal = document.querySelector("#"+params.descriptionTextField).value.trim();

    var caption = "";
    if(typeForQPost == "question"){
        caption = "Question";
    } else {
        caption = "Discussion";
    }
    var courseErrFlag = validateAnAQuesCourse();
    if(document.querySelector('#askCourseSelected') != null && courseErrFlag == false){
      document.querySelector('#cLst_error').style.display = 'none';
      return;
    }

    result = validateAnAPostTextCustomValidations(true, true, titleVal, caption);
    if(result == true){
        result = validateAnAPostTextCustomValidations(true, true, descVal, caption);
        if(result == true){
              document.querySelectorAll("#qsn-post .opacticy-col").forEach(
                currObj => currObj.style.display = 'block'
              );

              var title = titleVal;
              var entityDesc = descVal;
              var entityThreadId = 0; // Handle this in case of edit question
              var action = whichCase;

              if(action == "editing" || action == "editTag"){
                  entityThreadId = document.querySelector("#threadId").value;
              }

              let type = typeForQPost;

              document.querySelector("#qstn-title-posting span").innerHTML = title;

              // Tags Ajax
              let ajaxURL = config().SHIKSHA_HOME+"/messageBoard/AnAPostDesktop/postingIntermediatePageTagsData";
              let ajaxData = {'entityType' : type,'entityText' : title,'entityDesc' : entityDesc,'editEntityId' : entityThreadId,'action' :action};
              let axiosConfig = {
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                withCredentials: false
              }
              postRequest(ajaxURL, ajaxData, 'desktop', axiosConfig).then((res) => {
                tagsForIntermediatePageSuccess(res.data);
                if(action == "posting" && typeForQPost == 'question'){
                    // Similar Questions Ajax
                    document.querySelector("#similar_ques_outer").style.display = 'block';
                    ajaxURL = config().SHIKSHA_HOME+"/messageBoard/AnAPostDesktop/postingIntermediatePageSimilarQuestionsData";
                    ajaxData = {'entityType' : type,'entityText' : title,'shiksha_auth_token':document.querySelector('#shiksha_auth_token').value};
                    postRequest(ajaxURL, ajaxData, 'desktop', axiosConfig).then((similarRes) => {
                      similarQuestionIntermediatePageSuccess(similarRes.data);
                    });
                }else{
                    document.querySelector("#similar_ques_outer").style.display = 'none';
                }
              });
        }else{
          var descErrDiv = document.querySelector("#"+params.descriptionTextField+"_error");
          descErrDiv.innerHTML = result;
          descErrDiv.style.display = 'block';
          descErrDiv.parentElement.style.display = 'block';
        }
    }else{
        var textErrDiv = document.querySelector("#"+params.titleTextField+"_error");
        textErrDiv.innerHTML = result;
        textErrDiv.style.display = 'block';
        textErrDiv.parentElement.style.display = 'block';
    }
  }
}

function similarQuestionIntermediatePageSuccess(response){
  document.querySelector("#similar_ques_outer").innerHTML = response;
}

function addMoreTagsLinkClick(){
  document.querySelector("#posting-layer").style.display = 'block';
  //document.querySelector("#tags-layer").style.display = 'block';

  if(params.postingType == "layer"){
    document.querySelector("#qsn-prvw").style.display = 'none';
    //document.querySelector("#tags-head-post-q").style.display = 'none';
  }
  let tagHtml = "";
  document.querySelectorAll("#slctd-tags .choosen-tag").forEach(
    currObj => {
      let status = currObj.getAttribute('status');
      if(status == 'checked'){
        let tagId = currObj.getAttribute('tagId');
        let tagName = currObj.text;
        let classificType = currObj.getAttribute('classification');
        if(classificType == 'undefined' || classificType.trim() == ''){
          classificType = 'manual';
        }
        tagHtml += "<a class='choosen-tag' status='checked' href='javascript:void(0);' tagId ='"+tagId+"' classification='"+classificType+"'><span><i></i></span><span class='i-s'>"+tagName+"</span></a>";
      }
    }
  );
  document.querySelector("#tags-selctd-links").innerHTML = tagHtml;
  setTimeout(function(){
    document.querySelectorAll('#tags-selctd-links .choosen-tag').forEach(
      currObj => {
        currObj.addEventListener('click', (event)=>{
          removedTagsFromAddMoreLayer.push(currObj.getAttribute('tagId'));
          currObj.parentNode.removeChild(currObj);
        })
      }
    );
  }, 200);
}

function tagsForIntermediatePageSuccess(response){
  document.querySelector("#qsn-post").style.display = 'none';
  document.querySelector("#qsn-post .opacticy-col").style.display = 'none';
  document.querySelector("#qsn-prvw .opacticy-col").style.display = 'none';

  if(whichCase == 'editTag'){
    document.querySelector("#edit-qstn").style.display = 'block';
  }
  document.querySelector("#qsn-prvw").style.display = 'block';
  //document.querySelector("#tags-head-post-q").style.display = 'block';
  document.querySelector("#slctd-tags").innerHTML = response;
  attachClickHandlerToTags();
  document.querySelector('#add-more-tag').addEventListener('click', addMoreTagsLinkClick);
  if(alreadyIntitialized == 0){
    alreadyIntitialized = 1;
    let ajaxURL = config().SHIKSHA_HOME+"/messageBoard/AnAPostDesktop/fetchAddMoreLayer";
    let ajaxData = {'entityType' : typeForQPost};
    let axiosConfig = {
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      withCredentials: false
    }
    postRequest(ajaxURL, ajaxData, 'desktop', axiosConfig).then((moreTagResponse) => {
      initializeAddMoreTags(moreTagResponse.data);
    });
  }else{
    if(document.querySelector("#add_tags_layer_head") != null){
      document.querySelector("#add_tags_layer_head").innerHTML = typeForQPost.charAt(0).toUpperCase() + typeForQPost.slice(1);
    }
  }
}

function initializeAddMoreTags(response){
  document.querySelector('#addMoreTagsLayer').innerHTML = response;
  document.querySelector('#cls-add-tags').addEventListener('click', closeAddMoreTagsLayer);
  document.querySelector('#ext-add-tags').addEventListener('click', closeAddMoreTagsLayer);
  if(params.postingType == 'default'){
    document.querySelector('#tags-layer').addEventListener('click', closeAddMoreTagsLayer);
  }
  document.querySelector('#done-add-tags').addEventListener('click', doneAddTagsClick);
  initializeAutoSuggestorInstanceSearchV2(tagAutosuggestorConfig.options);
}

function closeAddMoreTagsLayer(){
  document.querySelector("#posting-layer").style.display = 'none';

  if(params.postingType == 'default'){
        document.querySelector("#tags-layer").style.display = 'none';
  }else{
        document.querySelector("#qsn-prvw").style.display = 'block';
        //document.querySelector("#tags-head-post-q").style.display = 'block';
  }

  document.querySelector("#tagSearch_error").style.display = 'none';
  document.querySelector("#tagSearch").value = "";
}

function doneAddTagsClick(){
  closeAddMoreTagsLayer();
  let SelectedTagsArray = [], PreviousTagsArray = [];
  let item = {};
  document.querySelectorAll("#tags-selctd-links .choosen-tag").forEach(
    currObj => {
      item = {};
      item['tagId'] = currObj.getAttribute('tagId');
      item['tagName'] = currObj.textContent;
      item['tagType'] = currObj.getAttribute('classification');
      SelectedTagsArray.push(item);
    }
  );
  document.querySelectorAll("#slctd-tags .choosen-tag").forEach(
    currObj => PreviousTagsArray.push(currObj.getAttribute('tagId'))
  );
  //UnSelect The removed Tags If Present
  for (var i = 0; i < removedTagsFromAddMoreLayer.length; i++) {
      obj = document.querySelector('#selected-tags .choosen-tag[tagId="'+removedTagsFromAddMoreLayer[i]+'"]');
      if(obj != null && obj.length != 0){
          obj.setAttribute('status','unchecked');
          obj.classList.add('un-tag');
      }
  }

  // Finally Select / Add the tags present
  for (var i = 0; i < SelectedTagsArray.length; i++) {
    var obj = document.querySelector('#selected-tags .choosen-tag[tagId="'+SelectedTagsArray[i].tagId+'"]');
    if(obj != null && obj.length != 0){
      obj.setAttribute('status','checked');
      obj.classList.remove('un-tag');
    }else {
      let classification = "manual";

      if(typeof SelectedTagsArray[i].tagType  != 'undefined' && SelectedTagsArray[i].tagType.trim() != ''){
        classification = SelectedTagsArray[i].tagType;
      }
      //tagHtml = "<a class='choosen-tag' status='checked' href='javascript:void(0);' tagId ='"+SelectedTagsArray[i].tagId+"' classification='"+classification+"'><span><i></i></span><span class='i-s'>"+SelectedTagsArray[i].tagName+"</span></a>";
      let aNode = document.createElement("a");
      aNode.className = 'choosen-tag';
      aNode.href = 'javascript:void(0);';
      //aNode.status = 'checked';
      //aNode.tagId= SelectedTagsArray[i].tagId;
      //aNode.classification = classification;

      let att = document.createAttribute("status");
      att.value = 'checked';
      aNode.setAttributeNode(att);

      att = document.createAttribute("tagId");
      att.value = SelectedTagsArray[i].tagId;
      aNode.setAttributeNode(att);

      att = document.createAttribute("classification");
      att.value = classification;
      aNode.setAttributeNode(att);
      aNode.innerHTML = "<span><i></i></span><span class='i-s'>"+SelectedTagsArray[i].tagName+"</span>";
      //let textNode = document.createTextNode("");
      //aNode.appendChild(textNode);
      document.querySelector("#selected-tags").appendChild(aNode);
    }
  }
  removedTagsFromAddMoreLayer = [];
  attachClickHandlerToTags();
}

function attachClickHandlerToTags(){
  if(document.querySelector("#slctd-tags .choosen-tag") != null){
    document.querySelectorAll("#slctd-tags .choosen-tag").forEach(
      currObj => currObj.addEventListener('click', clickHandlerForTags)
    );
  }
}

function clickHandlerForTags(e){
  let object = e.currentTarget;
  let status = object.getAttribute('status');
  //GA track more code here
  if(status == "checked"){
    object.classList.add('un-tag');
    object.setAttribute('status','unchecked');
  }
  else if(status == "unchecked"){
    object.classList.remove('un-tag');
    object.setAttribute('status','checked');
  }
}

function resetQuestionDiscPostngLayer() {
  document.querySelector("#more-ques-posting").style.display = 'none';
  document.querySelector("#more-qstns-posting").style.display = 'none';
  document.querySelector("#plus-minus-icon").classList.remove('after');
  document.querySelector("#plus-minus-icon").classList.add('before');
  document.querySelector("#plus-minus-icon").setAttribute('nextClass','after');

  // Hide Error Messages
  var textErrDiv = document.querySelector("#"+params.titleTextField+"_error");
  textErrDiv.style.display = 'none';
  textErrDiv.parentElement.style.display = 'none';

  var descErrDiv = document.querySelector("#"+params.descriptionTextField+"_error");
  descErrDiv.style.display = 'none';
  descErrDiv.parentElement.style.display = 'none';

  var textField = document.querySelector("#"+params.titleTextField);
  textField.value = "";
  triggerEvent(textField, 'keyup');

  var descField = document.querySelector("#"+params.descriptionTextField);
  descField.value = "";
  triggerEvent(descField, 'keyup');

  textField.style.height = '40px';
  descField.style.height = '120px';

  if(document.querySelector('#courseSelectionTab') != null){
      document.querySelector("#"+params.courseField).value = '';
  }

  if(document.querySelector("#askCourseSelected") != null){
      document.querySelector("#askCourseSelected").value = '';
  }


  whichCase = "posting";
  //document.querySelector("#tags-head-post-q span").innerHTML = "Ask your Question";
  document.querySelectorAll("#qsn-prvw .opacticy-col").forEach(
    currObj => currObj.style.display = 'none'
  );
  typeForQPost = actualType;
  populateQuesDiscPostingLayer(actualType);
}

function closeAddMoreTagsForLayerPost() {
  //document.querySelector("#tags-layer").style.display = 'none';
  //document.querySelector("#an-layer").style.display = 'none';
}

function populateQuesDiscPostingLayer(type){
  if(type == undefined){
    type = "question";
  }

  if(type == "discussion"){
    document.querySelector(".qdTitle-l2").innerHTML = "Your Discussion";
    document.querySelector("#"+params.titleTextField).setAttribute("placeholder","Type Your Discussion");
    document.querySelector("#"+params.titleTextField).setAttribute("caption","Discussion");


    document.querySelector("#"+params.titleTextField+"_counter_outer").innerHTML = "Characters <span id='"+params.titleTextField+"_counter'>0</span>/100";

    setTimeout(function(){
        document.querySelector("#"+params.titleTextField).setAttribute("maxlength","100");
        document.querySelector("#"+params.titleTextField).setAttribute("minlength","20");
        document.querySelector("#"+params.descriptionTextField).setAttribute("maxlength","2500");
        document.querySelector("#"+params.descriptionTextField).setAttribute("minlength","20");
    },100);

    document.querySelector("#"+params.descriptionTextField+"_counter_outer").innerHTML = "Characters <span id='"+params.descriptionTextField+"_counter'>0</span>/2500";

    document.querySelector("#"+params.descriptionTextField).setAttribute("caption","Description");

  } else {
      document.querySelector(".qdTitle-l2").innerHTML = "Your Question";
      document.querySelector("#"+params.titleTextField).setAttribute("placeholder","Type Your Question");
      document.querySelector("#"+params.titleTextField).setAttribute("caption","Question");

      setTimeout(function(){
          document.querySelector("#"+params.titleTextField).setAttribute("maxlength","140");
          document.querySelector("#"+params.titleTextField).setAttribute("minlength","20");
          document.querySelector("#"+params.descriptionTextField).setAttribute("maxlength","300");
          document.querySelector("#"+params.descriptionTextField).setAttribute("minlength","20");
      },100);
      document.querySelector("#"+params.titleTextField+"_counter_outer").innerHTML = "Characters <span id='"+params.titleTextField+"_counter'>0</span>/140";
      document.querySelector("#"+params.descriptionTextField+"_counter_outer").innerHTML = "Characters <span id='"+params.descriptionTextField+"_counter'>0</span>/300";
      document.querySelector("#"+params.descriptionTextField).setAttribute("caption","Description");
  }
}

function handleCharacterInTextField(event, isEnterAllowed)
{
    var regex = new RegExp("^[- a-zA-Z0-9?=.*!@#$%^&*\"'>~`()-+_|:;?/,\\\\{}\\[\\]]+$");
    var actualEvent = event.which || event.keyCode || event.charCode;
    //46 -> Delete, 36 -> Home, 35-> End,37-40 -> Arrow Keys 112-123 -> Function Keys , 9 - Tab

    var defaultAllowedArray = [9,46,36,35,37,38,39,40,112,113,114,115,116,117,118,119,120,121,122,123];

    if(defaultAllowedArray.indexOf(actualEvent) != -1){
        return true;
    }


    if(typeof(isEnterAllowed) != "undefined" && isEnterAllowed == true && actualEvent == 13){
        return true;
    }
    if(actualEvent != 8){
        var key = String.fromCharCode(event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    }
}

function autoGrowField(f, maxHeight) {
  if (f.style.overflowY != 'hidden') { f.style.overflowY = 'hidden' }
  if(maxHeight == undefined){
      var maxHeight = 300;
  }
  var id = f;
  var text = id && id.style ? id : document.getElementById(id);
  if ( !text )
    return;

  var adjustedHeight = text.clientHeight;
  if ( !maxHeight || maxHeight >= adjustedHeight )
  {
    adjustedHeight = Math.max(text.scrollHeight, adjustedHeight);
    if ( maxHeight ){
      adjustedHeight = Math.min(maxHeight, adjustedHeight);
    }
    if(adjustedHeight >= maxHeight){
      f.style.overflowY = 'scroll';
      text.style.height = adjustedHeight + "px";
    }
    else if ( adjustedHeight > text.clientHeight ){
      text.style.height = adjustedHeight + "px";
    }

  }
}

function textKey(Object) {
  supportsKeys = true;
  calcCharLeft(Object);
}

function calcCharLeft(Object) {
  var clipped = false;
  var lenUSig = 0;
  var maxLength = 1000;
  try{
    if(Object.getAttribute('maxlength')){
      maxLength = parseInt(Object.getAttribute('maxlength'));
    }
  }catch(e){}
  var charUsed;
  var textBoxVal = Object.value.trim();
  if (textBoxVal.length > maxLength) {
    Object.value = textBoxVal.substring(0,maxLength);
    charUsed = maxLength;
    clipped = true;
  } else {
    charUsed = textBoxVal.length;
  }
  document.getElementById(Object.id+'_counter').innerHTML = charUsed;
  return clipped;
}
