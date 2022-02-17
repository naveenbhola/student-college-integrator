import React from 'react';
import './../assets/L2Menu.css';
import './../../../common/assets/rippleEffect.css';
import {Link} from 'react-router-dom';
import Analytics from '../../../../modules/reusable/utils/AnalyticsTracking';
import {addingDomainToUrl} from './../../../../utils/commonHelper';
import {removeDomainFromUrlV2} from './../../../../utils/urlUtility';

class Menu extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {};
      this.stickyContainer = 0;
      this.footerHeight = 0;
   }

   componentDidMount(){
      if(typeof window != 'undefined' && document.getElementById('chpNavSticky')){
         this.stickyContainer = document.getElementById('chpNavSticky').offsetTop;
         this.footerHeight    = (document.getElementById('footer')) ? document.getElementById('footer').offsetTop : 0;
         window.addEventListener('scroll', this.handleScroll);
      }
   }

   componentWillUnmount(){
      if(typeof window != 'undefined'){
         window.removeEventListener('scroll', this.handleScroll);
      }
   }

   handleScroll = () => {
      let scrollPos = window.scrollY;
      if(scrollPos >= this.stickyContainer && scrollPos <= this.footerHeight && document.getElementById('footer')){
         document.getElementById('chpNavSticky').classList.add("sticky");
         document.getElementById('navContainer').classList.add("collapse");
      }else if(scrollPos >= this.stickyContainer && document.getElementById('page-footer')){
         document.getElementById('chpNavSticky').classList.add("sticky");
         document.getElementById('navContainer').classList.add("collapse");
         document.getElementById('expended').classList.remove("expended");
      }else{
         document.getElementById('chpNavSticky').classList.remove("sticky");
         document.getElementById('navContainer').classList.remove("collapse");
         if(document.getElementById('expended')){
            document.getElementById('expended').classList.add("expended");
         }
      }
   }

   createLink(){
      let listItem = new Array()
      let listMappings = {'homePage':'HomePage','popularColleges':'Popular Colleges','topRateCourses':'Top Courses','popularUGCourses':'UG Courses','popularPGCourses':'PG Courses','popularSpecialization':'Popular Specialization','topCollegesByLocation':'Top Ranked Colleges','topArticles':'Articles','anaWidget':'Q&A','popularExams':'Popular Exams','salary':'Salary'};

      let otherLinks = ['anaWidget','topArticles'];
      let linkTags   = (this.props.deviceType == 'desktop') ? ['popularColleges','topRateCourses','popularUGCourses','popularPGCourses','popularSpecialization','topCollegesByLocation'] : ['popularColleges','topRateCourses','popularUGCourses','popularPGCourses','popularSpecialization','topCollegesByLocation'];
      let ignoreList = ['homePage','popularSpecialization'];
      let bothDevice = ['topRateCourses','popularColleges','topCollegesByLocation'];

      if(typeof this.props.sectionData != 'undefined' && typeof this.props.sectionData.sectionOrder != 'undefined'){
            let popularCollegeUrl = (this.props.sectionData && typeof this.props.sectionData['popularColleges'] != 'undefined' && this.props.sectionData['popularColleges'] && typeof this.props.sectionData['popularColleges'].allTupleUrl) ? this.props.sectionData['popularColleges'].allTupleUrl : '';

            var sectionList = new Array();
            for(var i = 0; i < this.props.sectionData.sectionOrder.length; i++){
               if(ignoreList.indexOf(this.props.sectionData.sectionOrder[i]) == -1){
                  if(this.props.sectionData.sectionOrder[i] !='popularColleges' && this.props.sectionData[this.props.sectionData.sectionOrder[i]]['allTupleUrl'] != 'undefined' && this.props.sectionData[this.props.sectionData.sectionOrder[i]]['allTupleUrl'] != null && this.props.sectionData[this.props.sectionData.sectionOrder[i]]['allTupleUrl'].length>0){
                     sectionList.push(this.props.sectionData.sectionOrder[i]);
                  }else if(this.props.sectionData.sectionOrder[i] =='popularColleges' && popularCollegeUrl !=''){
                     sectionList.push(this.props.sectionData.sectionOrder[i]);
                  }
               }
            }

            for(var i in otherLinks){
               if(otherLinks[i] == 'anaWidget' && this.props.sectionData[otherLinks[i]] && typeof this.props.sectionData[otherLinks[i]]['allQuestionURL'] != 'undefined' && this.props.sectionData[otherLinks[i]]['allQuestionURL'] != null && this.props.sectionData[otherLinks[i]]['allQuestionURL'].length>0){
                  sectionList.push(otherLinks[i]);
               }else if(otherLinks[i] == 'topArticles' && this.props.sectionData[otherLinks[i]] && typeof this.props.sectionData[otherLinks[i]]['allArticlePageUrl'] != 'undefined' && this.props.sectionData[otherLinks[i]]['allArticlePageUrl'] != null && this.props.sectionData[otherLinks[i]]['allArticlePageUrl'].length>0){
                  sectionList.push(otherLinks[i]);
               }
            }

            let totalItem   = sectionList.length;
            let classAdded  = true;
            let liClass = 'active';
            for(var i=0;i<totalItem;i+=2){
               let item1 = '';
               let item2 = '';
               if(sectionList[i+1]){

                  let viewAllUrl1 = (typeof this.props.sectionData[sectionList[i]] != 'undefined' && this.props.sectionData[sectionList[i]] && this.props.sectionData[sectionList[i]]['allTupleUrl']) ? this.props.sectionData[sectionList[i]]['allTupleUrl'] : '';

                  viewAllUrl1 = (sectionList[i] == 'popularColleges' && popularCollegeUrl !='') ? popularCollegeUrl : viewAllUrl1;
                  viewAllUrl1 = (sectionList[i] == 'topArticles' && this.props.sectionData[sectionList[i]] && this.props.sectionData[sectionList[i]]['allArticlePageUrl']) ? this.props.sectionData[sectionList[i]]['allArticlePageUrl'] : viewAllUrl1;
                  viewAllUrl1 = (sectionList[i] == 'anaWidget' && this.props.sectionData[sectionList[i]] && this.props.sectionData[sectionList[i]]['allQuestionURL']) ? this.props.sectionData[sectionList[i]]['allQuestionURL'] : viewAllUrl1;

                  viewAllUrl1 = (this.props.deviceType != 'desktop' && sectionList[i] == 'topCollegesByLocation' && this.props.sectionData[sectionList[i]] && this.props.sectionData[sectionList[i]]['allTupleUrl']) ? removeDomainFromUrlV2(this.props.sectionData[sectionList[i]]['allTupleUrl']) : viewAllUrl1;

                  let viewAllUrl2 = (typeof this.props.sectionData[sectionList[i+1]] != 'undefined' && this.props.sectionData[sectionList[i+1]] && this.props.sectionData[sectionList[i+1]]['allTupleUrl']) ? this.props.sectionData[sectionList[i+1]]['allTupleUrl'] : '';
                  viewAllUrl2 = (sectionList[i+1] == 'popularColleges' && popularCollegeUrl !='') ? popularCollegeUrl : viewAllUrl2;
                  viewAllUrl2 = (sectionList[i+1] == 'topArticles' && this.props.sectionData[sectionList[i+1]] && this.props.sectionData[sectionList[i+1]]['allArticlePageUrl']) ? this.props.sectionData[sectionList[i+1]]['allArticlePageUrl'] : viewAllUrl2;
                  viewAllUrl2 = (sectionList[i+1] == 'anaWidget' && this.props.sectionData[sectionList[i+1]] && this.props.sectionData[sectionList[i+1]]['allQuestionURL']) ? this.props.sectionData[sectionList[i+1]]['allQuestionURL'] : viewAllUrl2;

                  viewAllUrl2 = (this.props.deviceType != 'desktop' && sectionList[i+1] == 'topCollegesByLocation' && this.props.sectionData[sectionList[i+1]] && this.props.sectionData[sectionList[i+1]]['allTupleUrl']) ? removeDomainFromUrlV2(this.props.sectionData[sectionList[i+1]]['allTupleUrl']) : viewAllUrl2;

                  if(linkTags.indexOf(sectionList[i]) != -1 && viewAllUrl1 != null && viewAllUrl1.length>0 && bothDevice.indexOf(sectionList[i]) != -1){
                     item1 = <Link onClick={this.trackEvent.bind(this,listMappings[sectionList[i]])} className={"sec-a"} to={removeDomainFromUrlV2(viewAllUrl1)} >{listMappings[sectionList[i]]}</Link>;
                     item1 = (this.props.isPdfCall && this.props.deviceType != 'desktop') ? <a href={addingDomainToUrl(viewAllUrl1, this.props.config.SHIKSHA_HOME)} onClick={this.trackEvent.bind(this,listMappings[sectionList[i]])} className={"sec-a"}>{listMappings[sectionList[i]]}</a> : item1;
                     classAdded = false;
                  }else if(linkTags.indexOf(sectionList[i]) != -1 && viewAllUrl1 != null && viewAllUrl1.length>0){
                    item1 = <Link onClick={this.trackEvent.bind(this,listMappings[sectionList[i]])} className={"sec-a"} to={viewAllUrl1} >{listMappings[sectionList[i]]}</Link>;
               	  item1 = (this.props.isPdfCall) ? <a href={addingDomainToUrl(viewAllUrl1, this.props.config.SHIKSHA_HOME)} onClick={this.trackEvent.bind(this,listMappings[sectionList[i]])} className={"sec-a"}>{listMappings[sectionList[i]]}</a> : item1;
               	     classAdded = false;
            	   }else if(viewAllUrl1 != null && viewAllUrl1.length>0){
            	     item1 = <a onClick={this.trackEvent.bind(this,listMappings[sectionList[i]])} className={"sec-a"} href={ (this.props.isPdfCall) ? addingDomainToUrl(viewAllUrl1, this.props.config.SHIKSHA_HOME) : viewAllUrl1}>{listMappings[sectionList[i]]}</a>;
            	     classAdded = false;
            	   }

            	   if(linkTags.indexOf(sectionList[i+1]) != -1 && viewAllUrl2 != null && viewAllUrl2.length>0 && bothDevice.indexOf(sectionList[i+1]) != -1){
            	     item2 = <Link onClick={this.trackEvent.bind(this,listMappings[sectionList[i+1]])} className={"sec-a"} to={removeDomainFromUrlV2(viewAllUrl2)} >{listMappings[sectionList[i+1]]}</Link>;
            	     item2 = (this.props.isPdfCall && this.props.deviceType != 'desktop') ? <a href={addingDomainToUrl(viewAllUrl2, this.props.config.SHIKSHA_HOME)} onClick={this.trackEvent.bind(this,listMappings[sectionList[i+1]])} className={"sec-a"}>{listMappings[sectionList[i+1]]}</a> : item2;
            	     classAdded = false;
            	   }else if(linkTags.indexOf(sectionList[i+1]) != -1 && viewAllUrl2 != null && viewAllUrl2.length>0){
            	     item2 = <Link onClick={this.trackEvent.bind(this,listMappings[sectionList[i+1]])} className={"sec-a"} to={viewAllUrl2} >{listMappings[sectionList[i+1]]}</Link>;
            	     item2 = (this.props.isPdfCall) ? <a href={addingDomainToUrl(viewAllUrl2, this.props.config.SHIKSHA_HOME)} onClick={this.trackEvent.bind(this,listMappings[sectionList[i+1]])} className={"sec-a"}>{listMappings[sectionList[i+1]]}</a> : item2;
            	     classAdded = false;
            	   }else if(viewAllUrl2 !=null && viewAllUrl2.length>0){
            	     item2 = <a onClick={this.trackEvent.bind(this,listMappings[sectionList[i+1]])} className={"sec-a"} href={ (this.props.isPdfCall) ? addingDomainToUrl(viewAllUrl2, this.props.config.SHIKSHA_HOME) : viewAllUrl2}>{listMappings[sectionList[i+1]]}</a>;
            	     classAdded = false;
            	   }

                  if(item1 !='' || item2 !=''){
            	     listItem.push(<li key={i} className={liClass}>{item1}{item2}</li>);
            	     liClass = '';
            	   }

            }else{
               	  let viewAllUrl1 = (typeof this.props.sectionData[sectionList[i]] != 'undefined' && this.props.sectionData[sectionList[i]] && this.props.sectionData[sectionList[i]]['allTupleUrl']) ? this.props.sectionData[sectionList[i]]['allTupleUrl'] : '';
               	  viewAllUrl1 = (sectionList[i] == 'popularColleges' && popularCollegeUrl !='') ? popularCollegeUrl : viewAllUrl1;
               	  viewAllUrl1 = (sectionList[i] == 'topArticles' && this.props.sectionData[sectionList[i]] && this.props.sectionData[sectionList[i]] && this.props.sectionData[sectionList[i]]['allArticlePageUrl']) ? this.props.sectionData[sectionList[i]]['allArticlePageUrl'] : viewAllUrl1;
               	  viewAllUrl1 = (sectionList[i] == 'anaWidget' && this.props.sectionData[sectionList[i]] && this.props.sectionData[sectionList[i]]['allQuestionURL']) ? this.props.sectionData[sectionList[i]]['allQuestionURL'] : viewAllUrl1;

                     viewAllUrl1 = (this.props.deviceType != 'desktop' && sectionList[i] == 'topCollegesByLocation' && this.props.sectionData[sectionList[i]] && this.props.sectionData[sectionList[i]]['allTupleUrl']) ? removeDomainFromUrlV2(this.props.sectionData[sectionList[i]]['allTupleUrl']) : viewAllUrl1;

                     if(linkTags.indexOf(sectionList[i]) != -1 && viewAllUrl1 != null && viewAllUrl1.length>0 && bothDevice.indexOf(sectionList[i]) != -1){
                        item1 = (this.props.isPdfCall && this.props.deviceType != 'desktop') ? <a href={addingDomainToUrl(viewAllUrl1, this.props.config.SHIKSHA_HOME)} onClick={this.trackEvent.bind(this,listMappings[sectionList[i]])} className={"sec-a"}>{listMappings[sectionList[i]]}</a> : <Link onClick={this.trackEvent.bind(this,listMappings[sectionList[i]])} className={"sec-a"} to={removeDomainFromUrlV2(viewAllUrl1)} >{listMappings[sectionList[i]]}</Link>;
                        classAdded = false;
                     }else if(linkTags.indexOf(sectionList[i]) != -1 && viewAllUrl1 != null && viewAllUrl1.length>0){
                        item1 = (this.props.isPdfCall) ? <a href={addingDomainToUrl(viewAllUrl1, this.props.config.SHIKSHA_HOME)} onClick={this.trackEvent.bind(this,listMappings[sectionList[i]])} className={"sec-a"}>{listMappings[sectionList[i]]}</a> : <Link onClick={this.trackEvent.bind(this,listMappings[sectionList[i]])} className={"sec-a"} to={viewAllUrl1} >{listMappings[sectionList[i]]}</Link>;
                        classAdded = false;
                     }else if(viewAllUrl1 != null && viewAllUrl1.length>0){
                        item1 = <a onClick={this.trackEvent.bind(this, listMappings[sectionList[i]])} className={"sec-a"} href={(this.props.isPdfCall) ? addingDomainToUrl(viewAllUrl1, this.props.config.SHIKSHA_HOME) : viewAllUrl1}>{listMappings[sectionList[i]]}</a>;
                        classAdded = false;
                     }

                     if(item1 !=''){
                        listItem.push(<li key={i} className={liClass}>{item1}</li>);
                     }
               }
           }
      }
      return listItem;
   }

   trackEvent(linkText, ele)
   {
      let actionlabel = (this.props.deviceType == 'desktop') ? 'CHP_Desktop_Quicklinks' : 'CHP_L2_Quicklinks';
      Analytics.event({category : 'CHP', action : actionlabel, label : 'CHP_'+linkText.replace(' ','_')});
   }

   manageNav(e){
      var ele = e.currentTarget;
      if(document.getElementById('navContainer').classList.contains("collapse")){
         ele.classList.add('expended');
         document.getElementById('navContainer').classList.remove("collapse");
         this.trackEvent('Expended');
      }else{
         document.getElementById('navContainer').classList.add("collapse");
         ele.classList.remove('expended');
         this.trackEvent('Collapse');
      }
   }

   render()
   {
      let itmeLen = this.createLink();
      if(itmeLen.length <= 0){
         return null;
      }
      return (
            <React.Fragment>
                  <div id="tab-section" className="nav-tabs">
                     <div className="chp-nav" id="chpNavSticky">
                        <div className="chp-navList">
                           { itmeLen.length>1 && <span onClick={this.manageNav.bind(this)} className="expnd-circle expended" id="expended"><span className="rippleefect ib-circle"><i  className="expnd-switch" ></i></span></span>}
                           <ul className="l2Menu-list" id="navContainer">
                             {this.createLink()}
                           </ul>
                        </div>
                     </div>
                  </div>
            </React.Fragment>
         )
   }
}
export default Menu;
