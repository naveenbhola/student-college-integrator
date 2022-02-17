import React from 'react';
import PropTypes from 'prop-types';
import ReactForm from 'formsy-react';
import CP_TextBox from './formElements/TextBox';
import {getExamSpecificData} from './../utils/collegePredictorHelper';
import ButtonWidget from './ButtonWidget';

class Step2 extends React.Component{
	constructor(props) {
		super(props);
		this.state = {
			canSubmit : false,
			genericErrorMsg : null
		};
		this.scoreData = {};
	}
	validateForm = (values) => {
		this.setState({genericErrorMsg : null});
		let isValid = false;
		this.scoreData = {};
		for(let examId in values['examScore']){
			if(values['examScore'].hasOwnProperty(examId)){
				this.scoreData[examId] = values['examScore'][examId];
				if(values['examScore'][examId] !== ''){
					isValid = true;
				}
			}
		}
		return isValid;
	};
	submitForm = (data) => {
		if(this.validateForm(data)){
			this.disableButton();
			this.props.nextStep(this.scoreData);
		}else{
			this.setState({genericErrorMsg : 'Enter Rank/Score for atleast one exam'});
		}
	};
	enableButton = () => {
		this.setState({ canSubmit: true });
	};
	disableButton = () => {
		this.setState({ canSubmit: false });
	};
	getExamScoreForm = () => {
		let scoreData = [], value;
		this.props.selectedExams.map((exam)=>{
			value = '';
			if(this.props.examScores[exam] != null){
				value = this.props.examScores[exam];
			}
			scoreData.push(
				<div className="field-input" key={'score-'+exam}>
					<p className="field-name"><strong>{this.props.examDetails[exam]['name']}</strong></p>
					<CP_TextBox
						name={'examScore['+exam+']'}
						placeholder={getExamSpecificData(this.props.examDetails[exam]['cutOffType']).placeholder}
						cutOffType={this.props.examDetails[exam]['cutOffType']}
						validations={{isNumeric:true, isValidInput:{'cutOffType':this.props.examDetails[exam]['cutOffType'], 'examId':exam}}}
						validationErrors={{isNumeric : "Please enter a valid number"}}
						value={value} />
				</div>
			);
		});
		return scoreData;
	};
	render()
	{
		let scoreFields = this.getExamScoreForm();
		return (
			<ReactForm onChange={this.validateForm} onValidSubmit={this.submitForm.bind(this)} onValid={this.enableButton.bind(this)} onInvalid={this.disableButton}>
			<div className="step-enterScore">
				<div className="forms-container">
					<div className="form-heading">
						<h2>Enter Exam Score/Rank </h2>
						<p>Giving your exam rank/score will help us recommend you better colleges and admissions. If you don't have actual rank/score, then enter expected rank/score.</p>
					</div>
					<div className="form-fields">
						{scoreFields}
					</div>
				</div>
				<ButtonWidget deviceType={this.props.deviceType} step={2} isButtonDisabled={!this.state.canSubmit} previousStep={this.props.previousStep} nextBtnText='Next' commonValidationMsg={this.state.genericErrorMsg} />
			</div>
			</ReactForm>
		)
	}
}
export default Step2;

Step2.propTypes = {
  deviceType: PropTypes.string,
  examDetails: PropTypes.object,
  examScores: PropTypes.any,
  nextStep: PropTypes.func,
  previousStep: PropTypes.func,
  selectedExams: PropTypes.array
};