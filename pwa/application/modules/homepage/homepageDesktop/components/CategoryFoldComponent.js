import React, { Component } from 'react';
import PropTypes from 'prop-types';
import '../assets/CategoryFold.css'
//import CategoryTabComponent from './CategoryTabComponent';
//import CategoryContainerComponent from './CategoryContainerComponent';

class CategoryFoldComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}
 	
  	render(){
  		return(
      	<section className="courseBanner">
                    <div className="_cntr">
                        <div className="tabSection">
                            <ul className="_ctFold">
                                <li data-index="1" className="active">Mba</li>
                                <li data-index="2" className="">B.TECH</li>
                                <li data-index="3" className="">Design</li>
                                <li data-index="4" className="">Law</li>
                                <li data-index="5" className="">More</li>
                            </ul>
                        </div>
                        <div className="tabContent" id="_tabCnt">
                            <div data-index="1" className="active">
                                <ul className="cFL">
                                    <li className="first">
                                        <a data-type="Exams" href="https://www.shiksha.com/mba/exams-pc-101">
                                            <div className="mba-exam"> <span> <i></i> </span> <strong>MBA EXAMS</strong>
                                                <p>50 + MBA exams. Do you know enough about them?</p>
                                                <p className="answer">Know important dates, preparation tips, syllabus and more</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li className="">
                                        <a data-type="Ranking" href="https://www.shiksha.com/mba/ranking/top-mba-colleges-in-india/2-2-0-0-0">
                                            <div className="mba-ranking"> <span> <i></i> </span> <strong>MBA RANKINGS</strong>
                                                <p>Curious to know the top MBA colleges?</p>
                                                <p className="answer">Check out latest college rankings from trusted sources</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="IIM-Predictor" href="https://www.shiksha.com/mba/resources/iim-call-predictor">
                                            <div className="mba-predictor"> <span> <i></i> </span> <strong>IIM &amp; Non IIM CALL PREDICTOR</strong>
                                                <p>IIMs consider a lot more than just the CAT score</p>
                                                <p className="answer">Find your eligibility and chances of getting an IIM call</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li className="">
                                        <a data-type="Find-College" href="https://www.shiksha.com/mba/colleges/mba-colleges-india">
                                            <div className="mba-college"> <span> <i></i> </span> <strong>FIND MBA COLLEGES</strong>
                                                <p>Want to find the right MBA college for you?</p>
                                                <p className="answer">Find colleges based on their location, fees, specialization and more</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="Review" href="https://www.shiksha.com/mba/resources/college-reviews/1">
                                            <div className="mba-review"> <span> <i></i> </span> <strong>COLLEGE REVIEWS</strong>
                                                <p>Nobody knows a college better than its alumni and students.</p>
                                                <p className="answer">Read 100% genuine reviews on faculty, placements, campus life and more</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li className="last">
                                        <a data-type="ASK CURRENT" href="https://www.shiksha.com/business-management-studies/resources/campus-connect-program-1">
                                            <div className="mba-student"> <span> <i></i> </span> <strong>ASK CURRENT MBA STUDENT</strong>
                                                <p>Have college specific questions?</p>
                                                <p className="answer">Get answers from current students of more than 400 colleges</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="Salary Data" href="https://www.shiksha.com/mba/resources/mba-alumni-data">
                                            <div className="mba-salary"> <span> <i></i> </span> <strong>ALUMNI SALARY DATA</strong>
                                                <p>Wondering about your career journey post-MBA?</p>
                                                <p className="answer">Check out company, role and salary data of MBA college alumni</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="Compare" href="https://www.shiksha.com/resources/college-comparison">
                                            <div className="mba-college-com"> <span> <i></i> </span> <strong>COMPARE COLLEGES</strong>
                                                <p>You always have options. Did you choose the right one?</p>
                                                <p className="answer">Compare colleges based on salary, rank, fees, infrastructure and more</p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div data-index="2" className="hide">
                                <ul className="cFL">
                                    <li className="first">
                                        <a data-type="Exams" href="https://www.shiksha.com/b-tech/exams-pc-10">
                                            <div className="mba-exam"> <span> <i></i> </span> <strong>B.Tech EXAMS</strong>
                                                <p>30+ state-level and private college exams you could apply for!</p>
                                                <p className="answer">Know eligibility, important dates, prep tips and more</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="MENTORSHIP" href="https://www.shiksha.com/tags/engineering-tdp-20">
                                            <div className="law-exp-guide"> <span> <i></i> </span> <strong>Get Expert Guidance</strong>
                                                <p>Want advice on exam prep, branch and college selection?</p>
                                                <p className="answer">Get continuous guidance from a current B.Tech student</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="Ranking" href="https://www.shiksha.com/b-tech/ranking/top-engineering-colleges-in-india/44-2-0-0-0">
                                            <div className="engg-ranking"> <span> <i></i> </span> <strong>B.Tech RANKINGS</strong>
                                                <p>3,400+ AICTE approved B.Tech colleges across India</p>
                                                <p className="answer">Start your college search with top colleges in your city and state</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="Rank Predictor" href="https://www.shiksha.com/b-tech/resources/rank-colleges-predictors">
                                            <div className="engg-rp"> <span> <i></i> </span> <strong>RANK PREDICTOR</strong>
                                                <p>Just took the exam and curious about your rank?</p>
                                                <p className="answer">Enter probable score and get your rank.</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="College Predictor" href="https://www.shiksha.com/b-tech/resources/rank-colleges-predictors">
                                            <div className="engg-cp"> <span> <i></i> </span> <strong>COLLEGE PREDICTOR</strong>
                                                <p>Got your rank? Find where you may get admission.</p>
                                                <p className="answer">Enter exam rank and category to see list of probable colleges</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="Find College" href="https://www.shiksha.com/b-tech/colleges/b-tech-colleges-india">
                                            <div className="engg-search"> <span> <i></i> </span> <strong>FIND B.Tech COLLEGES</strong>
                                                <p>Less than 1% B.Tech aspirants get into IITs</p>
                                                <p className="answer">Discover 3,000+ colleges based on location, branch &amp; other preferences</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="Review" href="https://www.shiksha.com/btech/resources/college-reviews/1">
                                            <div className="engg-review"> <span> <i></i> </span> <strong>COLLEGE REVIEWS</strong>
                                                <p>Want a trusted opinion on your target colleges?</p>
                                                <p className="answer">Make an informed decision with more than 6000 alumni reviews</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li className="last">
                                        <a data-type="Compare" href="https://www.shiksha.com/resources/college-comparison">
                                            <div className="engg-college-com"> <span> <i></i> </span> <strong>Compare Colleges</strong>
                                                <p>You always have options. Did you choose the right one?</p>
                                                <p className="answer">Compare colleges based on rank, fees, infrastructure and more</p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div data-index="3" className="hide">
                                <ul className="cFL">
                                    <li>
                                        <a data-type="Get Expert" href="https://www.shiksha.com/tags/design-tdp-10">
                                            <div className="law-exp-guide"> <span> <i></i> </span> <strong>GET EXPERT GUIDANCE</strong>
                                                <p>Are you aware of all course and career options in design?</p>
                                                <p className="answer">Get answers from students, faculty and industry professionals</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li className="first">
                                        <a data-type="Exams" href="https://www.shiksha.com/design/exams-st-3">
                                            <div className="law-exam"> <span> <i></i> </span> <strong>DESIGN EXAMS</strong>
                                                <p>Want to pursue design, but not sure which exams to take?</p>
                                                <p className="answer">Find out details like important dates, prep tips and more</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="Find College" href="https://www.shiksha.com/design/colleges/colleges-india">
                                            <div className="law-college"> <span> <i></i> </span> <strong>FIND DESIGN COLLEGES</strong>
                                                <p>Want to find the right design college for you?</p>
                                                <p className="answer">Find colleges based on location, specialization and more</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li className="last">
                                        <a data-type="Compare" href="https://www.shiksha.com/resources/college-comparison">
                                            <div className="law-compare"> <span> <i></i> </span> <strong>COMPARE COLLEGES</strong>
                                                <p>You always have options. Did you choose the right one?</p>
                                                <p className="answer">Compare colleges based on fees, infrastructure and more</p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div data-index="4" className="hide">
                                <ul className="cFL">
                                    <li className="first">
                                        <a data-type="Exams" href="https://www.shiksha.com/law/exams-st-5">
                                            <div className="law-exam"> <span> <i></i> </span> <strong>LAW EXAMS</strong>
                                                <p>Want to know all about CLAT, LSAT and other Law exams?</p>
                                                <p className="answer">Find out details like important dates, prep tips and more</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="Ranking" href="https://www.shiksha.com/law/ranking/top-law-colleges-in-india/56-2-0-0-0">
                                            <div className="law-ranking"> <span> <i></i> </span> <strong>LAW RANKINGS</strong>
                                                <p>Curious to know the top Law colleges?</p>
                                                <p className="answer">Check out latest college rankings from trusted sources</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-type="Find College" href="https://www.shiksha.com/law/colleges/colleges-india">
                                            <div className="law-college"> <span> <i></i> </span> <strong>FIND LAW COLLEGES</strong>
                                                <p>Want to find the best law college for you?</p>
                                                <p className="answer">Find colleges based on location, eligibility and more</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li className="last">
                                        <a data-type="Compare" href="https://www.shiksha.com/resources/college-comparison">
                                            <div className="law-compare"> <span> <i></i> </span> <strong>COMPARE COLLEGES</strong>
                                                <p>You always have options. Did you choose the right one?</p>
                                                <p className="answer">Compare colleges based on fees, affiliation and more</p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div data-index="5" className="hide">
                                <div id="homepageOtherStreamsSlider" className="sliderContainer otherSlider-v2"> <a className="leftArrow ar-disable"><i></i></a>
                                    <div className="slidingArea slidingArea-v2">
                                        <ul className="cFL others">
                                            <li className="">
                                                <div className="management hover">
                                                    <a data-type="Business" href="https://www.shiksha.com/business-management-studies/colleges/colleges-india"> <i></i> <strong>Business &amp; Management Studies</strong> </a>
                                                </div>
                                                <div className="engineering">
                                                    <a data-type="Engineering" href="https://www.shiksha.com/engineering/colleges/colleges-india"> <i></i> <strong>Engineering</strong> </a>
                                                </div>
                                                <div className="govt-exam">
                                                    <a data-type="Government" href="https://www.shiksha.com/government-exams/exams-st-21"> <i></i> <strong>Government Exams</strong> </a>
                                                </div>
                                                <div className="hospitality hover">
                                                    <a data-type="Hospitality" href="https://www.shiksha.com/hospitality-travel/colleges/colleges-india"> <i></i> <strong>Hospitality &amp; Travel</strong> </a>
                                                </div>
                                                <div className="animation">
                                                    <a data-type="Animation" href="https://www.shiksha.com/animation/colleges/colleges-india"> <i></i> <strong>animation</strong> </a>
                                                </div>
                                                <div className="media">
                                                    <a data-type="MassCommunication" href="https://www.shiksha.com/mass-communication-media/colleges/colleges-india"> <i></i> <strong>Mass Communication &amp; Media</strong> </a>
                                                </div>
                                                <div className="it">
                                                    <a data-type="IT" href="https://www.shiksha.com/it-software/colleges/colleges-india"> <i></i> <strong>IT &amp; Software</strong> </a>
                                                </div>
                                                <div className="Humanities">
                                                    <a data-type="Humanities" href="https://www.shiksha.com/humanities-social-sciences/colleges/colleges-india"> <i></i> <strong>Humanities &amp; Social Sciences</strong> </a>
                                                </div>
                                                <div className="art">
                                                    <a data-type="Arts" href="https://www.shiksha.com/arts-fine-visual-performing/colleges/colleges-india"> <i></i> <strong>Arts (Fine / Visual / Performing)</strong> </a>
                                                </div>
                                                <div className="science">
                                                    <a data-type="Science" href="https://www.shiksha.com/science/colleges/colleges-india"> <i></i> <strong>Science</strong> </a>
                                                </div>
                                            </li>
                                            <li>
                                                <div className="Planning">
                                                    <a data-type="Architecture" href="https://www.shiksha.com/architecture-planning/colleges/colleges-india"> <i></i> <strong>Architecture &amp; Planning</strong> </a>
                                                </div>
                                                <div className="Taxation">
                                                    <a data-type="Accounting" href="https://www.shiksha.com/accounting-commerce/colleges/colleges-india"> <i></i> <strong>Accounting &amp; <br/>Commerce</strong> </a>
                                                </div>
                                                <div className="banking">
                                                    <a data-type="Banking" href="https://www.shiksha.com/banking-finance-insurance/colleges/colleges-india"> <i></i> <strong>Banking, Finance &amp; Insurance</strong> </a>
                                                </div>
                                                <div className="Aviation">
                                                    <a data-type="Aviation" href="https://www.shiksha.com/aviation/colleges/colleges-india"> <i></i> <strong>Aviation</strong> </a>
                                                </div>
                                                <div className="Teaching">
                                                    <a data-type="Teaching" href="https://www.shiksha.com/teaching-education/colleges/colleges-india"> <i></i> <strong>Teaching &amp; Education</strong> </a>
                                                </div>
                                                <div className="Nursing">
                                                    <a data-type="Nursing" href="https://www.shiksha.com/nursing/colleges/colleges-india"> <i></i> <strong>Nursing</strong> </a>
                                                </div>
                                                <div className="medicine">
                                                    <a data-type="Medicine" href="https://www.shiksha.com/medicine-health-sciences/colleges/colleges-india"> <i></i> <strong>Medicine &amp; <br/>Health Sciences</strong> </a>
                                                </div>
                                                <div className="Beauty">
                                                    <a data-type="Beauty" href="https://www.shiksha.com/beauty-fitness/colleges/colleges-india"> <i></i> <strong>Beauty &amp; Fitness</strong> </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div> <a className="rightArrow"><i></i></a> </div>
                            </div>
                        </div>
                    </div>
                </section>
      )
  	}

}
export default CategoryFoldComponent;