import {getRequest, postRequest} from './../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';

export const fetchCourseHomePageData = (params,config,isServerCall) => (dispatch) => {
	var url = (isServerCall) ? APIConfig.GET_COURSEHOMEPAGE_FROM_SERVER : APIConfig.GET_COURSEHOMEPAGE;
	config = typeof config != 'undefined' ? config : {};
	return getRequest(url+'?data='+params,config).then((response) => {
		dispatch({
			type : 'courseHomePageData',
			data : response.data.data
	})});
}

export const preFilledDataForLoader = (chpData) => (dispatch) => {
	if(chpData && typeof chpData != 'undefined')
	{
		return	dispatch({
					type : 'courseHomePagePrefilled',
					data : chpData
			})
	}
}