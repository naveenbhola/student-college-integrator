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
import FeaturedArticleComponent from './FeaturedArticleComponent';
import { fetchInitialData } from './../../../common/actions/commonAction';
import {isErrorPage, isServerSideRenderedHTML} from './../../../reusable/utils/commonUtil';

class HomePageDesktop extends Component {
	  constructor(props){
  		super(props);
  	}
 	
  	componentDidMount(){
      if(!isServerSideRenderedHTML('hpDesktop')){
          this.initialFetchData();  
      }else if(!isErrorPage()){
          this.trackGTM();
      }
    }

    initialFetchData(){
      let self = this;
      this.setState({isShowLoader : true});
      let url = APIConfig.GET_DESK_HOMEPAGE_CLIENT_DATA;
      let fetchPromise = this.props.fetchInitialData(url);
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

  	render(){
      const {hpdata} = this.props;
      console.log('hpdata==',hpdata);
  		return(
          <div id="hpDesktop">
            {ClientSeo()}
            <TopComponent bannerData={hpdata.bannerData} listingsCount={hpdata.counts}/>
            <div className="bdL">
              <CategoryFoldComponent/>
              <MiddleFeaturedCollegeComponent/>
              <CareerGuidanceComponent/>
              <FeaturedArticleComponent/>
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
  return bindActionCreators({ fetchInitialData }, dispatch);  
} 
export default connect(mapStateToProps,mapDispatchToProps)(HomePageDesktop);