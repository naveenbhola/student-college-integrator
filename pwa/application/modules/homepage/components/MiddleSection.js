import React from 'react';
import ReactDOM from 'react-dom';
import {Link} from 'react-router-dom';
import styles from './../assets/categorySection.css';
import SingleSelectLayer from './../../common/components/SingleSelectLayer';
import {getRequest } from './../../../utils/ApiCalls';
import PropTypes from 'prop-types';
import MoreTabSection  from './MoreTabSection';
import {withRouter } from 'react-router-dom';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import {rippleEffect} from './../../reusable/utils/UiEffect';

import {getCookie,setCookie,showToastMsg} from './../../../utils/commonHelper';
import {hierarchyMap} from './../config/homepageConfig';

import { connect } from 'react-redux';
import APIConfig from './../../../../config/apiConfig';
import {defaultLocationLayerSteamIds} from './../../listing/categoryList/config/categoryConfig';
import NonZeroLocation from './../../common/components/NonZeroLocationLayer';

class MiddleSection extends React.Component
{
    constructor(props,context)
    {
        super(props,context);
        this.state = {
            activeTabId : 'mba',
            isOpen : false,
            customList : {},
            categoryData : {},
            layerHeading : '',
            search: false,
            subHeading : false,
            placeHolderText : '',
            layerType : '',
            isAnchorLink : true,
            categoryName : null,
            locationLayerData : {},
            nonZeroLayer : false,
            isLink : false
        }
        this.trackEvent = this.trackEvent.bind(this);
        this.gaCall = true;
    }
    componentDidMount()
    {
      //document.getElementById('mba-choose').click();
      var userOption = getCookie('hpTab');
      userOption = userOption != '' ? userOption+'-choose': 'mba-choose';
      this.gaCall = false;
      document.getElementById(userOption).click();
    }
    componentDidUpdate()
    {

    }

    modalClose()
    {
        this.setState({...this.state,'isOpen' : false,'customList':{},categoryName : null,locationLayerData : {},nonZeroLayer : false});
    }
    isActive(id)
    {
        return (id === this.state.activeTabId);
    }
    getExamsList(categoryName)
    {
        let categoryNameMapping = {'design' : 'Design','law' : 'Law' , 'engineering' : 'B.Tech', 'mba' : 'MBA'};
        this.setState({...this.state,'isOpen' : true,'layerHeading' : categoryNameMapping[categoryName]+ ' Exams','search' : true,'subHeading':true,'placeHolderText':'Search an exam',layerType : 'exam',isAnchorLink : true, isLink : true});
        var params = 'category='+categoryName;
        getRequest(APIConfig.GET_EXAMLIST+'?'+params).then((response) => {
            this.displayList(response.data.data);
        }).catch(function(res){
            showToastMsg("something went wrong. please try again");
        });
    }
    getRankPredictorList()
    {
        this.setState({...this.state,'isOpen' : true,'layerHeading' : 'Rank Predictor','search' : false,'subHeading': false,'placeHolderText':'Enter a rank predictor',layerType : 'predictor',isAnchorLink : true});
        getRequest(APIConfig.GET_RANKPREDICTOR).then((response) => {
            this.displayList(response.data.data);
        }).catch(function(res){
            showToastMsg("something went wrong. please try again");
        });
    }
    getCollegePrdictorList()
    {
        this.setState({...this.state,'isOpen' : true,'layerHeading' : 'College Predictor','search' : true,'subHeading': true,'placeHolderText':'Enter a college predictor', layerType : 'predictor',isAnchorLink : true});
        getRequest(APIConfig.GET_COLLEGEPREDICTOR).then((response) => {
            if(response.data.data)
                this.displayList(response.data.data['Engineering']);
        }).catch(function(res){
            showToastMsg("something went wrong. please try again");
        });
    }
    displayList(customData)
    {
        this.setState({'customList' : customData});
    }
    markSelected(activeTabId,actionLabel,event)
    {
        rippleEffect(event);
        this.setState({'activeTabId':activeTabId});
        if(this.gaCall)
            this.trackEvent(actionLabel);
        setCookie('hpTab',activeTabId);
        this.gaCall = true;
    }

    trackEvent(actionLabel)
    {
        Analytics.event({category : 'HPbody', action : actionLabel, label : 'NavLink'});
    }

    getLocationLayer(categoryName,obj)
    {
        this.setState({locationLayerData : obj,categoryName : categoryName,nonZeroLayer : true});
    }

