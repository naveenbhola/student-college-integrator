import React from 'react';
import { hydrate } from 'react-dom';
import {Route,BrowserRouter as Router ,browserHistory, IndexRoute} from 'react-router-dom';
import Header from './../modules/common/components/Header';
import Layout from './../modules/common/components/Layout';
import routes from '../../routes/routes';
import Footer from '../modules/common/components/Footer';
import Analytics from './../modules/reusable/utils/AnalyticsTracking';
import { Provider } from 'react-redux';
import thunk from 'redux-thunk';
import mainReducer from './../utils/mainReducer';
import { combineReducers } from 'redux';
import {createStore, applyMiddleware} from 'redux';
import TagManager  from './../modules/reusable/utils/loadGTM';
import Loadable from 'react-loadable';
import {setCookie} from '../utils/commonHelper';
import DFPBanner from './../modules/reusable/utils/DFPBanner';


//const preloadedConfig = window.__PRELOADED_CONFIG__;

//delete window.__PRELOADED_CONFIG__;

const preloadedState = window.__PRELOADED_STATE__;
const commonCss = window.commonCss;
const mobileApp = window.mobileApp;

// Allow the passed state to be garbage-collected
delete window.__PRELOADED_STATE__;
const headerFooterExcludeList = ['/searchLayer'];

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

		
		var pageReqData = {};
		pageReqData.mobileApp = mobileApp;
		const store = createStore(rootReducer, preloadedState, applyMiddleware(thunk));
		return (
					<Provider store={store} key={Math.random()}>
	    				<Router history={browserHistory} key={Math.random()}>
							<Layout>
		    					<React.Fragment>
									<Header mobileApp={mobileApp} />
										{routes(pageReqData)}
	                				<Footer mobileApp={mobileApp}/>
								</React.Fragment>
							</Layout>
						</Router>
					</Provider>
			);
	}
	componentWillMount()
	{
		if(mobileApp){
			Analytics.initialize(	'UA-4454182-4');
		}else{
			Analytics.initialize('UA-4454182-1');
		}

	}
	componentDidMount()
	{
		const tagManagerArgs = {
		    gtmId: 'GTM-5FCGK6',
		    dataLayerName: 'dataLayer'
		}
		// TagManager.initialize(tagManagerArgs);
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
	
