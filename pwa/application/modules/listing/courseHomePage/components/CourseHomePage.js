import React from 'react';
import  './../assets/chphomePageCss.css';
import {withRouter, Redirect} from 'react-router-dom';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import TopSection from './TopSection';
import OtherTopics from './OtherTopics';
import {fetchCourseHomePageData} from './../actions/CourseHomePageAction';
import ErrorMsg from './../../../common/components/ErrorMsg';
import NotFound from './../../../common/components/NotFound';
import {getChpTrackingParams, createSections,prepareDFPdata, getEncodedUrlParams} from './../utils/chpUtil';
import {getQueryVariable} from './../../../../utils/commonHelper';
import TagManager  from './../../../reusable/utils/loadGTM';
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import {isErrorPage,isServerSideRenderedHTML} from './../../../reusable/utils/commonUtil';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../../reusable/actions/commonAction';
import ContentLoader from './../utils/contentLoader';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import ClientSeo from './../../../common/components/ClientSeo';
import Loadable from 'react-loadable';

const BottomSticky = Loadable({
  loader: () => import('./BottomSticky'/* webpackChunkName: 'BottomSticky' */),
  loading() {return null},
});

class CourseHomePage extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = {
        	isShowLoader : false,
          showBottomSticky : false
    	}
		this.isDfpData = false;
	}

	componentDidMount(){
		if(!isServerSideRenderedHTML('CHP'))
		{
			const {location} = this.props;
			let url = location.pathname;
        	let params = getEncodedUrlParams(url);
        	this.initialFetchData(params);	
		}else{
			if(!isErrorPage()){
            	this.trackGTM();
            }
		}
		window.addEventListener("scroll", this.onScroll);
    }

  renderLoader() {
    PreventScrolling.enableScrolling(true);
    if(PreventScrolling.canUseDOM())
    {
        document.getElementById('page-header').style.display = "table";
        document.getElementById('page-header').style.position = "relative";
    }
    return <ContentLoader/>;
  }

    componentWillUnmount(){
    	this.props.clearDfpBannerConfig();
		window.removeEventListener('scroll', this.onScroll);
    }


    componentWillReceiveProps(nextProps)
  	{
	    let nextHash = nextProps.location.pathname;
	    let prevHash = this.props.location.pathname;
	    if(prevHash != nextHash || this.props.location.search != nextProps.location.search){
	     	let params = getEncodedUrlParams(nextHash);
	     	this.initialFetchData(params);
	    }
  	}
	   
    onScroll = () => {
  		if(!this.isDfpData){
  			this.isDfpData = true;
  			this.getDFPData();
  		}

      if(!this.state.showBottomSticky){
        BottomSticky.preload().then(()=>{ this.setState({showBottomSticky:true})});
      }
	};
  	trackGTM()
    { 
        const {config, courseHomePageData} = this.props;
        var trackingParams;
        var beaconData = {};
        if(typeof courseHomePageData != 'undefined' && courseHomePageData)
        {
            trackingParams = getChpTrackingParams(courseHomePageData);
            TagManager.dataLayer({dataLayer:trackingParams.gtmParams,dataLayerName:'dataLayer'});
            ElasticSearchTracking(trackingParams.beaconTrackData,config.BEACON_TRACK_URL);
        }
    }

  	initialFetchData(params)
  	{
      var self = this;
      this.setState({isShowLoader : true});
      let fetchPromise = this.props.fetchCourseHomePageData(params);
      fetchPromise.then(function(res){
      		self.setState({isShowLoader : false});
      		self.trackGTM();
      		self.getDFPData();
		  	self.isDfpData = true;
      });
  	}
	getDFPData(){
		var dfpPostParams = 'parentPage=DFP_CourseHomePage';
		if(typeof this.props.courseHomePageData != 'undefined' && this.props.courseHomePageData.chpId){
			dfpPostParams +='&entity_id='+this.props.courseHomePageData.chpId+'&extraPrams='+prepareDFPdata(this.props.courseHomePageData);
		}
		this.props.dfpBannerConfig(dfpPostParams);
	}
	render()
	{
    if(this.state.isShowLoader){
      return (
          <React.Fragment>
              {this.renderLoader()}
          </React.Fragment>
          );
    }

    if((this.props.courseHomePageData && typeof this.props.courseHomePageData.statusCode != 'undefined' && this.props.courseHomePageData.statusCode== 404)){
        return <NotFound deviceType="mobile" />;
    }else if((this.props.courseHomePageData && typeof this.props.courseHomePageData.statusCode != 'undefined' && this.props.courseHomePageData.statusCode== 301)){
        return <Redirect to={{ pathname: this.props.courseHomePageData.url, state: { status: 301 } }}/>;
    }

    const {config, courseHomePageData} = this.props;
    if(typeof courseHomePageData == 'undefined' || Object.keys(this.props.courseHomePageData).length==0) {
        return (
            <React.Fragment>
                {this.renderLoader()}
            </React.Fragment>
            );
    }
    else if(typeof courseHomePageData == 'undefined' || courseHomePageData == null) {
      return <ErrorMsg/>;
    }

    let currHash = getEncodedUrlParams(this.props.location.pathname);
    if(this.props.courseHomePageData.chpUrlHash != currHash) {
      return this.renderLoader();
    }

		let isPdfCall = false;
		let urlParams = getQueryVariable('isPdfCall', this.props.location.search);
		if(urlParams!=null && urlParams!='undefined' && urlParams){
			isPdfCall = true;
		}
		let chpSections   = createSections(this.props.config, this.props.courseHomePageData, isPdfCall ,'mobile');
    let seoData = (courseHomePageData && courseHomePageData.seoData) ? courseHomePageData.seoData : '';
		return (
				<React.Fragment>
          {ClientSeo(seoData)}
					<div id="CHP" className="chp">
						<TopSection isPdfCall={isPdfCall} config={this.props.config} sectionData={this.props.courseHomePageData} count={this.props.courseHomePageData.counts} imageUrl={this.props.courseHomePageData.imageUrl} displayName={this.props.courseHomePageData.displayName}/>
						{chpSections}
            {(typeof this.props.courseHomePageData != 'undefined' && typeof this.props.courseHomePageData.relatedCHP != 'undefined' && this.props.courseHomePageData.relatedCHP.length>0)?
              <OtherTopics relatedData={this.props.courseHomePageData.relatedCHP} key={19} config={this.props.config} isPdfCall={isPdfCall}/>:''
            }

            {this.state.showBottomSticky && <BottomSticky config={this.props.config} isPdfCall={isPdfCall} chpData={this.props.courseHomePageData} deviceType='mobile'/>}

					 </div>
				</React.Fragment>
			)
	  

  }
}

function mapStateToProps(state)
{
  return {
      courseHomePageData : state.courseHomePageData,
      config : state.config
  }
}

function mapDispatchToProps(dispatch){
  return bindActionCreators({ fetchCourseHomePageData, dfpBannerConfig,clearDfpBannerConfig }, dispatch); 
}

export default withRouter(connect(mapStateToProps,mapDispatchToProps)(CourseHomePage));
