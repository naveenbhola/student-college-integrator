/** 
  Desc    - PWA Mobile Hamburger
  Version - Hamburger-V2-Light Hamburger
  added by- akhter 
**/
import React, { Component } from 'react';
import HamburgerUser from './HamburgerUser';
import PopupLayer from './popupLayer';
import hmConfig from '../config/hamburgerConfig';
import {seo_url, getSeoUrl} from '../../../utils/urlUtility';
import {setCookie, getCookie} from '../../../utils/commonHelper';
import { connect } from 'react-redux';
import PreventScrolling from './../../reusable/utils/PreventScrolling';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import NonZeroLocation from './../../common/components/NonZeroLocationLayer';

class Hamburger extends Component {

  constructor(props){
    super(props);
    this.state = {
      locationLayerData : {},
      nonZeroLayer : false
    }
    this.streamWiseMenuIds   = hmConfig['streamWiseMenuIds'];
    this.menuLayer1Subtitles = hmConfig['menuLayer1Subtitles'];
    this.menuLayer1Groups    = hmConfig['menuLayer1Groups'];
    this.streamToTagsMapping = hmConfig['streamToTagsMapping']
    this.layer1Class = 'cat-head';
    this.layer2Class = 'cat-head child_link';
    this.defaultHtmlFor = 'level2-';
    this.THRESHOLD_LIMIT= 2;
    this.cookieStreamId = 'userStreamId';
    this.cookieStreamName = 'userStreamName';
  }

  isSessionStorageSupported() {
      var testKey = 'test', storage = window.sessionStorage;
      try 
      {
          storage.setItem(testKey, '1');
          storage.removeItem(testKey);
          return true;
      }catch (error) 
      {
          return false;
      }
  }

  componentDidUpdate(){
    this.bindClickHamburgerChild();
  }

  bindClickHamburgerChild(){
    if(typeof(document.querySelector('input[id^="level"]')) != 'undefined' && document.querySelector('input[id^="level"]')){
        document.querySelectorAll('input.toggle-child').forEach(function(data) {
          var currentInput = data;  
          currentInput.addEventListener('click' , function(){
              var x = this;
              var childBox = document.querySelector('#'+x.getAttribute('id')+'+ .subcat-layer');
              if(x.checked){
               childBox.classList.add("show");
               setTimeout(function(){childBox.classList.add("zero-marginleft")},0);
              }else{
               childBox.classList.remove("zero-marginleft");
               setTimeout(function(){childBox.classList.remove("show")},300);
              }
          });
        });
    }
  }

  prepareSelectStreamLayer(){
    var streamData = (this.props.tabsContentByCategory['hierarchiesHavingNonZeroListings']) ? this.props.tabsContentByCategory['hierarchiesHavingNonZeroListings'] : '' ;
    if(streamData){
        var layerData = new Array();
        for(var streamId in streamData){
            var s = streamData[streamId];
            if(s !== typeof undefined ){
              if(s.id == 21)
              {
                  layerData.push(<li key={ s.id } streamid={s.id}><a href={this.props.config.SHIKSHA_HOME+"/sarkari-exams/exams-st-21"}><span>{s.name}</span></a></li>)
              }
              else
              {
                layerData.push(<li key={ s.id } streamid={s.id} onClick={this.userSelectedStream.bind(this, {'id':s.id,'name':s.name})}><span>{s.name}</span></li>)
              }
            }
        }
        return (<ul className="ul-list" key="ullist"><li ref={(ele) => this.ele = ele} onClick={this.userSelectedStream.bind(this, {'id':0,'name':'Select Stream'})} key="0" streamid="0"><span>Select</span></li>{ layerData }</ul>);
    }
  }

  userSelectedStream(s){
    this.setCookieName(s.id, s.name);
    this.props.updateHamburgerByUserStream(s);
    this.gaTrackEvent('HburgStream','Click0');
  }

  /*this is use to personalize the hamburger data based on user selected stream*/
  retainUserState(apiResponse){
    var streamid = (getCookie(this.cookieStreamId)) ? getCookie(this.cookieStreamId) : '';
    var streamName = (getCookie(this.cookieStreamName)) ? getCookie(this.cookieStreamName) : '';
    if(parseInt(streamid) && streamid !='' && streamName !=''){
      this.setState({'selectedStreamId':getCookie(this.cookieStreamId),'selectedStreamName' : streamName, tabsContentByCategory:apiResponse, nonZeroData:apiResponse['hierarchiesHavingNonZeroListings'], rendered : true});
    }else{
      this.setState({tabsContentByCategory:apiResponse, nonZeroData:apiResponse['hierarchiesHavingNonZeroListings'], rendered : true});
    }
  }

  setCookieName(streamId, streamName){
    setCookie(this.cookieStreamId, streamId, 30);
    setCookie(this.cookieStreamName, streamName, 30);
  }

  changeHeading(){
    return (
      <p className="search-h1">{ this.props.selectedStreamId ?  'Change education stream to personalize menu' : 'Select education stream to personalize menu' }</p>
    );
  }

  selectStreamOption(){
    return this.props.selectedStreamName ?  this.props.selectedStreamName : 'Select Stream';
  }

  openPopUp(){
     this.PopupLayer.open();
  }

