 <amp-analytics type="googleanalytics" id="shikshaAnalytics">
    <script type="application/json">
      {
        "vars":{
          "account":"UA-4454182-1"
        },
        "triggers": {
          "trackPageview": {
            "on": "visible",
            "request": "pageview",
              "vars":{
                "title":"<?=$gaPageName;?>"
              }
          },
           "trackAnchorClicks": {
              "on": "click",
              "selector": ".ga-analytic",
              "request": "event",
              "vars": {
                "eventCategory":"${eventCatName}",
                "eventAction": "${eventName}<?php echo !empty($gaCommonName) ? $gaCommonName : '';?>"
              }
            }
        }
      }
    </script>
  </amp-analytics>
<!-- https://developers.google.com/analytics/devguides/collection/amp-analytics
https://developers.google.com/analytics/devguides/collection/amp-analytics/#debugging
http://juliencoquet.com/en/2017/01/27/tracking-amp-pages-with-google-analytics-and-tag-manager/
 -->