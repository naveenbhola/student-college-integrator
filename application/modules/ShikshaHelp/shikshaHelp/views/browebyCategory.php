<?php  
		$headerComponents = array(
						//'css'	=>	array('raised_all','mainStyle','header'),
						'css' => array('static'),
						'jsFooter'=>    array('common'),
						'title'	=>	'Shiksha.com Browse Education Career Information Search Institute Engineering MBA Medical Study Abroad - Forum Community',
						'tabName' =>	'Shiksha.com Browse Education Career Information Search Institute Engineering MBA Medical Study Abroad - Forum Community',
						'taburl' =>  site_url(),
						'metaKeywords'	=>'Shiksha, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
                        'metaDescription' => 'Browse colleges and universities in India & abroad by career options and get information on education & career prospects. Search Shiksha.com Now! Find list of engineering, MBA, Medical colleges, university, institutes, courses, schools. Find info on Foreign University, question and answer in education and career forum. Ask the education and career counselors.',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'product'	=>'Site Map',
                                                'callShiksha'=>1
					);
		$this->load->view('common/homepage', $headerComponents);

		$eduInfo = array('Study Abroad' => array('url'=>SHIKSHA_STUDYABROAD_HOME),
				 'Test Preparation'  => array('url'=>SHIKSHA_TESTPREP_HOME),
				 'Events' => array('url'=>SHIKSHA_EVENTS_HOME),
				 'Ask & Answer' => array('url'=>SHIKSHA_ASK_HOME)
				 	);
		$networkInfo = array('College Groups' => array('url'=>SHIKSHA_GROUPS_HOME),
				 'School Groups'  => array('url'=>SHIKSHA_SCHOOL_HOME)
				 	);
		$personalInfo = array('Messages' => array('url'=>SHIKSHA_HOME . '/mail/Mail/mailbox'),
				 'Alerts'  => array('url'=>SHIKSHA_HOME .'/alerts/Alerts/alertsHome')
				 	);
		$loggedInUserId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	
?>
<div class="mar_full_10p normaltxt_11p_blk">
<div>Browse Section</div>
<div class="lineSpace_5">&nbsp;</div>
<?php 
	$catUrl = SHIKSHA_HOME."/shikshaHelp/ShikshaHelp/browseByCategory/browse-colleges-career-option-listings";
	$secondLevelUrl = SHIKSHA_HOME."/shikshaHelp/ShikshaHelp/browseByColleges";
?>
<!--Start_Explore Colleges by Career Option-->	
	<div class="lineSpace_10">&nbsp;</div>
	<div><h2><span class="OrgangeFont fontSize_14p bld">Browse Institutes - Career Options</span></h2></div>
	<div class="lineSpace_5">&nbsp;</div>
	<div class="grayLine"></div>
	<div class="lineSpace_10">&nbsp;</div>

	<div class="row">
		<?php 
			foreach($categoryParentMap as $key => $record){
		?>
		<div class="float_L" style="width:46%">
			<div class="mar_bottom_10p">
				<div class="">
					<img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" />
					<?php 
						$catUrl = SHIKSHA_HOME."/shikshaHelp/ShikshaHelp/browseByCountry/".strtolower($record['url']);
					?>
					<a href="<?php echo $catUrl; ?>/All/All/All/colleges-university-institute" class="fontSize_12p"><?php echo $key; ?></a>					
					<div style="padding-left:12px; padding-right:15px;">
							<?php
							    $seperator = '';
							    global $courseTree;
								foreach($courseTree as $course) {
									if($course['category_id'] == $record['id']){
							?>
								<?=$seperator?> <a href="<?php echo getSeoUrlCourse(constant('SHIKSHA_'.strtoupper($record['url']).'_HOME'),$record['url'],$course['url']); ?>"><?=$course['course_title']?></a>
						    <?php $seperator = ', '; }} ?>
							<?php
							    
								foreach($categoryMap as $categoryMapElementId => $categoryMapElement) {
									$categoryMapElementName = $categoryMapElement['categoryName'];
									if($categoryMapElement['parentId'] == $record['id']) {
										$subCatUrl = $catUrl .'/All/'. $categoryMapElement['categoryUrlName'] .'/All/';
										$subCatUrl = str_replace('/-','',$subCatUrl);
										$subCatUrl = str_replace('&','',$subCatUrl);
										$subCatUrl = str_replace(',','',$subCatUrl);
										$subCatUrl = str_replace('--','-',$subCatUrl);
                                        $subCatUrl .= 'colleges-university-institute';
										echo $seperator . '<a href="'. $subCatUrl .'" class="blackFont" style="font-size:11px">'.$categoryMapElementName .'</a>';
										$seperator = ', ';
									}
								}
							?>
					</div>				
				</div>			
			</div>
		</div>	
		<?php
			 }
		?>
		<div class="clear_L"></div>
	</div>
