import PropTypes from 'prop-types'
import React from 'react';
import { Link } from "react-router-dom";
import "./../assets/css/knowledgeBox.css";
import './../../../common/assets/Wikki.css';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {addingDomainToUrl} from './../../../../utils/commonHelper';
import { add_query_params} from './../../../../utils/urlUtility';
import {storeChildPageDataForPreFilled} from './../../instituteChildPages/actions/AllChildPageAction';
import {getTextFromHtml,strip_html_tags} from './../../../../utils/stringUtility';



class KnowledgeBox extends React.Component{

    constructor(props) {
        super(props);
        this.state = {
            showFullAboutSection :false
        }
    }


    trackEvent(actionLabel,label)
    {
        var category = 'IULP_KnowledgeBox_PWA';
        Analytics.event({category : category, action : actionLabel, label : label});
    }

    showStreamsCount(data){

        var streams = null;
        if(data.courseWidget.streamObjects){
            streams = data.courseWidget.streamObjects.length;
        }

        return(
            <React.Fragment>
                {streams > 1 ? (streams + ' streams') : (streams + ' stream')}
            </React.Fragment>
        )
    }


    showcourseCount = (coursecount) => {
        if(coursecount == null || typeof coursecount == 'undefined' || coursecount < 0)
            return null;
        return(
            <span>{ coursecount > 1 ? (coursecount + ' courses') : (coursecount + ' course') }</span>
        )

    }

    handleClickallCourses(eventAction, eventlabel,PageHeading = ''){
        this.trackEvent(eventAction,eventlabel);
        var data = {};
        if(this.props.data.listingId){
            data.listingId = this.props.data.listingId;
        }
        if(this.props.data.instituteTopCardData){
            data.instituteTopCardData = this.props.data.instituteTopCardData;
        }
        if(this.props.data.reviewWidget){
            data.reviewWidget = this.props.data.reviewWidget;
        }
        if(this.props.data.currentLocation){
            data.currentLocation = this.props.data.currentLocation;
        }
        if(this.props.data.aggregateReviewWidget !='undefined'){
            data.aggregateReviewWidget = this.props.data.aggregateReviewWidget;
        }
        if(this.props.data.anaCountString !='undefined'){
            data.anaCountString = this.props.data.anaCountString;
        }
        data.anaWidget = {};
        data.allQuestionURL = '';
        data.showFullLoader = false;
        data.PageHeading = 'Courses & Fees 2019';
        if(PageHeading !=''){
            data.PageHeading = PageHeading+' '+data.PageHeading;
        }


        this.props.storeChildPageDataForPreFilled(data);
    }

    getPopularCourses(){
        const {data} = this.props;
        var popularCoursesHtml = [];
        if(data.courseWidget && data.courseWidget.baseCourseObjects ){
            if(data.courseWidget.baseCourseObjects[0] && data.courseWidget.baseCourseObjects[0].name){
                popularCoursesHtml.push(<strong key="popular_first"> <Link to={data.courseWidget.allCoursesUrl +'/'+data.courseWidget.baseCourseObjects[0].url} onClick={this.handleClickallCourses.bind(this,'Click','Popular_Course',data.courseWidget.baseCourseObjects[0].name)} rel="nofollow">{data.courseWidget.baseCourseObjects[0].name}</Link></strong>);
            }
            if(data.courseWidget.baseCourseObjects[1] && data.courseWidget.baseCourseObjects[1].name){
                popularCoursesHtml.push(<strong key="popular_second"> <Link to={data.courseWidget.allCoursesUrl +'/'+data.courseWidget.baseCourseObjects[1].url} onClick={this.handleClickallCourses.bind(this,'Click','Popular_Course',data.courseWidget.baseCourseObjects[1].name)} rel="nofollow">, {data.courseWidget.baseCourseObjects[1].name}</Link></strong>);
            }
        }

        return popularCoursesHtml;
    }

