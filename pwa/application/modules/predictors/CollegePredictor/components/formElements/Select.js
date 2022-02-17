import React from 'react';
import {withFormsy} from 'formsy-react';
import {event} from "../../../../reusable/utils/AnalyticsTracking";

class Select extends React.Component{
	constructor(props){
		super(props);
	}
	componentDidMount = () => {
		this.props.setValue(this.props.latestValue);
	};

	handleChange = (e) => {
		event({category : this.props.gaTrackingCategory, action : this.props.gaAction, label : 'select'});
		this.props.setValue(e.currentTarget.value);
	};
	render()
	{
		return(
			<React.Fragment>
				<select className={this.props.className} name={this.props.name} value={this.props.getValue()} onChange={this.handleChange}>{this.props.data}</select>
				<span className='cp-err-msg'>{this.props.getErrorMessage()}</span>
			</React.Fragment>
		);
		
	}
}
Select.defaultProps = {
	value : '',
	gaTrackingCategory : 'College_Predictor_Desktop'
};
export default withFormsy(Select);
