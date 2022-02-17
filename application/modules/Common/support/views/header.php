<html>
    <head>
        <title>Shiksha Support</title>
        <style type="text/css">
        body {background:#e6e6e6; font-family: georgia; font-size: 13px; margin:0; padding:0;}
        #container {width:900px; margin:0 auto; background:#fff; height:800px;}
        #masthead {background:#222; height:90px;}
        #logo {float:left; height:50px; padding:20px 5px 20px 35px; width:260px; background:#D64211; font-size: 40px; color:#fff;}
        #header {float:left; width:600px;}
        #loggedin {height:20px; color:#aaa; text-align: right; padding:10px 20px; font-family:arial; font-size:12px;}
        #loggedin a{color:#D64211; text-decoration: none;}
        #subline {text-align: right; font-size: 20px; color:#999; padding:10px 20px 0 0;}
        #menu {width:100%; height:40px;}
        .clr {clear:both;}
        .support_message_error {padding: 10px 0 10px 330px; font-size:15px; background:#FFDEDE; color:red;}
        .support_message_success {padding: 10px 0 10px 330px; font-size:15px; background:#D5F2DA; color:#0B7D1C;}
        #left_panel {background:#f6f6f6; width:280px; height:680px; padding-top:30px; padding-left: 20px; float:left;}
        #left_panel .inputbox {width:250px; font-size: 20px; border:1px solid #ccc; margin-top: 5px;}
        #left_panel .inputbutton {font-size: 15px; margin-top:5px; font-family: georgia;}
        #user_search_helptext {float:left; margin:50px 0 0 30px; width:550px; line-height:150%; color:#999; font-size: 18px; font-family: arial;}
        #main_panel {float:left; margin:30px 0 0 30px; width:540px;}
        #main_panel_heading {color:#999; font-size: 25px; font-family: arial; padding-bottom: 10px;}
        #user_details_block {padding-top: 20px; padding-bottom: 10px; border-top: 1px solid #ddd; background:#f6f6f6;}
        #user_details_block ul {list-style-type:none; margin:0; padding:0;}
        #user_details_block ul li{margin-bottom: 15px;}
        #user_details_block .inputbox {width:250px; font-size: 20px; border:1px solid #ccc;}
        #user_details_block .inputbutton {font-size: 15px; padding:5px; font-family: georgia;}
        .user_details_left {float:left; width:220px; text-align: right; color:#666;}
        .user_details_right {float:left; margin-left:10px;}
        #user_status_active {background:#39AD41; color:#fff; padding:5px;}
        #user_status_blocked {background:#F22718; color:#fff; padding:5px;}
        #block_button {margin:20px 0 0 225px;}
        #block_button input {font-size: 20px; padding:5px; font-family: georgia;}
        #change_info_heading {margin:20px 0 0 230px; font-size: 18px; color:#666;}
        .mt {margin-top:7px;}
        .input_error {color:red;  display:none}
        </style>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <?php $newA = file_get_contents("public/blacklisted.txt"); ?>
        <script>var blacklistWords = new Array(<?php echo $newA;?>);</script>
        <script>
        function submitFindUserById()
        {
            var userId = $.trim($('#suserId').val());
            if(userId) {
                window.location = '/support/Support/user/'+userId;
            }
            else {
                $('#suserId').val('')
                $('#error_suserId').show();
            }
            return false;
        }
        function submitFindUserByEmail()
        {
            var email = $.trim($('#semail').val());
            if(email) {
                var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/
                if(!filter.test(email)) {
                    $('#error_semail').html('The email id specified is not a valid email id');
                    $('#error_semail').show();
                    return false;
                }
                else {
                    return true;
                }
            }
            else {
                $('#semail').val('');
                $('#error_semail').html('Please enter email id');
                $('#error_semail').show();
                return false;
            }
        }
        function submitLogin()
        {
            var username = $('#username').val();
            var password = $('#password').val();
            
            var error = false;
            
            if(!username) {
                $('#error_username').show();
                error = true;
            }
            else {
                $('#error_username').hide();
            }
            
            if(!password) {
                $('#error_password').show();
                error = true;
            }
            else {
                $('#error_password').hide();
            }
            
            if(!error) {
                $.post('/support/Support/Login',{'username':username,'mpassword':(password)},function(data) {
                    if(parseInt(data) > 0) {
                        window.location = '/support/Support/user';
                    }
                    else {
                        $('#error_login').html('Incorrect account details. Please enter Login Email Id & Password again.');
                        $('#error_login').show();
                    }
                });
            }
            
            return false;
        }
        function submitSignOut()
        {
            $.post('/user/Login/signOut',{},function(data) {
                window.location = '/support/Support/user';
            });
        }
        function submitEditUser()
        {
            var displayName = $.trim($('#displayName').val());
            var email = $.trim($('#email').val());
            var mobile = $.trim($('#mobile').val());
            
            //if(!displayName && !email && !mobile) {
            //    alert("Please enter either display name or email or mobile");
            //    return false;
            //}
            
            var error = false;
            
            if(displayName) {
                var allowedChars = /^([A-Za-z0-9\s](,|\.|_|-){0,2})*$/;
            
                if(!allowedChars.test(displayName)) {
                    $('#error_displayName').html('The display name can not contain special characters');
                    $('#error_displayName').show();
                    error = true;
                }
                else {
                    /*
                     * Check for blacklisted words in name
                     */ 
                    
                    displayName = displayName.replace(/[(\n)\r\t\"\']/g,' ');
                    displayName = displayName.replace(/[^\x20-\x7E]/g,'');
                    displayName.toLowerCase();
                    
                    var blacklisted = false;
                    if(typeof(blacklistWords) == 'undefined'){
                        blacklistWords = new Array();
                    }
                    if(blacklistWords){
                        for (i=0; i < blacklistWords.length; i++) {
                            if(displayName.indexOf(blacklistWords[i].toLowerCase()) >= 0) {
                                blacklisted = true;
                            }
                        }
                    }
                    if(blacklisted) {
                        $('#error_displayName').html('This name is not allowed');
                        $('#error_displayName').show();
                        error = true;
                    }
                    else {
                        $('#error_displayName').html('');
                        $('#error_displayName').hide();
                    }
                }
            }
            
            if(email) {
                var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/
                if(!filter.test(email)) {
                    $('#error_email').html('The email id specified is not a valid email id');
                    $('#error_email').show();
                    error = true;
                }
                else {
                    $('#error_email').html('');
                    $('#error_email').hide();
                }
            }
            
            if(mobile) {
                
                var mobileError = '';
                
                var filter = /^(\d)+$/;
                if(!filter.test(mobile)){
                    mobileError = "The mobile number entered by you is not valid";
                }
                else if((mobile.substr(0,1) != 9)&&(mobile.substr(0,1) != 8)&&(mobile.substr(0,1) != 7)) {
                    mobileError = "The mobile number can start with 9 or 8 or 7 only.";
                }
                else if(mobile.length != 10) {
                    mobileError = "The mobile number must have 10 digits";
                }
                
                if(mobileError) {
                    $('#mobile_email').html(mobileError);
                    $('#mobile_email').show();
                    error = true;
                }
                else {
                    $('#mobile_email').html('');
                    $('#mobile_email').hide();
                }
            }
            
            if(error) {
                return false;
            }
            
            return true;
        }

        function doGetMappingInterface() {

            var userid = $('#logged_in_userid').val();
            var username = $('#logged_in_username').val();
            
            $.post('/sums/Nav_Integreation/clientSalesMappingInterface/',{'userid':userid,'username':username},function(data) {
                
                $('body').html(data);
                //window.location = '/sums/Nav_Integreation/clientSalesMappingInterface';

                //alert(data);
            });
        }

        </script>
    </head>
    <body>
        <div id="container">
            <div id="masthead">
                <div id="logo">
                    Shiksha.com
                </div>
                <div id="header">
                    <div id="loggedin">
                    <?php
                    if($loggedInUserInfo[0]['userid']) {
                        echo "Welcome ".$loggedInUserInfo[0]['displayname']." &nbsp;&nbsp;<a href='#' onclick='submitSignOut();'>Sign Out</a>";
                    }
                    ?>
                    </div>
                    <div id="subline">Support Utilities</div>
                </div>
                <div class="clr"></div>
            </div>
 
