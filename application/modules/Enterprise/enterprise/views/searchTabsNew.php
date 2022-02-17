<?php
	
	$sa_course = $_REQUEST['sa_course'];
	$sa_course_type = $_REQUEST['sa_course_type'];

	$courseLink = "/enterprise/enterpriseSearch/index?course_name=%s&categoryId=%d&show=%s";
	$abroadCourseLink = "/enterprise/shikshaDB/index?course_name=%s&sa_course=%s&sa_course_type=%s&show=%s";

	$course_name = isset($_REQUEST['course_name'])? $_REQUEST['course_name']:'National Courses';
	$main_div_style = 'style="display:none"';
	
	$category_courses = array(
								'popular' => array(
                                    STUDY_ABROAD_POPULAR_MBA,
                                    STUDY_ABROAD_POPULAR_MS,
                                    STUDY_ABROAD_POPULAR_BEBTECH,
                                    STUDY_ABROAD_POPULAR_MEM
									,STUDY_ABROAD_POPULAR_MPHARM 
									,STUDY_ABROAD_POPULAR_MFIN 
									,STUDY_ABROAD_POPULAR_MDES 
									,STUDY_ABROAD_POPULAR_MFA 
									,STUDY_ABROAD_POPULAR_MENG 
									,STUDY_ABROAD_POPULAR_BSC
									,STUDY_ABROAD_POPULAR_BBA
									,STUDY_ABROAD_POPULAR_MBBS
									,STUDY_ABROAD_POPULAR_BHM 
									,STUDY_ABROAD_POPULAR_MARCH 
									,STUDY_ABROAD_POPULAR_MIS 
									,STUDY_ABROAD_POPULAR_MIM 
									,STUDY_ABROAD_POPULAR_MASC
									,STUDY_ABROAD_POPULAR_MA 
                                ),
                                'category' => array(
                                    STUDY_ABROAD_CATEGORY_BUSINESS,
                                    STUDY_ABROAD_CATEGORY_ENGINEERING,
                                    STUDY_ABROAD_CATEGORY_COMPUTERS,
                                    STUDY_ABROAD_CATEGORY_SCIENCE,
                                    STUDY_ABROAD_CATEGORY_MEDICINE,
                                    STUDY_ABROAD_CATEGORY_HUMANITIES,
                                    STUDY_ABROAD_CATEGORY_LAW
                                )
							);

foreach($searchTabs as $tab) {
	if ($course_name == $tab['course_name']) {
		$temp = $tab['tab_type'];
		$temp = strtolower($temp);
		$temp = str_replace(" ", "_", $temp);
		$result = '';
		for ($i=0; $i<strlen($temp); $i++) {
			if (preg_match('([0-9]|[a-z]|_)', $temp[$i])) {
				$result = $result . $temp[$i];
			}
		}

?>
<script> var ldb_course_type_csv_download = '<?php echo $result; ?>';</script>
<?php
		$temp = $tab['course_name'];
		$temp = strtolower($temp);
		$temp = str_replace(" ", "_", $temp);
		$result = '';
		for ($i=0; $i<strlen($temp); $i++) {
			if (preg_match('([0-9]|[a-z]|_)', $temp[$i])) {
				$result = $result . $temp[$i];
			}
		}
?>
<script> var ldb_result_course_name = '<?php echo $result; ?>';</script>
<?php }
} ?>

<script type="text/javascript">
	var course_name = '<?php echo $course_name; ?>';
	var activeTab = '';
	if(course_name == 'Study Abroad') activeTab = 'study_abroad_div';
	else if(course_name == 'National Courses') activeTab = 'national_non_mr_div';
	else if(course_name == 'MBA (Full Time)' || course_name == 'B.E./B.Tech (Full Time)') activeTab = 'national_mr_div';
</script>

