import {getRequest} from './../../../utils/ApiCalls';
import APIConfig from './../../../../config/apiConfig';

export const fetchExamPageData = (params, config, isServerCall, examName) => (dispatch) => {
	var API = (isServerCall) ? APIConfig.GET_EXAMPAGE_FROM_SERVER : APIConfig.GET_EXAMPAGE;
	config = (typeof config != 'undefined' && config!='') ? config : {};
	var url = API+'?exam='+examName+'&data='+params;
	
	return getRequest(url, config).then((response) => { 
		dispatch({
			type : 'FETCH_EXAM_DATA',
			data : response.data.data
	})});
}
