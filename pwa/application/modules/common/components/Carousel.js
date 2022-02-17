import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import styles from '../assets/carousel.css';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import Lazyload from './../../reusable/components/Lazyload';
import {removeDomainFromUrl} from './../../../utils/urlUtility';
import { Link } from "react-router-dom";
import DownloadEBrochure from './DownloadEBrochure';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import {storeCourseDataForPreFilled} from './../../listing/course/actions/CourseDetailAction';
import {storeInstituteDataForPreFilled} from './../../listing/institute/actions/InstituteDetailAction';

class Carousel extends React.Component {
    constructor(props)
    {
        super(props);
        this.trackEvent = this.trackEvent.bind(this);
    }
    storeCourseDataFromRecoPage(item)
    {
        this.props.storeCourseDataForPreFilled(item);
    }
    storeInstituteDataFromRecoPage(item)
    {
        this.trackEvent('ChooseILP','click');
        if(typeof item != 'undefined' && typeof item == 'object') {
          var data =  [];
          data.instituteName = item.instituteName;
          data.cityName = item.cityName;
          data.instituteId = item.instituteId;
          this.props.storeInstituteDataForPreFilled(data);
        }
    }
    trackEvent(action,labelName)
    {
        if(this.props.page == "coursePage"){
            Analytics.event({category : 'CLP', action : action, label : labelName});
        }
        else if(this.props.page == "institute"){
            Analytics.event({category : 'ILP', action : action, label : labelName});
        }else {
            let category = (this.props.category) ? this.props.category : 'ULP';
            Analytics.event({category : category, action : action, label : labelName});
        }
    }
    render(){
        const {data,page,section,isCallReco,heading} = this.props;
        var recoEbTrackid =1073;
        var recoCMPTrackid = 1074;
        var recoShrtTrackid = 1075;
        var EbTrackid = 1123;
        if(page == "institute"){
            recoEbTrackid = 1063;
            recoCMPTrackid = 1074;
            recoShrtTrackid = 1208;
            if(this.props.section == 'similar') {
                EbTrackid = 1126;
            }
            else {
                EbTrackid = 1125;
            }
        }
        else if(page == "university"){
            recoEbTrackid = 1077;
            recoCMPTrackid = 1074;
            recoShrtTrackid = 1070;
            if(this.props.section == 'similar') {
                EbTrackid = 1128;  
            }
            else {
                EbTrackid = 1127;
            }
        }
        
        var self = this;
            let listItems='';
            if(page=="homepage"){
                listItems = data.map((item,index) =>
                <li key={item.id}>
                        <a href={item.targetUrl} onClick={ () => self.trackEvent('FeaturedInstitutes','Response',item.collegeName + "_"+ index)}>
                            <Lazyload offset={100} once>
                                <img src={(item.imageUrl) ? item.imageUrl : self.props.defaultImageUrl} />
                            </Lazyload>
                        </a>
                </li>
                );
            }

            
            let textLength = '70';
            if(page=="coursepage" || page == "institute" || page == "university"){
                if(section=="similar"){
                    let textLength = '60';
                }

                if(section=="alsoviewed" || section=="similar"){
                    listItems = data.map((item, index) =>
                    <li key={index}>
                        <div className="vwd-clg">
                            <Link className="link" to={removeDomainFromUrl(item.instituteUrl)} onClick={self.storeInstituteDataFromRecoPage.bind(self,item)}>
                                <Lazyload offset={100} once>
                                    <img src={(item.imageUrl) ? item.imageUrl : self.props.defaultImageUrl} alt={item.instituteName}/>
                                </Lazyload>
                            </Link>
                        </div>
                        <div className="vwd-clgInfo">
                            <p><Link className="link" to={removeDomainFromUrl(item.instituteUrl)} onClick={self.storeInstituteDataFromRecoPage.bind(self,item)}>{item.instituteName != null && item.instituteName.length>textLength?item.instituteName.substring(0,textLength)+'...': item.instituteName}</Link></p>
                            {((item.cityName!=null &&  item.cityName!='') && (item.establishYear!=null &&  item.establishYear!=''))?
                                <span>{item.cityName + " | Estd."+ item.establishYear}</span>:''
                            }
                            {((item.cityName!=null &&  item.cityName!='') && (item.establishYear==null ||  item.establishYear==''))?
                                <span>{item.cityName}</span>:''
                            }
                            {item.courseName!=null && item.courseName!=''?
                            <p><Link className="link" to={item.courseUrl} onClick={self.storeCourseDataFromRecoPage.bind(self,item)}>{item.courseName && item.courseName.length>textLength?item.courseName.substring(0,textLength)+'...': item.courseName}</Link></p>:''}

                            <DownloadEBrochure className={['rippleefect','dark'].join(' ')} buttonText="Request Brochure" listingId={(item.courseId!=0)?item.courseId:item.instituteId} listingName={item.instituteName} trackid={EbTrackid}  recoEbTrackid={recoEbTrackid} recoCMPTrackid={recoCMPTrackid} recoShrtTrackid={recoShrtTrackid}  isCallReco={isCallReco} clickHandler={self.trackEvent.bind(self,'DownloadBrochure','Response')} page={page}/>  
                            

                        </div>
                    </li>
                    );
                }
            }

            let classIdentifier = "";

            if(page=="coursepage" || page == "institute" || page == "university")
            {
                classIdentifier = "coursepage_sldrC sldrC";
            }
            else{
                classIdentifier = page+"_sldrC sldrC";
            }
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
Carousel.defaultProps = {
    defaultImageUrl : 'https://images.shiksha.ws/public/mobile5/images/recommender_dummy.png'
};
function mapDispatchToProps(dispatch){
  return bindActionCreators({ storeCourseDataForPreFilled ,storeInstituteDataForPreFilled}, dispatch); 
}
export default connect(null,mapDispatchToProps)(Carousel);

//export default Carousel;
