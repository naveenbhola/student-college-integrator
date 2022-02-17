<?php if(MOBILE_SEARCH_V2_INTEGRATION_FLAG == 1){ ?>
<section class="msgBnr">
    <div id="searchInnerCont" class="_container">
        <h1>
            Empowering millions of students in making the right
            career and college decision
        </h1>
        <div id="mainTabs1" class="tbSec">
            <ul class="SearchTab">
                <li data-index="1" class="active">
                    <h2>Colleges</h2>
                </li>
                <li data-index="2">
                    <h2><a class="_hmsrchTab" data-param="exams" href="javascript:void(0);" data-enhance="false" data-transition="none" data-rel="dialog" data-inline="true" data-transition="slideup">Exams</a></h2>
                </li>
                <li data-index="3">
                    <h2><a class="_hmsrchTab tb-psRl" data-param="questions" href="javascript:void(0);" data-enhance="false" data-transition="none" data-rel="dialog" data-inline="true" data-transition="slideup">Questions</a></h2>
                </li>
            </ul>
        </div>
        <div class="tbc">
            <div data-index="1" class="active">
                <ul>
                    <li>
                        <div id="srchContTab1" class="srBx">
                        <a href="javascript:void(0);" class="inpt _hmsrchTab" data-enhance="false" data-transition="none" data-rel="dialog" data-inline="true" data-transition="slideup">
                           Find colleges,universities & courses
                           <span class="search-field-btn"><i class=" msprite search-icn"></i>
                           </span>
                        </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<?php } else { ?>
		 <div id="searchContainerDiv">
		 <form id="searchbox1" action="/search/index" autocomplete="off" accept-charset="utf-8" method="get" onsubmit="return false;">
		     <article class="search-box">
			<div style="position: relative;" onclick="setCookie('refine_url',''); trackEventByGAMobile('HTML5_Homepage_Search_Button');trackUserAutoSuggestion('bc');return false;">
				<input type="button" class="search-btn flRt">
				<i class="msprite search-icn" style="position: absolute; right: 11px; top: 5px;"></i> 
			</div>
			<div class="serach-field" style="position: relative;">
				<input type="text" placeholder="Search by college or institute name" id="keywordSuggest" name="keyword"  minlength="1" />
				<ul id="suggestions_container" style="list-style: none;">
					 
				</ul>
			</div>
			<input type="hidden" name="from_page" value="mobilesearchhome" />
			<input type="hidden" name="keyword" id="keyword" autocomplete="off" value="">
			<input type="hidden" name="search_type" id="search_type" autocomplete="off" value="">
		     </article>
		 </form>
		 </div>
		 
		 <!--<i class="cross-icon" onClick="jQuery('#keywordSuggest').val('');jQuery('#suggestions_container').hide(); jQuery('.cross-icon').hide();" style="display:none">&times;</i>-->
		 <input type="hidden" name="suggestedInstitutes" id="suggestedInstitutes" value="" />
<?php } ?>