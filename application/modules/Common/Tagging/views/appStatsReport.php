<?php

$chart1Title = "Questions asked from App (Total $appQTotal)";
$chart2Title = "Total Questions asked (Total $totalQTotal)";
$chart3Title = "Answers from App (Total $appATotal)";
$chart4Title = "Total Answers (Total $totalATotal)";
$chart5Title = "Number of Active Users";
$chart6Title = "Backend API Hits";
$chart7Title = "Tags followed (Total $tagFollowTotal)";
$chart8Title = "Users followed (Total $userFollowTotal)";
$chart9Title = "New Registrations from App (Total $appRegTotal)";
$chart10Title = "Total Shiksha Registrations (Total $totalRegTotal)";
$chart11Title = "Avg. Server Processing Time";
$chart12Title = "Sharing from App Data (Total $sharingTotal)";
$chart13Title = "Shiksha Answer Rate within 24 Hrs";
$chart14Title = "Shiksha Answer Rate within 48 Hrs";
$chart15Title = "Shiksha App Installs (Total $installTotal)";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shiksha App Stats</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/humanity/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <style type="text/css">
    table tr:nth-child(even){background-color: #F5FBD5; }
    table td{padding: 4px 5px;}
    .userTable{border-collapse: collapse;width: 40%;font-size: 14px;}
    .userTable td{padding: 4px 5px;}
    .tooltip{padding:2px 10px;font-weight: bold;text-align: left;}
        </style>
</head>
<body>
<div style="width:1200px;margin:0 auto;">
<h1 style="font-family: Arial, Helvetica, sans-serif;color:#8B0707;">Shiksha Android App Stats of past 1 month</h1>
<div style="clear: both;font-size: 1px;"></div>
<br/>
        <div style="clear: both;font-size: 17px;margin-top:20px;font-weight:bold;color:#992600;">Traffic Data</div>
        <div class="moduleChart" id="moduleChart_5" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
        <div class="moduleChart" id="moduleChart_15" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

        <br/>
        <div style="clear: both;font-size: 17px;margin-top:40px;font-weight:bold;padding-top:15px;color:#992600;"></div>
        <div class="moduleChart" id="moduleChart_6" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
	
        <br/>
        <div style="clear: both;font-size: 17px;margin-top:40px;font-weight:bold;padding-top:15px;color:#992600;">Registrations Data</div>
        <div class="moduleChart" id="moduleChart_9" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
        <div class="moduleChart" id="moduleChart_10" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

        <br/>
        <div style="clear: both;font-size: 17px;margin-top:40px;font-weight:bold;padding-top:15px;color:#992600;">Answer Rate Data</div>
        <div class="moduleChart" id="moduleChart_13" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
        <div class="moduleChart" id="moduleChart_14" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

        <br/>
        <div style="clear: both;font-size: 17px;margin-top:40px;font-weight:bold;padding-top:15px;color:#992600;">Questions Data</div>
        <div class="moduleChart" id="moduleChart_1" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
        <div class="moduleChart" id="moduleChart_2" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

	<br/>
        <div style="clear: both;font-size: 17px;margin-top:40px;font-weight:bold;padding-top:15px;color:#992600;">Answers Data</div>
        <div class="moduleChart" id="moduleChart_3" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
        <div class="moduleChart" id="moduleChart_4" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

        <br/>
        <div style="clear: both;font-size: 17px;margin-top:40px;font-weight:bold;padding-top:15px;color:#992600;">Follow Data</div>
        <div class="moduleChart" id="moduleChart_7" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
        <div class="moduleChart" id="moduleChart_8" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

        <br/>
        <div style="clear: both;font-size: 17px;margin-top:40px;font-weight:bold;padding-top:15px;color:#992600;">Sharing Data</div>
        <div class="moduleChart" id="moduleChart_12" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>

        <br/>
        <div style="clear: both;font-size: 17px;margin-top:40px;font-weight:bold;padding-top:15px;color:#992600;">Performance Data</div>
        <div class="moduleChart" id="moduleChart_11" style="float:left; width:580px; margin-right:10px; height:300px; text-align: center; background: #F5FBD5"><img src='/public/images/appmonitor/loaderbg.gif' style='margin-top:160px;' /></div>
        <div style="float:left; width:250px; margin-right:10px; height:300px; text-align: left; margin-top: 40px;"><a href="/Tagging/AppReport/showAPIPerformanceReport">View detailed day-wise API Performance Stats</a></div>

	
        <br/>
        <div style="clear: both;font-size: 1px;"></div>
        </div>
</body>
</html>
<script type="text/javascript">

   google.load("visualization", "1", {packages:["corechart", 'bar']});
   google.setOnLoadCallback(drawChart);

   var optionsChart2;
   var chart2;
   var dataChart2;
   var hitCountchartData = <?php echo $appQuestionArray;?>;
   var latencychartData = <?php echo $totalQuestionArr;?>;
   var chart1Title = '<?php echo $chart1Title;?>';
   var chart2Title = '<?php echo $chart2Title; ?>';

   var appAnswerchartData = <?php echo $appAnswerArray;?>;
   var totalAnswerchartData = <?php echo $totalAnswerArr;?>;
   var chart3Title = '<?php echo $chart3Title;?>';
   var chart4Title = '<?php echo $chart4Title; ?>';

   var deviceData = <?php echo $deviceArray;?>;
   var apiHitData = <?php echo $apiArr;?>;
   var chart5Title = '<?php echo $chart5Title;?>';
   var chart6Title = '<?php echo $chart6Title; ?>';

   var tagFollowData = <?php echo $tagFollowArray;?>;
   var userFollowData = <?php echo $userFollowArr;?>;
   var chart7Title = '<?php echo $chart7Title;?>';
   var chart8Title = '<?php echo $chart8Title; ?>';

   var appRegData = <?php echo $appRegArray;?>;
   var totalRegData = <?php echo $totalRegArr;?>;
   var chart9Title = '<?php echo $chart9Title;?>';
   var chart10Title = '<?php echo $chart10Title; ?>';

   var performanceData = <?php echo $performanceArray;?>;
   var chart11Title = '<?php echo $chart11Title; ?>';
   
   var sharingData = <?php echo $sharingArray;?>;
   var chart12Title = '<?php echo $chart12Title; ?>';

   var sameDayData = <?php echo $sameDayArray;?>;
   var twoDayData = <?php echo $twoDayArray;?>;
   var chart13Title = '<?php echo $chart13Title;?>';
   var chart14Title = '<?php echo $chart14Title; ?>';

   var installData = <?php echo $installArray;?>;
   var chart15Title = '<?php echo $chart15Title; ?>';
   
   function drawChart(){
            //First one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', '');
            dataChart2.addColumn('number', 'Questions');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(hitCountchartData);

            optionsChart2 = {
                                  width: 580,
                                  height: 300,
                                  title: chart1Title,
                                  colors :['#8B0707'],
                                  hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                  vAxis: {minValue: 0},
                                  tooltip: { isHtml: true },
                                  backgroundColor: '#F5FBD5'
            };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_1'));
            chart2.draw(dataChart2, optionsChart2);

            //Second one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart2Title);
            dataChart2.addColumn('number', 'Questions');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(latencychartData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart2Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_2'));
            chart2.draw(dataChart2, optionsChart2);

            //Third one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart3Title);
            dataChart2.addColumn('number', 'Answers');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(appAnswerchartData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart3Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_3'));
            chart2.draw(dataChart2, optionsChart2);


            //Fourth one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart4Title);
            dataChart2.addColumn('number', 'Answers');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(totalAnswerchartData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart4Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_4'));
            chart2.draw(dataChart2, optionsChart2);

            //Fifth one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart5Title);
            dataChart2.addColumn('number', 'Devices');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(deviceData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart5Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_5'));
            chart2.draw(dataChart2, optionsChart2);

            //Sizth one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart6Title);
            dataChart2.addColumn('number', 'APIs');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(apiHitData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart6Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_6'));
            chart2.draw(dataChart2, optionsChart2);

            //seventh one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart7Title);
            dataChart2.addColumn('number', 'Tags');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(tagFollowData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart7Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_7'));
            chart2.draw(dataChart2, optionsChart2);

            //eigth one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart8Title);
            dataChart2.addColumn('number', 'Users');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(userFollowData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart8Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_8'));
            chart2.draw(dataChart2, optionsChart2);

            //ninth one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart9Title);
            dataChart2.addColumn('number', 'Registrations');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(appRegData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart9Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_9'));
            chart2.draw(dataChart2, optionsChart2);

            //tenth one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart10Title);
            dataChart2.addColumn('number', 'Registrations');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(totalRegData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart10Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_10'));
            chart2.draw(dataChart2, optionsChart2);

            //eleventh one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart11Title);
            dataChart2.addColumn('number', 'in ms');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(performanceData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart11Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_11'));
            chart2.draw(dataChart2, optionsChart2);
	    
            //twelth one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart12Title);
            dataChart2.addColumn('number', 'Shares');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(sharingData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart12Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_12'));
            chart2.draw(dataChart2, optionsChart2);

            //thirteenth one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart13Title);
            dataChart2.addColumn('number', '%');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(sameDayData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart13Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_13'));
            chart2.draw(dataChart2, optionsChart2);

            //fourteehth one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart14Title);
            dataChart2.addColumn('number', '%');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(twoDayData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart14Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_14'));
            chart2.draw(dataChart2, optionsChart2);

	    //Fifteenth one
            dataChart2 = new google.visualization.DataTable();
            dataChart2.addColumn('string', chart15Title);
            dataChart2.addColumn('number', 'Installs');
            dataChart2.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            dataChart2.addRows(installData);

                optionsChart2 = {
                                      width: 580,
                                      height: 300,
                                      title: chart15Title,
                                      colors :['#8B0707'],
                                      hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
                                      vAxis: {minValue: 0},
                                      tooltip: { isHtml: true },
                                      backgroundColor: '#F5FBD5'
                };

            chart2 = new google.visualization.LineChart(document.getElementById('moduleChart_15'));
            chart2.draw(dataChart2, optionsChart2);
   }

</script>
