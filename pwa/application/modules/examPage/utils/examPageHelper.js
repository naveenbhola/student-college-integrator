import React from 'react';
import {getCookie, getUrlParameter, isUserLoggedIn, setCookie, getObjectSize} from "../../../utils/commonHelper";
import {event} from './../../reusable/utils/AnalyticsTracking';
import PopupLayer from "./../../common/components/popupLayer";

export function getExamGroupIdForCTA(examBasicInfo){
    let groupId = getUrlParameter('course');
    if(groupId === "" || !(groupId > 0)){
        if(examBasicInfo != null && examBasicInfo.primaryGroup != null){
            groupId = examBasicInfo.primaryGroup.id;
        }else{
            groupId = 0;
        }
    }
    return groupId;
}
export const trackViewDetailEvent = (gaCategory, sectionName, deviceType) => {
    let labelName = isUserLoggedIn() ? 'Logged-In' : 'Non-Logged In';
    let deviceLabel = deviceType === 'mobile' ? 'MOB' : 'DESK';
    let actionLabel = sectionName.toUpperCase().replace(' ', '_')+'_VIEW_ALL_EXAM_PAGE_'+deviceLabel;
    event({category : gaCategory, action : actionLabel, label : labelName});
};

export function getExamPageDFPData(examPageData, deviceType) {
    let dfpPostParams = 'parentPage=DFP_ExamPage';
    let dfpData = {};
    if(examPageData.examBasicInfo == null || examPageData.groupBasicInfo == null || examPageData.groupBasicInfo == null){
        return;
    }
    dfpData['pageType'] = examPageData.activeSection;
    dfpData['examId'] = examPageData.examBasicInfo.id;
    dfpData['groupId'] = examPageData.groupBasicInfo.groupId;
    dfpData['conductedBy'] = examPageData.examBasicInfo.conductedBy;
    let primaryHierarchyId = (examPageData.groupBasicInfo != null && examPageData.groupBasicInfo.entitiesMappedToGroup != null && typeof examPageData.groupBasicInfo.entitiesMappedToGroup.primaryHierarchy != 'undefined') ? examPageData.groupBasicInfo.entitiesMappedToGroup.primaryHierarchy[0] : 0;
    if(primaryHierarchyId > 0){
        dfpData['stream_id'] = (examPageData.groupBasicInfo.hierarchyData[primaryHierarchyId].stream != null) ? examPageData.groupBasicInfo.hierarchyData[primaryHierarchyId].stream : 0;
        dfpData['substream_id'] = (examPageData.groupBasicInfo.hierarchyData[primaryHierarchyId].substream != null) ? examPageData.groupBasicInfo.hierarchyData[primaryHierarchyId].substream : 0;
        dfpData['specialization_id'] = (examPageData.groupBasicInfo.hierarchyData[primaryHierarchyId].specialization != null) ? examPageData.groupBasicInfo.hierarchyData[primaryHierarchyId].specialization : 0;
        dfpData['baseCourse'] = (examPageData.groupBasicInfo != null && examPageData.groupBasicInfo.entitiesMappedToGroup != null && typeof examPageData.groupBasicInfo.entitiesMappedToGroup.course != 'undefined') ? examPageData.groupBasicInfo.entitiesMappedToGroup.course : [];
    }

    if(typeof examPageData.contentInfo != 'undefined' && typeof examPageData.contentInfo.dfpAdUnits != 'undefined' && examPageData.contentInfo.dfpAdUnits){
        let addMoreSlot = prepareDFPSlot(examPageData.contentInfo.dfpAdUnits, deviceType);
        if(addMoreSlot && getObjectSize(addMoreSlot)){
            dfpData['addMoreDfpSlot'] = addMoreSlot;
        }
    }

    let dfpParams = JSON.stringify(dfpData);
    dfpPostParams +='&entity_id='+examPageData.examBasicInfo.id+'&extraPrams='+dfpParams;
    return dfpPostParams;
}

