<?php

$this->load->library('Online/courseLevelManager');
global $onlineFormsDepartments;
$exams = $onlineFormsDepartments[$this->courselevelmanager->getCurrentDepartment()]['exams'];

?>
<div style="display: none;" id="faq_layer">
         <!--FAQ Layer Starts here-->
         <div class="loginLayer">
			<div class="helpLayerContent">
        	<div id="helpHeaderLinks">
            	<ul>
                    <li><a href="#general" onclick="toogleHelpLayreLinks('generalLink');" id="generalLink" class="activeHelp">General</a></li>
                    <li><a href="#fillForm" onclick="toogleHelpLayreLinks('fillFormLink');" id="fillFormLink">Form Related - Filling Form</a></li>
                    <li><a href="#editForm" onclick="toogleHelpLayreLinks('editFormLink');" id="editFormLink">Form Related – Editing Form</a></li>
                    <li><a href="#payment" onclick="toogleHelpLayreLinks('payment2');" id="payment2">Payment Related</a></li>
                </ul>
            </div>
            <div class="clearFix"></div>
            <div class="helpScrollBox">
            	<div id="onlineAppsFaq">
    	<ul>
        	<li id="general">
            	<h4>General</h4>
                <strong class="flRt"><a href="#genOverlayAnA">(Top)</a></strong>
                <div class="clearFix"></div>
    			<h5>What is Shiksha Online Application Forms?</h5>
        		<p>Shiksha Online Application Form is a new feature on our website which enables you to submit application forms online directly from the Shiksha website.</p>
            </li>
            
            <li>
    			<h5>Is this equivalent to submitting the forms in-person/by post?</h5>
        		<p>Yes, it is equivalent to submitting the forms in-person or by post.</p>
            </li>
            
            <li>
    			<h5>How is it better than applying in-person or submitting by post?</h5>
        		<p>Applying online is a much easier and faster way as compared to applying in-person or by post. Through this application, you will be able to apply from anywhere, anytime as per your convenience.</p>
            </li>
            
            <li id="actualForm">
    			<h5>Are these the actual forms used by the university/college that I am applying to?</h5>
        		<p>These forms are approved by the Institutes and Shiksha has been authorized to facilitate the application submission process for the Institute. The process of applying online will be treated at par with the offline process, by all the Institutes.</p>
            </li>
            
            <li>
    			<h5>How will I know when my form is delivered to the Institute?</h5>
        		<p>You will get an acknowledgement as soon as you have submitted the form. You will also get a notification when the Institute has viewed your form. Apart from this, you will get regular updates about the status of your application as it passes through various stages of admission process.</p>
            </li>
            
            <li id="HowDoIStart">
    			<h5>How do I get started?</h5>
        		<p>In order to apply to colleges online, you must first create a profile on Shiksha.</p>
            </li>
            
            <li>
    			<h5>What are the system requirements to use this site?</h5>
        		<p>You must have an internet connection and a computer with one of the following browsers: Internet Explorer 6 or higher, Firefox 2 or higher, Opera 9 or higher, Chrome, and Safari.</p>
            </li>
            
            <li>
    			<h5>Do I need an Internet connection?</h5>
        		<p>Yes.</p>
            </li>
            
            <li id="fillForm">
            	<h4>Form Related - Filling Form </h4>
                <strong class="flRt"><a href="#genOverlayAnA">(Top)</a></strong>
                <div class="clearFix"></div>
    			<h5>What are <strong>'save and continue'</strong> and <strong>'save and exit'</strong> options?</h5>
        		<p>By using the '<strong>save and continue</strong>' option, you can save the information on this page and proceed to next step. Whereas, by using the '<strong>save and exit</strong>' option, you can save information on this page and quit online form submission at the moment. This enables you to login again and fill up the remaining information before submitting the form.</p>
            </li>
            
            <li>
    			<h5>Why do you want two email addresses?</h5>
        		<p>We require an alternate email address apart from your primary email address in case we cannot reach you in your primary email address.</p>
            </li>
            
            <li>
    			<h5>What is house number/house name in address section?</h5>
        		<p>This is a mandatory field where you must write the Name or Number of the house you own, e.g. 45A or Woodstock Villa, or 49B Jeevan Apartments.</p>
            </li>
            
            <li>
    			<h5>What is street/locality in address section?</h5>
        		<p>In this field, you must write the street/locality/area on which the house is located, e.g. Bhagat Singh Marg or Veera Desai Road. If you do not use street name or locality in your address, leave it blank, however; at least one among street name and area/locality must be mentioned.</p>
            </li>
            
            <li>
    			<h5>What is correspondence address?</h5>
        		<p>This is the address where you will receive the correspondence from Shiksha.com or the Institutes that you have applied to. It can be the address of your current residence, your friend's house, your hostel, or even your office.</p>
            </li>
            
            <li>
    			<h5>What is examination name for class 10th in Education History section?</h5>
        		<p>For details of the name of the examination, please refer to mark sheet/degree/certificate. For example, 10th Board from CBSE is called AISSE.</p>
            </li>
            
            <li>
    			<h5>Can I go back and edit the previous step?</h5>
        		<p>If your application form is still in the saved mode or you haven't yet submitted the form, you can go back and edit the previous steps as many times as you want.</p>
            </li>
            
            <li>
    			<h5>What is My Document section? How do I send my documents?</h5>
        		<p>You can find '<strong>My Document</strong>' section in your dashboard. It is the central repository for storing documents like scanned copies of mark sheets, certificates, experience letters etc. You can use this feature to upload and save your documents for easy access.<br />