    getMoreTabData(){
        if(typeof document != 'undefined' && document.getElementById('other').getElementsByTagName('*').length<=0){
            getRequest(APIConfig.GET_HIERARCHYTREEHAVING_NONZEROLISTINGS).then((response) =>{
                this.formatCategorySectionData(response.data.data);
            }).catch((err)=> console.log(err))
        }
    }

    formatCategorySectionData(responseData)
    {
        var result = new Array();
        for(var i in responseData){
            result.push(responseData[i]);
        }
        this.setState({'categoryData' : result});
    }

	render()
	{
        const {activeTab} = this.state;
        let options  = [];
        var date      = new Date();
		return (
            <React.Fragment>
                <section className="stdBnr">
            <div className="_container">
                <h2 className="tbSec2">Choose Stream</h2>
                <div className="tbSec" id="userTabs">
                    <ul className="studyTab">
                            <li data-index="1" data-tab="mba" key="mba"  data-val="112" className={this.isActive('mba') ? 'active' : ''}
                            onClick={this.markSelected.bind(this,'mba','MidMBA')}>
                                <label htmlFor="choose-mba" id="mba-choose"><h2 className="ripple dark">Mba</h2></label>
                            </li>
                        <li data-index="2" data-tab="engg" key="engg" className={ this.isActive('engg') ? 'active' : '' } onClick={this.markSelected.bind(this,'engg','MidBtech')}>
                            <label htmlFor="choose-btech" id="engg-choose"><h2 className="ripple dark">B.TECH</h2></label>
                        </li>
                        <li data-index="3" data-tab="design" key="design" onClick={this.markSelected.bind(this,'design','MidDesign')} className={this.isActive('design') ? 'active' : '' }>
                            <label htmlFor="choose-design" id="design-choose"><h2 className="ripple dark">Design</h2></label>
                        </li>
                        <li data-index="4" data-tab="law" key="law" onClick={this.markSelected.bind(this,'law','MidLaw')} className={this.isActive('law') ? 'active' : '' }>
                            <label htmlFor="choose-law" id="law-choose"><h2 className="ripple dark">Law</h2></label>
                        </li>
                        <li data-index="5" data-tab="other" key="others" onClick={this.markSelected.bind(this,'other','MidMore')} className={this.isActive('other') ? 'active' : '' }>
                            <label htmlFor="choose-other" id="other-choose" onClick={this.getMoreTabData.bind(this)}><h2 className="ripple dark">More</h2></label>
                        </li>
                    </ul>
                </div>
        <div className='tbc stdyc'>
            <input type="radio" name="choosestream" value="choose-mba" id="choose-mba" className="hide st" defaultChecked={true}/>
            <div data-index="1" id="mba" className="active chooseStream" onClick={ () => this.trackEvent('MidMBA')}>
                <ul className="cfl">
        <li className="first">
            <a className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)}>
                <div className="mba-exam">
                    <span>
                        <i>&nbsp;</i>
                    </span>
                    <div className="exmClk" onClick={this.getExamsList.bind(this,'mba')}>
                        <h3>MBA Exams</h3>
                        <p>Know key dates, prep tips, syllabus &amp; more</p>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <Link className="anchrHp ripple dark" to={"/mba/ranking/top-mba-colleges-in-india/2-2-0-0-0"} onClick={(event) => rippleEffect(event)}>
                <div className='mba-ranking'>
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>MBA Rankings</h3>
                        <p>Check college rankings from trusted sources</p>
                    </div>
                </div>
            </Link>