  render() {

    if(!this.props.rendered){
        return (
              <div>
                <div className="layer_nav" id="hbug"> 
                  <aside className="menu-container" id="menu-container">
                    <nav className="slide-menu">
                     <div className="inner-menu">
                       <HamburgerUser />
                       <div id="hmldr" className="hmldr">Loading... </div>
                     </div>
                    </nav>      
                  </aside>
                    <div className="layer_l" id="hmClose" onClick={this.hamburgerClose.bind()}>
                          <span htmlFor="offcanvas-menu" className="full-screen-close"></span>
                    </div>
                  </div>
              </div>
      );
    }
        
    return (
        <div>
           <div className="layer_nav" id="hbug"> 
              <aside className="menu-container" id="menu-container">
                 <nav className="slide-menu">
                   <div className="inner-menu">
                     <HamburgerUser />
                     <div id="hamburgerStreamSection">
                          <div className="search-nav">

                            {this.changeHeading()}
                            
                            <PopupLayer onRef={ref => (this.PopupLayer = ref)} data={this.prepareSelectStreamLayer()}/>
                            <div className="main-slct" onClick={this.openPopUp.bind(this)}>{this.selectStreamOption()}</div>
                            <div className="select-Class">
                                <select name="hamburgerStreamsSelect">
                                   <option value="0">Select</option>
                                 </select>
                            </div>
                          </div>

                          { this.prepareLayer() }

                     </div>
                   </div>
                </nav>
                
                { this.childLayers() }                              

              </aside>
                    <div className="layer_l" id="hmClose" onClick={this.hamburgerClose.bind()}>
                          <span htmlFor="offcanvas-menu" className="full-screen-close"></span></div>
                    </div>
                    {this.state.nonZeroLayer && <NonZeroLocation data={this.state.locationLayerData} hamburgerClose={this.closeHumberger.bind(this)} onClose={this.modalClose.bind(this)}/>}
              </div>
                 
      )
  }

  hamburgerClose(){
    if(typeof(document.getElementById('hbug')) != 'undefined' && document.getElementById('hbug') != null){
      var menuBox = document.querySelector('#menu-container');
      menuBox.classList.remove("transZero");
      document.getElementById('hbug').classList.remove("bcglayer");
      setTimeout(function(){menuBox.classList.remove("show");},300);

      (document.getElementById('hbug').classList.contains('bcglayer')) ? document.getElementById('hbug').classList.remove('bcglayer') : '';
      PreventScrolling.enableScrolling(); 
    }
  }

  closeHumberger()
  {
    if(document.getElementById('menu-container').classList.contains('show'))
    {
        document.getElementById('hmClose').click();
    }
  }

  _isGeneric() {
        return (this.props.selectedStreamId == 0) ? true : false;
  }

  gaTrackEvent(actionLabel,labelType)
  {
      Analytics.event({category : 'Head', action : actionLabel, label : labelType});
  }

  getHamburgerMenu(){

          var streamid   = this.props.selectedStreamId;
          var streamName = this.props.selectedStreamName;
          var self = this;
          var menuData = {}, data = {};
          
          if(streamid > 0){
            this.defaultHtmlFor = 'level1-';
          }else{
            this.defaultHtmlFor = 'level2-';
          }

          var allowedMenu = self.streamWiseMenuIds[streamid];
          
          data['layer1'] = {};
          data['layer2'] = {};
          data['layer3'] = {};
          data['layer4'] = {};
          
          if(typeof allowedMenu == 'undefined'){
            return data;
          }

          allowedMenu.forEach(function(menuId) {

              var groupId   = self.menuLayer1Subtitles[menuId]['group'];
              var groupName = self.menuLayer1Groups[groupId];

              if(Object.keys(data['layer1']).indexOf(groupName) == -1)
              {
                data['layer1'][groupName] = {};
                data['layer1'][groupName][menuId] = {};
              }

              if(Object.keys(data['layer2']).indexOf(menuId) == -1)
              {
                data['layer2'][menuId] = {};
              }

              if(Object.keys(data['layer3']).indexOf(menuId) == -1)
              {
                data['layer3'][menuId] = {};
              }

              if(Object.keys(data['layer4']).indexOf(menuId) == -1)
              {
                data['layer4'][menuId] = {};
              }
              

              if(self.menuLayer1Subtitles[menuId]['apiCall']) {
                
                  var funcName = self.menuLayer1Subtitles[menuId]['apiCall'];
                
                  if(typeof self[funcName] === "function"){

                      var menuData = {};
                      menuData = self[funcName](menuId);
                      
                      if(menuData['layer1'] !=''){
                        data['layer1'][groupName][menuId] = menuData['layer1'];
                      }
                      if(menuData['layer2'] !=''){
                        data['layer2'][menuId] = menuData['layer2'];
                      }

                      if(menuData['layer3'] !=''){
                        data['layer3'][menuId] = menuData['layer3'];
                      }

                      if(menuData['layer4'] !=''){
                        data['layer4'][menuId] = menuData['layer4'];
                      }
                      
                  }
              }
    
          });
          return data;
  }

