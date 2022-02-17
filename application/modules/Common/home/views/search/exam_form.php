<form formname="exams" onsubmit="return false;" class="shadow-box">
    <div class="customsearch-fields">
        <div class="search-refine-colleges">
            <div tabindex="1" style="position: relative">
                <input type="text" placeholder="Find exams by name or course..." autocomplete="off" name="search" id="searchby-exam">
                <div id="search-exam-layer" class="search-college-layer" style="display: none;">
                </div>
            </div>
        </div>
        <div class="custom-searchbn" onclick="submitExamsSearch(); event.stopPropagation();">
            Search
            <input type="button" class="orange f18" value="" id="submit_exams" value="Search">
        </div>
    </div> 
</form>
