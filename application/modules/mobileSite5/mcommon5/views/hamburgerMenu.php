<?php
global $mbaExamPageLinks;
global $engineeringExamPageLinks;
$this->load->library('category_list_client');
global $tabsContentByCategory;
$tabsContentByCategory = $this->category_list_client->getTabsContentByCategory();
$this->load->config('CP/CollegePredictorConfig',TRUE);
$settings = $this->config->item('settings','CollegePredictorConfig');
$exams = $settings['CPEXAMS'];

$this->load->config('RP/RankPredictorConfig',TRUE);
$rankPredictorSetting = $this->config->item('settings','RankPredictorConfig');
$rankPredictorArray = $rankPredictorSetting['RPEXAMS'];

$this->load->config('mcommon5/mobi_config');
$helplineUrl     = $this->config->item('helplineUrl');
$articleUrl      =  $this->config->item('articleUrl');
$aboutusUrl      = $this->config->item('aboutusUrl');
$rankingPageList = array (
                    array('title' => 'MBA', 'url' => SHIKSHA_HOME . '/mba/ranking/top-mba-colleges-india/2-2-0-0-0'),
                    array('title' => 'BE/BTech',  'url' => SHIKSHA_HOME . '/top-engineering-colleges-in-india-rankingpage-44-2-0-0-0'),
                    array('title' => 'Arts',  'url' => SHIKSHA_HOME . '/top-arts-colleges-in-india-rankingpage-95-2-0-0-0'),
                    array('title' => 'BBA',  'url' => SHIKSHA_HOME . '/top-bba-colleges-in-india-rankingpage-93-2-0-0-0'),
                    array('title' => 'BCA',  'url' => SHIKSHA_HOME . '/top-bca-colleges-in-india-rankingpage-96-2-0-0-0'),
                    array('title' => 'Commerce',  'url' => SHIKSHA_HOME . '/top-commerce-colleges-in-india-rankingpage-97-2-0-0-0'),
                    array('title' => 'Executive MBA', 'url' => SHIKSHA_HOME . '/mba/ranking/top-executive-mba-colleges-india/18-2-0-0-0'),
                    array('title' => 'Fashion Designing',  'url' => SHIKSHA_HOME . '/top-fashion-designing-colleges-in-india-rankingpage-94-2-0-0-0'),
                    array('title' => 'Hotel Management',  'url' => SHIKSHA_HOME . '/top-hotel-management-colleges-in-india-rankingpage-98-2-0-0-0'),
                    array('title' => 'Law',  'url' => SHIKSHA_HOME . '/top-llb-colleges-in-india-rankingpage-56-2-0-0-0'),
                    array('title' => 'Mass Communication',  'url' => SHIKSHA_HOME . '/top-mass-communication-colleges-in-india-rankingpage-99-2-0-0-0'),
                    array('title' => 'Medical',  'url' => SHIKSHA_HOME . '/top-medical-colleges-in-india-rankingpage-100-2-0-0-0'),
                    array('title' => 'Science',  'url' => SHIKSHA_HOME . '/top-science-colleges-in-india-rankingpage-101-2-0-0-0')
                );
		$mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
                $screenWidth = $mobile_details['resolution_width'];
                $screenHeight = $mobile_details['resolution_height'];
