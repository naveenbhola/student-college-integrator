<html>
    <head>
        <title>Shiksha.com</title>
        <style type='text/css'>
            h3 {font-size:18px; border-bottom: 1px solid #eee; padding-bottom: 10px; color:#333; margin-top:20px; margin-bottom: 10px; padding-left: 5px;}
            h2 {font-size:20px; border-bottom: 1px solid #eee; padding-bottom: 10px; color:#222; margin-top:20px; margin-bottom: 10px; padding-left: 5px;}
            h1 {font-size:32px;}
            a:link,a:active,a:visited {color:#245dc1; text-decoration: none; font-size: 15px;}
            a:hover {text-decoration: underline;}
            ul {margin-left:20px; padding-left:0}
            li {margin-bottom: 15px; list-style-type: bullet; margin-left:20px;}
            .smalldesc {font-size:12px; color:#999;}
            .desc {font-size:15px; color:#555; line-height: 130%;}
            a.nlink:link,a.nlink:active,a.nlink:visited {color:#ccc; text-decoration: none; font-size: 18px;}
            a.nlink:hover {text-decoration: none; color:#888;}
			a.doclink {color:#999; text-decoration:none; font-size:14px; }
	    a.doclink:hover {text-decoration:underline;}
	    a.noLink {color: black;}
        </style>
    </head>
    <body style="background: #fff; font-family: arial; font-size: 16px;margin:0;padding:0;">
		<!--<div style='background:#f8f8f8; border-bottom:1px solid #eee; height:30px;'><div style='margin:0px auto; width:840px; text-align:right; padding-top:8px;'><a href='http://snapdragon.infoedge.com/public/documentation/' class='doclink' target='_blank'>View PHPDoc Documentation</a></div></div>
            </div>-->
        <div style='margin:40px auto; width:840px;border:0px solid red;'>
            <h1><a href='/shikshaSchema/ShikshaSchema/index' class='nlink'>Modules</a></h1>
            
			<h1><?php echo $container." :: ".$module; ?></h1>
			
            <div class='desc'>
	    This module contains all the functionalities related to Mobile App.
            </div>
            
            <br />

            <h2>High Level Documents</h2>
            <ul>
                <li><a target="_blank" href='https://docs.google.com/document/d/1GcdETJC5A6qGqCpBEcV-Z8dysmcOaFeVVhKXNJUvzrA/edit?usp=sharing'>App - PRD</a></li>
                <li><a target="_blank" href='https://docs.google.com/spreadsheets/d/1BRM-L4KDhkRhpV-GgjEyNl3X46-VNgnuG4d9upjJCQM/edit?usp=sharing'>App - Release Plan</a></li>
		<li><a target="_blank" href='https://drive.google.com/file/d/0B2CekdqojLAaTkVfcGoyZXJKWU0/view'>App - Complete Flow charts</a></li>
		<li><a target="_blank" href='https://docs.google.com/presentation/d/1R41XD5FfTE597i1c-21wNd-BLrcrYaOaArp8ctJr4J0/edit?usp=sharing'>JSON API Guidelines</a></li>
		<li><a target="_blank" href='https://docs.google.com/spreadsheets/d/1IvXOiEXqKFhfVaTGFb26d4CYcVKAl18DK2B5-iblfTY/edit?usp=sharing'>JIRA User Story Template</a></li>
		<li><a target="_blank" href='https://docs.google.com/spreadsheets/d/1eZs7yaePDlNIJ__7jQYR1148g1rc5LVuNuI4ZK4xpfA/edit#gid=0'>Detailed Status of Backend Tickets</a></li>
            </ul>
	           
            <h2>DB Schema Modifications</h2>
            <ul>
		<li><a target="_blank" href='/shikshaSchema/MobileApp/dbSchema/userChanges'>User changes</a></li>
		<li><a target="_blank" href='/shikshaSchema/MobileApp/dbSchema/tags'>Tag feature</a></li>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/dbSchema/follow'>Follow functionality</a></li>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/dbSchema/share'>Content Sharing</a></li>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/dbSchema/editTracking'>Edit Tracking</a></li>
		<li><a target="_blank" href='/shikshaSchema/MobileApp/dbSchema/points'>New User point system</a></li>
		<li><a target="_blank" href='/shikshaSchema/MobileApp/dbSchema/shortlist'>Content Shortlist (Answer Later/Comment Later)</a></li>
            </ul>

            <h2>App Basics</h2>
            <ul>
		<li><a target="_blank" href='/shikshaSchema/MobileApp/document/codeArchitecture'>Code Architecture</a></li>
		<li><a target="_blank" href='/shikshaSchema/MobileApp/document/serverArchitecture'>Server Architecture</a></li>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/document/security'>Security of the APIs</a></li>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/document/versioning'>Versioning of the APIs</a></li>
		<li><a target="_blank" href='http://svntrac.infoedge.com:8080/Shiksha/wiki/ShikshaMobileApp2015-APIDocumentation'>API's Detailed Documentation</a></li>
            </ul>

            <h2>Homepage Personalization</h2>
            <ul>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/document/personalization'>Personalization Architecture</a></li>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/document/actionMapping'>Action mapping to Redis</a></li>
            </ul>

            <h2>Shiksha Tags</h2>
            <ul>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/document/tagDeduction'>Tags Auto Deduction</a></li>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/document/tagCMS'>Tag CMS</a></li>
            </ul>

            <h2>Search & Auto-suggestor</h2>
            <ul>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/document/solrSchemaChanges'>Solr Schema Changes</a></li>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/document/freeTextSearch'>Free Text Search Algo</a></li>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/document/autoSuggestor'>Auto-Suggestor Algo - Tags/Users</a></li>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/document/relatedData'>Related Data Algo - Question/Discussion/Tags</a></li>
            </ul>


            <h2>Unit Test Cases</h2>
            <ul>
                <li><a target="_blank" href='<?="http://shikshatest03.infoedge.com/test/my_test_user"?>'>User Register / Login API</a></li>
		<li><a target="_blank" href='<?="http://shikshatest03.infoedge.com/test/my_test_QDP"?>'>Question detail page API</a></li>
		<li><a target="_blank" href='<?="http://shikshatest03.infoedge.com/test/my_test_DDP"?>'>Discussion detail page API</a></li>
		<li><a target="_blank" href='<?="http://shikshatest03.infoedge.com/test/my_test_post"?>'>Post Ques/Disc/Answer/Comment/Rating/RA API</a></li>
		<li><a target="_blank" href='<?="http://shikshatest03.infoedge.com/test/my_test_Tags"?>'>Tag Detail Page</a></li>
            </ul>

            <h2>Testing Automation Reports</h2>
            <ul>
                <li><a target="_blank" href='http://172.16.3.215/intranetnew/intranetn/Shiksha Testing/Shiksha/zShikshaAutomation/WorkSpaceSoapUI/Gera/TestData'>APIs Automation (Using Soap UI): Test Data</a></li>
                <li><a target="_blank" href='http://172.16.3.215/intranetnew/intranetn/Shiksha Testing/Shiksha/zShikshaAutomation/WorkSpaceSoapUI/Gera/TestReport'>APIs Automation (Using Soap UI): Execution Logs</a></li>
		<li><a target="_blank" href='http://172.16.3.215/intranetnew/intranetn/Shiksha Testing/Shiksha/zShikshaAutomation/Branch_WorkSpace/ShikshaAppAutomation/src/appshiksha/testdata'>App Automation (Using Appium): Test Data</a></li>
		<li><a target="_blank" href='http://172.16.3.215/intranetnew/intranetn/Shiksha Testing/Shiksha/zShikshaAutomation/Branch_WorkSpace/ShikshaAppAutomation/src/appshiksha/reports'>App Automation (Using Appium): Execution Reports</a></li>
		(<strong>Credentials:</strong> abhishek.jain/naukri2840)

            </ul>

            <h2>Benchmarking Reports</h2>
            <ul>
                <li><a target="_blank" href='https://docs.google.com/spreadsheets/d/1rsya4Cc8BdV5_vNXqZ0ONWNyl01s6BxUzmxLihHmaaQ/edit?usp=sharing'>APIs Report (Response Size/Server Processing Time)</a></li>
                <li><a target="_blank" href='/shikshaSchema/MobileApp/dbSchema/loadTesting'>Load Testing</a></li>
		<li><a target="_blank" href='http://www.shiksha.com/Tagging/AppReport/showAPIPerformanceReport'>Live Performance Report</a></li>
            </ul>
 
            <!--<h2>Use Cases/Product Flows</h2>
            <ul>
                <li><a href='/shikshaSchema/ShikshaSchema/usecase/u/User/registration'>LDB Registration</a></li>
                <li><a href='/shikshaSchema/ShikshaSchema/usecase/u/MMP/registration'>MMP Registration</a></li>
		<li><a href='/public/documentation_ldb/index.html'>Documentation</a></li>
            </ul>
        
            <h2 style="margin-top: 40px;">Code Sequence Flows</h2>
            <ul>
                <li><a href='/shikshaSchema/ShikshaSchema/sequence/u/User/registration'>LDB Registration Form Generation</a></li>
                <li><a href='/shikshaSchema/ShikshaSchema/sequence/u/MMP/registration'>MMP Registration Form Generation</a></li>
                <li><a href='' style='color:#999;'>Registration Process (After form submit)</a></li>
            </ul>
            
            <h2 style="margin-top: 40px;">Related Database Schemas</h2>
            <ul>
                <li><a href='/shikshaSchema/ShikshaSchema/db/user/User/registration'>User Related Tables</a></li>
		<li><a href='/shikshaSchema/ShikshaSchema/db/mmp/MMP/registration'>MMP Related Tables</a></li>
            </ul>-->
        </div>
        
    </body>
</html>

