import React, { Component } from 'react';
import { connect } from 'react-redux';

import {setCookie,getCompareData,getCookie} from '../../../../utils/commonHelper';
import {showResponseFormWrapper} from '../../../../utils/regnHelper';
import CompareList from './CompareList';

class BottomCompareWidget extends Component {
  constructor(props){
    super(props);
    this.state = {
      compareStickyActiveStatus : 0
    }
  }
  componentDidMount(){
    window.getcHostName = this.props.config.SHIKSHA_HOME + '/resources/college-comparison';
    window.cookiename = 'compare-global-data';
    //window.addEventListener('load', this.handleBottomCompareWidget.bind(this));
    this.handleBottomCompareWidget();
    this.setCompareCookie();
    document.querySelectorAll('._cmpTgleBtn').forEach(
      currObj => {
        currObj.addEventListener('click', this.toggleCompareBottomSticky.bind(this, currObj));
      }
    );
    document.querySelector('.cmpre-btn').addEventListener('click', this.userLoginForCompare.bind(this));
    document.querySelector('.rmAll').addEventListener('click', this.removeAll.bind(this));
    if(getCookie('compare-active-status') == '1'){
      this.setState({compareStickyActiveStatus : 1});
    }
  }
  userLoginForCompare(){
    var trackingPageKeyId = getCookie('comparetrackingPageKeyId');
    var cmpCookieArr = getCompareData(cookiename);
    if(getCookie('user') == '' && cmpCookieArr.length >= 2){
      var formData = {
        'trackingKeyId': trackingPageKeyId,
        'callbackObj': this,
        'callbackFunction': 'gotoComparePageWrapper',
        'cta': 'compare'
	    };
      if(getCookie(cookiename) && getCookie(cookiename) !=''){
        var courseIdData = getCookie('compare-global-data').split('|')[0].split('::');
        formData = {
	        'trackingKeyId': courseIdData[1],
	        'callbackFunction': 'gotoComparePageWrapper',
          'cta': 'compare'
	    	};
      }
      //this.getComparePageUrl();
      showResponseFormWrapper(courseIdData[0], 'COMPARE_VIEWED', 'course', formData, {});
      return true;
    }else{
      this.getComparePageUrl();
    }
  }
  gotoComparePageWrapper(responseRes){
    console.log('gotoComparePageWrapper is called successfully');
    if(responseRes['userId'] > 0){
      this.getComparePageUrl();
    }
  }
  getComparePageUrl(){
    let cmpData = getCompareData(cookiename);
    if(cmpData.length <= 1){
      this.openConfirmLayer('Add at least two colleges to compare.');
      return true;
    }
    var finalUrl = "";
    var floatingWidget = 0;
    for(var i = 0; i <  4; i++){
        if(cmpData[i] != undefined){
            floatingWidget = 1;
            var cmpStr = cmpData[i].split("::");
            finalUrl += cmpStr[0]+'-'; // course
        }
    }
    finalUrl = finalUrl.substring(0, (finalUrl.length)-1);
  	if (getcHostName) {
  		finalUrl = getcHostName + '-' + finalUrl;
  	}else{
  		finalUrl = '/resources/college-comparison-'+finalUrl;
  	}
  	window.location = finalUrl;
  }
  openConfirmLayer(msg, isConfirmLayer, compareParam, thisObj, callBackData){
    isConfirmLayer = (typeof(isConfirmLayer) == 'undefined') ? false : isConfirmLayer;
  	if(isConfirmLayer){
  		document.querySelector('#cancelBtn').style.display = 'block';
  	}else{
  		document.querySelector('#cancelBtn').style.display = 'none';
  	}
    document.querySelector('#cmpLyr').style.display = 'block';
	  document.querySelector('#err_lyr').innerHTML = msg;

    document.querySelector('#okBtn').addEventListener('click', () => {
      document.querySelector('#cmpLyr').style.display = 'none';
    });
    document.querySelector('#cancelBtn').addEventListener('click', () => {
      document.querySelector('#cmpLyr').style.display = 'none';
    });
  }
  setCompareCookie(){
    setCookie('comparetrackingPageKeyId', this.props.compareTrackingId, 30);
  }
  removeAll(){
    setCookie(cookiename, '', 0);
    this.resetCompareView();
  }
  resetCompareView(){
    let resetView = '';
    for (var i = 1; i <= 4; i++) {
      resetView += '<div class="added-clgs"><div class="num-to-add">'+i+'</div></div>';
    }
    document.querySelector('#addItems').innerHTML = resetView;
  }
  toggleCompareBottomSticky(obj){
    let delayPeriod = 100;
    let btn1 = document.querySelector('#_cmpBt1');
    let btn2 = document.querySelector('#_cmpBt2');
    if (obj.getAttribute('data-status') == 'false') {
      document.querySelector('#_cmpSticky').classList.add('noshadow');
  		setTimeout(function(){ btn1.style.display = 'none';}, delayPeriod);
      document.querySelector('#cmpItemList').style.display = 'none';
      setTimeout(function(){ btn2.style.display = 'block';}, delayPeriod);
      setCookie('compare-active-status',0,30);
    }else{
      setTimeout(function(){
        btn2.style.display = 'none';
        document.querySelector('#cmpItemList').style.display = 'block';
      }, delayPeriod);
  		setTimeout(function(){
        btn1.style.display = 'block';
        document.querySelector('#_cmpSticky').classList.remove('noshadow')
        document.querySelector('#_cmpSticky').style.width = '988px';
        document.querySelector('#_cmpSticky').style.display = 'block';
      }, delayPeriod);
  		setCookie('compare-active-status',1,30);
    }
  }
  handleBottomCompareWidget(){
    if (typeof(isCompareEnable) !='undefined' && isCompareEnable == true && getCookie(cookiename) !='') {
      document.querySelector('#_cmpSticky').style.display = 'block';
  	}else{
  		document.querySelector('#_cmpSticky').style.display = 'none';
  	}
  }
  render() {
    return (
      <React.Fragment>
      <div className={"compare-bot-sticky "+ (this.state.compareStickyActiveStatus==1 ? '' : 'noshadow')} id="_cmpSticky" style={{display:'none'}}>
        <CompareList compareStickyActiveStatus={this.state.compareStickyActiveStatus} />
        <a href="javascript:void(0);" className="show-hide-btn _cmpTgleBtn" id="_cmpBt1" style={this.state.compareStickyActiveStatus==1 ? {display:'block'} : {display:'none'}} data-status="false"><i className="common-sprite hide-arr"></i></a>
        <a href="javascript:void(0);" className="show-cmpre-btn _cmpTgleBtn" id="_cmpBt2" style={this.state.compareStickyActiveStatus==1 ? {display:'none'} : {display:'block'}} data-status="true">Compare<i className="common-sprite show-arr"></i></a>
      </div>
      <div id="cmpLyr" className="cmp-show-layer" style={{display:'none'}}>
        <div className="cmn-head">
          <div className="cmn-header">
            <p>College Comparison</p>
          </div>
          <div className="alert-div">
            <p id="err_lyr" style={{position:'relative'}}></p>
            <div style={{textAlign:'right'}}>
              <a id="cancelBtn" className="pop cb">Cancel</a>
              <a id="okBtn" className="pop">Ok</a>
            </div>
          </div>
        </div>
      </div>
      </React.Fragment>
    );
  }
}

function mapStateToProps(state)
{
    return {
        config : state.config
    }
}
export default connect(mapStateToProps)(BottomCompareWidget);
