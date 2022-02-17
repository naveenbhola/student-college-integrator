import express from 'express';
import path from 'path';
//import apm from 'elastic-apm-node/start';
const ejs = require("ejs").__express;

import React from 'react';
import {Route, Router, createMemoryHistory} from 'react-router';

import {Redirect, StaticRouter, Switch} from 'react-router-dom';
import {renderToString} from 'react-dom/server';
import config from './../../config/config.js';

import renderer from './../utils/renderer';
import ampRenderer from './../utils/ampRenderer';
import createStore from './../utils/createStore';
import {addQueryString,removeParamFromUrl} from './../utils/commonHelper';

import logger from 'morgan';
import fs from 'fs';

import {fetchInitialData,clearReduxData,fetchAmpHamburgerData,fetchAlumniData,fetchCampusRepData,fetchImportantDatesData,getFooterLinks,getGNBLinks,getGNBLinksStaticObj, getFooterLinksStaticObj} from './../modules/common/actions/commonAction';
import {fetchCourseDetailPageData} from './../modules/listing/course/actions/CourseDetailAction';
import { fetchCategoryPageData } from './../modules/listing/categoryList/actions/CategoryPageAction';
import { fetchCourseHomePageData } from './../modules/listing/courseHomePage/actions/CourseHomePageAction';
import {dfpBannerConfig,clearDfpBannerConfig} from './../modules/reusable/actions/commonAction';

import {fetchInstituteDetailPageData} from './../modules/listing/institute/actions/InstituteDetailAction';
import { fetchAllCourseData,fetchAdmissionPageData,fetchPlacementPageData,fetchCutOffPageData} from './../modules/listing/instituteChildPages/actions/AllChildPageAction';
import Loadable from 'react-loadable';
import cookieParser from 'cookie-parser';
import session from 'express-session';
import csurf from 'csurf';
import {getHeaders} from './nodeHelper';
import APIConfig from './../../config/apiConfig';
import {getRequest, postRequest} from './../utils/ApiCalls';
import {removeDomainFromUrl} from './../utils/urlUtility';
import {fetchCollegeSRPData, fetchExamSRPData, fetchTrendingSearchesData} from "../modules/search/actions/SearchAction";
import {fetchExamPageData} from './../modules/examPage/actions/ExamPageAction';
import {fetchCollegePredictorData, fetchCollegePredictorResultData} from './../modules/predictors/CollegePredictor/actions/CollegePredictorAction';
import {makeInputObjectForResultPage} from './../modules/predictors/CollegePredictor/utils/collegePredictorHelper';
import {fetchRecommendationData} from "../modules/listing/recommendationPage/actions/RecommendationPageAction";

const rankingRoutes = require('./routes/rankingRoutes');
var url = require('url');
var shikshaConfig = config();
var isServerCall = shikshaConfig.API_SERVER_CALL;
let footerLinksObj = null;
let gnbLinksObj = null;
var ampHamburgerHtml = null;

var app = express();
app.disable('x-powered-by');

logger.token('device-type', function getDeviceType(req){
	let deviceType = (req['headers']['x-mobile'] == "True" ) ? 'mobile' : 'desktop';
	return deviceType;
});

logger.token('remote-addr', function getRemoteAddress(req){
	let ip = req.ip;
	if (ip.substr(0, 7) == "::ffff:") {
  		 return ip.substr(7);
	}
	return ip;
})

logger.token('http_host', function getHttpHost(req){

	return req['headers'].host;
});

logger.token('x-request-id', function getXRequestId(req){
	return req['headers']['x-request-id'];
});

logger.token('x-transaction-id', function getXtransactionId(req){
	return req['headers']['x-transaction-id'];
});

logger.token('realclfdate', function (req, res) {
    	var clfmonth = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
 	var pad2 = function(num) {
		var str = String(num);
		return (str.length === 1 ? '0' : '') + str;
	};
	var dateTime = new Date();
	var date = dateTime.getDate()	;
	var hour = dateTime.getHours();
	var mins = dateTime.getMinutes();
	var secs = dateTime.getSeconds();
	var year = dateTime.getFullYear();
	var timezoneofset = dateTime.getTimezoneOffset();
	var sign = timezoneofset > 0 ? '-' : '+';
	timezoneofset = parseInt(Math.abs(timezoneofset)/60);
	var month = clfmonth[dateTime.getUTCMonth()];

	return pad2(date) + '/' + month + '/' + year
	+ ':' + pad2(hour) + ':' + pad2(mins) + ':' + pad2(secs)
	+ ' '+sign+pad2(timezoneofset)+'00';
});

var loggerFormat = ':remote-addr [:realclfdate] :http_host :response-time ":method :url" :status :res[content-length] :referrer :user-agent :x-request-id :x-transaction-id :device-type';

var logPath = "/var/log/node/logs/";

app.use(logger(loggerFormat,
	{
	      stream: require('file-stream-rotator').getStream({
	      filename: path.join(logPath, 'access_%DATE%.log'),
	      frequency: 'daily',
	      verbose: false,
	      date_format: 'YYYYMMDD'
	    })
	}
));
app.use(cookieParser());
app.use(session({
    secret: 'mnbvcxxxxzlkjhgfdsapoiuytrewq', // just a long random string
    resave: false,
    saveUninitialized: true
}));
app.use(csurf({ cookie: true }));
app.use('/pwa/public/',express.static(path.resolve('public')));
if(shikshaConfig.JS_DOMAIN === '') {
    app.use('/public/', express.static(path.resolve('public')));
}

if(process.env.NODE_ENV !='production'){
	 app.use('/pwa/reports/',express.static(path.resolve('reports')));
}

app.set('view engine', 'ejs');
app.engine('.ejs', ejs);
if(process.env.NODE_ENV !='production'){
	app.use('/pwa/report/',express.static(path.resolve('report')));
}

 app.get('/service-worker([.]{0,1})([a-zA-Z0-9\-\ ]{0,}).js', (req,res) => {
     res.sendFile(path.join(process.cwd(),req.path));
 });

app.get('/manifest.json', (req,res) => {
		res.sendFile(path.join(process.cwd(),'manifest.json'));
});

var logPerformanceUrl = "/LogPerformance/logPerformace";
var serverStartTime = '';
var fromAmpPage=false;

