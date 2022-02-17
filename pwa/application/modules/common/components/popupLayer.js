import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import popupLayerCSS from '../assets/popupLayerCSS.css';

import PreventScrolling from './../../reusable/utils/PreventScrolling';

class Popup extends Component {

  render() {
    if(typeof window === 'undefined'){
      return null;
    }
    return ReactDOM.createPortal(
      this.prepareHtml(),
      document.getElementById('popup-container'),
    );
  }

  prepareHtml() {
    return (
      <div className="black-screen bcglayer">
             <div className={"layer-blink "+this.props.layerCssClass}>
              { this.props.heading != '' && <div className="pwa-hd">
                <p className="flLt">{this.props.heading}</p>
                <a href="javascript:void(0);" className="lyr-crs" onClick={this.props.closePopup}>&times;</a>
                <p className="clr"></p>
              </div>}
              <div className="static-layer">
                    {this.props.heading == '' && <i className="close-tip" onClick={this.props.closePopup}>&times;</i>}
                    {this.props.onContentClickClose ? <div className="option_wrap"  onClick={this.props.closePopup}>
                        {this.props.innerHtml}
                    </div> : <div className="option_wrap">
                        {this.props.innerHtml}
                    </div>}
                 </div>
          </div>
      </div>
    );
  }
}
Popup.defaultProps = {
  heading : '',
  layerCssClass : ''
};

class popupLayer extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isPopUpOn: this.props.isPopUpOn
    };
  }

  componentDidMount() {
    this.props.onRef(this);
    if(this.state.isPopUpOn){
      PreventScrolling.disableScrolling();
    }
  }
  componentWillReceiveProps(nextProps, nextContext) {
    if(this.props.isPopUpOn !== nextProps.isPopUpOn && nextProps.isPopUpOn){
      this.setState({isPopUpOn : true}, () => {
        PreventScrolling.disableScrolling();
      });
    }else if(this.props.isPopUpOn !== nextProps.isPopUpOn && !nextProps.isPopUpOn){
      this.setState({isPopUpOn : false}, () => {
        PreventScrolling.enableScrolling();
      });
    }
  }

  componentWillUnmount() {
    this.props.onRef(undefined)
  }

  open() {
    this.setState({
      isPopUpOn: !this.state.isPopUpOn
    },function()
    {
      if(this.state.isPopUpOn)
      {
          PreventScrolling.disableScrolling();
      }
      else
      {
        if(this.props.closePopup) {
          this.props.closePopup();
        }
        PreventScrolling.enableScrolling();
      }
    });
  }
  close()
  {
    this.setState({isPopUpOn : false},function(){
        PreventScrolling.enableScrolling()
      });
  }
  render() {
    return (
       <React.Fragment>
        {
          this.state.isPopUpOn ? <Popup innerHtml={this.props.data} closePopup={this.open.bind(this, this.state.isPopUpOn)} heading={this.props.heading} layerCssClass={this.props.layerCssClass} onContentClickClose={this.props.onContentClickClose}/> : null
        }
      </React.Fragment>
    );
  }
}
popupLayer.defaultProps = {
  onContentClickClose : true,
  isPopUpOn : false,
  layerCssClass : ''
};
export default popupLayer;
