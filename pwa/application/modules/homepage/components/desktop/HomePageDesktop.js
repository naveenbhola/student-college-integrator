import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import APIConfig from './../../../../../config/apiConfig';
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import ClientSeo from './../../../common/components/ClientSeo';
import TopComponent from './TopComponent';
import CategoryFoldComponent from './CategoryFoldComponent';
import MiddleFeaturedCollegeComponent from './MiddleFeaturedCollegeComponent';
import CareerGuidanceComponent from './CareerGuidanceComponent';
import ArticleComponent from './ArticleComponent';
import BottomComponent from './BottomComponent';
import { fetchInitialData } from './../../../common/actions/commonAction';
import {dfpBannerConfig, clearDfpBannerConfig} from './../../../reusable/actions/commonAction';
import {isErrorPage, isServerSideRenderedHTML} from './../../../reusable/utils/commonUtil';
import {resetGNB} from './../../../../utils/commonHelper';
import HomeContentLoader from './../../utils/desktopContentLoader';

class HomePageDesktop extends Component {
	  constructor(props){
  		super(props);
  		this.state = {
            isShowLoader : false
        };
  	}
 	
  	componentDidMount(){
      //resetGNB();
      window.scrollTo(0, window.pageYOffset - 1);
      if(!isServerSideRenderedHTML('hpDesktop')){
          this.initialFetchData();
      }else if(!isErrorPage()){
          this.trackGTM();
      }
    }
    componentWillUnmount = () => {
        this.props.clearDfpBannerConfig();
    };

    initialFetchData(){
      let self = this;
      this.setState({isShowLoader : true});
      let url = APIConfig.GET_DESK_HOMEPAGE_CLIENT_DATA;
      let fetchPromise = this.props.fetchInitialData(url, 'homepageData');
      fetchPromise.then(function(){
          self.setState({isShowLoader : false});
          self.trackGTM();
      });
    }

    trackGTM =()=>{
      const {config} = this.props;
      let beaconTrackData = {'pageIdentifier' : 'homePage', 'pageEntityId' : 0,'extraData' : {'countryId' : 2, 'childPageIdentifier' : 'homePage'}};
      ElasticSearchTracking(beaconTrackData,config.BEACON_TRACK_URL);
    };
    showLoader = () => {
      return <HomeContentLoader />
    };
    componentWillReceiveProps = (nextProps) => {
    };

    render(){
        //console.log('this.props',this.props.hpdata);
        const {hpdata} = this.props;
        if(this.state.isShowLoader){
            return this.showLoader();
        }
        if(typeof this.props.hpdata === 'undefined' || this.props.hpdata === null || Object.keys(this.props.hpdata).length === 0){
            return this.showLoader();
        }
        //   if(hpdata.urlHash != currHash) {
        //     return this.showLoader();
        // }
      let latestUpdate = (typeof this.props.hpdata != 'undefined' && typeof this.props.hpdata.latestUpdates != 'undefined' && this.props.hpdata.latestUpdates) ? this.props.hpdata.latestUpdates : null;
      let counts = (typeof hpdata.counts != 'undefined' && hpdata.counts && typeof hpdata.counts.national != 'undefined') ? hpdata.counts.national : null;
  		return(
          <div id="hpDesktop">
            {ClientSeo()}
            <TopComponent bannerData={hpdata.bannerData} listingsCount={counts} imageDomain={this.props.config.IMAGES_SHIKSHA} collegeAds={hpdata.collegeAds}/>
            <div className="bdL">
              <CategoryFoldComponent CategoryFoldData={hpdata.categoryFolder} />
              {(this.props.hpdata.articles!=null && this.props.hpdata.articles.length>0)?<ArticleComponent articleData={this.props.hpdata.articles} latestUpdate={latestUpdate} config={this.props.config}/>:''}
              {<MiddleFeaturedCollegeComponent featuredColleges={hpdata.featuredColleges}/>}
              <CareerGuidanceComponent pageName={this.props.pageName} gaCategory={'SHIKSHAHOMEPAGE_DESKAnA'}/>
              {((this.props.hpdata.testimonials!=null && this.props.hpdata.testimonials.length>0) || (this.props.hpdata.marketing!=null && this.props.hpdata.marketing.length>0))?<BottomComponent testimonialData={this.props.hpdata.testimonials} marketingData={this.props.hpdata.marketing}/>:''}
            </div>
          </div>
      )
  	}

}

function mapStateToProps(state)
{
    return {
        config : state.config,
        hpdata : state.hpdata
    }
}
function mapDispatchToProps(dispatch){
  return bindActionCreators({ fetchInitialData,dfpBannerConfig,clearDfpBannerConfig }, dispatch);
}
HomePageDesktop.defaultProps = {
    pageName : 'homePage'
};
export default connect(mapStateToProps,mapDispatchToProps)(HomePageDesktop);