    generateTableData(data){
        const {config} =this.props;
        var tableDataArray = {};
        if(data.instituteTopCardData.inlineData && data.instituteTopCardData.inlineData.estbYear){
            tableDataArray['Established'] = data.instituteTopCardData.inlineData.estbYear;
        }

        if(data.abbrevation || data.secondaryName){

            if(data.abbrevation){
                tableDataArray['Also Known As'] = data.abbrevation;
            }
            else if(data.secondaryName){
                tableDataArray['Also Known As'] = data.secondaryName;
            }
            else if(data.abbrevation && data.secondaryName){
                tableDataArray['Also Known As'] = (data.abbrevation.toLowerCase() === data.secondaryName.toLowerCase()? data.abbrevation : data.abbrevation+", "+data.secondaryName);
            }
        }

        if(data.currentLocation && data.currentLocation.city_name){
            tableDataArray['City'] = data.currentLocation.city_name;
        }

        if(data.currentLocation  && data.currentLocation.contact_details &&  data.currentLocation.contact_details.website_url){
            tableDataArray['Website'] = data.currentLocation.contact_details.website_url;
        }

        if(data.tupleData && data.tupleData.length != 0) {
            tableDataArray['Exams Conducted'] = <div><strong> {data.tupleData[0] && <a href={addingDomainToUrl(data.tupleData[0].url,config.SHIKSHA_HOME)} onClick={this.trackEvent.bind(this,'Click','Exams_Conducted')} rel="nofollow">{data.tupleData[0].name}</a>}</strong><strong> {data.tupleData[1] && <a href={addingDomainToUrl(data.tupleData[1].url,config.SHIKSHA_HOME)} onClick={this.trackEvent.bind(this,'Click','Exams_Conducted')} rel="nofollow">, {data.tupleData[1].name}</a>}</strong> </div>;
        }

        if(data.acceptedExams && data.acceptedExams.length != 0 ){
            tableDataArray['Accepted Exams'] = <div><strong> {data.acceptedExams[0] && <a href={addingDomainToUrl(data.acceptedExams[0].url,config.SHIKSHA_HOME)} onClick={this.trackEvent.bind(this,'Click','Exams_Accepted')} rel="nofollow">{data.acceptedExams[0].name}</a>}</strong><strong> {data.acceptedExams[1] && <a href={addingDomainToUrl(data.acceptedExams[1].url,config.SHIKSHA_HOME)} onClick={this.trackEvent.bind(this,'Click','Exams_Accepted')} rel="nofollow">, {data.acceptedExams[1].name}</a>}</strong> </div>
        }

        if(data.courseWidget && data.courseWidget.baseCourseObjects && data.courseWidget.baseCourseObjects[0]){
            tableDataArray['Popular Courses'] = this.getPopularCourses();
        }

        if(data.courseWidget && data.courseWidget.totalCourseCount){
            let url = data.courseWidget.allCoursesUrl;
            let locationurl = this.props.location;
            if(this.props.fromwhere =='institutePage' && this.props.isMultiLocation){
                if(locationurl && locationurl.city_id){
                    url = add_query_params(url, 'ct[]='+locationurl.city_id);
                }
                if(locationurl && locationurl.locality_id){
                    url = add_query_params(url, 'lo[]='+locationurl.locality_id);
                }
            }
            tableDataArray['Total Courses'] = <div><Link to={url} onClick={this.handleClickallCourses.bind(this,'Click','Courses_Offered','')}>{this.showcourseCount(data.courseWidget.totalCourseCount)}</Link> across {this.showStreamsCount(data)}</div>;
        }

        return tableDataArray;
    }


    renderTableHtml(){

        const {data} = this.props;
        var tableData = this.generateTableData(data);
        var totalElements = Object.keys(tableData).length;
        var ElementsInFirst = (totalElements%2 == 0)?(totalElements/2):((totalElements+1)/2) ;
        var ElementsInSecond = totalElements - ElementsInFirst;
        var first_table = [];
        var second_table = [];
        for(var key in tableData){
            if(ElementsInFirst >0 )
            {
                first_table.push(<tr key={"key_"+key}>
                    <td><strong>{key}</strong></td>
                    <td>{tableData[key]}</td>
                </tr>);

                ElementsInFirst--;
                continue;
            }
            if(ElementsInSecond >0 && ElementsInFirst == 0)
            {
                second_table.push(<tr key={"key_"+key}>
                    <td><strong>{key}</strong></td>
                    <td>{tableData[key]}</td>
                </tr>);

                ElementsInSecond--;
                continue;
            }
        }

        if(!this.props.isDesktop){
            return(<table>
                    <tbody>
                    {first_table}
                    {second_table}
                    </tbody>
                </table>
            );
        }else{
            return(<React.Fragment>
                    <table>
                        <tbody>
                        {first_table}
                        </tbody>
                    </table>
                    <table>
                        <tbody>
                        {second_table}
                        </tbody>
                    </table>
                </React.Fragment>
            );
        }

    }

    getAboutSection(){
        const {data} = this.props;
        let showFullText='hid';
        let showHalfText='';
        if(this.state.showFullAboutSection){
            showFullText = '';
            showHalfText = 'hid';
        }else{
            showFullText = 'hid';
            showHalfText = '';
        }
        var StringWithoutTags = strip_html_tags(data.aboutCollege);
            return(
                <React.Fragment>
                <div className={'aboutSection '+showHalfText}>
                    <div dangerouslySetInnerHTML={{
                         __html: getTextFromHtml(data.aboutCollege, 450)
                        }}></div>
                    {StringWithoutTags.length>450?<a href='javascript:void(0)' onClick={this.viewAboutSection} > Read More</a>:null}    
                </div>
                <div className={'aboutSection '+showFullText}>
                    <div dangerouslySetInnerHTML={{
                        __html:(data.aboutCollege)
                    }}></div>
                </div>
                </React.Fragment>
            )
    }

    viewAboutSection = () => {
        this.trackEvent("Click","readMore_about_section")
        this.setState({'showFullAboutSection':true});
    }



    render(){
        const {data} = this.props;
        return(
            <section className='listingTuple' id='Overview'>
                <div className="collegedpts listingTuple">
                    <div className="_container">
                        <div className="_subcontainer">
                            <div className="wikkiContents">
                                {data.aboutCollege && this.getAboutSection()}
                            </div>
                            <div className="facts_table">
                                {this.renderTableHtml()}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        )
    }

}

function mapDispatchToProps(dispatch){
    return bindActionCreators({ storeChildPageDataForPreFilled}, dispatch);
}


export default connect(null,mapDispatchToProps)(KnowledgeBox);

KnowledgeBox.propTypes = {
  config: PropTypes.any,
  data: PropTypes.any,
  fromwhere: PropTypes.any,
  isDesktop: PropTypes.any,
  isMultiLocation: PropTypes.any,
  location: PropTypes.any,
  storeChildPageDataForPreFilled: PropTypes.any
}