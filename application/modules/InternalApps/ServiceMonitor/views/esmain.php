<!--
Copyright 2013 Roy Russo

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

Latest Builds: https://github.com/royrusso/elasticsearch-HQ
-->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>ElasticSearch Monitoring</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>    
<style type="text/css">
  ul.appTabs { margin:0; padding: 0;}
ul.appTabs li {float:left; list-style:none; margin:0; padding:0;}
ul.appTabs li a{display:block; padding:7px 20px 5px 20px; font-size:12px; text-transform: uppercase; color:#7e929a; border:0px solid red; text-decoration: none; font-family: "Open Sans"}
ul.appTabs li a:hover{background: #1e4d61; text-decoration: none;}
ul.appTabs li a.appActive{background: #04599e; color:#ccc;}
  </style>
    <link href="/public/elastichq/css/all.min.css" rel="stylesheet" media="screen">
    
</head>
<body style="margin:0px;padding:0px;overflow:hidden">

<div style="background:#253840; height:31px; display:block; border-bottom:1px solid #2c4b58">
  <div style="margin:0 0; width:1200px; border:0px solid red;">
      <ul class="appTabs">
          <li><a href="http://www.shiksha.com/AppMonitor/Dashboard">AppMonitor</a></li>
          <!--li><a href="http://localhost:3000">Server Monitoring</a></li-->
          <li><a href="http://www.shiksha.com/FailureMatrix/FailureMatrix">Failure Matrix</a></li>
          <li><a href="http://www.shiksha.com/shikshaSchema/ShikshaSchema/index">Documentation</a></li>
          <li><a href="/ServiceMonitor/ElasticSearch/index" class="appActive">Marvel</a></li>
          <li><a href="/LogDrill/LogDrill/index">LogDrill</a></li>
	       <li><a href="/SolrWatch/SolrWatch/index">SolrWatch</a></li>
         <li><a href="/RedisTracker/RedisTracker/index">Happy Redis</a></li>
        <div style="clear:both;"></div>
      </ul>
    <div style="clear:both;"></div>
  </div>
</div>
<div style="clear:both;"></div>

<iframe src="http://10.10.82.14:5601/app/monitoring" width="100%" height="100%" border="0" style="display: block; 
    background: #000;
    border: none;
    height: 100vh;
    width: 100vw;" />

</body>
</html>
