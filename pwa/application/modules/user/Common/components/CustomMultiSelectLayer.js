import React from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import CustomMultiSelectTemplate from './CustomMultiSelectTemplate';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';

class CustomMultiSelectLayer extends React.Component {

    constructor(props) {
        super(props);    
        this.state = {
            data : [],
            searchText : ''
        }
        this.selectItem = this.selectItem.bind(this);
        this.stateOpen  = false;
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
        const placeHolderText = 'Search '+this.props.searchHeading;
        return (
            <div className="pwa-lyrSrc" key="search-tab">
                <div className="pwa-searchBox">
                    <i className="search-ico"></i>
                    <input id="search_input" type="text" placeholder={placeHolderText} ref={(input) => this.inputText = input} onChange={this.getInputValue.bind(this)} onKeyUp={this.handleKeyUp.bind(this)} />
                    <a className="clg-rmv ih" id="searchCross" onClick={this.removeSearchText.bind(this)}>&times;</a>
                </div>
            </div>
            );
    }
    
    selectItem() {
        PreventScrolling.enableFormScrolling();
        this.removeSearchText();
        this.props.clickHandlerFunction();
    }

    render() {
        if(!this.props.show) {
            if(this.stateOpen) {
                this.stateOpen = false;
                PreventScrolling.enableFormScrolling();
            }
            return null;
        }
        
        return (
            <div className="pwa-lyr">
                <div className="pwa-hd pwa-algnLft">
                    <i className="pwa-bckLyr" onClick={this.closeLayer.bind(this)}></i>
                    <strong className="greenBarHeading">{this.props.heading.toUpperCase()}</strong>
                </div>
                {this.props.search && ((Object.keys(this.props.data).length > 7) && this.renderSearchTemplate())}
                { this.props.data && Object.keys(this.props.data).length > 0 ? <CustomMultiSelectTemplate data={this.props.data} searchText={this.state.searchText} fieldId={this.props.fieldId} showSubHeading={this.props.showSubHeading} clickHandlerFunction={this.props.clickHandlerFunction} />    : 
                    <div style={{textAlign: 'center', marginTop: '7px', marginBottom: '10px', display: 'block'}} id="loader-id">
                        <img border="0" alt="" id="loadingImage1" className="small-loader" style={{borderadius:'50%',height: '40px',width: '40px'}} src="//images.shiksha.ws/public/mobile5/images/ShikshaMobileLoader.gif"/>
                    </div>}
                <div className="done-btn dn-btm">
                    <input type="button" value="Done" className="green-btn done-clrBtn" id="saveMultiSelect" onClick={this.selectItem.bind(this)} />
                </div>
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

CustomMultiSelectLayer.propTypes = {
    onClose: PropTypes.func.isRequired,
    show: PropTypes.bool,
    children: PropTypes.node
};
export default CustomMultiSelectLayer;