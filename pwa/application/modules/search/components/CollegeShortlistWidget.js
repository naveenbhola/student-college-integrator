import PropTypes from 'prop-types'
import React, {Component} from 'react';
import '../assets/CollegeShortlistWidget.css'
import Analytics from "../../reusable/utils/AnalyticsTracking";
import {Link} from "react-router-dom";

class CollegeShortlistWidget extends Component {
    constructor(props){
        super(props);
    }
    trackEvent(actionLabel, label){
        Analytics.event({category: this.props.gaTrackingCategory, action: actionLabel, label: label});
    }
    render(){
        return(<section className={this.props.fullLengthWidget && this.props.deviceType === "desktop" ? "ranking--clgwidget" :
                this.props.deviceType === "mobile" ? "ranking--clgwidgetMobile" : ""}>
                <div className={"_subcontainer inner-bordercol text-center" + (this.props.fullLengthWidget &&
                this.props.deviceType === "mobile" ? " padding " : "")}>
                    <h2>Get college recommendation based on the engineering exams you have given</h2>
                    <Link to="/college-predictor" className="button button--orange"
                          onClick={this.trackEvent.bind(this, "college_predictor_interlinking", "click")}>Get Recommendations Now</Link>
                </div>
            </section>

        );
    }
}
export default CollegeShortlistWidget;

CollegeShortlistWidget.propTypes = {
  deviceType: PropTypes.string.isRequired,
  fullLengthWidget: PropTypes.bool,
  gaTrackingCategory: PropTypes.string.isRequired
};

CollegeShortlistWidget.defaultProps = {
  fullLengthWidget: false
};