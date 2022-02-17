import React from 'react';

const QuestionsSearch = (props) => {
  return (
    <form formname="questions" className="shadow-box" onSubmit={props.onFormSubmit}>
      <div className="customsearch-fields">
        <div className="search-refine-colleges">
          <div style={{position: 'relative'}}>
            <input type="text" placeholder="Search from 5 lakh+ answered questions" autoComplete="off" name="search" id="searchby-question"/>
            <div id="search-question-layer" className="search-college-layer" style={{display: 'none'}}></div>
          </div>
        </div>
        <div className="custom-searchbn" onClick={props.submitQuestionSearch}>
          Search
          <input type="button" className="orange f18" value="" id="submit_question"/>
        </div>
      </div>
    </form>
  );
}

export default QuestionsSearch;
