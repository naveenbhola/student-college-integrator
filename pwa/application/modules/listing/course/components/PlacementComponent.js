import PropTypes from 'prop-types'
import React, {Component} from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { Chart } from 'react-google-charts';
import '../assets/placement.css';
import './../assets/courseCommon.css';

import { getRupeesDisplayableAmount, convertSalaryIntoLakh, trim } from '../utils/listingCommonUtil';
import {prepareHeadingData} from '../services/HeadingService';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import PopupLayer from './../../../common/components/popupLayer';
import Gradient from './../../../common/components/Gradient';
import {showToastMsg, getUrlParameter, removeQueryString, isUserLoggedIn} from './../../../../utils/commonHelper';
import Loadable from 'react-loadable';
import {showResponseFormWrapper} from './../../../../utils/regnHelper';
import {storeChildPageDataForPreFilled} from './../../instituteChildPages/actions/AllChildPageAction';
import {Link} from 'react-router-dom';


const ResponseForm = Loadable({
    loader: () => import('./../../../user/Response/components/ResponseForm'/* webpackChunkName: 'ResponseForm' */),
    loading() {return null},
});

class PlacementComponent extends Component {
    constructor(props) {
        super(props);
        this.options= {
            bar: { groupWidth: '50%' },
            vAxis: {minValue:0,title:'Salary in (lakh)'},
            legend: { position: 'none'},
            seriesType: 'bars',
            annotations: {
                textStyle: {
                    color: 'black',
                    fontSize: 11,
                },
                alwaysOutside: true
            },
            chartArea : {'width': '80%',height:'80%',top:20,},
            tooltip: { textStyle: { fontName: 'verdana', fontSize: 12,fontWeight:600 } }

        },

            this.state = {
                data: [['', '',{ role: "style" },{ role: 'annotation' },{role: 'tooltip'}]],
                partialRecruitmentCompanies:'',
                allRecruitmentCompanies:'',
                showCompanies: false,
                isInternationalPlacementAvailable : false,
                isDomesticPlacementAvailable: false,
                maxInternationalSalary: null,
                totalInternationalOffers:null,
                placementHeading:'',
                openResponseForm: false,
                tracking_keyid : 0,
                actionType : '',
                enableRegLayer: false
            };
        this.totalTypeOfValuePresent =0; //count of min,avg,max value
        this.salaryType =''; //count of min,avg,max value
        this.valueType; // value of min,avg,max
        this.isUserLoggedIn;
    }

    componentDidMount(){
        if(isUserLoggedIn()){
            this.setState({enableRegLayer: true});
        }
        if(this.props.placementData!=null){
            this.prepareGraphData();
            Promise.resolve(prepareHeadingData(
                this.props.placementData.batch_year,
                this.props.placementData.course,
                this.props.placementData.course_type,
                this.props.instituteName
            )).then((data) => {
                this.setState({placementHeading:data});
            });
        }
        if(this.props.recruitmentCompanies!=null){
            this.prepareRecruitmentCompniesData();
        }
        var url = (typeof(window) != 'undefined') ? window.location.href : '';
        //if(url.indexOf('fromwhere=pwaResponseLogin') != '-1') {
        //    var actionType  = getUrlParameter('action');
        //    switch(actionType){
        //        case 'placement':
        //            if(document.getElementById('placement')){
        //                document.getElementById('placement').click();
        //            }
        //            removeQueryString();
        //            break;
        //        case 'intern':
        //            if(document.getElementById('intern')){
        //                document.getElementById('intern').click();
        //            }
        //            removeQueryString();
        //            break;
        //    }
        //}
        if(this.props.deviceType=='desktop'){
            window.placementCTACallBack = this.placementCTA.bind(this);
            this.setState({ showCompanies : true} );
        }
    }

    handlePlacementClick(){
        var data = {};
        this.trackEvent('VIEW_PLACEMENT_DETAILS','click');
        if(this.props.data.listingId){
            data.listingId = this.props.data.listingId;
        }
        if(this.props.data.listingType){
            data.listingType = this.props.data.listingType;
        }
        if(this.props.data.instituteTopCardData){
            data.instituteTopCardData = this.props.data.instituteTopCardData;
        }
        if(this.props.data.reviewWidget){
            data.reviewWidget = this.props.data.reviewWidget;
        }
        if(this.props.data.currentLocation){
            data.currentLocation = this.props.data.currentLocation;
        }
        if(this.props.data.aggregateReviewWidget !=='undefined'){
            data.aggregateReviewWidget = this.props.data.aggregateReviewWidget;
        }
        if(this.props.data.anaCountString !=='undefined'){
            data.anaCountString = this.props.data.anaCountString;
        }
        data.fromWhere = 'placementPage';  
        data.anaWidget = {};
        data.allQuestionURL = '';
        data.showFullLoader = false;
        data.showFullSectionLoader = true;
        data.PageHeading = 'Placement - Highest & Average Salary Package';
        this.props.storeChildPageDataForPreFilled(data);
    }