export function getExamPageTrackingData(examPageData) {
    if(examPageData.examBasicInfo == null || examPageData.groupBasicInfo == null || examPageData.groupBasicInfo == null){
        return {'gtmParams' : {}, 'beaconTrackData' : {}};
    }
    let gtmParams = {};
    gtmParams['exam'] = examPageData.examBasicInfo.id;
    gtmParams['groupId'] = examPageData.examBasicInfo.groupId;
    let beaconTrackData = {
        'pageIdentifier' : 'examPageMain',
        'pageEntityId' : examPageData.examBasicInfo.id,
        extraData : {
            'countryId' : 2,
            'groupId' : examPageData.groupBasicInfo.groupId,
            'examId' : examPageData.examBasicInfo.id,
            'isAmpPage' : false
        }
    };
    
    if(examPageData.activeSection !== 'homepage'){
        beaconTrackData.extraData.childPageIdentifier = 'exam'+(examPageData.contentInfo.sectionNameMapping[examPageData.activeSection].replace(' ',''))+'Page';
    }else{
        beaconTrackData.extraData.childPageIdentifier = 'examPageMain';
    }

    let heirarchies = [], data = {};
    if(examPageData.groupBasicInfo != null){
        let allHeirarchies = examPageData.groupBasicInfo.hierarchyData;
        for(let key in allHeirarchies){
            if(allHeirarchies.hasOwnProperty(key)){
                data = {};
                gtmParams['stream'] = data['streamId'] = allHeirarchies[key].stream;
                if(allHeirarchies[key].substream != null){
                    gtmParams['substream'] = data['substreamId'] = allHeirarchies[key].substream;
                }
                if(allHeirarchies[key].specialization != null){
                    gtmParams['specialization'] = data['specializationId'] = allHeirarchies[key].specialization;
                }
                data['primaryHierarchy'] = allHeirarchies[key].primary_hierarchy;
                heirarchies.push(data);
            }
        }
    }
    if(heirarchies.length > 0){
        beaconTrackData['extraData']['hierarchy'] = heirarchies;
    }
    if(examPageData.groupBasicInfo != null && examPageData.groupBasicInfo.entitiesMappedToGroup.course){
        gtmParams['baseCourseId'] = beaconTrackData['extraData']['baseCourseId'] = examPageData.groupBasicInfo.entitiesMappedToGroup.course;
    }
    if(examPageData.groupBasicInfo != null && examPageData.groupBasicInfo.attributeInfo != null && Object.keys(examPageData.groupBasicInfo.attributeInfo).length > 0){
        for (let attrId in examPageData.groupBasicInfo.attributeInfo){
            if(examPageData.groupBasicInfo.attributeInfo[attrId].attributeId === 5) {
                gtmParams['educationType'] = beaconTrackData['extraData']['educationType'] = examPageData.groupBasicInfo.attributeInfo[attrId].valueId;
            }else if(examPageData.groupBasicInfo.attributeInfo[attrId].attributeId === 7){
                gtmParams['deliveryMethod'] = beaconTrackData['extraData']['deliveryMethod'] = examPageData.groupBasicInfo.attributeInfo[attrId].valueId;
            }else if(examPageData.groupBasicInfo.attributeInfo[attrId].attributeId === 3){
                gtmParams['credential'] = beaconTrackData['extraData']['credential'] = examPageData.groupBasicInfo.attributeInfo[attrId].valueId;
            }
        }
    }

    gtmParams['pageType'] = 'examPage';
    return {'gtmParams' : gtmParams, 'beaconTrackData' : beaconTrackData};
}
export function updateGuideTrackCookie(entityId, cookieName){
    let entityValues = getCookie(cookieName);
    if(entityValues !== ""){
        entityValues = JSON.parse(atob(entityValues));
    }else{
        entityValues = [];
    }
    if(entityValues.indexOf(entityId) === -1){
        entityValues.push(entityId);
    }
    setCookie(cookieName, btoa(JSON.stringify(entityValues)));
}

