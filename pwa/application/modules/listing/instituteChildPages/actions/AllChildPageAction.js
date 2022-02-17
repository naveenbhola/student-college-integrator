	import {getRequest} from './../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';
import {getCookie, getQueryVariable} from "../../../../utils/commonHelper";

export const fetchAllCourseData = (params, config, isServerCall) => (dispatch) => {
	var API = (isServerCall) ? APIConfig.GET_ALL_COURSE_PAGEA_DATA_SERVER : APIConfig.GET_ALL_COURSE_PAGEA_DATA;
	config = typeof config != 'undefined' ? config : {};
	let paramsPage;
	let paramsObj = JSON.parse(Buffer.from(params, 'base64').toString());
	var url = API+'?instituteId='+paramsObj.instituteId+'&data='+params;
	return getRequest(url, config).then((response) => { 
		dispatch({
			type : 'allChildPageData',
			data : response.data.data
	})}).catch(function(err){
		var response = {'statusCode' :404};
		dispatch({
				type : 'allChildPageData',
				data : response
			})
		});
}

export const fetchAdmissionPageData = (params, config, isServerCall) => (dispatch) => {
	var API = (isServerCall) ? APIConfig.GET_ADMISSION_PAGE_DATA_SERVER : APIConfig.GET_ADMISSION_PAGE_DATA;
	config = typeof config != 'undefined' ? config : {};
	var url = API+'?'+params;
	return getRequest(url, config).then((response) => { 
		dispatch({
			type : 'allChildPageData',
			data : response.data.data
	})}).catch(function(err){
		var response = {'statusCode' :404};
		dispatch({
				type : 'allChildPageData',
				data : response
			})
		});
}

export const fetchPlacementPageData = (params, config, isServerCall) => (dispatch) => {
	var API = (isServerCall) ? APIConfig.GET_PLACEMENT_PAGE_DATA_SERVER : APIConfig.GET_PLACEMENT_PAGE_DATA;
	config = typeof config != 'undefined' ? config : {};
	let paramsObj = JSON.parse(Buffer.from(params, 'base64').toString());
	var url = API+'?instituteId='+paramsObj.instituteId+'&data='+params;
	console.log(params);
	console.log(url);
	return getRequest(url, config).then((response) => { 
		dispatch({
			type : 'allChildPageData',
			data : response.data.data
	})}).catch(function(err){
		console.log('in page error state');
		var response = {'statusCode' :404};
		console.log(response);
		dispatch({
				type : 'allChildPageData',
				data : response
			})
		});
}

export const fetchCutOffPageData = (params, config, isServerCall) => (dispatch) => {
	var API = (isServerCall) ? APIConfig.GET_CUTOFF_PAGE_DATA_SERVER : APIConfig.GET_CUTOFF_PAGE_DATA;
	config = typeof config != 'undefined' ? config : {};
	let paramsObj = JSON.parse(Buffer.from(params, 'base64').toString());
	var url = API+'?instituteId='+paramsObj.instituteId+'&data='+params;
	//console.log(params);
	console.log(url);
	return getRequest(url, config).then((response) => { 
		//response.data.data.tupleData = data1;
		dispatch({
			type : 'allChildPageData',
			data : response.data.data
	})}).catch(function(err){
		console.log('in page error state');
		var response = {'statusCode' :404};
		console.log(response);
		dispatch({
				type : 'allChildPageData',
				data : response
			})
		});
}



export const storeChildPageDataForPreFilled = (data) => (dispatch) => {
	if(typeof data != 'undefined' && data)
	{
		return	dispatch(
			{
					type : 'loaderData',
					data : data
			})
	}
	return	dispatch({
					type : 'loaderDataEmpty',
					data : {}
			})
}