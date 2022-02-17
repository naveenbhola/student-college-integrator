import React, { Component } from 'react';
import PropTypes from 'prop-types';
import '../assets/CareerGuidanceComponent.css';

class CareerGuidanceComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}
 	
  	render(){
  		return(
      	<section id="exp-inn" className="expertBanner">
                <div className="_cntr">
                    <div className="heading3">
                        <h2>EDUCATION &amp; CAREER GUIDANCE</h2>
                    </div>
                    <div className="caption">Get guidance from faculty, alumni, exam toppers &amp; industry professionals to help you make the right decision. Also, find information on 180+ careers.</div>
                    <div className="expertContent">
                        <ul className="expertList">
                            <li className="first">
                                <div className="expertContentBox">
                                    <h2>ASK &amp; ANSWER</h2>
                                    <div>
                                        <p>
                                            Get answers on career and education queries
                                        </p>
                                        <ul className="table">
                                            <li>
                                                <a className="exprt" href="javascript:void(0);">
                                                    <strong>1000+</strong>
                                                    <span>Experts</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a className="rel-ans" href="javascript:void(0);">
                                                    <strong>Reliable</strong>
                                                    <span>Answers</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a className="qck-resp" href="javascript:void(0);">
                                                    <strong>Quick</strong>
                                                    <span>Response</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <form id="askQuestionForm" name="askQuestionForm">
            
                                            <div className="quetion-area">
                                                <textarea placeholder="Type your question here" id="questionText" name="questionText"></textarea>
                                            </div>
            
                                            <input type="button" value="ASK NOW" title="Submit your question" className="primaryButton button button--orange" id="AskAnAButtonAnAHomePage"/>
                                            <div className="linkContainer">
                                                <strong>View questions on : </strong><a href="https://www.shiksha.com/tags/mba-tdp-422">MBA</a><span>|</span><a href="https://www.shiksha.com/tags/engineering-tdp-20">Engineering</a><span>|</span><a href="https://www.shiksha.com/tags/design-tdp-10">Design</a><span>|</span><a href="https://www.shiksha.com/tags/law-tdp-15">Law</a><span>|</span><a href="https://ask.shiksha.com/questions">Others</a>
                                            </div>
                                            <input type="hidden" name="referalUrlForAskQuestionFromHeader" id="referalUrlForAskQuestionFromHeader" value="hackParameter"/>
                                            <input type="hidden" name="tracking_keyid" id="tracking_keyid_ques" value="67"/>
                                        </form>
                                    </div>
                                </div>
                            </li>
            
                            <li className="add">
                                <div className="expertContentBox">
                                    <a className="_appDwnGif" href="javascript:void(0);">
                                        <img src="https://images.shiksha.ws/public/images/Homepage-Banner.gif" alt="Shiksha's App" title="Shiksha's App"/></a>
                                </div>
                            </li>
                            <li className="last">
                                <div className="expertContentBox">
                                    <h2>CAREERS AFTER 12TH</h2>
                                    <div className="answerSec">
                                        <p>Want to find the right career based on your interest?</p>
                                        <p>Select a stream to get started</p>
                                        <p className="answerOptions">
                                            <span>
                                <button className="primaryButton hm-c button button--orange" data-type="Humanities">ARTS &amp; HUMANITIES</button>
                            </span>
                                            <span>
                                <button className="primaryButton hm-c button button--orange" data-type="Commerce">COMMERCE</button>
                            </span>
                                            <span>
                                <button className="primaryButton hm-c button button--orange" data-type="Science">SCIENCE</button>
                            </span>
                                        </p>
                                    </div>
            
                                </div>
            
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
      )
  	}

}
export default CareerGuidanceComponent;