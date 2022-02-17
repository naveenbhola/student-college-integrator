import React from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import SingleLayerTemplate from './SingleLayerTemplate';
import MultiSelectLayerTemplate from './MultiSelectLayerTemplate';
import mstyles from './../assets/multiselect.css';
import PreventScrolling from './../../reusable/utils/PreventScrolling';
class SingleSelectLayer extends React.Component {

    constructor(props)
    {
        super(props);    
        this.state = {
            data : [],
            searchText : ''
        }
        this.stateOpen = false;
        this.scroll = 0;
    }
    closeLayer()
    {
        this.stateOpen = false;
        PreventScrolling.enableScrolling();
        this.props.onClose();
    }
    beforeOpen()
    {
        if(!this.stateOpen)
        {
            PreventScrolling.disableScrolling();
        }
        this.stateOpen = true;
    }
    getInputValue()
    {
        this.setState({'searchText': this.inputText.value.trim()});
    }
    renderSearchTemplate()
    {
        const {placeHolderText} = this.props;
        const {captionMsg} = this.props;
        return (<div className={"pwa-lyrSrc" + (captionMsg ? ' top83' : '')} key="search-tab">
            <i className='search-ico'></i>
            <input type="text" placeholder={placeHolderText} ref={(input) => this.inputText = input} onChange={this.getInputValue.bind(this)}/>
        </div>);
    }
    renderCaptionMsg()
    {
        const {captionMsg} = this.props;
        return captionMsg && captionMsg != '' && <p className="hd-desc">{captionMsg}</p>;
    }

  render() {
        if(!this.props.show) {
            if(this.stateOpen)
            {
                this.stateOpen = false;
                PreventScrolling.removeClassName('disable-scroll');
            }
          return null;
        }
        this.beforeOpen();
    return (
        <div className={this.props.isDesktop ? "singleLayer-container show" : "singleLayer-container"} >
            <div className={'pwa-lyr'+ (this.props.multiSelect ? ' multi-select' : '')}>
                <div className='pwa-hd'>
                    <p className='flLt'>{this.props.heading}</p>
                    <a href="javascript:void(0);" className='lyr-crs' onClick={this.closeLayer.bind(this)}>&times;</a>
                    <p className='clr'></p>
                </div>
                {this.renderCaptionMsg()}
                <div className="customSelectCountry">
                    {this.props.search && this.renderSearchTemplate()}
                    { this.props.data && Object.keys(this.props.data).length > 0 && !this.props.multiSelect ? <SingleLayerTemplate data={this.props.data} searchText={this.state.searchText} showSubHeading={this.props.showSubHeading} layerType={this.props.layerType} isAnchorLink={this.props.isAnchorLink} onClick={this.props.handleOptionClick} isLink={this.props.isLink} onCloseLayer={this.closeLayer.bind(this)}/>    : ((this.props.data && Object.keys(this.props.data).length > 0 && this.props.multiSelect) ? <MultiSelectLayerTemplate data={this.props.data} searchText={this.state.searchText} showSubHeading={this.props.showSubHeading} layerType={this.props.layerType} isAnchorLink={this.props.isAnchorLink} onSubmit={this.props.onSubmit}/>  : (!this.props.data ? <p className="lyr-noRslt-div">Sorry! we can't reach Shiksha servers right now. <br/> please try again later or check your connection</p> : <div style={{textAlign: 'center', marginTop: '7px', marginBottom: '10px', display: 'block'}} id="loader-id">
                            <img border="0" alt="" id="loadingImage1" className="small-loader" style={{borderadius:'50%',height: '40px',width: '40px'}} src="//images.shiksha.ws/public/mobile5/images/ShikshaMobileLoader.gif"/>
                        </div>))}
                </div>
            </div>
        </div>
    );
  }
}

SingleSelectLayer.propTypes = {
  onClose: PropTypes.func.isRequired,
  show: PropTypes.bool,
  children: PropTypes.node,
  isAnchorLink : PropTypes.bool,
  handleOptionClick : PropTypes.func
};
SingleSelectLayer.defaultProps = {
    isAnchorLink : true
}
export default SingleSelectLayer;
