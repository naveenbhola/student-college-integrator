import React from 'react';
import './../assets/css/style.css';
import './../../course/assets/courseCommon.css';
import {stringTruncate,cutString} from './../../../../utils/stringUtility';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {addingDomainToUrl, getImageUrlBySize, formatDate} from './../../../../utils/commonHelper';
import Anchor from "../../../reusable/components/Anchor";
import Lazyload from '../../../reusable/components/Lazyload';


const Articles = (props) => {
    let isAmp = props.isAmp;
    var category = 'ILP';
    var action   = 'Articlewidget';
    var labelName= 'Click';
    if(props.page == "university"){
        category = 'ULP';
    }else if(props.page=="CourseHomePage"){
        category = "CHP";
        action   = 'Articles';
        labelName= 'Articles_ViewAll';
    }else if(props.page === 'ExamPage'){
        category = "ExamPage";
    }

    action = (props.deviceType == 'desktop') ? 'Desktop_'+action : action;

    const trackEvent = () => (Analytics.event({category : category, action : category+'_'+action, label : labelName}));

    const gaTrackEvent = () => (Analytics.event({category : category, action : category+'_'+action, label : category+'_'+action+'_Title_Click'}));

    var numberOfArticles = 0;
    var articlesData = props.data.articleData.articleDetails;
    var totalArticleCount = props.data.articleData.numFound;
    var numberOfArticleToShow = articlesData.length;
    let countNumbetText = totalArticleCount > 0 ? " "+totalArticleCount : "";
    if(numberOfArticleToShow >0)
    {
        var allArticleURL = addingDomainToUrl(props.data.allArticlePageUrl,props.config.SHIKSHA_HOME);
        let articleText = numberOfArticleToShow>1?'Articles':'Article';
        return (
            <React.Fragment>
                { (isAmp) ?
                    <section className='listingTuple' id="Articles">
                        <div className="_container">
                            <h2 className="tbSec2">News & Articles  <span>{totalArticleCount > 0 ? "(Showing "+numberOfArticleToShow+" of "+ totalArticleCount+" "+articleText+")": ""}</span></h2>
                            <div className="_subcontainer">
                                <ul className="artcl-List">
                                    {
                                        articlesData.map(function(articles,index){
                                            numberOfArticles = numberOfArticles+1;
                                            return (
                                                <li key={"article_"+index}>
                                                    <strong><a href ={addingDomainToUrl(articles.url,props.config.SHIKSHA_HOME)} onClick={gaTrackEvent} >{articles.blogTitle}</a></strong>
                                                    <input type="checkbox" id={"article_"+index} className="read-more-state hide" value="articles"/>
                                                    <p className='read-more-wrap word-break' dangerouslySetInnerHTML={{ __html : cutString(articles.summary,86,"article_"+index)}}></p>
                                                </li>
                                            )
                                        })
                                    }
                                </ul>
                                <div className="button-container" id="viewMoreLink" ><a href={allArticleURL} onClick= {trackEvent} className="trnBtn rippleefect dark">{"View all"+countNumbetText+" Articles"}<i className="blu-arrw"></i></a></div>
                            </div>
                        </div>
                    </section>:
                    <section className='listingTuple' id="Articles">
                        <div className="_container">
                            <h2 className="tbSec2">{props.heading} <span>{(props.showCount && totalArticleCount > 0) ? "(Showing "+numberOfArticleToShow+" of "+ totalArticleCount+" "+articleText+")": ""}</span></h2>
                            <div className="_subcontainer">
                                <div className="articles_box">
                                    {
                                        articlesData.map(function(articles, index) {
                                            numberOfArticles++;
                                            return (
                                                <div className="article_block" key={"article_"+index}>
                                                    <div className="pwa_thumbnail">
                                                        {articles.blogImageURL!=null && articles.blogImageURL!=='' && <Lazyload offset={100} once><img src={props.config.IMAGES_SHIKSHA+getImageUrlBySize(articles.blogImageURL, 'medium')} alt={articles.blogTitle} /></Lazyload>}
                                                    </div>
                                                    <div className="article_infocol">
                                                        {articles.featured && <span className="sponsoredTag">SPONSORED</span>}
                                                        <Anchor link={false} to={addingDomainToUrl(articles.url, props.config.SHIKSHA_HOME)} onClick={gaTrackEvent}>{stringTruncate(articles.blogTitle, 60)}</Anchor>
                                                        <p>{formatDate(articles.lastModifiedDate)}</p>
                                                    </div>
                                                </div>
                                            );
                                        })
                                    }
                                </div>
                                {allArticleURL!=null && allArticleURL!=='' && <div className="button-container" id="viewMoreLink" ><Anchor link={false} to={allArticleURL} onClick= {trackEvent} className=""><button type="button" name="button" className="button button--secondary arrow">{"View all"+countNumbetText+" Articles"} </button></Anchor></div>}
                            </div>
                        </div>
                    </section>
                }
            </React.Fragment>
        );
    }

};
Articles.defaultProps = {
    heading : 'News & Articles',
    showCount : true,
    isAmp: false
}

export default Articles;
