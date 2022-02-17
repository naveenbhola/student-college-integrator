import React from 'react';
import './../../../common/assets/Wikki.css';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {getTextFromHtml,strip_html_tags} from './../../../../utils/stringUtility';


class EditorialContentComponent extends React.Component{

    constructor(props) {
        super(props);
        this.state = {
            showFullAboutSection :false
        }
    }


    trackEvent(actionLabel,label)
    {
        Analytics.event({category : this.props.gaCategory, action : actionLabel, label : label});
    }

    getEditorialData(){
        const {data} = this.props;
        let showFullText='hid';
        let showHalfText='';
        if(this.state.showFullAboutSection){
            showFullText = '';
            showHalfText = 'hid';
        }else{
            showFullText = 'hid';
            showHalfText = '';
        }
        var StringWithoutTags = strip_html_tags(data);
            return(
                <React.Fragment>
                <div className={'aboutSection '+showHalfText}>
                    <div dangerouslySetInnerHTML={{
                         __html: getTextFromHtml(data, this.props.readMoreCount)
                        }}></div>
                    {StringWithoutTags.length>this.props.readMoreCount?<a href='javascript:void(0)' onClick={this.viewFullSection} > Read More</a>:null}    
                </div>
                <div className={'aboutSection '+showFullText}>
                    <div dangerouslySetInnerHTML={{
                        __html:(data)
                    }}></div>
                </div>
                </React.Fragment>
            )
    }

    viewFullSection = () => {
        this.trackEvent("Click","viewmore_editorial_content")
        this.setState({'showFullAboutSection':true});
    }



    render(){
        const {data} = this.props;
        return(
            <section className='listingTuple' id='Overview'>
                <div className="collegedpts listingTuple">
                    <div className="_container">
                        <div className="_subcontainer">
                            <div className="wikkiContents">
                                {data && this.getEditorialData()}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        )
    }

}


export default EditorialContentComponent;
