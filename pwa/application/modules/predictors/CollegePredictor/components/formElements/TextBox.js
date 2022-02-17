import React from 'react';
import {withFormsy, addValidationRule} from 'formsy-react';

addValidationRule('isValidInput', (values, value, validationInputs) => {
	if(validationInputs['cutOffType'] === 'score' && validationInputs['examId'] === 304){
		return value > 400 ? 'Score should be less than or equal to 400' : true;
	}else if(validationInputs['cutOffType'] === 'score' && validationInputs['examId'] === 9247){
		return value > 200 ? 'Score should be less than or equal to 200' : true;
	}else if(validationInputs['cutOffType'] === 'percentile') {
		return value > 100 && value < 0 ? 'Percentile to be between 0 to 100' : true;
	}else{
		return value !== '' && value <= 0 ? 'Rank should be greater than 0' : true;
	}
});
class TextBox extends React.Component{
	constructor(props){
		super(props);
	}
	changeValue = (event) => {
		this.props.setValue(event.currentTarget.value);
	};
	render()
	{
		return(
			<React.Fragment>
				<input readOnly={this.props.readOnly} maxLength='9' autoComplete='off' type="text" name={this.props.name} className={this.props.className} placeholder={this.props.placeholder} value={this.props.getValue() || ''} onChange={this.changeValue} />
				<span className='cp-err-msg'>{this.props.getErrorMessage()}</span>
			</React.Fragment>
		);
		
	}
}
TextBox.defaultProps = {
	placeholder : 'Score',
	value : '',
	readOnly : false
};
export default withFormsy(TextBox);
