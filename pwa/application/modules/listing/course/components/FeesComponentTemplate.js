import PropTypes from 'prop-types'
import React from 'react';
import './../assets/fees.css';
import './../assets/courseCommon.css';
import PopupLayer from './../../../common/components/popupLayer';
import GetFeeDetails from './../../../common/components/GetFeeDetail';
import {cutStringWithShowMore} from './../../../../utils/stringUtility';
import {capFirstLetterInWord} from './../utils/listingCommonUtil';
import Analytics from './../../../reusable/utils/AnalyticsTracking';

function bindFunctions(functions)
{
    functions.forEach( f => (this[f] = this[f].bind(this)));
}

class FeesTempalte extends React.Component{
    constructor(props)
    {
        super(props);
        this.state ={
            categoryName : 'general',
            displayCatName : 'General',
            feeStructureData : {},
            showFeeDetails:false
        };
        this.feeStructureLayer = [];
        bindFunctions.call(this,[
            "openOTPLayer"
        ])
    }
    isActive(catName)
    {
        return this.state.categoryName == catName;
    }

    trackEvent(actionLabel,label)
    {
        Analytics.event({category : 'CLP_PWA', action : actionLabel, label : label});
    }


    changeFeeDetails(){
        if(!this.state.showFeeDetails){
            this.setState({showFeeDetails:!this.state.showFeeDetails});
        }
    }

    openFeesStructureLayer(categoryName)
    {
        this.setState({feeStructureData : this.feeStructureLayer[categoryName]});
        this.feeStLayer.open();
    }

    generateFeeDetailHtml(){
        let html = [];
        if(!this.state.showFeeDetails)
            html.push(<div key="getFeedtl"><GetFeeDetails buttonText="Get Fee Details" listingId={this.props.courseId} listingName={this.props.listingName} trackid="2031" recoEbTrackid="1073" recoCMPTrackid="1074" recoShrtTrackid="1075" clickHandler={this.trackEvent.bind(this,'Get_Fee_Details','Response')} page = "coursePage" responseCallBack={this.changeFeeDetails.bind(this)} title={"Get Fees Details of "+this.props.courseName}/></div>);
        return html;
    }

