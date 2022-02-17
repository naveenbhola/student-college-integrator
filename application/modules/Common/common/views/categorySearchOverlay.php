<div id="categorysearchoverlay">
<div id="drpDown" onMouseOver="MM_showHideLayers('drpDown','','show')" onMouseOut="MM_showHideLayers('drpDown','','hide'); MM_showHideLayers('countryOption','','hide');MM_showHideLayers('careerOption','','hide');MM_showHideLayers('eventCategories','','hide');MM_showHideLayers('eventCountries','','hide');MM_showHideLayers('importantDeadlines','','hide');MM_showHideLayers('impDeadlines','','hide');MM_showHideLayers('countries','','hide');MM_showHideLayers('eventTypes','','hide');MM_showHideLayers('testPreparation','','hide')" class="brd" style="display:none">
		<div style="line-height:8px">&nbsp;</div>
		<a href="javascript:void(0)" onMouseOver="openSubDiv(this, 'careerOption');" id="careerMenus" style="width:120px;"> &nbsp; &nbsp;Career Options</a>
		<a href="javascript:void(0)" onMouseOver="openSubDiv(this, 'testprep');" id="testprepMenus" style="width:120px;"> &nbsp; &nbsp;Test Preparations</a>
		<a href="javascript:void(0)" onMouseOver="openSubDiv(this, 'countryOption');" id="countryMenus" style="width:120px;"> &nbsp; &nbsp;Countries</a>
		<a href="javascript:void(0)" onMouseOver="openSubDiv(this, 'testPreparation');" id="testPreparationMenus" style="width:120px"> &nbsp; &nbsp;Test Preparation</a>
		<a href="javascript:void(0)" onMouseOver="openSubDiv(this, 'eventCategories');" id="eventCategoriesMenus" style="width:120px"> &nbsp; &nbsp;Event Categories</a>
                <a href="javascript:void(0)" onMouseOver="openSubDiv(this, 'eventCountries');" id="eventCountriesMenus" style="width:120px"> &nbsp; &nbsp;Event Countries</a>
		<a href="javascript:void(0)" onMouseOver="openSubDiv(this, 'importantDeadlines');" id="importantDeadlinesMenus" style="width:120px"> &nbsp; &nbsp;Important Deadlines</a>
		 <a href="javascript:void(0)" onMouseOver="openSubDiv(this, 'impDeadlines');" id="impDeadlinesMenus" style="width:120px"> &nbsp; &nbsp;Imp Deadlines</a>
		<a href="javascript:void(0)" onMouseOver="openSubDiv(this, 'eventTypes');" id="eventTypesMenus" style="width:120px"> &nbsp; &nbsp;Event Types</a>
		<a href="javascript:void(0)" onMouseOver="openSubDiv(this, 'countries');" id="countriesMenus" style="width:120px"> &nbsp; &nbsp;Change Country</a>
</div>
<?php
		global $popularInst;
		$popularInst = $this->category_list_client->getInstituteForTabs($appID);
		$popularInstNew = array();
                if($popularInst[0]!='NO_DATA_FOUND'){
			foreach($popularInst as $institiue) {
				$popularInstNew[$institiue[course_type]][$institiue[position]] = $institiue;
			}	
			//_P($popularInstNew); 
		}
?>
<script>
var count_popular_testprep = <?php echo count($popularInstNew['toptestprepcourses']);?>;
var count_of_total_popular_institutes = '<?php echo count($popularInstNew);?>'
var popularInstNew_json = '<?php echo json_encode($popularInstNew);?>';
if(popularInstNew_json) {
	popularInstNew_json = eval("eval("+popularInstNew_json+")");
}
var studyabroad_navigation_lebel = "";
var reset_array = {0:'topstudyabroadUGcourses',1:'topstudyabroadPGcourses',2:'topstudyabroadPHDcourses'};
</script>
<div id="careerOption" onMouseOver="MM_showHideLayers('careerOption','','show');" onMouseOut="MM_showHideLayers('careerOption','','hide');">	
<ul id="category_navigation">
<?php
			$this->load->library('category_list_client');
			global $categoryTree;
			$categoryTree = $this->category_list_client->getCategoryTree($appID,1);
			global $tabsContentByCategory;
			$tabsContentByCategory = $this->category_list_client->getTabsContentByCategory();
			$i = -1;
			foreach($tabsContentByCategory as $category) {
				$i++;
?>
				<li alreadyclicked ='NO' onclick="makeItSticky(<?=$i?>,<?=$category['id']?>,this);" id="catagoryli_<?=$category['id']?>" <?php if($category['id'] == 11){ echo 'class="last"';}?>><span><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" id="catagory_<?=$category['id']?>" onmouseover="showSubCatagories(<?=$i?>,<?=$category['id']?>,this)" href="javascript:void(0);" style="cursor:default"><?php echo str_replace(array('AVGC',')','(','Visual Effects'),array('','','','VFX'),$category['name']); ?></a></span></li>
<?php
		    }
