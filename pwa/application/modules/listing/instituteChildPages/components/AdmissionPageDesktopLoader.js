import PropTypes from 'prop-types'
import React from 'react';
import {sectionLoder} from './../../../../utils/commonHelper';

class AdmissionPageDesktopLoader extends React.Component {

    constructor(props){
        super(props);
    }

    getTopcard(){
        return (
            <div className="clp">
                <div className="pwa_headerv1">
                    <div className="header-bgcol"></div>
                    <div className="pwa-headerwrapper">
                        <div className="pwa_topwidget">
                            <div className="header_img">
                                <div className="loader-line shimmer thumbImg"></div>
                            </div>
                            <div className="text-cntr clg_dtlswidget">
                                <h1 className=""><div className="loader-line shimmer"></div></h1>
                                <p className="region-widget"><span className="loader-line shimmer"></span> <span className="loader-line shimmer"></span></p>
                                <div className="rank-widget contentloader">
                                    <span className="loader-line shimmer"></span>
                                </div>
                            </div>
                            <div className="flex flex-column">
                                <div className="facts-widget contentloader">
                                    <div className="loader-line shimmer"></div>
                                    <div className="loader-line shimmer"></div>
                                </div>
                                <div className="topcard_btns">
                                    <button type="button" name="button" className="pwa-btns loader-line shimmer ht20"></button>
                                    <button type="button" name="button" className="pwa-btns loader-line shimmer ht20"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }

    leftLoader(){
        return(
            <div className="pwa_leftCol">
                <section>
                    {sectionLoder()}
                </section>
                <section>
                    {sectionLoder()}
                </section>
            </div>
        )

    }
    rightLoader(){
        return(
            <div className="pwa_rightCol">
                <section>
                    {sectionLoder()}
                </section>
            </div>
        )
    }

    render(){
        if(this.props.leftLoader && this.props.rightLoader){
            return(
                <React.Fragment>
                    {this.leftLoader()}
                    {this.rightLoader()}
                </React.Fragment>

            )
        }
        else{
            return(
                <React.Fragment>
                    <div className="ilp courseChildPage pwa_admission">
                        <div className="pwa_pagecontent">
                            <div className="pwa_container">
                                {this.getTopcard()}
                                {this.leftLoader()}
                                {this.rightLoader()}
                            </div>
                        </div>
                    </div>
                </React.Fragment>
            )
        }



    }

}

export default AdmissionPageDesktopLoader;

AdmissionPageDesktopLoader.propTypes = {
    leftLoader: PropTypes.any,
    rightLoader: PropTypes.any
}