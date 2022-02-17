import PropTypes from 'prop-types'
import React from 'react';

import './../assets/ExamList.css';

import config from './../../../../config/config';
import {addingDomainToUrl} from './../../../utils/commonHelper';
import Analytics from './../../reusable/utils/AnalyticsTracking';

class ExamList extends React.Component {
  constructor(props){
    super(props);
    this.state = {
      showMore : false,
      expandMore : false
    }
  }
  componentDidMount(){
    let self = this;
    document.addEventListener("click", function(e){
      if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('more-exam') < 0)){
        self.hideMoreList();
      }
    });
  }
  trackEvent(actionLabel,label){
    Analytics.event({category : this.props.gaTrackingCategory, action : actionLabel, label : label});
  }
  render(){
    let moreExam = [], examUrl = null, noExamCls = '', expandedExam = [];
    if(this.props.examList.length > 0){
      this.props.examList.forEach(
          (exam, iter) => {
            if(iter > 0){
              if(exam.url == null || exam.url == ''){
                examUrl = 'javascript:void(0);';
                noExamCls = 'no-exam';
              }else{
                examUrl = exam.url;
                noExamCls = '';
              }
              // moreExam.push(<li key={exam.id+'_'+iter}><a className={noExamCls} href={examUrl} onClick={this.trackEvent.bind(this, 'Exams', 'tuple_click')}>{exam.name}</a></li>);
              expandedExam.push(<span key={exam.id+'_'+iter}><a className={noExamCls} href={examUrl} onClick={this.trackEvent.bind(this, 'Exams', 'tuple_click')}>{exam.name}</a></span>);
            }
          }
      );
    }
    let firstExamUrl = 'javascript:void(0);';
    noExamCls = 'no-exam';
    if(this.props.examList[0].url != null && this.props.examList[0].url != ''){
      noExamCls = '';
      firstExamUrl = addingDomainToUrl(this.props.examList[0].url, config().SHIKSHA_HOME);
    }
    return (
        <React.Fragment>
        <span className="exam">
          Exams
          <strong>
            {/*this.props.examList.length > 0 ? <span><a className={noExamCls} href={firstExamUrl} onClick={this.trackEvent.bind(this, 'Exams', 'tuple_click')}>{this.props.examList[0].name}</a></span> : null*/}
            {expandedExam.map((exam, i) => {return ([i > 0 && ",", exam])})}
            {/*this.props.examList.length > 0 ? <span className="more-exam" onClick={this.showMoreList.bind(this)}> +{this.props.examList.length-1} More <span>{this.props.examList.length > 1 ? <div className="exm-more-lyr"><ul>{moreExam}</ul></div> : null}</span></span> : null */}
          </strong>
        </span>
        </React.Fragment>
    );
  }
  showMoreList(){
    this.trackEvent('More Exams', 'tuple_click');
    if(this.props.deviceType == 'desktop'){
      if(this.state.showMore){
        this.setState({showMore : false});
      }
      else {
        this.setState({showMore : true});
      }
    }else{
      this.setState({expandMore : true});
    }
  }
  hideMoreList(){
    if(this.props.deviceType == 'desktop'){
      if(this.state.showMore == true){
        this.setState({showMore : false});
      }
    }
  }
}

ExamList.defaultProps = {
  deviceType : 'mobile'
};

export default ExamList;

ExamList.propTypes = {
  deviceType: PropTypes.string,
  examList: PropTypes.any,
  gaTrackingCategory: PropTypes.any
}