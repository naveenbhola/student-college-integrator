<script type="text/javascript">
var session_id = '<?php  echo sessionId(); ?>';
var ip_address = '<?php echo ip2long(S_REMOTE_ADDR); ?>';
try {
_canvasSupport=(!!document.createElement('canvas').getContext)?true:false;
_videoSupport=(!!document.createElement('video').canPlayType)?true:false;
_localstorageSupport=('localStorage' in window && window['localStorage'] !== null)?true:false;
_webworkersSupport=(!!window.Worker)?true:false;
_applicationcacheSupport=(!!window.applicationCache)?true:false;
_geolocationSupport=('geolocation' in navigator)?true:false;
var i = document.createElement('input');
_formdateSupport=('date' in i)?true:false;
_formplaceholderSupport=('placeholder' in i)?true:false;
_formsautofocusSupport=('autofocus' in i)?true:false;
_html5historySupport=(!!(window.history && history.pushState))?true:false;
} catch(e){}
</script>
<?php
global $tempForTracking;
?>
<script type="text/javascript">
var t_bodyend = new Date().getTime();
</script>
<?php
if (is_array($_REQUEST) AND array_key_exists('_debug', $_REQUEST))
{
    echo '<div id="results"></div>';
}
?>

