<div id = "socialShareLayer" class="social-links1" onclick="freePage();$('#socialShareLayer').hide(); var html = $('html');
      var scrollPosition = html.data('scroll-position');
      html.css('overflow', html.data('previous-overflow'));
      window.scrollTo(scrollPosition[0], scrollPosition[1]);
      $('#page-header-container .header').show()">
  <div class="scl">
    <p class="">Share</p>
    <ul class="foote-scl">
     <li><a onclick="shareEntityFacebook();" href="javascript: void(0)"><i class="ic-fb spriteSocialIcons"></i><p>Facebook</p></a></li>
      <li><a onclick="shareEntityTwitter();" href="javascript: void(0)"><i class="ic-tweet spriteSocialIcons"></i><p>Twitter</p></a></li>
       <li><a onclick="shareEntityWhatsApp();" href="javascript: void(0)" data-action="share/whatsapp/share"><i class="ic-wa spriteSocialIcons"></i><p>Whats App</p></a></li>
    </ul>
  </div>
</div>

<script type="text/javascript">
  function freePage(){
      if(typeof history.pushState != 'undefined' && typeof closeLayerId.id != 'undefined' && closeLayerId.id != '') {
       history.back();
    }
    var browserType = window.navigator.userAgent;
    if((browserType.match(/Windows/g) != null || browserType.match(/Microsoft/g) != null)){
       $('.ui-content').height('auto');
       $('.ui-panel-dismiss').height('');
    }
  }
</script>

