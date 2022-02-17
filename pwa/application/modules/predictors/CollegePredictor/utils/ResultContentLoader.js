import React from 'react';
import  './../assets/contentLoader.css';
import {connect} from 'react-redux';
import {getUrlParameter} from "../../../../utils/commonHelper";

const ResultContentLoader = (props) => {
	let tabType = (getUrlParameter('tab')) ? getUrlParameter('tab') : 'college';
	let filterList = [];
	for(let i=0;i<=5;i++){
		let filterLoader = (<div key={"filter_loader" + i} className="filter-block">
			<h2 className="f14_bold"><span className="loader-line shimmer wdt85"></span></h2>
			<div className="filter-content">
				<div className="fix-scroll">
					<ul className="sidebar-filter">
						<li className="enable"> <label className="checkbox-label" htmlFor="chck-city-278">
							<span className="loader-line shimmer"></span></label>
						</li>
						<li className="enable"> <label className="checkbox-label" htmlFor="chck-city-278">
							<span className="loader-line shimmer wdt85"></span></label>
						</li>
						<li className="enable"> <label className="checkbox-label" htmlFor="chck-city-278">
							<span className="loader-line shimmer"></span></label>
						</li>
						<li className="enable"> <label className="checkbox-label" htmlFor="chck-city-278">
							<span className="loader-line shimmer wdt85"></span></label>
						</li>
						<li className="enable"> <label className="checkbox-label" htmlFor="chck-city-278">
							<span className="loader-line shimmer"></span></label>
						</li>
					</ul>
				</div>
			</div>
		</div>);
		filterList.push(filterLoader);
	}
	let desktopFilters = null, tabLoader = null, saveListCTA = null;
	if(props.deviceType === 'desktop'){
		desktopFilters = <React.Fragment>
			<div className='fltlft filter-area'>
				<div className="cp-filters desk-filter">
					<label className="nav_main_head">Filters </label>
					{filterList}
				</div>
			</div>
		</React.Fragment>;
		tabLoader = <div className="fltryt">
			<div className="table specialization-tab"><span className="table-cell">View By: <a><span id="tabCollege" className={(tabType == 'college') ? "tab active" : "tab" }><i className="college-icon"></i>Colleges</span></a>
			    </span><span className="table-cell"><a className="blackLink" ><span id="tabSpecialization" className={(tabType == 'branch') ? "tab active" : "tab" }><i className="specialization-icon"></i>Specialization</span></a>
			    </span>
			</div>
		</div>;
		saveListCTA = <div className="fltryt">
			<div className="table cta-cont">
				<div className="table-cell">
					<button className="button button--secondary">Save List</button>
				</div>
			</div>
		</div>;
	}
	return (
		<div className='collegeShortlistResult cpDesktop'>
			<div className='pwa_pagecontent'>
				<div className='pwa_container'>
					<div className='college-shortlist-details'>

					<div className="shadow-box box clearFix">
					    <div className="fltlft rsltArea">
						    <div className="flex left">
						        <div className="flex-item result"><strong className="h1"><div className="loader-line shimmer wdt15" style={{minWidth:33}}> </div> Colleges</strong><span className="fw-nrml"> found for</span></div>
						        <div className="flex-item modify"><a ><strong className="link modifySrch lead1"><i className="refresh-icon"></i> Modify Search </strong></a></div>
						    </div>
						    <div>
						        <div>
						          <strong><div className="loader-line shimmer wdt25"> </div></strong>
						        </div>
						    </div>
						</div>
						{saveListCTA}
					</div>
					{props.deviceType != 'desktop' && 
					<div className="fixedTop-wrapper">
					    <div className="shadow-box" id="mobileFilter">
					        <div className="ctp-filter-sec" id="fixed-card">
					            <div className="flex filter-area">
					                <div className="filterBlock"><i className="filter-icon"></i>Filters <span className="filterCount"></span></div>
					                <div className="optionBlock">
					                    <div className="sliderWrapper">
					                        <div className="filter-items"><span className="gredient-corner left"></span><span className="gredient-corner right"></span>
					                            <ul className="filter-item-list">
					                                <li><span className="filter-capsule">Location</span></li>
					                                <li><span className="filter-capsule">Exams</span></li>
					                                <li><span className="filter-capsule">Fees</span></li>
					                                <li><span className="filter-capsule">Specialization</span></li>
					                                <li><span className="filter-capsule">Ownership</span></li>
					                                <li><span className="filter-capsule">Institute</span></li>
					                            </ul>
					                        </div>
					                    </div>
					                </div>
					            </div>
					        </div>
					        <div className="table specialization-tab"><span className="table-cell"><a className={(tabType == 'college') ? "activeTab" : "" }><span id="tabCollege" className={(tabType == 'college') ? "tab active" : "tab" }><i className="college-icon"></i>Colleges</span></a>
					            </span><span className="table-cell"><a className={(tabType == 'branch') ? "activeTab" : "" }><span id="tabSpecialization" className={(tabType == 'branch') ? "tab active" : "tab" }><i className="specialization-icon"></i>Specialization</span></a>
					            </span>
					        </div>
					    </div>
					</div>
					}
						<div className='college-shortlist-content clearFix'>
							{desktopFilters}
							<div className='fltryt card-area cp-results'>
								<div className="tab-sec box clearFix">
								    <div className="fltlft">
										<p className="infoText">Colleges that come under home state quota are denoted by <span className="quota lead1">Home State</span></p>
								    </div>
									{tabLoader}
								</div>

								<div className='fltlft odd-data'>

									<div className="shortlist-tuple">
									    <div className="instituteCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div className="loader-line shimmer wdt25"> </div>
									    </div>
									    <div className="courseCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									    </div>
									    <div className="courseCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									    </div>
									</div>

									<div className="shortlist-tuple">
									    <div className="instituteCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div className="loader-line shimmer wdt25"> </div>
									    </div>
									    <div className="courseCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									    </div>
									    <div className="courseCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									    </div>
									</div>
								</div>
								<div className='fltryt even-data'>
									<div className="shortlist-tuple">
									    <div className="instituteCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div className="loader-line shimmer wdt25"> </div>
									    </div>
									    <div className="courseCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									    </div>
									    <div className="courseCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									    </div>
									</div>
									<div className="shortlist-tuple">
									    <div className="instituteCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div className="loader-line shimmer wdt25"> </div>
									    </div>
									    <div className="courseCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									    </div>
									    <div className="courseCard">
									        <div className="loader-line shimmer wdt85"> </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									        <div>
										        <div className="loader-line shimmer wdt25"> </div>
									        </div>
									    </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
};

function mapStateToProps(state){
	return{
		cpFilters : state.collegePredictorFilterData
	}
}
export default connect(mapStateToProps)(ResultContentLoader);
