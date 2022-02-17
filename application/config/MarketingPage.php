<?php
/**
 * Main library file for Marketing Page config.
 *
 * @author Ravi Raj <ravi.raj@shiksha.com>
 * @package Marketing Page
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| PAGE_NAME Settings
|--------------------------------------------------------------------------
|
*/
$config['PAGE_NAME'] = array(
	    'management' => 'management',
	    'management_2' => 'management',
	    'distancelearning' => 'management',
	    'animation' => 'management',
	    'media' => 'management',
	    'hospitality' => 'management',
	    'banking' => 'management',
	    'science' => 'management',
	    'bcait' => 'management',
	    'mcait' => 'management',
	    'studyAbroad' => 'management',
	    'generic' => 'management',
	    'campaign1' =>'management',
	    'campaign2' =>'management',
	    'campaign3' =>'management',
            'campaign4' =>'management',
	    'campaign5' =>'management',
            'campaign6' =>'management',
	    'campaign7' =>'management',
            'campaign8' =>'management',
	    'campaign9' =>'management',
            'it'=>'management',
	    'bba'=>'management',
	    'clinical_research'=>'management',
	    'fashion_design'=>'management',
	    'mass_communications'=>'management',
	    'testprep' => 'management'
	);

/*
|--------------------------------------------------------------------------
| LEFT_PANEL Settings
|--------------------------------------------------------------------------
|
*/
$config['LEFT_PANEL'] = array(
	    'management' => NULL,
	    'management_2' => NULL,
	    'distancelearning' => NULL,
	    'animation' => 'banner',
	    'media' => NULL,
	    'hospitality' => 'banner',
	    'banking' => NULL,
	    'science' => 'banner',
	    'bcait' => NULL,
	    'mcait' => NULL,
	    'studyAbroad' => NULL,
	    'generic' => NULL,
	    'campaign1' =>'banner',
	    'campaign2' =>'banner',
	    'campaign3' =>'banner',
            'campaign4' =>'banner',
            'campaign5' =>'banner',
            'campaign6' =>'banner',
	    'campaign7' =>'banner',
            'campaign8' =>'banner',
	    'campaign9' =>'banner',
            'it'=>'banner',
	    'bba'=>'banner',
	    'clinical_research'=>'banner',
	    'fashion_design'=>'banner',
	    'mass_communications'=>'banner',
	    'testprep' => 'banner'
	);
