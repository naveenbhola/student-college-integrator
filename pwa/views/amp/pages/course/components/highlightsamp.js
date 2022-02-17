import React from 'react';
import {htmlEntities,nl2br} from '../../../../../application/utils/stringUtility';
import { makeURLAsHyperlink } from '../../../../../application/utils/urlUtility';

const  HighlightsAmp = (props)=>{
  var numberOfUSP = 0;
  var maxDisplayUsp = 4;
    return(
      <div className="data-card m-5btm" id="high">
          <h2 className="color-3 f16 heading-gap font-w6">Highlights</h2>
          <div className="card-cmn color-w">
              <input type="checkbox" className="read-more-state hide" id="post-11" />
              <ol className="highlights-ol read-more-wrap">

                {
                  props.data.map(function(usp,index){
                    numberOfUSP++;
                    return <li key={"usp_"+index} className={index >= maxDisplayUsp ? 'read-more-target color-6 f13 word-break' : 'color-6 f13 word-break'} dangerouslySetInnerHTML={{ __html :nl2br(makeURLAsHyperlink(htmlEntities(usp.description)))}}></li>
                  })
                }
              </ol>
              {numberOfUSP > maxDisplayUsp &&
                  <label for="post-11" className="read-more-trigger color-b t-cntr f14 color-b block font-w6 v-arr ga-analytic" data-vars-event-name="Highlight_VIEWALL">View all</label>
              }

          </div>
      </div>
    )
  }


export default HighlightsAmp;
