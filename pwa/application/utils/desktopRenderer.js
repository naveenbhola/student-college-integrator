import React from 'react';
import {StaticRouter} from 'react-router-dom';
import {renderToString} from 'react-dom/server';
import Loadable from 'react-loadable';
import  {Provider}  from 'react-redux';
import {getBundles} from 'react-loadable/webpack';
import config from './../../config/config.js';
import shikshaConfig from './../modules/common/config/shikshaConfig';
import routes from '../../routes/routesDesktop'
import DesktopHeader from './../modules/common/components/desktop/Header';
import DesktopFooter from './../modules/common/components/desktop/Footer';
import NotFound from './../modules/common/components/NotFound';
import ErrorMsg from './../modules/common/components/ErrorMsg';
import SeoDetails from './../modules/common/components/SeoDetails';
import SeoSchema from './../modules/common/components/SeoSchema';
import inlinePageCSS from './../../public/css/index_desktop_css.css';
import {getInlineCSS} from './commonHelper';

import {getCSSWithVersion,getJSWithVersion,getCssMinify, includeJSFiles, includeCSSFiles} from './../server/nodeHelper';
import chatPluginContainer from './../../views/chatPluginContainer.ejs';

let nodeJsPath = 'js_temp';
if(process.env.NODE_ENV == 'development') {
	nodeJsPath = 'js';
}
const stats = require('./../../public/'+nodeJsPath+'/react-loadable.json');
const headerFooterExcludeList = ['searchLayer', 'pdfGenerator'];
export default (req, store, fromWhere, isInvalid = false, extraData = {}) => {
  var modules = [];
  const seoTags = renderToString(SeoDetails(store.getState(), fromWhere));
    const isHeaderFooterExclude = headerFooterExcludeList.indexOf(fromWhere);
    const header = isHeaderFooterExclude === -1 ? renderToString(
    <Provider store={store}>
    	<StaticRouter context={{}} location={req.url}>
    		<Loadable.Capture report={function(moduleName) {return modules.push(moduleName)}}>
    			<DesktopHeader isUserLoggedIn={extraData.isUserLoggedIn} />
    		</Loadable.Capture>
    	</StaticRouter>
    </Provider>
	): '';

    const footer = isHeaderFooterExclude === -1 ? renderToString(
    <Provider store={store}>
      <StaticRouter context={{}} location={req.url}>
        <Loadable.Capture report={function(moduleName) {return modules.push(moduleName)}}>
          <DesktopFooter />
        </Loadable.Capture>
      </StaticRouter>
    </Provider>
  ) : '';
  let wrapperStyle = {minHeight:'100px'};
  if(fromWhere === 'homePage'){
    wrapperStyle = {minHeight:'100px', marginTop : '80px'};
  }
  const content = renderToString(
    <Provider store={store}>
      <StaticRouter context={{}} location={req.url}>
        <Loadable.Capture report={function(moduleName) {return modules.push(moduleName)}}>
        <React.Fragment>
        <main id="main-wrapper" style={wrapperStyle} className="pwa_wrapper">
          { (fromWhere == '404Page' && isInvalid == true) ? <NotFound deviceType = 'desktop'/> : (fromWhere == 'ErrPage' ? <ErrorMsg/> : routes())}
        </main>
        </React.Fragment>
        </Loadable.Capture>
      </StaticRouter>
    </Provider>
  );
  let bundles = getBundles(stats, modules);
  let styles  = bundles.filter(bundle => bundle.file.endsWith('.css'));
  const jsDomain = config().JS_DOMAIN;
  let scripts = bundles.filter(bundle => bundle.file.endsWith('.js')).map(function(scripts){
    return jsDomain+'/pwa/public/js/'+scripts.file;
  });

  let scriptsArr = scripts.filter(function(item, pos){
      return scripts.indexOf(item)== pos; 
  });

  let css = '';
  const serviceWokrerFile = (process.env.NODE_ENV == 'production') ? getJSWithVersion('service-worker') : 'service-worker.js';
  let cssLinks = [];
  let cssLinksArr = [];
  const cssDomain = config().CSS_DOMAIN;
  if(styles.length>0){
          cssLinks = styles.map(function(index){
                  css += require('./../../public/'+nodeJsPath+'/'+index.file);
                  return cssDomain+'/pwa/public/js/'+index.file;
          });

          cssLinksArr = cssLinks.filter(function(item, pos){
              return cssLinks.indexOf(item)== pos; 
          });

          //css = require("fs").readFileSync("public/js/"+styles[0].file,"utf-8")
          if(typeof css[0] != 'undefined' && typeof css[0][1] != 'undefined' && typeof css[0][1] == 'string')
          {
                  css = getCssMinify(css[0][1]);
          }
  }

	let jquery_regn_search_scripts = [];
  jquery_regn_search_scripts.push(includeJSFiles('pwa-desktop-common-search', 'shikshaDesktop', ['crossorigin','async'],config()));
  jquery_regn_search_scripts.push(includeJSFiles('pwa-desktop-common-regn', 'shikshaDesktop', ['crossorigin','async'],config()));
  jquery_regn_search_scripts.push(includeJSFiles('pwa-desktop-other', 'shikshaDesktop', ['crossorigin','async'],config()));
  let extra_css = includeCSSFiles('pwa-desktop-extra-css', 'shikshaDesktop', config());
 
  const seoSchema = SeoSchema(store.getState(), fromWhere);
  let internalCSS = getCssMinify(inlinePageCSS[0][1]);
  let inlineCSS = getInlineCSS(fromWhere);
  if(inlineCSS!=null){
    css = getCssMinify(inlineCSS[0][1]);
  }

  return {
    "schema": (seoSchema) ? seoSchema : '',
    "seoTags": seoTags,
    "serviceWokrerFile" : serviceWokrerFile,
    "COOKIEDOMAIN" : config().COOKIE_DOMAIN,
    "getCSSWithVersion" : getCSSWithVersion,
    "preloadedState" : store.getState(),
    "internalCSS" : internalCSS,
    "styles": css,
    "cssLinks" : cssLinksArr,
    "cssDomain" : config().CSS_DOMAIN,
    "jsDomain" : jsDomain,
    "SHIKSHA_HOME" : config().SHIKSHA_HOME,
    "scripts" : scriptsArr,
		"validDomains" : shikshaConfig.validEmailDomains,
    "jquery_regn_search_scripts" : jquery_regn_search_scripts,
    "extra_css" : extra_css,
    "csrfToken" : req.csrfToken(),
		"sessionID" : req.sessionID,
		"content": content,
		"footer" : footer,
    "header" : header,
    "chatPluginContainer": chatPluginContainer,
    "userCookieFlag" : extraData.isUserLoggedIn
	};
};
