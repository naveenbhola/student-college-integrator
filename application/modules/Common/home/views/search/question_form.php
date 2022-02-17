<form formname="questions" onsubmit="return false;" class="shadow-box">
    <div class="customsearch-fields">
        <div class="search-refine-colleges">
            <div tabindex="1" style="position: relative">
                <input type="text" placeholder="Search from 5 lakh+ answered questions" autocomplete="off" name="search" id="searchby-question">
                <div id="search-question-layer" class="search-college-layer" style="display: none;">
                </div>
            </div>
        </div>
        <div class="custom-searchbn" onclick="submitQuestionSearch(); event.stopPropagation();">
            Search
            <input type="button" class="orange f18" value="" id="submit_question" value="Search">
        </div>
    </div> 
</form>
