import React from 'react';
import styles from './../assets/multiselect.css';
class MultiSelectLayerTemplate extends React.Component{
    constructor(props)
    {
        super(props);    
        this.state ={
            data : {},
            filter : true,
            render : false,
            isSearchText : false
        }
        this.selectedOptions = new Array();
		this.showSelected = new Array();
		this.removeAllSeclectedFilters = this.removeAllSeclectedFilters.bind(this);
    }
    componentDidMount()
    {
        this.setState({'data':this.props.data});
        this.setPopupHeight();
    }
    componentDidUpdate()
    {
        var liElements = document.getElementsByClassName('drpdwn-li').length;
        for(var i= 0 ;i<liElements;i++)
        {   
            if(document.getElementsByClassName('drpdwn-li')[i].hasAttribute('style'))
                    document.getElementsByClassName('drpdwn-li')[i].removeAttribute('style');
        }
        this.setPopupHeight();
    }
    setPopupHeight(){
        if(document.getElementById('option-wrapper'))
        {

          let windowHieght = parseInt(screen.height);
          let ulElements = document.getElementById('option-wrapper').getElementsByClassName('drpdwn-ul').length;
          let liElements = document.getElementById('option-wrapper').getElementsByClassName('drpdwn-ul')[ulElements-1].getElementsByTagName("li").length;
          var buttonHeight = document.getElementById('saveMultiSelect').offsetHeight;
          document.getElementById('mt55').style.maxHeight = (windowHieght-120) + 'px';
          document.getElementById('option-wrapper').style.maxHeight = (windowHieght-120) + 'px';
          let totalLiElements = document.getElementsByClassName('drpdwn-li').length;
          let liOffsetHt = document.getElementsByClassName('drpdwn-li')[0].offsetHeight;
          if((totalLiElements * liOffsetHt) > (windowHieght-120))
                document.getElementById('option-wrapper').getElementsByClassName('drpdwn-ul')[ulElements-1].getElementsByTagName("li")[liElements-1].style.paddingBottom = parseInt(buttonHeight)+'px';
      }
    }
    componentWillReceiveProps(newProps)
    {
    	console.log('componentWillReceiveProps');
        if(newProps.searchText.length > 0)
            this.filterDisplayList(newProps.searchText);
        else
            this.setState({'data':newProps.data,isSearchText : false});    
    }
    filterDisplayList(searchText)
    {
        var dispalyedItems = this.props.data;
        Object.filter = (obj, predicate) => Object.keys(obj).map( function(key) { 
             return dispalyedItems[key].filter(function(n,i){
                return n.name.toLowerCase().indexOf(searchText.toLowerCase()) !== -1;
            })
         } );
        var filtered = Object.filter(dispalyedItems, searchText); 
        var finalResult = {};
        var keyNames = Object.keys(this.props.data);
        for(var i in filtered)
        {
            if(filtered[i].length > 0)
                finalResult[keyNames[i]] = filtered[i];
        }
        this.setState({'data':finalResult,isSearchText : true});
    }
    handleLayerOptionClick(optionValue,optionName)
    {
    	console.log('handleLayerOptionClick',optionValue);
        if(this.isOptionSelected(optionValue))
        {
        	console.log('removeFromSelectedOptions',optionValue);
            this.removeFromSelectedOptions(optionValue);
        }
        else
        {
        	console.log('addToSelectedOptions',optionValue);
        	this.addToSelectedOptions(optionName,optionValue);
        }
    }
    addToSelectedOptions(optionName,optionValue)
    {
        if(this.isOptionSelected(optionValue))
        {
            return;
        }
        this.selectedOptions.push({optionName : optionName,optionValue : optionValue});
        this.showSelected.push(optionValue); 
        this.setState({render : true});
    }
    isOptionSelected(option)
	{
		return this.showSelected.includes(option);
	}
    renderSelectedOptionsHtml()
    {
        if(this.state.isSearchText)
            return;

        var selectedOptions = [];
        var hideselectedOptions = [];
        for(var sel in this.selectedOptions)
        {
            if(sel == 0)
            {
                selectedOptions.push(<div key={"selected_"+sel} className="select-box" key={this.selectedOptions[sel].optionValue} onClick={this.removeFromSelectedOptions.bind(this,this.selectedOptions[sel].optionValue)}>{this.selectedOptions[sel].optionName} <a href="javascript:void(0)" className="remove-city">&times;</a></div>);   
            }else
            {
                hideselectedOptions.push(<div key={"selected_"+sel} className="select-box" key={this.selectedOptions[sel].optionValue} onClick={this.removeFromSelectedOptions.bind(this,this.selectedOptions[sel].optionValue)}>{this.selectedOptions[sel].optionName} <a className="remove-city" href="javascript:void(0)">&times;</a></div>);
            }
        }
        if(hideselectedOptions.length > 0)
        {
            selectedOptions.push(<div key="span" ref={ (showAll) => this.showAll = showAll} className="hide sltd-fltOpt">{hideselectedOptions}</div>);
        }

        if(!this.state.showAllSelected && this.selectedOptions.length > 1)
        {
            selectedOptions.push(<a key="view-all" href="javascript:void(0);" data-var="1" className="cnt-morLnk" ref={ (showMore) => this.showMore = showMore} onClick={this.showAllselectedOptions.bind(this,'hide')}>+{this.selectedOptions.length-1} more</a>);
        }
        
        
        if(this.selectedOptions.length == 0)
        {
            return null;
        }
        return (
			<div className="ctp-filtr-selectn" id="ctp-filtr-selectn">
                <a className="clear-all" onClick={this.removeAllSeclectedFilters}>Clear All</a>
                <div className="fltr-ovrflw">
                    <div className="selectd-fltr sltd-fltOpt">
                       	{selectedOptions} 
                    </div>
                </div>
            </div>
		)
    }
    showAllselectedOptions(className)
	{
		if(this.showAll.classList.contains(className)){
			this.showAll.classList.remove(className);
			this.showAll.classList.add('d-i');
		}
		if(!this.showMore.classList.contains(className))
		{
			this.showMore.classList.add(className);
		}
	}
    removeAllSeclectedFilters()
	{
		this.showSelected.map(function(index){
			document.getElementById(index).checked = false;
		});
		this.showSelected = [];
		this.selectedOptions = new Array();
		this.setState({render : true});
	}
    removeFromSelectedOptions(optionValue)
    {
        let selectedOptions = this.selectedOptions.filter(function(value){
            return !(value['optionValue'] == optionValue)
        });
        this.selectedOptions = selectedOptions;
        let postion = this.showSelected.indexOf(optionValue);
        this.showSelected.splice(postion, 1);

        if(document.getElementById(optionValue))
        {
        	setTimeout(function(){
        		document.getElementById(optionValue).checked = false;
        	},0);
        }
        this.setState({render : true});
    }
    static formatHeadings(string)
    {
        if(!string || string.length == 0)
        {
            return;
        }
        return string.replace(/([A-Z])/g, ' $1').replace(/^(.)*/, function(str){ return str.charAt(0).toUpperCase() + str.substring(1); });
    }
    handleOptionClick(n)
    {
        const {onClick} = this.props;
        onClick(event,n);
    }
    onSubmit()
    {
    	const {onSubmit} = this.props;
    	onSubmit(this.selectedOptions);
    }
    render()
    {
        const {isAnchorLink} = this.props;
        if( this.state.data == null || Object.keys(this.state.data) == 0)
            return (<div className="noResult-found">Sorry, no {this.props.layerType} found for your query "{this.props.searchText}"</div>);
        var values = this.state.data;
        let showSubHeading = this.props.showSubHeading;
        var self = this;
       return(
       		<React.Fragment>
                <div className="mt55" id="mt55">
                	<div className="option-wrapper show" id="option-wrapper" append-selected-value="1">
                		<div className="selected-criteria-scrl" style={{display: 'block'}}>
                		{this.renderSelectedOptionsHtml()}
                		</div>
                			{ 
                					Object.keys(values).map(function(name,index){
	                					return (values[name] != null && values[name].length > 0 )? 
	                                    <React.Fragment key={"fg_"+index}>
	                                        { showSubHeading ? <strong key= { index } >{MultiSelectLayerTemplate.formatHeadings(name)}</strong> : '' }
		                				<ul className="drpdwn-ul">
		                					{values[name].map(function(n,i){
                                                let optionId = n.filterType+"_"+n.id;
			                					return (<li key={i} className="drpdwn-li">
					                				<div className="Customcheckbox">
					                				<input type="checkbox" className="shikshaMultiSelect" id={n.filterType+"_"+n.id} value={n.name} checked={self.showSelected.indexOf(optionId) >-1 ? 'checked' : ''}/>
					                				<label htmlFor={n.filterType+"_"+n.id} onClick={self.handleLayerOptionClick.bind(self,n.filterType+"_"+n.id,n.name)}>{n.name}</label></div></li>)
				                			})}
		                				</ul>
	                				</React.Fragment>
	                				:''
                				})
                			}

                		</div>
                </div>
                <div className="done-btn">
                	<input type="button" id="saveMultiSelect" showloaderondone="false" data-select="allIndiaCitySelect_input" className="green-btn" value="Done" onClick={this.onSubmit.bind(this)}/>
                </div>
            </React.Fragment>
        )
    }

}

export default MultiSelectLayerTemplate;