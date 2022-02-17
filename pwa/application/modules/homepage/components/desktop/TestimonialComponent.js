import React, { Component } from 'react';
import PropTypes from 'prop-types';
import config from './../../../../../config/config';

class TestimonialComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}

    createTestimonial(){
        let self  =this;
        let html = '';
        let sectionData  = new Array();
        let counter = 0;
        html = this.props.testimonialData.map(function(value,index){
            counter++;
            return(
                <div key={index}>
                	<div className="speaker-con">
                                    <div className="fltlft avatar-figure">
                                        <img className="avatar lazy" data-original={config().IMAGES_SHIKSHA+value.imageUrl} src={config().IMAGES_SHIKSHA+value.imageUrl} className="inline"/>
                                    </div>
                                    <div className="fltryt avatar-caption" lang="en">
                                        <p className="tesName">
                                            <strong>{value.name} <b>|</b></strong>
                                            <span>{value.designation}</span>
                                        </p>
                                        <p className="caption-main">
                                        {value.testimonial}
                                        </p>
                                    </div>
                                    <div className="clr"></div>
                                </div>
                                {(counter<self.props.testimonialData.length)?<div className="seprator"></div>:''}
                </div>
            );
        })
        sectionData.push(html);
        return sectionData;
    }
  	render(){
        let testimonials = this.createTestimonial();
  		return(
        <div className="fltryt GRATITUDE">
                            <div className="speaker-sec">
                                <h2>WORDS OF GRATITUDE</h2>
                                {testimonials}
                            </div>
                        </div>
      )
  	}

}
export default TestimonialComponent;
