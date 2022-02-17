<section class="content-wrap clearfix" style="border-radius:0; box-shadow:none; margin:15px 0; background: #fff;">
  <header class="content-inner content-header clearfix" style="padding: 15px !important">
    <div class="custome-dropdown" id="questionWidgetDropDownList" style="z-index:9;">
	<a href="javascript:void(0)" onClick="toggleCustomDropDown('questionWidgetDropDown');" >
		<div class="arrow"><i class="caret"></i></div>
	    <div style="max-height: 25px; overflow: hidden;"><span class="display-area" id="questionListSelection" style="width: 91px;
display: inline-block;">Select</span></div>
	</a>
	<div class="drop-layer" id="questionWidgetDropDown" style="display:none; width: 96px;">
	  <ul>
	    <li><a id="topRatedQuestions" href="javascript:void(0)" onClick="loadHomepageQuestionWidget(this, 'topQuestion'); trackEventByGAMobile('TOP_RANKED_QUESTIONS_CCHOME_MOBILE');">Top Ranked</a></li>
	    <li><a id="" href="javascript:void(0)" onClick="loadHomepageQuestionWidget(this, 'featured'); trackEventByGAMobile('FEATURED_QUESTIONS_CCHOME_MOBILE');">Featured</a></li>
	    <li><a id="" href="javascript:void(0)" onClick="loadHomepageQuestionWidget(this, 'mostViewed'); trackEventByGAMobile('MOST_VIEWED_QUESTIONS_CCHOME_MOBILE');">Most Viewed</a></li>
	    <li><a id="" href="javascript:void(0)" onClick="loadHomepageQuestionWidget(this, 'trending'); trackEventByGAMobile('TRENDING_QUESTIONS_CCHOME_MOBILE');">Trending</a></li>
	  </ul>
	</div>
      </div>
      <h2 style="margin-right:98px; padding-top:5px;" class="title-txt" id="questionListHeading">Top Ranked Questions</h2>
  </header>
  <div class="campus-college-sub-container" id="questionListContainer">
      <?php $this->load->view('campus_connect/questionContainerView'); ?>

  </div>
</section>