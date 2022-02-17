import React,{Component} from 'react';


class SimilarCoursesAmp extends React.Component{
  constructor(props){
    super(props);
  }
  generateSimilarWidgetData = ()=>{
    var similardata = this.props.similarCourses;
  }

  render(){
     return(
       <section className="data-card">
         <h2 class="color-3 f16 heading-gap font-w6">Other Similar Courses</h2>
         <div class="card-cm">
             <amp-carousel height="0" width="0" layout="responsive" type="carousel" class="s-c ga-analytic" data-vars-event-name="STUDENT_WHO_VIEWED">

             </amp-carousel>
         </div>
       </section>
     )
  }
}
<section class="data-card">
        <h2 class="color-3 f16 heading-gap font-w6">Other Similar Courses</h2>
        <div class="card-cm">
           <amp-carousel height="0" width="0" layout="responsive" type="carousel" class="s-c ga-analytic" data-vars-event-name="STUDENT_WHO_VIEWED">
            <% data.map((item) =>  { %>
              <figure class="slide-fig color-w">
                 <a>
                    <amp-img src="<%- item.imageUrl %>" width=155 height=116 layout=responsive></amp-img>
                 </a>
                 <div class="pad-5">
                    <a href="<%- item.instituteUrl %>" class="caption color-3 f14 font-w6 m-5btm clg-tl"><%- item.instituteName %></a>
                    <figcaption class="caption color-6 f12 font-w4 m-5btm clg-lc">
                       <%- item.cityName %> | Estd. <%- item.establishYear %>
                    </figcaption>
                    <a href="<%- item.courseUrl %>" class="caption color-3 f14 font-w6 clg-tl"><%- item.courseName %></a>
                    <a href="https://www.shiksha.com/muser5/UserActivityAMP/getResponseAmpPage?listingType=course&listingId=71248&actionType=brochure&fromwhere=coursepage&pos=similar" class="btn btn-primary color-o color-f f14 font-w7 m-top ga-analytic" data-vars-event-name="DBROCHURE_COURSE_SIMILAR">Request Brochure</a>
                 </div>
              </figure>
            <% }) %>
           </amp-carousel>
        </div>
     </section>
