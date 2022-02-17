import React, { Component } from 'react';
import PropTypes from 'prop-types';
import '../assets/TopFeaturedCollege.css';

class TopFeaturedCollegeComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}
 	
  	render(){
  		return(
      	<div className="n-featColg">
                <a className="featTxt"><i className="icons ic_featdTxt"></i></a>
                <a className="featMoveArw sldrDisableBtn"><i className="icons ic_right-gry"></i></a>
                <ul className="n-featBanrs addTranstnEfct" style={{width: "1245px", marginLeft: "0%"}}>
                    <li style={{width: "246.8px"}}>
                        <a href="https://www.shiksha.com/trackCtr/493?url=https%3A%2F%2Fwww.shiksha.com%2Fb-tech%2Fcourse%2Fb-e-in-computer-science-and-engineering-hkbk-college-of-engineering-nagavara-bangalore-8508" target="_blank" rel="nofollow">
                            <div>
                                <h1 className="n-bnrSmllTxt">
                                    HKBK College of Engineering                         <b>
                                     Bangalore                        </b>
                                </h1>
                                <p>
                                    Top Ranked Engineering College </p>
                            </div>
                        </a>
                    </li>
                    <li style={{width: "246.8px"}}>
                        <a href="https://www.shiksha.com/trackCtr/463?url=https%3A%2F%2Fwww.shiksha.com%2Fmba%2Fcourse%2Fmaster-of-business-administration-mit-school-of-management-kothrud-pune-2340" target="_blank" rel="nofollow">
                            <div>
                                <h1 className="n-bnrSmllTxt">
                                    MIT World Peace University                        <b>
                                    Pune                        </b>
                                </h1>
                                <p>
                                    Admissions Open - MBA - 2019 Batch </p>
                            </div>
                        </a>
                    </li>
                    <li style={{width: "246.8px"}}>
                        <a href="https://www.shiksha.com/trackCtr/469?url=https%3A%2F%2Fwww.shiksha.com%2Fb-tech%2Fcourse%2Fb-tech-in-computer-science-and-engineering-mitsoe-mit-school-of-engineering-loni-kalbhor-pune-276797" target="_blank" rel="nofollow">
                            <div>
                                <h1 className="">
                                    MIT ADT University                        <b>
                                    Pune                        </b>
                                </h1>
                                <p>
                                    Admissions Open for 2019 </p>
                            </div>
                        </a>
                    </li>
                    <li style={{width: "246.8px"}}>
                        <a href="https://www.shiksha.com/trackCtr/539?url=https%3A%2F%2Fwww.shiksha.com%2Funiversity%2Fintegral-university-iul-lucknow-21870%3Frf%3DsearchWidget%26landing%3Dilp" target="_blank" rel="nofollow">
                            <div>
                                <h1 className="">
                                    Integral University                        <b>
                                    Lucknow                        </b>
                                </h1>
                                <p>
                                    Admissions Open </p>
                            </div>
                        </a>
                    </li>
                    <li style={{width: "246.8px"}}>
                        <a href="https://www.shiksha.com/trackCtr/541?url=https%3A%2F%2Fwww.shiksha.com%2Fmba%2Fcourse%2Fpost-graduate-diploma-in-management-pgdm-aims-institute-of-management-studies-kondhwa-budruk-pune-366751" target="_blank" rel="nofollow">
                            <div>
                                <h1 className="n-bnrSmllTxt">
                                    AIMS Institute of Management Studies                        <b>
                                    Pune                        </b>
                                </h1>
                                <p>
                                    Admissions Open! </p>
                            </div>
                        </a>
                    </li>
                    <p className="clr"></p>
                </ul>
            </div>
      )
  	}

}
export default TopFeaturedCollegeComponent;