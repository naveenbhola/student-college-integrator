      

        <!-- Begin comScore Tag -->
        <script>
                
                var _comscore = _comscore || [];
                _comscore.push({ c1: "2", c2: "6035313" });
                
                (function() {
                        var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
                        s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
                        el.parentNode.insertBefore(s, el);
                })();
                
        </script>
        
        <noscript>
                <img src="https://b.scorecardresearch.com/p?c1=2&c2=6035313&cv=2.0&cj=1" />
        </noscript>
        <!-- End comScore Tag -->
        
        <div class="clearFix"></div>
        <div class="clearFix"></div>
        <div style="display:none" id="newMMpPixelCode">
                <?php echo base64_decode($mmp_details['pixel_codes']); ?>
        </div>
    </body>
</html>

<div id="signUpForm"></div>
<?php
$CI = & get_instance();
$CI->load->library('security');
$CI->security->setCSRFToken();
?>
<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />