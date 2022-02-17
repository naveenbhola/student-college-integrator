<!DOCTYPE html>
<html lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Shiksha.com | MIS</title>
    <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("jquery.min","nationalMIS"); ?>"></script>
    <?php if($metric == 'registration' || $metric == 'traffic' || $metric == 'engagement' || $metric == 'response' || $metric == 'RMC' || ($pageName == "searchPage" && $teamName =="Study Abroad" && $metric == "home")
        || ($teamName =="Study Abroad" && $metric == "exam_upload")){ ?>
        <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("shikshaMIS","nationalMIS"); ?>"></script>
    <?php }else if($metric == 'sassistant'){?>
        <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("sassistantMIS","nationalMIS"); ?>"></script>
    <?php } ?>

    <?php if($misSource == 'studyAbroad'){?>
        <!-- Bootstrap core CSS -->
        <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("bootstrap",'nationalMIS'); ?>" rel="stylesheet">
        <!-- Custom styling plus plugins -->
        <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("customMIS",'nationalMIS'); ?>" rel="stylesheet">
    <?php } ?>

    <?php
    if(
        $misSource != 'studyAbroad' ||
        strcasecmp($metric, 'leads') == 0 ||
        strcasecmp($metric, 'response') == 0 ||
        strcasecmp($metric, 'exam_upload') == 0 ||
        strcasecmp($metric, 'rmc') == 0 ||
        strcasecmp($metric, 'registration') == 0 ||
        strcasecmp($metric, 'engagement') == 0 ||
        strcasecmp($metric, 'traffics') == 0 
    ){?>
    <!-- chart js -->
	<?php if($includeUpdatedJS === true){ ?>
	<script src="//<?php echo JSURL; ?>/public/js/trackingMIS/chartjs/Chart.min.js"></script>
	<?php } else { ?>
    <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/chartjs/chart.min.js"></script>
	<?php } ?>
    <!-- Bootstrap core CSS -->
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("bootstrap",'nationalMIS'); ?>" rel="stylesheet">

    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("animate",'nationalMIS'); ?>" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("customMIS",'nationalMIS'); ?>" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/icheck/flat/green.css" rel="stylesheet" />
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("floatexamples",'nationalMIS'); ?>" rel="stylesheet" type="text/css" />
    <?php if($misSource == "CD"
    || $misSource == "Content-Delivery"
    || $metric == "registration"
    || $metric == "traffic"
    || $metric == "engagement"
    || $metric == "response"
    || $metric == "RMC"
    || ($pageName == "searchPage" && $teamName =="Study Abroad" && $metric == "home")
    || ($teamName =="Study Abroad" && $metric == "exam_upload")
    || $metric == "sassistant"
    )
    {}
    else{?>
    <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("commonMIS","nationalMIS"); ?>"></script>
    <?php } ?>
    <?php } ?>
    <?php
    switch ($misSource) {
        case 'nationalListing':
        case 'national': ?>
        <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("listingMIS","nationalMIS"); ?>"></script>
    <?php
            break;

        case 'ldb':
    ?>
        <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("ldbMIS","nationalMIS"); ?>"></script>
    <?php
            break;

        case 'CD':
    case 'Content-Delivery':
    ?>
        <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("cdMIS","nationalMIS"); ?>"></script>
        <link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("bootstrap-multiselect",'nationalMIS'); ?>" type="text/css">
        <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("bootstrap-multiselect","nationalMIS"); ?>"></script>
        <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/maps/jquery-jvectormap-2.0.1.css" rel="stylesheet">

		<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/maps/jquery-jvectormap-2.0.1.min.js"></script> 
		<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/maps/gdp-data.js"></script>
		<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/maps/india-en.js"></script>
    <?php
            break;  
    case 'App':
    ?>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/flot/jquery.flot.min.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/flot/jquery.flot.pie.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/flot/jquery.flot.orderBars.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/flot/jquery.flot.time.min.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/flot/date.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/flot/jquery.flot.spline.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/flot/jquery.flot.stack.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/flot/curvedLines.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/flot/jquery.flot.resize.js"></script>
    <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("appMIS","nationalMIS"); ?>"></script>
    <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/chartjs/chart.min.js"></script>

    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("animate",'nationalMIS'); ?>" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/icheck/flat/green.css" rel="stylesheet" />
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("floatexamples",'nationalMIS'); ?>" rel="stylesheet" type="text/css" />

            <?php
            break;

    case  "SASALES": ?>
    <link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("saSales",'nationalMIS'); ?>" type="text/css">
    <?php break;       
    } ?>

    

