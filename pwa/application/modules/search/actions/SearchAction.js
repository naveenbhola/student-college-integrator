import {getRequest,postRequest, postRequestAPIs} from './../../../utils/ApiCalls';
import APIConfig from './../../../../config/apiConfig';
import {getCookie} from "../../../utils/commonHelper";

const axiosConfig = {
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    withCredentials: true
};
export const fetchTupleData = (searchedKeyword, config, extraParams = null) =>{
    let paramObj = {'keyword':searchedKeyword};
    if(extraParams != null){
      extraParams.keyword = searchedKeyword;
      paramObj = extraParams;
    }
    let queryString  = JSON.stringify(paramObj);
    let data = Buffer.from(queryString).toString('base64');
    let autosuggestorUrl  = APIConfig.GET_AUTOSUGGESTOR_TUPLES+"?data=" + encodeURIComponent(data);
    return getRequest(autosuggestorUrl, config);
}


export const fetchCollegeData = (college) =>{
    let paramObj = {'id':college.id, 'type': college.type};
    let queryString  = JSON.stringify(paramObj);
    let data = Buffer.from(queryString).toString('base64');
    let collegeDataUrl  = APIConfig.GET_SEARCH_ADVANCED_OPTIONS+"?data="+data;
    return getRequest(collegeDataUrl);
}

export const fetchSearchURL = (url, params) => {
    return Promise.resolve(postRequest(url, params,'',axiosConfig))
        .then((response) => {
            return response.data;
        })
        .catch((err) => {/*console.log('error in fetchSearchURL API url is = ',url,' ',err)*/});
}
export const fetchCollegeSRPData = (params,config,isServerCall) => (dispatch) => {
    var url = (isServerCall) ? APIConfig.GET_COLLEGE_SRP_TUPLE_SERVER : APIConfig.GET_COLLEGESRP_TUPLE;
    config = typeof config != 'undefined' ? config : {};
    return getRequest(url+'?data='+encodeURIComponent(params), config).then((response) => {
        dispatch({
            type : 'categoryData',
            data : response.data.data
        })});
}
export const trackSearch = (dataObj) => {
    dataObj.requestFrom = 'searchWidget';
    dataObj.sessionId = getCookie('visitorSessionId');
    postRequestAPIs(APIConfig.GET_SEARCH_TRACKING, dataObj)
        .catch((err) => {}
        );
}

export const fetchTrendingSearchesData = (isServerCall, config) => (dispatch) => {
    var url = (isServerCall) ? APIConfig.GET_TRENDING_SEARCH_DATA_SERVER : APIConfig.GET_TRENDING_SEARCH_DATA;
    config = typeof config != 'undefined' ? config : {};
    return getRequest(url, config).then((response) => {
        dispatch({
            type : 'trendingData',
            data : response.data.data
        })});
}

export const fetchExamSRPData = (params,config,isServerCall) => (dispatch) => {
    var url = (isServerCall) ? APIConfig.GET_EXAM_SRP_TUPLE_SERVER : APIConfig.GET_EXAM_SRP_TUPLE;
    config = typeof config != 'undefined' ? config : {};
    return getRequest(url+'?data='+encodeURIComponent(params), config).then((response) => {
        dispatch({
            type : 'examData',
            data : response.data.data
        })});
}

export const fetchIntegratedSRPData = (searchedKeyword, config) =>{
    const paramObj = {'q':searchedKeyword};
    const queryString  = JSON.stringify(paramObj);
    const data = Buffer.from(queryString).toString('base64');
    const integratedSearchUrl  = APIConfig.GET_INTEGRATED_SRP_DATA + "?data=" + encodeURIComponent(data);
    return getRequest(integratedSearchUrl, config);
}
