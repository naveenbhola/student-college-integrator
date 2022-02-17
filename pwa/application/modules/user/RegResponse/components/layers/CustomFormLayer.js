import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import PreventScrolling from './../../../../reusable/utils/PreventScrolling';

class FullPagePopup extends Component {
  
    render() {
        if(typeof window === 'undefined'){
            return null;
        }
        return ReactDOM.createPortal(
            this.prepareHtml(),
            document.getElementById('reg-full-layer'),
        );
    }
  
    prepareHtml() {
          
        return(
            <React.Fragment>
                <div id="form-loader" className="loader-col-msearch ih">
                    <div className="three-quarters-loader-msearch">Loading…</div>
                </div>
                <div className="pwa-lyr bcglayer reg-lyr">
                    <div className="pwa-hd-sgnup" id={"heading_"+this.props.regFormId}>
                        {this.props.heading && <p className="flLt">{this.props.heading}</p>}
                        <a href="javascript:void(0);" className="lyr-crs-sgnup" onClick={this.props.closeLayer}>&times;</a>
                        <p className="clr"></p>
                    </div>
                    <div className="pwa-cont-sgnup">
                            {this.props.innerHtml}                         
                    </div>
                </div>
            </React.Fragment>
        );

    }
}

class CustomFormLayer extends Component {
    
    constructor() {
        super();
    }

    beforeOpen() {
        PreventScrolling.disableScrolling();
    }

    closeLayer() {
        const {onClose} = this.props;
        PreventScrolling.enableScrolling(true);
        onClose(true);
    }

    render() {
        
        const {onClose,isOpen} = this.props;
        if(isOpen) {
          this.beforeOpen();
        }
    
        if(isOpen){            
            return (      <div> 
                              <FullPagePopup innerHtml={this.props.data} closeLayer={this.closeLayer.bind(this)} heading={this.props.heading} regFormId={this.props.regFormId} />
                          </div>
            );
        } else {            
          return null; 
        }
    }

    setFormHeight() {

        let regFormId = this.props.regFormId;
        if(this.props.isOpen && this.props.isResponseForm) {
            setTimeout(function() {
                var winH = window.innerHeight;
                var calH = 175;
                if(typeof(document.getElementById('sndscreenSroll_'+regFormId)) != 'undefined' && document.getElementById('sndscreenSroll_'+regFormId) != null) {
                    document.getElementById('sndscreenSroll_'+regFormId).style.maxHeight = (winH-calH)+'px';
                }
            },500);
        }
    }

    componentDidUpdate() {
        
        this.setFormHeight();
        var self = this;
        window.addEventListener("orientationchange", function() {
            document.activeElement.blur();
            self.setFormHeight();
        });

    }

    componentDidMount() {
        
		this.setFormHeight();
        var self = this;
        window.addEventListener("orientationchange", function() {
            document.activeElement.blur();
            self.setFormHeight();
        });
        
    }

}

CustomFormLayer.propTypes = {
   onClose : PropTypes.func
}
export default CustomFormLayer;