global.isMobileRequest = true;
global.isUserLoggedIn = false;

var applyHooks = function(req, res, next){
	serverStartTime = Date.now();
	require('./../hooks/visitor_trackers').track_visitor(req, res);
	global.isMobileRequest = require('./../hooks/get_mobile_useragent').get_mobile_useragent(req, res, config);
	require('./../hooks/set_siteview').set_siteview(req, res);
	if(process.env.NODE_ENV=='production' && APIConfig.BOT_DETECTION_ENABLED){

		//require('./../hooks/bot_detector').detect(req, res, next);
		next();
	}else{
		next();
	}
}

app.use(applyHooks);

var gnbLinksData = function(req, res, next){
	const url = APIConfig.GET_GNB_HEADER_LINKS;
	if(global.isMobileRequest){
		const gnbData = store.dispatch(getGNBLinks(url,'mobile'));
		Promise.resolve(gnbData).then((response) => {
			next();
		});
	}else{
		let gnbData;
		if(gnbLinksObj === null)
			gnbData = store.dispatch(getGNBLinks(url,'desktop'));
		else
			gnbData = store.dispatch(getGNBLinksStaticObj(gnbLinksObj));
		Promise.resolve(gnbData).then((response) => {
			gnbLinksObj = store.getState().gnbLinks;
			next();
		});
	}
}

app.use(gnbLinksData);

var footerLinksData = function(req, res, next){
	const url = APIConfig.GET_FOOTER_LINKS;
	if(global.isMobileRequest){
		const footerData = store.dispatch(getFooterLinks(url,'mobile'));
		Promise.resolve(footerData).then((response) => {
			next();
		});
	}else{
		let footerData;
		if(footerLinksObj === null)
			footerData = store.dispatch(getFooterLinks(url,'desktop'));
		else
			footerData = store.dispatch(getFooterLinksStaticObj(footerLinksObj));
		Promise.resolve(footerData).then((response) => {
			footerLinksObj = store.getState().footerLinks;
			next();
		});
	}
}

app.use(footerLinksData);

var checkUserLogin = function(req, res, next){
	global.isUserLoggedIn = !!(req.cookies && req.cookies.user !== undefined && req.cookies.user != null && req.cookies.user !== '');
	next();
}

app.use(checkUserLogin);

const store = createStore();

app.get('/(*)/ranking/(*)/(*)', (req,res) => {
	let extraParams = {};
	extraParams.serverStartTime   = serverStartTime;
	extraParams.logPerformanceUrl = logPerformanceUrl;
	extraParams.template = getTemplate();
	rankingRoutes(req, res, store, extraParams);
});

app.get('/', (req,res) => {
	let url = '';
	if(global.isMobileRequest){
		url = (isServerCall) ? APIConfig.GET_HOME_LATESTARTICLE_SERVER : APIConfig.GET_LATESTARTICLESANDCOUNTPARAMS;
	}else{
		url = (isServerCall) ? APIConfig.GET_DESK_HOMEPAGE_SERVER_DATA : APIConfig.GET_DESK_HOMEPAGE_CLIENT_DATA;
	}
	var headerConfig= getHeaders(req);
	const HomePageData = store.dispatch(fetchInitialData(url,'homepageData',headerConfig));
	Promise.resolve(HomePageData).then((data) => {
		store.dispatch(clearReduxData('except_homepagedata'));
		if(!store.getState().hpdata || (store.getState().hpdata && store.getState().hpdata.statusCode == 404)){
			send404Page(req,res);
		}else{
			res.render(getTemplate(),renderer(req, store,'homePage'));
		}
		let endserverTime = Date.now();
		let postData = 'serverStartTime='+serverStartTime+'&endserverTime='+endserverTime+'&requestedUrl='+req.url+'&module=homepage&teamname=mobile_app';
		if(endserverTime-serverStartTime>500){
			postRequest(shikshaConfig.SHIKSHA_HOME+logPerformanceUrl, postData).then((response)=>{
				//console.log(response);
			});
		}
	});
});

app.get('/clearGNBFooterData',  (req,res,next) => {
	const footerData = store.dispatch(getFooterLinks(url,'desktop'));
	Promise.resolve(footerData).then((response) => {
		footerLinksObj = store.getState().footerLinks;
	});
	const gnbData = store.dispatch(getGNBLinks(url,'desktop'));
	Promise.resolve(gnbData).then((response) => {
		gnbLinksObj = store.getState().gnbLinks;
	});
	send404Page(req,res,true);
});

app.get(['/([a-zA-Z0-9\-\ ]{0,})(*)-exam','/([a-zA-Z0-9\-\ ]{0,})(*)-exam-(homepage|admit-card|answer-key|dates|application-form|counselling|cutoff|pattern|results|question-papers|slot-booking|syllabus|vacancies|call-letter|news|preptips)','([a-zA-Z0-9\-\ ]{0,})(*)/exams/([a-zA-Z0-9\-\ ]{0,})(*)'], (req,res) => {
	let headerConfig= getHeaders(req);
	let url = req.path;
	let paramsObj = {};
	paramsObj['url'] = url;
	if(typeof req.query.course != 'undefined' && req.query && req.query.course){
		if(req.query.course.match(/^-{0,1}\d+$/) == null){ //check for pure integer
			return res.redirect(301, paramsObj['url']);
		}
		paramsObj['groupId'] = req.query.course;
	}
	let examName = '';
	if(url.indexOf('-exam')!=-1){
		let urlString   = req.path.split('-exam');
		let splitParams    = urlString[0].split('/');
		examName = splitParams[(splitParams.length-1)]
	}else{
		let splitParams   = req.path.split('/');
		examName      = splitParams[2];
	}
	let data = Buffer.from(JSON.stringify(paramsObj)).toString('base64');
	const examPageData = store.dispatch(fetchExamPageData(data,headerConfig,isServerCall,examName));
	Promise.resolve(examPageData).then(data => {
		store.dispatch(clearReduxData('except_examPageData'));
		if(!store.getState().examPageData || (store.getState().examPageData && store.getState().examPageData.statusCode == 404)){
			send404Page(req,res,true);
		}else if((store.getState().examPageData && store.getState().examPageData.statusCode ==  301)){
			res.redirect(301, store.getState().examPageData.url);
		}else if((store.getState().examPageData && store.getState().examPageData.statusCode ==  302)){
			res.redirect(302, store.getState().examPageData.url);
		}else{
			res.render(getTemplate(),renderer(req, store,'examPage'));
		}
	});
});