?>
</ul>
<div style="display:none"></div>
		<div id="subCatagories">
				<!--<div id="whiteLine">&nbsp;&nbsp;</div>-->
				<div id="subCourse">d</div>
				<?php $i = 0;
				    foreach($tabsContentByCategory as $category) { ?>
				    <div class = "careerOptionsCategories" id="subCat_<?=$i?>_<?=$category['id']?>">
				        <ul>
				        <?php foreach($category['subcats'] as $id => $catSubCat){
					    	$subcat_id = $id;
						$subcat_name = $catSubCat['name'];
						$subcat_name = ($subcat_name == 'BE/Btech'?'B.E/B.Tech':$subcat_name);
						$subcat_name = ($subcat_name == 'Mass Communication'?'Mass Communication / Viscomm':$subcat_name);
						$subcat_url  = $catSubCat['url'];
					?>
						<li><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$subcat_url?>" title="<?=$subcat_name?> colleges lists"><?=$subcat_name?></a></li>
				        <?php
						}
						if($category['id'] == 4) { //Banking & Finance
								?>
								<li><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="http://it.shiksha.com/tally-courses-in-india-categorypage-10-120-1-0-0-1-1-2-0-none-1-0" title="Tally colleges lists">Tally</a></li>
								<?php
						}
						?>
				        </ul>
				    </div>
				<?php $i++; } ?>
		</div>
</div>
<script type="text/javascript">
		var categoryTree = new Array();
		categoryList = categoryTree;
		tabsContentByCategory = <?=json_encode($tabsContentByCategory)?>;
</script>
<!--onMouseOut="MM_showHideLayers('MBA','','hide');" -->
<!--MBA Layer starts -->
<div id="MBA" style="width:740px; margin: 0px; z-index: 1400; display:none;" onMouseOver="MM_showHideLayers('MBA','','show')"; onMouseOut="MM_showHideLayers('MBA','','hide');">
		<div class="layer-search">
				<div style="position:relative; z-index:99; float:left; width:100%;">
					<form method="get" onsubmit="checkTextElementOnTransition(document.getElementById('tempkeyword'),'focus'); submitMBALayerSearch(); return false;">
						<input type="button" onclick="trackEventByGA('MBALayer','SEARCH_BTN'); submitMBALayerSearch();" class="gray-button2 flRt" value="Search">
						<div style="width:620px;" class="search-outer"><span class="search-icn"></span>
						    <input type="text" style="color:#565656;width:570px" class="homeShik_searchtextBox" autocomplete="off" value="Find Institute by Name" default="Find Institute by Name" id="mbalayer_tempkeyword"  onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" />
						</div>
					</form>
				</div>
				<div class="clearFix"></div>
        </div>
		<table width="100%" cellspacing="0" cellpadding="0" class="layer-table">
				<tr>
						<td valign="top" class="layer-col-1">
							<div class="layer-titles">View Institutes by Courses</div>
							<ul class="course-list">
								<?php
								foreach($tabsContentByCategory[3]['subcats'] as $cat) {
										if(in_array($cat['id'], array(23,24,25,26,27))){
												?>
												<li><a onclick="trackEventByGA('topnavlinkclick', this.innerHTML)" href="<?=$cat['url']?>" title="<?php echo $cat['name']." institutes list";?>"><?php echo $cat['name']; ?></a></li>
												<?php
										}
								}
								?>
							</ul>
						</td>
						<?php
						$middleColBorderStyle = "";
						if(count($popularInstNew['topmbacourses']) == 0){
								$middleColBorderStyle = "border-right:none;";
						}
						$examsList = array('CAT', 'XAT', 'MAT', 'CMAT');
						$otherExams = array("PAN", "KAJ");
						?>
						<td valign="top" class="layer-col-2" style="<?php echo $middleColBorderStyle;?>">
								<div class="layer-titles">View Full Time MBA Institutes by Exam Accepted</div>
								<ul class="course-list">
										<?php
										$i = 0;
										while($i < count($examsList)){
												$examSlice = array_slice($examsList, $i, 2);
										?>
												<li>
														<?php
														foreach($examSlice as $exam){
																$onclickString = "";
																if($exam == "Others"){
																?>
																		<label style="position:relative; z-index:999" ><input onclick="manageOtherExamsBlock(this);" type="checkbox" value="<?php echo $exam;?>"/><?php echo $exam;?></label>
																		<div id="other-course-layer" class="other-course-layer" style="display:none;">
																				<?php
																				foreach($otherExams as $exam){
																				?>
																						<label><input type="checkbox" name="gnbotherexams[]" value="<?php echo $exam;?>"/>&nbsp;<?php echo $exam;?></label>
																				<?php
																				}
																				?>
																				<div class="spacer5 clearFix"></div>
																				<div class="tac"><input type="button" value="Ok" class="gray-button2" onclick="hideElement(document.getElementById('other-course-layer'));" /></div>
																		</div>
																<?php
																} else {
																		?>
																		<label><input type="checkbox" name="gnbexams[]" value="<?php echo $exam;?>"/>&nbsp;<?php echo $exam;?></label>
																<?php
																}
														}
														$i = $i + 2;
														?>
												</li>
										<?php
										}
										?>
										<li>
											<input type="button" value="Proceed" class="gray-button2" onclick="submitGNBExams();"/>
											<br/><span class="errorMsg" id="gnb_exam_submit_error_cont" style="display:none;"></span>
										</li>
									
								</ul>
						</td>
						<td valign="top" class="layer-col-3">
								<?php
								if(count($popularInstNew['topmbacourses']) > 0){
								?>
								<div class="layer-titles">Featured Institutes</div>
								<ul class="popular-courses">
										<?php
										foreach($popularInstNew['topmbacourses'] as $c) {
										?>
												<li>
													<div class="figure">
														<a onclick="trackEventByGA('topnavlinkclick', this.innerHTML)" href="<?=$c['detailurl']?>" title="<?=html_entity_decode($c['instituteName'])?>">
																<img src="<?=$c['logo']?>"/>
														</a>
													</div>
													<div class="details">
														<a <?php if(!empty($c['course_id'])) { echo "rel='nofollow'"; } ?> onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$c['detailurl']?>" title="<?=html_entity_decode($c['instituteName'])?>"><?=html_entity_decode($c['instituteName'])?></a>
													</div>
												</li>
										<?php
										}
										?>
								</ul>
								<?php
								}
								?>
						</td>
				</tr>
		</table>
		<div class="spacer10"></div>
        <!--<div class="mba-layer-bot">Confused? Ask our experts about MBA that suits your profile &nbsp;&nbsp;&nbsp;<input type="button" value="Proceed" class="gray-button2" onclick="goToAnAFromMBALayer();"/></div>-->
