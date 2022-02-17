import PropTypes from 'prop-types'
import React, { Component } from 'react';
import './../../../common/assets/Wikki.css';

class AdmissonPageMiddleSection extends Component
{
	constructor(props)
	{
		super(props);
	}

	ScrollToDiv(){
		var ele = document.getElementById("couses_Section");
		ele.scrollIntoView({block: "start", inline: "nearest", behavior: 'smooth'});
	}

	render(){
		const{data} = this.props;
		if(data.admissionDetails == null){
			return null;
		}
		const hideAdmissionNav = (this.props.isPdfGenerator != null && this.props.isPdfGenerator) ? this.props.isPdfGenerator : false;
		return (
			<React.Fragment>
				<section>
				{!hideAdmissionNav  &&
					<div className="admission_nav">
						<div className="nav_list">
							<ul>
								<li> <a href="javascript:void(0)" className="active">About Admission</a> </li>
								{this.props.selectedStreamId &&
								<li>
									<a href="javascript:void(0);" onClick={this.ScrollToDiv.bind(this)}>Course Information</a>
								</li>
								}
							</ul>
						</div>
					</div>
				}
					<div className="_conatiner">
						<div className="_subcontainer">
							<div className="wikkiContents">
								{data.admissionPostedDate != null ? <p className="post-date">Updated on {data.admissionPostedDate}</p> : ''}
								<div dangerouslySetInnerHTML={{
									__html:(data.admissionDetails)
								}}></div>
							</div>
						</div>
					</div>
				</section>
			</React.Fragment>
		)
	}
}

export default AdmissonPageMiddleSection;

AdmissonPageMiddleSection.propTypes = {
	data: PropTypes.any,
	selectedStreamId: PropTypes.any
}