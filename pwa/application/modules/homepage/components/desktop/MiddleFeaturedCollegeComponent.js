import React, { Component } from 'react';
import PropTypes from 'prop-types';
import '../../assets/desktop/MiddleFeaturedCollege.css';
import Slider from "react-slick";
import './../../../common/assets/SliderSlick.css';
import Lazyload from "../../../reusable/components/Lazyload";
import {chunkArray} from "../../../../utils/commonHelper";
import Anchor from "../../../reusable/components/Anchor";

class MiddleFeaturedCollegeComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}

    getSlidingData(){
      let tupleWithLi = [];
      if(this.props.featuredColleges && this.props.featuredColleges.length > 0){
          let tupleLimit = 8;
          let chunks = chunkArray(this.props.featuredColleges, tupleLimit);
          
          for (let i = 0; i < chunks.length; i++) {
            let tuples = [];
            for (let j = 0; j < chunks[i].length; j++) {
              tuples.push(
                    <a key={'tuple'+i+j} className="banner randomize_paid" href={chunks[i][j].targetUrl}> <Lazyload offset={100} once><img className="lazy" data-original={chunks[i][j].imageUrl} alt={chunks[i][j].collegeName} title={chunks[i][j].collegeName} src={chunks[i][j].imageUrl} className="inline"/></Lazyload></a>
                );
            }
            tupleWithLi.push(<div className="clgLst" key={'li'+i}>{tuples}</div>);
          }
        }
        return tupleWithLi;
    }
 	
  	render(){
      let settings = {
          dots: false,
          infinite: false,
          speed: 500,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 3000,
      };
      if(!(this.props.featuredColleges && this.props.featuredColleges.length > 0)) {
          return null;
      }

	    
            
	        return(
            <section className="featureBanner">
                    <div className="_cntr">
                        <div className="heading3">
                            <h2>FEATURED COLLEGES</h2> 
                        </div>
                        <div className="featuredContainer">
                            <div className="sliderContainer homepageFeaturedCollegeSlider">
                                <div className="featuredSlider">
                                    <Slider {...settings}>
                                        {this.getSlidingData()}
                                    </Slider>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
          );
        }

}
export default MiddleFeaturedCollegeComponent;