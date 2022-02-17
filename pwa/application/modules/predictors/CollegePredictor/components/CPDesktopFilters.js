import React from 'react';
import DesktopFilters from "../../../search/components/DesktopFilters";
import {removeParamFromUrl} from "../../../../utils/commonHelper";
const CPDesktopFilters = (props) => {
	let filtersWithSearch = ['location', 'specialization', 'institute'];
	let newPageUrl = removeParamFromUrl('pageNo', props.pageUrl);
	return (<div className='fltlft filter-area'>
		<div className='cp-filters desk-filter'>
			<DesktopFilters
				gaTrackingCategory={props.gaTrackingCategory}
				shortName={props.aliasMapping}
				filterOrder={props.filterOrder}
				filters={props.filters}
				displayName={props.nameMapping}
				pageUrl={newPageUrl}
				urlHasParams={true}
				filtersWithSearch={filtersWithSearch}
			/>
		</div>
	</div>);
};
CPDesktopFilters.defaultProps = {
	gaTrackingCategory : 'AllCollegePredictorResultDesktop'
};
export default CPDesktopFilters;