<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Multiple Apply</title>

    <link href="/public/css/header.css" type="text/css" rel="stylesheet" />
    <link href="/public/css/mainStyle.css" type="text/css" rel="stylesheet" />
    <link href="/public/css/raised_all.css" type="text/css" rel="stylesheet" />
    <link href="/public/css/footer.css" type="text/css" rel="stylesheet" />

    <!-- Need to add css for overlay -->
    <link href="/public/css/modal-message.css" type="text/css" rel="stylesheet" />

    <script type="text/javascript" src="/public/js/tooltip.js"></script>
    <script type="text/javascript" src="/public/js/common.js"></script>
    <script type="text/javascript" src="/public/js/utils.js"></script>
    <!-- Need to load 2 js files related to overlay -->
    <script type="text/javascript" src="/public/js/lazyload.js"></script>
    <script type="text/javascript" src="/public/js/multipleapply.js"></script>
</head>

<body style="padding:40px">

    <a href="#" onclick="displayMessage('/MultipleApply/MultipleApply/showoverlay/1',500,260);return false">Message from url (example 1)</a><br>
    <a href="#" onclick="displayMessage('/MultipleApply/MultipleApply/showoverlay/2',500,240);return false">Message from url (example 2)</a><br>
    <a href="#" onclick="displayMessage('/MultipleApply/MultipleApply/showoverlay/3',500,380);return false">Message from url (example 3)</a><br>
    <a href="#" onclick="displayMessage('/MultipleApply/MultipleApply/showoverlay/4',500,380);return false">Message from url (example 4)</a><br>
    <a href="#" onclick="displayStaticMessage('<h1>Static message</h1><p>This is a static message.</p><p><a href=\'#\' onclick=\'closeMessage();return false\'>Close</a></p>',false,500,240);return false">Static message (Example 1)</a><br>
    
    <script>
    LazyLoad.loadOnce([
            '/public/js/ajax.js',
            '/public/js/modal-message.js',
            '/public/js/ajax-dynamic-content.js',
            '/public/js/user.js',
            '/public/js/'.<?php echo getJSWithVersion('cityList'); ?>
        ],callbackfn);
    </script>
</body>
</html>