import React from 'react';
import {dfpConfig} from '../../common/config/dfpBannerConfig';
import { connect } from 'react-redux';
import {getObjectSize} from './../../../utils/commonHelper';
import DFPSlotsProvider from './DfpSlotsProvider';
import AdSlot from './DfpAdslot';
import DFPManager from './DFPManager';

class DFPBanner extends React.Component{
	getDfpConfig()
	{
		const {bannerPlace,dfpParams} = this.props;
		if(typeof dfpParams == "undefined" || typeof dfpParams.parentPage == 'undefined' || (typeof dfpParams == 'object' && getObjectSize(dfpParams) == 0))
			return;

		var currentConfig;
		if(bannerPlace == 'footer' && (typeof dfpConfig[dfpParams.parentPage] == 'undefined' || (typeof dfpConfig[dfpParams.parentPage] != 'undefined' && typeof dfpConfig[dfpParams.parentPage][bannerPlace] == 'undefined')))
		{
			currentConfig = dfpConfig['shiksha'][bannerPlace];
		}
		else if(bannerPlace) {
			currentConfig = typeof dfpConfig[dfpParams.parentPage] != 'undefined' ? dfpConfig[dfpParams.parentPage][bannerPlace] : null;
		    if(typeof currentConfig == 'undefined' || !currentConfig)
		    {
		        currentConfig = dfpConfig['DFP_Others'][bannerPlace];
		    }
		}
		DFPManager.load();
	    DFPManager.refresh();
	    return currentConfig;
	}
	render()
	{
		var currentConfig = this.getDfpConfig();
		if(typeof currentConfig == 'undefined' || !currentConfig || (typeof currentConfig != 'undefined' && getObjectSize(currentConfig) == 0))
			return null;
		return (
				<React.Fragment>
					<DFPSlotsProvider dfpNetworkId="21720783678" targettingParams={this.props.dfpParams} collapseEmptyDivs={true}>
				        <div className="dfp-wraper">
				              <div className="dfp-add">  
					                <AdSlot sizes={[[currentConfig['width'],currentConfig['height']]]} adUnit={currentConfig['slotId']} slotId={currentConfig['elementId']}/>
				                </div>
				        </div>
	      			</DFPSlotsProvider>
      		</React.Fragment>		
      		);
	}
}
function mapStateToProps(state)
{
  return {
      dfpParams : state.dfpParams
  }
}

export default connect(mapStateToProps)(DFPBanner);

