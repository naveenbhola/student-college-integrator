import React, { Component } from 'react';
import PropTypes from 'prop-types';

class CategoryTabComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}
 	
  	render(){
  		return(
      	<section className="courseBanner">
          <div className="tabSection">
              <ul className="_ctFold">
                  <li data-index="1" className="active">Mba</li>
                  <li data-index="2">B.TECH</li>
                  <li data-index="3">Design</li>
                  <li data-index="4">Law</li>
                  <li data-index="5">More</li>
              </ul>
          </div>
        </section>
      )
  	}

}
export default CategoryTabComponent;