app.get(['/listing/pdfgenerator/admission/(:id([0-9]+))', '/listing/pdfgenerator/courses/(:id([0-9]+))','/listing/pdfgenerator/cutoff/(:id([0-9]+))'], (req,res,next) => {
		console.log("Inside listing generator path");
		var keyMapping = {city : 'cityId',locality : 'localityId','year':'courseCompletionYear'};
		let reqParams = 'instituteId='+req.params.id;
		var url = req.path;
		if(isNaN(req.params.id)){
			send404Page(req,res,true);
		}
		for(var query in req.query)
		{
			if(query in keyMapping){
				reqParams += '&'+keyMapping[query]+'='+req.query[query];
			}
			else{
				reqParams += '&'+query+'='+req.query[query];
			}
		}
		//addQueryString();
		let paramsObj = {};
		var childPageData = {};
		var headerConfig= getHeaders(req);
		if(req.path.indexOf('/admission') != -1){

			//let reqParams = 'instituteId='+req.params.id;
			childPageData  = store.dispatch(fetchAdmissionPageData(reqParams, headerConfig, isServerCall));
							store.dispatch(clearReduxData('except_allChildPageData'));
		}
		else if(req.path.indexOf('/courses') != -1){
			for(var key in req.query)
			{
				paramsObj[key] = req.query[key];
			}
			let length = url.length;
			let lastCharUrl = url[length-1];
			// if(!isNaN(lastCharUrl)){
			//         var urlArray = url.split('-');
			//         paramsObj['pn'] = parseInt(urlArray[urlArray.length-1]); 
			// }  
			if(typeof req.path != 'undefined' ){
				paramsObj['url'] =  '/college/abc-'+req.params.id+'/courses';

			}
			paramsObj['instituteId'] = req.params.id;
			var url  = JSON.stringify(paramsObj);
			var data = Buffer.from(url).toString('base64');
			console.log(data);
			childPageData  = store.dispatch(fetchAllCourseData(data, headerConfig, isServerCall));
							store.dispatch(clearReduxData('except_allChildPageData'));
		}else if(req.path.indexOf('/cutoff') != -1){
			console.log("Inside cutoff path");
			// if(req.params["2"] != '' ){
			// 	paramsObj['examName'] =  req.params['2'].slice(1);
			// }
			for(var key in req.query)
			{
				if(key == 'courseId'){
				  paramsObj['boostCourseId'] = req.query[key];//queryParams.getAll(key)[0];
				}else{
					paramsObj[key] = req.query[key];
				}	
			}
			console.log(paramsObj);
			let length = url.length;
			let lastCharUrl = url[length-1];
			//if(!isNaN(lastCharUrl)){
			//        var urlArray = url.split('-');
			//        paramsObj['pn'] = parseInt(urlArray[urlArray.length-1]); 
			//}  
			if(typeof req.path != 'undefined' ){
				paramsObj['url'] =  req.path;

			}
			paramsObj['instituteId'] = req.params.id;
			var reqString  = JSON.stringify(paramsObj);
			var data = Buffer.from(reqString).toString('base64');
			console.log("calling cutoff api");
			childPageData  = store.dispatch(fetchCutOffPageData(data, headerConfig, isServerCall));
							store.dispatch(clearReduxData('except_allChildPageData'));
		}
		Promise.resolve(childPageData).then((data) => { 
			var redirectUrl = '';
			var requestParams = '';
		
			if(req.path.indexOf('/admission') != -1){
				if(store.getState().childPageData && req.query['streamId']){
					if(store.getState().childPageData.selectedStreamId){
						var operator = requestParams == ''? '?':'&';
						requestParams += operator+'streamId='+store.getState().childPageData.selectedStreamId;
					}
					if(store.getState().childPageData.selectedCourseId){
						var operator = requestParams == ''? '?':'&';
						requestParams += operator+'courseId='+store.getState().childPageData.selectedCourseId;
					}
				}
			}
			if(store.getState().childPageData && store.getState().childPageData.seoUrl){
				redirectUrl  = store.getState().childPageData.seoUrl;
				if(req.path.indexOf('/admission') != -1){
					redirectUrl = redirectUrl + requestParams;
					if(req.query['courseId'] && typeof req.query['streamId'] =='undefined'){
				    	// res.redirect(301,redirectUrl);  
				    }
				}
			}

			res.render(getTemplate(),renderer(req, store,'pdfGenerator'));
			let endserverTime = Date.now();
			if(endserverTime-serverStartTime>500){
				let postData = 'serverStartTime='+serverStartTime+'&endserverTime='+endserverTime+'&requestedUrl='+req.url+'&module=institutepage&teamname=listings';
				postRequest(shikshaConfig.SHIKSHA_HOME+logPerformanceUrl, postData).then((response)=>{
				});
			}
		}).catch(function(err){
			console.log(err);
			sendSorryPage(req,res);
		});
});