</div>
<!--MBA Layer ends -->


<?php

$rankingPageList = array (
	array('title' => 'Full time MBA/PGDM', 'url' => SHIKSHA_HOME . '/' . trim('mba/ranking/top-mba-colleges-india/2-2-0-0-0', '/')),
	array('title' => 'Part-time MBA',  'url' => SHIKSHA_HOME . '/' . trim('mba/ranking/top-part-time-mba-colleges-india/26-2-0-0-0', '/')),
	array('title' => 'Executive MBA', 'url' => SHIKSHA_HOME . '/' . trim('mba/ranking/top-executive-mba-colleges-india/18-2-0-0-0', '/')),
    array('title' => 'Engineering',  'url' => SHIKSHA_HOME . '/' . trim('top-engineering-colleges-in-india-rankingpage-44-2-0-0-0', '/')),
    array('title' => 'LLB',  'url' => SHIKSHA_HOME . '/' . trim('top-llb-colleges-in-india-rankingpage-56-2-0-0-0', '/')),

);
?>

<div id="testprep" onMouseOver="MM_showHideLayers('testprep','','show');" onMouseOut="MM_showHideLayers('testprep','','hide');/*MM_showHideLayers('drpDown','','hide');*/" style="width:275px;">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
    	<tr>
		    <td valign="top" class="nav-list" style="border:none";>
				<ul>
				<?php
				$totalRankingPages = count($rankingPageList);
				for($count=0; $count < $totalRankingPages; $count++){
						$rankingPage = $rankingPageList[$count];
						$rankingPageTitle = $rankingPage['title'];
						$rankingPageURL	  = $rankingPage['url'];
						if($count == $totalRankingPages - 1){
								?>
								<li onmouseover="if(this.className == 'last') {this.className='hover last'} else {this.className='hover'};" onmouseout="if(this.className == 'hover last') {this.className='last';} else {this.className='';}" class="last"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?php echo $rankingPageURL;?>"><?php echo $rankingPageTitle;?></a></li>
								<?php
						} else {
								?>
								<li onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?php echo $rankingPageURL;?>"><?php echo $rankingPageTitle;?></a></li>
								<?php
						}
				}
				?>
				</ul>
		   </td>
		</tr>
  </table>      
</div>

