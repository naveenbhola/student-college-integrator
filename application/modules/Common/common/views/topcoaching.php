<html>
<body>
    
                                           <div id="tframe_loader_img" style="padding: 200px 0 0 200px;min-height: 645px;">
                                               <img src="/public/images/loader.gif" alt="loading.."/>

                                           </div>
    <div id="tform" style="display:none;">
        <?php
        $url = "";
        switch ($acronym) {
            case "MBA" :
                $url = "http://shiksha.topcatcoaching.com/user/Login/submit";
                break;
            case "Engineering" :
                $url = "http://shiksha.topiitcoaching.com/user/Login/submit";
                break;
            case "Foreign" :
                $url = "http://shiksha.topgrecoaching.com/user/Login/submit";
                break;
            case "Medical" :
                $url = "http://shiksha.topmbbscoaching.com/user/Login/submit";
                break;
        }
        ?>
        <form id="topcoachform" method="post" action="<?php echo $url ?>">
                <div id="signInForm">
                    <input type="hidden" value="/" id="redirectUrl" name="redirectUrl">
                    <div class="formLabel"> Login Email Id : <span>*</span></div>
                    <div class="formInput">
                        <input type="text" validate="validateStr" minlength="2" maxlength="125" id="username" name="username" value="<?php echo md5($userEmail)."@shikshatest.com";?>" >
                    </div>
                    <br clear="all">

                    <div class="formLabel"> Password : <span>*</span></div>
                    <div class="formInput">
                        <input type="password" name="password" value="shikshatestprep" validate="validateStr" minlength="5" maxlength="125" id="password">
                    </div>
                    <br clear="all">

                    <div class="formLabel">
                        <input type="submit" value="Go"/>
                    </div>

                </div>
                </form>
</div>



    <script type="text/javascript">
<?php if ($url != "") { ?>
            document.forms["topcoachform"].submit();
<?php } ?>
            document.getElementById('tframe_loader_img').style.display = 'none';
            //document.getElementById('tform').style.display = '';
    </script>

</body>
</html>