app.get(['/college/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))/courses','/university/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))/courses','/college/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))/courses(.{0,}*)','/university/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))/courses(.{0,}*)', '/college/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))/admission(.{0,}*)', '/university/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))/admission(.{0,}*)', '/college/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))/placement(.{0,}*)', '/university/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))/placement(.{0,}*)','/college/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))/cutoff(.{0,}*)', '/university/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))/cutoff(.{0,}*)'], (req,res,next) => {
		var keyMapping = {city : 'cityId',locality : 'localityId','year':'courseCompletionYear'};
		

		let reqParams = 'instituteId='+req.params.id;
		var url = req.path;
		console.log("GET",req.path);
		if(isNaN(req.params.id)){
			send404Page(req,res,true);
		}
		for(var query in req.query)
		{
			if(query in keyMapping){
				reqParams += '&'+keyMapping[query]+'='+req.query[query];
			}
			else{
				reqParams += '&'+query+'='+req.query[query];
			}
		}
		//addQueryString();
		let paramsObj = {};
		var childPageData = {};
		var headerConfig= getHeaders(req);
		if(req.path.indexOf('/placement') != -1){

			paramsObj['instituteId'] = req.params.id;
			if(req.params["2"] != '' ){
				paramsObj['baseCourseNameFromUrl'] =  req.params['2'];
			}
			for(var key in req.query)
			{
				paramsObj[keyMapping[key]] = req.query[key];
			}
			var reqString  = JSON.stringify(paramsObj);
			var data = Buffer.from(reqString).toString('base64');
			


			childPageData  = store.dispatch(fetchPlacementPageData(data, headerConfig, isServerCall));
							store.dispatch(clearReduxData('except_allChildPageData'));
		}
		else if(req.path.indexOf('/cutoff') != -1){
			if(req.params["2"] != '' && req.params["2"].charAt(0) == '-'){
				paramsObj['examName'] =  req.params['2'].slice(1);
				paramsObj['examName'] =  paramsObj['examName'].split('/')[0];
			}
			for(var key in req.query)
			{
				if(key == 'courseId'){
				  paramsObj['boostCourseId'] = req.query[key];//queryParams.getAll(key)[0];
				}else{
					paramsObj[key] = req.query[key];
				}
			}
			console.log(paramsObj);
			let length = url.length;
			let lastCharUrl = url[length-1];
			//if(!isNaN(lastCharUrl)){
			//        var urlArray = url.split('-');
			//        paramsObj['pn'] = parseInt(urlArray[urlArray.length-1]); 
			//}  
			if(typeof req.path != 'undefined' ){
				paramsObj['url'] =  req.path;

			}
			paramsObj['instituteId'] = req.params.id;
			var reqString  = JSON.stringify(paramsObj);
			var data = Buffer.from(reqString).toString('base64');
			


			childPageData  = store.dispatch(fetchCutOffPageData(data, headerConfig, isServerCall));
							store.dispatch(clearReduxData('except_allChildPageData'));
		}
		else if(req.path.indexOf('/admission') != -1){

			//let reqParams = 'instituteId='+req.params.id;
			childPageData  = store.dispatch(fetchAdmissionPageData(reqParams, headerConfig, isServerCall));
							store.dispatch(clearReduxData('except_allChildPageData'));
		}
		else{
			for(var key in req.query)
			{
				paramsObj[key] = req.query[key];
			}
			let length = url.length;
			let lastCharUrl = url[length-1];
			if(!isNaN(lastCharUrl)){
			        var urlArray = url.split('-');
			        paramsObj['pn'] = parseInt(urlArray[urlArray.length-1]); 
			}  
			if(typeof req.path != 'undefined' ){
				paramsObj['url'] =  req.path;

			}
			paramsObj['instituteId'] = req.params.id;
			var url  = JSON.stringify(paramsObj);
			var data = Buffer.from(url).toString('base64');
			childPageData  = store.dispatch(fetchAllCourseData(data, headerConfig, isServerCall));
							store.dispatch(clearReduxData('except_allChildPageData'));
		}
		Promise.resolve(childPageData).then((data) => { 
			var redirectUrl = '';
			var requestParams = '';
		
			if(req.path.indexOf('/admission') != -1){
				if(store.getState().childPageData && req.query['streamId']){
					if(store.getState().childPageData.selectedStreamId){
						var operator = requestParams == ''? '?':'&';
						requestParams += operator+'streamId='+store.getState().childPageData.selectedStreamId;
					}
					if(store.getState().childPageData.selectedCourseId){
						var operator = requestParams == ''? '?':'&';
						requestParams += operator+'courseId='+store.getState().childPageData.selectedCourseId;
					}
				}
			}
			if(store.getState().childPageData && store.getState().childPageData.seoUrl){
				redirectUrl  = store.getState().childPageData.seoUrl;
				if(req.path.indexOf('/admission') != -1){
					redirectUrl = redirectUrl + requestParams;
					if(req.query['courseId'] && typeof req.query['streamId'] =='undefined'){
				    	res.redirect(301,redirectUrl);  
				    }
				}
			}

		if(!store.getState().childPageData || (store.getState().childPageData && (store.getState().childPageData.statusCode == 404 || store.getState().childPageData.statusCode == 400 || store.getState().childPageData.statusCode == 500  )) || store.getState().childPageData.seoUrl == null || store.getState().childPageData.seoUrl == "")
			{
				send404Page(req,res,true);
			}
			else if((store.getState().childPageData && store.getState().childPageData.statusCode ==  301))
			{
				res.redirect(301,redirectUrl);
			}
			else if((store.getState().childPageData && typeof store.getState().childPageData.seoUrl != 'undefined' && store.getState().childPageData.seoUrl != req.path))
			{
				let queryString = '';
				if(require('url').parse(req.url).query!='' && require('url').parse(req.url).query!='null' && require('url').parse(req.url).query!=null) {
                    queryString = "?" + require('url').parse(req.url).query;
                }   
				if(req.path.indexOf('/admission') != -1){
					res.redirect(301, redirectUrl);
				}				
				if(req.path.indexOf('/placement') != -1){
					res.redirect(301, redirectUrl);
				}				
				if(req.path.indexOf('/cutoff') != -1){
					res.redirect(301, redirectUrl);
				}
				else{
					res.redirect(301,redirectUrl+queryString);
				}


			}
			else{
				res.render(getTemplate(),renderer(req, store,'allChildPage'));
			}
			let endserverTime = Date.now();
			if(endserverTime-serverStartTime>500){
				let postData = 'serverStartTime='+serverStartTime+'&endserverTime='+endserverTime+'&requestedUrl='+req.url+'&module=institutepage&teamname=listings';
				postRequest(shikshaConfig.SHIKSHA_HOME+logPerformanceUrl, postData).then((response)=>{
				});
			}
		}).catch(function(err){
			console.log(err);
			sendSorryPage(req,res);
		});
});