/*
|--------------------------------------------------------------------------
| TEXT_BELOW_LEFT_PANEL Settings
|--------------------------------------------------------------------------
|
*/
$config['TEXT_BELOW_LEFT_PANEL'] = array(
	    'management' => NULL,
	    'management_2' => NULL,
	    'distancelearning' => NULL,
	    'animation' => '<div style="line-height:24px">&nbsp</div>
					<div style="font-size:14px;color:#464645" class="bld">At Shiksha.com, you can find over 3554 animation courses across 4500 institutes.</div>
					<div style="line-height:24px">&nbsp</div>
					<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Now, choose Shiksha.com to</div>
					<div style="padding:5px 0 0 10px">
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get admission alerts</div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Seek advice from experts, alumni and institutes </div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Know about study abroad options and scholarships </div>
					</div>
					<div style="line-height:18px">&nbsp</div>
					<div>
					    Just fill in the small form on the right to mention your educational preferences. <a href="'.SHIKSHA_HOME.'">Shiksha.com</a> will help you select the most suitable course basis this information.
					</div>',
	    'media' => NULL,
	    'hospitality' => '<div style="line-height:24px">&nbsp</div>
					<div style="font-size:14px;color:#464645" class="bld">At Shiksha.com, you can find 4200 hospitality courses across 2700 institutes.</div>
					<div style="line-height:24px">&nbsp</div>
					<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Now, choose Shiksha.com to</div>
					<div style="padding:5px 0 0 10px">
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get admission alerts</div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Seek advice from experts, alumni and institutes </div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Know about study abroad options and scholarships </div>
					</div>
					<div style="line-height:18px">&nbsp</div>
					<div>
					    Just fill in the small form on the right to mention your educational preferences. <a href="'.SHIKSHA_HOME.'">Shiksha.com</a> will help you select the most suitable course basis this information.
					</div>',
	    'banking' => NULL,
	    'science' => '<div style="line-height:24px">&nbsp</div>
					<div style="font-size:14px;color:#464645" class="bld">At Shiksha.com, you can find over 15000 engineering courses across 6300 institutes.</div>
					<div style="line-height:24px">&nbsp</div>
					<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Now, choose Shiksha.com to</div>
					<div style="padding:5px 0 0 10px">
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get admission alerts</div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Seek advice from experts, alumni and institutes </div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Know about study abroad options and scholarships </div>
					</div>
					<div style="line-height:18px">&nbsp</div>
					<div>
					    Just fill in the small form on the right to mention your educational preferences. <a href="'.SHIKSHA_HOME.'">Shiksha.com</a> will help you select the most suitable course basis this information.
					</div>',
	    'bcait' => NULL,
	    'mcait' => NULL,
	    'studyAbroad' => NULL,
	    'generic' => NULL,
	    'campaign1' =>  '<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Choose Shiksha.com to</div>
					<div style="padding:5px 0 0 10px">
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Explore 92,100 courses across 25,000 institutes in India</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Post your queries and get relevant responses</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get all the information you need to study abroad</div>
					</div>
					<div style="line-height:18px">&nbsp</div>
					<div>Just fill in the small form on the right to mention your educational preferences.</div>',
	    'campaign2' => '<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Choose Shiksha.com to</div>
					<div style="padding:5px 0 0 10px">
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Explore 92,100 courses across 25,000 institutes in India</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Post your queries and get relevant responses</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get all the information you need to study abroad</div>
					</div>
					<div style="line-height:18px">&nbsp</div>
					<div>Just fill in the small form on the right to mention your educational preferences.</div>',
	    'campaign3' =>  '<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Choose Shiksha.com to</div>
                                        <div style="padding:5px 0 0 10px">
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get your NMAT result</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Apply for courses offered in NMIMS</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get expert advice from our panelists</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get alumni review for NMIMS college</div>
                                        </div>
                                        <div style="line-height:18px">&nbsp</div>
                                        <div>Just fill in the small form on the right to mention your educational preferences.</div>',
            'campaign4' => '<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Choose Shiksha.com to</div>
                                        <div style="padding:5px 0 0 10px">
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get your ATMA result</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Search and apply for colleges with your ATMA score</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get expert advice from our panelists</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Connect with other MBA aspirants</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get alumni review for MBA colleges</div>
                                        </div>
                                        <div style="line-height:18px">&nbsp</div>
                                        <div>Just fill in the small form on the right to mention your educational preferences.</div>',
	    'campaign5' =>  '<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Choose Shiksha.com to</div>
                                        <div style="padding:5px 0 0 10px">
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get your CAT result</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Search and apply for colleges with your CAT score</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get expert advice from our panelists</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Connect with other MBA aspirants</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get alumni review for MBA colleges</div>
                                        </div>
                                        <div style="line-height:18px">&nbsp</div>
                                        <div>Just fill in the small form on the right to mention your educational preferences.</div>',
            'campaign6' => '<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Choose Shiksha.com to</div>
                                        <div style="padding:5px 0 0 10px">
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get your IBSAT result</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Search and apply for colleges with your IBSAT score</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get expert advice from our panelists</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get alumni review for  IBSAT college</div>
                                        </div>
                                        <div style="line-height:18px">&nbsp</div>
                                        <div>Just fill in the small form on the right to mention your educational preferences.</div>',
	    'campaign7' =>  '<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Choose Shiksha.com to</div>
                                        <div style="padding:5px 0 0 10px">
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get your JMET result</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Apply for colleges with your JMET score</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get expert advice from our panelists</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Connect with other MBA aspirants</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get alumni review for MBA colleges</div>
                                        </div>
                                        <div style="line-height:18px">&nbsp</div>
                                        <div>Just fill in the small form on the right to mention your educational preferences.</div>',
            'campaign8' => '<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Choose Shiksha.com to</div>
                                        <div style="padding:5px 0 0 10px">
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get your SNAP result</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Search and apply for colleges with your SNAP score</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get expert advice from our panelists</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get alumni review for various MBA colleges</div>
                                        </div>
                                        <div style="line-height:18px">&nbsp</div>
                                        <div>Just fill in the small form on the right to mention your educational preferences.</div>',
	    'campaign9' =>  '<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Choose Shiksha.com to</div>
                                        <div style="padding:5px 0 0 10px">
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get your XAT result</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Search and apply for colleges with your XAT score</div>
                                            <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get expert advice from our panelists</div>
					    <div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get alumni review for colleges accepting XAT score</div>
                                        </div>
                                        <div style="line-height:18px">&nbsp</div>
                                        <div>Just fill in the small form on the right to mention your educational preferences.</div>',
            'it' => 	'<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Get the Shiksha advantage:</div>
					<div>
					    <p class="oDot"><span class="OrgangeFont fontSize_18p">Options Galore:</span> <span style="color: rgb(80, 80, 80); font-size: 13px; font-weight: 700;"> Choose from over 150 IT courses across 3700 institutes.</span></p>
					    <p class="oDot"><span class="OrgangeFont fontSize_18p">Free Expert Advice:</span> <span style="color: rgb(80, 80, 80); font-size: 13px; font-weight: 700;"> Get free career counseling from experts.</span> </p>
					    <p class="oDot"><span class="OrgangeFont fontSize_18p">Apply Directly:</span> <span style="color: rgb(80, 80, 80); font-size: 13px; font-weight: 700;"> Use Insta Apply to directly apply to your preferred institute.</span></p>
					    <p class="oDot"><span class="OrgangeFont fontSize_18p">Never Miss a Deadline:</span> <span style="color: rgb(80, 80, 80); font-size: 13px; font-weight: 700;"> Set alerts for relevant admission deadlines, exams and events.</span></p>
					    <p>Just fill in the small form on the right to mention your educational preferences.</p>
					</div>',
	    'bba' => 	'<div style="line-height:24px">&nbsp</div>
					<div style="font-size:14px;color:#464645" class="bld">With over 3500 jobs listed for BBA graduates on naukri.com, your choice to pursue BBA is the first step to a promising career.</div>
					<div style="line-height:24px">&nbsp</div>
					<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Now, choose Shiksha.com to</div>
					<div style="padding:5px 0 0 10px">
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Explore over 2600 BBA courses across 1000 institutes</div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get GD/PI calls from the top institutes directly</div>
					</div>
					<div style="line-height:18px">&nbsp</div>
					<div>
					    Just fill in the small form on the right to mention your educational preferences. <a href="'.SHIKSHA_HOME.'">Shiksha.com</a> will help you select the most suitable course basis this information.
					</div>',
	    'clinical_research'=>'<div style="line-height:24px">&nbsp</div>
					<div style="font-size:14px;color:#464645" class="bld">At Shiksha.com, you can find over 3482 Clinical Research courses across 5500 institutes.</div>
					<div style="line-height:24px">&nbsp</div>
					<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Now, choose Shiksha.com to</div>
					<div style="padding:5px 0 0 10px">
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get admission alerts</div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Seek advice from experts, alumni and institutes </div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Know about study abroad options and scholarships </div>
					</div>
					<div style="line-height:18px">&nbsp</div>
					<div>
					    Just fill in the small form on the right to mention your educational preferences. <a href="'.SHIKSHA_HOME.'">Shiksha.com</a> will help you select the most suitable course basis this information.
					</div>',
	    'fashion_design'=>'<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Register at Shiksha.com to:</div>
					<div style="padding:5px 0 0 10px">
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Choose the best Fashion Designing institutes</div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Browse through various courses in the field</div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get answers to your education-related queries</div>
					</div>
					<div style="line-height:18px">&nbsp</div>
					<div>
					    Just fill in the small form on the right to mention your educational preferences. <a href="'.SHIKSHA_HOME.'">Shiksha.com</a> will help you select the most suitable course basis this information.
					</div>',
	    'mass_communications'=>'<div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Register at Shiksha.com to:</div>
					<div style="padding:5px 0 0 10px">
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Search for various courses in Media, Films and Mass Communication</div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get answers to your education-related queries</div>
					</div>
					<div style="line-height:18px">&nbsp</div>
					<div>
					    Just fill in the small form on the right to mention your educational preferences. <a href="'.SHIKSHA_HOME.'">Shiksha.com</a> will help you select the most suitable course basis this information.
					</div>',

	    'testprep' => 		'<div style="line-height:24px">&nbsp</div>
					<div style="font-size:14px;color:#464645" class="bld">Crack them successfully!</div>
					<div style="line-height:24px">&nbsp</div><div style="background:#f5f5f5;padding-left:10px;font-size:16px;line-height:26px" class="OrgangeFont bld">Register at Shiksha.com to:</div>
					<div style="padding:5px 0 0 10px">
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Browse through institutes that prepare for these tests</div>
						<div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get Exam Alerts, Important Dates and Updates</div><div class="oDot" style="padding-bottom:8px;font-size:14px;font-weight:bold">Get Expert Advice</div>
					</div>
					<div style="line-height:18px">&nbsp</div>
					<div>
					    Just fill in the small form on the right to mention your educational preferences. <a href="'.SHIKSHA_HOME.'">Shiksha.com</a> will help you select the most suitable course basis this information.
					</div>'

	);
