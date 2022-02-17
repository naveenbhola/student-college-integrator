<?php $this->load->view('home/homepageRedesign/autoSuggestorCollegeReviewWidget'); ?>
<?php $this->load->view('home/homepageRedesign/autoSuggestorCampusConnectWidget'); ?>


<div id="searchOpacityLayer"></div>
<div id="searchReviewLayer" style="display: none;position:fixed;left: 30%;z-index: 10000;width:auto;"></div>

<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("homepageWidget"); ?>" type="text/css" rel="stylesheet" />
<div style=" clear: both;"></div>
<div style=" position: relative;">
	  
<div class="sugest-bx" id="tileSuggested" style="border: none;">
	  <ul style="text-align: left; display: none;" id="suggestions_container"></ul>
	  <ul class="cc-sugest" id="suggestedList"></ul>
</div>
<div class="sugest-bx" id="tileSuggestedCampusConnect" style="border: none;">
	  <ul style="text-align: left; display: none;padding: 0;font: 500 12px/18px tahoma" id="suggestions_containerCampusConnect"></ul>
	  <ul class="cc-sugest" id="suggestedListCampusConnect"></ul>
</div>
<div id="slider" style='width:100%;overflow:hidden; margin-bottom: 20px;'>
	  <div id="controls" style='width:300%'>
		    <div style='width:300%;overflow:hidden'>
			      <div class="slidescontainer" style='width:300%;position:relative;overflow:hidden;background-color:#fff;'>
					<div class="cump-con">
						  <div class="cump-wid-sl-bt">
							    <a href="javascript:void(0)"  onclick='animateCustom(false)'>
								      <i class="cr-home-sprite"  ></i>
							    </a>
						  </div>
						  <ul class="cump-con-wid" id='main_slider_ul'>
							    
							    <li class='main_slider' onclick="trackEventByGA('TileClick','<?=$widgetForPage?>_COLLEGE_REVIEW_WIDGET')">
								      <h4>MBA College Reviews and Ratings</h4>
								      <p class="stud">By Alumni and Current Students</p>
								      <div class="cump-wid-srch" style="position:relative" id="searchBox">
										<input type="text" placeholder="Search Reviews by College Name" name="keyword"  id="keywordSuggest" minlength="1" onfocus="getSuggestedSearch('','focus');" autocomplete="off" style='height: 25px;'/>
										<i class="cr-home-sprite" onclick="gotoSearchPage('<?php echo SHIKSHA_HOME ?>','keywordSuggest')"></i>
								      </div>
								      <p class="cump-wid-ask">Popular Review Collection</p>
								      <div>
										<div class="cump-wid-rank links">
										<?php
											  $count = 0;
											  foreach($widget_collegeReviewData as $data) { 
										?>
												    <a class='link<?php echo $count ?>' href="<?php echo $data['seoUrl']; ?>" class="cump-wid-tp" style='display: none;'><?php echo $data['title']; ?></a>
												    <span class='break<?php echo $count ?>' style='display: none'>&nbsp;<br></span>
												    
												    <?php $count++; ?>
										<?php
											  }
										?>
										

										</div> 
										<div class="cump-wid-viw" style="margin-top: 19px;">
											  <a href="<?php echo base_url()?><?= MBA_COLLEGE_REVIEW ?>">View All</a>
										</div> 
								      </div>
							    </li>
							    
							    <li  class='main_slider' onclick="trackEventByGA('TileClick','<?=$widgetForPage?>_CAMPUS_CONNECT_WIDGET')">
								      <h4>Connect with Current MBA Students </h4>
								      <p class="stud">6,000+ questions answered by 400+ students</p>
								      <div class="cump-wid-srch" id='searchBoxCampusConnect'>
										<input type="text" placeholder="Enter College Name to get Connected" name="keyword" id="keywordSuggestCampusConnect" minlength="1" autocomplete="off" style='height: 25px;'/>
										<i class="cr-home-sprite" onclick="gotoSearchPage('<?php echo SHIKSHA_HOME ?>','keywordSuggestCampusConnect')"></i>
								      </div>
								      <p class="cump-wid-ask">Explore what others are asking</p>
								      <a href="<?php echo base_url() ?>mba/resources/ask-current-mba-students#topQuestionContainer" class="cump-wid-tp">Questions for Top Ranking MBA Colleges</a>
								      <div>
										<div class="cump-wid-rank">
											  <a href="<?php echo base_url() ?>mba/resources/ask-current-mba-students#mostViewedContainer">Most Viewed Questions</a>
										</div> 
										<div class="cump-wid-viw">
											  <a href="<?php echo base_url() ?>mba/resources/ask-current-mba-students">View All</a>
										</div> 
								      </div>
							    </li>
							    
							    <li  class='main_slider' onclick="trackEventByGA('TileClick','<?=$widgetForPage?>_CAREER_COMPASS_WIDGET')">
								      <h4>Have a dream Job?</h4>
								      <div class="stud">Find MBA colleges to help you get there!</div>
								      <p class="cump-wid-bst">Find Best colleges to get a job in:</p>
								      <ul class="cump-wid-info">
										<li><a href="<?php echo SHIKSHA_HOME;?>/mba/resources/best-mba-sales-colleges-based-on-mba-alumni-data">Sales</a></li>
										<li><a href="<?php echo SHIKSHA_HOME;?>/mba/resources/best-mba-finance-colleges-based-on-mba-alumni-data">Finance</a></li>
										<li><a href="<?php echo SHIKSHA_HOME;?>/mba/resources/best-mba-marketing-colleges-based-on-mba-alumni-data">Marketing</a></li>
										<li><a href="<?=SHIKSHA_HOME.'/mba/resources/mba-alumni-data'?>">More &raquo;</a></li>
								      </ul>
								      <ul class="cump-wid-info">
										<li><a href="javascript:void(0)" onClick="setCookie('seo_data','Companies_HDFC Bank_0',3000,'/',COOKIEDOMAIN); window.location='<?=SHIKSHA_HOME.'/mba/resources/mba-alumni-data'?>';">HDFC Bank</a></li>
										<li><a href="javascript:void(0)" onClick="setCookie('seo_data','Companies_Infosys Technologies_3',3000,'/',COOKIEDOMAIN); window.location='<?=SHIKSHA_HOME.'/mba/resources/mba-alumni-data'?>';">Infosys Technology's</a></li>
										<li><a href="javascript:void(0)" onClick="setCookie('seo_data','Companies_Axis Bank_2',3000,'/',COOKIEDOMAIN); window.location='<?=SHIKSHA_HOME.'/mba/resources/mba-alumni-data'?>';">Axis Bank</a></li>
										<li><a href="<?=SHIKSHA_HOME.'/mba/resources/mba-alumni-data'?>">More &raquo;</a></li>
								      </ul>
							    </li>
						  </ul>
					</div>
			      </div>
		    </div>
	  </div>
</div>
</div>
<?php foreach($widget_collegeReviewData as $data) { ?>
<h2 class="tileText" style='display: none;'>
	  <a class="tileFinder" href="<?php echo $data['seoUrl'] ?>"><?php echo $data['title'] ?></a>
</h2>
<?php } ?>