<div id="cms-sub-tab">
	<ul>

		<li>
			<a <?php if($course_name == 'Study Abroad') echo 'class="active"'; ?> id="a_study_abroad_div" href="javascript:void(0);" onClick="showhideDIVOverlay(event,'study_abroad_div');">Study Abroad
				<span>&nbsp;</span>
			</a>
			<div id="study_abroad_div" class="cms-sub-menu" <?php echo $main_div_style; ?> >
				<ul class="sub-wdth">
					<li>
						<a href="<?php echo sprintf($abroadCourseLink,'Study Abroad',urlencode('All'),'all','true'); ?>" <?php echo ($sa_course == 'All' ? 'class="active-sublink"' : ''); ?> >
							<?php echo str_replace(" ","&nbsp;",'All');?>
						</a>
					</li>
                    <?php
						foreach($category_courses['popular'] as $category_course) {
					?>
					<li>
						<a href="<?php echo sprintf($abroadCourseLink,'Study Abroad',urlencode($category_course),'popular','true'); ?>" <?php echo ($sa_course == $category_course ? 'class="active-sublink"' : ''); ?> >
							<?php echo str_replace(" ","&nbsp;",$category_course);?>
						</a>
					</li>
					<?php
						}
					?>
				</ul>
				<ul class="sub-wdth">
					<?php
						foreach($category_courses['category'] as $category_course) {
					?>
					<li>
						<a href="<?php echo sprintf($abroadCourseLink,'Study Abroad',urlencode($category_course),'category','true'); ?>" <?php echo ($sa_course == $category_course ? 'class="active-sublink"' : ''); ?> >
							<?php echo str_replace(" ","&nbsp;",$category_course);?>
						</a>
					</li>
					<?php
						}
					?>
				</ul>
			</div>
		</li>

		<li>
			<a <?php if($course_name == 'MBA (Full Time)' || $course_name == 'B.E./B.Tech (Full Time)') echo 'class="active"'; ?> id="a_national_mr_div" href="javascript:void(0);" onClick="showhideDIVOverlay(event,'national_mr_div');">Domestic MR
				<span>&nbsp;</span>
			</a>
			<div id="national_mr_div" class="cms-sub-menu" <?php echo $main_div_style; ?> >
				<ul>
					<li>
						<a href="<?php echo sprintf($courseLink,urlencode('MBA (Full Time)'),3,'true'); ?>" <?php echo ($course_name == 'MBA (Full Time)' && $search_category_id == 3 ? 'class="active-sublink"' : ''); ?> >
							<?php echo str_replace(" ","&nbsp;",'MBA (Full Time)');?>
						</a>
					</li>
					<li>
						<a href="<?php echo sprintf($courseLink,urlencode('B.E./B.Tech (Full Time)'),2,'true'); ?>" <?php echo ($course_name == 'B.E./B.Tech (Full Time)' && $search_category_id == 2 ? 'class="active-sublink"' : ''); ?> >
							<?php echo str_replace(" ","&nbsp;",'B.E./B.Tech (Full Time)');?>
						</a>
					</li>	
				</ul>
			</div>
		</li>

		<li>
			<a <?php if($course_name == 'National Courses') echo 'class="active"'; ?> id="a_national_non_mr_div" href="/enterprise/enterpriseSearch/index" >Domestic Leads
			</a>
			<div id="national_non_mr_div"  class="cms-sub-menu" <?php echo $main_div_style; ?> >
				<ul>
					<li>
						<a href="/enterprise/enterpriseSearch/index" <?php if($course_name == 'National Courses') echo 'class="active-sublink"'; ?> >
							Domestic Leads
						</a>
					</li>
				</ul>
			</div>
		</li>

	</ul>
</div>

