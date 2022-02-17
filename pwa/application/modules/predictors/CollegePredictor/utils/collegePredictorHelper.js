import {getRequest} from "../../../../utils/ApiCalls";
import APIConfig from './../../../../../config/apiConfig';
import {getJsonFromUrl} from "../../../../utils/commonHelper";

export const getTrackingParams =(fromWhere, examId)=> {
    let gtmParams = {};
    let data = new Object();
    let beaconTrackData = {};
    beaconTrackData['extraData'] = {};
    let beaconEntryHieraries = new Array();
    gtmParams['stream'] = 2;
    data['stremId'] = 2;

    gtmParams['baseCourseId'] = 10;
    beaconTrackData['extraData']['baseCourseId'] = 10;

    gtmParams['educationType'] = 20;
    beaconTrackData['extraData']['educationType'] = 20;

    beaconEntryHieraries.push(data);
    beaconTrackData['extraData']['hierarchy'] = beaconEntryHieraries;
    beaconTrackData['extraData']['countryId'] = 2;
    if(fromWhere && fromWhere === 'resultPage'){
      beaconTrackData['extraData']['childPageIdentifier'] = 'resultPage';
    }
    gtmParams['pageType'] = 'allCollegePredictorPage';
    gtmParams['exams']    = (examId && examId.length) ? examId.join() : '';
    beaconTrackData['pageIdentifier'] = "allCollegePredictorPage";
    beaconTrackData['pageEntityId'] = 0;
    return {'gtmParams' : gtmParams, 'beaconTrackData' : beaconTrackData};
};

export const getExamSpecificData = (scoreType) => {
  let data = {};
  switch (scoreType) {
    case 'score' :
      data.placeholder = 'Score';
      break;
    case 'percentile' :
      data.placeholder = 'Percentile';
      break;
    default :
      data.placeholder = 'Rank';
  }
  return data;
};