export function isGuideDownloaded(entityId, cookieName){
    let entityValues = getCookie(cookieName);
    if(entityValues !== ""){
        entityValues = JSON.parse(atob(entityValues));
        window.data = entityValues;
        if(entityValues.indexOf(entityId) !== -1){
            let e = document.getElementsByClassName('eApplyNow'+entityId);  // Find the elements
            if(typeof e != 'undefined' && e !== null && e.length > 0){
                for(let i = 0; i < e.length; i++){
                    e[i].innerText = 'Guide Mailed';
                    e[i].classList.add('eaply-disabled');
                }
            }
        }
    }
}
export function disableGuideDownload(cookieName) {
    let entityValues = getCookie(cookieName);
    if(entityValues !== "") {
        entityValues = JSON.parse(atob(entityValues));
        for(let i = 0; i<entityValues.length; i++){
            let e = document.getElementsByClassName('eApplyNow'+entityValues[i]);
            if(typeof  e != 'undefined' && e != null){
                for(let j = 0; j < e.length; j++){
                    e[j].innerText = 'Guide Mailed';
                    e[j].classList.add('eaply-disabled');
                }
            }
        }
    }
}
export function disableDownloadBrochure(){
    if(typeof(document) !='undefined' && document.getElementsByClassName('tupleBrochureButton').length>0){
        let ebLen = document.getElementsByClassName('tupleBrochureButton');
        for(let i=0;i<ebLen.length;i++){
            let courseId = ebLen[i].getAttribute('courseid');
            let ebCookie = 'applied_'+courseId;
            if(getCookie(ebCookie)){
                document.getElementById('ebTxt'+courseId).innerText = 'Brochure Mailed';
                ebLen[i].classList.add('ebDisabled');
            }
        }
    }
}

export function prepareDFPSlot(dfpData, deviceType){
    if(typeof dfpData != 'object' || (typeof dfpData == 'object' && getObjectSize(dfpData) == 0)){
        return null;
    }
    let finalData = new Object();
    let tmpArr = new Array();
    dfpData.forEach((value,index)=>{
        let key = deviceType+'_content_'+index;
        let tempData  = new Object();
        if(tmpArr.indexOf(value.divId) == -1){ // add only unique data
            tempData.slotId    = value.adUniPath;
            tempData.elementId = value.divId;
                if(deviceType == 'mobile'){
                    tempData.height = value.mobileHeight;
                    tempData.width  = value.mobileWidth;  
                }else{
                    tempData.height = value.desktopHeigtht;
                    tempData.width  = value.desktopWidth;  
                }
            finalData[key] = tempData;    
            tmpArr.push(value.divId);
        }
    });
    return finalData;
} 

export function displayWikiDFP(dfpSlot){
    if(typeof dfpSlot != 'object' || (typeof dfpSlot == 'object' && getObjectSize(dfpSlot) == 0)){
        return null;
    }
    if(typeof window !='undefined' && typeof window.googletag != 'undefined' && dfpSlot){
        dfpSlot.map(obj => {
            window.googletag.cmd.push(function () {
                window.googletag.display(obj.divId);
            });
        });    
    }
}

export function storeCTA(groupId, actionType){
    let actionList = [];
    if(typeof window.sessionStorage != 'undefined' && groupId && actionType){
        let storedArray = getCTAInfo(groupId);
        if(storedArray){
            actionList = storedArray;
        }
        if(actionList.indexOf(actionType) == -1){
            actionList.push(actionType);
            window.sessionStorage.setItem("EPCTA_"+groupId, JSON.stringify(actionList));       
        }
    }
}

export function getCTAInfo(groupId){
    if(typeof window.sessionStorage != 'undefined' && groupId){
        let storedArray = JSON.parse(window.sessionStorage.getItem("EPCTA_"+groupId));
        return storedArray;
    }
}

export function isCTAResponseMade(groupId, actionType){
    let response = false;
    if(typeof window.sessionStorage != 'undefined' && groupId && actionType){
        let storedArray = getCTAInfo(groupId);
        if(storedArray && storedArray.indexOf(actionType) != -1){
            response = true;
        }
    }
    return response;
}