app.get('/([a-zA-Z0-9\-\ ]{0,})(*)-chp', (req,res) => {
	let paramsObj = {};
	/*for(var key in req.query)
	{
		paramsObj[key] = req.query[key];
	}*/
	if(typeof req.query != 'undefined' && req.query['isPdfCall'] == 1){
		if(typeof req.query['now'] != 'undefined'){
			paramsObj['now']= req.query['now'];
		}
		else{
			paramsObj['now']= Date.now();
		}
	}
	paramsObj['url'] = req.path;

	var url  = JSON.stringify(paramsObj);
	var data = Buffer.from(url).toString('base64');
	var headerConfig= getHeaders(req);
	const courseHomePageData = store.dispatch(fetchCourseHomePageData(data,headerConfig,isServerCall));
	Promise.resolve(courseHomePageData).then((data) => {
		store.dispatch(clearReduxData('except_courseHomePageData'));
		if(!store.getState().courseHomePageData || (store.getState().courseHomePageData && store.getState().courseHomePageData.statusCode == 404)){
			send404Page(req,res);
		}else if((store.getState().courseHomePageData && store.getState().courseHomePageData.statusCode ==  301)){
			res.redirect(301, store.getState().courseHomePageData.url);
		}else{
    		res.render(getTemplate(),renderer(req, store, 'courseHomePage'));
		}
    }).catch(function(err){
        sendSorryPage(req,res);
    });
});

app.get(['/([a-zA-Z0-9\-\ ]{0,})/course/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))','/([a-zA-Z0-9\-\ ]{0,})/([a-zA-Z0-9\-\ ]{0,})/course/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))','getListingDetail([a-zA-Z0-9\-\ ]{0,})/course([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))'], (req,res,next) => {
		var reqParams = 'courseId='+req.params.id;
		var keyMapping = {city : 'cityId',locality : 'localityId'};
		fromAmpPage = false;
		if(isNaN(req.params.id))
		{
			send404Page(req,res,true);
		}
		for(var query in req.query)
		{
			if(query in keyMapping)
			{
				reqParams += '&'+keyMapping[query]+'='+req.query[query];
			}
		}

		var headerConfig= getHeaders(req);
		const courseData = store.dispatch(fetchCourseDetailPageData(reqParams, headerConfig, isServerCall));						
		Promise.resolve(courseData).then((data) => {
			store.dispatch(clearReduxData('except_coursepagedata'));
			if(!store.getState().courseData || (store.getState().courseData && store.getState().courseData.statusCode == 404))
			{
				send404Page(req,res);
			}
			else if((store.getState().courseData && store.getState().courseData.statusCode ==  301))
			{
				res.redirect(301, store.getState().courseData.redirectUrl);
			}
			else if((store.getState().courseData && store.getState().courseData.statusCode ==  302))
			{
				res.redirect(302, store.getState().courseData.redirectUrl);
			}
			else if((store.getState().courseData && typeof store.getState().courseData.courseUrl != 'undefined' && store.getState().courseData.courseUrl != req.path))
			{
				var reqParams = '';
				for(var query in req.query)
				{
					if(query in keyMapping)
					{
						reqParams += '&'+keyMapping[query]+'='+req.query[query];
					}
					else
					{
						reqParams += '&'+query+'='+req.query[query];
					}
				}
				if(reqParams != '')
				{
					reqParams = '?'+reqParams;
				}

				res.redirect(301, store.getState().courseData.courseUrl+reqParams);

			}
			else
				res.render(getTemplate(),renderer(req, store,'coursePage'));
			
			let endserverTime = Date.now();
			if(endserverTime-serverStartTime>500){

				let postData = 'serverStartTime='+serverStartTime+'&endserverTime='+endserverTime+'&requestedUrl='+req.url+'&module=coursepage&teamname=listings';
				postRequest(shikshaConfig.SHIKSHA_HOME+logPerformanceUrl, postData).then((response)=>{
				});
			}
		}).catch(function(err){
			sendSorryPage(req,res);
		});
});


app.get(['/university/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))','/college/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))'], (req,res,next) => {
	var reqParams = 'instituteId='+req.params.id;
	var serverStart = Date.now();
	fromAmpPage = false;
		var keyMapping = {city : 'cityId',locality : 'localityId'};
		if(isNaN(req.params.id)) {
			send404Page(req,res,true);
		}
		for(var query in req.query){
			if(query in keyMapping)
			{
				reqParams += '&'+keyMapping[query]+'='+req.query[query];
			}
		}
		var headerConfig= getHeaders(req);

		const instituteData = store.dispatch(fetchInstituteDetailPageData(reqParams, headerConfig, isServerCall));

		Promise.resolve(instituteData).then((data) => {
			var serverEnd = Date.now();
			console.log("**** Api Server Time",(serverEnd - serverStart));
			store.dispatch(clearReduxData('except_institutepagedata'));
			if(!store.getState().instituteData || (store.getState().instituteData && store.getState().instituteData.status == 404) || store.getState().instituteData.seoUrl == null || store.getState().instituteData.seoUrl == "")
			{
				send404Page(req,res,true);
			}
			else if((store.getState().instituteData && store.getState().instituteData.status ==  301))
			{
				res.redirect(301, store.getState().instituteData.seoUrl);
			}
			else if((store.getState().instituteData && store.getState().instituteData.status ==  302))
			{

				res.redirect(302, store.getState().instituteData.seoUrl);
			}
			else if((store.getState().instituteData && typeof store.getState().instituteData.seoUrl != 'undefined' && store.getState().instituteData.seoUrl != req.path))
			{
				var reqParams = '';
				for(var query in req.query)
				{
					if(query in keyMapping)
					{
						reqParams += '&'+keyMapping[query]+'='+req.query[query];
					}
					else
					{
						reqParams += '&'+query+'='+req.query[query];
					}
				}
				if(reqParams != '')
				{
					reqParams = '?'+reqParams;
				}


				res.redirect(301, store.getState().instituteData.seoUrl+reqParams);
			}
			else
				res.render(getTemplate(),renderer(req, store,'institutePage'));
			
			let endserverTime = Date.now();
			if(endserverTime-serverStartTime>500){
				let postData = 'serverStartTime='+serverStartTime+'&endserverTime='+endserverTime+'&requestedUrl='+req.url+'&module=institutepage&teamname=listings';
				postRequest(shikshaConfig.SHIKSHA_HOME+logPerformanceUrl, postData).then((response)=>{
	    			//console.log(response);
				});
			}
		}).catch(function(err){
			console.log(err);
			sendSorryPage(req,res);
		});
});

