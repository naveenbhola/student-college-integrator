import React, { Component } from 'react';
import PropTypes from 'prop-types';
import '../../assets/desktop/FeaturedArticle.css';
import Slider from "react-slick";
import SliderCSS from './../../../common/assets/SliderSlick.css';
import Lazyload from './../../../reusable/components/Lazyload';
import config from './../../../../../config/config';

class ArticleComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}

    createFeaturedArticleSection(){
        let html = '';
        html = this.props.articleData.map(function(value,index){
            return(
                <div className="slideItem" key={value.id}>
                    <div>
                        <a href={value.url} lang="en"> 
                            <span>
                              <Lazyload offset={100} once>
                                <img className="inline" data-original={config().IMAGES_SHIKSHA+value.homepageImageUrl} alt={value.title} title={value.title} src={config().IMAGES_SHIKSHA+value.homepageImageUrl} />
                              </Lazyload>
                            </span>
                            <p>{value.title}</p>
                        </a>
                    </div>
                </div>
            )
        });
        return html;
    }

    createLatesUpdates=()=>{
        let updateList = [];
        this.props.latestUpdate.forEach((value)=>{
            let heading = (value['title'].length>73) ? value['title'].substring(0,70)+'...' : value['title']; 
            let item = <li className="first" key={value['id']}> <a href={this.props.config.SHIKSHA_HOME+value['url']}>{heading}</a><p className="smal-sep-line"><span></span> </p></li>;
            updateList.push(item);
        });
        return updateList;
    }
 	
  	render(){
        let featuredArticle = this.createFeaturedArticleSection();
        var settings = {
          dots: false,
          infinite: true,
          speed: 500,
          slidesToShow: 3,
          slidesToScroll: 3,
          autoplay: true,
          autoplaySpeed: 3000,
        };
  		return(
      	<section className="articleBanner">
                <div className="_cntr">
                    <div className="articleContentSec">
                        <div className="fltlft articleSlide">
                            <div className="heading3">
                                <h2>FEATURED ARTICLES</h2> 
                            </div>
                            <div className="sliderContainer homepageFeaturedArticleSlider">
                              <Slider {...settings}>
                                    {featuredArticle}
                              </Slider>
                            </div>
                            <div className="right_button"> 
                              <a className="button button--orange" href={this.props.config.SHIKSHA_HOME+"/news-articles"}>View All Articles</a> 
                            </div>
                        </div>
                        
                        {this.props.latestUpdate && <div className="fltryt articleLatest">
                                <div>
                                    <h2>Latest Updates</h2>
                                    <ul className="latestUpdateList">
                                        {this.createLatesUpdates()}
                                    </ul>
                                </div>
                            </div>
                        }
                        <div className="clr"></div>
                    </div>
                </div>
            </section>
      )
  	}

}
ArticleComponent.propTypes = {
  latestUpdate : PropTypes.array,
  articleData : PropTypes.array,
  config : PropTypes.object
}
export default ArticleComponent;
