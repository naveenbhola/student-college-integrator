import PropTypes from 'prop-types'
import React, {Component} from 'react';
import './../assets/naukriCompanyList.css';
import {getObjectSize} from './../../../../utils/commonHelper';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import Lazyload from './../../../reusable/components/Lazyload';

class NaukriCompanyList extends Component {
    constructor(props) {
        super(props);
        this.state = {
            numberOfCompanyList :10
        }
    }

    trackEvent(actionLabel,label)
    {
      var category = "Placement_Page";
      if(this.props.gaTrackingCategory){
        category = this.props.gaTrackingCategory; 
      }
      Analytics.event({category : category, action : actionLabel, label : label});
    }


    companyList  =  () => {
        const {companyData}  = this.props;
        let number = getObjectSize(companyData);
        let maxSalary = 0;
         maxSalary = companyData[0].avgSalary;
        let companyList = [];
        let index =0;
        for(let i in companyData){
            index++;
            if(index > this.state.numberOfCompanyList){
                companyList.push(<label key={'comany_'+index} onClick={() => this.viewAllCompany()} id='view_all_company' className="arrow">{'View All '+number}</label>);
                break;
            }
            let salary =  parseInt((companyData[i].avgSalary/maxSalary)*100);
            let pageHtml =
                (
                    <div className={"organizer_list"} key={'comany1_'+index}>
                        <div className="organizer_logo">
                            <Lazyload offset={100} once>
                                <img src={companyData[i].companyLogoFileName} alt="sample image" />
                            </Lazyload>
                        </div>
                        <div className="organizer_dtls">
                            <p className="organizer_name">{companyData[i].companyName}</p>
                            <div className="progress_block">
                                <div className="avg_package">Avg &#8377; {companyData[i].avgSalary}L <span>|</span> {companyData[i].numAlumni} Alumni</div>
                                <div className="packagebar">
                                    <div className="avg_packagebar" style={{width: salary + '%'}}></div>
                                </div>
                            </div>
                        </div>
                    </div>
                );
            companyList.push(pageHtml);
        }
        return companyList;
    }

    viewAllCompany(){
        this.trackEvent("Click","view_all_20_companies_Naukri_placement")
        this.setState({'numberOfCompanyList':25});
    }


    render(){
        const {companyData,totalCompanies}  = this.props;
        if(totalCompanies ==0){
            return null;
        }   
        return(
            <div className="salary_stats" id='companyList'>
                <strong className="organzr_titl">How much they earn</strong>
                <div className='salary_stats_desk'>
                    {this.companyList()}

                </div>
            </div>
        )
    }

}

export default NaukriCompanyList;

NaukriCompanyList.propTypes = {
    companyData: PropTypes.any
}