app.get('/searchLayer', (req,res) => {
	const headerConfig= getHeaders(req);
	const trendingData = store.dispatch(fetchTrendingSearchesData(isServerCall, headerConfig));
	Promise.resolve(trendingData).then((data) => {
		res.render(getTemplate(),renderer(req, store,'searchLayer'));
	}).catch(function(err){
		res.render(getTemplate(),renderer(req, store,'searchLayer'));
	});

});
app.get(['/colleges/(.{0,}*)','/(.{0,}*)/colleges/(.{0,}*)','/colleges(.{0,}*)'], (req,res,next) => {
		const randomNumber = Math.floor(1000 + Math.random() * 9000);
		let ctpgRandom = req.cookies.ctpgRandom;
		if(typeof req.cookies.ctpgRandom=='undefined' || req.cookies.ctpgRandom==''){
			res.cookie('ctpgRandom', randomNumber, {domain : shikshaConfig.COOKIE_DOMAIN});
			ctpgRandom = randomNumber;
		}
		fromAmpPage = false;
		let paramsObj = {};
		let firstPage = true;
		const locationRegex = /^\/colleges/;
		const  locationPage = locationRegex.test(req.path);
		let isCached = true;
		for(let key in req.query) {
			isCached = false;
			paramsObj[key] = req.query[key];
		}
		paramsObj['url'] = req.path;
		if(paramsObj['rf'] !== 'filters')
			paramsObj['fr'] = "true";
		if(paramsObj['uaf']){
			if(Array.isArray(paramsObj['uaf']) && paramsObj['uaf'].indexOf('undefined') !== -1) {
				delete paramsObj['uaf'][paramsObj['uaf'].indexOf('undefined')];
			} else if(!Array.isArray(paramsObj['uaf'])){
				delete paramsObj['uaf'];
			}
		}
		if(paramsObj['uaf'] === ''){
			delete paramsObj['uaf'];
		}
		let length = req.path.length;
		let lastCharUrl = req.path[length-1];
		if(!isNaN(lastCharUrl) && parseInt(lastCharUrl) > 1){
            firstPage = false;
		}
    	const showPCW = firstPage && !locationPage;
		const headerConfig= getHeaders(req);
		console.time(req['headers']['x-transaction-id'] + ':::' + req['headers']['x-mobile'] + ':::API BE:::');
		const categoryData = store.dispatch(fetchCategoryPageData(paramsObj,headerConfig,isServerCall, showPCW, ctpgRandom, isCached, firstPage));
		Promise.resolve(categoryData).then((data) => {
			console.timeEnd(req['headers']['x-transaction-id'] + ':::' + req['headers']['x-mobile'] + ':::API BE:::');
			console.time(req['headers']['x-transaction-id'] + ':::' + req['headers']['x-mobile'] + ':::NODE PROCESSING:::');
			store.dispatch(clearReduxData('except_categorypagedata'));
			if(!store.getState().categoryData || (store.getState().categoryData && store.getState().categoryData.requestData &&
				!store.getState().categoryData.requestData.categoryData)) {
				send404Page(req,res);
			}
			else {
				let queryString = '';
				if(require('url').parse(req.url).query!='' && require('url').parse(req.url).query!='null' &&
					require('url').parse(req.url).query!=null) {
                    queryString = "?" + require('url').parse(req.url).query;
                }
                let totalCount = store.getState().categoryData.totalInstituteCount;
                if(totalCount <10 && store.getState().categoryData.fallbackResultCount && store.getState().categoryData.fallbackResultCount > 0){
                    totalCount += store.getState().categoryData.fallbackResultCount;
                }
				let maxPages = Math.ceil(totalCount/30);
				let currentPageNum = store.getState().categoryData.requestData.pageNumber;
	    		let basePageUrl = store.getState().categoryData.requestData.categoryData.url;
				if(currentPageNum > maxPages && maxPages !=0) { //safe check
					res.redirect(301, basePageUrl+queryString);
	    		}else if(store.getState().categoryData && store.getState().categoryData.requestData && !store.getState().categoryData.requestData.categoryData && store.getState().categoryData.requestData.show404)
	    		{
	    			send404Page(req,res);
	    		}
	    		else {
					res.render(getTemplate(),renderer(req, store, 'categoryPage'));
	    		}
				console.timeEnd(req['headers']['x-transaction-id'] + ':::' + req['headers']['x-mobile'] + ':::NODE PROCESSING:::');
	    		let endserverTime = Date.now();
			if(endserverTime-serverStartTime>500){
				let postData = 'serverStartTime='+serverStartTime+'&endserverTime='+endserverTime+'&requestedUrl='+req.url+'&module=categorypage&teamname=listings';
				postRequest(shikshaConfig.SHIKSHA_HOME+logPerformanceUrl, postData).then((response)=>{
				});
			}
			}
		}).catch(function(err){
			console.log('some exception', err);
			send404Page(req,res, true);
		});
});

app.get('/search/exam(.{0,}*)', (req,res,next) => {
	let paramsObj = {};
	for (let key in req.query) {
		paramsObj[key] = req.query[key];
	}
	const data = Buffer.from(JSON.stringify(paramsObj)).toString('base64');
	const headerConfig = getHeaders(req);
	const srpData = store.dispatch(fetchExamSRPData(data, headerConfig, isServerCall));

	Promise.resolve(srpData).then((data) => {
		store.dispatch(clearReduxData('except_examSRPData'));
		if (!store.getState().examData || !store.getState().examData.tupleData) {
			send404Page(req, res);
		} else {
			let queryString = '';
			if (require('url').parse(req.url).query != '' && require('url').parse(req.url).query != 'null' && require('url').parse(req.url).query != null) {
				queryString = "?" + require('url').parse(req.url).query;
			}
			let maxPages = Math.ceil(store.getState().examData.resultCount/20);
			let currentPageNum = store.getState().examData.paginationData.currentPageNUmber;
			if(maxPages > 0 && currentPageNum > maxPages) { //safe check
				let queryParams = require('querystring').parse(require('url').parse(req.url).query);
				queryParams['pn'] = 1;
				let queryStr = require('querystring').stringify(queryParams);
				res.redirect(301, '/search/exam?'+queryStr);
			} else{
				res.render(getTemplate(), renderer(req, store, 'examSrp'));
			}
		}
	}).catch(function (err) {
		console.log(err);
		sendSorryPage(req, res);
	});
});

