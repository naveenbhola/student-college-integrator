import React from 'react';
import {Link} from 'react-router-dom';
import {splitPathQueryParamsFromUrl} from '../../../../../application/utils/commonHelper';

const CourseCategoryPageLinkAmp = (props)=>{
  let rankingList = '';
	let categoryList = '';
  if(props.rankingInterlinking!=null){
	    rankingList = props.rankingInterlinking.map(function(info, index){
        let anchorText = info.title;
        if(!info.title.startsWith("Top ")) {
          anchorText = "Top "+ info.title;
        }
  			return (<li key={index} className="color-9">
  			        <a className="link color-b f14 l-14 block ga-analytic" data-vars-event-name="RANKING_LINKS" href={props.config.SHIKSHA_HOME+'/'+info.url}>{anchorText}</a>
  			    </li>)
  			});
	}
  if(props.categoryInterlinking!=null){
      categoryList = props.categoryInterlinking.map(function(info, index){
        var urlObject = splitPathQueryParamsFromUrl(info.url);
        var urlPathname = "";
        var searchParams = "";
        if(typeof urlObject == "object" && typeof urlObject.search != "undefined" && urlObject.search != "")
        {
          searchParams = urlObject.search;
        }
        if(typeof urlObject == "object" && typeof urlObject.pathname != "undefined" && urlObject.pathname != "")
        {
          urlPathname = urlObject.pathname;
        }
        if(!urlPathname || urlPathname == "" || typeof urlPathname == 'undefined')
          return;
    return (<li key={index} className="color-9">
        <Link to={{ pathname : urlPathname,search : searchParams}} className="color-b f14 l-14 block ga-analytic " data-vars-event-name="CAT_LINKS">{info.title}</Link>
        </li>)
    });
  }

  return(
    <section id="viewCollegeSec" className='viewCollegeSec'>
       <div class="data-card pad10 mr-20">
              {props.rankingInterlinking!=null && Array.isArray(props.rankingInterlinking) && props.rankingInterlinking.length > 0?
                <React.Fragment><h2 class="color-3 f15 font-w6">View colleges by ranking</h2>
                        <ul class="in-ul">
                         {rankingList}
                        </ul>
                  </React.Fragment>:''}
          </div>
           <div class="data-card pad10 mr-20">
                {props.categoryInterlinking!=null && Array.isArray(props.categoryInterlinking) && props.categoryInterlinking.length > 0 ?
  	            <React.Fragment> <h2 class="color-3 f15 font-w6">View colleges by location</h2>
  	              <ul className='in-ul'>
  	                {categoryList}
  	                </ul>
  	            </React.Fragment>:''}
    </div>
    </section>
  )
}
export default CourseCategoryPageLinkAmp;
