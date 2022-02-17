import React from 'react';
import ImportantDatesAmp from './ImportantDatesAmp';
import {cutStringWithShowMore,htmlEntities,nl2br} from '../../../../../application/utils/stringUtility';
import { makeURLAsHyperlink } from '../../../../../application/utils/urlUtility';


class AdmissionProcessAmp extends React.Component{
  constructor(props){
    super(props);
    this.state={
      admissionCount:0
    }
  }

  formatAdmissionData(viewType='page',admissionProcessCount){
    const {admissionProcess} = this.props;
    let admissionData = ((this.props.admissionProcess != 'undefined' && this.props.admissionProcess != null)?this.props.admissionProcess:{});
    var admissionDataCount = 0;
		for(var i in admissionData)
		{
			admissionDataCount++;
		}
    var html = [];
		var html1 = [];
    if(viewType == 'page' && admissionProcessCount > 3){
			var countCheck = 2;
		}else{
			var countCheck = admissionDataCount - 1;
		}
    var indexInc = 0;
    for(var index in admissionData)
		{
				html1.push(
	        		<li key={indexInc}>
	                    {admissionDataCount>1 && <strong className="f13 color-3 font-w6 m-3btm">{admissionData[index]['admissionName']}</strong>}
	                    <input type="checkbox" className="read-more-state hide" id={"admission_data_"+indexInc}/>

	                   	{(viewType == 'page')?<p className='read-more-wrap word-wrap f13 color-3 l-16' dangerouslySetInnerHTML={{ __html : cutStringWithShowMore(admissionData[index]['description'],160,'admission_data_'+indexInc,'more',true,true,false,true)}}></p>:<p className='f13 color-3 l-16 word-wrap' dangerouslySetInnerHTML={{ __html : nl2br(makeURLAsHyperlink(htmlEntities(admissionData[index]['description'])))}} ></p>}

	                </li>
	            );

            if(indexInc==countCheck)
            		html.push(<ul key={'ul_'+indexInc} className="ad-ul">{html1}</ul>);

			if(viewType == 'page' && indexInc == 2)
				break;
			indexInc++;
		}
    return html;
  }

  render(){
    const {admissionProcess,importantDatesAmp} = this.props;
		var admissionProcessCount = 0;
		if(typeof admissionProcess != 'undefined' && admissionProcess != null)
		{
			for(var i in admissionProcess)
			{
				admissionProcessCount++;
			}
		}
		let admissionHtml = this.formatAdmissionData('page',admissionProcessCount);
		let layerHtml = this.formatAdmissionData('layer',admissionProcessCount);
    if(this.props.admissionProcess != null && this.props.importantDates != null && this.props.importantDates.importantDates != null){
          var mainHeading = 'Admissions';
        }
        else if(this.props.admissionProcess != null){
          var mainHeading = 'Admission Process';
        }
        else if(this.props.importantDates != null && this.props.importantDates.importantDates != null){
          var mainHeading = 'Important Dates';
        }
    return(
        <div className="data-card m-5btm">
          <h2 className="color-3 f16 heading-gap font-w6">{mainHeading} </h2>
           <div className="card-cmn color-w">
             {/*<h3 className="f14 color-3 font-w6 m-btm">{mainHeading}</h3>*/}
             {this.props.importantDates != null && this.props.importantDates.importantDates != null && this.props.admissionProcess != null && <h3 className='f14 color-3 font-w6 m-btm'>Admission Process</h3>}
             {admissionHtml}
             {admissionProcessCount > 3 && <a on="tap:admission-process" className="block m-top color-b t-cntr f14 font-w6 v-arr ga-analytic" role="button" tabIndex="0" id="admissionProcess" data-vars-event-name="Admissions_VIEW_COMPLETE_PROCESS">View complete process</a>}
             {this.props.importantDates != null && this.props.importantDates.importantDates != null && <ImportantDatesAmp importantDates={this.props.importantDates} admissionCount={admissionProcessCount} courseId={this.props.courseId} importantDatesAmp={importantDatesAmp}/>}
           </div>
           <amp-lightbox id="admission-process"   layout="nodisplay" scrollable>
                <div className="lightbox" on="tap:admission-process.close" role="button" tabIndex="0">
                    <a className="cls-lightbox color-f font-w6 t-cntr" >&times;</a>
                    <div className="color-w pad10 m-layer">
                      <p className="m-btm f14 color-3 font-w6">Admission Process</p>

                       <div className="m-btm padb">
                        {layerHtml}
                       </div>

                    </div>
                </div>
            </amp-lightbox>
        </div>
    )
  }
}

export default AdmissionProcessAmp;
