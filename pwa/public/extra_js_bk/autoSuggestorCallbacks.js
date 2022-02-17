/* Callback functions - start */
var SEARCH_PAGE_URL_PREFIX = '/search';
var DO_SEARCHPAGE_TRACKING = true;
var autoSuggestorInstanceArray = [];
function handleAutoSuggestorMouseClickedTags(dict, obj){
  var data = {};
  let pageName = 'shikshaHomePage';
  if(typeof dict.id == 'object'){
    obj.hideSuggestionContainer();
    if(pageName = 'shikshaHomePage'){
      tagsAutoSuggestorHandlerPostPage(dict.id.tagName, dict.id.id, true);
    }
  }else{
    data={'url':''};
    //trackData('tag',data);
    document.querySelector('#tagSearchQDP_error').style.display = 'block';
    obj.hideSuggestionContainer();
  }
}

function tagsAutoSuggestorHandlerPostPage(currentTagName, currentTagId, fetchParent, classificationType){
  if(typeof classificationType == 'undefined' || classificationType.trim() == ''){
    classificationType = 'manual';
  }
  let tagsPresent = false;
  document.querySelectorAll("#tags-selctd-links .choosen-tag").forEach(
    currObj => {
      if(currObj.getAttribute('tagId') == currentTagId){
        tagsPresent = true;
      }
    }
  );
  if(tagsPresent == false){
    document.querySelector("#tagSearch_error").innerHTML = "Tag Already Present";
    document.querySelector("#tagSearch_error").style.display = "none";
    let classification = "manual";
    let aNode = document.createElement("a");
    aNode.className = 'choosen-tag';
    aNode.href = 'javascript:void(0);';
    //aNode.status = 'checked';
    //aNode.tagId = currentTagId;
    //aNode.classification = classificationType;

    let att = document.createAttribute("status");
    att.value = 'checked';
    aNode.setAttributeNode(att);

    att = document.createAttribute("tagId");
    att.value = currentTagId;
    aNode.setAttributeNode(att);

    att = document.createAttribute("classification");
    att.value = classificationType;
    aNode.setAttributeNode(att);
    aNode.innerHTML = "<span><i></i></span><span class='i-s'>"+currentTagName+"</span>";
    document.querySelector("#tags-selctd-links").appendChild(aNode);

    document.querySelectorAll('#tags-selctd-links .choosen-tag').forEach(
      currObj => {
        currObj.addEventListener('click', (event)=>{
          removedTagsFromAddMoreLayer.push(currObj.getAttribute('tagId'));
          currObj.parentNode.removeChild(currObj);
        })
      }
    );
  }else{
    document.querySelector("#tagSearch_error").innerHTML = "Tag Already Present";
    document.querySelector("#tagSearch_error").style.display = "block";
  }
  document.querySelector("#tagSearch").value = "";
}
/* Callback functions - end */
