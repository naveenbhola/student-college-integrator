import PropTypes from 'prop-types'
import React from 'react';
import PopupLayer from './../../../common/components/popupLayer';
import AmpLightBox from './../../../common/components/AmpLightbox';
import Analytics from './../../../reusable/utils/AnalyticsTracking';

class CourseEntryChances extends React.Component
{
	constructor(props)
	{
		super(props);
	}
	generatePredictorLayer()
	{
		let html = [];
		const {predictorInfo, isAmp} = this.props;
		if(predictorInfo.length > 1) {
			if(isAmp){
				let layerHtml = predictorInfo.map(function(value,index){
					html.push({layerHtml});
					return <a href={value['url']} key={index}>{value['name']}</a>
				});
			}else{
				let layerHtml = predictorInfo.map(function(value,index){
					return <li key={index}><a href={value['url']}>{value['name']}</a></li>
				});
				html.push(<ul key="ullist" className='ul-list chance-list'>{layerHtml}</ul>);
			}

		}
		return html;
	}

	trackEvent(actionLabel,label)
	{
		const {fromWhere} = this.props;
		if(fromWhere == "admissionPage"){
			Analytics.event({category : 'AdmissionPage_PWA', action : actionLabel, label : label});
		}
		else if(fromWhere == "admissionPageDesktop"){
			Analytics.event({category : 'AdmissionPage_PWA_Desk', action : actionLabel, label : label});
		}
	}

	openPredictorLayer()
	{
		this.trackEvent('Find_Now','Click');
		this.predictorLayer.open();
	}
	renderSinglePredictorInfo()
	{
		let showRankWidget = false;
		const {predictorInfo, isAmp} = this.props;
		if(predictorInfo[0]['name'] == 'JEE-Mains')
		{
			showRankWidget = true;
		}
		if(isAmp){
			return (<a data-vars-event-name="WANT_TO_FINDOUTNOW"  href={predictorInfo[0]['url']} className='btn btn-secondary color-w color-b f14 font-w6 ga-analytic'>{showRankWidget == true ? 'Predict College' : 'Find Now'}</a>)
		}else{

			return (<a href={predictorInfo[0]['url']} onClick={this.trackEvent.bind(this,'CourseEntryChances','Click')} className='button button--secondary ripple dark'>{showRankWidget == true ? 'Predict College' : 'Find Now'}</a>)

		}
	}
	renderRankWidget()
	{
		let showRankWidget = false;
		const {predictorInfo, isAmp} = this.props;
		if(predictorInfo[0]['name'] == 'JEE-Mains')
		{
			showRankWidget = true;
		}
		if(showRankWidget)
		{
			return (
				<React.Fragment>
					{ (isAmp) ? <div className='data-card m-5btm'>
							<div className='card-cmn color-w'>
								<h2 className='f14 color-3 font-w6 m-btm'>Want to find out your chances getting into this College ?</h2>
								<a data-vars-event-name="WANT_TO_FINDOUTNOW" href={predictorInfo[0]['rankPredictorUrl']} className='btn btn-secondary color-w color-b f14 font-w6 ga-analytic'>Predict Rank</a>
							</div>
						</div>
						:
						<div className='find-schlrSec'>
							<div className='find-schlrSec-inr recomended-flex'>
								<p>Want to find out your chances getting into this College</p>
								<a href={predictorInfo[0]['rankPredictorUrl']} onClick={this.trackEvent.bind(this,'Predict_Rank','Click')} className='button button--secondary ripple dark'>Predict Rank</a>

							</div>
						</div>
					}
				</React.Fragment>

			)
		}
		else
			return null;
	}
	render()
	{
		const {predictorInfo,isAmp} = this.props;
		// let showRankWidget = false;
		if(predictorInfo == null || predictorInfo.length == 0)
			return null;
		if(this.props.showRankOnly){
			if(predictorInfo.length == 1 && this.renderRankWidget()){
				return(
					<section className="viewCollegeSec">
						<div className="_container">
							<div className= "_subcontainer _noPad find-schlrSecv1">
								{this.renderRankWidget()}
							</div>
						</div>
					</section>
				);
			}
			else{
				return null;
			}
		}
		if(this.props.showCollegeOnly){
			return(
				<section className="viewCollegeSec">
					<div className="_container">
						<div className= "_subcontainer _noPad find-schlrSecv1">
							<div className='find-schlrSec'>
								<div className='find-schlrSec-inr recomended-flex'>
									<p>Want to find out your chances getting into this College</p>
									{predictorInfo.length > 1 &&
									<React.Fragment><button type="button" className='button button--secondary ripple dark' onClick={this.openPredictorLayer.bind(this)}>Find Now</button><PopupLayer onRef={ref => (this.predictorLayer = ref)} data={this.generatePredictorLayer()} heading={"Predictors"}/> </React.Fragment>}

									{predictorInfo.length == 1 && this.renderSinglePredictorInfo()}
								</div>
							</div>
						</div>
					</div>
				</section>
			);
		}

		if(isAmp){
			return(
				<div className='data-card m-5btm'>
					<div className='card-cmn color-w'>
						<h2 className='f14 color-3 font-w6 m-btm'>Want to find out your chances getting into this College ?</h2>
						{ predictorInfo.length > 1 &&
						<React.Fragment><a  data-vars-event-name="WANT_TO_FINDOUTNOW" on='tap:predictor-dtls' role='button' tabIndex='0' className='btn btn-secondary color-w color-b f14 font-w6 ga-analytic'>Find Out Now</a> <AmpLightBox id='predictor-dtls' onRef={ref => (this.predictorLayer = ref)} data={this.generatePredictorLayer()} heading={"Predictors"}/></React.Fragment>}
						{predictorInfo.length == 1 && this.renderSinglePredictorInfo()}
					</div>
					{predictorInfo.length == 1 && this.renderRankWidget()}
				</div>
			)
		}else{
			return(
				<div className='find-schlrSec'>
					<div className='find-schlrSec-inr'>
						<p>Want to find out your chances getting into this College</p>
						{ predictorInfo.length > 1 &&
						<React.Fragment><button type="button" className='button button--secondary ripple dark' onClick={this.openPredictorLayer.bind(this)}>Find Now</button><PopupLayer onRef={ref => (this.predictorLayer = ref)} data={this.generatePredictorLayer()} heading={"Predictors"}/> </React.Fragment>}
						{predictorInfo.length == 1 && this.renderSinglePredictorInfo()}
					</div>
					{predictorInfo.length == 1 && this.renderRankWidget()}
				</div>
			)
		}
	}
}
CourseEntryChances.defaultProps = {
	isAmp : false
}
export default CourseEntryChances;

CourseEntryChances.propTypes = {
	fromWhere: PropTypes.any,
	isAmp: PropTypes.bool,
	predictorInfo: PropTypes.any,
	showCollegeOnly: PropTypes.any,
	showRankOnly: PropTypes.any
}