<!-- 
<div id="testprep" onMouseOver="MM_showHideLayers('testprep','','show');" onMouseOut="MM_showHideLayers('testprep','','hide');/*MM_showHideLayers('drpDown','','hide');*/" style="<?php if(count($popularInstNew['toptestprepcourses'])>0){echo 'width:595px;';} else { echo 'width:275px';} ?>" <?php if(count($popularInstNew['toptestprepcourses'])>0):?> class="testprep-shade"<?php endif;?>>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
    	<tr>
        <?php
		if(count($popularInstNew['toptestprepcourses'])>0){
		//echo '<td valign="top"><ul class="popular-courses"><li><div class="popular-title">Featured Institutes</div></li>';
		foreach($popularInstNew['toptestprepcourses'] as $In) {
		$testprep_course_id = $In['course_id'];
		?>
		<li>
			<div class="figure">
				<a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$In['detailurl']?>" title="<?=htmlspecialchars($In['instituteName'])?>"><img src="<?=$In['logo']?>" /></a>
			</div>
            
            <div class="details">
            	<a <?php if(!empty($testprep_course_id)) echo "rel='nofollow'"?> onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$In['detailurl']?>" title="<?=htmlspecialchars($In['instituteName'])?>"><?=htmlspecialchars($In['instituteName'])?></a>
			</div>
		</li>
<?php } ?>
</ul>
</td>
<?php } ?>
    
    <td valign="top" class="nav-list" <?php if(count($popularInstNew['toptestprepcourses']) == 0) {echo 'style="border:none"';}?>
       <?php if(count($popularInstNew['toptestprepcourses']) > 0) {echo 'style="background-color: #F8F8F8;"';}?>>
	<ul style="width:274px;">
	<ul>
		<?php
			$i=0;foreach($tabsContentByCategory[14]['subcats'] as $cat) { 
		?>
        	<li onmouseover="if(this.className == 'last') {this.className='hover last'} else {this.className='hover'};" onmouseout="if(this.className == 'hover last') {this.className='last';} else {this.className='';}" <?php if($i == count($tabsContentByCategory[14]['subcats'])-1):?>class="last"<?php endif;?>>
			<a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$cat['url']?>" title="<?php echo $cat['name']." institutes list";?>"><?php echo $cat['name']; ?></a></li>
		<?php $i++;} ?>
		</ul>
        </td>
  </tr>
  </table>      
</div>
-->


<div id="countryOption" onMouseOver="MM_showHideLayers('countryOption','','show');" onMouseOut="MM_showHideLayers('countryOption','','hide');/*MM_showHideLayers('drpDown','','hide');*/"  style="width:222px;">

	<ul>
	
		
        <li onclick="makeAbroadNavSticky('countryOption',function() {MM_showHideLayers('countryOption','','show');},function() {MM_showHideLayers('countryOption','','hide');},'topstudyabroadUGcourses',reset_array);" alreadyClicked= "NO" id="topstudyabroadUGcourses" onMouseOver="showSubmenuForStuabroadTab('topstudyabroadUGcourses',this);this.className='hover';" onMouseOut="this.className='';">
	<span><a style="cursor:default;" onclick="trackEventByGA('topnavlinkclick',this.innerHTML);" href="javascript:void(0);" title="">UG / Bachelors Courses</a></span></li>

<li onclick="makeAbroadNavSticky('countryOption',function() {MM_showHideLayers('countryOption','','show');},function() {MM_showHideLayers('countryOption','','hide');},'topstudyabroadPGcourses',reset_array);" alreadyClicked= "NO" id="topstudyabroadPGcourses"   onMouseOver="showSubmenuForStuabroadTab('topstudyabroadPGcourses',this);this.className='hover';" onMouseOut="this.className='';">
		<span><a style="cursor:default;" onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="javascript:void(0);" title="">PG / Masters Courses</a></span></li>

<li onclick="makeAbroadNavSticky('countryOption',function() {MM_showHideLayers('countryOption','','show');},function() {MM_showHideLayers('countryOption','','hide');},'topstudyabroadUniversitiesLinks',reset_array);" alreadyClicked= "NO" id="topstudyabroadUniversitiesLinks"   onMouseOver="showSubmenuForStuabroadTab('topstudyabroadUniversitiesLinks',this);this.className='hover';" onMouseOut="this.className='';">
		<span><a style="cursor:default;" onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="javascript:void(0);" title="">Universities</a></span></li>

<li onclick="makeAbroadNavSticky('countryOption',function() {MM_showHideLayers('countryOption','','show');},function() {MM_showHideLayers('countryOption','','hide');},'topstudyabroadExamsLinks',reset_array);" alreadyClicked= "NO" id="topstudyabroadExamsLinks"   onMouseOver="showSubmenuForStuabroadTab('topstudyabroadExamsLinks',this);this.className='hover';" onMouseOut="this.className='';">
		<span><a style="cursor:default;" onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="javascript:void(0);" title="">Exams</a></span></li>

<li onclick="makeAbroadNavSticky('countryOption',function() {MM_showHideLayers('countryOption','','show');},function() {MM_showHideLayers('countryOption','','hide');},'topstudyabroadGuidesLinks',reset_array);" alreadyClicked= "NO" id="topstudyabroadGuidesLinks" class="last"  onMouseOver="showSubmenuForStuabroadTab('topstudyabroadGuidesLinks',this);this.className='hover last';" onMouseOut="this.className='last';">
		<span><a style="cursor:default;" onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="javascript:void(0);" title="">Student Guides</a></span></li>


<li  style="display:none;" onclick="makeAbroadNavSticky('countryOption',function() {MM_showHideLayers('countryOption','','show');},function() {MM_showHideLayers('countryOption','','hide');},'topstudyabroadPHDcourses',reset_array);" alreadyClicked= "NO" id="topstudyabroadPHDcourses" onMouseOver="showSubmenuForStuabroadTab('topstudyabroadPHDcourses',this);this.className='hover last';" onMouseOut="this.className='last';">
		<span><a  style="cursor:default;" onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="javascript:void(0);" title="">Ph.D / Doctoral Courses</a></span></li>
		
		