  getFindCollegesHtml(menuId){
    var html = [],data = [];
    var self = this;
    var childLabel1 = [],childLabel2 = [],childLabel3 = [];
    var tmpData = {};

    data['id'] = menuId;
    data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
    html['layer1']    = this.generateParentLabel(data);
    if(this._isGeneric()){
        html['layer2']    = this.prepareStreamLayer(data);
        tmpData = this.props.nonZeroData;
    }else{
        tmpData[this.props.selectedStreamId] = (this.props.nonZeroData && this.props.nonZeroData[this.props.selectedStreamId]) ? this.props.nonZeroData[this.props.selectedStreamId] : '';
    }   

    for(var streamId in tmpData){
        let element =  <div key={"l2-"+menuId+streamId}>
                          <input type="checkbox" id={this.defaultHtmlFor+menuId+'-'+streamId} className="toggle-child" />
                          <div className="subcat-layer">
                              <div className="subcat-heading">
                                <label className="hamburger-back" htmlFor={this.defaultHtmlFor+menuId+'-'+streamId}></label>
                                <div className="r-table">
                                  <div className="subcat-p">{data['labelName']}</div>
                                  <div className="subcat-dtls">
                                    <span className="clgs-stream">{'In '+tmpData[streamId].name}</span>
                                  </div>
                                </div>
                              </div>
                              <div className="subcat-nav">
                                  <div className="subcat-inner" onClick={this.gaTrackEvent.bind(this,'HburgClick','Click2')}>
                                        {self.prepareSubStreamSpec(menuId, streamId)}
                                        {self.prepareStreamSpecialization(menuId, streamId)}                          
                                  </div>
                              </div>
                          </div>
                        </div>
        childLabel2.push(element);

        let subSpecData = (this.props.nonZeroData && this.props.nonZeroData[streamId]['substreams']) ? this.props.nonZeroData[streamId]['substreams'] : '';
        if(subSpecData){
          for(var substreamId in subSpecData){
           let element = <div key={"l3-"+menuId+substreamId+streamId}>
                          <input type="checkbox" id={"level3-"+menuId+"-"+substreamId} className="toggle-child" />
                          <div className="subcat-layer">
                              <div className="subcat-heading">
                                <label className="hamburger-back" htmlFor={"level3-"+menuId+"-"+substreamId}></label>
                                <div className="r-table">
                                  <div className="subcat-p">{data['labelName']}</div>
                                  <div className="subcat-dtls">
                                    <span className="clgs-stream">{'In '+subSpecData[substreamId].name}</span>
                                  </div>
                                </div>
                              </div>
                              <div className="subcat-nav">
                                  <div className="subcat-inner">
                                        {self.parepareSubSpecialization(menuId, streamId, substreamId)}
                                  </div>
                              </div>
                          </div>
                        </div>
            childLabel3.push(element);
          }
        }
    }
    html['layer3'] = childLabel2.length ? childLabel2 : null;
    html['layer4'] = childLabel3.length ? childLabel3 : null;
    return html;
  }

  prepareStreamLayer(data, otherParam){
    var shtml = [];
    let element =  <div key={"l1-"+data['id']}>
                      <input type="checkbox" id={"level1-"+ data['id'] +'-'+this.props.selectedStreamId } className="toggle-child" />
                      <div className="subcat-layer">
                          <div className="subcat-heading">

                          <label className="hamburger-back" htmlFor={"level1-"+ data['id']+'-'+this.props.selectedStreamId }></label>
                            <div className="r-table">
                              <div className="subcat-p">{data['labelName']}</div>
                              <div className="subcat-dtls">
                                <span className="clgs-stream initial_hide"></span>
                              </div>
                            </div>
                          </div>
                          <div className="subcat-nav">
                              <div className="subcat-inner" onClick={this.gaTrackEvent.bind(this,'HburgClick','Click2')}>
                                {this.prepareStreamData(data['id'], otherParam)}                                      
                              </div>
                          </div>
                      </div>
                  </div>
      shtml.push(element);
      return shtml;
  }

  //stream
  prepareStreamData(menuId, otherParam){
    const data = this.props.nonZeroData;
    var strLab = new Array();
      if(typeof(otherParam) != 'undefined' && otherParam == 'allExamPage'){
        for(var s in data){
          if(typeof(this.props.tabsContentByCategory['allExamsPageData']) !='undefined' && this.props.tabsContentByCategory['allExamsPageData'][data[s]['id']]){
            if(data[s]['id'] == 21)
            {
               var ele = <label data-st={data[s]['id']} key={data[s]['id']} className="subcat-a no-child"><a href={this.props.config.SHIKSHA_HOME+"/sarkari-exams/exams-st-21"}>{data[s]['name']}</a></label>
            }
            else
            {
                var ele = <label data-st={data[s]['id']} key={data[s]['id']} htmlFor={"level2-"+menuId+'-'+data[s]['id']} className="subcat-a"><a href={this.props.tabsContentByCategory['allExamsPageData'][data[s]['id']]}>{data[s]['name']}</a></label>
            }
            strLab.push(ele);  
          }
        }
      }else{
        for(var s in data){
          if(data[s]['id'] == 21)
          {
            var ele = <label data-st={data[s]['id']} key={data[s]['id']} className="subcat-a child_link no-child"><a href={this.props.config.SHIKSHA_HOME+"/sarkari-exams/exams-st-21"}>{data[s]['name']}</a></label>
          }
          else
          {
            var ele = <label data-st={data[s]['id']} key={data[s]['id']} htmlFor={"level2-"+menuId+'-'+data[s]['id']} className="subcat-a child_link">{data[s]['name']}</label>
          }
          strLab.push(ele);
        }
      }
    return strLab;
  }

  //substream=>specialization
  prepareSubStreamSpec(menuId, streamId){
    if(!streamId){
      return null;
    }
    let labelHtml = [];
    
    let tmpData = (this.props.nonZeroData && this.props.nonZeroData[streamId]) ? this.props.nonZeroData[streamId] : '';
    let defaultLbl = <label onClick={this.getNonZeroLocation.bind(this,{'streamId':streamId})} data-str={tmpData.id} key={tmpData.id+'-'+menuId} className="subcat-a">{'All '+tmpData.name+' Colleges'}</label>
    
    labelHtml.push(defaultLbl);

    let data = tmpData['substreams'];
    if(data){
      for(var substreamId in data){
              var lbl = '';

              var hasSpec = false;
              for(var specId in data[substreamId]['specializations']){
                 if(typeof(specId) != 'undefined' && specId){
                   hasSpec = true;
                   break;
                 }
              }

              if(hasSpec){
                lbl = <label data-sub={data[substreamId].id} key={data[substreamId].id+'-'+menuId} htmlFor={"level3-"+menuId+"-"+data[substreamId].id} className="subcat-a child_link">{data[substreamId].name}</label>
              }else{
                // add click Event
                lbl = <label onClick={this.getNonZeroLocation.bind(this,{'streamId':streamId,'substreamId':data[substreamId].id})} data-sub={data[substreamId].id} key={data[substreamId].id+'-'+menuId} className="subcat-a">{data[substreamId].name}</label>
              }
              labelHtml.push(lbl);
      }  
    }
    return labelHtml;
  }

