import React, { Component } from 'react';
import CustomSingleSelectLayer from './../../Common/components/CustomSingleSelectLayer';
import CustomSelect from './../../Common/components/CustomSelect';

class PreferredLocation extends Component {

	constructor(props) {
		super(props);
		this.state = {
			isOpenSelectLayer: false
		}
		this.layerData              = null;
		this.layerHeading           = null;
		this.fieldId                = null;
		this.cityCount              = 0;
		this.selectedCity           = (props.formValues.prefCity) ? (props.formValues.prefCity) : -1;
		this.localityCount          = 0;
		this.selectedLocality       = (props.formValues.prefLocality) ? (props.formValues.prefLocality) : -1;
		this.createSelectionField   = this.createSelectionField.bind(this);
		this.renderPrefCityHtml     = this.renderPrefCityHtml.bind(this);
		this.renderPrefLocalityHtml = this.renderPrefLocalityHtml.bind(this);
	}

	render() {
		
		return (	
				<div>
					<div id={"prefCityHolder_"+this.props.regFormId} className="ih">
						{this.renderPrefCityHtml()}
					</div>
					<div id={"prefLocalityHolder_"+this.props.regFormId} className="ih">
						{this.renderPrefLocalityHtml()}
					</div>
					<CustomSingleSelectLayer show={this.state.isOpenSelectLayer} data={this.layerData} heading={this.layerHeading} search={true} fieldId={this.fieldId} showSubHeading={true} clickHandlerFunction={this.setFieldValue.bind(this)} onClose={this.closeSelectLayer.bind(this)} >
            		</CustomSingleSelectLayer>
				</div>
			);
		
	}

	componentDidMount() {
		
		if(this.cityCount <= 1) {
			document.getElementById("prefCityHolder_"+this.props.regFormId).classList.add("ih");
		} else {
        	document.getElementById("prefCityHolder_"+this.props.regFormId).classList.remove("ih");
        }
        
        if(this.localityCount <= 1) {
			document.getElementById("prefLocalityHolder_"+this.props.regFormId).classList.add("ih");
		} else {
        	document.getElementById("prefLocalityHolder_"+this.props.regFormId).classList.remove("ih");
        }

	}

	componentDidUpdate() {
		
		if(this.cityCount <= 1) {
			document.getElementById("prefCityHolder_"+this.props.regFormId).classList.add("ih");
		} else {
        	document.getElementById("prefCityHolder_"+this.props.regFormId).classList.remove("ih");
        }
        
        if(this.localityCount <= 1) {
			document.getElementById("prefLocalityHolder_"+this.props.regFormId).classList.add("ih");
		} else {
        	document.getElementById("prefLocalityHolder_"+this.props.regFormId).classList.remove("ih");
        }

	}

	createSelectionField(fieldId, fieldLabel, fieldValues, fieldLabelForSearch, selectedValue, isSelected) {
        
        var fieldHtml = [];

	    fieldHtml.push(<option key={-1} value="">{"Select "+fieldLabel}</option>);
		for(var index in fieldValues) {
			fieldHtml.push(<option key={index} value={index}>{fieldValues[index]}</option>);
		}
		var params = {'inputName':fieldId,'regFormId':this.props.regFormId};

        return (
        	<CustomSelect 
        		name={fieldId} 
        		title={fieldLabel} 
        		defaultValue={selectedValue} 
        		value={selectedValue > 0 ? selectedValue : null} 
        		isSelected={isSelected} 
        		validations={{validateSelectMandatory: params}}
				validationErrors={{validateSelectMandatory: 'Please select '+fieldLabel}} 
        		baseId={fieldId} 
        		options={fieldHtml} 
        		regFormId={this.props.regFormId} 
        		clickHandlerFunction={this.openSelectLayer.bind(this,fieldId,fieldValues,fieldLabelForSearch)} 
        	/>
        );
    
    }

    renderPrefCityHtml() {

		var prefLocations = this.props.locations;
		var prefCities    = [];
		var lastId        = 0;
		var counter       = 0;
		
		for(var index in prefLocations) {
            prefCities[index] = prefLocations[index]['cityName'];
            lastId = index;
            counter++;
        }
        this.cityCount = counter;

        if(counter == 1 && lastId != 0) {
        	this.selectedCity = index;
        	if(typeof(prefLocations[this.selectedCity]['localities']) != 'undefined' && prefLocations[this.selectedCity]['localities'] != null) {
	        	this.localityCount = prefLocations[this.selectedCity]['localities'].length;
	        }
        	return null;
        }

        var isSelected   = (this.props.formValues.prefCity) ? true : false;
		var prefCityHtml = this.createSelectionField('prefCity', 'City you want to study in', prefCities, 'City', this.selectedCity, isSelected);
        return prefCityHtml;

	}

	setFieldValue(params) {
		
		if(params.fieldId == 'prefCity') {

			this.selectedCity = params.itemId;
			if(this.selectedLocality > 0) {

				this.selectedLocality = -1;
				document.getElementById("prefLocality_block_"+this.props.regFormId).classList.remove("filled");
				document.getElementById("prefLocality_input_"+this.props.regFormId).innerHTML = '';

			}

		} else if(params.fieldId == 'prefLocality') {

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

	renderPrefLocalityHtml() {
		
		if(!this.selectedCity || this.selectedCity < 1) {
			return null;
		}

		var prefLocations    = this.props.locations;
		var prefLocalityHtml = null;
		
		if(!prefLocations[this.selectedCity]['localities']) {

			this.selectedLocality = -1;
			return null;
			
        } else {

        	var prefLocalities = [];
			var lastId         = 0;
			var counter        = 0;
			
			for(var index in prefLocations[this.selectedCity]['localities']) {
	            prefLocalities[index] = prefLocations[this.selectedCity]['localities'][index];
	            lastId = index;
	            counter++;
	        }
	        this.localityCount = counter;
			
	        if(counter == 1 && lastId != 0) {
				this.selectedLocality = index;
        		if(this.cityCount == 1) {
        			return null;
        		}
        	}

        	var isSelected   = (this.props.formValues.prefLocality) ? true : false;
        	prefLocalityHtml = this.createSelectionField('prefLocality', 'Nearest center you want to study in', prefLocalities, 'Center', this.selectedLocality, isSelected);
			
        }
		
	    return prefLocalityHtml;

	}

}

export default PreferredLocation;