</ul>
<!--</td>-->

<div id="subCatagories_country" onmouseover="$(studyabroad_navigation_lebel).className = $(studyabroad_navigation_lebel).className + ' hover';" onmouseout="if($(studyabroad_navigation_lebel).className == 'hover last') {if($j('#'+studyabroad_navigation_lebel).attr('alreadyClicked') == 'NO'){$(studyabroad_navigation_lebel).className = 'last';} else {$(studyabroad_navigation_lebel).className = 'hover last';}}else {if($j('#'+studyabroad_navigation_lebel).attr('alreadyClicked') == 'NO'){$(studyabroad_navigation_lebel).className = '';} else {$(studyabroad_navigation_lebel).className = 'hover';}};" style="top:0;display:none;height:310px;<?php if(count($popularInstNew['topstudyabroadUGcourses'])>0 || count($popularInstNew['topstudyabroadPGcourses'])>0 || count($popularInstNew['topstudyabroadPHDcourses'])>0){echo 'width:520px'; } else { echo 'width:300px'; } ?>">

		<ul style="float:left; width:300px; display: none;" id="topstudyabroadUGcourses_content">
				<span style="color: #666666;display: block;font-weight: bold;margin-bottom: 5px; margin-left: 10px;margin-top: 3px;font-size: 13px;">By Course</span>
				<li onmouseover="if(this.className == 'last') {this.className='last'} else {this.className=''};" onmouseout="if(this.className == 'last') {this.className='last';} else {this.className='';}"  >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/be-btech-in-abroad-dc11510'?>" title="B Tech / BE (<?=$GLOBALS["studyAbroadPopularCourses"]["1510"]?>)">B Tech / BE (<?=$GLOBALS["studyAbroadPopularCourses"]["1510"]?>)</a>
				</li>
				<span style="color: #666666;display: block;font-weight: bold;margin-bottom: 5px;margin-left: 10px;font-size: 13px;">By Stream</span>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-business-in-abroad-cl1239'?>" title="Business">Business</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-science-in-abroad-cl1242'?>" title="Science">Science</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-engineering-in-abroad-cl1240'?>" title="Engineering">Engineering</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-computers-in-abroad-cl1241'?>" title="Computers">Computers</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-medicine-in-abroad-cl1243'?>" title="Medicine">Medicine</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-law-in-abroad-cl1245'?>" title="Law">Law</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" class="last" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-humanities-in-abroad-cl1244'?>" title="Humanities">Humanities</a>
				</li>
		</ul>

		<ul style="float:left; width:310px; display: none;" id="topstudyabroadPGcourses_content">
				<span style="color: #666666;display: block;font-weight: bold;margin-bottom: 5px; margin-left: 10px;margin-top: 3px;font-size: 13px;">By Course</span>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}"  >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/mba-in-abroad-dc11508'?>" title="MBA (<?=$GLOBALS["studyAbroadPopularCourses"]["1508"]?>)">MBA (<?=$GLOBALS["studyAbroadPopularCourses"]["1508"]?>)</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/ms-in-abroad-dc11509'?>" title="MS (<?=$GLOBALS["studyAbroadPopularCourses"]["1509"]?>)">MS (<?=$GLOBALS["studyAbroadPopularCourses"]["1509"]?>)</a>
				</li>
				<span style="color: #666666;display: block;font-weight: bold;margin-bottom: 5px;margin-left: 10px;font-size: 13px;">By Stream</span>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-business-in-abroad-cl1239'?>" title="Business">Business</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-science-in-abroad-cl1242'?>" title="Science">Science</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-engineering-in-abroad-cl1240'?>" title="Engineering">Engineering</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-computers-in-abroad-cl1241'?>" title="Computers">Computers</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-medicine-in-abroad-cl1243'?>" title="Medicine">Medicine</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-law-in-abroad-cl1245'?>" title="Law">Law</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" class="last" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-humanities-in-abroad-cl1244'?>" title="Humanities">Humanities</a>
				</li>
		</ul>

		<ul style="float:left; width:245px; display: none;" id="topstudyabroadUniversitiesLinks_content">
		<?php global $studyAbroadStaticUniversities;
			  $totalStaticUni =  count($studyAbroadStaticUniversities);
		
			foreach($studyAbroadStaticUniversities as $key=>$val){
				if(($totalStaticUni-1) == $key){
					$lastCond = "class='last'";
				}else{
					$lastCond = "";
				}
				?>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" <?=$lastCond?> >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/'.$val['url']?>" title="<?=$val['title']?>"><?=$val['title']?></a>
				</li>
				<?php } ?>		
		</ul>

		<ul style="float:left; width:245px; display: none;" id="topstudyabroadExamsLinks_content">
		<?php global $studyAbroadStaticExams;
			  $totalStaticExam =  count($studyAbroadStaticExams);
		
			foreach($studyAbroadStaticExams as $key=>$val){
				if(($totalStaticExam-1) == $key){
					$lastCond = "class='last'";
				}else{
					$lastCond = "";
				}
				?>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" <?=$lastCond?> >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/'.$val['url']?>" title="<?=$val['title']?>"><?=$val['title']?></a>
				</li>
				<?php } ?>		
		</ul>

		<ul style="float:left; width:245px; display: none;" id="topstudyabroadGuidesLinks_content">
		<?php global $studyAbroadStaticStudentGuides;
			  $totalStaticGuides =  count($studyAbroadStaticStudentGuides);
		
			foreach($studyAbroadStaticStudentGuides as $key=>$val){
				if(($totalStaticGuides-1) == $key){
					$lastCond = "class='last'";
				}else{
					$lastCond = "";
				}
				?>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" <?=$lastCond?> >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/'.$val['url']?>" title="<?=$val['title']?>"><?=$val['title']?></a>
				</li>
				<?php } ?>		
		</ul>


		
		<ul style="float:left; width:245px; display: none;" id="topstudyabroadPHDcourses_content">
				<span style="color: #666666;display: block;font-weight: bold;margin-bottom: 5px; margin-left: 10px;margin-top: 3px;font-size: 13px;">By Stream</span>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-humanities-in-abroad-cl1244'?>" title="Business">Business</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-humanities-in-abroad-cl1244'?>" title="Science">Science</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-humanities-in-abroad-cl1244'?>" title="Engineering">Engineering</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-humanities-in-abroad-cl1244'?>" title="Computers">Computers</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-humanities-in-abroad-cl1244'?>" title="Medicine">Medicine</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-humanities-in-abroad-cl1244'?>" title="Law">Law</a>
				</li>
				<li onmouseover="if(this.className == 'last') {this.className=' last'} else {this.className=''};" onmouseout="if(this.className == ' last') {this.className='last';} else {this.className='';}" class="last" >
						<a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage',studyabroad_navigation_lebel);trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_STUDYABROAD_HOME.'/masters-of-humanities-in-abroad-cl1244'?>" title="Humanities">Humanities</a>
				</li>
		</ul>