  //stream=>substream=>specialization
  parepareSubSpecialization(menuId, streamId, subStreamId){
    let labelHtml = [];
    if(Object.keys(this.props.nonZeroData[streamId]['substreams'][subStreamId]).indexOf('specializations')>0){

      let tmpData = this.props.nonZeroData[streamId]['substreams'][subStreamId];
      let defaultLbl = <label onClick={this.getNonZeroLocation.bind(this,{'streamId':streamId,'substreamId':subStreamId})} data-str={tmpData.id} key={tmpData.id+'-'+menuId} className="subcat-a">{'All '+tmpData.name+' Colleges'}</label>
    
      labelHtml.push(defaultLbl);

      let data = this.props.nonZeroData[streamId]['substreams'][subStreamId]['specializations'];
      if(data){
        for(var specId in data){  
          let lbl = <label onClick={this.getNonZeroLocation.bind(this,{'streamId':streamId,'substreamId':subStreamId,'specializationId':data[specId].id})} key={'spec_'+specId} data-spec={data[specId].id} className="subcat-a">{data[specId].name}</label>
          labelHtml.push(lbl);
        }  
      }
    }
    return labelHtml;
  }

  //stream=>specialization
  prepareStreamSpecialization(menuId, streamId){
    if(!streamId){
      return null;
    }
    let labelHtml = [];
    const data = (this.props.nonZeroData && this.props.nonZeroData[streamId]['specializations']) ? this.props.nonZeroData[streamId]['specializations'] : '';
    if(data){
      for(var specId in data){   
        // add click Event
        let lbl = <label onClick={this.getNonZeroLocation.bind(this,{'streamId':streamId,'specializationId':data[specId].id})} data-spec={data[specId].id} key={data[specId].id+'-'+menuId} className="subcat-a">{data[specId].name}</label>
        labelHtml.push(lbl);
      }  
    }
    return labelHtml;
  }

  getFindCollegeByCourseHtml(menuId){
    var html = [], data = [];
    var self = this;
    var childLabel = [];
    var tmpData = {};

    data['id'] = menuId;
    data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
    html['layer1']    = this.generateParentLabel(data);
      
    if(this._isGeneric()){
        html['layer2']    = this.prepareStreamLayer(data);
        tmpData = this.props.nonZeroData;
        for(var streamId in tmpData){
            let element =  <div key={"l2-"+menuId+streamId}>
                              <input type="checkbox" id={this.defaultHtmlFor+menuId+"-"+streamId} className="toggle-child" />
                              <div className="subcat-layer">
                                  <div className="subcat-heading">
                                    <label className="hamburger-back" htmlFor={this.defaultHtmlFor+menuId+"-"+streamId}></label>
                                    <div className="r-table">
                                      <div className="subcat-p">{data['labelName']}</div>
                                      <div className="subcat-dtls">
                                        <span className="clgs-stream">{'In '+tmpData[streamId].name}</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div className="subcat-nav">
                                      <div className="subcat-inner" onClick={this.gaTrackEvent.bind(this,'HburgClick','Click2')}>
                                        {this.getBaseCourseByStream(menuId, streamId, tmpData[streamId].name)}
                                      </div>
                                  </div>
                              </div>
                            </div>
            childLabel.push(element);
        }
        html['layer3'] = childLabel.length ? childLabel : null;
    }else{
        tmpData[this.props.selectedStreamId] = (this.props.nonZeroData && this.props.nonZeroData[this.props.selectedStreamId]) ? this.props.nonZeroData[this.props.selectedStreamId] : '';
        for(var streamId in tmpData){
            let element =  <div key={"l2-"+menuId+streamId}>
                              <input type="checkbox" id={this.defaultHtmlFor+menuId+"-"+streamId} className="toggle-child" />
                              <div className="subcat-layer">
                                  <div className="subcat-heading">
                                    <label className="hamburger-back" htmlFor={this.defaultHtmlFor+menuId+"-"+streamId}></label>
                                    <div className="r-table">
                                      <div className="subcat-p">{data['labelName']}</div>
                                      <div className="subcat-dtls">
                                        <span className="clgs-stream">{'In '+tmpData[streamId].name}</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div className="subcat-nav">
                                      <div className="subcat-inner" onClick={this.gaTrackEvent.bind(this,'HburgClick','Click2')}>
                                        {this.getBaseCourseByStream(menuId, streamId, tmpData[streamId].name)}
                                      </div>
                                  </div>
                              </div>
                            </div>
            childLabel.push(element);
        }
        html['layer2'] = childLabel.length ? childLabel : null;
    } 
    return html;
  }

  getBaseCourseByStream(menuId, streamId, streamName){
    var html = [];
    var baseCourse = (this.props.tabsContentByCategory['baseCoursesHavingNonZeroListings']) ? this.props.tabsContentByCategory['baseCoursesHavingNonZeroListings'][streamId] : '';
    var defaultLabel = <label onClick={this.getNonZeroLocation.bind(this,{'streamId':streamId})} data-str={streamId} key={streamId+'-'+menuId} className="subcat-a">{'All '+streamName+' Colleges'}</label>
        html.push(defaultLabel);
    for(var i in baseCourse){
        html.push(<label onClick={this.getNonZeroLocation.bind(this,{'streamId':streamId,'baseCourseId':i})}  key={ menuId+streamId+i } className="subcat-a">{baseCourse[i]}</label>);
    }
    return html;
  }

