import React from 'react';
import './../assets/ExamDateWidget.css';
import PropTypes from 'prop-types';
import WikiContent from '../../common/components/WikiContent';

class ExamDateWidget extends React.Component{
	constructor(props)
	{
		super(props);
	}
	getDateString = (startDate, endDate) => {
		const month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		let myStartDate = new Date(startDate), myEndDate = null;
		if(startDate == endDate){
			return <p>{myStartDate.getDate() + ' ' + month[myStartDate.getMonth()] + " \'" + myStartDate.getFullYear().toString().substr(-2)}</p>;
		}else{
			myEndDate = new Date(endDate);
			return <React.Fragment><p>{myStartDate.getDate() + ' ' + month[myStartDate.getMonth()] + " \'" + myStartDate.getFullYear().toString().substr(-2) + ' - '}</p><p> {myEndDate.getDate() + ' ' + month[myEndDate.getMonth()] + " \'" + myEndDate.getFullYear().toString().substr(-2)}</p></React.Fragment>;
		}
	};
	isEventOnGoing = (startDate, endDate) => {
		let myStartDate = new Date(startDate).getTime();
		let myEndDate = new Date(endDate).getTime();
		let currDate = new Date().getTime();
		if(myStartDate == myEndDate){
			myEndDate += 86399000;
		}
		return (myStartDate <= currDate && currDate <= myEndDate);

	};
	render = () =>	{
		let upcomingEvents = [], pastEvents = [], onGoingHtml = null, cutPastDates = true;
		if(this.props.dates!=null  && this.props.dates.futureDates != null && this.props.dates.futureDates.length > 0){
			upcomingEvents.push(<tr key="head1"><th colSpan="2">UPCOMING EVENTS</th></tr>);
			for(let i in this.props.dates.futureDates){
				let date = this.props.dates.futureDates[i];
				onGoingHtml = null;
				if(this.isEventOnGoing(date.start_date, date.end_date))
				{
					onGoingHtml = <span className="tag ongoing">ONGOING</span>;
				}
				upcomingEvents.push(<tr key={i}><td>{this.getDateString(date.start_date, date.end_date)}</td><td><p>{date.event_name}</p>{onGoingHtml}</td></tr>);
				if(i > 0 && this.props.cutContent === true){
					cutPastDates = false;
					break;
				}
			}
		}
		if(cutPastDates && this.props.dates!=null  && this.props.dates.pastDates != null && this.props.dates.pastDates.length > 0){
			pastEvents.push(<tr key="head2" className="faded"><th colSpan="2">PAST EVENTS</th></tr>);
			for(let i in this.props.dates.pastDates){
				let date = this.props.dates.pastDates[i];
				pastEvents.push(<tr key={i} className="faded"><td>{this.getDateString(date.start_date, date.end_date)}</td><td><p>{date.event_name}</p></td></tr>);
				if(i > 0 && this.props.cutContent === true){
					break;
				}
			}
		}
		return (
			<div className="exam-cstm-wdgt">
				{this.props.defaultText && this.props.defaultText !== '' && <p>{this.props.defaultText}</p>}
				<table className="upcomming-events">
					<tbody>
					{upcomingEvents}
					{pastEvents}
					</tbody>
				</table>
			</div>
		);
	}

}

ExamDateWidget.propTypes = {
	cutContent : PropTypes.bool,
	dates : PropTypes.object

}
export default ExamDateWidget;