<?php
		if(count($popularInstNew['topstudyabroadUGcourses'])>0 || count($popularInstNew['topstudyabroadPGcourses'])>0 || count($popularInstNew['topstudyabroadPHDcourses'])>0){
		echo '<ul id="popular-courses" class="popular-courses"><li><div class="popular-title">Featured Institutes</div></li>';
		foreach($popularInstNew['topstudyabroadUGcourses'] as $In) {
		$topstudyabroad_course_id = $In['course_id'];
		?>
        <li>
		<div class="figure">
        	<a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$In['detailurl']?>" title="<?=html_entity_decode($In['instituteName'])?>"><img src="<?=$In['logo']?>" /></a>
        </div>
        <div class="details">
         	<a  <?php if(!empty($topstudyabroad_course_id)) echo "rel='nofollow'"?> onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$In['detailurl']?>" title="<?=html_entity_decode($In['instituteName'])?>"><?=html_entity_decode($In['instituteName'])?></a>
         </div>
         </li>
<?php } ?>
</ul>
<?php } ?>
</div>
</div>
<!-- Entrance Exam menu start-->
<?php
$entranceExamList = array(); //Modules::run('examPages/ExamPageMain/getCategoriesWithExams');
global $subCategoriesEntranceExamGNB;
?>
<div id="entranceExamMenu" onMouseOver="MM_showHideLayers('entranceExamMenu','','show');" onMouseOut="MM_showHideLayers('entranceExamMenu','','hide');"  style="width:222px;">

	<ul>
	
		<?php
			$subCategoriesEntranceExamGNBcount = 0;
			$entranceExamListCount = count($entranceExamList);
			foreach($subCategoriesEntranceExamGNB as $categoryName => $categoryData) { 
				if(!$entranceExamList[$categoryName]) {
					continue;
				}
				else {
					++$subCategoriesEntranceExamGNBcount;
				}
				$className = '';
				if($subCategoriesEntranceExamGNBcount == $entranceExamListCount) {
					$className = 'last';
				}
		?>
			<li onclick="makeAbroadNavSticky('entranceExamMenu',function() {MM_showHideLayers('entranceExamMenu','','show');},function() {MM_showHideLayers('entranceExamMenu','','hide');},'<?php echo $categoryData['id'];?>',reset_array);" alreadyClicked= "NO" id="<?php echo $categoryData['id'];?>" onMouseOver="showSubmenuForEntranceExam('<?php echo $categoryData['id'];?>',this);this.className='hover';" onMouseOut="this.className='<?php echo $className;?>';" style="<?php echo $categoryData['style'];?>" class="<?php echo $className;?>">
				<span>
					<a style="cursor:default;" onclick="trackEventByGA('topnavlinkclick',this.innerHTML);" href="javascript:void(0);" title=""><?php echo $categoryData['value'];?></a>
				</span>
			</li>
		<?php }
		?>
