import React, {Component} from "react";
import "../assets/OCF.css";
import Analytics from "../../reusable/utils/AnalyticsTracking";
import {getObjectSize} from "../../../utils/commonHelper";

class TCF extends Component {
    constructor(props) {
        super(props);

    }
    trackEvent = (actionLabel, label) => {
        const categoryType = this.props.gaTrackingCategory;
        Analytics.event({category : categoryType, action : actionLabel, label : label});
    };
    onFilterClick = filterType => () => {
        this.trackEvent('TCF_'+ filterType, 'click');
        this.props.tcfAction(filterType);
    };
    generateTCF(){
        const {filterOrder, filters, nameMapping} = this.props;
        if(getObjectSize(filters) === 0 || getObjectSize(filterOrder) === 0 ) {
            this.setState({showLoader: true});
            return null;
        }
        let tcfList = [];
        for(let filterType of filterOrder) {
            if(!filters[filterType])
                continue;
            tcfList.push(<li key={'TCF_'+filterType} onClick={this.onFilterClick(filterType)}
            >{nameMapping[filterType]}</li>)
        }
        return (
            <div id="inline-filter" className="ocfTuple">
                <div className="ocf-filter-sec ctp-SrpLst" id="fixed-card">
                    <strong className="filter_name">Filter Colleges</strong>
                    <div className="scroll_div">
                        <ul className="filterUl">
                            {tcfList}
                        </ul>
                    </div>
                </div>
            </div>
        );
    }
    render(){
        return this.generateTCF();
    }
} export default TCF;