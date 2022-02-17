import React from 'react';
import {withFormsy} from 'formsy-react';
import {event} from './../../../../reusable/utils/AnalyticsTracking';

class CheckBoxGroup extends React.Component{
	constructor(props){
		super(props);
		this.state = {
			value: [],
			cmp: (a, b) => a === b
		};
		this.props.setValue([]);
	};
	componentDidMount = () => {
		this.props.setValue([]);
	};
	componentWillReceiveProps(nextProps, nextContext) {
		if(this.props.currentRating !== nextProps.currentRating){
			this.props.setValue([]);
			this.setState({value : []});
		}
	}

	changeValue = (tagId, tagName, e) => {
		const checked = e.currentTarget.checked;
		let newValue = [];
		if (checked) {
			newValue = this.state.value.concat(tagName);
			//event({category : this.props.gaTrackingCategory, action : 'Form_1_'+tagName, label : 'select'});
		} else {
			newValue = this.state.value.filter(it => !this.state.cmp(it, tagName));
		}

		this.props.setValue(newValue);
		this.setState({ value: newValue });
	};
	render = () => {
		let checkBoxes = [];
		for (let i in this.props.tags){
			if(this.props.tags.hasOwnProperty(i)){
				let tagInfo = this.props.tags[i];
				checkBoxes.push(<li key={'tag-'+i}>
					<input className={this.props.className} type="checkbox" id={"rating-"+tagInfo.id} name={this.props.name} onChange={this.changeValue.bind(this, tagInfo.id, tagInfo.tag)} checked={contains(this.state.value, tagInfo.tag, this.state.cmp)} />
					<label className="capsule" htmlFor={"rating-"+tagInfo.id}>{tagInfo.tag}</label>
				</li>);
			}
		}
		return checkBoxes;
	};
}
CheckBoxGroup.defaultProps = {
	checked : false,
	className : 'hide',
	currentRating : 0
};
function contains(container, item, cmp) {
	for (const it of container) {
		if (cmp(it, item)) {
			return true;
		}
	}
	return false;
}
export default withFormsy(CheckBoxGroup);
