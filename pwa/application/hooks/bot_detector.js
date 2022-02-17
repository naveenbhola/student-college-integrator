import {getRequest, postRequest} from './../utils/ApiCalls';
import APIConfig from './../../config/apiConfig';

export function detect(req, res, next){
	var params =  new Array();
	let min = 1;
	let max = 1000000;
	params['rnd'] = Math.floor(Math.random() * (max - min + 1)) + min;
	params['ip']  =  req.headers['x-forwarded-for'] || req.connection.remoteAddress;
	if(params['ip'].substr(0, 7) == "::ffff:") {
  		params['ip'] = params['ip'].substr(7)
	}
	params['reqtype'] = 'getsid';
	params['ua']      = req.headers['user-agent'];
	let url = makeApiRequestUrl(params);
	let status    = '';
	let sessionId = '';
	getRequest(url).then((response) => {
		let status    = parseInt(response.data.data.status);
		var isGoodBot;
        if(status == 4){
                isGoodBot = 1;
        }
        else{
                isGoodBot = 0;
        }
        return response.data.data;
	}).then((cabisReturnData)=>{
		status    = cabisReturnData.status;
		sessionId = cabisReturnData.sid;
		let ip        = params['ip'];
		let userAgent = params['ua'];
		let postData = 'ip='+ip+'&userAgent='+userAgent+'&status='+status+'&sessionId='+sessionId;
		let securityCheckUrl  = "https://www.shiksha.com/CabisCall/detect/";
		return postRequest(securityCheckUrl, postData).then((response1)=>{
			return response1.data;
		}).
		catch((e)=>{
			console.log(e);
		});
	}).
	then((response)=>{
			if(response=='SECURITY_CHECK_REQUIRED'){
				let fullUrl = req.protocol + '://' + req.get('host') + req.originalUrl;
				let base64encodeUrl = Buffer.from(fullUrl).toString('base64');
				res.redirect("https://www.shiksha.com/SecurityCheck/index?sessionId="+sessionId+"&return="+base64encodeUrl);
			}else{
				next();
			}
		})
	.catch((e)=> {
		console.log(e);
	});
}

function makeApiRequestUrl(params)
{
    var paramArr = new Array();
    for(var paramKey in params) {
        paramArr.push(paramKey+'='+urlencode(params[paramKey]));
    }
    return APIConfig.GET_BOT_DETECTOR_URL+'?'+paramArr.join('&');
}

function urlencode (str) {
	str = (str + '');
	return encodeURIComponent(str)
    .replace(/!/g, '%21')
    .replace(/'/g, '%27')
    .replace(/\(/g, '%28')
    .replace(/\)/g, '%29')
    .replace(/\*/g, '%2A')
    .replace(/%20/g, '+');
}
