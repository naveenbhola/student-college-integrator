<?php $this->load->view('header'); ?>
<div id="wrapper" data-role="page" style="min-height:413px;padding-top:40px;">

   <?php
	 echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	 echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
   ?>

    <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
     <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
    </header>	
    
	  
    <div data-role="content">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>  
	  
        <div class="head-group">
            <h1>Cookie Policy</h1>
        </div>

    <script>if (history.length<=1) { $('#backButton').hide();}</script>

    <div class="content-wrap2">
    	<section class="static-cont clearfix">
        	<h6>Introduction</h6>
            <p>
            	We, at 'Info Edge (India) Limited' and our affiliated companies worldwide, are committed to respecting your online privacy and recognize your need for appropriate protection and management of any personally identifiable information ("Personal Information") you share with us.<br /><br />
"Personal Information" means any information that may be used to identify an individual, including, but not limited to, a first and last name, a home or other physical address and an email address or other contact information, whether at work or at home. In general, you can visit Info Edge (India) Limited's Web pages without telling us who you are or revealing any Personal Information about yourself. 
            </p>
        </section>
        
        <section class="static-cont clearfix">
        	<h6>Cookies and Other Tracking Technologies</h6>
            <p>
            	Some of our Web pages utilize "cookies" and other tracking technologies. A "cookie" is a small text file that may be used, for example, to collect information about Web site activity. Some cookies and other technologies may serve to recall Personal Information previously indicated by a Web user. Most browsers allow you to control cookies, including whether or not to accept them and how to remove them.<br /><br />

You may set most browsers to notify you if you receive a cookie, or you may choose to block cookies with your browser, but please note that if you choose to erase or block your cookies, you will need to re-enter your original user ID and password to gain access to certain parts of the Web site and some sections of the site would not work.
<br /><br />
Tracking technologies may record information such as Internet domain and host names; Internet protocol (IP) addresses; browser software and operating system types; clickstream patterns; and dates and times that our site is accessed. Our use of cookies and other tracking technologies allows us to improve our Web site and your Web experience. We may also analyze information that does not contain Personal Information for trends and statistics.  
            </p>
        </section>
        
        <section class="static-cont clearfix">
        	<h6>Third Party Services</h6>
            <p>
            	 Third parties provide certain services available on www.shiksha.com on Info Edge (India) Limited's behalf. 'Info Edge (India) Limited' may provide information, including Personal Information, that 'Info Edge (India) Limited' collects on the Web to third-party service providers to help us deliver programs, products, information, and services. Service providers are also an important means by which 'Info Edge (India) Limited' maintains its Web site and mailing lists. 'Info Edge (India) Limited' will take reasonable steps to ensure that these third-party service providers are obligated to protect Personal Information on Info Edge (India) Limited's behalf.<br /><br />

