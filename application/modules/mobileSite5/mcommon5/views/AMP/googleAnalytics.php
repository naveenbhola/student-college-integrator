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
  <?php 
  if(!empty($beaconTrackData) && count($beaconTrackData) > 0)
  { 
     $ampBeaconCall = loadBeaconTracker($beaconTrackData,true);
    if(!empty($ampBeaconCall)) { ?>
        <amp-analytics>
          <script type="application/json">
              {
                "requests": {
                  "pageview": "<?=$ampBeaconCall;?>"
                },
                "triggers": {
                  "trackPageview": {
                    "on": "visible",
                    "request": "pageview"
                  }
              }
            }
          </script>
        </amp-analytics>
   
  <?php } } ?>

  <?php 
  if(!empty($beaconData) && count($beaconData) > 0)
  { 
     $ampBeaconIndexCall = getBeaconURL($beaconData,true);
    if(!empty($ampBeaconIndexCall)) { ?>
        <amp-analytics>
          <script type="application/json">
              {
                "requests": {
                  "pageview": "<?=$ampBeaconIndexCall;?>"
                },
                "triggers": {
                  "trackPageview": {
                    "on": "visible",
                    "request": "pageview"
                  }
              }
            }
          </script>
        </amp-analytics>
   
  <?php } } ?>
 
 <?php if(!empty($gtmParams) && count($gtmParams) > 0) {?>
    <amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-T9G78ZC&gtm.url=SOURCE_URL" data-credentials="include">
    <script type="application/json">
    {
      "vars": 
        <?php echo json_encode($gtmParams);?>
      
    }
    </script>
  </amp-analytics>
<?php } ?>

<amp-pixel src="https://www.facebook.com/tr?id=639671932819149&ev=PageView&noscript=1"
  layout="nodisplay"></amp-pixel>

<!-- <amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-5FCGK6&gtm.url=SOURCE_URL" data-credentials="include">
  <script type="application/json">
  {
    "vars": {
      "gaTrackingId": "UA-12345-1"
    }
  }
  </script>
</amp-analytics> -->
<!-- https://developers.google.com/analytics/devguides/collection/amp-analytics
https://developers.google.com/analytics/devguides/collection/amp-analytics/#debugging
http://juliencoquet.com/en/2017/01/27/tracking-amp-pages-with-google-analytics-and-tag-manager/
 -->