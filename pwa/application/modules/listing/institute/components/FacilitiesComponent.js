import PropTypes from 'prop-types'
import React from 'react';
import './../assets/css/style.css';
import FullPageLayer from '../../../common/components/FullPageLayer';
import './../../course/assets/courseCommon.css';
import {htmlEntities,nl2br } from './../../../../utils/stringUtility';
import { makeURLAsHyperlink } from './../../../../utils/urlUtility';
import Analytics from './../../../reusable/utils/AnalyticsTracking';

class Facilities extends React.Component
{
    constructor(props)
    {
        super(props);
        this.IconMapping ={
            "Library": "library",
            "Cafeteria": "cafeteria",
            "Hostel": "hostel",
            "Sports Complex": "sportsCmplx",
            "Gym": "gym",
            "Hospital / Medical Facilities": "hosptl",
            "Wi-Fi Campus": "wifi",
            "Auditorium": "auditorium",
            "Music Room": "music_room",
            "A/C Classrooms": "ac",
            "Convenience Store": "cnvnStr",
            "Labs": "labs",
            "Shuttle Service": "shuttle",
            "Design Studio": "design_studio",
            "Law College": "moot_court",
            "Dance Room": "dance_room",
            "Moot Court (Law)":"moot_court"
        };

        this.facility = this.props.data;
        this.state = { layerOpen :false,
            ScrollToElementId :''
        };
        this.showViewAll = false;
    }

    trackEvent()
    {
        var category = 'ILP';
        if(this.props.page == "university"){
            category = 'ULP';
        }
        Analytics.event({category : category, action : 'FacilityWidget', label : 'click'});
    }

    closeRecoLayer(){
        this.setState({layerOpen : false});
    }

    handleClick(i) {
        this.trackEvent();
        this.setState({layerOpen:true , ScrollToElementId: 'facility_layer_'+i});
    }


    othersHtmlGenerate(othersFacilities){
        var othersHTML = [];
        var length = othersFacilities.otherFacilities.length;
        for(var i=0;i<length;i++){
            othersHTML.push(<div className="flex-ul m-5top" key = {'facility_'+i}>
                    {(i<length) && <div className="fac-bx otherdtls">{ othersFacilities.otherFacilities[i++]}</div>}

                    {(i<length) && <div className="fac-bx otherdtls">{ othersFacilities.otherFacilities[i]}</div>}
                </div>

            );
        }
        return othersHTML;
    }

    generateFacilityLayerData(facilityWithChild){

        var childHtml = [];
        var mandatoryHostel = [];
        var length = facilityWithChild.child_facilities.length;
        for(var i =0;i<length;i++){
            if(facilityWithChild.child_facilities[i].facility_name == "Mandatory Hostel" && facilityWithChild.child_facilities[i].has_facility != 0){
                mandatoryHostel.push(<div className = "mndtry_hstl"key = {"mandatory_hostel"}>
                    <p>note : Staying in hostel is mandatory</p>
                </div>);
            }
            else if(facilityWithChild.child_facilities[i].facility_name && facilityWithChild.child_facilities[i].has_facility != 0){
                childHtml.push(<div className = "iblock" key={"child_facilities_"+i}>
                        <strong>{facilityWithChild.child_facilities[i].facility_name}</strong>
                        {(facilityWithChild.child_facilities[i].additional_info) && facilityWithChild.child_facilities[i].additional_info.map(function(content){
                            return(<div className="facilitycell" key ={"facilitycell_"+content.name}>
                                <p>{(content.value != true)?content.name+" - "+content.value: content.name}</p>
                            </div>)
                        })
                        }
                    </div>

                );}
        }
        return (
            <React.Fragment>
                {mandatoryHostel}
                {(childHtml != "" && facilityWithChild.child_facilities && facilityWithChild.facility_name != "Hostel")?<p className="fnt10 avlbl_fact">Available Facilities:</p>: null}
                <div className = "cellcontainer">
                    {childHtml}
                </div>
            </React.Fragment>
        )
    }

    generateFacilityLayerHtml(facility){
        var layerHtml = [];
        var length = facility.length;
        for(let index = 0;index<length; index++) {
            if(facility[index] && facility[index].facilityDetail && facility[index].facilityName != null){
                layerHtml.push((facility[index].facilityDetail.description || facility[index].facilityDetail.child_facilities) && <div className="facilityLayer" key ={"facility_layer_"+index} id ={"facility_layer_"+index} >
                    <div className="faciltyList">
                        <div className="faciltydataTitle">{facility[index].facilityName}</div>
                        <div className="faciltydata">
                            {(facility[index].facilityDetail.description)? <p dangerouslySetInnerHTML={{ __html :  nl2br(makeURLAsHyperlink(htmlEntities(facility[index].facilityDetail.description)))  }} ></p> : null}
                            {(facility[index].facilityDetail.child_facilities != null)?this.generateFacilityLayerData(facility[index].facilityDetail):null}
                        </div>
                    </div>
                </div>
                );
            }
        }
        return (
            <div className="facility_all">
                {layerHtml}
            </div>
        );

    }