'Info Edge (India) Limited' does not intend to transfer Personal Information without your consent to third parties who are not bound to act on Info Edge (India)Limited's behalf unless such transfer is legally required. Similarly, it is against Info Edge (India)Limited's policy to sell Personal Information collected online without consent. 
            </p>
        </section>
        
        <section class="static-cont clearfix">
        	<h6>Your Consent</h6>
            <p>
            	 By using this Web site, you consent to the terms of our Online Privacy Policy and to Info Edge (India) Limited's processing of Personal Information for the purposes given above as well as those explained where 'Info Edge (India) Limited' collects Personal Information on the Web. 
            </p>
        </section>
        
        <section class="static-cont clearfix">
        	<h6>Information security </h6>
            <ul>
            	<li>We take appropriate security measures to protect against unauthorized access to or unauthorized alteration, disclosure or destruction of data. </li>
                <li>We restrict access to your personally identifying information to employees who need to know that information in order to operate, develop or improve our services.</li>
            </ul>
        </section>
        
        <section class="static-cont clearfix">
        	<h6>Updating your information</h6>
            <p>We provide mechanisms for updating and correcting your personally identifying information for many of our services. For more information, please see the help pages for each service.</p>
        </section>
        
        <section class="static-cont clearfix">
        	<h6>Children</h6>
            <p>'Info Edge (India) Limited' will not contact children under age 13 about special offers or for marketing purposes without a parent's permission.</p>
        </section>
        
        <section class="static-cont clearfix">
        	<h6>Information Sharing and Disclosure</h6>
            <ul>
            	<li>'Info Edge (India) Limited' does not rent, sell, or share personal information about you with other people (save with your consent) or non-affiliated companies except to provide products or services you've requested, when we have your permission, or under the following circumstances:</li>
                <ul>
                	<li>We provide the information to trusted partners who work on behalf of or with 'Info Edge (India) Limited' under confidentiality agreements. These companies may use your personal information to help 'Info Edge (India) Limited' communicate with you about offers from 'Info Edge (India) Limited' and our marketing partners. However, these companies do not have any independent right to share this information. </li>
                    <li>We respond to subpoenas, court orders, or legal process, or to establish or exercise our legal rights or defend against legal claims; </li>
                    <li>We believe it is necessary to share information in order to investigate, prevent, or take action regarding illegal activities, suspected fraud, situations involving potential threats to the physical safety of any person, violations of Info Edge (India)Limited's terms of use, or as otherwise required by law.</li>
                    <li>We transfer information about you if 'Info Edge (India) Limited' is acquired by or merged with another company. In this event, 'Info Edge (India) Limited' will notify you before information about you is transferred and becomes subject to a different privacy policy. </li>
                </ul>
                <li>'Info Edge (India) Limited' displays targeted advertisements based on personal information. Advertisers (including ad serving companies) may assume that people who interact with, view, or click on targeted ads meet the targeting criteria - for example, women ages 18-24 from a particular geographic area. </li>
                <ul>
                	<li>'Info Edge (India) Limited' does not provide any personal information to the advertiser when you interact with or view a targeted ad. However, by interacting with or viewing an ad you are consenting to the possibility that the advertiser will make the assumption that you meet the targeting criteria used to display the ad. </li>
                    <li>'Info Edge (India) Limited' advertisers include financial service providers (such as banks, insurance agents, stock brokers and mortgage lenders) and non-financial companies (such as stores, airlines, and software companies). </li>
                </ul>
                <li>'Info Edge (India) Limited' works with vendors, partners, advertisers, and other service providers in different industries and categories of business. For more information regarding providers of products or services that you've requested please read our detailed reference links. </li>
            </ul>
        </section>
        
        <section class="static-cont clearfix">
        	<h6>Confidentiality and Security</h6>
            <ul>
            	<li>We limit access to personal information about you to employees who we believe reasonably need to come into contact with that information to provide products or services to you or in order to do their jobs.</li>
                <li>We have physical, electronic, and procedural safeguards that comply with the laws prevalent in India to protect personal information about you. We seek to ensure compliance with the requirements of the Information Technology Act, 2000 and Rules made there under to ensure the protection and preservation of your privacy.</li>
            </ul>
        </section>
        
        <section class="static-cont clearfix">
        	<h6>Changes to this Privacy Policy</h6>
			<p>'Info Edge (India) Limited' reserves the right to update, change or modify this policy at any time. The policy shall come to effect from the date of such update, change or modification. </p>
        </section>
        
         <section class="static-cont clearfix">
        	<h6>Disclaimer</h6>
			<p>Info Edge (India) Limited does not store or keep credit card data in a location that is accessible via the Internet. Once a credit card transaction has been completed, all credit card data is moved off-line only to ensure that the data/credit card information received is not accessible to anyone after completion of the on-line transaction and to ensure the maximum security. Info Edge (India) Limited uses the maximum care as is possible to ensure that all or any data / information in respect of electronic transfer of money does not fall in the wrong hands.<br /><br />

Info Edge (India) Limited shall not be liable for any loss or damage sustained by reason of any disclosure (inadvertent or otherwise) of any information concerning the user's account and / or information relating to or regarding online transactions using credit cards / debit cards and / or their verification process and particulars nor for any error, omission or inaccuracy with respect to any information so disclosed and used whether or not in pursuance of a legal process or otherwise. </p>
        </section>
        
        <section class="static-cont clearfix">
        	<h6>Contact Information</h6>
			<p>'Info Edge (India) Limited' welcomes your comments regarding this privacy statement at the contact address given at the website. Should there be any concerns about contravention of this Privacy Policy, 'Info Edge (India)Limited' will employ all commercially reasonable efforts to address the same.</p>
        </section>
        
            
    </div>
<?php $this->load->view('footerLinks'); ?>
</div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('footer'); ?>

