import PropTypes from 'prop-types'
import React from 'react';
import PopupLayer from './../../../common/components/popupLayer';
import './../assets/seatsBreakup.css';
import './../assets/courseCommon.css';

class SeatsComponent extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = {
			seatsData: {},
		}

	}

	formatSeatsData(){
		let categoryWiseData = this.props.seatsData.categoryWiseSeats != null ? this.props.seatsData.categoryWiseSeats : [];
		let examWiseData = this.props.seatsData.examWiseSeats != null ? this.props.seatsData.examWiseSeats : [];
		let domicileWiseData = this.props.seatsData.domicileWiseSeats != null ? this.props.seatsData.domicileWiseSeats : [];
		var html = [];
		var html1 = [];
		var html2 = [];
		var html3 = [];
		var categoryMoreHtml = [];
		var examMoreHtml = [];
		var domicileMoreHtml = [];
		var categoryBreakup = 0;
		var examBreakup = 0;
		var domicileBreakup = 0;
		var maxDisplayBreakup = 5;
		for(let index in categoryWiseData)
		{
			categoryBreakup++;
			html1.push(<li key={"cate_"+index} className={index >= maxDisplayBreakup ? 'read-more-target' : ''}><span>{categoryWiseData[index]['category']}</span>
				<span className="count-spn">{categoryWiseData[index]['seats']}</span></li>);


			if(index==(categoryWiseData.length - 1)){
				if(categoryBreakup > maxDisplayBreakup)
					categoryMoreHtml.push(<div className="read-more-trigger" key={"divcat_"+index}><label htmlFor="catBreakup-more" className="vw-allLink">View All<i className="blu-arrw rotate"></i></label></div>);
				html.push(<div className="tab" key={"tabcat_"+index}><input id="tab-1" type="checkbox" name="tabs"/><label htmlFor="tab-1" className="tab-label">Category</label><div className="tab-content"><input type="checkbox" className="read-more-state hide"  id="catBreakup-more"/><ul className="nrml-list read-more-wrap">{html1}</ul>{categoryMoreHtml}</div></div>);
			}
		}

		for(let index in examWiseData)
		{
			examBreakup++;
			html2.push(<li key={"exam_"+index} className={index >= maxDisplayBreakup ? 'read-more-target' : ''}><span>{examWiseData[index]['exam']}</span><span  className="count-spn">{examWiseData[index]['seats']}</span></li>);


			if(index==(examWiseData.length - 1)){
				if(examBreakup > maxDisplayBreakup)
					examMoreHtml.push(<div className="read-more-trigger" key={"divexm_"+index}><label htmlFor="examBreakup-more" className="vw-allLink">View All</label></div>);
				html.push(<div className="tab" key={"tabexm_"+index}><input id="tab-2" type="checkbox" name="tabs"/><label htmlFor="tab-2" className="tab-label">Entrance Exam</label><div className="tab-content"><input type="checkbox" className="read-more-state hide"  id="examBreakup-more"/><ul className="nrml-list read-more-wrap">{html2}</ul>{examMoreHtml}</div></div>);
			}

		}

		for(let index in domicileWiseData)
		{
			domicileBreakup++;
			html3.push(<li key={"dom_"+index} className={index >= maxDisplayBreakup ? 'read-more-target' : ''}><span>{domicileWiseData[index]['category']}</span><span  className="count-spn">{domicileWiseData[index]['seats']}</span></li>);

			if(index==domicileWiseData.length - 1){
				if(domicileBreakup > maxDisplayBreakup)
					domicileMoreHtml.push(<div className="read-more-trigger" key={'layerDiv_'+index}><label htmlFor="domicileBreakup-more" className="vw-allLink">View All</label></div>);

				html.push(<div className="tab" key={"domicileDiv_"+index}><input id="tab-3" type="checkbox" name="tabs"/><label htmlFor="tab-3" className="tab-label">Domicile{(this.props.seatsData.relatedStates !== null) && <i key={'domicile_icon'} className="info-icon" onClick={this.openHelpLayer.bind(this)}></i>}</label><div className="tab-content"><input type="checkbox" className="read-more-state hide" id="domicileBreakup-more"/><ul className="nrml-list read-more-wrap">{html3}</ul>{domicileMoreHtml}</div></div>);
			}
		}

		return html;
	}

	openHelpLayer(event) {
		this.helpLayer.open();
		event.preventDefault();
	}

	render()
	{

		const {seatsData} = this.props;

		if(!((seatsData.totalSeats > 0) || (seatsData.categoryWiseSeats != null && Array.isArray(seatsData.categoryWiseSeats) && seatsData.categoryWiseSeats.length > 0) || (seatsData.examWiseSeats != null && Array.isArray(seatsData.examWiseSeats) && seatsData.examWiseSeats.length > 0) || (seatsData.domicileWiseSeats != null && Array.isArray(seatsData.domicileWiseSeats) && seatsData.domicileWiseSeats.length > 0) || (seatsData.relatedStates != null && Array.isArray(seatsData.relatedStates) && seatsData.relatedStates.length > 0)))
			return null;


		let seatsBreakupHtml = this.formatSeatsData();
		let totalSeats = this.props.seatsData.totalSeats;
		var domicileHelpText = (this.props.seatsData.relatedStates !== null) ? this.props.seatsData.relatedStates : '';
		var domicileHelpTextArr = [];

		domicileHelpTextArr.push(<p className="help_p" key="help">{domicileHelpText}</p>)
		return (
			<section className='seatBnr listingTuple' id="seats">
				<div className='_container'>
					<h2 className='tbSec2'>Seat Break-up</h2>
					<div className='_subcontainer'>
						{ totalSeats > 0 && <p className='seat-head'><strong>{totalSeats}</strong> Total Seats</p> }
						<div className="seat-info">
							{seatsBreakupHtml}
						</div>
					</div>
					{domicileHelpTextArr != '' && <PopupLayer onRef={ref => (this.helpLayer = ref)} data={domicileHelpTextArr} heading='Domicile'/>}
				</div>
			</section>

		)
	}
}

export default SeatsComponent;

SeatsComponent.propTypes = {
	seatsData: PropTypes.any
}