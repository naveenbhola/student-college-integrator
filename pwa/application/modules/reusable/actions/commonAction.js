import {postRequest} from './../../../utils/ApiCalls';
import APIConfig from './../../../../config/apiConfig';

export const dfpBannerConfig = (params) => (dispatch) => {
	var url = APIConfig.GET_DFP_PARAMS+'?'+params;
	return postRequest(url, params).then((response) => { 
		dispatch({
			type : 'dfpParams',
			data : response.data.data
	})}).catch(function(err){
		});
}

export const clearDfpBannerConfig = () => (dispatch) => {
	return dispatch({
		type : 'emptydfpParams',
		data : {}
	})
}