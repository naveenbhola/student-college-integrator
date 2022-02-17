import React from 'react';
import  './../assets/Wikki.css';
import {Link} from 'react-router-dom';
import Analytics from '../../../../modules/reusable/utils/AnalyticsTracking';
import {Ucfirst} from './../../../../utils/commonHelper';

class WikiContent extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {}
      this.maxOverviewSectionHeight = '500';
      this.maxOtherSectionHeight = '240';
      this.wikkiContentId = "wikkiContents_"+this.props.sectionname+"_"+this.props.order;
      this.viewMoreLink = "viewMoreLink_"+this.props.sectionname+"_"+this.props.order;
   }

   componentDidMount(){
      let sectionHeight = this.maxOverviewSectionHeight;         
      if(this.props.sectiondata.labelName!='Overview'){
         sectionHeight = this.maxOtherSectionHeight;
      }

      if(document.getElementById(this.wikkiContentId).childNodes[0].offsetHeight<sectionHeight){
         document.getElementById(this.viewMoreLink).style.display="none";
      }else{
         document.getElementById(this.viewMoreLink).style.display="block";
      }
   }

   setReferrer(){
     window.referrer = window.location.href;
   }

   gaTrackEvent(labelName)
   {
      var gaLabelName = 'OtherLabels';
      if(this.props.labelList.indexOf(labelName) != '-1'){
         gaLabelName = Ucfirst(labelName);
      }
      let actionLabel = (this.props.deviceType == 'desktop') ? 'CHP_Desktop_'+gaLabelName : 'CHP_'+gaLabelName;
      Analytics.event({category : 'CHP', action : actionLabel, label : 'CHP_'+gaLabelName+'_ReadMore'});
   }

   showHiddenText(id)
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
      if(this.props.sectiondata.labelName!='Overview'){
         classVal = 'wikkiContents miniView';
      }
      
      return (
         <React.Fragment>
            <div id={this.wikkiContentId} className={classVal}>
                     <div dangerouslySetInnerHTML={{ __html : this.props.sectiondata.labelValue}} />
                     {this.props.sectionname!='homePage'? <div className="gradient-col" style={{display:'none'}} id={this.viewMoreLink} ><a className="gradVw-mr trnBtn more arrow" onClick={this.showHiddenText.bind(this, this.props.sectionname+'_'+this.props.order)}>View more</a></div>:''}
                 </div>
                     {this.props.sectionname=='homePage'?
               <div className="gradient-col" style={{display:'none'}} id={this.viewMoreLink} ><a className="gradVw-mr trnBtn more arrow" onClick={this.showHiddenText.bind(this, this.props.sectionname+'_'+this.props.order)}>View more</a></div>:''}
               </React.Fragment>
         )
   }
}

WikiContent.defaultProps = {
   labelList : ['Overview','Eligibility','popularColleges','topRateCourses','popularUGCourses','popularPGCourses','popularSpecialization','topCollegesByLocation','topArticles','anaWidget','popularExams','salary']
};

export default WikiContent;
