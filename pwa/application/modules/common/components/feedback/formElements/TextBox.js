import React from 'react';
import {withFormsy} from 'formsy-react';

class TextBox extends React.Component{
	constructor(props){
		super(props);
		this.props.setValue(this.props.value);
	}
	changeValue = (event) => {
		//TODO
		console.log('TODO');
		this.props.setValue(event.currentTarget.value);
	};
	render()
	{
		return<input maxLength='1' type="text" name={this.props.name} className={this.props.className} value={this.props.getValue() || ''} onChange={this.changeValue} />;
		
	}
}
TextBox.defaultProps = {
	value : ''
};
export default withFormsy(TextBox);