import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import './../../assets/dropDown.css';

class DropDown extends Component {
    
    constructor(props)
    {
        super(props);
    }
    

    render(){
    	if(this.props.isOpen){
    		if(this.props.completeHtml){
    			return(
    				<React.Fragment>
    				{this.props.data}
    				</React.Fragment>
    			)
    		}else{
	    		return(
			    	<React.Fragment>
			    		<div className="input-box select-box">
			    			<div className="option-list-box">
			    			  <div className="single-select option-list-container">
			    			     <div className="scrollable">
			    			        {this.props.isSearchExist ? this.props.searchHtml:null}
			    			        <ul className="single-select-list option-list">
			    			          {this.props.data}
			    			        </ul>
			    			      </div>
			    			  </div>
			    			</div>
			    		</div>
			    	</React.Fragment>
	    		)
    			
    		}

    	}
    	else{
    		return null;
    	}
    }
}
export default DropDown;