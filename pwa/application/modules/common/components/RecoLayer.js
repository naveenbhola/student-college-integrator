
// Desc : this component is used for Request E brochure reco layer from AMP to PWA

import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { getRequest } from './../../../utils/ApiCalls';
import {setCookie, getCookie, showToastMsg, getEBCookie} from '../../../utils/commonHelper';
import APIConfig from './../../../../config/apiConfig';
import FullPageLayer from './FullPageLayer';
import CategoryTuple from './../../listing/categoryList/components/CategoryTuple';

class RecoLayer extends Component {
    
    constructor(props)
    {
        super(props);
        this.state = { layerOpen :false,showMailerMessage:false};
    }

    downloadEBFromAMP(){
        var ampData = this.checkFromAMP();
        var listingType = ampData[3];
        var keys = {};
        var url = (typeof(window) != 'undefined') ? window.location.href : '';
        if(url.indexOf('=brochure') !='-1'){
            
            if(listingType == "institute"){
                keys = {'trackid':1769,'recoEbTrackid':1063,'recoCMPTrackid':1074,'recoShrtTrackid':1208};
            }
            else if(listingType == "university"){
                keys = {'trackid':1765,'recoEbTrackid':1077,'recoCMPTrackid':1074,'recoShrtTrackid':1070};
            }
            else if(listingType == "coursepage"){
                keys = {'trackid':955,'recoEbTrackid':1073,'recoCMPTrackid':1074,'recoShrtTrackid':1075};
            }
            else{
                keys = {'trackid':1198,'recoEbTrackid':1222,'recoCMPTrackid':1223,'recoShrtTrackid':1224};
            }

        }else if(url.indexOf('=shortlist') !='-1'){

            if(listingType == "institute"){
                keys = {'trackid':1771,'recoEbTrackid':1063,'recoCMPTrackid':1074,'recoShrtTrackid':1208};
            }
            else if(listingType == "university"){
                keys = {'trackid':1767,'recoEbTrackid':1077,'recoCMPTrackid':1074,'recoShrtTrackid':1070};
            }
            else if(listingType == "coursepage"){
                keys = {'trackid':954,'recoEbTrackid':1073,'recoCMPTrackid':1074,'recoShrtTrackid':1075};
            }
            else{
                keys = {'trackid':'','recoEbTrackid':'','recoCMPTrackid':'','recoShrtTrackid':''};
            }
        }

        return keys;
    }

    componentDidMount(){
        console.log('aaaaaaaaaaaaaaaaa',this.props.courseId);
        var ampData = this.checkFromAMP();
        /*if(typeof(ampData) != 'undefined' && ampData.length>0 && ampData[1] && ampData[0] == 'brochure'){
            this.getRecoData(ampData[1]);
        }*/

        // Call reco layer for loggedIn User from AMP to CLP
        var url = (typeof(window) != 'undefined') ? window.location.href : '';
        
        if(this.props.courseId > 0) {
            if((url.indexOf('=brochure') !='-1' && ampData.length<=0 && !getEBCookie(this.props.courseId)) || (url.indexOf('=shortlist') !='-1')){ 
                this.getRecoData();
            }   
        }
    }

    render(){
      return(
        <React.Fragment>

            <FullPageLayer data={this.getTupleHTML()} heading={'Students who showed interest in this institute also looked at'} onClose={this.closeRecoLayer.bind(this)} isOpen={this.state.layerOpen} showMailerMessage={this.state.showMailerMessage}/>

        </React.Fragment>
      );
    }

    closeRecoLayer(){
        this.setState({layerOpen : false});
    }

    getRecoData(courseId){
        var courseId = (typeof(this.props.courseId) != 'undefined') ? this.props.courseId : courseId;
        getRequest(APIConfig.GET_RECOMMENDATION+'?courseId='+courseId).then((response) => {
            if(response.data.data){
                this.setState({recoData:response.data.data,showMailerMessage:response.data.data.showMailerMessage, layerOpen:true});
                setCookie('fromAMPCTA', '', 0, 'seconds');
            }
        }).catch(function(err){});
    }

    getTupleHTML(){
        if(!this.state.recoData || !this.state.recoData.categoryInstituteTuple || typeof this.state.recoData.categoryInstituteTuple == 'undefined'){ 
            return null; 
        }        
        let ampKeysObj = this.downloadEBFromAMP();
        let amprecoEbTrackid   = (Object.keys(ampKeysObj).length) ? ampKeysObj.recoEbTrackid : '';
        let amprecoCMPTrackid  = (Object.keys(ampKeysObj).length) ? ampKeysObj.recoCMPTrackid : '';
        let amprecoShrtTrackid = (Object.keys(ampKeysObj).length) ? ampKeysObj.recoShrtTrackid : '';
        
        return(<CategoryTuple aggregateRatingConfig = {this.state.recoData.aggregateRatingConfig} isImageLazyLoad={false} recoData={this.state.recoData.categoryInstituteTuple} isCallReco={false} recoEbTrackid={amprecoEbTrackid} recoCMPTrackid={amprecoCMPTrackid} recoShrtTrackid={amprecoShrtTrackid} />);
    }

    checkFromAMP(){
        var ampDEB = (getCookie('fromAMPCTA')) ? atob(getCookie('fromAMPCTA')).split('::') : [];
        return ampDEB;
    }
}
export default RecoLayer;