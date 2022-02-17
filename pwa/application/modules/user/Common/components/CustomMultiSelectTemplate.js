import React from 'react';

class CustomMultiSelectTemplate extends React.Component{

    constructor(props) {
        super(props);    
        this.state = {
            data : {},
            filter : true
        }
        this.renderLayerHtml = this.renderLayerHtml.bind(this);
    }

    componentDidMount() {
        this.setState({'data':this.props.data}, this.checkInputs.bind(this));
    }

    checkInputs() {
        var container     = document.querySelector('#mainDiv-checkboxes');
        var checkedInputs = container.querySelectorAll('input[type="checkbox"]');
        var originalId    = null;
        
        Array.from(checkedInputs).forEach(input => {
            var originalId = input.id.split('_input');
            if(document.getElementById(originalId[0]).checked){
                document.getElementById(input.id).checked = true;
            } else {
                document.getElementById(input.id).checked = false;
            }
        });
    }

    componentWillReceiveProps(newProps) {

        if(newProps.searchText.length > 0){
            this.filterDisplayList(newProps.searchText);
        } else {
            this.setState({'data':newProps.data}, this.checkInputs.bind(this));
        }

    }

    filterDisplayList(searchText) {

        var displayedItems = [];
        var reverseKeyMap  = {};
        searchText         = searchText.replace(/[()+.*]/g, "");
        
        if(this.props.data instanceof Array) {
            displayedItems = this.props.data;
            for(var key in displayedItems) {
                reverseKeyMap[displayedItems[key]] = key;
            }
        } else {
            displayedItems = Object.values(this.props.data);
            for(var key in this.props.data) {
                reverseKeyMap[this.props.data[key]] = key;
            }
        }
        
        var filtered = [];
        filtered = displayedItems.filter(function(n,i){
            var temp = n.replace(/[()+.*]/g, "");
            return (temp.toLowerCase().indexOf(searchText.toLowerCase()) > -1);
        });
        
        var finalResult = {};
        for(var i in filtered) {
            if(filtered[i].length > 0){
                finalResult[reverseKeyMap[filtered[i]]] = filtered[i];
            }
        }
        this.setState({'data':finalResult}, this.checkInputs.bind(this));
    }

    renderLayerHtml() {

        var layerHtml      = [];
        var values         = this.state.data;
        var showSubHeading = this.props.showSubHeading;
        var fieldId        = this.props.fieldId;
        
        layerHtml = (
            <React.Fragment>
                { (showSubHeading && this.props.searchText == '') ? <div id="subHeading" className="loc-title ht sub-Lbl" >Select one or more</div> : ''}
                <ul className="drpdwn-ul">
                {
                    Object.keys(values).map(function(name,index){
                        return (
                            <li key={index} className="lfield">
                                <div key={'a'+index} className="Customcheckbox fcb">
                                    <input key={'b'+index} type="checkbox" value={values[name]} id={fieldId+"_"+Object.keys(values)[index]+"_input"} />
                                    <label key={'c'+index} htmlFor={fieldId+"_"+Object.keys(values)[index]+"_input"}>{values[name]}</label>
                                </div>
                            </li>
                        );
                    })
                }
                </ul>
            </React.Fragment>
        );

        return layerHtml;
    }

    render() {

        if( this.state.data == null || Object.keys(this.state.data).length == 0) {
            return (<div className="noResult-found">No Results Found</div>);
        }

        return(
            <div className="pwa-cont pad pwa-resp">
                <div className="pwa-multiselect">
                    <div id="mainDiv-checkboxes" className="option-wrapper">
                        {this.renderLayerHtml()}
                    </div>
                </div>
            </div>
        );

    }

}

export default CustomMultiSelectTemplate;