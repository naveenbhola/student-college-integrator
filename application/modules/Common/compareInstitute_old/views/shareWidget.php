	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>


        <label class="share-title">Share this comparison : </label>
        <div style="float:left">
                        <?php
                            $url_parts = parse_url("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
                            $constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
                            $urlPage = urlencode($constructed_url);
                        ?>

                        <div class="flLt">
                            <div class="fb-share-button" data-href="<?="http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]?>" data-type="button"></div>
                        </div>
                        
                        <div class="flLt" style="margin-left:10px;">
                                
                                <a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-url="<?="http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]?>">Tweet</a>
                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

                        </div>
                        <div class="flLt" style="margin-left:10px;">

                                <!-- Place this tag where you want the +1 button to render. -->
                                <div class="g-plusone" data-size="medium" data-annotation="none"></div>
                                
                                <!-- Place this tag after the last +1 button tag. -->
                                <script type="text/javascript">
                                  (function() {
                                    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                                    po.src = 'https://apis.google.com/js/platform.js';
                                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                                  })();
                                </script>

                        </div>
        </div>
