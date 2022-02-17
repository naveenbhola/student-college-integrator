<?php  
		$headerComponents = array(
						//'css'	=>	array('raised_all','mainStyle','header'),
						'css' => array('static'),
						'jsFooter'=>    array('common'),
						'title'	=>	'Shiksha.com '. $maincat .' '.$subcat .' Colleges University Institute - Browse '. $maincat .' '.$subcat .' Education Career Information Search College Study Abroad',
						'tabName' =>	'Shiksha.com Browse Education Career Information Search College Engineering MBA Medical Study Abroad - Forum Community',
						'taburl' =>  site_url(),
                        'metaDescription' => 'Browse list of '. $maincat .' '.$subcat .' colleges universities institutes in India & abroad and get information on '. $maincat .' '.$subcat .' education & career prospects in Shiksha.com Now! Find list of engineering, MBA, Medical colleges, university, institutes, courses, schools. Find info on Foreign  University, question and answer in education and career forum. Ask the education and career counselors.',
                        'metaKeywords' => 'Shiksha, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
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
<?php 
	$countrytUrl = SHIKSHA_HOME."/shikshaHelp/ShikshaHelp/browseByCountry/".$maincat."/";
?>
<?php
	$redirectUrlMap = array(
		'animation' => SHIKSHA_ANIMATION_HOME, 
		'banking' => SHIKSHA_BANKING_HOME, 
		'it' => SHIKSHA_IT_HOME, 
		'media' => SHIKSHA_MEDIA_HOME, 
		'professionals' => SHIKSHA_PROFESSIONALS_HOME, 
		'science' => SHIKSHA_SCIENCE_HOME, 
		'arts' => SHIKSHA_ARTS_HOME, 
		'hospitality' => SHIKSHA_HOSPITALITY_HOME, 
		'management' => SHIKSHA_MANAGEMENT_HOME, 
		'medicine' => SHIKSHA_MEDICINE_HOME, 
		'retail' => SHIKSHA_RETAIL_HOME
	);
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
?>
<?php 
	$breadUrl = SHIKSHA_HOME."/shikshaHelp/ShikshaHelp/browseByCategory/browse-colleges-career-option-listings";
?>
<div class="mar_full_10p normaltxt_11p_blk">
<div>
    <h1><span class="normaltxt_11p_blk" style="font-weight:normal">
	<a href="<?php echo $breadUrl ?>">Browse Section</a> >
	<?php
    if($cityName=='colleges-university-institute') {$cityName = 'All';}
    $maincat = htmlentities($maincat);
    $subcat = htmlentities($subcat);
    $country = htmlentities($country);
    $cityName = htmlentities($cityName);
    if($maincat!='All' && $subcat=='All') {
		 echo ucwords($maincat);
	} 
	else if($maincat!='All' && $subcat!='All') {
		 echo ucwords($maincat)." > ".ucwords($subcat);
	} 
	else if($country!='All') {
		 echo ucwords($country);
	}
	?>
    </span></h1>
</div>
<div class="lineSpace_5">&nbsp;</div>
<?php 
	
	$value = strtolower($maincat);
	$url = $redirectUrlMap[$value];
	$catUrl = $url.'/';
    if(!strstr($catUrl,'getCategoryPage')) {
        $catUrl .= 'getCategoryPage/colleges/'.$value.'/';
    }
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
					<a href="<?php echo $catUrl.strtolower($countries['australia']['name']).'/All/'.$subcat.'/All/'; ?>" class="fontSize_12p"><?php echo $countries['australia']['name']; ?></a>
				</div>				
				<div style="padding-left:58px;font-size:11px">
					<?php foreach($countryAustralia as $cityId => $value) { 
						echo '<a href="'.$catUrl.strtolower($countries['australia']['name']).'/'.$cityId.'/'.$subcat.'/'.$value.'" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
					 }?>
				</div>
			</div>

			<div class="mar_bottom_10p">
				<?php 
					if(strtolower($countries['newzealand']['name'])=='new zealand')
					{
						$shortNZ = 'new zealand';						
					}
				?>
				<div>
					<img src="<?php echo $countries['newzealand']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $catUrl.$shortNZ.'/All/'.$subcat.'/All/'; ?>" class="fontSize_12p"><?php echo $countries['newzealand']['name']; ?></a>
				</div>				
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryNewzealand as $cityId => $value) { 
						echo '<a href="'.$catUrl.$shortNZ.'/'.$cityId.'/'.$subcat.'/'.$value.'" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>

			<div class="mar_bottom_10p">
				<?php 
					if(strtolower($countries['uk']['name'])=='united kingdom')
					{
						$shortUK = 'uk';						
					}
				?>
				<div>
					<img src="<?php echo $countries['uk']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $catUrl.$shortUK.'/All/'.$subcat.'/All/'; ?>" class="fontSize_12p"><?php echo $countries['uk']['name']; ?></a>
				</div>				
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryUK as $cityId => $value) { 
						echo '<a href="'.$catUrl.$shortUK.'/'.$cityId.'/'.$subcat.'/'.$value.'" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['germany']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $catUrl.strtolower($countries['germany']['name']).'/All/'.$subcat.'/All/'; ?>" class="fontSize_12p"><?php echo $countries['germany']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryGermany as $cityId => $value) { 
						echo '<a href="'.$catUrl.strtolower($countries['germany']['name']).'/'.$cityId.'/'.$subcat.'/'.$value.'" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
            <div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['uae']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $catUrl.strtolower($countries['uae']['name']).'/All/'.$subcat.'/All/'; ?>" class="fontSize_12p"><?php echo $countries['uae']['name']; ?></a>
				</div>			
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryUAE as $cityId => $value) { 
						echo '<a href="'.$catUrl.strtolower($countries['uae']['name']).'/'.$cityId.'/'.$subcat.'/'.$value.'" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
		</div>


		<div style="width:46%">
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['india']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $catUrl.strtolower($countries['india']['name']).'/All/'.$subcat.'/All/'; ?>" class="fontSize_12p"><?php echo $countries['india']['name']; ?></a>
				</div>				
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryIndia as $cityId => $value) { 
						echo '<a href="'.$catUrl.strtolower($countries['india']['name']).'/'.$cityId.'/'.$subcat.'/'.$value.'" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['canada']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $catUrl.strtolower($countries['canada']['name']).'/All/'.$subcat.'/All/'; ?>" class="fontSize_12p"><?php echo $countries['canada']['name']; ?></a>
				</div>				
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryCanada as $cityId => $value) { 
						echo '<a href="'.$catUrl.strtolower($countries['canada']['name']).'/'.$cityId.'/'.$subcat.'/'.$value.'" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>

			<div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['singapore']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $catUrl.strtolower($countries['singapore']['name']).'/All/'.$subcat.'/All/'; ?>" class="fontSize_12p"><?php echo $countries['singapore']['name']; ?></a>
				</div>
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countrySingapore as $cityId => $value) { 
						echo '<a href="'.$catUrl.strtolower($countries['singapore']['name']).'/'.$cityId.'/'.$subcat.'/'.$value.'" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			
			<div class="mar_bottom_10p">
				<?php 
					if(strtolower($countries['usa']['name'])=='united states')
					{
						$shortUSA = 'usa';						
					}
				?>
				<div>
					<img src="<?php echo $countries['usa']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $catUrl.$shortUSA.'/All/'.$subcat.'/All/'; ?>" class="fontSize_12p"><?php echo $countries['usa']['name']; ?></a>
				</div>			
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryUSA as $cityId => $value) { 
						echo '<a href="'.$catUrl.$shortUSA.'/'.$cityId.'/'.$subcat.'/'.$value.'" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>
			
            <div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['qatar']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $catUrl.strtolower($countries['qatar']['name']).'/All/'.$subcat.'/All/'; ?>" class="fontSize_12p"><?php echo $countries['qatar']['name']; ?></a>
				</div>			
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countryqatar as $cityId => $value) { 
						echo '<a href="'.$catUrl.strtolower($countries['qatar']['name']).'/'.$cityId.'/'.$subcat.'/'.$value.'" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
				}?>
				</div>
			</div>

            
            <div class="mar_bottom_10p">
				<div>
					<img src="<?php echo $countries['saudiarabia']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> 
					<a href="<?php echo $catUrl.strtolower($countries['saudiarabia']['name']).'/All/'.$subcat.'/All/'; ?>" class="fontSize_12p"><?php echo $countries['saudiarabia']['name']; ?></a>
				</div>			
				<div style="padding-left:58px;font-size:11px">
				<?php foreach($countrySaudiArabia as $cityId => $value) { 
						echo '<a href="'.$catUrl.strtolower($countries['saudiarabia']['name']).'/'.$cityId.'/'.$subcat.'/'.$value.'" class="blackFont" style="font-size:11px">'.$value.'</a>, ';
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
