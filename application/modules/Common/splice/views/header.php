<!DOCTYPE html>
<html lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $keyName;?></title>
    <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("jquery.min","nationalMIS"); ?>"></script>
    
    <!-- Bootstrap core CSS -->
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("bootstrap",'nationalMIS'); ?>" rel="stylesheet">
        
    <!-- chart js -->
    <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/chartjs/chart.min.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/js/trackingMIS/bootstrap-wysiwyg/css/prettify.min.css" rel="stylesheet">    
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("animate",'nationalMIS'); ?>" rel="stylesheet">
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/bootstrap-wysiwyg/js/prettify.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/bootstrap-wysiwyg/js/jquery.hotkeys.js"></script>
    <!-- Custom styling plus plugins -->
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("salesOpsCustom",'nationalMIS'); ?>" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/icheck/flat/green.css" rel="stylesheet" />
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("floatexamples",'nationalMIS'); ?>" rel="stylesheet" type="text/css" />
    
    

    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("animate",'nationalMIS'); ?>" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/icheck/flat/green.css" rel="stylesheet" />
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("floatexamples",'nationalMIS'); ?>" rel="stylesheet" type="text/css" />
</head>  

<body class="nav-md" >
<?php   if($source == 'viewRequest'){ ?>
            <div class="col-md-12 col-sm-3 col-xs-12 voverlay" id="voverlay"></div>    
            <div id="vdialog" title="Request Details" style='margin:0 auto; width:1000px; display:none;'>
                <div style='position:relative;'>
                    <div style='position:absolute; width:1100px; background:#fff;   z-index:189; overflow: auto; padding:10px 20px' id='vdialog_inner'>
                        <div style='float:right;'><a href='#' onclick="$('#vdialog').hide(); $('#voverlay').hide(); $('body').removeClass('noscroll'); return false"><img src='/public/images/trackingMIS/close.png' width='24' /></a>
                        </div>
                        <div class="clearFix"></div>
                        <div id='vdialog_content'></div>
                    </div>
                </div>
            </div>
<?php   } ?>
<div id="toolTipContainer" class="toolTipContainer bold-font">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
</div>
<div class="container body">
        <div class="main_container">
             <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img src="<?=$avtarImg?>" alt=""><?php echo $userDataArray['firstname']." ".$userDataArray['lastname'];?>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                    <li><a href="<?php SHIKSHA_HOME?>/userprofile/edit" target ="_blank">  Profile</a>
                                    </li>                                    
                                    <li><a href="javascript:void(0);" onclick="logout();"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
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
