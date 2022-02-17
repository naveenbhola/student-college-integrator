import PropTypes from 'prop-types'
import React, {Component} from 'react';
import './../assets/naukriCompanyList.css';
import NaukriCompanyList from './NaukriCompanyList';
import PlacementPageFilters from './PlacementPageFilters';
import NaukriPlacementGraph from './NaukriPlacementGraph';
import {getObjectSize} from './../../../../utils/commonHelper';

class NaukriAlumniComponent extends Component {
    constructor(props) {
        super(props);
    }

    companyHeading(){
        const {pageData} = this.props;
        let total =getObjectSize(pageData.naukriData.companyData);
        let totalCompanies = pageData.naukriData.totalCompanies;
        let heading  = 'Showing top '+total+' of '+ totalCompanies+' companies where ';
        if(pageData.selectedBaseCourseId === -1 && pageData.selectedYear === -1){
            heading +='last 3 years alumni are employed';
        }else if(pageData.selectedBaseCourseId !== -1 && pageData.selectedYear !==-1){
            heading +=('<strong>'+pageData.selectedYear+' '+pageData.selectedBaseCourseName+'</strong> alumni are employed');
        }else if(pageData.selectedBaseCourseId != -1){
            heading +=('<strong>'+pageData.selectedBaseCourseName+'</strong> alumni are employed');
        }else if(pageData.selectedYear !==-1){
            heading +=('<strong>'+pageData.selectedYear+' passout batch</strong> alumni are employed');
        }
        if(getObjectSize(pageData.naukriData.companyData) ==0 || pageData.naukriData.totalCompanies ==0){
            return null;
        }
        else{
            return(
                <div>
                    <div className="salry_rslts" dangerouslySetInnerHTML={{__html: heading}}></div>
                </div>
            )
        }



    }



    render(){
        const {pageData} = this.props;
        let naukriData = pageData.naukriData;
        let selectedBaseCourse ='';
        if(pageData.selectedBaseCourseName){
            selectedBaseCourse = pageData.selectedBaseCourseName;
        }
        return(
            <section className="naukri_alumini" id="naukri_alumini">
                <div className="_container ctpn-filter-sec">
                    <h2 className="tbSec2" id="alumni_heading">Alumni Employment</h2>
                    <div className="_subcontainer">
                        <div className="salry_dtlsblock">
                            <div className="_padaround">
                                <PlacementPageFilters placementPageUrl={pageData.placementPageUrl} seoUrl ={pageData.seoUrl} pageData={pageData} data={naukriData} selectedBaseCourseId={pageData.selectedBaseCourseId} selectedYear={pageData.selectedYear} gaTrackingCategory={this.props.gaCategory} />
                            </div>
                            <div className="salary_graph">
                                <p className="graph_title"><strong>Salary of {selectedBaseCourse} Alumni</strong> <span>(Annual) (In INR)</span></p>
                                <div>
                                    <div className="pwa_googlechart">
                                        {naukriData.salaryData && <NaukriPlacementGraph salaryData = {naukriData.salaryData}/>}
                                    </div>
                                    <div className='note_block'>
                                        <p><strong>Note:</strong> Min, Median and Max is the salary of 25%ile, 50%ile and 95%ile alumni</p>
                                    </div>
                                    <p className="report_txt">In case you think the placement details are not accurate, please report at <a href="mailto:site.feedback@shiksha.com">site.feedback@shiksha.com</a>
                                    </p>
                                    {this.companyHeading()}
                                </div>

                            </div>
                            {naukriData.companyData &&  <NaukriCompanyList companyData={naukriData.companyData} totalCompanies={naukriData.totalCompanies} gaTrackingCategory={this.props.gaCategory}/>}
                        </div>
                    </div>
                </div>
            </section>


        )

    }
}

export default NaukriAlumniComponent;

NaukriAlumniComponent.propTypes = {
    pageData: PropTypes.any
};