</ul>
<!--</td>-->

<div id="subCatagories_entranceExam" onmouseover="$(studyabroad_navigation_lebel).className = $(studyabroad_navigation_lebel).className + ' hover';" onmouseout="if($(studyabroad_navigation_lebel).className == 'hover last') {if($j('#'+studyabroad_navigation_lebel).attr('alreadyClicked') == 'NO'){$(studyabroad_navigation_lebel).className = 'last';} else {$(studyabroad_navigation_lebel).className = 'hover last';}}else {if($j('#'+studyabroad_navigation_lebel).attr('alreadyClicked') == 'NO'){$(studyabroad_navigation_lebel).className = '';} else {$(studyabroad_navigation_lebel).className = 'hover';}};" style="top:0;height:115px;width: 200px;">
<?php 
foreach($subCategoriesEntranceExamGNB as $categoryName => $categoryData) { 
	if(!$entranceExamList[$categoryName]) {
		continue;
	}
?>
	<ul style="width:100%;" id="<?php echo $categoryData['id'];?>_content">
		<?php
			$count=0;
			$examHeight = '';
			if(count($entranceExamList[$categoryName]) < 3 && isset($categoryData['style'])) {
				$examHeight = 'height:40px;';
			}
			foreach($entranceExamList[$categoryName] as $examName=>$examData) {
		        $count++;
                $trimmedExamName = $examName;
                $featuredStyle   = '';
                if(strlen($examName) >= 13) {
                	$trimmedExamName = substr($examName,0,11)."...";
                }
                if($examData['is_featured'] == 1) {
                    $featuredStyle = "style='text-decoration:underline !important;'";
                    $trimmedExamName = "<strong>$trimmedExamName</strong>";
                }
            ?>
            <li class="<?php echo ($count%2==0)?'flRt':'flLt'?>" style="width:42%;<?php echo $examHeight;?>"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML);" href="<?= $examData['url'];?>" title="<?php echo $examName;?>" <?php echo $featuredStyle?>><?php echo $trimmedExamName;?></a></li>
		<?php } ?>
	</ul>
<?php } ?>
</div>
</div>

<!-- Entrance Exam Menu End-->





<div id="cafeOption" onMouseOver="MM_showHideLayers('cafeOption','','show');" onMouseOut="MM_showHideLayers('cafeOption','','hide');">
	<ul>
    	<li onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?php echo SHIKSHA_ASK_HOME; ?>" rel="nofollow">Caf&eacute; Buzz</a></li>
        <li onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/discussionHome/1/1/1/answer/">Q & A</a></li>
        <li onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/discussionHome/1/6/1/answer/">Discussions</a></li>
        <li onmouseover="if(this.className == 'last') {this.className='hover last'} else {this.className='hover'};" onmouseout="if(this.className == 'hover last') {this.className='last';} else {this.className='';}" <?php if($validateuser =="false"):?>class="last"<?php endif;?>><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/discussionHome/1/7/1/answer/">Announcements</a></li>
						 <?php if(is_array($validateuser) && ($validateuser != "false")) {
								if($validateuser[0]['usergroup'] !== 'cms'){?>
						 <li onmouseover="if(this.className == 'last') {this.className='hover last'} else {this.className='hover'};" onmouseout="if(this.className == 'hover last') {this.className='last';} else {this.className='';}" class="last"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/discussionHome/1/4/1/answer/">My Q &amp; A</a></li>
						 <?php }else{ ?>
						 <li onmouseover="if(this.className == 'last') {this.className='hover last'} else {this.className='hover'};" onmouseout="if(this.className == 'hover last') {this.className='last';} else {this.className='';}" class="last"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/discussionHome/1/5/1/answer/">Editor's Pick</a></li>
						 <?php }} ?>
		</ul>	 
</div>


<div id="onlineOption" onMouseOver="MM_showHideLayers('onlineOption','','show');" onMouseOut="MM_showHideLayers('onlineOption','','hide');">
	<ul style="width:154px;">
		<?php
		global $onlineFormsDepartments;
		
		?>
    	<li onmouseover="this.className='hover last';" onmouseout="this.className='last';">
				<a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_HOME.'/'.$onlineFormsDepartments['Management']['url']?>"><?=$onlineFormsDepartments['Management']['shortName']?> Forms</a>
		</li>

        <li class="last" onmouseover="this.className='hover last';" onmouseout="this.className='last';">
                                <a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_HOME.'/'.$onlineFormsDepartments['Engineering']['url']?>"><?=$onlineFormsDepartments['Engineering']['shortName']?> Forms</a>
                </li>
		
		</ul>	 
</div>

