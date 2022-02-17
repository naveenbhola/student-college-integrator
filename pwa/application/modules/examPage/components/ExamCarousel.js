import React from 'react';
import './../../common/assets/carousel.css';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import {removeDomainFromUrl} from './../../../utils/urlUtility';
import Anchor from './../../reusable/components/Anchor';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import {storeCourseDataForPreFilled} from './../../listing/course/actions/CourseDetailAction';
import {storeInstituteDataForPreFilled} from './../../listing/institute/actions/InstituteDetailAction';
import PropTypes from 'prop-types';

class ExamCarousel extends React.Component {
    constructor(props){
        super(props);
        this.trackEvent = this.trackEvent.bind(this);
    }

    storeCourseDataFromRecoPage(item){
        this.props.storeCourseDataForPreFilled(item);
    }

    storeInstituteDataFromRecoPage(item){
        this.trackEvent('ChooseILP','click');
        if(typeof item != 'undefined' && typeof item == 'object') {
          var data =  [];
          data.instituteName = item.instituteName;
          data.cityName = item.cityName;
          data.instituteId = item.instituteId;
          this.props.storeInstituteDataForPreFilled(data);
        }
    }
    
    trackEvent(action,labelName){
        Analytics.event({category : this.props.category, action : action, label : labelName});
    }

    render(){
            const {data,heading} = this.props;    
            var self = this;
            let listItems='';
            let textLength = 45;            
            listItems = data.map((item, index) =>
            <li key={index}>
                <div className="vwd-clg">
                    <Anchor link={(this.props.deviceType == 'desktop') ? false : true} className="link" to={removeDomainFromUrl(item.instituteUrl)} onClick={self.storeInstituteDataFromRecoPage.bind(self,item)}>
                        <img className="lazy" data-original={(item.imageUrl) ? item.imageUrl : self.props.defaultImageUrl} alt={item.instituteName}/>
                    </Anchor>
                </div>
                <div className="vwd-clgInfo">
                    <p><Anchor link={(this.props.deviceType == 'desktop') ? false : true} className="link" to={removeDomainFromUrl(item.instituteUrl)} onClick={self.storeInstituteDataFromRecoPage.bind(self,item)}>{item.instituteName != null && item.instituteName.length>textLength?item.instituteName.substring(0,textLength)+'...': item.instituteName}</Anchor></p>
                    {((item.cityName!=null &&  item.cityName!='') && (item.establishYear!=null &&  item.establishYear!=''))?
                        <span>{item.cityName + " | Estd."+ item.establishYear}</span>:''
                    }
                    {((item.cityName!=null &&  item.cityName!='') && (item.establishYear==null ||  item.establishYear==''))?
                        <span>{item.cityName}</span>:''
                    }
                    {item.courseName!=null && item.courseName!=''?
                    <p><Anchor link={(this.props.deviceType == 'desktop') ? false : true} className="link" to={item.courseUrl} onClick={self.storeCourseDataFromRecoPage.bind(self,item)}>{item.courseName && item.courseName.length>textLength?item.courseName.substring(0,textLength)+'...': item.courseName}</Anchor></p>:''}

                    {item.ctaText && <a onClick={self.trackEvent.bind(this,'Click','CTA')} href={item.redirectUrl} className="button button--orange rippleefect dark">{item.ctaText}</a>}

                </div>
            </li>
            );
                
            let classIdentifier = "coursepage_sldrC sldrC";
            return (
                <section className='ftrbnr listingTuple'>
                <div className='_container'>
                    <h2 className='tbSec2'>{heading}</h2>
                    <div className={classIdentifier} >
                        <div className='sldAr'>
                        <ul className='ftrSlder'>
                            {listItems}
                        </ul>
                    </div>
                    </div>
                </div>
                </section>
            );
    }
}

ExamCarousel.defaultProps = {
    category : 'ExamPage_Featured_Institute',
    defaultImageUrl : 'https://images.shiksha.ws/public/mobile5/images/recommender_dummy.png'
};

ExamCarousel.propTypes = {
   data : PropTypes.array,
   category: PropTypes.string,
   defaultImageUrl : PropTypes.string,
   heading : PropTypes.string,
   deviceType : PropTypes.string,
   storeCourseDataForPreFilled : PropTypes.func,
   storeInstituteDataForPreFilled : PropTypes.func
}

function mapDispatchToProps(dispatch){
  return bindActionCreators({ storeCourseDataForPreFilled ,storeInstituteDataForPreFilled}, dispatch); 
}

export default connect(null,mapDispatchToProps)(ExamCarousel);