export const getThirdFormData = (examIds) => {
    let axiosConfig = {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        withCredentials: true
    };
    let data = {};
    let examIdStr = '';
    if(examIds.length > 0){
        examIdStr = examIds.join(',');
        return getRequest(APIConfig.GET_COLLEGE_PREDICTOR_THIRD_FORM_DATA+'?examIds='+examIdStr, data, '', axiosConfig).then((response) => {
            return response.data;
        }).catch(function(err){});
    }
};
export const getUserFormDataFromUrl = (url) => {
    let newParams = [];
    if(!url){
        return newParams;
    }
    let queryObj = getJsonFromUrl(url);
    for(let alias in queryObj){
        if(queryObj.hasOwnProperty(alias)){
            switch (alias) {
                case 'category':
                case 'gender':
                case 'ds':
                    if(!isNaN(parseInt(queryObj[alias]))){
                        newParams.push(alias+'='+parseInt(queryObj[alias]));
                    }else{
                        newParams.push(alias+'=0');
                    }
                    break;
                case 'se':
                    queryObj[alias].map((id)=>{
                        if(!isNaN(parseInt(id))){
                            newParams.push(alias+'[]='+parseInt(id));
                        }else{
                            newParams.push(alias+'[]=0');
                        }
                    });
                    break;
                case 'sc':
                case 'esc':
                    for(let i in queryObj[alias]){
                        if(queryObj[alias].hasOwnProperty(i) && !isNaN(parseInt(queryObj[alias][i]))){
                            newParams.push(alias+'['+parseInt(i)+']='+parseInt(queryObj[alias][i]));
                        }else{
                            newParams.push(alias+'['+parseInt(i)+']=0');
                        }
                    }
                    break;
                case 'tab':
                case 'giveFilter':
                    newParams.push(alias+'='+queryObj[alias]);
                    break;
                case 'pageNo':
                    if(!isNaN(parseInt(queryObj[alias]))){
                        newParams.push(alias+'='+parseInt(queryObj[alias]));
                    }else{
                        newParams.push(alias+'=1');
                    }
                    break;
            }
        }
    }
    return newParams;
};
export const getUserFormDataObjectFromUrl = (url) => {
    let newParams = {};
    if(!url){
        return newParams;
    }
    let queryObj = getJsonFromUrl(url);
    if(Object.keys(queryObj).length<=0){
        return newParams
    }
    for(let alias in queryObj){
        if(queryObj.hasOwnProperty(alias)){
            switch (alias) {
                case 'category':
                case 'gender':
                case 'ds':
                    if(!isNaN(parseInt(queryObj[alias]))){
                        newParams[alias] = parseInt(queryObj[alias]);
                    }else{
                        newParams[alias] = 0;
                    }
                    break;
                case 'se':
                    newParams[alias] = [];
                    queryObj[alias].map((id)=>{
                        if(!isNaN(parseInt(id))){
                            newParams[alias].push(parseInt(id));
                        }else{
                            newParams[alias].push(0);
                        }
                    });
                    break;
                case 'sc':
                case 'esc':
                    newParams[alias] = [];
                    for(let i in queryObj[alias]){
                        if(queryObj[alias].hasOwnProperty(i)){
                            if(!isNaN(parseInt(queryObj[alias][i]))){
                                newParams[alias][parseInt(i)] = parseInt(queryObj[alias][i]);
                            }else{
                                newParams[alias][parseInt(i)] = 0;
                            }
                        }
                    }
                    break;
                case 'tab':
                case 'giveFilter':
                    newParams[alias] = queryObj[alias];
                    break;
                case 'pageNo':
                    if(!isNaN(parseInt(queryObj[alias]))){
                        newParams[alias] = parseInt(queryObj[alias]);
                    }else{
                        newParams[alias] = 1;
                    }
                    break;
            }
        }
    }
    return newParams;
};
export const getFilterDataObjectFromUrl = (url) => {
    let newParams = {};
    if(!url){
        return newParams;
    }
    let queryObj = getJsonFromUrl(url);
    if(Object.keys(queryObj).length<=0){
        return newParams
    }
    for(let alias in queryObj){
        if(queryObj.hasOwnProperty(alias)){
            switch (alias) {
                case 'ex':
                case 'sp':
                case 'co':
                case 'i':
                case 'fe':
                case 'ct':
                case 'st':
                    if(!newParams[alias] || !Array.isArray(newParams[alias])){
                        newParams[alias] = [];
                    }
                    queryObj[alias].map((id)=>{
                        if(alias === 'co' || alias === 'fe'){
                            newParams[alias].push(id);
                        }else if(!isNaN(parseInt(id))){
                            newParams[alias].push(parseInt(id));
                        }else{
                            newParams[alias].push(0);
                        }
                    });
                    break;
            }
        }
    }
    return newParams;
};
export const getResultType = (e) => {
    let examArr = [304, 9247];
    return examArr.indexOf(parseInt(e)) !== -1 ? 'score' : 'rank';
};
export const makeInputObjectForResultPage = (url, viewMoreInputs = {}) => {
    let uData = getUserFormDataObjectFromUrl(url);
    let fData = getFilterDataObjectFromUrl(url);
    let finalInput = {}, examTuple;
    finalInput.inputs = [];
    if(typeof uData['se'] !== 'undefined'){
        uData['se'].map((e)=>{
            examTuple = {};
            examTuple.examId = e;
            examTuple.resultType = getResultType(e); //for BITSAT exam
            if(typeof uData['sc'] != 'undefined' && typeof uData['sc'][e] != 'undefined'){
                examTuple.result = uData['sc'][e];
            }
            if(typeof uData['category'] != 'undefined'){
                examTuple.categoryId = uData['category'];
            }
            if(typeof uData['esc'] != 'undefined' && typeof uData['esc'][e] != 'undefined' && uData['esc'][e] > 0){
                examTuple.categoryId = uData['esc'][e];
            }
            finalInput.inputs.push(examTuple);
        });
        finalInput.genderId = uData['gender'];
    }
    if(typeof uData['ds'] !== 'undefined'){
        finalInput.stateId = uData['ds'];
    }
    finalInput.filters = {};
    for (let i in fData){
        if(fData.hasOwnProperty(i)){
            finalInput.filters[i] = [];
            fData[i].map((id)=>{
                if(i === 'co' || i === 'fe'){
                    finalInput.filters[i].push(id);
                }else if(!isNaN(parseInt(id))){
                    finalInput.filters[i].push(parseInt(id));
                }else{
                    finalInput.filters[i].push(0);
                }
            });
        }
    }
    if(finalInput.inputs.length !== 0 || Object.keys(finalInput.filters).length !== 0){
        finalInput.tabType = 'college';
        if(typeof uData['tab'] != 'undefined' && uData['tab'] === 'branch'){
            finalInput.tabType = 'branch';
        }
        finalInput.pageNo = 1;
        if(typeof uData['pageNo'] != 'undefined' && !isNaN(parseInt(uData['pageNo'])) && uData['pageNo'] > 0){
            finalInput.pageNo = parseInt(uData['pageNo']);
        }
        if(typeof uData['giveFilter'] != 'undefined' && !isNaN(parseInt(uData['giveFilter'])) && uData['giveFilter'] <= 0){
            finalInput.giveFilter = (parseInt(uData['giveFilter'])<=0) ? false : true;
        }
        let viewMoreDTO = {};
        if(Object.keys(viewMoreInputs).length > 0){
            if(typeof viewMoreInputs.instId != 'undefined' && viewMoreInputs.instId > 0){
                viewMoreDTO.instId = viewMoreInputs.instId;
            }
            if(typeof viewMoreInputs.specId != 'undefined' && viewMoreInputs.specId > 0){
                viewMoreDTO.specId = viewMoreInputs.specId;
            }
            if(typeof viewMoreInputs.exCLPs != 'undefined' && Array.isArray(viewMoreInputs.exCLPs) && viewMoreInputs.exCLPs.length > 0){
                viewMoreDTO.exCLPs = viewMoreInputs.exCLPs;
            }
            finalInput.viewMoreDTO = viewMoreDTO;
        }
        return Buffer.from(JSON.stringify(finalInput)).toString('base64');
    }
    return '';
};
export const getModifySearchUrl = (cpFormUrl, currentUrl) => {
    let userFormData = getUserFormDataFromUrl(currentUrl);
    return cpFormUrl + '?' + userFormData.join('&');
};

export function getRegistrationPrams(domain, regData, url, deviceType, otherData = {}){
    let customFields =  regData, trackingDataStr = '';
    if(typeof window !== 'undefined' && otherData.trackUserInputData){
        window.reactCallBackCollegePredictor = otherData.trackUserInputData;
    }
    let formData = { 'trackingKeyId': 3211, 'customFields': customFields, 'callbackFunction': 'callBackCollegePredictor', 'callbackFunctionParams':{ 'redirectUrl' : url}};
    if(deviceType === 'desktop'){
        return formData;
    }else{
        if(otherData.userInputTrackingDataStr){
            trackingDataStr = '&trackData=' + otherData.userInputTrackingDataStr;
        }
        return domain + "/muser5/UserActivityAMP/getRegistrationAmpPage?actionType=finalStep&fromwhere=allCollegePredictor&referer="+Buffer.from(url).toString('base64')+trackingDataStr;
    }
}
export  const addToShortlist =(params, otherParams = {}) => {
    if(typeof(window) !='undefined' && typeof(addShortlistFromSearch) !='undefined'){
        window.addShortlistFromSearch(params.courseId, params.srtTrackId, params.pageType, otherParams);
    }
};