<amp-analytics>
      <script type="application/json">
        {
            "requests": {
              "event": "/examPages/ExamPageMain/storeClickCount?cdClickCount=${customEventName}"
            },"triggers":{
              "trackAnchorClicks": {
              "on": "click",
              "selector": ".custom-click-analytic",
              "request": "event",
              "vars": {
		"eventAction": "${customEventName}"
              }
            }
          }}
      </script>
  </amp-analytics>