    generateFacilityHtml(facility){
        var facilityHtml = [];
        var length = facility.length;
        var flagForViewMore =false;
        for(var index = 0;index < length; index++) {
            if(index>=8){flagForViewMore = true; this.showViewAll = true;}
            if(facility[index] && facility[index].facilityName != null && facility[index].facilityName != "Others" && facility[index].otherFacilities === null){
                facilityHtml.push( <div className={(index>=8) ?"fac-List read-more-target" :"fac-List"} key={'facility_'+index} >
                        <div className="fac-bx">
                            <div className="fac-icn"><i className={this.IconMapping[facility[index].facilityName]}></i></div>
                            <div className="fac-info">
                                <span> {facility[index].facilityName} </span>
                                {facility[index].viewFacility && <a href={(this.props.isAmp)?'' :  'javascript:void(0);' } id = {index} onClick={this.handleClick.bind(this, index)}>View Details</a>}

                            </div>
                        </div>
                        {(facility[++index] && facility[index].facilityName != null && facility[index].facilityName != "Others" && facility[index].otherFacilities === null) && <div className="fac-bx" key = {'facility_'+index} >
                            <div className="fac-icn"><i className={this.IconMapping[facility[index].facilityName]}></i></div>
                            <div className="fac-info">
                                <span> {facility[index].facilityName}</span>
                                {facility[index].viewFacility && <a href={(this.props.isAmp)?'' :  'javascript:void(0); '}  id = {index} onClick={this.handleClick.bind(this, index)}>View Details</a>}
                            </div>
                        </div>}
                    </div>

                );
            }


            var othersHTML = null;

            if(facility[index] && (facility[index].facilityName === null || facility[index].facilityName === "Others" || facility[index].facilityName ==="") && facility[index].otherFacilities != null && facility[index].otherFacilities.length >0){
                othersHTML = this.othersHtmlGenerate(facility[index]);
            }
            else if(facility[index-1] && (facility[index-1].facilityName === null || facility[index-1].facilityName === "Others" || facility[index-1].facilityName ==="") && facility[index-1].otherFacilities != null && facility[index-1].otherFacilities.length >0){
                othersHTML = this.othersHtmlGenerate(facility[index-1]);
            }
            if(othersHTML){facilityHtml.push( <div className={flagForViewMore ?"fac-List read-more-target" :"fac-List"} key={'facility_'+index}>
                    <div className="fac-bx">
                        <div className="fac-info otrsfctls">
                            <span className = "other_faclt"> Other Facilities: </span>
                            {othersHTML}
                        </div>
                    </div>
                </div>
            );}


        }

        if(facilityHtml.length > 0)
            return facilityHtml;
        else return null;
    }

    generateFacilityHtmlDesktop(facility){
        var facilityHtml = [];
        var length = facility.length;
        var flagForViewMore =false;
        for(var index = 0;index < length; index++) {
            if(facility[index] && facility[index].facilityName != null && facility[index].facilityName != "Others" && facility[index].otherFacilities === null){
                facilityHtml.push( <div className={"fac-List"} key={'facility_'+index} >
                        <div className="fac-bx">
                            <div className="fac-icn"><i className={this.IconMapping[facility[index].facilityName]}></i></div>
                            <div className="fac-info">
                                <span> {facility[index].facilityName} </span>
                                {facility[index].viewFacility && <a href='javascript:void(0);' id = {index} onClick={this.handleClick.bind(this, index)}>View Details</a>}

                            </div>
                        </div>
                    </div>

                );
            }


            var othersHTML = null;

            if(facility[index] && (facility[index].facilityName === null || facility[index].facilityName === "Others" || facility[index].facilityName ==="") && facility[index].otherFacilities != null && facility[index].otherFacilities.length >0){
                othersHTML = this.othersHtmlGenerate(facility[index]);
            }
            else if(facility[index-1] && (facility[index-1].facilityName === null || facility[index-1].facilityName === "Others" || facility[index-1].facilityName ==="") && facility[index-1].otherFacilities != null && facility[index-1].otherFacilities.length >0){
                othersHTML = this.othersHtmlGenerate(facility[index-1]);
            }
            if(othersHTML){facilityHtml.push( <div className={flagForViewMore ?"fac-List fac-expanded" :"fac-List fac-expanded"} key={'facility_'+index}>
                    <div className="fac-bx">
                        <div className="fac-info otrsfctls">
                            <span className = "other_faclt"> Other Facilities: </span>
                            {othersHTML}
                        </div>
                    </div>
                </div>
            );}


        }

        if(facilityHtml.length > 0)
            return facilityHtml;
        else return null;
    }


    render()
    {
        var facilityHtml=[];
        if(this.props.isDesktop){
            facilityHtml = this.generateFacilityHtmlDesktop(this.props.data);
        }
        else{
            facilityHtml= this.generateFacilityHtml(this.props.data);
        }
        if(facilityHtml){
            return(
                <section className='listingTuple' id="Facilities">
                    <div className="_container">
                        <h2 className="tbSec2">Infrastructure/Facilities</h2>
                        <div className="_subcontainer">
                            <input id="facility_view" type="checkbox" className = "read-more-target hide" name="facilities_view_all"/>
                            <div className="read-more-wrap">
                                {facilityHtml}
                            </div>
                            {(this.showViewAll) && <div className="button-container">
                                <a onClick={this.trackEvent.bind(this)}><label htmlFor="facility_view"  className="read-more-trigger vwal-Link">View All <i className="chBrnch-ico"></i></label></a>
                            </div>}
                        </div>
                    </div>
                    <FullPageLayer data={this.generateFacilityLayerHtml(this.props.data)}  heading={'Infrastructure/Facilities'} onClose={this.closeRecoLayer.bind(this)} isOpen={this.state.layerOpen} ScrollToElementId={this.state.ScrollToElementId}/>
                </section>

            )
        }
        else return null;
    }
}


export default Facilities;

Facilities.propTypes = {
    data: PropTypes.any,
    isAmp: PropTypes.any,
    isDesktop: PropTypes.any,
    page: PropTypes.any
}