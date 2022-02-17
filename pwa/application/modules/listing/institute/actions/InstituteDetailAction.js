import {getRequest} from './../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';

export const fetchInstituteDetailPageData = (params, config, isServerCall) => (dispatch) => {
	var API = (isServerCall) ? APIConfig.GET_INSTITUTE_FROM_SERVER : APIConfig.GET_INSTITUTE;
	config = typeof config != 'undefined' ? config : {};
	var url = API+'?'+params;
	return getRequest(url, config).then((response) => { 
		dispatch({
			type : 'instituteData',
			data : response.data.data
	})});
}


//fetch affliated and collegedepatments listing

export const fetchInstituteCollegeListPageData = (instituteId) => {
	var API = APIConfig.GET_INSTITUTECOLLEGELIST;
	//config = typeof config != 'undefined' ? config : {};
	var url = API+'?instituteId=' + instituteId;
	return getRequest(url);
}

//Precache top two also viewed and similar courses
export const getAlsoViewedAndSimilarInstitutes = (alsoViewedArr, similarInstituteArr, currentInstituteId) =>{

	if(currentInstituteId){
			var url = APIConfig.GET_INSTITUTE+'?instituteId='+currentInstituteId;
			getRequest(url);
		}
}

export function preCacheCourseData(courseIds){
	for(var i in courseIds){
		if(i>=1){
			break;
		}
		var url = APIConfig.GET_COURSE+'?courseId='+courseIds[i];
		getRequest(url);
	}
}


export function preCacheFirstCollegeList(instituteId){
		if(instituteId){
			var url = APIConfig.GET_INSTITUTE+'?instituteId='+instituteId;
			getRequest(url);
		}
}

export const storeInstituteDataForPreFilled = (instituteData) => (dispatch) => {
	if(instituteData && typeof instituteData != 'undefined')
	{
		return	dispatch({
					type : 'catpageInstitute',
					data : instituteData
			})
	}
	return	dispatch({
					type : 'catpageInstituteEmpty',
					data : {}
			})
}
