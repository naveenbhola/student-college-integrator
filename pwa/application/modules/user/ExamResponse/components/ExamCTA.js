import React, { Component } from 'react';
import ExamResponseForm from './ExamResponseForm';

class ExamCTA extends Component {

    constructor(props) {
        super(props);
        this.state = {
            open: false
        }
        this.openFormLayer  = this.openFormLayer.bind(this);
        this.closeFormLayer = this.closeFormLayer.bind(this);
    }

    canUseDOM() {
        return !!(typeof window !== 'undefined' && window.document && window.document.createElement);
    }

    render() {
        
        return (
            <React.Fragment>
                <a id={this.props.ctaId} href="javascript:void(0);" className={this.props.ctaClass} onClick={this.openFormLayer.bind(this)}>{this.props.ctaText}</a>
                <ExamResponseForm openResponseForm={this.state.open} examGroupId={this.props.examGroupId} cta={this.props.cta} actionType={this.props.actionType} trackingKeyId={this.props.trackingKeyId} callBackFunction={this.props.callBackFunction.bind(this)} onClose={this.closeFormLayer} />
            </React.Fragment>
        );
    }

    openFormLayer() {
        this.props.clickFunction();
        this.setState({open: true});
    }

    closeFormLayer() {
        this.props.closeFunction();
        this.setState({open: false});
    }

}
export default ExamCTA;