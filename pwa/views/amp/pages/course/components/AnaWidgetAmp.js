import React from 'react';
import {cutStringWithShowMore} from '../../../../../application/utils/stringUtility';
import Analytics from '../../../../../application/modules/reusable/utils/AnalyticsTracking';
import config from './../../../../../config/config';


class AnaWidgetAmp extends React.Component{
  constructor(props){
    super(props);

  }

  generateAnAWidgetHtml(){

      var questiondata = (this.props.anaWidget)?this.props.anaWidget.questionsDetail:null;
      var anaHtml = [];
      for(var index in questiondata) {
        anaHtml.push(<li key="anaHtml_key"><div className="qstn-div" key={'ques_'+index}>
                        <div className="qstn-det">
                             <a className="color-3 font-w6 l-18 block f16 m-5btm ga-analytic" data-vars-event-name="QUESTION_TITLE" href={questiondata[index]['url']}>{questiondata[index]['title']}</a>
                               <span className="a-span block">
                                  <ol>
                                    {questiondata[index]['msgCount'] != undefined ?  <React.Fragment><li className="i-block">
                                        <p className="color-3 f10 pos-rl">{questiondata[index]['msgCount'] > 1 ? questiondata[index]['msgCount'] +' Answers' : questiondata[index]['msgCount'] + ' Answer'}</p>
                                    </li> <li className="i-block">
                                       <p className="color-3 f10 pos-rl"><i className="dot"></i></p>
                                    </li></React.Fragment>: ''}
                                    { questiondata[index]['viewCount'] != undefined ?
                                    <li className="i-block">
                                        <p className="color-3 f10 pos-rl"> { questiondata[index]['viewCount'] > 1 ? questiondata[index]['viewCount'] + ' Views' : questiondata[index]['viewCount'] + ' View' } </p>
                                        <span></span>
                                    </li>: ''}
                                  </ol>
                                </span>
                        </div>
                        <div className="ana-det m-15top">
                          <p className="m-5btm color-6 f14">
                              Answered by &nbsp;
                               <strong className="color-3 f13 font-w6">{questiondata[index]['answerOwnerName']}</strong>
                          </p>
                          <div>
                            <input type="checkbox" className="read-more-state hide" id={"answerTxt_"+index}/>
                            <p className='read-more-wrap word-break f13 color-3 l-18 font-w4 ga-analytic' data-vars-event-name="VIEW_MORE_ANSWER" dangerouslySetInnerHTML={{ __html : cutStringWithShowMore(questiondata[index]['answerText'],300,'answerTxt_'+index,'more',false,false,true,true)}}></p>
                          </div>


                            {/*questiondata[index]['msgCount']>1 && <a href={questiondata[index]['url']} +' '+questiondata[index]['answerOwnerLevel'] className="nrml-link rd-link">{'Read All Answers ('+questiondata[index]['msgCount']+')'}</a>*/}
                        </div>
                    </div></li>);
        }

      return anaHtml;

  }

  generateCampusRepHtml(){
    let campusRepData = this.props.campusRepData.campusReps;
    let html =[];
    for(var index in campusRepData){
      html.push(<figure key="figure_key" className="color-w ask-s">
                         <div className="usr-id t-cntr m-5btm">
                        {
                          campusRepData[index]['imageUrl'] != '' && campusRepData[index]['imageUrl'] ? <amp-img src={campusRepData[index]['imageUrl']} width="61" height="61" layout="responsive" alt={campusRepData[index]['displayName'].substring(0,1).toUpperCase()}>
                                    </amp-img> : campusRepData[index]['displayName'] && campusRepData[index]['displayName'].substring(0,1).toUpperCase()
                        }
                      </div>
                      <div className="m-top">
                             <figcaption className="caption color-6 f12 font-w7 ellipsis">{campusRepData[index]['displayName']}</figcaption>
                      </div>
                    </figure>);

    }
    return html;
  }

  trackEvent(actionLabel,label)
  {
    Analytics.event({category : 'CLP', action : actionLabel, label : label});
  }

  render(){
    const domainName = config().SHIKSHA_HOME;
    var ANAlayerUrl = domainName+'/mAnA5/AnAMobile/getQuestionPostingAmpPage?courseId='+this.props.courseId+'&listingId='+this.props.instituteId+'&fromwhere=coursepage';
    var quesShown = (this.props.anaWidget != null && this.props.anaWidget.questionsDetail!=null && typeof this.props.anaWidget.questionsDetail != 'undefined') ? Object.keys(this.props.anaWidget.questionsDetail).length : 0;
    var totalQues = this.props.anaWidget != null ? this.props.anaWidget.totalNumber : 0;
    return(
      <React.Fragment>
      <section>
        <div className="data-card m-5btm" id="ana">
           {totalQues>0&&<h2 className='color-3 f16 heading-gap font-w6'>Ask & Answer <span className="f12 font-w4 color-3">(Showing {quesShown} of {totalQues} Q&A)</span>
            </h2>}
           {totalQues>0&&<div className="card-cmn color-w">
           <ul className="d">
              { this.generateAnAWidgetHtml()}
           </ul>
           {totalQues>2 && <a className='btn btn-ter color-w color-3 f14 font-w6 m-15top ga-analytic' data-vars-event-name="ASK_VIEWALL" href={this.props.anaWidget.allQuestionURL} >View All {totalQues} Questions </a>}
          </div>
          }
        </div>
      </section>
      <section class="data-card m-5btm">
             <h2 class="color-3 f16 heading-gap font-w6">{(this.props.campusRepData.campusReps && this.props.campusRepData.campusReps.length !=0 )?"Ask your queries to current students":"Still you have any questions?"}</h2>
                <div>
                 <amp-carousel height="0" width="0"  type="carousel"  class="s-c student-div">
                 {this.generateCampusRepHtml()}
                 </amp-carousel>
                <a class="btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic " data-vars-event-name="CA_ASK_NOW" href={ANAlayerUrl}>{(this.props.campusRepData.campusReps && this.props.campusRepData.campusReps.length !=0 )?"Ask Question":"Ask Now"}</a>
             </div>
         </section>
        </React.Fragment>
    )
  }
}


export default AnaWidgetAmp;
