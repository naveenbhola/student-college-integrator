import React from 'react';
import {StaticRouter} from 'react-router-dom';
import {renderToString} from 'react-dom/server';
import Header from './../modules/common/components/Header';
import SeoDetails from './../modules/common/components/SeoDetails';
import SeoSchema from './../modules/common/components/SeoSchema';
import Footer from './../modules/common/components/Footer';

import config from './../../config/config.js';
import routes from '../../routes/routes';
import  {Provider}  from 'react-redux';
import {getBundles} from 'react-loadable/webpack';
import Loadable from 'react-loadable';
import NotFound from './../modules/common/components/NotFound';
import ErrorMsg from './../modules/common/components/ErrorMsg';
import inlinePageCSS from './../../public/css/index_css.css';
import {getCSSWithVersion,getJSWithVersion,getCssMinify} from './../server/nodeHelper';//getJSWithVersion
import chatPluginContainer from './../../views/chatPluginContainer.ejs';
import {getInlineCSS} from './commonHelper';

let nodeJsPath = 'js_temp';
if(process.env.NODE_ENV == 'development') {
	nodeJsPath = 'js';
}
const stats = require('./../../public/'+nodeJsPath+'/react-loadable.json');
const headerFooterExcludeList = ['searchLayer', 'pdfGenerator'];
const noCrawlList = ['recommendation'];
export default (req, store, fromWhere, isInvalid = false) => {
	var RenderStartTime = Date.now();
	const seoTags = renderToString(SeoDetails(store.getState(), fromWhere));
	var modules = [];
	const isHeaderFooterExclude = headerFooterExcludeList.indexOf(fromWhere);
	let header = '';
	let content ='';
	let footer = '';
	const noCrawl = noCrawlList.indexOf(fromWhere) > -1;
	let mobileApp = false;
	if(req.cookies.AndroidSource && req.cookies.AndroidSource=='AndroidWebView'){
		mobileApp = true;
	}
	var pageReqData = {};
	pageReqData.mobileApp = mobileApp;

	header = isHeaderFooterExclude === -1 ? renderToString(
			<Provider store={store}>
				<StaticRouter context={{}} location={req.url}>
					<Loadable.Capture report={function(moduleName) {return modules.push(moduleName)}}>
						<Header gdprCookie = {req.cookies.gdpr} mobileApp={mobileApp} />
					</Loadable.Capture>
				</StaticRouter>
			</Provider>
		) : '';
	content = renderToString(
			<Provider store={store}>
				<StaticRouter context={{}} location={req.url}>
					<Loadable.Capture report={function(moduleName) {return modules.push(moduleName)}}>
						{ (fromWhere == '404Page' && isInvalid == true) ? <NotFound deviceType = 'mobile'/> : (fromWhere == 'ErrPage' ? <ErrorMsg/> : routes(pageReqData,req.cookies.recentSearches))}
					</Loadable.Capture>
				</StaticRouter>
			</Provider>
		);
	footer = isHeaderFooterExclude === -1 ? renderToString(
			<Provider store={store}>
				<StaticRouter context={{}} location={req.url} >
					<Loadable.Capture report={function(moduleName) {return modules.push(moduleName)}}>
						<Footer mobileApp={mobileApp}/>
					</Loadable.Capture>
				</StaticRouter>
			</Provider>
		) : '';
	var RenderEndTime = Date.now();
	console.log("**** RenderTime",(RenderEndTime - RenderStartTime));
	let bundles = getBundles(stats, modules);
	let styles  = bundles.filter(bundle => bundle.file.endsWith('.css'));
	const jsDomain = config().JS_DOMAIN;
	const SHIKSHA_HOME = config().SHIKSHA_HOME;
  	let scripts = bundles.filter(bundle => bundle.file.endsWith('.js')).map(function(scripts){
  		return jsDomain+'/pwa/public/js/'+scripts.file;
  	});

  	let css = '';
  	const serviceWokrerFile = (process.env.NODE_ENV == 'production') ? getJSWithVersion('service-worker') : 'service-worker.js';
  	const chatPluginFile = (process.env.NODE_ENV == 'production') ? getJSWithVersion('chatplugin') : 'chatplugin.js';
	let cssLinks = [];
	let cssLinksArr = [];
        const cssDomain = config().CSS_DOMAIN;
        if(styles.length>0){
                cssLinks = styles.map(function(index){
                        css += require('./../../public/'+nodeJsPath+'/'+index.file);
                        return cssDomain+'/pwa/public/js/'+index.file;
                });

                //css = require("fs").readFileSync("public/js/"+styles[0].file,"utf-8")
                if(typeof css[0] != 'undefined' && typeof css[0][1] != 'undefined' && typeof css[0][1] == 'string')
                {
                        css = getCssMinify(css[0][1]);
                }
        }
	const seoSchema = SeoSchema(store.getState(), fromWhere);
	let internalCSS = getCssMinify(inlinePageCSS[0][1]);
	let inlineCSS = getInlineCSS(fromWhere);
	if(inlineCSS!=null){
		css = getCssMinify(inlineCSS[0][1]);
	}
	return {
		 "schema":(seoSchema) ? seoSchema : '',
		"seoTags":seoTags,
		"scripts" : scripts,
		"internalCSS" : internalCSS,
		"styles": css,
		"cssLinks" : cssLinks,
		"header" : header,
		"content": content,
		"config" : config,
		"footer" : footer,
		"preloadedState" : store.getState(),
		"getCSSWithVersion" : getCSSWithVersion,
		"serviceWokrerFile" : serviceWokrerFile,
		"COOKIEDOMAIN"		: config().COOKIE_DOMAIN,
		"jsDomain" : jsDomain,
		"cssDomain" : cssDomain,
		"chatPluginFile": chatPluginFile,
		"SHIKSHA_HOME": SHIKSHA_HOME,
		"chatPluginContainer": chatPluginContainer,
		'mobileApp':mobileApp,
		'noCrawl' : noCrawl
		};
}

