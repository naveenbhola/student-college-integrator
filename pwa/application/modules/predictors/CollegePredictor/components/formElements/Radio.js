import React from 'react';
import {withFormsy} from 'formsy-react';

class Radio extends React.Component{
	state = {};
	constructor(props){
		super(props);
	}
	componentDidMount() {
		const value = this.props.value;
		this.props.setValue(value);
		this.setState({ value });
	}
	handleChange = (value) => {
		this.props.setValue(value);
		this.setState({ value });
		if(this.props.form3Data){
			let form3Data = this.props.form3Data();
			form3Data.gender = value;
			this.props.onChangeAction && this.props.onChangeAction(form3Data);
		}
	};
	render()
	{
		const className = 'form-group' + (this.props.showRequired() ? ' required' : this.props.showError() ? ' error' : '');
		const { name, items } = this.props;
		return (
			<div className={className}>
				{items.map((item, i) => (
					<div key={i} className={this.props.className}>
						<input
							className={this.props.radioClass}
							type="radio"
							name={name}
							id={'gen-'+item.id}
							onChange={this.handleChange.bind(this, item.id)}
							checked={this.state.value === item.id}
						/>
						<label htmlFor={'gen-'+item.id} className="expected-label">{item.fullName.toString()}</label>
					</div>
				))
				}
				{/*<span className='cp-err-msg'>{this.props.getErrorMessage()}</span>*/}
			</div>
		);

	}
}
export default withFormsy(Radio);
