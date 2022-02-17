<!--Start_TabingLine-->
<style type="text/css">
#cms-sub-tab{border-bottom:1px solid #ececec; width:100%; float:left; padding-bottom:1px}
#cms-sub-tab ul{margin:0; padding:0}
#cms-sub-tab ul li{list-style:none; border-right:1px solid #ececec; float:left; font-size:12px; padding-right:0px; margin-right:1px; position:relative; z-index:99}
#cms-sub-tab ul li a{color:#000000; text-decoration:none; background:none; padding:4px 10px 8px 10px; float:left; outline: none;}
#cms-sub-tab ul li a span{background:url(/public/images/dwn-arrow.gif) 0 7px no-repeat; width:10px; height:17px;
display:inline-block;}
#cms-sub-tab ul li a.active{color:#fff !important; background:#ff6900; box-shadow:2px 2px 2px #c2c2c2;
-moz-box-shadow:2px 2px 2px #c2c2c2; -webkit-box-shadow:2px 2px 2px #c2c2c2; padding:4px 10px 4px 10px;}
#cms-sub-tab ul li a.active span{background:url(/public/images/dwn-arrow-2.gif) 0 7px no-repeat; width:10px;
height:17px; display:inline-block;}
#cms-sub-tab ul li a.hover{color:#000 !important; border:1px solid #a2b7d2; border-bottom:0 none; position:relative;
z-index:99; background:#fff; padding:3px 9px 8px 9px;}
#cms-sub-tab ul li a.hover span{background:url(/public/images/dwn-arrow.gif) 0 7px no-repeat; width:10px; height:17px;
display:inline-block;}
.cms-sub-menu{border:1px solid #a2b7d2; width:260px; position:absolute; padding:10px; left:0; top:28px; z-index:1;
background:#fff; box-shadow:2px 2px 2px #c2c2c2; -moz-box-shadow:2px 2px 2px #c2c2c2; -webkit-box-shadow:2px 2px 2px
#c2c2c2;}
.last-sub-menu{width:280px; left:auto; right:0; box-shadow:-2px 2px 2px #c2c2c2; -moz-box-shadow:-2px 2px 2px #c2c2c2;
-webkit-box-shadow:-2px 2px 2px #c2c2c2;}
.cms-sub-menu ul{width:260px; float:left}
.cms-sub-menu ul li{border:none !important; display:block !important; margin:0 0 1px 0 !important; clear:both;
font-size:11px !important; width:100%}
.cms-sub-menu ul li a{padding:0 !important; color:#0065dc !important; padding:3px !important}
.cms-sub-menu ul li a:hover, .cms-sub-menu ul li a.active-sublink{background:#ebf3ff !important;font-size:10px !important; border:0 none
!important; font-weight:700;font-size:11px; color:#000 !important}
</style>
<!--<div id="cmsSearch_tab" style="padding-left:0">-->
<?php
$course_name = isset($_REQUEST['course_name'])? $_REQUEST['course_name']:'Full Time MBA/PGDM';

/**
 * For study abroad
 */ 
$sa_course = $_REQUEST['sa_course'];
$sa_course_type = $_REQUEST['sa_course_type'];

foreach($searchTabs as $tab) {
/* commented now */
//$class = ($course_name==$tab['course_name'])? "cmsSelectedTab" : "";
if ($course_name==$tab['course_name']) {
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
<?php } ?>
<!--<a style="cursor:pointer" href="/enterprise/shikshaDB/index?course_name=<?php //echo urlencode($tab['course_name']);
?>&show=true" class="<?php //echo $class; ?>"><span><?php //echo str_replace(" ", "&nbsp;", $tab['course_name']);
?></span></a> -->
<?php } ?>
<!--</div>-->
<script>
var LDBTabs = ['study_abroad_div','mgt_div','science_div','it_div','anima_div','hospitality_id','media_id','testprep_id','other_id'];
var activeTab = '';
function showhideDIVOverlay(e,id)
{
	for(var i=0;i<LDBTabs.length;i++) {
		var tabId = LDBTabs[i];
		if(tabId == id) {
			$(tabId).style.display = "block";
			if(tabId == 'study_abroad_div' || tabId == 'mgt_div' || tabId == 'science_div' || tabId == 'other_id') {
				$(tabId).style.width = (530)+'px';
			}
			else {
				$(tabId).style.width = (260)+'px';
			}
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
<?php
// Default course is 'Full time MBA PGDM'
$course_name = isset($_REQUEST['course_name'])? $_REQUEST['course_name']:LDB_FULL_TIME_MBA_PGDM;
// Default all divs will be in hide state
$main_div_style = 'style="display:none"';
$top_link = '';

// Menu start 
?>
<div id="cms-sub-tab">
<ul>

<?php
$courseLink = "/enterprise/shikshaDB/index?course_name=%s&categoryId=%d&show=%s";
$abroadCourseLink = "/enterprise/shikshaDB/index?course_name=%s&sa_course=%s&sa_course_type=%s&show=%s";
require FCPATH.'globalconfig/LDBSearchTabsCoursesList.php';
			

foreach($LDBCourseList as $category_label => $category_data) {

	//if($category_label == 'others' || $category_label == 'studyabroad') continue;
	
	$category_id = $category_data['id'];
	$category_name = $category_data['name'];
	$category_courses = $category_data['courses'];
	$html_a_id = $category_data['html_a_id'];
	$html_div_id = $category_data['html_div_id'];
	
	if ((in_array($course_name, $category_courses) && $search_category_id == $category_id) || ($course_name == 'Study Abroad' && $category_name == 'Study Abroad')) {
		$top_link = 'class="active"';
?>
		<script> activeTab = "<?php echo $html_div_id; ?>"; </script>
<?php
	}
	else {
		$top_link = '';
	}
	
	$num_courses = count($category_courses);
	$column_break = ceil($num_courses/2);
?>
<li>
	<a <?php echo $top_link; ?> id = "<?php echo $html_a_id; ?>" href="javascript:void(0);" onClick="showhideDIVOverlay(event,'<?php echo $html_div_id; ?>');"><?php echo $category_name; ?> <span>&nbsp;</span></a>
	<div id="<?php echo $html_div_id; ?>"  class="cms-sub-menu<?php if($category_label == 'testprep') echo " last-sub-menu"; ?>" <?php echo $main_div_style; ?>>
		<ul>
		<?php
		if($category_label == 'studyabroad') {
                    ?>
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
			</ul><ul>
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
		}
		else {
			$j = 0;
			foreach($category_courses as $category_course) {
			?>
				<li>
					<a href="<?php echo sprintf($courseLink,urlencode($category_course),$category_id,'true'); ?>" <?php echo ($course_name == $category_course  && $search_category_id == $category_id ? 'class="active-sublink"' : ''); ?> >
						<?php echo str_replace(" ","&nbsp;",$category_course);?>
					</a>
				</li>	
			<?php
				$j++;
				if(($category_label == 'science' || $category_label == 'management') && $j == $column_break) {
					echo "</ul><ul>";
				}
			}
		}
		?>
		</ul>
	</div>
</li>	
<?php
}

if (isset($other_array[$course_name]) && $other_array[$course_name] == $search_category_id) {
	$top_link = 'class="active"';
?>
	<script> activeTab = "other_id"; </script>
<?php  
}
else {
  $top_link = '';
}
?>
<li style="border:none;">
	<a <?php echo $top_link; ?> id = "a_other_id" href="javascript:void(0);" onClick="showhideDIVOverlay(event,'other_id');">Others <span>&nbsp;</span></a>
	<div id="other_id"  class="cms-sub-menu last-sub-menu" <?php echo $main_div_style; ?>>
		<ul>
		<?php
		$j = 0;
		foreach($other_array as $other_course => $category_id) {
		?>
			<li>
				<a href="<?php echo sprintf($courseLink,urlencode($other_course),$category_id,'true'); ?>" <?php echo ($course_name == $other_course && $category_id == $search_category_id ? 'class="active-sublink"' : ''); ?> >
					<?php echo str_replace(" ","&nbsp;",$other_course);?>
				</a>
			</li>	
		<?php
			$j++;
			if($j == 6) {
				echo "</ul><ul>";
			}
		}
		?>
		</ul>
	</div>
</li>
</ul>
</div>

<!--End_TabingLine-->
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
<!--End Search Agent -->
<script>
var sTodayDateLDBSearch = '<?php echo date("dMy"); ?>';
var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
document.getElementById('manageLinkOrHeadingDiv').style.display="block";
document.getElementById('manageHeadingOrLinkDiv').style.display="none";

function handleLayerHide(e)
{
	showhideDIVOverlay(e);
}
i
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
</script>
