

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lead Tracking System</title>

    <!-- Bootstrap Core CSS -->
    <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("bootstrap"); ?>" type="text/css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("leadTracking"); ?>" type="text/css" rel="stylesheet" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        Menu
                    </a>
                </li>
                <li class ='menuBox'>
                    <a onclick ="displayLeadDetails()" href="javascript:void(0)">Lead Details</a>
                </li>	
                <li class ='menuBox'>
                    <a onclick ="displayAllocatedGenieForm()" href="javascript:void(0)">Lead Allocation Details</a>
                </li>
                <li class ='menuBox'>
                    <a onclick ="displayMissingGenieForm()" href="javascript:void(0)">See Why Search Agent Got Missed</a>
                </li>
                <li class ='menuBox'>
                    <a onclick ="displaySearchAgentDetails()" href="javascript:void(0)">Search Agent Details</a>
                </li>
                <li class ='menuBox'>
                    <a onclick ="displayAllocatedGenie()" href="javascript:void(0)">Recent Allocated leads to client</a>
                </li>
                <li>
                    <a onclick ="getSALeadMatchingLog()" href="javascript:void(0)">SALeadMatchingLog Entries</a>
                </li>
                <li>
                    <a href="#">About Developer</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Lead Allocation Management</h1>
                        <p><i>.</i></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

        <div id='pageDisplayContent'>
        </div>	

    </div>
    <!-- /#wrapper -->

 <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
    <!-- Bootstrap Core JavaScript -->
    <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('bootstrap_min'); ?>"></script>
    <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('leadTracking'); ?>"></script>

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

</body>

</html>
