import React from 'react';

const CollegesSearch = (props) => {
  return (
    <form formname="colleges" onSubmit={props.onFormSubmit}>
      <div className="shadow-box">
        <div className="customsearch-fields">
          <div className="search-refine-colleges">
            <div style={{position: 'relative'}}>
              <input type="text" placeholder="Find colleges, universities & courses" autoComplete="off" name="search" id="searchby-college" />
              <span id="keywordCross" onClick={props.enableSearchInputBox}><i className="icons ic_close1"></i></span>
              <div id="search-college-layer" className="search-college-layer" style={{display: 'none'}}></div>
            </div>
          </div>
          <div className="search-refine-locations">
            <select className="chosen-select" multiple={true} style={{display: 'none'}}></select>
            <div id="chosenMultiSelectBox" className="chosen-container chosen-container-multi">
              <ul className="chosen-choices" style={{position: 'relative', overflow: 'hidden', margin: '0px 0px 0px 0px', backgroundColor: '#fff', cursor: 'text'}}>
                <li className="search-field">
                  <input type="text" placeholder="Enter location" className="default" autoComplete="off" style={{padding: '0px 16px', outline: 0, background: 'transparent!important', boxShadow: 'none', color: '#BFBFBF', fontSize: '14px', borderRadius: 0, border: '0px'}}/>
                </li>
              </ul>
            </div>
          </div>
          <div id="submitButtonCollegeSearch" onClick={props.submitCollegeSearch} className="custom-searchbn">Search</div>
          <input type="submit" id="submit_query" value="Search" style={{display:'none'}}/>
        </div>
        <div id="coursesAdvanceOptions" className="search-listfileds slideClose">
          <p id="customsearch-title">Advanced Options :</p>
          <div className="ext-options">
            <div className="customsearch-exam dropDown">
              <select id="dropDown1" className="custom-select-exam" style={{display: 'none'}}></select>
            </div>
            <div className="customsearch-fees dropDown">
              <select id="dropDown2" className="custom-select-fees" style={{display: 'none'}}></select>
            </div>
            <div className="customsearch-specialisation dropDown">
              <select id="dropDown3" className="custom-select-spec" style={{display: 'none'}}></select>
            </div>
          </div>
        </div>
        <div id="instituteAdvanceOptions" className="search-listfileds slideClose">
          <p id="customsearch-title">Advanced Options :</p>
          <div className="ext-options">
            <div className="customsearch-exam dropDown">
              <select id="selectInstituteCategory" onChange={props.populateCourseList} placeholder="Choose Stream" className="custom-select-normal" style={{display: 'none', width: '359px'}}>
                <option disabled="disabled" value=""></option>
              </select>
            </div>
            <div className="customsearch-specialisation dropDown">
              <select id="selectInstituteCourse" disabled="disabled" placeholder="Select Course" className="custom-select-normal" style={{display: 'none', width: '359px'}}>
                <option disabled="disabled" value=""></option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </form>
  );
}

export default CollegesSearch;