        </li>
        <li>
            <a className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)} href={ this.props.config.SHIKSHA_HOME + "/mba/cat-exam-percentile-predictor"}>
                <div className='mba-predictor'>
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>CAT Percentile Predictor</h3>
                        <p>Predict your CAT {date.getFullYear()} Percentile</p>
                    </div>
                </div>
            </a>

        </li>
        <li>
            <a className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)} href={ this.props.config.SHIKSHA_HOME +  "/mba/resources/iim-call-predictor"}>
                <div className='mba-predictor'>
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>IIM & Non IIM Call Predictor</h3>
                        <p>Find your chances of getting an IIM call</p>
                    </div>
                </div>
            </a>

        </li>
        <li>
            <a className="anchrHp  ripple dark" onClick={(event) => rippleEffect(event)}>
                <div className='mba-college'>
                    <span>
                        <i></i>
                    </span>
                    <div className="tabClk" onClick={this.getLocationLayer.bind(this,'mba',{})}>
                        <h3>Find MBA Colleges</h3>
                        <p>Find colleges based on location, fee &amp; more</p>
                        <div className='select-className'>
                            <select style={{'display':'none'}}></select>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)} href={ this.props.config.SHIKSHA_HOME +  "/resources/college-comparison"}>
                <div className='mba-college-com'>
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>Compare Colleges</h3>
                        <p>Compare on salary, infrastructure &amp; more</p>
                    </div>
                </div>
            </a>

        </li>
        <li>
            <a className="anchrHp  ripple dark" onClick={(event) => rippleEffect(event)} href={ this.props.config.SHIKSHA_HOME + "/mba/resources/mba-alumni-data"}>
                <div className='mba-salary'>
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>Alumni Salary Data</h3>
                        <p>Check company, role &amp; salary data of alumni</p>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a className="anchrHp  ripple dark" onClick={(event) => rippleEffect(event)} href={ this.props.config.SHIKSHA_HOME + "/business-management-studies/resources/campus-connect-program-1"}>
                <div className='mba-student'>
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>Ask Current MBA Students</h3>
                        <p>Get answers from students of 300+ colleges</p>
                    </div>
                </div>
            </a>
        </li>
        <li className="last">
        <a className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)} href={ this.props.config.SHIKSHA_HOME + "/mba/resources/college-reviews/1"}>
                <div className='mba-review'>
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>College Reviews</h3>
                        <p>Read reviews &amp; ratings of 350+ colleges</p>
                    </div>
                </div>
            </a>
        </li>
    </ul>
    </div>
        <input type="radio" name="choosestream" value="choose-btech" id="choose-btech" className="hide st"/>
            <div data-index="2" id="engg" className="chooseStream" onClick={ () => this.trackEvent('MidBtech')}>
            <div data-index="2">
    <ul className="cfl">
        <li className="first">
            <a className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)}>
                <div className="eng-exam mba-exam">
                    <span>
                        <i>&nbsp;</i>
                    </span>
                    <div className="exmClk" onClick={this.getExamsList.bind(this,'engineering')}>
                        <h3>B.Tech Exams</h3>
                        <p>Know key dates, prep tips, syllabus &amp; more</p>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a  className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)} href={ this.props.config.SHIKSHA_HOME + "/tags/engineering-tdp-20"}>
                <div className="design-e-guid">
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>Get Expert Guidance</h3>
                        <p>Get guidance from a current student</p>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <Link className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)} to={"/b-tech/ranking/top-engineering-colleges-in-india/44-2-0-0-0"}>
                <div className="mba-ranking">
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>B.Tech Rankings</h3>
                        <p>Check college rankings from trusted sources</p>
                    </div>
                </div>
            </Link>
        </li>
        <li>
            <a className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)}>
                <div className="eng-rank-predictor">
                    <span>
                        <i></i>
                    </span>
                    <div className="rpClk" onClick={this.getRankPredictorList.bind(this)}>
                        <h3>Rank Predictor</h3>
                        <p>Get your rank based on your probable score</p>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)}>
                <div className="eng-col-predictor">
                    <span>
                        <i></i>
                    </span>
                    <div className="cpClk" onClick={this.getCollegePrdictorList.bind(this)}>
                        <h3>College Predictor <span className='addNew'> New</span></h3>
                        <p>See probable colleges based on exam rank</p>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a  className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)}>
                <div className="mba-college">
                    <span>
                        <i></i>
                    </span>
                    <div className="tabClk" onClick={this.getLocationLayer.bind(this,'engg',{})}>
                        <h3>Find B.Tech Colleges</h3>
                        <p>Find colleges based on location, branch &amp; more</p>
                    </div>
                </div>
            </a>

        </li>
        <li>
            <a  className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)} href={ this.props.config.SHIKSHA_HOME + "/btech/resources/college-reviews/1"}>
                <div className="mba-review">
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>College Reviews</h3>
                        <p>Read reviews &amp; ratings of 500+ colleges</p>
                    </div>
                </div>
            </a>
        </li>
        <li className="last">
            <a  className="anchrHp ripple dark" href={ this.props.config.SHIKSHA_HOME + "/resources/college-comparison"}>
                <div className="mba-college-com">
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>Compare Colleges</h3>
                        <p>Compare on rank, infrastructure &amp; more</p>
                    </div>
                </div>
            </a>
        </li>
    </ul>
