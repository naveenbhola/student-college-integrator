import React, {Component} from 'react';
import {bindActionCreators} from 'redux';
import ContentLoader from './../../categoryList/utils/contentLoader'
import {fetchRecommendationData} from "../actions/RecommendationPageAction";
import CategoryWidget from "../../../search/components/CategoryWidget";
import {connect} from 'react-redux';
import "./../assets/RecommendationPage.css";
import PCW from "../../../search/components/PCW";
import ElasticSearchTracking from "../../../reusable/utils/ElasticSearchTracking";

class RecommendationPage extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isShowLoader : false
        };

    }
    componentDidMount() {
        if (!this.isServerSideRenderedHTML()) {
            this.initialFetchData();
        }
        if (!this.isErrorPage()) {
            this.trackBeacon();
        }
    }

    trackBeacon() {
        const {recommendationData, config} = this.props;
        if (recommendationData) {
            let trackingParams;
            trackingParams = {};
            trackingParams['pageIdentifier'] = 'search';
            trackingParams['pageEntityId'] = 0;
            trackingParams['extraData'] = {};
            trackingParams['extraData']['childPageIdentifier'] = 'recommendationPage';
            ElasticSearchTracking(trackingParams, config.BEACON_TRACK_URL);
        }
    }

    initialFetchData() {
        const queryParams = this.props.location;
        if(!queryParams)
            return;
        const paramsDataArray = queryParams.split("data=");
        if(!paramsDataArray || paramsDataArray.length < 2)
            return;
        const paramsData = paramsDataArray[1];
        this.setState({isShowLoader: true});
        let fetchPromise = this.props.fetchRecommendationData(paramsData);
        fetchPromise.then(() => {
            this.setState({isShowLoader: false});
            this.trackBeacon();
        });
    }

    getPCWWidget(){
        let pcwArray = [];
        const {recommendationData} = this.props;
        const instituteArray = recommendationData.categoryInstituteTuple;
        for(let instituteData in instituteArray) {
            pcwArray.push(<PCW key={"pcw"+instituteData} nonPWALinks={false}
                               aggregateReviewConfig={recommendationData.aggregateRatingConfig}
                               showPCW={true} tupleData={[instituteArray[instituteData]]}
                               gaTrackingCategory={"ILP_Recommendation"}/>)
        }
        return pcwArray;
    }

    render() {
        const {recommendationData} = this.props;
        if (this.state.isShowLoader  || !recommendationData || Object.keys(recommendationData).length === 0) {
            return <ContentLoader />;
        } else if (recommendationData && Array.isArray(recommendationData.categoryInstituteTuple) &&
            recommendationData.categoryInstituteTuple.length === 0) {
            return <ContentLoader />;
        }
        return (
            <div id="recoPage">
                <section>
                        <div className="recomPageHeading">
                            <h1>{recommendationData.categoryInstituteTuple.length} Colleges picked just for you</h1>
                            <p>Inspired by your browsing history</p>
                        </div>
                    {recommendationData.coursePage ? <CategoryWidget categoryData={recommendationData} config={this.props.config} showUSPLda={false}
                                        gaTrackingCategory="ILP_Recommendation" showOAF={false} deviceType="mobile"
                                        ebTrackid={273} srtTrackId={1089} showRecoLayer ={false}/> : this.getPCWWidget()

                    }

                </section>
            </div>
        )
    }

    isServerSideRenderedHTML() {
        let htmlNode = document.getElementById('recoPage');
        return ((htmlNode && htmlNode.innerHTML) || this.isErrorPage());
    }

    isErrorPage() {
        let html404 = document.getElementById('notFound-Page');
        return (html404 && html404.innerHTML);
    }
}



function mapStateToProps(state) {
    return {
        recommendationData : state.recommendationData,
        config: state.config
    }
}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({fetchRecommendationData}, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(RecommendationPage);