  getRankingMenuHtml(menuId){
    var thresoldExceed = false;
    var html = [],data = [], childLabel = [], childLabel2 = [], self = this;
    var tmpData = {};
    data['id'] = menuId;
    data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
    if(this._isGeneric()){
      html['layer1']    = this.generateParentLabel(data);
      tmpData = (this.props.tabsContentByCategory['rankingStreamWiseData']) ? this.props.tabsContentByCategory['rankingStreamWiseData'] : '';  
      let element =  <div key={"l2-"+menuId+this.props.selectedStreamId}>
                          <input type="checkbox" id={"level1-"+ menuId +'-'+this.props.selectedStreamId } className="toggle-child" />
                          <div className="subcat-layer">
                              <div className="subcat-heading">
                                <label className="hamburger-back" htmlFor={"level1-"+ menuId +'-'+this.props.selectedStreamId }></label>
                                <div className="r-table">
                                  <div className="subcat-p">{data['labelName']}</div>
                                  <div className="subcat-dtls">
                                    <span className="clgs-stream initial_hide"></span>
                                  </div>
                                </div>
                              </div>
                              <div className="subcat-nav">
                                  <div className="subcat-inner" onClick={this.gaTrackEvent.bind(this,'HburgClick','Click2')}>
                                      { Object.keys(tmpData).map((streamId)=> {
                                          return (<label htmlFor={this.defaultHtmlFor+menuId+"-"+streamId} key={'st-'+streamId} className="subcat-a child_link">{this.streamToTagsMapping[streamId].name}</label>)
                                        }) 
                                      }        
                                  </div>
                              </div>
                          </div>
                        </div>
        childLabel.push(element);
        html['layer2'] = childLabel.length ? childLabel : null;
    }else{
      if(typeof(this.props.tabsContentByCategory['rankingStreamWiseData'][this.props.selectedStreamId]) !='undefined'){
        tmpData[this.props.selectedStreamId] = this.props.tabsContentByCategory['rankingStreamWiseData'][this.props.selectedStreamId];
        thresoldExceed = (this.props.tabsContentByCategory['rankingStreamWiseData'][this.props.selectedStreamId].length <= this.THRESHOLD_LIMIT) ? true : false; 
        if(thresoldExceed){
          var label = new Array();
          for(var s in tmpData[this.props.selectedStreamId]){
            label.push(<label key={tmpData[this.props.selectedStreamId][s].rankingPageName} className="cat-wrapper"><a className={this.layer1Class} href={tmpData[this.props.selectedStreamId][s].url}>{tmpData[this.props.selectedStreamId][s].rankingPageName+' College Rankings'}</a></label>);
          }
          html['layer1'] = label;
        }else{
          html['layer1']    = this.generateParentLabel(data);
        }
      }
    }

    if(Object.keys(tmpData).length>0 && !thresoldExceed){
        childLabel2 = Object.keys(tmpData).map((streamId)=>{
            return (<div key={"l3-"+menuId+streamId}>
                          <input type="checkbox" id={this.defaultHtmlFor+menuId+"-"+streamId} className="toggle-child" />
                          <div className="subcat-layer">
                              <div className="subcat-heading">
                                <label className="hamburger-back" htmlFor={this.defaultHtmlFor+menuId+"-"+streamId}></label>
                                <div className="r-table">
                                  <div className="subcat-p">{data['labelName']}</div>
                                  <div className="subcat-dtls">
                                    <span className="clgs-stream">{(this.streamToTagsMapping[streamId].name) ? 'In '+this.streamToTagsMapping[streamId].name : null}</span>
                                  </div>
                                </div>
                              </div>
                              <div className="subcat-nav">
                                  <div className="subcat-inner">
                                    {
                                        this.prepareRankingInnerHtml(tmpData[streamId])
                                    }
                                  </div>
                              </div>
                          </div>
                        </div>
              )  
          });
        html['layer3'] = childLabel2.length ? childLabel2 : null;
    }

    return html;
  } 

  prepareRankingInnerHtml(tmpData){
    var tmpHtml = new Array();
    for(var i in tmpData){
        var lbl = <label key={tmpData[i].rankingPageName} className="subcat-a"><a href={tmpData[i].url}>
      {(tmpData[i].rankingPageName == "Engineering") ? 'All '+tmpData[i].rankingPageName+((this.props.selectedStreamId>0) ? ' College Rankings' : '') : tmpData[i].rankingPageName+((this.props.selectedStreamId>0) ? ' College Rankings' : '')}</a></label>
          tmpHtml.push(lbl);
     }
     return tmpHtml;
  }

  _getReviewsHtml(menuId){
    return this.getCommonConfigLayer(menuId, 'collegeReviewsUrl');
  }

  getCompareCollegesHtml(menuId){
    var html = [],data = [];
    
    data['id'] = menuId;
    data['class'] = this.layer1Class;
    data['link'] = this.props.config.SHIKSHA_HOME+'/resources/college-comparison';
    data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
    html['layer1']    = this.generateParentLabel(data);
    
    return html; 
  }