<div id="gradOption" onMouseOver="MM_showHideLayers('gradOption','','show');" onMouseOut="MM_showHideLayers('gradOption','','hide');" style="<?php if(count($popularInstNew['topgradcourses'])>0) {echo 'width:860px'; } else { echo 'width:575px';} ?>">
	<table cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td valign="top" class="nav-list" <?php if(count($popularInstNew['topgradcourses']) == 0) {echo 'style="border:none"';}?>
        <?php if(count($popularInstNew['topgradcourses']) > 0) {echo 'style="background-color: #F8F8F8;"';}?>>
	<ul style="width:275px;">
    	<li onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$tabsContentByCategory[3]['subcats'][28]['url']?>">BBA/BMS/BBM/BBS</a></li>
        <li onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$tabsContentByCategory[2]['subcats'][56]['url']?>">B.E/B.Tech</a></li>
        <li onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$tabsContentByCategory[10]['subcats'][100]['url']?>">BCA, DCA, B.Sc CS/IT</a></li>
		<li style="<?php if(count($popularInstNew['topgradcourses']) >0) {echo 'padding-bottom:17px;border-radius:0px 0px 0px 8px; -moz-box-border-radius:0px 0px 0px 8px;'; }?>" onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$tabsContentByCategory[6]['subcats'][84]['url']?>">Bachelor in Hotel Management <?php if(count($popularInstNew['topgradcourses']) >0):?><br /><?php endif;?>(BHM)</a></li>
        <li style="<?php if(count($popularInstNew['topgrasdcourses']) >0) {echo 'border-radius:0px 0px 0px 8px; -moz-box-border-radius:0px 0px 0px 8px;'; }?>" <?php if(count($popularInstNew['topgradcourses']) ==0):?>class ="last" onmouseover="this.className='hover last';" onmouseout="this.className='last';" <?php else:?> onmouseover="this.className='hover';" onmouseout="this.className='';" <?php endif;?>><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=SHIKSHA_BANKING_HOME.'/bcom-colleges-in-india-categorypage-4-83-527-0-0-1-1-2-0-none-1-0'?>">B.Com</a></li>
		<li onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="http://arts.shiksha.com/ba-llb-colleges-in-india-categorypage-9-33-1456-0-0-1-1-2-0-none-1-0">B.A. LL.B. (Hons)</a></li>
        <li onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="http://arts.shiksha.com/bba-llb-colleges-in-india-categorypage-9-33-1457-0-0-1-1-2-0-none-1-0">BBA LL.B. (Hons)</a></li>
</ul></td>
	<!--li onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="http://arts.shiksha.com/ba-llb-colleges-in-india-categorypage-9-33-1456-0-0-1-1-2-0-none-1-0">B.A. LL.B. (Hons)</a></li>
        <li onmouseover="this.className='hover';" onmouseout="this.className='';"><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="http://arts.shiksha.com/bba-llb-colleges-in-india-categorypage-9-33-1457-0-0-1-1-2-0-none-1-0">BBA LL.B. (Hons)</a></li-->
	<td class="nav-list" style="vertical-align: top; font-size:16px; line-height: 24px;">
		<form name="careercentral" id="careercentral" method="post">
		<div style="width:300px;padding: 10px;">
                    <a href="/careers" style="text-decoration:none;margin-bottom:10px;display: block;">Career Central</a>
				<p>What's the right career for you?</p>
				<p>Find Here</p>
				<div style="font-size:14px; line-height: 22px; margin:10px 0">
						<p style="color:#646464; margin-bottom:5px;">Select your Std XII stream</p>
						<label style="cursor: pointer;"><input type="radio" name="careerstream" value="Science" checked="checked"/>Science</label>
						<label style="cursor: pointer;"><input type="radio" name="careerstream" value="Commerce"/>Commerce</label>
						<label style="cursor: pointer;"><input type="radio" name="careerstream" value="Humanities"/>Humanties/Arts</label>
				</div>
				<a href="javascript:void(0);" style=" padding:6px 12px; border:1px solid #d5d5d5; -moz-border-radius:10px;-webkit-border-radius:10px;border-radius:10px; color:#333; font-size:14px; background: #f0f0f0; text-decoration: none;" onclick="careerCentralOptionSet();">Continue</a>
		</div>
		</form>
	</td>	


<?php
		if(count($popularInstNew['topgradcourses'])>0){
		echo '<td valign="top"><ul class="popular-courses"><li><div class="popular-title">Featured Institutes</div></li>';
		foreach($popularInstNew['topgradcourses'] as $In) {
		$topgrad_course_id = $In['course_id'];
		?>
		<li>
        	<div class="figure">
				<a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$In['detailurl']?>" title="<?=html_entity_decode($In['instituteName'])?>"><img src="<?=$In['logo']?>"/></a>
			</div>
			<div class="details">
            	<a <?php if(!empty($topgrad_course_id)) echo "rel='nofollow'"?> onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?=$In['detailurl']?>" title="<?=html_entity_decode($In['instituteName'])?>"><?=html_entity_decode($In['instituteName'])?></a>
			</div>
		</li>
<?php } ?>
</ul></td>
<?php } ?>
</tr>
</table>
</ul>
</div>
<?php
//if(stripos($_SERVER['REQUEST_URI'],"event") > 0)
//		$this->load->view('common/eventsOverlay');
?>
</div>
