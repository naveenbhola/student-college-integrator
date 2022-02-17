import PropTypes from 'prop-types'
import React from 'react';

const CollegePredictorInfoForUser = (props) => {
	return (
		<div className={(props.deviceType == 'mobile') ? 'fltlft box' : 'fltlft'}>
			<p className="infoText">Colleges that come under home state quota are denoted by <span className="quota lead1">Home State</span></p>
		</div>
	);
};
export default CollegePredictorInfoForUser;

CollegePredictorInfoForUser.propTypes = {
  deviceType: PropTypes.string
}