import React from 'react';
import AmpLightBox  from '../../../../../application/modules/common/components/AmpLightbox';

class SeatsBreakupAmp extends React.Component{
  constructor(props){
    super(props);
    this.state ={
      seatsData : {}
    }
  }
  formatSeatsData(){
    let categoryWiseData = this.props.seatsData.categoryWiseSeats != null ? this.props.seatsData.categoryWiseSeats : [];
    let examWiseData = this.props.seatsData.examWiseSeats != null ? this.props.seatsData.examWiseSeats : [];
    let domicileWiseData = this.props.seatsData.domicileWiseSeats != null ? this.props.seatsData.domicileWiseSeats : [];
    var html = [];
    var html1 = [];
    var html2 = [];
    var html3 = [];
    var categoryMoreHtml = [];
    var examMoreHtml = [];
    var domicileMoreHtml = [];
    var categoryBreakup = 0;
    var examBreakup = 0;
    var domicileBreakup = 0;
    var maxDisplayBreakup = 5;

    for(var index in categoryWiseData)
		{
			categoryBreakup++;
			html1.push(<tr className={index >= maxDisplayBreakup ? 'read-more-target' : ''} key={"cate_"+index}><td><p className="f14 color-3 font-w4"> {categoryWiseData[index]['category']} </p></td>
      <td><p className="pad3 f14 font-w6 t-cntr">{categoryWiseData[index]['seats']}</p></td></tr>);


			if(index==(categoryWiseData.length - 1)){
				if(categoryBreakup > maxDisplayBreakup)
					categoryMoreHtml.push( <label key={"divcat_"+index} htmlFor="catBreakup-more" className="block m-top color-b t-cntr f14 font-w6 v-arr read-more-trigger ga-analytic" data-vars-event-name="SEAT_BREAKUP_VIEWALL">View All<i className="blu-arrw rotate"></i></label> );
				html.push(<section expanded={'expanded'.toString()} className="seats-drop ga-analytic" data-vars-event-name="SEAT_BREAKUP_ACCORDION" key={"tabcat_"+index}> <h4 className="color-w f14 pad8 font-w6 color-3">Category</h4><div className="pad10 res-col"><input type="checkbox" className="read-more-state hide"  id="catBreakup-more"/><table className="seats-table read-table">{html1}</table>{categoryMoreHtml}</div></section>);
			}
		}

    for(var index in examWiseData)
		{
			examBreakup++;
			html2.push(<tr className={index >= maxDisplayBreakup ? 'read-more-target' : ''} key={"exam_"+index}><td><p className="f14 color-3 font-w4">{examWiseData[index]['exam']}</p></td> <td><p className="pad3 f14 font-w6 t-cntr">{examWiseData[index]['seats']}</p></td></tr>);


			if(index==(examWiseData.length - 1)){
				if(examBreakup > maxDisplayBreakup)
					examMoreHtml.push(<label htmlFor="examBreakup-more" className="block m-top color-b t-cntr f14 font-w6 v-arr read-more-trigger ga-analytic" data-vars-event-name="SEAT_BREAKUP_VIEWALL" key={"divexm_"+index}>View All</label>);
						html.push(<section className="seats-drop ga-analytic" data-vars-event-name="SEAT_BREAKUP_ACCORDION" key={"tabexm_"+index}><h4 className="color-w f14 pad8 font-w6 color-3">Entrance Exam</h4><div className="pad10 res-col"><input type="checkbox" className="read-more-state hide"  id="examBreakup-more"/><table className="seats-table read-table">{html2}</table>{examMoreHtml}</div></section>);
			}

		}

    for(var index in domicileWiseData)
		{
			domicileBreakup++;
			html3.push(<tr className={index >= maxDisplayBreakup ? 'read-more-target' : ''} key={"dom_"+index}>
      <td><p className="f14 color-3 font-w4" >{domicileWiseData[index]['category']}  {(domicileWiseData[index]['category'] ==='Related State') ? <React.Fragment><a className='i-block color-b f12 font-w6' on="tap:domicileSeats" role="button" tabindex="0"><i className='cmn-sprite clg-info i-block v-mdl'></i></a></React.Fragment>: ''} </p></td>
      <td><p className="pad3 f14 font-w6 t-cntr">{domicileWiseData[index]['seats']}</p></td></tr>);

			if(index==domicileWiseData.length - 1){
				if(domicileBreakup > maxDisplayBreakup)
					domicileMoreHtml.push(<label htmlFor="domicileBreakup-more" key={'layerDiv_'+index} className="block m-top color-b t-cntr f14 font-w6 v-arr read-more-trigger ga-analytic" data-vars-event-name="SEAT_BREAKUP_VIEWALL">View All</label>);
				html.push(<section  className="seats-drop ga-analytic" data-vars-event-name="SEAT_BREAKUP_ACCORDION" key={"domicileDiv_"+index}><h4 className="color-w f14 pad8 font-w6 color-3">Domicile{(this.props.seatsData.relatedStates !== null)  }</h4><div className="pad10 res-col"><input type="checkbox" className="read-more-state hide" id="domicileBreakup-more"/><table className="seats-table read-table">{html3}</table>{domicileMoreHtml}</div></section>);
			}
		}
     return html;
  }

  render(){
      const {seatsData} = this.props;
      if(!((seatsData.totalSeats > 0) || (seatsData.categoryWiseSeats != null && Array.isArray(seatsData.categoryWiseSeats) && seatsData.categoryWiseSeats.length > 0) || (seatsData.examWiseSeats != null && Array.isArray(seatsData.examWiseSeats) && seatsData.examWiseSeats.length > 0) || (seatsData.domicileWiseSeats != null && Array.isArray(seatsData.domicileWiseSeats) && seatsData.domicileWiseSeats.length > 0) || (seatsData.relatedStates != null && Array.isArray(seatsData.relatedStates) && seatsData.relatedStates.length > 0)))
			return null;

      let seatsBreakupHtml = this.formatSeatsData();
  		let totalSeats = this.props.seatsData.totalSeats;
  		var domicileHelpText = (this.props.seatsData.relatedStates !== null) ? this.props.seatsData.relatedStates : '';
  		var domicileHelpTextArr = [];
      domicileHelpTextArr.push(<ul> <li className="f12 color-6 m-5btm al-ul" key="help">{domicileHelpText}</li> </ul>);

      return(
        <div className="data-card m-5btm" id="seats">
           <h2 className="color-3 f16 heading-gap font-w6">Seats Break-up</h2>
           <div className="card-cmn color-w">
              <h3 className="color-9 pos-rl f14 font-w4">Total Seats</h3>
              { totalSeats > 0 && <p className='color-3 f26 font-w6 m-3btm'><strong>{totalSeats}</strong> </p> }
                <amp-accordion>
                     {seatsBreakupHtml}
                </amp-accordion>
              {domicileHelpTextArr != '' && <AmpLightBox onRef={ref => (this.helpLayer = ref)} data={domicileHelpTextArr} heading='Domicile' id='domicileSeats'/>}
          </div>
        </div>
      )
  }
}


export default SeatsBreakupAmp;