  getViewExamDetailsHtml(menuId){
    var thresoldExceed = false;
    var html = [],data = [], childLabel = [];
    var tmpData = {};
        data['id']  = menuId;
        data['labelName'] = this.menuLayer1Subtitles[menuId]['name'].replace('{name}','');
    if(this._isGeneric()){
        html['layer1']    = this.generateParentLabel(data);
        html['layer2']    = this.prepareStreamLayer(data,'allExamPage');
    }else{
        if(typeof(this.props.tabsContentByCategory['allExamsPageData']) !='undefined' && typeof(this.props.tabsContentByCategory['allExamsPageData'][this.props.selectedStreamId])){
            var labelName = (this.props.selectedStreamName.length>23) ? this.props.selectedStreamName.substr(0,23)+ ' ... Exams' : this.props.selectedStreamName + ' Exams';
            var label = new Array();
                label.push(<label key={'ex'+this.props.selectedStreamId} className="cat-wrapper"><a href={this.props.tabsContentByCategory['allExamsPageData'][this.props.selectedStreamId]} className={this.layer1Class}>{labelName}</a></label>)
            html['layer1'] = label;
        }
    }
    return html; 
  }

  getExamImportantDatesHtml(menuId){
    return this.getCommonConfigLayer(menuId, 'examImportantDatesUrl');
  }

  getCommonConfigLayer(menuId, configKeyName){

    var html = [], data = [], childLabel = [];
    var tmpData = {};
    var tmpName = (hmConfig[configKeyName][this.props.selectedStreamId]) ? hmConfig[configKeyName][this.props.selectedStreamId]['name'] : '';
    if(this._isGeneric()){
      data['id']  = menuId;
      data['labelName'] = this.menuLayer1Subtitles[menuId]['name'].replace('{name}',tmpName);
      html['layer1']    = this.generateParentLabel(data);
      let element =  <div key={"l2-"+menuId}>
                      <input type="checkbox" id={'level1-'+menuId+"-"+this.props.selectedStreamId} className="toggle-child" />
                      <div className="subcat-layer">
                          <div className="subcat-heading">
                            <label className="hamburger-back" htmlFor={'level1-'+menuId+"-"+this.props.selectedStreamId}></label>
                            <div className="r-table">
                              <div className="subcat-p">{data['labelName']}</div>
                              <div className="subcat-dtls">
                                <span className="clgs-stream initial_hide"></span>
                              </div>
                            </div>
                          </div>
                          <div className="subcat-nav">
                              <div className="subcat-inner" onClick={this.gaTrackEvent.bind(this,'HburgClick','Click2')}>

                                {this.generateLabelData(hmConfig[configKeyName], this.props.config.SHIKSHA_HOME)}

                              </div>
                          </div>
                      </div>
                    </div>
    childLabel.push(element);
    html['layer2'] = childLabel.length ? childLabel : null;
    }else if(tmpName){
      data['id']  = menuId;
      data['class'] = this.layer1Class;
      data['link'] = this.props.config.SHIKSHA_HOME+hmConfig[configKeyName][this.props.selectedStreamId].url;
      data['labelName'] = this.menuLayer1Subtitles[menuId]['name'].replace('{name}',tmpName);
      html['layer1']    = this.generateParentLabel(data);
    }
    return html; 
  }

  getCampusRepPrograms(menuId){
    var html = [],data = [] , childLabel = [];
    var tmpData = {};

    if(this._isGeneric()){
      var tmpName = (this.props.selectedStreamName != 'Select Stream') ? this.props.selectedStreamName :'';
      data['id']  = menuId;
      data['labelName'] = this.menuLayer1Subtitles[menuId]['name'].replace('{name}',tmpName);
      html['layer1']    = this.generateParentLabel(data);
      tmpData = (this.props.tabsContentByCategory['campusRepProgramDetails']) ? this.props.tabsContentByCategory['campusRepProgramDetails'] : '';
      let element =  <div key={"l2-"+menuId}>
                      <input type="checkbox" id={'level1-'+menuId+"-"+this.props.selectedStreamId} className="toggle-child" />
                      <div className="subcat-layer">
                          <div className="subcat-heading">
                            <label className="hamburger-back" htmlFor={'level1-'+menuId+"-"+this.props.selectedStreamId}></label>
                            <div className="r-table">
                              <div className="subcat-p">{data['labelName']}</div>
                              <div className="subcat-dtls">
                                <span className="clgs-stream initial_hide"></span>
                              </div>
                            </div>
                          </div>
                          <div className="subcat-nav">
                              <div className="subcat-inner" onClick={this.gaTrackEvent.bind(this,'HburgClick','Click2')}>
                    
                                {this.generateCRLabelData(tmpData, '')}

                              </div>
                          </div>
                      </div>
                    </div>
      childLabel.push(element);
      html['layer2'] = childLabel.length ? childLabel : null;
    }else{
      var listData = [],tmpHtml = [];
      var mapData = hmConfig['askCurrentStudents'][this.props.selectedStreamId];
          tmpData = (this.props.tabsContentByCategory['campusRepProgramDetails']) ? this.props.tabsContentByCategory['campusRepProgramDetails'] : '';
      if((typeof(tmpData) != 'undefined' && tmpData) && typeof(mapData) != 'undefined'){
          for(var i in tmpData){
             if(mapData.indexOf(tmpData[i].programName) != -1){
                listData.push(i);
             }
          }
          if(listData.length <= this.THRESHOLD_LIMIT){
              for(var i in listData){
                  let element = <label  key={tmpData[listData[i]].programName} className="cat-wrapper"><a className={this.layer1Class} href={tmpData[listData[i]].ccUrl}>{this.menuLayer1Subtitles[menuId]['name'].replace('{name}',tmpData[listData[i]].programName)}</a></label>
                    tmpHtml.push(element);
              }
              html['layer1'] = tmpHtml; 
          }else{
                  data['id']  = menuId;
                  data['labelName'] = this.menuLayer1Subtitles[menuId]['name'].replace('{name}','');
                  let element =  <div key={"l2-"+menuId}>
                      <input type="checkbox" id={'level1-'+menuId+"-"+this.props.selectedStreamId} className="toggle-child" />
                      <div className="subcat-layer">
                          <div className="subcat-heading">
                            <label className="hamburger-back" htmlFor={'level1-'+menuId+"-"+this.props.selectedStreamId}></label>
                            <div className="r-table">
                              <div className="subcat-p">{data['labelName']}</div>
                              <div className="subcat-dtls">
                                <span className="clgs-stream initial_hide"></span>
                              </div>
                            </div>
                          </div>
                          <div className="subcat-nav">
                              <div className="subcat-inner" onClick={this.gaTrackEvent.bind(this,'HburgClick','Click2')}>
                            
                                {this.generateCRLabelData(tmpData, listData)}

                              </div>
                          </div>
                      </div>
                    </div>
                  tmpHtml.push(element);
              html['layer1']    = this.generateParentLabel(data);
              html['layer2'] = tmpHtml.length ? tmpHtml : null;
          }
      }
    }
    return html;   
  }

