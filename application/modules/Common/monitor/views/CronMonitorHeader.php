<!DOCTYPE html>
<html>
    <head>
        <title>Shiksha Cron Monitor</title>
        <style type='text/css'>
            body {  padding: 0; margin:0; font: 14px "Lucida Grande", Helvetica, Arial, sans-serif; background: #E2E3E5;}
            table { border-left:0px solid #eee; border-top:0px solid #eee; }
            th {text-align:left; border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding:10px; color:#000; font-size:15px; background: #f0f0f0;}
            td {text-align:left; border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding:10px; font-size:13px; line-height: 150%;}
            .tdleft {color:#444; font-style:normal}
            .tdright {padding-left: 10px;}
            ul {list-style-type: none; border-bottom: 0px solid #ddd; margin:24px 0 0 0; padding:0;}
            li {float:left; border:0px solid #fff;}
            li a {display:block;  padding:10px 30px; text-decoration: none; color:#ccc;}
            li a:hover {color:#f7f7f7;}
            li a.active {background:#E2E3E5; color:#555;}
        </style>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script>$j = $.noConflict();</script>
    </head>
    <body>
    
    <div style='margin:0px 20px 20px 20px; font-size:18px; font-weight:normal; color:#999; background: #27ae60; padding:20px 0 0 20px;'>
        <h1 style='padding:0; margin:0; color:#ecf0f1;'>Shiksha Cron Monitor</h1>
        <ul>
            <li>
                <a href='/monitor/CronMonitor/' id='cronLagLink' <?php if($page == 'Main') echo "class='active'"; ?>>Cron Lag <span style='font-size:14px;'>(in seconds)</span></a>    
            </li>
            <li>
                <a href='/monitor/CronMonitor/showCronErrors' id='cronErrorLink' <?php if($page == 'CronErrors') echo "class='active'"; ?>>Cron Errors</a>
            </li>
            <div style='clear:both;'></div>
        </ul>
    </div>