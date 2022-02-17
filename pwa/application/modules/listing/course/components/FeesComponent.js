import PropTypes from 'prop-types'
import React from 'react';
//import {categoriesNameMapping } from './../config/courseConfig';
import FeesTemplate from './FeesComponentTemplate';
import {getRupeesDisplayableAmount} from './../utils/listingCommonUtil';


class Fees extends React.Component{
    constructor(props)
    {
        super(props);
    }
    formatFeesData()
    {
        var feesData = {};
        var nonLocationWise = true;
        let totalFeesData = this.props.fees.fees != null && typeof this.props.fees.fees.totalFees != 'undefined' && this.props.fees.fees.totalFees != null ? this.props.fees.fees.totalFees : {};
        if(this.props.fees.locationWiseFees != null)
        {
            feesData['isLocationLevelFeesAvailable'] = true;
            var clFees = (typeof this.props.currentLocation.fees != 'undefined') ? this.props.currentLocation.fees : {};
            for(var clfKey in clFees)
            {
                nonLocationWise = false;
                if(typeof feesData['feesInfo'] == 'undefined')
                {
                    feesData['feesInfo'] = {};
                }
                feesData['feesInfo'][clFees[clfKey]['category']] = clFees[clfKey]['fees_unit_name']+ " "+getRupeesDisplayableAmount(clFees[clfKey]['fees_value']);
                if(typeof  feesData['categoryNameMapping'] == 'undefined')
                {
                    feesData['categoryNameMapping'] = {};
                }
                feesData['categoryNameMapping'][clFees[clfKey]['category']] = this.props.fees.categoryNameMapping[clFees[clfKey]['category']];
            }
            var listing_location_id = typeof this.props.currentLocation['listing_location_id'] != 'undefined' ?  this.props.currentLocation['listing_location_id']  : '';
            if(listing_location_id != '')
                feesData['showDisclaimer'] = typeof this.props.fees.locationWiseFees[listing_location_id] != 'undefined' ? this.props.fees.locationWiseFees[listing_location_id].showDisclaimer : false;
            if(nonLocationWise && totalFeesData != null && Object.keys(totalFeesData).length > 0)
            {
                feesData['isLocationLevelFeesAvailable'] = false;
                this.formatNonLocationWiseFees(feesData);
            }
        }
        else if(totalFeesData != null && Object.keys(totalFeesData).length > 0)
        {
            this.formatNonLocationWiseFees(feesData);
        }
        if(typeof this.props.fees.description != 'undefined' && this.props.fees.description != null && this.props.fees.description.length > 0)
        {
            feesData['description'] = this.props.fees['description'];
        }
        var totalIncludes = this.props.fees != null && this.props.fees.fees != null && this.props.fees.fees.totalIncludes != null && typeof this.props.fees.fees.totalIncludes != 'undefined' && this.props.fees.fees.totalIncludes.length > 0 ? this.props.fees.fees.totalIncludes : [];
        if(totalFeesData != null && totalIncludes.length > 0 && !feesData['isLocationLevelFeesAvailable'])
        {
            feesData['totalIncludes'] = totalIncludes.join(', ');
        }
        return feesData;
    }
    formatNonLocationWiseFees(feesData)
    {

        let totalFeesData = typeof this.props.fees.fees.totalFees != 'undefined' && this.props.fees.fees.totalFees != null ? this.props.fees.fees.totalFees : {};
        feesData['isLocationLevelFeesAvailable'] = false;
        for(var tfData in totalFeesData)
        {
            if(typeof feesData['feesInfo'] == 'undefined')
            {
                feesData['feesInfo'] = {};
            }
            feesData['feesInfo'][tfData] = this.props.fees.feesUnitName +" "+ getRupeesDisplayableAmount(totalFeesData[tfData].value);

            if(typeof feesData['otpAndHostelFees'] == 'undefined')
                feesData['otpAndHostelFees'] = {};

            feesData['otpAndHostelFees'][tfData] = {};
            feesData['otpAndHostelFees'][tfData]['total'] = this.props.fees.feesUnitName +" "+getRupeesDisplayableAmount(totalFeesData[tfData].value);
            if(typeof  feesData['categoryNameMapping'] == 'undefined')
            {
                feesData['categoryNameMapping'] = {};
            }
            feesData['categoryNameMapping'][tfData] = this.props.fees.categoryNameMapping[tfData];
        }
        var durationWise = typeof this.props.fees.fees.fees != 'undefined' ? this.props.fees.fees.fees : {};
        if(Object.keys(durationWise).length > 0)
        {

            for(var dKey in durationWise)
            {
                for(var catKey in durationWise[dKey])
                {
                    if(typeof feesData['otpAndHostelFees'] == 'undefined')
                        feesData['otpAndHostelFees'] = {};

                    if(typeof feesData['otpAndHostelFees'][catKey]['durationWise'] == 'undefined')
                    {
                        feesData['otpAndHostelFees'][catKey]['durationWise'] = {};
                    }
                    feesData['otpAndHostelFees'][catKey]['durationWise'][dKey]= {};
                    feesData['otpAndHostelFees'][catKey]['durationWise'][dKey]['value'] = this.props.fees.feesUnitName +" "+getRupeesDisplayableAmount(durationWise[dKey][catKey].value);
                    feesData['otpAndHostelFees'][catKey]['durationWise'][dKey]['periodType'] = this.props.fees.fees.periodType;
                }
            }
        }
        var hostelFees = typeof this.props.fees.fees.hostelFees != 'undefined' ? this.props.fees.fees.hostelFees : {};
        if(Object.keys(hostelFees).length > 0)
        {
            for(let catKey in hostelFees)
            {
                if(typeof feesData['otpAndHostelFees'] == 'undefined')
                    feesData['otpAndHostelFees'] = {};

                if(typeof feesData['otpAndHostelFees'][catKey] == 'undefined')
                    feesData['otpAndHostelFees'][catKey] = {};
                feesData['otpAndHostelFees'][catKey]['hostel'] = this.props.fees.feesUnitName+" "+getRupeesDisplayableAmount(hostelFees[catKey]['value']);
            }
        }
        var otpValues = typeof this.props.fees.fees.oneTimePayment != 'undefined' ? this.props.fees.fees.oneTimePayment : {};
        if(Object.keys(otpValues).length > 0)
        {
            for(let catKey in otpValues)
            {
                if(typeof feesData['otpAndHostelFees'] == 'undefined')
                    feesData['otpAndHostelFees'] = {};
                if(typeof feesData['otpAndHostelFees'][catKey] == 'undefined')
                    feesData['otpAndHostelFees'][catKey] = {};
                feesData['otpAndHostelFees'][catKey]['otp'] = this.props.fees.feesUnitName+" "+getRupeesDisplayableAmount(otpValues[catKey]['value']);
            }
        }
        if( typeof this.props.fees.fees.showDisclaimer != 'undefined' && this.props.fees.fees.showDisclaimer)
        {
            feesData['showDisclaimer'] = this.props.fees.fees['showDisclaimer'];
        }
    }
    render()
    {
        const {fees} = this.props;
        var feesData = this.formatFeesData();
        var showFeeDetailCTA = false;
        if( (fees.fees.oneTimePayment &&  Object.keys(fees.fees.oneTimePayment).length) || feesData.showDisclaimer  || (fees.fees.totalIncludes && fees.fees.totalIncludes.length) || (fees.fees.hostelFees && Object.keys(fees.fees.hostelFees).length)  || fees.description || (fees.fees.fees && Object.keys(fees.fees.fees).length) ){
            showFeeDetailCTA = true;
        }
        let categories = typeof feesData.categoryNameMapping != 'undefined' ? Object.keys(feesData.categoryNameMapping).sort() : [];

        if(categories.length > 0 && Object.keys(feesData).length > 0)
        {
            feesData['showCategoryDropDown'] = true;
        }
        else if(Object.keys(feesData).length > 0)
        {
            feesData['showCategoryDropDown'] = false;
        }

        if(categories.length == 1 && categories.indexOf('general') > -1 && Object.keys(feesData).length > 0)
        {
            feesData['showCategoryDropDown'] = false;
        }
        if(Object.keys(feesData).length > 0)
            return (
                <FeesTemplate courseId={this.props.courseId} listingName={this.props.listingName} courseName={this.props.courseName} feesData={feesData} scholarshipData={this.props.scholarshipData} showFeeDetailCTA={showFeeDetailCTA} />
            );

        return null;

    }
}

export default Fees;

Fees.propTypes = {
  courseId: PropTypes.any,
  courseName: PropTypes.any,
  currentLocation: PropTypes.any,
  fees: PropTypes.any,
  listingName: PropTypes.any,
  scholarshipData: PropTypes.any
}