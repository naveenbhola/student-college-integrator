<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $appName;?> Login Page</title>

    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("bootstrap",'nationalMIS'); ?>" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("animate",'nationalMIS'); ?>" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("customMIS",'nationalMIS'); ?>" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/icheck/flat/green.css" rel="stylesheet">
</head>
<body style="background:#F7F7F7;">
    <div class="">
        <div id="wrapper">
            <div id="login" class="animate form">
                <section class="login_content">
                    <form name="userLogin" id="userLogin" method="post" onkeypress="submitOnEnter(event);">
                        <h1>Login Form</h1>
                        <div>
                            <input type="text" name="username" id="loginEmail" class="form-control" placeholder="Username" required="" style="margin:0px !important;"/>
                            <div style="margin-bottom:10px !important;float:left;color:#955;">
                                <span id="loginEmailError"></span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div>
                            <input type="password" class="form-control" placeholder="Password" autocomplete="off" name="password" id="loginPassword" style="margin:0px !important;"/>
                            <div style="margin-bottom:10px !important;float:left;color:#955;">
                                <span id="loginPasswordError"></span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div>
                            <a class="btn btn-default submit" href="javascript:void(0);" onclick="submitLogin();" id="submitLogin">Log in</a>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </section>
            </div>
        </div>
    </div>
    <!-- CSRF Protection Code -->
    <?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
    ?>
    <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
    <!-- CSRF Protection Code -->
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
    function validateEmail(email){
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    }
    
    function submitLogin() {
        $('#loginEmailError').html('');
        $('#loginPasswordError').html('');
        
        var email = $('#loginEmail').val();
        var password = $('#loginPassword').val();
        
        var error = false;
        
        if(!email) {
            $('#loginEmailError').html("Please enter login email id");
            error = true;
        }else if(!validateEmail(email)) {
            $('#loginEmailError').html("The email address specified is not correct");
            error = true;
        }
        
        if(!password) {
            $('#loginPasswordError').html("Please enter password");
            error = true;
        }
        
        if(!error) {
            $('#loginPasswordError').html('');
            $('#loginEmailError').html('');
            var ajaxData = {
                'typeOfLoginMade':'request',
                'usernamerb':email,
                'mpasswordrb':password,
                'shiksha_auth_token' : $('#shiksha_auth_token').val()
            };
            $.ajax({
                method:'post',
                data:ajaxData,
                url:'/user/Login/submit',
                success:function(res){
                    if (res == 0) {
                        $('#loginPasswordError').html('Invalid Login or Password');
                    }else{
                        window.location.reload();
                    }
                }
            });
        }
        return false;
    }
    
    function submitOnEnter(event){
        if (event.charCode ==13 || event.keyCode == 13) {
            $("#submitLogin").trigger("click");
            event.preventDefault();
        }
    }
    
</script>
</html>