app.get('/search(.{0,}*)', (req,res,next) => {
    let paramsObj = {};
    for(let key in req.query) {
        paramsObj[key] = req.query[key];
        if(key === 'rf' && paramsObj[key] === 'searchwidget'){
            paramsObj[key] = 'searchWidget';
        }
    }
    const data = Buffer.from(JSON.stringify(paramsObj)).toString('base64');
    const headerConfig = getHeaders(req);
    const srpData = store.dispatch(fetchCollegeSRPData(data, headerConfig, isServerCall));
    
    Promise.resolve(srpData).then((data) => {
    	store.dispatch(clearReduxData('except_categorypagedata'));
        if(!store.getState().categoryData || !store.getState().categoryData.requestData) {
            send404Page(req,res);
        }
        else {
            let queryString = '';
            if(require('url').parse(req.url).query!='' && require('url').parse(req.url).query!='null' && require('url').parse(req.url).query!=null){
                queryString = "?"+require('url').parse(req.url).query;
            }
            let maxPages = Math.ceil(store.getState().categoryData.totalInstituteCount/30);
            let currentPageNum = store.getState().categoryData.requestData.pageNumber;
			if(store.getState().categoryData &&  store.getState().categoryData.requestData && store.getState().categoryData.requestData.redirectUrl){
				res.redirect(301, store.getState().categoryData.requestData.redirectUrl);
			}
           else if(maxPages > 0 && currentPageNum > maxPages) { //safe check
            	let queryParams = require('querystring').parse(require('url').parse(req.url).query);
            	queryParams['pn'] = 1;
            	let queryStr = require('querystring').stringify(queryParams);
                res.redirect(301, '/search?'+queryStr);
            } else{
                res.render(getTemplate(),renderer(req, store, 'collegesrp'));
            }

            /*let endserverTime = Date.now();
            if(endserverTime-serverStartTime>500){
                let postData = 'serverStartTime='+serverStartTime+'&endserverTime='+endserverTime+'&requestedUrl='+req.url+'&module=categorypage';
                postRequest("https://www.shiksha.com"+logPerformanceUrl, postData).then((response)=>{
                    //console.log(response);
                });
            }*/
        }
    }).catch(function(err){
    	console.log(err);
        sendSorryPage(req,res);
    });
});
app.get('/recommendation(.{0,}*)', (req,res) => {
	const headerConfig = getHeaders(req);
	const apiParams = req.query["data"];
	const recommendationData = store.dispatch(fetchRecommendationData(apiParams, headerConfig, true));
	Promise.resolve(recommendationData).then(() => {
		store.dispatch(clearReduxData('except_recommendationPageData'));
		if(!store.getState().recommendationData || !store.getState().recommendationData.categoryInstituteTuple) {
			send404Page(req,res);
			return;
		}
		res.render(getTemplate(),renderer(req, store, 'recommendation'));
	}).catch(function(err){
		console.log(err);
		sendSorryPage(req,res);
	});
});

/*pwa amp page url | course page*/
// '/clpamp/:courseId'
app.get(['/([a-zA-Z0-9\-\ ]{0,})/course/amp/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))','/([a-zA-Z0-9\-\ ]{0,})/([a-zA-Z0-9\-\ ]{0,})/course/amp/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))','getListingDetail([a-zA-Z0-9\-\ ]{0,})/course/amp/([a-zA-Z0-9\-\ ]{0,})-(:id([0-9]+))'], function(req,res){
	var serverStart = Date.now();
	console.log(req.originalUrl);
	let newUrl = req.originalUrl.replace("/course/amp/", "/course/");
	console.log(newUrl);
	res.redirect(301, newUrl);
	return;
	var reqParams = 'courseId='+req.params.id;
	fromAmpPage = true;
	var setAgent = 'default';
	var https    = require('https');
	    setAgent = new https.Agent({
	    rejectUnauthorized: false,//add when working with https sites
	    requestCert: false,//add when working with https sites
	    agent: false,//add when working with https sites
	});
	var headerConfig=  {
            headers: {'Content-Type': 'application/json; charset=utf-8'},
            httpsAgent: setAgent,
            withCredentials: true
        };
	var courseId = req.params.id; //1653 , 163114 , 167945
	var params = '&from=coursepage&listingId='+courseId;
	var url =  APIConfig.GET_AMP_HAMBURGER+params;
	var ampHmdata = null;
	if(ampHamburgerHtml == null){
		ampHmdata = fetchAmpHamburgerData(url,'ampHamburger',headerConfig);
	}
	const courseData = store.dispatch(fetchCourseDetailPageData(reqParams, headerConfig, isServerCall));
	const importantDatesData = store.dispatch(fetchImportantDatesData(APIConfig.GET_AMP_IMPORTANT_DATES+'?courseId='+courseId,'importantDatesAmp'))
	store.dispatch(clearReduxData('except_coursepagedata'));

	Promise.all([courseData,ampHmdata,importantDatesData]).then((data) => {
		if(ampHamburgerHtml == null){
			ampHamburgerHtml = data[1]['html'];
		}
		let campusRepUrl = APIConfig.GET_CA_WIDGET+'?listingId='+store.getState().courseData.courseId+'&listingType=course&instituteId='+store.getState().courseData.instituteId;
		let alumni_url = APIConfig.GET_COURSE_NAUKRIDATA+'?courseId='+store.getState().courseData.courseId+'&instituteId='+store.getState().courseData.instituteId;
		const campusRepData = store.dispatch(fetchCampusRepData(campusRepUrl,'campusRepData'));
		const alumniData = store.dispatch(fetchAlumniData(alumni_url,'alumniData'));
		var dfpPostParams = 'parentPage=DFP_CourseDetailPage';
	    if(store.getState().courseData != null && typeof store.getState().courseData != 'undefined' && typeof store.getState().courseData.currentLocation != 'undefined' && store.getState().courseData.currentLocation)
	    {
	        dfpPostParams +='&city='+store.getState().courseData.currentLocation.city_id+'&state='+store.getState().courseData.currentLocation.state_id+'&entity_id='+courseId;
	    }
        const DFPData = store.dispatch(dfpBannerConfig(dfpPostParams));
		Promise.all([alumniData,campusRepData,DFPData]).then((data)=>{

			var serverEnd = Date.now();
			console.log("****** Api Server",(serverEnd - serverStart) );
			if(store.getState().courseData && store.getState().courseData.courseUrl)
			{
				var ampUrl = store.getState().courseData.courseUrl.replace("/course/","/course/amp/");
			}
			if(!store.getState().courseData || (store.getState().courseData && store.getState().courseData.statusCode == 404))
			{
				console.log("Inside 404");
				send404Page(req,res,false,fromAmpPage);
			}
			else if((store.getState().courseData && store.getState().courseData.statusCode ==  301))
			{
				res.redirect(301, store.getState().courseData.redirectUrl);
			}
				else if((store.getState().courseData && store.getState().courseData.statusCode ==  302))
				{
					res.redirect(302, store.getState().courseData.redirectUrl);
				}
			else if((store.getState().courseData && typeof ampUrl != 'undefined' && ampUrl != req.path))
			{
				var reqParams = '';
				for(var query in req.query)
				{
					if(query in keyMapping)
					{
						reqParams += '&'+keyMapping[query]+'='+req.query[query];
					}
					else
					{
						reqParams += '&'+query+'='+req.query[query];
					}
				}
				if(reqParams != '')
				{
					reqParams = '?'+reqParams;
				}
				res.redirect(301, ampUrl+reqParams);
			}
			else {
				res.render('amp_index' , ampRenderer(req, store,'coursePage',ampHamburgerHtml));
			}
			let endserverTime = Date.now();
			if(endserverTime-serverStartTime>500){
				let postData = 'serverStartTime='+serverStartTime+'&endserverTime='+endserverTime+'&requestedUrl='+req.url+'&module=coursepage';
				postRequest("https://"+shikshaConfig.SHIKSHA_HOME+logPerformanceUrl, postData).then((response)=>{
				});
			}
		}).catch(function(err){
				console.log("Error ==============",err);
				sendSorryPage(req,res,fromAmpPage);
		});
	}).catch(function(err){
		sendSorryPage(req,res,fromAmpPage);
	});

});