?>
<nav id="mypanel" data-position="left" data-display="reveal" data-role="panel" data-position-fixed="true" data-swipe-close="false" class="mm-menu mm-horizontal mm-offcanvas mm-current mm-opened" style="height: <?php echo $screenHeight.'px;';?>;overflow: auto;">
	<div data-enhance="false">
	<ul id="mm-m0-p0" class="mm-list">
		<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_HOME')"><a href="<?php echo SHIKSHA_HOME;?>">Home</a></li>
		<li  class="courses" onclick="changeMenu('1','0');trackEventByGAMobile('HTML5_HAMBURGER_COURSES')">
			<a class="mm-subopen" href="#mm-m0-p1"></a>
			<a id="courses">Courses</a>
		</li>
		<li onclick="changeMenu('exam','0');trackEventByGAMobile('HTML5_HAMBURGER_EXAMS')">
			<a class="mm-subopen" href="#mm-m0-pexam"></a>
			<a id="exams">EXAMS</a>
		</li>
		<li onclick="window.location='<?php echo $articleUrl;?>';setCookie('articleType','allArticles',0,'/',COOKIEDOMAIN);trackEventByGAMobile('HTML5_HAMBURGER_NEWS_ARTICLES')"><a href="javascript:void(0);">NEWS & ARTICLES</a></li>
		<li onclick="changeMenu('collegerankings','0');trackEventByGAMobile('HTML5_HAMBURGER_TOP_RANKED_COLLEGES')">
			<a class="mm-subopen" href="#mm-m0-collegerankings"></a>
			<a>COLLEGE RANKINGS</a>			
		</li>
                <li onclick="trackEventByGAMobile('HTML5_HAMBURGER_ASK_AND_ANSWER')">
                        <a href="<?=SHIKSHA_ASK_HOME?>">Ask & Answer</a>
                </li>
		<?php if(IIM_CALL_INTERLINKING_FLAG == 'true'){?>
		<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_IIM_CALL_PREDICTOR')">
			<a href="<?=SHIKSHA_HOME?>/mba/resources/iim-call-predictor">IIM CALL PREDICTOR<sup>New</sup></a>
		</li>
		<?php } ?>

		<?php if(MENTORSHIP_PROGRAM_FLAG == 'true'){?>
		<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_GET_A_MENTOR')">
			<a href="<?=SHIKSHA_HOME?>/mentors-engineering-exams-colleges-courses">GET A MENTOR</a>
		</li>
		<?php } ?>
		<li onclick="changeMenu('collegepredictorexams','0');trackEventByGAMobile('HTML5_HAMBURGER_COLLEGE_PREDICTOR')">
			<a class="mm-subopen" href="#mm-m0-collegepredictorexams"></a>
			<a>COLLEGE PREDICTOR</a>
		</li>
		<li onclick="changeMenu('rankpredictorexams','0');trackEventByGAMobile('HTML5_HAMBURGER_RANK_PREDICTOR')" style="position: relative;">
			<a class="mm-subopen" href="#mm-m0-rankpredictorexams"></a>
			<a>RANK PREDICTOR</a>
		</li>
		<li onclick="changeMenu('studenttools','0');trackEventByGAMobile('HTML5_HAMBURGER_STUDENT_TOOLS')">
			<a class="mm-subopen" href="#mm-m0-pstudenttools"></a>
			<a id="exams">STUDENT TOOLS</a>
		</li>
		<li onclick="changeMenu('examcalendar','0');trackEventByGAMobile('HTML5_HAMBURGER_EXAM_CALENDAR')">
			<a class="mm-subopen" href="#mm-m0-pexamcalendar"></a>
			<a id="examcalendar">EXAM CALENDAR</a>
		</li>
		<li onclick="changeMenu('collegereview','0');trackEventByGAMobile('HTML5_HAMBURGER_COLLEGE_REVIEWS')">
			<a class="mm-subopen" href="#mm-m0-collegereview"></a>
			<a id="collegereview">College Reviews</a>
		</li>
		
		<!--<li onclick="window.location='<?php echo SHIKSHA_HOME;?>/jee-main-rank-predictor';trackEventByGAMobile('HTML5_HAMBURGER_RANK_PREDICTOR')"><a href="javascript:void(0);">RANK PREDICTOR</a></li>-->
		<!--li onclick="window.location='<?php echo SHIKSHA_HOME;?>/compare-colleges';trackEventByGAMobile('HTML5_HAMBURGER_COMPARE_COLLEGE')"><a href="javascript:void(0);">COMPARE COLLEGES</a></li>
		<li onclick="window.location='<?php echo SHIKSHA_MANAGEMENT_HOME;?>/best-colleges-for-jobs-based-on-mba-alumni-data';trackEventByGAMobile('HTML5_HAMBURGER_CAREER_COMPASS')"><a href="javascript:void(0);">CAREER COMPASS</a></li-->
		
		
		<li onclick="window.location='<?php echo $helplineUrl;?>' ; trackEventByGAMobile('HTML5_HAMBURGER_STUDENT_HELPLINE')"><a href="javascript:void(0);">STUDENT HELPLINE</a></li>
		<li id="hAboutUs" onclick="window.location='<?php echo $aboutusUrl;?>' ; trackEventByGAMobile('HTML5_HAMBURGER_ABOUT_US')"><a href="javascript:void(0);">ABOUT US</a></li>
		<li id="hCommunityGuidelines" onclick="window.location='<?php echo $communityGuidelineUrl;?>' ; trackEventByGAMobile('HTML5_HAMBURGER_COMMUNITY_GUIDELINES')"><a href="javascript:void(0);">Community Guidelines</a></li>
		<li id="hUserPointSystem" onclick="window.location='<?php echo $userPointSystemUrl;?>' ; trackEventByGAMobile('HTML5_HAMBURGER_USER_POINT_SYSTEM')"><a href="javascript:void(0);">User Point System</a></li>

	</ul>
	
	<?php
	$mainCategory = array('Courses');
	?>
	<div id="mm-m0-p1" style="display: none;" class="otherMenu">
	<ul style="padding: 0px" class="mm-list">
		<li><a href="#mm-m0-p0" onclick="goToPreviousMenu('0','1');">Menu</a></li>
		<li class="mm-subtitle" onclick="goToPreviousMenu('0','1');"><a class="mm-subclose" href="#mm-m0-p0"><?php echo $mainCategory[0];?></a></li>
	</ul>
	<ul class="level-2-content mm-list">
		<li style="color:#566ec2">
			<span>
				<small style="display:block; font-size:80%; color:#6d6d6d;">Quick Links</small>
				<span onclick="trackEventByGAMobile('HTML5_HAMBURGER_QUICK_LINK_FULL_TIME_MBA');window.location='<?php echo SHIKSHA_HOME.'/mba-courses-in-india-ctpg'?>'" style="cursor: pointer;">FULL TIME MBA</span> | <span  onclick="trackEventByGAMobile('HTML5_HAMBURGER_QUICK_LINK_BE_BTECH');window.location='<?php echo SHIKSHA_HOME.'/be-btech-courses-in-india-ctpg'?>'" style="cursor: pointer;">B.TECH</span>
			</span>
		</li>
		<?php
		foreach($tabsContentByCategory as $key=>$value){ ?>
		<li onclick="changeMenu('<?php echo $key;?>','1');trackEventByGAMobile('HTML5_HAMBURGER_<?php echo strtoupper(preg_replace('/[_]+/','_',preg_replace('/(\s)|&|,/','_',$value['name']))); ?>');">
			<a class="mm-subopen" href="#mm-m0-p<?php echo $key;?>"></a>
			<a><?php echo strtoupper($value['name']);?></a>
		</li>
		<?php } ?>
	</ul>
	</div>
	<?php
	foreach($tabsContentByCategory as $key=>$value){ ?>
	<div id="mm-m0-p<?php echo $key;?>" style="display: none;" class="otherMenu">
		<ul style="padding: 0px;" class="mm-list">
			<li><a href="#mm-m0-p0" onclick="goToPreviousMenu('0','<?php echo $key;?>');">Menu</a></li>
			<li class="mm-subtitle" onclick="goToPreviousMenu('1','<?php echo $key;?>');"><a class="mm-subclose" href="#mm-m0-p1" style="width:85%"><?php echo strtoupper($value['name']);?></a></li>
		</ul>
		<ul class="level-2-content mm-list">
			<?php foreach($value['subcats']as $k=>$v){ ?>
			<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_<?php echo strtoupper(preg_replace('/[_]+/','_',preg_replace('/(\s)|&|,|\+|\/|-/','_',$v['name']))); ?>');">
				<a href="<?php echo $v['url'];?>"><?php echo $v['name'];?></a>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
	<div id="mm-m0-pexam" style="display: none;" class="otherMenu">
		<ul style="padding: 0px;" class="mm-list" >
			<li onclick="goToPreviousMenu('0','exam')"><a href="#mm-m0-p0">Menu</a></li>
			<li class="mm-subtitle" onclick="goToPreviousMenu('0','exam')"><a class="mm-subclose" href="#mm-m0-p0">EXAMS</a></li>
		</ul>
		<ul class="level-2-content mm-list">
			<li onclick="changeMenu('managementexams','exam');trackEventByGAMobile('HTML5_HAMBURGER_MANAGEMENT_EXAMS');">
				<a class="mm-subopen" href="#mm-m0-managementexams"></a>
				<a id="management">MANAGEMENT EXAMS</a>
			</li>
			<li onclick="changeMenu('enginneringexams','exam');trackEventByGAMobile('HTML5_HAMBURGER_ENGINEERING_EXAMS');">
				<a class="mm-subopen" href="#mm-m0-enginneringexams"></a>
				<a id="management">ENGINEERING EXAMS</a>
			</li>
		</ul>
	</div>
	<div id="mm-m0-pstudenttools" style="display: none;" class="otherMenu">
		<ul style="padding: 0px;" class="mm-list" >
			<li onclick="goToPreviousMenu('0','studenttools')"><a href="#mm-m0-p0">Menu</a></li>
			<li class="mm-subtitle" onclick="goToPreviousMenu('0','studenttools')"><a class="mm-subclose" href="#mm-m0-p0">STUDENT TOOLS</a></li>
		</ul>
		<ul class="level-2-content mm-list">
		<li onclick="window.location='<?php echo SHIKSHA_HOME;?>/compare-colleges';trackEventByGAMobile('HTML5_HAMBURGER_COMPARE_COLLEGE')"><a href="javascript:void(0);">COMPARE COLLEGES</a></li>
		<li onclick="window.location='<?php echo SHIKSHA_HOME;?>/mba/resources/mba-alumni-data';trackEventByGAMobile('HTML5_HAMBURGER_CAREER_COMPASS')"><a href="javascript:void(0);">CAREER COMPASS</a></li>
		</ul>
		<!--ul class="level-2-content mm-list">
			<li onclick="changeMenu('collegepredictorexams','studenttools');trackEventByGAMobile('HTML5_HAMBURGER_COLLEGE_PREDICTOR');">
				<a class="mm-subopen" href="#mm-m0-collegepredictorexams"></a>
				<a id="collegepredictorexams">COLLEGE PREDICTOR</a>
			</li>
			<li onclick="changeMenu('rankpredictorexams','studenttools');trackEventByGAMobile('HTML5_HAMBURGER_RANK_PREDICTOR');">
				<a class="mm-subopen" href="#mm-m0-rankpredictorexams"></a>
				<a id="rankpredictorexams">RANK PREDICTOR</a>
				<span class="menu-new-badge">New</span>
			</li>
		</ul-->
	</div>
	<div id="mm-m0-pexamcalendar" style="display: none;" class="otherMenu">
		<ul style="padding: 0px;" class="mm-list" >
			<li onclick="goToPreviousMenu('0','examcalendar')"><a href="#mm-m0-p0">Menu</a></li>
			<li class="mm-subtitle" onclick="goToPreviousMenu('0','examcalendar')"><a class="mm-subclose" href="#mm-m0-p0">EXAM CALENDAR</a></li>
		</ul>
		<ul class="level-2-content mm-list">
			<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_ENGINEERING_EXAM_CALENDAR');"><a href="<?=SHIKSHA_HOME.'/engineering-exams-dates'?>">ENGINEERING EXAM CALENDAR</a></li>
			<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_MBA_EXAM_CALENDAR');"><a href="<?=SHIKSHA_MBA_CALENDAR;?>">MBA EXAM CALENDAR</a></li>
		</ul>
	</div>
	<div id="mm-m0-pcollegereview" style="display: none;" class="otherMenu">
		<ul style="padding: 0px;" class="mm-list" >
			<li onclick="goToPreviousMenu('0','collegereview')"><a href="#mm-m0-p0">Menu</a></li>
			<li class="mm-subtitle" onclick="goToPreviousMenu('0','collegereview')"><a class="mm-subclose" href="#mm-m0-p0">EXAM CALENDAR</a></li>
		</ul>
		<ul class="level-2-content mm-list">
			<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_COLLEGE_REVIEWS');"><a href="<?=SHIKSHA_HOME.'/'.MBA_COLLEGE_REVIEW?>">MBA COLLEGE REVIEWS</a></li>
			<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_COLLEGE_REVIEWS');"><a href="<?=SHIKSHA_HOME.'/'.ENGINEERING_COLLEGE_REVIEW?>">ENGINEERING COLLEGE REVIEWS</a></li>
		</ul>
	</div>
	<div id="mm-m0-pmanagementexams" style="display: none;" class="otherMenu">
		<ul style="padding: 0px;" class="mm-list">
			<li onclick="goToPreviousMenu('0','managementexams');"><a href="#mm-m0-p0">Menu</a></li>
			<li class="mm-subtitle" onclick="goToPreviousMenu('exam','managementexams');"><a class="mm-subclose" href="#mm-m0-pexam">MANAGEMENT EXAMS</a></li>
		</ul>
		<ul class="level-2-content mm-list">
			<?php
			foreach($mbaExamPageLinks[1] as $key=>$value){
			?>
			<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_MANAGENT_EXAMS_<?php echo $value['name'];?>');"><a href="<?php echo $value['url'];?>"><?php echo $value['name'];?></a></li>
			<?php } ?>
			<?php
			foreach($mbaExamPageLinks[2] as $key=>$value){
			?>
			<li  onclick="trackEventByGAMobile('HTML5_HAMBURGER_MANAGENT_EXAMS_<?php echo $value['name'];?>');"><a href="<?php echo $value['url'];?>"><?php echo $value['name'];?></a></li>
			<?php } ?>
		</ul>
	</div>
	<div id="mm-m0-penginneringexams" style="display: none;" class="otherMenu">
		<ul style="padding: 0px;" class="mm-list">
			<li onclick="goToPreviousMenu('0','enginneringexams');"><a href="#mm-m0-p0">Menu</a></li>
			<li class="mm-subtitle" onclick="goToPreviousMenu('exam','enginneringexams')"><a class="mm-subclose" href="#mm-m0-pexam">ENGINEERING EXAMS</a></li>
		</ul>
		<ul class="level-2-content mm-list">
			<?php
			foreach($engineeringExamPageLinks[1] as $key=>$value){
			?>
			<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_ENGINEERING_EXAMS_<?php echo strtoupper(preg_replace('/(\s)/','_',$value['name']));?>');"><a href="<?php echo $value['url'];?>"><?php echo $value['name'];?></a></li>
			<?php } ?>
			<?php
			foreach($engineeringExamPageLinks[2] as $key=>$value){
			?>
			<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_ENGINEERING_EXAMS_<?php echo $value['name'];?>');"><a href="<?php echo $value['url'];?>"><?php echo $value['name'];?></a></li>
			<?php } ?>
		</ul>
	</div>
	<div id="mm-m0-pcollegepredictorexams" style="display: none;" class="otherMenu"> 
		<ul style="padding: 0px;" class="mm-list">
			<li onclick="goToPreviousMenu('0','collegepredictorexams');"><a href="#mm-m0-p0">Menu</a></li>
			<li class="mm-subtitle" onclick="goToPreviousMenu('0','collegepredictorexams');"><a class="mm-subclose" href="#mm-m0-p0">COLLEGE PREDICTOR</a></li>
		</ul>
		<ul class="level-2-content mm-list">
			<?php
			foreach ($exams as $examName => $examData)
			{ 
				$configExamName = strtoupper($examName);
				$configExamArray = $settings[$configExamName];
				$dbExamName = $configExamArray['examName'];
			?>
				<li><a href="javascript:void(0);" onclick="trackEventByGAMobile('HTML5_HAMBURGER_<?php echo strtoupper(preg_replace('/(\s)/','_',$examData['name']));?>');cpRedirect('<?php echo $examData['collegeUrl'];?>','<?php echo $dbExamName;?>')"><?php echo $examData['name'];?></a></li>
			<?php
			}
			?>
		</ul>
	</div>
	<div id="mm-m0-pcollegerankings" style="display: none;" class="otherMenu">
		<ul style="padding: 0px;" class="mm-list">
			<li onclick="goToPreviousMenu('0','collegerankings');"><a href="#mm-m0-p0">Menu</a></li>
			<li class="mm-subtitle" onclick="goToPreviousMenu('0','collegerankings');"><a class="mm-subclose" href="#mm-m0-p0">COLLEGE RANKINGS</a></li>
		</ul>
		<ul class="level-2-content mm-list">
			<?php
			foreach ($rankingPageList as $key => $value)
			{ 
			?>
				<li onclick="trackEventByGAMobile('HTML5_HAMBURGER_<?php echo strtoupper(preg_replace('/(\s)|&|,|\+|\(|\)|\/|-/','_',$value['title']));?>');"><a href="<?php echo $value['url'];?>"><?php echo $value['title'];?></a></li>
			<?php
			}
			?>
		</ul>
	</div>
	
	<div id="mm-m0-prankpredictorexams" style="display: none;" class="otherMenu"> 
		<ul style="padding: 0px;" class="mm-list">
			<li onclick="goToPreviousMenu('0','rankpredictorexams');"><a href="#mm-m0-p0">Menu</a></li>
			<li class="mm-subtitle" onclick="goToPreviousMenu('0','rankpredictorexams');" style="position: relative"><a class="mm-subclose" href="#mm-m0-p0">RANK PREDICTOR</a>
			</li>
		</ul>
		<ul class="level-2-content mm-list">
			<?php
			foreach ($rankPredictorArray as $rankPredictorArrays => $rankPredictor)
			{ 
				$configRank = strtoupper($rankPredictorArrays);
				$configRankArray = $settings[$configRank];
				$dbExamName = $configRankArray['examName'];
			?>
				<li><a href="javascript:void(0);" onclick="trackEventByGAMobile('HTML5_HAMBURGER_<?php echo strtoupper(preg_replace('/(\s)/','_',$examData['name']));?>');cpRedirect('<?php echo $rankPredictor['url'];?>','<?php echo $dbExamName;?>')"><?php echo $rankPredictor['name'];?></a></li>
			<?php
			}
			?>
		</ul>
	</div>
	</div>
</nav>
<?php global $isHamburgerMenu;
$isHamburgerMenu = true; ?>
