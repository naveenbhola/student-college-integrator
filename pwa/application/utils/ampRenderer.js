import React from 'react';
import {Route, Router, createMemoryHistory} from 'react-router';

import {StaticRouter, Switch} from 'react-router-dom';
import {renderToString} from 'react-dom/server';
import Header from './../modules/common/components/Header';
import SeoDetails from './../modules/common/components/SeoDetails';
import ScriptList from './../../views/amp/common/ScriptList';
import BoilerPlateStyle from './../../views/amp/common/BoilerPlateStyle';
import CommonCssFiles from './../../views/amp/common/CommonCssFiles';
import PageSpecificCss from './../../views/amp/common/PageSpecificCss';
import MetaHead from './../../views/amp/common/MetaHead';
import SeoSchema from './../modules/common/components/SeoSchema';
import Footer from './../modules/common/components/Footer';
import config from './../../config/config.js';
import routes from '../../routes/routes';
import  {Provider}  from 'react-redux';
import {getBundles} from 'react-loadable/webpack';
import Loadable from 'react-loadable';
import Loading from './../modules/reusable/utils/Loader';
import NotFound from './../modules/common/components/NotFound';
import ErrorMsg from './../modules/common/components/ErrorMsg';
import {getCSSWithVersion,getJSWithVersion,getCssMinify} from './../server/nodeHelper';//getJSWithVersion

let nodeJsPath = 'js_temp';
if(process.env.NODE_ENV == 'development') {
	nodeJsPath = 'js';
}
const stats = require('./../../public/'+nodeJsPath+'/react-loadable.json');
const headerFooterExcludeList = ['searchLayer'];
export default (req, store, fromWhere,ampHmData, params = null) => {
	var RenderStart = Date.now();
	const hamburgerHtml = ampHmData;
	const seoSchema = SeoSchema(store.getState(), fromWhere);

	var scriptList = "";
	if(fromWhere == "coursePage"){
	 scriptList = ScriptList(fromWhere,store.getState().courseData.courseId);		
	}
	else{
	 scriptList = ScriptList(fromWhere,store.getState().instituteData.instituteId);
	}
	const boilerplateStyle = BoilerPlateStyle();
	const commonCss = CommonCssFiles();
	const pageSpecificCss = PageSpecificCss(store.getState(), fromWhere);
	const metaHead = MetaHead();

	const seoTags = renderToString(SeoDetails(store.getState(), fromWhere));
	var modules = [];

	const content = renderToString(
			<Provider store = {store}>
				<StaticRouter location={req.url}>
					<Loadable.Capture report={function(moduleName) {return modules.push(moduleName)}}>
						{ fromWhere == '404Page' ? <NotFound/> : (fromWhere == 'ErrPage' ? <ErrorMsg/> : routes())}
					</Loadable.Capture>
				</StaticRouter>
			</Provider>
		);

	var RenderEnd = Date.now();
	console.log("****** Render Time ",(RenderEnd - RenderStart ) );
   	const serviceWokrerFile = (process.env.NODE_ENV == 'production') ? getJSWithVersion('service-worker') : 'service-worker.js';

  	return {
		"schema":(seoSchema) ? seoSchema : '',
		"seoTags":seoTags,
		"content": content,
		"scriptList":scriptList,
		"boilerplateStyle": boilerplateStyle,
		"commonCss": commonCss,
		"pageSpecificCss": pageSpecificCss,
		"metaHead" : metaHead,
		"config" : config,
		"hamburgerHtml" : hamburgerHtml,
		"serviceWokrerFile" : serviceWokrerFile,
		"COOKIEDOMAIN"		: config().COOKIE_DOMAIN
		};
}
