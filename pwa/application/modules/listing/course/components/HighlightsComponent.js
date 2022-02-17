import PropTypes from 'prop-types'
import React from 'react';
import './../assets/highlights.css';
import './../assets/courseCommon.css';
import {htmlEntities,nl2br} from './../../../../utils/stringUtility';
import { makeURLAsHyperlink } from './../../../../utils/urlUtility';
import Analytics from './../../../reusable/utils/AnalyticsTracking';

const Highlights = (props) => {
    var category = 'CLP';
    if(props.page == "institute"){
        category = 'ILP';
    }
    else if(props.page == "university"){
        category = 'ULP';
    }
    const trackEvent = () => (Analytics.event({category : category, action : 'Highlightswidget', label : 'Click'}));

    const {isAmp} = props;
    var numberOfUSP = 0;
    var maxDisplayUsp = 4;

    return(
        <React.Fragment>
            { (!isAmp) ?  <section className='hilightBnr listingTuple' id="highlights">
                    <div className='_container'>
                        <h2 className='tbSec2'>Highlights</h2>
                        <div className='_subcontainer'>
                            <input type="checkbox" className='read-more-state hide'  id="hglts-more"/>
                            <ul className='nrml-list read-more-wrap'>
                                {
                                    props.data.map(function(usp,index){
                                        numberOfUSP++;
                                        return <li key={"usp_"+index} className={index >= maxDisplayUsp ? 'read-more-target' : ''} dangerouslySetInnerHTML={{ __html :nl2br(makeURLAsHyperlink(htmlEntities(usp.description)))}}></li>
                                    })
                                }
                            </ul>
                            {numberOfUSP > maxDisplayUsp && <div className='nrml-list-cntnr read-more-trigger'>
                                <a className='nrml-link' onClick={trackEvent}>
                                    <label htmlFor="hglts-more">Read More</label>
                                </a>
                            </div>}
                        </div>
                    </div>
                </section>:
                <div className="data-card m-5btm" id="high">
                    <h2 className="color-3 f16 heading-gap font-w6">Highlights</h2>
                    <div className="card-cmn color-w">
                        <input type="checkbox" className="read-more-state hide" id="post-11" />
                        <ol className="highlights-ol read-more-wrap">

                            {
                                props.data.map(function(usp,index){
                                    numberOfUSP++;
                                    return <li key={"usp_"+index} className={index >= maxDisplayUsp ? 'read-more-target color-6 f13 word-break' : 'color-6 f13 word-break'} dangerouslySetInnerHTML={{ __html :nl2br(makeURLAsHyperlink(htmlEntities(usp.description)))}}></li>
                                })
                            }
                        </ol>
                        {numberOfUSP > maxDisplayUsp &&
                        <label htmlFor="post-11" className="read-more-trigger color-b t-cntr f14 color-b block font-w6 v-arr ga-analytic" data-vars-event-name="Highlight_VIEWALL">View all</label>
                        }

                    </div>
                </div>}
        </React.Fragment>
    );
};

Highlights.defaultProps = {
    isAmp : false
}
export default Highlights;

Highlights.propTypes = {
    data: PropTypes.any,
    isAmp: PropTypes.bool,
    page: PropTypes.any
}