import React, { Component } from 'react';
import { postRequest,getRequest } from '../../../utils/ApiCalls';
import PropTypes from 'prop-types';
import TopSection from './TopSection';
import MiddleSection from './MiddleSection';
import ExpertGuidanceWidget from './ExpertGuidanceWidget';
import LatestUpdateWidget from './LatestUpdateWidget';
import Carousel from '../../common/components/Carousel';

import { fetchInitialData } from '../../common/actions/commonAction';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import APIConfig from './../../../../config/apiConfig';
import ElasticSearchTracking from './../../reusable/utils/ElasticSearchTracking';
import PreventScrolling from './../../reusable/utils/PreventScrolling';
import HomeContentLoader from './../utils/contentLoader';
import {dfpBannerConfig, clearDfpBannerConfig} from './../../reusable/actions/commonAction';
import ClientSeo from './../../common/components/ClientSeo';

/*export const buttonStyle = style({
  marginLeft: 10,
  display: 'inline',
  border: '1px solid black'
})
{ ...buttonStyle }
*/

class HomePage extends Component {
  constructor(props,context)
  {
    super(props,context);
    this.state = {
  	   featuredCollegeData: []
  	}
    this.scrollCount = 0;
  }

  getFeaturedCollege = ()=>{
      if(this.scrollCount==0){
          getRequest(APIConfig.GET_HOMEPAGE_FEATURED_COLLEGE+'?deviceType=mobile')
          .then((response) => {
            if(response.data.data){
                const combinedArrays = response.data.data.paidBannerList.concat(response.data.data.freeBannerList);
                      this.setState({featuredCollegeData: combinedArrays});
            }
          })
          .catch((err)=> console.log(err));  
          this.scrollCount = 1;
      }
  }    

  componentDidMount(){
      const {config} = this.props;
      window.addEventListener('scroll', this.getFeaturedCollege);
      window.addEventListener('load', this.props.fetchInitialData(APIConfig.GET_LATESTARTICLESANDCOUNTPARAMS,'homepageData'));

      let beaconTrackData = {'pageIdentifier' : 'homePage', 'pageEntityId' : 0, 
                              'extraData' : {'countryId' : 2, 'childPageIdentifier' : 'homePage'}};
      ElasticSearchTracking(beaconTrackData,config.BEACON_TRACK_URL);
      /*document.getElementById('page-header').style.position = 'relative';
      var dfpPostParams = 'parentPage=DFP_HomePage'; 
      this.props.dfpBannerConfig(dfpPostParams);*/
     
    }

    componentWillUnmount()
    {
      PreventScrolling.enableScrolling(false,true);
      if(PreventScrolling.canUseDOM())
      {
          document.getElementById('page-header').style.display = "table";
          document.getElementById('page-header').style.position = "relative";
      }
      this.props.clearDfpBannerConfig();
      window.removeEventListener('scroll', this.getFeaturedCollege);
    }


  render() {
    return (
		<React.Fragment>
      {ClientSeo()}
    		<div className="MHP">
		    	<div className="bdl">
			    	<div>
			    		<TopSection/>
              <MiddleSection/>
              <LatestUpdateWidget/>
              {this.state.featuredCollegeData.length ? <Carousel heading="Featured Colleges" data={this.state.featuredCollegeData}  page="homepage"/>: null}
              <ExpertGuidanceWidget config={this.props.config}/>
            </div>
				</div>
			</div>
		      </React.Fragment>
    	)
  }
}
function mapStateToProps(state)
{
    return {
        config : state.config
    }
}

function mapDispatchToProps(dispatch){
  return bindActionCreators({ fetchInitialData,dfpBannerConfig,clearDfpBannerConfig }, dispatch);  
} 
export default connect(mapStateToProps,mapDispatchToProps)(HomePage);
