import React, { Component } from 'react';
import PropTypes from 'prop-types';
import config from './../../../../../config/config';
import Lazyload from './../../../reusable/components/Lazyload';

class MarketingComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}

    returnMarketingFold(foldData){
        let listHTML = [];
        if(typeof foldData != undefined && foldData != null){
            let temEle = '';
            if(foldData.type == 'videoOnly'){
                let videoUrl = foldData.targetURL;
                let myregexp = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i;
                let matches = videoUrl.match(myregexp);

                temEle=<div className="videoSec" key={0}>
                          <Lazyload offset={100} once>
                            <iframe width='512' height='288' src={`https://www.youtube.com/embed/${matches[1]}`} title='YouTube video player' frameBorder='0' allowfullscreen/>
                          </Lazyload>
                        </div>
            }
            else if(foldData.type=='imageWithText'){
                temEle=<div className="ImageWithCaption" key="1">
                              <div className="secImg">
                              <Lazyload offset={100} once>
                                  <img className="lazy" data-original={config().IMAGES_SHIKSHA+foldData.imgURL} alt="" title="" src="" />
                              </Lazyload>  
                              </div>
                              <div className="secDesc" lang="en">
                                  {foldData.header!=''?
                                  <h5>
                                    <strong>{foldData.header} : </strong>{foldData.subHeader}
                                  </h5>:''}
                                  <p>
                                      {foldData.description}
                                  </p>
                                  {foldData.targetURL!=''?
                                  <div className="buttonContainer">
                                      <a href={foldData.targetURL} target="_blank" className="button button--orange">READ MORE</a>
                                  </div>:''}
                              </div>
                          </div>

            }
            else if(foldData.type=='imageOnly'){
                temEle=<div className="singleImage" key={2}>
                        <Lazyload offset={100} once>
                            <img className="lazy" data-original={config().IMAGES_SHIKSHA+foldData.imgURL} alt="" title="" width="512" height="288" src="" />
                        </Lazyload>
                            <div className="img-caption">
                                <p>{foldData.header}</p>
                                {foldData.targetURL!=''?
                                <a href={foldData.targetURL} target="_blank" className="trnBtn">READ MORE</a>:''}
                            </div>
                        </div>
            }

            listHTML.push(temEle);
        }
        return listHTML;
    }

  	render(){
      let foldData = this.props.marketingData;
      return(
        <div className="fltlft addvertisement">
          {this.returnMarketingFold(foldData)}
        </div>
      )
  	}

}
export default MarketingComponent;
