import React from 'react';
import './../assets/ResultTuple.css';
import InlineFilterWidget from './InlineFilterWidget';
import TopCard from './TopCard';

class ResultTuple extends React.Component{
	constructor()
	{
		super();
	}

	render()
	{
		return (
			<div className="college-shortlist-details">
				
				{<TopCard deviceType={this.props.deviceType}/>}

				<div className="selected-filters box desktopOnly">
					selected filters
				</div>
				<div className="fltlft filter-area desktopOnly">
					I am filter
				</div>
				<div className="fltryt card-area">
					<div className="tab-sec box clearFix">
						<div className="fltlft">
							<ul className="bullet-list">
								<li>Colleges that come under home state quota are denoted by <span className="quota lead1">Home State</span></li>
								<li>Download application details.read review and compare colleges for courses you like.</li>
							</ul>
						</div>
						<div className="fltryt desktopOnly">
							<div className="table specialization-tab">
								<span className="table-cell">View By: <span id="tabCollege" className="tab active"><i className="college-icon"></i>Colleges</span></span>
								<span className="table-cell"><span id="tabSpecialization" className="tab"><i className="specialization-icon"></i>Specialization</span></span>
							</div>
						</div>
					</div>
					<div className="fltlft odd-data">
						<div className="shortlist-tuple">
							<div className="instituteCard">
								<p className="instituteName"><strong>Indian Institute of Technology Delhi</strong></p>
								<p className="instituteLocation">Delhi</p>
								<p>Ranked by No 1 <a className="link" href="#">Business today</a></p>
							</div>
							<div className="courseCard">
								<p>
									<a className="fnt-w6" href="#">Post Graduate Diploma in Management</a>
								</p>
								<div className="rating-widget">
									aggregate rating widget
								</div>
								<div className="er-detail">
									<label>
									Exams:
									</label>
									<span className="">
									<a href="#"> CMAT</a>,
									<a href="#"> CAT</a>
									</span>
									<span className="pipe">|</span>
									<label>Round</label><span className="black">6</span>
									<span className="pipe">|</span>
									<label>Rank</label><span className="black">1200</span>					
								</div>
								<i className="course-shrtlst"></i>
							</div>
							<div className="loadMore">
								<strong className="link">View All Branches<i className="arrow more"></i></strong>
							</div>
						</div>
						<div className="shortlist-tuple">
							<div className="instituteCard">
								<p className="instituteName"><strong>Indian Institute of Technology Delhi</strong></p>
								<p className="instituteLocation">Delhi</p>
								<p>Ranked by No 1 <a className="link" href="#">Business today</a></p>
							</div>
							<div className="courseCard">
								<p>
									<a className="fnt-w6" href="#">Post Graduate Diploma in Management</a>
								</p>
								<div className="rating-widget">
									aggregate rating widget
								</div>
								<div className="er-detail">
									<label>
									Exams:
									</label>
									<span className="">
									<a href="#"> CMAT</a>,
									<a href="#"> CAT</a>
									</span>
									<span className="pipe">|</span>
									<label>Round</label><span className="black">6</span>
									<span className="pipe">|</span>
									<label>Rank</label><span className="black">1200</span>					
								</div>
								<i className="course-shrtlst"></i>
							</div>
							<div className="courseCard">
								<p>
									<a className="fnt-w6" href="#">Post Graduate Diploma in Management</a>
								</p>
								<div className="rating-widget">
									aggregate rating widget
								</div>
								<div className="er-detail">
									<label>
									Exams:
									</label>
									<span className="">
									<a href="#"> CMAT</a>,
									<a href="#"> CAT</a>
									</span>
									<span className="pipe">|</span>
									<label>Round</label><span className="black">6</span>
									<span className="pipe">|</span>
									<label>Rank</label><span className="black">1200</span>					
								</div>
								<i className="course-shrtlst"></i>
							</div>
							<div className="courseCard">
								<p>
									<a className="fnt-w6" href="#">Post Graduate Diploma in Management</a>
								</p>
								<div className="rating-widget">
									aggregate rating widget
								</div>
								<div className="er-detail">
									<label>
									Exams:
									</label>
									<span className="">
									<a href="#"> CMAT</a>,
									<a href="#"> CAT</a>
									</span>
									<span className="pipe">|</span>
									<label>Round</label><span className="black">6</span>
									<span className="pipe">|</span>
									<label>Rank</label><span className="black">1200</span>					
								</div>
								<i className="course-shrtlst"></i>
							</div>
							<div className="loadMore">
								<strong className="link">View All Branches<i className="arrow more"></i></strong>
							</div>
						</div>

						{<InlineFilterWidget/>}

					</div>


					<div className="fltryt even-data">
						<div className="shortlist-tuple">
							<div className="instituteCard">
								<p className="instituteName"><strong>Indian Institute of Technology Delhi</strong></p>
								<p className="instituteLocation">Delhi</p>
								<p>Ranked by No 1 <a className="link" href="#">Business today</a></p>
							</div>
							<div className="courseCard">
								<p>
									<a className="fnt-w6" href="#">Post Graduate Diploma in Management</a>
								</p>
								<div className="rating-widget">
									aggregate rating widget
								</div>
								<div className="er-detail">
									<label>
									Exams:
									</label>
									<span className="">
									<a href="#"> CMAT</a>,
									<a href="#"> CAT</a>
									</span>
									<span className="pipe">|</span>
									<label>Round</label><span className="black">6</span>
									<span className="pipe">|</span>
									<label>Rank</label><span className="black">1200</span>					
								</div>
								<i className="course-shrtlst"></i>
							</div>
							<div className="courseCard">
								<p>
									<a className="fnt-w6" href="#">Post Graduate Diploma in Management</a>
								</p>
								<div className="rating-widget">
									aggregate rating widget
								</div>
								<div className="er-detail">
									<label>
									Exams:
									</label>
									<span className="">
									<a href="#"> CMAT</a>,
									<a href="#"> CAT</a>
									</span>
									<span className="pipe">|</span>
									<label>Round</label><span className="black">6</span>
									<span className="pipe">|</span>
									<label>Rank</label><span className="black">1200</span>					
								</div>
								<i className="course-shrtlst"></i>
							</div>
							<div className="courseCard">
								<p>
									<a className="fnt-w6" href="#">Post Graduate Diploma in Management</a>
								</p>
								<div className="rating-widget">
									aggregate rating widget
								</div>
								<div className="er-detail">
									<label>
									Exams:
									</label>
									<span className="">
									<a href="#"> CMAT</a>,
									<a href="#"> CAT</a>
									</span>
									<span className="pipe">|</span>
									<label>Round</label><span className="black">6</span>
									<span className="pipe">|</span>
									<label>Rank</label><span className="black">1200</span>					
								</div>
								<i className="course-shrtlst"></i>
							</div>
							<div className="loadMore">
								<strong className="link">View All Branches<i className="arrow more"></i></strong>
							</div>
						</div>
						
						{<InlineFilterWidget/>}


						<div className="shortlist-tuple">
							<div className="instituteCard">
								<p className="instituteName"><strong>Indian Institute of Technology Delhi</strong></p>
								<p className="instituteLocation">Delhi</p>
								<p>Ranked by No 1 <a className="link" href="#">Business today</a></p>
							</div>
							<div className="courseCard">
								<p>
									<a className="fnt-w6" href="#">Post Graduate Diploma in Management</a>
								</p>
								<div className="rating-widget">
									aggregate rating widget
								</div>
								<div className="er-detail">
									<label>
									Exams:
									</label>
									<span className="">
									<a href="#"> CMAT</a>,
									<a href="#"> CAT</a>
									</span>
									<span className="pipe">|</span>
									<label>Round</label><span className="black">6</span>
									<span className="pipe">|</span>
									<label>Rank</label><span className="black">1200</span>					
								</div>
								<i className="course-shrtlst"></i>
							</div>
							<div className="courseCard">
								<p>
									<a className="fnt-w6" href="#">Post Graduate Diploma in Management</a>
								</p>
								<div className="rating-widget">
									aggregate rating widget
								</div>
								<div className="er-detail">
									<label>
									Exams:
									</label>
									<span className="">
									<a href="#"> CMAT</a>,
									<a href="#"> CAT</a>
									</span>
									<span className="pipe">|</span>
									<label>Round</label><span className="black">6</span>
									<span className="pipe">|</span>
									<label>Rank</label><span className="black">1200</span>					
								</div>
								<i className="course-shrtlst"></i>
							</div>
							<div className="courseCard">
								<p>
									<a className="fnt-w6" href="#">Post Graduate Diploma in Management</a>
								</p>
								<div className="rating-widget">
									aggregate rating widget
								</div>
								<div className="er-detail">
									<label>
									Exams:
									</label>
									<span className="">
									<a href="#"> CMAT</a>,
									<a href="#"> CAT</a>
									</span>
									<span className="pipe">|</span>
									<label>Round</label><span className="black">6</span>
									<span className="pipe">|</span>
									<label>Rank</label><span className="black">1200</span>					
								</div>
								<i className="course-shrtlst"></i>
							</div>
							<div className="loadMore">
								<strong className="link">View All Branches<i className="arrow more"></i></strong>
							</div>
						</div>
					</div>
				</div>
			</div>
		)
	}
}
export default ResultTuple;
