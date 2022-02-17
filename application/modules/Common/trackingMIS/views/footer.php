            </div>
        </div>
        <div class='document_hide cropper-hidden'>
            <button class="close" data-dismiss="modal" type="button">×</button>
            <div id="helpHeading"></div>
            <div id="helpText"></div>
        </div>
    </div>

    <?php if($misSource == 'studyAbroad' && $metric != "registration" && $metric != "RMC" && $metric != "traffic"  && $metric != "engagement" && $metric != "response"){?>
        <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/fonts/css/font-awesome.min.css" rel="stylesheet">
        <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("animate",'nationalMIS'); ?>" rel="stylesheet">
        <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/icheck/flat/green.css" rel="stylesheet" />
        <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("floatexamples",'nationalMIS'); ?>" rel="stylesheet" type="text/css" />
        <!-- chart js -->
        <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/chartjs/chart.min.js"></script>
        <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("saMIS","nationalMIS"); ?>"></script>
    <?php } ?>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("bootstrap.min","nationalMIS"); ?>"></script>

    
    
    <!-- bootstrap progress js -->
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/progressbar/bootstrap-progressbar.min.js"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/icheck/icheck.min.js"></script>
    <!-- daterangepicker -->
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("moment.min","nationalMIS"); ?>"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/datepicker/daterangepicker.js"></script>

    <!-- flot js -->
    <!--[if lte IE 8]><script type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
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
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("customMIS","nationalMIS"); ?>"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("jquery.dataTables","nationalMIS"); ?>"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("dataTables.tableTools","nationalMIS"); ?>"></script>
    <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">


    <?php if(isset($colorCodes) && count($colorCodes) > 0 ) { ?>
        <script type="text/javascript">
        if(typeof Common != 'undefined'){
            Common.colors = [
                <?php foreach ($colorCodes as $colorIndex => $colorValue) {
                    echo '"'.$colorValue.'", ';
                }
                ?>
            ];}
        </script>
    <?php } ?>

    <?php if( isset ($resultsToShow) ) { ?>

        <script>
            $(document).ready(function () {
                var data1 = [<?php
                $dateParts = '';
                $oneIndex = '';
                $oneValue = '';

                foreach ($resultsForGraph as $oneIndex => $oneValue) {
                    if($oneValue->ResponseDate == ''){
                        continue;
                    }
                    $dateParts = explode("-", $oneValue->ResponseDate);
                    $year = $dateParts[0];
                    $month = $dateParts[1];
                    $dayOfMonth = $dateParts[2];
                    echo "[Overview.gd('$oneValue->ResponseDate'), ($oneValue->ResponseCount)], ";
                }
                ?>];
                Common.datePeriod = "<?php echo $period; ?>";

                <?php if(isset($comparisonResultsForGraph)){
                $dateParts = '';
                $oneIndex = '';
                $oneValue = '';
                $period = "day";
                ?>
                var data2 = [<?php
                foreach ($comparisonResultsForGraph as $oneIndex => $oneValue) {
                    $dateParts = explode("-", $oneValue->ResponseDate);
                    $year = $dateParts[0];
                    $month = $dateParts[1];
                    $dayOfMonth = $dateParts[2];
                    echo "[Overview.gd($year, $month, $dayOfMonth), ($oneValue->ResponseCount)], ";
                }
                ?>];
                Common.lineChart(data1, data2);
                <?php } else { ?>
                Common.lineChart(data1);
                <?php } ?>
            });
        </script>
    <?php }?>

    <!-- worldmap -->
    

    <?php if(isset($splitData)) { ?>
    <script>

        var canvasElements = $('canvas.canvas1');

        $.each(canvasElements, function (canvasIndex, canvasValue) {
            var currentElement = this;
            var currentElementObject = $(this);
            var tileInfo = currentElementObject.parent('td').next('td').find('div').children('.tile_info');
            var nonEmptyTD = tileInfo.find('td.nonempty');


            var doughnutData = {};
            $.each(nonEmptyTD, function(nonEmptyKey, nonEmptyValue){
                doughnutData[nonEmptyKey] = {
                    value: parseFloat(Common.StringOperations.removeCommas($(nonEmptyValue).html())),
                    color: Common.colors[nonEmptyKey]
                }
            });

            var myDoughnut = new Chart(currentElement.getContext("2d")).Doughnut(doughnutData);
        });
    </script>
    <?php
    }
    if( count($dates) > 0 ) {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            var startDate = moment('<?php echo date('m/d/Y', strtotime($dates['startDate'])); ?>');
            var endDate = moment('<?php echo date('m/d/Y', strtotime($dates['endDate']));?>');
            Common.dateDropdown(startDate, endDate);

            <?php
            $actionNames = array(
            'response',
            'registration',
            'traffic',
            'engagement',
            'RMC'
            );

        if(in_array(strtolower($actionName), $actionNames)) {
            if($actionName == 'engagement' || $actionName == 'traffic'){ // Provide names to be shown on the linegraph ?>Common.tileNames = <?php echo json_encode($tileNames) ?>;<?php } ?>
            var targetBaseUrl            = '<?php echo SHIKSHA_HOME."/trackingMIS" ?>';
            var bootstrapDropdownHandler = new BootstrapDropdownHandler(targetBaseUrl);
            var actionName = '<?php echo $actionName; ?>';
            bootstrapDropdownHandler.generateAjaxInput(actionName);

            var urlInput = {
                'dim'  : '<?php echo $metricName; ?>',
                'pivot': '<?php echo $pivotName; ?>',
            };

            var viewType = $("input[name='view']");
            if (viewType.length < 1) {
                var input = document.createElement('input');
                input.setAttribute('name', 'view');
                input.setAttribute('type', 'hidden');
                input.setAttribute('value', '<?php echo $view; ?>');
                $('#showView').append(input);
            }

            bootstrapDropdownHandler.submitAjaxInput(urlInput);

            $('#submit').on('click', function () {
                bootstrapDropdownHandler.submitAjaxInput(urlInput);
                bootstrapDropdownHandler.update = 'all';
            });
            <?php } ?>
        });
    </script>
    <?php } ?>

            <script>
            misSource = "<?php echo $misSource;?>";
            if(misSource == 'nationalListing'){

            } else {
                    $(document).ready(function() {
                        if(misSource == "CD" || misSource == "Content-Delivery")
                        {
                            misAjaxUrl = "<?php echo $ajaxUrl;?>";
                            pageIdentifier = '<?php echo $page?>';
                            preparePageDataCD(misAjaxUrl);
                        }
                        else if(misSource == 'studyAbroad')
                        {
                            ajaxUrl = "<?php echo $ajaxUrl;?>";
                            metric = "<?php echo $metric; ?>";
                            pageName = "<?php echo $pageName; ?>";

                            if ((metric != 'RESPONSE') &&(metric != 'RMC')) {
                                $('#submit').on('click', function () {
                                    isChange        = 1;
                                    var compareDate = $("input[name=daterangepicker_start1]").val();
                                    startDate       = moment($("input[name=daterangepicker_start]").val()).format('YYYY-MM-DD');
                                    endDate         = moment($("input[name=daterangepicker_end]").val()).format('YYYY-MM-DD');
                                    var temp1       = endDate;
                                    var temp2       = moment().format('YYYY-MM-DD');
                                    if ((moment(temp1).diff(moment(temp2))) == 0) {
                                        endDate = moment(endDate).subtract(1, 'days').format('YYYY-MM-DD');
                                    }
                                    if (misSource == "studyAbroad") {
                                        if ($('#compareTo').is(':checked')) {
                                            if (compareDate) {
                                                startDateOne = moment($("input[name=daterangepicker_start1]").val()).format('YYYY-MM-DD');
                                                endDateOne   = moment($("input[name=daterangepicker_end1]").val()).format('YYYY-MM-DD');
                                                var temp1    = endDateToCompare;
                                                var temp2    = moment().format('YYYY-MM-DD');
                                                if ((moment(temp1).diff(moment(temp2))) == 0) {
                                                    endDateToCompare = moment(endDate).subtract(1, 'days').format('YYYY-MM-DD');
                                                }
                                                dateDiff    = (moment(endDate).diff(moment(startDate), 'days') + 1);
                                                dateDiffOne = (moment(endDateOne).diff(moment(startDateOne), 'days') + 1);

                                                //BootstrapDropdownHandlerForComparesion.getDateArrayForComparision();
                                                //BootstrapDropdownHandlerForComparesion.submitInput(ajaxUrlForCompare,startDate,endDate,startDateOne,endDateOne);
                                                getDailyData();
                                            }
                                        }
                                        else {
                                            //bootstrapDropdownHandler.submitInput(ajaxUrl,startDate,endDate);
                                            dateDiff    = (moment(endDate).diff(moment(startDate), 'days') + 1);
                                            dateDiffOne = -1;
                                            //getDateArray(startDate);
                                            getDailyData();
                                        }
                                    }

                                    $('.first').hide();
                                    $('.second').hide();
                                });


                                if (metric == 'TRAFFIC') {
                                    var trafficAspects = $('.tile_count');
                                    trafficAspects.children('div').each(function () {
                                        $(this).on('click', function () {
                                            var clickedElement = $(this);
                                            var aspect = clickedElement.attr('data-text');
                                            if (aspect != '% New Sessions') {
                                                trafficAspect = aspect;
                                                isChange      = 1;
                                                getDailyData();
                                                trafficAspects.find('div.animated').not('#topHeading_4').removeClass('defaultColor').addClass('bgColor');
                                                clickedElement.removeClass('bgColor').addClass('defaultColor');
                                            }
                                        });
                                    });
                                } else if (metric == 'ENGAGEMENT') {
                                    subMetric          = 'pageviews';
                                    var trafficAspects = $('.tile_count');
                                    trafficAspects.children('div').each(function () {
                                        $(this).on('click', function () {
                                            var clickedElement = $(this);
                                            var aspect         = clickedElement.attr('data-text');
                                            switch (aspect) {
                                                case 'Pages/Session':
                                                    subMetric = 'pgpersess';
                                                    break;

                                                case 'Avg Session (mm:ss)':
                                                    subMetric = 'avgsessdur';
                                                    break;

                                                case 'Bounce Rate (%)':
                                                    subMetric = 'bounce';
                                                    break;

                                                case 'Page Views':
                                                    subMetric = 'pageviews';
                                                    break;

                                                case 'Exit Rate (%)':
                                                    subMetric = 'exit';
                                                    break;
                                            }
                                            trafficAspect = aspect;
                                            isChange      = 1;
                                            getDailyData();
                                            trafficAspects.find('div.animated').removeClass('defaultColor').addClass('bgColor');
                                            clickedElement.removeClass('bgColor').addClass('defaultColor');
                                        });
                                    });
                                }
                                filterArray = <?php echo json_encode($topFilter); ?>;


                                <?php  if($metric == 'TRAFFIC' && $pageName == 'categoryPage'){  ?>
                                //$('#example-getting-started').multiselect();
                                //$('#example-getting-started').next('div').find('button').find('span').text('Choose Country');
                                /*$('.multiselect-container').addClass('overflow_autoSA');
                                $('.multiselect-container').addClass('multiselectCountry');*/
                                //$('#example-buttonWidth-overflow').multiselect({
                                    //buttonWidth: '165px'
                                //});
                                //multi();
                                <?php } ?>
                                preparePageData(ajaxUrl);
                            }
                            else {
                                bootstrapDropdownHandler = new BootstrapDropdownHandlerSA();
                                bootstrapDropdownHandler.generateInput()
                                Overview.pageName = pageName;
                                Overview.responseType = '<?php echo $responseType; ?>';
                                Overview.preparePageDataForOverview('abroad',metric); // this is only used for abroad as such
                            }
                        }else if(misSource == 'global' || misSource == 'national'){
                            var source;
                            if(misSource == 'global'){
                                source = '<?php echo $source;?>';
                                if(source!='abroad'){
                                    source = '';
                                }
                                
                            }else{
                                source = misSource
                            }
                            Overview.preparePageDataForOverview(source,'<?php echo $metric; ?>');
                        }else if(misSource =='shikshaMIS'){
                            <?php if($validRequest === false){ ?>
                                alert("Invalid Url");
                                return;
                            <?php } ?>
                            shikshaMIS.ajaxDestinationURL = '<?php echo $ajaxDestinationURL;?>';
                            shikshaMIS.misSource = '<?php echo $source;?>';
                            shikshaMIS.metric = '<?php echo $metric;?>';
                            shikshaMIS.topFilters = <?php echo json_encode($topFilters);?>;
                            shikshaMIS.diffCharts = <?php echo json_encode($diffChartFilter);?>;
                            shikshaMIS.pageName = '<?php echo $pageName?>';
                            shikshaMIS.colors = [
                                <?php foreach ($colorCodes as $colorIndex => $colorValue) {
                                    echo '"'.$colorValue.'", ';
                                }
                                ?>
                            ];

                            if(shikshaMIS.metric == 'traffic') {
                                shikshaMIS.trafficAspect          = 'Users';
                                var trafficAspects = $('.tile_count');
                                trafficAspects.children('div').each(function () {
                                    $(this).on('click', function () {
                                        var clickedElement = $(this);
                                        var aspect = clickedElement.attr('data-text');
                                        if (aspect != 'perNewSessions') {
                                            shikshaMIS.trafficAspect = aspect;
                                            shikshaMIS.isChange      = 1;
                                            shikshaMIS.getDailyData();
                                            trafficAspects.find('div.animated').not('#topHeading_4').removeClass('defaultColor').addClass('bgColor');
                                            clickedElement.removeClass('bgColor').addClass('defaultColor');
                                        }
                                    });
                                });
                            }else if(shikshaMIS.metric == 'engagement') {
                                    shikshaMIS.trafficAspect          = 'pageviews';
                                    var trafficAspects = $('.tile_count');
                                    trafficAspects.children('div').each(function () {
                                        $(this).on('click', function () {
                                            var clickedElement = $(this);
                                            var aspect         = clickedElement.attr('data-text');
                                            switch (aspect) {
                                                case 'Pages/Session':
                                                    shikshaMIS.trafficAspect = 'pgpersess';
                                                    break;

                                                case 'Avg Session (mm:ss)':
                                                    shikshaMIS.trafficAspect = 'avgsessdur';
                                                    break;

                                                case 'Bounce Rate (%)':
                                                    shikshaMIS.trafficAspect = 'bounce';
                                                    break;

                                                case 'Page Views':
                                                    shikshaMIS.trafficAspect = 'pageviews';
                                                    break;

                                                case 'Exit Rate (%)':
                                                    shikshaMIS.trafficAspect = 'exit';
                                                    break;
                                            }
                                            //shikshaMIS.trafficAspect = aspect;
                                            if (aspect != 'totalSessions') {
                                                isChange      = 1;
                                                shikshaMIS.getDailyData();
                                                trafficAspects.find('div.animated').removeClass('defaultColor').addClass('bgColor');
                                                clickedElement.removeClass('bgColor').addClass('defaultColor');
                                            }
                                        });
                                    });
                            }

                            shikshaMIS.deliveryMethodMapping = <?php echo json_encode($deliveryMethodMapping);?>;
                            shikshaMIS.prepareDataForMIS();
                        }else if(misSource =='assistantMIS'){
                            <?php if($validRequest === false){ ?>
                                alert("Invalid Url");
                                return;
                            <?php } ?>
                            sassistantMIS.ajaxDestinationURL = '<?php echo $ajaxDestinationURL;?>';
                            sassistantMIS.ajaxDestinationURL = '/trackingMIS/Dashboard/diffChartDataForAssistantMIS/';
                            
                            sassistantMIS.misSource = '<?php echo $source;?>';
                            sassistantMIS.metric = '<?php echo $metric;?>';
                            sassistantMIS.topFilters = <?php echo json_encode($topFilters);?>;
                            sassistantMIS.diffCharts = <?php echo json_encode($diffChartFilter);?>;
                            //sassistantMIS.pageName = '<?php echo $pageName?>';
                            sassistantMIS.colors = [
                                <?php foreach ($colorCodes as $colorIndex => $colorValue) {
                                    echo '"'.$colorValue.'", ';
                                }
                                ?>
                            ];
                            sassistantMIS.deliveryMethodMapping = <?php echo json_encode($deliveryMethodMapping);?>;
                            sassistantMIS.prepareDataForAssistantMIS();
                        }
                    });
                }
            </script>
</body>
</html>
