import React from 'react';
import {withFormsy} from 'formsy-react';
import {event} from './../../../../reusable/utils/AnalyticsTracking';
import Lazyload from './../../../../reusable/components/Lazyload';

class CheckBox extends React.Component{
	state = { value: this.props.selectedExams, cmp: (a, b) => a === b };
	componentDidMount = () => {
		this.props.setValue(this.props.selectedExams);
	};

	changeValue = (examId, examName, e) => {
		const checked = e.currentTarget.checked;
		let newValue = [];
		if (checked) {
			newValue = this.state.value.concat(examId);
			event({category : this.props.gaTrackingCategory, action : 'Form_1_'+examName, label : 'select'});
		} else {
			newValue = this.state.value.filter(it => !this.state.cmp(it, examId));
		}

		this.props.setValue(newValue);
		this.setState({ value: newValue });
	};
	render = () => {
		let checkBoxes = [], item;
		this.props.orderedExams.map((examId) => {
			item = this.props.items[examId];
			if(this.props.selectedExams.indexOf(examId) !== -1){

			}
			checkBoxes.push(
				<li key={'exam_'+item.id}>
					<input type="checkbox" className={this.props.className} id={'selectedExam_'+item.id} name={this.props.name} onChange={this.changeValue.bind(this, item.id, item.name)} checked={contains(this.state.value, item.id, this.state.cmp)} />
					<div className="exam-img-cont">
						<label htmlFor={'selectedExam_'+item.id}>
							<i className="select-tick"> </i>
							<div className="img-box">
								<Lazyload offset={10} once><img alt={item.name} src={this.props.deviceType === 'mobile' ? item.mobileLogo : item.desktopLogo} className="img-exam"></img></Lazyload>
							</div>
							<span className="exam-name">{item.name}</span>
						</label>
					</div>
				</li>);
		});
		return checkBoxes;
	};
}
CheckBox.defaultProps = {
	checked : false,
	selectedExams : [],
	className : 'hide'
};
function contains(container, item, cmp) {
	for (const it of container) {
		if (cmp(it, item)) {
			return true;
		}
	}
	return false;
}
export default withFormsy(CheckBox);
