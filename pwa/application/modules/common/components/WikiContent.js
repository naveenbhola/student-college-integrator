import React from 'react';
import PropTypes from 'prop-types';
import  './../assets/Wikki.css';
import {event} from '../../../modules/reusable/utils/AnalyticsTracking';
import {Ucfirst} from './../../../utils/commonHelper';
import {getTextFromHtml} from "../../../utils/stringUtility";

class WikiContent extends React.Component
{
   constructor(props)
   {
      super(props);
      this.wikkiContentId = "wikkiContents_"+this.props.sectionname+"_"+this.props.order;
      this.viewMoreLink = "viewMoreLink_"+this.props.sectionname+"_"+this.props.order;
   }

   setReferrer(){
      window.referrer = window.location.href;
   }

   gaTrackEvent(labelName)
   {
      let gaLabelName = 'OtherLabels';
      if(this.props.labelList.indexOf(labelName) !== -1){
         gaLabelName = Ucfirst(labelName);
      }
      let actionLabel = (this.props.deviceType === 'desktop') ? 'CHP_Desktop_'+gaLabelName : 'CHP_'+gaLabelName;
      event({category : 'CHP', action : actionLabel, label : 'CHP_'+gaLabelName+'_ReadMore'});
   }

   showHiddenText()
   {
      if(typeof document!='undefined'){
         document.getElementById(this.viewMoreLink).style.display="none";
         document.getElementById(this.wikkiContentId).className += " showContent";
      }
      this.gaTrackEvent(this.props.sectiondata.labelName);
   }

   render()
   {
      let classVal = 'wikkiContents';
      if(this.props.cutWikiContent){
         classVal = 'wikkiContents shortView';
      }
      return (
             <div id={this.wikkiContentId} className={classVal}>
                <div dangerouslySetInnerHTML={this.getWikiHtml()} />
                {this.props.sectionname!='homePage'? <div className="gradient-col" style={{display:'none'}} id={this.viewMoreLink} ><a className="gradVw-mr trnBtn more arrow" onClick={this.showHiddenText.bind(this, this.props.sectionname+'_'+this.props.order)}>View more</a></div>:''}
             </div>
      );
   }

   getWikiHtml(){
      let html = this.props.sectiondata[this.props.labelValue];
      if(this.props.cutWikiContent && this.props.sectiondata[this.props.labelValue] !== '' && this.props.sectiondata[this.props.labelValue] != null){
          html = getTextFromHtml(this.props.sectiondata[this.props.labelValue], 500, ['table', 'script']);
      }
      return { __html : html};
   }
}

WikiContent.defaultProps = {
   labelList : ['Overview','Eligibility','popularColleges','topRateCourses','popularUGCourses','popularPGCourses','popularSpecialization','topCollegesByLocation','topArticles','anaWidget','popularExams','salary'],
   labelValue: 'labelValue',
   cutWikiContent : false,
   cutLength : 500
};
WikiContent.propTypes = {
   sectionname : PropTypes.string,
   order : PropTypes.number,
   labelList : PropTypes.array,
   deviceType : PropTypes.string,
   sectiondata : PropTypes.object,
   cutWikiContent : PropTypes.bool,
   labelValue : PropTypes.string,
   labelName : PropTypes.string
};

export default WikiContent;