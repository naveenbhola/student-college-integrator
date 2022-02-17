import React from 'react';

function rankingLoader(props) {
	let tuple = [];
	for (var i = 0; i < 7; i++) {
		tuple.push(
			<section key={'lt_'+i} className="rank_tuplev1 bg-white clear_float ranking-flex desktop_loader">
				 <div className="clear_float desk-col">
					 <div className="flt_left rank_section">
								<div className="rank-hold">
										<div className="loader-line shimmer wdt85"></div>
										<div className="loader-line shimmer wdt36"></div>
								</div>
								<div className="rank-hold">
										<div className="loader-line shimmer wdt85"></div>
										<div className="loader-line shimmer wdt36 mrgn-bwLoadr"></div>
								 </div>
					 </div>
					 <div className="flt_right rank_dtl">
					 <div className="tuple-inst-img">
						 <div className="loader-line shimmer"></div>
					 </div>
					 <div className="tuple-inst-info">
							 <div className="loader-line shimmer"></div>
							 <div>
									<div className="loader-line shimmer wdt60"></div>
							 </div>
								<div className="loader-line shimmer wdt36 only-largescreen"></div>
						</div>
						 <div className="clear_float tuple_btns ctp-SrpBtnDivloader-ContBox loader-hmBx loader-tbBx">
									 <div className="loader-line shimmer wdt50"></div>
									 <div className="loader-line shimmer rank_clg"></div>
							 </div>
						 </div>
				 </div>
				 <div className="clear_float tuple_btns ctp-SrpBtnDiv">
					 <div className="loader-line shimmer hgt36"></div> <div className="loader-line shimmer hgt36"></div>
				 </div>
			</section>
		);
	}
	if(typeof props.isMobile == 'undefined' || props.isMobile == true){
		return (
			<React.Fragment>
				<div className="pwa_container">
					<section className="_subcontainer bg-white cntnt-laderbrder">
					<div className="loader-line shimmer wdt36"></div>
					<div className="loader-line shimmer"></div>
					<div className="loader-line shimmer"></div>
					</section>
					{tuple}
				</div>
			</React.Fragment>
		);
	}else{
		return <React.Fragment>{tuple}</React.Fragment>;
	}
}

export default rankingLoader;