app.get('/college-predictor', (req,res,next) => {
	let params = '';
	let headerConfig= getHeaders(req);
	const collegePredictorData = store.dispatch(fetchCollegePredictorData(params, headerConfig, isServerCall));
	Promise.resolve(collegePredictorData).then(data => {
		store.dispatch(clearReduxData('except_collegePredictorData'));
		if(typeof store.getState().collegePredictorData != 'undefined' && store.getState().collegePredictorData != null && store.getState().collegePredictorData){
			res.render(getTemplate(),renderer(req, store, 'collegePredictor'));
		}else{
			sendSorryPage(req,res);	
		}
	}).catch(function(err){
		sendSorryPage(req, res);
	});
});

app.get('/college-predictor-results', (req,res,next) => {
	let params = makeInputObjectForResultPage(req.url);
	const collegePredictorResults = store.dispatch(fetchCollegePredictorResultData(params, '', isServerCall));
	Promise.resolve(collegePredictorResults).then(data => {
		store.dispatch(clearReduxData('except_collegePredictorResults'));
		if(typeof store.getState().collegePredictorResults != 'undefined' && store.getState().collegePredictorResults != null && store.getState().collegePredictorResults){
			let cpResults = store.getState().collegePredictorResults;
			if(cpResults.currentPageNumber > 1 && (cpResults.tuplesList == null || cpResults.tuplesList.length === 0)){
				let redirectUrl = removeParamFromUrl('pageNo', req.url);
				res.redirect(302, redirectUrl);
			}else{
				res.render(getTemplate(),renderer(req, store, 'collegePredictorResults'));
			}
		}else{
			sendSorryPage(req,res);
		}
	}).catch(function(err){
		if(err && err.response && err.response.status == 404){
			send404Page(req,res,true);
		}else{
			sendSorryPage(req, res);
		}
	});
});

app.get('*',function(req,res,next){
	//res.render(getTemplate(),renderer(req,store,'404Page'));
	var err = new Error('Not Found');
  	err.status = 404;
  	res.locals.message = err.message;
  	res.locals.error = (process.env.NODE_ENV === 'development') ? err : {};
  // // render the error page
  	res.status(err.status || 500);

  	if(fromAmpPage){
  		res.render('amp_index',ampRenderer(req,store,'404Page'));
  	}
  	else{
  		res.render(getTemplate(),renderer(req,store,'404Page',true));
  	}
 });



function sendSorryPage(req,res,fromAmpPage=false){
	if(fromAmpPage){
		res.render('amp_index',ampRenderer(req,store,'ErrPage'));
	}
	else{
  		res.render(getTemplate(),renderer(req,store,'ErrPage'));
  	}
}

// catch 404 and forward to error handler
/*app.use(function(req, res, next) {
	send404Page(next);
});*/

function send404Page(req,res,isInvalid = false,fromAmpPage=false)
{
	var err = new Error('Not Found');
  	err.status = 404;
  	res.locals.message = err.message;
  	res.locals.error = (process.env.NODE_ENV === 'development') ? err : {};

  // render the error page
  	res.status(err.status || 500);

  	if(fromAmpPage){
  		res.render('amp_index',ampRenderer(req,store,'404Page'));
  	}
  	else{
  		res.render(getTemplate(),renderer(req,store,'404Page',isInvalid));
  	}

}

function getTemplate(){
	return global.isMobileRequest == true ? 'index' : 'index_desktop';
}

// error handler
app.use(function(err, req, res, next) {
  res.locals.message = err.message;
  res.locals.error = (process.env.NODE_ENV === 'development') ? err : {};
  // render the error page
  res.status(err.status || 500);
  if(fromAmpPage){
  	res.render('amp_index',ampRenderer(req,store,'ErrPage'));
  }
  else{
  	 res.render(getTemplate(),renderer(req,store,'ErrPage'));
  }

});

Loadable.preloadAll().then(() => {
    app.listen(3022, () => {
    console.log('Running on http://localhost:3022/');
  });
});
/*<Router history={createMemoryHistory} routes={routes} />
				<WithStylesContext onInsertCss={styles => css.push(styles._getCss())}>
				*/
