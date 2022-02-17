import PropTypes from 'prop-types'
import React from 'react';
import './../assets/admissionProcess.css';
import './../assets/courseCommon.css';
import ImpDatesWidget from './ImportantDatesComponent';
import FullPageLayer from './../../../common/components/FullPageLayer';
import {cutStringWithShowMore,nl2br,htmlEntities} from './../../../../utils/stringUtility';
import { makeURLAsHyperlink } from './../../../../utils/urlUtility';

class AdmissionProcess extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = {
			layerHeading:'Admission Process',
			layerOpen : false,
			admissionCount:0
		}
		this.closeLayer = this.closeLayer.bind(this);
	}

	formatAdmissionData(viewType='page',admissionProcessCount){

		let admissionData = ((this.props.admissionProcess != 'undefined' && this.props.admissionProcess != null)?this.props.admissionProcess:{});

		//var admissionProcessCount =0;

		var admissionDataCount = Object.keys(admissionData).length;

		//this.state.admissionCount = admissionProcessCount;

		var html = [];
		var html1 = [];
		var countCheck ;
		if(viewType == 'page' && admissionProcessCount > 3){
			countCheck = 2;
		}else{
			countCheck = admissionDataCount - 1;
		}
		var indexInc = 0;
		let admissionProcessText = '';

		for(let index in admissionData)
		{
			if(this.props.isPdfGenerator != null && this.props.isPdfGenerator) {
				admissionProcessText =  admissionData[index]['description'];
			}
			else {
				admissionProcessText =  cutStringWithShowMore(admissionData[index]['description'],160,'admission_data_'+indexInc,'more',true,true);
			}
			html1.push(
				<li key={indexInc}>
					{admissionDataCount>1 && <strong className="blk-text word-wrap">{admissionData[index]['admissionName']}</strong>}
					<input type="checkbox" className="read-more-state hide" id={"admission_data_"+indexInc}/>
					{(viewType == 'page')?<p className='read-more-wrap word-wrap' dangerouslySetInnerHTML={{ __html : admissionProcessText}}></p>:<p className=' word-wrap' dangerouslySetInnerHTML={{ __html : nl2br(makeURLAsHyperlink(htmlEntities(admissionData[index]['description'])))}} ></p>}

				</li>
			);

			if(indexInc==countCheck)
				html.push(<ul key={'ul_'+indexInc} className="flt-list exp-guid">{html1}</ul>);

			if(viewType == 'page' && indexInc == 2)
				break;
			indexInc++;
		}

		return html;
	}

	openAdmissionProcessLayer() {
		this.setState({layerOpen : true});
	}

	closeLayer()
	{
		this.setState({layerOpen : false});
	}

	render()
	{
		const {admissionProcess} = this.props;
		var admissionProcessCount = 0;
		if(typeof admissionProcess != 'undefined' && admissionProcess != null)
		{
			admissionProcessCount = Object.keys(admissionProcess).length;
		}
		let admissionHtml = this.formatAdmissionData('page',admissionProcessCount);
		let layerHtml = this.formatAdmissionData('layer',admissionProcessCount), 
		showCompleteProcessLink = !((this.props.isPdfGenerator != null && this.props.isPdfGenerator) ? this.props.isPdfGenerator : false);;
		var mainHeading ;
		if(this.props.admissionProcess != null && typeof this.props.importantDates != 'undefined' && this.props.importantDates != null  && this.props.importantDates.importantDates != null){
			mainHeading = 'Admissions';
		}
		else if(this.props.admissionProcess != null){
			mainHeading = 'Admission Process';
		}
		else if( typeof this.props.importantDates != 'undefined' && this.props.importantDates != null && this.props.importantDates.importantDates != null){
			mainHeading = 'Important Dates';
		}

		return (
			<section className="admissionBnr listingTuple" id="admissions">
				<div className="_container" id="admissions">
					<h2 className="tbSec2">{mainHeading}</h2>
					<div className='_subcontainer'>
						<div>
							{ typeof this.props.importantDates != 'undefined' && this.props.importantDates != null  && this.props.importantDates.importantDates != null && this.props.admissionProcess != null && <h3>Admission Process</h3>}
							{admissionHtml}
						</div>
						<FullPageLayer desktopTableData={this.props.deviceType == 'desktop' ? true : false} data={layerHtml} heading={this.state.layerHeading} onClose={this.closeLayer} isOpen={this.state.layerOpen}/>

						{showCompleteProcessLink && admissionProcessCount > 3 && <div className='button-container'>
							<a className='button button--secondary arrow' onClick={this.openAdmissionProcessLayer.bind(this)}>View Complete Process </a>
						</div>}
						{ typeof this.props.importantDates != 'undefined' && this.props.importantDates != null  && this.props.importantDates.importantDates != null && <ImpDatesWidget deviceType={this.props.deviceType} importantDates={this.props.importantDates} admissionCount={admissionProcessCount} courseId={this.props.courseId}/>}
					</div>

				</div>
			</section>
		)
	}
}

export default AdmissionProcess;

AdmissionProcess.propTypes = {
	admissionProcess: PropTypes.any,
	courseId: PropTypes.any,
	deviceType: PropTypes.any,
	importantDates: PropTypes.any
}