<br />
While filing up your application form, you do not need to upload the documents from your computer. You can directly attach them from My Documents section, thus saving time.
</p>
                
            </li>
            <li id="editForm">
            	<h4>Form Related – Editing Form</h4>
                <strong class="flRt"><a href="#genOverlayAnA">(Top)</a></strong>
                <div class="clearFix"></div>
    			<h5>Can I edit my form once I've filled it?</h5>
        		<p>Yes, you can edit your form before the final submission.</p>
            </li>
            
            <li>
    			<h5>I want to update my address information in the form. What should I do?</h5>
        		<p>You cannot edit forms once you have submitted them. In case your form is in saved mode, you can update the information using the 'Review Your Form' option.</p>
            </li>
            
            <li>
    			<h5>I want to edit my phone number in the form. What should I do?</h5>
        		<p>You cannot edit forms once you have submitted them. In case your form is in saved mode, you can update the information using the <strong>'Review Your Form' option</strong>.</p>
            </li>
            
            <li>
    			<h5>If I edit information in My Profile, will it get changed for all the forms?</h5>
        		<p>If you edit any information in your profile in <strong>'My Profile'</strong> section, you will be asked if you want this change to be reflected in all saved forms. If you click yes, then only this change will be reflected in your saved and un-submitted forms. <strong>Please note:</strong> You cannot edit/change any information in a form that has already been submitted, except your scores (<?=$exams?>).</p>
            </li>
            
            <li>
    			<h5>I have submitted the form. Now I can't see the option for editing it. Why?</h5>
        		<p>Forms once submitted, cannot be edited. Only qualifying test scores (<?=$exams?>) can be edited/updated. For updating any other info, the applicant will have to get in touch with the institute offline. Before submission, till the time the form is in saved state, it can be edited any number of times.</p>
            </li>
            
            <li>
    			<h5>I have got an email/sms alert saying the institute wants my photograph. What's this?</h5>
        		<p>It is possible that due to some technical problem, the institute did not get your photograph. In case you get an email/sms, please try resending you photograph again.</p>
            </li>
            
            <li>
    			<h5>I have got an email/sms alert saying the institute wants my documents. What's this?</h5>
        		<p>It is possible that due to some technical problem, the institute did not get your document attached with the application form. In case you get an email/sms, please try resending you documents again.</p>
            </li>
            
            <li>
    			<h5>How can I send my updated test (<?=$exams?>) scores to the institute?</h5>
        		<p>You can update your test (<?=$exams?>) scores from the 'My Profile' section.</p>
            </li>
            
            <li>
    			<h5>I have sent incorrect scores by mistake. What should I do now?</h5>
        		<p>You can click on "update scores" option in your dashboard, against the application number and update the scores from there.</p>
            </li>
            <li id="payment">
            	<h4>Payment Related</h4>
                <strong class="flRt"><a href="#genOverlayAnA">(Top)</a></strong>
                <div class="clearFix"></div>
    			<h5 id="onlinePayment">What is online payment?</h5>
        		<p>This option enables you to pay the submission charge online through various modes like Netbanking and Credit/Debit Cards. Online payment on Shiksha is absolutely safe and secure.</p>
            </li>
            
            <li id="creditCardQues">
    			<h5>What If I don't have a credit card?</h5>
        		<p>If you don't have a credit card, you can use your debit card, Netbanking or the offline payment mode.</p>
            </li>
            
            <!-- <li>
    			<h5>What if I don't have a debit card or online banking account?</h5>
        		<p>In case you don't have a debit card or online banking account, you can make an offline payment though Draft.</p>
            </li> -->
            
            <li>
    			<h5>While making the payment, my internet connection dropped. What should I do now?</h5>
        		<p>You can check the status of your payment on your Shiksha dashboard. In case your payment didn't get processed, you may need to initiate the online payment process again.</p>
            </li>
            
            <li>
    			<h5>My credit card is charged but my dashboard shows payment cancelled. Why?</h5>
        		<p>This may happen due to technical failure at the Bank or Payment Gateway's end. Do not worry as payments on Shiksha are safe and secure. Just write a mail to <a href="mailto:help@shiksha.com">help@shiksha.com</a>, mentioning your transaction ID or call up at 011-4046-9621, and our customer care team will resolve the issue for you.</p>
            </li>
            
            <li>
    			<h5>Where should I contact for payment related queries?</h5>
        		<p>Just write a mail to <a href="mailto:help@shiksha.com">help@shiksha.com</a>, mentioning your transaction ID or call up at 011-4046-9621, and our customer care team will be glad to help you.</p>
            </li>
            
           <!--  <li>
    			<h5>How can I make payments through draft?</h5>
        		<p>You can make payments through draft by clicking on the <strong>'Take print out of your application form'</strong> and then following the instructions given here.</p>
            </li> -->
            
            <!-- <li>
    			<h5>I have sent the draft to the Institute. Now what?</h5>
        		<p>Once you have sent the draft, you will need to update the draft number and date of dispatch. To do this log on to Shiksha online forms section, and click on the application number on your dashboard. On the application form detail page, click on update draft details link, to enter details. Alternatively, you can also click on Update draft details link in the alerts section on your dashboard.</p>
            </li> -->
            
            <!-- <li>
    			<h5>I forgot to update the draft details. What can I do now?</h5>
        		<p>You can update draft details at any time. To do so, on the application form detail page, click on update draft details link, to enter details. Alternatively, you can also click on Update draft details link in the alerts section on your dashboard. If you are still unclear about what to do, just write a mail to <a href="mailto:help@shiksha.com">help@shiksha.com</a>, mentioning your user name and application number or call up at 011-4046-9621, and our customer care team will resolve the issue for you.</p>
            </li>
             -->
            
            <!-- <li>
    			<h5>I updated incorrect draft details. What should I do now?</h5>
        		<p>Simply write a mail to <a href="mailto:help@shiksha.com">help@shiksha.com</a>, mentioning your user name and application number or call up at 011-4046-9621, and our customer care team will resolve the issue for you.</p>
            </li>
             -->
            <li>
    			<h5>I have some other question regarding payments. Who should I contact?</h5>
        		<p>Simply write a mail to <a href="mailto:help@shiksha.com">help@shiksha.com</a>, mentioning your user name and application number or call up at 011-4046-9621, and our customer care team will resolve the issue for you.</p>
            </li>
        </ul>
    </div>
            </div>
            <div class="clearFix spacer25"></div>
        </div>
        </div>
	<!--FAQ Layer Ends here-->
</div>
