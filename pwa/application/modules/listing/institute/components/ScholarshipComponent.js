import PropTypes from 'prop-types'
import React from 'react';
import './../assets/css/style.css';
import './../../course/assets/courseCommon.css';
import {htmlEntities,nl2br} from './../../../../utils/stringUtility';
import { makeURLAsHyperlink } from './../../../../utils/urlUtility';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {addingDomainToUrl} from './../../../../utils/commonHelper';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import {ILPConstants, ULPConstants} from './../../categoryList/config/categoryConfig';

const Scholarship = (props) => {
   
    var category = 'ILP';
    let config = ILPConstants();
    if(props.page == "university"){
        category = 'ULP';
        config = ULPConstants();
    }
    const trackEvent = () => (Analytics.event({category : category, action : 'Scholarshipwidget', label : 'Click'}));

    const scholarship = props.scholarship.scholarShipDetails;
    const scholarshipPdf = props.scholarship.pdfUrl;

    let isAmp = props.isAmp;
    const first_scholarship = scholarship[0];
    const allScholarshipUrl = addingDomainToUrl(props.scholarship.allScholarshipPageUrl,props.config.SHIKSHA_HOME);
    var heading ='';
    var scholarshipType_name = (first_scholarship.scholarship_type_name == "Discount")?"Scholarship":first_scholarship.scholarship_type_name;
    if(scholarship.length>1){
        heading = <label><strong>{scholarshipType_name}</strong></label>
    }
    return (
        <React.Fragment>
            {(!isAmp) ?
                <section className='listingTuple' id="scholarships">
                    <div className="_container">
                        <h2 className="tbSec2">Scholarships</h2>
                        <div className="_subcontainer">
                            <div className="adm-DivSec">
                                {heading}
                                <p className='word-wrap' dangerouslySetInnerHTML={{ __html : nl2br(makeURLAsHyperlink(htmlEntities(first_scholarship.description)))}} ></p>
                            </div>
                            <div className="gradient-col gardient-flex" id="viewMoreLink" >
                            { scholarshipPdf && <DownloadEBrochure actionType="Get_Scholarship_Details" className="button--blue" buttonText="Get Scholarship Details" ctaName='getScholarshipDetails' heading='Downloading Scholarship Details.'  uniqueId={'getScholarshipDetails_'+props.listingId} listingId={props.listingId} listingName={props.instituteName} recoEbTrackid={config.GetScholarshipDetailCTA} clickHandler={trackEvent.bind(this,'NEWCTA','click_get_scholarship_cta')} isCallReco={false} page = {props.page} pdfUrl={scholarshipPdf} />}
                              
                              <a href={allScholarshipUrl} className="gradVw-mr button button--secondary rippleefect" onClick = {trackEvent}>View more<i className="blu-arrw"></i></a>
                            </div>
                        </div>
                    </div>
                </section>:
                <section>
                    <div className='data-card m-5btm pos-rl'>
                        <h2 className='color-3 f16 heading-gap font-w6'>Scholarships</h2>
                        <div className='card-cmn color-w schlrs-ampDv'>
                            <input type="checkbox" className='read-more-state-out hide' id="post-11"/>
                            <ul className='sc-ul n-ul read-more-wrap'>
                                <input type="checkbox" className="read-more-state hide" id="scholarship" />
                                <li>{heading}<p className='read-more-wrap f14 color-6' dangerouslySetInnerHTML={{ __html : nl2br(makeURLAsHyperlink(htmlEntities(first_scholarship.description)))}} ></p></li>
                            </ul>
                            <div className='gradient-col hide-class'>
                                <a href={allScholarshipUrl} className="color-b btn-tertiary f14 ga-analytic" data-vars-event-name="VIEW_ALL_SCHOLARSHIP" on = {'tap:',trackEvent}>View Scholarships</a>
                            </div>
                        </div>
                    </div>
                </section>
            }
        </React.Fragment>
    );
};

Scholarship.defaultProps ={
    isAmp: false
}
export default Scholarship;

Scholarship.propTypes = {
  config: PropTypes.any,
  isAmp: PropTypes.bool,
  page: PropTypes.any,
  scholarship: PropTypes.any
}