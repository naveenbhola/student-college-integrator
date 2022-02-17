<?php
if($consultantObj->getFacebookLink() != "") {
?>
<div class="consultant-widget" style="padding:1px;">
    <h2 style="color:#333;padding:8px;"><?=$consultantObj->getName()?> on Facebook</h2>
        <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    
<div class="fb-page" data-href="<?=$consultantObj->getFacebookLink()?>" data-width="80" data-hide-cover="true" data-show-facepile="false" data-show-posts="false"></div>

</div>

<?php
}
?>