import React from 'react';
import './../assets/CPZRP.css';
import Anchor from "../../../reusable/components/Anchor";
import {Ucfirst} from "../../../../utils/commonHelper";
const CPZRP = (props) => {
	let allCriteria = [], ctpgLinks = [];
	if(typeof props.criteriaData !== 'undefined' && props.criteriaData && props.criteriaData.length > 0){
		props.criteriaData.map((criteria) => {
			allCriteria.push(<p className='usr-criteria' key={'criteria'+criteria.examName}>{criteria.examName} {Ucfirst(criteria.resultType)} : {criteria.result}{criteria.resultType === 'rank' ? ' AIR' : ''}, {criteria.categoryName}</p>);
			if(criteria.ctpUrl && criteria.ctpUrl !== ''){
				ctpgLinks.push(<p key={'ctpg'+criteria.examName}><Anchor to={criteria.ctpUrl} className="button button--secondry">See Colleges accepting {criteria.examName}</Anchor></p>);
			}
		});
	}
	return (<div className='collegeShortlistResult cpDesktop' id='collegeShortlistResult'>
			<div className='pwa_pagecontent'>
				<div className='pwa_container'>
					<div className="shadow-box box zrp-box">
						<div className="flex column">
							<p>No result found for</p>
							{allCriteria}
							<p><Anchor to={props.modifySearchUrl} className="button button--secondary">Modify Search</Anchor></p>
							{ctpgLinks.length > 0 ? <React.Fragment><p><label className="lead1">OR</label></p>{ctpgLinks}</React.Fragment> : null}
						</div>
					</div>
				</div>
			</div>
		</div>);
};
CPZRP.defaultProps = {
	criteriaData : []
};
export default CPZRP;