  generateCRLabelData(data, listData){
    var label = new Array();
    for(var s in data){
      if(data[s].programName != 'All' && listData==''){
          label.push(<label  key={data[s].programName} className="subcat-a"><a href={data[s].ccUrl}>{data[s].programName}</a></label>);
      }else if(typeof(listData) != 'undefined' && listData.indexOf(s) != -1){
          label.push(<label  key={data[s].programName} className="subcat-a"><a href={data[s].ccUrl}>{data[s].programName}</a></label>);
      }
    }
    return label;
  }

  getAnaLayerHtml(menuId){
    var html = [];
    var data = [];

    data['id']  = menuId;
    data['link']  = this.props.config.SHIKSHA_HOME+'/mAnA5/AnAMobile/getQuestionPostingAmpPage';
    data['class']  = this.layer1Class;
    data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
    html['layer1']    = this.generateParentLabel(data);
    
    return html;
  }

  getAlumniHtml(menuId){
    var html = [];
    var data = [];

    data['id']    = menuId;
    data['link']  = this.props.config.SHIKSHA_HOME+'/mba/resources/mba-alumni-data';
    data['class'] = this.layer1Class;
    data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
    html['layer1']    = this.generateParentLabel(data);
    
    return html;
  }

  getNewsHtml(menuId){
        var html = [];
        var data = [];
        if(this.props.selectedStreamId){
            data['link'] = this.props.config.SHIKSHA_HOME+'/'+seo_url(this.props.selectedStreamName)+'/articles-st-'+this.props.selectedStreamId; 
        }else {
            data['link'] = this.props.config.SHIKSHA_HOME+hmConfig.articleUrl;
        }
        data['id'] = menuId;
        data['class'] = this.layer1Class;
        data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
        html['layer1']    = this.generateParentLabel(data);
        return html;
  }

  getApplyCollegesHtml(menuId){
    return this.getCommonConfigLayer(menuId, 'applyColleges');
  }  

  getDiscussionsHtml(menuId){
        var html = [];
        var data = [];
        
        if(this.props.selectedStreamId){
            var streamToTagsMapping = hmConfig['streamToTagsMapping'];
            var tagId   = streamToTagsMapping[this.props.selectedStreamId]['id'];
            var tagName = streamToTagsMapping[this.props.selectedStreamId]['name'];
            var seoLink = getSeoUrl(tagId,'tag',tagName); 
            data['link'] = this.props.config.SHIKSHA_HOME+seoLink+'?type=discussion';
        }else {
            data['link'] = this.props.config.SHIKSHA_HOME+hmConfig['discussionsHome'];
        }
        data['id'] = menuId;
        data['class'] = this.layer1Class;
        data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
        html['layer1']    = this.generateParentLabel(data);
        return html;  
  }

  getQuestionsHtml(menuId){
        var html = [];
        var data = [];
        
        if(this.props.selectedStreamId){
            var streamToTagsMapping = hmConfig['streamToTagsMapping'];
            var tagId = streamToTagsMapping[this.props.selectedStreamId]['id'];
            var tagName = streamToTagsMapping[this.props.selectedStreamId]['name'];
            var seoLink = getSeoUrl(tagId,'tag',tagName); 
            data['link'] = this.props.config.SHIKSHA_HOME+seoLink+'?type=question';
        }else {
            data['link'] = this.props.config.SHIKSHA_ASK_HOME;
        }
        data['id'] = menuId;
        data['class'] = this.layer1Class;
        data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
        html['layer1']    = this.generateParentLabel(data);
        return html;
  }

  getIIMPredictorHtml(menuId){
        var html = [];
        var data = [];
        
        data['id']    = menuId;
        data['link']  = this.props.config.SHIKSHA_HOME+hmConfig['iimPredictorUrl'];
        data['class'] = this.layer1Class;
        data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
        html['layer1']    = this.generateParentLabel(data);
        
        return html;
  }

  getCollegePredictorHtml(menuId){
        var html = [],data = [];
        var cpData = {};
        var finalData = [];
        cpData = (this.props.tabsContentByCategory['collegePredictorData']) ? this.props.tabsContentByCategory['collegePredictorData'] : '';
        if(this._isGeneric()){
            for(var stream in cpData){
      
                var cpp = (Object.keys(cpData[stream]).indexOf('popularPredictor') !=-1) ? cpData[stream]['popularPredictor'] : '';
                var cpo = (Object.keys(cpData[stream]).indexOf('otherPredictor') !=-1) ? cpData[stream]['otherPredictor'] : '';
              
                for(var j in cpp){
                    finalData.push(cpp[j]);
                }                                                                                                                             
                for(var j in cpo){
                    finalData.push(cpo[j]);
                }
            }  
        }else{
          if(Object.keys(cpData).indexOf(this.props.selectedStreamName) != -1){
            var cpp = cpData[this.props.selectedStreamName]['popularPredictor'];
            var cpo = cpData[this.props.selectedStreamName]['otherPredictor'];
            if(cpp.length){
                for(var j in cpp){
                      finalData.push(cpp[j]);
                }
            }
            if(cpo.length){                                                     
              for(var j in cpo){
                    finalData.push(cpo[j]);
              }
            }  
          }
        }
        data['id']    = menuId;
        data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
        html['layer1']    = this.generateParentLabel(data);
        html['layer2']    = this.getPredictorsLayer(menuId, data, finalData);
        return html;
  }

