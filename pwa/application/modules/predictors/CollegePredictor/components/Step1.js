import React from 'react';
import PropTypes from 'prop-types';
import ReactForm from 'formsy-react';
import CP_CheckBoxGroup from './formElements/CheckBox';
import ButtonWidget from './ButtonWidget';

class Step1 extends React.Component{
	constructor(props){
		super(props);
		this.state = {
			canSubmit : false,
			genericErrorMsg : null
		};
		this.selectedExams = [];
	}
	getExamsList = () => {
		return <CP_CheckBoxGroup className='selectedExam' name={'selectedExam'} idLabel={'selectedExam_'} items={this.props.examList} orderedExams={this.props.orderedExams} deviceType={this.props.deviceType} selectedExams={this.props.selectedExams} gaTrackingCategory={this.props.gaTrackingCategory} />;
	};
	submitForm = (values) => {
		if(this.validateForm(values)){
			this.disableButton();
			this.props.nextStep(this.selectedExams);
		}else{
			this.setState({genericErrorMsg : 'Please select atleast one exam'});
		}
	};
	validateForm = (values) => {
		this.setState({genericErrorMsg : null});
		this.selectedExams = [];
		if(typeof values['selectedExam'] !== 'undefined'){
			values['selectedExam'].map(examId => {
				this.selectedExams.push(parseInt(examId));
			});
		}
		return (this.selectedExams.length > 0);

	};
	enableButton = () => {
		this.setState({ canSubmit: true });
	};
	disableButton = () => {
		this.setState({ canSubmit: false });
	};
	render()
	{
		return (
			<ReactForm onChange={this.validateForm.bind(this)} onValidSubmit={this.submitForm.bind(this)} onValid={this.enableButton.bind(this)} onInvalid={this.disableButton.bind(this)}>
			<div className="step-selectExam">
				<div className="forms-container">
					<div className="form-heading">
						<h2>Select exams you have taken</h2>
					</div>
					<div className="form-fields">
						<div className="form-field">
							<ul className="exam-selection-list">
								{this.getExamsList()}
							</ul>
						</div>
					</div>
				</div>
				<ButtonWidget deviceType={this.props.deviceType} step={1} isButtonDisabled={!this.state.canSubmit} nextBtnText='Next' commonValidationMsg={this.state.genericErrorMsg} />
			</div>
			</ReactForm>
		)
	}
}
export default Step1;
Step1.defaultProps = {
  deviceType: 'mobile',
  selectedExam: []
};
Step1.propTypes = {
  deviceType: PropTypes.string.isRequired,
  examList: PropTypes.object.isRequired,
  nextStep: PropTypes.func.isRequired,
  orderedExams: PropTypes.array,
  selectedExams: PropTypes.array
};