import React from 'react';
import  './../assets/contentLoader.css';
const FormContentLoader = (props) => {
	let leftArea = null;
	if(props.deviceType === 'desktop'){
		leftArea = <div className='col-step fltlft'>
			<div className="loader-line shimmer wdt85"> </div>
			<div className="loader-line shimmer wdt85"> </div>
			<div className="loader-line shimmer wdt85"> </div>
		</div>;
	}
	return (
		<div className='collegeShortlist cpDesktop'>
			<div className='pwa_banner'>
				<section className="head-strip">
				    <div id="cp-banner">
				        <div className="clg-bg">
				            <div className="clg-txBx">
				                <h1>College Predictor</h1>
				                <p>Predict Colleges based on engineering exams you have taken.</p>
				            </div>
				        </div>
				    </div>
				</section>				
			</div>
			<div className='pwa_pagecontent'>
				<div className='pwa_container'>
					<div className='form-area shadow-box clearFix'>
						{leftArea}
						<div className='col-forms fltryt'>
							<div className="form-heading">
								<h2><div className="loader-line shimmer wdt85"> </div></h2>
							</div>
							<div className="form-fields">
							    <div className="form-field">
							        <ul className="exam-selection-list">
							            <li>
							                <div className="exam-img-cont">
							                    <label>
							                        <div className="img-box">
								                        <div className="loader-line shimmer wdt85"></div>
							                        </div>
							                    </label>
							                </div>
							            </li>
							            <li>
							                <div className="exam-img-cont">
							                    <label>
							                        <div className="img-box">
								                        <div className="loader-line shimmer wdt85"></div>
							                        </div>
							                    </label>
							                </div>
							            </li>
							            <li>
							                <div className="exam-img-cont">
							                    <label>
							                        <div className="img-box">
								                        <div className="loader-line shimmer wdt85"></div>
							                        </div>
							                    </label>
							                </div>
							            </li>
							            <li>
							                <div className="exam-img-cont">
							                    <label>
							                        <div className="img-box">
								                        <div className="loader-line shimmer wdt85"></div>
							                        </div>
							                    </label>
							                </div>
							            </li>
							            <li>
							                <div className="exam-img-cont">
							                    <label>
							                        <div className="img-box">
								                        <div className="loader-line shimmer wdt85"></div>
							                        </div>
							                    </label>
							                </div>
							            </li>
							            <li>
							                <div className="exam-img-cont">
							                    <label>
							                        <div className="img-box">
								                        <div className="loader-line shimmer wdt85"></div>
							                        </div>
							                    </label>
							                </div>
							            </li>
							            <li>
							                <div className="exam-img-cont">
							                    <label>
							                        <div className="img-box">
								                        <div className="loader-line shimmer wdt85"></div>
							                        </div>
							                    </label>
							                </div>
							            </li>
							            <li>
							                <div className="exam-img-cont">
							                    <label>
							                        <div className="img-box">
								                        <div className="loader-line shimmer wdt85"></div>
							                        </div>
							                    </label>
							                </div>
							            </li>
							            <li>
							                <div className="exam-img-cont">
							                    <label>
							                        <div className="img-box">
								                        <div className="loader-line shimmer wdt85"></div>
							                        </div>
							                    </label>
							                </div>
							            </li>
							            <li>
							                <div className="exam-img-cont">
							                    <label>
							                        <div className="img-box">
								                        <div className="loader-line shimmer wdt85"></div>
							                        </div>
							                    </label>
							                </div>
							            </li>
							        </ul>
							    </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
};
export default FormContentLoader;
