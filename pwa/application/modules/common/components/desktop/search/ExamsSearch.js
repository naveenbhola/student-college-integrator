import React from 'react';

const ExamsSearch = (props) => {
  return (
    <form formname="exams" className="shadow-box" onSubmit={props.onFormSubmit}>
      <div className="customsearch-fields">
        <div className="search-refine-colleges">
          <div style={{position: 'relative'}}>
            <input type="text" placeholder="Find exams by name or course..." autoComplete="off" name="search" id="searchby-exam"/>
            <div id="search-exam-layer" className="search-college-layer" style={{display: 'none'}}></div>
          </div>
        </div>
        <div className="custom-searchbn" onClick={props.submitExamSearch}>
          Search
          <input type="button" className="orange f18" value="" id="submit_exams"/>
        </div>
      </div>
    </form>
  );
}

export default ExamsSearch;