    generateHtml(feesData)
    {
        let html = [];
        var counter = 0;
        var getFeeDetailHtml = [];
        if(this.props.showFeeDetailCTA){
            getFeeDetailHtml = this.generateFeeDetailHtml();
        }
        else if(!this.props.showFeeDetailCTA){
            this.changeFeeDetails();
        }
        for(var feeKey in feesData['feesInfo'])
        {
            let htmlContent = [];
            htmlContent.push(<p key="fee-h" className='fee-hdng'>Total Fees</p>);
            let viewStrHtml = [];
            if(!feesData.isLocationLevelFeesAvailable && typeof feesData['otpAndHostelFees'][feeKey]['durationWise'] != 'undefined' && Object.keys(feesData['otpAndHostelFees'][feeKey]['durationWise']).length > 0)
            {
                viewStrHtml.push(<a key="fees-struc" href="javascript:void(0);" className='nrml-link str-arw' onClick={this.openFeesStructureLayer.bind(this,feeKey)}> View Fees Structure <i className='blu-arrw'></i></a>);
            }
            htmlContent.push(<div key="fee-val"><strong key="fee-val-stng">{feesData['feesInfo'][feeKey]} </strong>{(this.state.showFeeDetails)?viewStrHtml:null}{getFeeDetailHtml}</div>);

            //htmlContent.push(<div key="show_feedtl">{getFeeDetailHtml}</div>);            
            var totalIncludesHtml = [];
            if(typeof feesData.totalIncludes != 'undefined' && feesData.totalIncludes != null)
            {
                if(this.state.showFeeDetails){
                    htmlContent.push(<p className='fee-det' key="fee-comp">(Fees Components : {feesData.totalIncludes})</p>);
                }
                totalIncludesHtml.push(<p className='fee-det' key="fee-comp">(Fees Components : {feesData.totalIncludes})</p>);
            }
            var otpHostelHtml = [];
            var otpHtml = [];
            var hostelHtml = [];
            if(typeof feesData['otpAndHostelFees'] != 'undefined')
            {
                if(typeof feesData['otpAndHostelFees'][feeKey]['otp'] != 'undefined' && feesData['otpAndHostelFees'][feeKey]['otp'].trim() != '')
                {
                    otpHtml.push(<li key="otp"><div>
                        <label key="otp-label" className='fee-hdng'>One Time Payment</label>
                        <strong key="otp-val-stng">{feesData['otpAndHostelFees'][feeKey]['otp']} <i className='info-icon' onClick={this.openOTPLayer}></i></strong>
                        <PopupLayer onRef={ref => (this.otpLayer = ref)} data="Note - Applicable if you want to pay the complete fees at one go" heading="One Time Payment"/>
                    </div></li>);
                }
                if(typeof feesData['otpAndHostelFees'][feeKey]['hostel'] != 'undefined' &&feesData['otpAndHostelFees'][feeKey]['hostel'].trim() != '')
                {
                    hostelHtml.push(<li key="hostel"><div>
                        <label key="hstl-key" className='fee-hdng'>Hostel Fees (Yearly)</label>
                        <strong key="hstl-val-key">{feesData['otpAndHostelFees'][feeKey]['hostel']}</strong>
                    </div></li>);
                }
                this.generateFeeStructureLayer(feeKey,feesData['feesInfo'][feeKey],feesData['otpAndHostelFees'],totalIncludesHtml);
            }
            otpHostelHtml.push(<ul className='fee-BnrLst' key={feeKey+'ul'}>{otpHtml}{hostelHtml}
                { typeof feesData['description'] != 'undefined' && feesData['description'].length > 0 &&
                <li key="desc">
                <input type="checkbox" className='read-more-state hide' id={"fees_desc_"+counter}/>
                <p className='read-more-wrap word-break' >{feesData['description']}</p>
                    </li>}
                    </ul>);
            counter ++;

            var showHtml = [];
            if(this.state.showFeeDetails){
                showHtml.push(otpHostelHtml);
            }
            html.push(<div className={(this.isActive(feeKey) || feeKey == 'noneAvailable') ? 'table-active' : 'table-dis table-el clp'} key={feeKey+"-feesdata"}>{htmlContent}{showHtml}</div>);
        }
        return html;
    }
    openOTPLayer()
    {
        this.otpLayer.open();
    }
    generateFeeStructureLayer(categoryName,totalFees,jsonData,totalIncludesHtml)
    {
        var html = [];
        var durationHtml = [];
        // var isHtmlExist = false;
        if(typeof jsonData[categoryName]['durationWise'] != 'undefined')
        {
            for(var duration in jsonData[categoryName]['durationWise'])
            {
                var periodType = jsonData[categoryName]['durationWise'][duration].periodType;
                durationHtml.push(<li key={categoryName+'-'+'duration'+ duration}>
                    <span key={periodType+duration}>{capFirstLetterInWord(periodType) +' '+parseInt(duration)}</span>
                    <span key={periodType+duration+'s'}>{jsonData[categoryName]['durationWise'][duration].value}</span>
                </li>);
                // isHtmlExist = true;
            }
        }
        durationHtml.push(<li key='total-duration'>
            <span key="tot-h">Total fees</span>
            <span key="tot-val">{totalFees}</span>
        </li>);
        html.push(<div key={categoryName+'layer'}>
            <div>
                <ul className='fee-div'>{durationHtml}</ul>
                {totalIncludesHtml}
            </div></div>);
        this.feeStructureLayer[categoryName] = {};
        this.feeStructureLayer[categoryName] = html;
    }
    openCategorySelection() {
        this.categoryLayer.open();
    }
    activeCategory(categoryName,displayCatName)
    {
        this.setState({ categoryName :  categoryName,displayCatName : displayCatName});
    }
    handleOptionClick(index)
    {
        const categoryNameMapping = this.props.feesData.categoryNameMapping;
        this.activeCategory(index,categoryNameMapping[index]);
        this.trackEvent('FeeType','Subwidget');
    }
    generateCategorySelectionLayer()
    {
        var html = [];
        var self = this;
        const categoryNameMapping = this.props.feesData.categoryNameMapping;
        if(Object.keys(categoryNameMapping).length > 0) {
            var categories = Object.keys(categoryNameMapping).sort();
            var layerHtml = categories.map(function(index){
                return <li key={index} onClick={self.handleOptionClick.bind(self,index)}>{categoryNameMapping[index]}</li>
            });
            html.push(<ul key="ullist" className='ul-list'>{layerHtml}</ul>);
        }
        return html;
    }
    render()
    {
        const {feesData} = this.props;
        var html = this.generateHtml(feesData);
        var noBottomSpace = this.props.scholarshipData.length>0?'noMrgn-pwa':'';
        return (
            <section className={'feeBnr listingTuple ' + noBottomSpace} id="fees">
                <div className='_container'>
                    <h2 className='tbSec2'>Fees</h2>
                    { feesData.showCategoryDropDown && <div className='dropdown-primary'>
                        <PopupLayer onRef={ref => (this.categoryLayer = ref)} data={this.generateCategorySelectionLayer()} heading={"Category"}/>
                        <label className='option-slctd' onClick={this.openCategorySelection.bind(this)}>{this.state.displayCatName + " Category"}</label>
                    </div> }
                    <div className='_subcontainer'>

                        {html}
                        {
                            (this.state.showFeeDetails && feesData['showDisclaimer']) && <p className='disclmr-pra'>Disclaimer: Total fees has been calculated bases year/semester 1 fees provided by the college. The actual fees may vary.</p>
                        }
                        <PopupLayer onRef={ref => (this.feeStLayer = ref)} data={this.state.feeStructureData} heading={"Fees Structure"}/>
                    </div>
                </div>
            </section>
        )
    }
}


export default FeesTempalte;

FeesTempalte.propTypes = {
    courseId: PropTypes.any,
    courseName: PropTypes.any,
    feesData: PropTypes.any,
    listingName: PropTypes.any,
    scholarshipData: PropTypes.any,
    showFeeDetailCTA: PropTypes.any
}