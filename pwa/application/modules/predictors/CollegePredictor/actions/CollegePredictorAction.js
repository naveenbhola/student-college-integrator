import {getRequest} from './../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';

export const fetchCollegePredictorData = (params, config, isServerCall) => (dispatch) => {
	var url = (isServerCall) ? APIConfig.GET_COLLEGE_PREDICTOR_FROM_SERVER : APIConfig.GET_COLLEGE_PREDICTOR;
	config = (typeof config != 'undefined' && config!='') ? config : {};
	return getRequest(url, config).then((response) => {
		dispatch({
			type : 'FETCH_COLLEGE_PREDICTOR_DATA',
			data : response.data.data
	})});
}

export const fetchCollegePredictorResultData = (params, config, isServerCall) => (dispatch) => {
	if(params === ''){
		return new Promise(function(resolve){
			resolve(dispatch({
				type : 'FETCH_COLLEGE_PREDICTOR_RESULTS_DATA',
				data : {'showError' : true}
			}));
		});
	}
	let url = (isServerCall) ? APIConfig.GET_COLLEGE_PREDICTOR_RESULTS_SERVER : APIConfig.GET_COLLEGE_PREDICTOR_RESULTS;
	config = (typeof config != 'undefined' && config!=='') ? config : {};
	url = url + '?data=' + params;
	return getRequest(url, config).then((response) => {
		response.data.data.urlHash = params;
		response.data.data.showError = false;
		dispatch({
			type : 'FETCH_COLLEGE_PREDICTOR_RESULTS_DATA',
			data : response.data.data
	})}).catch(function(err){
		var response = {'statusCode' : 404};
		dispatch({
			type : 'FETCH_COLLEGE_PREDICTOR_RESULTS_DATA',
			data : response
			})
		});
}

export const storeSaveList = (data) => (dispatch) => {
	    if(data){
		    dispatch({
				type : 'CPSaveList',
				data : data
			})
		}
};

export const storeCollegePredictorFilterData = (data) => (dispatch) => {
	    if(data){
		    dispatch({
				type : 'CP_FILTER_DATA',
				data : data
			})
		}
};