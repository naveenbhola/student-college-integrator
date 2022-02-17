import React from 'react';
import { hydrate } from 'react-dom';
import {Route,BrowserRouter as Router ,browserHistory, IndexRoute} from 'react-router-dom';
import Header from './../modules/common/components/desktop/Header';
import routes from '../../routes/routesDesktop';
import Footer from '../modules/common/components/desktop/Footer';
import Analytics from './../modules/reusable/utils/AnalyticsTracking';
import { Provider } from 'react-redux';
import thunk from 'redux-thunk';
import mainReducer from './../utils/mainReducer';
import { combineReducers } from 'redux';
import {createStore, applyMiddleware} from 'redux';
import TagManager  from './../modules/reusable/utils/loadGTM';
import Loadable from 'react-loadable';
import registerServiceWorker,{checkUserOnline} from '../utils/registerServiceWorker.js';
import {setCookie} from '../utils/commonHelper';
import DFPBanner from './../modules/reusable/utils/DFPBanner';


//const preloadedConfig = window.__PRELOADED_CONFIG__;

//delete window.__PRELOADED_CONFIG__;

const preloadedState = window.__PRELOADED_STATE__;
const userCookieFlag = window.userCookieFlag;
const serviceWokrerFile = window.serviceWokrerFile;
const commonCss = window.commonCss;

// Allow the passed state to be garbage-collected
delete window.__PRELOADED_STATE__;

const combinedReducers = Object.assign({},mainReducer);

const rootReducer = combineReducers(mainReducer);

export default class Index extends React.Component
{
	constructor(props)
	{
		super(props);
	}
	render()
	{
	/*	const context = {
		  insertCss(...styles) { styles.forEach(style => style._insertCss()); },
		};*/
		//context.config = preloadedConfig;
		const store = createStore(rootReducer, preloadedState, applyMiddleware(thunk));
		return (
					<Provider store={store}>
	    				<Router history={browserHistory}>
	    					<React.Fragment>
								<Header isUserLoggedIn={userCookieFlag} />
								<main id="main-wrapper" style={{minHeight:'100px'}} className="pwa_wrapper">
								{routes()}
								</main>
	                			<Footer/>
							</React.Fragment>
						</Router>
					</Provider>
			);
	}
	componentWillMount()
	{
		Analytics.initialize('UA-4454182-1');
		const tagManagerArgs = {
		    gtmId: 'GTM-5FCGK6',
		    dataLayerName: 'dataLayer'
		}
		//TagManager.initialize(tagManagerArgs);
		DFPBanner.initializeBanner();
	}
	componentDidMount()
	{
		setCookie('ispwa',true);
	}
}

	Loadable.preloadReady().then(() => {
	  hydrate(
			<React.Fragment>
					<Index />
		  </React.Fragment>,
		  document.getElementById('root')
		);
	});
	registerServiceWorker(serviceWokrerFile);
