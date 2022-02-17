import PropTypes from 'prop-types'
import React, {Component} from 'react';
import CategoryTupleNew from "./CategoryTupleNew";

class CategoryWidget extends Component {
    generateTuples(){
        const {categoryData} = this.props;
        const instituteArray = categoryData.categoryInstituteTuple;
        let tupleArray = [];
        for(let instituteData in instituteArray){
            if(!instituteArray.hasOwnProperty(instituteData))
                continue;
            const instituteId = instituteArray[instituteData].instituteId;
            tupleArray.push(
                <CategoryTupleNew showRecoLayer={this.props.showRecoLayer} key={"insti_" + instituteId} {...this.props}
                                   index = {instituteData} categoryTuple={instituteArray[instituteData]}/>
            );
        }
        return tupleArray;
    }
    render() {
        return (
            <div className="ctpSrp-contnr">
                {this.generateTuples()}
            </div>)
    }
}
export default CategoryWidget;

CategoryWidget.propTypes = {
  applyNowTrackId: PropTypes.number,
  categoryData: PropTypes.object.isRequired,
  config: PropTypes.object,
  deviceType: PropTypes.string.isRequired,
  ebTrackid: PropTypes.number.isRequired,
  isPdfCall: PropTypes.bool,
  loadMoreCourses: PropTypes.object,
  pageType: PropTypes.string,
  recoEbTrackid: PropTypes.number,
  recoShrtTrackid: PropTypes.number,
  showOAF: PropTypes.bool.isRequired,
  showUSPLda: PropTypes.bool.isRequired,
  srtTrackId: PropTypes.number.isRequired,
  showRecoLayer: PropTypes.bool
};

CategoryWidget.defaultProps = {
  isPdfCall: false,
  showRecoLayer: true
};
