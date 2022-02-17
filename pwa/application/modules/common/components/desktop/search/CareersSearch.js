import React from 'react';

const CareersSearch = (props) => {
  return (
    <form formname="careers" className="shadow-box" onSubmit={props.onFormSubmit}>
    	<div className="customsearch-fields">
    		<div className="search-refine-colleges">
    			<div style={{position: 'relative'}}>
    				<input type="text" placeholder="Enter a career that you are interested in... (E.g. Engineer, Doctor)" autoComplete="off" name="search" id="searchby-career"/>
    				<div id="search-career-layer" className="search-college-layer" style={{display: 'none'}}></div>
    			</div>
    		</div>
    		<div className="custom-searchbn" onClick={props.submitCareersSearch}>
          Search
    			<input type="button" className="orange f18" value="" id="submit_careers"/>
    		</div>
    	</div>
    </form>
  );
}

export default CareersSearch;
