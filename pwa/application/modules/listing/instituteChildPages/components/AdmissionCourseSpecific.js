import PropTypes from 'prop-types'
import React, { Component } from 'react';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {AdmissionPageConstants} from './../../categoryList/config/categoryConfig';
import FullPageLayer from '../../../common/components/FullPageLayer';
import DropDown from '../../../common/components/desktop/DropDown';
import {getRequest} from './../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import {addQueryParams,pushNewUrl} from './../../../../utils/commonHelper';


class AdmissionCourseSpecific extends Component
{
	constructor(props)
	{
		super(props);
		if(this.props.fromWhere && this.props.fromWhere === 'admissionPage'){
			this.config = AdmissionPageConstants();
		}
		this.state = {
			layerOpenStream :false,
			layerOpenCourse:false,
			check:true,
			courseCollegeGroup: this.props.courseCollegeGroup
		};
		this.scrollY = 0;

	}

	componentDidMount(){
		let self = this;
		if(this.props.isDesktop){
			document.addEventListener("click", function(e){
				if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('click-cls-st') < 0)){
					self.closeStreamLayer();
				}
				if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('click-cls-course') < 0)){
					self.closeCourseLayer();
				}
			});

		}
	}

	trackEvent(action)
	{

		Analytics.event({category : this.props.gaTrackingCategory, action : action, label : 'click'});
	}

	handleClickOnStream() {
		if(this.props.isDesktop){
			this.scrollY = 0;
		}

		this.trackEvent('Course_Specific');
		if(!this.props.courseSpecificData){
			this.props.clickHandlerForCourseData();
		}
		this.setState({layerOpenStream:!this.state.layerOpenStream});
	}
	handleClickOnCourse() {
		if(this.props.isDesktop){
			this.scrollY = 0;
		}
		this.trackEvent('Course_Specific');
		if(!this.props.courseSpecificData){
			this.props.clickHandlerForCourseData();
		}
		this.setState({layerOpenCourse:!this.state.layerOpenCourse,courseCollegeGroup: this.props.courseCollegeGroup});
	}

	closeStreamLayer(){
		this.setState({layerOpenStream : false});

	}
	closeCourseLayer(){
		this.setState({layerOpenCourse:false });
	}

	renderSearchTemplate()
	{
		const {placeHolderText} = this.props;
		return (<div className="pwa-lyrSrc click-cls-course" key="search-tab">
			<i className='search-ico sddsd click-cls-course'></i>
			<input className="click-cls-course" type="text" placeholder={placeHolderText} ref={(input) => this.inputText = input} onChange={this.getInputValue.bind(this)}/>
		</div>);
	}

	getInputValue()
	{
		let searchText = this.inputText.value.trim();
		this.filterDisplayList(searchText);
	}

	filterDisplayList(searchText)
	{
		Object.filter = (obj, searchText) => Object.keys(obj).map( function(key) {
			return obj[key].filter(function(n){
				return n.name.toLowerCase().indexOf(searchText.toLowerCase()) !== -1;
			})
		});
		const {courseCollegeGroup}  = this.props;

		var filtered = Object.filter(courseCollegeGroup, searchText);
		var finalResult = {};
		var keyNames = Object.keys(courseCollegeGroup);
		for(var i in filtered)
		{
			if(filtered[i].length > 0)
				finalResult[keyNames[i]] = filtered[i];
		}
		this.setState({'courseCollegeGroup':finalResult});
	}



	generateStreamsHtml(){
		const {streamObjs} = this.props;
		let pageHtml=[];
		let html =[];
		let eleindex = 0;
		if(!streamObjs || streamObjs.length === 0){
			return (<div className="noResult-found"> Data is Loading</div>);
		}
		var self = this;
		for (var index in streamObjs){
			var params={};
			params.streamId = streamObjs[index].streamId;
			html.push(<li className="rippleefect click-cls-st" key={'Streams'+(eleindex++)} onClick={self.getData.bind(this,params)}>{streamObjs[index]['name']}</li>);
		}
		pageHtml.push(<ul key={'main-li'+(eleindex++)} className='pad10-l click-cls-st'>{html}</ul>);
		return pageHtml;
	}

	generateCourseHtml(){
		const {collegeOrdering} = this.props;
		const {courseCollegeGroup}  = this.state;
		if(!courseCollegeGroup){
			return (<div className="noResult-found click-cls-course"> Data is Loading</div>);
		}
		let pageHtml = [];
		var self = this;
		let eleindex = 0;
		for(var index in collegeOrdering){
			let innerHtml = [];
			let collegegrounp = [];
			let collegeName =[];
			collegeName.push(<strong className='ListHeading click-cls-course' key={'head'+(eleindex++)}>{collegeOrdering[index]}</strong>) ;
			for(var courseIndex in courseCollegeGroup[collegeOrdering[index]]){
				var params={};
				params.courseId = courseCollegeGroup[collegeOrdering[index]][courseIndex].courseId;
				innerHtml.push(<li className="rippleefect click-cls-course" key={'stream'+(eleindex++)} onClick={self.getData.bind(this,params)}>{courseCollegeGroup[collegeOrdering[index]][courseIndex]['name']}</li>);
			}
			if(innerHtml.length){
				collegegrounp.push(<ul key={'ul-ele'+(eleindex++)} className='pad10-l click-cls-course'>{innerHtml}</ul>);
				pageHtml.push(<div className='click-cls-course' key={'main-div'+(eleindex++)}>{collegeName}{collegegrounp}</div>);
			}
		}
		if(pageHtml.length){
			return pageHtml;
		}
		else{
			return (
				<div className="noResult-found click-cls-course">
					Sorry, no Course Found
				</div>
			)
		}
	}


	getData(apiData){
		let url = APIConfig.GET_ADMISSION_STREAM_COURSE_DATA;
		let param='';
		param ='?instituteId='+this.props.data.listingId;
		let pageType ='';
		if(apiData.streamId){
			pageType  = 'Stream';
			param +='&streamId='+apiData.streamId;
			if(window.location){
				let newUrl=window.location.protocol + "//" + window.location.host + window.location.pathname+'?streamId='+apiData.streamId;
				pushNewUrl(newUrl);
			}
			this.trackEvent('Course_Specific_Stream');
		}
		if(apiData.courseId){
			pageType  ='Course';
			param +='&courseId='+apiData.courseId;
			param +='&streamId='+this.props.selectedStreamId;
			let newUrl = addQueryParams('courseId='+apiData.courseId);
			newUrl = addQueryParams('streamId='+this.props.selectedStreamId,newUrl);
			pushNewUrl(newUrl);
			this.trackEvent('Course_Specific_Course');
		}
		var self = this;
		if(this.props.isDesktop){
			document.getElementById('courseSpecificLoder').style.display = "block";
		}
		getRequest(url+param).then((response) => {
			if(this.props.isDesktop){
				document.getElementById('courseSpecificLoder').style.display = "none";
			}
			if(pageType === 'Stream'){
				if(!this.props.isDesktop){
					PreventScrolling.enableScrolling(true);
				}
				self.closeStreamLayer();
			}
			else{
				if(!this.props.isDesktop){
					PreventScrolling.enableScrolling(true);
				}
				self.closeCourseLayer();
			}
			if(response){
				self.props.clickHandler(response.data.data);
			}
		});

	}


	render(){
		let searchHtml = this.renderSearchTemplate();
		return (
			<React.Fragment>
				<section id="couses_Section">
					<div className="_container">
						<h2 className="tbSec2">Admission Process for Specific Courses</h2>
						<div className="_subcontainer">
							<p className="processTxt">To find out information on eligibility, process and important dates, select a course</p>
							<div className="pwa_mulitslct">
								<div className="dropdownlabel">
									<div className="fieldwraper">
										<div className="txt_placeholder">Stream</div>
										<a className="slctd_data click-cls-st" onClick={this.handleClickOnStream.bind(this)}>{this.props.streamName}</a>
									</div>
									{this.props.isDesktop ?
										(<DropDown data={this.generateStreamsHtml()} isOpen={this.state.layerOpenStream} />)
										:(<FullPageLayer data={this.generateStreamsHtml()}  heading={'Select Stream'} onClose={this.closeStreamLayer.bind(this)} isOpen={this.state.layerOpenStream} />)
									}
								</div>
								<div className="dropdownlabel">
									<div className="fieldwraper">
										<div className="txt_placeholder">Course</div>
										<a className="slctd_data click-cls-course" onClick={this.handleClickOnCourse.bind(this)}>{this.props.courseName}</a>
									</div>
									{this.props.isDesktop ?
										(<DropDown data={this.generateCourseHtml()} isOpen={this.state.layerOpenCourse} isSearchExist = {true} searchHtml = {searchHtml} /> )
										: (<FullPageLayer data={this.generateCourseHtml()}  heading={'Select Course'} onClose={this.closeCourseLayer.bind(this)} isOpen={this.state.layerOpenCourse} isSearchExist = {true} searchHtml = {searchHtml} />)
									}
								</div>
							</div>
						</div>
					</div>
				</section>
			</React.Fragment>
		)
	}
}
export default AdmissionCourseSpecific;

AdmissionCourseSpecific.propTypes = {
  clickHandler: PropTypes.any,
  clickHandlerForCourseData: PropTypes.any,
  collegeOrdering: PropTypes.any,
  courseCollegeGroup: PropTypes.any,
  courseName: PropTypes.any,
  courseSpecificData: PropTypes.any,
  data: PropTypes.any,
  fromWhere: PropTypes.any,
  gaTrackingCategory: PropTypes.any,
  isDesktop: PropTypes.any,
  placeHolderText: PropTypes.any,
  selectedStreamId: PropTypes.any,
  streamName: PropTypes.any,
  streamObjs: PropTypes.any
};