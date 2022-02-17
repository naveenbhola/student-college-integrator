<?php  
		$this->load->view('header');
		
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
<link rel="canonical" href="http://www.shiksha.com/shikshaHelp/ShikshaHelp/contactUs" >
<style type="text/css">
.contact-cont{padding:10px 10px 15px 10px; font-size:small; border-top:1px solid #e4e4e4; margin-top:10px}
</style>
<div class="contact-cont">
<div style="color:#FD8103; font-size:18px; font-weight:normal; margin-bottom:12px; display:block">Contact Us</div>
<div class="spacer10 clearFix"></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td><b>Ahemdabad</b></td></tr>
<tr><td>079 - 26440210 / 079 - 40233628/ 9925229091</td></tr>
<tr><td>203, Shitiratna Panchvati Circle, CG Road, Ahemadabad - 380 006</td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td><b>Bangalore</b></td></tr>
<tr><td>080 - 42814112 / 42814106</td></tr>
<tr><td>S-416,South Block,Manipal Centre, Dickenson Road, Bangalore - 560042</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><b>Calcutta</b></td></tr>
<tr><td>033 - 40064896/97/98/99</td></tr>
<tr><td>Info Edge India Ltd, Shiksha.com, No 38, Identity, Module No: 5, 1 st Floor, Gariahat Road (S), Near Selimpur Bus Stop, Kolkata - 700031</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><b>Chandigarh</b></td></tr>
<tr><td>0172- 5074040</td></tr>
<tr><td>Info Edge (India) Ltd., First Floor, SCO 14-15, Sector 9-D, Madhya Marg, Chandigarh - 160017</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><b>Chennai</b></td></tr>
<tr><td>044-42977777     Fax: 044-42068533</td></tr>
<tr><td>Samson Towers, 1st floor, 403 L, pantheon Road, Egmore, Chennai-600008</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><b>Delhi Ncr (Head Office)</b></td></tr>
<tr><td>0120 - 4629400</td></tr>
<tr><td>Info Edge India Ltd, A-88, Sector -2, Noida - 201301</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><b>Hyderabad</b></td></tr>
<tr><td>040 - 44751049, 9849695326</td></tr>
<tr><td>6-3-1192/1/1, Office No:113 To 115, 3rd Block, 1st Floor, White House, Kundan bagh, Beside Life Style, Begumpet, Hyderabad - 500026</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><b>Jaipur</b></td></tr>
<tr><td>0141 - 4048908, 9928858144</td></tr>
<tr><td>Info Edge (India) Ltd., 605, Crystal Mall, S.J.S. Highway, Bani Park, Jaipur - 302016</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><b>Mumbai</b></td></tr>
<tr><td>022 - 42447835</td></tr>
<tr><td>127-128, 1st Floor, Chintamani Plaza, Andheri Kurla Road, Andheri (E), Mumbai – 400099</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><b>Pune</b></td></tr>
<tr><td>020 - 41407100</td></tr>
<tr><td>Info Edge (India) Ltd., 2nd Floor, Unit No. 202, 203, Gera Sterling, Koregaon Park, North Main Road, Pune - 411001</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><b>Indore</b></td></tr>
<tr><td>0731-4010305 / 9893627008</td></tr>
<tr><td>201, Royal Ratan Building , 7 M.G. Road, Indore - 452001</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><b>Outside India</b></td></tr>
<tr><td>1800 717 1094 (Toll Free)</td></tr>
</table>
</div>
<div class="clearFix"></div>
<?php $this->load->view('footer'); ?>
