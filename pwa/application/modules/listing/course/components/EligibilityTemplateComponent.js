import PropTypes from 'prop-types'
import React from 'react';
import './../assets/eligibility.css';
import './../assets/courseCommon.css';
import PopupLayer from './../../../common/components/popupLayer';
import DropDown from './../../../common/components/desktop/DropDown';
import ExamTuple from "./../../../search/components/Exam/ExamTuple.js";
import AmpLightBox from './../../../common/components/AmpLightbox';
import {cutStringWithShowMore} from './../../../../utils/stringUtility';
import {divideStringFormat} from './../utils/listingCommonUtil';
import CourseEntryChances from './CourseEntryChancesWidget';
import Analytics from './../../../reusable/utils/AnalyticsTracking';

class EligibilityTemplate extends React.Component{
	constructor(props)
	{
		super(props);
		this.examRoundsLayer = {};
		this.state = {
			openCategoryDropDown:false,
			roundLayerData : {},
			categoryName : 'general',
			displayCatName : 'General',
			layerHeading : ''
		}
		this.additionalInfoData = [];
		this.isEligibilityTableExist = false;
	}



	componentDidMount(){
		let self = this;
		if(this.props.deviceType=='desktop'){
			document.addEventListener("click", function(e){
				if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('cat-slection') < 0)){
					self.closeCategoryDropDown();
				}
			});
		}
	}

	closeCategoryDropDown(){
		this.setState({openCategoryDropDown : false});
	}



	renderNonTableHtml(categoryWiseData){
		const {isAmp} = this.props;
		var isEligibilityAgeExpExist  = false;
		var isEligibilityWorkExpExist = false;
		var isInternationalStudentDataExist = false;
		if(typeof categoryWiseData['minAge'] !='undefined' || typeof categoryWiseData['maxAge'] !='undefined'){
			isEligibilityAgeExpExist = true;
		}
		if(typeof categoryWiseData['minWorkEx'] != 'undefined' || typeof categoryWiseData['maxWorkEx'] != 'undefined'){
			isEligibilityWorkExpExist = true;
		}
		if(typeof categoryWiseData['internationalDescription'] != 'undefined' || typeof categoryWiseData['description'] != 'undefined'){
			isInternationalStudentDataExist = true;
		}
		var finalHtml = [];
		var html = [];
		if(isEligibilityAgeExpExist)
		{
			var agestr = '';
			if(typeof categoryWiseData['minAge'] != 'undefined')
			{
				agestr = "Minimum: "+ categoryWiseData['minAge'] +" year";
				agestr += (categoryWiseData['minAge'] > 1)? 's':'';
			}
			if(typeof categoryWiseData['minAge'] != 'undefined' && typeof(categoryWiseData['maxAge']) != 'undefined'){
				agestr += ' | ';
			}
			if(typeof categoryWiseData['maxAge'] != 'undefined')
			{
				agestr += "Maximum: "+ categoryWiseData.maxAge +" year";
				agestr += (categoryWiseData['maxAge'] > 1)? 's':'';
			}
			if(isAmp){
				finalHtml.push(<section key="finalHtml" className='cut-off-widget'><label className="block f14 color-6 font-w6">Age</label><p className="f14 color-3 font-w4">{agestr}</p></section>);
			}
			else{
				finalHtml.push(<li key="finalHtmlli" ><strong className='blk-text' key="age-key-str">Age</strong><span className='blk-text'>{agestr}</span></li>);
			}
		}
		if(isEligibilityWorkExpExist)
		{
			var workstr = '';
			if(typeof categoryWiseData['minWorkEx'] != 'undefined')
			{
				workstr = "Minimum: "+ categoryWiseData['minWorkEx'] +" Month";
				workstr +=  (categoryWiseData['minWorkEx'] > 1)? 's':'';
			}
			if(typeof categoryWiseData['minWorkEx'] != 'undefined' && typeof(categoryWiseData['maxWorkEx']) != 'undefined'){
				workstr += ' | ';
			}
			if(typeof categoryWiseData['maxWorkEx'] != 'undefined')
			{
				workstr += "Maximum: "+ categoryWiseData['maxWorkEx'] +" Month";
				workstr += (categoryWiseData['maxWorkEx'] > 1)? 's':'';
			}

			if(isAmp){
				finalHtml.push(<section key="catwiseData" className='cut-off-widget'><label className="block f14 color-6 font-w6">Work experience</label><p className="f14 color-3 font-w4">{workstr}</p></section>);

			}
			else{
				finalHtml.push(<li key="catwiseDatali" ><strong className='blk-text' key="work-key-str">Work Experience</strong><span className='blk-text'>{workstr}</span></li>);
			}
		}
		var interDescFalg = false;
		if(typeof categoryWiseData['internationalDescription'] != 'undefined')
		{
			let internationalDescription = '';
			if(this.props.isPdfGenerator != null && this.props.isPdfGenerator) {
				internationalDescription = categoryWiseData['internationalDescription'];
			}
			else {
				internationalDescription = cutStringWithShowMore(categoryWiseData['internationalDescription'],130,'inter_stud_desc','more',true,true,false,true)
			}
			interDescFalg = true;
			if(isAmp){
				finalHtml.push(
					<section key="internationalDescription" className='cut-off-widget'>
						<label className="block f14 color-6 font-w6">International students eligibility</label>
						<input type="checkbox" className='read-more-state hide' id="inter_stud_desc" key="inter_stud_desc"/>
						<p className='read-more-wrap word-break' key="int-desc-p" dangerouslySetInnerHTML={{ __html : internationalDescription}}></p>
					</section>
				);

			}
			else{
				finalHtml.push(<li key="desc-key">
						<strong className='blk-text' key="int-he">International students eligibility</strong>
						<input type="checkbox" className='read-more-state hide' id="inter_stud_desc" key="inter_stud_desc"/>
						<p className='read-more-wrap word-break' key="int-desc-p" dangerouslySetInnerHTML={{ __html : internationalDescription}}></p>
					</li>
				);
			}
		}
		if(typeof categoryWiseData['description'] != 'undefined')
		{
			let otherDescription = '';
			if(this.props.isPdfGenerator != null && this.props.isPdfGenerator) {
				otherDescription = categoryWiseData['description'];
			}
			else {
				otherDescription = cutStringWithShowMore(categoryWiseData['description'],130,'other_desc','more',true,true,false,true);
			}
			var otherhtml = [];
			if(interDescFalg || isEligibilityAgeExpExist || isEligibilityWorkExpExist || this.isEligibilityTableExist)
			{
				if(isAmp){
					otherhtml.push(<label key="other_interDesclabel" className="block f14 color-6 font-w6">Other eligibility criteria</label>);
				}
				else{
					otherhtml.push(<strong key="other_interDesc" className='blk-text' >Other eligibility criteria</strong>);
				}
			}
			if(isAmp){
				if(categoryWiseData['description'].length > 127){
					otherhtml.push(<input type="checkbox" className="read-more-state hide" id="other_desc"/>);
					otherhtml.push(<React.Fragment><p key="other_desc" className="read-more-wrap word-break"  dangerouslySetInnerHTML={{ __html: otherDescription}}></p>

						</React.Fragment>
					);
				}
				else{
					otherhtml.push(<p key="others-key" className="read-more-wrap word-break">
                         <span className="f13 color-3 l-16 lt">
                           {categoryWiseData['description']}
                         </span>
					</p>);
				}

				finalHtml.push(<section key="other_final" className="cut-off-widget">{otherhtml}</section>);
			}
			else{
				otherhtml.push(<input type="checkbox" className='read-more-state hide' id="other_desc" key="other_desc"/>);
				otherhtml.push(<p key="other-desc-key" className='read-more-wrap word-break' dangerouslySetInnerHTML={{ __html : otherDescription}}></p>);
				finalHtml.push(<li key="other-desc">{otherhtml}</li>);
			}
		}

		if(isEligibilityAgeExpExist || isEligibilityWorkExpExist || isInternationalStudentDataExist)
		{
			if(isAmp){
				html.push(<div key="elig_exist" className="age-exp-col padb0 border-top ">{finalHtml}
				</div>);
			}
			else{
				html.push(<ul key="elig_exist"  className='flt-list exp-guid' >{finalHtml}
				</ul>);
			}
		}

		return html;
	}
	renderTableHtml(categoryWiseData){
		let html = [];
		const {selectedCategoryEligi,isAmp,categoryNameMapping} = this.props;
		this.additionalInfoData = this.props.additionalInfoData;
		this.additionalInfoDataLayer = [];
		for(var qualification in this.additionalInfoData)
		{
			if(isAmp){
				this.additionalInfoDataLayer.push(<li key="addInfo_key" className="f12 color-6 m-5btm"><div className="m-5btm"><strong className="block  color-3 f14 font-w6">{qualification}</strong><p className="color-3 l-18 f12 word-break">{this.additionalInfoData[qualification]}</p></div></li>);
			}
			else{
				this.additionalInfoDataLayer.push(<li key={qualification+'-addinfo'}><p key={qualification+"-info-h"}>{qualification}</p><p key={qualification+"-info-d"}>{this.additionalInfoData[qualification]}</p></li>);
			}
		}
		if(this.additionalInfoDataLayer.length > 0)
		{
			this.additionalInfoData = [];

			this.additionalInfoData.push(
				<div>
					<div>
						<div>
							<ul key="add_div_key" className="addtnl-div">
								{this.additionalInfoDataLayer}
							</ul>
						</div>
					</div>
				</div>
			);
		}
		for(var i in categoryWiseData['tableData'])
		{
			if(Object.keys(categoryWiseData['tableData'][i]).length == 0)
				continue;

			var headingsArray = [];
			var checked =false;
			if(i == selectedCategoryEligi || i == "noneAvailable")
			{
				checked = true;
			}
			if(isAmp){
				headingsArray.push(  <th key="qualify"><h3 className="f14 color-6 font-w6">Qualification</h3></th>);
			}
			else{
				headingsArray.push(<th key="qualify">Qualification</th>);
			}
			if(typeof(categoryWiseData['tableData'][i]['showEligibilityVal']) != 'undefined' && categoryWiseData['tableData'][i]['showEligibilityVal'] == true)
			{
				if(isAmp){
					headingsArray.push( <th key="elig"><h3 className="f14 color-6 font-w6">Minimum Eligibility</h3></th>);
				}
				else{
					headingsArray.push(<th key="elig">Minimum Eligibility</th>);
				}
			}
			if(typeof(categoryWiseData['tableData'][i]['showCutOff']) != 'undefined' && categoryWiseData['tableData'][i]['showCutOff'] == true)
			{
				if(isAmp){
					headingsArray.push(<th key="cutoff"><h3 className="f14 color-6 font-w6">Cut-Offs {typeof(categoryWiseData['tableData'][i]['cutoff_year'] != 'undefined') && <span className="block f12 color-6 font-w4">({categoryWiseData['tableData'][i]['cutoff_year']})</span>}</h3></th>);
				}
				else{
					headingsArray.push(<th key="cutoff">Cut-offs {typeof(categoryWiseData['tableData'][i]['cutoff_year'] != 'undefined') && <span className='f12'>({categoryWiseData['tableData'][i]['cutoff_year']})</span>}</th>);
				}
			}
			var showingHeading = [];
			var rows = [];
			var layerRoundsStructure = [];
			var count = 0;
			for(var j in categoryWiseData['tableData'][i]['table'])
			{
				var contentArray = [];
				var values = categoryWiseData['tableData'][i]['table'][j];
				var firstColumn = [];
				if(values.hasOwnProperty('url') && values['url'] != '' && values['url'])
				{
					if(isAmp){
						firstColumn.push(<a key="url" className='block f14 color-b font-w6' href={values['url']}><strong>{j}</strong></a>);
					}
					else{
						firstColumn.push(<a key="url" className='nrml-link' href={values['url']}><strong>{j}</strong></a>);
					}
				}
				else
				{
					if(isAmp){
						firstColumn.push(<strong key="blk-text" className="block color-3 f14 font-w6">{j}</strong>);
					}
					else{
						firstColumn.push(<span key="blk-text" className='blk-text'>{j}</span>);
					}
				}
				if(typeof(this.props.additionalInfoData[j])!= 'undefined' && this.props.additionalInfoData[j].length > 0 )
				{
					if(isAmp){
						firstColumn.push(<a key="link" className="block f12 color-b ga-analytic"  data-vars-event-name="ELIGIBILITY_ADDITIONAL_DETAILS" on={"tap:additional-dtls_"+i+j} role="button" tabIndex="0">Additional details</a>);
						firstColumn.push(<AmpLightBox key="AmpLightbox_key" onRef={ref => (this.roundLayer = ref)} data={this.additionalInfoData} heading="Additional Details" id={"additional-dtls_"+i+j}/>);
					}
					else{
						firstColumn.push(<a key="link" className='nrml-link' onClick={this.openAdditionalInfoLayer.bind(this)}>Additional Details</a>);
					}
				}
				contentArray.push(<td key="fcol">{firstColumn}</td>);
				if(typeof(categoryWiseData['tableData'][i]['showEligibilityVal']) != 'undefined' && categoryWiseData['tableData'][i]['showEligibilityVal'] == true)
				{
					if(isAmp){
						contentArray.push(<td key="scol" className="color-3 f14 font-w4">{values.eligibility}</td>);
					}
					else{
						contentArray.push(<td key="scol">{values.eligibility}</td>);
					}
				}
				if(typeof(categoryWiseData['tableData'][i]['showCutOff']) != 'undefined' && categoryWiseData['tableData'][i]['showCutOff'] == true)
				{

					if(typeof(values.cutoff) != 'undefined' && (values.cutoff instanceof Object && values.cutoff instanceof Array))
					{
						if(isAmp){
							contentArray.push(<td key="tcol" className="color-3 f13 font-w4">{values.cutoff}</td>);
						}
						else{
							contentArray.push(<td key="tcol">{values.cutoff}</td>);
						}
					}
					else if(typeof values.cutoff != 'undefined' && typeof values.cutoff == 'string')
					{
						if(isAmp){
							contentArray.push(<td key="tcol" className="color-3 f13 font-w4">{values.cutoff}</td>);
						}
						else{
							contentArray.push(<td key="tcol">{values.cutoff}</td>);
						}
					}
					else
					{
						var toolTip = [];
						var cutoffstr = [];
						for(var cut in values.cutoff)
						{
							var temp = [];
							if(categoryWiseData['tableData'][i]['table'][j]['quotaCount'][values.cutoff[cut]['quota']] > 1)
							{
								var quotaName = divideStringFormat(values.cutoff[cut]['quota'],"_");
								for(var roundData in categoryWiseData['tableData'][i]['examCutoffData'][j])
								{
									var toolTipHtml = [];
									for(var quota in categoryWiseData['tableData'][i]['examCutoffData'][j][roundData])
									{
										toolTipHtml.push(<span key={quota}>{quotaName} (Round {parseInt(roundData)}) :{categoryWiseData['tableData'][i]['examCutoffData'][j][roundData][quota]}<br/></span>)
									}
									if(toolTipHtml.length > 0)
										toolTip.push(toolTipHtml);
								}
								if(isAmp){
									temp.push(<a key={i+'icon'} className="pos-rl" on={"tap:eligible-rounds-dtls"+i} role="button" tabIndex="0"><i className="cmn-sprite clg-info i-block v-mdl"></i></a>);
								}
								else{
									temp.push(<i key={i+'icon'} className='info-icon' onClick={this.openRoundSelection.bind(this,i,j)}></i>);
								}
							}else{
								temp.push("");
							}
							cutoffstr.push(<div className='cutoff-col-div' key={cut}>{divideStringFormat(values.cutoff[cut]['quota'],"_")+ ": " }{values.cutoff[cut]['cutoffstr']}{temp}</div>);
						}
						if(toolTip.length > 0){
							if(isAmp){
								layerRoundsStructure.push(<div  key={j+"tool1"} className="m-5btm pad10"><strong className="block m-3btm color-3 f14 font-w6">{j}</strong><p className="color-3 l-18 f12 word-break">{toolTip}</p></div>);
							}
							else{
								layerRoundsStructure.push(<div key={j+"tool1"} className='cutoff-div'><strong className='font-w6'>{j}</strong><p className='f12 word-break'>{toolTip}</p></div>);
							}
						}
						if(isAmp){
							contentArray.push(<td key="tcol" className="color-3 f13 font-w4">{cutoffstr}</td>);
						}
						else{
							contentArray.push(<td key="tcol">{cutoffstr}</td>);
						}
						if(typeof this.examRoundsLayer[i] == 'undefined')
						{
							this.examRoundsLayer[i] = {};
						}
						this.examRoundsLayer[i] = layerRoundsStructure;
					}
				}
				rows.push(<tr key={count++}>{contentArray}</tr>);
			}
			if(isAmp){
				var AmpLightBoxHtml = [];
				AmpLightBoxHtml.push(<AmpLightBox key="AmpLightboxHtml" data={layerRoundsStructure} heading="Cut-Off" id={"eligible-rounds-dtls"+i}/>);
				html.push(<input type="radio" name="eligible" value={"eligi_"+i} id={"eligi_"+i} className="hide st" checked={checked} />);
				var ShowingCategoryHtml = [];
				if(categoryNameMapping[i] != null){
					ShowingCategoryHtml.push(<p key="catHtml-key" className="color-3f14 f12 font-w6 n-border-color"><span className="i-block 	color-6 font-w4">Showing info for</span> {categoryNameMapping[i]} Category</p>);
				}
				html.push(<div key="catHtml_key" className="table tob1">{ShowingCategoryHtml}<table className="table tob1" id="#tops"><tbody className="default-body"><tr>{headingsArray}</tr>{rows}{AmpLightBoxHtml}</tbody></table></div>);
			}
			else{
				html.push(<div className={(this.isActive(i) || i == 'noneAvailable') ? 'table-active' : 'table-dis table-el clp'} key={i+"-data"}>{showingHeading}<table className='tbl-exp-guid'><tbody><tr>{headingsArray}</tr>{rows}</tbody></table></div>);
			}
			this.isEligibilityTableExist = true;
		}

		return html;
	}
	openRoundSelection(i) {
		this.setState({roundLayerData : this.examRoundsLayer[i],layerHeading : 'Cut-Off'});
		this.roundLayer.open();
	}
	openAdditionalInfoLayer(){
		this.setState({roundLayerData : this.additionalInfoData,'layerHeading' : 'Additional Details'});
		this.roundLayer.open();
	}
	openCategorySelection() {
		if(this.props.deviceType=='desktop'){
			this.setState({openCategoryDropDown : !this.state.openCategoryDropDown});
		}else{
			this.categoryLayer.open();
		}
	}
	getRoundsLayerData(i,j)
	{
		return this.examRoundsLayer[i][j];
	}
	activeCategoryEligibile(categoryName,displayCatName)
	{
		this.setState({ categoryName :  categoryName,displayCatName : displayCatName,openCategoryDropDown:false});
	}
	trackEvent()
	{
		Analytics.event({category : 'CLP', action : 'EligibilityType', label : 'Subwidget'});
	}
	handleOptionClick(index)
	{
		const {categoryNameMapping} = this.props;
		this.activeCategoryEligibile(index,categoryNameMapping[index]);
		this.trackEvent();
	}
	generateCategorySelectionLayer()
	{
		const {categoryNameMapping,isAmp} = this.props;
		var html = [];
		var self = this;
		if(Object.keys(categoryNameMapping).length > 0) {
			var categories = Object.keys(categoryNameMapping).sort();
			if(isAmp){
				let layerHtml = categories.map(function(index){
					return <li key={index}><label htmlFor={"eligi_"+index}  className="block">{categoryNameMapping[index]} Category</label></li>
				});
				html.push( <ul key="ullist" className="color-6">{layerHtml}</ul>);
			}
			else{
				let layerHtml = categories.map(function(index){
					return <li key={index} className='cat-slection' onClick={self.handleOptionClick.bind(self,index)}>{categoryNameMapping[index]}</li>
				});
				html.push(<ul key="ullist" className='cat-slection ul-list'>{layerHtml}</ul>);
			}
		}
		return html;
	}
	isActive(catName)
	{
		return this.state.categoryName == catName;
	}
	render()
	{

		const {categoryWiseData,isAmp,examTuples,acceptingExamMapping} = this.props;
		let examList = null;
		if(examTuples){
			examList = examTuples.map((tuple, i) =>
				<ExamTuple key={"examTuple" + i} acceptingExam={acceptingExamMapping && acceptingExamMapping.hasOwnProperty(tuple.examId) ?
					acceptingExamMapping[tuple.examId] : null} tupleData = {tuple} isMobile={this.props.deviceType == "mobile"?true:false}  gaCategory ='CLP' trackingKeyId={3173}/>
			);
		}
		this.isEligibilityTableExist = false;
		if(this.props.isPdfGenerator) {
			categoryWiseData.showCategoryDropDown = false;	
		}
		return(
			<React.Fragment>
				{isAmp ?
					(<React.Fragment>
						<section className='eligibilityBnr listingTuple' id="eligibility">
							<div className="data-card m-5btm pos-rl" id="eligibility">
								<h2 className="color-3 f16 heading-gap font-w6 pos-rl">Eligibility</h2>
								{(categoryWiseData.showCategoryDropDown) && <div className="dropdown-primary" on="tap:cat-list" role="button" tabIndex="0">
									<span className="option-slctd block color-6 f12 font-w6 ga-analytic" data-vars-event-name="Eligibility" id="optnSlctd">Choose Category</span>
									<AmpLightBox onRef={ref => (this.categoryLayer = ref)} data={this.generateCategorySelectionLayer()} heading={"Category"} id="cat-list" taponPage={true}/>
								</div>
								}
								<div className="card-cmn color-w">
									{this.renderTableHtml(categoryWiseData)}
									{this.renderNonTableHtml(categoryWiseData)}
								</div>
							</div>
						</section>
						<CourseEntryChances predictorInfo={this.props.predictorInfo} isAmp={true}/>
					</React.Fragment>)
					:
					(<section className='eligibilityBnr listingTuple' id="eligibility">
						<div className='_container'>
							<h2 className='tbSec2'>Eligibility</h2>
							{ categoryWiseData.showCategoryDropDown && <div className='dropdown-primary'>
								<label className='cat-slection option-slctd' onClick={this.openCategorySelection.bind(this)}>{this.state.displayCatName + " Category"}</label>
								{this.props.deviceType == 'desktop' ?

									(<DropDown data={this.generateCategorySelectionLayer()} isOpen={this.state.openCategoryDropDown} /> )

									:<PopupLayer onRef={ref => (this.categoryLayer = ref)} data={this.generateCategorySelectionLayer()} heading={"Category"}/>
								}

							</div> }
							<div className='_subcontainer'>
								<div>
									{this.renderTableHtml(categoryWiseData)}
									{this.renderNonTableHtml(categoryWiseData)}
									<PopupLayer onRef={ref => (this.roundLayer = ref)} data={this.state.roundLayerData} heading={this.state.layerHeading}/>
								</div>
							</div>
							{examList &&  <div className='pwaexams_container'>
								<div className='listof_exams auto_clear'>{examList}</div>
							</div>}
							<CourseEntryChances predictorInfo={this.props.predictorInfo}/>
						</div>
					</section>)
				}
			</React.Fragment>
		);
	}

}

EligibilityTemplate.defaultProps = {
	selectedCategoryEligi : 'general',
	isAmp: false
}

export default EligibilityTemplate;

EligibilityTemplate.propTypes = {
	acceptingExamMapping: PropTypes.any,
	additionalInfoData: PropTypes.any,
	categoryNameMapping: PropTypes.any,
	categoryWiseData: PropTypes.any,
	deviceType: PropTypes.any,
	examTuples: PropTypes.any,
	isAmp: PropTypes.bool,
	predictorInfo: PropTypes.any,
	selectedCategoryEligi: PropTypes.string
}