</div>
            </div>
            <input type="radio" name="choosestream" value="choose-design" id="choose-design" className="hide st"/>
            <div data-index="3" id="design" className="chooseStream" onClick={ () => this.trackEvent('MidDesign')}>
            <div data-index="3">
    <ul className="cfl">
        <li className="first">
            <a  className="anchrHp ripple dark" href={ this.props.config.SHIKSHA_HOME + "/tags/design-tdp-10"}>
                <div className="design-e-guid">
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>Get Expert Guidance</h3>
                        <p>Get answers from students &amp; professionals</p>
                    </div>
                </div>
            </a>

        </li>
        <li>
            <a className="anchrHp ripple dark" >
                <div className="mba-exam">
                    <span>
                        <i>&nbsp;</i>
                    </span>
                    <div className="exmClk" list="design" onClick={this.getExamsList.bind(this,'design')}>
                        <h3>Design Exams</h3>
                        <p>Know key dates, prep tips, syllabus &amp; more</p>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a className="anchrHp ripple dark" >
                <div className="mba-college">
                    <span>
                        <i></i>
                    </span>
                    <div className="tabClk" onClick={this.getLocationLayer.bind(this,'',{streamId :  3})}>
                        <h3>Find Design Colleges</h3>
                        <p>Find colleges based on fees &amp; specialization</p>
                        <div className='select-className'>
                            <select name="tabLocationSelect_design" style={{'display':'none'}}></select>
                        </div>
                    </div>
                </div>
            </a>

        </li>
        <li className="last">
            <a  className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)} href={ this.props.config.SHIKSHA_HOME + "/resources/college-comparison"}>
                <div className="mba-college-com">
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>Compare Colleges</h3>
                        <p>Compare on fees, infrastructure &amp; more</p>
                    </div>
                </div>
            </a>
        </li>
    </ul>
</div>          </div>
    <input type="radio" name="choosestream" value="choose-law" id="choose-law" className="hide st"/>
            <div data-index="4" id="law" className="chooseStream" onClick={ () => this.trackEvent('MidLaw')}>
            <div data-index="4">
    <ul className="cfl">
        <li className="first">
            <a  className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)} href={ this.props.config.SHIKSHA_HOME + "/tags/law-tdp-15"}>
                <div className="design-e-guid">
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>Get Expert Guidance</h3>
                        <p>Get answers from students &amp; professionals</p>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)}>
                <div className="mba-exam">
                    <span>
                        <i>&nbsp;</i>
                    </span>
                    <div className="exmClk" list="law" onClick={this.getExamsList.bind(this,'law')}>
                        <h3>Law Exams</h3>
                        <p>Know key dates, prep tips, syllabus &amp; more</p>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <Link className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)} to={"/law/ranking/top-law-colleges-in-india/56-2-0-0-0"}>
                <div className="mba-ranking">
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>Law Rankings</h3>
                        <p>Check college rankings from trusted sources</p>
                    </div>
                </div>
            </Link>

        </li>
        <li>
            <a className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)}>
                <div className="mba-college">
                    <span>
                        <i></i>
                    </span>
                    <div className="tabClk" onClick={this.getLocationLayer.bind(this,'law',{})}>
                        <h3>Find Law Colleges</h3>
                        <p>Find colleges based on fees &amp; specialization</p>
                        <div className='select-className'>
                            <select name="tabLocationSelect_law" style={{'display':'none'}}></select>
                        </div>
                    </div>
                </div>
            </a>

        </li>
        <li className="last">
            <a  className="anchrHp ripple dark" onClick={(event) => rippleEffect(event)} href={ this.props.config.SHIKSHA_HOME + "/resources/college-comparison"}>
                <div className="mba-college-com">
                    <span>
                        <i></i>
                    </span>
                    <div>
                        <h3>Compare Colleges</h3>
                        <p>Compare on fees, infrastructure &amp; more</p>
                    </div>
                </div>
            </a>
        </li>
    </ul>
</div>          </div>
        <input type="radio" name="choosestream" value="choose-other" id="choose-other" className="hide st"/>
             {/*this.isActive('others') ? <MoreTabSection/> : '' */}
             <MoreTabSection onClick={this.getLocationLayer.bind(this)} categoryData={this.state.categoryData} config={this.props.config}/>
            </div>
        </div>
    </section>
            {this.state.nonZeroLayer && <NonZeroLocation data={this.state.locationLayerData} categoryName={this.state.categoryName} onClose={this.modalClose.bind(this)}/>}
            <SingleSelectLayer show={this.state.isOpen} onClose={this.modalClose.bind(this)} data={this.state.customList} search={this.state.search} heading= {this.state.layerHeading} showSubHeading={this.state.subHeading} placeHolderText={this.state.placeHolderText} layerType={this.state.layerType} isAnchorLink={this.state.isAnchorLink} isLink={this.state.isLink}>
            </SingleSelectLayer>
    </React.Fragment>
            )
	}
}

function mapStateToProps(state)
{
    return {
        config : state.config
    }
}

MiddleSection = withRouter(MiddleSection);
export default connect(mapStateToProps)(MiddleSection);
