<section class="clearfix content-wrap" style="border-radius:0; box-shadow:none; margin:15px 0; background: #fff;">
  <header class="content-inner content-header clearfix" style="padding: 15px !important">
    <div class="custome-dropdown" id="collegeCardDownList" style="z-index:9;">
	<a href="javascript:void(0)"  onClick="toggleCustomDropDown('collegeCardDropDown');$('#questionWidgetDropDownList').css({'z-index':1});">
		<div class="arrow" ><i class="caret"></i></div>
	    <span class="display-area" id="widgetTypeOption">Top Ranked</span>
	</a>
	<div class="drop-layer" id="collegeCardDropDown" style="display:none; width: 96px;">
	  <ul>
	    <li><a id="" href="javascript:void(0)" onClick="changeCollegeCardWidget(this,'topRanked','Top Ranked Colleges'); trackEventByGAMobile('TOP_RANKED_COLLEGES_CCHOME_MOBILE');">Top Ranked</a></li>
	    <li><a id="" href="javascript:void(0)" onClick="changeCollegeCardWidget(this,'featured','Featured Colleges'); trackEventByGAMobile('FEATURED_COLLEGES_CCHOME_MOBILE');">Featured</a></li>
	    <li><a id="" href="javascript:void(0)" onClick="changeCollegeCardWidget(this,'mostViewed','Most Viewed Colleges'); trackEventByGAMobile('MOST_VIEWED_COLLEGES_CCHOME_MOBILE');">Most Viewed</a></li>
	    <li><a id="" href="javascript:void(0)" onClick="changeCollegeCardWidget(this,'trending','Trending Colleges'); trackEventByGAMobile('TRENDING_COLLEGES_CCHOME_MOBILE');">Trending</a></li>
	  </ul>
	</div>
    </div>
    <h2 style="margin-right:98px; padding-top:5px;" class="title-txt" id="WidgetHeading">Top Ranked Colleges</h2>
  </header>
  
  <article id="college-Widget-Main">
      <?php $this->load->view('campus_connect/collegeCardSliderView'); ?>
    </article>
  
</section>