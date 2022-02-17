<html>
<head>
<style type = "text/css">
#user
{

font-family:verdana,arial;
font-size:13px;
padding-left:100px;
}
#text
{
width:50px;
font-family:verdana,arial;
font-size:13px;
position:absolute;
left:250px;
}
.error
{
font-family:verdana,arial;
font-size:13px;
position:absolute;
left:250px;
width:1000px;
padding-top:10px;
color:red;
}
#ref
{
font-family:verdana,arial;
font-size:13px;
position:absolute;
left:350px;
width:1000px;
color:red;
}

<?php
$this->load->helper('form');
?>
</style>
</head>
<title> Enterprise-Shiksha Page Access Disallowed</title>
<body>

<?php
$headerComponents = array(
								'css'	=>	array('headerCms','raised_all','mainStyle','footer'),
								'js'	=>	array('common','prototype','user'),
								'title'	=>	'User Registration',
								'tabName'	=>	'Register',
                                                                'taburl' => site_url('/enterprise/Enterprise'),
								'metaKeywords'	=>'Some Meta Keywords'
							);
		$this->load->view('enterprise/headerCMS', $headerComponents);
?>

<!--<form method=post action="http://localhost/shiksha/validate"/>-->

<div>
<center>
    <h2> You are disallowed to access this page of Enterprise-Shiksha </h2>
</center>
</div>
</br>
</br>
</br>
</br>
<a href="/enterprise/Enterprise">Go back to Enterprise-Shiksha </a></br>

<?php $this->load->view('enterprise/footer'); ?>


