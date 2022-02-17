import PropTypes from 'prop-types'
import React from 'react';
import {Link} from 'react-router-dom';
import LinkOCF from './LinkOCF';
import ChildPagesInterlinking from './ChildPagesInterlinking';
import EditorialContentComponent from './EditorialContentComponent';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';
import {mobileSectionLoder} from './../../../../utils/commonHelper';

class CutOffPageContentLoader extends React.Component {
    
    constructor(props){
        super(props);
    }

    getBipSipLoader(college,exam){

      const {deviceType} = this.props;
      let BipSipHtml = [];
      let heading = 'Select a College';
      if(college){
        heading = 'Select an Exam';
      }

      BipSipHtml.push(
        <React.Fragment key="bip_sip">
                   <div className={"_browsesection textOverflow"}>
                        <div className="_sctntitle">{heading}</div>
                        <div className="_browseBy">
                           <div className="_browseRow">
                           <a className="_clist loader-line shimmer"></a>
                           <a className="_clist loader-line shimmer"></a>
                           {deviceType == 'desktop' && <a className="_clist loader-line shimmer"></a>}
                           {deviceType == 'desktop' && <a className="_clist loader-line shimmer"></a>}
                           {deviceType == 'desktop' && <a className="_clist loader-line shimmer"></a>}
                           </div>
                        </div>
                  </div>
             </React.Fragment>);

      return BipSipHtml;
    
    }

    getMobileFilterLoader(){
      const {contentLoaderData} = this.props;
      let filtersHtml = [];

      filtersHtml.push(
      <React.Fragment key="filter">
        <div id="tab-section" className="cutoff_filters_tab">
            <div className="fixed-card-wrapper" id="fixed-card-wrapper">
              <div className={"ctp-filter-sec dspTbl"  }>
                  <a href="javascript:void(0)" id="filters_mobile">Filters <span>
                  {contentLoaderData.selectedFiltersCount && contentLoaderData.selectedFiltersCount > 0 ? '(' + contentLoaderData.selectedFiltersCount + ')':''}</span></a>
                  <a className='reset-link' href={contentLoaderData.pageUrl} ><i className='reset-ico'></i> Reset </a>
                </div>
            </div>
        </div>
      </React.Fragment>);

      return filtersHtml; 
    }

    getDesktopFilterLoader(){
      const {contentLoaderData} = this.props;
      let filtersHtml = [];

      filtersHtml.push(
      <React.Fragment key="filter">
        <section class="listingTuple" id="admission"><div class="_container"><div class="cutt-off_filters"><div class="filters_exam filter_arw click-cls-ex"><div class="filter__text click-cls-ex"><div className='loader-line shimmer  wdt75'></div> </div></div><div class="filters_exam filter_arw click-cls-clg"><div class="filter__text click-cls-clg"><div className='loader-line shimmer  wdt75'></div></div></div><div class="filters_exam filter_arw click-cls-spe"><div class="filter__text click-cls-spe"><div className='loader-line shimmer  wdt75'></div></div></div><div class="filters_course filter_arw click-cls-cat"><div class="filter__text click-cls-cat"><div className='loader-line shimmer  wdt75'></div></div></div></div></div></section>
      </React.Fragment>);

      return filtersHtml; 
    }

    getLeftSectionLoader(){
      const {contentLoaderData} = this.props;

      if(contentLoaderData.childPageData){
        let showOCF = false;
        if(this.props.deviceType == 'desktop' && contentLoaderData.examOCF){
          showOCF = true;
        }else if(this.props.deviceType == 'mobile'){
          showOCF = true;
        }
       return(
        <React.Fragment>
          {contentLoaderData.aboutCollege && <EditorialContentComponent data={contentLoaderData.aboutCollege} readMoreCount={this.props.deviceType == 'desktop' ? 750 : 450} gaCategory='Cutoff_Page_mobile' deviceType='mobile'/>}  

          {this.props.deviceType == 'mobile' && this.getMobileFilterLoader()}
          
          {showOCF && <div className={"_padaround white--bg"+ (this.props.deviceType == 'mobile' ? " mgBtm":"")}>
                      {contentLoaderData.collegeOCF && <LinkOCF selectedTag={contentLoaderData.selectedTag} collegeOCF={true} examOCF={false} pageUrl={contentLoaderData.pageUrl} childPageData={contentLoaderData.childPageData}  gaCategory={'Cutoff_Page_mobile'}/>}
                      {this.props.deviceType == 'mobile' && this.getBipSipLoader(contentLoaderData.collegeOCF,contentLoaderData.examOCF)}
                      {contentLoaderData.examOCF && <LinkOCF selectedTag={contentLoaderData.selectedTag} collegeOCF={false} examOCF={true} pageUrl={contentLoaderData.pageUrl} childPageData={contentLoaderData.childPageData} gaCategory={'Cutoff_Page_mobile'} />}
                    </div>}

          {this.props.deviceType == 'desktop' && this.getDesktopFilterLoader()}

          

          {mobileSectionLoder()}
          
          {mobileSectionLoder()}
        
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
      if(contentLoaderData.childPageData){
      return(
        <React.Fragment>
          {this.props.deviceType == 'desktop' && <DFPBannerTempalte key={"Dfpbanner3"} bannerPlace="C_RP" parentPage={"DFP_InstituteDetailPage"}/>}
          <ChildPagesInterlinking data={contentLoaderData.childPageData} 
            gaCategory='CutOff_Page_Desk' 
            fromWhere= "cutoffPage" 
            similarPlacement={true}
            />  
          <ChildPagesInterlinking data={contentLoaderData.childPageData} 
            gaCategory='CutOff_Page_Desk' 
            fromWhere= "cutoffPage" />   
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


export default CutOffPageContentLoader;
CutOffPageContentLoader.defaultProps = {
  contentLoader: false,
  filterLoader: false
}

CutOffPageContentLoader.propTypes = {
  contentLoader: PropTypes.bool,
  currentPage: PropTypes.any,
  filter: PropTypes.any,
  filterLoader: PropTypes.bool,
  fullLoader: PropTypes.any,
  onlyBipSip: PropTypes.any,
  tuple: PropTypes.any
}