    openPlacementLayer(){
        this.PopupLayer.open();
        this.trackEvent("Click","Get_placement_report_College_placement");
    }

    showResponseForm(d) {
        if(this.props.deviceType == 'desktop'){
            this.PopupLayer.close();
            if(isUserLoggedIn()){
                this.isUserLoggedIn = true; 
            }
            else{
                this.isUserLoggedIn = false; 
            }
            var formData = {trackingKeyId:d.tracking_keyid,callbackFunction: 'placementCTA'};
            showResponseFormWrapper(this.props.clientCourseId,d.actionType,'course',formData,{});
        }
        else{
            ResponseForm.preload().then(() => {
                this.setState({enableRegLayer: true}, () => {this.setState({openResponseForm: true, actionType:d.actionType, tracking_keyid:d.tracking_keyid})});
            });
        }
    }


    placementCTA(){
        if(this.isUserLoggedIn){
            showToastMsg('Report mail successfully');
        }
        else{
            window.location.reload();
        }
    }

    closeResponseForm() {
        this.setState({openResponseForm: false});
    }

    callBackPlacement(response){
        if(response.userId){
            showToastMsg('Report emailed successfully');
        }
    }

    placementHtml(){
        var html = new Array();
        var placement = (this.props.placementData) ? <li key={this.props.placementCTATrackingKey} onClick={this.showResponseForm.bind(this,{'actionType':'NM_courseDownloadPlacement','tracking_keyid':this.props.placementCTATrackingKey,type:'Placement'})}>Placement Report</li> : '';
        var internship = (this.props.intershipData) ? <li key={this.props.internshipCTATrackingKey} onClick={this.showResponseForm.bind(this,{'actionType':'NM_courseDownloadInternship','tracking_keyid':this.props.internshipCTATrackingKey,type:'Internship'})}>Internship Report</li> : '';
        html.push(placement);
        html.push(internship);
        return(<ul className="ul-list" key="101">{html}</ul>)
    }

    prepareDownloadPlacement(){
        if(this.props.placementData || this.props.intershipData) {
            return (<button className='button button--orange ripple dark' onClick={(this.props.placementData && this.props.intershipData) ? this.openPlacementLayer.bind(this) : ((this.props.placementData) ? this.showResponseForm.bind(this,{'actionType':'NM_courseDownloadPlacement','tracking_keyid':this.props.placementCTATrackingKey}) : this.showResponseForm.bind(this,{'actionType':'NM_courseDownloadInternship','tracking_keyid':this.props.internshipCTATrackingKey})) }>Download Placement Reports</button>);
        }
        else {
            return null;
        }
    }

    prepareGraphData(){
        this.totalTypeOfValuePresent=0;
        this.salaryType = '';
        let maxValue = 0;  // eslint-disable-line  
        let minSalary = this.props.placementData.min_salary;
        let avgSalary = this.props.placementData.avg_salary;
        let maxSalary = this.props.placementData.max_salary;
        let medianSalary = this.props.placementData.median_salary;

        let data = this.state.data;
        let maximumValue = 0; // eslint-disable-line  

        if(minSalary!=null){
            this.salaryType = 'Minimum';
            this.totalTypeOfValuePresent ++;
            this.valueType = convertSalaryIntoLakh(minSalary);
            maximumValue = Math.max(maximumValue,this.valueType);
            data.push(['Min',this.valueType,"#008489",this.valueType+' lakh',this.valueType+' lakh']);
        }
        if(medianSalary!=null){
            this.salaryType = 'Median';
            this.totalTypeOfValuePresent ++;
            this.valueType = convertSalaryIntoLakh(medianSalary);
            maximumValue = Math.max(maximumValue,this.valueType);
            data.push(['Median',this.valueType,"#008489",this.valueType+' lakh',this.valueType+' lakh']);
        }
        if(avgSalary!=null){
            this.salaryType = 'Average';
            this.totalTypeOfValuePresent ++;
            this.valueType = convertSalaryIntoLakh(avgSalary);
            maximumValue = Math.max(maximumValue,this.valueType);
            data.push(['Avg',this.valueType,"#008489",this.valueType+' lakh',this.valueType+' lakh']);
        }

        if(maxSalary!=null){
            this.salaryType = 'Maximum';
            this.totalTypeOfValuePresent ++;
            this.valueType = convertSalaryIntoLakh(maxSalary);
            maximumValue = Math.max(maximumValue,this.valueType);
            data.push(['Max',this.valueType,"#008489",this.valueType+' lakh',this.valueType+' lakh']);
        }
        //this.options.vAxis.maxValue = maximumValue+10;
        if(this.totalTypeOfValuePresent == 2){
            this.options.bar.groupWidth = '30%';
        }
        
        if(minSalary!=null || medianSalary!=null || avgSalary!=null || maxSalary!=null ){
            this.setState({
                isDomesticPlacementAvailable: true,
                data
            })
        }
    }

