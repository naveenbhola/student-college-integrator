<!-- html code for cookie banner -->

<style>
    .cokkie-lyr{width:100%;padding: 10px;background:rgba(0,0,0,0.9); color: #fff; position:static; bottom:0; left:0; right: 0;font-size: 11px;font-family: 'open sans', sans-serif;z-index: 100; display:none; box-sizing: border-box;}
    .cokkie-box{max-width: 945px; margin: 0 auto;display: table; line-height: 15px;}
    .cokkie-box p, .cokkie-lyr .tar{display: table-cell; vertical-align: middle;}
    .cokkie-box p{font-size: 11px;}
    .cokkie-box p a{color: #0efbf3;}
    .cokkie-lyr a.ui-link{color: #0efbf3;text-decoration: none;word-break: inherit;}
    .cookAgr-btn.ui-link{color: #fff !important;}
    a.cookAgr-btn{background: #f09c43; font-size: 13px; font-weight: 600; border-radius: 2px; text-align: center; text-decoration: none;color: #fff;display: inline-block;width: 66px;height: 30px; line-height: 28px;margin-left: 5px;font-family: 'open sans', sans-serif;}
</style>

<div id="cookiebanner" class="cokkie-lyr">
      <div class="cokkie-box">
          <p>We use cookies to improve your experience. By continuing to browse the site, you agree to our <a href="<?php echo $this->config->item('policyUrl'); ?>">Privacy Policy</a> and <a href="<?php echo $this->config->item('cookieUrl'); ?>">Cookie Policy</a>.</p>
          <div class="tar"><a onclick="setCookieBanner()" class="cookAgr-btn">OK</a></div>
      </div>
</div>