<!--End_Explore Colleges by Career Option-->	

<?php 
	$countrytUrl = SHIKSHA_HOME."/shikshaHelp/ShikshaHelp/browseByColleges/";
?>
<?php
$countryIndia = array(
	'278' => 'Bangalore', 
	'74' => 'Delhi', 
	'151' => 'Mumbai', 
	'64' => 'Chennai', 
	'702' => 'Hyderabad', 
	'174' => 'Pune', 
	'67' => 'Coimbatore', 
	'916' => 'Punjab', 
	'30' => 'Ahmedabad', 
	'130' => 'Kolkata', 
	'156' => 'Nagpur', 
	'161' => 'Noida', 
	'63' => 'Chandigarh', 
	'109' => 'Jaipur', 
	'1359' => 'Calcutta', 
	'138' => 'Lucknow', 
	'55' => 'Bhopal', 
	'153' => 'Mysore', 
	'106' => 'Indore', 
	'87' => 'Ghaziabad', 
	'45' => 'Aurangabad', 
	'193' => 'Tamil Nadu', 
	'143' => 'Mangalore', 
	'141' => 'Madurai', 
	'171' => 'Patna', 
	'912' => 'Bhubaneswar', 
	'40' => 'Andhra Pradesh', 
	'122' => 'Kanpur', 
	'29' => 'Agra', 
	'73' => 'Dehradun' 
);

$countryCanada = array(
	'412' => 'Esslingen', 
	'400' => 'Bonn', 
	'397' => 'Berlin', 
	'420' => 'Hamburg', 
	'408' => 'Dresden', 
	'415' => 'Freiburg', 
	'399' => 'Bochum', 
	'429' => 'Leipzig', 
	'398' => 'Bielefeld', 
	'417' => 'Giessen', 
	'433' => 'Munich', 
	'405' => 'Dusseldorf', 
	'409' => 'Duisburg And Essen', 
	'441' => 'Stuttgart', 
	'430' => 'Mannheim', 
	'401' => 'Bremen', 
	'426' => 'Karlsruhe', 
	'394' => 'Aachen'
);

$countrySingapore = array(
	'695' => 'Alexandra Road', 
	'691' => 'Beach Road', 
	'1076' => 'Bencoolen Street', 
	'693' => 'Clementi Road', 
	'696' => 'German Centre', 
	'26' => 'Johr Bahru', 
	'965' => 'Middle Road', 
	'689' => 'Nanyang Avenue', 
	'23' => 'Singapore', 
	'694' => 'Spring'
);

$countryUSA = array(
	'459' => 'Atlanta', 
	'460' => 'Baltimore', 
	'9261' => 'Baton Rouge', 
	'18' => 'Boston', 
	'8351' => 'Brooklyn', 
	'8' => 'Chicago', 
	'8362' => 'Cincinnati', 
	'498' => 'Columbus', 
	'9787' => 'Denver', 
	'9225' => 'Indianapolis', 
	'8771' => 'Jacksonville', 
	'8199' => 'Kansas City', 
	'9610' => 'Los Angeles', 
	'1065' => 'Louisville', 
	'580' => 'Memphis', 
	'8591' => 'Miami', 
	'593' => 'Nashville', 
	'10' => 'New York', 
	'8982' => 'Orlando', 
	'236' => 'Philadelphia', 
	'615' => 'Phoenix', 
	'617' => 'Pittsburgh', 
	'7819' => 'Portland', 
	'9670' => 'Sacramento', 
	'8227' => 'Saint Louis', 
	'9676' => 'San Diego', 
	'9678' => 'San Francisco', 
	'9008' => 'Tampa', 
	'8614' => 'Tulsa', 
	'9479' => 'Washington'
);

$countryqatar = array(
	'10295' => 'Doha' 
);

$countryUAE = array(
	'10293' => 'Abu Dhabi',
	'10294' => 'Dubai',
	'10292' => 'Sharjah'
);

