import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { postRequest } from './../../../utils/ApiCalls';
import {isUserLoggedIn} from '../../../utils/commonHelper';
import config from './../../../../config/config';
import PopupLayer from './popupLayer';
import Loadable from 'react-loadable';

const ResponseForm = Loadable({
  loader: () => import('./../../user/Response/components/ResponseForm'/* webpackChunkName: 'ResponseForm' */),
  loading() {return null},
});

class ApplyNow extends Component {
    
    constructor(props)
    {
        super(props);
        this.state = {enableRegLayer: false, openResponseForm: false};
    }

    componentDidMount(){
        if(isUserLoggedIn()){
            this.setState({enableRegLayer: true});
        }
    }

    render(){
      return(


        <React.Fragment>
            {this.props.showLastDate ?
            (<div key="dates" className='dot-div' >
                            <h2>{this.props.dateText} <strong className="apply-t">{this.props.displayDate}</strong></h2>
                            <p><button type='button' id="applynow" className='oafcta-btn' onClick={this.applyOnline.bind(this)}><i className="d-arrow"></i>{this.props.ctaName} </button></p>
                        </div>)
            :<button type='button' id="applynow" className='oafcta-btn' onClick={this.applyOnline.bind(this)}><i className="d-arrow"></i>{this.props.ctaName} </button>}

            <PopupLayer onRef={ref => (this.PopupLayer = ref)} data={this.alertHtml()} heading={'Application for '+this.props.instituteName} onContentClickClose={false}/>
            {this.state.enableRegLayer && <ResponseForm openResponseForm={this.state.openResponseForm} clientCourseId={this.props.courseId} listingType="course" cta="applyOnline" actionType="applyOnline" trackingKeyId={this.props.trackid} callBackFunction={this.callBackApplyNow.bind(this)} onClose={this.closeResponseForm.bind(this)}/>}
        </React.Fragment>
      );
    }

    applyOnline(){
        document.getElementById('applynow').style.pointerEvents = "none";        
        ResponseForm.preload().then(()=>{
            this.setState({enableRegLayer: true});
            this.callRegLayer();
        });
    }

    callRegLayer(self){
        this.setState({...this.state, openResponseForm: true});
    }

    callBackApplyNow(response){
        if(response.userId){
            this.emailResults()
        }
    }

    emailResults(){
        var postData = 'courseId='+this.props.courseId+'&instituteName='+btoa(this.props.instituteName)+'&isInternal='+this.props.isInternal+'&fromWhere=pwa';

        const axiosConfig = {headers: {'Content-Type': 'application/x-www-form-urlencoded'},withCredentials: true};
        postRequest(config().SHIKSHA_HOME+'/mOnlineForms5/OnlineFormsMobile/emailResults',postData,'',axiosConfig).then((response) => {
            if(response.data=='Successful'){
                this.setState({openResponseForm: false});
                document.getElementById('applynow').style.pointerEvents = "";
                this.alertMsg();
            }else{
                this.setState({openResponseForm: false});
                document.getElementById('applynow').style.pointerEvents = "";
            }
        }).catch(function(err){});
    }

    closeResponseForm() {
        document.getElementById('applynow').style.pointerEvents = "";
        this.setState({openResponseForm: false});
    }

    alertMsg(){
        this.PopupLayer.open();
    }

    alertHtml(){
        return(
                <React.Fragment>
                    <p>You have successfully started your application. We have sent you an email with the link to your application. Open that link on the desktop/laptop for an easy and smooth process to complete your submission.</p>
                </React.Fragment>
            );
    }
}
ApplyNow.defaultProps = {
  showLastDate : true, trackid : 986
};
export default ApplyNow;