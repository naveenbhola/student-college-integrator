import PropTypes from 'prop-types'
import React from 'react';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import {fetchCollegePredictorResultData, storeCollegePredictorFilterData} from './../actions/CollegePredictorAction';
import {isErrorPage, isServerSideRenderedHTML} from './../../../reusable/utils/commonUtil';
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import TagManager  from './../../../reusable/utils/loadGTM';
import {getModifySearchUrl, getTrackingParams, getUserFormDataFromUrl, makeInputObjectForResultPage, getUserFormDataObjectFromUrl} from './../utils/collegePredictorHelper';
import config from './../../../../../config/config';
import CollegePredictorMobileContent from './CollegePredictorMobileContent';
import CPMobileFilter from './CPMobileFilter';
import TopCard from "./TopCard";
import CollegePredictorInfoForUser from "./CollegePredictorInfoForUser";
import PaginationComponent from "../../../common/components/PaginationComponent";
import './../assets/ResultTuple.css';
import {getUrlParameter, removeParamFromUrl, isUserLoggedIn} from "../../../../utils/commonHelper";
import PreventScrolling from "../../../reusable/utils/PreventScrolling";
import CPZRP from "./CPZRP";
import ResultContentLoader from './../utils/ResultContentLoader';
import ClientSeo from './../../../common/components/ClientSeo';
import ErrorMsg from './../../../common/components/ErrorMsg';
import NotFound from "../../../common/components/NotFound";

class CollegePredictorResultsMobile extends React.Component{
	
	constructor(props){
		super(props);
		this.state = {
			isShowLoader : false
		};
		this.isClientCall = false;
	}

	componentDidMount(){
		if(!isServerSideRenderedHTML('collegeShortlistResult')){
			let finalInput = makeInputObjectForResultPage(this.props.location.search);
			this.initialFetchData(finalInput);
		}else if(!isErrorPage()){
			this.trackGTM();
		}
		if(this.props.location.search !== ''){
			if(!isUserLoggedIn()){
				this.validateUser();
			}
			this.storFilterData();
		}
    }

    validateUser=()=>{
    	let url = this.props.location.search;
			url = (url) ? this.props.location.pathname+url : '/college-predictor';
			window.location.href = config().SHIKSHA_HOME+"/muser5/UserActivityAMP/getRegistrationAmpPage?actionType=finalStep&fromwhere=allCollegePredictor&referer="+Buffer.from(url).toString('base64');
    };

	componentWillReceiveProps(nextProps, nextContext) {
		let nextHash = nextProps.location.search;
		let prevHash = this.props.location.search;
		if(prevHash !== nextHash){
			this.isClientCall = true;
			let urlParams  = this.isFilterAllow(nextProps.location.search);
			let finalInput = makeInputObjectForResultPage(urlParams);
			this.initialFetchData(finalInput);
		}
	}

    initialFetchData(params)
  	{
      let self = this;
      this.setState({isShowLoader : true});
      let fetchPromise = this.props.fetchCollegePredictorResultData(params);
	  window.scrollTo(0, 0);
      fetchPromise.then(function(){
      		self.setState({isShowLoader : false});
      		self.trackGTM();
      		self.storFilterData();
      });
  	}

  	trackGTM =()=>{
  		let urlParams = getUserFormDataObjectFromUrl(this.props.location.search);
  		let examIds = (urlParams['se'] && urlParams['se'].length) ? urlParams['se'] : '';
  		let trackParams = getTrackingParams('resultPage', examIds);
		if(typeof trackParams != 'undefined' && trackParams){
			ElasticSearchTracking(trackParams.beaconTrackData, config().BEACON_TRACK_URL);
			TagManager.dataLayer({dataLayer:trackParams.gtmParams, dataLayerName:'dataLayer'});
		}
  	};

  	static renderLoader() {
		PreventScrolling.enableScrolling(true);
		return <ResultContentLoader deviceType='mobile' />;
  	}

	showZRP() {
		return <CPZRP modifySearchUrl={getModifySearchUrl(this.props.cpFormUrl, this.props.location.search)} criteriaData={this.props.cpResults['topCardData']} />;
	}

	getPageUrl = () => {
		let userFormData = getUserFormDataFromUrl(this.props.location.search);
		let url = this.props.location.pathname;
		if(userFormData.length > 0){
			return url + '?' + userFormData.join('&');
		}
		return url;
	};

	storFilterData(){
		if(this.props.cpResults && this.props.cpResults.filters){
			let cpFilters     = {};
			cpFilters.filters = this.props.cpResults.filters;
			cpFilters.collegeCount = this.props.cpResults.collegeCount;
			this.props.storeCollegePredictorFilterData(cpFilters);
		}
	}

	isFilterAllow(location){
		let params = location;
		let pageNo = getUrlParameter('pageNo');
		if(pageNo && this.isClientCall){
			params = params+'&giveFilter=0';
		}
		return params;
	}

