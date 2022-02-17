<form formname="questions" onsubmit="return false;">
    <ul>
        <li>
            <div class="search-layer-field">
                <input type="text" placeholder="Search from 5 lakh+ answered questions" autocomplete="off" name="search" id="searchby-question" class="search-clg-field">
                <a style="display:none;" class="clg-rmv keywordCross">&times;</a>
            </div> 
            <ul id="search-question-layer" class="college-course-list" style="display: none;"></ul>
            <span style="display: none;" class="select_err" id="searchby-question_error">Please select the stream</span>
            <p class='clr'></p>
        </li>
        <li>
            <input type="button" class="green-btn" value="SEARCH" id="submit_questions" onclick="submitQuestionSearchFromButton();event.stopPropagation();">
        </li>
    </ul>
    <p class='clr'></p>
</form>