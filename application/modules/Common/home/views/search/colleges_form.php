<form formname="colleges" onsubmit="return false;">
<div class="shadow-box">
            <div class="customsearch-fields">

              <div class="search-refine-colleges">
                <div tabindex="1" style="position: relative">
                 <!-- <div class="search-colg-overlaplayer"></div> -->
                 <input type="text" placeholder="Find colleges, universities & courses" autocomplete="off" name="search" id="searchby-college">
                 <span id='keywordCross' onclick="enableSearchInputBox();">
                 <i class="icons ic_close1"></i>
                 </span>
                 <div id="search-college-layer" class="search-college-layer" style="display: none;">
                 </div>

               </div></div>
               <div class="search-refine-locations">
                <select class="chosen-select" multiple="true" tabindex="-1" style="display: none;">
                </select>
                <div id="chosenMultiSelectBox" class="chosen-container chosen-container-multi" style="<?php echo ($widgetForPage == 'HOMEPAGE_DESKTOP') ? 'width: 368px' : ''?>">
                  <ul class="chosen-choices" style="position: relative; overflow: hidden; margin: 0px 0px 0px 0px; background-color: #fff; cursor: text;">
                    <li class="search-field">
                      <input type="text" value="" placeholder="Enter location" class="default" autocomplete="off" style="padding: 0px 16px; outline: 0; background: transparent!important; box-shadow: none; color: #BFBFBF; font-size: 14px; border-radius: 0; border: 0px;">
                    </li>
                  </ul>
                </div>
              </div>
              <div id="submitButtonCollegeSearch" onclick="autoSuggestorInstanceArray.autoSuggestorInstanceSearch.handleInputKeys($j.Event( 'keypress', { keyCode: 13 } )); event.stopPropagation();" class="custom-searchbn">
               Search
              </div>
                <input type="submit" value="" id="submit_query" value="Search" style="display:none;">
            </div>  

            <div id="coursesAdvanceOptions" class="search-listfileds slideClose">
             
                <p id="customsearch-title">Advanced Options  :</p>
               <div class="ext-options">
             <div class="customsearch-exam dropDown">
              <select  id="dropDown1" class="custom-select-exam" style="display: none;">
             </select>
            </div> 
            <div class="customsearch-fees dropDown">
             <select id="dropDown2" class="custom-select-fees" style="display: none;">
             </select>
            </div>
            <div class="customsearch-specialisation dropDown">
              <select  id="dropDown3" class="custom-select-spec" style="display: none;">
              </select>
                  </div>
            </div>
          </div>

          <div id="instituteAdvanceOptions" class="search-listfileds slideClose">
              
                <p id="customsearch-title">Advanced Options  :</p>
              <div class="ext-options">
             <div class="customsearch-exam dropDown">
              <select id="selectInstituteCategory" onchange="populateCourseList();" placeholder="Choose Stream" class="custom-select-normal" style="display: none; width: 359px;">
               <option disabled="disabled" selected="selected"></option>
             </select>
            </div>
            <div class="customsearch-specialisation dropDown">
              <select id="selectInstituteCourse" disabled="disabled" placeholder="Select Course" class="custom-select-normal" style="display: none; width: 359px;">
                <option disabled="disabled" selected="selected"></option>
              </select>
            </div>
            </div>
            </div>
          </div>
     </form>