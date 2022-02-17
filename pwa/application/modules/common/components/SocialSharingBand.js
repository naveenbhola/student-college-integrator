/**
 * Desc   : Social sharing Band
 * author : akhter
 **/
import React, { Component } from 'react';
import styles from './../assets/SocialSharingBand.css';
import {getShareUrl, getShareCount, calculateShareCount} from './../utils/socialShareHelper';
import SocialSharingBandInner  from './SocialSharingBandInner';

class SocialSharingBand extends Component{
    constructor(props){
        super(props);
        this.state = {
            shareUrl : '',
            shareCount : 0
        };
    }

    componentDidMount(){
        this.setState({ shareUrl : getShareUrl()});
        Promise.resolve(getShareCount()).then((response) => {
            if(response.data.data && typeof response.data.data.totalShareCount != 'undefined' && response.data.data.totalShareCount){
                let shareCount = response.data.data.totalShareCount;
                if(shareCount>10){
                    this.setState({ shareCount : calculateShareCount(shareCount) });
                }
            }
        });
    }   

    render(){
        return(
            <div className="social-sharing-widget">
                <div className="sharing-box">
                    <ul className="sharing-band-list" style={{'display' : 'flex', 'listStyle' : 'none'}} >
                        <SocialSharingBandInner widgetPosition={this.props.widgetPosition} deviceType={this.props.deviceType} shareUrl={this.state.shareUrl} shareCount={this.state.shareCount}/>
                    </ul>
                </div>
            </div>
        )    
    }
    
};
SocialSharingBand.defaultProps= {
  deviceType : 'mobile'
}
export default SocialSharingBand;