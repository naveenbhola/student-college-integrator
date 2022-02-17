import {getRequest, postRequest} from './../../../utils/ApiCalls';
import APIConfig from './../../../../config/apiConfig';

export const fetchRankingPageData = (params, config, isServerCall = false) => (dispatch) => {
	let url = (isServerCall) ? APIConfig.GET_RANKINGPAGE_FOR_SERVER : APIConfig.GET_RANKINGPAGE;
	config = typeof config != 'undefined' ? config : {};
	return getRequest(url+'?data='+params, config).then( (response) => {
		response.data.data.rankingUrlHash = params;
		dispatch({
			type : 'rankingPageData',
			data : response.data.data
	  });
  }).catch(function(err){
		if(err.response.data.status == 'error'){
			dispatch({
				type : 'rankingPageData',
				data : {}
		  });
		}
	});
}
