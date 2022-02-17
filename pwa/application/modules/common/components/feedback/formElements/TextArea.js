import React from 'react';
import {withFormsy} from 'formsy-react';

class TextArea extends React.Component{
	constructor(props){
		super(props);
		this.props.setValue(this.props.value);
	}
	changeValue = (event) => {
		this.props.setValue(event.currentTarget.value);
	};
	componentWillReceiveProps(nextProps, nextContext) {
		if(nextProps.rating !== this.props.rating){
			this.props.setValue('');
		}
	}

	render()
	{
		return <textarea rows={this.props.rows} cols={this.props.cols} name={this.props.name} className={this.props.className} maxLength={this.props.maxLength} onChange={this.changeValue} value={this.props.getValue() || ''} placeholder={this.props.placeholder} />;
		
	}
}
TextArea.defaultProps = {
	value : '',
	rows : 5,
	cols : 40,
	maxLength : 200,
	placeholder : 'Optional'
};
export default withFormsy(TextArea);