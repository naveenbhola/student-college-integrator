import PropTypes from 'prop-types'
import React from 'react';
import BreadCrumb from './../../common/components/BreadCrumb';
import SocialSharingBand from './../../common/components/SocialSharingBand';

const RankingPageInnerHeader = (props) => {
    return (
        <div className={(props.deviceType == 'mobile'? "_subcontainer ":"")+ "bg-white"}>
            {props.breadCrumb && <BreadCrumb breadCrumb={props.breadCrumb} /> }
            <h1 className="h1 clr_0">{props.rankingPageName}</h1>
            <div className="BHST_parent">
                <div className="BHST_child f12_normal clr_0" dangerouslySetInnerHTML={{__html : props.disclaimer}}></div>
            </div>
            <div className="BHST_read_more color-b" onClick={handleReadMoreClick.bind(this,props)}>Read More</div>
            <div className={ (props.deviceType == 'mobile') ? "socialShareRPTop" : "socialShareRPDeskTop"}><SocialSharingBand widgetPosition={"RP_Top"} deviceType={props.deviceType}/></div>
        </div>
    );
};

const handleReadMoreClick = (props) => {
    document.getElementsByClassName('BHST_parent')[0].style.maxHeight = 'none';
    document.getElementsByClassName('BHST_read_more')[0].style.display = 'none';
    props.filterStickyFunction();
};

export default RankingPageInnerHeader;

RankingPageInnerHeader.propTypes = {
  breadCrumb: PropTypes.any,
  deviceType: PropTypes.any,
  disclaimer: PropTypes.any,
  rankingPageName: PropTypes.any
}