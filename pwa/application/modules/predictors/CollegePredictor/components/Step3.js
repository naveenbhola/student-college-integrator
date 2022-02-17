import React from 'react';
import PropTypes from 'prop-types';
import ReactForm from 'formsy-react';
import CP_RadioGroup from './formElements/Radio';
import CP_Select from './formElements/Select';
import CP_TextBox from './formElements/TextBox';
import FullPageLayer from "../../../common/components/FullPageLayer";
import PreventScrolling from "../../../reusable/utils/PreventScrolling";
import ButtonWidget from './ButtonWidget';
import {event} from './../../../reusable/utils/AnalyticsTracking';

class Step3 extends React.Component{
	constructor(props)
	{
		super(props);
		this.state = {
			canSubmit : false,
			validationErrors : {},
			errMsg : null,
			layerOpenCategory : false,
			layerOpenDomState : false,
			categoryValue : ['1', 'General'],
			DomStateValue : ['', '']
		};
	}
	getForm3Data = () => {
		return this.refs.form.getModel();
	};
	getGenderForm = () => {
		let genderValue = '';
		if(this.props.userPersonalData && this.props.userPersonalData.gender){
			genderValue = this.props.userPersonalData.gender;
		}
		let genderFields = <CP_RadioGroup
			className='field-input'
			radioClass='hide selectedGender'
			onChangeAction={this.validateForm}
			form3Data={this.getForm3Data}
			name={'gender'}
			value={genderValue}
			items={this.props.categoriesData.gender}
		 />;
		return (
			<div className="form-field gender-field">
				<p className="field-name"><strong>Gender</strong></p>
				{genderFields}
			</div>
		);
	};
	closeCategoryLayer = () => {
		this.setState({layerOpenCategory : false});
	};
	closeDomStateLayer = () => {
		this.setState({layerOpenDomState : false});
	};
	closeExamSpecificCategoryLayer = (examId) => {
		let state = {};
		state['layerOpenExamSpecificCategory_'+examId] = false;
		this.setState(state);
	};

	handleCategoryClick = () => {
		this.setState({layerOpenCategory : true});
	};
	handleDomStateClick = () => {
		this.setState({layerOpenDomState : true});
	};
	handleExamSpecificCategoryLayerClick = (examId) => {
		let state = {};
		state['layerOpenExamSpecificCategory_'+examId] = true;
		this.setState(state);
	};

	changeCategoryValue = (value, name) => {
		PreventScrolling.enableScrolling(true);
		event({category : this.props.gaTrackingCategory, action : 'Form_3_Category', label : 'select'});
		this.setState({categoryValue: [value, name], layerOpenCategory : false});
	};
	changeDomStateValue = (value, name) => {
		PreventScrolling.enableScrolling(true);
		event({category : this.props.gaTrackingCategory, action : 'Form_3_State', label : 'select'});
		this.setState({DomStateValue: [value, name], layerOpenDomState : false});
	};
	changeExamSpecificCategoryValue = (value, name, examId, examName) => {
		PreventScrolling.enableScrolling(true);
		event({category : this.props.gaTrackingCategory, action : 'Form_3_'+examName+'_category', label : 'select'});
		let state = {};
		state['examSpecificCategoryValue_'+examId] = [value, name];
		state['layerOpenExamSpecificCategory_'+examId] = false;
		this.setState(state);
	};

