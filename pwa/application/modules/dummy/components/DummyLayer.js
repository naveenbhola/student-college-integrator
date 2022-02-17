import React, { Component } from 'react';
import ExamResponseForm from './../../user/ExamResponse/components/ExamResponseForm';
import { viewedResponse } from './../../user/utils/Response';

class DummyLayer extends Component {

    constructor(props) {
        super(props);
        this.state = {
            examGroupId: 9,
            trackingKeyId: 1234,
            actionType: 'viewedResponse',
            listingType: 'exam',
            open: false
        }
        
        this.setCTA           = this.setCTA.bind(this);
        this.openFormLayer  = this.openFormLayer.bind(this);
        this.closeFormLayer = this.closeFormLayer.bind(this);
    }

    canUseDOM() {
        return !!(typeof window !== 'undefined' && window.document && window.document.createElement);
    }

    getViewedResponse(viewedResponseData){
        viewedResponse(viewedResponseData);
    }

    render() {
        let viewedResponseData = {
            "listingId":this.state.examGroupId,
            "trackingKeyId":this.state.trackingKeyId,
            "actionType":this.state.actionType,
            "listingType":this.state.listingType
            };

        return (
            <React.Fragment>
                <div className="btn-dummy inpt-div">
                    <ul>
                        <li>
                            <label>Enter Exam Group ID: </label><input type='text' name='examGroup' id='examGroup' /><br/>
                        </li>
                        <li>
                            <label>Enter Tracking Key ID: </label><input type='text' name='trackingKey' id='trackingKey' /><br/>
                        </li>
                        <li>
                            <label>Enter Listing Type: </label><input type='text' name='listingType' id='listingType' /><br/>
                        </li>
                        <li>
                            <a className="dummy" href="javascript:void(0);" onClick={this.setCTA.bind(this)}>Submit</a>
                        </li>
                        <li>
                            <a className="dummy" href="javascript:void(0);" onClick={this.getViewedResponse.bind(this,viewedResponseData)}>Viewed Response</a>
                        </li>
                    </ul>
                </div>
                <div className="btn-dummy">
                    <a id="dummy" href="javascript:void(0);" className="dummy" onClick={this.openFormLayer.bind(this)}>Download Guide</a>

                    <ExamResponseForm openResponseForm={this.state.open} examGroupId={this.state.examGroupId} cta="examDownloadGuide" actionType="downloadGuide" trackingKeyId={this.state.trackingKeyId}  onClose={this.closeFormLayer.bind(this)} />
                </div>
            </React.Fragment>
        );
    }

    setCTA() {
        let examGroupId   = document.getElementById('examGroup').value;
        let trackingKeyId = document.getElementById('trackingKey').value;
        let listingType = document.getElementById('listingType').value;
        if(listingType=="")
        {
            listingType = "exam";
        }
        this.setState({
            examGroupId: examGroupId,
            trackingKeyId: trackingKeyId,
            listingType: listingType
        }, () => alert("values submitted, now click CTA"));
    }

    openFormLayer() {
        this.setState({open: true});
    }

    closeFormLayer() {
        this.setState({open: false});
    }

}
export default DummyLayer;
