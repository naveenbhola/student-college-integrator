<img id = 'pageTrack_beaconImg' src="/public/images/blankImg.gif" width=1 height=1 >
<script>
    try{
    var img = document.getElementById('pageTrack_beaconImg');
    var randNumForBeacon = Math.floor(Math.random()*Math.pow(10,16));
    img.src = '<?php echo BEACON_URL; ?>/'+randNumForBeacon+'/11110051/0/<?php echo urlencode(urlencode(str_replace('/index.php','',"http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'])));?>/<?php echo urlencode(urlencode(str_replace('/index.php','',$_COOKIE['referer_url'])));?>';
    setCookie('referer_url',location.href);
    } catch(ex){
    // do debugging later
    // seems some varibles are override with calendarPopup.js
    }
</script>