/*
|--------------------------------------------------------------------------
| REGISTRATION_WIDGET_FILE Settings
|--------------------------------------------------------------------------
|
*/
$config['REGISTRATION_WIDGET_FILE'] = array(
	    'management' => NULL,
	    'management_2' => NULL,
	    'distancelearning' => NULL,
	    'animation' => 'management',
	    'media' => NULL,
	    'hospitality' => 'management',
	    'banking' => NULL,
	    'science' => 'management',
	    'bcait' => NULL,
	    'mcait' => NULL,
	    'studyAbroad' => NULL,
	    'generic' => NULL,
	    'campaign1' =>'management',
	    'campaign2' =>'management',
	    'campaign3' =>'management',
            'campaign4' =>'management',
	    'campaign5' =>'management',
            'campaign6' =>'management',
	    'campaign7' =>'management',
            'campaign8' =>'management',
	    'campaign9' =>'management',
            'it' =>'management',
	    'bba'=>'management',
	    'clinical_research'=>'management',
	    'fashion_design'=>'management',
	    'mass_communications'=>'management',
	    'testprep' => NULL
	);
/*
|--------------------------------------------------------------------------
| TEXT_HEADING Settings
|--------------------------------------------------------------------------
|
*/
$config['TEXT_HEADING'] = array(
	    'management' => NULL,
	    'management_2' => NULL,
	    'distancelearning' => NULL,
	    'animation' => 'Enhance your creative skills with a degree in Animation',
	    'media' => NULL,
	    'hospitality' => 'Build a winning career in the Hospitality Industry',
	    'banking' => NULL,
	    'science' => 'Give wings to your career with a degree in Engineering',
	    'bcait' => NULL,
	    'mcait' => NULL,
	    'studyAbroad' => NULL,
	    'generic' => NULL,
	    'campaign1' =>'Take Your Career To The Next Level',
	    'campaign2' =>'Take Your Career To The Next Level',
	    'campaign3' =>'Take Your Career To The Next Level',
            'campaign4' =>'Take Your Career To The Next Level',
	    'campaign5' =>'Take Your Career To The Next Level',
            'campaign6' =>'Take Your Career To The Next Level',
	    'campaign7' =>'Take Your Career To The Next Level',
            'campaign8' =>'Take Your Career To The Next Level',
	    'campaign9' =>'Take Your Career To The Next Level',
            'it' =>'Build a Winning Career with a Course in IT',
	    'bba'=>'Give wings to your career with a BBA degree',
	    'clinical_research'=>'Give wings to your career with a degree in Clinical Research',
	    'fashion_design'=>'Give a platform to your creativity',
	    'mass_communications'=>'Take the first step towards a successful career in Media',
	    'testprep' => 'Worried about your entrance exams?'
	);