<div id="download_cvs_msg" style="line-height:26px;clear:left">&nbsp;</div>
<!--Start Search Agent -->
<div style="width:100%">
	<div class="float_L" style="width:49%">
		<div style="padding-top:4px">
			<div id="manageLinkOrHeadingDiv" style="display:none;margin-bottom:10px;">
			<?php
			foreach($searchTabs as $tab) {
				if ($course_name==$tab['course_name']) {
					$course_name_tab=urlencode($tab['course_name']);
				}
			}
			?>
            <!-- Manage validateForCMSAdmin js API -->
			<a onclick="validateForCMSAdmin('edit','zero');return false;" href="#"><span class="Fnt14 bld">Manage Your Genies</span></a>
			</div>
			<input type="hidden" id="current_link_url" autocomplete="off" value="/searchAgents/searchAgents/openUpdateSearchAgent/0/10?course_name=<?php echo $course_name_tab; ?>&show=true" />
			<?php if($deliveryMethod == 'normal') { ?>
				<div id="manageHeadingOrLinkDiv"><span class="Fnt14 bld">Manage Your Genie</span></div>
			<?php } else { ?>
				<div id="manageHeadingOrLinkDiv">
					<a href='/searchAgents/searchAgents/openUpdateSearchAgent/0/10'>&lt; Back to manage lead genie</a>
					<div style="padding-top:5px;">
						<span class="Fnt14 bld">Porting Genie</span>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	
	<div class="float_R" style="width:49%">
	<div id="runSearchAgentDiv" style="display:none">
	<?php if($deliveryMethod == 'normal') { ?>
		<div style="text-align:right">
			<span>Run Your Genie &nbsp;</span> <select name="dd_searchagents_list" id="dd_searchagents_list"  style="width:230px;font-size:12px;font-family:Arial">
			<option value="0">Select Genie</option>
			<?php
			foreach($search_agents_array as $key=>$value)
			{
				echo "<option value='".$value[0]."'>".$value[1]."</option>";
			}
			?>
			</select> &nbsp; <input type="button" class="sa_goBtn" onClick="return runsearchAgent(document.getElementById('dd_searchagents_list').value);" value="&nbsp;" />
		</div>
		<div style="text-align:left;padding-left:205px"><div class="errorPlace"><div id="run_search_agent_error" class="errorMsg">&nbsp;</div></div></div>
	<?php } ?>
	</div>
	</div>
	
	<div class="clear_B">&nbsp;</div>
</div>

<script>

var LDBTabs = ['study_abroad_div','national_mr_div','national_non_mr_div'];

var sTodayDateLDBSearch = '<?php echo date("dMy"); ?>';
var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
document.getElementById('manageLinkOrHeadingDiv').style.display="block";
document.getElementById('manageHeadingOrLinkDiv').style.display="none";

function handleLayerHide(e)
{
	showhideDIVOverlay(e);
}

if (window.addEventListener){
  window.addEventListener('click', handleLayerHide, false);
} else if (window.attachEvent){
  document.attachEvent('onclick', handleLayerHide);
}

for(var i=0;i<LDBTabs.length;i++) {
	var tabId = LDBTabs[i];
	$(tabId).style.display = "none";
	$(tabId).onclick = function(e) {
		if(!e) {
			e = window.event;
		}
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
	}
}

function showhideDIVOverlay(e,id)
{
	for(var i=0;i<LDBTabs.length;i++) {
		var tabId = LDBTabs[i];
		if(tabId == id) {
			if(tabId == 'national_non_mr_div' || tabId == 'national_mr_div'){
				$(tabId).style.width = (200)+'px';
			} else{
				$(tabId).style.width = (240)+'px';
			}

			$(tabId).style.display = "block";
			
			$('a_'+tabId).className = 'hover';
		}
		else {
			$(tabId).style.display = "none";
			if(tabId == activeTab) {
				$('a_'+tabId).className = 'active';
			}
			else  {
				$('a_'+tabId).className = '';
			}
		}
	}
	
	if(navigator.userAgent.indexOf('MSIE')>=0) {
		IEOverlayDropdownFix(id);
	}
	
	if(!e) {
		e = window.event;
	}
	e.cancelBubble = true;
	if (e.stopPropagation) e.stopPropagation();
}

function IEOverlayDropdownFix(id)
{
	var dropdowns = document.getElementsByTagName('select');
	for(var i=0;i<dropdowns.length;i++) {
		if(id) {
			dropdowns[i].style.visibility = 'hidden';
		}
		else {
			dropdowns[i].style.visibility = 'visible';
		}
	}
}

</script>
