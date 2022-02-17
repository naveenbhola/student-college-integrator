import React from 'react';
import PropTypes from 'prop-types';

const UserStepsWidget = (props) => {
	return (<div className="col-step fltlft">
			<ul className="step-list">
				<li className={(props.steps > 1) ? "completed" : "active"}>
					<i>1</i>Select Exam
				</li>
				<li className={(props.steps > 2) ? "completed" : ((props.steps < 2) ? "" : "active")}>
					<i>2</i>Enter Score
				</li>
				<li className={(props.steps === 3) ? "active" : ""}>
					<i>3</i>Personal Detail
				</li>
			</ul>
		</div>);
};

export default UserStepsWidget;

UserStepsWidget.propTypes = {
  steps: PropTypes.number
};