    prepareRecruitmentCompniesData(){
        var originalData = this.props.recruitmentCompanies;
        var partialRecruitmentCompanies = '';
        var allRecruitmentCompanies = '';


        if(originalData.length>0){
            var maxloop;
            maxloop = (originalData.length>8?9:originalData.length)

            for(let i=0;i<maxloop;i++){
                partialRecruitmentCompanies = partialRecruitmentCompanies.concat(originalData[i]['companyName'].concat('  |  '));
            }
            for(let i=0;i<this.props.recruitmentCompanies.length;i++){
                allRecruitmentCompanies = allRecruitmentCompanies.concat(originalData[i]['companyName'].concat('  |  '));
            }
        }
        partialRecruitmentCompanies = trim(partialRecruitmentCompanies);
        allRecruitmentCompanies = trim(allRecruitmentCompanies);
        this.setState({
            partialRecruitmentCompanies: partialRecruitmentCompanies,
            allRecruitmentCompanies: allRecruitmentCompanies
        })
    }

    showFullText() {
        this.trackEvent("Click","viewmore_company_names_College_placement");
        this.setState({ showCompanies : true} );
    }

    countPlacementData(){
        const {placementData} = this.props;

        if(placementData==null){
            return 0;
        }
        this.totalTypeOfValuePresent =0;

        if(placementData.min_salary!=null){
            this.totalTypeOfValuePresent++;
        }
        if(placementData.median_salary!=null){
            this.totalTypeOfValuePresent++;
        }
        if(placementData.avg_salary!=null){
            this.totalTypeOfValuePresent++;
        }
        if(placementData.max_salary!=null){
            this.totalTypeOfValuePresent++;
        }

    }

    trackEvent(actionLabel,label)
    {
        let category = this.props.gaCategory;
        Analytics.event({category : category, action : actionLabel, label : label});
    }

