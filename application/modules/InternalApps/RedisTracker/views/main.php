<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Redis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>    
<style type="text/css">
  ul.appTabs { margin:0; padding: 0;}
ul.appTabs li {float:left; list-style:none; margin:0; padding:0;}
ul.appTabs li a{display:block; padding:7px 20px 5px 20px; font-size:12px; text-transform: uppercase; color:#7e929a; border:0px solid red; text-decoration: none; font-family: "Open Sans"}
ul.appTabs li a:hover{background: #1e4d61; text-decoration: none;}
ul.appTabs li a.appActive{background: #04599e; color:#ccc;}
  </style>
</head>
<body style="margin:0px;padding:0px;overflow:hidden">

<div style="background:#253840; height:31px; display:block; border-bottom:1px solid #2c4b58">
  <div style="margin:0 0; width:1200px; border:0px solid red;">
      <ul class="appTabs">
          <li><a href="http://www.shiksha.com/AppMonitor/Dashboard">AppMonitor</a></li>
          <!--li><a href="http://localhost:3000">Server Monitoring</a></li-->
          <li><a href="http://www.shiksha.com/FailureMatrix/FailureMatrix">Failure Matrix</a></li>
          <li><a href="http://www.shiksha.com/shikshaSchema/ShikshaSchema/index">Documentation</a></li>
          <li><a href="/ServiceMonitor/ElasticSearch/index">Marvel</a></li>
          <li><a href="/LogDrill/LogDrill/index">LogDrill</a></li>
          <li><a href="/SolrWatch/SolrWatch/index">SolrWatch</a></li>
          <li><a href="/RedisTracker/RedisTracker/index" class="appActive">Happy Redis</a></li>
        <div style="clear:both;"></div>
      </ul>
    <div style="clear:both;"></div>
  </div>
</div>
<div style="clear:both;"></div>

<iframe src="http://10.10.16.101:3000/dashboard/db/redis" width="100%" height="100%" border="0" style="display: block; 
    background: #000;
    border: none;
    height: 100vh;
    width: 100vw;" />

</body>
</html>
