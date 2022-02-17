<form formname="careers" onsubmit="return false;" class="shadow-box">
	<div class="customsearch-fields">
		<div class="search-refine-colleges">
			<div tabindex="1" style="position: relative">
				<input type="text" placeholder="Enter a career that you are interested in... (E.g. Engineer, Doctor)" autocomplete="off" name="search" id="searchby-career">
				<div id="search-career-layer" class="search-college-layer" style="display: none;">
				</div>
			</div>
		</div>
		<div class="custom-searchbn" onclick="submitCareersSearch(); event.stopPropagation();">
			Search
			<input type="button" class="orange f18" value="" id="submit_careers" value="Search">
		</div>
	</div> 
</form>