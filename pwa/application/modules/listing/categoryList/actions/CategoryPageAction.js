import {getRequest, postRequest, getMultipleAxiosRequest} from './../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';
import { showResponseForm, getFormByClientCourse } from './../../../user/Response/actions/ResponseFormAction';
import {getCookie, getQueryVariable, categoryPageShuffle} from "../../../../utils/commonHelper";

export const fetchCategoryPageData = (paramsObj, config, isServerCall, isPCWReq=false, ctpgRandom, isCaching = false, isFirstPage) => (dispatch) => {
	let url = (isServerCall) ? APIConfig.GET_CATEGORYPAGE_TUPLE_SERVER : APIConfig.GET_CATEGORYPAGE_TUPLE;
	config = typeof config != 'undefined' ? config : {};
    let paramsCatPage;
	if(isServerCall){
		paramsCatPage = Buffer.from(JSON.stringify(paramsObj)).toString('base64');
	}
    else {
        paramsCatPage = btoa(JSON.stringify(paramsObj));
    }
	let catPageAPIUrl;
    if(isCaching){
		catPageAPIUrl = url +  '/noFilter?data=' + paramsCatPage;
	} else{
		catPageAPIUrl = url + '?data=' + paramsCatPage;
	}
	isPCWReq = isPCWReq && !paramsObj['sby']; 
	if(isPCWReq){
        let pcwUrl = (isServerCall) ? APIConfig.GET_PCW_DATA_SERVER : APIConfig.GET_PCW_DATA;
        let pcwAPIUrl;
        delete paramsObj['fr'];
        let paramsPcw;
        if(isServerCall){
            paramsPcw = Buffer.from(JSON.stringify(paramsObj)).toString('base64');
        }
        else {
            paramsPcw = btoa(JSON.stringify(paramsObj));
        }
		if(isCaching){
			pcwAPIUrl = pcwUrl +  '/noFilter?data=' + paramsPcw;
		}else{
			pcwAPIUrl = pcwUrl + '?data=' + paramsPcw;
		}
        return getMultipleAxiosRequest(catPageAPIUrl , pcwAPIUrl, config).then((response) => {
        	if(response && response.catPageData && response.catPageData.data && response.catPageData.data.data)
        		response.catPageData.data.data['pcwData'] = response.pcwData.data.data;
        	if(isFirstPage) {
        		//console.log('shuffle   ');
				const shuffledArray = categoryPageShuffle(response.catPageData.data.data, ctpgRandom);
				response.catPageData.data.data.categoryInstituteTuple = shuffledArray;
			}
            dispatch({
                type: 'categoryData',
                data: response.catPageData.data.data
            })
		});

	}
	else {
        return getRequest(catPageAPIUrl, config).then((response) => {
        	response.data.data.pcwData = null;
			if(isFirstPage) {
				//console.log('shuffle   qqq');
				const shuffledArray = categoryPageShuffle(response.data.data, ctpgRandom);
				response.data.data.categoryInstituteTuple = shuffledArray;
			}
            dispatch({
                type: 'categoryData',
                data: response.data.data
            })
        });
    }
}

//PreCache Course Page of Top 3 Tuples

export const fetchTupleData = (tupleData, categoryUrl) =>{
	var tupleInfo = new Array();

	if(tupleData != null && tupleData.length>0){
		tupleInfo = tupleData.slice(0,1); 
	}
	let paramsObj = {};
	paramsObj['url'] = categoryUrl;
	//paramsObj['ctpgRandom'] = getCookie('ctpgRandom');
	let queryString  = JSON.stringify(paramsObj);
	let data = Buffer.from(queryString).toString('base64');
	let categoryPageUrl  = APIConfig.GET_CATEGORYPAGE_TUPLE+"?data="+data;

	for(var i in tupleInfo){
		let url = APIConfig.GET_COURSE+'?courseId='+tupleInfo[i].courseTupleData.courseId; // cache course tuple
		window.addEventListener('load', getRequest(url));
		url = APIConfig.GET_INSTITUTE+'?instituteId='+tupleInfo[i].instituteId; // cache institute tuple
		window.addEventListener('load', getRequest(url));

    }
}

export const fetchResponseFormsData = (tupleData) => {

	if(tupleData != null && tupleData.length>0){

		// pre-cache only 1 course form data for now; number can be changed later
		var requiredTupleData = new Array();
		requiredTupleData     = tupleData.slice(0,1);
		for(var i in requiredTupleData){

			let courseId      = requiredTupleData[i].courseTupleData.courseId;
			let params1       = 'clientCourseId='+courseId+'&listingType=course';
			window.addEventListener('load', showResponseForm(params1));
			let params2       = 'clientCourse='+courseId;
			window.addEventListener('load', getFormByClientCourse(params2));   
		    
	    }

	}
	
}
