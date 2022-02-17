import PropTypes from 'prop-types'
import React, { Component } from 'react';
import '../assets/alumni.css';
import './../assets/courseCommon.css';
import {getRequest } from '../../../../utils/ApiCalls';
import PopupLayer from '../../../common/components/popupLayer';
import FullPageLayer from './../../../common/components/FullPageLayer';
import {cutStringWithShowMore} from './../../../../utils/stringUtility';
import { Chart } from 'react-google-charts';
import APIConfig from './../../../../../config/apiConfig';

class AlumniSalaryGraph extends Component{
    constructor(props){
        super(props);
        this.state = {
            graphData: [
                ['Experience Bucket', 'Average Salary (in lakh)' , {type:'string', role:'annotation'}, {type: 'string', role: 'style'}]
            ],
            graphOptions: {
                title: '',
                vAxis   :   {baselineColor: '#cacaca',baseline: 0,minValue:0,maxValue:0,gridlines:{ count:0}},
                hAxis   :   {textStyle:{color: '#222222',fontSize: 11}},
                seriesType: 'bars',
                bar: { groupWidth: '40%' },
                legend: 'none',
                annotations: {alwaysOutside: true,textStyle: {color: '#666',opacity: 1}},
                chartArea : {'width': '100%'}
            }
        }
    }

    componentDidMount(){
        this.createGraphData(this.props.alumniData);
    }

    createGraphData(salaryData){
        let data = this.state.graphData;
        let prevState = {...this.state.graphOptions}
        let max_value = 0;
        for(let i in salaryData){
            var ctc = parseFloat(salaryData[i].value);
            ctc = Math.round(ctc * 100) / 100;
            data.push([salaryData[i].bucket+' Years', ctc, ctc+' Lakh', 'stroke-color: #007075; stroke-width: 1; fill-color: #007075; fill:#000000']);
            max_value = Math.max(max_value,ctc);
        }
        max_value = max_value+5;
        prevState.vAxis.maxValue = max_value;
        this.setState({
            graphOptions: prevState,
            data
        });
    }

    render() {
        if(Object.keys(this.props.alumniData)<=0){
            return null;
        }
        return (
            <React.Fragment>
                <p>Data based on resumes from <i className="naukri-img"></i>{"India's no.1 job site"}</p>
                <div className='border-box cont-img'>
                    <div className='graphHeading'>
                        <h3>Average annual salary details of {this.props.alumniCountBySalaries}  alumni of this course
                            <span>  (by work experience) (In <b>INR</b>)</span>
                        </h3>
                    </div>
                    <Chart
                        chartType="ComboChart"
                        data={this.state.graphData}
                        options={this.state.graphOptions}
                        graph_id="ComboChart"
                        width="100%"
                        height="55%"
                        legend_toggle
                    />
                </div>
            </React.Fragment>
        )
    }
}

class AlumniComponent extends Component {
    constructor(props){
        super(props);
        this.state = {
            specialization : {},
            slicedCompanyList : [],
            fullCompanyList : [],
            fullCompanyListOnCompanyLayer : [],
            slicedFunctionalAreaList: [],
            fullFunctionalAreaList: [],
            fullCompanyListOnFunctionalLayer : [],
            alumniCountByPlacements: 0,
            alumniCountBySalaries: 0,
            selectedSpecializationName:'All Specialization',
            displayedSpecializationName: 'All Specialization',
            displayedSpecializationNameOnCompanyLayer: 'All Specialization',
            displayedSpecializationNameOnFunctionalLayer: 'All Specialization',
            alumniData: null
        }
    }

    componentDidMount(){
        let url = APIConfig.GET_COURSE_NAUKRIDATA;
        getRequest(url+'?courseId='+this.props.courseData.courseId+'&instituteId='+this.props.courseData.instituteId)
            .then((response) =>{
                this.setState({
                    alumniData: response.data.data
                },function(){
                    if(this.state.alumniData!=null){
                        this.createCompanyData(this.state.selectedSpecializationName, 'main');
                        this.createFunctionalAreaData(this.state.selectedSpecializationName, 'main');
                    }
                });
            });


    }

    openPopUp(){
        this.PopupLayer.open();
    }

    openPopUpOnCompanyLayer(){
        this.companyPopupLayer.open();
    }

    openPopUpOnFunctionalLayer(){
        this.functionalPopupLayer.open();
    }

    openCompanyLayer() {
        this.setState({
            companyFullLayer: true
        })
    }

    closeCompanyLayer(){
        this.setState({
            companyFullLayer: false
        })
    }

    openFunctionalLayer() {
        this.setState({
            functionalFullLayer: true
        })
    }

    closeFunctionalLayer(){
        this.setState({
            functionalFullLayer: false
        })
    }

