import React, { Component } from 'react';
import CustomSingleSelectLayer from './../../Common/components/CustomSingleSelectLayer';
import CustomSelect from './../../Common/components/CustomSelect';
import { getLocalities } from './../actions/ResponseFormAction';
import Loader from './../../../reusable/components/Loader';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';

class ResidenceCityLocality extends Component {

	constructor(props) {
		
		super(props);
		this.state = {
			isOpenSelectLayer: false,
			showLocalities: false,
			loader: false
		}
		this.layerData           = null;
		this.layerHeading        = null;
		this.fieldId             = null;
		this.selectedCity        = (props.formValues.residenceCityLocality) ? (props.formValues.residenceCityLocality) : -1;
		this.selectedCityName    = null;
		this.selectedLocality    = (props.formValues.residenceLocality) ? (props.formValues.residenceLocality) : -1;
		this.localitiesData      = null;
		this.renderCityHtml      = this.renderCityHtml.bind(this);
		this.renderLocalityHtml  = this.renderLocalityHtml.bind(this);
		this.getLocalitiesValues = this.getLocalitiesValues.bind(this);

	}

	componentWillReceiveProps(nextProps) {
		console.log("baseCourse",nextProps.baseCourse);
		if(nextProps.baseCourse != null) {
			if(this.state.loader==false){
				this.getLocalitiesValues(nextProps.baseCourse);
			}
		}

	}

	render() {
		
		return (
				<div>
					{ this.renderCityHtml() }
					<div id={"locality_block_"+this.props.regFormId}>
						{ this.state.showLocalities && this.renderLocalityHtml() }
					</div>
					<CustomSingleSelectLayer show={this.state.isOpenSelectLayer} data={this.layerData} heading={this.layerHeading} search={true} optgroup={true} fieldId={this.fieldId} showSubHeading={true} clickHandlerFunction={this.setFieldValue.bind(this)} onClose={this.closeSelectLayer.bind(this)} >
            		</CustomSingleSelectLayer>
            		<Loader show={this.state.loader}/>
            	</div>
			);

	}

	componentDidMount() {

		if(this.selectedCity > 0 && (typeof(document.getElementById("residenceCityLocality_"+this.props.regFormId)) != 'undefined' && document.getElementById("residenceCityLocality_"+this.props.regFormId) != null)) {
			var event = new Event('change', { bubbles: true });
  			document.getElementById("residenceCityLocality_"+this.props.regFormId).dispatchEvent(event);
  		}

  		if(this.state.loader || this.state.isOpenSelectLayer) {
  			PreventScrolling.disableFormScrolling();
  		} else {
  			PreventScrolling.enableFormScrolling();
  		}

	}

	componentDidUpdate() {
		
		if(this.state.loader || this.state.isOpenSelectLayer) {
  			PreventScrolling.disableFormScrolling();
  		} else {
  			PreventScrolling.enableFormScrolling();
  		}
		
	}

	renderCityHtml() {

		var cityStateList    = this.props.cityList;
		var cityStateOptions = [];
		var statesMap        = [];
		var citiesMap        = [];			
		var optionsMap 		 = [];
		
		optionsMap.push(<option key={-1} value="">Current City</option>); 

		cityStateOptions = Object.keys(cityStateList).map(function(index) {
			
			if(index == 'metroCities') {
				
				var state    = 'Popular Cities';
				statesMap[0] = state;
				citiesMap[0] = [];

				return (

					<optgroup key={index} label="Popular Cities">
					{
					Object.keys(cityStateList.metroCities).map(function(i) {
						citiesMap[0][i] = [];
						citiesMap[0][i]['id']    = cityStateList.metroCities[i].cityId;
						citiesMap[0][i]['value'] = cityStateList.metroCities[i].cityName;
						return (
							<option key={i} value={cityStateList.metroCities[i].cityId}>{cityStateList.metroCities[i].cityName}</option>
						);
					})
					}
					</optgroup>

				);

			} else if(index == 'stateCities') {
				var counter = 0;
				return (

					<React.Fragment key={index}>
					{
					Object.keys(cityStateList.stateCities).map(function(i) {
						counter++;
						statesMap[counter] = cityStateList.stateCities[i].StateName;
						citiesMap[counter] = [];
						return (

							<optgroup key={i} label={cityStateList.stateCities[i].StateName}>
							{
							Object.keys(cityStateList.stateCities[i].cityMap).map(function(j) {
								citiesMap[counter][j] = [];
								citiesMap[counter][j]['id']    = cityStateList.stateCities[i].cityMap[j].CityId;
								citiesMap[counter][j]['value'] = cityStateList.stateCities[i].cityMap[j].CityName;
								return (
									<option key={j} value={cityStateList.stateCities[i].cityMap[j].CityId}>{cityStateList.stateCities[i].cityMap[j].CityName}</option>
								);
							})
							}
							</optgroup>
						);
					})
					}
					</React.Fragment>

				)

			}

		});
		
		var dataMap       = [];
		dataMap.optgroups = statesMap;
		dataMap.options   = citiesMap;
		optionsMap.push(cityStateOptions);
		var isSelected    = this.props.formValues.residenceCityLocality ? true : false;
		var params        = {'inputName':'residenceCityLocality','regFormId':this.props.regFormId};

		return (
			<CustomSelect 
				name="residenceCityLocality" 
				title="City you live in" 
				defaultValue={this.selectedCity} 
				value={this.selectedCity > 0 ? this.selectedCity : null} 
				selectedInput={this.selectedCityName} 
				isSelected={isSelected} 
				validations={{validateSelectMandatory: params}}
				validationErrors={{validateSelectMandatory: 'Please select City you live in'}} 
				baseId="residenceCityLocality" 
				options={optionsMap} 
				regFormId={this.props.regFormId} 
				clickHandlerFunction={this.openSelectLayer.bind(this,'residenceCityLocality',dataMap,'City')} 
				changeHandlerFunction={this.getLocalitiesValues} 
			/>
		);

	}

