<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shiksha Tracking MIS Login Page</title>
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("bootstrap",'nationalMIS'); ?>" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("customMIS",'nationalMIS'); ?>" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("animate",'nationalMIS'); ?>" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/icheck/flat/green.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>
<body style="background:#F7F7F7;">
    <div class="">
        <a class="hiddenanchor" id="toregister"></a>
        <a class="hiddenanchor" id="tologin"></a>
        <div id="wrapper">
            <div id="login" class="animate form">
                <section class="login_content">
                   Unauthorized Access!
                   <div style="margin-top:15px;">
                        <a href="javascript:void(0);" onclick="logout();">Sign in with a different account &gt;</a>
                        <br/>
                        <a href="<?=SHIKSHA_HOME?>">Go to Home Page &gt;</a>
                    </div>
                </section>
            </div>
            
        </div>
    </div>
</body>
<script>
    function logout(){
        $.ajax({
            url: '/user/Login/signout',
            method: 'GET',
            success:function(res){
                if(res == "1"){
                    window.location =  "<?=SHIKSHA_HOME?>/splice/dashboard/login";
                }else{
                    window.location = "<?=SHIKSHA_HOME?>";
                }
            }
        });
    }
</script>
</html>
