import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import ClientSeo from './../../common/components/ClientSeo';
import './../instituteChildPages/assets/style.css';
import './../instituteChildPages/assets/ADPDesktop.css';
import ChildPagesInterlinking from './../instituteChildPages/components/ChildPagesInterlinking';
import CutoffWidgetWrapper from './../instituteChildPages/components/CutoffWidgetWrapper';

              
class CutOffPageDesktop extends React.Component
{
  constructor(props)
    {
      super(props);
    }

  instituteLocationName(){
        const {childPageData,config} = this.props;
        let locationName = '';

        if(childPageData.currentLocation){
            if(childPageData.currentLocation.locality_name){
                locationName +=  childPageData.currentLocation.locality_name;
            }
            let showCityName = this.showCityName();
            if(childPageData.currentLocation.city_name && showCityName){
                if(locationName !=''){
                    locationName += ', ';
                }
                locationName += childPageData.currentLocation.city_name;
            }
        }
        return locationName;
    }

    showCityName(){
        const {childPageData} = this.props;
        let instituteName = '';
        let cityName = '';
        if(childPageData.instituteTopCardData && childPageData.instituteTopCardData.instituteName){
            instituteName = childPageData.instituteTopCardData.instituteName.toLowerCase();
        }
        if(childPageData.currentLocation && childPageData.currentLocation.city_name){
            cityName = childPageData.currentLocation.city_name.toLowerCase();
        }
        return (instituteName.indexOf(cityName) == -1)

    }

  getHeadingHtml(heading,extraHeading =null ,linkingUrl = ""){
      var headingHtml = [];
      if( this.props.fromwhere && this.props.fromwhere == "institutePage"){
          headingHtml.push(<h1 className='inst-name' key="heading_h1">{heading} <span className='hid'> ,{this.instituteLocationName()}</span></h1>);
      }
      else{
          headingHtml.push(<h1 className='inst-name' key="heading_h1">{heading} {extraHeading}<span className='hid'> ,{this.instituteLocationName()}</span></h1>);
      }
      return headingHtml;
  }

  render(){

      const pageUrl = this.props.childPageData.listingUrl+'/cutoff';
      const {childPageData,config} = this.props;
      let seoData = (childPageData && childPageData.seoData) ? childPageData.seoData : '', 
      extraHeadingSuffix = (seoData.headingSuffix != null) ? seoData.headingSuffix: 'Cut-Offs';
      return(
            <React.Fragment>
              {ClientSeo(seoData)}
              <div className="ilp courseChildPage" id="PP">
                  {this.getHeadingHtml(childPageData.instituteTopCardData.instituteName, extraHeadingSuffix)}
                  <CutoffWidgetWrapper 
                    data={childPageData.courseTuples}
                    config={config}
                    listingId={childPageData.listingId}
                     listingName ={childPageData.listingName}
                     listingType = {childPageData.listingType}
                     device='desktop'
                     trackingKey = {3677}
                     pdfUrl ={'shiksha.com'}
                     shortlistTrackingKey = {3671}
                     ebTrackingKey = {3675}
                     gaTrackingCategory={"Cutoff_Page_Desk"}
                     ViewMoreCount = {100}
                     contentLoaderData={this.contentLoaderData}
                    courseTupleCount = {childPageData.courseTupleCount}
                    courseTupleLimit = {30}
                     pageUrl ={childPageData.seoUrl}
                     childPageData={childPageData}
                     isPDF={true}
                  />
                  <ChildPagesInterlinking data={childPageData} 
                                              gaCategory='Placement_Page_Desk' 
                                              fromWhere= "cutoffPage" 
                                              similarPlacement={false}
                                              />
              </div>
            </React.Fragment>
        )
  }

}

function mapStateToProps(state)
{
    return {
      childPageData : state.childPageData,
      config : state.config,
      contentLoaderData : state.contentLoaderData
    }
}
function mapDispatchToProps(dispatch){
    return bindActionCreators({ }, dispatch);
}
export default connect(mapStateToProps,mapDispatchToProps)(CutOffPageDesktop);