/*
|--------------------------------------------------------------------------
| TEXT_REGISTRATION_WIDGET Settings
|--------------------------------------------------------------------------
|
*/
$config['TEXT_REGISTRATION_WIDGET'] = array(
	    'management' => NULL,
	    'management_2' => NULL,
	    'distancelearning' => NULL,
	    'animation' => '<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Let’s connect you to the right institute</div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account & to get the relevant institutes contact you.</div>',
	    'it' => '<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Let’s connect you to the right institute</div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account & to get the relevant institutes contact you.</div>',
	    'bba' => '<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Let’s connect you to the right institute</div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account & to get the relevant institutes contact you.</div>',
	    'media' => NULL,
	    'hospitality' => '<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Let’s connect you to the right institute</div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account & to get the relevant institutes contact you.</div>',
	    'banking' => NULL,
	    'science' => '<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Let’s connect you to the right institute</div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account & to get the relevant institutes contact you.</div>',
	    'bcait' => NULL,
	    'mcait' => NULL,
	    'studyAbroad' => NULL,
	    'generic' => NULL,
	    'campaign1' =>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Enjoy benefits of being a Shiksha member </div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account.</div>',
	    'campaign2' =>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Enjoy benefits of being a Shiksha member </div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account.</div>',
	    'campaign3' =>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Check your results now </div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account.</div>',
            'campaign4' =>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Check your results now </div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account.</div>',
	    'campaign5' =>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Check your results now </div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account.</div>',
            'campaign6' =>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Check your results now </div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account.</div>',
	    'campaign7' =>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Check your results now </div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account.</div>',
            'campaign8' =>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Check your results now </div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account.</div>',
            'campaign9' =>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Check your results now </div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account.</div>',
	    'clinical_research'=>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Let’s connect you to the right institute</div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account & to get the relevant institutes contact you.</div>',
	    'fashion_design'=>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Let’s connect you to the right institute</div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account & to get the relevant institutes contact you.</div>',
	    'mass_communications'=>'<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Let’s connect you to the right institute</div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account & to get the relevant institutes contact you.</div>',
	    'testprep' => '<div class="OrgangeFont bld" style="font-size:20px;padding-top:2px">Let’s connect you to the right institute</div> <div style="padding-top:2px">We need a few details from you to create your free Shiksha account & to get the relevant institutes contact you.</div>'
	);

