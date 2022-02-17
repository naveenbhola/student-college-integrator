import PropTypes from 'prop-types'
import React from 'react';
import {getFormattedScore} from './../utils/listingCommonUtil';
import EligibilityTemplate from './EligibilityTemplateComponent';
import {nl2br,htmlEntities} from './../../../../utils/stringUtility';
import {makeURLAsHyperlink} from './../../../../utils/urlUtility';

class Eligibility extends React.Component{
	constructor(props)
	{
		super(props);
		this.eligMapping = {'tenthDetails' : '10th','twelthDetails':'12th','graduationDetails':'Graduation','postGraduationDetails':'Post Graduation'};
		this.uniqueAttr = ['minWorkEx','maxWorkEx','minAge','maxAge','internationalDescription','description'];
		this.additionalInfoData = [];

	}
	formatEligibiltyWidgetData()
	{
		this.additionalInfoData = [];
		let categories = Object.keys(this.props.eligibility.categoryNameMapping).sort();
		let categoryWiseData = {};
		categoryWiseData['tableData'] = {};
		var self = this;
		categories.forEach(function(index){
			categoryWiseData['showCategoryDropDown'] = true;
			categoryWiseData['tableData'][index] = {};
			categoryWiseData['tableData'][index]['table'] = {};
			categoryWiseData['tableData'][index]['cutoff_year'] = self.props.eligibility.year != ''? self.props.eligibility.year : '';
		});
		if(categories.length == 0)
		{
			categoryWiseData['tableData']['noneAvailable'] = {};
			categoryWiseData['showCategoryDropDown'] = false;
		}

		if(categories.length == 1 && categories.indexOf('general') > -1)
		{
			categoryWiseData['showCategoryDropDown'] = false;
		}

		for(var i in this.props.eligibility)
		{
			if(this.props.eligibility[i] == null)
				continue;
			if(this.uniqueAttr.indexOf(i) > -1)
			{
				categoryWiseData[i] = this.props.eligibility[i];
				continue;
			}
			var val = this.eligMapping.hasOwnProperty(i);
			if(val)
			{
				this.formatCategoryWise(i,categoryWiseData['tableData']);
			}
			if(i == 'exams')
			{
				this.formatExamEligibility(categoryWiseData['tableData']);
			}
		}
		return categoryWiseData;
	}
	formatExamEligibility(categoryWiseData)
	{
		let categories = Object.keys(this.props.eligibility.categoryNameMapping).sort();
		categories.forEach(function(catName){
			if(typeof categoryWiseData[catName] == 'undefined')
			{
				categoryWiseData[catName] = {};
			}
			if(typeof categoryWiseData[catName]['table'] == 'undefined')
			{
				categoryWiseData[catName]['table'] = {};
			}
			categoryWiseData[catName]['examCutoffData'] = {};
		});
		var self = this;
		for(var eKey in this.props.eligibility['exams'])
		{
			var examName = this.props.eligibility['exams'][eKey]['examName'];
			categories.forEach(function(index){
				categoryWiseData[index]['table'][examName] = {};
				categoryWiseData[index]['table'][examName]['eligibility'] = 'N/A';
				categoryWiseData[index]['table'][examName]['additionalInfo'] = 'N/A';
				categoryWiseData[index]['table'][examName]['cutoff'] = 'N/A';
				categoryWiseData[index]['table'][examName]['url'] = self.props.eligibility['exams'][eKey]['examUrl'];
				categoryWiseData[index]['table'][examName]['qualification'] = examName;
			});
			if(categories.length == 0)
			{
				if(typeof categoryWiseData['noneAvailable']['table'] == 'undefined')
				{
					categoryWiseData['noneAvailable']['table'] = {};
				}
				categoryWiseData['noneAvailable']['table'][examName] = {};
				categoryWiseData['noneAvailable']['table'][examName]['eligibility']    = 'N/A';
				categoryWiseData['noneAvailable']['table'][examName]['additionalInfo'] = 'N/A';
				categoryWiseData['noneAvailable']['table'][examName]['cutoff']         = 'N/A';
				categoryWiseData['noneAvailable']['table'][examName]['url']  = self.props.eligibility['exams'][eKey]['examUrl'];
				categoryWiseData['noneAvailable']['table'][examName]['qualification'] = examName;
			}

			for(var catKey in this.props.eligibility['exams'][eKey].categoryWiseScores)
			{
				if(typeof this.props.eligibility['exams'][eKey].categoryWiseScores[catKey]['score'] != 'undefined' && this.props.eligibility['exams'][eKey].categoryWiseScores[catKey]['score'] != null)
				{
					categoryWiseData[catKey]['showEligibilityVal'] = true;
				}
				categoryWiseData[catKey]['table'][examName]['eligibility'] = getFormattedScore(this.props.eligibility['exams'][eKey].categoryWiseScores[catKey]['score'],this.props.eligibility['exams'][eKey].scoreType,this.props.eligibility['exams'][eKey].categoryWiseScores[catKey]['maxScore']);
			}
			var roundsCutOffData = (this.props.eligibility['exams'][eKey]['cutOffData'] !== null) ? this.props.eligibility['exams'][eKey]['cutOffData']['roundsCutOffData'] : {};
			var cutOffYear = (this.props.eligibility['exams'][eKey]['cutOffData'] !== null) ? this.props.eligibility['exams'][eKey]['cutOffData']['cutOffYear'] : {};

			let relatedStatesStr = [];
			if(this.props.eligibility['exams'][eKey]['cutOffData'] != null)
			{
				for(let states in this.props.eligibility['exams'][eKey]['cutOffData']['relatedStates'])
				{
					relatedStatesStr.push(this.props.eligibility['exams'][eKey]['cutOffData']['relatedStates'][states]);
				}
			}
			if(relatedStatesStr.length > 0)
			{
				var prefixStateString = relatedStatesStr.length > 1 ? 'Related States:': 'Related State:';
				if(typeof this.additionalInfoData[examName] == 'undefined')
					this.additionalInfoData[examName] = [];
				this.additionalInfoData[examName].push(prefixStateString +" "+relatedStatesStr.join(','));
			}

			/*if(Object.keys(roundsCutOffData).length > 1)
			{*/
			// var countFlag ;
			for(var rKey in roundsCutOffData)
			{

				for(var rCatKey in roundsCutOffData[rKey])
				{
					if(typeof categoryWiseData[rCatKey]['examCutoffData'][examName] == 'undefined')
					{
						categoryWiseData[rCatKey]['examCutoffData'][examName]	= {};
					}
					var m = 0;
					categoryWiseData[rCatKey]['table'][examName]['cutoff'] = {};
					let relatedStatesStr = [];
					for(let states in this.props.eligibility['exams'][eKey]['cutOffData']['relatedStates'])
					{
						relatedStatesStr.push(this.props.eligibility['exams'][eKey]['cutOffData']['relatedStates'][states]);
					}
					categoryWiseData[rCatKey]['table'][examName]['relatedStates'] = relatedStatesStr.join(',');

					for(let quotaState in roundsCutOffData[rKey][rCatKey])
					{
						categoryWiseData[rCatKey]['examCutoffData'][examName][rKey] = {};
						categoryWiseData[rCatKey]['examCutoffData'][examName][rKey][quotaState] = getFormattedScore(roundsCutOffData[rKey][rCatKey][quotaState]['score'],this.props.eligibility['exams'][eKey]['cutOffData']['cutOffUnit'],roundsCutOffData[rKey][rCatKey][quotaState]['maxScore']);
						categoryWiseData[rCatKey]['table'][examName]['cutoff'][m] = {};
						categoryWiseData[rCatKey]['table'][examName]['cutoff'][m++] = {'cutoffstr' : categoryWiseData[rCatKey]['examCutoffData'][examName][rKey][quotaState],'round' : rKey,'quota' : quotaState}
						if(typeof categoryWiseData[rCatKey]['table'][examName]['quotaCount'] == 'undefined')
						{
							categoryWiseData[rCatKey]['table'][examName]['quotaCount'] = {};
						}
						if(!categoryWiseData[rCatKey]['table'][examName]['quotaCount'].hasOwnProperty(quotaState)){
							categoryWiseData[rCatKey]['table'][examName]['quotaCount'][quotaState] = 1;
						}
						else {
							++categoryWiseData[rCatKey]['table'][examName]['quotaCount'][quotaState];
						}
						// countFlag = true;
					}
					if(cutOffYear != '')
					{
						categoryWiseData[rCatKey]['cutoff_year'] = cutOffYear;
					}
					categoryWiseData[rCatKey]['showCutOff'] = true;
					// countFlag = true;
				}
			}
		}
	}
	formatCategoryWise(eduKey,categoryWiseData)
	{
		var self = this;
		let categories = Object.keys(this.props.eligibility.categoryNameMapping).sort();
		categories.forEach(function(index){
			if(typeof categoryWiseData[index] == 'undefined')
			{
				categoryWiseData[index] = {};
			}
			if(typeof categoryWiseData[index]['table'] == 'undefined')
			{
				categoryWiseData[index]['table'] = {};
			}
			categoryWiseData[index]['table'][self.eligMapping[eduKey]] = {};
			categoryWiseData[index]['table'][self.eligMapping[eduKey]]['eligibility']    = 'N/A';
			categoryWiseData[index]['table'][self.eligMapping[eduKey]]['additionalInfo'] = 'N/A';
			categoryWiseData[index]['table'][self.eligMapping[eduKey]]['cutoff']         = 'N/A';
			categoryWiseData[index]['table'][self.eligMapping[eduKey]]['type']  = 'section';
			categoryWiseData[index]['table'][self.eligMapping[eduKey]]['qualification'] = self.eligMapping[eduKey];
		});
		if(categories.length == 0)
		{
			if(typeof categoryWiseData['noneAvailable']['table'] == 'undefined')
			{
				categoryWiseData['noneAvailable']['table'] = {};
			}
			categoryWiseData['noneAvailable']['table'][self.eligMapping[eduKey]] = {};
			categoryWiseData['noneAvailable']['table'][self.eligMapping[eduKey]]['eligibility']    = 'N/A';
			categoryWiseData['noneAvailable']['table'][self.eligMapping[eduKey]]['additionalInfo'] = 'N/A';
			categoryWiseData['noneAvailable']['table'][self.eligMapping[eduKey]]['cutoff']         = 'N/A';
			categoryWiseData['noneAvailable']['table'][self.eligMapping[eduKey]]['type']  = 'section';
			categoryWiseData['noneAvailable']['table'][self.eligMapping[eduKey]]['qualification'] = self.eligMapping[eduKey];
		}

		for(var catKey in this.props.eligibility[eduKey].categoryWiseScores)
		{
			if(typeof categoryWiseData[catKey] == 'undefined')
				categoryWiseData[catKey] = {};
			if(typeof categoryWiseData[catKey]['table'] == 'undefined')
			{
				categoryWiseData[catKey]['table'] = {};
			}
			if( this.props.eligibility[eduKey].categoryWiseScores[catKey]['score'] != 'undefined' && this.props.eligibility[eduKey].categoryWiseScores[catKey]['score'] != null)
			{
				categoryWiseData[catKey]['showEligibilityVal']	= true;
			}
			categoryWiseData[catKey]['table'][this.eligMapping[eduKey]]['eligibility'] = getFormattedScore(this.props.eligibility[eduKey].categoryWiseScores[catKey]['score'],this.props.eligibility[eduKey].scoreType,this.props.eligibility[eduKey].categoryWiseScores[catKey]['maxScore']);
		}
		if(this.props.eligibility[eduKey].description != null && this.props.eligibility[eduKey].description.length > 0)
		{
			if(!this.additionalInfoData.hasOwnProperty(this.eligMapping[eduKey]))
				this.additionalInfoData[this.eligMapping[eduKey]] = [];
			this.additionalInfoData[this.eligMapping[eduKey]].push(<span key="first" dangerouslySetInnerHTML={{ __html : nl2br(makeURLAsHyperlink(htmlEntities(this.props.eligibility[eduKey].description)))}}></span>);
		}
		if(typeof this.props.eligibility[eduKey]['subjects'] != 'undefined' && this.props.eligibility[eduKey]['subjects'] != null)
		{
			var subjectsData = this.props.eligibility[eduKey]['subjects'];
			var subjectPrefixHeading = this.props.eligibility[eduKey]['subjects'] != null && this.props.eligibility[eduKey]['subjects'].length > 0 ? 'Mandatory Subjects :' : 'Mandatory Subject :';
			var string = [];
			string.push(<span>{(!self.additionalInfoData.hasOwnProperty(self.eligMapping[eduKey])) ? subjectPrefixHeading+ " " + subjectsData.join(',') : subjectPrefixHeading+ " " + subjectsData.join(',')} <br/> { this.additionalInfoData[this.eligMapping[eduKey]]}</span>);
			this.additionalInfoData[this.eligMapping[eduKey]] = [];
			this.additionalInfoData[this.eligMapping[eduKey]].push(string);
		}
		else if(typeof this.props.eligibility[eduKey]['courseSpecMapping'] != 'undefined' && this.props.eligibility[eduKey]['courseSpecMapping'] != null && this.props.eligibility[eduKey]['courseSpecMapping'].length > 0)
		{
			var mandatoryCourses = this.props.eligibility[eduKey]['courseSpecMapping'];
			var mCoursesArray = [];
			var prefixString = mandatoryCourses.length > 1 ? 'Mandatory Courses:' : 'Mandatory Course:';
			var suffixString = {};
			for(var mCourses in mandatoryCourses)
			{
				if(typeof suffixString[mandatoryCourses[mCourses]['baseCourseName']] == 'undefined')
				{
					suffixString[mandatoryCourses[mCourses]['baseCourseName']] = [];
				}
				if(typeof mandatoryCourses[mCourses]['specializationName'] != 'undefined' && mandatoryCourses[mCourses]['specializationName'] != null && mandatoryCourses[mCourses]['specializationName'].length > 1)
				{
					suffixString[mandatoryCourses[mCourses]['baseCourseName']].push(mandatoryCourses[mCourses]['specializationName']);
				}
			}
			var suffixStringText = '';
			for(var courseName in suffixString)
			{
				suffixStringText += courseName +(suffixString[courseName].length > 0 ? ' ('+suffixString[courseName].join(', ')+' ) ' : '');
			}
			mCoursesArray.push(suffixStringText);
			let string = [];
			string.push(<span>{(this.additionalInfoData[this.eligMapping[eduKey]] == 'N/A') ? prefixString+ " " + mCoursesArray.join(',') : prefixString+ " " + mCoursesArray.join(',') } <br/> {this.additionalInfoData[this.eligMapping[eduKey]]} </span>);
			this.additionalInfoData[this.eligMapping[eduKey]] = [];
			this.additionalInfoData[this.eligMapping[eduKey]].push(string);
		}
		if(this.props.eligibility[eduKey].cutoff != 'undefined')
		{
			for(var cutKey in this.props.eligibility[eduKey].cutoff)
			{
				if(typeof categoryWiseData[cutKey] == 'undefined')
					categoryWiseData[cutKey] = {};
				var cutoffString = [];
				for(var k in this.props.eligibility[eduKey].cutoff[cutKey])
				{
					var keyName = (k == 'cutOff12th') ? '12th' : k ;
					cutoffString.push(<p key={cutKey+k+'-cutoff'}>{keyName} : {this.props.eligibility[eduKey].cutoff[cutKey][k].score}%</p>);
				}
				categoryWiseData[cutKey]['table'][this.eligMapping[eduKey]]['cutoff'] = cutoffString;
				categoryWiseData[cutKey]['showCutOff'] = true;
			}
		}
		/*else
        {
            categoryWiseData[j]['table'][this.eligMapping[eduKey]]['cutoff'] = 'N/A';
        }*/
	}
	render()
	{
		if(typeof this.props.eligibility == 'undefined')
			return null;
		let categoryWiseData =  this.formatEligibiltyWidgetData();
		let categoryNameMapping = this.props.eligibility.categoryNameMapping;
		return (
			<EligibilityTemplate deviceType={this.props.deviceType} acceptingExamMapping={this.props.acceptingExamMapping} examTuples={this.props.examTuples} categoryWiseData={categoryWiseData} additionalInfoData={this.additionalInfoData} categoryNameMapping={categoryNameMapping} predictorInfo= {this.props.predictorInfo} isAmp ={this.props.isAmp}  isPdfGenerator={this.props.isPdfGenerator} />
		)
	}
}

Eligibility.defaultProps = {
	selectedCategoryEligi : 'general',
	deviceType:'mobile'
}

export default Eligibility;

Eligibility.propTypes = {
  acceptingExamMapping: PropTypes.any,
  deviceType: PropTypes.string,
  eligibility: PropTypes.any,
  examTuples: PropTypes.any,
  isAmp: PropTypes.any,
  predictorInfo: PropTypes.any,
  selectedCategoryEligi: PropTypes.string
}