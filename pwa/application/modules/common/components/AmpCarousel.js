import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import styles from '../assets/carousel.css';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import Lazyload from './../../reusable/components/Lazyload';
import { Link } from "react-router-dom";
import DownloadEBrochure from './DownloadEBrochure';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import {storeCourseDataForPreFilled} from './../../listing/course/actions/CourseDetailAction';
import config from './../../../../config/config';

class AmpCarousel extends React.Component {
    constructor(props)
    {
        super(props);
        this.trackEvent = this.trackEvent.bind(this);
    }
    storeCourseDataFromRecoPage(item)
    {
        this.props.storeCourseDataForPreFilled(item);
    }
    trackEvent(category,action,labelName)
    {
        Analytics.event({category : category, action : action, label : labelName});
    }
    render(){
        const {data,page,section,isCallReco,heading,courseId} = this.props;
        const domainName = config().SHIKSHA_HOME;
        var self = this;
            let listItems='';

            let textLength = '70';
            let gaEventName = 'DBROCHURE_COURSE_ALSOVIEWED';
            if(page=="coursepage" || page == "institutepage"){
                if(section=="similar"){
                    textLength = '60';
                    gaEventName = 'DBROCHURE_COURSE_SIMILAR';
                }

                if(section=="alsoviewed" || section=="similar"){
                    listItems = data.map((item, index) =>
                    <figure className="slide-fig color-w" key={index}>
                       <a href={item.instituteUrl}>
                          <amp-img src={item.imageUrl} alt={item.instituteName} width="155" height="116" ></amp-img>
                       </a>
                       <div className="pad-5">
                          <a href={item.instituteUrl} className="caption color-3 f14 font-w6 m-5btm clg-tl">{item.instituteName != null && item.instituteName.length>textLength?item.instituteName.substring(0,textLength)+'...': item.instituteName}</a>
                          {((item.cityName!=null &&  item.cityName!='') && (item.establishYear!=null &&  item.establishYear!=''))?
                              <figcaption className="caption color-6 f12 font-w4 m-5btm clg-lc">
                                {item.cityName } | Estd. { item.establishYear }
                             </figcaption> : ''
                          }
                          {((item.cityName!=null &&  item.cityName!='') && (item.establishYear==null ||  item.establishYear=='')) ?
                                <figcaption className="caption color-6 f12 font-w4 m-5btm clg-lc">
                                  {item.cityName }
                               </figcaption> : ''
                          }

                           <a href={item.courseUrl}  className="caption color-3 f14 font-w6 clg-tl">{item.courseName}</a>
                           <a  href={domainName+"/muser5/UserActivityAMP/getResponseAmpPage?listingType=course&listingId="+courseId+"&actionType=brochure&fromwhere=coursepage&pos="+section} className="btn btn-primary color-o color-f f14 font-w7 m-top ga-analytic" data-vars-event-name={gaEventName}>Request Brochure</a>

                       </div>
                    </figure>


                    );
                }
            }

            let classIdentifier = "";

            if(page=="coursepage" || page == "institutepage")
            {
                classIdentifier = "coursepage_sldrC sldrC";
            }
            else{
                classIdentifier = page+"_sldrC sldrC";
            }
            return (
                <section className='data-card'>
                <h2 className='color-3 f16 heading-gap font-w6'>{heading}</h2>
                <div className='card-cm'>
                    <div className={classIdentifier} >
                        <div className='sldAr'>
                        <amp-carousel  height="390px" width="auto" type="carousel"  class="s-c ga-analytic" data-vars-event-name="STUDENT_WHO_VIEWED">
                            {listItems}
                        </amp-carousel>
                    </div>
                    </div>
                </div>
                </section>
            );
}
}

function mapDispatchToProps(dispatch){
  return bindActionCreators({ storeCourseDataForPreFilled }, dispatch);
}
export default connect(null,mapDispatchToProps)(AmpCarousel);

//export default Carousel;