	openSelectLayer(fieldId, fieldValues, fieldLabelForSearch) {
		
		this.setState({
			...this.state, 
			isOpenSelectLayer: true,
		});
		this.layerData    = fieldValues;
		this.layerHeading = fieldLabelForSearch;
		this.fieldId      = fieldId;

	}

	closeSelectLayer() {
		this.setState({...this.state, isOpenSelectLayer: false});
	}

	setFieldValue(params) {
		
		if(params.fieldId == 'residenceCityLocality') {

			this.selectedCity     = params.itemId;
			this.selectedCityName = params.itemName;
			if(this.selectedLocality > 0) {

				document.getElementById("residenceLocality_block_"+this.props.regFormId).classList.remove("filled");
				document.getElementById("residenceLocality_input_"+this.props.regFormId).innerHTML = '';

			}

		} else if(params.fieldId == 'residenceLocality') {

			this.selectedLocality = params.itemId;

		}

		document.getElementById(params.fieldId+"_block_"+this.props.regFormId).classList.add("filled");
  		document.getElementById(params.fieldId+"_input_"+this.props.regFormId).innerHTML = params.itemName;
  		document.getElementById(params.fieldId+"_"+this.props.regFormId).value = params.itemId;
  		this.setState({
        	...this.state,
        	isOpenSelectLayer: false
        });

        var event = new Event('change', { bubbles: true });
  		document.getElementById(params.fieldId+"_"+this.props.regFormId).dispatchEvent(event);
  		
	}

	getLocalitiesValues(baseCourseValue) {
		if(this.selectedCity <= 0 || (!this.props.baseCourse && !this.props.isExamResponse)) {
			return null;
		}

		if(this.props.isExamResponse) {
			var baseCourse = [];
			Array.from(document.getElementsByName('baseCourses[]')).forEach(a => {
				baseCourse.push(a.value);
			});
		} else {
			var baseCourse = this.props.baseCourse;
		}

		if(baseCourseValue!=undefined && baseCourseValue!='undefined'){
			baseCourse = baseCourseValue;
		}

		
		this.setState({...this.state, loader: true});
		var cityId = this.selectedCity;
		var self   = this;
		var params = 'cityId='+cityId+'&baseCourses[]=';
		if(Array.isArray(baseCourse) && baseCourse.length>1)
		{
			for(var i=0;i<baseCourse.length;i++)
			{
				if(i<baseCourse.length-1)
				{
					params = params + baseCourse[i] + "&baseCourses[]=";
				}
				else
				{
					params = params + baseCourse[i];
				}
			}
		}
		else
		{
			params = params + baseCourse;
		}
		console.log(params);
		console.log("baseCourse ",baseCourse);
		// for (var index in baseCourse) {
  //           if (baseCourse[index] != 'undefined' && typeof(baseCourse[index]) != 'undefined') {
  //               params += '&baseCourses[]=' + baseCourse[index];
  //           }
  //       }

		var callResponse = Promise.resolve(getLocalities(params)).then((response) => {
			return response;
		});

		//console.log(callResponse);
		
		Promise.resolve(callResponse).then((resp) => {

			// console.log(resp);
			
			if(resp.localitiesMap && !resp.noLocalities) {
				
				self.localitiesData = resp.localitiesMap;
				self.setState({
					...self.state,
					showLocalities: true,
					loader: false,
					isOpenSelectLayer: false
				});

			} else {

				self.selectedLocality = -1;
				self.setState({
					...self.state,
					showLocalities: false,
					loader: false,
					isOpenSelectLayer: false
				});
				
			}
			
		});

	}

	renderLocalityHtml() {

		if(!this.state.showLocalities || !this.localitiesData) {
			return null;
		}

		var localitiesMap   = this.localitiesData;
		var localityOptions = [];
		var optgroups		= [];
		var options 		= [];
		var counter 		= -1;
		
		localityOptions = Object.keys(localitiesMap).map(function(zone) {
			counter++;
			optgroups[counter] = zone;
			options[counter]   = [];
			var optionCount    = -1;
			
			return (
				<optgroup key={zone} label={zone}>
				{
					Object.keys(localitiesMap[zone]).map(function(index) {
						optionCount++;
						options[counter][optionCount]          = [];
						options[counter][optionCount]['id']    = index;
						options[counter][optionCount]['value'] = localitiesMap[zone][index];
						return (
							<option key={index} value={index}>{localitiesMap[zone][index]}</option>
						);
					})
				}
				</optgroup>
			);
		});

		var dataMap       = [];
		dataMap.optgroups = optgroups;
		dataMap.options   = options;
		var isSelected    = this.props.formValues.residenceLocality ? true : false;
		var params        = {'inputName':'residenceLocality','regFormId':this.props.regFormId};

		return (
			<CustomSelect 
				name="residenceLocality" 
				title="Nearest locality you live in" 
				defaultValue={this.selectedLocality} 
				value={this.selectedLocality > 0 ? this.selectedLocality : null} 
				isSelected={isSelected} 
				validations={{validateSelectMandatory: params}}
				validationErrors={{validateSelectMandatory: 'Please select Nearest locality you live in'}} 
				baseId="residenceLocality" 
				options={localityOptions} 
				regFormId={this.props.regFormId} 
				clickHandlerFunction={this.openSelectLayer.bind(this,'residenceLocality',dataMap,'Locality')} 
			/>
		);

	}

}

export default ResidenceCityLocality;
