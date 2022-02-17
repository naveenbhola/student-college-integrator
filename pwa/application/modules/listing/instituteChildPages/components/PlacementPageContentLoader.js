import PropTypes from 'prop-types'
import React from 'react';
import PlacementPageFilters from './PlacementPageFilters';
import EditorialContentComponent from './EditorialContentComponent';
import ChildPagesInterlinking from './ChildPagesInterlinking';
import {mobileSectionLoder} from './../../../../utils/commonHelper';

class PlacementPageContentLoader extends React.Component {
    
    constructor(props){
        super(props);
    }

    getBipSipLoader(yearClick){

      const {deviceType} = this.props;
      let BipSipHtml = [];

      BipSipHtml.push(
        <React.Fragment key="bip_sip">
                     <div className="_browsesection">
                          <div className="_sctntitle">{yearClick ?  "Select a course to view Salary details:" : "Select a passout batch"}</div>
                          <div className="_browseBy">
                             <div className="_browseRow">
                             <a className="_clist loader-line shimmer"></a>
                             <a className="_clist loader-line shimmer"></a>
                             {deviceType == 'desktop' && <a className="_clist loader-line shimmer"></a>}
                             {yearClick && <a className="_clist loader-line shimmer"></a>}
                             {yearClick && <a className="_clist loader-line shimmer"></a>}
                             {deviceType == 'desktop' && yearClick && <a className="_clist loader-line shimmer"></a>}
                             {deviceType == 'desktop' && yearClick && <a className="_clist loader-line shimmer"></a>}
                             </div>
                          </div>
                    </div>
             </React.Fragment>);

      return BipSipHtml;
    
    }

    getLeftSectionLoader(){
      const {contentLoaderData} = this.props;
      if(contentLoaderData.placementFiltersData){
       return(
        <React.Fragment>
            {contentLoaderData.aboutCollege && <EditorialContentComponent data={contentLoaderData.aboutCollege} readMoreCount={this.props.deviceType == 'mobile' ? 450 : 750} gaCategory='Placement_Page_Mobile' deviceType={this.props.deviceType}/>}
            <section className="naukri_alumini" id="naukri_alumini">
              <div className="_container ctpn-filter-sec">
                  <h2 className="tbSec2" id="alumni_heading">Alumni Employment</h2>
                  <span className='naukri-logo-txt'>Powered by 
                      <i className="naukri-img"></i>
                  </span>
                  <div className="_subcontainer">
                      <div className="salry_dtlsblock">
                        <div className="_padaround">
                          {contentLoaderData.yearClick && <PlacementPageFilters courseClick={false} placementPageUrl={contentLoaderData.placementFiltersData.placementPageUrl} seoUrl ={contentLoaderData.placementFiltersData.seoUrl} pageData={contentLoaderData.placementFiltersData.pageData} data={contentLoaderData.placementFiltersData.data} selectedBaseCourseId={contentLoaderData.selectedBaseCourseId} selectedYear={contentLoaderData.selectedYear} />}
                          {this.getBipSipLoader(contentLoaderData.yearClick)}
                          {contentLoaderData.courseClick && <PlacementPageFilters yearClick={false} placementPageUrl={contentLoaderData.placementFiltersData.placementPageUrl} seoUrl ={contentLoaderData.placementFiltersData.seoUrl} pageData={contentLoaderData.placementFiltersData.pageData} data={contentLoaderData.placementFiltersData.data} selectedBaseCourseId={contentLoaderData.selectedBaseCourseId} selectedYear={contentLoaderData.selectedYear} />}
                        </div>
                        <div className="loader-ContDiv">
                             <div className="loader-line shimmer"></div>
                             <div className="loader-line shimmer wdt85"></div>
                             <div className="loader-line shimmer wdt75"></div>
                             <div className="loader-line shimmer wdt75"></div>
                             <div className="loader-line shimmer wdt75"></div>
                        </div>
                      </div>
                  </div>
              </div>
          </section>
          <section>
            {mobileSectionLoder()}
          </section>
        </React.Fragment>
        );
      }
      else {
        <React.Fragment>
          <section>
            {mobileSectionLoder()}
          </section>
          <section>
            {mobileSectionLoder()}
          </section>
        </React.Fragment>
      }

    }

    getRightSectionLoader(){
      const {contentLoaderData} = this.props;
      if(contentLoaderData.placementFiltersData.pageData){
      return(
        <React.Fragment>
          <ChildPagesInterlinking data={contentLoaderData.placementFiltersData.pageData} 
            gaCategory='Placement_Page_Desk' 
            fromWhere= "placementPage" 
            similarPlacement={true}
            />  
          <ChildPagesInterlinking data={contentLoaderData.placementFiltersData.pageData} 
            gaCategory='Placement_Page_Desk' 
            fromWhere= "placementPage" />   
        </React.Fragment>    
        );  
      }
      else{
        return mobileSectionLoder();
      }
    }
    
    render(){

        const {contentLoaderData,deviceType} = this.props;
         
        let loaderHtml =[];

        if(deviceType == 'mobile'){

          loaderHtml.push(<React.Fragment key="mobile_loader_html">
                {this.getLeftSectionLoader()}
                {this.getRightSectionLoader()}
            </React.Fragment>
            );

        }

        if(deviceType == 'desktop'){
          
          loaderHtml.push(<React.Fragment key="desktop_loader_html">
                  <div className="pwa_leftCol" >
                    {this.getLeftSectionLoader()}
                  </div>
                  <div className="pwa_rightCol">
                    {this.getRightSectionLoader()}
                  </div>
               </React.Fragment>
            );

        }

        return loaderHtml;
      
    }



}


export default PlacementPageContentLoader;
PlacementPageContentLoader.defaultProps = {
  contentLoader: false,
  filterLoader: false
}

PlacementPageContentLoader.propTypes = {
  contentLoader: PropTypes.bool,
  currentPage: PropTypes.any,
  filter: PropTypes.any,
  filterLoader: PropTypes.bool,
  fullLoader: PropTypes.any,
  onlyBipSip: PropTypes.any,
  tuple: PropTypes.any
}