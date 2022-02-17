import {getRequest} from './../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';


export const fetchRecommendationData = (params, config, isServerCall = false) => (dispatch) => {
	const API = (isServerCall) ? APIConfig.GET_RECOMMENDATION_PAGE_DATA_SERVER : APIConfig.GET_RECOMMENDATION_PAGE_DATA;
	config = typeof config != 'undefined' ? config : {};
	const url = API+'?data='+params;
	return getRequest(url, config).then((response) => {
		dispatch({
			type : 'recommendationData',
			data : response.data.data
	})}).catch(function(err){
		let response = {'statusCode' :404};
		dispatch({
				type : 'recommendationData',
				data : response
			})
		});
};