  getRankPredictorHtml(menuId){
        var html = [],data = [];
        var popularPredictor = (this.props.tabsContentByCategory['rankPredictorData']) ? this.props.tabsContentByCategory['rankPredictorData']['popularPredictor'] : '';
        var otherPredictor   = (this.props.tabsContentByCategory['rankPredictorData']) ? this.props.tabsContentByCategory['rankPredictorData']['otherPredictor'] : '';
        var rankData = popularPredictor.concat(otherPredictor);
  
        data['id']    = menuId;
        data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
        html['layer1']    = this.generateParentLabel(data);
        html['layer2']    = this.getPredictorsLayer(menuId, data, rankData);
        return html;
  }  

  getPredictorsLayer(menuId, data, predictData){
    var label = new Array();
    var childLabel = [];
    let element =  <div key={"l2-"+menuId}>
                    <input type="checkbox" id={'level1-'+menuId+"-"+this.props.selectedStreamId} className="toggle-child" />
                    <div className="subcat-layer">
                        <div className="subcat-heading">
                          <label className="hamburger-back" htmlFor={'level1-'+menuId+"-"+this.props.selectedStreamId}></label>
                          <div className="r-table">
                            <div className="subcat-p">{data['labelName']}</div>
                            <div className="subcat-dtls">
                              <span className="clgs-stream initial_hide"></span>
                            </div>
                          </div>
                        </div>
                        <div className="subcat-nav">
                            <div className="subcat-inner" onClick={this.gaTrackEvent.bind(this,'HburgClick','Click2')}>
                                {this.generateLabelData(predictData)}
                            </div>
                        </div>
                    </div>
                  </div>
            childLabel.push(element);
            return childLabel.length ? childLabel : null;
  }

  generateLabelData(data, domain){
    var label = new Array();
    for(var s in data){
      label.push(<label  key={data[s].name} className="subcat-a"><a href={(domain) ? domain+data[s].url : data[s].url }>{data[s].name}</a></label>);
    }
    return label;
  }
  
  getAboutHtml(menuId){
      var html = [];
      var data = [];
      data['id']   = menuId;
      data['link'] = this.props.config.SHIKSHA_HOME+hmConfig['aboutusUrl'];
      data['class'] = this.layer1Class;
      data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
      html['layer1']    = this.generateParentLabel(data);
      return html;
  }

  getHelpHtml(menuId){
      var html = [];
      var data = [];
      data['id']   = menuId;
      data['link'] = this.props.config.SHIKSHA_HOME+hmConfig['helplineUrl'];
      data['class'] = this.layer1Class;
      data['labelName'] = this.menuLayer1Subtitles[menuId]['name'];
      html['layer1']    = this.generateParentLabel(data);
      return html;
  }
  
  generateParentLabel(option){
    var label     = [];
    var clsName   = (option['class']) ? option['class'] : this.layer2Class;
    var uniqueKey = option['id'];
    var href      = option['link'];
    var heading   = option['labelName'];
    if(href){
          var lnk = <label  key={ uniqueKey } htmlFor={"level1-"+ uniqueKey +'-'+this.props.selectedStreamId } className="cat-wrapper"><a className={clsName} href={href}>{heading}</a></label>
      }else{
          var lnk = <label  key={ uniqueKey } htmlFor={"level1-"+ uniqueKey +'-'+this.props.selectedStreamId } className="cat-wrapper"><a className={clsName}>{heading}</a></label>    
      }
    label.push(lnk);
    return label;            
  }

  prepareLayer(){
    var mainData = this.getHamburgerMenu();
    var labelArr = Object.keys(mainData['layer1']); 
    return (
      <div>

            <div id="l1Layer" className="cat-menu" onClick={this.gaTrackEvent.bind(this,'HburgClick','Click1')}>
                  {
                    labelArr.map((section)=> {
                              var tmpList = [];
                                  tmpList.push(<div className="sectionHeading" key={section}>{section}</div>);

                              for(var i in mainData['layer1'][section]){
                                  tmpList.push(mainData['layer1'][section][i]);
                              }
                      return tmpList;
                    })
                  }
                  <a href="https://studyabroad.shiksha.com" className="abroadHeading">Go to study abroad website <span className="abroadLink-icn"></span></a>
            </div>
      </div>
    );
  }

  childLayers(){
    var mainData = this.getHamburgerMenu();
    var mainKeys = Object.keys(mainData);
    var keyIndex = mainKeys.indexOf('layer1');
        mainKeys.splice(keyIndex,1);
      
    return (
      <div>
            {
              mainKeys.map((layer)=>{
                var layerDataArr = [];
                    for(var i in mainData[layer]){
                      (mainData[layer][i]) ? layerDataArr.push(mainData[layer][i]) : null
                    }
                    return layerDataArr;
              })
            }
      </div>
    );
  }


  modalClose()
  {
      this.setState({...this.state,locationLayerData : {},nonZeroLayer : false});
  }

  getNonZeroLocation(data){
      if(Object.keys(data).length > 0)
      {
        this.setState({locationLayerData : data,nonZeroLayer : true});
      }
  }

}

function mapStateToProps(state)
{
    return {
        config : state.config
    }
}
export default connect(mapStateToProps)(Hamburger);