    activeSpecialization(displayedSpecializationName, selectedSpecializationName, pageType){
        if(pageType=='main'){
            this.setState({
                selectedSpecializationName,
                displayedSpecializationName,
                displayedSpecializationNameOnCompanyLayer: displayedSpecializationName,
                displayedSpecializationNameOnFunctionalLayer: displayedSpecializationName
            }, this.createCompanyData(selectedSpecializationName, pageType), this.createFunctionalAreaData(selectedSpecializationName, pageType));
        }else if(pageType=='companyLayer'){
            this.setState({
                displayedSpecializationNameOnCompanyLayer: displayedSpecializationName
            }, this.createCompanyData(selectedSpecializationName, pageType));
        }else if(pageType=='functionalLayer'){
            this.setState({
                displayedSpecializationNameOnFunctionalLayer: displayedSpecializationName
            }, this.createFunctionalAreaData(selectedSpecializationName, pageType));
        }
    }

    getCompanyLayerHtml(){
        let html = [];
        let maximumEmployeesInCompanies = 0;
        let html1 = (this.state.fullCompanyListOnCompanyLayer).map(function (key, index){
            let no_of_emps = key.no_of_emps;
            if(index==0){
                maximumEmployeesInCompanies = no_of_emps;
            }
            let barWidth = Math.round(Math.abs(((no_of_emps/maximumEmployeesInCompanies)*100)));
            return(<li key={index}>
                <label><strong>{key.no_of_emps}</strong> <span> Employees</span> | {key.comp_name}</label>
                <div className='prg-bar'><p className='prg-bar pink' style={{width:`${barWidth}%`}}></p></div>
            </li>)
        });
        html.push(<div className='comp-bus-bar'>
            <div className='dropdown-primary full-width'>
                <PopupLayer onRef={ref => (this.companyPopupLayer = ref)} data={this.prepareSpecializationLayer('companyLayer')} heading="Specialization"/>
                <label className="option-slctd" onClick={this.openPopUpOnCompanyLayer.bind(this)}>
                    {this.state.displayedSpecializationNameOnCompanyLayer}
                </label>
            </div>
            <div>
                <div className='comp-bCol'>
                    <p className='alumni-hdTitle'>Companies they work for</p>
                    <ul>{html1}</ul>
                </div>
            </div>
        </div>);
        return html;
    }

    getFunctionalAreaLayerHtml(){
        let html = [];
        let maximumEmployeesInFunctionalArea = 0;
        let html1 = (this.state.fullCompanyListOnFunctionalLayer).map(function (key, index){
            let f_no_of_emps = key.no_of_emps;
            if(index==0){
                maximumEmployeesInFunctionalArea = f_no_of_emps;
            }
            let fbarWidth = Math.round(Math.abs(((f_no_of_emps/maximumEmployeesInFunctionalArea)*100)));
            return(<li key={index}>
                <label><strong>{key.no_of_emps}</strong> <span> Employees</span> | {key.func_name}</label>
                <div className='prg-bar'><p className='slctd-bar' style={{width:`${fbarWidth}%`}}></p></div>
            </li>)
        })
        html.push(<div className='comp-bus-bar last'>
                <div className='dropdown-primary full-width'>
                    <PopupLayer onRef={ref => (this.functionalPopupLayer = ref)} data={this.prepareSpecializationLayer('functionalLayer')} heading="Specialization"/>
                    <label className="option-slctd" onClick={this.openPopUpOnFunctionalLayer.bind(this)}>
                        {this.state.displayedSpecializationNameOnFunctionalLayer}
                    </label>
                </div>
                <div>
                    <div className='comp-bCol bus-col'>
                        <p className='alumni-hdTitle'>Business functions they are in</p>
                        <ul>{html1}</ul>
                    </div>
                </div>
            </div>
        );
        return html;
    }


    prepareSpecializationLayer(pageType='main'){
        const data = this.state.alumniData.specializationData;
        var otherManagementFlag = false;
        var lastIndex = '';
        var self = this;
        const listItems = new Array();
        for(var index in data){
            var splz = data[index];
            var specializationName = '';
            if( splz.name== "Systems" || splz.name == "#N/A") {
                specializationName = '';
            }
            else if(splz.name == "Other Management") {
                otherManagementFlag = true;
                specializationName = '';
            }
            else if(splz.name == "Marketing") {
                specializationName = 'Sales & Marketing';
            }else if(splz.name == "HR/Industrial Relations"){
                specializationName = 'Human Resources';
            }else if(splz.name == "Information Technology"){
                specializationName = 'IT';
            }else{
                specializationName = splz.name;
            }
            lastIndex = index;
            if(specializationName){
                listItems.push(<li key={index} onClick={self.activeSpecialization.bind(self,specializationName,splz.name, pageType)}>
                    {specializationName}
                </li>);
            }
        }

        return(<ul className="ul-list" key="ullist">
            <li key='all' onClick={self.activeSpecialization.bind(self,'All Specialization','All', pageType)}>All Specialization</li>
            {listItems}
            {(otherManagementFlag)?<li key={lastIndex+1} onClick={self.activeSpecialization.bind(self,'Other Management','Other Management', pageType)}>Other Management</li>:''}
        </ul>);
    }

