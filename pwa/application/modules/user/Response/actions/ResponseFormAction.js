import { getRequest , postRequest } from './../../../../utils/ApiCalls';
import config from './../../../../../config/config';

const axiosConfig = {
        	headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        	withCredentials: true
    	};
const domainName               = config().SHIKSHA_HOME;
const showResponseFormUrl      = domainName + '/registration/RegistrationAPIs/showResponseForm';
const getFormByClientCourseUrl = domainName + '/registration/RegistrationAPIs/getFormByClientCourse';
const getLocalitiesUrl		   = domainName + '/registration/RegistrationAPIs/getLocalities';
const createResponseUrl		   = domainName + '/registration/RegistrationAPIs/createResponse';
const isValidResponseUserUrl   = domainName + '/registration/RegistrationAPIs/isValidResponseUser';
const storeResponseDataUrl     = domainName + '/registration/RegistrationAPIs/storeResponseDataForUnverifiedUser';

export const showResponseForm = (params) => {
	
	return Promise.resolve(getRequest(showResponseFormUrl+"?"+params,axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in showResponseForm API ',err));

}

export const getFormByClientCourse = (params) => {
	
	return Promise.resolve(getRequest(getFormByClientCourseUrl+"?"+params,axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in getFormByClientCourse API ',err));

}

export const getLocalities = (params) => {
	
	return Promise.resolve(postRequest(getLocalitiesUrl,params,'',axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in getLocalities API ',err));

}

export const createResponse = (params) => {
	
	return Promise.resolve(postRequest(createResponseUrl,params,'',axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in createResponse API ',err));

}

export const isValidResponseUser = (params) => {
	
	return Promise.resolve(postRequest(isValidResponseUserUrl,params,'',axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in isValidResponseUser API ',err));

}

export const storeResponseData = (params) => {

	return Promise.resolve(postRequest(storeResponseDataUrl,params,'',axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in storeResponseData API ',err));

}
