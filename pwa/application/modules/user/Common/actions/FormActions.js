import { postRequest } from './../../../../utils/ApiCalls';
import config from './../../../../../config/config';

const axiosConfig = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            withCredentials: true
        };
const domainName                = config().SHIKSHA_HOME;
const checkForExistingUserUrl   = domainName + '/registration/RegistrationAPIs/checkForExistingUser';
const verifyUserForOTPUrl       = domainName + '/registration/RegistrationAPIs/verifyUserForOTP';
const verifyOTPCallUrl          = domainName + '/registration/RegistrationAPIs/verifyOTPCall';
const registerNewUserUrl        = domainName + '/registration/RegistrationAPIs/register';
const updateExistingUserUrl     = domainName + '/registration/RegistrationAPIs/updateUser';
const trackFieldDataUrl         = domainName + '/registration/RegistrationAPIs/trackFieldData';
const reportErrorOnForm			= domainName + '/registration/RegistrationAPIs/reportErrorOnForm';

export const checkForExistingUser = (params) => {
	
	return Promise.resolve(postRequest(checkForExistingUserUrl,params,'',axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in checkForExistingUser API ',err));

}

export const verifyUserForOTP = (params) => {
	
	return Promise.resolve(postRequest(verifyUserForOTPUrl,params,'',axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => {
	    	console.log('error in verifyUserForOTP API ',err);
	    	var errValues = Object.values(err);
	    	var data = "url=verifyUserForOTP&error="+errValues[1]['statusText'];
	    	return reportError(data);
	    });

}

export const verifyOTPCall = (params) => {
	
	return Promise.resolve(postRequest(verifyOTPCallUrl,params,'',axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => {
	    	console.log('error in verifyOTPCall API ',err);
	    	var errValues = Object.values(err);
	    	var data = "url=verifyOTPCall&error="+errValues[1]['statusText'];
	    	return reportError(data);
	    });

}

export const registerNewUser = (params) => {
	
	return Promise.resolve(postRequest(registerNewUserUrl,params,'',axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in registerNewUser API ',err));

}

export const updateExistingUser = (params) => {
	
	return Promise.resolve(postRequest(updateExistingUserUrl,params,'',axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in updateExistingUser API ',err));

}

export const trackFieldData = (params) => {
	
	return Promise.resolve(postRequest(trackFieldDataUrl,params,'',axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in trackFieldData API ',err));

}

function reportError(data){
	return Promise.resolve(postRequest(reportErrorOnForm,data,'',axiosConfig))
	.then((response) => {
		document.getElementsByTagName("body")[0].innerHTML = '<div class="error-msg-notifier active"><div class="err-msg-box"><p class="blackBgText">Opps! an error occured</p><a onclick="window.location.reload();" class="blackBgLink">Try Again</a></div></div>' + document.getElementsByTagName("body")[0].innerHTML;
		setTimeout(function(){ window.location.reload(); }, 5000);
		return "error";
    })
    .catch((error) => console.log('error in report Error API ',error));
}