    render() {
      if(!this.props.placementData && !this.props.intershipData && (this.props.recruitmentCompanies && this.props.recruitmentCompanies.length==0)){
        return null;
      }
      let showBarGraph = false;
      if(this.props.placementData!=null && (this.props.placementData.min_salary || this.props.placementData.avg_salary || this.props.placementData.max_salary || this.props.placementData.median_salary)){
          showBarGraph = true;
      }
      this.countPlacementData();
      let addContainerClass = '';
      if(this.props.showGradient){
        addContainerClass = 'gradientHeight';
      }
        return (
            <section className='placementBnr listingTuple' id="placements">
                <div className='_container'>
                    <h2 className='tbSec2'>Placements <span>(As provided by college)</span></h2>
                    <div className={'_subcontainer '+addContainerClass}>
                        {this.props.flagshipCourseName ? 
                            (<p><strong>Showing placement data for</strong> 
                                {this.props.deviceType=='desktop'?
                                  <a className='nrml_link' href={this.props.flagshipCourseUrl}>{' '+this.props.flagshipCourseName}</a>
                                  :
                                  <Link className='nrml_link' to={this.props.flagshipCourseUrl}>{' '+this.props.flagshipCourseName}</Link>  
                                }
                             </p>)  
                            :''
                        }
                        <div className='flex-order-item'>
                            {this.props.placementData!=null && this.props.placementData.percentage_batch_placed!=null?
                                <div className="flex-charts">
                                    <div className=''>
                                        <Chart
                                            height ='160px'
                                            width ='150px'
                                            chartType="PieChart"
                                            graph_id='PieChart1'
                                            data={[
                                                ['type', 'placement Percentage'],
                                                ['placed', this.props.placementData.percentage_batch_placed],
                                                ['unplaced', 100-this.props.placementData.percentage_batch_placed],
                                            ]}
                                            options={{
                                                chartArea: { left: 0, top:0, right: 0, bottom: 0 },
                                                enableInteractivity:false,
                                                legend:'none',
                                                pieHole: 0.3,
                                                slices: {
                                                    0: { color: '#008489' },
                                                    1: { color: '#cecfd3' }
                                                },
                                                fontSize:'20px'

                                            }}
                                            rootProps={{ 'data-testid': '1' }}
                                        />
                                    </div>
                                    <div className="piechart_data">
                                        <p><strong>{this.props.placementData.percentage_batch_placed}%</strong> <br/> of Total Batch Placed</p>
                                    </div>
                                </div>
                                :''
                            }
                                <React.Fragment>
                                        {
                                            this.props.recruitmentCompanies.length>0?
                                            <div className="inner-flex-item">
                                                <div className='plcd-compny'>
                                                    <strong className='blk-text'>Recruiting Companies</strong>
                                                    <p>
                                                        {this.state.showCompanies? this.state.allRecruitmentCompanies: this.state.partialRecruitmentCompanies}
                                                    </p>
                                                </div>

                                              {(!this.state.showCompanies && this.props.recruitmentCompanies.length>10)?
                                              <a className='nrml-link' onClick={this.showFullText.bind(this)}>View More</a>:''}
                                            </div>:''
                                        }

                                    {showBarGraph ?    
                                    <div className='border-box cont-img brd-bottom mrg-bottom'>
                                        <div className='graphHeading'>
                                            <h3>Salary Stats
                                                <span>  (Annual) (In <b>{this.props.placementData.salary_unit_name
                                                }</b>)</span>
                                            </h3>
                                        </div>
                                        {
                                            this.totalTypeOfValuePresent ==1 ?
                                                <div><b>  {this.valueType+' lakh '}</b>{' '+this.salaryType+' Salary'}  </div> :
                                                <div>
                                                    <Chart
                                                        chartType="ColumnChart"
                                                        data={this.state.data}
                                                        options={this.options}
                                                        graph_id="LineChart"
                                                        width="100%"
                                                        height="250px"
                                                    />
                                                </div>
                                        }
                                    </div>:''
                                  }
                                    </React.Fragment>
                            {this.props.placementData && (this.props.placementData.total_international_offers!=null || this.props.placementData.max_international_salary!=null) ?
                                <div className='border-box cont-img img-1'>
                                    <div className='graphHeading'>
                                        <h3>International Placements
                                            <span>  (Annual)</span>
                                        </h3>

                                        {this.props.placementData.total_international_offers!=null?
                                            <div className='t-off'>
                                                <p>Total offers</p>
                                                <span>{this.props.placementData.total_international_offers}</span>
                                            </div>:''}

                                        {this.props.placementData && this.props.placementData.max_international_salary?
                                            <div className='t-off'>
                                                <p>Maximum Salary</p>
                                                <span>{this.props.placementData.max_international_salary_unit_name} {getRupeesDisplayableAmount(this.props.placementData.max_international_salary)}</span>
                                            </div>:''}

                                    </div>
                                </div>:''}

                            <div className='button-container mb4'>

                                <a id="placement" className='hide' onClick={this.showResponseForm.bind(this,{'actionType':'NM_courseDownloadPlacement','tracking_keyid':this.props.placementCTATrackingKey})}>Placement Report</a>
                                <a id="intern" className='hide' onClick={this.showResponseForm.bind(this,{'actionType':'NM_courseDownloadInternship','tracking_keyid':this.props.internshipCTATrackingKey})}>Internship Report</a>

                                {this.prepareDownloadPlacement()}

                                {this.state.enableRegLayer && <ResponseForm openResponseForm={this.state.openResponseForm} clientCourseId={this.props.clientCourseId} listingType="course" cta="" actionType={this.state.actionType} trackingKeyId={this.state.tracking_keyid} callBackFunction={this.callBackPlacement.bind(this)} onClose={this.closeResponseForm.bind(this)} />
                                }

                                {
                                    <PopupLayer onRef={ref => (this.PopupLayer = ref)}  data={this.placementHtml()} heading={'Download Report'}/>
                                }


                            </div>

                        </div>
                    </div>
                {this.props.placementUrl && this.props.showGradient && <Gradient onClick={this.handlePlacementClick.bind(this)} heading='View Placement Details' url={this.props.placementUrl} />}    
                </div>
            </section>
        );
    }
}

function mapDispatchToProps(dispatch){
    return bindActionCreators({ storeChildPageDataForPreFilled}, dispatch);
}


export default connect(null,mapDispatchToProps)(PlacementComponent);

PlacementComponent.propTypes = {
  clientCourseId: PropTypes.any,
  deviceType: PropTypes.any,
  gaCategory: PropTypes.any,
  instituteName: PropTypes.any,
  internshipCTATrackingKey: PropTypes.any,
  intershipData: PropTypes.any,
  placementCTATrackingKey: PropTypes.any,
  placementData: PropTypes.any,
  recruitmentCompanies: PropTypes.any
}