$countrySaudiArabia = array(
	'10297' => 'Jeddah', 
	'10296' => 'Riyadh' 
);
$countryAustralia = array(
	'284' => 'Acton', 
	'6' => 'Adelaide', 
	'287' => 'Albury-wodonga', 
	'288' => 'Alice Springs', 
	'257' => 'Ballarat', 
	'25' => 'Brisbane', 
	'228' => 'Brisbane Queensland', 
	'295' => 'Building 51', 
	'297' => 'Bundaberg', 
	'298' => 'Burwood', 
	'246' => 'Callaghan', 
	'7' => 'Canberra', 
	'231' => 'Caulfield East', 
	'304' => 'Clayton', 
	'305' => 'Coffs Harbour', 
	'312' => 'Gold Coast', 
	'247' => 'Hawthorn, Victoria', 
	'316' => 'Hobart', 
	'319' => 'Launceston', 
	'320' => 'Lismore', 
	'22' => 'Melbourne', 
	'267' => 'Newcastle', 
	'24' => 'Perth', 
	'283' => 'Queensland', 
	'5' => 'Sydney', 
	'342' => 'Townsville', 
	'1069' => 'Varsity Lakes Qld', 
	'232' => 'Victiria', 
	'272' => 'Victoria', 
	'221' => 'Wollongong'
);

$countryNewzealand = array(
	'687' => 'Auckland', 
	'928' => 'Blenheim', 
	'698' => 'Canterbury', 
	'690' => 'Christchurch', 
	'686' => 'Dunedin', 
	'966' => 'Gisborne', 
	'1062' => 'Greymouth', 
	'697' => 'Hamilton', 
	'954' => 'Hawkes Bay', 
	'962' => 'Invercargill', 
	'920' => 'New Plymouth', 
	'1060' => 'Otaki', 
	'692' => 'Palmerston North', 
	'968' => 'Queenstown', 
	'953' => 'Tauranga', 
	'952' => 'Timaru', 
	'919' => 'Wellington', 
	'960' => 'Whangarei'
);

$countryUK = array(
	'218' => 'Bangor', 
	'254' => 'Bedfordshire', 
	'349' => 'Belfast', 
	'14' => 'Birmingham', 
	'274' => 'Brighton', 
	'17' => 'Bristol', 
	'16' => 'Cambridge', 
	'265' => 'Cardiff', 
	'220' => 'Coventry', 
	'252' => 'Dorset', 
	'358' => 'Edinburgh', 
	'934' => 'Essex', 
	'235' => 'Glasgow', 
	'258' => 'Hampshire', 
	'251' => 'Lancashire', 
	'250' => 'Lancaster', 
	'225' => 'Leicester', 
	'234' => 'Lincoln', 
	'366' => 'Liverpool', 
	'13' => 'London', 
	'249' => 'Manchester', 
	'10162' => 'Northern Ireland', 
	'371' => 'Norwich', 
	'277' => 'Scotland', 
	'262' => 'Sheffield', 
	'955' => 'Staffordshire', 
	'241' => 'Surrey', 
	'1084' => 'Tyne', 
	'1088' => 'Wales', 
	'256' => 'York'
);

$countryGermany = array(
	'394' => 'Aachen', 
	'397' => 'Berlin', 
	'398' => 'Bielefeld', 
	'399' => 'Bochum', 
	'400' => 'Bonn', 
	'401' => 'Bremen', 
	'408' => 'Dresden', 
	'409' => 'Duisburg And Essen', 
	'405' => 'Dusseldorf', 
	'412' => 'Esslingen', 
	'415' => 'Freiburg', 
	'417' => 'Giessen', 
	'420' => 'Hamburg', 
	'426' => 'Karlsruhe', 
	'429' => 'Leipzig', 
	'430' => 'Mannheim', 
	'433' => 'Munich', 
	'441' => 'Stuttgart'
);
$countryMalaysia = array(
'10249' => 'Johor','10250' => 'Kedah','10251' => 'Kelantan','10252' => 'Kuala Lumpur','10253' => 'Labuan','10254' => 'Melaka','10255' => 'Negeri Sembilan','10256' => 'Pahang','10257' => 'Penang','10258' => 'Perak','10259' => 'Perlis','10260' => 'Putrajaya','10261' => 'Sabah','10262' => 'Sarawak','10263' => 'Selangor','10264' => 'Terengganu'
);
$countrySweden = array(
    '10268' => 'Goteborg','10266' => 'Lund','10265' => 'Stockholm','10267' => 'Uppsala'
);
$countryFrance = array(
    '10271' => 'Lyon','10269' => 'Paris','10270' => 'Strasbourg','10272' => 'Talence'
);
$countrySwitzerland = array(
    '10274' => 'Basel','10276' => 'Bern','10275' => 'Geneva','10273' => 'Zurich'
);
$countryDenmark = array(
'10282' => 'Aarhus','10281' => 'Copenhagen'    
);
$countrySpain = array(
    '10283' => 'Barcelona','10284' => 'Madrid'
);
$countryRussia = array(
    '10288' => 'Kazan','10285' => 'Moscow','10291' => 'Novosibirsk','10289' => 'Rostov-on-Don','10286' => 'Saint Petersburg','10290' => 'Tomsk','10287' => 'Vladivostok'
);
$countryHolland = array(
    '10278' => 'Amsterdam','10280' => 'Leiden','10279' => 'Rotterdam','10277' => 'Utrecht'
);
$countryIreland = array(
    '10298' => 'Dublin','10299' => 'Cork','10300' => 'Dingle','10301' => 'Doolin','10302' => 'Galway','10303' => 'Kilkenny','10304' => 'Kinsale',
    '10305' => 'Limerick','10306' => 'Sligo','10307' => 'Waterford');
