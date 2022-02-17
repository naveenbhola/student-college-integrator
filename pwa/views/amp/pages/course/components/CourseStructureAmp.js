import React from 'react';
import { makeURLAsHyperlink } from '../../../../../application/utils/urlUtility';
import { connect } from 'react-redux';

class CourseStructureAmp extends React.Component{
  constructor(props){
    super(props);
    this.state = {
      courseStructure: {}
    }
  }
  formatStructureData(viewType='page'){
    var structureData = this.props.courseStructure.periodWiseCourses;
    var structureGroup = this.props.courseStructure.period;
    var html = [];
    for(var index in structureData)
		{
			if(structureGroup != 'program')
			{
				html.push(<h3 key={'h_'+index} className="font-w6 m-3btm f14 color-3 txt-cptl">{structureGroup+' ' + (parseInt(index)) +' Courses'}</h3>);
			}
			var html1 = structureData[index].map(function (course, key) {
							if(structureGroup == 'program' && key > 6 && viewType == 'page')
								return;
			        		return  <li key={'course_'+key+index} dangerouslySetInnerHTML={{ __html : makeURLAsHyperlink(course)}} className="f13 color-3 font-w4"></li>
			        	})
			html.push(<div key="pad-all_key" className="pad-all"><ul key={'ul_'+index} className="cs-ul ct-l cs">{html1}</ul></div>);
			if(viewType == 'page' && index == 1)
				break;
		}
        return html;
  }


  render(){
    var structureData = this.props.courseStructure.periodWiseCourses;
    var structureHtml = this.formatStructureData('page');
    var layerHtml = this.formatStructureData('layer');
    var structureCount = Object.keys(this.props.courseStructure.periodWiseCourses).length;

  

    return(
      <div className="data-card m-5btm">
        <h2 className="color-3 f16 heading-gap font-w6">Course Structure</h2>
        <div className="card-cmn color-w">
           <div className="block m-5btm">
              <div className="pad-all">
                {structureHtml}
              </div>
            </div>
           { structureCount > 5 ?   <a className="block m-top color-b t-cntr f14 font-w6 v-arr ga-analytic" on="tap:course-struct" role="button" tabindex="0" data-vars-event-name="COURSE_STRUCTURE_VIEWCOMPLETE">View complete course structure</a> : ''  }

         </div>

      { structureCount > 5 ?   <amp-lightbox id="course-struct" className="" layout="nodisplay" scrollable>
         <div className="lightbox" >
            <a className="cls-lightbox color-f font-w6 t-cntr" on="tap:course-struct.close" role="button" tabindex="0">&times;</a>
            <div className="m-layer">
               <div className="min-div color-w course-lt">
                  <div className="block str-block">

                      {layerHtml}

                  </div>
               </div>
            </div>
         </div>
       </amp-lightbox> : ''}
      </div>
    )
  }
}


export default CourseStructureAmp;