</head>  

<body class="nav-md">
<div class="loader_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/loader_MIS.gif"></div>
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
            <div class="top_nav" <?php echo ($skipNavigationSASales === true?'style="margin-left:0px !important;"':''); ?>>
                <div class="nav_menu">
                    <nav class="" role="navigation">
						<?php if($skipNavigationSASales !== true){ ?>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
						<?php } ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img src="<?=$avtarImg?>" alt=""><?php echo $userDataArray['firstname']." ".$userDataArray['lastname'];?>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                    <li><a href="<?php SHIKSHA_HOME?>/userprofile/edit" target ="_blank">  Profile</a>
                                    </li>
                                    <?php if(!$skipNavigationSASales){ ?>
									<li><a href="<?php SHIKSHA_HOME?>/trackingMIS/KiPoints/dashboard" >  Add Tracking Key</a>
                                    </li>

                                    <li><a href="<?php SHIKSHA_HOME?>/LDBRecatMigrationScript/registrationStatusPanel" >  Domestic Registration Stats</a>
                                    </li>
									<?php } ?>
                                    <li><a href="javascript:void(0);" onclick="logout();"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                    </li>
                                </ul>
                            </li>
                            

                            <!--Drop down menu for  selecting Team Name-->
							<?php if($skipNavigationSASales !== true){ ?>
                                <li role="presentation" class="dropdown">
                                    <a id="drop4" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" role="button" aria-expanded="false">
										<?php if(empty($teamName)) { $teamName = 'Shiksha';} echo $teamName.'   '; ?>[Change]
										<span class="caret"></span>
									</a>
									<ul id="menu6" class="dropdown-menu animated fadeInDown" role="menu">
										<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo SHIKSHA_HOME; ?>/trackingMIS/Dashboard/overview">Shiksha</a>
										</li>
										<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo SHIKSHA_HOME; ?>/trackingMIS/Listings/dashboard">Domestic</a>
										</li>
										<li role="presentation"><a role="menuitem" tabindex="-1" href="<?=SHIKSHA_HOME?>/trackingMIS/saMIS/metric/overview/abroad">Study Abroad</a>
										</li>

                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=SHIKSHA_HOME?>/trackingMIS/saSalesDashboard/">SA Sales</a>
                                        </li>
										<!--  <li role="presentation"><a role="menuitem" tabindex="-1" href="--><?//=SHIKSHA_HOME?><!--/trackingMIS/ldb/dashboard">LDB</a>-->
										<!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=SHIKSHA_HOME?>/trackingMIS/cdMIS/customerDeliveryDashBoard/DomesticOverview">Customer Delivery</a>
										</li>
										<li role="presentation"><a role="menuitem" tabindex="-1" href="<?=SHIKSHA_HOME?>/trackingMIS/cdMIS/contentDeliveryDashBoard/contentDomesticOverview">Content Delivery</a>
										</li> -->
										<li role="presentation"><a role="menuitem" tabindex="-1" href="<?=SHIKSHA_HOME?>/trackingMIS/AppDashboard/showReport">Mobile App</a>
										</li>
									</ul>
                                </li>
							<!--Drop menu list for Team Name Ends Here -->
							<?php } ?>
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
                                window.location =  "<?=SHIKSHA_HOME?>/trackinglogin.html";
                            }else{
                                window.location = "<?=SHIKSHA_HOME?>";
                            }
                        }
                    });
                }
            </script>
