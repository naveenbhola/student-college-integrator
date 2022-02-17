import React from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import CustomSingleSelectTemplate from './CustomSingleSelectTemplate';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';

class CustomSingleSelectLayer extends React.Component {

    constructor(props) {
        super(props);    
        this.state = {
            data : [],
            searchText : ''
        }
        this.stateOpen = false;
    }

    closeLayer() {
        this.stateOpen = false;
        PreventScrolling.enableFormScrolling();
        this.removeSearchText();
        this.props.onClose();
    }

    layerOpen() {
        if(!this.stateOpen) {
            PreventScrolling.disableFormScrolling();
        }
        this.stateOpen = true;
    }

    getInputValue() {
        this.setState({'searchText': this.inputText.value.trim()});
    }

    handleKeyUp() {
        var searchInput = this.inputText.value.trim();
        if(searchInput != '') {
            document.getElementById('searchCross').classList.remove('ih');
        } else {
            document.getElementById('searchCross').classList.add('ih');
        }
    }

    removeSearchText() {
        if(typeof(document.getElementById('search_input')) != 'undefined' && document.getElementById('search_input') != null){
            document.getElementById('search_input').value = '';
        }
        if(typeof(document.getElementById('searchCross')) != 'undefined' && document.getElementById('searchCross') != null){
            document.getElementById('searchCross').classList.add('ih');
        }
        this.setState({'searchText': ''});
    }

    renderSearchTemplate() {
        const placeHolderText = 'Search '+this.props.heading;
        return (
            <div className="pwa-lyrSrc crsSrc" key="search-tab">
                <div className="pwa-searchBox">
                    <i className="search-ico"></i>
                    <input id="search_input" type="text" placeholder={placeHolderText} ref={(input) => this.inputText = input} onChange={this.getInputValue.bind(this)} onKeyUp={this.handleKeyUp.bind(this)} />
                    <a className="clg-rmv ih" id="searchCross" onClick={this.removeSearchText.bind(this)}>&times;</a>
                </div>
            </div>
            );
    }

    render() {
        if(!this.props.show) {
            if(this.stateOpen) {
                this.stateOpen = false;
                PreventScrolling.enableFormScrolling();
            }
            return null;
        }

        var optgroup = false;
        if(this.props.optgroup) {
            optgroup = this.props.optgroup;
        }
        return (
            <div className="pwa-lyr">
                <div className="pwa-hd pwa-algnLft">
                    <i className="pwa-bckLyr" onClick={this.closeLayer.bind(this)}></i>
                    <strong className="greenBarHeading">{this.props.heading}</strong>
                </div>
                {this.props.search && ((Object.keys(this.props.data).length >= 5 || (optgroup)) && this.renderSearchTemplate())}
                { this.props.data && Object.keys(this.props.data).length > 0 ? <CustomSingleSelectTemplate data={this.props.data} sortOptions={this.props.sortOptions} searchText={this.state.searchText} fieldId={this.props.fieldId} showSubHeading={this.props.showSubHeading} optgroup={optgroup} removeSearchText={this.removeSearchText.bind(this)} clickHandlerFunction={this.props.clickHandlerFunction} />    : 
                    <div style={{textAlign: 'center', marginTop: '7px', marginBottom: '10px', display: 'block'}} id="loader-id">
                        <img border="0" alt="" id="loadingImage1" className="small-loader" style={{borderadius:'50%',height: '40px',width: '40px'}} src="//images.shiksha.ws/public/mobile5/images/ShikshaMobileLoader.gif"/>
                    </div>}
            </div>
        );
    }

    componentDidMount() {
        if(this.props.show) {
            this.layerOpen();
        }
    }

    componentDidUpdate() {
        if(this.props.show) {
            this.layerOpen();
        }
    }

}

CustomSingleSelectLayer.propTypes = {
    onClose: PropTypes.func.isRequired,
    show: PropTypes.bool,
    children: PropTypes.node
};
export default CustomSingleSelectLayer;