$config['REDIRECTION_URL'] = array(
	    'management' => NULL,
	    'testprep' => NULL,
	    'management_2' => NULL,
	    'distancelearning' => NULL,
	    'animation' => NULL,
	    'it' => NULL,
	    'bba' => NULL,
	    'media' => NULL,
	    'hospitality' => NULL,
	    'banking' => NULL,
	    'science' => NULL,
	    'bcait' => NULL,
	    'mcait' => NULL,
	    'studyAbroad' => NULL,
	    'generic' => NULL,
	    'clinical_research'=>NULL,
	    'fashion_design'=>NULL,
	    'mass_communications'=>NULL,
	    'campaign1' =>SHIKSHA_HOME . '/getArticleDetail/2830/',
	    'campaign2' =>SHIKSHA_HOME . '/getArticleDetail/2829/',
	    'campaign3' =>SHIKSHA_HOME . '/nmat-results',
	    'campaign4' =>SHIKSHA_HOME . '/mat-results',
	    'campaign5' =>SHIKSHA_HOME . '/cat-results',
	    'campaign6' =>SHIKSHA_HOME . '/micat-results',
	    'campaign7' =>SHIKSHA_HOME . '/getArticleDetail/2803/',
	    'campaign8' =>SHIKSHA_HOME . '/snap-results',
	    'campaign9' =>SHIKSHA_HOME . '/xat-results'
	);
$config['POPUP_URL']=array('management' => NULL,
            'testprep' => NULL,
            'management_2' => NULL,
            'distancelearning' => NULL,
            'animation' => NULL,
            'it' => NULL,
            'bba' => NULL,
            'media' => NULL,
            'hospitality' => NULL,
            'banking' => NULL,
            'science' => NULL,
            'bcait' => NULL,
            'mcait' => NULL,
            'studyAbroad' => NULL,
            'generic' => NULL,
            'clinical_research'=>NULL,
            'fashion_design'=>NULL,
            'mass_communications'=>NULL,
            'campaign1' =>NULL,
            'campaign2' =>NULL,
	    'campaign3' => SHIKSHA_HOME.'/nmat-exam-results-article-5668-1',
	    'campaign4' =>'http://www.atma-aims.org/',
	    'campaign5' => SHIKSHA_HOME.'/cat-exam-results-article-5652-1',
	    'campaign6' => SHIKSHA_HOME.'/micat-results-article-5831-1',
	    'campaign7' =>'http://web.iitd.ac.in/~gate/jmet/imp_dates/imp_dates.html',
	    'campaign8' =>SHIKSHA_HOME.'/after-cat-its-time-for-snap-results-article-4345-1',
	    'campaign9' =>SHIKSHA_HOME.'/appeared-for-xat-results-to-be-out-tomorrow-article-4400-1',
	);

?>