?>
<!--Start_Explore Colleges by Career Countries-->	
	<div class="lineSpace_20">&nbsp;</div>
	<div><h2><span class="OrgangeFont fontSize_14p bld">Browse by Location</span></h2></div>
	<div class="lineSpace_5">&nbsp;</div>
	<div class="grayLine"></div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="row">	
		<div class="float_R" style="width:46%">
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['australia']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.strtolower($countries['australia']['name']).'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['australia']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
					<?php foreach($countryAustralia as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.strtolower($countries['australia']['name']).'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
					 }?>
				</div>
			</div>

			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['newzealand']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/new zealand/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['newzealand']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryNewzealand as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/new zealand/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>

			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['uk']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/uk/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['uk']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryUK as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/uk/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['germany']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.strtolower($countries['germany']['name']).'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['germany']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryGermany as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.strtolower($countries['germany']['name']).'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
            
            <div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['holland']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.strtolower($countries['holland']['name']).'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['holland']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryHolland as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.strtolower($countries['holland']['name']).'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
    
    
            <div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['switzerland']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.strtolower($countries['switzerland']['name']).'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['switzerland']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countrySwitzerland as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.strtolower($countries['switzerland']['name']).'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>

            <div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['spain']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.strtolower($countries['spain']['name']).'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['spain']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countrySpain as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.strtolower($countries['spain']['name']).'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['france']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.$countries['france']['name'].'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['france']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryFrance as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.$countries['france']['name'].'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['uae']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/uae/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['uae']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryUAE as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/uae/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			
            <div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['saudiarabia']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/saudiarabia/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['saudiarabia']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countrySaudiArabia as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/saudiarabia/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>

		</div>


		<div style="width:46%">
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['india']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.strtolower($countries['india']['name']).'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['india']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryIndia as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.strtolower($countries['india']['name']).'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['canada']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.strtolower($countries['canada']['name']).'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['canada']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryCanada as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.strtolower($countries['canada']['name']).'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>

			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['singapore']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.strtolower($countries['singapore']['name']).'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['singapore']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countrySingapore as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.strtolower($countries['singapore']['name']).'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['usa']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/usa/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['usa']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryUSA as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/usa/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>

			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['qatar']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/qatar/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['qatar']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryqatar as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/qatar/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>

            <div class="mar_bottom_10p">
				<div>
				<img src="<?php echo $countries['malaysia']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.strtolower($countries['malaysia']['name']).'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['malaysia']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryMalaysia as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.strtolower($countries['malaysia']['name']).'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['sweden']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.$countries['sweden']['name'].'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['sweden']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countrySweden as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.$countries['sweden']['name'].'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>

			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['denmark']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.$countries['denmark']['name'].'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['denmark']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryDenmark as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.$countries['denmark']['name'].'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			
            <div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['russia']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.$countries['russia']['name'].'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['russia']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryRussia as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.$countries['russia']['name'].'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['ireland']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $secondLevelUrl.'/All/'.$countries['ireland']['name'].'/All/All/colleges-university-institute'; ?>" class="fontSize_12p"><?php echo $countries['ireland']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryIreland as $cityId => $value) { 
						echo '<a href="'.$secondLevelUrl.'/All/'.$countries['ireland']['name'].'/All/'.$cityId.'/'.$value.'/colleges-university-institute" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
		</div>
		<div class="clear_R"></div>
	</div>	
<!--End_Explore Colleges by Career Countries-->	
</div>
<?php
	$this->load->view('common/footer'); 
?>
