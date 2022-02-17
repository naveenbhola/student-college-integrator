import React from 'react';
import SelectedFilters from "../../../search/components/SelectedFilter";
const CPSelectedFilters = (props) => {
	return (
		<div className='cp-sltd-fltrs'>
			<SelectedFilters
				filtersData={props.filters}
				shortName={props.aliasMapping}
				pageUrl={props.pageUrl}
				urlHasParams={true}
			/>
		</div>
	);
};

export default CPSelectedFilters;