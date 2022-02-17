import React from 'react';
import config from './../../../../../config/config';

class CourseStickyWidgetAMP extends React.Component{
  constructor(props){
    super(props)
  }
  render(){
    const domainName = config().SHIKSHA_HOME;
    const {courseId,courseUrl} =  this.props;
    return(
    <div class="sticky-dv">
             <div class="table max-table">
                    <section class="wd50 i-block m-lt" amp-access="shortlisted" amp-access-hide="1" tabindex="0">
                      <a class="btn btn-secondary color-w color-b f14 font-w7 wd50 tab-cell ga-analytic" href={domainName+courseUrl+"?actionType=shortlist&course="+courseId+"&fromCoursePage"} data-vars-event-name="SHORTLIST">
                          Shortlisted
                      </a>
                    </section>

                    <section class="wd50 i-block m-lt" amp-access="NOT subscriber AND NOT shortlisted" amp-access-hide="1">
                          <a class="btn btn-secondary color-w color-b f14 font-w7 wd50 tab-cell ga-analytic" href={domainName+"/muser5/UserActivityAMP/getResponseAmpPage?listingId="+courseId+"&actionType=shortlist&fromwhere=coursepage&pos=sticky"} data-vars-event-name="SHORTLIST">Shortlist</a>
                    </section>              
                    <section class="wd50 i-block m-lt" amp-access="subscriber AND NOT shortlisted" amp-access-hide="1" tabindex="0">
                      <a class="btn btn-secondary color-w color-b f14 font-w7 wd50 tab-cell ga-analytic" href={domainName+"/muser5/UserActivityAMP/getResponseAmpPage?listingId="+courseId+"&actionType=shortlist&fromwhere=coursepage&pos=sticky"} data-vars-event-name="SHORTLIST">
                          Shortlist
                      </a>
                    </section>

                    <section class="wd50 i-block" amp-access="bMailed" amp-access-hide="1" tabIndex="0">
                      <a class="btn btn-primary color-o color-f f14 font-w7 wd50 tab-cell btn-mob-dis">
                          Brochure Mailed
                      </a>
                    </section>

                    <section class="wd50 i-block" amp-access="NOT validuser AND NOT bMailed" amp-access-hide="1">
                          <a class="btn btn-primary color-o color-f f14 font-w7 wd50 tab-cell ga-analytic"  href={domainName+"/muser5/UserActivityAMP/getResponseAmpPage?listingId="+courseId+"&actionType=brochure&fromwhere=coursepage"} data-vars-event-name="BROCHURE">Apply Now</a>
                    </section>
                    <section class="wd50 i-block" amp-access="validuser AND NOT bMailed" amp-access-hide="1" tabIndex="0">
                      <a class="btn btn-primary color-o color-f f14 font-w7 wd50 tab-cell ga-analytic" href={domainName+courseUrl+"?actionType=brochure&course="+courseId} data-vars-event-name="BROCHURE">
                          Apply Now
                      </a>
                    </section>
                 <p class="clr"></p>
             </div>
    </div>
    )
  }
}

export default CourseStickyWidgetAMP;