	getCategoryForm = () => {
		let categoryFields = [], categoryFieldsDesk = [];
		this.props.categoriesData.mainCategory.map((category,i)=>{
			categoryFields.push(<li key={'tmp-cat'+i} onClick={this.changeCategoryValue.bind(this, category.id, category.fullName)}>{category.fullName}</li>);
			categoryFieldsDesk.push(<option key={'tmp-cat'+i} value={category.id}>{category.fullName}</option>);
		});
		return (
			<div className="field-input">
				<p className="field-name"><strong>Category</strong></p>
				{this.props.deviceType === 'mobile' ? <React.Fragment><FullPageLayer data={<ul className='option-list'>{categoryFields}</ul>} heading={'Select Category'} onClose={this.closeCategoryLayer} isOpen={this.state.layerOpenCategory} />
					<input placeholder="Select Category" readOnly={true} onClick={this.handleCategoryClick.bind(this)} className="input select" value={this.state.categoryValue[1]}/>
					<i className='dark-arow'> </i>
					<div className="input-box select-box" style={{display:'none'}}>
					<CP_TextBox	name={'userCategory'} readOnly={true} value={this.state.categoryValue[0]} />
					</div></React.Fragment> : <div className="input-box select-box">
					<CP_Select
						name={'userCategory'}
						latestValue={this.state.categoryValue[0]}
						className="select input"
						gaTrackingCategory={this.props.gaTrackingCategory}
						gaAction={'Form_3_Category'}
						data={categoryFieldsDesk} />
				</div>}
			</div>
		);
	};
	getDomicileStateForm = () => {
		let domStFields = [], domStFieldsDesk = [];
		domStFieldsDesk.push(<option key={'ds-default'} value=''>Choose Domicile State</option>);
		this.props.domicileStates.map((state,i)=>{
			domStFields.push(<li key={'ds'+i} onClick={this.changeDomStateValue.bind(this, state.state_id, state.state_name)}>{state.state_name}</li>);
			domStFieldsDesk.push(<option key={'dsd'+i} value={state.state_id}>{state.state_name}</option>);
		});
		return (
			<div className="field-input">
				<p className="field-name"><strong>Domicile State</strong></p>
				{this.props.deviceType === 'mobile' ? <React.Fragment><FullPageLayer data={<ul className='option-list'>{domStFields}</ul>} heading={'Choose Domicile State'} onClose={this.closeDomStateLayer} isOpen={this.state.layerOpenDomState} />
					<input placeholder="Choose Domicile State" readOnly={true} onClick={this.handleDomStateClick} className="input select" value={this.state.DomStateValue[1]}/>
					<i className='dark-arow'> </i>
					<div className="input-box select-box" style={{display:'none'}}>
						<CP_TextBox	name={'userDomState'} readOnly={true} value={this.state.DomStateValue[0]} />
					</div></React.Fragment> : <div className="input-box select-box">
						<CP_Select name={'userDomState'} defaultValue={''} className="select input" data={domStFieldsDesk} gaTrackingCategory={this.props.gaTrackingCategory} gaAction={'Form_3_State'} />
					</div>}

			</div>
		);
	};
	getExamSpecificCategories = () => {
		let examCategories = [], categoryOptions, examCat, categoryOptionsDesk;
		for(let examId in this.props.categoriesData.examSpecificCategories){
			if(this.props.categoriesData.examSpecificCategories.hasOwnProperty(examId)){
				categoryOptions = [];
				categoryOptionsDesk = [];
				examCat = this.props.categoriesData.examSpecificCategories[examId];
				categoryOptionsDesk.push(<option key={'catOpt-default'+examId} value=''>Choose Category</option>);
				examCat.categories.map((categories,j)=>{
					categoryOptions.push(<li key={'catOpt-'+examId+'-'+j} onClick={this.changeExamSpecificCategoryValue.bind(this, categories.id, categories.fullName, examId, examCat.examName)}>{categories.fullName}</li>);
					categoryOptionsDesk.push(<option key={'catOptDesk-'+examId+'-'+j} value={categories.id}>{categories.fullName}</option>);
				});
				if(this.props.examScores[parseInt(examId)] === ''){
					continue;
				}
				examCategories.push(
					<div className="field-input" key={'dd-'+examId}>
						<p className="field-name"><strong>Select sub-category for {examCat.examName}</strong></p>
						{this.props.deviceType === 'mobile' ? <React.Fragment><FullPageLayer data={<ul className='option-list'>{categoryOptions}</ul>} heading={'Choose Category'} onClose={this.closeExamSpecificCategoryLayer.bind(this,examId)} isOpen={this.state['layerOpenExamSpecificCategory_'+examId]} /> <input placeholder="Choose Category" readOnly={true} onClick={this.handleExamSpecificCategoryLayerClick.bind(this,examId)} className="input select" value={this.state['examSpecificCategoryValue_'+examId] ? this.state['examSpecificCategoryValue_'+examId][1] : ''}/>
							<i className='dark-arow'> </i>
							<div className="input-box select-box" style={{display:'none'}}>
							<CP_TextBox	name={'userExamSpcCategory['+examId+']'} readOnly={true} value={this.state['examSpecificCategoryValue_'+examId] ? this.state['examSpecificCategoryValue_'+examId][0] : ''} />
							</div></React.Fragment> : <div className="input-box select-box">
							<CP_Select name={'userExamSpcCategory['+examId+']'} defaultValue={''} className="select input" data={categoryOptionsDesk} gaTrackingCategory={this.props.gaTrackingCategory} gaAction={'Form_3_'+examCat.examName+'_category'} />
						</div>}
					</div>
				);
			}
		}
		return examCategories;
	};

	enableButton = () => {
		this.setState({ canSubmit: true });
	};
	disableButton = () => {
		this.setState({ canSubmit: false });
	};
	submitForm = (data) => {
		if(this.validateForm(data)){
			let personalData = {};
			personalData.gender = data.gender;
			personalData['userExamSpcCategory'] = {};
			for(let examId in data['userExamSpcCategory']){
				if(data['userExamSpcCategory'].hasOwnProperty(examId)){
					personalData['userExamSpcCategory'][examId] = data['userExamSpcCategory'][examId];
				}
			}
			personalData['userCategory'] = data['userCategory'];
			personalData['userDomState'] = data['userDomState'];
			this.props.nextStep(personalData);
		}
	};
	validateForm = (values) => {
		let isValid = true;
		if (values.gender === '') {
			isValid = false;
			this.setState({validationErrors : {gender : 'Please Select Gender'}, errMsg : 'Please Select Gender'});
		} else {
			isValid = true;
			event({category : this.props.gaTrackingCategory, action : 'Form_3_Gender', label : 'select'});
			this.setState({validationErrors : {}, errMsg : null});
		}
		return isValid;
	};
	render()
	{
		return (
			<ReactForm ref="form" onValidSubmit={this.submitForm.bind(this)} onValid={this.enableButton.bind(this)} onInvalid={this.disableButton.bind(this)} validationErrors={this.state.validationErrors}>
			<div className="step-personalDetail">
				<div className="forms-container">
					<div className="form-heading">
						<h2>Tell Us about yourself</h2>
					</div>
					<div className="form-fields">
						{this.getGenderForm()}
						<div className="form-field">
							{this.getCategoryForm()}
							{this.getDomicileStateForm()}
							{this.getExamSpecificCategories()}
						</div>
					</div>
				</div>
				<ButtonWidget deviceType={this.props.deviceType} step={3} isButtonDisabled={!this.state.canSubmit} previousStep={this.props.previousStep} nextBtnText='Finish' commonValidationMsg={this.state.errMsg} />
			</div>
			</ReactForm>
		)
	}
}
export default Step3;

Step3.propTypes = {
  categoriesData: PropTypes.object,
  deviceType: PropTypes.string,
  domicileStates: PropTypes.array,
  examScores: PropTypes.object,
  nextStep: PropTypes.func,
  previousStep: PropTypes.func
};