	render()
	{	
		const {cpResults} = this.props;
		const {cpFilters} = this.props;
		let propsLocation = this.props.location.search;
		
		if(this.state.isShowLoader){
      		return CollegePredictorResultsMobile.renderLoader();
    	}
		if(cpResults && cpResults.statusCode === 404) {
			return <NotFound deviceType="desktop" />;
		}
    	if(Object.keys(cpResults).length === 0 || cpResults.showError){
    		return <ErrorMsg/>;
    	}
		let currentPageUrl = this.props.location.pathname+propsLocation;
		let urlWithoutPageNo = removeParamFromUrl('pageNo',currentPageUrl);
		if(cpResults.currentPageNumber > 1 && (cpResults.tuplesList == null || cpResults.tuplesList.length === 0)){
			this.props.history.push(urlWithoutPageNo+'&pageNo=1');
			return null;
		}
		let tabUrl = removeParamFromUrl('tab', urlWithoutPageNo);
		let urlParams = this.isFilterAllow(propsLocation);
		let currHash  = makeInputObjectForResultPage(urlParams);
		if(cpResults.urlHash !== currHash) {
			return CollegePredictorResultsMobile.renderLoader();
		}
		if(cpResults.tuplesList === null || cpResults.tuplesList.length === 0){
			return this.showZRP();
		}

    	let activeTab = (cpResults.tabType && cpResults.tabType === 'branch') ? 'branch' : 'college';
    	let currentPageNo = (cpResults.currentPageNumber) ? cpResults.currentPageNumber : 1 ;
    	let totalResults  = (cpResults.resultCount) ? cpResults.resultCount : 0;
    	let resultPerPage = (cpResults.resultPerPage) ? cpResults.resultPerPage : 10;
    	let seoData       = (cpResults && cpResults.seoData) ? cpResults.seoData : '';
		let basePageUrlForFilter = removeParamFromUrl('pageNo', this.getPageUrl());

		let filterData    = (cpResults.filters) ? cpResults.filters : cpFilters.filters;
		let collegeCount  = (cpResults.collegeCount) ? cpResults.collegeCount : cpFilters.collegeCount;
    	
		if (PreventScrolling.canUseDOM() && document.getElementById('root').classList.contains('disable-scroll'))
			PreventScrolling.enableScrolling(true);
		return(
			<div id="collegeShortlistResult" className={'collegeShortlistResult'+(this.props.deviceType === 'desktop' ? ' cpDesktop' : ' cpMobile')}>
				{ClientSeo(seoData)}
				<div className="_container">
					<div className="collegeShortlistContainer">
						<TopCard deviceType={this.props.deviceType} modifySearchUrl={getModifySearchUrl(this.props.cpFormUrl, propsLocation)} collegeCount={collegeCount} topCardData={cpResults['topCardData']} currentPageUrl={currentPageUrl} gaTrackingCategory={this.props.gaTrackingCategory} />
						<CPMobileFilter currentPageUrl={tabUrl} activeTab={activeTab} {...filterData} pageUrl={basePageUrlForFilter} gaTrackingCategory={this.props.gaTrackingCategory} />
						<CollegePredictorInfoForUser deviceType={this.props.deviceType}/>
						<CollegePredictorMobileContent ocfWidget={['fees', 'college_ownership', 'location', 'exam']} activeTab={activeTab} tuplesList={(cpResults && cpResults.tuplesList) ? cpResults.tuplesList : ''} deviceType={this.props.deviceType} search={removeParamFromUrl('pageNo', propsLocation)} filters={(cpResults && filterData && filterData.filters) ? filterData.filters : null} filterNames={(cpResults && filterData && filterData.nameMapping) ? filterData.nameMapping : null} aliasMapping={(cpResults && filterData && filterData.aliasMapping) ? filterData.aliasMapping : null} currentPageNumber={(cpResults && cpResults.currentPageNumber) ? cpResults.currentPageNumber : 1} currentPageUrl={propsLocation} gaTrackingCategory={this.props.gaTrackingCategory} />
						<PaginationComponent totalItems={totalResults} currentPage={currentPageNo} currentPageUrl={currentPageUrl} queryParam="pageNo" pageSize={resultPerPage}/>
					</div>
				</div>
			</div>
		);
		
	}
}
CollegePredictorResultsMobile.defaultProps = {
	deviceType : 'mobile',
	cpFormUrl : '/college-predictor',
	gaTrackingCategory : 'College_Predictor_Mobile'
};

function mapStateToProps(state){
	return{
		cpResults : state.collegePredictorResults,
		cpFilters : state.collegePredictorFilterData
	}
}
function mapDispatchToProps(dispatch){
	return bindActionCreators({fetchCollegePredictorResultData, storeCollegePredictorFilterData},dispatch);
}
export default connect(mapStateToProps, mapDispatchToProps)(CollegePredictorResultsMobile);

CollegePredictorResultsMobile.propTypes = {
  cpFormUrl: PropTypes.string,
  cpResults: PropTypes.object,
  deviceType: PropTypes.string,
  fetchCollegePredictorResultData: PropTypes.func,
  storeCollegePredictorFilterData : PropTypes.func,
  location: PropTypes.object
}