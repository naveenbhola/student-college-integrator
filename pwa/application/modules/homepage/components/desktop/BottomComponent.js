import React, { Component } from 'react';
import PropTypes from 'prop-types';
import MarketingComponent from './MarketingComponent';
import TestimonialComponent from './TestimonialComponent';

class BottomComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}

  	render(){
  		return(
        <section id="mf" className="marketingBanner">
                <div className="_cntr">
                    <div className="marketingCont">
                      {(this.props.marketingData!=null)?<MarketingComponent marketingData={this.props.marketingData}/>:''}
                      {(this.props.testimonialData!=null && this.props.testimonialData.length>0)?<TestimonialComponent testimonialData={this.props.testimonialData}/>:''}
                      <div className="clr"></div>
                    </div>
                </div>
        </section>
      )
  	}

}
export default BottomComponent;
