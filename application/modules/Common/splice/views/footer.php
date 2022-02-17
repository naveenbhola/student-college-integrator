            </div>
        </div>        
    </div>

        <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/fonts/css/font-awesome.min.css" rel="stylesheet">
        <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("animate",'nationalMIS'); ?>" rel="stylesheet">
        <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/icheck/flat/green.css" rel="stylesheet" />
        <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("floatexamples",'nationalMIS'); ?>" rel="stylesheet" type="text/css" />
        <!-- chart js -->

    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("bootstrap.min","nationalMIS"); ?>"></script>

    <!-- bootstrap progress js -->
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/progressbar/bootstrap-progressbar.min.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/icheck/icheck.min.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("moment.min","nationalMIS"); ?>"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("customMIS","nationalMIS"); ?>"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("salesOps","nationalMIS"); ?>"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/dateRangePicker/daterangepicker.js"></script>
    <script type="text/javascript">
            $(document).ready(function(){
                formValidation = new salesOpsformValidation;
            });
            </script>
    <?php

        if($source == 'addNewRequest'){ ?>
    <?php    }else if($source == 'dashboard'){ ?>
            <script type="text/javascript">
            $(document).ready(function(){
                salesOpsDashboard = new salesOpsInterfaceDashboard;
                salesOpsDashboard.teamGroupIds = <?php echo json_encode($teamGroupIds); ?>;
                salesOpsDashboard.getDashboardData(<?php  echo $userDataArray['groupId'];  ?>,<?php  echo $userDataArray['userid'];  ?>);
            });
            </script>

    <?php }else if($source == "viewRequest"){ 
                       ?>                        
            <script type="text/javascript">                
                $(document).ready(function(){
                    $('#advancedFilter_taskCategory').multiselect();                                
                    $('.multiselect-container').addClass('advancedFilter_multiSelectTaskCategory');
                    $(".multiselect").children('span').html('All Task Category');
                    $('#advancedFilter_taskCategory').next('div').children('button').next('ul').addClass('overflow_auto');

                    $('#advancedFilter_branch').multiselect();
                    $('#advancedFilter_branch').next('div').children('button').children('span').html('All Branch');
                    $('#advancedFilter_branch').next('div').children('button').next('ul').addClass('overflow_auto');
                    $('#advancedFilter_branch').next('div').children('button').next('ul').addClass('advancedFilter_multiSelectBranch');

                    $('#advancedFilter_course').multiselect();
                    $('#advancedFilter_course').next('div').children('button').children('span').html('Select Course');
                    $('#advancedFilter_course').next('div').children('button').next('ul').addClass('overflow_auto');
                    $('#advancedFilter_course').next('div').children('button').next('ul').addClass('advancedFilter_multiSelectCourse');

                    $('#advancedFilter_currentStatus').multiselect();
                    $('#advancedFilter_currentStatus').next('div').children('button').children('span').html('All Status');
                    $('#advancedFilter_currentStatus').next('div').children('button').next('ul').addClass('overflow_auto');
                    $('#advancedFilter_currentStatus').next('div').children('button').next('ul').addClass('advancedFilter_multiSelectCourse');
                    /*$('.multiselect-container').addClass('advancedFilter_multiSelectBranch');
                    $(".multiselect").children('span').html('All Branch');*/

                    viewRequest = new salesOpsViewRequest;
                    viewRequest.multiSelectTaskCategory();
                    viewRequest.multiSelectBranch();
                    viewRequest.multiSelectStatus();
                    //viewRequest.multiSelectCourse();
                    $('#advancedFilter_fromDatePicker').daterangepicker({
                      //minDate: moment(),
                      singleDatePicker: true,
                      calender_style: "picker_1"
                    }, function(start, end, label) {
                        $('#advancedFilter_fromDatePicker').val(end.format('MMMM D, YYYY'));
                    });
                    $('#advancedFilter_fromDatePicker').val(moment().format('MMMM D, YYYY'));
                   $('#advancedFilter_toDatePicker').daterangepicker({
                      //minDate: moment(),
                      singleDatePicker: true,
                      calender_style: "picker_1"
                    }, function(start, end, label) {
                        $('#advancedFilter_toDatePicker').val(end.format('MMMM D, YYYY'));
                    });
                   $('#advancedFilter_toDatePicker').val(moment().format('MMMM D, YYYY'));
        
                    prepareGraphToDisplay = new prepareDiffGraphToDisplay;

                    viewRequest.userIds = <?php  echo json_encode($dataTable['userIds']);?>;
                    viewRequest.requestIds = '<?php  echo json_encode($dataTable['requestIds']);?>';
                    viewRequest.userType = <?php  echo json_encode($userType);?>;
                    viewRequest.team = <?php  echo json_encode($team);?>;
                    viewRequest.groupId  = <?php echo $userDataArray['groupId']; ?>;
                    viewRequest.teamGroupIds  = <?php echo json_encode($teamGroupIds); ?>;
                    viewRequest.isCheckForUserIds = "<?php echo $isCheckForUserIds; ?>";
                    viewRequest.filter = "<?php echo $filter; ?>";
                    <!-- Calling js function -->
                    viewRequest.prepareDataForUserRequests("<?php echo $requestTypeForViewDetails; ?>");
                });
            </script>
    <?php    }else if($source == 'requestTaskDetails'){ ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    taskStatus = new requestTaskStatus;
                    taskStatus.userId = <?php echo $userDataArray['userid']; ?>;
                    taskStatus.currentStatus = "<?php echo $requestTaskDetails['status']; ?>";
                    taskStatus.team = "<?php echo $team; ?>";
                    taskStatus.groupId = <?php echo $userDataArray['groupId'];?>;
                    taskStatus.requestId = <?php echo $requestTaskDetails['requestId'];?>;
                    taskStatus.requestTaskId = <?php echo $requestTaskDetails['taskId'];?>;
                    taskStatus.currentAssignee = <?php echo $requestTaskDetails['taskAssignedTo'];?>;
                    taskStatus.lastUpdatedOn = <?php echo json_encode($requestTaskDetails['lastUpdatedOnDate']);?>;
                    taskStatus.taskCategory = <?php echo json_encode($requestTaskDetails['taskCategory']);?>;
                    taskStatus.TATDate = <?php echo json_encode(date('Y-m-d',strtotime($requestTaskDetails['TATDate'])));?>;
                    taskStatus.site = "<?php echo $requestTaskDetails['site']; ?>";
                    taskStatus.teamGroupIds  = <?php echo json_encode($teamGroupIds); ?>;
                    taskStatus.requestTaskTitle  = <?php echo json_encode($requestTaskDetails['requestTaskTitle']); ?>;
                    taskStatus.taskCategory  = <?php echo json_encode($requestTaskDetails['taskCategory']); ?>;
                });
            </script>
    <?php    } ?>
    <script type="text/javascript">
            $(document).ready(function() {
                bootstrapDropdownHandler = new BootstrapDropdownHandlerSalesOps();
                bootstrapDropdownHandler.generateInput()
                shikshaSales = new shikshaSalesOps();
                shikshaSales.groupId = <?php echo $userDataArray['groupId']?>;
                shikshaSales.userId = <?php  echo $userDataArray['userid'] ?>;
                shikshaSales.supervisorId = <?php  echo $userDataArray['userid'] ?>;
                shikshaSales.userGroupIds = [1,4,7,10,12];
                shikshaSales.leadGroupIds = [2,5,8,11,13];
                shikshaSales.managerGroupIds = [3,6,9,14];
                <?php
                    if($source == 'addNewRequest'){ ?>
                        shikshaSales.requestData = <?php echo json_encode($requestData) ?>;
                <?php    }
                ?>
            });
    </script>
</body>
</html>