    createCompanyData(specializationName, pageType='main'){
        let naukriData = {};
        const specialization = this.state.alumniData.specializationData;
        if(specializationName=='All Specialization' || specializationName=='All'){
            for(var i in specialization){
                var splz = specialization[i];
                var companyData = splz.companyData;
                for(var index in companyData){
                    var cData = companyData[index];
                    naukriData[cData.name] = naukriData[cData.name] || [];
                    naukriData[cData.name].push(cData.count);
                }
            }
        }else{
            for(let i in specialization){
                let splz = specialization[i];
                if(splz.name==specializationName){
                    let companyData = splz.companyData;
                    for(let index in companyData){
                        let cData = companyData[index];
                        naukriData[cData.name] = naukriData[cData.name] || [];
                        naukriData[cData.name].push(cData.count);
                    }
                }
            }
        }

        var final_response = [];
        var totalAlumni = 0;
        Object.keys(naukriData).forEach(function (key){
            var total_emp = naukriData[key].reduce((a, b) => a + b, 0);
            totalAlumni = totalAlumni + total_emp ;
            final_response.push({'comp_name':key, 'no_of_emps':total_emp});
        });

        final_response.sort(function(obj1, obj2){
            return obj2.no_of_emps- obj1.no_of_emps;
        })

        var items = final_response.slice(0, 3)
        if(pageType=='main'){
            this.setState({
                slicedCompanyList: items,
                fullCompanyList: final_response,
                fullCompanyListOnCompanyLayer : final_response,
            })
        }else if(pageType=='companyLayer'){
            this.setState({
                fullCompanyListOnCompanyLayer : final_response,
            })
        }
    }

    createFunctionalAreaData(specializationName, pageType='main'){
        let naukriData = {};
        const specialization = this.state.alumniData.specializationData;
        if(specializationName=='All Specialization' || specializationName=='All'){
            for(let i in specialization){
                let splz = specialization[i];
                let functionalNameMapping = splz.functionalAreaData;
                for(let index in functionalNameMapping){
                    let cData = functionalNameMapping[index];
                    naukriData[cData.name] = naukriData[cData.name] || [];
                    naukriData[cData.name].push(cData.count);
                }
            }
        }else{
            for(let i in specialization){
                let splz = specialization[i];
                if(splz.name==specializationName){
                    let functionalNameMapping = splz.functionalAreaData;
                    for(let index in functionalNameMapping){
                        let cData = functionalNameMapping[index];
                        naukriData[cData.name] = naukriData[cData.name] || [];
                        naukriData[cData.name].push(cData.count);
                    }
                }
            }
        }

        var final_response = [];
        var totalAlumni = 0;
        Object.keys(naukriData).forEach(function (key){
            var total_emp = naukriData[key].reduce((a, b) => a + b, 0);
            totalAlumni = totalAlumni + total_emp ;
            final_response.push({'func_name':key, 'no_of_emps':total_emp});
        });

        final_response.sort(function(obj1, obj2){
            return obj2.no_of_emps- obj1.no_of_emps;
        })

        var items = final_response.slice(0, 3)

        if(pageType=='main'){
            this.setState({
                slicedFunctionalAreaList: items,
                fullFunctionalAreaList: final_response,
                fullCompanyListOnFunctionalLayer: final_response
            })
        } else if(pageType=='functionalLayer'){
            this.setState({
                fullCompanyListOnFunctionalLayer : final_response,
            })
        }
    }

