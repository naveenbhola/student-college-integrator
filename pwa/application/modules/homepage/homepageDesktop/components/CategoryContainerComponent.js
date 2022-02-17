import React, { Component } from 'react';
import PropTypes from 'prop-types';
import CategoryContentComponent from './CategoryContentComponent';

class CategoryContainerComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}
 	
  	render(){
  		return(
      	<CategoryContentComponent/>
      )
  	}

}
export default CategoryContainerComponent;