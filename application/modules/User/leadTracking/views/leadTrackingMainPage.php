
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
                    <a onclick ="displayMissingGenieForm()" href="javascript:void(0)">Why Search Agent Got Missed</a>
                </li>
                
                <li class ='menuBox'>
                    <a onclick ="displayAllocationToGenieForm()" href="javascript:void(0)">Allocation to Search Agent</a>
                </li>

                <li onclick="alert('We are the Bhaii..ss!!')">
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

                   <!--  <iframe src="http://127.0.0.1:5601/app/kibana#/dashboard/3bf46610-7104-11e7-a31b-fd8dd8e34bf6?embed=true&_g=(refreshInterval%3A(display%3AOff%2Cpause%3A!f%2Cvalue%3A0)%2Ctime%3A(from%3Anow-5y%2Cmode%3Aquick%2Cto%3Anow))" height="600" width="800"></iframe> -->


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

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="succesModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body succesModel">
        Data has been saved successfully
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"  data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="errorModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body errorMsgBody">
        Something went wrong
      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>

</html>

<script type="text/javascript">
    displayMissingGenieForm();

</script>>