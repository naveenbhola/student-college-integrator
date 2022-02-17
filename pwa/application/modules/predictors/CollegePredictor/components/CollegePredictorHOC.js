import React from 'react';
import UserStepsWidget from './userStepsWidget';
import {showRegistrationFormWrapper} from "../../../../utils/regnHelper";

const CollegePredictorHOC = (MobileComponent, desktopStaticProps) => {
	return class HOC extends React.Component {
		constructor(props)
		{
			super(props);
			this.state = {
				formStep : 1
			};
		}

		manageActiveSteps = (step) => {
			this.setState({formStep : step});
		};

		render() {
			let extraProps = {};
			extraProps.getUserSteps = <UserStepsWidget steps={this.state.formStep} />;
			extraProps.showRegistrationFormWrapper = showRegistrationFormWrapper;
			return <MobileComponent 
				{...this.props} 
				{...desktopStaticProps} 
				{...extraProps}
				manageActiveSteps={this.manageActiveSteps.bind(this)} 
			/>;
		}
	}
};
export default CollegePredictorHOC;