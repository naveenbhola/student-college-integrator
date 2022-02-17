import React from 'react';
import {Link} from 'react-router-dom';
import  './../assets/TopColleges.css';
import WikiContent from './../../../common/components/WikiContent';
import FullPageLayer from './../../../common/components/FullPageLayer';
import Analytics from '../../../../modules/reusable/utils/AnalyticsTracking';
import {removeDomainFromUrlV2} from './../../../../utils/urlUtility';

class TopCollegesByLoc extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {}
   }
    setReferrer(){
        window.referrer = window.location.href;
    }

  openCityLocationLayer() {
        this.setState({
            locationCityFullLayer: true
        });
        this.addBackground();
        this.trackEvent('City_ViewMore');
    }

  openStateLocationLayer() {
        this.setState({
            locationStateFullLayer: true
        });
        this.addBackground();
        this.trackEvent('State_ViewMore');
    }

  closeCityLocationLayer(){
        this.setState({
            locationCityFullLayer: false
        })
        this.removeBackground();
    }

  closeStateLocationLayer(){
        this.setState({
            locationStateFullLayer: false
        })
        this.removeBackground();
    }

  addBackground(){
    if(document.getElementById('ChpDesktop')==null){
      return;
    }
    document.getElementById('fullLayer-container').classList.add("show");
    document.getElementById('wrapperMainForCompleteShiksha').classList.add("noscroll");
  }

  removeBackground(){
    if(document.getElementById('ChpDesktop')==null){
      return;
    }
    document.getElementById('fullLayer-container').classList.remove("show");
    document.getElementById('wrapperMainForCompleteShiksha').classList.remove("noscroll");
  }
   getWikiHtml(){
      var self = this;
      var sectionData  = new Array();
      let html = (this.props.sectiondata.wikiData).map(function (data, index){
              return (<WikiContent key={index} order={self.props.order} sectiondata={data} sectionname={self.props.sectionname}/>);
      });
      sectionData.push(html);
      return sectionData;
   }

  trackEvent = (linkText) =>{
    let actionLable = (this.props.deviceType == 'desktop') ? 'CHP_Desktop_TopRankedCollege' : 'CHP_TopRankedCollege';
    Analytics.event({category : 'CHP', action : actionLable, label : 'CHP_'+linkText});
  }

   getLocationHtml(location, locationData, limit){
      var self = this;
      var sectionData  = new Array();
      let html = '';
      if(limit == 'all'){
        html = (locationData).map(function (data, index){
          if(self.props.isPdfCall && self.props.deviceType != 'desktop'){
            return (<a onClick={self.trackEvent.bind(this,location)} href={data.url} className ="_clist rippleefect" target="_blank" key={'location_'+index}>{data.title}</a>);
          }else{
            return (<Link onClick={self.trackEvent.bind(this,location)} to={removeDomainFromUrlV2(data.url)} className ="_clist rippleefect" key={'location_'+index}>{data.title}</Link>);
          }
        });
      }else{
        html  = (locationData.slice(0, limit)).map(function (data, index){
          if(self.props.isPdfCall && self.props.deviceType != 'desktop'){
            return (<a onClick={self.trackEvent.bind(this,location)} href={data.url} className ="_clist rippleefect" target="_blank" key={'location_'+index}>{data.title}</a>);
          }else{
            return (<Link onClick={self.trackEvent.bind(this,location)} to={removeDomainFromUrlV2(data.url)} className ="_clist rippleefect" key={'location_'+index}>{data.title}</Link>);
          }
        });
      }


      sectionData.push(html);
      return sectionData;
   }

   render()
   {
      const {config} = this.props;
      let chpWikiData    = '';
      let cityList    = '';
      let fullCityList    = '';
      let stateList    = '';
      let fullStateList    = '';
      if(this.props.sectiondata.wikiData!=null){
        chpWikiData   = this.getWikiHtml();
      }


      if(this.props.sectiondata.tuple!=null && this.props.sectiondata.tuple.cityList!=null){
        fullCityList = this.getLocationHtml('city',this.props.sectiondata.tuple.cityList, 'all');
        cityList = this.getLocationHtml('city',this.props.sectiondata.tuple.cityList, 10);
      }

      if(this.props.sectiondata.tuple!=null && this.props.sectiondata.tuple.stateList!=null){
        fullStateList = this.getLocationHtml('state',this.props.sectiondata.tuple.stateList, 'all');
        stateList = this.getLocationHtml('state',this.props.sectiondata.tuple.stateList, 10);
      }

      return (
            <React.Fragment>
             <section id="TopCollegesByLoc">
                <div className="_container">
                  <h2 className="tbSec2">Top Ranked Colleges by Location</h2>
                  <div className="_subcontainer">
                    {chpWikiData}
                  </div>
                  {(cityList!='' || stateList!='')?
                   <div>
                      <div className="_padaround">
                      {cityList!=''?
                         <div className="_browsesection">
                            <div className="_sctntitle">Browse by Cities</div>
                            <div className="_browseBy">
                               <div className="_browseRow">
                                  {cityList}
                                  { this.props.sectiondata.tuple.cityList.length>8 && <span id="viewAllTags" className="_clist viewalltags rippleefect" onClick={this.openCityLocationLayer.bind(this)}>View More</span> }
                                  { this.props.sectiondata.tuple.cityList.length>8 && <FullPageLayer data={fullCityList} heading='Top Ranked Colleges in' isOpen={this.state.locationCityFullLayer} onClose={this.closeCityLocationLayer.bind(this)}/> }
                               </div>
                            </div>
                         </div>:''}
                        {stateList!=''?
                         <div className="_browsesection">
                            <div className="_sctntitle">Browse by States</div>
                            <div className="_browseBy">
                               <div className="_browseRow">
                                  {stateList}
                                  { this.props.sectiondata.tuple.stateList.length>8 && <span id="viewAllTags" className="_clist viewalltags rippleefect" onClick={this.openStateLocationLayer.bind(this)} >View More</span> }
                                  { this.props.sectiondata.tuple.stateList.length>8 && <FullPageLayer data={fullStateList} heading='Top Ranked Colleges in' isOpen={this.state.locationStateFullLayer} onClose={this.closeStateLocationLayer.bind(this)}/> }
                                </div>
                            </div>
                         </div>:''}
                      </div>
                   </div>:''}
                </div>
             </section>
            </React.Fragment>
         )
   }
}

export default TopCollegesByLoc;