    render() {
        if(this.state.alumniData==null){
            return (null);
        }

        let companylayerHtml = this.getCompanyLayerHtml();
        let functionallayerHtml = this.getFunctionalAreaLayerHtml();
        let alumniCountByPlacements = this.state.alumniData.alumniCountByPlacements;
        let heading = 'Employment details of '+alumniCountByPlacements+' alumni';
        let maximumEmployeesInCompanies = 0;
        let maximumEmployeesInFunctionalArea = 0;
        let disclaimer = "Disclaimer: Shiksha alumni salary and employment data relies entirely on salary information provided by individual users on Naukri. To maintain confidentiality, only aggregated information is made available. Salary and employer data shown here is only indicative and may differ from the actual placement data of college. Shiksha offers no guarantee or warranty as to the correctness or accuracy of the information provided & will not be liable for any financial or other loss directly or indirectly arising or related to use of the same.";
        return (
            <section className="aluminiBnr listingTuple" id="naukri_widget_data">
                <div className='_container'>
                    <h2 className='tbSec2'>Alumni Employment Stats</h2>
                    <div className="_subcontainer">
                        <div className='almn-det'>
                            {/*<p className={styles['plc-title']}>Showing placement details for Master of Business
                          Administration programs of Indian Institute of Management, Ahmedabad.</p>*/}

                            <AlumniSalaryGraph alumniData={this.state.alumniData.salaryBuckets} alumniCountBySalaries={this.state.alumniData.alumniCountBySalaries}/>

                            <div className='comp-bus-bar'>
                                <h3 className='admisn f50'>Employment details of {alumniCountByPlacements} alumni</h3>
                                <div className='dropdown-primary'>
                                    <PopupLayer onRef={ref => (this.PopupLayer = ref)} data={this.prepareSpecializationLayer('main')} heading="Specialization"/>
                                    <label className="option-slctd" onClick={this.openPopUp.bind(this)}>
                                        {this.state.displayedSpecializationName}
                                    </label>
                                </div>
                                <div id="employeeData">
                                    <div className='comp-bCol'>
                                        <p>Companies they work for</p>
                                        <ul>
                                            {
                                                (this.state.slicedCompanyList).map(function (key, index){
                                                    let no_of_emps = key.no_of_emps;
                                                    if(index==0){
                                                        maximumEmployeesInCompanies = no_of_emps;
                                                    }
                                                    let barWidth = Math.round(Math.abs(((no_of_emps/maximumEmployeesInCompanies)*100)));
                                                    return(<li key={index}>
                                                        <label><strong>{key.no_of_emps}</strong> <span> Employees</span> | {key.comp_name}</label>
                                                        <div className='prg-bar'><p className='prg-bar pink' style={{width:`${barWidth}%`}}></p></div>
                                                    </li>)
                                                })
                                            }
                                        </ul>
                                    </div>
                                    <div className='button-container'>
                                        <FullPageLayer data={companylayerHtml} heading={heading} isOpen={this.state.companyFullLayer} onClose={this.closeCompanyLayer.bind(this)}/>
                                        {this.state.fullCompanyListOnCompanyLayer.length>3?
                                            <div className="button-container"><button type="button" onClick={this.openCompanyLayer.bind(this)} className="button button--secondary arrow">View All Alumni by Company </button></div>:''}
                                    </div>
                                </div>
                            </div>
                            <div className='comp-bus-bar last'>
                                <div className='comp-bCol bus-col'>
                                    <p>Business functions they are in</p>
                                    <ul>
                                        {
                                            (this.state.slicedFunctionalAreaList).map(function (key, index){
                                                let f_no_of_emps = key.no_of_emps;
                                                if(index==0){
                                                    maximumEmployeesInFunctionalArea = f_no_of_emps;
                                                }
                                                let fbarWidth = Math.round(Math.abs(((f_no_of_emps/maximumEmployeesInFunctionalArea)*100)));
                                                return(<li key={index}>
                                                    <label><strong>{key.no_of_emps}</strong> <span> Employees</span> | {key.func_name}</label>
                                                    <div className='prg-bar'><p className='slctd-bar' style={{width:`${fbarWidth}%`}}></p></div>
                                                </li>)
                                            })
                                        }
                                    </ul>
                                </div>
                                <div className='button-container'>
                                    <FullPageLayer data={functionallayerHtml} heading={heading} isOpen={this.state.functionalFullLayer} onClose={this.closeFunctionalLayer.bind(this)}/>
                                    {this.state.fullCompanyListOnFunctionalLayer.length>3?
                                        <div className="button-container"><button type="button" onClick={this.openFunctionalLayer.bind(this)} className="button button--secondary arrow">View All Alumni by Function </button></div>:''}
                                </div>
                            </div>
                            <input type="checkbox" className="read-more-state hide" id="disclaimer_text"/>
                            <p className='disclmr-pra read-more-wrap word-wrap' key="disclaimer_text" dangerouslySetInnerHTML={{ __html : cutStringWithShowMore(disclaimer ,83,'disclaimer_text','more')}}></p>
                        </div>

                    </div>
                </div>
            </section>
        );
    }
}



export default AlumniComponent;

AlumniComponent.propTypes = {
    courseData: PropTypes.any
}

AlumniSalaryGraph.propTypes = {
    alumniCountBySalaries: PropTypes.any,
    alumniData: PropTypes.any
}