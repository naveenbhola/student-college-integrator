<script async src="//<?php echo JSURL; ?>/public/mobileSA/js/<?php echo getJSWithVersion('trackingsSA','abroadMobile'); ?>"></script>
<script async src="//<?php echo JSURL; ?>/public/mobile5/js/vendor/boomerang/<?php echo getJSWithVersion("boomerang","nationalMobileBoomerang"); ?>"></script>
<script type="text/javascript">
var ABROAD_BOOMR_TRACKING_ENABLE = true;  
var session_id          = '<?php  echo sessionId(); ?>';
var ip_address          = '<?php echo ip2long(S_REMOTE_ADDR); ?>';
var server_p_time       = "<?php global $tempForTracking;echo $tempForTracking;?>";
var user_agent          = "<?php echo urlencode($_SERVER['HTTP_USER_AGENT']); ?>";
var boomr_pageid        = "<?php if(isset($boomr_pageid)) { echo $boomr_pageid ; } else { echo  "SA_PAGE_UNDEFINED"; } ?>";
var boomerangUser_ip    = "<?php echo S_REMOTE_ADDR; ?>";
var boomerangBeacon_url ="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>/mcommon5/MobileBeacon/mobilebeacon/";
var boomerangBase_url ="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>/public/mobile/js/vendor/boomerang/images/"

<?php
if (is_array($_REQUEST) AND array_key_exists('_debug', $_REQUEST))
{
?>
BOOMR.subscribe('before_beacon', function(o) {
        var html = "", t_name, t_other, others = [];

        if(!o.t_other) o.t_other = "";

        for(var k in o) {
                if(!k.match(/^(t_done|t_other|bw|lat|bw_err|lat_err|u|r2?)$/)) {
                        if(k.match(/^t_/)) {
                                o.t_other += "," + k + "|" + o[k];
                        }
                        else {
                                others.push(k + " = " + o[k]);
                        }
                }
        }

        if(o.t_done) { html += "This page took " + o.t_done + " ms to load<br>"; }
        if(o.t_other) {
                t_other = o.t_other.replace(/^,/, '').replace(/\|/g, ' = ').split(',');
                html += "Other timers measured: <br>";
                for(var i=0; i<t_other.length; i++) {
                        html += "&nbsp;&nbsp;&nbsp;" + t_other[i] + " ms<br>";
                }
        }

        if(o.bw) { html += "Your bandwidth to this server is " + parseInt(o.bw*8/1024) + "kbps (&#x00b1;" + parseInt(o.bw_err*100/o.bw) + "%)<br>"; }
        if(o.lat) { html += "Your latency to this server is " + parseInt(o.lat) + "&#x00b1;" + o.lat_err + "ms<br>"; }

        var r = document.getElementById('results');
        r.innerHTML = html;

        if(others.length) {
                r.innerHTML += "Other parameters:<br>";
                for(var i=0; i<others.length; i++) {
                        var t = document.createTextNode(others[i]);
                        r.innerHTML += "&nbsp;&nbsp;&nbsp;";
                        r.appendChild(t);
                        r.innerHTML += "<br>";

                }
        }
});
<?php
}
?>
</script>
<?php
if (is_array($_REQUEST) AND array_key_exists('_debug', $_REQUEST))
{
    echo '<div id="results"></div>';
}
?>