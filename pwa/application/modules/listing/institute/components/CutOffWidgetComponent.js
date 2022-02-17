import PropTypes from 'prop-types'
import React from 'react';
import {Link} from 'react-router-dom';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import './../assets/css/style.css';
import './../../course/assets/courseCommon.css';
import {getTextFromHtml} from './../../../../utils/stringUtility';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {addingDomainToUrl} from './../../../../utils/commonHelper';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import {storeChildPageDataForPreFilled} from './../../instituteChildPages/actions/AllChildPageAction';

class CutOffWidget extends React.Component {

    handleClickonCutoff(eventAction, PageHeading = ''){
      this.trackEvent();
      let data = {},i=0;
      let dataArr = ['listingId','listingType','instituteTopCardData','reviewWidget','currentLocation','aggregateReviewWidget','anaCountString','filters'];
      for(i=0;i<dataArr.length - 1;i++){
        if(this.props.instituteData[dataArr[i]] && this.props.instituteData[dataArr[i]] != 'undefined' ){
          data[dataArr[i]] = this.props.instituteData[dataArr[i]];
        }
      }
      data.anaWidget = {};
      data.allQuestionURL = ''; 
      data.showFullLoader = false;
      data.showFullSectionLoader = true;
      data.PageHeading = 'Cut off & Merit List 2019';
      if(PageHeading !=''){
       data.PageHeading = PageHeading+data.PageHeading; 
      }
      data.fromWhere = "cutoffPage";
      this.props.storeChildPageDataForPreFilled(data);
    }

    trackEvent(action='CutOffwidget',label='Click'){
        var category = 'ILP';
        if(this.props.page == "university"){
            category = 'ULP';
        }
        Analytics.event({category : category, action : action, label : label})
    }

    render(){
        const CutOffPageUrl = (this.props.data.cutOffUrl)?addingDomainToUrl(this.props.data.cutOffUrl,this.props.config.SHIKSHA_HOME):null;
        var content = (this.props.data.previewText)? this.props.data.previewText:null;
        let isAmp =  this.props.isAmp;
        let trackingKey = 3689;
        if(this.props.page == 'university'){
          trackingKey = 3681;
        }
        if(content && CutOffPageUrl){
        return (
          <React.Fragment>
            {(!isAmp) ?
                <section className='listingTuple' id="Cut-Offs">
                    <div className="_container">
                        <h2 className="tbSec2">College Cut-Offs</h2>
                        <div className="_subcontainer">
                            <div className="adm-DivSec">
                                <p key={"description_"} className='word-wrap' dangerouslySetInnerHTML={{ __html :(getTextFromHtml(content,1000))}}></p>
                            </div>
                            <div className="gradient-col gardient-flex" id="viewMoreLink" >
                                {this.props.pdfUrl && <DownloadEBrochure actionType='Download_Cutoff_Details' heading='Thank you for your Interest.' className='button--purple' ctaName='downloadCutoffDetails'  buttonText='Download Cut-Off Details' listingId={this.props.listingId} uniqueId={'downloadCutoffDetails_'+this.props.listingId} listingName={this.props.listingName} recoEbTrackid={trackingKey}  isCallReco={false} clickHandler={this.trackEvent.bind(this,'NEWCTA','click_Download_Cutoff_Details')} page = {this.props.listingType} pdfUrl={this.props.pdfUrl}/>}
                                <Link to={this.props.data.cutOffUrl} onClick = {this.handleClickonCutoff.bind(this)} className="gradVw-mr button button--secondary rippleefect">View More<i className="blu-arrw"></i></Link></div>
                            </div>
                        </div>
                    </section> :
                        <section>
                            <div className="data-card m-5btm pos-rl">
                                <h2 className="color-3 f16 heading-gap font-w6">College Cut-Offs</h2>
                                <div className="card-cmn color-w schlrs-ampDv">
                                    <div className="cutoff-div">
                                        <div className="column-list">
                                            {content.map(function(description,index){
                                                return <p key={"description_"+index}  dangerouslySetInnerHTML={{ __html :(getTextFromHtml(description,1000))}}></p>
                                            })
                                            }
                                        </div>
                                    </div>
                                    <div className="gradient-col hide-class">
                                        <a href={CutOffPageUrl} onClick = {this.trackEvent.bind(this)} className="color-b btn-tertiary f14 ga-analytic" data-vars-event-name="VIEW_MORE_CUTOFF">View All Cutoffs</a>
                                    </div>
                                </div>
                            </div>
                        </section>
                    }
                </React.Fragment>
        );
        }
        else return null;
    }

}

CutOffWidget.defaultProps = {
    isAmp: false
}


function mapDispatchToProps(dispatch){
  return bindActionCreators({ storeChildPageDataForPreFilled}, dispatch);
}

export default connect(null,mapDispatchToProps)(CutOffWidget);

CutOffWidget.propTypes = {
  config: PropTypes.any,
  data: PropTypes.any,
  isAmp: PropTypes.bool,
  page: PropTypes.any
}