import React, { Component } from 'react';
import { connect } from 'react-redux';
import {bindActionCreators} from 'redux';
import APIConfig from './../../../../../config/apiConfig';
import {getRequest} from '../../../../utils/ApiCalls';
import {getCompareCourse,getCompareData, setCookie, updateHeaderNotification} from '../../../../utils/commonHelper';
import {storeSaveList} from './../../../predictors/CollegePredictor/actions/CollegePredictorAction';

let isCmpRemoved = false;
class CompareList extends Component {
  componentDidMount(){
    let storeData = {};
    let comparedCourses = getCompareCourse('compare-global-data');
    var query = '';
    for(var i in comparedCourses){
        query += '&compareCourseIds='+comparedCourses[i];
    }
    getRequest(APIConfig.GET_RHL+'?userId=0'+query)
      .then((res) => {
          this.setCompareList(res.data.data.compareCourseMap);
          let shrtCount = (res.data.data.shortlistedCourse.length) ? res.data.data.shortlistedCourse.length : 0;
          setCookie('mob_shortlist_Count',shrtCount);
          document.querySelectorAll('.removeCompare').forEach(
            currObj => {
              currObj.addEventListener('click', this.removeCompareFormSticky.bind(this))
            }
          );
          updateHeaderNotification({shortlistCount : shrtCount});

          let saveListUrl = (res.data.data && res.data.data.shortlistUrl) ? res.data.data.shortlistUrl : '';
          if(saveListUrl){
              storeData.url = saveListUrl;
              this.props.storeSaveList(storeData);
          }
      }).catch((err)=> console.log(err));
  }
  removeCompareFormSticky(obj){
    var thisObj = obj.srcElement;
    var iconId = thisObj.getAttribute('id');
  	var count = iconId.split('_');
  	var innerhtml='<div class="num-to-add">'+count[2]+'</div>';
    document.querySelector('#b_'+count[2]).innerHTML = innerhtml
  	var courseId = thisObj.getAttribute('data-courseId');
  	var instituteId = thisObj.getAttribute('data-instituteId');
  	this.removeCompare(courseId,'closeIcon');
  	var response = {'msg':'success','actionType': 'removed', 'courseId':courseId, 'instituteId':instituteId};
  	//callRemoveAllCallBack(response);
  	//getStickyView();
  }
  removeCompare(courseId, fromWhere){
    fromWhere = (typeof(fromWhere) == 'undefined') ? '' : fromWhere;
	  var array = getCompareData('compare-global-data');
	  isCmpRemoved = false;
	  for(var i in array){
	    var cookieItemArray = array[i].split("::");
      if(cookieItemArray[0]==courseId){
        array.splice(i,1);
        cmpCookieArr = array;
        if(fromWhere === 'closeIcon'){
         	setCookie('compare-global-data', cmpCookieArr.join("|"), 30);
        }
        isCmpRemoved = true;
        break;
      }
    }
  }
  setCompareList(compareData){
    let finalData = [];
    let iter = 1;
    for (var i in compareData) {
      finalData.push(
        '<div class="added-clgs _ldr" id="'+'b_'+iter+'"><a class="ready-to-compare" href="/mba/course/dummyURL" title="'+compareData[i].instituteName+'">'+compareData[i].instituteName+'</a><a class="close-icon removeCompare" href="javascript:void(0);" id="'+'_close_'+iter+'" data-courseid="'+compareData[i].courseId+'" data-instituteid="'+compareData[i].instituteId+'">×</a></div>'
      );
      iter++;
    }
    if(iter <= 4){
      for (var i = iter; i <= 4; i++) {
        finalData.push(
          '<div class="added-clgs"><div class="num-to-add">'+i+'</div></div>'
        );
      }
    }
    document.querySelector('#addItems').innerHTML = finalData.join("");
  }
  render(){
    let courseList = (
      <div className="clgs-added" id="addItems">
        <div className="added-clgs"><div className="num-to-add">1</div></div>
        <div className="added-clgs"><div className="num-to-add">2</div></div>
        <div className="added-clgs"><div className="num-to-add">3</div></div>
        <div className="added-clgs"><div className="num-to-add">4</div></div>
      </div>
    );
    return (
      <div className="compare-sticky-items" id="cmpItemList" style={this.props.compareStickyActiveStatus==1 ? {display:'block'} : {display:'none'}}>
        <div className="cmploader"><img /></div>
        {courseList}
        <div className="added-clgs btn-col" id="compare-orng-btn">
          <div className="cmpre-col">
            <a href="javascript:void(0);" className="cmpre-btn">Compare</a>
            <a href="javascript:void(0);" className="link rmAll">Remove All</a>
          </div>
        </div>
      </div>
    );
  }
}

function mapStateToProps(state)
{
    return {
        config : state.config
    }
}
function mapDispatchToProps(dispatch){
  return bindActionCreators({storeSaveList},dispatch);
}
export default connect(mapStateToProps, mapDispatchToProps)(CompareList);
