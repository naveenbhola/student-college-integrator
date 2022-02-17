import PropTypes from 'prop-types'
import React, { Component } from 'react';
import PopupLayer from '../../../common/components/popupLayer';
import { makeURLAsHyperlink } from './../../../../utils/urlUtility';
import {nl2br,htmlEntities} from './../../../../utils/stringUtility';
import Analytics from './../../../reusable/utils/AnalyticsTracking';

class ScholarshipComponent extends Component{
    constructor(props){
        super(props);
    }

    getScholarshipHtml(multipleScholarships, multipleFinances, counterSch, counterFin){

        const scholarship = this.props.scholarship;
        let html = [];
        var html1 = new Array();
        var heading = '';
        for(var i in scholarship){
            var k = scholarship[i];
            let scholarshipName = k.scholarship_type_name;
            if(k.scholarship_type_name=='Scholarship' || k.scholarship_type_name=='Others' || k.scholarship_type_name=='Discount'){
                scholarshipName = 'Scholarship';
            }
            if(scholarship.length>1){
                if(multipleScholarships && k.scholarship_type_name == "Scholarship" || k.scholarship_type_name == "Discount" || k.scholarship_type_name == "Others"){
                    heading = <label><strong>{scholarshipName} {counterSch}</strong></label>
                    counterSch++;
                } else if(multipleFinances && k.scholarship_type_name == "Financial Assistance"){
                    heading = <label><strong>{scholarshipName} {counterFin}</strong></label>
                    counterFin++;
                }

            }
            html1.push(<li key={'sc1-'+k.scholarship_type_id}>{heading}<div><p className='word-wrap' dangerouslySetInnerHTML={{ __html : nl2br(makeURLAsHyperlink(htmlEntities(k.description)))}} ></p></div></li>)
        }
        html.push(<div key="sch-1">
            <div>
                <div>
                    <ul className="nrml-list word-wrap noBullet">{html1}</ul>
                </div>
            </div>
        </div>);
        return html;
    }

    openPopUpOnScholarshipLayer(){
        this.scholarshipPopupLayer.open();
    }
    trackEvent()
    {
        Analytics.event({category : 'CLP', action : 'Scholwidget', label : 'Click'});
    }

    handleOptionClick()
    {
        this.openPopUpOnScholarshipLayer();
        this.trackEvent();
    }

    render(){
        var countSch = 0;
        var countFin = 0;
        const scholarship = this.props.scholarship;
        for(var i in scholarship){
            var k = scholarship[i];
            if(k.scholarship_type_name == "Scholarship" || k.scholarship_type_name == "Discount" || k.scholarship_type_name == "Others"){
                countSch++;
            }
            if(k.scholarship_type_name == "Financial Assistance"){
                countFin++;
            }
        }
        var multipleScholarships = false;
        var multipleFinances = true;
        var counterSch = '';
        var counterFin = '';

        if(countSch >= 2){
            multipleScholarships = true;
            counterSch = 1;
        }
        if(countFin >= 2){
            multipleFinances = true;
            counterFin = 1;
        }

        let scholarshipLayerHtml = this.getScholarshipHtml(multipleScholarships, multipleFinances, counterSch, counterFin);
        return(<section id="scholarship"><div className='find-schlrSec'>
            <div className='find-schlrSec-inr'>
                <PopupLayer onRef={ref => (this.scholarshipPopupLayer = ref)} data={scholarshipLayerHtml} heading="Scholarship" onContentClickClose={false}/>
                <p>Want to Know more about {this.props.instituteName} Scholarship details?</p>
                <a href='javascript:void(0)' onClick={this.handleOptionClick.bind(this)}> <button className="button button--secondary rippleeffect dark">Read about scholarships</button></a>
            </div>
        </div></section>)
    }

}

export default ScholarshipComponent;

ScholarshipComponent.propTypes = {
    instituteName: PropTypes.any,
    scholarship: PropTypes.any
}