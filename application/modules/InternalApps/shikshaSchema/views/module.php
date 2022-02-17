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
        </style>
    </head>
    <body style="background: #fff; font-family: arial; font-size: 16px;margin:0;padding:0;">
		<div style='background:#f8f8f8; border-bottom:1px solid #eee; height:30px;'><div style='margin:0px auto; width:840px; text-align:right; padding-top:8px;'><a href='http://snapdragon.infoedge.com/public/documentation/' class='doclink' target='_blank'>View PHPDoc Documentation</a></div></div>
            </div>
        <div style='margin:40px auto; width:840px;border:0px solid red;'>
            <h1><a href='/shikshaSchema/ShikshaSchema/index' class='nlink'>Modules</a></h1>
            
			<h1><?php echo $container." :: ".$module; ?></h1>
			
            <div class='desc'>
	    The registration module encapsulates all the functionality for registering users on site. 
	    User can register either as a lead (by specifiying the course/specialization she is interested in along with other preferences) or as a response (by indicating interest in a particular course of an institute). 
	    The fields that the user has to fill while registering vary depending upon the course selected.	
            </div>
            
            <br />
            
            <h2>Use Cases/Product Flows</h2>
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
            </ul>
        </div>
        
    </body>
</html>

