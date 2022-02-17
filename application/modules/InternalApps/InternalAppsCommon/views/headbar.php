<div style='background:#404c56; height:30px;'>
	<div style='margin:0 auto; width:1200px; border:0px solid red;'>
		<ul class='appTabs'>
			<li><a href='/AppMonitor/Dashboard' <?php echo $app == 'AppMonitor' || !$app ? "class='appActive'" : ""; ?>>AppMonitor</a></li>
			<!--li><a href='http://localhost:3000'>Server Monitoring</a></li-->
			<li><a href='/FailureMatrix/FailureMatrix' <?php echo $app == 'FailureMatrix' ? "class='appActive'" : ""; ?>>Failure Matrix</a></li>
			<li><a href='/shikshaSchema/ShikshaSchema/index' <?php echo $app == 'Documentation' ? "class='appActive'" : ""; ?>>Documentation</a></li>
            <li><a target="_blank" href="http://10.10.82.14:5601/app/monitoring">Marvel</a></li>
          <li><a target="_blank" href="http://10.10.16.101:8047/query">LogDrill</a></li>
		<li><a target="_blank" href="http://10.10.16.82:3000/d/HA2rqnxik/solrwatch-search?orgId=1">SolrWatch</a></li>
		<li><a target="_blank" href="http://10.10.16.82:3000/d/redis-monitoring/redis-monitoring?orgId=1">Happy Redis</a></li>
		<li><a target="_blank" href="http://10.10.16.82:3000/d/0jRhpYDik/services-jvm-monitoring?orgId=1&from=now-30m&to=now">Services</a></li>
		<li><a target="_blank" <?php echo $app == 'cicd' ? "class='appActive'" : ""; ?> href="/AppMonitor/CiCd">CI/CD</a></li>
		<li><a target="_blank" href="https://docs.google.com/spreadsheets/d/16zh1qPkTssTkW6rzlHckA1orYA7awa9RMgQ5GuZfp7M/edit#gid=0">Code Review</a></li>
		</ul>
	</div>
</div>
