import React from 'react';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';

class CustomSingleSelectTemplate extends React.Component{

    constructor(props) {
        super(props);    
        this.state = {
            data : {},
            filter : true
        }
        this.selectItem      = this.selectItem.bind(this);
        this.renderLayerHtml = this.renderLayerHtml.bind(this);
    }

    componentDidMount() {
        this.setState({'data':this.props.data});
    }

    componentWillReceiveProps(newProps) {

        if(newProps.searchText.length > 0){
            this.filterDisplayList(newProps.searchText);
        } else {
            this.setState({'data':newProps.data});    
        }

    }

    filterDisplayList(searchText) {

        var finalResult = {};
        searchText      = searchText.replace(/[()+.*]/g, "");

        if(this.props.optgroup) {

            var itemsToFilter = this.props.data.options;
            var parentArray   = this.props.data.optgroups;
            var filteredArray = [];
            
            for(var arr in itemsToFilter) {

                filteredArray[arr] = [];
                filteredArray[arr] = itemsToFilter[arr].filter(function(n,i){
                    var temp = n.value.replace(/[()+.*]/g, "");
                    return (temp.toLowerCase().indexOf(searchText.toLowerCase()) > -1);
                });

            }
			
            var optionsArray  = [];
            var optgroupArray = [];

            for(var index in filteredArray) {

                if(filteredArray[index].length > 0){
                    
                    optionsArray[index]  = [];
                    optionsArray[index]  = filteredArray[index];
                    optgroupArray[index] = [];
                    optgroupArray[index] = parentArray[index];

                } 

            }
            
            finalResult.optgroups = optgroupArray;
            finalResult.options   = optionsArray;

        } else {

            var displayedItems = [];
            var reverseKeyMap  = {};
            
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
            
            for(var i in filtered) {
                if(filtered[i].length > 0){
                    finalResult[reverseKeyMap[filtered[i]]] = filtered[i];
                }
            }

        }
        this.setState({'data':finalResult});

    }

    selectItem(itemId, itemName) {

        PreventScrolling.enableFormScrolling();
        this.props.removeSearchText();
        var fieldId = this.props.fieldId;
        var params = {
            fieldId: fieldId,
            itemId: itemId,
            itemName: itemName
        };
        this.props.clickHandlerFunction(params);

    }

    renderLayerHtml() {

        var layerHtml      = [];
        var values         = this.state.data;
        var showSubHeading = this.props.showSubHeading;
        var clickFunction  = this.selectItem;
        var fieldId        = this.props.fieldId;
        var sortOptions    = this.props.sortOptions;
        
        if(!this.props.optgroup) {
            
            layerHtml = (
                <React.Fragment>
                    { (showSubHeading && this.props.searchText == '') ? <div id="subHeading" className="loc-title ht sub-Lbl" >Select one</div> : ''}
                    <ul className="drpdwn-ul">
                        {values[-1] && <li key={-1} className="lfield" id={fieldId+"_"+-1} onClick={clickFunction.bind(this, -1, values[-1])} >
                                <span key={'a'+-1} className="fcb">{values[-1]}</span>
                        </li>}
                    {
                        Object.keys(values).map(function(name,index){
                            if(Object.keys(values)[index] != -1) {
                                return (
                                    <li key={index} className="lfield" id={fieldId+"_"+Object.keys(values)[index]} onClick={clickFunction.bind(this, name, values[name])} >
                                        <span key={'a'+index} className="fcb">{values[name]}</span>
                                    </li>
                                );
                            }
                        })
                    }
                    </ul>
                </React.Fragment>
            );

        } else {

            layerHtml = (
                <React.Fragment>
                    { (showSubHeading && this.props.searchText == '') ? <div id="subHeading" className="loc-title ht sub-Lbl" >Select one</div> : ''}
                    <ul className="drpdwn-ul">
                    {
                    Object.keys(values.optgroups).map(function(i){
                        
			 if(sortOptions !== 'none'){
                             values.options[i].sort((a, b) => a.value.localeCompare(b.value));
                        }

                        return (
                            <div key={'a'+i}>
                                <div key={'b'+i} className="optgrp-hd">
                                    {values.optgroups[i]}
                                </div>
                                <ul key={'c'+i} className="drpdwn-ul">
                                {
                                    Object.keys(values.options[i]).map(function(name,index){
                                        return (
                                            <li key={index} className="lfield" id={fieldId+"_"+values.options[i][index].id} onClick={clickFunction.bind(this, values.options[i][index].id, values.options[i][index].value)} >
                                                <span key={'a'+index} className="fcb">{values.options[i][index].value}</span>
                                            </li>
                                        );
                                    })
                                }
                                </ul>
                            </div>
                        );
                    })
                    }
                    </ul>
                </React.Fragment>
            );
        }

        return layerHtml;
    }

    render() {

        if(!this.props.optgroup) {
            if( this.state.data == null || Object.keys(this.state.data).length == 0 ) {
                return (<div className="noResult-found">No Results Found</div>);
            }
        } else {
            if( this.state.data.options == null || Object.keys(this.state.data.options).length == 0 ) {
                return (<div className="noResult-found">No Results Found</div>);
            }
        }

        return(
            <div className="pwa-cont pad pwa-resp">
                <div className="customSelect singleselect editable">
                    <div className="option-wrapper">
                        {this.renderLayerHtml()}
                    </div>
                </div>
            </div>
        );

    }

}

export default CustomSingleSelectTemplate;
